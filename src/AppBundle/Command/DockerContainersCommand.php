<?php

namespace AppBundle\Command;

use Docker\Container;
use Docker\Docker;
use Docker\Http\DockerClient;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DockerContainersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('docker:containers')
            ->setDescription('Build docker images')
            ->addArgument(
                'container_command',
                InputArgument::REQUIRED,
                'What to do with containers (start|stop)?'
            )
            ->addOption(
                'purge',
                null,
                InputOption::VALUE_NONE,
                'If set, images and containers will removed and built again.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $stdout)
    {
        $command = $input->getArgument('container_command');
        $purge = $input->getOption('purge');

        $client = new DockerClient(array(), 'unix:///var/run/docker.sock');
        $docker = new Docker($client);

        $containerManager = $docker->getContainerManager();
        $imageManager = $docker->getImageManager();

        $containers = [
            'percona-symblog' => [
                'container' => [
                    'Image' => 'percona-symblog',
                    'Env' => [
                        'MYSQL_ROOT_PASSWORD=12345'
                    ],
                ],
                'image' => <<<DOCKFILE
FROM percona:latest
RUN apt-get update
RUN apt-get install -y vim
DOCKFILE
            ],
            'php53-symblog' => [
                'container' => [
                    'Image' => 'php53-symblog',
                    'ExposedPorts' => ['80/tcp' => []],
                    'HostConfig' => [
                        'PortBindings' => [
                            '80/tcp' => [
                                ['HostPort' => '8080'],
                            ],
                        ],
                        'Links' => [
                            'percona-symblog:mysql'
                        ],
                        'Binds' => [
                            '/var/www:/var/www',
                            '/etc/passwd:/etc/passwd',
                            '/etc/group:/etc/group',
                        ]
                    ]
                ],
                'image' => <<<DOCKFILE
FROM markfletcher/php5.3-zend

RUN apt-get update
RUN apt-get install -y vim
RUN apt-get install -y mysql-client php5-mysqlnd

EXPOSE 80
CMD ["apache2", "-DFOREGROUND"]
DOCKFILE
            ]
        ];

        foreach ($containers as $containerName => $containerParams) {
            $imageManager->build($containerParams['image'], null, ['t' => $containerName, 'nocache' => $purge]);
            try {
                $container = $containerManager->find($containerName);
            } catch (RequestException $e) {
                $container = null;
            }

            if (($container instanceof Container && $purge) ||
                (!$container instanceof Container)
            ) {
                if ($container instanceof Container && $purge) {
                    $containerManager->remove($container, false, true);
                }

                $container = new Container($containerParams['container']);
                $container->setName($containerName);
                $containerManager->create($container);
            }

            $runtimeInfo = $container->getRuntimeInformations();

            if ($command === 'start' &&
                (!isset($runtimeInfo['State']['Running']) || !$runtimeInfo['State']['Running'])
            ) {
                $containerManager->start($container);
            } elseif ($command === 'stop' &&
                (isset($runtimeInfo['State']['Running']) && $runtimeInfo['State']['Running'])
            ) {
                $containerManager->stop($container);
            }
        }
    }
}
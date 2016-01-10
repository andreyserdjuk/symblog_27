<?php

namespace AppBundle\Command;

use Docker\Container;
use Docker\Context\ContextBuilder;
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

        try {
            $docker->getVersion();
        } catch (\Exception $e) {
            $stdout->writeln('Cannot access docker daemon: ' . $e->getMessage());
        }

        $containers = [
            'percona-symblog' => [
                'Image' => 'percona-symblog',
                'Env' => [
                    'MYSQL_ROOT_PASSWORD=12345'
                ],
            ],
            'php53-symblog' => [
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
            ]
        ];

        if ($purge) {
            try {
                $imageManager->removeImages(['percona-symblog', 'php53-symblog'], true);
            } catch (RequestException $e) {
            }
        }

        if ($command === 'start') {
            $contextBuilder = new ContextBuilder();
            $contextBuilder->from('percona:latest');
            $contextBuilder->run('apt-get update');
            $contextBuilder->run('apt-get install -y vim');
            $docker->build($contextBuilder->getContext(), 'percona-symblog');

            $contextBuilder = new ContextBuilder();
            $contextBuilder->from('markfletcher/php5.3-zend');
            $contextBuilder->run('apt-get update');
            $contextBuilder->run('apt-get install -y vim');
            $contextBuilder->run('apt-get install -y mysql-client php5-mysqlnd');
            $docker->build($contextBuilder->getContext(), 'php53-symblog');
        }

        foreach ($containers as $containerName => $containerParams) {
            try {
                $container = $containerManager->find($containerName);
            } catch (RequestException $e) {
                $container = null;
            }

            if ($container instanceof Container && $purge) {
                try {
                    $containerManager->remove($container, false, true);
                } catch (RequestException $e) {
                }
            }

            if ($command === 'start') {
                $container = new Container($containerParams);
                $container->setName($containerName);
                $containerManager->create($container);
                $containerManager->start($container);
            }

            if ($command === 'stop' && $container instanceof Container) {
                try {
                    $runtimeInfo = $container->getRuntimeInformations();
                    if (isset($runtimeInfo['State']['Running']) && $runtimeInfo['State']['Running']) {
                        $containerManager->stop($container);
                    }
                } catch (RequestException $e) {
                    $stdout->writeln('Error when trying to stop container "' . $containerName . '": ' . $e->getMessage());
                }
            }
        }
    }
}
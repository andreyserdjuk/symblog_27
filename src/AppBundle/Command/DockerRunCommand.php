<?php

namespace Symfony\Bundle\AsseticBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DockerRunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('docker:run')
            ->setDescription('Run docker containers from images')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $stdout)
    {
        $client = new \Docker\Http\DockerClient(array(), 'tcp://127.0.0.1');
        $docker = new \Docker\Docker($client);

        $container = new \Docker\Container([
            'Image' => 'percona:latest',
            'Env' => [
                'MYSQL_ROOT_PASSWORD=12345'
            ]
        ]);

        $docker
            ->getContainerManager()
            ->run($container, function($output, $type) {
                fputs($type === 1 ? STDOUT : STDERR, $output);
            });
    }
}

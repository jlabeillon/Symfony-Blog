<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
       // the name of the command (the part after "bin/console")
       ->setName('app:create-user')

       // the short description shown while running "php bin/console list"
       ->setDescription('Creates a new user.')

       // the full command description shown when running the command with
       // the "--help" option
       ->setHelp('This command allows you to create a user...')
       // configure an argument
        ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->writeln('create a user.');

        $slugger = $this->getContainer()->get('slugger');

        // retrieve the argument value using getArgument()
        $output->writeln('Username: '.$input->getArgument('username'));
        $output->writeln('Slug: '.$slugger->slugify($input->getArgument('username')));

        $output->writeln('');

    }
}

<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class PostsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
       // the name of the command (the part after "bin/console")
       ->setName('app:post:slugify')
       // the short description shown while running "php bin/console list"
       ->setDescription('Slugify all posts titles.')
       // the full command description shown when running the command with
       // the "--help" option
       ->setHelp('This command allows you to slugify posts titles...')
       ->addArgument('entity', InputArgument::OPTIONAL, 'Target Entity')
       ->addOption(
            'dump',
            null,
            InputOption::VALUE_NONE,
            'Display detail.',
            null
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Post Slugifier',
            '==============',
            '',
        ]);

        $output->writeln('Traitements des slugs.');

        // On accède au slugger
        $slugger = $this->getContainer()->get('slugger');
        // On accède à l'entityManager
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $entity = $input->getArgument('entity');

        // L'input a-t-elle été renseignée ?
        if(empty($entity)) {
            // Asking the User for Information
            // cf : https://symfony.com/doc/current/components/console/helpers/questionhelper.html
            $helper = $this->getHelper('question');
            $question = new Question('Please enter the name of the class to slugify? ');

            echo $entity = $helper->ask($input, $output, $question);
        }

        // La classe (Entité) existe-t-elle ?
        if(!class_exists('AppBundle\\Entity\\'.$entity)) {
            exit('Entity does not exist.');
        }
        // Récupération de nos posts depuis le repository
        $repo = $manager->getRepository('AppBundle:'.$entity);
        $objects = $repo->findAll();

        // On boucle sur nos posts
        foreach ($objects as $object) {
            // Choix du champ source
            if($entity == 'Post') {
                $source = $object->getTitle();
            }
            if($entity == 'Author') {
                $source = $object->getName();
            }
            // On transforme le title
            $slug = $slugger->slugify($source);
            // On enregistre le slug associé
            $object->setSlug($slug);
            // Affichage du traitement
            if($input->getOption('dump')) {
                $output->writeln('Traitement de '.$entity.' #'.$object->getId().' : '.$slug);
            }
        }
        // On sauve en bdd
        $manager->flush();
    }
}

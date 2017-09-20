<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\User;

use Faker; // Import de Faker

class FakeData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // On crée une instance de Faker en français
        $generator = Faker\Factory::create('fr_FR');
        // On passe le Manager de Doctrine à Faker !
        $populator = new Faker\ORM\Doctrine\Populator($generator, $manager);

        // Appel au service via le container
        $encoder = $this->container->get('security.password_encoder');

        // Users
        $users = ['admin', 'user'];
        foreach ($users as $name) {
            $user = new User;
            $user->setUsername($name);
            $user->setEmail($name.'@oclock.io');
            $user->setRole('ROLE_'.strtoupper($name));
            $encoded = $encoder->encodePassword($user, $name);
            $user->setPassword($encoded);
            $manager->persist($user);
            $manager->flush();
        }

        $populator->addEntity('AppBundle\Entity\Author', 5, array(
            'name' => function() use ($generator) { return $generator->name(); },
        ));
        $populator->addEntity('AppBundle\Entity\Post', 5, array(
            'imageUrl' => function() use ($generator) { return $generator->imageUrl($width = 640, $height = 480); },
        ));
        $populator->addEntity('AppBundle\Entity\Comment', 10);
        $populator->execute();
    }
}

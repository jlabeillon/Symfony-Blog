<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use AppBundle\Service\MessageGenerator;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, MessageGenerator $generator)
    {
        $message = $generator->getHappyMessage();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("encode")
     */
    public function encodeAction(/* UserPasswordEncoderInterface $encoder */)
    {
        $encoder = $this->container->get('security.password_encoder');

        $user = new \AppBundle\Entity\User;
        $plainPassword = 'admin';
        $encoded = $encoder->encodePassword($user, $plainPassword);

        return new Response('<html><body>'.$encoded.'</body></html>');
    }

}

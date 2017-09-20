<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;

use AppBundle\Service\Slugger;
use AppBundle\Service\FileUploader;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Post controller.
 *
 * @Route("post")
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     * @Route("/", name="post_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')->findAll();

        return $this->render('post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/new", name="post_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $post = new Post();
        $form = $this->createForm('AppBundle\Form\PostType', $post, [
            'upload_directory' => $this->getParameter('upload_directory'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Début traitement upload fichier
            $fileName = null;
            // On récupère le champ
            $file = $post->getPdf();
            // Si fichier envoyé
            if(!empty($file)) {
                $fileName = $fileUploader->upload($file);
            }
            // On stocke le nom du fichier dans le post
            $post->setPdf($fileName);

            // Début traitement upload fichier
            $fileName = null;
            // On récupère le champ
            $file = $post->getImage();
            // Si fichier envoyé
            if(!empty($file)) {
                $fileName = $fileUploader->upload($file);
            }
            // On stocke le nom du fichier dans le post
            $post->setImage($fileName);

            // Récupération du service slugger
            $slugger = $this->container->get('slugger');

            // On transforme le title
            $slug = $slugger->slugify($post->getTitle());
            // On enregistre le slug associé
            $post->setSlug($slug);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_show', array('slug' => $post->getSlug()));
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/{slug}", name="post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post, Slugger $slugger, FileUploader $fileUploader)
    {
        // On mémorise le nom du fichier présent
        $currentPdf = $post->getPdf();
        $currentImage = $post->getImage();

        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('AppBundle\Form\PostType', $post, [
            'upload_directory' => $this->getParameter('upload_directory'),
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // Début traitement upload fichier
            $fileName = $currentPdf;
            // On récupèe le champ
            $file = $post->getPdf();
            // Si fichier envoyé
            if(!empty($file)) {
                // On supprime l'ancien fichier lié
                $fileUploader->delete($currentPdf);
                // On définit un nom unique pour sauvegarder ce fichier
                // Afin que les fichiers ne s'écrasent pas les uns les autres
                $fileName = $fileUploader->upload($file);
            }
            // On stocke le nom du fichier dans le post
            $post->setPdf($fileName);

            // Début traitement upload fichier
            $fileName = $currentImage;
            // On récupèe le champ
            $file = $post->getImage();
            // Si fichier envoyé
            if(!empty($file)) {
                // On supprime l'ancien fichier lié
                $fileUploader->delete($currentImage);
                // On définit un nom unique pour sauvegarder ce fichier
                // Afin que les fichiers ne s'écrasent pas les uns les autres
                $fileName = $fileUploader->upload($file);
            }
            // On stocke le nom du fichier dans le post
            $post->setImage($fileName);

            // On transforme le title
            $slug = $slugger->slugify($post->getTitle());
            // On enregistre le slug associé
            $post->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @Route("/{id}", name="post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post, FileUploader $fileUploader)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        // On supprime les fichiers liés
        $fileUploader->delete($post->getPdf());
        $fileUploader->delete($post->getImage());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            // Flash message
            $this->addFlash('success', 'Post supprimé(e).');
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

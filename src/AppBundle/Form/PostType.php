<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title')
        ->add('summary')
        ->add('content')
        ->add('imageUrl')
        ->add('image', FileType::class, [
            'label' => 'Image',
        ])
        ->add('pdf', FileType::class, [
            'label' => 'Fichier joint (PDF)',
        ])
        ->add('isActive')
        ->add('createdAt')
        ->add('author');

        $callbackTransformer = new CallbackTransformer(
            // Used to render the field
            function ($fileName) use($options) {
                if(empty($fileName)) {
                    return null;
                }
                // transform the string to a File
                return new File($options['upload_directory'].'/'.$fileName);
            },
            // Used by your controller code (when you do $post->getPdf())
            // Quand le form est soumis
            function ($file) {
                // return the object file
                return $file;
            }
        );

        $builder->get('pdf')
            ->addModelTransformer($callbackTransformer);

        $builder->get('image')
            ->addModelTransformer($callbackTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post',
            'attr' => ['novalidate' => 'novalidate'],
            'upload_directory' => null,
        ));

        //$resolver->setDefined(array('upload_directory'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}

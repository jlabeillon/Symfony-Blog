<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username')
        ->add('content', null, [
            'label' => 'Contenu',
            'attr' => [
                'placeholder' => 'Veuillez mettre un contenu optimisé SEO',
                'help_text' => 'Ne pas dépasser 140 caractères',
            ]
        ])
        ->add('publishedAt')
        // Ajout d'un champ non mappé à l'entité Comment
        // (n'existe pas dans Comment)
        ->add('autorize', CheckboxType::class, [
            'label' => 'Je souhaite recevoir une copie de ce commentaire par mail.',
            'mapped' => false, // Utilisation de l'option 'mapped'
        ])
        ->add('post');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Comment',
            'attr' => ['novalidate' => 'novalidate'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_comment';
    }


}

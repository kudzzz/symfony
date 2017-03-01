<?php

namespace OC\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date')
            ->add('title', 'text')
            ->add('author','text')
            ->add('content','textarea')
            ->add('image', new ImageType())

            ->add('categories', 'entity', array(
                'class' => 'OCPlatformBundle:category',
                'property' => 'name',
                'multiple' => true

            ))
            ->add('save', 'submit');

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function(FormEvent $event){

                $advert = $event->getData();

                if ( null === $advert){
                    return;
                }
                if(!$advert->getPublished() || null === $advert->getId()){

                    $event->getForm()->add('published', 'checkbox', array('required' => false));

                }
                else{
                    $event->getForm()->remove('published');
                }
            });

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'oc_platformbundle_advert';
    }


}

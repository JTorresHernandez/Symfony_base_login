<?php

namespace AppBundle\Form;

use AppBundle\Entity\Producto;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nombre')
            ->add('Tipo')
            ->add('Descripcion')
            ->add('Guardar',SubmitType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Producto::class,
        ));
    }
    public function getBlockPrefix()
    {
        return 'app_bundle_producto_type';
    }
}
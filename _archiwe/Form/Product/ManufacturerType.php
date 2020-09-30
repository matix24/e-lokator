<?php

namespace App\Form\App\Product;

use App\Entity\Manufacturer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ManufacturerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manufacturer_name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź nazwę'
                ],
                'constraints' => [
                    new Length(['min'=>3, 'max'=>250])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary'
                ],
                'label'=>"Zapisz"
            ])
        ;
    }//end buildForm

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Manufacturer::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',            
        ]);
    }// end configureOptions

}// end class

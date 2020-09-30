<?php

namespace App\Form\App\Product;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CategoryType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('category_name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź nazwę'
                ],
                'constraints'=>[
                    new Length(['min'=>3,'max'=>99])
                ]
            ])
            ->add('category_disabled', CheckboxType::class, [
                'attr'=>[
                    'class'=>'form-check-input',
                    'checked'=>'checked'
                ],
                'label_attr'=>[
                    'class'=>'form-check-label'
                ],
                'label'=>'Widoczny?',
                'required'   => false
            ])
            ->add('category_order_by', IntegerType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Kolejność w widoku',
                    'min'=>0
                ],
                'label'=>'Kolejność w widoku',
                'required'   => false
            ])->add('submit', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary'
                ],
                'label'=>'Zapisz'
            ])
        ;
    }// end buildForm

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',                        
        ]);
    }// end configureOptions

}// end class

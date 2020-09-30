<?php

namespace App\Form;

use App\Entity\User;
use App\Security\AuthRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź imię'
                ],
                'constraints' => [
                    new Length(['min'=>3, 'max'=>90])
                ],
                'label' => 'Imię'
            ])
            ->add('surname', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź nazwisko'
                ],
                'constraints' => [
                    new Length(['min'=>3, 'max'=>90])
                ],                
                'label'=>'Nazwisko',
            ])   
            ->add('email', EmailType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź e-mail'
                ],
                'constraints' => [
                    new Length(['min'=>3, 'max'=>180])
                ],                
                'label'=>'E-mail',
            ])
            ->add('password', PasswordType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź hasło'
                ],
                'constraints' => [
                    new Length(['min'=>6, 'max'=>20])
                ],                
                'label'=>'Hasło',
            ])  
            ->add('roles', ChoiceType::class, [
                'attr'  =>  [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Administrator' => [
                        AuthRole::ROLE_ADMIN => AuthRole::ROLE_ADMIN
                    ],
                    'Klient' => [
                        AuthRole::ROLE_CUSTOMER => AuthRole::ROLE_CUSTOMER
                    ]
                ],
                'multiple' => true,
                'required' => true,
            ])                    
            ->add('is_verified', HiddenType::class, [
                'data'=>'0'
            ])                        
            ->add('is_disabled', HiddenType::class, [
                'data'=>'0'
            ])                                           
            ->add('submit', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary'
                ],
                'label'=>"Zapisz"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',             
        ]);
    }

}// end class

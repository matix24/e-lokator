<?php

namespace App\Form\App\Product;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Manufacturer;
use Symfony\Component\Form\FormEvent;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Repository\ManufacturerRepository;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\JsonValidator;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź nazwę produktu'
                ],
                'constraints' => [
                    new Length(['min'=>3, 'max'=>255])
                ],
                'label'=>'Nazwa produktu'
            ])
            ->add('product_description', TextareaType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź opis produktu'
                ],
                'constraints' => [
                    new Length(['min'=>3, 'max'=>65000])
                ],
                'label'=>'Opis produktu'
            ])  
            ->add('product_manufacturer_symbol', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź symbol produktu'
                ],
                'constraints' => [
                    new Length(['min'=>3, 'max'=>45])
                ],
                'label'=>'Symbol producenta dla produktu'
            ])   
            ->add('category', EntityType::class, [
                'class'=>Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.category_name', 'ASC');
                },
                'choice_label' => 'category_name',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Kategoria',
            ])
            ->add('manufacturer', EntityType::class, [
                'class'=>Manufacturer::class,
                'query_builder' => function (ManufacturerRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.manufacturer_name', 'ASC');
                },
                'choice_label' => 'manufacturer_name',                
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Dostawca'
            ])
            ->add('product_manufacturer_price', MoneyType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź cenę produktu'
                ],
                'label'=>'Cena dostawcy',
                'divisor' => 100,
                'currency'=>''
            ])       
            ->add('product_details', TextareaType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Wprowadź szczegóły produktu'
                ],
                'constraints' => [
                    new Length(['min'=>3, 'max'=>65000])
                ],
                'required'   => false,
                'label'=>'Szczegóły produktu'
            ])
            ->add('product_disabled', CheckboxType::class, [
                'attr'=>[
                    'class'=>'form-check-input',
                    'placeholder'=>'Produkt wyłączony?'
                ],
                'label_attr'=>[
                    'class'=>'form-check-label'
                ],                
                'label'=>'Produkt wyłączony?',
                'required'   => false
            ])                                                
            ->add('submit', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary'
                ],
                'label'=>"Zapisz"
            ])          
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
                // dump($event); die;
                // $dataForm = $event->getData();
                // $dataForm['product_details'] = json_decode($dataForm['product_details'], true);
                // $event->setData($dataForm);
                // dump($event); die;
                // dump($event->getData()); die;
            })
        ;

        $builder->get('product_details')
            ->addViewTransformer(new CallbackTransformer(
                function ($detailsAsArray) {
                    if(!is_array($detailsAsArray)){
                        return '[]';
                    }
                    return json_encode($detailsAsArray); // array to string
                },
                function ($detailsAsString) {
                    // return $detailsAsString;
                    if($detailsAsString === null){
                        $detailsAsString = [];
                    }
                    return json_decode($detailsAsString, true); // string to array
                }
            ))
        ;        

    }//end buildForm

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',            
        ]);
    }// end configureOptions

}// end class

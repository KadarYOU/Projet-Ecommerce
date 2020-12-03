<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Callback;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['placeholder' => 'Tapez le nom du produit']
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description du produit',
                'attr' => ['placeholder' => 'Tapez la description du produit']

            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit',
                'attr' => ['placeholder' => 'Tapez le prix du produit '],
                'divisor' => 100
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit',
                'attr'  => ['placeholder' => 'Tapez une Url d\'image !']
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categorie',
                'placeholder' => '--Choisir une catégorie--',
                'class' => Category::class,
                'choice_label' => 'name'
            ]);
        // $builder->get('price')->addModelTransformer(new CentimesTransformer);
        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     $product = $event->getData();
        //     if ($product->getPrice() !== null) {
        //         $product->setPrice($product->getPrice() * 100);
        //     }
        // });
        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();

        //     /**@var Product */
        //     $product = $event->getData();

        //     if ($product->getPrice()) {
        //         $product->setPrice($product->getPrice() / 100);
        //     }
        //     // if (!$product->getId()) {
        //     //     $form->add('category', EntityType::class, [
        //     //         'label' => 'Categorie',
        //     //         'placeholder' => '--Choisir une catégorie--',
        //     //         'class' => Category::class,
        //     //         'choice_label' => 'name'
        //     //     ]);
        //     // }
        // });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;

use Doctrine\ORM\EntityManager;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category", priority=-1)
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category =  $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            throw new NotFoundHttpException("La catégorie demandée n'existe pas");
        }
        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }
    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     * 
     */
    public function show($slug, ProductRepository $productRepository)
    {


        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);
        if (!$product) {
            throw $this->createNotFoundException("Le produit demandée n'existe pas");
        }
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);


        // $form = $builder->getForm();
        $form->handleRequest($request);
        // $data = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            // $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            // $product = new Product;
            // $product->setName($data['name'])
            //     ->setShortDescription($data['shortDescription'])
            //     ->setCategory($data['category']);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getslug(),
                'slug' => $product->getSlug()
            ]);
        }
        $formView = $form->createView();

        return  $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */
    public function edit($id,  ProductRepository $productRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        // $product = new Product;
        // $resultat = $validator->validate($product);
        // if ($resultat->count() > 0) {
        //     dd("il y a des erreurs ", $resultat);
        // }
        // dd("Tout va bien");

        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());


            $em->flush();
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getslug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();


        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView

        ]);
    }
}

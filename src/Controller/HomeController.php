<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class HomeController extends AbstractController
{
  /**
   * @Route("/", name="homepage")
   */
  public function homepage(EntityManagerInterface $manager, ProductRepository $repo)
  {

    $products = $repo->findby([], [], 3);

    // modifier un produit
    // $productRepository = $manager->getRepository(Product::class);
    // // // ajouter un produit
    // $product = $productRepository->find(5);
    // $manager->remove($product);
    // $manager->flush();
    // $product
    //   ->setName('Table en métal')
    //   ->setPrice(3000)
    //   ->setSlug('table-en-metal');
    // $manager->persist($product);
    // $manager->flush();
    // dd($product);

    return $this->render('home.html.twig', [
      'product' => $products
    ]);
  }
}

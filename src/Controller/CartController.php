<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var CartService
     */
    protected $cartService;
    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }
    /**
     * @Route("/cart", name= "cart_show")
     */
    public function show()
    {
        $detailedCart = $this->cartService->getDetailedCart();
        $total = $this->cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'Total' => $total
        ]);
    }
    /**
     * @Route("/cart/add/{id}", name="cart_add" , requirements={"id":"\d+"})
     */
    public function add($id, Request $request)
    {
        //0. Securisation : est-ce que le produit existe
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas");
        }
        $this->cartService->add($id);

        // supprimer le panier
        // $request->getSession()->remove('cart');
        // dd($session->get('cart'));
        // dd('test');

        $this->addFlash('success', "Le produit a bien été ajouté au panier");

        $this->addFlash('error', "error");

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute("cart_show");
        }
        // $flashBag->add('sucess', "Le produit a bien été ajouté au panier");
        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }
    /**
     * @Route("/cart/delete/{id}", name ="cart_delete", requirements={"id": "\d+"})
     */
    public function delete($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("le produit $id n' existe pas et ne peut pas etre supprimer");
        }
        $this->cartService->remove($id);
        $this->addFlash("success", "Le produit a bien été supprimer dans le panier");

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart/decrement/{id}", name ="cart_decrements", requirements={"id":"\d+"})
     */

    public function decrements($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut etre décrement !");
        }

        $this->cartService->decrement($id);
        $this->addFlash("success", "Le produit a bien été décrément");
        return $this->redirectToRoute("cart_show");
    }
}

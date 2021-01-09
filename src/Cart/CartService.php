<?php

namespace App\Cart;

use App\Repository\ProductRepository;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartService
{

  protected $session;
  protected $productRepository;

  public function __construct(SessionInterface $session, ProductRepository $productRepository)
  {
    $this->session = $session;
    $this->productRepository = $productRepository;
  }
  protected function getCart(): array
  {
    return $this->session->get('cart', []);
  }
  protected function saveCart(array $cart)
  {
    return $this->session->set('cart', $cart);
  }
  public function add($id)
  {


    // 1. Retrouver le panier dans la session (sous forme de tableau)
    //2. Si il n'existe pas encore, alors prendre un tableau vide

    $cart = $this->getCart();

    // 3. voir si le produit ($id) existe déjà dans le tableau
    // 4. si c'est le cas, simplement augmenter la quantité 
    // 5. sinon, ajouter le produit avec la quantité:
    if (!array_key_exists($id, $cart)) {
      $cart[$id] = 0;
    }
    $cart[$id]++;
    // 6. Enregistrer le tableau mis à jour dans la session 
    $this->saveCart($cart);
    /** @var FlashBag */
    $flashBag = $this->session->getBag('flashes');
  }
  public function remove(int $id)
  {
    $cart = $this->session->get('cart', []);
    unset($cart[$id]);
    $this->session->set('cart', $cart);
  }
  public function getTotal(): int
  {
    $total = 0;

    foreach ($this->session->get('cart', []) as $id => $qty) {
      $product = $this->productRepository->find($id);
      if (!$product) {
        continue;
      }
      $total += $product->getPrice() * $qty;
    }
    return $total;
  }

  public function decrement(int $id)
  {
    $cart = $this->session->get('cart', []);
    if (!array_key_exists($id, $cart)) {
      return;
    }
    // soit le produit est égale à 1, alors il faut supprimer
    if ($cart[$id] === 1) {
      $this->remove($id);
      return;
    }
    // soit le produit est à plus de 1, alors il faut decrement
    $cart[$id]--;
    $this->session->set('cart', $cart);
  }

  public function getDetailedCart(): array
  {
    $detailedCart = [];

    foreach ($this->session->get('cart', []) as $id => $qty) {
      $product = $this->productRepository->find($id);
      if (!$product) {
        continue;
      }
      $detailedCart[] = new CartItem($product, $qty);
    }
    return $detailedCart;
  }
}

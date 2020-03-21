<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository)
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        foreach($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        $total = 0;
        foreach($cartWithData as $item) {
            $totalItem = $item['product']->getProductPrice() * $item['quantity'];
            $total += $totalItem;
        }
        return $this->render('cart/index.html.twig', [
            'products' => $cartWithData,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_cart")
     */
    public function add($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/remove/{id}", name="remove_product_cart")
     */
    public function remove($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute("cart");
    }
}

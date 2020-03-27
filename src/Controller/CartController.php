<?php

namespace App\Controller;

use App\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(TranslatorInterface $translator)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $carts = $em->getRepository(Cart::class)->findAll();
            $size = count($carts);
            // Translation EN -> FR
            $local_cart_heading = $translator->trans('Shopping Cart');
            $local_table_product = $translator->trans('Product(s)');
            $local_table_quantity = $translator->trans('Quantity');
            $local_table_price = $translator->trans('Price');
            $local_cart_empty = $translator->trans('Cart is empty !');
            $local_btn_delete = $translator->trans('Remove');

            return $this->render('cart/index.html.twig', [
                'carts' => $carts,
                'size' => $size,
                'loc_cart_heading' => $local_cart_heading,
                'loc_table_product' => $local_table_product,
                'loc_table_quantity' => $local_table_quantity,
                'loc_table_price' => $local_table_price,
                'loc_cart_empty' => $local_cart_empty,
                'loc_btn_delete' => $local_btn_delete,
            ]);
        }catch (\Exception $e) {
            return new Response('Erreur : aucune bdd défini');
        }
    }

    /**
     * @Route ("/cart/delete/{id}", name="delete_cart")
     */
    public function delete(Cart $cart=null, TranslatorInterface $translator)
    {
        if($cart !=null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cart);
            $em->flush();
            $this->addFlash("success", $translator->trans('Success'));
        }
        return $this->redirectToRoute('cart');
    }
}

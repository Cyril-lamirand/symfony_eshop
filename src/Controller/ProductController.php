<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Cart;
use App\Form\ProductType;
use App\Form\CartType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(Request $request, TranslatorInterface $translator)
    {
        try {

            $em = $this->getDoctrine()->getManager();
            $product = new Product();
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $picture = $form->get('product_picture')->getData();
                if($picture){
                    $productPicture = uniqid().'.'.$picture->guessExtension();
                    try{
                        $picture->move(
                            $this->getParameter('upload_dir'),
                            $productPicture
                        );
                    }
                    catch(FileException $e){
                        $this->addFlash('error', "Impossible d'uploader l'image");
                        return $this->redirectToRoute('product');
                    }
                    $product->setProductPicture($productPicture);
                }
                $em->persist($product);
                $em->flush();
                $this->addFlash('success', 'Produit ajouté !');
            }
            $products = $em->getRepository(Product::class)->findAll();
            // Translation EN -> FR
            $local_prod = $translator->trans('Product(s)');
            $local_info = $translator->trans('More');
            $local_crea = $translator->trans('Create Product');
            $local_empt = $translator->trans('There is no products here !');

            return $this->render('product/index.html.twig', [
                'products' => $products,
                // Translation
                'loc_prod' => $local_prod,
                'loc_info' => $local_info,
                'loc_crea' => $local_crea,
                'loc_empt' => $local_empt,
                'form_add_product' => $form->createView()
            ]);

        } catch (\Exception $e) {
            return new Response('Erreur : aucune bdd défini');
        }
    }

    /**
     * @Route("/product/create", name="create_product")
     */
    public function productCreate(Request $request, TranslatorInterface $translator)
    {
        $em = $this->getDoctrine()->getManager();
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $picture = $form->get('product_picture')->getData();
            if($picture){
                $productPicture = uniqid().'.'.$picture->guessExtension();
                try{
                    $picture->move(
                        $this->getParameter('upload_dir'),
                        $productPicture
                    );
                }
                catch(FileException $e){
                    $this->addFlash('error', "Impossible d'uploader l'image");
                    return $this->redirectToRoute('product');
                }
                $product->setProductPicture($productPicture);
            }
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Produit ajouté !');

            return $this->redirectToRoute('product');
        }
        // Translation EN -> FR
        $local_newprod = $translator->trans('New product');

        return $this->render('product/newproduct.html.twig', [
            'form_add_product' => $form->createView(),
            'loc_newprod' => $local_newprod,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_info")
     */
    public function productInfo($id, Product $product=null, Request $request, TranslatorInterface $translator)
    {
        if($product != null){
            $em = $this->getDoctrine()->getManager();
            $cart = new Cart();
            $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($id);
            $form = $this->createForm(CartType::class, $cart);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $cart = $form->getData();
                $cart->setCartProduct($product);
                $cart->setCartDatecreate(new \DateTime());
                $cart->setCartState(0);
                $em->persist($cart);
                $em->flush();
                $this->addFlash("success", 'Product added !');
                return $this->redirectToRoute('cart');
            }
            // Translation EN -> FR
            $local_stock = $translator->trans('Stock Quantity');

            return $this->render('product/product.html.twig', [
                'product' => $product,
                'loc_stock' => $local_stock,
                'form_cart_product' => $form->createView(),
            ]);
        }else{
            $this->addFlash('error', 'Product not found');
            return $this->redirectToRoute('home');
        }
    }
}

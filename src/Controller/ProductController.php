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

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(Request $request)
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
        }

        $products = $em->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form_add_product' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/create", name="create_product")
     */
    public function productCreate(Request $request)
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
        }

        return $this->render('product/newproduct.html.twig', [
            'form_add_product' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_info")
     */
    public function productInfo(Product $product=null, Request $request)
    {
        if($product != null){
            $form = $this->createForm(CartType::class);
            return $this->render('product/product.html.twig', [
                'product' => $product,
                'form_cart_product' => $form->createView(),
                'title' => "Hello World !",
            ]);
        }else{
            $this->addFlash('error', 'Product not found');
            return $this->redirectToRoute('home');
        }
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
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
    public function createProduct(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setProductName('Mouse');
        $product->setProductPrice(50);
        $product->setProductQuantity(10);
        $product->setProductPicture('symfony_eshop/src/img/produit-2.jpg');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $form = $this->createForm(ProductType::class, $product);

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * @Route("/product/{id}", name="product_info")
     */
    public function productInfo(Product $product=null, Request $request)
    {
        if($product != null){
            return $this->render('product/product.html.twig', [
                'product' => $product,
                'title' => "Hello World !",
            ]);
        }else{
            $this->addFlash('error', 'Série introuvable');
            return $this->redirectToRoute('serie');
        }
    }
}

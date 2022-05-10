<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/', name: 'products')]
    public function index(ProductRepository $productRepository): Response
    {

        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            "products" => $products
        ]);
    }

    #[Route('/product/new', name: 'product_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render('product/create.html.twig', [
            'formProduct' => $form->createView()
        ]);
    }

    #[Route('/product/update/{id}', name: 'product_update')]
    public function update(Product $product,Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('products');
        }
        return $this->renderForm('product/update.html.twig', [

            'product' => $product,
            "formProduct" => $form,
        ]);
    }

    #[Route('/product/delete/{id}', name: 'product_delete')]
    public function delete(Product $product, EntityManagerInterface $em)
    {
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('products');

    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(Product $product)
    {
        return $this->render('product/show.html.twig', [
            "product" => $product,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'categories')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }

    #[Route('/category/new', name: 'category_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('categories');
        }

        return $this->render('category/create.html.twig', [
            'formCategory' => $form->createView()
        ]);
    }

    #[Route('/category/update/{id}', name: 'category_update')]
    public function update(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('categories');
        }
        return $this->renderForm('category/update.html.twig', [
            "category" => $category,
            "formCategory" => $form,
        ]);
    }

    #[Route('/category/delete/{id}', name: 'category_delete')]
    public function delete(Category $category,EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('categories');

    }

    #[Route('/category/{id}', name: 'category_show')]
    public function show(Category $category)
    {
        return $this->render('category/show.html.twig', [
            "category" => $category,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories")
 */
class CategoriesController extends AbstractController
{
    /**
    * @Route("/list", name="Categories_api", methods={"GET"})
    */
public function listCate(CategoriesRepository $Cate){
    return $this->json($Cate->findAll(),200,[],['groups'=>'post:read']);

}
    /**
     * @Route("/", name="categories_index", methods={"GET"})
     */
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categories_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('categories_index');
        }

        return $this->render('categories/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{codeCate}", name="categories_show", methods={"GET"})
     */
    public function show(Categories $category): Response
    {
        return $this->render('categories/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{codeCate}/edit", name="categories_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categories $category): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categories_index');
        }

        return $this->render('categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{codeCate}", name="categories_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categories $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getCodeCate(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categories_index');
    }
}

<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use App\Form\CategoryType;
use App\Entity\Category;


class CategoryController extends AbstractController
{
    /**
     * List all categories entities
     * 
     * @Route("/admin/category", name="admin.category.list", methods="GET")
     * 
     * @param EntityManagerInterface $em
     * 
     * @return Response
     */
    public function list(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/category/create", name="admin.category.create", methods={"GET","POST"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param FileUploader fileUploader
     * 
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($category); //Doctrine administra el objeto $obj
            $em->flush(); // Se lanzan las sentencias sql de los objetos administrados por document


            return $this->redirectToRoute('admin.category.list');
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing category entity
     *
     * @Route("/admin/category/{id}/edit", name="admin.category.edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Category $category
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin.category.list');
        }
        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }
    
    /**
     * Delete category.
     *
     * @Route("/admin/category/{id}/delete", name="admin.category.delete", methods="DELETE",
     * requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Category $category
     *
     * @return Response
     */
    public function delete(Request $request, EntityManagerInterface $em, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('admin.category.list');
    }
}

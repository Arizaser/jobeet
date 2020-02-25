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
use App\Form\JobType;
use App\Entity\Job;
use Knp\Component\Pager\PaginatorInterface;


class JobController extends AbstractController
{
    /**
     * List all jobs entities
     * 
     * @Route("/admin/job/{page}", name="admin.job.list", methods="GET", defaults={"page": 1}, requirements={"page" = "\d"})
     * 
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param int $page
     * 
     * @return Response
     */
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, int $page): Response
    {
        $jobs = $paginator->paginate($em->getRepository(Job::class)->findAll(), $page, 10);
        return $this->render('admin/job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/admin/job/create", name="admin.job.create", methods={"GET","POST"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param FileUploader fileUploader
     * 
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($job); //Doctrine administra el objeto $obj
            $em->flush(); // Se lanzan las sentencias sql de los objetos administrados por document


            return $this->redirectToRoute('admin.job.list');
        }

        return $this->render('admin/job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing job entity
     *
     * @Route("/admin/job/{id}/edit", name="admin.job.edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin.job.list');
        }
        return $this->render('admin/job/edit.html.twig', [
            'form' => $form->createView(),
            'job' => $job,
        ]);
    }
    
    /**
     * Delete job.
     *
     * @Route("/admin/job/{id}/delete", name="admin.job.delete", methods="DELETE",
     * requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Job $job
     *
     * @return Response
     */
    public function delete(Request $request, EntityManagerInterface $em, Job $job): Response
    {
        if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
            $em->remove($job);
            $em->flush();
        }
        return $this->redirectToRoute('admin.job.list');
    }
}

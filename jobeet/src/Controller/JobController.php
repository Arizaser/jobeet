<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Job;
use App\Repository\JobRepository;
use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use App\Form\JobType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormInterface;

class JobController extends AbstractController{

/**
     * Finds and displays a job entity.
     * 
     * @Route("/job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     * 
     * @Entity("job",expr="repository.findActiveJobs(id)")
     * 
     * @param Job $job
     * 
     * @return Response
     */
    public function show(Job $job) : Response
    {
        return $this->render('job/show.html.twig',[
            'job' => $job,
        ]);
    }

     /** 
     * @Route("/job/create", name="job.create", methods={"GET", "POST"})
     * 
     * @param Request $request
     * @param EntityManagerInterface $em
     * 
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader) : Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $logoFile = $form->get('logo')->getData();

            if($logoFile instanceOf UploadedFile){
                $fileName=$fileUploader->upload($logoFile);
                $job->setLogo($fileName);
            }

            $em->persist($job); //Doctrine administra el objeto $obj
            $em->flush(); // Se lanzan las sentencias sql de los objetos administrados por document
        

            return $this->redirectToRoute('job.preview', [
                'token' => $job->getToken(),
            ]);
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     

    /**
     * Lists all job entities.
     * 
     * @Route("/job", name="job.list", methods="GET")
     * 
     * 
     * @return Response
     */

    public function list(EntityManagerInterface $em) : Response
    {
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

     /**
    * Edit existing job entity
    *
    * @Route("/job/{token}/edit", name="job.edit", methods={"GET","POST"}, requirements={"token" = "\w+"})
    *
    * @param Request $request
    * @param Job $job
    * @param EntityManagerInterface $em
    * @return Response
    */
    public function edit(Request $request, Job $job, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('job.list');
        }
        return $this->render('job/edit.html.twig', [
        'form' => $form->createView(),
        ]);
    }
    
     /**
     * Finds and displays the preview page for a job entity.
     *
     * @Route("job/{token}", name="job.preview", methods="GET", requirements={"token" = "\w+"})
     *
     * @param Job $job
     *
     * @return Response
     */
    public function preview(Job $job) : Response
    {
        $deleteForm = $this->createDeleteForm($job);

        return $this->render('job/show.html.twig', [
            'job' => $job,
            'hasControlAccess' => true,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a job entity.
     *
     * @param Job $job
     *
     * @return FormInterface
     */
    private function createDeleteForm(Job $job) : FormInterface
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('job.delete', ['token' => $job->getToken()]))
        ->setMethod('DELETE')
        ->getForm();
    }


    /**
     * Delete a job entity.
     *
     * @Route("job/{token}/delete", name="job.delete", methods={"DELETE"}, requirements={"token" =
     *  "\w+"})
     * 
     * @param Request $request
     * 
     * @param Job $job
     * 
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Job $job, EntityManagerInterface $em):Response
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->remove($job);
            $em->flush();
        }
        return $this->redirectToRoute('job.list');
    }
}


?>
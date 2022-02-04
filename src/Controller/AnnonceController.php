<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Review;
use App\Form\AnnonceType;
use App\Form\ReviewType;
use App\Repository\AnnonceRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonce")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="annonce_index", methods={"GET"})
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="annonce_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $listPicture = [];

            foreach ($form->get('listPicture')->getData() as $picture){
               if($picture){
                   $namePicture = uniqid().$picture->getClientOriginalName();
                   $picture->move($this->getParameter('upload_directory'), $namePicture);
                   $listPicture[] = $namePicture;
               }
            }
            $annonce->setListPicture($listPicture);

            $annonce->setDateCreation(new \DateTime());
            $annonce->setUser($this->getUser());
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_show", methods={"GET"})
     */
    public function show(Annonce $annonce, Request $request, EntityManagerInterface $entityManager, ReviewRepository $repository): Response
    {

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review->setAuteur($this->getUser());
            $review->setAnnonce($annonce);
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'review' => $review,
            'form' => $form->createView(),
            'listReview' => $repository->findBy(['annonce'=> $annonce->getId()], null, 5)
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonce_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if($annonce->getUser() !== $this->getUser()){
            $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_delete", methods={"POST"})
     */
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}

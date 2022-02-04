<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'listAnnonceRecent' => $annonceRepository->findBy([], ['date_creation'=>'DESC'], 4 )
        ]);
    }

    /**
     * @Route("/recent", name="app_recent")
     */
    public function recent(AnnonceRepository $annonceRepository){

        return $this->render('home/recent.html.twig', [
            'listAnnonceRecent' => $annonceRepository->findBy([], ['date_creation'=>'DESC'], 10 )
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     */
    public function handleSearch(Request $request, AnnonceRepository $repo)
    {
        $type = $request->request->get('form')['type'];
        $query = $request->request->get('form')['query'];

        if($type == "tags") {
            $blogs = $repo->findArticlesByName($query, $type);
        }else{
            $blogs = $repo->findArticlesByName($query , $type);
        }

        return $this->render('home/index.html.twig', [
            'listAnnonceRecent' => $blogs
        ]);
    }

    public function searchBar(): Response
    {

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Mot-Clé' => "tags",
                    'Titre' => "titre",
                ],
                'label'=>" "
            ])
            ->add('query', TextType::class, [
                'label'=>false,
            ])
            ->add('recherche', SubmitType::class)
            ->getForm();

        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);

    }
}

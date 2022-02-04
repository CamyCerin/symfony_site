<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    /**
     * @Route("/user/addupvote/{id}", name="app_upvote")
     */
    public function userUpVote(User $user, EntityManagerInterface $entityManager) : Response{

        $user->setUpvote($user->getUpvote() + 1);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute("app_home");
    }

    /**
     * @Route("/user/adddownvote/{id}", name="app_downvote")
     */
    public function userDownVote(User $user, EntityManagerInterface $entityManager) : Response{

        $user->setDownvote($user->getDownvote() + 1);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute("app_home");
    }
}

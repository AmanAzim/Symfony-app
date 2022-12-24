<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MicroPostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')] 
    public function showProfile(User $user, MicroPostRepository $microPostRepo): Response
    {
        return $this->render('profile/showProfile.html.twig', [
            'user' => $user,
            'posts' => $microPostRepo->findAllByAuthor($user)
        ]);
    }

    #[Route('/profile/{id}/follows', name: 'app_profile_follows')]
    public function follows(User $user, Request $request, ManagerRegistry $doctrineManager): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        // if ($userToUnfollow->getId() !== $currentUser->getId()) {
        //     $currentUser->unFollow($userToUnfollow);

        //     $doctrineManager->getManager()->flush();
        // }

        return $this->render('profile/follows.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('profile/{id}/followers', name: 'app_profile_followers')]
    public function followers(User $user, Request $request, ManagerRegistry $doctrineManager): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        return $this->render('profile/followers.html.twig', [
            'user' => $user,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('IS_AUTHENTICATED_FULLY')] 
class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    public function like(MicroPost $post, MicroPostRepository $microPostRepo, Request $request): Response
    {
        $currentUser = $this->getUser();
        $post->addLikedBy($currentUser);
        $microPostRepo->save($post, true);

        return $this->redirect($request->headers->get('referer')); // this returns last visited route
    }
    
    #[Route('/unlike/{id}', name: 'app_unlike')]
    public function unLike(MicroPost $post, MicroPostRepository $microPostRepo, Request $request): Response
    {
        $currentUser = $this->getUser();
        $post->removeLikedBy($currentUser);
        $microPostRepo->save($post, true);

        return $this->redirect($request->headers->get('referer'));
    }
}

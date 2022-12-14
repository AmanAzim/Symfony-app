<?php

namespace App\Controller;

use DateTime;
use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $post): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $post->findAll(),
        ]);
    }

    #[Route('/micro-post/{id}', name: 'app_micro_single_post')]
    public function showOne(MicroPost $post): Response //composer require sensio/framework-extra-bundle enabling this direct maping to post by id
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }
}




        // $microPost = new MicroPost();
        // $microPost->setTitle('Welsome to BD');
        // $microPost->setText('Welsome to BD text');
        // $microPost->setCreatedAt(new DateTime());
        // $post->save($microPost, true);

        // $existingPost = $post->find(4);
        // $existingPost->setTitle('Welsome to Bangladesh');
        // $post->save($existingPost, true);

        // $existingPost = $post->find(4);
        // $post->remove($existingPost, true);

        // dd($post->findAll());
        // dd($post->find(1));
        // dd($post->findOneBy(['title' => 'Welsome to BD']));
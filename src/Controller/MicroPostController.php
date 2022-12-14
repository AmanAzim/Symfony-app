<?php

namespace App\Controller;

use DateTime;
use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $post): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $post->findAll(),
        ]);
    }

    #[Route('/micro-post/{id}', name: 'app_micro_get_single_post')]
    public function showOne(MicroPost $post): Response //composer require sensio/framework-extra-bundle enabling this direct maping to post by id
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 1)]
    public function addMicroPost(Request $request, MicroPostRepository $posts): Response {
        $microPost = new MicroPost();
        // $form = $this->createFormBuilder($microPost)
        // ->add('title')
        // ->add('text') // this fields must match the MicroPost entity fields
        // // ->add('submit', SubmitType::class, ['label' => 'Save'])
        // ->getForm();

        $form = $this->createForm(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // $data->setCreatedAt(new DateTime());

            $posts->save($data, true);

            $this->addFlash('success', 'post added !');

            return $this->redirectToRoute('app_micro_post'); //return $this->redirect('/micro-post');
        }

        return $this->renderForm('micro_post/addPost.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/micro-post/edit/{id}', name: 'app_micro_post_edit', priority: 1)]
    public function editMicroPost(MicroPost $post, Request $request, MicroPostRepository $posts): Response {
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $posts->save($data, true);

            $this->addFlash('success', 'post updated !');

            return $this->redirectToRoute('app_micro_post'); //return $this->redirect('/micro-post');
        }

        return $this->renderForm('micro_post/addPost.html.twig', [
            'form' => $form
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
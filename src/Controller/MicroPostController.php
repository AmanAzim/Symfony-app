<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $microPostRepo): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $microPostRepo->findAllWithComments(),
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
    public function addMicroPost(Request $request, MicroPostRepository $microPostRepo): Response {
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
            $data->setCreatedAt(new DateTime());

            $microPostRepo->save($data, true);

            $this->addFlash('success', 'post added !');

            return $this->redirectToRoute('app_micro_post'); //return $this->redirect('/micro-post');
        }

        return $this->renderForm('micro_post/addPost.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/micro-post/edit/{id}', name: 'app_micro_post_edit', priority: 1)]
    public function editMicroPost(MicroPost $post, Request $request, MicroPostRepository $microPostRepo): Response {
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $microPostRepo->save($data, true);

            $this->addFlash('success', 'post updated !');

            return $this->redirectToRoute('app_micro_post'); //return $this->redirect('/micro-post');
        }

        return $this->renderForm('micro_post/editPost.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }


    #[Route('/micro-post/{id}/comment', name: 'app_micro_post_add_comment')]
    public function addComment(Request $request, MicroPost $post, CommentRepository $commentRepo): Response {
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setMicroPost($post); // stablish relation ship with the particular post

            $commentRepo->save($comment, true);

            $this->addFlash('success', 'Comment added !');

            return $this->redirectToRoute('app_micro_get_single_post', [ 'id' => $post->getId() ]);
        }

        return $this->renderForm('micro_post/comment.html.twig', [
            'form' => $form,
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
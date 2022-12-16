<?php

namespace App\Controller;
use DateTime;
use App\Entity\User;
use App\Entity\MicroPost;
use App\Entity\Comment;
use App\Entity\UserProfile;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// class HelloController {
//     private array $messages = ["hello", "hi", "bi"];

//     #[Route('/{limit<\d+>?3}', name: 'app_index')]
//     public function index(int $limit): Response {
//         $result = array_slice($this->messages, 0, $limit);

//         return new Response(implode(',', $result));
//     } 

//     #[Route('/messages/{id<\d+>}', name: 'app_index_show_one')]
//     public function showOne($id): Response {
//         return new Response($this->messages[$id]);
//     } 
// }

class HelloController extends AbstractController { // this parent class helps to genereate response based on Twig template
    private array $messages = [
        ['message' => 'Hello', 'created' => '2022/11/12'],
        ['message' => 'Hi', 'created' => '2022/10/12'],
        ['message' => 'Bye!', 'created' => '2021/05/12']
    ];

    #[Route('/', name: 'app_index_test_relation', priority: 1 )]
    public function testRelation(UserProfileRepository $profileRepo, UserRepository $userRepo, CommentRepository $commentRepo, MicroPostRepository $microPostRepo): Response {
        // $post = new MicroPost();
        // $post->setTitle('Hello');
        // $post->setText('Hello');
        // $post->setCreatedAt(new DateTime());

        $comment = new Comment();
        $comment->setText('one coment');
        // $post->addComment($comment);
        // $microPostRepo->save($post, true);

        $post = $microPostRepo->find(2);
        $comment->setMicroPost($post);
        // $comment = $post->getComments()[0];
        $commentRepo->save($comment, true);

        // One to One
        // $user = new User();
        // $user->setEmail('example#gmail.com');
        // $user->setPassword('1234');
        // // $userRepo->save($user, true); we don't need to save it saving user profile with this will do as they are in cascade oneTOone relationship

        // $userProfile = new UserProfile();
        // $userProfile->setTwitterUserName('cool_user');
        // $userProfile->setUser($user);
        // $profileRepo->save($userProfile, true);

        // $profileToRemove = $profileRepo->find(1);
        // $profileRepo->remove($profileToRemove, true);

        return $this->render(
            'helloController/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => 3
            ]
        );
    } 

    #[Route('/{limit<\d+>?3}', name: 'app_index')]
    public function index(int $limit): Response {
        $result = array_slice($this->messages, 0, $limit);

        return $this->render(
            'helloController/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => $limit
            ]
        );
    } 

    #[Route('/messages/{id<\d+>}', name: 'app_index_show_one')]
    public function showOne($id): Response {
        return $this->render(
            'helloController/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
    } 
}
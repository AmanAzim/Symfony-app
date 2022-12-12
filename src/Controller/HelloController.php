<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
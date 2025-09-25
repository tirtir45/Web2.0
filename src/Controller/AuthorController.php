<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/{name}', name: 'app_author_name')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }
    #[Route('/authors', name: 'app_author_details')]
    public function listAuthors(): Response
    {
        $authors=array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpeg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com','nb_books' => 100),
            array('id' => 2, 'picture' => '/images/willaim-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com','nb_books' => 200),
            array('id' => 3, 'picture' => '/images/taha-hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com','nb_books' => 300),
        );

        return $this->render('author/list.html.twig' , [
            'authors' => $authors,
        ]);
    }
    
    #[Route('/author/detail/{id}', name: 'app_author_detail')]
    public function authorDetails(int $id): Response
    {
        $authors=array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpeg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com','nb_books' => 100),
            array('id' => 2, 'picture' => '/images/willaim-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com','nb_books' => 200),
            array('id' => 3, 'picture' => '/images/taha-hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com','nb_books' => 300),
        );

        $author = null;
        foreach ($authors as $a) {
            if ($a['id'] === $id) {
                $author = $a;
                break;
            }
        }
        return $this->render('author/showAuthor.html.twig' , [
            'author' => $author,
        ]);
    }

}


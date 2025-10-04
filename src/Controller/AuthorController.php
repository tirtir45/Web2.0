<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;
use App\Form\AuthorEditType;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors=$authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    //ajout statique d'un auteur
    #[Route('/author/add', name: 'app_author_add')]
    public function add(EntityManagerInterface $entityManager): Response
    {
        $author = new Author();

        $author->setUsername('Stephan King');
        $author->setEmail('stephan.king@gmail.com');

        $entityManager->persist($author);
        $entityManager->flush();

        $this->addFlash('success', 'Author added!');
        return $this->redirectToRoute('app_author');
    }

    //ajout form
    #[Route('/author/addf', name: 'app_author_addform')]
    public function addF(Request $request, EntityManagerInterface $entityManager): Response {
    
        $author= new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        
        //form valide or nah
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($author); //to save the entity
            $entityManager->flush(); //sync 
            return $this->redirectToRoute('app_author');
        }

        return $this->render('author/addf.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }

    //edit 
    #[Route('/author/edit/{id}', name: 'app_author_edit')]
    public function editA(int $id, Request $request, EntityManagerInterface $entityManager, AuthorRepository $authorRepository): Response
    {

        $author = $authorRepository->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        //form valide or nah
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_author');
        }
        
        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }

    //delete
    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    public function delete(int $id, Request $request, EntityManagerInterface $entityManager, AuthorRepository $authorRepository): Response
    {
        $author = $authorRepository->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }

        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('app_author');
    }

    //delete author based on nbBooks
    #[Route('/author/deleteb/{id}', name: 'app_author_deleteb')]
    public function deleteB(int $id, Request $request, EntityManagerInterface $entityManager, AuthorRepository $authorRepository): Response
    {
        $author = $authorRepository->find($id);
        if (!$author) {
            throw $this->createNotFoundException('Author not found');
        }

        if ($author->getNbBooks() > 0) {
            return $this->redirectToRoute('app_author');
        }

        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('app_author');
    }

}

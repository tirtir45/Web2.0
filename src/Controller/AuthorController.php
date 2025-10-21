<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuthorRepository; //pour l'affichage
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

    //affichage
    #[Route('/author/get', name: 'get_author')]
    public function getAll(AuthorRepository $authorRepository): Response //$authorRepository ism il injection de dépendance
    {
        $authors=$authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    /*---------------------------PARTIE STATIQUE-----------------------------------------------
    //ajout
    #[Route('/author/add', name: 'add_author')]
    public function add(EntityManagerInterface $entityManager): Response
    {
        $author1=new Author();
        $author1->setUsername('auteur1');
        $author1->setEmail('author1.0@gmail.com');
        $author1->setNbBooks('10');

        $author2=new Author();
        $author2->setUsername('auteur2');
        $author2->setEmail('auhtor2.0@gmail.com');
        $author2->setNbBooks('11');

        $entityManager->persist($author1);
        $entityManager->persist($author2);
        $entityManager->flush();


        return new Response('auteurs ajoutés');
        return $this->redirectToRoute('get_author');
    }

    //suppression
    #[Route('/author/delete/{id}',name:'delete_author')]
    public function delete(int $id,EntityManagerInterface $em,AuthorRepository $authorRepository): Response
    {
        $author=$authorRepository->find($id);
        if($author){
            $em->remove($author);
            $em->flush();
            return new Response('auteur supprimé');
        }
        
        return new Response('Auteur non trouvé');
        return $this->redirectToRoute('get_author');
    }

    //édition
    #[Route('/author/edit/{id}',name:'edit_author')]
    public function edit(int $id,EntityManagerInterface $em,AuthorRepository $authorRepository): Response
    {
        $author=$authorRepository->find($id=3);
        $author->setUsername('auteur modifié');
        $author->setEmail('author.modifié@gmail.com');
        $author->setNbBooks('15');
        $em->flush();
        return new Response('auteur modifié');
        return $this->redirectToRoute('get_author');
    }*/

    /*---------------------------PARTIE DYNAMIQUE-----------------------------------------------*/
    //ajout form
    #[Route('/author/addf', name: 'app_author_addform')]
    public function addF(Request $request, EntityManagerInterface $entityManager): Response {
    
        $author= new Author(); //empty instance
        $form = $this->createForm(AuthorType::class, $author /*win bch titzed il data ili bch n3amrha fil form*/);
        $form->handleRequest($request);
        
        //form valide or nah
        if($form->isSubmitted()){
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
        if ($form->isSubmitted()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_author');
        }
        
        return $this->render('author/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }
    
    //delete author based on nbBooks
    #[Route('/author/deleteb/{id}', name: 'app_author_deleteb')]
    public function deleteB(int $id, Request $request, EntityManagerInterface $entityManager, AuthorRepository $authorRepository): Response
    {
        $author = $authorRepository->find($id);

        if ($author->getNbBooks() > 0) {
            return $this->redirectToRoute('app_author');
        }

        $entityManager->remove($author);
        $entityManager->flush();

        return $this->redirectToRoute('app_author');
    }

}
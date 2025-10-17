<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\BookType;
use App\Form\BookEditType;
use Doctrine\ORM\EntityManager;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Entity\Author;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $books = $entityManager->getRepository(Book::class)->findAll();
        
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
        ]);
    }

    //add book form
    #[Route('/book/addf', name: 'app_book_add')]
    public function addF_B(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        //form valide or nah
        if($form->isSubmitted() && $form->isValid()){
            
            if ($book->isEnabled()) {
                $author = $book->getAuthorB();
                if($author) {
                    $currentNbBooks = $author->getNbBooks() ?? 0;
                    $author->setNbBooks($currentNbBooks + 1);
                }
            }
            
            $entityManager->persist($book); 
            $entityManager->flush(); 
            return $this->redirectToRoute('app_book_list');
        }

        return $this->render('book/addf.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //list books
    #[Route('/book/list', name: 'app_book_list')]
    public function list(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);
    }

    //edit book
    #[Route('/book/edit/{id}', name: 'app_book_edit')]
    public function edit(int $id,Request $request, EntityManagerInterface $entityManager, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }
        $form = $this->createForm(BookEditType::class, $book);
        $form->handleRequest($request);

        //form valide or nah
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('app_book_list');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    //delete book
    #[Route('/book/delete/{id}', name: 'app_book_delete')]
    public function delete(int $id, Request $request, EntityManagerInterface $entityManager, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('app_book_list');
    }

    //show book details
    #[Route('/book/show/{id}', name: 'app_book_show')]
    public function show(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    //testing

    #[Route('/book/count', name: 'app_book_count')]
    public function countBooks(BookRepository $bookRepository): Response
    {
        $countDql = $bookRepository->getNbrBooksDQL();
        $countQb = $bookRepository->getNbrBooksQB();
        return $this->render('book/count.html.twig', [
            'countDql' => $countDql,
            'countQb'  => $countQb,
        ]);
    }

    #[Route('/book/by-author/{authorId}', name: 'app_book_by_author')]
    public function booksByAuthor(int $authorId, BookRepository $bookRepository): Response
    {
        // get books by DQL
        $booksDql = $bookRepository->getBooksByAuthorDQL($authorId);

        // get books by QueryBuilder
        $booksQb = $bookRepository->getBooksByAuthorQB($authorId);

        return $this->render('book/by_author.html.twig', [
            'authorId'  => $authorId,
            'booksDql'  => $booksDql,
            'booksQb'   => $booksQb,
        ]);
    }
}
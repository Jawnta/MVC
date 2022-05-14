<?php

namespace App\library;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class for book
 */
class Book extends AbstractController
{
    /**
     * Getter for * books
     */
    public function getAllBooks(BooksRepository $bookRepository): array
    {
        return $bookRepository->findAll();
    }

    /**
     * method for adding a new book to database
     */
    public function newBook(ManagerRegistry $doctrine, $name, $isbn, $author, $description, $image): Books
    {
        $entityManager = $doctrine->getManager();
        $book = new Books();
        $book->setTitle($name);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setDescription($description);
        $book->setImage($image);

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $book;
    }

    /**
     * finds and returns a book from $id
     */
    public function getOneBook(ManagerRegistry $doctrine, $id) {
        $entityManager = $doctrine->getManager();
        return $entityManager->getRepository(Books::class)->find($id);
    }

    /**
     * method for updating properties of a book
     */
    public function updateBook(ManagerRegistry $doctrine, $id, $name, $isbn, $author, $description, $image) {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $book->setTitle($name);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setDescription($description);
        $book->setImage($image);
        $entityManager->flush();
    }

    /**
     * method for deleting a book from the database
     */
    public function deleteBook(ManagerRegistry $doctrine, $id) {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Books::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();
    }

}

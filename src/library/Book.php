<?php

namespace App\library;

use App\Entity\Books;
use App\Entity\Product;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Book extends AbstractController
{
    public function getAllBooks(BooksRepository $bookRepository): array
    {
        return $bookRepository->findAll();
    }

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

    public function getOneBook(ManagerRegistry $doctrine, $id) {
        $entityManager = $doctrine->getManager();
        return $entityManager->getRepository(Books::class)->find($id);
    }

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

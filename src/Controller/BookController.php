<?php

namespace App\Controller;

use App\Entity\Books;
use App\Entity\Product;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    /**
     * @Route("/book/library", name="library")
     */
    public function library(BooksRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        return $this->render('book/library.html.twig', ['books' => $books]);
    }

    /**
     * @Route("/book/add_book", name="add_book")
     */
    public function addBook(): Response
    {
        return $this->render('book/add_book.html.twig');
    }

    /**
     * @Route("/book/create", name="create_book", methods={"POST"})
     */
    public function createBook(ManagerRegistry $doctrine, Request $request): RedirectResponse {
        $entityManager = $doctrine->getManager();
        $name = $request->get('b_name');
        $isbn = $request->get('b_isbn');
        $author = $request->get('b_author');
        $description = $request->get('b_description');
        $image = $request->get('b_image');


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

        return $this->redirect('library');
    }

    /**
     * @Route("/book/show_book/{id}", name="show_book")
     */
    public function showBook(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);
        return $this->render('book/show_book.html.twig', ['book' => $book]);
    }

    /**
     * @Route("/book/pre_update/{id}", name="pre_update")
     */
    public function preUpdate(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Books::class)->find($id);
        return $this->render('book/update_book.html.twig', ['book' => $book]);
    }


    /**
     * @Route("/book/update_book", name="update_book")
     */
    public function updateBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $id = $request->get('b_id');
        $book = $entityManager->getRepository(Books::class)->find($id);
        $name = $request->get('b_name');
        $isbn = $request->get('b_isbn');
        $author = $request->get('b_author');
        $description = $request->get('b_description');
        $image = $request->get('b_image');

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

        return $this->redirectToRoute('library');
    }

    /**
     * @Route("/book/delete/{id}", name="delete_book")
     */
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Books::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('library');
    }
}

<?php

namespace App\Controller;

use App\library\Book;
use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private Book $book;

    /**
     * Constructor which holds properties of hand, score, balance and bet.
     */
    #[Pure] public function __construct($hand = [], $score = 0, $balance = 2000, $bet = 0)
    {
        $this->book = new Book();

    }

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
        $books = $this->book->getAllBooks($bookRepository);

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

        $name = $request->get('b_name');
        $isbn = $request->get('b_isbn');
        $author = $request->get('b_author');
        $description = $request->get('b_description');
        $image = $request->get('b_image');


        $this->book->newBook($doctrine, $name, $isbn, $author, $description, $image);

        return $this->redirect('library');
    }

    /**
     * @Route("/book/show_book/{id}", name="show_book")
     */
    public function showBook(ManagerRegistry $doctrine, int $id): Response
    {
        $book = $this->book->getOneBook($doctrine, $id);
        return $this->render('book/show_book.html.twig', ['book' => $book]);
    }

    /**
     * @Route("/book/pre_update/{id}", name="pre_update")
     */
    public function preUpdate(ManagerRegistry $doctrine, int $id): Response
    {

        $book = $this->book->getOneBook($doctrine, $id);

        return $this->render('book/update_book.html.twig', ['book' => $book]);
    }


    /**
     * @Route("/book/update_book", name="update_book")
     */
    public function updateBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {

        $id = $request->get('b_id');
        $name = $request->get('b_name');
        $isbn = $request->get('b_isbn');
        $author = $request->get('b_author');
        $description = $request->get('b_description');
        $image = $request->get('b_image');

        $this->book->updateBook($doctrine, $id, $name, $isbn, $author, $description, $image);

        return $this->redirectToRoute('library');
    }

    /**
     * @Route("/book/delete/{id}", name="delete_book")
     */
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {

        $this->book->deleteBook($doctrine, $id);


        return $this->redirectToRoute('library');
    }
}

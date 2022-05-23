<?php

namespace App\Proj;


use App\Entity\HighScore;
use App\Repository\HighScoreRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class for HighScoreList
 * @SuppressWarnings(ShortVariable)
 */
class HighScoreList extends AbstractController
{
    /**
     * getter for all highscore entries
     * @param HighScoreRepository $highScoreRepository
     * @return array<object>
     */
    public function getHighScoreEntries(HighScoreRepository $highScoreRepository): array
    {
        return $highScoreRepository->findAll();
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param string $name
     * @param int $balance
     * @param DateTimeInterface|null $dateTime
     * @return HighScore
     */
    public function addEntry(ManagerRegistry $doctrine, string $name, int $balance, DateTimeInterface $dateTime=null): HighScore
    {
        $entityManager = $doctrine->getManager();
        if ($dateTime == null) {
            $dateTime = new DateTime();
        }
        $highScore = new HighScore();
        $highScore->setName($name);
        $highScore->setBalance($balance);
        $highScore->setDate($dateTime);


        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($highScore);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $highScore;
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param int $id
     * @return void
     */
    public function deleteEntry(ManagerRegistry $doctrine, int $id)
    {
        $entityManager = $doctrine->getManager();
        $entry = $entityManager->getRepository(HighScore::class)->find($id);

        if (!$entry) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $entityManager->remove($entry);
        $entityManager->flush();
    }

    /**
     * @param HighScoreRepository $highScoreRepository
     * @param ManagerRegistry $doctrine
     * @return void
     */
    public function resetDb(HighScoreRepository $highScoreRepository, ManagerRegistry $doctrine)
    {
        $entries = $this->getHighScoreEntries($highScoreRepository);
        $names = ["Kalle", "Adam", "Bertil"];
        $balance = [4300, 5500, 8000];
        $entriesSize = sizeof($entries);
        $dateOne = date_create_from_format('j-M-Y', '30-Mar-2022');
        $dateTwo = date_create_from_format('j-M-Y', '15-Feb-2022');
        $dateThree = date_create_from_format('j-M-Y', '20-Apr-2022');
        $dates = [$dateOne, $dateTwo, $dateThree];
        if (!$entries == []) {
            for ($i = 0; $i < $entriesSize; $i++) {
                $this->deleteEntry($doctrine, $entries[$i]->id);
            }
        }
        for ($x = 0; $x < 3; $x++) {
            $this->addEntry($doctrine, $names[$x], $balance[$x], $dates[$x]);
        }
    }
}

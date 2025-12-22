<?php
class Test {
    public $questions = [];
    private $answers = [];
    private $category;

    public function isAvailable(): bool {
        return !empty($this->questions);
    }
    private function getQuestionsOfChoice(string $questionLevel, int $pointQuantity, int $questionQuantity) {
        require_once("../dbconnect.php");

        $getQuestionsQuery = $connection->prepare(
            'SELECT pyt.Numer_pytania, pyt.Pytanie, pyt.Odp_A, pyt.Odp_B, pyt.Odp_C, pyt.Poprawna_odp, pyt.Media, pyt.Zakres_struktury, pyt.Liczba_punktow
            FROM pytania_egzaminacyjne as pyt WHERE Kategorie LIKE :category AND Zakres_struktury LIKE :questionLevel AND Liczba_punktow = :pointQuantity
            ORDER BY RAND() LIMIT :questionQuantity'
        );

        $categoryValue = "%{$this->category},%";
        $getQuestionsQuery->bindValue(':category', $categoryValue, PDO::PARAM_STR);
        $getQuestionsQuery->bindValue(':questionLevel', $questionLevel, PDO::PARAM_STR);
        $getQuestionsQuery->bindValue(':pointQuantity', $pointQuantity, PDO::PARAM_INT);
        $getQuestionsQuery->bindValue(':questionQuantity', $questionQuantity, PDO::PARAM_INT);
        //gdyby pojawił się błąd to może on być związany z typem danych powyższych dwóch parametrów

        $getQuestionsQuery->execute();

        return $getQuestionsQuery->fetchAll();
    }
    private function assignQuestions() {
        $this->questions[] = $this->getQuestionsOfChoice("PODSTAWOWY", 1, 4);
        $this->questions[] = $this->getQuestionsOfChoice("PODSTAWOWY", 2, 6);
        $this->questions[] = $this->getQuestionsOfChoice("PODSTAWOWY", 3, 10);
        $this->questions[] = $this->getQuestionsOfChoice("SPECJALISTYCZNY", 1, 2);
        $this->questions[] = $this->getQuestionsOfChoice("SPECJALISTYCZNY", 2, 4);
        $this->questions[] = $this->getQuestionsOfChoice("SPECJALISTYCZNY", 3, 6);
    }
    function __construct(string $category = "B") {
        $this->category = $category;
        $this->assignQuestions();
    }
}
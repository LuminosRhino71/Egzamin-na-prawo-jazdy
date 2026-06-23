<!DOCTYPE html>
<?php
    session_start();
    require_once("../dbconnect.php");

    $category = "B";

    $getQuestionQuery = $connection->prepare(
        'SELECT pyt.Numer_pytania, pyt.Pytanie, pyt.Odp_A, pyt.Odp_B, pyt.Odp_C, pyt.Poprawna_odp, pyt.Media, pyt.Zakres_struktury, pyt.Liczba_punktow
        FROM pytania_egzaminacyjne as pyt WHERE Kategorie LIKE :category /*AND Zakres_struktury LIKE :questionLevel AND Liczba_punktow = :pointQuantity*/
        ORDER BY RAND() LIMIT :questionQuantity'
    );

    $categoryValue = "%{$category},%";
    $getQuestionQuery->bindValue(':category', $categoryValue, PDO::PARAM_STR);
    //$getQuestionQuery->bindValue(':questionLevel', $questionLevel, PDO::PARAM_STR);
    //$getQuestionQuery->bindValue(':pointQuantity', $pointQuantity, PDO::PARAM_INT);
    $getQuestionQuery->bindValue(':questionQuantity', 1, PDO::PARAM_INT);
    $getQuestionQuery->execute();
    $question = $getQuestionQuery->fetch();
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tryb Nauki</title>
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="../variable_styles.css">
    </head>
    <body>
        <header>
            <h1>Ucz się</h1>
        </header>
        <main>
            <a href="../" class="defaultButton">Porzuć naukę</a>
            <?php
                
            ?>
        </main>
        <script src="app.js"></script>
    </body>
</html>
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
                if (!empty($question)) {
                    $mediaContainerDisplayCSSProperty = $question["Media"] ? "block" : "none";

                    echo <<<HTML
                        <div id="questionInfo-MediaContainer" class="horizontalContainer">
                            <div class="mediaContainer" style="display: {$mediaContainerDisplayCSSProperty}">
                    HTML;

                    if (str_ends_with($question["Media"], ".mp4")) {
                        echo <<<HTML
                            <video id="questionVideo" class="questionMedia" autoplay muted>
                                <source src='../test/media/{$question["Media"]}' type='video/mp4'/>
                            </video>
                        HTML;
                    } else if (str_ends_with($question["Media"], ".jpg")) {
                        echo <<<HTML
                            <img id="questionImage" src='../test/media/{$question["Media"]}' class="questionMedia" alt='Obraz załączony do pytania'/>
                        HTML;
                    }

                    echo <<<HTML
                            </div>
                        </div>
                        <p>{$question["Pytanie"]}</p>
                    HTML;
                } else {
                    echo /*html*/'<p class="errorMessage">Wystąpił błąd podczas pobierania pytania.</p>';
                }
            ?>
        </main>
        <script src="app.js"></script>
    </body>
</html>
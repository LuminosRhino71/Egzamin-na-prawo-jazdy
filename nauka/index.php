<!DOCTYPE html>
<?php
    session_start();
    require_once("../dbconnect.php");

    $category = "B";

    if (isset($_SESSION["currentQuestion"])) {
        $question = $_SESSION["currentQuestion"];
    } else {
        $getQuestionQuery = $connection->prepare(
            'SELECT pyt.Numer_pytania, pyt.Pytanie, pyt.Odp_A, pyt.Odp_B, pyt.Odp_C, pyt.Poprawna_odp, pyt.Media, pyt.Zakres_struktury, pyt.Liczba_punktow
            FROM pytania_egzaminacyjne as pyt WHERE Kategorie LIKE :category ORDER BY RAND() LIMIT :questionQuantity'
        );

        $categoryValue = "%{$category},%";
        $getQuestionQuery->bindValue(':category', $categoryValue, PDO::PARAM_STR);
        $getQuestionQuery->bindValue(':questionQuantity', 1, PDO::PARAM_INT);
        $getQuestionQuery->execute();
        $question = $getQuestionQuery->fetch();
        $_SESSION["currentQuestion"] = $question;
    }
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tryb Nauki</title>
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="../variable_styles.css">
        <link rel="stylesheet" href="styles.css">
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
                        <p>{$question["Pytanie"]}</p>
                    HTML;

                    echo /*html*/'<form id="answerForm" method="POST">';

                    if ($question["Poprawna_odp"] === "A" || $question["Poprawna_odp"] === "B" || $question["Poprawna_odp"] === "C") {
                        echo <<<HTML
                            <input type="radio" id="aAnswer" name="multipleChoiceAnswer" value="A" class="invisibleRadio">
                            <label for="aAnswer" class='answerRadioLabel'>A. {$question["Odp_A"]}</label>
                            <input type="radio" id="bAnswer" name="multipleChoiceAnswer" value="B" class="invisibleRadio">
                            <label for="bAnswer" class='answerRadioLabel'>B. {$question["Odp_B"]}</label>
                            <input type="radio" id="cAnswer" name="multipleChoiceAnswer" value="C" class="invisibleRadio">
                            <label for="cAnswer" class='answerRadioLabel'>C. {$question["Odp_C"]}</label>
                            <input type="radio" id="noAnswer" name="multipleChoiceAnswer" value="NOT ANSWERED" class="invisibleRadio" checked>
                        HTML;
                    } else if ($question["Poprawna_odp"] === "T" || $question["Poprawna_odp"] === "N") {
                        echo <<<HTML
                            <input type="radio" id="trueAnswer" name="multipleChoiceAnswer" value="T" class="invisibleRadio">
                            <label for="trueAnswer" class='answerRadioLabel'>Tak</label>
                            <input type="radio" id="falseAnswer" name="multipleChoiceAnswer" value="N" class="invisibleRadio">
                            <label for="falseAnswer" class='answerRadioLabel'>Nie</label>
                            <input type="radio" id="noAnswer" name="multipleChoiceAnswer" value="NOT ANSWERED" class="invisibleRadio" checked>
                        HTML;
                    }

                    echo /*html*/'</form>';
                    echo /*html*/'<button type="submit" id="submitAnswerButton" class="defaultButton" form="answerForm">Sprawdź odpowiedź</button>';
                } else {
                    echo /*html*/'<p class="errorMessage">Wystąpił błąd podczas pobierania pytania.</p>';
                }
            ?>
        </main>
        <script src="app.js"></script>
    </body>
</html>
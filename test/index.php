<!DOCTYPE html>
<?php
    session_start();
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test</title>
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Odpowiadaj na pytania.</h1>
        </header>
        <main>
            <a href="../" class="defaultButton">Porzuć test</a>
            <?php
                require_once("../dbconnect.php");

                $getQuestionQuery = $connection->query(
                    'SELECT pyt.Numer_pytania, pyt.Pytanie, pyt.Odp_A, pyt.Odp_B, pyt.Odp_C, pyt.Poprawna_odp, pyt.Media, pyt.Zakres_struktury, pyt.Liczba_punktow
                    FROM pytania_egzaminacyjne as pyt WHERE Kategorie LIKE "%B,%"
                    ORDER BY RAND() LIMIT 1'
                );
                $questionData = $getQuestionQuery->fetch();
                $_SESSION["correctAnswer"] = $questionData["Poprawna_odp"];

                if (!empty($getQuestionQuery) && 0 < $getQuestionQuery->rowCount()) {
                    echo <<<HTML
                        <div id="infoContainer" class="horizontalContainer">
                            <div class="mediaContainer">
                    HTML;

                    if (str_ends_with($questionData["Media"], ".mp4")) {
                        echo <<<HTML
                            <video id="questionVideo" autoplay muted>
                                <source src='media/{$questionData["Media"]}' type='video/mp4'/>
                            </video>
                        HTML;
                    } else if (str_ends_with($questionData["Media"], ".jpg")) {
                        echo <<<HTML
                            <img id="questionImage" src='media/{$questionData["Media"]}' alt='Obraz załączony do pytania'/>
                        HTML;
                    }

                    echo <<<HTML
                            </div>
                            <div id="testInfoContainer">
                                <p>Numer pytania: {$questionData["Numer_pytania"]}</p>
                                <p>Liczba punktów: {$questionData["Liczba_punktow"]}</p>
                                <p>Zakres struktury: {$questionData["Zakres_struktury"]}</p>
                                <button type="submit" id="submitAnswerButton" class="defaultButton" form="answerForm">Zatwierdź odpowiedź</button>
                            </div>
                        </div>
                        <p>{$questionData["Pytanie"]}</p>
                    HTML;

                    echo /*html*/'<form id="answerForm" action="answerCheckScreen/" method="post">';

                    if ($questionData["Poprawna_odp"] === "A" || $questionData["Poprawna_odp"] === "B" || $questionData["Poprawna_odp"] === "C") {
                        echo <<<HTML
                            <input type="radio" id="aAnswer" name="multipleChoiceAnswer" value="A" class="answerRadio">
                            <label for="aAnswer" class="answerRadioLabel">A. {$questionData["Odp_A"]}</label>
                            <input type="radio" id="bAnswer" name="multipleChoiceAnswer" value="B" class="answerRadio">
                            <label for="bAnswer" class="answerRadioLabel">B. {$questionData["Odp_B"]}</label>
                            <input type="radio" id="cAnswer" name="multipleChoiceAnswer" value="C" class="answerRadio">
                            <label for="cAnswer" class="answerRadioLabel">C. {$questionData["Odp_C"]}</label>
                        HTML;
                    } else if ($questionData["Poprawna_odp"] === "T" || $questionData["Poprawna_odp"] === "N") {
                        echo <<<HTML
                            <input type="radio" id="trueAnswer" name="multipleChoiceAnswer" value="T" class="answerRadio">
                            <label for="trueAnswer" class="answerRadioLabel">Tak</label>
                            <input type="radio" id="falseAnswer" name="multipleChoiceAnswer" value="N" class="answerRadio">
                            <label for="falseAnswer" class="answerRadioLabel">Nie</label>
                        HTML;
                    }
                    echo /*html*/'</form>';
                } else if (!empty($getQuestionQuery) && 0 == $getQuestionQuery->rowCount()) {
                    echo /*html*/'<p>Brak pytań.</p>';
                } else {
                    echo /*html*/'<p class="errorMessage">Wystąpił błąd podczas pobierania pytań.</p>';
                }
            ?>
        </main>
        <script src="app.js"></script>
    </body>
</html>
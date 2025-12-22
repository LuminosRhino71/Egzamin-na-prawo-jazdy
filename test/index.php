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
                require_once("test.php");
                $test = new Test();

                if ($test->isAvailable()) {
                    echo <<<HTML
                        <div id="infoContainer" class="horizontalContainer">
                            <div class="mediaContainer">
                    HTML;

                    if (str_ends_with($test->questions[0]["Media"], ".mp4")) {
                        echo <<<HTML
                            <video id="questionVideo" autoplay muted>
                                <source src='media/{$test->questions[0]["Media"]}' type='video/mp4'/>
                            </video>
                        HTML;
                    } else if (str_ends_with($test->questions[0]["Media"], ".jpg")) {
                        echo <<<HTML
                            <img id="questionImage" src='media/{$test->questions[0]["Media"]}' alt='Obraz załączony do pytania'/>
                        HTML;
                    }

                    echo <<<HTML
                            </div>
                            <div id="testInfoContainer">
                                <p>Numer pytania: {$test->questions[0]["Numer_pytania"]}</p>
                                <p>Liczba punktów: {$test->questions[0]["Liczba_punktow"]}</p>
                                <p>Zakres struktury: {$test->questions[0]["Zakres_struktury"]}</p>
                                <button type="submit" id="submitAnswerButton" class="defaultButton" form="answerForm">Zatwierdź odpowiedź</button>
                            </div>
                        </div>
                        <p>{$test->questions[0]["Pytanie"]}</p>
                    HTML;

                    echo /*html*/'<form id="answerForm" action="answerCheckScreen/" method="post">';

                    if ($test->questions[0]["Poprawna_odp"] === "A" || $test->questions[0]["Poprawna_odp"] === "B" || $test->questions[0]["Poprawna_odp"] === "C") {
                        echo <<<HTML
                            <input type="radio" id="aAnswer" name="multipleChoiceAnswer" value="A" class="answerRadio">
                            <label for="aAnswer" class="answerRadioLabel">A. {$test->questions[0]["Odp_A"]}</label>
                            <input type="radio" id="bAnswer" name="multipleChoiceAnswer" value="B" class="answerRadio">
                            <label for="bAnswer" class="answerRadioLabel">B. {$test->questions[0]["Odp_B"]}</label>
                            <input type="radio" id="cAnswer" name="multipleChoiceAnswer" value="C" class="answerRadio">
                            <label for="cAnswer" class="answerRadioLabel">C. {$test->questions[0]["Odp_C"]}</label>
                        HTML;
                    } else if ($test->questions[0]["Poprawna_odp"] === "T" || $test->questions[0]["Poprawna_odp"] === "N") {
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
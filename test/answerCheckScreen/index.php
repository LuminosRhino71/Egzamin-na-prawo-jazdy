<!DOCTYPE html>
<?php
    session_start();
    if (isset($_POST["multipleChoiceAnswer"])) {
        $userAnswer = $_POST["multipleChoiceAnswer"];
        $correctAnswer = $_SESSION["correctAnswer"];
        if ($userAnswer === $correctAnswer) {
            $answerInfo = "poprawna";
        } else {
            $answerInfo = "niepoprawna";
        }
    } else {
        header("Location: ../../");
    }
?>
<html>
    <head>
        <title>Sprawdzenie odpowiedzi</title>
        <link rel="stylesheet" href="../../styles.css">
    </head>
    <body>
        <header>
            <h1>Twoja odpowiedź jest <?=$answerInfo?>.</h1>
        </header>
        <main>
            <a href="../" class="defaultButton">Następne pytanie</a>
        </main>
    </body>
</html>
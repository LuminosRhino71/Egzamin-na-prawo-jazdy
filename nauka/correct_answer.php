<?php
session_start();
$question = $_SESSION["currentQuestion"];

echo $question["Poprawna_odp"];
session_unset();
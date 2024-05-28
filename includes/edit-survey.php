<?php
require_once 'database_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['survey_id'])) {
    $survey_id = $_POST['survey_id'];
    echo $survey_id;
    // $survey = get_survey_by_id($survey_id);

    // if ($survey) {
    //     header("Location: index.php?edit_survey_id=$survey_id");
    //     exit;
    // } else {
    //     echo 'Formulario no encontrado.';
    // }
} else {
}
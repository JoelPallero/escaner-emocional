<?php
require_once '../../../wp-load.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['survey_id'])) {
    $survey_id = $_POST['survey_id'];
    $survey = getSurveyById($survey_id);

    if ($survey) {
        // Aquí puedes agregar el formulario de edición con los datos obtenidos
        echo '<form method="post" action="update_survey.php">';
        echo '<input type="hidden" name="survey_id" value="' . esc_attr($survey['id']) . '">';
        echo '<label for="survey_name">Nombre:</label>';
        echo '<input type="text" id="survey_name" name="survey_name" value="' . esc_attr($survey['name']) . '">';
        // Agrega más campos según sea necesario
        echo '<input type="submit" value="Actualizar">';
        echo '</form>';
    } else {
        echo 'Formulario no encontrado.';
    }
} else {
    echo 'Solicitud no válida.';
}
<?php
// Ruta: /wp-content/plugins/tu-plugin/includes/delete_survey.php
require_once '../../../wp-load.php'; // Cargar WordPress
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['survey_id'])) {
    $survey_id = $_POST['survey_id'];
    deleteSurveyById($survey_id);

    // Redireccionar a la lista de encuestas después de borrar
    wp_redirect(admin_url('admin.php?page=survey-list'));
    exit();
} else {
    echo 'Solicitud no válida.';
}
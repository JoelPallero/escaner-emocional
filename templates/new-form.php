<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!defined('ABSPATH')) {
    exit;
}

// Incluir funciones necesarias
include_once plugin_dir_path(__FILE__) . '../includes/database_functions.php';

$is_editing = isset($_GET['edit_survey_id']);

if ($is_editing) {
    $survey_id = $_GET['edit_survey_id'];
    $survey_data = get_survey_by_id($survey_id);
} else {
    include plugin_dir_path(__FILE__) . 'survey_list.php';
    $count = countSvs();
}

include_once plugin_dir_path(__FILE__) . '../includes/sanitize.php';
include_once plugin_dir_path(__FILE__) . '../includes/category_functions.php';

$categories = getSurveyCategories();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST['submit-category'])){
        global $wpdb;
        $newCategory = sanitizeData($_POST['create_survey_category']);

        if(empty($newCategory)){
            $errorCategory = 'No se ha creado una categoría. Por favor, cree la categoría o elija una categoría de la lista.';
        }else{
            $date_now = current_time('mysql');

            $wpdb->insert("{$wpdb->prefix}se_survey_category", [
                'name' => $newCategory,
                'date_creation' => $date_now
            ]);

            if ($wpdb->last_error) {
                $errorCategory = $wpdb->last_error;
                exit;
            }
        }
    }

    if (isset($_POST['submit-survey'])) {
        global $wpdb;

        $survey_name = sanitizeData($_POST['survey_name']);
        $category_id = sanitizeData($_POST['survey_category']);
        $survey_description = sanitizeData($_POST['survey_description']);
        $date_now = current_time('mysql');

        if (isset($survey_name) && !empty($survey_name)) {

            $wpdb->insert("{$wpdb->prefix}se_survey", [
                'name' => $survey_name,
                'description' => $survey_description,
                'shortcode' => '',
                'survey_category_id' => $category_id,
                'date_creation' => $date_now
            ]);
            $survey_id = $wpdb->insert_id;

            // Generar shortcode único
            $shortcode = '[emotional_scanner id=' . $survey_id . ']';
        
            // Actualizar la encuesta con el shortcode generado
            $wpdb->update("{$wpdb->prefix}se_survey", 
                ['shortcode' => $shortcode], // Datos a actualizar
                ['id' => $survey_id], // Condición WHERE
                ['%s'], // Formato de los datos que se están actualizando
                ['%d'] // Formato de los datos de la condición WHERE
            );

            if ($wpdb->last_error) {
                echo 'Error al insertar la encuesta: ' . $wpdb->last_error;
                exit;
            }

            $survey_id = $wpdb->insert_id;
        } else {
            $errorSurvey = 'Falta el nombre de la encuesta a crear. Por favor, ingrese el nombre.';
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Form</title>
</head>
<body class="container-lg">
    <div class="container-fluid mt-4">
        <h2 class="mt-4 mb-2"> 
            
        </h2>
        <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Crear Encuesta</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">Crear Preguntas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab3-tab" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false">Formulario 3</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                <?php include_once plugin_dir_path(dirname(__FILE__)) . 'templates/new_survey.php'; ?>
            </div>
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                <?php include_once plugin_dir_path(dirname(__FILE__)) . 'templates/questions_form.php'; ?>
            </div>
            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                <?php //include 'form3.php'; ?>
            </div>
        </div>
    </div>
</body>
</html>
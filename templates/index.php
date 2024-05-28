<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!defined('ABSPATH')) {
    exit;
}

// Incluir funciones necesarias
include_once plugin_dir_path(__FILE__) . '../includes/database_functions.php';
include_once plugin_dir_path(__FILE__) . '../includes/sanitize.php';
include_once plugin_dir_path(__FILE__) . '../includes/category_functions.php';


// Inicializa las variables de error
$errorCategory = '';
$errorSurvey = '';

// Verificar si se está editando un formulario
$is_editing = isset($_GET['edit_survey_id']);

if ($is_editing) {
    // Obtener datos del formulario para edición
    $survey_id = $_GET['edit_survey_id'];
    $survey_data = get_survey_by_id($survey_id);
} else {
    // Incluir el archivo survey_list.php para mostrar la lista de encuestas
    include plugin_dir_path(__FILE__) . 'survey_list.php';
    $count = countSvs();
}

//Get Categories
$categories = getSurveyCategories();

// Envío de datos de formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit-survey'])) {
        global $wpdb;

        // Data to submit, first of all sanitizing all data received
        $category_name = sanitizeData($_POST['create_survey_category']);
        $survey_name = sanitizeData($_POST['survey_name']);

        if (isset($category_name) && !empty($category_name)) {
            // Inserción de datos iniciales para una encuesta de ejemplo
            $date_now = current_time('mysql');

            // Crear categoría global
            $wpdb->insert("{$wpdb->prefix}se_survey_category", [
                'name' => $category_name,
                'date_creation' => $date_now
            ]);

            if ($wpdb->last_error) {
                echo 'Error al insertar la categoría: ' . $wpdb->last_error;
                exit;
            }

            $survey_category_id = $wpdb->insert_id;

            if (isset($survey_name) && !empty($survey_name)) {
                // Generar shortcode único
                $shortcode = 'survey_' . uniqid();

                // Crear encuesta
                $wpdb->insert("{$wpdb->prefix}se_survey", [
                    'name' => $survey_name,
                    'shortcode' => $shortcode,
                    'survey_category_id' => $survey_category_id,
                    'date_creation' => $date_now
                ]);

                if ($wpdb->last_error) {
                    echo 'Error al insertar la encuesta: ' . $wpdb->last_error;
                    exit;
                }

                $survey_id = $wpdb->insert_id;
            } else {
                $errorSurvey = 'Falta el nombre de la encuesta a crear. Por favor, ingrese el nombre.';
            }
        } else {
            $errorCategory = 'No se ha creado una categoría. Por favor, cree la categoría o elija una categoría de la lista.';
        }
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularios</title>
</head>

<body class="container-fluid">
    <div class="wrap container-fluid">
        <?php if ($is_editing && $survey_data): ?>
            <!-- Mostrar el formulario de edición -->
            <h1 class="wp-heading-inline">Editar Encuesta</h1>
            <form method="post" action="includes/update_survey.php">
                <input type="hidden" name="survey_id" value="<?php echo esc_attr($survey_data[0]['survey_id']); ?>" />
                <div class="form-group">
                    <label for="survey_name">Nombre de la encuesta</label>
                    <input type="text" class="form-control" id="survey_name" name="survey_name" value="<?php echo esc_attr($survey_data[0]['survey_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="global_category_name">Categoría</label>
                    <input type="text" class="form-control" id="global_category_name" name="global_category_name" value="<?php echo esc_attr($survey_data[0]['global_category_name']); ?>" required>
                </div>
                <button type="submit" class="btn btn-success">Actualizar</button>
            </form>
        <?php else: ?>

            <!-- Mostrar el contenido existente si no se está editando -->
            <div class="container-fluid">
                <header class="header">
                    <h1 class="wp-heading-inline">Encuestas</h1>
                    <a id="newSurvey" class="btn btn-primary">Añadir nueva</a>
                </header>
                <div class="footer">
                    <ul class="subsubsub">
                        <li class="all">
                            <a href="" class="current" aria-current="page">
                                Todo 
                                <span class="count">(<?php echo $count; ?>)</span>
                            </a> 
                            |
                        </li>
                        <li class="publish">
                            <a href="">Publicados
                                <span class="count">(0)</span>
                            </a> 
                            |
                        </li>
                        <li class="trash">
                            <a href="">Papelera 
                                <span class="count">(0)</span>
                            </a> 
                            |
                        </li>
                    </ul>
                </div>
            </div>

            <div class="container-fluid">
                <?php
                    listSurvey();
                ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Survey Modal -->
    <div class="modal fade modal-lg modal-dialog modal-dialog-centered modal-dialog-scrollable" id="newFormModal" tabindex="-1" aria-labelledby="newFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newFormModalLabel"> 
                        <?php 
                            if(isset($task) && $task){
                                echo $task . ' Formulario';
                            }
                        ?>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>             
                <form action="" method="post">
                    <div class="modal-body grid gap-3">
                        <div class="errors">
                            <?php
                                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                    if (isset($errorCategory) && $errorCategory) {
                                        echo '<span>Error: ' . $errorCategory . '</span>';
                                    } elseif (isset($errorSurvey) && $errorSurvey) {
                                        echo '<span>Error: ' . $errorSurvey . '</span>';
                                    }
                                }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="survey_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="survey_name" name="survey_name" placeholder="Encuesta #1" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="create_survey_category" class="form-label">Crear categoría</label>
                                <input type="text" class="form-control" id="create_survey_category" name="create_survey_category" placeholder="Categoria de encuesta">
                                <small>Si ya tienes una, no hace falta llenar este campo!</small>
                            </div>
                            <div class="col">
                                <label for="survey_category" class="form-label">Categoría</label>
                                <select id="survey_category" name="survey_category" class="form-select form-select-lg survey_select" aria-label="Large select example">
                                    <option selected disabled>Elegir</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo esc_attr($category->id); ?>"><?php echo esc_html($category->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="submit-survey">Guardar Formulario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Questions Modal -->
    <div class="modal fade modal-lg modal-dialog modal-dialog-centered modal-dialog-scrollable" id="questionsModal" tabindex="-1" aria-labelledby="questionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="questionsModalLabel"> 
                        <?php 
                            if(isset($survey_id) && $survey_id){
                                echo $survey_id . ' Formulario';
                            }
                        ?>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body grid gap-3">
                        <div class="errors">
                            <?php
                                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                    if (isset($errorCategory) && $errorCategory) {
                                        echo '<span>Error: ' . $errorCategory . '</span>';
                                    } elseif (isset($errorSurvey) && $errorSurvey) {
                                        echo '<span>Error: ' . $errorSurvey . '</span>';
                                    }
                                }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="survey_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="survey_name" name="survey_name" placeholder="Encuesta #1">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="survey_category" class="form-label">Crear categoría</label>
                                <input type="text" class="form-control" id="survey_category" name="survey_category" placeholder="Categoria de encuesta">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#newFormModal" data-bs-toggle="modal">Atrás</button>
                        <button type="submit" class="btn btn-primary" name="submit-questions">Guardar Preguntas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>

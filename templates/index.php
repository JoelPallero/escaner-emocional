<?php

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

?>

<!DOCTYPE html>
<html lang="es">
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
                <input type="hidden" name="survey_id" value="<?php echo esc_attr($survey_data[0]['survey_id']); ?>"/>

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
                    <a id="" class="btn btn-primary" href="<?php echo admin_url('admin.php?page=new-form'); ?>">Añadir nueva</a>
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
</body>
</html>
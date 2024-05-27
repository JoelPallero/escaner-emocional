<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../includes/database_functions.php';

function loadSurveys() {
    global $database_functions_path;
    // Verificar si el archivo existe antes de incluirlo
    if (get_survey()) {
        return get_survey();
    } else {
        // Manejar el caso de que el archivo no exista
        return "No se ha podido traer los datos";
    }
}

function listSurvey(){
    $results = loadSurveys();    
    if (is_array($results)) {        
        if(count($results) > 0){
            foreach ($results as $survey) {
                $checkbox_id = 'survey_' . $survey['id'];
                echo '<th scope="row" class="check-column">' .
                        '<input id="' . esc_attr($checkbox_id) . '" type="checkbox" name="' . esc_attr($survey['name']) . '" value="' . esc_attr($survey['id']) . '">' .
                        '<label for="' . esc_attr($checkbox_id) . '"></label>' .
                    '</th>';
                echo '<td>' . esc_html($survey['name']) . '</td>' .
                    '<td>' . esc_html($survey['category_name']) . '</td>' .
                    '<td class="td-actions">' .
                        '<form method="post" action="' . plugins_url('includes/edit_survey.php', __FILE__) . '">' .
                            '<input type="hidden" name="survey_id" value="' . esc_attr($survey['id']) . '"/>' .
                            '<input class="btn btn-warning" type="submit" value="Editar"/>' .
                        '</form>' .
                        '<form method="post" action="' . plugins_url('includes/delete_survey.php', __FILE__) . '" onsubmit="return confirm(\'¿Estás seguro de que deseas borrar este formulario?\');">' .
                            '<input type="hidden" name="survey_id" value="' . esc_attr($survey['id']) . '"/>' .
                            '<input class="btn btn-danger" type="submit" value="Borrar"/>' .
                        '</form>' .
                    '</td>';
            }
        }
    }else{
        return $results;
    }
}

function countSvs(){
    $count = countSurveys();
    return $count;
}
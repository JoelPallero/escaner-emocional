<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../includes/database_functions.php';

function loadSurveys() {
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
        echo '<table class="table table-light table-striped">' .
        '<thead>' .
            '<tr>' .
                '<td scope="col" id="cb" class="manage-column column-cb check-column">' .
                    '<input id="cb-select-all-1" type="checkbox">' .
                    '<label for="cb-select-all-1"><span class="screen-reader-text">Seleccionar todos</span></label>' .
                '</td>' .
                '<th scope="col">Nombre</th>' .
                '<th scope="col">Shortcode</th>' .
                '<th scope="col">Categoría</th>' .
                '<th scope="col">Estadísticas</th>' .
                '<th scope="col">Acción</th>' .
            '</tr>' .
        '</thead>' .
        '<tbody id="table-group-divider">';
        if(count($results) > 0){
            foreach ($results as $survey) {
                $checkbox_id = 'survey_' . $survey['id'];
                echo '<tr>' .
                        '<th scope="row" class="check-column">' .
                            '<input id="' . esc_attr($checkbox_id) . '" type="checkbox" name="' . esc_attr($survey['name']) . '" value="' . esc_attr($survey['id']) . '">' .
                            '<label for="' . esc_attr($checkbox_id) . '"></label>' .
                        '</th>' .
                        '<td>' . esc_html($survey['name']) . '</td>' .
                        '<td >' .
                            ''.esc_html($survey['shortcode']).'' .
                        '</td>' .
                        '<td>' . esc_html($survey['category_name']) . '</td>' .
                        '<td class="">' .
                            '<form method="post" action="' . esc_url(admin_url('../includes/statistics.php', __FILE__)) . '">' .
                                '<input type="hidden" name="survey_id" value="' . esc_attr($survey['id']) . '"/>' .
                                '<input class="btn btn-outline-primary" type="submit" value="Ver estadísticas"/>' .
                            '</form>' .
                        '</td>' .
                        '<td class="td-actions">' .
                            '<form method="post" action="' . esc_url(admin_url('../includes/edit_survey.php', __FILE__)) . '">' .
                                '<input type="hidden" name="survey_id" value="' . esc_attr($survey['id']) . '"/>' .
                                '<input class="btn btn-warning" type="submit" value="Editar"/>' .
                            '</form>' .
                            '<form method="post" action="' . esc_url(plugin_dir_url(__FILE__) . '../includes/delete_survey.php') . '" onsubmit="return confirm(\'¿Estás seguro de que deseas borrar este formulario?\');">' .
                                '<input type="hidden" name="survey_id" value="' . esc_attr($survey['id']) . '"/>' .
                                '<input class="btn btn-danger" type="submit" value="Borrar"/>' .
                            '</form>' .
                        '</td>' .
                    '</tr>';
            }
        }                        
        echo '</tbody>' .
        '</table>';

    } else {
        return $results;
    }
}



function countSvs(){
    $count = countSurveys();
    return $count;
}

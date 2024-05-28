<?php

function get_survey(){
    global $wpdb;

    $getSurveys = "SELECT {$wpdb->prefix}se_survey.id, {$wpdb->prefix}se_survey.name, {$wpdb->prefix}se_survey.shortcode, {$wpdb->prefix}se_survey.survey_category_id, {$wpdb->prefix}se_survey_category.name AS category_name
            FROM {$wpdb->prefix}se_survey
            JOIN {$wpdb->prefix}se_survey_category ON {$wpdb->prefix}se_survey.survey_category_id = {$wpdb->prefix}se_survey_category.id
            ORDER BY {$wpdb->prefix}se_survey.id;";

    $query_result = $wpdb->get_results($getSurveys, ARRAY_A);

    return $query_result;

}

function get_survey_by_id($id){
    global $wpdb;

    // Nombre real de la tabla de encuestas
    $survey_table = $wpdb->prefix . 'se_survey';
    // Nombre real de la tabla de categorías de encuestas
    $category_table = $wpdb->prefix . 'se_survey_category';

    // Consulta SQL con nombres de tablas y campos corregidos
    $getSurveys = $wpdb->prepare("
        SELECT 
            s.id AS survey_id,
            s.name AS survey_name,
            sc.id AS global_category_id,
            sc.name AS global_category_name
        FROM $survey_table AS s
        JOIN $category_table AS sc ON s.survey_categorie_id = sc.id
        WHERE s.id = %d", $id);

    // Ejecutar la consulta y obtener los resultados
    $query_result = $wpdb->get_results($getSurveys, ARRAY_A);

    // Retornar los resultados
    return $query_result;
}



function countSurveys(){
    global $wpdb;
    $query = "SELECT COUNT(*) AS count FROM {$wpdb->prefix}se_survey;";

    $count = $wpdb->get_results($query);

    return count($count);
}
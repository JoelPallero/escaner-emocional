<?php

function get_survey(){
    global $wpdb;

    $getSurveys = "SELECT {$wpdb->prefix}se_survey.id, {$wpdb->prefix}se_survey.name, {$wpdb->prefix}se_survey.survey_category_id, {$wpdb->prefix}se_survey_category.name AS category_name
            FROM {$wpdb->prefix}se_survey
            JOIN {$wpdb->prefix}se_survey_category ON {$wpdb->prefix}se_survey.survey_category_id = {$wpdb->prefix}se_survey_category.id
            ORDER BY {$wpdb->prefix}se_survey.id;";

    $query_result = $wpdb->get_results($getSurveys, ARRAY_A);

    return $query_result;
}

function get_survey_by_id(){
    global $wpdb;
    $getSurveys = "SELECT {$wpdb->prefix}se_survey.id, {$wpdb->prefix}se_survey.name, {$wpdb->prefix}se_survey.survey_category_id, {$wpdb->prefix}se_survey_category.name AS category_name
            FROM {$wpdb->prefix}se_survey
            JOIN {$wpdb->prefix}se_survey_category ON {$wpdb->prefix}se_survey.survey_category_id = {$wpdb->prefix}se_survey_category.id
            ORDER BY {$wpdb->prefix}se_survey.id;";

    $query_result = $wpdb->get_results($getSurveys, ARRAY_A);

    return $query_result;
}


function countSurveys(){
    global $wpdb;
    $query = "SELECT COUNT(*) AS count FROM {$wpdb->prefix}se_survey;";

    $count = $wpdb->get_results($query);

    return count($count);
}



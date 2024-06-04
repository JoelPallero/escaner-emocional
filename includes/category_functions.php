<?php
function getSurveyCategories() {
    global $wpdb;
    return $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}se_survey_category");
}

function getSurveySubcategories() {
    global $wpdb;
    return $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}se_child_category");
}

function createSubCategory(){
    
}
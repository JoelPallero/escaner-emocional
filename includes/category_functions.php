<?php
function getSurveyCategories() {
    global $wpdb;
    return $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}se_survey_category");
}
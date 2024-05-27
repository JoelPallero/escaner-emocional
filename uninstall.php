<?php


if(!defined('WP_UNINSTALL_PLUGIN')){
    die();
}

global $wpdb;

$tables = [
    "{$wpdb->prefix}se_user_answer_survey",
    "{$wpdb->prefix}se_responses",
    "{$wpdb->prefix}se_questions",
    "{$wpdb->prefix}se_child_category",
    "{$wpdb->prefix}se_survey",
    "{$wpdb->prefix}se_survey_category"
];

foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS $table");
}

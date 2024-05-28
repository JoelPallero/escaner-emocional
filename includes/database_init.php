<?php

function create_tables() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Crear tabla de categorías generales
    $createGlobalCategory = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}se_survey_category (
        `id` INT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(45) NOT NULL,
        `date_creation` DATETIME NOT NULL,
        PRIMARY KEY (`id`)
    ) $charset_collate;";
    $wpdb->query($createGlobalCategory);

    // Crear tabla de encuestas
    $createSurvey = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}se_survey (
        `id` INT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(45) NOT NULL,
        `shortcode` VARCHAR(45) NOT NULL,
        `survey_category_id` INT NOT NULL,
        `date_creation` DATETIME NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`survey_category_id`) REFERENCES {$wpdb->prefix}se_survey_category(`id`)
    ) $charset_collate;";
    $wpdb->query($createSurvey);

    // Crear tabla de categorías hijas
    $createChildCategory = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}se_child_category (
        `id` INT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(45) NOT NULL,
        `survey_id` INT NOT NULL,
        `date_creation` DATETIME NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`survey_id`) REFERENCES {$wpdb->prefix}se_survey(`id`)
    ) $charset_collate;";
    $wpdb->query($createChildCategory);

    // Crear tabla de preguntas
    $createQuestionsSurvey = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}se_questions (
        `id` INT NOT NULL AUTO_INCREMENT,
        `question` VARCHAR(255) NOT NULL,
        `question_score` DECIMAL(2, 1) NOT NULL,
        `survey_id` INT NOT NULL,
        `child_category_id` INT NOT NULL,
        `date_creation` DATETIME NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`child_category_id`) REFERENCES {$wpdb->prefix}se_child_category(`id`),
        FOREIGN KEY (`survey_id`) REFERENCES {$wpdb->prefix}se_survey(`id`)
    ) $charset_collate;";
    $wpdb->query($createQuestionsSurvey);

    // Crear tabla de respuestas
    $createResponsesQuestionSurvey = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}se_responses (
        `id` INT NOT NULL AUTO_INCREMENT,
        `response` VARCHAR(255) NOT NULL,
        `response_point` INT NOT NULL,
        `question_id` INT NOT NULL,
        `date_creation` DATETIME NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`question_id`) REFERENCES {$wpdb->prefix}se_questions(`id`)
    ) $charset_collate;";
    $wpdb->query($createResponsesQuestionSurvey);

    // Crear tabla de respuestas de usuarios
    $createUserAnswersSurvey = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}se_user_answer_survey (
        `id` INT NOT NULL AUTO_INCREMENT,
        `user_id` INT NOT NULL,
        `response_id` INT NOT NULL,
        `answer_score` INT NOT NULL,
        `date_creation` DATETIME NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`response_id`) REFERENCES {$wpdb->prefix}se_responses(`id`)
    ) $charset_collate;";
    $wpdb->query($createUserAnswersSurvey);
}

function insert_initial_data() {
    global $wpdb;
    
    // Inserción de datos iniciales para una encuesta de ejemplo
    $date_now = current_time('mysql');

    // Crear categoría global
    $wpdb->insert("{$wpdb->prefix}se_survey_category", [
        'name' => 'Tópico General',
        'date_creation' => $date_now
    ]);
    $survey_category_id = $wpdb->insert_id;

    // Generar shortcode único
    $shortcode = 'survey_' . uniqid();

    // Crear encuesta
    $wpdb->insert("{$wpdb->prefix}se_survey", [
        'name' => 'Encuesta de Ejemplo',
        'shortcode' => $shortcode,
        'survey_category_id' => $survey_category_id,
        'date_creation' => $date_now
    ]);
    $survey_id = $wpdb->insert_id;

    // Crear categorías de preguntas
    $child_categories = ['Categoría 1', 'Categoría 2', 'Categoría 3'];
    $child_category_ids = [];
    foreach ($child_categories as $category_name) {
        $wpdb->insert("{$wpdb->prefix}se_child_category", [
            'name' => $category_name,
            'survey_id' => $survey_id,
            'date_creation' => $date_now
        ]);
        $child_category_ids[] = $wpdb->insert_id;
    }

    // Crear preguntas
    $questions = ['Pregunta 1', 'Pregunta 2', 'Pregunta 3'];
    $score = 0.3;
    foreach ($questions as $index => $question_text) {
        $wpdb->insert("{$wpdb->prefix}se_questions", [
            'question' => $question_text,
            'question_score' => $score,
            'survey_id' => $survey_id,
            'child_category_id' => $child_category_ids[$index],
            'date_creation' => $date_now
        ]);
        $score += 0.2;
    }

    // Crear respuestas
    $responses = [
        ['response' => 'Bien', 'response_point' => 3],
        ['response' => 'Regular', 'response_point' => 2],
        ['response' => 'Mal', 'response_point' => 1]
    ];
    foreach ($responses as $response) {
        $wpdb->insert("{$wpdb->prefix}se_responses", [
            'response' => $response['response'],
            'response_point' => $response['response_point'],
            'question_id' => $survey_id,
            'date_creation' => $date_now
        ]);
    }
}

// Llamar a las funciones cuando el plugin se active
register_activation_hook(__FILE__, 'create_tables');
register_activation_hook(__FILE__, 'insert_initial_data');
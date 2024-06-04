<?php
/*
* Plugin Name: Escaner Emocional
* Plugin: Ver Detalles
* Plugin URI: https://joelpallero.com.ar/plugins
* Description: Es un cuestionario de preguntas para analizar el estado interno de una persona Los cálculos para los resultados son hechos a través de cálculos ponderados.
* Author: Joel Pallero
* Author URI: https://joelpallero.com.ar
* Version: 1.0
* Text Domain: escaner-emocional
*/

require_once plugin_dir_path(__FILE__) . 'includes/database_init.php';

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

// Verificar el acceso al plugin
function check_access_to_plugin() {
    // Verificar el rol del usuario actual
    if (is_user_logged_in()) {
        // Verificar el rol del usuario actual
        $user = wp_get_current_user();
        if (in_array('administrator', $user->roles) || in_array('editor', $user->roles)) {
            // El usuario tiene permisos de administrador o editor, permitir el acceso al plugin
            return;
        } else {
            // El usuario no tiene permisos suficientes, redirigir a una página de acceso denegado
            wp_safe_redirect(home_url('/access-denied'));
            exit;
        }
    } else {
        // El usuario no está autenticado, redirigir al inicio de sesión
        wp_safe_redirect(wp_login_url());
        exit;
    }
}

// Ejecutar la función de verificación en un gancho de WordPress
add_action('plugins_loaded', 'check_access_to_plugin');

function Activate(){
    create_tables();
    insert_initial_data();
}

function Deactivate(){

}

function Delete(){
    
}

register_activation_hook(__FILE__, 'Activate');
register_deactivation_hook(__FILE__, 'Deactivate');
register_uninstall_hook(__FILE__, 'uninstall.php');


function AsideMenuForWp(){
    add_menu_page(
        //titulo del page
        'Escaner Emocional', 
        //Titulo del Menu
        'Encuestas', 
        //Capability
        'manage_options',
        //slug
        'gestion-cuestionarios',
        //funcion del contenido
        'render_index_page',
        //funcion del icono del menu
        plugin_dir_url(__FILE__).'assets/img/logo-menu.png',
        //orden
        '1'
    );
    add_submenu_page(
        //parent slug
        'gestion-cuestionarios', 
        //Titulo de la pagina
        'Nuevo Formularios', 
        //Titulo del Submenu
        'Añadir Formulario', 
        //Capability
        'manage_options',
        //slug
        'new-form',
        //funcion del contenido
        'render_new_form_page',
        //orden
        '1'
    );
    
    // Obtener el ID del usuario actual
    $user_id = get_current_user_id();

    // Comprobar si el usuario actual tiene el nivel de capacidad de administrador
    if (current_user_can('manage_options')) {
        // Otorgar al usuario actual permisos de administrador
        $user = new WP_User($user_id);
        $user->add_role('administrator');
    }
}

add_action('admin_menu', 'AsideMenuForWp');

function enqueue_admin_styles() {
    wp_enqueue_style(
        'escaner-emocional-styles', 
        plugin_dir_url(__FILE__) . 'assets/css/app.css', 
        array(), 
        '1.0.0', 
        'all'
    );
}

//include Bootstrap

function includeBootstrapJs($hook) {    
    $result = checkHookPage($hook);
    if($result){
        wp_enqueue_script('bootstrapJs', plugins_url('assets/bootstrap/js/bootstrap.min.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('NewSurveyJs', plugins_url('assets/js/new_survey.js', __FILE__), array('jquery'), null, true);
    }
}

add_action('admin_enqueue_scripts', 'includeBootstrapJs');

function includeBootstrapCss($hook) {
    $result = checkHookPage($hook);
    if($result){
        wp_enqueue_style('bootstrapCSS', plugins_url('assets/bootstrap/css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('MainCSS', plugins_url('assets/css/app.css', __FILE__));
    }
}

add_action('admin_enqueue_scripts', 'includeBootstrapCss');

function checkHookPage($hook){
    switch ($hook) {
        case 'toplevel_page_gestion-cuestionarios':
            return true;
            break;
        case 'encuestas_page_new-form': // Corregido el slug de la página
            return true;
            break;
        default:
            return false;
            break;
    }
}


function render_index_page() {
    include plugin_dir_path(__FILE__) . 'templates/index.php';
}

function render_new_form_page() {
    include plugin_dir_path(__FILE__) . 'templates/new-form.php';
}


//forms ajax handler

function handle_create_category_ajax() {
    global $wpdb;

    if (!isset($_POST['formData'])) {
        wp_send_json_error(['message' => 'Datos del formulario no enviados.']);
    }

    parse_str($_POST['formData'], $formData);

    $newCategory = sanitize_text_field($formData['create_survey_category']);

    if (empty($newCategory)) {
        wp_send_json_error(['message' => 'No se ha creado una categoría. Por favor, cree la categoría o elija una categoría de la lista.']);
    }

    $date_now = current_time('mysql');

    $wpdb->insert("{$wpdb->prefix}se_survey_category", [
        'name' => $newCategory,
        'date_creation' => $date_now
    ]);

    if ($wpdb->last_error) {
        wp_send_json_error(['message' => $wpdb->last_error]);
    }

    $newCategoryId = $wpdb->insert_id;

    wp_send_json_success(['id' => $newCategoryId, 'name' => $newCategory]);
}
add_action('wp_ajax_create_category', 'handle_create_category_ajax');
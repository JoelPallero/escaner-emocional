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
        'Escaner Emocional', 
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
    if($hook != "toplevel_page_gestion-cuestionarios"){
        return;
    }
    wp_enqueue_script('bootstrapJs', plugins_url('assets/bootstrap/js/bootstrap.min.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_script('NewSurveyJs', plugins_url('assets/js/new_survey.js', __FILE__), array('jquery'), null, true);

}

add_action('admin_enqueue_scripts', 'includeBootstrapJs');

function includeBootstrapCss($hook) {
    if($hook != "toplevel_page_gestion-cuestionarios"){
        return;
    }
    wp_enqueue_style('bootstrapCSS', plugins_url('assets/bootstrap/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('MainCSS', plugins_url('assets/css/app.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'includeBootstrapCss');

function render_index_page() {
    include plugin_dir_path(__FILE__) . 'templates/index.php';
}


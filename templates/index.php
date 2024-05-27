<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    if (!defined('ABSPATH')) {
        exit;
    }
    
    // Incluir el archivo survey_list.php para mostrar la lista de encuestas
    include plugin_dir_path(__FILE__) . 'survey_list.php';
    $count = countSvs();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php wp_enqueue_style('escaner-emocional-styles'); ?>

    <title>Formularios</title>
</head>

<body class="container-fluid">
    <div class="wrap container-fluid">

        <!-- Aca se van a mostrar todos las encuestas o formularios -->
        <div class="container-fluid ">
            <div class="container-fluid">
                <header class="header">
                    <h1 class="wp-heading-inline">Encuestas</h1>
                    <a href="<?php echo plugin_dir_url(__FILE__) . 'admin-form.php'; ?>" class="btn btn-primary">Añadir
                        nueva</a>
                </header>
                <div class="footer">
                    <ul class="subsubsub">
                        <li class="all">
                            <a href="" class="current" aria-current="page">
                                Todo 
                                <span class="count">(<?php echo $count; ?>)</span>
                            </a> 
                            |
                        </li>
                        <li class="publish">
                            <a href="">Publicados
                                <span class="count">(0)</span>
                            </a> 
                            |
                        </li>
                        <li class="trash">
                            <a href="">Papelera 
                                <span class="count">(0)</span>
                            </a> 
                            |
                        </li>
                    </ul>
                </div>
            </div>

            <div class="container-fluid">
                <table class="table table-light table-striped">
                    <thead>
                        <tr>
                            <td scope="col" id="cb" class="manage-column column-cb check-column">
                                <input id="cb-select-all-1" type="checkbox">
                                <label for="cb-select-all-1"><span class="screen-reader-text">Seleccionar
                                        todos</span></label>
                            </td>
                            <th scope="col">Nombre</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="table-group-divider">
                        <?php
                            listSurvey();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
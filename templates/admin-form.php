<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión del Cuestionario</title>

    <?php wp_enqueue_style('escaner-emocional-styles'); ?>

</head>
<body>

<div class="wrap">
    <h1 class="title-page">Gestión del Cuestionario</h1>

    <h2>Encuesta activa: Escaner Emocional</h2>

    <!-- Aca deberian mostrarse cada una de las diferentes categorias internas de la encuesta, segun se hayan creado, y la primera tab que va a ser el manage general de cada encuesta. Desde ahi se va a gestionar las opciones generales de todo el formulario completo -->
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'general-settings')">Ajustes</button>
        <!-- <button class="tablinks" onclick="openTab(event, 'questions')">Preguntas</button> -->
    </div>


    <div id="general-settings" class="tabcontent">
        <h2>Ajustes Generales</h2>
        <?php include plugin_dir_path(__FILE__) . 'general-settings.php'; ?>
    </div>

     
        <!-- Vamos a dejar este de muestra.
    <div id="questions" class="tabcontent">
        <h2>Preguntas por Categoría</h2>
        include plugin_dir_path(__FILE__) . 'questions.php';
    </div> -->
    <!-- End Comment -->
</div>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Open the first tab by default
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.tab button').click();
    });
</script>

</body>
</html>
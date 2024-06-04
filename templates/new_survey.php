<?php 





?>

<div class="grid container pe-4">
    <h1>
        <?php 
            if(isset($task) && $task){
                echo $task . ' Formulario';
            }
        ?>
    </h1>
    <div class="grid">
        <div class="row mt-3">
            <h4>Datos de la Encuesta</h4>
        </div>
        <!-- errores -->
        <div class="row">
            <div class="mt-3">
                <?php
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        if (isset($errorCategory) && $errorCategory) {
                            echo '<div class="mb-4">';
                            echo '<span class="alert alert-danger" role="alert">Error: ' . $errorCategory . '</span>';
                            echo '</div>';
                        } else if (isset($errorSurvey) && $errorSurvey) {
                            echo '<div class="mb-4">';
                            echo '<span class="alert alert-danger" role="alert">Error: ' . $errorSurvey . '</span><br>';
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        </div>
        <!-- Datos de la encuesta -->
        <!-- Categora padre -->
        <div class="container mt-1">
            <div class="row">
                <div class="col">
                    <h5>Categoría</h5>
                    <hr>
                </div>
            </div>
            <div id="errorCategory" class="alert alert-danger d-none"></div>
            <form id="categoryForm" class="row g-3" method="post">
                <div class="col-md-12">
                    <label for="create_survey_category" class="form-label">Categoría</label>
                    <input required type="text" class="form-control" id="create_survey_category" name="create_survey_category"
                        placeholder="Categoria de encuesta">
                    <small class="text-muted">Ingresa el nombre de la Categoría o elige una de la lista.</small>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" name="submit-category">Crear Categoría</button>
                </div>
            </form>
        </div>

        <div class="container mt-3">
            <form id="surveyForm" class="row g-3" method="post">
                <!-- Nombre de la encuesta y categoría -->
                <div class="col-md-12">
                    <div class="row mb-4">
                        <div class="col">
                            <h5>Encuesta</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="survey_name" class="form-label">Nombre</label>
                            <input required type="text" class="form-control" id="survey_name" name="survey_name"
                                placeholder="Encuesta #1">
                        </div>
                        <div class="col-md-4">
                            <label for="survey_category" class="form-label">Categoría</label>
                            <select id="survey_category" name="survey_category"
                                class="form-select form-select-xl survey_select" aria-label="Large select" required>
                                <option selected disabled>Elegir</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo esc_attr($category->id); ?>">
                                    <?php echo esc_html($category->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Descripción de la encuesta -->
                <div class="col-md-12">
                    <div class="row mt-3">
                        <div class="col">
                            <label for="survey_description" class="form-label">Descripción de la Encuesta</label>
                            <textarea class="form-control textarea-scanner" name="survey_description"
                                id="survey_description" style="height: 100px;"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Botón de envío -->
                <div class="col-md-12">
                    <div class="row mt-4">
                        <div class="col d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" name="submit-survey">Crear
                                Cuestionario</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>


<div class="row mt-3">
    <div class="row ms-1">
        Agrega las Subcategorías una a la vez
    </div>
    <div class="row mt-3">
        <table id="dinamicList">
            <tr>
                <td scope="row" class="col">
                    <input type="text" name="name[]" id="name_subcategory" class="form-control name_list ms-1" placeholder="Ej: Espiritual">
                </td>
                <td scope="row" class="col px-2">
                    <button name="add" id="add" class="btn btn-success">Agregar</button>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <hr class="my-4 my-sm">
    <h5>Agregar las preguntas</h5>
    <hr class="my-4">
</div>
<div class="row">
    <div class="errors">
        <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // if (isset($errorCategory) && $errorCategory) {
                //     echo '<span>Error: ' . $errorCategory . '</span>';
                // } elseif (isset($errorSurvey) && $errorSurvey) {
                //     echo '<span>Error: ' . $errorSurvey . '</span>';
                // }
            }
        ?>
    </div>
</div>
<div class="row form-group">
    <div class="col">
    <div class="col"></div>
</div>
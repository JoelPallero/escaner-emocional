jQuery(document).ready(function($){
   let counter = 1;

   // Prevenir el submit del formulario al presionar Enter
   $('#new-form').on('keypress', function(event) {
      if (event.keyCode === 13) {
          event.preventDefault();
      }
   });

   $('#add').click(function (){
      let lastInput = $('#dinamicList tr:first-child input');
      let inputValue = lastInput.val().trim();
      
      if(inputValue === '') {
          alert('No puede haber preguntas vacías. Por favor, complete la pregunta y luego presione el botón correspondiente.');
          return false;
      }
      
       
      $('#dinamicList').append(
         '<tr id="row'+counter+'" class="row mt-1">' +
             '<td scope="row" class="col-11">' +
                 '<input value="'+inputValue+'" type="text" name="name[]" id="name_subcategory" class="form-control name_list ms-1" placeholder="Pregunta '+counter+'">' +
             '</td>' +
             '<td scope="row" class="col-1">' +
                 '<button name="remove" id="row_'+counter+'" class="btn btn-danger remove-btn">Borrar</button>' +
             '</td>'+
         '</tr>'
      );
     
      counter++;
      lastInput.val('');
      return false;
   });

   // Event delegation for dynamically created elements
   $(document).on('click', '.remove-btn', function(){
      let button_id = $(this).attr("id");
      $('#'+button_id).closest('tr').remove();
   });


   /* Category form update */
   $('#categoryForm').on('submit', function(event) {
        event.preventDefault(); // Evitar que el formulario se envíe normalmente

        const formData = $(this).serialize(); // Serializar los datos del formulario

        $.ajax({
            url: ajaxurl, // URL para enviar la petición AJAX en WordPress
            type: 'POST',
            data: {
                action: 'create_category', // Nombre de la acción para WordPress
                formData: formData // Los datos del formulario serializados
            },
            success: function(response) {
                if (response.success) {
                    $('#survey_category').append('<option value="' + response.data.id + '">' + response.data.name + '</option>');
                    $('#create_survey_category').val('');
                } else {
                    $('#errorCategory').removeClass('d-none').text(response.data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });
});
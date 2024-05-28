jQuery(document).ready(function($){
   const newSurvey = document.getElementById('newSurvey');

   $('#newSurvey').click(function(){
      $('#newFormModal').modal("show");
   })

   $('#submit-survey').click(function(e){
      e.preventDefault();
   })

});
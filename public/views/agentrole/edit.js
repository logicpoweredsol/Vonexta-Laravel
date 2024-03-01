$(function () {
    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
    $(".select3").select2({
        minimumResultsForSearch: Infinity
      });
     
});




$('#allowe_profiles').on('switchChange.bootstrapSwitch', function(event, state) {
    if (this.checked) { 
        $("#allowedd_profiles").prop('disabled',  true );
    } else {
        $("#allowedd_profiles").prop('disabled', false);
    }
});

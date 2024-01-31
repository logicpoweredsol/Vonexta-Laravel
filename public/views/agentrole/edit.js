$(function () {
    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
});




$('#allowed_compaigns').on('switchChange.bootstrapSwitch', function(event, state) {
    if (this.checked) { 
        $("#all_comp_select").prop('disabled',  true );
    } else {
        $("#all_comp_select").prop('disabled', false);
    }
});

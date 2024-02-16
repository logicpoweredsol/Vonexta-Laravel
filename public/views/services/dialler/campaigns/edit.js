function speed_calculate(id){

    var type = $("#"+id+" option:selected").text();


    // var type  = $("#"+id).val();
 

    if(type == 'Predictive' || type == 'Smart Predictive'){
        var html = '';
        for (var i = 1; i <= 5; i++) {
            html += '<option value="' + i + '">' + i + '</option>';
        }  
        $("#speed_div").removeClass('d-none');
    }else if(type == 'Agent Dial Next'){
        $("#speed_div").addClass('d-none');
    }else if(type == 'Auto Dial Next'){
        var html = '';
        $("#speed_div").removeClass('d-none');
        var html = '';
        for (var i = 5; i <= 30; i+=5) {
            html += '<option value="' + i + '">' + i + '</option>';
        }
    }
    $("#speed").html(html);

}

$(document).ready(function()
{

    var showPagination =true;
    var showPagination2 =true;

    if ($('#Compaign-table').find('tbody tr').length <= 10) {
        showPagination = false;  // If records are 10 or less, hide pagination
    }

    $('#Compaign-table').DataTable({
        "paging": showPagination,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });

   

});




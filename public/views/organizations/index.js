$(document).ready(function(){
    var showPagination =true;
    var showPagination2 =true;

    if ($('#organizationsDT').find('tbody tr').length <= 10) {
        showPagination = false;  // If records are 10 or less, hide pagination
    }
    $('#organizationsDT').DataTable({
        "paging": showPagination,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });
    
});


function show_add_organsization()
{
    $("#add-organization").modal('show');
}
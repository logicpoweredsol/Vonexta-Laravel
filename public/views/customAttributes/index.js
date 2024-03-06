var showPagination =true;
var showPagination2 =true;

if ($('#usersDT').find('tbody tr').length <= 10) {
    showPagination = false;  // If records are 10 or less, hide pagination
}

$('#usersDT').DataTable({
    "paging": showPagination,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true
});



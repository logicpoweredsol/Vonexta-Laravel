$(document).ready(function(){
    $('#usersDT').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});




function change_tab(id) {
    var id_array = ['org-user', 'add-org-user', 'edit-org-user'];

    id_array.forEach(element => {
        if (element == id) {
            $("#" + element).removeClass('d-none');
        } else {
            $("#" + element).addClass('d-none');
        }
    });
}









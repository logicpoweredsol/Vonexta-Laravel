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




function success(message) {
    Swal.fire({
      title: "Good job!", // Add the title "Good job!"
      text: message,
      icon: "success",
      timer: 5000,
      showConfirmButton: true,
    });
  }


  function failed(message) {
    Swal.fire({
      title: "Failed job!", 
      text: message,
      icon: "error", // Corrected "warrning" to "error" for the icon type
      timer: 5000,
      showConfirmButton: true,
    });
  }
});



// function add_superAdmin()
// {
//   $("#add-superAdmin").modal('show');
// }


// function ()
// {
//   $("#Edit-superAdmin").modal('show');
// }
  
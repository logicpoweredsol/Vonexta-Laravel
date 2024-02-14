$(document).ready(function(){


    showPagination =true;
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
        "responsive": true,
    });

    $(document).on('click', '.btnDelete', function (){
        let user = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${baseUrl}/administration/users/delete`,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        'user':user
                    },
                    success: function (response) {
                        let icon = response.status ? "success" : "error";
                        let title = response.status ? "Deleted!" : "Error";
                        let text = response.status ? response.message : response.error;
                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon,
                            timer: 2000,
                            showConfirmButton: false,
                            willClose: () => {
                                window.location.reload();
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Error deleting the user",
                            icon: "error",
                            timer: 2000,
                            showConfirmButton: false,
                            willClose: () => {
                                window.location.reload();
                            }
                        });
                    }
                });
            }
        });
    });
});


function show_user_on_administrtion()
{
    $("#administration_user_modal").modal('show');
}

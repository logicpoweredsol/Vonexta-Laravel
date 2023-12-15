$(document).ready(function () {
    var dtConfig = {
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    };
    let ogServicesDT = $('#servicesTable').DataTable(dtConfig);


    $(document).on('click', '.btnRemoveService', function () {
        let parentDiv = $(this).parents('.myService');
        let myServiceId = $(this).attr('data-id');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, remove it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${baseUrl}/organizations/services/delete/${myServiceId}`,
                    type: 'DELETE',
                    data: {
                        _token: csrfToken
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
                        });
                        parentDiv.remove();
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Error deleting service",
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


   



    //Add Service  Model 
    $(document).on('click', '#btnAddService', function () {
        let Service = $('#services').val();
        if (Service === "") {
            Swal.fire({
                title: "Organization Services",
                text: "Please select a service to add.",
                icon: "warning",
                showConfirmButton: true,
            });
            return false;
        }
    
        Swal.fire({
            title: "Please enter the name for your service",

            title: "Please enter the details for your service",
            html: `
                <input id="service_name_field" class="swal2-input" placeholder="Service Name">
                <input id="service_nice_name_field" maxlength="15" class="swal2-input" placeholder="Service Nick Name ">
            `,
            showCancelButton: true,
            confirmButtonText: "Add Service",
            showLoaderOnConfirm: true,
            preConfirm: async (service_name) => {

                // var service_name = $("#service_name").val();

                var serviceName = $('#service_name_field').val();
                var serviceNiceName = $('#service_nice_name_field').val();

                if (serviceName == "" || serviceNiceName == "") {
                    Swal.showValidationMessage("Service name and nice name are required");
                    return false;
                }


                let services_type = $('#services_type').val();
                $.ajax({
                    url:  `${baseUrl}/organizations/services/get_service_type`,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        'services_type': services_type,
                    },
                    success: function (response) {
                    var service_id = response['id'];
                    var conParams = response['connection_parameters'];
                    var conParamsHTML = "";
                    conParams = JSON.parse(conParams);
                    $.each(conParams, function (key, value){
                        if(key!=='type'){
                            conParamsHTML += `
                            <div class="form-group row connParamsRow">
                                <label for="${key}" class="col-sm-12 col-md-4 col-lg-4 col-form-label">${key}</label>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <input type="text" class="form-control" required  id="${key}" name="param_keys[${key}]" value="${value}" placeholder="Parameter Value">
                                </div>
                            </div>
                        `;
                        }
                    })
                    $('#connectionParams').html(conParamsHTML);

                    $("#add_serive_name").val(serviceName);
                    $("#add_serive_Nickname").val(serviceNiceName);
                    $('#add_service_type').val(service_id);
                    // $("#add_org_id").val($("#orgId").val());
                    
                    $('#modalConnectionParameters').modal('show');

                    },
                    error: function (xhr, status, error) {},
                });
            }
        });
    });


    //Add Save Button
    $(document).on('click', '#btnAddConnectionParameters', function (){
        // Disable the button to prevent multiple clicks
        $("#btnAddConnectionParameters").prop('disabled', true);
    
        // Validate the form fields
        if(validateForm()) {
            // All fields are filled, proceed with the AJAX request
            let url = `${baseUrl}/organizations/services/add-service`;
            let formData = $('#formConnectionParameters').serialize();
            var serive_name = $("#add_serive_name").val();
            var add_service_type = $("#add_service_type").val();
            var org_id = $("#orgId").val();
            var serviceNiceName = $("#add_serive_Nickname").val();
            // $("#serive_Nickname").val(serviceNiceName);
            
            $.ajax({
                url:  url,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    'formData': formData,
                    'serive_name': serive_name,
                    'add_service_type': add_service_type,
                    'org_id': org_id,
                    'serviceNiceName':serviceNiceName
                },
                success: function (response) {
                    if(response.status){
                        Swal.fire({
                            icon: 'success',
                            title: 'Service added',
                            text: 'Service added successfully',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#formConnectionParameters')[0].reset();
                                $('#modalConnectionParameters').modal('hide');
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.',
                        });
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error
                },
            });
        } else {
            $("#btnAddConnectionParameters").prop('disabled', false);
        }
    });


    // Edit Service modal
    $(document).on('click', '.btnEditService', function (){
        let myServiceId = $(this).attr('data-id');
        $.ajax({
            url: `${baseUrl}/organizations/services/edit/${myServiceId}`,
            type: 'POST',
            data: {
                _token: csrfToken
            },
            success: function (response) {
                $('.connParamsRow').remove();
                $('#ogService_id').val(response.ogService.id);
                $('#edit_serive_name').val(response.ogService.service_name);
                $('#edit_serive_Nickname').val(response.ogService.service_nick_name);
                let conParamsHTML = "";
                var conParams = JSON.parse(response.connection_parameters);
                var ogServiceConParams = JSON.parse(response.ogService.connection_parameters);
                $.each(conParams, function (key, value){
                    if(key!=='type'){
                        conParamsHTML += `
                            <div class="form-group row connParamsRow">
                                <label for="${key}" class="col-sm-12 col-md-4 col-lg-4 col-form-label">${key}</label>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <input type="text" class="form-control" id="${key}" name="param_keys[${key}]" value="${ogServiceConParams[key] != null ? ogServiceConParams[key] : value}" placeholder="Parameter Value">
                                </div>
                            </div>
                        `;
                    }
                });
                $('#editOgServiceBody').html(conParamsHTML);
                $('#modalEditOgService').modal('show');
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: "Error",
                    text: "Error getting service",
                    icon: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });


    // edit Save BUtton
    $(document).on('click', '#btnUpdateOgService', function (){
        // Disable the button to prevent multiple clicks
        $("#btnUpdateOgService").prop('disabled', true);
    
        // Validate the form fields
        if(updatevalidateForm()) {
            // All fields are filled, proceed with the AJAX request
            let url = `${baseUrl}/organizations/services/update-service`;
            let formData = $('#formEditOgService').serialize();

            var ogService_id = $('#ogService_id').val();
            var edit_service_name =  $('#edit_serive_name').val();
            var edit_serive_Nickname =  $('#edit_serive_Nickname').val();
    
            $.ajax({
                url:  url,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    'formData': formData,
                    'id':ogService_id,
                    'serive_name':edit_service_name,
                    'serviceNiceName':edit_serive_Nickname
                },
                success: function (response) {
                    if(response.status){
                        Swal.fire({
                            icon: 'success',
                            title: 'Service Update',
                            text: 'Service Update successfully',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#formEditOgService')[0].reset();
                                $('#modalEditOgService').modal('hide');
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again.',
                        });
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error
                },
            });
        } else {
            $("#btnUpdateOgService").prop('disabled', false);
        }
    });




    

    



   
    // $(document).on('click', '.btnRemoveParameter', function () {
    //     // Remove the current parameter row
    //     let thisParam = $(this).closest('.newParam');
    //     thisParam.remove();
    //     updateButtonVisibility();
    // });




   
    
    // Function to validate the form fields
   


    



    function validateForm() {

        var isValid = true;
    
        $('#formConnectionParameters [required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                // Optionally, you can add additional logic to highlight the invalid fields
                $(this).addClass('is-invalid');
            } else {
                // Remove any previous validation classes
                $(this).removeClass('is-invalid');
            }
        });
    
        return isValid;
    }


    function updatevalidateForm() {
        var isValid = true;
        // Get the values of specific input fields using jQuery
        var edit_serive_name = $('#edit_serive_name').val();
        var edit_serive_Nickname = $('#edit_serive_Nickname').val();


    
        // Check if the specific input fields are empty
        if (!edit_serive_name) {
            // Optionally, you can add additional logic to highlight the invalid fields
            $('#edit_serive_name').addClass('is-invalid');
            isValid =  false;
        } else {
            // Remove any previous validation classes
            $('#edit_serive_name').removeClass('is-invalid');
        }


        if (!edit_serive_Nickname) {
            // Optionally, you can add additional logic to highlight the invalid fields
            $('#edit_serive_Nickname').addClass('is-invalid');
            isValid =  false;
        } else {
            // Remove any previous validation classes
            $('#edit_serive_Nickname').removeClass('is-invalid');
        }


    
        // Get the editOgServiceBody div using jQuery
        var editOgServiceBodyDiv = $('#editOgServiceBody');
    
        // Get all input elements within the div
        var inputElements = editOgServiceBodyDiv.find('input');
    
       
        // Loop through each input element and perform your check
        inputElements.each(function () {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                // Remove any previous validation classes
                $(this).removeClass('is-invalid');
            }
        });
    
        return isValid;
    }

    

    
});





$(document).ready(function () {
    var service_name_text = "";

    // var dtConfig = {
    //     "paging": true,
    //     "lengthChange": true,
    //     "searching": true,
    //     "ordering": true,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    // };
    

    // let ogServicesDT = $('#servicesTable').DataTable(dtConfig);
    if ($('#servicesTable').find('tbody tr').length <= 10) {
        showPagination2 = false;
    }

    $('#servicesTable').DataTable({
        "paging": showPagination2,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });


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

        let Service = $('#services_type').val();
        
        if (Service === "" || Service === "" ) {
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
                    service_name_text =  await  getSelectedOptionText();
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
    $(document).on('click', '#btnAddConnectionParameters',async  function (){
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
            service_type_name = false;
            responce_status = false;

            if(service_name_text.includes("Dialer")){
                service_type_name = true;
                responce_status  = await ceck_service_detail(formData);
            }




            if(service_type_name ===  responce_status ){
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
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: responce_status,
                });
                $("#btnAddConnectionParameters").prop('disabled', false);
            }

            // $.ajax({
            //     url:  url,
            //     method: 'POST',
            //     headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            //     data: {
            //         'formData': formData,
            //         'serive_name': serive_name,
            //         'add_service_type': add_service_type,
            //         'org_id': org_id,
            //         'serviceNiceName':serviceNiceName
            //     },
            //     success: function (response) {
            //         if(response.status){
            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Service added',
            //                 text: 'Service added successfully',
            //             }).then((result) => {
            //                 if (result.isConfirmed) {
            //                     $('#formConnectionParameters')[0].reset();
            //                     $('#modalConnectionParameters').modal('hide');
            //                     window.location.reload();
            //                 }
            //             });
            //         } else {
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Error',
            //                 text: 'Something went wrong. Please try again.',
            //             });
            //         }
            //     },
            //     error: function (xhr, status, error) {
            //         // Handle error
            //     },
            // });

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
                $("#service_type").val(response.ogService.service.name);
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
    $(document).on('click', '#btnUpdateOgService', async function (){
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

            var service_type = $('#service_type').val();

            service_type_name = false;
            responce_status = false;


            if(service_type.includes("Dialer")){
                service_type_name = true;
                responce_status  = await ceck_service_detail(formData,'edit');
            }


            if(service_type_name ===  responce_status ){
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
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.',
                });
                $("#btnUpdateOgService").prop('disabled', false);
            }
           

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




    async function ceck_service_detail(formData,action){

        if(action !== 'undefined'){
            var action = action;
        }else{
            var action = "";
        }
       
        var status = false;
        var params = new URLSearchParams(formData);
        // Create an empty object to store the key-value pairs
        var keyValuePairs = {};

        // Iterate over each pair in the URLSearchParams and append it to the object
        params.forEach((value, key) => {
        keyValuePairs[key] = value;
        });    

        var serverUrl = keyValuePairs['param_keys[server_url]'];
        var apiUser = keyValuePairs['param_keys[api_user]'];
        var apiPass = keyValuePairs['param_keys[api_pass]'];

        let url = `${baseUrl}/organizations/services/ceck_service_detail`;

        await  $.ajax({
            url: `${url}`,
            type: 'POST',
            data: {
                'serverUrl':serverUrl,
                'apiUser':apiUser,
                'apiPass':apiPass,
                'action':action,
                _token: csrfToken
            },
            success: function (response) {
                if(response == 1){
                    status = true;
                }else{
                    status = response;
                }

            },
            error: function () {
            }
        });

        return status;
    }



    async function getSelectedOptionText() {
        var selectElement = document.getElementById("services_type");
        var selectedIndex = selectElement.selectedIndex;
      
        if (selectedIndex !== -1) {
          var selectedOptionText = selectElement.options[selectedIndex].text;
            return selectedOptionText;
        }
      }


    //   async function ceck_service_detail(formData){
    //             var status = false;
    //             var params = new URLSearchParams(formData);
    //             // Create an empty object to store the key-value pairs
    //             var keyValuePairs = {};
        
    //             // Iterate over each pair in the URLSearchParams and append it to the object
    //             params.forEach((value, key) => {
    //             keyValuePairs[key] = value;
    //             });
        
    //             var apiUser = keyValuePairs['param_keys[api_user]'];
    //             var apiPass = keyValuePairs['param_keys[api_pass]'];
    //             var Action = 'GetAllUsers';
    //             var session_user = apiUser;
    //             var responsetype = 'json';
    //             $.ajax({
    //                 url: `https://vn21.vonexta.com/APIv2/Users/API.php`,
    //                 type: 'POST',
    //                 data: {
    //                     'apiUser':apiUser,
    //                     'apiPass':apiPass,
    //                     'Action':Action,
    //                     'session_user':session_user,
    //                     'responsetype':responsetype,
    //                     _token: csrfToken
    //                 },
    //                 success: function (response) {
        
        
    //                     console.log(response);
        
    //                     // var responseData = JSON.parse(response);
    //                     // var resultStatus = responseData.result;
    //                     // if(resultStatus == 'success'){
    //                     //     status = true;
    //                     // }
    //                 },
    //                 error: function (xhr, status, error) {
    //                 }
    //             });
        
        
    //             return status;
    //   }

    
});


function impersonate_modal(id)
{
    $('#user_password_box').addClass('d-none');
    $("#org_user_id").val(id);
    $("#Impersonate-Modal").modal('show');
}




function send_impersonation_email()
{
    var org_user_id = $("#org_user_id").val();
    $.ajax({
        url: `${baseUrl}/organizations/user/send-email`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            "user":org_user_id
        },
        success: function(response) {
            if(response){
                $('#user_password_box').removeClass('d-none');
                $('#user_email_box').addClass('d-none');
            }
           
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}





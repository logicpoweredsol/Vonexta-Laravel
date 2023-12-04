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
                $('#service_name').val(response.ogService.service_name);
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
                $('#editOgServiceBody').append(conParamsHTML);
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
    $(document).on('click', '#btnAddService', function (){
        let newService = $('#services').val();
        if(newService==""){
            Swal.fire({
                title: "Organization Services",
                text: "Please select service to add.",
                icon: "warning",
                // timer: 2000,
                showConfirmButton: true,
            });
            return false;
        }
        Swal.fire({
            title: "Please enter name for your service",
            input: "text",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Add Service",
            showLoaderOnConfirm: true,
            preConfirm: async (serviceName) => {
                try {
                    const formData = new FormData();
                    formData.append('service_id', newService);
                    formData.append('service_name', serviceName);
                    formData.append('_token', csrfToken);
                    formData.append('organization_id', $('#orgId').val());
                    const url = `${baseUrl}/organizations/services/add`;
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData,
                    });
                    if (!response.ok) {
                        return Swal.showValidationMessage(`Something went wrong, please try again.`);
                    }
                    return response.json();
                } catch (error) {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value && result.value.data && result.value.data.id) {
                    let osId = result.value.data.id;
                    let conParams = result.value.data.connection_parameters;
                    let conParamsHTML = "";
                    conParams = JSON.parse(conParams);
                    $.each(conParams, function (key, value){
                        if(key!=='type'){
                            conParamsHTML += `
                            <div class="form-group row connParamsRow">
                                <label for="${key}" class="col-sm-12 col-md-4 col-lg-4 col-form-label">${key}</label>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <input type="text" class="form-control" id="${key}" name="param_keys[${key}]" value="${value}" placeholder="Parameter Value">
                                </div>
                            </div>
                        `;
                        }
                    });
                    $('#connectionParams').append(conParamsHTML);
                    $('#os_id').val(osId);
                    $('#modalConnectionParameters').modal('show');
                    return false;
                }else{
                    throw new Error("Something went wrong.");
                }
            }
        }).catch((error) => {
            // Handle fetch error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
            });
        });;
    });
    // $(document).on('click', '.btnAddParameter', function (){
    //    let newParamHtml = `<div class="form-group row newParam">
    //         <div class="col-sm-5 col-md-5 col-lg-5">
    //             <input type="text" class="form-control" name="param_keys[]" placeholder="Enter key">
    //         </div>
    //         <div class="col-sm-5 col-md-5 col-lg-5">
    //             <input type="text" class="form-control" name="param_values[]" placeholder="Enter value">
    //         </div>
    //         <div class="col-sm-2 col-md-2 col-lg-2">
    //             <button type="button" class="btn btn-primary btn-md btnAddParameter"><i class="fas fa-plus"></i></button>
    //             <button type="button" class="btn btn-danger btn-md btnRemoveParameter" style="display: none;"><i class="fas fa-trash"></i></button>
    //         </div>
    //    </div>`;
    //    $('#connectionParams').append(newParamHtml);
    //     updateButtonVisibility();
    // });
    $(document).on('click', '.btnRemoveParameter', function () {
        // Remove the current parameter row
        let thisParam = $(this).closest('.newParam');
        thisParam.remove();
        updateButtonVisibility();
    });
    $(document).on('click', '#btnAddConnectionParameters', function (){
        let url = `${baseUrl}/organizations/services/update_connection_parameters/${$('#os_id').val()}`;
        let formData = $('#formConnectionParameters').serialize();
        $.ajax({
            url: url,
            type: 'PUT',
            data: formData,
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
               }else{
                   Swal.fire({
                       icon: 'error',
                       title: 'Error',
                       text: 'Something went wrong. Please try again.',
                   });
               }
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.',
                });
            }
        });
    });
    $(document).on('click', '#btnUpdateOgService', function (){
        let url = `${baseUrl}/organizations/services/update_connection_parameters/${$('#ogService_id').val()}`;
        let formData = $('#formEditOgService').serialize();
        $.ajax({
            url: url,
            type: 'PUT',
            data: formData,
            success: function (response) {
                if(response.status){
                    Swal.fire({
                        icon: 'success',
                        title: 'Service updated',
                        text: 'Service updated successfully',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#formEditOgService')[0].reset();
                            $('#modalEditOgService').modal('hide');
                            window.location.reload();
                        }
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.',
                });
            }
        });
    });
});

// const updateButtonVisibility = () => {
//     // Iterate over each .newParam element
//     $('#connectionParams .newParam').each(function(index, element) {
//         let btnRemove = $(element).find('.btnRemoveParameter');
//         let btnAdd = $(element).find('.btnAddParameter');
//
//         // Show btnRemove and hide btnAdd for all elements except the last one
//         if (index !== $('#connectionParams .newParam').length - 1) {
//             btnRemove.show();
//             btnAdd.hide();
//         } else {
//             // Show btnAdd and hide btnRemove for the last element
//             btnRemove.hide();
//             btnAdd.show();
//         }
//     });
//
//     // Show or hide btnAddParameter in the authType row based on the existence of .newParam children
//     if ($('#connectionParams .newParam').length > 0) {
//         $('.authType .btnAddParameter').hide();
//     } else {
//         $('.authType .btnAddParameter').show();
//     }
// };

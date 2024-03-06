$(document).ready(function(){
 
    $("[data-bootstrap-switch]").bootstrapSwitch();   

});
$(function() {
    // Initialize the ionRangeSlider
    $('#inbound_calls_limit').ionRangeSlider({
        min: 1,
        max: 1000,
        // from    : 0,
        type: 'single',
        step: 1,
        prettify: false,
        hasGrid: true
    });
    $("#max_inbound_calls1").ionRangeSlider({
        min: 1,
        max: 1000,
        // from    : 0,
        type: 'single',
        step: 1,
        prettify: false,
        hasGrid: true

    });

});


  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  
$(document).on('click','#Modal2',function(){
    $('#NewModal').modal('show');
});




function add_row() {
    var originalSelect = $('#email_1');
    var clonedSelect = originalSelect.clone();

    var f = 0;
    var row_number = $(".add_row tr").length;

    var email = $("#email_" + row_number).val();
    var extension = $("#extension_" + row_number).val();
    var full_name = $("#full_name_" + row_number).val();
    var active = $("#active_" + row_number).val();

    if (email === '' || email === null) {
        $("#email_" + row_number).css('border', '1px solid red');
        f = 1;
    }

    if (extension === '' || extension === null) {
        $("#extension_" + row_number).css('border', '1px solid red');
        f = 1;
    }
    if (full_name === '' || full_name === null) {
        $("#full_name_" + row_number).css('border', '1px solid red');
        f = 1;
    }
    if (active === '' || active === null) {
        $("#active_" + row_number).css('border', '1px solid red');
        f = 1;
    }

    if (f === 0) {
        row_number = row_number + 1;
        var html = `<tr id=${row_number}>
                        <td>
                            <select class="form-control select2" name="email[]" id="email_${row_number}" style="width: 100%;">`;
                                clonedSelect.find('option').each(function(index, option) {
                                    var optionValue = $(option).val();
                                    var optionText = $(option).text();
                                    if(optionValue == email ){
                                        html += `<option selected value="${optionValue}">${optionText}</option>`;
                                    }else{
                                        html += `<option value="${optionValue}">${optionText}</option>`;
                                    }
                                   
                                });

        html += `</select>
                        </td>
                        <td>
                            <input type="text" class="form-control" onkeyup="allow_only_number(this.id);" onchange="check_previous_extension(this.id);" name="extension[]" id="extension_${row_number}">
                            <span style="color:red" id="extension-error-${row_number}"></span>
                            <span style="color:green" id="extension-success-${row_number}"></span>
                        </td>
                        <td><input type="text" class="form-control" name="full_name[]" id="full_name_${row_number}"></td>
                        <td>
                            <select class="form-control" required name="active[]" id="active_${row_number}">
                                <option value="1">Active</option>
                                <option value="0">No Active</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" onclick="add_row();" class="btn btn-success btn-sm">+</button>
                            <button type="button" onclick="remove_row(${row_number});" class="btn btn-danger btn-sm">-</button>
                        </td>
                    </tr>`;

        $(".add_row").append(html);

        $("#email_" + (row_number-1)).css('border', '1px solid #F9FAFB');
        $("#extension_" + (row_number-1)).css('border', '1px solid #F9FAFB');
        $("#full_name_" + (row_number-1)).css('border', '1px solid #F9FAFB');
        $("#active_" + (row_number-1)).css('border', '1px solid #F9FAFB');

    }
}





function remove_row(row_number){
    $("#" +row_number).remove();

}


// check extension 
function check_previous_extension(id){

    var organization_servicesID = $('#organization_servicesID').val();

    var extension = $("#" + id).val();


    var previous_extension_array = id.split('_');
    var pre_num = previous_extension_array[1];

    var previous_extension_val = $("#extension_"+(pre_num-1)).val();


    $.ajax({
         url:  `${baseUrl}/services/dialer/agents/check-extension`,  // Updated URL
        // url:  `${baseUrl}/check-extension`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'extension': extension,
            'organization_servicesID':organization_servicesID
        },
        success: function(response) {

            $('#extension-error-'+pre_num).html('');
            $('#extension-success-'+pre_num).html('');

            if(response.status == 'success'){

                if(previous_extension_val == extension ){
                    $('#extension-error-'+pre_num).html(response.message)
                }
                $('#extension-success-'+pre_num).html(response.message)
            } 
            if(response.status == 'failed'){
                $('#extension-error-'+pre_num).html(response.message)
            } 

          
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });

}




function copy_agent_skill(){
  var organization_servicesID =   $('#organization_ser_id').val();
  var previous_agent =   $('#other_user').val();
  if(previous_agent != '' && previous_agent != null){

    $.ajax({
         url:  `${baseUrl}/services/dialer/agents/copy-skill`,  // Updated URL
        // url:  `${baseUrl}/check-extension`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'extension': previous_agent,
            'organization_servicesID':organization_servicesID
        },
        success: function(response) {


            // console.log(response);

            for (var p = 0; p < response['call_log_inbounds'].length; p++) {
                var model_group_id = $("#model_group_id_" + response['call_log_inbounds'][p]['group_id']).val();
            
                if (model_group_id == response['call_log_inbounds'][p]['group_id']) {
                    var modelLevelElement = $("#model_level_" + response['call_log_inbounds'][p]['group_id']);
                    modelLevelElement.val(response['call_log_inbounds'][p]['group_grade']).trigger('change');
            
                    if (response['call_log_inbounds'][p]['selected'] == 1) {
                        $("#model_invited_" + response['call_log_inbounds'][p]['group_id']).prop('checked', true).trigger('change');
                    }
                }
            }
            



            for(var q=0; q < response['call_log_outbounds'].length; q++){
                var model_campaign_id = $("#model_profile_id_"+response['call_log_outbounds'][q]['campaign_id']).val();
                if(model_campaign_id == response['call_log_outbounds'][q]['campaign_id'] ){
                    $("#model_prof_level_"+response['call_log_outbounds'][q]['campaign_id']).val(response['call_log_outbounds'][q]['campaign_grade']).trigger('change');
                    

                }
            }



            $('#inbound_calls_limit').data("ionRangeSlider").update({
                from: response['inbound_calls_limit']
              });

            // console.log(response);

    

        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });

  }else{
    $('#inbound_calls_limit').data("ionRangeSlider").update({
        from: 1000
      });

  }


}



function allow_only_number(id) {
    var sanitizedValue = $("#" + id).val().replace(/[^0-9]/g, '');
    $("#" + id).val(sanitizedValue);

    $("#error_extension-error").text('');
    $("#success_extension-success").text('');
    
    if (sanitizedValue.length < 3) {
        $("#extension_val_idd").prop('disabled', true);
        $("#error_extension-error").text('Extension needs to be at least 3 digits');
    }else if(sanitizedValue.length > 8){
        $("#extension_val_idd").prop('disabled', true);
        $("#error_extension-error").text('Extension cannot be more then 8 digits');
    }else {
            $("#extension_val_idd").prop('disabled', false);
            // $("#success_extension-success").text('Extension is correct');
        }
}





function check_extension(id, organization_servicesID ,action) {


    $('#extension-error').html('')
    $('#extension-success').html('')
    var extension = $("#" + id).val();

    if(extension.length >=3 && extension.length < 8 ){
        $.ajax({ 
            url:  `${baseUrl}/services/dialer/agents/check-extension`,  // Updated URL
           // url:  `${baseUrl}/check-extension`,
           method: 'POST',
           headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
           data: {
               'extension': extension,
               'organization_servicesID':organization_servicesID
           },
           success: function(response) {
   
               if(action == 'add-agnet'){
                  
                   if(response.status == 'success'){
                       $('#extension-success').html(response.message)
                   } 
                   if(response.status == 'failed'){
                       $('#extension-error').html(response.message)
                   } 
               }else if(action == 'add-bulk-agnet'){
   
                   $('#extension-error-1').html('')
                   $('#extension-success-1').html('')
   
                   if(response.status == 'success'){
                       $('#extension-success-1').html(response.message)
                   } 
                   if(response.status == 'failed'){
                       $('#extension-error-1').html(response.message)
                   } 
               }
             
           },
           error: function(xhr, status, error) {
               console.error("Error occurred:", error);
           }
       });
    }

   
}



function validateAndNext(){

    var f = 0;

    var organization_user = $("#organization_user").val();
    var user = $("#user").val();
    var agent_role = $("#agent_role").val();
    var agent_name = $("#agent_name").val();
    var status = $("#status").val();

    
    if (organization_user == '' || organization_user == null) {
        $("#organization_user").css('border', '1px solid red');
        f = 1;
    }else{
        $("#organization_user").css('border', '1px solid #EEEEEE');
    }


    if (user == '' || user == null) {
        $("#user").css('border', '1px solid red');
        f = 1;
    }else{
        $("#user").css('border', '1px solid #EEEEEE');
    }

    if (agent_role == '' || agent_role == null) {
        $("#agent_role").css('border', '1px solid red');
        f = 1;
    }else{
        $("#agent_role").css('border', '1px solid #EEEEEE');
    }



    if (agent_name == '' || agent_name == null) {
        $("#agent_name").css('border', '1px solid red');
        f = 1;
    }else{
        $("#agent_name").css('border', '1px solid #EEEEEE');
    }

    if (status == '' || status == null) {
        $("#status").css('border', '1px solid red');
        f = 1;
    }else{
        $("#status").css('border', '1px solid #EEEEEE');
    }



    if(f==0){
        stepper.next();
    }

}



// Bulk adent add function 
function bulk_agent_add(){
 
    var f = 0;

   var other_user = $("#other_user_bulk").val();

   var row_number = $(".add_row tr").length;
   var email = $("#email_" + row_number).val();
   var extension = $("#extension_" + row_number).val();
   var full_name = $("#full_name_" + row_number).val();
   var active = $("#active_" + row_number).val();

   if (email == '' || email == null) {
       $("#email_" + row_number).css('border', '1px solid red');
       f = 1;  // Use a single equal sign for assignment
   }
   if (extension == '' || extension == null) {
       $("#extension_" + row_number).css('border', '1px solid red');
       f = 1;  // Use a single equal sign for assignment
   }
   if (full_name == '' || full_name == null) {
       $("#full_name_" + row_number).css('border', '1px solid red');
       f = 1;  // Use a single equal sign for assignment
   }
   if (active == '' || active == null) {
       $("#active_" + row_number).css('border', '1px solid red');
       f = 1;  // Use a single equal sign for assignment
   }


   if(other_user == '' || other_user == null){
        $("#other_user_bulk").css('border', '1px solid red');
        f= 1;
   }


   if(f==0){
    $('#bulk_agent_save_form').submit();
   }

    

}

// End Bulk adent add function 





function addMoreAgents() {
    $("#add-agent-model").modal('show');
    console.log('Adding more agents...');
    Swal.close();
}

function closeSwal() {
    Swal.close();
}



  // Successful Swal.fire example with auto close
  function success(message) {
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: message,
      confirmButtonText: 'OK',
      timer: 10000, // Auto close after 10 seconds (in milliseconds)
      timerProgressBar: true // Display a progress bar
    });
  }

  // Failed Swal.fire example with auto close
  function error(message) {
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: message,
      confirmButtonText: 'OK',
      timer: 10000, // Auto close after 10 seconds (in milliseconds)
      timerProgressBar: true // Display a progress bar
    });
  }



  function add_more_agent(message) {
    Swal.fire({
        title: 'Success',
        text: message,
        icon: 'success',
        showCloseButton: false,
        showConfirmButton: false,
        timer: 10000, // 10 seconds
        timerProgressBar: true,
        position: 'center',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        footer: `
            <div >
                <button class="swal2-confirm swal2-styled" onclick="addMoreAgents()">Add More Agents</button>
                <button class="swal2-cancel swal2-styled" onclick="closeSwal()">Close</button>
            </div>
        `,
        onOpen: () => {
            Swal.showLoading();
        },
    });
}







function EmergencyLogout(type, organization_servicesID, extension) {
    $.ajax({
        url: `${baseUrl}/services/${type}/agents/emergency-logout`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'extension': extension,
            'organization_servicesID': organization_servicesID
        },
        success: function(response) {
            let errorMessage = "";
            if (typeof response === 'object' && response.hasOwnProperty('result')) {
                errorMessage = response.result;
            } else if (typeof response === 'string') {
                errorMessage = response;
            }
            if (errorMessage.includes("Error")) {
                error('Agent was not logged in'); 
            } else {
                success('Agent has been logged out'); 
            }
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}











var selected_agent = [];

function bulk_button(id) {


    // Toggle the checked status of the checkbox
    var checkbox = $("#" + id);

    // checkbox.prop('checked', !checkbox.prop('checked'));

    // Check if the checkbox is now checked
    if (checkbox.prop('checked')) {
        selected_agent.push(checkbox.val());
    } 
    else {
        // Remove the value from the selected_agent array if it exists
        var index = selected_agent.indexOf(checkbox.val());
        if (index > -1) {
            selected_agent.splice(index, 1);
        }
    }

    if(selected_agent.length > 0){
        $(".check_btn").removeClass('d-none');
    }else{
        $(".check_btn").addClass('d-none');
    }

}



function bulk_button_action(actionType,organization_servicesID) {

    $.ajax({
        url: `${baseUrl}/services/dialer/agents/bulk-action`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'extension': selected_agent,
            'organization_servicesID': organization_servicesID,
            'actionType':actionType
        },
        success: function(response) {
            var agent = '';
            
            // Check if response[1] exists
            if (typeof response[1] !== 'undefined') {
                for (let index = 1; index < response.length; index++) {
                    if (agent == '') {
                        agent = response[index]['value'];
                    } else {
                        agent = agent + ',' + response[index]['value'];
                    }
                }
        
                var message = '';
                if (actionType == 'emergency') {
                    message = 'These agents were logged out:';
                } else if (actionType == 'disable') {
                    message = 'These agents were disable out:';
                } else if (actionType == 'delete') {
                    message = 'These agents were delete out:';
                }
                success(message + agent);
            } else if (actionType == 'emergency') {
                error('Agents are already logged Out');
            } else if (actionType == 'disable') {
                error('Agent are Already disable');
            } else if (actionType == 'delete') {
                error('Agent are Already delete');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });



    // console.warn(url);
    // return url;
}



function add_agent_model(){
    $("#add-agent-model").modal('show');
}

// function show_modal_user(organization_servicesID) {


//     $.ajax({
//         url: `${baseUrl}/services/dialer/agents/create-session-variable`,
//         method: 'POST',
//         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//         data: {
//             'organization_servicesID':organization_servicesID
//         },
//         success: function(response) {
//             open_mmodel();
//         },
//         error: function(xhr, status, error) {
//             console.error("Error occurred:", error);
//         }
//     });


   
// }

function show_adduser_modal()
{
   $("#add_organization_user").modal('show');
}




// Add User on Add Agent Model 
function save_systemUsersForm() {
    var formData = $('#systemUsersForm').serialize();

    var html = '';
    $.ajax({
        url: `${baseUrl}/administration/users/store_user_by_agent_side`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: formData,
        success: function (response) {
            if(response['status'] == 'success'){
                $("#add_organization_user").modal('hide');
                success('User added successfully');
                html = ` <option selected value="${response['data']['id']}" >${response['data']['name']}</option>`
                $("#organization_user").append(html);
            }else if(response['status'] == 'validator-fail'){

                var error = "";
                for (let key in response['errors']) {
                    if (response['errors'].hasOwnProperty(key)) {
                        error += response['errors'][key][0]+'\n';
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error,
                    confirmButtonText: 'OK',
                    timer: 10000, // Auto close after 10 seconds (in milliseconds)
                    timerProgressBar: true // Display a progress bar
                  });

                // error(error);
            }else{
                    error('Something seems to be going wrong');
            }  
            
        },
        error: function (xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}

// Edn Add User on Add Agent Model 






// // start Add Agent Model Skill data fetch 
// function on_inbound_skills_agent(organization_servicesID)
// {
//     $.ajax({
//         url:  `${baseUrl}/services/dialer/agents/inbound_Skills_on_add_agent`,
//        method: 'POST',
//        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//        data: {
          
//            'organization_servicesID':organization_servicesID,
           
//        },
//        success: function(response) {
//         var html = '';

//         for (let index = 0; index < response['group_id'].length; index++) {

//             var group_id = response['group_id'][index];
//             html += `
        
//                 <tr> 
//                     <td> <input type='text' readonly value='${response['group_id'][index]}' name='group_id[]' style='border:none;background: transparent;' /> </td>
//                     <td> <select class="form-control" name='level[]'>`;
        
//                     for (var p = 1; p <= 9; p++) {
//                         html += `<option value='${p}'>${p}</option>`;
//                     }
        
//             html += `</select>
//                         </td>
//                         <td>
//                             <div class="form-check">
//                             <input type="checkbox" name='${group_id}' id='invited_${index}' class="form-check-input switch_class" data-bootstrap-switch data-off-color="danger" data-on-color="success">
//                             </div>
//                         </td>`;
//             html += `</tr>`;
//         }


//         $("#append-html-agent-inblound").html(html);
//         $("[data-bootstrap-switch]").bootstrapSwitch();
//        },
//        error: function(xhr, status, error) {
//            console.error("Error occurred:", error);
//        }
//    });
    
// }

// function on_compaign_skills_agent(organization_servicesID) {
//     $.ajax({
//         url: `${baseUrl}/services/dialer/agents/compaign_Skills_on_add_agent`,
//         method: 'POST',
//         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//         data: {
//             'organization_servicesID': organization_servicesID
//         },
//         success: function (response) {
//              var html = '';

//         for (let index = 0; index < response['campaign_id'].length; index++) {
//             html += `
//                 <tr> 
//                     <td> <input type='text' value='${response['campaign_id'][index]}' name='campaign_id[]' style='border:none;background: transparent;' /> </td>
//                     <td> <select class="form-control" name='campaign_id_level[]'>`;
        
//                     for (var p = 1; p <= 9; p++) {
//                         html += `<option value='${p}'>${p}</option>`;
//                     }
        
//             html += `</select> </td>`;
//             html += `</tr>`;
//         }
        
//         // Now you can use the 'html' variable as needed, for example, appending it to a table
        

//         $("#append-html-agent-compaign").html(html);


//         },
//         error: function (xhr, status, error) {
//             console.error("Error occurred:", error);
//         }
//     });
// }

// END  Add Agent Model Skill data fetch 

function toggal_service(){
    var role = $('#role').val();

    if(role == 'user'){

        
        $('#div_services').addClass('d-none');
    }else{
        $('#div_services').removeClass('d-none');
    }

}


function update_skill_modal(service, organization_servicesID, extension) {
    $.ajax({
        url: `${baseUrl}/services/dialer/agents/update-skills`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'service': service,
            'organization_servicesID': organization_servicesID,
            "extension": extension
        },
        success: function (response) {

            $("#modell_user").text(extension);
            $("#model_organization_services_id").val(organization_servicesID);
            $("#model_User").val(extension);

            var html = '';
            var html1 = '';
            for (let index = 0; index < response['call_log_inbounds'].length; index++) {
                html += `
                <tr>
                    <td>
                        <input type='text' id='group_id_${index}' readonly value='${response['call_log_inbounds'][index]['group_id']}' style='border: none; background: transparent;'/>
                        <span class="adminlet3" onclick="open_inbound_model('${response['call_log_inbounds'][index]['group_id']}');"><i class="fas fa-list"></i></span>
                    </td>
            
                    <td>
                        <select id="group_grade_${index}" class="form-control" onchange='update_skill_inbound(${index});' name='level[]'>`;
                        for (var p = 1; p <= 9; p++) {
                            if (p == response['call_log_inbounds'][index]['group_grade']) {
                                html += `<option selected value='${p}'>${p}</option>`;
                            } else {
                                html += `<option value='${p}'>${p}</option>`;
                            }
                        }
            html += `</select>
                    </td>
                    <td>
                        <div class="form-check">
                            ${response['call_log_inbounds'][index]['selected'] == 0 ?
                            `<input type="checkbox" id='invited_${index}' class="form-check-input inbound-checkbox" onchange='update_skill_inbound(${index});' data-bootstrap-switch data-off-color="danger" data-on-color="success"></input>` :
                            `<input type="checkbox" id='invited_${index}' checked class="form-check-input inbound-checkbox" onchange='update_skill_inbound(${index});' data-bootstrap-switch data-off-color="danger" data-on-color="success"></input>`}
                        </div>
                    </td>
                    <td>
                        <input type='text' readonly value='${response['call_log_inbounds'][index]['calls_today']}' style='border:none;background: transparent;' />
                    </td>
                </tr>`;
            }

            var html1 = '';
            for (let index = 0; index < response['call_log_outbounds'].length; index++) {
                html1 += `
                <tr> 
                    <td>
                        <input type='text' id='profile_id_${index}' readonly value='${response['call_log_outbounds'][index]['campaign_id']}' style='border:none;background: transparent;' />
                        <span class="adminlet3" onclick="open_outbound_model('${response['call_log_outbounds'][index]['campaign_id']}');"><i class="fas fa-list"></i></span>
                    </td>
                    <td>
                        <select id="profile_grade_${index}" class="form-control select2" onchange='update_skill_outbound(${index});' name='level[]'>`;
                        for (var q = 1; q <= 9; q++) {
                            if (q == response['call_log_outbounds'][index]['campaign_grade']) {
                                html1 += `<option selected value='${q}'>${q}</option>`;
                            } else {
                                html1 += `<option value='${q}'>${q}</option>`;
                            }
                        }
                        html1 += `</select>
                    </td>
                    <td>
                        <div class="form-check">
                            <input type='text' readonly value='${response['call_log_outbounds'][index]['calls_today']}' style='border:none;background: transparent;' />
                        </div>
                    </td>
                </tr>`;
            
            }

            $("#Enbound_body").html(html);
            $("#Campaigns-body").html(html1);
            // Show the modal
            $("#update_skill_modal").modal('show');

            $("[data-bootstrap-switch]").bootstrapSwitch();


            $('#max_inbound_calls1').data("ionRangeSlider").update({
                from: response['max_inbound_calls']
              });

          
        },
        error: function (xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}




//Start update skill modal value 

function update_skill_inbound(row_number){

    var organization_services_id = $("#model_organization_services_id").val();
    var extension = $("#model_User").val();

    var group_id = $('#group_id_'+row_number).val();

    var group_grade = $("#group_grade_"+row_number).val();
    var invited = 'NO';
    if ($('#invited_'+row_number).is(':checked')) {
        invited = 'YES';
    } else {
        invited = 'NO';
    }
    
    $.ajax({
        url: `${baseUrl}/services/dialer/agents/update_skill_inbound`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'group_id':group_id,
            'group_grade':group_grade,
            'invited': invited,
            'organization_services_id': organization_services_id,
            'extension':extension
        },
        success: function(response) {

        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });

}

function update_skill_outbound(row_number) {
    var organization_services_id = $("#model_organization_services_id").val();
    var extension = $("#model_User").val();
    var campaign_id = $('#profile_id_' + row_number).val();
    var profile_grade = $("#profile_grade_" + row_number).val();

    $.ajax({
        url: `${baseUrl}/services/dialer/agents/update_skill_outbound`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'campaign_id': campaign_id, // Corrected key name
            'profile_grade': profile_grade,
            'organization_services_id': organization_services_id,
            'extension': extension
        },
        success: function (response) {
            // Handle success if needed
        },
        error: function (xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}


// End update skill modal value 



function update_inblound_call_limit(id){

    var max_inbound_calls = $("#max_inbound_calls").val();

    var organization_services_id = $("#model_organization_services_id").val();
    var extension = $("#model_User").val();





    $.ajax({
        url: `${baseUrl}/services/dialer/agents/update_inblound_call_limit`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'max_inbound_calls':max_inbound_calls,
            'organization_services_id': organization_services_id,
            'extension':extension
        },
        success: function(response) {

        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });


}


// this function is for showing inbound skills on agents list
function open_inbound_model(row_number ) {
    var group_id = $('#model_group_id_' + row_number).val(); // Assuming you have an input with this ID
    var organization_servicesID =   $('#organization_ser_id').val();
    var extension =   $('#other_user').val();

        $.ajax({
            url: `${baseUrl}/services/dialer/agents/get_skill_inbound_level`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                'group_id': group_id,
                'organization_services_id': organization_servicesID,
                'extension': extension
            },
            success: function(response) {
                console.log(response);
    
                $("#skill_name").text(group_id);
    
                var html = '';
    
                for (let index = 0; index < response[0].length; index++) {
                    html += `<tr> 
                                <td>${response[0][index]}</td>
                                <td>${response[1][index]}</td>`;
                                 if(response[3][index] == 0){
                                    html += ` <td>NO</td> `;
                                }else{
                                    html += ` <td>YES</td> `;
                                }
                                
                               html += `<td>${response[2][index]}</td>
                            </tr>`;
                }
    
                $("#detail_modal_body").html(html);
    
            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });
    
        $("#detail_modal").modal('show');
    }


// this function is for showing Outbound skills on agents list

function open_outbound_model(row_number)
{

    var profile_id_ = $('#model_profile_id_'+row_number).val();
    var organization_servicesID = $("#organization_ser_id").val();
    var extension = $("#other_user").val();

    
    $.ajax({
        url: `${baseUrl}/services/dialer/agents/get_skill_outbound_level`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'profile_id_':profile_id_,
            'organization_services_id': organization_servicesID,
            'extension':extension
        },
        success: function(response) {

            $("#skill_name").text(profile_id_);

            var html = '';

        for (let index = 0; index < response[0].length; index++) {
            html += `<tr> 
                        <td>${response[0][index]}</td>
                        <td>${response[1][index]}</td>
                        <td>${response[2][index]}</td>
                    </tr>`;
        }

        $("#Outbound-Skill-body").html(html);

        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });





    $("#Outbound-Skill").modal('show');
}







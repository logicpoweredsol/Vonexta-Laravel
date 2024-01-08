$(document).ready(function(){
    $('#compaignns , #Inbound, #usersDT, #bulk-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });


    $("[data-bootstrap-switch]").bootstrapSwitch();
    // $('.select2').select2();

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



// function add_row() {


   
//     var originalSelect = $('#email_1');

//     var clonedSelect = originalSelect.clone();

//     var f = 0;

//     var row_number = $(".add_row tr").length;

//     var email = $("#email_" + row_number).val();
//     var extension = $("#extension_" + row_number).val();
//     var full_name = $("#full_name_" + row_number).val();
//     var active = $("#active_" + row_number).val();

//     if (email == '' || email == null) {
//         $("#email_" + row_number).css('border', '1px solid red');
//         f = 1;  // Use a single equal sign for assignment
//     }
//     if (extension == '' || extension == null) {
//         $("#extension_" + row_number).css('border', '1px solid red');
//         f = 1;  // Use a single equal sign for assignment
//     }
//     if (full_name == '' || full_name == null) {
//         $("#full_name_" + row_number).css('border', '1px solid red');
//         f = 1;  // Use a single equal sign for assignment
//     }
//     if (active == '' || active == null) {
//         $("#active_" + row_number).css('border', '1px solid red');
//         f = 1;  // Use a single equal sign for assignment
//     }

//     if (f == 0) {
//         row_number = row_number + 1;
//         var html = `<tr id=${row_number}>
//                         <td>

//                         <select class="form-control select2" name="email[]" id="email_${row_number}" style="width: 100%;">`;
//                           clonedSelect.find('option').each(function(index, option) {
                       
//                                 var optionValue = $(option).val();
//                                 var optionText = $(option).text();
//             html +=            '<option value="'+optionValue+'">'+optionText+'</option>';
//                         // console.log("Option " + index + ": Value - " + optionValue + ", Text - " + optionText);
//                     });
        

//         html +=                `</td>
//                         <td><input type="text" class="form-control" onkeyup="allow_only_number(this.id);"  onchange="ceck_previous_extension(this.id);"  name="extension[]" id="extension_${row_number}">
//                             <span style = "color:red" id="extension-error-${row_number}"></span>
//                             <span style = "color:green" id="extension-success-${row_number}"></span>

//                         </td>
//                         <td><input type="text" class="form-control" name="full_name[]" id="full_name_${row_number}"></td>
//                         <td>
//                         <select class="form-control" required name="active[]" id="active_${row_number}">
//                                     <option value="1">Active</option>
//                                     <option value="0">No Active</option>
//                                         </select>
//                         </td>
//                         <td>
//                             <button type="button" onclick="add_row();" class="btn btn-success btn-sm">+</button>
//                             <button type="button"  onclick="remove_row(${row_number});" class="btn btn-danger btn-sm">-</button>
//                         </td>
//                     </tr>`;

//         $(".add_row").append(html);
//     }
// }


function remove_row(row_number){
    $("#" +row_number).remove();

}

function change_tab2(id) {

    var id_array = ['inbound-log', 'outbound-log', 'manual-log','transfer-log'];
    var id = $("#table_log").val();

    id_array.forEach(element => {
        if (element == id) {
            $("." + element).removeClass('d-none');
        } else {
            $("." + element).addClass('d-none');
        }
    });
}


function Change_tab(id) {
    var id_array = ['compaigns', 'inbounds'];

    var id = $("#"+id).val();

    id_array.forEach(element => {
        if (id.includes(element)) {
            $("." + element).removeClass('d-none');
        } else {
            $("." + element).addClass('d-none');
        }
    });
}




// aaaa

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

function copy_agent_detail(organization_servicesID){
  var previous_agent =   $('#other_user').val();

  if(previous_agent != '' && previous_agent != null){

    $.ajax({
         url:  `${baseUrl}/services/dialer/agents/get-extension-detail`,  // Updated URL
        // url:  `${baseUrl}/check-extension`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'extension': previous_agent,
            'organization_servicesID':organization_servicesID
        },
        success: function(response) {

            $("#Sms_number").val(response['data']['mobile_number']);

            if(response['data']['agent_choose_ingroups'] == 1){
                $('#agent_choose_ingroups').prop('checked', true).trigger("change");
            }else{
                $('#agent_choose_ingroups').prop('checked', false).trigger("change");
            }

            if(response['data']['agent_choose_blended'] == 1){
                $('#agent_choose_blended').prop('checked', true).trigger("change");
            }else{
                $('#agent_choose_blended').prop('checked', false).trigger("change");
            }

            if(response['data']['closer_default_blended'] == 1){
                $('#closer_default_blended').prop('checked', true).trigger("change");
            }else{
                $('#closer_default_blended').prop('checked', false).trigger("change");
            }

            if(response['data']['scheduled_callbacks'] == 1){
                $('#scheduled_callbacks').prop('checked', true).trigger("change");
            }else{
                $('#scheduled_callbacks').prop('checked', false).trigger("change");
            }

            if(response['data']['agentonly_callbacks'] == 1){
                $('#agentonly_callbacks').prop('checked', true).trigger("change");
            }else{
                $('#agentonly_callbacks').prop('checked', false).trigger("change");
            }

            if(response['data']['agentcall_manual'] == 1){
                $('#agentcall_manual').prop('checked', true).trigger("change");
            }else{
                $('#agentcall_manual').prop('checked', false).trigger("change");
            }

            if(response['data']['agent_call_log_view_override'] == 'Y'){
                $('#agent_call_log_view_override').prop('checked', true).trigger("change");
            }else{
                $('#agent_call_log_view_override').prop('checked', false).trigger("change");
            }


           

            if(response['data']['max_inbound_calls']){
                $('#max_inbound_calls').data("ionRangeSlider").update({
                    from: response['data']['max_inbound_calls']
                  });
            }

            




        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });

  }


}



function allow_only_number(id) {
    var sanitizedValue = $("#" + id).val().replace(/[^0-9]/g, '');
    $("#" + id).val(sanitizedValue);
}



function check_extension(id, organization_servicesID ,action) {

    var extension = $("#" + id).val();

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
                $('#extension-error').html('')
                $('#extension-success').html('')

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



function validateAndNext(){

    var f = 0;

    var organization_user = $("#organization_user").val();
    var user = $("#user").val();
    var user_group = $("#user_group").val();
    var full_name = $("#full_name").val();
    var active = $("#active").val();

    
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



    if (user_group == '' || user_group == null) {
        $("#user_group").css('border', '1px solid red');
        f = 1;
    }else{
        $("#user_group").css('border', '1px solid #EEEEEE');
    }



    if (full_name == '' || full_name == null) {
        $("#full_name").css('border', '1px solid red');
        f = 1;
    }else{
        $("#full_name").css('border', '1px solid #EEEEEE');
    }

    if (active == '' || active == null) {
        $("#active").css('border', '1px solid red');
        f = 1;
    }else{
        $("#active").css('border', '1px solid #EEEEEE');
    }



    if(f==0){
        stepper.next();
    }

}



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



  function bluck_success(message) {
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
            <div>
                <button class="swal2-confirm swal2-styled" onclick="addMoreAgents()">Add More Agents</button>
                <button class="swal2-cancel swal2-styled" onclick="closeSwal()">Close</button>
            </div>
        `,
        onOpen: () => {
            Swal.showLoading();
        },
    });
}






function show_call_log_tb() {
    var table_log = $("#table_log").val();
    var tb_th = [];

    var All_tf = {
        'inbound-log': ['Call Time', 'Phone Number', 'Contact List', 'Type', 'Source', 'Disposition'],
        'outbound-log': ['Call Date', 'Lead ID', 'Phone Number', 'Campaign', 'Call length', 'Status'],
        'manual-log': ['Call Date', 'Lead ID', 'Phone Number', 'Campaign', 'Call length', 'Status'],
        'transfer-log': ['Transfer Date', 'Lead ID', 'Phone Number', 'Transferred to']
    };

    table_log.forEach(logType => {
        if (All_tf[logType]) {
            tb_th = tb_th.concat(All_tf[logType]);
        }
    });

    var html = '<div class="card ' + table_log + '">';
    html += '<div class="card-header">';
    html += '<h3 class="card-title"><b>' + table_log + ':</b></h3>';
    html += '</div>';
    html += '<div class="card-body">';
    html += '<table class="table table-striped table-hover vonexta-table">';
    html += '<thead>';
    html += '<tr>';
    
    tb_th.forEach(element => {
        html += '<th>' + element + '</th>';
    });
    
    html += '</tr>';
    html += '</thead>';
    html += '</table>';
    html += '</div>';
    html += '</div>';

    $(".call-log-tb").html(html);
    console.log(tb_th);
}





// var Inbound = ['Call Time' , 'Phone Number' , 'Contact List' ,'Type' , 'Source' , 'Disposition'];
// var Outbound = ['Call Date' ,'Lead ID' ,'Phone Number','Compaign','Call length','Status'];
// var Manual = ['Call Date' ,'Lead ID','Phone Number','Compaign','Call length','Status'];
// var Transfers = ['Transfer Date','lead ID', 'Phone Number', 'Transferred to'];



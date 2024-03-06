$(function () {
    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
});

$(function () {
    // Initialize the ionRangeSlider
    $('#inbound_calls_limit').ionRangeSlider({
      min     : 1,
      max     : 1000,
      // from    : 0,
      type    : 'single',
      step    : 1,
      prettify: false,
      hasGrid : true
    });


       $('#inbound_calls_limit').data("ionRangeSlider").update({
      from: $('#inbound_calls_limit').val()
    });

  });




$(document).ready(function(){


    $(".select2").select2({

        minimumResultsForSearch: Infinity
    });

    // $('.adminlet3').tooltip();
    var showPagination =true;
    var showPagination2 =true;

    if ($('#Campaigns').find('tbody tr').length <= 10) {
        showPagination = false;  // If records are 10 or less, hide pagination
    }

    $('#Campaigns').DataTable({
        "paging": showPagination,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });


    if ($('#Enbound').find('tbody tr').length <= 10) {
        showPagination2 = false;  // If records are 10 or less, hide pagination
    }

    $('#Enbound').DataTable({
        "paging": showPagination2,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });



    

    show_call_log_tb();



 //Activatu
    $('#reservation2').daterangepicker({
       
        opens: 'left', // Position of the calendar dropdown
        autoApply: false, // Auto-apply the selected date range
        showApplyButton: true, // Display the Apply button
        showCancelButton: true, // Display the Cancel button
        locale: {
            format: 'MM/DD/YYYY', // Date format
            separator: ' to ', // Separator between start and end date
        },

    }).on('apply.daterangepicker', function(ev, picker) {
       
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        var organization_services_id = $("#organization_services_id").val();
        var extension = $("#User").val();        
        $.ajax({
            url: `${baseUrl}/services/dialer/agents/activity`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                'startDate':startDate,
                'endDate':endDate,
                'extension': extension,
                'organization_services_id': organization_services_id
            },
            success: function(response) {
                var html = "";
            
                if (response['data']['event_time'] != null) {
                    for (var p = 0; p < response['data']['event_time'].length; p++) {
                        var session_length = '';
            
                        // Convert the string to a Date object
                        var originalDate = new Date(response['data']['event_time'][p]);
                        var month = originalDate.getMonth() + 1; // Adding 1 because months are zero-based
                        var day = originalDate.getDate();
                        var year = originalDate.getFullYear();
                        var formattedDate = (month < 10 ? '0' : '') + month + '/' + (day < 10 ? '0' : '') + day + '/' + year;
            
                        // Get the time part as a string
                        var timePart = response['data']['event_time'][p].split(' ')[1];
            
                        // Combine the formatted date and the original time part
                        var formattedDateTime = formattedDate + ' ' + timePart;
            
                        html += "<tr>";
                        html += "<td>" + formattedDateTime + "</td>";
                        html += "<td>" + response['data']['sub_status'][p] + "</td>";
            
                        if ((response['data']['sub_status'][p] == 'LOGOUT' || response['data']['sub_status'][p] == 'FORCE-LOGOUT')) {
                            var logout_time = response['data']['event_time'][p];
            
                            if (response['data']['event_time'][p + 1]) {
                                var login_time = response['data']['event_time'][p + 1];
                                session_length = calculateTimeDifference(logout_time, login_time);
                            } else {
                                session_length = '';
                            }
                        }
            
                        html += "<td>" + session_length + "</td>";
                        html += "<td>" + response['data']['campaign_id'][p] + "</td>";
                        html += "</tr>";
                    }
                }

                var dataTable = $('#active-log').DataTable();
                dataTable.destroy();


                $("#activity-tbody").html(html);

         
                var showPagination4 = true;

                if ($('#active-log tbody tr').length <= 10) {
                    showPagination4 = false;  // If records are 10 or less, hide pagination
                }

                // Initialize DataTable again
                $('#active-log').DataTable({
                    "paging": showPagination4,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true
                });
                
          
                
                
                



            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });

    });



    

    //call Log :
    $('#reservation1').daterangepicker({
       
        opens: 'left', // Position of the calendar dropdown
        autoApply: false, // Auto-apply the selected date range
        showApplyButton: true, // Display the Apply button
        showCancelButton: true, // Display the Cancel button
        locale: {
            format: 'MM/DD/YYYY', // Date format
            separator: ' to ', // Separator between start and end date
        },

    }).on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        var organization_services_id = $("#organization_services_id").val();
        var extension = $("#User").val();

        var  selected_table = $("#table_log").val();
        if(selected_table.length > 0){
            $.ajax({
                url: `${baseUrl}/services/dialer/agents/call_log`,
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    'startDate':startDate,
                    'endDate':endDate,
                    'extension': extension,
                    'organization_services_id': organization_services_id,
                    'selected_table':selected_table
                },
                success: function(response) {
                    var html = "";
                    var  table_log = $("#table_log").val();
                        table_log.forEach(category => {
                            var data = response[category];
                            if (typeof data !== 'undefined' && data['call_date'] !== null) {
                                for (var p = 0; p < data['call_date'].length; p++) {

                                     // Convert the string to a Date object
                                    var originalDate = new Date(data['call_date'][p]);
                                    var month = originalDate.getMonth() + 1; // Adding 1 because months are zero-based
                                    var day = originalDate.getDate();
                                    var year = originalDate.getFullYear();
                                    var formattedDate = (month < 10 ? '0' : '') + month + '/' + (day < 10 ? '0' : '') + day + '/' + year;
                        
                                    // Get the time part as a string
                                    var timePart = data['call_date'][p].split(' ')[1];
                        
                                    // Combine the formatted date and the original time part
                                    var formattedDateTime = formattedDate + ' ' + timePart;


                                    html += "<tr>";
                                    html += "<td>" + formattedDateTime + "</td>";
                                    html += "<td>" + data['phone_number'][p] + "</td>";
                                    html += "<td>" + data['list_id'][p] + "</td>";
                                    html += "<td>" + category + "</td>";
                                    html += "<td>" + data['campaign_id'][p] + "</td>";
                                    html += "<td>" + data['status'][p] + "</td>";

                                    if(category == 'Outbound' ){
                                        html += "<td>" + data['lead_id'][p] + "</td>";
                                    }else{
                                        html += "<td> - </td>";
                                    }
                                    html +="<td>" + data['length_in_sec'][p] + "</td>";
                                  
                                    html += "</tr>";
                                }
                            }
                        });

                        var dataTable = $('#call-logs').DataTable();
                        dataTable.destroy();

                        $("#call_lo_body").html(html);
                        var showPagination4 = true;
    
                        if ($('#call-logs tbody tr').length <= 10) {
                            showPagination4 = false;  // If records are 10 or less, hide pagination
                        }
    
                        // Initialize DataTable again
                        $('#call-logs').DataTable({
                            "paging": showPagination4,
                            "lengthChange": true,
                            "searching": true,
                            "ordering": false,
                            "info": true,
                            "autoWidth": false,
                            "responsive": true
                        });
    
                },

                error: function(xhr, status, error) {
                    console.error("Error occurred:", error);
                }
            });
    
        }else{
            alert('At least one filter to be selected ');
        }

       
    });

    function calculateTimeDifference(time1, time2) {

        // console.log("login time" + time1);
        // console.log("logout time" + time2);

        
        // Parse input times into JavaScript Date objects
        const datetime1 = new Date(time1);
        const datetime2 = new Date(time2);
    
        // Calculate the difference in milliseconds
        const diffInMilliseconds = Math.abs(datetime1 - datetime2);
    
        // Calculate hours, minutes, and seconds
        const hours = Math.floor(diffInMilliseconds / 3600000);
        const minutes = Math.floor((diffInMilliseconds % 3600000) / 60000);
        const seconds = Math.floor((diffInMilliseconds % 60000) / 1000);
    
        // Format the result as 0:00:29
        const formattedTimeDifference = `${hours}:${(minutes < 10 ? '0' : '')}${minutes}:${(seconds < 10 ? '0' : '')}${seconds}`;
    
        return formattedTimeDifference;
    }
    

});


var tb_th = [];
function show_call_log_tb(first) {
    var table_log;
    
    if (first !== undefined && first !== 'undefined') {
        table_log = "Inbound";
    } else {
        table_log = $("#table_log").val();
    }
    var All_tf = {
        'Inbound': ['Call Time', 'Phone Number', 'Contact List', 'Type', 'Source', 'Disposition'],
        'Outbound': ['Contact ID', 'Phone Number','Length'],
        'Manual': ['Contact ID', 'Phone Number',],
        'Transfer': [ 'Contact ID', 'Phone Number', 'Transferred to']
    };

    // Convert the table_log string into an array if needed
    if (typeof table_log === 'string') {
        table_log = table_log.split(',');
    }

    table_log.forEach(logType => {
        if (All_tf[logType]) {
            tb_th = tb_th.concat(All_tf[logType]);
        }
    });


    if(table_log.length == 0 && table_log == ""){
        tb_th = tb_th.concat(All_tf['Inbound']);

        // console.log(tb_th);
    }

    let tb_th_unique = tb_th.filter((value, index, self) => {
        return self.indexOf(value) === index;
    });

    var html = `
        <div class="card ${table_log.join(' ')}">
            <div class="card-header">`;

            if(table_log.length == 0){
                html += `<h3 class="card-title"><b></b></h3>`;
            }else{
                html += ` <h3 class="card-title"><b>${table_log.join(', ')}  Logs </b></h3>`;
            }
               
            html += `</div>
            <div class="card-body">
                <table class=" table table-striped table-hover vonexta-table" id='call-logs'>
                    <thead>
                        <tr>
                            ${tb_th_unique.map(element => `<th>${element}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody id='call_lo_body'></tbody>
                </table>
            </div>
        </div>
    `;

    $(".call-log-tb").html(html);
    
}





$('.inbound-checkbox').on('switchChange.bootstrapSwitch', function(event, state) {
    var idValue = this.id;  // idValue will be 'invited_0', 'invited_1', etc.
   var numericPart = idValue.split('_')[1];  // Extract the numeric part after the underscore
   update_skill_inbound(numericPart);
});


function update_skill_inbound(row_number){
    var group_id = $('#inbound_id_'+row_number).text();
    var group_grade = $("#group_grade_"+row_number).val();
    var invited = 'NO';
    if ($('#invited_'+row_number).is(':checked')) {
        invited = 'YES';
    } else {
        invited = 'NO';
    }
    var organization_services_id = $("#organization_services_id").val();
    var extension = $("#User").val();



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


function update_skill_outbound(row_number){

    var profile_id_ = $('#profile_id_'+row_number).text();
    var campaign_grade = $("#campaign_grade_"+row_number).val();

    var organization_services_id = $("#organization_services_id").val();
    var extension = $("#User").val();



    $.ajax({
        url: `${baseUrl}/services/dialer/agents/update_skill_outbound`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'campaign_id':campaign_id,
            'campaign_grade':campaign_grade,
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


function update_inblound_call_limit(id){

    var inbound_calls_limit = $("#inbound_calls_limit").val();
    var organization_services_id = $("#organization_services_id").val();
    var extension = $("#User").val();



    $.ajax({
        url: `${baseUrl}/services/dialer/agents/update_inblound_call_limit`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'inbound_calls_limit':inbound_calls_limit,
            'organization_services_id': organization_services_id,
            'extension':extension
        },
        success: function(response) {
            console.log(response);

        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });


}






function open_inbound_model(row_number){

    var group_id = $('#inbound_id_'+row_number).text();
    var organization_services_id = $("#organization_services_id").val();
    var extension = $("#User").val();


    $.ajax({
        url: `${baseUrl}/services/dialer/agents/get_skill_inbound_level`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'group_id':group_id,
            'organization_services_id': organization_services_id,
            'extension':extension
        },
        success: function(response) {


            console.log(response);

            $("#skill_name").text(group_id);

            var html = '';

        for (let index = 0; index < response[0].length; index++) {
            html += `<tr> 
                        <td>${response[0][index]}</td>
                        <td>${response[1][index]}</td> `;

                        if(response[3][index] == 0){
                            html += ` <td>NO</td> `;
                        }else{
                            html += ` <td>YES</td> `;
                        }
                       html += ` <td>${response[2][index]}</td>
                       
                        
                    </tr>`;
        }

        $("#detail_modal_body").html(html);



            // var html = '';

            // for (let index = 0; index < response[0].length; index++) {

            //     html  = `<tr> 
            //                 <td> ${response[0][index]} </td>
            //                 <td> </td>
            //                 <td> </td>
            //     </tr>`

            //     const element = array[index];
                
            // }


            // console.log(response);


            // $("#detail_modal_body").html(html);

        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });





    $("#detail_modal").modal('show');
}


function open_outbound_model(row_number) {
    var profile_id_ = $('#profile_id_' + row_number).text();
    var organization_services_id = $("#organization_services_id").val();
    var extension = $("#User").val();

    $.ajax({
        url: `${baseUrl}/services/dialer/agents/get_skill_outbound_level`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'profile_id_': profile_id_,
            'organization_services_id': organization_services_id,
            'extension': extension
        },
        success: function (response) {
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
        error: function (xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });


    $("#Outbound-Skill").modal('show');
}









  
function add_row() {

    var html = '';
    var total_length =$(".customfield-wrap").length ;

    if(total_length < 10){
        html = ` <div class="customfield-wrap" id="row_${total_length+1}">
        <div class="form-group fromgroup">
            <label for="active" class="form-label">Custom Attribute ${total_length+1}</label>
            <input type="text" class="form-control custom-attribute" name="custom_attribute[]"  placeholder="Custom attribute ${total_length+1}">
        </div>
        <div class="additionalfield-wrap">
            <button type="button" onclick="add_row();" class="btn btn-success btn-sm mt-2"> + </button>
            <button type="button" onclick="remove_row(${total_length+1});" class="btn btn-danger btn-sm mt-2"> - </button>
        </div>
    </div>`;
    }

    $("#body_attribute").append(html);
  
 
  }

    function remove_row(action) {
        if(action != "" || action != null){
            $("#row_"+action).remove();
        }
       
    }





    function add_custom_attribute()
    {
       $("#custommmmmm").modal('show');
    }



    $(document).ready(function() {
        // Function to check if the fields are empty
        function checkFields() {
            var apiValue = $('input[name="api"]').val();
            var nameValue = $('input[name="name"]').val();
            
            if (apiValue.trim() !== '') {
                $('#apiError').addClass('d-none');
            } else {
                $('#apiError').removeClass('d-none');
            }
            
            if (nameValue.trim() !== '') {
                $('#nameError').addClass('d-none');
            } else {
                $('#nameError').removeClass('d-none');
            }
            
            if (apiValue.trim() !== '' && nameValue.trim() !== '') {
                $('#submitButton').prop('disabled', false); // Enable the submit button
            } else {
                $('#submitButton').prop('disabled', true); // Disable the submit button
            }
        }
    
        // Call the function on keyup event for both fields
        $('input[name="api"], input[name="name"]').on('keyup', function() {
            checkFields();
        });
    
        // Call the function on page load to set initial state of the submit button
        checkFields();
    });
    
    
    
    function check_previous_api_name(inputId, organization_servicesID, action) {
        $('#api-error').html('');
        $('#api-success').html('');
        
        var extensionField = $("#" + inputId);
        var extension = extensionField.val(); // Retrieve the value of the input field
        
        // Regular expression to match only '-' and '_' characters
        var regex = /^[a-zA-Z0-9_-]+$/;
        
        if (!extension) {
            // If the input is empty, clear any previous error messages and exit
            $('#api-error').html('');
            $('#submitButton').prop('disabled', true);
            return;
        }
        
        if (!regex.test(extension)) {
            // If the input doesn't match the allowed characters, show an error
            $('#api-error').html('Only "-" and "_" characters are allowed.');
            $('#submitButton').prop('disabled', true); // Disable the submit button
            return; 
        } else {
            $('#api-error').html(''); // Clear any previous error messages
            $('#submitButton').prop('disabled', false); // Enable the submit button
        }
        
        $.ajax({
            url: `${baseUrl}/services/dialer/agents/check-previous-api-name`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                'api_name': extension,
                'organization_servicesID': organization_servicesID
            },
            success: function(response) {
                if (action == 'add-apiname') {
                    if (response.status == 'success') {
                        $('#api-success').html(response.message);
                    } 
                    if (response.status == 'failed') {
                        $('#api-error').html(response.message);
                    } 
                }
            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });
    
        // Attach an event listener to the input field to re-validate whenever it changes
        extensionField.on('input', function() {
            var newExtension = $(this).val();
            if (!newExtension) {
                $('#api-error').html(''); 
                $('#submitButton').prop('disabled', true); 
                return;
            }
            if (!regex.test(newExtension)) {
                $('#api-error').html('Only "-" and "_" characters are allowed.');
                $('#submitButton').prop('disabled', true); 
            } else {
                $('#api-error').html(''); 
                $('#submitButton').prop('disabled', false); 
            }
        });
    }
    
    
    
    
    
    



    

   

  
    
    
    


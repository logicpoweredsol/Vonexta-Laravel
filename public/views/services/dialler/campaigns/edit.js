function speed_calculate(id){

    var type = $("#"+id+" option:selected").text();
    if(type == 'Predictive' || type == 'Smart Predictive'){
        var html = '';
        for (var i = 1; i <= 5; i++) {
            html += '<option value="' + i + '">' + i + '</option>';
        }  
        $("#speed_div").removeClass('d-none');
    }else if(type == 'Agent Dial Next'){
        $("#speed_div").addClass('d-none');
    }else if(type == 'Auto Dial Next'){
        var html = '';
        $("#speed_div").removeClass('d-none');
        var html = '';
        for (var i = 5; i <= 30; i+=5) {
            html += '<option value="' + i + '">' + i + '</option>';
        }
    }
    $("#Velocity").html(html);

}

$(document).ready(function()
{

    var showPagination =true;

    if ($('#Compaign-table').find('tbody tr').length <= 10) {
        showPagination = false;  // If records are 10 or less, hide pagination
    }

    $('#Compaign-table').DataTable({
        "paging": showPagination,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });


    calculate_velocity();

});




function calculate_velocity()
{


    var  velocity_val =  $("#velocity_val").val()
    if(velocity_val !=0 ){
        var type = $("#type option:selected").text();
        if(type == 'Predictive' || type == 'Smart Predictive'){
            var html = '';
            for (var i = 1; i <= 5; i++) {
                html += '<option value="' + i + '">' + i + '</option>';
            }  
            $("#speed_div").removeClass('d-none');
        }else if(type == 'Agent Dial Next'){
            $("#speed_div").addClass('d-none');
        }else if(type == 'Auto Dial Next'){
            var html = '';
            $("#speed_div").removeClass('d-none');
            var html = '';
            for (var i = 5; i <= 30; i+=5) {
                html += '<option value="' + i + '">' + i + '</option>';
            }
        }
        $("#Velocity").html(html);
    
        $("#Velocity").val(velocity_val);
    }else{
        $("#speed_div").addClass('d-none');
    }

}




function add_custom_attribute()
{
    $("#outbound_cutom_attributes").modal('show');
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
        $('#api-error').html('');
        $('#submitButton').prop('disabled', true); 
        return;
    }
    
    if (!regex.test(extension)) {
        // If the input doesn't match the allowed characters, show an error
        $('#api-error').html('Only "-" and "_" characters are allowed.');
        $('#submitButton').prop('disabled', true); // Disable the submit button
        return; // Exit the function
    } else {
        $('#api-error').html(''); // Clear any previous error messages
        $('#submitButton').prop('disabled', false); // Enable the submit button
    }
    
    $.ajax({
        url: `${baseUrl}/services/dialer/campaigns/check-previous-api-name-outbound`,
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










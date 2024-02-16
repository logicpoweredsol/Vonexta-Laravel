$(function () {
  $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });
});



function showmodal()
{
    $("#exampleModal").modal('show');
}


function add_agent_role() {


  var f = 0;
  var user_group = $("#user_group").val().trim(); // Trim whitespace


  var allowed_profiles = $("#allowed_profiles").val();
  var transfer_caller_id = $("#transfer_caller_id").val();
  var manual_dial_caller_id = $("#manual_dial_caller_id").val();

  // Reset error message
  $("#role_error").html('');

  if (user_group === "") {
      $("#role_error").html('Agent Role is required');
      f = 1;
  } else {
      var check = checkString(user_group);

      if (!check) {
          $("#role_error").html('Agent Role should contain only letters, not numbers');
          f = 1;
      }

      if(check){

        var check2 =  calculateStringLength(user_group);
        if(!check2){
          $("#role_error").html('Agent Role should greater then 3 letters');
          f = 1;
        }

      }
  }


  if(allowed_profiles == "" || allowed_profiles == null){
    $("#allowed_profiles").css('border', '1px solid red');
    f=1;
  }else{
    $("#allowed_profiles").css('border', '1px solid #DEE2E6');
    f =0;
  }

  if(transfer_caller_id == "" || transfer_caller_id == null){
    $("#transfer_caller_id").css('border', '1px solid red');
    f=1;
  }else{
    $("#transfer_caller_id").css('border', '1px solid #DEE2E6');
    f =0;
  }

  if(manual_dial_caller_id == "" || manual_dial_caller_id == null){
    $("#manual_dial_caller_id").css('border', '1px solid red');
    f=1;
  }else{
    $("#manual_dial_caller_id").css('border', '1px solid #DEE2E6');
    f =0;
  }



  if (f == 0) {
      $("#form_add_agent_role").submit();
  }
}

// Example usage:
// Ensure you have the checkString function defined as mentioned in a previous response.



function checkString(inputString) {
  // Check if the inputString is a non-empty string
  if (typeof inputString === 'string' && inputString.trim() !== '') {
      // Check if the string contains only characters (no numbers)
      if (/^[a-zA-Z]+$/.test(inputString)) {
          // If it passes both conditions, return true
          return true;
      } else {
          // If it contains numbers, return false
          return false;
      }
  } else {
      // If the input is not a non-empty string, return false
      return false;
  }
}


function calculateStringLength(inputString) {
  var length = inputString.length;

  if (length < 3) {
    return false;
  } else {
    return true;
  }
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






$('#allowe_compaigns').on('switchChange.bootstrapSwitch', function(event, state) {
  if (this.checked) { 
      $("#allowed_profiles").prop('disabled',  true );
  } else {
      $("#allowed_profiles").prop('disabled', false);
  }
});



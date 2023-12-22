$(document).ready(function(){

    // DataTable Initialization
    $('#usersDT').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

    // Wizard Stepper Initialization
    var stepperEl = document.getElementById('newUserWizard');
    stepper = new Stepper(stepperEl);

    // Add User Modal
    $(document).on('click', '#btnAddUser', function(){
        $('#modalNewUser').modal('show');
    });

    // Wizard Steps Navigation
    $(document).on('click', '#btnNextStep', function(){
        stepper.next();
        $(this).hide();
        $('#btnPreviousStep').show();
        $('#btnNextStep').show();
        // $('#btnSubmit').show();
    });
   

    $(document).on('click', '#btnPreviousStep', function(){
        stepper.previous();
        $(this).hide();
        $('#btnPreviousStep').show();
        $('#btnNextStep').show();
        // $('#btnSubmit').show();
    });

});

// Event listener for bs-stepper show event
document.getElementById('newUserWizard').addEventListener('show.bs-stepper', function (event) {
    // You can call preventDefault to stop the rendering of your step
    // event.preventDefault();
    console.warn(event.detail.indexStep);
});

$(document).ready(function(){
    $('#compaignns , #Inbound').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

});

function updateValues(id, services_id) {
 // alert('ok');
    var extension = $("#" + id).val();
    $.ajax({
        url: `${baseUrl}/services.agents.detail`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'extension': extension,
            'services_id': services_id
        },
        success: function(response) {
            console.log(response);
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}


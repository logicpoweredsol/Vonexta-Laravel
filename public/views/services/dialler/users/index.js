// $(document).ready(function(){

//     // DataTable Initialization
//     $('#usersDT,#bulk-table').DataTable({
//         "paging": true,
//         "lengthChange": true,
//         "searching": true,
//         "ordering": true,
//         "info": true,
//         "autoWidth": false,
//         "responsive": true,
//     });
    

//     // Wizard Stepper Initialization
//     var stepperEl = document.getElementById('newUserWizard');
//     stepper = new Stepper(stepperEl);

//     // Add User Modal
//     $(document).on('click', '#btnAddUser', function(){
//         $('#modalNewUser').modal('show');
//     });
    
//     $(document).on('click','#Modal2',function(){
//         $('#NewModal').modal('show');
//     })

//     // Wizard Steps Navigation
//     $(document).on('click', '#btnNextStep', function(){
//         stepper.next();
//         $(this).hide();
//         $('#btnPreviousStep').show();
//         $('#btnNextStep').show();
//         // $('#btnSubmit').show();
//     });
   

//     $(document).on('click', '#btnPreviousStep', function(){
//         stepper.previous();
//         $(this).hide();
//         $('#btnPreviousStep').show();
//         $('#btnNextStep').show();
//         // $('#btnSubmit').show();
//     });

// });



// // Event listener for bs-stepper show event
// document.getElementById('newUserWizard').addEventListener('show.bs-stepper', function (event) {
//     // You can call preventDefault to stop the rendering of your step
//     // event.preventDefault();
//     console.warn(event.detail.indexStep);
// });





$(document).ready(function () {
    // Initialize stepper
    var stepper = new Stepper(document.getElementById('newUserWizard'), {
        linear: false, // Allows navigation to any step
        animation: true // Enable animation when changing steps
    });

    // DataTable Initialization
    $('#usersDT, #bulk-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

    // Add User Modal
    $(document).on('click', '#btnAddUser', function () {
        $('#modalNewUser').modal('show');
    });

    // Wizard Steps Navigation
    $(document).on('click', '#btnNextStep', function () {
        stepper.next();
        updateNavigationButtons();
    });

    $(document).on('click', '#btnPreviousStep', function () {
        stepper.previous();
        updateNavigationButtons();
    });

    // Function to update navigation buttons
    function updateNavigationButtons() {
        var currentIndex = stepper.currentIndex;

        if (currentIndex === 0) {
            $('#btnPreviousStep').hide();
            $('#btnNextStep').show();
            $('#btnSubmit').hide();
        } else if (currentIndex === 4) { // Assuming the last step index is 3, update accordingly
            $('#btnPreviousStep').show();
            $('#btnNextStep').hide();
            $('#btnSubmit').show();
        } else {
            $('#btnPreviousStep').show();
            $('#btnNextStep').show();
            $('#btnSubmit').hide();
        }
    }
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



function check_extension(id, services_id) {
    
    $('#extension-error').html('')
    $('#extension-success').html('')
    var extension = $("#" + id).val();

    $.ajax({
        url:  `${baseUrl}/check-extension`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            'extension': extension,
            'services_id':services_id
        },
        success: function(response) {
            console.log(response);
            if(response.status == 'success'){
                $('#extension-error').html('')
                $('#extension-success').html(response.message)
            } 
            if(response.status == 'failed'){
                $('#extension-error').html(response.message)
                $('#extension-success').html('')
            } 
        },
        error: function(xhr, status, error) {
            console.error("Error occurred:", error);
        }
    });
}


function add_row(){
    var row_number = $(".add_row tr").length;
    row_number = row_number+1;
    html =  `<tr  id=${row_number}>
                <td><input type="email" class="form-control" name="email[]" id="email"></td>
                <td><input type="text" class="form-control" name="extension[]" id="extension"></td>
                <td><input type="text" class="form-control" name="full_name[]" id="full_name"></td>
                <td><input type="password" class="form-control" name="password[]" id="password"></td>
                <td><input type="text" class="form-control" name="status[]" id="status"></td>
                <td>
                <button onclick="add_row();" class="btn btn-success btn-sm">+</button>
                <button onclick="remove_row(${row_number});" class="btn btn-danger btn-sm">-</button>
                </td>
            </tr>`

    $(".add_row").append(html);
}


function remove_row(row_number){
    $("#" +row_number).remove();

}

function change_tab(id) {

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

    var id_array = ['inbounds', 'compaigns'];
    var id = $("#skills_log").val();

    id_array.forEach(element => {
        if (element == id) {
            $("." + element).removeClass('d-none');
        } else {
            $("." + element).addClass('d-none');
        }
    });
}

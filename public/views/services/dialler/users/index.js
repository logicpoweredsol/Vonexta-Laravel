$(document).ready(function(){
    // BS-Stepper Init
    var stepper = new Stepper(stepperEl);
    $('#usersDT').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

    //Add user...
    $(document).on('click', '#btnAddUser',function(){
        $('#modalNewUser').modal('show');
    });
    $(document).on('submit', '#btnPreviousStep', function(e){

    });
    //Wizard Steps...
    $(document).on('click', '#btnNextStep', function(){
        stepper.next();
        $(this).hide();
        $('#btnPreviousStep').show();
        $('#btnSubmit').show();
    });
    $(document).on('click', '#btnPreviousStep', function(){
        stepper.previous();
        $(this).hide();
        $('#btnNextStep').show();
        $('#btnSubmit').hide();
    });
});
var stepperEl = document.getElementById('newUserWizard');
stepperEl.addEventListener('show.bs-stepper', function (event) {
    // You can call preventDefault to stop the rendering of your step
    // event.preventDefault()
    console.warn(event.detail.indexStep);
})
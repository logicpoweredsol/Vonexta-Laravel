$(document).ready(function (){
    $('#systemUsersForm').validate({
        rules:{
            email:{
                required: true,
                email: true,
            },
            password:{
                // required: true,
                minlength: 8
            },
            role:{
                required: true,
            },
            permissions:{
                required: true,
                minlength: 1
            },
        },
        messages: {
            email: {
                required: "Please enter a email address",
                email: "Please enter a valid email address"
            },
            password: {
                // required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long"
            },
            role: "Please select role for user.",
            "permissions[]": "Please select at-least one permission."
        },
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            // element.closest('.form-group').append(error);
            element.closest('.form-group').find('.error').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            form.submit();
        }
    })
});

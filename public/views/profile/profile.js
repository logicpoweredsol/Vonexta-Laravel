
    function OpenResetModel() {
        Swal.fire({
            title: 'Reset Password',
            html:
                '<input id="currentPassword" class="swal2-input" placeholder="Current Password" type="password" required>' +
                '<input id="newPassword" class="swal2-input" placeholder="New Password" type="password" required>' +
                '<input id="confirmPassword" class="swal2-input" placeholder="Confirm Password" type="password" required>',
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel',
            focusConfirm: false,
            preConfirm: () => {
                return {
                    currentPassword: $('#currentPassword').val(),
                    newPassword: $('#newPassword').val(),
                    confirmPassword: $('#confirmPassword').val()
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updatePassword(result.value);
            }
        });
    }

    function updatePassword(data) {
        var currentPassword = data['currentPassword'];
        var newPassword = data['newPassword'];
        var confirmPassword = data['confirmPassword'];
    
        // Append the parameters to the URL for a GET request
        var url = `${baseUrl}/reset-password?currentPassword=${currentPassword}&newPassword=${newPassword}&confirmPassword=${confirmPassword}`;
    
        $.ajax({
            url: url,
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json', // Specify the expected data type of the response
            success: function (response) {
                if(response == 'Password updated successfully'){
                    Swal.fire(response, '', 'success');
                }else{
                    Swal.fire(response, '', 'error');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
            },
        });
    }

    

 


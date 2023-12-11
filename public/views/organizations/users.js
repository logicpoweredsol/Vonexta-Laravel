$(document).ready(function(){
    $('#usersDT').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});



// function search_organization_user() {
//     var organization = $('#orgId').val();
//     var url = `${baseUrl}/organizations/user/get-organization-user`;
//     $.ajax({
//         url: url,
//         method: 'POST',
//         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//         data: {
//             'organization_id': organization  // Fix the typo here
//         },
//         success: function (response) {
//             // return;
//             var html = `<div class="card" id="user-search-tab">
//                 <div class="card-header">
//                     <h3 class="card-title">All Users</h3>
//                     <div class="card-tools">
//                         <a href="javascript:;" onclick="add_organization_user()" class="btn btn-sm btn-primary">
//                             <i class="fas fa-plus"></i> Add User
//                         </a>
//                     </div>
//                 </div>
//                 <div class="card-body">
//                     <table id="usersDT" class="table table-bordered table-striped table-hover">
//                         <thead>
//                             <tr>
//                                 <th>#</th>
//                                 <th>Name</th>
//                                 <th>Email</th>
//                                 <th>Status</th>
//                                 <th>Actions</th>
//                             </tr>
//                         </thead>
//                         <tbody>`;
//             for (let index = 0; index < response.length; index++) {
//                 html += `<tr>
//                             <td></td>
//                             <td>${response[index]['organization_user']['name']}</td>
//                             <td>${response[index]['organization_user']['email']}</td>`;

//                 if (response[index]['organization_user']['active'] == 1) {
//                     html += `<td><span class="badge badge-success">Active</span></td>`;
//                 } else {
//                     html += `<td><span class="badge badge-danger">Inactive</span></td>`;
//                 }

//                 html += `<td>
//                         <a href="javascript:;" onclick='edit_org_user(${response[index]['organization_user']['id']} , ${response[index]['id']} )' class="btn btn-sm btn-primary"><i class="fas fa-pen"></i></a>
//                         <a href="javascript:;" onclick='delete_org_user(${response[index]['organization_user']['id']})' class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>

//                         </td>
//                     </tr>`;
//             }

//             html += `</tbody>
//                     </table>
//                 </div>
//             </div>`;

//             $("#Organization-user").html(html);
//         },
//         error: function (xhr, status, error) {
//             // Handle error
//         },
//     });
// }


// function add_organization_user() {

//     var oranization = $('#orgId').val();
//     var html = "";
//     var url = `${baseUrl}/get-permissions-attribute`;
//     $.ajax({
//         url: url,
//         method: 'POST',
//         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//         data: {},
//         success: function (response) {
//             html += `<form id='add-form-user-organization' action="${baseUrl}/organizations/user/store" method="post" class="form-horizontal">`;
//             html += `<input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">`;
//             html += `<div class="card">
//                         <div class="card-header">
//                             <h3 class="card-title">Add User</h3>
//                         </div>
//                         <input type='hidden' value='${oranization}' name='organizations_id' >
                        
//                         <div class="card-body">
//                             <div class="form-group row">
//                                 <label for="name" class="col-sm-2 col-form-label">Name</label>
//                                 <div class="col-sm-10">
//                                     <input type="text" class="form-control" id="add-name" name="name" placeholder="Name of the user">
//                                 </div>
//                             </div>
//                             <div class="form-group row">
//                                 <label for="email" class="col-sm-2 col-form-label">Email</label>
//                                 <div class="col-sm-10">
//                                     <input type="email" class="form-control " id="add-email" name="email" placeholder="Email">
//                                     <span id='already-email' class='text-red d-none'>Email already used.</span>
//                                 </div>
//                             </div>
//                             <div class="form-group row">
//                                 <label for="password" class="col-sm-2 col-form-label">Password</label>
//                                 <div class="col-sm-10">
//                                     <input type="text" class="form-control" id="add-password" name="password" placeholder="Password">
//                                 </div>
//                             </div>
//                             <div class="form-group row">
//                                 <label for="role" class="col-sm-2 col-form-label">Role</label>
//                                 <div class="col-sm-10">
//                                     <select class="form-control" id="add-role" name="role">
//                                         <option value="admin">Admin</option>
//                                         <option value="user">User</option>
//                                     </select>
//                                 </div>
//                             </div>`;

//             html += `<div class="form-group row">
//                         <label for="permissions_row" class="col-sm-2 col-form-label">This user can</label>
//                         <div class="col-sm-10">
//                             <div class="row" id="permissions_row">`;

//             for (let index = 0; index < response.length; index++) {
//                 html += `<div class="col-sm-6 mb-3">
//                             <div class="form-check">
//                                 <input type="checkbox" class="form-check-input" id="${response[index].name}" name="permissions[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" checked value="${response[index].name}">
//                                 <label class="form-check-label" for="${response[index].name}">${ucwords(response[index].name)}</label>
//                             </div>
//                         </div>`;
//             }

//             html += `         </div>
//                         </div>
//                     </div>`;

//             html += `</div>
//                         <div class="card-footer">
//                             <button type="button" onclick="save_organization_user('add')" class="btn btn-primary float-right">Save</button>
//                             <a href="javascript:;" onclick='search_organization_user();' class="btn btn-default">Cancel</a>
//                         </div>
//                     </div>`;

//             html += `</form>`;

//         $("#Organization-user").html(html);


//         },
//         error: function (xhr, status, error) {
//             // Handle error
//         },
//     });
// }

// function edit_org_user(user_id ,org_id){
//     var html = "";
//     var url = `${baseUrl}/organizations/user/edit`;
//     $.ajax({
//         url: url,
//         method: 'POST',
//         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//         data: {
//             'user_id':user_id,
//             'org_id':org_id
//         },
//         success: function (response) {
//             // console.log(response);
//             html += `<form id='edit-form-user-organization' action="${baseUrl}/organizations/user/update" method="post" class="form-horizontal">`;
//             html += `<input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">`;
//             html += `<div class="card">
//                         <div class="card-header">
//                             <h3 class="card-title">Add User</h3>
//                         </div>
//                         <input type='hidden' value='${org_id}' name='organizations_id' >
//                         <input type='hidden' value='${user_id}' name='edit_org_user_id' id='edit_org_user_id' >
                        
//                         <div class="card-body">
//                             <div class="form-group row">
//                                 <label for="name" class="col-sm-2 col-form-label">Name</label>
//                                 <div class="col-sm-10">
//                                     <input type="text" class="form-control" id="add-name" name="name" value="${response['user'].name}" placeholder="Name of the user">
//                                 </div>
//                             </div>
//                             <div class="form-group row">
//                                 <label for="email" class="col-sm-2 col-form-label">Email</label>
//                                 <div class="col-sm-10">
//                                     <input type="email" class="form-control " id="add-email" value="${response['user'].email}" name="email" placeholder="Email">
//                                     <span id='already-email' class='text-red d-none'>Email already used.</span>
//                                 </div>
//                             </div>
//                             <div class="form-group row">
//                                 <label for="password" class="col-sm-2 col-form-label">Password</label>
//                                 <div class="col-sm-10">
//                                     <input type="text" class="form-control" id="add-password" name="password" placeholder="Password">
//                                 </div>
//                             </div>
//                             <div class="form-group row">
//                                 <label for="role" class="col-sm-2 col-form-label">Role</label>
//                                 <div class="col-sm-10">
//                                     <select class="form-control" id="add-role" name="role">
//                                     <option ${response['userRoles'][0] == 'admin' ? 'selected' : ''} value="admin">Admin</option>
//                                     <option ${response['userRoles'][0] == 'user' ? 'selected' : ''} value="user">User</option>                                    
//                                     </select>
//                                 </div>
//                             </div>

//                             <div class="form-group row">
//                             <label for="role" class="col-sm-2 col-form-label">Active</label>
//                             <div class="col-sm-10">
//                                 <select class="form-control" id="add-active" name="active">
//                                 <option ${response['user'].active == '1' ? 'selected' : ''} value="1">Yes</option>
//                                 <option ${response['user'].active == '0' ? 'selected' : ''} value="0">No</option>                                    
//                                 </select>
//                             </div>
//                         </div>

                            
//                             `;

//             html += `<div class="form-group row">
//                         <label for="permissions_row" class="col-sm-2 col-form-label">This user can</label>
//                         <div class="col-sm-10">
//                             <div class="row" id="permissions_row">`;

//                         for (let index = 0; index < response['permissions'].length; index++) {
//                             html += `<div class="col-sm-6 mb-3">
//                                         <div class="form-check">
//                                             <input type="checkbox" class="form-check-input" id="${response['permissions'][index].name}" name="permissions[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" ${ response['userPermissions'].includes(response['permissions'][index].name) ? 'checked' : ''}  value="${response['permissions'][index].name}">
//                                             <label class="form-check-label" for="${response['permissions'][index].name}">${ucwords(response['permissions'][index].name)}</label>
//                                         </div>
//                                     </div>`;
//                         }

//             html += `         </div>
//                         </div>
//                     </div>`;

//             html += `</div>
//                         <div class="card-footer">
//                             <button type="button" onclick="save_organization_user('edit')" class="btn btn-primary float-right">Save</button>
//                             <a href="javascript:;" onclick='search_organization_user();' class="btn btn-default">Cancel</a>
//                         </div>
//                     </div>`;



//             html += `</form>`;

//         $("#Organization-user").html(html);


//         },
//         error: function (xhr, status, error) {
//             // Handle error
//         },
//     });



// }


// async function save_organization_user(action){
//     var f = 0;
//     var add_name = $("#add-name").val();
//     var add_email = $("#add-email").val();
//     var add_password = $("#add-password").val();
//     var add_role = $("#add-role").val();


    

   

//     if(add_name == "" || add_name == null){
//         $("#add-name").css('border', '1px solid red');
//         f = 1;
//     }else{
//         $("#add-name").css('border', '1px solid #E7E9EC');
//     }


    

//     var Email_check  = await check_email(add_email ,action);

//     if(Email_check == 1){
//         $("#add-email").css('border', '1px solid red');
//         $("#already-email").removeClass('d-none');
//         f = 1;
//     }

//     if(add_email == "" || add_email == null){
//         $("#add-email").css('border', '1px solid red');
//         f = 1;
//     }else{
//         $("#add-email").css('border', '1px solid #E7E9EC');
//     }




//     if(action == 'add'){

//         if(add_password == "" || add_password == null){
//             $("#add-password").css('border', '1px solid red');
//             f = 1;
//         }else{
//             $("#add-password").css('border', '1px solid #E7E9EC');
//         }
//     }
   

    
//     if(add_role == "" || add_role == null){
//         $("#add-role").css('border', '1px solid red');
//         f = 1;
//     }



//     if (f == 0){
//         if(action == 'add'){
//             $("#add-form-user-organization").submit();
//         }
//         if(action == 'edit'){
//             $("#edit-form-user-organization").submit();
//         }
//     }
   

// }



// async function check_email(email,action) {

//     var user_id = $('#edit_org_user_id').val();

//     var status = 0;
//     var url = `${baseUrl}/organizations/user/check-user-email`;

//     try {
//         const response = await $.ajax({
//             url: url,
//             method: 'POST',
//             headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//             data: {
//                 'email': email,
//                 'action':action,
//                 'user_id':user_id
//             },
//         });

//         if (response == 1) {
//             status = 1;
//         }

//     } catch (error) {
//         // Handle error
//         console.error(error);
//     }

//     return status;
// }


// function delete_org_user(id) {
//     let user = id;
//         Swal.fire({
//             title: "Are you sure?",
//             text: "You won't be able to revert this!",
//             icon: "warning",
//             showCancelButton: true,
//             confirmButtonColor: "#3085d6",
//             cancelButtonColor: "#d33",
//             confirmButtonText: "Yes, delete it!",
//             showLoaderOnConfirm: true,
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 var url = `${baseUrl}/organizations/user/delete`;
//                 $.ajax({
//                     url: url,
//                     type: 'POST',
//                     headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//                     data: {
//                         'user_id': user 
//                     },
//                     success: function (response) {
//                         let icon = response.status ? "success" : "error";
//                         let title = response.status ? "Deleted!" : "Error";
//                         let text = response.status ? response.message : response.error;
//                         Swal.fire({
//                             title: title,
//                             text: text,
//                             icon: icon,
//                             timer: 2000,
//                             showConfirmButton: false,
//                             willClose: () => {
//                                 window.location.reload();
//                             }
//                         });
//                     },
//                     error: function (xhr, status, error) {
//                         Swal.fire({
//                             title: "Error",
//                             text: "Error deleting the user",
//                             icon: "error",
//                             timer: 2000,
//                             showConfirmButton: false,
//                             willClose: () => {
//                                 window.location.reload();
//                             }
//                         });
//                     }
//                 });
//             }
//         });

// }


// function ucwords(str) {
//     return str.replace(/\b\w/g, function (char) {
//         return char.toUpperCase();
//     });
// }



function change_tab(id) {
    var id_array = ['org-user', 'add-org-user', 'edit-org-user'];

    id_array.forEach(element => {
        if (element == id) {
            $("#" + element).removeClass('d-none');
        } else {
            $("#" + element).addClass('d-none');
        }
    });
}









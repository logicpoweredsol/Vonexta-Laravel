$(function () {
    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
});




$(document).ready(function(){
    $('#Campaigns ,#Enbound').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });


    show_call_log_tb();

});



function show_call_log_tb(first) {
    var table_log;
    
    if (first !== undefined && first !== 'undefined') {
        table_log = "Inbound";
    } else {
        table_log = $("#table_log").val();
    }
   
    var tb_th = [];

    var All_tf = {
        'Inbound': ['Call Time', 'Phone Number', 'Contact List', 'Type', 'Source', 'Disposition'],
        'Outbound': ['Call Date', 'Lead ID', 'Phone Number', 'Campaign', 'Call length', 'Status'],
        'Manual': ['Call Date', 'Lead ID', 'Phone Number', 'Campaign', 'Call length', 'Status'],
        'Transfer': ['Transfer Date', 'Lead ID', 'Phone Number', 'Transferred to']
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
                <table class="table table-striped table-hover vonexta-table" id="call-log-table">
                    <thead>
                        <tr>
                            ${tb_th_unique.map(element => `<th>${element}</th>`).join('')}
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    `;

    $(".call-log-tb").html(html);

    $('#call-log-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "responsive": false,
    });
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

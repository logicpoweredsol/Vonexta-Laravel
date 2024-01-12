$(function () {
    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
});




$(document).ready(function(){

    $('.adminlet3').tooltip();
    showPagination =true;
    if ($('#tbl').find('tbody tr').length <= 10) {
        showPagination = false;  // If records are 10 or less, hide pagination
    }


    $('#Campaigns ,#Enbound,.table').DataTable({
        "paging": showPagination,
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
            format: 'YYYY-MM-DD', // Date format
            separator: ' to ', // Separator between start and end date
        },
        // Define predefined date ranges
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }

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

                console.log(response);
                var html = "";
                if(response['data']['event_time'] != null){
                    for (var p = 0; p < response['data']['event_time'].length; p++) {
                        html += "<tr>";
                        html += "<td>" + response['data']['event_time'][p] + "</td>";
                        html += "<td>" + response['data']['sub_status'][p] + "</td>";
                        html += "<td>" + response['data']['event_time'][p] + "</td>";
                        html += "<td>" + response['data']['campaign_id'][p] + "</td>";
                        html += "</tr>";
                    }
                } 
                $("#activity-tbody").html(html);

                // var dataTable = $('.table').DataTable();

                //     // Destroy the existing DataTable and then reinitialize it with the updated HTML
                //     dataTable.destroy();
                    // $('.table').DataTable({
                    //     "paging": true,
                    //     "lengthChange": true,
                    //     "searching": true,
                    //     "ordering": true,
                    //     "info": true,
                    //     "autoWidth": false,
                    //     "responsive": true
                    // });

            },
            error: function(xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });

    });

    





    $('#reservation1').daterangepicker({
       
        opens: 'left', // Position of the calendar dropdown
        autoApply: false, // Auto-apply the selected date range
        showApplyButton: true, // Display the Apply button
        showCancelButton: true, // Display the Cancel button
        locale: {
            format: 'YYYY-MM-DD', // Date format
            separator: ' to ', // Separator between start and end date
        },
        // Define predefined date ranges
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }

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
    
                    console.log(response);
    
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred:", error);
                }
            });
    
        }else{
            alert('At least one filter to be selected ');
        }

       
    });


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
        'Outbound': ['Contact ID', 'Phone Number', 'Campaign','Status'],
        'Manual': ['Contact ID', 'Phone Number', 'Campaign', 'Status'],
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

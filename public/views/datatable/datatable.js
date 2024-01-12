$(document).ready(function(){


    showPagination =true;
    if ($('#tbl').find('tbody tr').length <= 10) {
        showPagination = false;  // If records are 10 or less, hide pagination
    }

    // Initialize DataTable
    const table = $('#tbl').DataTable({
        "paging": showPagination,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });

    // Get the select element for status
    const statusEl = document.querySelector('#search_filter');
    
    // Add event listener for status change to redraw table
    statusEl.addEventListener('change', function () {
        table.draw();
    });

    // Custom status filtering function
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        let selectedStatus = statusEl.value.trim().toLowerCase();  // Convert to lower case and trim
        let rowStatus = data[5].trim().toLowerCase();  // Convert to lower case and trim
        if(selectedStatus != 'all'){
            return selectedStatus === rowStatus;
        }else{
            return true;
        }
       
    });
});

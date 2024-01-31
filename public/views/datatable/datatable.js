var table = "";

$(document).ready(function () {
    var showPagination = true;

    if ($('#tbl').find('tbody tr').length <= 10) {
        showPagination = false; // If records are 10 or less, hide pagination
    }

    // Initialize DataTable
    table = $('#tbl').DataTable({
        "paging": showPagination,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });
});

function search_filter(statusEl) {
    // Remove the previous search function before adding a new one
    $.fn.dataTable.ext.search.pop();

    // Add a new search function
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        // Check if data[5] is defined and not null before accessing its properties
        let rowStatus = (data[5] || '').trim().toLowerCase();


        let valueToSearch = statusEl.trim().toLowerCase();
    
        if (valueToSearch !== 'all') {
            return valueToSearch === rowStatus;
        } else {
            return true;
        }
    });
    

    // Redraw the DataTable with the new search function
    table.draw();

    $("#cur_status").text(statusEl);
}

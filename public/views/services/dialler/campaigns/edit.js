function speed_calculate(id){

    var type = $("#"+id+" option:selected").text();


    // var type  = $("#"+id).val();
 

    if(type == 'Predictive' || type == 'Smart Predictive'){
        var html = '';
        for (var i = 1; i <= 5; i++) {
            html += '<option value="' + i + '">' + i + '</option>';
        }  
        $("#speed_div").removeClass('d-none');
    }else if(type == 'Agent Dial Next'){
        $("#speed_div").addClass('d-none');
    }else if(type == 'Auto Dial Next'){
        var html = '';
        $("#speed_div").removeClass('d-none');
        var html = '';
        for (var i = 5; i <= 30; i+=5) {
            html += '<option value="' + i + '">' + i + '</option>';
        }
    }
    $("#speed").html(html);

}

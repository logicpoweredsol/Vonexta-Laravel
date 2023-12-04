$(document).ready(function(){
    // BS-Stepper Init
    var campaignStepper = new Stepper(campaignStepperEl);
    $('#campaignsDT','#dispositionsDT','#leadRecyclingDT', '#accidDT').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
    
    //Add campaign...
    $(document).on('click', '#btnAddCampaign',function(){
        $('#modalNewCampaign').modal('show');
    });
    $('#modalNewCampaign').on('shown.bs.modal', function(){
        resetCampaignForm();
    });
    $('#modalNewCampaign').on('hidden.bs.modal', function(){
        resetCampaignForm();
    });
    // $(document).on('submit', '#btnCampaignPreviousStep', function(e){

    // });
    //Campaign Wizard Steps...
    $(document).on('click', '#btnCampaignNextStep', function(){
        let campaignType = $('#campaign_type').val();
        let validated = validateForNextStep(campaignType);
        if(validated){
            campaignStepper.next();
            $(this).hide();
            $('#btnCampaignPreviousStep').show();
            $('#btnCampaignSubmit').show();
        }
    });
    $(document).on('click', '#btnCampaignPreviousStep', function(){
        campaignStepper.previous();
        $(this).hide();
        $('#btnCampaignNextStep').show();
        $('#btnCampaignSubmit').hide();
    });

    //campaign types settings...
    $(document).on('change', '#campaign_type', function(e){
        let campaignType = $(this).val();
        $(this).css({
            'border-color' : ''
        });
        resetCampaignForm();
        switch(campaignType){
            case "outbound":
                $('#campaign_type_outbound').show();
                $('#campaign_type_inbound').hide();
                $('#campaign_type_survey').hide();
                $('#campaign_type_copy').hide();
                break;
            case "inbound":
                $('#campaign_type_outbound').hide();
                $('#campaign_type_inbound').show();
                $('#campaign_type_survey').hide();
                $('#campaign_type_copy').hide();
                break;
            case "blended":
                $('#campaign_type_outbound').show();
                $('#campaign_type_inbound').show();
                $('#campaign_type_survey').hide();
                $('#campaign_type_copy').hide();
                break;
            case "survey":
                $('#campaign_type_outbound').show();
                $('#campaign_type_inbound').hide();
                $('#campaign_type_survey').show();
                $('#campaign_type_copy').hide();
                break;
            case "copy":
                $('#campaign_type_outbound').hide();
                $('#campaign_type_inbound').hide();
                $('#campaign_type_survey').hide();
                $('#campaign_type_copy').show();
                break;
            default:
                $('#campaign_type_inbound').hide();
                $('#campaign_type_outbound').hide();
                $('#call_route_ingroup').hide();
                $('#call_route_ivr').hide();
        }
    });
    $(document).on('change', '#call_route', function(){
        let callRoute = $(this).val();
        if(callRoute == "INGROUP"){
            $('#call_route_ingroup').show();
            $('#call_route_ivr').hide();
        }else if(callRoute == "IVR"){
            $('#call_route_ingroup').hide();
            $('#call_route_ivr').show();
        }else{
            $('#call_route_ivr').hide();
            $('#call_route_ingroup').hide();
        }
    });
});
var campaignStepperEl = document.getElementById('newCampaignWizard');
campaignStepperEl.addEventListener('show.bs-stepper', function (event) {
    console.warn(event.detail.indexStep);
});

const resetCampaignForm = () => {
    $('#campaign_type_inbound').hide();
    $('#campaign_type_outbound').hide();
    $('#campaign_type_survey').hide();
    $('#campaign_type_copy').hide();
    $('#call_route_ingroup').hide();
    $('#call_route_ivr').hide();
};

const validateForNextStep = (campaignType) => {
    if(campaignType==""){
        $('#campaign_type').css({
            'border-color' : 'red'
        });
        return false;
    }else{
        $('#campaign_type').css({
            'border-color' : ''
        });
    }
    let isValidated = true;
    let campaign_id = $('#campaign_id');
    let campaign_name = $('#campaign_name');
    let call_route = $('#call_route');
    if(campaign_id.val()==""){
        $(campaign_id).css({
            'border-color' : 'red'
        });
        isValidated = false;
    }else{
        $(campaign_id).css({
            'border-color' : ''
        });
    }
    if(campaign_name.val()==""){
        $(campaign_name).css({
            'border-color' : 'red'
        });
        isValidated = false;
    }else{
        $(campaign_name).css({
            'border-color' : ''
        });
    }
    if(campaignType=="blended" || campaignType=="inbound"){
        if(call_route.val==""){
            $(call_route).css({
                'border-color' : 'red'
            });
            isValidated = false;
        }else{
            $(call_route).css({
                'border-color' : ''
            });
        }
    }
    return isValidated;
};
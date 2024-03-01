// var table = "";
// $(document).ready(function(){

//     var showPagination = true;

//     if ($('#Compaign-table').find('tbody tr').length <= 10) {
//         showPagination = false; // If records are 10 or less, hide pagination
//     }

//     // Initialize DataTable
//     table = $('#Compaign-table').DataTable({
//         "paging": showPagination,
//         "lengthChange": true,
//         "searching": true,
//         "ordering": true,
//         "info": true,
//         "autoWidth": false,
//         "responsive": true
//     });






//     // BS-Stepper Init
//     var campaignStepper = new Stepper(campaignStepperEl);
//     $('#campaignsDT','#dispositionsDT','#leadRecyclingDT', '#accidDT').DataTable({
//         "paging": true,
//         "lengthChange": true,
//         "searching": true,
//         "ordering": true,
//         "info": true,
//         "autoWidth": false,
//         "responsive": true,
//     });

//     $(".select5").select2();
    
//     //Add campaign...
//     $(document).on('click', '#btnAddCampaign',function(){
//         $('#modalNewCampaign').modal('show');
//     });
//     $('#modalNewCampaign').on('shown.bs.modal', function(){
//         resetCampaignForm();
//     });
//     $('#modalNewCampaign').on('hidden.bs.modal', function(){
//         resetCampaignForm();
//     });
//     // $(document).on('submit', '#btnCampaignPreviousStep', function(e){

//     // });
//     //Campaign Wizard Steps...
//     $(document).on('click', '#btnCampaignNextStep', function(){
//         let campaignType = $('#campaign_type').val();
//         let validated = validateForNextStep(campaignType);
//         if(validated){
//             campaignStepper.next();
//             $(this).hide();
//             $('#btnCampaignPreviousStep').show();
//             $('#btnCampaignSubmit').show();
//         }
//     });
//     $(document).on('click', '#btnCampaignPreviousStep', function(){
//         campaignStepper.previous();
//         $(this).hide();
//         $('#btnCampaignNextStep').show();
//         $('#btnCampaignSubmit').hide();
//     });

//     //campaign types settings...
//     $(document).on('change', '#campaign_type', function(e){
//         let campaignType = $(this).val();
//         $(this).css({
//             'border-color' : ''
//         });
//         resetCampaignForm();
//         switch(campaignType){
//             case "outbound":
//                 $('#campaign_type_outbound').show();
//                 $('#campaign_type_inbound').hide();
//                 $('#campaign_type_survey').hide();
//                 $('#campaign_type_copy').hide();
//                 break;
//             case "inbound":
//                 $('#campaign_type_outbound').hide();
//                 $('#campaign_type_inbound').show();
//                 $('#campaign_type_survey').hide();
//                 $('#campaign_type_copy').hide();
//                 break;
//             case "blended":
//                 $('#campaign_type_outbound').show();
//                 $('#campaign_type_inbound').show();
//                 $('#campaign_type_survey').hide();
//                 $('#campaign_type_copy').hide();
//                 break;
//             case "survey":
//                 $('#campaign_type_outbound').show();
//                 $('#campaign_type_inbound').hide();
//                 $('#campaign_type_survey').show();
//                 $('#campaign_type_copy').hide();
//                 break;
//             case "copy":
//                 $('#campaign_type_outbound').hide();
//                 $('#campaign_type_inbound').hide();
//                 $('#campaign_type_survey').hide();
//                 $('#campaign_type_copy').show();
//                 break;
//             default:
//                 $('#campaign_type_inbound').hide();
//                 $('#campaign_type_outbound').hide();
//                 $('#call_route_ingroup').hide();
//                 $('#call_route_ivr').hide();
//         }
//     });
//     $(document).on('change', '#call_route', function(){
//         let callRoute = $(this).val();
//         if(callRoute == "INGROUP"){
//             $('#call_route_ingroup').show();
//             $('#call_route_ivr').hide();
//         }else if(callRoute == "IVR"){
//             $('#call_route_ingroup').hide();
//             $('#call_route_ivr').show();
//         }else{
//             $('#call_route_ivr').hide();
//             $('#call_route_ingroup').hide();
//         }
//     });
// });
// var campaignStepperEl = document.getElementById('newCampaignWizard');
// campaignStepperEl.addEventListener('show.bs-stepper', function (event) {
//     console.warn(event.detail.indexStep);
// });

// const resetCampaignForm = () => {
//     $('#campaign_type_inbound').hide();
//     $('#campaign_type_outbound').hide();
//     $('#campaign_type_survey').hide();
//     $('#campaign_type_copy').hide();
//     $('#call_route_ingroup').hide();
//     $('#call_route_ivr').hide();
// };

// const validateForNextStep = (campaignType) => {
//     if(campaignType==""){
//         $('#campaign_type').css({
//             'border-color' : 'red'
//         });
//         return false;
//     }else{
//         $('#campaign_type').css({
//             'border-color' : ''
//         });
//     }
//     let isValidated = true;
//     let campaign_id = $('#campaign_id');
//     let campaign_name = $('#campaign_name');
//     let call_route = $('#call_route');
//     if(campaign_id.val()==""){
//         $(campaign_id).css({
//             'border-color' : 'red'
//         });
//         isValidated = false;
//     }else{
//         $(campaign_id).css({
//             'border-color' : ''
//         });
//     }
//     if(campaign_name.val()==""){
//         $(campaign_name).css({
//             'border-color' : 'red'
//         });
//         isValidated = false;
//     }else{
//         $(campaign_name).css({
//             'border-color' : ''
//         });
//     }
//     if(campaignType=="blended" || campaignType=="inbound"){
//         if(call_route.val==""){
//             $(call_route).css({
//                 'border-color' : 'red'
//             });
//             isValidated = false;
//         }else{
//             $(call_route).css({
//                 'border-color' : ''
//             });
//         }
//     }
//     return isValidated;
// };


// function search_filter(statusEl) {
//     // Remove the previous search function before adding a new one
//     $.fn.dataTable.ext.search.pop();

//     // Add a new search function
//     $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
//         // Check if data[5] is defined and not null before accessing its properties
//         let rowStatus = (data[4] || '').trim().toLowerCase();
//         let valueToSearch = statusEl.trim().toLowerCase();
//         if (valueToSearch !== 'All') {
//             return valueToSearch === rowStatus;
//         } else {
//             return true;
//         }
//     });
    

//     // Redraw the DataTable with the new search function
//     table.draw();

//     $("#cur_status").text(capitalizeFirstLetter(statusEl));
// }

// function capitalizeFirstLetter(str) {
//     return str.charAt(0).toUpperCase() + str.slice(1);
// }

// $(document).ready(function()
// {

// var showPagination = true;

// $(document).ready(function() {
//     if ($('#tablessss').find('tbody tr').length <= 10) {
//         showPagination = false;  // If records are 10 or less, hide pagination
//     }

//     $('#tablessss').DataTable({
//         "paging": showPagination,
//         "lengthChange": true,
//         "searching": true,
//         "ordering": true,
//         "info": true,
//         "autoWidth": true,
//         "responsive": true
//     });
// });

   






function show_add_compaign_modal() {
  $("#compaignn-modal").modal('show');
}







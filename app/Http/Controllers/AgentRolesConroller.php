<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizationServices;

class AgentRolesConroller extends Controller
{
    //



    // index 
    public function agentRole($service, $organization_servicesID)
    {
        $userGroupsResult = $this->get_agent_roles($organization_servicesID);
        $userGroups = "";

        if ($userGroupsResult['result'] == 'success') {
            $userGroups =  $userGroupsResult;
        }

        // dd( $userGroups);

        $all_compaign = '';
        $compaign_responce = $this->get_all_Campaigns($organization_servicesID);
        if ($compaign_responce['result'] == 'success') {
            $all_compaign =   $compaign_responce;
        }

        
        return view('dialer.admin.agentrole.index', compact('service', 'organization_servicesID', 'userGroups','all_compaign'));
    }



    function get_agent_roles($organization_service_id) {
        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/UserGroups/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllUserGroups',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
        ];
        $ch = curl_init($apiEndpoint);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute cURL session and get the response
        $response = curl_exec($ch);
    
        // Close cURL session
        curl_close($ch);
        
        return json_decode($response, true);
    }
    // end index 





    function addAgentRole(Request $request) {

        dd($request->all());

        $select_inbound_upon_login = isset($request->select_inbound_upon_login) ? 'Y' : 'N';
        $select_auto_outbound_upon_login = isset($request->select_auto_outbound_upon_login) ? 'Y' : 'N';
        $allow_auto_outbound = isset($request->allow_auto_outbound) ? 'Y' : 'N';
        $allow_manual_calls = isset($request->allow_manual_calls) ? 'Y' : 'N';
        $allow_schedule_callbacks = isset($request->allow_schedule_callbacks) ? 'Y' : 'N';
        $allow_personal_callbacks = isset($request->allow_personal_callbacks) ? 'Y' : 'N';
        $allow_edit_contact_info = isset($request->allow_edit_contact_info) ? 'Y' : 'N';
        $allow_edit_contact_phone_number = isset($request->allow_edit_contact_phone_number) ? 'Y' : 'N';
        $display_dialable_contacts = isset($request->display_dialable_contacts) ? 'Y' : 'N';
        $allow_waiting_calls_view = isset($request->allow_waiting_calls_view) ? 'Y' : 'N';
        $show_call_log = isset($request->show_call_log) ? 'Y' : 'N';
        $allow_transfer_to_agent = isset($request->allow_transfer_to_agent) ? 'Y' : 'N';
        $agent_xfer_blind_transfer = isset($request->agent_xfer_blind_transfer) ? 'Y' : 'N';
        $allow_conference_call = isset($request->allow_conference_call) ? 'Y' : 'N';
        $allow_direct_extension_inbounds = isset($request->allow_direct_extension_inbounds) ? 'Y' : 'N';
        $allow_transfers_to_number = isset($request->allow_transfers_to_number) ? 'Y' : 'N';
        $allow_transfers_to_queue = isset($request->allow_transfers_to_queue) ? 'Y' : 'N';


        $compaign = "";
        $transfer_caller_id = "";
        $manual_dial_caller_id = "";
        if(isset($request->all_allowed_profiles)) {
            $compaign = '-ALL-CAMPAIGNS-';
        } else {
            $compaign = implode('-', $request->allowed_profiles);
        }
        

        if(isset($request->transfer_caller_id) ){
            $transfer_caller_id = implode('-', $request->transfer_caller_id);

        }

        if(isset($request->manual_dial_caller_id) ){
            $manual_dial_caller_id = implode('-', $request->manual_dial_caller_id);

        }




        $OrganizationServices = OrganizationServices::find($request->organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/UserGroups/API.php';
        // POST data
        $postData = [
            'Action' => 'AddUserGroup',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'user_group'=> $request->role,
            'group_name'=> $request->user_group,
            'group_level'=> 9,

            'allowed_profiles' => $compaign,
            'transfer_caller_id'=> $transfer_caller_id,
            'manual_dial_caller_id'=> $manual_dial_caller_id,
        
            "select_inbound_upon_login" => $select_inbound_upon_login,
            "select_auto_outbound_upon_login" => $select_auto_outbound_upon_login,
            "allow_auto_outbound" => $allow_auto_outbound,
            "allow_manual_calls" => $allow_manual_calls,
            "allow_schedule_callbacks" => $allow_schedule_callbacks,
            "allow_personal_callbacks" => $allow_personal_callbacks,
            "allow_edit_contact_info" => $allow_edit_contact_info,
            "allow_edit_contact_phone_number" => $allow_edit_contact_phone_number,
            "display_dialable_contacts" => $display_dialable_contacts,
            "allow_waiting_calls_view" => $allow_waiting_calls_view,
            "show_call_log" => $show_call_log,
            "allow_transfer_to_agent" => $allow_transfer_to_agent,
            "agent_xfer_blind_transfer" => $agent_xfer_blind_transfer,
            "allow_conference_call" => $allow_conference_call,
            "allow_direct_extension_inbounds" => $allow_direct_extension_inbounds,
            "allow_transfers_to_number" => $allow_transfers_to_number,
            "allow_transfers_to_queue" => $allow_transfers_to_queue
            
        ];

        // dd($postData);
        $ch = curl_init($apiEndpoint);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute cURL session and get the response
        $response = curl_exec($ch);
    
        // Close cURL session
        curl_close($ch);
        
        $api_responce =  json_decode($response, true);

        if($api_responce['result'] == 'success'){
            return redirect()->back()->with('success', 'Agent Role  Added successfully');
           }else{
            return redirect()->back()->with('error', 'Some thing Went Wrong ');
           }


    }



    // edit 
    function edit($service , $organization_service_id,$user_group)
    {

        $agent_edit_data = '';
        $all_compaign = '';
        $agnet_role_responce = $this->edit_get_agent_roles($organization_service_id,$user_group);
        if ($agnet_role_responce['result'] == 'success') {
            $agent_edit_data =   $agnet_role_responce['data'];

            // dd($agent_edit_data);
        }

        $compaign_responce = $this->get_all_Campaigns($organization_service_id);


        if ($compaign_responce['result'] == 'success') {
            $all_compaign =   $compaign_responce;

            // dd($all_compaign);
        }
        return view('dialer.admin.agentrole.edit',compact('service','agent_edit_data' ,'organization_service_id' ,'all_compaign'));
    }
    
    function edit_get_agent_roles($organization_service_id,$user_group) {
        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/UserGroups/API.php';
        // POST data
        $postData = [
            'Action' => 'GetUserGroupInfo',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'user_group' =>$user_group
        ];
        $ch = curl_init($apiEndpoint);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute cURL session and get the response
        $response = curl_exec($ch);
    
        // Close cURL session
        curl_close($ch);
        
        return json_decode($response, true);
    }


    function get_all_Campaigns ($organization_service_id) {
        $OrganizationServices = OrganizationServices::find($organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/Campaigns/API.php';
        // POST data
        $postData = [
            'Action' => 'GetAllCampaigns',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json'
        ];
        $ch = curl_init($apiEndpoint);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute cURL session and get the response
        $response = curl_exec($ch);
    
        // Close cURL session
        curl_close($ch);
        
        return json_decode($response, true);
    }
    // end edit 


    function agentRoleUpdate(Request $request) {


        // dd($request->all());
        $select_inbound_upon_login = isset($request->select_inbound_upon_login) ? 'Y' : 'N';
        $select_auto_outbound_upon_login = isset($request->select_auto_outbound_upon_login) ? 'Y' : 'N';
        $allow_auto_outbound = isset($request->allow_auto_outbound) ? 'Y' : 'N';
        $allow_manual_calls = isset($request->allow_manual_calls) ? 'Y' : 'N';
        $allow_schedule_callbacks = isset($request->allow_schedule_callbacks) ? 'Y' : 'N';
        $allow_personal_callbacks = isset($request->allow_personal_callbacks) ? 'Y' : 'N';
        $allow_edit_contact_info = isset($request->allow_edit_contact_info) ? 'Y' : 'N';
        $allow_edit_contact_phone_number = isset($request->allow_edit_contact_phone_number) ? 'Y' : 'N';
        $display_dialable_contacts = isset($request->display_dialable_contacts) ? 'Y' : 'N';
        $allow_waiting_calls_view = isset($request->allow_waiting_calls_view) ? 'Y' : 'N';
        $show_call_log = isset($request->show_call_log) ? 'Y' : 'N';
        $allow_transfer_to_agent = isset($request->allow_transfer_to_agent) ? 'Y' : 'N';
        $agent_xfer_blind_transfer = isset($request->agent_xfer_blind_transfer) ? 'Y' : 'N';
        $allow_conference_call = isset($request->allow_conference_call) ? 'Y' : 'N';
        $allow_direct_extension_inbounds = isset($request->allow_direct_extension_inbounds) ? 'Y' : 'N';
        $allow_transfers_to_number = isset($request->allow_transfers_to_number) ? 'Y' : 'N';
        $allow_transfers_to_queue = isset($request->allow_transfers_to_queue) ? 'Y' : 'N';
        

        $compaign = "";
        $transfer_caller_id = "";
        $manual_dial_caller_id = "";
        if(isset($request->all_allowed_profiles) ){
            $compaign = '-ALL-CAMPAIGNS-';
        }else{
            $compaign = implode('-', $request->allowed_profiles);
        }

        if(isset($request->transfer_caller_id) ){
            $transfer_caller_id = implode('-', $request->transfer_caller_id);

        }

        if(isset($request->manual_dial_caller_id) ){
            $manual_dial_caller_id = implode('-', $request->manual_dial_caller_id);

        }




        $OrganizationServices = OrganizationServices::find($request->organization_service_id);
        $phpArray = json_decode($OrganizationServices->connection_parameters, true);
        $apiEndpoint = 'https://' . $phpArray['server_url'] . '/APIv2/UserGroups/API.php';
        // POST data
        $postData = [
            'Action' => 'EditUserGroup',
            'apiUser' =>  $phpArray['api_user'],
            'apiPass' =>  $phpArray['api_pass'],
            'session_user' =>  auth()->user()->email,
            'responsetype' => 'json',
            'allowed_profiles' => $compaign,
            'transfer_caller_id'=> $transfer_caller_id,
            'manual_dial_caller_id'=> $manual_dial_caller_id,
            'user_group'=> $request->role,
            "select_inbound_upon_login" => $select_inbound_upon_login,
            "select_auto_outbound_upon_login" => $select_auto_outbound_upon_login,
            "allow_auto_outbound" => $allow_auto_outbound,
            "allow_manual_calls" => $allow_manual_calls,
            "allow_schedule_callbacks" => $allow_schedule_callbacks,
            "allow_personal_callbacks" => $allow_personal_callbacks,
            "allow_edit_contact_info" => $allow_edit_contact_info,
            "allow_edit_contact_phone_number" => $allow_edit_contact_phone_number,
            "display_dialable_contacts" => $display_dialable_contacts,
            "allow_waiting_calls_view" => $allow_waiting_calls_view,
            "show_call_log" => $show_call_log,
            "allow_transfer_to_agent" => $allow_transfer_to_agent,
            "agent_xfer_blind_transfer" => $agent_xfer_blind_transfer,
            "allow_conference_call" => $allow_conference_call,
            "allow_direct_extension_inbounds" => $allow_direct_extension_inbounds,
            "allow_transfers_to_number" => $allow_transfers_to_number,
            "allow_transfers_to_queue" => $allow_transfers_to_queue
            
        ];
        $ch = curl_init($apiEndpoint);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute cURL session and get the response
        $response = curl_exec($ch);
    
        // Close cURL session
        curl_close($ch);
        
        $api_responce =  json_decode($response, true);

        // dd($api_responce);

        if($api_responce['result'] == 'success'){
            return redirect('services/dialer/agents/agent-role/'.$request->organization_service_id)->with('success', 'Agent Role  Updated successfully');
           }else{
            return redirect('services/dialer/agents/agent-role/'.$request->organization_service_id)->with('error', 'Something Went Wrong ');
           }


    }




    
}

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

        // dd($request->all());

        $select_inbound_upon_login = isset($request->select_inbound_upon_login) ? 'Y' : 'N';
        $select_auto_outbound_upon_login = isset($request->select_auto_outbound_upon_login) ? 'Y' : 'N';
        $allow_auto_outbound = isset($request->allow_auto_outbound) ? 'Y' : 'N';
        $allow_manual_calls = isset($request->allow_manual_calls) ? 'Y' : 'N';
        $allow_schedule_callbacks = isset($request->allow_schedule_callbacks) ? 'Y' : 'N';
        $allow_personal_callbacks = isset($request->allow_personal_callbacks) ? 'Y' : 'N';
        $allow_alter_contact = isset($request->allow_alter_contact) ? 'Y' : 'N';
        $allow_alter_phone_number = isset($request->allow_alter_phone_number) ? 'Y' : 'N';
        $display_dialable_contacts = isset($request->display_dialable_contacts) ? 'Y' : 'N';
        $allow_waiting_calls_view = isset($request->allow_waiting_calls_view) ? 'Y' : 'N';
        $agent_call_log_view = isset($request->agent_call_log_view) ? 'Y' : 'N';
        $agent_xfer_consultative = isset($request->agent_xfer_consultative) ? 'Y' : 'N';
        $agent_xfer_blind_transfer = isset($request->agent_xfer_blind_transfer) ? 'Y' : 'N';
        $agent_xfer_dial_with_customer = isset($request->agent_xfer_dial_with_customer) ? 'Y' : 'N';


        $compaign = "";
        $transfer_caller_id = "";
        $manual_dial_caller_id = "";
        if(isset($request->all_allowed_compaigns) ){
            $compaign = '-ALL-CAMPAIGNS-';
        }else{
            $compaign = implode('-', $request->allowed_campaigns);
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
            'user_group'=> $request->user_group,
            'group_name'=> $request->user_group,
            'group_level'=> 9,

            'allowed_campaigns' => $compaign,
            'transfer_caller_id'=> $transfer_caller_id,
            'manual_dial_caller_id'=> $manual_dial_caller_id,
        
            "select_inbound_upon_login" => $select_inbound_upon_login,
            "select_auto_outbound_upon_login" => $select_auto_outbound_upon_login,
            "allow_auto_outbound" => $allow_auto_outbound,
            "allow_manual_calls" => $allow_manual_calls,
            "allow_schedule_callbacks" => $allow_schedule_callbacks,
            "allow_personal_callbacks" => $allow_personal_callbacks,
            "allow_alter_contact" => $allow_alter_contact,
            "allow_alter_phone_number" => $allow_alter_phone_number,
            "display_dialable_contacts" => $display_dialable_contacts,
            "allow_waiting_calls_view" => $allow_waiting_calls_view,
            "agent_call_log_view" => $agent_call_log_view,
            "agent_xfer_consultative" => $agent_xfer_consultative,
            "agent_xfer_blind_transfer" => $agent_xfer_blind_transfer,
            "agent_xfer_dial_with_customer" => $agent_xfer_dial_with_customer,
            
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
        $allow_alter_contact = isset($request->allow_alter_contact) ? 'Y' : 'N';
        $allow_alter_phone_number = isset($request->allow_alter_phone_number) ? 'Y' : 'N';
        $display_dialable_contacts = isset($request->display_dialable_contacts) ? 'Y' : 'N';
        $allow_waiting_calls_view = isset($request->allow_waiting_calls_view) ? 'Y' : 'N';
        $agent_call_log_view = isset($request->agent_call_log_view) ? 'Y' : 'N';
        $agent_xfer_consultative = isset($request->agent_xfer_consultative) ? 'Y' : 'N';
        $agent_xfer_blind_transfer = isset($request->agent_xfer_blind_transfer) ? 'Y' : 'N';
        $agent_xfer_dial_with_customer = isset($request->agent_xfer_dial_with_customer) ? 'Y' : 'N';
        

        $compaign = "";
        $transfer_caller_id = "";
        $manual_dial_caller_id = "";
        if(isset($request->all_allowed_compaigns) ){
            $compaign = '-ALL-CAMPAIGNS-';
        }else{
            $compaign = implode('-', $request->allowed_campaigns);
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
            'allowed_campaigns' => $compaign,
            'transfer_caller_id'=> $transfer_caller_id,
            'manual_dial_caller_id'=> $manual_dial_caller_id,
            'user_group'=> $request->user_group,
            "select_inbound_upon_login" => $select_inbound_upon_login,
            "select_auto_outbound_upon_login" => $select_auto_outbound_upon_login,
            "allow_auto_outbound" => $allow_auto_outbound,
            "allow_manual_calls" => $allow_manual_calls,
            "allow_schedule_callbacks" => $allow_schedule_callbacks,
            "allow_personal_callbacks" => $allow_personal_callbacks,
            "allow_alter_contact" => $allow_alter_contact,
            "allow_alter_phone_number" => $allow_alter_phone_number,
            "display_dialable_contacts" => $display_dialable_contacts,
            "allow_waiting_calls_view" => $allow_waiting_calls_view,
            "agent_call_log_view" => $agent_call_log_view,
            "agent_xfer_consultative" => $agent_xfer_consultative,
            "agent_xfer_blind_transfer" => $agent_xfer_blind_transfer,
            "agent_xfer_dial_with_customer" => $agent_xfer_dial_with_customer,
            
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
            return redirect('services/dialer/agents/agent-role/'.$request->organization_service_id)->with('error', 'Some thing Went Wrong ');
           }


    }




    
}

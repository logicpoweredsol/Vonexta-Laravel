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

        $userGroupsResults = $this->get_agent_roles($organization_servicesID);
        $userGroups = "";

        if ($userGroupsResults['result'] == 'success') {
            $userGroups =  $userGroupsResults;
        }
        // dd($userGroups);

        

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


   

        $all_agents = "";
        // $transfer_caller_id = "";
        // $manual_dial_caller_id = "";
        if(isset($request->allow_all_agents)) {
            $all_agents = '--ALL-GROUPS--';

        } else {
            if (is_array($request->allowed_profiles)) {
                $all_agents = implode('-', $request->allowed_profiles);
            } else {
                // Handle the case when $request->allowed_profiles is not an array
                // For example, set a default value or throw an error
            }
        }
        

        // if(isset($request->transfer_caller_id) ){
        //     $transfer_caller_id = implode('-', $request->transfer_caller_id);

        // }

        // if(isset($request->manual_dial_caller_id) ){
        //     $manual_dial_caller_id = implode('-', $request->manual_dial_caller_id);

        // }




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
            // 'group_name'=> $request->user_group,
            'group_level'=> 9,

            // 'allowed_profiles' => $compaign,
            // 'transfer_caller_id'=> $transfer_caller_id,
            // 'manual_dial_caller_id'=> $manual_dial_caller_id,
        


            // "allow_manual_calls" => $allow_manual_calls,
           
            // "allow_personal_callbacks" => $allow_personal_callbacks,
            
           
            
            // "allow_waiting_calls_view" => $waiting_inbound_calls,
            // "agent_call_log_view" => $show_call_log,
           
            
            // "allow_conference_call" => $allow_conference_call,
            // "allow_transfers_to_number" => $allow_transfers_to_number,

           
                "select_inbound_upon_login" => 'N',
                "select_auto_outbound_upon_login" => 'N',
                "allow_auto_outbound" => 'N',

                "allow_transfers_to_queue" => 'N',
                "agent_xfer_consultative" => 'N',
                "agent_xfer_blind_transfer" => 'N',
                "agent_status_viewable_groups" => 'N',
                "allow_direct_extension_inbounds" => 'N',

                "allow_schedule_callbacks" => 'N',
                "agent_call_log_view" => 'N',
                "allow_waiting_calls_view" => 'N',
                "display_dialable_contacts" => 'N',
                "allow_alter_phone_number" => 'N',
                "allow_alter_contact" => 'N'
                

            
        ];


        

        foreach ($request->login_permissions as $i => $data) {
            if ($request->login_permissions[$i] == 'select_inbound_queus') {
                $postData["select_inbound_upon_login"] = 'Y';
            } elseif ($request->login_permissions[$i] == 'select_auto_outbound') {
                $postData["select_auto_outbound_upon_login"] = 'Y';
            } elseif ($request->login_permissions[$i] == 'allow_auto_outbound') {
                $postData["allow_auto_outbound"] = 'Y';
            } else {
                $postData["select_inbound_upon_login"] = 'N';
                $postData["select_auto_outbound_upon_login"] = 'N';
                $postData["allow_auto_outbound"] = 'N';
            }
        }

        

    

        
        foreach($request->transfer_permissions as $i => $data1) {
            switch($request->transfer_permissions[$i]) {
                case 'allow_transfers_to_queue':
                    $postData["allow_transfers_to_queue"] = 'Y';
                    break;
                default:
                    $postData["allow_transfers_to_queue"] = 'N';
            }
        
            switch($request->transfer_permissions[$i]) {
                case 'allow_transfer_to_agent':
                    $postData["agent_xfer_consultative"] = 'Y';
                    break;
                default:
                    $postData["agent_xfer_consultative"] = 'N';
            }
        
            switch($request->transfer_permissions[$i]) {
                case 'blind_transfers':
                    $postData["agent_xfer_blind_transfer"] = 'Y';
                    break;
                default:
                    $postData["agent_xfer_blind_transfer"] = 'N';
            }
        
            switch($request->transfer_permissions[$i]) {
                case '3-way_calls':
                    $postData["agent_status_viewable_groups"] = 'Y';
                    break;
                default:
                    $postData["agent_status_viewable_groups"] = 'N';
            }
        
            switch($request->transfer_permissions[$i]) {
                case 'receive_direct_calls':
                    $postData["allow_direct_extension_inbounds"] = 'Y';
                    break;
                default:
                    $postData["allow_direct_extension_inbounds"] = 'N';
            }
        }
        // dd($postData);
        
        
        foreach($request->permissions as $i=>$data2)
        {

            if($request->permissions[$i] == 'schedule_callbacks'){
                $postData["allow_schedule_callbacks"] = 'Y';
            }else{
                $postData["allow_schedule_callbacks"] = 'N';
            }

            if($request->permissions[$i] == 'personal_call_backs'){
                $postData["agent_xfer_consultative"] = 'Y';
            }else{
                $postData["agent_xfer_consultative"] = 'N';
            }


            if($request->permissions[$i] == 'allow_manual_calls'){
                $postData["agent_xfer_blind_transfer"] = 'Y';
            }else{
                $postData["agent_xfer_blind_transfer"] = 'N';
            }

            if($request->permissions[$i] == 'edit_contact_info'){
                $postData["agent_status_viewable_groups"] = 'Y';
            }else{
                $postData["agent_status_viewable_groups"] = 'N';
            }

            if($request->permissions[$i] == '3-way_calls'){
                $postData["allow_direct_extension_inbounds"] = 'Y';
            }else{
                $postData["allow_direct_extension_inbounds"] = 'N';
            }
            
            if($request->permissions[$i] == 'edit_contact_p_number'){
                $postData["allow_alter_phone_number"] = 'Y';
            }else{
                $postData["allow_alter_phone_number"] = 'N';
            }
            if($request->permissions[$i] == 'show_call_log'){
                $postData["agent_call_log_view"] = 'Y';
            }else{
                $postData["agent_call_log_view"] = 'N';
            }

            if($request->permissions[$i] == 'show_disable_contacts'){
                $postData["display_dialable_contacts"] = 'Y';
            }else{
                $postData["display_dialable_contacts"] = 'N';
            }

            if($request->permissions[$i] == 'waiting_inbound_calls'){
                $postData["allow_waiting_calls_view"] = 'Y';
            }else{
                $postData["allow_waiting_calls_view"] = 'N';
            }
            
            
        }

        
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

            dd($agent_edit_data);

            
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
        $select_inbound_queus = isset($request->select_inbound_queus) ? 'Y' : 'N';
        $select_auto_outbound = isset($request->select_auto_outbound) ? 'Y' : 'N';
        $allow_auto_outbound = isset($request->allow_auto_outbound) ? 'Y' : 'N';
        $allow_manual_calls = isset($request->allow_manual_calls) ? 'Y' : 'N';
        $schedule_callbacks = isset($request->schedule_callbacks) ? 'Y' : 'N';
        $allow_personal_callbacks = isset($request->allow_personal_callbacks) ? 'Y' : 'N';
        $edit_contact_info = isset($request->edit_contact_info) ? 'Y' : 'N';
        $edit_contact_p_number = isset($request->edit_contact_p_number) ? 'Y' : 'N';
        $show_disable_contacts = isset($request->show_disable_contacts) ? 'Y' : 'N';
        $waiting_inbound_calls = isset($request->waiting_inbound_calls) ? 'Y' : 'N';
        $show_call_log = isset($request->show_call_log) ? 'Y' : 'N';
        $allow_transfer_to_agent = isset($request->allow_transfer_to_agent) ? 'Y' : 'N';
        $blind_transfers = isset($request->blind_transfers) ? 'Y' : 'N';
        $allow_conference_call = isset($request->allow_conference_call) ? 'Y' : 'N';
        $receive_direct_calls = isset($request->receive_direct_calls) ? 'Y' : 'N';
        $allow_transfers_to_number = isset($request->allow_transfers_to_number) ? 'Y' : 'N';
        $allow_transfers_to_queue = isset($request->allow_transfers_to_queue) ? 'Y' : 'N';
        

        $compaign = "";
        $transfer_caller_id = "";
        $manual_dial_caller_id = "";
        if(isset($request->all_allowed_profiles)) {
            if (is_array($request->all_allowed_profiles)) {
                $compaign = '-ALL-CAMPAIGNS-';
            } else {
                // Handle the case where $request->all_allowed_profiles is not an array
            }
        } else {
            // $compaign = implode('-', $request->all_allowed_profiles);
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
            "select_inbound_upon_login" => $select_inbound_queus,
            "select_auto_outbound_upon_login" => $select_auto_outbound,
            "allow_auto_outbound" => $allow_auto_outbound,
            "allow_manual_calls" => $allow_manual_calls,
            "allow_schedule_callbacks" => $schedule_callbacks,
            "allow_personal_callbacks" => $allow_personal_callbacks,
            "allow_alter_contact" => $edit_contact_info,
            "allow_alter_phone_number" => $edit_contact_p_number,
            "display_dialable_contacts" => $show_disable_contacts,
            "allow_waiting_calls_view" => $waiting_inbound_calls,
            "agent_call_log_view" => $show_call_log,
            "agent_xfer_consultative" => $allow_transfer_to_agent,
            "agent_xfer_blind_transfer" => $blind_transfers,
            "allow_conference_call" => $allow_conference_call,
            "allow_direct_extension_inbounds" => $receive_direct_calls,
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

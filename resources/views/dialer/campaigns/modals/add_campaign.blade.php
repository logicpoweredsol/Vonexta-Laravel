<!-- Modals... -->
<div class="modal fade" id="modalNewCampaign">
        <div class="bs-stepper" id="newCampaignWizard">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Campaign</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- your steps here -->
                            <div class="step" data-target="#campaignInformation">
                                <button type="button" class="step-trigger" role="tab" aria-controls="campaignInformation" id="campaignInformation-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Campaign Information</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#additionaldetails">
                                <button type="button" class="step-trigger" role="tab" aria-controls="additionaldetails" id="additionaldetails-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Additional Details</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <!-- your steps content here -->
                            <div id="campaignInformation" class="content" role="tabpanel" aria-labelledby="campaignInformation-trigger">
                                <div class="form-group row">
                                    <label for="campaign_type" class="col-sm-4 col-form-label">Campaign Type</label>
                                    <div class="col-sm-8">
                                        <select id="campaign_type" name="campaign_type" class="form-control" aria-invalid="false">
                                            <option value="" selected>Select Campaign Type</option>
				    						<option value="outbound">Outbound</option>
                                            <option value="inbound">Inbound</option>
				    						<option value="blended">Blended</option>
				    						<option value="survey">SURVEY PRESS 1</option>
				    						<option value="copy">Copy from Campaign</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_id" class="col-sm-4 col-form-label">Campaign ID</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="campaign_id" id="campaign_id">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campaign_name" class="col-sm-4 col-form-label">Campaign Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="campaign_name" id="campaign_name">
                                    </div>
                                </div>
                                <div id="campaign_type_outbound">
                                    <div class="form-group row">
                                        <label for="dial_prefix" class="col-sm-4 col-form-label">Carrier use for Campaign</label>
                                        <div class="col-sm-8">
                                            <select name="dial_prefix" id="dial_prefix" class="form-control" aria-invalid="false">
                                                <option value="CUSTOM">CUSTOM DIAL PREFIX</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="custom_prefix" class="col-sm-4 col-form-label">Custom Prefix</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="custom_prefix" id="custom_prefix" value="9" minlength="1" maxlength="20">
                                        </div>
                                    </div>
                                </div>
                                <div id="campaign_type_inbound">
                                    <div class="form-group row">
                                        <label for="did_tfn_extension" class="col-sm-4 col-form-label">DID/TFN Extension</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="did_tfn_extension" id="did_tfn_extension" value="1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="call_route" class="col-sm-4 col-form-label">Call Route</label>
                                        <div class="col-sm-8">
                                            <select id="call_route" name="call_route" class="form-control" aria-invalid="false">
                                                <option value="" selected>Select Call Route</option>
                                                <option value="INGROUP">Ingroup (Default)</option>
                                                <option value="IVR">IVR (Call Menu)</option>
                                                <!--<option value="AGENT">AGENT</option>-->
                                                <!--<option value="VOICEMAIL">VOICEMAIL</option>-->
				                            </select>
                                        </div>
                                    </div>
                                    <div id="call_route_ingroup">
                                        <div class="form-group row">
                                            <label for="ingroup_text" class="col-sm-4 col-form-label">Ingroup</label>
                                            <div class="col-sm-8">
                                                <select id="ingroup_text" name="ingroup_text" class="form-control" aria-invalid="false">
                                                    <option value="AGENTDIRECT">Single Agent Direct Queue</option>
                                                    <option value="AGENTDIRECT_CHAT">Agent Direct Queue for Chats</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="group_color" class="col-sm-4 col-form-label">Group Color</label>
                                            <div class="col-sm-8">
                                                <input type="color" class="form-control colorpicker colorpicker-element" name="group_color" id="group_color">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="call_route_ivr">
                                        <div class="form-group row">
                                            <label for="ivr_text" class="col-sm-4 col-form-label">IVR</label>
                                            <div class="col-sm-8">
                                                <select id="ivr_text" name="ivr_text" class="form-control">
                                                    <option value="defaultcallmenu">Default Call Menu</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="campaign_type_survey">
                                    <div class="form-group row">
                                        <label for="no_channels" class="col-sm-4 col-form-label">Number of Channels</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="no_channels" id="no_channels">
                                        </div>
                                    </div>
                                </div>
                                <div id="campaign_type_copy">
                                    <div class="form-group row">
                                        <label for="copy_from_campaign" class="col-sm-4 col-form-label">Copy From Campaign</label>
                                        <div class="col-sm-8">
                                            <select id="copy_from_campaign" name="copy_from_campaign" class="form-control valid" aria-invalid="false">
                                                <option value="TESTCAMP">TESTCAMP - TEST CAMPAIGN</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="additionaldetails" class="content" role="tabpanel" aria-labelledby="additionaldetails-trigger">
                                <div class="form-group row">
                                    <label for="user" class="col-sm-2 col-form-label">User ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="user" id="user">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" name="email" id="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="user_group" class="col-sm-2 col-form-label">User Group</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="user_group" id="user_group">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="full_name" class="col-sm-2 col-form-label">Full Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="full_name" id="full_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pass" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="pass" id="pass">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="confirm_pass" class="col-sm-2 col-form-label">Confirm Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="confirm_pass" id="confirm_pass">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="active" class="col-sm-2 col-form-label">Active</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="active" id="active">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="server_ip" class="col-sm-2 col-form-label">Server IP</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="server_ip" id="server_ip">
                                            <option value="127.0.0.1">127.0.0.1 - Vonexta meetme server</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="vonexta-modal-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4" style="text-align:left;">
                                <button class="btn btn-md btn-block btn-secondary" id="btnCampaignPreviousStep" style="display:none;">Previous</button>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4"></div>
                            <div class="col-sm-12 col-md-4 col-lg-4" style="text-align:right;">
                                <button class="btn btn-md btn-block btn-primary" id="btnCampaignNextStep">Next</button>
                                <button class="btn btn-md btn-block btn-success" type="submit" id="btnCampaignSubmit" style="display:none;">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
    <!-- /.Modals -->
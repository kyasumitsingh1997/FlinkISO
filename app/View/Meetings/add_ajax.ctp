<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style>
.ui-tabs .ui-tabs-panel { padding: 0px !important; border: 0px !important;}
</style>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[Meeting][employee_by]'){
                $(element).next().after(error);            
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function (form) {

            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
        }
    });
    function noemail(n){
        if(n == 1){
            $("#MeetingShowOnTimeline").attr('disabled',true);
            $("#MeetingSendEmail").attr('disabled',true);
        }else{
            $("#MeetingShowOnTimeline").attr('disabled',false);
            $("#MeetingSendEmail").attr('disabled',false);
        }
    }
    $().ready(function () {
        $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        $("#MeetingDepartmentDepartmentId").change(function () {
            var selected = $('#MeetingDepartmentDepartmentId').val()
            $.ajax({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_department_employee/" + selected,
                success: function (data, result) {
                    $('#MeetingEmployeeEmployeeId').find('option').remove().end().append(data).trigger('chosen:updated');
                }
            });
        });

        $('#MeetingAddAjaxForm').validate({
            rules: {
                "data[Meeting][employee_by]": {
                    greaterThanZero: true,
                },
                // "data[MeetingBranch][branch_id]": {
                //     greaterThanZero: true,
                // },
                // "MeetingDepartment.department_id[]": {
                //     required: true,
                //     greaterThanZero: true,
                // },
                // "MeetingEmployee.employee_id[]": {
                //     required: true,
                //     greaterThanZero: true,
                // },
            }
        });

        $('#MeetingBranchBranchId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        // $('#MeetingDepartmentDepartmentId').change(function () {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#MeetingEmployeeBy').change(function () {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#MeetingEmployeeEmployeeId').change(function () {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });


    });

    function addAgendaDiv(args) {
        var i = parseInt($('#MeetingAgendaNumber').val());
        $('#MeetingAgendaNumber').val();
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_meeting_topics/" + i, function (data) {
            $('#meetingTopics_ajax').append(data);
        });
        i = i + 1;
        $('#MeetingAgendaNumber').val(i);
    }

    function removeAgendaDiv(i) {
        var r = confirm("Are you sure to remove this agenda?");
        if (r == true) {
            $('#meetingTopics_ajax' + i).remove();
        }

    }
</script>

<div id="meetings_ajax">
    <?php
        echo $this->Session->flash();
        $i = 0;
        $j = 1;
    ?>
    <div class="nav">
        <div class="meetings form col-md-8">
            <h4><?php echo __('Add Meeting'); ?></h4>
            <?php echo $this->Form->create('Meeting', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <div class="row">
                <fieldset><legend><h5><?php echo __('Meeting Details'); ?></h5></legend>
                    <div class="col-md-6"><?php echo $this->Form->input('title', array('label' => __('Title'))); ?></div>
                    <div class="col-md-3"><?php echo $this->Form->input('meeting_type', array('options'=>$meetingType,'type'=>'radio', 'onClick'=>'noemail(this.value)', 'default'=>0, 'label' => __('Meeting Type'))); ?></div>
                    
                    <div class="col-md-3"><?php echo $this->Form->input('previous_meeting_date', array('options'=>$meetings, 'label' => __('Previous Meeting'))); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('standard_id', array('name' => 'data[Meeting][standard_id][]', 'type' => 'select', 'multiple', 'style' => 'width:100%', 'label' => __('Relevant Standards and Regulations'))); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('meeting_details', array('label' => __('Meeting Details'))); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('MeetingBranch.branch_id', array('options' => $PublishedBranchList, 'style' => 'width:100%', 'label' => __('Meeting Place'))); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('external_meeting_place', array('rows'=>'1', 'label' => __('Meeting Place (External)'))); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('employee_by', array('type' => 'select', 'options' => $PublishedEmployeeList, 'label' => __('Meeting Secretary'))); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('MeetingDepartment.department_id', array('name' => 'MeetingDepartment.department_id[]', 'type' => 'select', 'multiple', 'options' => $PublishedDepartmentList, 'style' => 'width:100%', 'label' => __('Department'))); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('MeetingEmployee.employee_id', array('name' => 'MeetingEmployee.employee_id[]', 'type' => 'select', 'multiple',  'label' => __('Invitees'), 'style' => 'width:100%')); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('external_invities', array()); ?></div>

                    <div class="col-md-6"><?php echo $this->Form->input('supplier_registration_id', array('label' => __('Supplier/Vendor'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('customer_id', array('label' => __('Customer'))); ?></div>
                    
                    <div class="col-md-6"><?php echo $this->Form->input('scheduled_meeting_from', array('label' => __('Meeting Start Time'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('scheduled_meeting_to', array('label' => __('Meeting End Time'))); ?></div>

                </fieldset>
            </div>
            <br/><br/>
            <div id="meetingTopics_ajax">
                <?php
                    $topics = array();
                    $topics[0] = array("title"=> "Follow-up actions from previous management reviews","text"=>"");
                    $topics[1] = array("title"=>"Suitability & effectiveness of Q.M.S, Quality policy & Quality objectives","text"=>"");
                    $topics[2] = array("title"=>"ISO 9001:2008 Certification Status","text"=>"");
                    $topics[3] = array("title"=>"Manual, policy and procedures status","text"=>"");
                    $topics[4] = array("title"=>"Results of internal audits – Trends, audit schedules","text"=>"");
                    $topics[5] = array("title"=>"Training & motivational aspects & Resource requirements.","text"=>"");
                    $topics[6] = array("title"=>"Process Performance and product / service conformity","text"=>"");
                    $topics[7] = array("title"=>"Changes affecting the Quality Management System","text"=>"");
                    $topics[8] = array("title"=>"Recommendations for improvement","text"=>"");
                    
                    foreach ($topics as $topic):
                ?>
                    <div id="meetingTopics_ajax<?php echo $i; ?>">
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading"><?php echo __('Agenda'); ?><span class="glyphicon glyphicon-remove pull-right" style="font-size:20px;background:none"type="button" onclick='removeAgendaDiv(<?php echo $i; ?>)'></span></div>
                                <div class="panel-body">
                                    <fieldset>
                                        <div class="col-md-12"><?php echo $this->Form->input('MeetingTopic.' . $i . '.topic', array('value' => $topic['title'], 'label' => __('Topic'))); ?></div>
                                        <div class="col-md-12"><?php echo $this->Form->input('MeetingTopic.' . $i . '.topic_text', array('value' => $topic['text'], 'label' => __('Topic Details'))); ?></div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $i++; $j++; ?>
                <?php endforeach ?>
            </div>

            <div class="col-md-6"><?php echo $this->Form->input('agendaNumber', array('type' => 'hidden', 'value' => $i)); ?></div>
            <div class="row">
                <?php echo $this->Form->button('Add New Agenda', array('label' => false, 'type' => 'button', 'div' => false, 'class' => 'btn btn-md btn-info pull-right', 'onclick' => 'addAgendaDiv()', 'style' => 'margin-bottom:25px;')); ?>
            </div>
<div id="meeting_tabs" class="row">
            <ul>
                <li><a href="#dc_requests">Document Change Requests</a></li>
                <li><a href="#ccs">Customer Complaints</a></li>
                <li><a href="#ncs">CAPA</a></li>
                <li><a href="#ses">Supplier Evaluations</a></li>
                <li><a href="#pas">Project Activites</a></li>
            </ul>
<div id="dc_requests">
            <div class="">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo __('Would you like to include these recent Document Change Requests in your current meeting?'); ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive">
                            <tr>
                                <th><?php echo __('Add?') ?></th>
                                <th><?php echo __('Doc Number') ?></th>
                                <th><?php echo __('Title') ?></th>
                                <th><?php echo __('Request From') ?></th>                                
                            </tr>
                            <?php $new_i = 0; ?>
                            <?php foreach ($allChangeAdditionDeletionRequests as $requests): 
                                if($requests['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1){?>
                                <tr class="text-success">
                                <?php }else{?>
                                <tr class="text-danger">
                                <?php } ?>
                                    <td><?php echo $this->Form->checkbox('AdditionalTopics.ChangeAdditionDeletionRequest.' . $new_i . '.change_addition_deletion_request_id', array('value' => $requests['ChangeAdditionDeletionRequest']['id'], 'checked' => $checked)) ?>
                                    </td>
                                    <td><?php 
                                            if($requests['ChangeAdditionDeletionRequest']['file_upload_id'] == -1 or $requests['ChangeAdditionDeletionRequest']['file_upload_id'] == NULL){ 
                                                echo $requests['MasterListOfFormat']['document_number'];
                                            }
                                            else{
                                                echo '--';
                                            } ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if($requests['ChangeAdditionDeletionRequest']['file_upload_id'] == -1 or $requests['ChangeAdditionDeletionRequest']['file_upload_id'] == NULL){
                                                echo $requests['MasterListOfFormat']['title']; 
                                            }else{
                                                echo $requests['ChangeAdditionDeletionRequest']['title'];
                                    }?>
                                    </td>
                                    <td>
                                        <?php  if ($requests['ChangeAdditionDeletionRequest']['branch_id'] != -1 && $requests['ChangeAdditionDeletionRequest']['branch_id'] != NULL) echo "<strong>Branch</strong><br/>" . $requests['Branch']['name']; ?>
                                        <?php if ($requests['ChangeAdditionDeletionRequest']['department_id'] != -1 && $requests['ChangeAdditionDeletionRequest']['department_id'] != NULL) echo "<strong>Department</strong><br/>" . $requests['Department']['name']; ?>
                                        <?php if ($requests['ChangeAdditionDeletionRequest']['employee_id'] != -1 && $requests['ChangeAdditionDeletionRequest']['employee_id'] != NULL) echo "<strong>Employee</strong><br/>" . $requests['Employee']['name']; ?>
                                        <?php if ($requests['ChangeAdditionDeletionRequest']['suggestion_form_id'] != -1 && $requests['ChangeAdditionDeletionRequest']['suggestion_form_id'] != NULL) echo "<strong>Suggestion From</strong><br/>" . $requests['SuggestionForm']['title']; ?>
                                        <?php if ($requests['ChangeAdditionDeletionRequest']['customer_id'] != -1 && $requests['ChangeAdditionDeletionRequest']['customer_id'] != NULL) echo "<strong>Customer</strong><br/>" . $requests['Customer']['name']; ?>
                                        <?php if ($requests['ChangeAdditionDeletionRequest']['others'] != NULL) echo "<strong>Other</strong><br/>" . $requests['ChangeAdditionDeletionRequest']['others']; ?>
                                    </td>
                                    <td>
                                        
                                    </td>                                    
                                </tr>
                                <?php $new_i++; ?>
                            <?php endforeach; ?>
                        </table>
                        <h5><b>Note: The Green records indicates Document Change Requests are accepted.</b></h5>
                    </div>
                </div>
            </div>
</div>
<div id="ccs">
            <div class="">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo __('Would you like to include these Customer Complaints in your current meeting?'); ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive">
                            <tr>
                                <th><?php echo __('Add?') ?></th>
                                <th><?php echo __('Complaint Number') ?></th>
                                <th><?php echo __('Customer Name') ?></th>
                                <th><?php echo __('Details') ?></th>
                                <th><?php echo __('Date') ?></th>
                                <th><?php echo __('Assigned To') ?></th>
                                <th><?php echo __('Target Date') ?></th>
                            </tr>
                            <?php $new_i = 0; ?>
                            <?php foreach ($allCustomerComplaints as $requests): ?>
                            <?php if($requests['CustomerComplaint']['current_status'] == 0)echo "<tr class='text-danger'>";
                            else echo "<tr class='text-success'>";
                            ?>                                
                                    <td><?php echo $this->Form->checkbox('AdditionalTopics.CustomerComplaint.' . $new_i . '.customer_complaint_id', array('value' => $requests['CustomerComplaint']['id'])) ?></td>
                                    <td><?php echo $requests['CustomerComplaint']['complaint_number'] ?></td>
                                    <td><?php echo $requests['Customer']['name'] ?></td>
                                    <td><?php echo $requests['CustomerComplaint']['details'] ?></td>
                                    <td><?php echo $requests['CustomerComplaint']['complaint_date'] ?></td>
                                    <td><?php echo $requests['Employee']['name'] ?></td>
                                    <td><?php echo $requests['CustomerComplaint']['target_date'] ?></td>
                                </tr>
                                <?php $new_i++; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
</div>
<div id="ncs">
            <div class="">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo __('Would you like to include these in your current meeting?'); ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive">
                            <tr>
                                <th><?php echo __('Add?') ?></th>
                                <th><?php echo __('Source') ?></th>
                                <th><?php echo __('Category') ?></th>
                                <th><?php echo __('From') ?></th>
                                <th><?php echo __('Initial Remarks') ?></th>
                                <th><?php echo __('Assigned To') ?></th>
                                <th><?php echo __('Target Date') ?></th>
                            </tr>
                            <?php $new_i = 0; ?>
                            <?php foreach ($allCorrectivePreventiveActions as $requests): ?>
                                <tr>
                                    <td><?php echo $this->Form->checkbox('AdditionalTopics.CorrectivePreventiveAction.' . $new_i . '.corrective_preventive_action_id', array('value' => $requests['CorrectivePreventiveAction']['id'])) ?></td>
                                    <td><?php echo $requests['CapaSource']['name'] ?></td>
                                    <td><?php echo $requests['CapaCategory']['name'] ?></td>
                                    <td>
                                        <?php if ($requests['CorrectivePreventiveAction']['internal_audit_id'] != -1 && $requests['CorrectivePreventiveAction']['internal_audit_id'] != NULL) echo "<strong>Internal Audit</strong><br/>" . $requests['InternalAudit']['title']; ?>
                                        <?php if ($requests['CorrectivePreventiveAction']['suggestion_form_id'] != -1 && $requests['CorrectivePreventiveAction']['suggestion_form_id'] != NULL) echo "<strong>Suggestion</strong><br/>" . $requests['SuggestionForm']['title']; ?>
                                        <?php if ($requests['CorrectivePreventiveAction']['customer_complaint_id'] != -1 && $requests['CorrectivePreventiveAction']['customer_complaint_id'] != NULL) echo "<strong>Customer Complaint</strong><br/>" . $requests['CustomerComplaint']['complaint_number'] . '/' . $request['CustomerComplaint']['complaint_date']; ?>
                                        <?php if ($requests['CorrectivePreventiveAction']['supplier_registration_id'] != -1 && $requests['CorrectivePreventiveAction']['supplier_registration_id'] != NULL) echo "<strong>Supplier</strong><br/>" . $requests['SupplierRegistration']['title']; ?>
                                        <?php if ($requests['CorrectivePreventiveAction']['product_id'] != -1 && $requests['CorrectivePreventiveAction']['product_id'] != NULL) echo "<strong>Product</strong><br/>" . $requests['Product']['name']; ?>
                                        <?php if ($requests['CorrectivePreventiveAction']['device_id'] != -1 && $requests['CorrectivePreventiveAction']['device_id'] != NULL) echo "<strong>Device</strong><br/>" . $requests['Device']['name']; ?>
                                    </td>
                                    <td><?php echo $requests['CorrectivePreventiveAction']['initial_remarks'] ?></td>
                                    <td><?php echo $requests['AssignedTo']['name'] ?></td>
                                    <td><?php echo $requests['CorrectivePreventiveAction']['target_date'] ?></td>
                                </tr>
                                <?php $new_i++; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
</div>
<div id="ses">
            <div class="">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo __('Would you like to include these Supplier Evaluations in your current meeting ?'); ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive">
                            <tr>
                                <th><?php echo __('Add?') ?></th>
                                <th><?php echo __('Supplier Name') ?></th>
                                <th><?php echo __('Category') ?></th>
                                <th><?php echo __('Remark') ?></th>
                                <th><?php echo __('Evaluation By') ?></th>
                                <th><?php echo __('Evaluation Date') ?></th>
                            </tr>

                            <?php $new_i = 0; ?>
                            <?php foreach ($allSummeryOfSupplierEvaluations as $requests): ?>
                                <tr>
                                    <td><?php echo $this->Form->checkbox('AdditionalTopics.SummeryOfSupplierEvaluation.' . $new_i . '.summery_of_supplier_evaluation_id', array('value' => $requests['SummeryOfSupplierEvaluation']['id'])) ?></td>
                                    <td><?php echo $requests['SupplierRegistration']['title'] ?></td>
                                    <td><?php echo $requests['SupplierCategory']['name'] ?></td>
                                    <td><?php echo $requests['SummeryOfSupplierEvaluation']['remarks'] ?></td>
                                    <td><?php echo $requests['Employee']['name'] ?></td>
                                    <td><?php echo $requests['SummeryOfSupplierEvaluation']['evaluation_date'] ?></td>
                                </tr>
                                <?php $new_i++; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
</div>
<div id="pas">
            <div class="">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo __('Would you like to include these Project Activities in your current meeting ?'); ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive">
                            <tr>
                                <th><?php echo __('Add?') ?></th>
                                <th><?php echo __('Activity') ?></th>
                                <th><?php echo __('Details') ?></th>
                                <th><?php echo __('Start Date / End Date') ?></th>
                                <th><?php echo __('Project') ?></th>
                                <th><?php echo __('Milestone') ?></th>
                            </tr>

                            <?php $new_i = 0; ?>
                            <?php foreach ($allProjectActivities as $requests): ?>
                                <tr>
                                    <td><?php echo $this->Form->checkbox('AdditionalTopics.ProjectActivity.' . $new_i . '.project_activity_id', array('value' => $requests['ProjectActivity']['id'])) ?></td>
                                    <td><?php echo $requests['ProjectActivity']['title'] ?></td>
                                    <td><?php echo $requests['ProjectActivity']['details'] ?></td>
                                    <td><?php echo $requests['ProjectActivity']['start_date'] ?>/<?php echo $requests['ProjectActivity']['end_date'] ?></td>
                                    <td><?php echo $requests['Project']['title'] ?></td>
                                    <td><?php echo $requests['Milestone']['title'] ?></td>
                                </tr>
                                <?php $new_i++; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
</div>
</div>
            <?php
                echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
            ?>
            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <span><?php  
                // echo $this->Form->input('publish', array('label' => __('Publish')));
                echo $this->Form->input('show_on_timeline', array('type' => 'checkbox', 'label' => __('Show on Timeline'))); 
                echo $this->Form->input('send_email', array('type' => 'checkbox', 'label' => __('Send Emails to invitees')));
                ?><br></span>
            <?php 
                echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
                echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#meetings_ajax', 'async' => 'false','id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>

<script>
    /*$("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        maxDate: -1
    }).attr('readonly', 'readonly');
*/
    $( "#meeting_tabs" ).tabs();
    var startDateTextBox = $('#MeetingScheduledMeetingFrom');
    var endDateTextBox = $('#MeetingScheduledMeetingTo');
    var previousDate = $('#MeetingPreviousMeetingDate');

    previousDate.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        changeMonth: true,
        changeYear: true,

        onSelect: function(selectedDate) {
            startDateTextBox.datetimepicker('option', 'minDate', previousDate.datetimepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
    previousDate.datepicker('option', 'maxDate', 0);
    
    
    startDateTextBox.datetimepicker({
        format: 'Y-m-d h:m',
        autoclose:true,
        minuteInterval : 30,
        // timeFormat: 'HH:mm:ss',
        changeMonth: true,
        changeYear: true,
        onClose: function(dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datetimepicker('getDate');
                var testEndDate = endDateTextBox.datetimepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.val(startDateTextBox.val());
            } else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate'));
        }
    });
    
    endDateTextBox.datetimepicker({
        format: 'Y-m-d h:m',
        minuteInterval : 30,
        autoclose:true,
        // timeFormat: 'HH:mm:ss',
        changeMonth: true,
        changeYear: true,
        onClose: function(dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datetimepicker('getDate');
                var testEndDate = endDateTextBox.datetimepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.val(endDateTextBox.val());
            } else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datetimepicker('option', 'maxDate', endDateTextBox.datetimepicker('getDate'));
        }
    });        
</script>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

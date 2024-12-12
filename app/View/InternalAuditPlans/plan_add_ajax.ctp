<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<?php if (isset($this->request->params['pass']) && isset($this->request->params['pass'][0])) { ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if($(element).attr('name') == 'data[InternalAuditPlanDepartment][employee_id]') {
                $(element).next().after(error);
            } else if($(element).attr('name') == 'data[InternalAuditPlanDepartment][list_of_trained_internal_auditor_id]') {
                $(element).next().after(error);
            } else if($(element).attr('name') == 'data[InternalAuditPlanDepartment][branch_id]') {
                $(element).next().after(error);
            } else if($(element).attr('name') == 'data[InternalAuditPlanDepartment][department_id]') {
                $(element).next().after(error);
            } else{
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            $('#InternalAuditPlanDepartmentNote').val(CKEDITOR.instances.InternalAuditPlanDepartmentNote.getData());
            $('#InternalAuditPlanDepartmentPlanAddAjaxForm').ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo "internal_audit_plan_departments" ?>/plan_add_ajax/<?php echo $this->request->params['pass'][0]; ?>",
                type: 'POST',
                target: '#ui-id-2',
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

    $().ready(function () {
        $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $("#InternalAuditPlanDepartmentDepartmentId").change(function() {
            $.ajax({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_dept_clauses/" + $('#InternalAuditPlanDepartmentDepartmentId').val(),
                get: $('#InternalAuditPlanDepartmentDepartmentId').val(),
                success: function(data, result) {
                    $('#InternalAuditPlanDepartmentClauses').val(data);
                }
            });
            $.ajax({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_department_employee/" + $('#InternalAuditPlanDepartmentDepartmentId').val(),
                success: function(data, result) {
                    $('#InternalAuditPlanDepartmentEmployeeId').find('option').remove().end().append(data).trigger('chosen:updated');
                }
            });
        });
        $('#InternalAuditPlanDepartmentPlanAddAjaxForm').validate({
            rules: {
                "data[InternalAuditPlanDepartment][employee_id]": {
                    greaterThanZero: true,
                },
                "data[InternalAuditPlanDepartment][list_of_trained_internal_auditor_id]": {
                    greaterThanZero: true,
                },
                "data[InternalAuditPlanDepartment][branch_id]": {
                    greaterThanZero: true,
                },
                "data[InternalAuditPlanDepartment][department_id]": {
                    greaterThanZero: true,
                }
            }
        });
        $('#InternalAuditPlanDepartmentBranchId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#InternalAuditPlanDepartmentDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#InternalAuditPlanDepartmentEmployeeId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#InternalAuditPlanDepartmentListOfTrainedInternalAuditorId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>

<?php } else { ?>

<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $('#InternalAuditPlanNote').val(CKEDITOR.instances.InternalAuditPlanNote.getData());
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/plan_add_ajax",
                type: 'POST',
                target: '#internalAuditPlans_ajax',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
        }
    });

    $().ready(function() {
        $("#submit-indicator").hide();
        $('#InternalAuditPlanPlanAddAjaxForm').validate();
    });
</script>

<?php } ?>

<div id="internalAuditPlans_ajax">
    <?php $i = 0; ?>
    <div class="row">
        <div class="internalAuditPlans form col-md-8">
           <?php if (isset($this->request->params['pass']) && isset($this->request->params['pass'][0])) { ?>
                <div class="row">
                    <div class="col-md-12" id="audit_plans">
                        <div class="panel panel-default">
                            <div class="panel-heading"><div class="panel-title"><?php echo __('Schedule Details'); ?></div></div>
                            <div class="panel-body">
                                <p>
                                <dl style="clear: both; margin: 0 0 10px 0" class="pull-left">
                                    <dt><?php echo __('Standard'); ?></dt>
                                    <dd><?php echo $internalAuditPlan['Standard']['name']; ?>&nbsp;</dd>
                                    <dt><?php echo __('Schedule title'); ?></dt>
                                    <dd><?php echo $internalAuditPlan['InternalAuditPlan']['title']; ?>&nbsp;</dd>
                                    <dt><?php echo __('From'); ?></dt>
                                    <dd><?php echo date('d M Y',strtotime($internalAuditPlan['InternalAuditPlan']['schedule_date_from'])); ?>&nbsp;</dd>
                                    <dt><?php echo __('To'); ?></dt>
                                    <dd><?php echo date('d M Y',strtotime($internalAuditPlan['InternalAuditPlan']['schedule_date_to'])); ?>&nbsp;</dd>
                                    <dt><?php echo __('Notes'); ?></dt>
                                    <dd><?php echo html_entity_decode($internalAuditPlan['InternalAuditPlan']['note']); ?>&nbsp;</dd>
                                </dl>
                                </p>
                                <hr />
                                <ul class="nav nav-tabs pull-left">
                                    <?php foreach ($PublishedBranchList as $key => $value): ?>
                                        <li><?php echo $this->Html->link($value . " <span class='badge btn-info'>" . count($plan[$key]) . "</span>", '#' . $key, array('data-toggle' => 'tab', 'escape' => false)); ?> </li>
                                    <?php endforeach ?>
                                </ul>
                                <div class="tab-content">
                                    <?php foreach ($plan as $key => $value): ?>
                                        <div class="tab-pane" id="<?php echo $key ?>">
                                            <table class="table">
                                                
                                                <?php foreach ($value as $department): ?>
                                                <tr>
                                                    <th><?php echo __('Department');?></th>
                                                    <th><?php echo __('Clauses');?></th>
                                                    <th><?php echo __('Auditee');?></th>
                                                    <th><?php echo __('Auditor');?></th>
                                                    <th><?php echo __('Time');?></th>
                                                    <th><?php echo __('Note');?></th>
                                                    <th><?php echo __('Action');?></th>
                                                    
                                                    <?php if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1] == 1) echo '<th>' . __('Add Details') . '</th>'; ?>
                                                </tr>
                                                    <tr>
                                                        <td><?php echo $department['Department']['name'] ?></td>
                                                        <td><?php echo $department['InternalAuditPlanDepartment']['clauses'] ?></td>
                                                        <td><?php echo $department['Employee']['name'] ?></td>
                                                        <td><?php echo $department['TrainedInternalAuditor'] ?></td>
                                                        <td><?php echo ('From : ' . date('d M Y',strtotime($department['InternalAuditPlanDepartment']['start_time'])) . '<br /> to : ' . date('d M Y',strtotime($department['InternalAuditPlanDepartment']['end_time']))); ?></td>
                                                        <td><?php echo html_entity_decode($department['InternalAuditPlanDepartment']['note']); ?></td>
                                                        <?php if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1] == 1) echo "<td>" . $this->Html->link(__('Add Details'), '#add_details', array('onClick' => 'getVals("' . $department['InternalAuditPlanDepartment']['id'] . '","' . $department['InternalAuditPlanDepartment']['list_of_trained_internal_auditor_id'] . '","' . $department['InternalAuditPlanDepartment']['clauses'] . '","' . $department['InternalAuditPlanDepartment']['start_time'] . '","' . $department['InternalAuditPlanDepartment']['end_time'] . '")')) . "</td>"; ?>
                                                        <td><?php echo $this->Html->link(__('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'), '#', array( 'escape'=>false,  'onClick' => "editModal('" . $department['InternalAuditPlanDepartment']['id'] . "')")); ?>
                                                            <?php echo $this->Html->image('indicator.gif', array('id' => "modalInd-{$department['InternalAuditPlanDepartment']['id']}", 'style' => 'display: none;')); ?>
                                                            <?php echo $this->Form->postLink(__('<i class="fa fa-times-circle" aria-hidden="true"></i>'), array('controller'=>'internal_audit_plan_departments', 'action' => 'delete', $department['InternalAuditPlanDepartment']['id'],$department['InternalAuditPlanDepartment']['internal_audit_plan_id']), array('escape' => false), __('Are you sure you want to delete this record ?', $department['InternalAuditPlanDepartment']['id'])); ?> 
                                                                                                                        
                                                        </td>
                                                        <div id="editModal<?php echo $department['InternalAuditPlanDepartment']['id']; ?>"></div>
                                                    </tr>
                                                    <?php if($department['InternalAuditPlanDepartment']['process_id']) {?>
                                                        <tr><th>Process:</th><td colspan="6"><?php echo $processes[$department['InternalAuditPlanDepartment']['process_id']];?></td></tr>
                                                    <?php } ?>
                                                    <?php if($department['InternalAuditPlanDepartment']['risk_assessment_id']) {?>
                                                        <tr><th>Risk:</th><td colspan="6"><?php echo $riskAssessments[$department['InternalAuditPlanDepartment']['risk_assessment_id']];?></td></tr>
                                                    <?php } ?>
                                                <?php endforeach; ?>
                                            </table>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<script>
function editModal(edit){
    $('#modalInd-'+edit).show();
    $('#editModal'+edit).load('<?php echo Router::url('/', true); ?>internal_audit_plan_departments/edit/' + edit, function(response, status, xhr){
        $('#modalInd-'+edit).hide();
    });
}
</script>

            <?php } else {
                    echo $this->Form->create('InternalAuditPlan', array('role' => 'form', 'class' => 'form', 'default' => false));
            ?>
                <fieldset>
                    <div class="row">
                        <?php $options = array(0=>'Internal',1=>'External');?>
                        <div class="col-md-4"><?php echo $this->Form->input('plan_type',array('type'=>'radio', 'options'=>$options,'default'=>0)); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('audit_type_master_id'); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('standard_id'); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8"><?php echo $this->Form->input('title'); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('schedule_date_from'); ?></div>
                        <!-- <div class="col-md-6"><?php echo $this->Form->input('schedule_date_to'); ?></div> -->
                        <div class="col-md-12">  <label for="InternalAuditPlanNote"><?php echo __('Note') ?></label></div>
                        <div class="col-md-12" style='clear:both'>
                            <textarea name="data[InternalAuditPlan][note]" id="InternalAuditPlanNote"  style=""></textarea>
                        </div>
                        <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                        <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                        <?php echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id'])); ?>
                    </div>
                </fieldset>

<script>
    $("#InternalAuditPlanScheduleDateFrom").daterangepicker({
        format: 'MM/DD/YYYY',
        locale: {
            format: 'MM/DD/YYYY'
        },
        autoclose:true,
    }); 
    // var startDateTextBox = $('#InternalAuditPlanScheduleDateFrom');
    // var endDateTextBox = $('#InternalAuditPlanScheduleDateTo');

    // startDateTextBox.datepicker({
    //     format: 'yyyy-mm-dd',
    //   autoclose:true,
    //     timeFormat: 'HH:mm:ss',
    //     onClose: function(dateText, inst) {
    //         if (endDateTextBox.val() != '') {
    //             var testStartDate = startDateTextBox.datepicker('getDate');
    //             var testEndDate = endDateTextBox.datepicker('getDate');
    //             if (testStartDate > testEndDate)
    //                 endDateTextBox.val(startDateTextBox.val());
    //         } else {
    //             endDateTextBox.val(dateText);
    //         }
    //     },
    //     onSelect: function(selectedDateTime) {
    //         endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
    //     }
    // }).attr('readonly', 'readonly');
    // endDateTextBox.datepicker({
    //     format: 'yyyy-mm-dd',
    //   autoclose:true,
    //     timeFormat: 'HH:mm:ss',
    //     onClose: function (dateText, inst) {
    //         if (startDateTextBox.val() != '') {
    //             var testStartDate = startDateTextBox.datepicker('getDate');
    //             var testEndDate = endDateTextBox.datepicker('getDate');
    //             if (testStartDate > testEndDate)
    //                 startDateTextBox.val(endDateTextBox.val());
    //         } else {
    //             startDateTextBox.val(dateText);
    //         }
    //     },
    //     onSelect: function (selectedDateTime) {
    //         startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
    //     }
    // }).attr('readonly', 'readonly');
</script>

                <?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
                <?php echo $this->fetch('script'); ?>

<script type="text/javascript">
    CKEDITOR.replace('InternalAuditPlanNote', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]
    });
</script>
                <?php echo $this->element('internal_audit_plan_approval'); ?>
            <div class="row">
            <div class="col-md-12" >
                <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#internalAuditPlans_ajax', 'async' => 'false','id'=>'submit_id')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
                </div>

</div>
            <?php } ?>

          <?php if (isset($this->request->params['pass']) && isset($this->request->params['pass'][0])) { ?>
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Form->create('InternalAuditPlanDepartment', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
                <div class="">                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title"><?php echo __('Create Plan'); ?></div>
                        </div>
                        <div class="panel-body no-padding">
                            <?php echo $this->Form->hidden('internal_audit_plan_id', array('style' => 'width:100%', 'value' => $this->request->params['pass'][0])); ?>
                            <div class="col-md-6"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'), 'options' => $PublishedBranchList)); ?></div>
                            <div class="col-md-6"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'), 'options' => $PublishedDepartmentList)); ?></div>
                            <div class="col-md-12"><?php echo $this->Form->input('process_id', array('style' => 'width:100%', 'label' => __('Process'))); ?></div>
                            <div class="col-md-12"><?php echo $this->Form->input('risk_assessment_id', array('style' => 'width:100%', 'label' => __('Risks'))); ?></div>
                            <div class="col-md-6"><?php echo $this->Form->input('employee_id', array('style' => 'width:100%', 'options' => $PublishedEmployeeList, 'label' => __('Auditee'))); ?></div>
                            <div class="col-md-6"><?php echo $this->Form->input('list_of_trained_internal_auditor_id', array('style' => 'width:100%','label'=>'Auditor')); ?></div>
                            <div class="col-md-6"><?php echo $this->Form->input('clauses', array('style' => 'width:100%', 'label' => __('Clauses'))); ?></div>
                            <div class="col-md-6"><?php echo $this->Form->input('startTime', array('label'=> 'Schedule From-To', 'default' => $internalAuditPlan['InternalAuditPlan']['schedule_date_from'])); ?></div>
                            <div class="col-md-6"><?php echo $this->Form->hidden('endTime', array('style' => 'width:100%', 'default' => $internalAuditPlan['InternalAuditPlan']['schedule_date_to'])); ?></div>
                            <div class="col-md-12"> <label><?php echo __('Note') ?></label></div>
                            <div class="col-md-12" style='clear:both'>
                                <textarea name="data[InternalAuditPlanDepartment][note]" id="InternalAuditPlanDepartmentNote"  style=""></textarea>
                            </div>
                            <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                            <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                            <?php echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id'])); ?>
                            <div class="col-md-12"><?php echo $this->element('internal_audit_plan_approval'); ?></div>
                        </div>
                        <br/>
                    </div>
                    <?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#internalAuditPlanDepartments_ajax', 'async' => 'false','id'=>'submit_id')); ?>
                    <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                    <?php echo $this->Form->end(); ?>
                    <?php echo $this->Js->writeBuffer(); ?>
                </div>

<script>
    $("#InternalAuditPlanDepartmentStartTime").daterangepicker({
        format: 'MM/DD/YYYY',
        minDate: '<?php echo date("yyyy-MM-dd",strtotime($internalAuditPlan["InternalAuditPlan"]["schedule_date_from"]))?>',
        maxDate: '<?php echo date("yyyy-MM-dd",strtotime($internalAuditPlan["InternalAuditPlan"]["schedule_date_to"]))?>',
        locale: {
            format: 'MM/DD/YYYY'
        },
        autoclose:true,
    });
    // var startDateTextBox = $('#InternalAuditPlanDepartmentStartTime');
    // var endDateTextBox = $('#InternalAuditPlanDepartmentEndTime');

    // startDateTextBox.datetimepicker({
    //     format: 'Y-m-d h:m:s',
    //   autoclose:true,
        
    //     onClose: function (dateText, inst) {
    //         if (endDateTextBox.val() != '') {
    //             var testStartDate = startDateTextBox.datetimepicker('getDate');
    //             var testEndDate = endDateTextBox.datetimepicker('getDate');
    //             if (testStartDate > testEndDate)
    //                 endDateTextBox.val(startDateTextBox.val());
    //         } else {
    //             endDateTextBox.val(dateText);
    //         }
    //     },
    //     onSelect: function (selectedDateTime) {
    //         endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate'));
    //     }
    // }).attr('readonly', 'readonly');


    // endDateTextBox.datetimepicker({
    //     format: 'Y-m-d h:m:s',
    //   autoclose:true,
        
    //     onClose: function (dateText, inst) {
    //         if (startDateTextBox.val() != '') {
    //             var testStartDate = startDateTextBox.datetimepicker('getDate');
    //             var testEndDate = endDateTextBox.datetimepicker('getDate');
    //             if (testStartDate > testEndDate)
    //                 startDateTextBox.val(endDateTextBox.val());
    //         } else {
    //             startDateTextBox.val(dateText);
    //         }
    //     },
    //     onSelect: function (selectedDateTime) {
    //         startDateTextBox.datetimepicker('option', 'maxDate', endDateTextBox.datetimepicker('getDate'));
    //     }
    // }).attr('readonly', 'readonly');
</script>

                <?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
                <?php echo $this->fetch('script'); ?>

<script type="text/javascript">
    CKEDITOR.replace('InternalAuditPlanDepartmentNote', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]
    });
</script>

            <?php } ?>

            <?php echo $this->Session->flash(); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div></div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

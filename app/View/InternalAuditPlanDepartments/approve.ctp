<script>
    $().ready(function() {
        $("#InternalAuditPlanDepartmentDepartmentId").change(function() {
            var selected = $('#InternalAuditPlanDepartmentDepartmentId').val();
            $.ajax({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_department_employee/" + selected,
                success: function(data, result) {
                    $('#InternalAuditPlanDepartmentEmployeeId').find('option').remove().end().append(data).trigger('chosen:updated');
                }
            });
        });
        $('#InternalAuditPlanDepartmentApproveForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#InternalAuditPlanDepartmentApproveForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
		 $("#InternalAuditPlanDepartmentApproveForm").submit();
             }

        });

    });
</script>

<div id="internalAuditPlanDepartments_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel">
        <div class="internalAuditPlanDepartments form col-md-8">
            <h4><?php echo __('Approve Internal Audit Plan Department'); ?>
                <?php echo $this->Html->link(__('List'), array('controller' => 'internal_audit_plans', 'action' => 'lists', $this->request->data['InternalAuditPlanDepartment']['internal_audit_plan_id']), array('class' => 'label btn-info')); ?>
            </h4>
            <?php echo $this->Form->create('InternalAuditPlanDepartment', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>

            <div class="row">
                <?php echo $this->Form->hidden('internal_audit_plan_id', array('style' => 'width:100%')); ?>

                <div class="col-md-6"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'))); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('clauses'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('employee_id', array('style' => 'width:100%', 'label' => __('Auditee'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('list_of_trained_internal_auditor_id', array('style' => 'width:100%')); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('start_time', array('style' => 'width:100%', 'class' => 'disabled')); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('end_time', array('style' => 'width:100%', 'class' => 'disabled')); ?></div>
            </div>

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>

<script>
    var startDateTextBox = $('#InternalAuditPlanDepartmentStartTime');
    var endDateTextBox = $('#InternalAuditPlanDepartmentEndTime');

    startDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        timeFormat: 'HH:mm:ss',
        onClose: function (dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.datepicker('setDate', testStartDate);
            } else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
    endDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        timeFormat: 'HH:mm:ss',
        onClose: function (dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.datepicker('setDate', testEndDate);
            } else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
</script>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#internalAuditPlanDepartments_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','plugins/daterangepicker/daterangepicker',)); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $(document).ready(function () {
        $.ajaxSetup({cache: false});
        $('#intPlanEdit_modal<?php echo $this->data['InternalAuditPlanDepartment']['id']; ?>').modal();
        $('.chosen-select').chosen();
    });
</script>
<style>
    .modal-dialog {width: 60% !important; height: 50% !important;}
    .chosen-container, .chosen-container-single, .chosen-select{width:100% !important;}
</style>

<div class="modal fade" id="intPlanEdit_modal<?php echo $this->data['InternalAuditPlanDepartment']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Edit Internal Audit Plan Department'); ?></h4>
            </div>
            <div class="modal-body">

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
    });
    $().ready(function() {
      $("#InternalAuditPlanDepartmentDepartmentId").change(function() {
            $.ajax({
                url: "<?php echo Router::url('/', true); ?>internal_audit_plans/get_dept_clauses/" + $('#InternalAuditPlanDepartmentDepartmentId').val(),
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
       // $('#InternalAuditPlanDepartmentEditForm').validate();
	jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

	 $('#InternalAuditPlanDepartmentEditForm').validate({
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
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#InternalAuditPlanDepartmentEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#InternalAuditPlanDepartmentEditForm").submit();
             }
        });
    });
</script>

                <div id="internalAuditPlanDepartments_ajax">
                    <?php echo $this->Session->flash(); ?>
                    <div class="row">
                        <div class="internalAuditPlanDepartments form col-md-12">
                            <?php echo $this->Form->create('InternalAuditPlanDepartment', array('role' => 'form', 'class' => 'form no-margin no-padding')); ?>
                            <?php echo $this->Form->input('id'); ?>

                            <div class="row">
                                <?php echo $this->Form->hidden('internal_audit_plan_id', array('style' => 'width:100%')); ?>
                                <div class="col-md-6"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'))); ?></div>
                                <div class="col-md-6"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'))); ?></div>
                            <div class="col-md-12"><?php echo $this->Form->input('process_id', array('style' => 'width:100%', 'label' => __('Process'))); ?></div>
                            <div class="col-md-12"><?php echo $this->Form->input('risk_assessment_id', array('style' => 'width:100%', 'label' => __('Risks'))); ?></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6"><?php echo $this->Form->input('clauses'); ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><?php echo $this->Form->input('employee_id', array('style' => 'width:100%', 'label' => __('Auditee'))); ?></div>
                                <div class="col-md-6"><?php echo $this->Form->input('list_of_trained_internal_auditor_id', array('style' => 'width:100%')); ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><?php echo $this->Form->input('start_time', array('style' => 'width:100%', 'class' => 'disabled')); ?></div>
                                <!-- <div class="col-md-6"><?php echo $this->Form->input('end_time', array('style' => 'width:100%', 'class' => 'disabled')); ?></div> -->
                            </div>

                            <?php
                                echo $this->element('internal_audit_plan_approval');
                                echo $this->Form->hidden('publish', array('value' => 1));
                            ?>
                            <br />
                            <?php
                                echo $this->Form->hidden('History.pre_post_values', array('value' => json_encode($this->data)));
                                echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'id' => 'submit_id'));
                            ?>
                            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                            <?php echo $this->Form->end(); ?>
                            <?php echo $this->Js->writeBuffer(); ?>
                        </div>
<script>
    $("#InternalAuditPlanDepartmentStartTime").daterangepicker({
        format: 'MM/DD/YYYY',
        minDate : '<?php echo date("m-d-Y",strtotime($this->data["InternalAuditPlan"]["schedule_date_from"])) ;?>',
        maxDate : '<?php echo date("m-d-Y",strtotime($this->data["InternalAuditPlan"]["schedule_date_to"])) ;?>',
        startDate : '<?php echo date("m-d-Y",strtotime($this->data["InternalAuditPlanDepartment"]["start_time"])) ;?>',
        endDate : '<?php echo date("m-d-Y",strtotime($this->data["InternalAuditPlanDepartment"]["end_time"])) ;?>',
        
        locale: {
            format: 'MM/DD/YYYY'
        },
        autoclose:true,
    }); 
    // var startDateTextBox = $('#InternalAuditPlanDepartmentStartTime');
    // var endDateTextBox = $('#InternalAuditPlanDepartmentEndTime');

    // startDateTextBox.datepicker({
    //     format: 'yyyy-mm-dd',
    //   autoclose:true,
    //     timeFormat: 'HH:mm:ss',
    //     onClose: function (dateText, inst) {
    //         if (endDateTextBox.val() != '') {
    //             var testStartDate = startDateTextBox.datepicker('getDate');
    //             var testEndDate = endDateTextBox.datepicker('getDate');
    //             if (testStartDate > testEndDate)
    //                 endDateTextBox.datepicker('setDate', testStartDate);
    //         } else {
    //             endDateTextBox.val(dateText);
    //         }
    //     },
    //     onSelect: function (selectedDateTime) {
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
    //                 startDateTextBox.datepicker('setDate', testEndDate);
    //         } else {
    //             startDateTextBox.val(dateText);
    //         }
    //     },
    //     onSelect: function (selectedDateTime) {
    //         startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
    //     }
    // }).attr('readonly', 'readonly');
</script>

                    </div>
                    <?php $this->Js->get('#list'); ?>
                    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#internalAuditPlanDepartments_ajax'))); ?>
                    <?php echo $this->Js->writeBuffer(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

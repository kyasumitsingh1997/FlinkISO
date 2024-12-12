<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[Production][branch_id]' ||
                $(element).attr('name') == 'data[Production][employee_id]' ||
                $(element).attr('name') == 'data[Production][production_weekly_plan_id]' ||
                $(element).attr('name') == 'data[Production][product_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        }
    });

    $().ready(function () {
        $("#hisory").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_history/product_id:' + $("#ProductionProductId").val()+'/week_id:'+$("#ProductionProductionWeeklyPlanId").val(), function(response, status, xhr) {               
                });

        $('#ProductionBatchNumber').blur(function() {
            $("#getBatch").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_batch/' + encodeURIComponent(this.value), function(response, status, xhr) {
                if (response != '') {
                    $('#ProductionBatchNumber').val('');
                    $('#ProductionBatchNumber').addClass('error');
                    $("#getBatch").val(response);
                } else {
                    $('#ProductionBatchNumber').removeClass('error');
                }
            });
        });

        $("#ProductionProductId").change(function(){
            $.get("<?php echo Router::url('/', true); ?>productions/get_plan/product_id:" + $("#ProductionProductId").val(), function(data) {
                $("#ProductionProductionWeeklyPlanId").html(data).trigger("chosen:updated");                 
            });

            $("#stocks").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_stocks/product_id:' + $("#ProductionProductId").val(), function(response, status, xhr) {                
            });
            $("#ProductionActualProductionNumber").val('0');
        });

        $("#ProductionProductionWeeklyPlanId").change(function(){
            $("#hisory").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_history/product_id:' + $("#ProductionProductId").val()+'/week_id:'+$("#ProductionProductionWeeklyPlanId").val(), function(response, status, xhr) {
                // alert(response);
                    // if (response != '') {
                    //     $('#ProductionBatchNumber').val('');
                    //     $('#ProductionBatchNumber').addClass('error');
                    //     $("#getBatch").val(response);
                    // } else {
                    //     $('#ProductionBatchNumber').removeClass('error');
                    // }
            });
            // $("#ProductionActualProductionNumber").val('0');

        })

        $("#ProductionActualProductionNumber").change(function(){
            var actual = $("#ProductionActualProductionNumber").val();
            var planned = $("#ProductionProductionPlanned").val();

            if(Number(actual) > Number(planned)){
                alert('Actual Production can not be more than planned');
                $("#ProductionActualProductionNumber").val(0);
                $("#ProductionActualProductionNumber").focus();
                // return false;
            }
        });

        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        $('#ProductionEditForm').validate({
            rules: {
                "data[Production][branch_id]": {
                    greaterThanZero: true,
                },
                "data[Production][employee_id]": {
                    greaterThanZero: true,
                },
                "data[Production][product_id]": {
                    greaterThanZero: true,
                },"data[Production][production_weekly_plan_id]": {
                    greaterThanZero: true,
                },
            }
        });
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#ProductionEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#ProductionEditForm").submit();
             }
        });
        $('#ProductionBranchId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProductionEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProductionProductId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProductionProductionWeeklyPlanId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#ProductionBatchNumber').blur(function() {
            $("#getBatch").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_batch/' + encodeURIComponent(this.value) + '/<?php echo $this->data['Production']['id']; ?>', function(response, status, xhr) {
                if (response != '') {
                    $('#ProductionBatchNumber').val('');
                    $('#ProductionBatchNumber').addClass('error');
                    $("#getBatch").val(response);
                } else {
                    $('#ProductionBatchNumber').removeClass('error');
                }
            });
        });
    });
</script>

<div id="productions_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel">
        <div class="productions form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Production'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>

            </h4>
            <?php echo $this->Form->create('Production', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $this->Form->input('Production.batch_number',array('default'=>$batch,'label'=>'Batch#')); ?>
                        <label id="getBatch" class="error" style="clear:both" ></label>
                </div>
                <div class="col-md-4"><?php echo $this->Form->input('product_id',array()); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('production_weekly_plan_id',array()); ?></div>
            </div>    
            <div class="row">
                <div class="col-md-4"><?php echo $this->Form->input('current_status',array('legend'=>'Plan Status','options'=>$currentStatus, 'type'=>'radio',  'default'=>0)); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('production_date',array()); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('actual_production_number',array('label'=>'# Actual Production', 'default'=>0)); ?></div>
                
                <div class="col-md-12 text-danger"><p><br /><strong>Note: </strong>Once <b>"Plan status"</b> is set at <b>"completed"</b> you will no longer be able to add production batch under that plan.</p></div>
                <div class="col-md-12">
                    <div id="hisory"></div>
                </div>
                <div><div id="stocks"></div></div>
                <div class="col-md-6"><?php echo $this->Form->input('branch_id',array('type'=>'select','options'=>$PublishedBranchList)); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('employee_id', array('label' => 'Supervisor','type'=>'select','options'=>$PublishedEmployeeList)); ?></div>
            </div>
                <div class="row">
                    <div class="col-md-6"><?php echo $this->Form->input('details'); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('remarks', array('label' => 'Any other Remarks')); ?></div>

                    <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                    <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                </div>  
            
            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
            ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>

<script>
    // var startDateTextBox = $('#ProductionStartDate');
    // var endDateTextBox = $('#ProductionEndDate');

    // startDateTextBox.datepicker({
    //     format: 'yyyy-mm-dd',
    //   autoclose:true,
    //     changeMonth: true,
    //     changeYear: true,
    //     'showTimepicker': false,
    //     onClose: function (dateText, inst) {
    //         if (endDateTextBox.val() != '') {
    //             var testStartDate = startDateTextBox.datepicker('getDate');
    //             var testEndDate = endDateTextBox.datepicker('getDate');
    //             if (testStartDate > testEndDate) {
    //                 endDateTextBox.val(startDateTextBox.val());
    //             }
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
    //     changeMonth: true,
    //     changeYear: true,
    //     'showTimepicker': false,
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
$("#ProductionProductionDate").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }); 
</script>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#productions_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo __('Import from file (excel & csv formats only)'); ?></h4>
            </div>
            <div class="modal-body"><?php echo $this->element('import'); ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[CapaInvestigation][employee_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CapaInvestigation][corrective_preventive_action_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function (form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_capa_investigation",
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

    $().ready(function () {
    $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#CapaInvestigationAddAjaxForm').validate({
            rules: {
                "data[CapaInvestigation][employee_id]": {
                    greaterThanZero: true,
                },
                "data[CapaInvestigation][corrective_preventive_action_id]": {
                    greaterThanZero: true,
                },
              
            }
        });

        $('#CapaInvestigationEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CapaInvestigationCorrectivePreventiveActionId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    
 });
   
    function currentStatus() {
	var status = $("[name='data[CapaInvestigation][current_status]']:checked").val();
	if (status == 1) {
	    $('#CapaInvestigationClosedBy').prop('disabled', false).trigger('chosen:updated');
	    $('#CapaInvestigationClosedOnDate').prop('disabled', false);
	    $('#CapaInvestigationClosedBy').rules('add', {greaterThanZero: true});
	    $('#CapaInvestigationClosedOnDate').rules('add', {required: true, number: false});
	} else if (status == 0) {
	    $('#CapaInvestigationClosedBy').prop('disabled', true).trigger('chosen:updated');
	    $('#CapaInvestigationClosedOnDate').prop('disabled', true);
	    $('#CapaInvestigationClosedBy').rules('remove');
	    $('#CapaInvestigationClosedOnDate').rules('remove');
	    $('#CapaInvestigationClosedBy').next().next('label').remove();
	    $('#CapaInvestigationClosedOnDate').next('label').remove();
	    $('#CapaInvestigationClosedBy').val('-1').trigger('chosen:updated');
	    $('#CapaInvestigationClosedOnDate').val('');
	    $('#CapaInvestigationClosedOnDate').removeClass('error');
	}
    }
   

</script>

<div id="capaInvestigations_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav">
        <div class="capaInvestigations form col-md-8">
            <h4><?php echo __('Add Capa Investigation'); ?></h4>
            <?php echo $this->Form->create('CapaInvestigation', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>

       
        
            <div class="row">

                <div class="col-md-6"><?php echo $this->Form->input('corrective_preventive_action_id', array('options' => $capa)); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('employee_id', array('options' => $PublishedEmployeeList, 'label' => __('Assigned To'))); ?></div>
               
              
            </div>
              <div class="row">
                  <div class="col-md-6"><?php echo $this->Form->input('details', array('label' => __('Details'))); ?></div>

<div class="col-md-6"><?php echo $this->Form->input('proposed_action', array('label' => __('Proposed Action'))); ?></div></div>
            <div id="statusCloseDetails" class="row">
                 <div class="col-md-6"><?php echo $this->Form->input('target_date', array('label' => __('Target Date'))); ?></div>
             
                <div class="col-md-6"><?php echo $this->Form->input('completed_on_date', array('label' => __('Completed on Date'))); ?></div>
                  </div>
          
	
            <hr />
            <div class="row">
                <div class="col-md-12"><?php
                    echo "<label>" . __('Current Status') . "</label>";
                    echo $this->Form->input('current_status', array('value' => '0', 'label' => false, 'legend' => false,  'div' => false, 'options' => array('0' => 'Open', '1' => 'Close'), 'type' => 'radio', 'style' => 'float:none','onclick' => 'currentStatus()'));
                    ?></div>

            </div>
            

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->hidden('branchid', array('value' => $this->Session->read('User.branch_id'), 'disabled' => 'disabled')); ?>
            <?php echo $this->Form->hidden('departmentid', array('value' => $this->Session->read('User.department_id'), 'disabled' => 'disabled')); ?>
            <?php echo $this->Form->hidden('master_list_of_format_id', array('value' => $documentDetails['MasterListOfFormat']['id'])); ?>

            <?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#capaInvestigations_ajax', 'async' => 'false','id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>

<script>
    var targetDate = $('#CapaInvestigationTargetDate');
    var completedOnDate = $('#CapaInvestigationCompletedOnDate');
    

    targetDate.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        changeMonth: true,
        changeYear: true,

        onSelect: function(selectedDate) {
            completedOnDate.datepicker('option', 'minDate', targetDate.datepicker('getDate'));
         
            completedOnDate.datepicker('option', 'minDate', targetDate.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');


    $("#CapaInvestigationCompletedOnDate").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
    }).attr('readonly', 'readonly');


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

<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<script>
$.validator.setDefaults({
    ignore: null,
    errorPlacement: function (error, element) {
        if ($(element).attr('name') == 'data[CapaRootCauseAnalysi][employee_id]') {
            $(element).next().after(error);
        } else if ($(element).attr('name') == 'data[CapaRootCauseAnalysi][corrective_preventive_action_id]') {
            $(element).next().after(error);
            
        } else if ($(element).attr('name') == 'data[CapaRootCauseAnalysi][determined_by]') {
            $(element).next().after(error);
        }  else if ($(element).attr('name') == 'data[CapaRootCauseAnalysi][action_assigned_to]') {
            $(element).next().after(error);
        } else {
            $(element).after(error);
        }
    },
    submitHandler: function (form) {
        $(form).ajaxSubmit({
            url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
            type: 'POST',
            target: '#capaRootCauseAnalysis_ajax',
            beforeSend: function(){
             $("#submit_id").prop("disabled",true);
             $("#submit-indicator-root").show();
             // $('#rootCauseModal').modal('hide');
         },
         complete: function() {
             $("#submit_id").removeAttr("disabled");
             $("#submit-indicator-root").hide();
         },
         error: function (request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
    }
});
$().ready(function () {
   $('.chosen-select').chosen();
   $("#submit-indicator-root").hide();
   jQuery.validator.addMethod("greaterThanZero", function (value, element) {
    return this.optional(element) || (parseFloat(value) > 0);
}, "Please select the value");

   $('#CapaRootCauseAnalysiAddAjaxForm').validate({
    rules: {
        "data[CapaRootCauseAnalysi][employee_id]": {
            greaterThanZero: true,
        },
        "data[CapaRootCauseAnalysi][corrective_preventive_action_id]": {
            greaterThanZero: true,
        },
        "data[CapaRootCauseAnalysi][determined_by]": {
            greaterThanZero: true,
        },
        "data[CapaRootCauseAnalysi][action_assigned_to]": {
            greaterThanZero: true,
        },

    }
});

   $('#CapaRootCauseAnalysiEmployeeId').change(function () {
    if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        $(this).next().next('label').remove();
    }
});
   $('#CapaRootCauseAnalysiCorrectivePreventiveActionId').change(function () {
    if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        $(this).next().next('label').remove();
    }
});
   $('#CapaRootCauseAnalysiDeterminedBy').change(function () {
    if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        $(this).next().next('label').remove();
    }
});
   $('#CapaRootCauseAnalysiActionAssignedTo').change(function () {
    if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        $(this).next().next('label').remove();
    }
});

});
</script>

<div id="capaRootCauseAnalysis_ajax">
    <?php echo $this->Session->flash();?><div class="nav">
    <?php
    if($modal != 1) { ?>
    <div class="capaRootCauseAnalysis form col-md-8">
        <h4>Add Capa Root Cause Analysis</h4>
        <?php } else{ ?>
        <div class="capaRootCauseAnalysis form col-md-12">

           <?php } ?>
           <?php echo $this->Form->create('CapaRootCauseAnalysi',array('role'=>'form','class'=>'form','default'=>false)); 

           ?>
           <div class="row">
              <fieldset>
                 <?php
                 echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array('options' => $correctivePreventiveActionIds,'value'=>$capaId)) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('options' => $PublishedEmployeeList)) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('determined_by',array('options' => $PublishedEmployeeList)) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('determined_on_date',array()) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('root_cause_details',array()) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('root_cause_remarks',array()) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('action_assigned_to',array('options' => $PublishedEmployeeList)) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('action_completed_on_date',array()) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('proposed_action',array()) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('action_completion_remarks',array()) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('effectiveness',array()) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('closure_remarks',array()) . '</div>'; 

                 echo "<div class='col-md-6'>"."<label>" . __('Current Status') . "</label>";
                 echo $this->Form->input('current_status', array('value' => '0', 'label' => false, 'legend' => false,  'div' => false, 'options' => array('0' => 'Open', '1' => 'Close'), 'type' => 'radio', 'style' => 'float:none','onclick' => 'currentStatus()')). '</div>'; 
                 ?>
             </fieldset>
             <?php
             echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
             echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
             echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
             ?>
        </div>
        <?php
            if ($showApprovals && $showApprovals['show_panel'] == true) {
                echo $this->element('approval_form');
            } else {
                echo $this->Form->input('publish', array('label' => __('Publish')));
            }?>
        <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#capaRootCauseAnalysis_ajax','async' => 'false','id'=>'submit_id')); ?>
      <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator-root')); ?>
      <?php echo $this->Form->end(); ?>
      <?php echo $this->Js->writeBuffer();?>
  </div>
  <style>
  #ui-datepicker-div{z-index:1999 !important}
  </style>
  <script>
  $("[name*='date']").datepicker({
    changeMonth: true,
    changeYear: true,
    format: 'yyyy-mm-dd',
    autoclose:true,
});
  </script>
  <?php if($modal != 1) { ?>
  <div class="col-md-4">
   <p><?php echo $this->element('helps'); ?></p>
</div>
<?php } ?>
</div>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?> 
<div id="incidentInvestigators_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="incidentInvestigators form col-md-8">
<h4><?php echo __('Edit Incident Investigator'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('IncidentInvestigator',array('role'=>'form','class'=>'form')); ?>
<div class="row">
    
			<?php
                        
                echo "<div class='col-md-6'> <label>Person Type</label>".$this->Form->input('person_type',array('type' => 'radio', 'options' => array(0=>'Employee', 1=>'Other'), 'style' => 'width:100%', 'label' => false, 'legend' => false,  'div' => false, 'style' => 'float:none','default'=>0)) . '</div>'; 
                echo "<div id='employee_data'>";
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('style'=>'')) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->input('department_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('designation_id',array('style'=>'')) . '</div>';
                echo '</div>';
                echo "<div class='col-md-6' id='other_data' >".$this->Form->input('name',array()) . '</div>'; 
		
		echo "<div class='col-md-6'>".$this->Form->input('address') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('phone') . '</div>'; 
		 
		echo "<div class='col-md-6'>".$this->Form->input('age') . '</div>'; 
		echo "<div class='col-md-6'> <label>Gender</label>".$this->Form->input('gender',array('type' => 'radio', 'options' => array(0=>'Male', 1=>'Female'), 'style' => 'width:100%', 'label' => false, 'legend' => false,  'div' => false, 'style' => 'float:none', 'default'=>0)) . '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo 	$this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); echo $this->Form->input('state_id', array('type' => 'hidden', 'value' => $this->Session->read('User.state_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="row">
		

<?php

		
	if ($showApprovals && $showApprovals['show_panel'] == true) {
		
		echo $this->element('approval_form');
		
	} else {
		
		echo $this->Form->input('publish', array('label' => __('Publish')));
		
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?><?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentInvestigators_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
		<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Import from file (excel & csv formats only)</h4>
		</div>
<div class="modal-body"><?php echo $this->element('import'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>

<script>
    $.validator.setDefaults({
  ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[IncidentInvestigator][incident_id]' ||
                    $(element).attr('name') == 'data[IncidentInvestigator][employee_id]' ||
                    $(element).attr('name') == 'data[IncidentInvestigator][department_id]' ||
                    $(element).attr('name') == 'data[IncidentInvestigator][designation_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
  });
       
 
		$().ready(function() {
    $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        $('#IncidentInvestigatorEditForm').validate({
              rules: {
                "data[IncidentInvestigator][incident_id]": {
                    greaterThanZero: true,
                },
                  <?php if($this->data[IncidentInvestigator][person_type] == 0){ ?>
                "data[IncidentInvestigator][employee_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentInvestigator][department_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentInvestigator][designation_id]": {
                    greaterThanZero: true,
                },
                  <?php } ?>
            }
        }); 
           $('#IncidentInvestigatorIncidentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
         $('#IncidentInvestigatorDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
    });
         $('#IncidentInvestigatorDesignationId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $("#submit_id").click(function(){
            if($('#IncidentInvestigatorEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#IncidentInvestigatorEditForm').submit();
            }

        });
        <?php if($this->data[IncidentInvestigator][person_type] == 1){ ?>
             $("#employee_data").hide();
        <?php }else{ ?>
            $("#other_data").hide();
        <?php } ?>
        $("[name='data[IncidentInvestigator][person_type]']").click(function(){
           
	  
            var status = $("[name='data[IncidentInvestigator][person_type]']:checked").val();
           
             if (status == 1) {
                $("#employee_data").hide();
                $("#other_data").show();
                $('#IncidentInvestigatorEmployeeId').val(0).trigger('chosen:updated');
                $('#IncidentInvestigatorDepartmentId').val(0).trigger('chosen:updated');
                $('#IncidentInvestigatorDesignationId').val(0).trigger('chosen:updated');
                $('#IncidentInvestigatorEmployeeId').rules('remove');
                $('#IncidentInvestigatorDepartmentId').rules('remove');
                $('#IncidentInvestigatorDesignationId').rules('remove');
                $('#IncidentInvestigatorName').rules('add', {
                    required: true
                });
            }else{
                $("#other_data").hide();
                $("#employee_data").show();
                $('#IncidentInvestigatorEmployeeId').rules('add', {
                    greaterThanZero: true
                });
                $('#IncidentInvestigatorDepartmentId').rules('add', {
                    greaterThanZero: true
                });
                $('#IncidentInvestigatorDesignationId').rules('add', {
                    greaterThanZero: true
                });
               $('#IncidentInvestigatorName').val('');
                $('#IncidentInvestigatorName').rules('remove');
            }
    });
        $("[name='data[IncidentInvestigator][employee_id]']").change(function(){
          
            $("#IncidentInvestigatorAjaxData").load('<?php echo Router::url('/', true); ?>incidents/get_employee_info/' + encodeURIComponent(this.value), function(response, status, xhr) {
                var myObject = JSON.parse(response);
              
            
                $("#IncidentInvestigatorDesignationId").val(myObject.Employee.designation_id).trigger('chosen:updated');
                if(myObject.Employee.mobile !='') var mobile = myObject.Employee.mobile;
                else if(myObject.Employee.office_telephone !='') var mobile = myObject.Employee.office_telephone;
                else if(myObject.Employee.personal_telephone !='') var mobile = myObject.Employee.personal_telephone;
                else var mobile = '';
                $("#IncidentInvestigatorPhone").val(mobile);
                
                if(myObject.Employee.residence_address !='') var address = myObject.Employee.residence_address;
                else if(myObject.Employee.permenant_address !='') var address = myObject.Employee.permenant_address;
                else var address = '';
                $("#IncidentInvestigatorAddress").val(address);
            });
              if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
    
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
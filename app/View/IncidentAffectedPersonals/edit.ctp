<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
 <div id="incidentAffectedPersonals_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="incidentAffectedPersonals form col-md-8">
<h4><?php echo __('Edit Incident Affected Personal'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('IncidentAffectedPersonal',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
        echo "<div class='col-md-6'>".$this->Form->input('incident_id',array('value'=>$incidentId)) . '</div>'; 
        echo "<div class='col-md-6'> <label>Person Type</label>".$this->Form->input('person_type',array('type' => 'radio', 'options' => array(0=>'Employee', 1=>'Other'), 'style' => 'width:100%', 'label' => false, 'legend' => false,  'div' => false, 'style' => 'float:none','default'=>0)) . '</div>'; 
        echo '</div>';
        echo "<div class='row'>";
        echo "<div id='employee_data'>";
        echo "<div class='col-md-6'>".$this->Form->input('employee_id',array()) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('department_id',array()) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('designation_id',array()) . '</div>'; 
        echo '</div>';
        echo "<div class='col-md-6' id='other_data' >".$this->Form->input('name',array()) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('phone',array()) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('address',array('type' => 'text')) . '</div>'; 
        echo "<div class='col-md-3'>".$this->Form->input('age',array()) . '</div>'; 
        echo "<div class='col-md-3'> <label>Gender</label>".$this->Form->input('gender',array('type' => 'radio', 'options' => array(0=>'Male', 1=>'Female'), 'style' => 'width:100%', 'label' => false, 'legend' => false,  'div' => false, 'style' => 'float:none', 'default'=>0)) . '</div>';     
        echo '</div>';
        echo "<div class='row'>";
        echo "<div class='col-md-2'>".$this->Form->input('first_aid_provided',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('first_aid_provided_by',array()) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('follow_up_action_taken',array()) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('first_aid_details',array()) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('other',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('illhealth_reported',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('normal_work_affected',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('number_of_work_affected_dates',array()) . '</div>'; 
        echo "</div>";
        echo "<div class='row'>";
        echo "<div class='col-md-12'><h2>Add Investigation Interview Details</h2></div>"; 
    		echo "<div class='col-md-6'>".$this->Form->input('incident_investigator_id',array()) . '</div>';     
    		echo "<div class='col-md-6'>".$this->Form->input('date_of_interview',array()) . '</div>'; 
    		echo "<div class='col-md-12'>".$this->Form->input('investigation_interview_findings',array()) . '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo 	$this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                    echo $this->Form->input('ajax_data', array('type' => 'hidden'));
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
<script>  
    $("[name*='date_of_interview']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');  
     $("[name*='date_of_interview']").datepicker('option', 'maxDate', 0);
</script>


<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentAffectedPersonals_ajax')));?>

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
            if ($(element).attr('name') == 'data[IncidentAffectedPersonal][incident_id]' ||
                    $(element).attr('name') == 'data[IncidentAffectedPersonal][employee_id]' ||
                    $(element).attr('name') == 'data[IncidentAffectedPersonal][department_id]' ||
                    $(element).attr('name') == 'data[IncidentAffectedPersonal][designation_id]') {
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
        $('#IncidentAffectedPersonalEditForm').validate({
              rules: {
                "data[IncidentAffectedPersonal][incident_id]": {
                    greaterThanZero: true,
                },
                  <?php if($this->data[IncidentAffectedPersonal][person_type] == 0){ ?>
                "data[IncidentAffectedPersonal][employee_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentAffectedPersonal][department_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentAffectedPersonal][designation_id]": {
                    greaterThanZero: true,
                },
                  <?php } ?>
            }
        }); 
           $('#IncidentAffectedPersonalIncidentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
         $('#IncidentAffectedPersonalDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
         $('#IncidentAffectedPersonalDesignationId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $("#submit_id").click(function(){
            if($('#IncidentAffectedPersonalEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#IncidentAffectedPersonalEditForm').submit();
            }

        });
        <?php if($this->data[IncidentAffectedPersonal][person_type] == 1){ ?>
             $("#employee_data").hide();
        <?php }else{ ?>
            $("#other_data").hide();
        <?php } ?>
        $("[name='data[IncidentAffectedPersonal][person_type]']").click(function(){
           
	  
            var status = $("[name='data[IncidentAffectedPersonal][person_type]']:checked").val();
           
             if (status == 1) {
                $("#employee_data").hide();
                $("#other_data").show();
                $('#IncidentAffectedPersonalEmployeeId').val(0).trigger('chosen:updated');
                $('#IncidentAffectedPersonalDepartmentId').val(0).trigger('chosen:updated');
                $('#IncidentAffectedPersonalDesignationId').val(0).trigger('chosen:updated');
                $('#IncidentAffectedPersonalEmployeeId').rules('remove');
                $('#IncidentAffectedPersonalDepartmentId').rules('remove');
                $('#IncidentAffectedPersonalDesignationId').rules('remove');
                $('#IncidentAffectedPersonalName').rules('add', {
                    required: true
                });
            }else{
                $("#other_data").hide();
                $("#employee_data").show();
                $('#IncidentAffectedPersonalEmployeeId').rules('add', {
                    greaterThanZero: true
                });
                $('#IncidentAffectedPersonalDepartmentId').rules('add', {
                    greaterThanZero: true
                });
                $('#IncidentAffectedPersonalDesignationId').rules('add', {
                    greaterThanZero: true
                });
               $('#IncidentAffectedPersonalName').val('');
                $('#IncidentAffectedPersonalName').rules('remove');
            }
    });
        $("[name='data[IncidentAffectedPersonal][employee_id]']").change(function(){
          
            $("#IncidentAffectedPersonalAjaxData").load('<?php echo Router::url('/', true); ?>incidents/get_employee_info/' + encodeURIComponent(this.value), function(response, status, xhr) {
                var myObject = JSON.parse(response);
              
            
                $("#IncidentAffectedPersonalDesignationId").val(myObject.Employee.designation_id).trigger('chosen:updated');
                if(myObject.Employee.mobile !='') var mobile = myObject.Employee.mobile;
                else if(myObject.Employee.office_telephone !='') var mobile = myObject.Employee.office_telephone;
                else if(myObject.Employee.personal_telephone !='') var mobile = myObject.Employee.personal_telephone;
                else var mobile = 'N/A';
                $("#IncidentAffectedPersonalPhone").val(mobile);
                
                if(myObject.Employee.residence_address !='') var address = myObject.Employee.residence_address;
                else if(myObject.Employee.permenant_address !='') var address = myObject.Employee.permenant_address;
                else var address = 'N/A';
                $("#IncidentAffectedPersonalAddress").val(address);
                if(myObject.Employee.name !='') var name = myObject.Employee.name;
                else if(myObject.Employee.name !='') var address = myObject.Employee.name;
                else var name = 'N/A';
                $("#IncidentAffectedPersonalName").val(name);

                if(myObject.Employee.age !='') var age = myObject.Employee.age;
                else if(myObject.Employee.age !='') var age = myObject.Employee.age;
                else var age = '00';
                $("#IncidentAffectedPersonalAge").val(age);
            });
              if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
    
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

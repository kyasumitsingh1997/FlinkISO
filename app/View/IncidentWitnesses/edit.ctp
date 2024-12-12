<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="incidentWitnesses_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="incidentWitnesses form col-md-8">
    <h4><?php echo __('Edit Incident Witness'); ?>		
        <?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
    </h4>
<?php echo $this->Form->create('IncidentWitness',array('role'=>'form','class'=>'form')); ?>
<div class="row">
    <fieldset>
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
        echo "<div class='col-md-6' id='other_data'>".$this->Form->input('name',array()) . '</div>';
        echo "<div class='col-md-6'>".$this->Form->input('phone',array()) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('address',array('type' => 'text')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('age',array()) . '</div>'; 
		echo "<div class='col-md-6'> <label>Gender</label>".$this->Form->input('gender',array('type' => 'radio', 'options' => array(0=>'Male', 1=>'Female'), 'style' => 'width:100%', 'label' => false, 'legend' => false,  'div' => false, 'style' => 'float:none', 'default'=>0)) . '</div>'; 
		

        echo "</div>";
        echo "<div class='row'>";
        echo "<div class='col-md-12'><h2>Add Investigation Interview Details</h2></div>"; 
        echo "<div class='col-md-6'>".$this->Form->input('investigation_interview_taken_by',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('date_of_interview',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('investigation_interview_findings',array()) . '</div>'; 
	?>
</fieldset>
<?php
		echo $this->Form->input('id');
		echo 	$this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                     echo $this->Form->input('ajax_data', array('type' => 'hidden'));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentWitnesses_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>


<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[IncidentWitness][incident_id]' ||
                    $(element).attr('name') == 'data[IncidentWitness][employee_id]' ||
                    $(element).attr('name') == 'data[IncidentWitness][department_id]' ||
                    $(element).attr('name') == 'data[IncidentWitness][designation_id]') {
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
        $('#IncidentWitnessEditForm').validate({
              rules: {
                "data[IncidentWitness][incident_id]": {
                    greaterThanZero: true,
                },
                  <?php if($this->data[IncidentWitness][person_type] == 0){ ?>
                "data[IncidentWitness][employee_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentWitness][department_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentWitness][designation_id]": {
                    greaterThanZero: true,
                },
                  <?php } ?>
            }
        }); 
           $('#IncidentWitnessIncidentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
         $('#IncidentWitnessDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
         $('#IncidentWitnessDesignationId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
         $("#submit_id").click(function(){
            if($('#IncidentWitnessEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#IncidentWitnessEditForm').submit();
            }

        });
    $("[name*='date_of_interview']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly'); 
     $("[name*='date_of_interview']").datepicker('option', 'maxDate', 0);
        <?php if($this->data[IncidentWitness][person_type] == 1){ ?>
             $("#employee_data").hide();
        <?php }else{ ?>
            $("#other_data").hide();
        <?php } ?>
      $("[name='data[IncidentWitness][person_type]']").click(function(){
	  
            var status = $("[name='data[IncidentWitness][person_type]']:checked").val();
            if (status == 1) {
                 $("#employee_data").hide();
                 $("#other_data").show();
                $('#IncidentWitnessEmployeeId').val(0).trigger('chosen:updated');
                $('#IncidentWitnessDepartmentId').val(0).trigger('chosen:updated');
                $('#IncidentWitnessDesignationId').val(0).trigger('chosen:updated');
                $('#IncidentWitnessEmployeeId').rules('remove');
                $('#IncidentWitnessDepartmentId').rules('remove');
                $('#IncidentWitnessDesignationId').rules('remove');
                $('#IncidentWitnessName').rules('add', {
                    required: true
                });
            }else{
                 $("#other_data").hide();
                 $("#employee_data").show();
                $('#IncidentWitnessEmployeeId').rules('add', {
                    greaterThanZero: true
                });
                $('#IncidentWitnessDepartmentId').rules('add', {
                    greaterThanZero: true
                });
                $('#IncidentWitnessDesignationId').rules('add', {
                    greaterThanZero: true
                });
               
               $('#IncidentWitnessName').val('');
                $('#IncidentWitnessName').rules('remove');
            }
    });
         $("[name='data[IncidentWitness][employee_id]']").change(function(){
        
            $("#IncidentWitnessAjaxData").load('<?php echo Router::url('/', true); ?>incidents/get_employee_info/' + encodeURIComponent(this.value), function(response, status, xhr) {
                var myObject = JSON.parse(response);
              
            
                $("#IncidentWitnessDesignationId").val(myObject.Employee.designation_id).trigger('chosen:updated');
                if(myObject.Employee.mobile !='') var mobile = myObject.Employee.mobile;
                else if(myObject.Employee.office_telephone !='') var mobile = myObject.Employee.office_telephone;
                else if(myObject.Employee.personal_telephone !='') var mobile = myObject.Employee.personal_telephone;
                else var mobile = 'N/A';
                $("#IncidentWitnessPhone").val(mobile);
                
                if(myObject.Employee.residence_address !='') var address = myObject.Employee.residence_address;
                else if(myObject.Employee.permenant_address !='') var address = myObject.Employee.permenant_address;
                else var address = 'N/A';
                $("#IncidentWitnessAddress").val(address);
                if(myObject.Employee.name !='') var name = myObject.Employee.name;
                else if(myObject.Employee.name !='') var address = myObject.Employee.name;
                else var name = 'N/A';
                $("#IncidentWitnessName").val(name);

                if(myObject.Employee.age !='') var age = myObject.Employee.age;
                else if(myObject.Employee.age !='') var age = myObject.Employee.age;
                else var age = '00';
                $("#IncidentWitnessAge").val(age);
            });
             
             if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

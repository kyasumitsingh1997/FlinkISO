<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="incidentWitnesses_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<?php
   if($modal != 1) { ?>
      <div class="incidentWitnesses form col-md-8">
        <h4>Add Incident Witness</h4>
    <?php } else{
      echo "<div class='row'><div class='col-md-12'>";
      foreach($witnesses as $id => $name):
        echo $this->Html->link($name,array('controller'=>'incident_witnesses','action'=>'edit',$id),array( 'class'=>'btn btn-sm btn-info', 'escape'=>false)) . "&nbsp;";
      endforeach;  
      echo "</div></div>";
      ?>
    <div class="incidentWitnesses form col-md-12">
  <?php } ?>

<?php echo $this->Form->create('IncidentWitness',array('role'=>'form','class'=>'form','default'=>false)); ?>
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
		echo "<div class='col-md-3'>".$this->Form->input('age',array()) . '</div>'; 
		echo "<div class='col-md-3'> <label>Gender</label>".$this->Form->input('gender',array('type' => 'radio', 'options' => array(0=>'Male', 1=>'Female'), 'style' => 'width:100%', 'label' => false, 'legend' => false,  'div' => false, 'style' => 'float:none', 'default'=>0)) . '</div>'; 
		//echo "<div class='col-md-6'>".$this->Form->input('investigation_interview_taken_by',array()) . '</div>'; 
		//echo "<div class='col-md-6'>".$this->Form->input('date_of_interview',array()) . '</div>'; 
		//echo "<div class='col-md-6'>".$this->Form->input('investigation_interview_findings',array()) . '</div>'; 
	?>
</fieldset>
<?php
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
      }?>
      <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#incidentWitnesses_ajax','async' => 'false')); ?>
      <?php echo $this->Form->end(); ?>
      <?php echo $this->Js->writeBuffer();?>
  </div>
</div>
<?php if($modal != 1) { ?>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
<?php } else{ ?>
<script>
  $(function() {
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
  });
</script>
<?php } ?>
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
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                    $('#witnessesModal').modal('hide');
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    });
        }
    });
		$().ready(function() {
                
    $("#submit-indicator").hide();
    jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        $('#IncidentWitnessAddAjaxForm').validate({
              rules: {
                "data[IncidentWitness][incident_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentWitness][employee_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentWitness][department_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentWitness][designation_id]": {
                    greaterThanZero: true,
                },
            }
        });      
$("#other_data").hide();
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

  
    $("[name*='date_of_interview']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly'); 
     $("[name*='date_of_interview']").datepicker('option', 'maxDate', 0);
      
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

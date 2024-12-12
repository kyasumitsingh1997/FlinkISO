


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>


<div id="incidentInvestigators_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="incidentInvestigators form col-md-8">
         <h4>
                <?php echo __('List Courses'); ?>
                 <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
            </h4>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('table th a, .pag_list li span a').on('click', function() {
                        var url = $(this).attr("href");
                        $('#courses_ajax').load(url);
                        return false;
                    });
                });
            </script>
            <div class="table-responsive" id="courses">
                <table cellpadding="0" cellspacing="0" class="table table-bordered table-condensed table table-striped table-hover">
                    <tr>
                        <th><?php echo $this->Paginator->sort('name', __('Name')); ?></th>
                        <th><?php echo $this->Paginator->sort('phone', __('Phone')); ?></th>
                        <th><?php echo $this->Paginator->sort('publish', __('Publish')); ?></th>
                    </tr>
                    <?php
                        if ($incidentInvestigators) {
                           
                            $x = 0;
                           foreach ($incidentInvestigators as $incidentInvestigator): ?>
                  
                    <tr>
                    <?php if($incidentInvestigator['IncidentInvestigator']['person_type'] == 1) { ?>
                    <td><?php echo h($incidentInvestigator['IncidentInvestigator']['name']); ?>&nbsp;</td>
                    <?php }else { ?>
                    <td><?php echo h($incidentInvestigator['Employee']['name']); ?>&nbsp;</td>
                    <?php } ?>
                        <td><?php echo h($incidentInvestigator['IncidentInvestigator']['phone']); ?>&nbsp;</td>
                       

                        <td width="60">
                           <?php if($incidentInvestigator['IncidentInvestigator']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
                            <?php } ?>&nbsp;</td>
                    </tr>
                    <?php
                        $x++;
                        endforeach;
                        } else {
                    ?>
                    <tr><td colspan=15><?php echo __('No results found'); ?></td></tr>
                    <?php } ?>
                </table>
            </div>
            <p>
                <?php
                    echo $this->Paginator->options(array(
                        'update' => '#courses',
                        'evalScripts' => true,
                        'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                        'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
                    ));

                    echo $this->Paginator->counter(array(
                        'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
                    ));
                ?></p>
            <ul class="pagination">
                <?php
                    echo "<li class='previous'>" . $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')) . "</li>";
                    echo "<li>" . $this->Paginator->numbers(array('separator' => '')) . "</li>";
                    echo "<li class='next'>" . $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')) . "</li>";
                ?>
            </ul>
<h4>Add Incident Investigator</h4>
<?php echo $this->Form->create('IncidentInvestigator',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
		
                
              
		
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
		echo "<div class='col-md-6'>".$this->Form->input('age',array()) . '</div>'; 
		echo "<div class='col-md-6'> <label>Gender</label>".$this->Form->input('gender',array('type' => 'radio', 'options' => array(0=>'Male', 1=>'Female'), 'style' => 'width:100%', 'label' => false, 'legend' => false,  'div' => false, 'style' => 'float:none', 'default'=>0)) . '</div>'; 
		
	?>
</fieldset>
<?php
                    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                     echo $this->Form->input('ajax_data', array('type' => 'hidden'));
                    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); echo $this->Form->input('state_id', array('type' => 'hidden', 'value' => $this->Session->read('User.state_id')));
                    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
                    
		?>
 <?php echo $this->Form->input('redirect', array('type' => 'hidden', 'value' => $redirect)); ?>
</div>
<div class="row">
		

<?php

		
                
	if ($showApprovals && $showApprovals['show_panel'] == true) {
                
		echo $this->element('approval_form');
                
	} else {
                
		echo $this->Form->input('publish', array('label' => __('Publish')));
                
	}
            ?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#incidentInvestigators_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
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
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target:  <?php if($redirect){echo "'#incidentInvestigators_ajax'";} else {echo "'#main'";}?>,
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
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
        $('#IncidentInvestigatorAddAjaxForm').validate({
              rules: {
                "data[IncidentInvestigator][incident_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentInvestigator][employee_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentInvestigator][department_id]": {
                    greaterThanZero: true,
                },
                "data[IncidentInvestigator][designation_id]": {
                    greaterThanZero: true,
                },
            }
        });      
        $("#other_data").hide();

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
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="projectOverallPlans_ajax">
<?php echo $this->Session->flash();?>	<div class="nav">
		<div class="projectOverallPlans form col-md-12">
			<h4>Add Project Overall Plan</h4>
			<?php echo $this->Form->create('ProjectOverallPlan',array('role'=>'form','class'=>'form','default'=>true)); ?>
			<div class="row">
				<div class="col-md-12">
					<?php 
					$calTypes = array(0=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Units/Hours',1=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hours/Units');
					echo $this->Form->input('cal_type',array('type'=>'radio','separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp',  'options'=>$calTypes, 'style'=>'min-width:auto !important', 'label'=>false))?>
					<table class="table table-condensed table-bordered makeEditables">
		                <thead>
		                    <tr>
		                        <th width="120">Plan Type</th>
		                        <th width="120">Lot/Process</th>
		                        <th width="220">Details</th>
		                        <th width="120">Est Units</th>
		                        <th width="120">Overall Metrics</th>
		                        <th width="120">Est Man Hrs</th> 
		                        <th width="120">Est Days</th> 
		                        <th width="120">Est Resource</th>
		                        <th width="120">Start Date</th>
		                        <th width="120">End Date</th>
		                    </tr>
		                </thead>
		                <tbody>
		                <?php $pop = 0; ?>
		                    <tr>
		                    	<?php 
		                    	echo "<div class='col-md-4 hide'>".$this->Form->hidden('project_id',array('default'=>$this->request->params['named']['project_id'])) . '</div>'; 
								echo "<div class='col-md-4 hide'>".$this->Form->hidden('milestone_id',array('default'=>$this->request->params['named']['milestone_id'])) . '</div>'; 
								?>
		                        <?php $planTypes = array(0=>'Field',1=>'Production'); ?>
		                        <td><?php echo $this->Form->input('plan_type',array('options'=>$planTypes, 'style'=>'min-width:auto !important', 'label'=>false))?></td>
		                        <?php $types = array(0=>'Lot',1=>'Process'); ?>
		                        <td><?php echo $this->Form->input('type',array('options'=>$types, 'style'=>'min-width:auto !important', 'label'=>false))?></td>
		                        <td><?php echo $this->Form->input('lot_process',array('label'=>false))?></td>
		                        <td><?php echo $this->Form->input('estimated_units',array('default'=>0, 'label'=>false))?></td>
		                        <td><?php echo $this->Form->input('overall_metrics',array('default'=>0,'label'=>false))?></td>
		                        <td><?php echo $this->Form->input('estimated_manhours',array('default'=>0,'label'=>false))?></td>
		                        <td><?php echo $this->Form->input('days',array('default'=>0,'label'=>false))?></td>
		                        <td><?php echo $this->Form->input('estimated_resource',array('default'=>0,'label'=>false))?></td>
		                        <td><?php echo $this->Form->input('start_date',array('label'=>false))?></td>
		                        <td><?php echo $this->Form->input('end_date',array('label'=>false))?></td>		                        
		                    </tr>
		                    
		                    <tr id="addpophere-<?php echo $key;?>-<?php echo $pop;?>">
		                </tbody>
		            </table>
				</div>
			<fieldset>
					
			</fieldset>
			<?php
			    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
			    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
			    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
			?>
		</div>
		<div class="">
<?php

		if ($showApprovals && $showApprovals['show_panel'] == true) {
			echo $this->element('approval_form');
		} else {
			echo $this->Form->input('publish', array('label' => __('Publish')));
		}?>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectOverallPlans_ajax','async' => 'false')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>
<?php // echo $this->Js->writeBuffer();?>
		</div>
	</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); 
</script>
<div class="col-md-12">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>


                $().ready(function(){

                    $("#ProjectOverallPlanDays").addClass('dayscal<?php echo $key?><?php echo $pop?>');
                    $("#ProjectOverallPlanEstimatedResource").addClass('addtotal<?php echo $key?><?php echo $pop?> ers<?php echo $key?><?php echo $pop?>');
                    $("#ProjectOverallPlanEstimatedUnits").addClass('addtotal<?php echo $key?><?php echo $pop?> units<?php echo $key?><?php echo $pop?>');
                    $("#ProjectOverallPlanOverallMetrics").addClass('addtotal<?php echo $key?><?php echo $pop?>');
                    $("#ProjectOverallPlanEstimatedManhours").addClass('addtotal<?php echo $key?><?php echo $pop?>');

                    $('input[name="data[ProjectOverallPlan][cal_type]"]').addClass('addtotal<?php echo $key?><?php echo $pop?>');


                    $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker(
                        {
                            // minDate : $("#ProjectStartDate").datepicker('getDate'),
                            // startDate : $("#ProjectStartDate").datepicker('getDate'),
                            // endDate : $("#ProjectEndDate").datepicker('getDate'),
                            dateFormat:'yy-mm-dd'   
                        });
                    
                    // $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('option', 'minDate', $("#ProjectStartDate").datepicker('getDate'));
                    // $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('option', 'startDate', $("#ProjectStartDate").datepicker('getDate'));

                    $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>EndDate").datepicker(
                        {
                            // minDate : $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('getDate'),
                            // startDate : $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('getDate'),
                            // endDate : $("#ProjectEndDate").datepicker('getDate'),
                            dateFormat:'yy-mm-dd'   
                        });
                    
                    // $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>EndDate").datepicker('option', 'minDate', $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('getDate'));
                    // $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>EndDate").datepicker('option', 'startDate', $("#ProjectEndDate").datepicker('getDate'));
                
                    $(".addtotal<?php echo $key?><?php echo $pop?>").on('change',function(){
                    	var cal_type = $('input[name="data[ProjectOverallPlan][cal_type]"]:checked').val();
            
			        	if(cal_type == 0){
			        		var units = parseFloat($("#ProjectOverallPlanEstimatedUnits").val());
				            var overall_metrics = parseFloat($("#ProjectOverallPlanOverallMetrics").val());

				            var hours = units / overall_metrics;
				            $("#ProjectOverallPlanEstimatedManhours").val(hours);	
			        	}else{
			        		var units = parseFloat($("#ProjectOverallPlanEstimatedUnits").val());
				            var overall_metrics = parseFloat($("#ProjectOverallPlanOverallMetrics").val());

				            var hours = units * overall_metrics;
				            $("#ProjectOverallPlanEstimatedManhours").val(hours);
			        	}


			        	var hours = parseFloat($("#ProjectOverallPlanEstimatedManhours").val());
                        var days = parseFloat($("#ProjectOverallPlanDays").val());
                            var mp = hours / 7 / days;
                        $("#ProjectOverallPlanEstimatedResource").val(Math.round(mp));
                        // var units = parseFloat($("#ProjectOverallPlanEstimatedUnits").val());
                        // var overall_metrics = parseFloat($("#ProjectOverallPlanOverallMetrics").val());

                        // var hours = units / overall_metrics;
                        // $("#ProjectOverallPlanEstimatedManhours").val(hours);

                        // var days = hours / 7;
                        // $("#ProjectOverallPlanDays").val(days);
                    });

                    $("#ProjectOverallPlanDays").on('change',function(){
                        var hours = parseFloat($("#ProjectOverallPlanEstimatedManhours").val());
                        var days = parseFloat($("#ProjectOverallPlanDays").val());
                            var mp = hours / 7 / days;
                        $("#ProjectOverallPlanEstimatedResource").val(Math.round(mp));
                    });


                    $("#ProjectOverallPlanStartDate").on('change',function(){
                        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/start_date:" + btoa($("#ProjectOverallPlanStartDate").val()) +'/days:'+ $("#ProjectOverallPlanDays").val() , function(data) {
                                  console.log(data);
                                  $("#ProjectOverallPlanEndDate").val(data);
                            });
                    });

                });


    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                $(element).attr('name') == 'data[ProjectOverallPlan][project_id]' ||
				$(element).attr('name') == 'data[ProjectOverallPlan][milestone_id]')
			{	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#projectOverallPlans_ajax',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                   $("#loadhear").html('');
                   $("#producionModal").modal('toggle');

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
	        
	        $('#ProjectOverallPlanAddAjaxForm').validate({
	            rules: {
					"data[ProjectOverallPlan][project_id]": {
						greaterThanZero: true,
					},"data[ProjectOverallPlan][milestone_id]": {
						greaterThanZero: true,
					},
				}
	        }); 

			$('#ProjectOverallPlanProjectId').change(function() {
				if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
					$(this).next().next('label').remove();
				}
			});
			$('#ProjectOverallPlanMilestoneId').change(function() {
				if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
					$(this).next().next('label').remove();
				}
			});       
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

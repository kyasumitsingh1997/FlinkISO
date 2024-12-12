<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?><div id="projectResources_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectResources form col-md-8">
<?php 
$qucipro = $this->requestAction('projects/projectdates/'.$this->request->params['named']['project_id']);
echo $this->element('projectdates',array('qucipro'=>$qucipro));
?>
<h4><?php echo __('Edit Project Resource'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('ProjectResource',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('user_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('project_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('milestone_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('project_activity_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('mandays') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('resource_cost') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('resource_sub_total',array('default'=>$this->request->data['ProjectResource']['mandays'] * $this->request->data['ProjectResource']['resource_cost'])) . '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
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
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectResources_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                
			$(element).attr('name') == 'data[ProjectResource][user_id]' ||
			$(element).attr('name') == 'data[ProjectResource][project_id]' ||
			$(element).attr('name') == 'data[ProjectResource][milestone_id]')
						{	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
    });
    
    $().ready(function() {

    	$("#ProjectResourceMandays").on('change',function(){
    		$("#ProjectResourceResourceSubTotal").val(parseInt($("#ProjectResourceMandays").val()) * parseInt($("#ProjectResourceResourceCost").val()))
    	});

    	$("#ProjectResourceResourceCost").on('change',function(){
    		$("#ProjectResourceResourceSubTotal").val(parseInt($("#ProjectResourceMandays").val()) * parseInt($("#ProjectResourceResourceCost").val()))
    	});

    	jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#ProjectResourceEditForm').validate({        	
            rules: {
				"data[ProjectResource][user_id]": {
                		greaterThanZero: true,
					},
				"data[ProjectResource][project_id]": {
                		greaterThanZero: true,
					},
				"data[ProjectResource][milestone_id]": {
                		greaterThanZero: true,
					},
                
            }
        }); 
			
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ProjectResourceEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ProjectResourceEditForm').submit();
            }

        });

		$('#ProjectResourceUserId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ProjectResourceProjectId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ProjectResourceMilestoneId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});	

    });
</script>
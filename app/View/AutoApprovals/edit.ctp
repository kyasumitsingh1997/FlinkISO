 <div id="autoApprovals_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="autoApprovals form col-md-8">
<h4><?php echo __('Edit Auto Approval'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('AutoApproval',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('system_table',array()) . '</div>'; 	
		echo "<div class='col-md-6'>".$this->Form->input('name',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="row">
  <div id="steps-tabs" class="col-md-12">	
    <ul>
					<?php 
          
            foreach ($allBranches as $key => $value) { 
            $i = 1;
            ?>
						  <li><a href="#<?php echo $key;?>"><?php echo $value; ?></a></li>
					<?php $i = $i+10; } ?>
		</ul>

    <?php 
 	   foreach ($allBranches as $key => $value) { 
      $i = 1; ?>
        <div id="<?php echo $key?>">
          <fieldset>
          <?php
          $x= 1;
           foreach($this->data['AutoApprovalStep'] as $appData)
            	{
            		
		            	if($appData['branch_id'] == $key && $appData['step_number'] == $x)
		            	{
		            		
		            		$new_data = $appData;		            			
		            		echo "<div class='col-md-12'><h4> Step " . $x ."</h4></div>";
							echo $this->Form->hidden($key . '.AutoApprovalStep'.'.'.$x.'.step_number',array('value'=>$x,'label'=>'&nbsp;')); 
							
							echo "<div class='col-md-6'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$x.'.name',
								array('value'=>$new_data['name'])) . '</div>'; 
							
							echo "<div class='col-md-6'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$x.'.user_id',array('style'=>'width:100%','class'=>'chosen-select','label'=>'User to send','options'=>$fwd_users,
								'default'=>$new_data['user_id'])) . '</div>';
							
							echo "<div class='col-md-12'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$x.'.details',array('label'=>'Any other details/notes  <small>(Optional)</small>','value'=>$new_data['details'])) . '</div>'; 
							
							echo "<div class='col-md-7'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$x.'.allow_approval',array('label'=>'Allow approval at this stage and skip rest of the stages?','checked'=>$new_data['allow_approval'])) . '</div>';
							
							echo "<div class='col-md-5'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$x.'.show_details',array('label'=>'Share details/notes with the user?',
								'checked'=>$new_data['show_details'])) . '</div>';
							
							echo $this->Form->hidden($key . '.AutoApprovalStep'.'.'.$x.'.branch_id',array('type'=>'text','value'=>$key));
							echo $this->Form->hidden($key . '.AutoApprovalStep'.'.'.$x.'.auto_approval_id',array('type'=>'text','value'=>$new_data['auto_approval_id']));
							echo $this->Form->hidden($key . '.AutoApprovalStep'.'.'.$x.'.id',array('type'=>'text','value'=>$new_data['id']));
							$x++;	

						}	
					
         		}

         		for($i = $x; $i <= 10; $i ++){
            				echo "<div class='col-md-12'><h4> Step " . $i ."</h4></div>";
							echo $this->Form->hidden($key . '.AutoApprovalStep'.'.'.$i.'.step_number',array('value'=>$i,'label'=>'&nbsp;')); 
							echo "<div class='col-md-6'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$i.'.name',array('value'=>'Step '. $i)) . '</div>'; 
							echo "<div class='col-md-6'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$i.'.user_id',array('style'=>'width:100%','class'=>'chosen-select','label'=>'User to send','options'=>$fwd_users)) . '</div>';
							echo "<div class='col-md-12'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$i.'.details',array('label'=>'Any other details/notes  <small>(Optional)</small>',)) . '</div>'; 
							echo "<div class='col-md-7'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$i.'.allow_approval',array('label'=>'Allow approval at this stage and skip rest of the stages?')) . '</div>';
							echo "<div class='col-md-5'>".$this->Form->input($key . '.AutoApprovalStep'.'.'.$i.'.show_details',array('label'=>'Share details/notes with the user?')) . '</div>';
							echo $this->Form->hidden($key . '.AutoApprovalStep'.'.'.$i.'.branch_id',array('type'=>'text','value'=>$key));
							echo $this->Form->hidden($key . '.AutoApprovalStep'.'.'.$i.'.auto_approval_id',array('type'=>'text','value'=>$id));
				}			
							
	            
					
				
          ?> 
          
          </fieldset>
         </div>
        <?php } ?>
  </div>
</div>
<script>$(function() {
  $(".chosen-container").width('100%');
  $(".chosen-container-single").width('100%');
  $( "#steps-tabs" ).tabs();});</script>

<?php
                    echo $this->Form->input('id');
                    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); echo $this->Form->input('state_id', array('type' => 'hidden', 'value' => $this->Session->read('User.state_id')));
                    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

<div class="row">
		
<?php

		
                
	if ($showApprovals && $showApprovals['show_panel'] == true) {
                
		echo $this->element('approval_form');
                
	} else {
                
		echo $this->Form->input('publish', array('label' => __('Publish')));
                
	}
?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#autoApprovals_ajax','async' => 'false')); ?>
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
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#autoApprovals_ajax')));?>

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
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
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
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    });
        }
    });
		$().ready(function() {
    $("#submit-indicator").hide();
        $('#AutoApprovalAddAjaxForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

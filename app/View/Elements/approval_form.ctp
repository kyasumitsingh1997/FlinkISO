<script>
	$().ready(function() {
             <?php if(!isset($show_auto_approval_panel) && $show_auto_approval_panel == false){ ?>
		$('#ApprovalUserId').change(function() {
			if ($('#ApprovalUserId').val() != -1) {
				$("[name*='publish']").prop('readonly', 'readonly');
				$("[name*='publish']").prop('checked', false);
			} else {
				$('#ApprovalComments').val('');
				$("[name*='publish']").removeProp("readonly", true)
			}
		});
                $('input[type="checkbox"][name*="publish"]').click(function(e) {
                    if ($('#ApprovalUserId').val() != -1) {
                        return false;
                    }
                });
            <?php  }else{ ?>
//               $('#ApprovalUserId').change(function() {
//			if ($('#ApprovalUserId').val() != -1) {
//				$("[name*='publish']").prop('readonly', 'readonly');
//				$("[name*='publish']").prop('checked', false);
//			} else {
//				$('#ApprovalComments').val('');
//				$("[name*='publish']").removeProp("readonly", true)
//			}
//		});
                $('input[type="checkbox"][name*="send_forward"]').click(function(e) {
                   $("[name*='publish']").prop('checked', false);
                   $("[name*='send_back']").prop('checked', false);
                   $("#ApprovalSendBackUserId").val(0).trigger('chosen:updated');
                   $("#ApprovalSendBackUserId_chosen").hide();
                   $("#send_back_user").css("visibility", "hidden");
                   $("#send_forward_user").css("visibility", "visible");
                   $("#ApprovalSendForwardUserId_chosen").show();
;
                   
                });
                $('input[type="checkbox"][name*="send_back"]').click(function(e) {
                   $("[name*='publish']").prop('checked', false);
                   $("[name*='send_forward']").prop('checked', false);
                   $('#ApprovalSendForwardUserId').val(0).trigger('chosen:updated');
                   $("#ApprovalSendBackUserId_chosen").show();
                   $("#ApprovalSendForwardUserId_chosen").hide();
                   $("#send_forward_user").css("visibility", "hidden");
                   $("#send_back_user").css("visibility", "visible");

                });
                $('input[type="checkbox"][name*="publish"]').click(function(e) {
                   $("[name*='send_forward']").prop('checked', false);
                   $('#ApprovalSendForwardUserId').val(0).trigger('chosen:updated');
                   $("[name*='send_back']").prop('checked', false);
                   $("#ApprovalSendBackUserId").val(0).trigger('chosen:updated');
                   $("#send_forward_user").css("visibility", "hidden");
                   $("#send_back_user").css("visibility", "hidden");
                });
                
                  $("#send_forward_user").css("visibility", "hidden");
                   $("#send_back_user").css("visibility", "hidden");
            <?php  } ?>
		
	});
</script>

<?php
	$modelName = Inflector::singularize($this->name);
	if (isset($this->data['CreatedBy']['username']))
		$approversList[$this->data[$modelName]['created_by']] = $this->data['CreatedBy']['name'] . " (" . $this->data['CreatedBy']['username'] . ")";
?>
<?php 
	if($this->request->params['controller'] == 'internal_audit_plans')
	{
		if($show_auto_approval_panel)$disabled = true; ?>
		<div class =row>
			<div class = "col-md-6">
				<?php echo $this->Form->input('prepared_by', 
						array('options' => $PublishedEmployeeList,'default'=>$internalAuditPlan['InternalAuditPlan']['prepared_by'])); ?>
			</div>
			<div class = "col-md-6">
					<?php if(!$show_auto_approval_panel)echo $this->Form->input('approved_by', 
						array('disabled'=>$disabled,'options' => $PublishedEmployeeList,'default'=>$internalAuditPlan['InternalAuditPlan']['approved_by'])); ?>
			</div>
		</div>
	
	<?php }else{?>

		<div class="row">
			<div class = "col-md-6">
					<?php echo $this->Form->input('prepared_by', 
						array('options' => $PublishedEmployeeList,'default'=>$this->Session->read('User.employee_id'))); ?>
			</div>
			<div class = "col-md-6">
			<?php
			if($this->Session->read('User.is_approvar')==1){
				if(!$show_auto_approval_panel)echo $this->Form->input('approved_by', 
						array('disabled'=>$disabled,'options' => $PublishedEmployeeList,'default'=>$internalAuditPlan['InternalAuditPlan']['approved_by'])); 
			}else{
				echo "<div class='alert alert-danger'><strong>Note:</strong>Choose approvar form the drop down below and send the record for approval process.</div>";	
			}?>
			</div>
		</div>
	<?php }?>
	<div class="clearfix">&nbsp;</div>
	
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php if(!isset($show_auto_approval_panel)) { ?>
				<h3 class="panel-title"><?php echo __("Send for approval") ?></h3>
			<?php } else { ?> 
				<h3 class="panel-title"><?php echo __("Auto Approval") ?> : <?php echo $current_approval_step['AutoApprovalStep']['name']; ?></h3>
			<?php } ;?>
		</div>
		<div class="panel-body">
			<?php echo __("Send appended records for approval to the relevant approving authority from the given list.") ?>
			<br />
			<span class="text-danger"><?php echo __("You can upload approval related files after submitting the record. To add files, after submitting, click on 'Approval' button at the top. It will open a new page, select your record, goto Approval History and upload files.") ?>
			</span>
			<?php if (isset($approversList))  ?>
			<!-- approval for if auto approvals are not set -->	
			<?php if(!isset($show_auto_approval_panel) && $show_auto_approval_panel == false) { ?>			
				<div class="row">
					<div class="col-md-12">
						<?php 
							echo $this->Form->input('Approval.user_id', array('label'=>'Select user you want to send this record for approval',
									'options' => $approversList)); 					
						?>
					</div>
					<?php 
						echo $this->Form->hidden('Approval.send_back', array('type'=>'checkbox','value'=>0)); 
						echo $this->Form->hidden('Approval.send_forward', array('type'=>'checkbox','value'=>0)); 
						?>
					<div class="col-md-12">
							<?php echo $this->Form->input('Approval.comments', array('label'=>'Add your initial comments','type' => 'textarea')); ?>
					</div>
				</div>
				
			<?php } else { ?> 
				<!-- approval for if auto approvals are set -->
					<div class="row">
						
						<?php echo $this->Form->hidden('Approval.user_id', array('options' => $approversList));?>
						<?php echo $this->Form->hidden('Approval.auto_approval_id', array('type'=>'text','value'=>$current_approval_step['AutoApprovalStep']['auto_approval_id']));?>
						<?php echo $this->Form->hidden('Approval.auto_approval_step_id', array('type'=>'text','value'=>$current_approval_step['AutoApprovalStep']['id']));?>
						<?php echo $this->Form->hidden('Approval.auto_step', array('value'=>$current_approval_step['AutoApprovalStep']['step_number']));?>

						<?php echo $this->Form->hidden('Approval.next_auto_approval_step_id', array('type'=>'text','value'=>$next_approval_step['AutoApprovalStep']['id']));?>
						<?php echo $this->Form->hidden('Approval.next_auto_step', array('value'=>$next_approval_step['AutoApprovalStep']['step_number']));?>
						
						<div class="col-md-12">
							<?php 
								if($current_approval_step['AutoApprovalStep']['show_details'] == 1)$comments  = $current_approval_step['AutoApprovalStep']['details'];
								else $comments = 'Send for approval';
								echo $this->Form->input('Approval.comments', 
								array('label'=>'Add your comments','type' => 'textarea','default'=> $comments)); ?>
						</div>
												
						<div class="col-md-6">
							<?php if(!empty($approvalHistory['history'])){?>	
							<?php echo $this->Form->input('Approval.send_back', array('type'=>'checkbox')); ?>
							<?php } ?>							
						</div>						
						
						<div class="col-md-6">
							<?php if($next_approval_step or $current_approval_step['AutoApprovalStep']['user_id'] != $this->Session->read('User.id')){
								 echo $this->Form->input('Approval.send_forward', array('type'=>'checkbox')); 
								}	
								 ?>
							
						</div>
						

						<div class="col-md-6" id="send_back_user">
							<?php if(!empty($approvalHistory['history'])){?>
							<?php
								if($previous_approval_step){
									echo $this->Form->input('Approval.send_back_user_id', array('options'=>$users_group, 'default'=>$previous_approval_step['AutoApprovalStep']['user_id']));
								}else{
									echo $this->Form->input('Approval.send_back_user_id', array('options'=>$users_group, 'default'=>$current_approval['Approval']['from']));	
								}
								
								?>
							<small>Record will be sent back to creator</small>
							<?php } ?>
						</div>
						
						<div class="col-md-6" id="send_forward_user">
							
							<?php 							
							if($next_approval_step or $current_approval_step['AutoApprovalStep']['user_id'] != $this->Session->read('User.id')){
								if($current_approval_step['AutoApprovalStep']['user_id'] != $this->Session->read('User.id')){
									echo $this->Form->input('Approval.send_forward_user_id', 
										array('options'=>$users_group, 'default'=>$current_approval_step['AutoApprovalStep']['user_id']));	
								}else{
								echo $this->Form->input('Approval.send_forward_user_id', 
										array('options'=>$users_group, 'default'=>$next_approval_step['AutoApprovalStep']['user_id']));
								}
							
							?>
							<small>Record will be sent back to next approvar</small>
							<?php } 

							?>
						</div>
						
					</div>	
			<?php }	 ?>
		</div>
		<?php if (isset($showApprovals['show_publish']) && $showApprovals['show_publish'] == true) { ?>
		<div class="panel-footer">
			<h5>					
			<?php if( isset($show_auto_approval_panel) && $current_approval_step['AutoApprovalStep']['allow_approval'] == 0){ ?>
				<?php echo $this->Form->hidden('publish'); ?>
					<span class="help-block">
						<?php echo __('Can not publish record at this stage. Select either select "Send Back" or "Send Forward" option and a correct user.'); ?></span>
				<?php }else{ ?>

					<?php echo $this->Form->input('publish'); ?>
					<span class="help-block"><?php echo __('Check the checkbox above to publish the record'); ?></span>
				
				<?php } ?>
			</h5>
		</div>
		<?php } ?>
	</div>

	<?php if (isset($approvalHistory) && isset($approvalHistory['History'])) echo $this->element("approval_history"); ?>

<!--<style>
#p_rule .modal-dialog{width:60%; height:100%}
</style>
<div class="modal fade" id="p_rule">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Document Details</h4>
      </div>
      <div class="modal-body"> -->  			
<div class="nav"><div class="col-md-12">	  
<h2><?php echo __('Define Followup Rules'); ?><small>
<div class="btn btn-group pull-right">
	<?php echo $this->Html->link('Add Customer',array('controller'=>'customers','action'=>'index'),array('class'=>'btn btn-xs btn-default')); ?>
	<?php echo $this->Html->link('Add Proposal',array('controller'=>'proposals','action'=>'lists'),array('class'=>'btn btn-xs btn-default')); ?>
</div></small></h2>

	<div id="main">
			<?php echo $this->Session->flash(); ?>
			<div class="ProposalFollowupRules">
				<?php 
					for($i=1;$i<=365;$i = $i+30){
						$options[$i] = round($i / 30) . ' - Months';
					}
					?>
				<?php if($proposalFollowupRules){ ?>
				<table class="table table-bordered" >
					<tr>
						<th>Rule</th>
						<th>Duration</th>
						<th>Notes</th>
						<th width="65">Action</th>
					</tr>
					<?php foreach($proposalFollowupRules as $rules): ?>
					<tr>
						<td><?php echo $rules['ProposalFollowupRule']['rule']; ?></td>
						<td><strong><?php echo round(($rules['ProposalFollowupRule']['number_of_followups_required']-1)/30) . ' Months'; ?></strong>
							<?php 	$followUpCount = json_decode($rules['ProposalFollowupRule']['followup_sequence'],true);
										$followUpCount = count($followUpCount);
										echo " - " . $followUpCount . ' Follow ups'; ?></td>
						<td><?php echo $rules['ProposalFollowupRule']['notes']; ?></td>
						<td><?php echo $this->html->link('Edit','#',array('id'=>'resetRules_'.$rules['ProposalFollowupRule']['id'],'class'=>'btn btn-warning btn-sm','escape'=>false)); ?></td>
					</tr>
					<script>
					$('#resetRules_<?php echo $rules['ProposalFollowupRule']['id']; ?>').click(function(){
							$('#loadData').load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/edit/<?php echo $rules['ProposalFollowupRule']['id']; ?>');
							$('#addNewData').hide();
						});
					</script>
					<?php endforeach; ?>
				</table>
				<div id="loadData"></div>
				<?php } ?>
				<div class="panel panel-default" id="addNewData">
					<div class="panel-title">
						<div class="panel-heading">
							<h4>Add New Followup Rules</h4>
							<small>Name your rule from "Rule" textbox, select "Duration" form dropdown (in months), it will open a panel, select day and type of followup requried. Click save</small>
						</div>
					</div>
					<div class="panel-body"> <?php echo $this->Form->create('ProposalFollowupRule', array('role' => 'form', 'class' => 'form')); ?>
						<div class="row">
							<div class="col-md-8"><?php echo $this->Form->input('rule'); ?></div>
							<div class="col-md-4"><?php echo $this->Form->input('number_of_followups_required',array('label'=>'Duration','options'=>$options)); ?></div>
							<div id="draw" class="col-md-12"></div>
							<div class="col-md-12 hide"><?php echo $this->Form->input('followup_sequence'); ?></div>
							<div class="col-md-12"><?php echo $this->Form->input('notes'); ?></div>
							<div class="col-md-12">
							<?php
                				if ($showApprovals && $showApprovals['show_panel'] == true) {
									echo $this->element('approval_form');
								} else {
									echo $this->Form->input('publish');
								}
							?></div>
							<div class="col-md-12"> <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?> 
							<?php echo $this->Form->input('followup_sequence', array('type' => 'hidden', 'value' => 'Preparing')); ?> 
							<?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?> 
							<?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
										echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));
										echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator'));
										echo $this->Form->end(); 
										echo $this->Js->writeBuffer(); 
									?> </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
			<script>
				$().ready(function(){
					//$('#p_rule').modal();
					$('#ProposalFollowupRulePreparedBy').chosen();
					$('#ProposalFollowupRuleApprovedBy').chosen();
					$('#ProposalFollowupRuleNumberOfFollowupsRequired').chosen();	
					$('#ApprovalUserId').chosen();
	
					$('#ProposalFollowupRuleNumberOfFollowupsRequired').change(function(){
					var	draw = $('#ProposalFollowupRuleNumberOfFollowupsRequired').val();
					var drawHtml = '';
					for(i=1 ; i<= draw ; i++){	
						if(i == 1){
							drawHtml = drawHtml + "<div class=\"row\">";
						};
						
						drawHtml  = drawHtml + "<div class='col-md-4'><div class='btn-group'><div class='btn btn-sm btn-default' id= 'days"+i+"' onClick=checks("+i+")><strong>Day "+ i + "</strong></div>";
						drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Email_"+i+"_0' onClick=reset_checks('Email',"+i+")>Email <input style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Email\" id=\"folloupType_" + i + "_0\"></div>";
						drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Call_"+i+"_1' onClick=reset_checks('Call',"+i+")>Call <input  style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Call\" id=\"folloupType_"+ i + "_1\"></div>";
						drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Visit_"+i+"_2' onClick=reset_checks('Visit',"+i+")>Visit <input  style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Visit\" id=\"folloupType_"+ i + "_2\"></div>";
						drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Other_"+i+"_3' onClick=reset_checks('Other',"+i+")>Other <input  style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Other\" id=\"folloupType_"+ i + "_3\"></div>";
						drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Any_"+i+"_4' onClick=reset_checks('Any',"+i+")>Any <input  style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Any\" id=\"folloupType_"+ i + "_4\"></div></div></div>";
						if(i == 3) drawHtml + "<\/div>";
						if(i % 3 == 0){
							drawHtml = drawHtml + "<\/div><br /><div class=\"row\">"
						};
					};
					$('#draw').html(drawHtml);
					});
					
				});
			
				function checks(ctr){
					
					$('#days'+ctr).toggleClass(' btn-default');
					$('#days'+ctr).toggleClass(' btn-success');
					
					var newClass = $('#days'+ctr).prop('class');

					if(newClass == 'btn btn-sm btn-success'){
						$('#Any_'+ctr+'_4').removeClass(' disabled').addClass(' btn-default');
						$('#Any_'+ctr+'_4').toggleClass(' btn-default').addClass(' btn-default');
						$('#Any_'+ctr+'_4').toggleClass(' btn-success').addClass(' btn-default');	
						
						$('#Email_'+ctr+'_0').removeClass(' disabled').addClass(' btn-default');		
						$('#Call_'+ctr+'_1').removeClass(' disabled').addClass(' btn-default');		
						$('#Visit_'+ctr+'_2').removeClass(' disabled').addClass(' btn-default');		
						$('#Other_'+ctr+'_3').removeClass(' disabled').addClass(' btn-default');		
						
						$('#folloupType_'+ctr+'_0').prop('checked',false);
						$('#folloupType_'+ctr+'_1').prop('checked',false);
						$('#folloupType_'+ctr+'_2').prop('checked',false);
						$('#folloupType_'+ctr+'_3').prop('checked',false);	
						$('#folloupType_'+ctr+'_4').prop('checked',true);	
					}else{
						$('#Email_'+ctr+'_0').add(' btn-default disabled').removeClass(' btn-success');	
						$('#Call_'+ctr+'_1').add(' btn-default disabled').removeClass(' btn-success');	
						$('#Visit_'+ctr+'_2').add(' btn-default disabled').removeClass(' btn-success');	
						$('#Other_'+ctr+'_3').add(' btn-default disabled').removeClass(' btn-success');	
						$('#Any_'+ctr+'_4').add(' btn-default disabled').removeClass(' btn-success');	
						
						$('#folloupType_'+ctr+'_0').prop('checked',false);
						$('#folloupType_'+ctr+'_1').prop('checked',false);
						$('#folloupType_'+ctr+'_2').prop('checked',false);	
						$('#folloupType_'+ctr+'_3').prop('checked',false);	
						$('#folloupType_'+ctr+'_4').prop('checked',false);	
					}
				}
				
				function reset_checks(whatIsClicked, ctr){
					switch(whatIsClicked){
						case 'Email':							
							$('#Call_'+ctr+'_1').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_1').prop('checked',false);	
							$('#Visit_'+ctr+'_2').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_2').prop('checked',false);	
							$('#Other_'+ctr+'_3').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_3').prop('checked',false);	
							$('#Any_'+ctr+'_4').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_4').prop('checked',false);
							$('#Email_'+ctr+'_0').addClass(' btn-success');$('#folloupType_'+ctr+'_0').prop('checked',true);	
							break;
						
						case 'Call':							
							$('#Email_'+ctr+'_0').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_0').prop('checked',false);	
							$('#Visit'+ctr+'_2').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_2').prop('checked',false);	
							$('#Other_'+ctr+'_3').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_3').prop('checked',false);	
							$('#Any_'+ctr+'_4').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_4').prop('checked',false);							
							$('#Call_'+ctr+'_1').addClass(' btn-success');$('#folloupType_'+ctr+'_1').prop('checked',true);	
							break;
						
						case 'Visit':							
							$('#Email_'+ctr+'_0').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_0').prop('checked',false);	
							$('#Call_'+ctr+'_1').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_1').prop('checked',false);
							$('#Other_'+ctr+'_3').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_3').prop('checked',false);		
							$('#Any_'+ctr+'_4').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_4').prop('checked',false);							
							$('#Visit_'+ctr+'_2').addClass(' btn-success');$('#folloupType_'+ctr+'_2').prop('checked',true);	
							
							break;
							
						case 'Other':							
							$('#Email_'+ctr+'_0').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_0').prop('checked',false);	
							$('#Call_'+ctr+'_1').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_1').prop('checked',false);	
							$('#Visit_'+ctr+'_2').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_2').prop('checked',false);	
							$('#Any_'+ctr+'_4').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_4').prop('checked',false);							
							$('#Other_'+ctr+'_3').addClass(' btn-success');$('#folloupType_'+ctr+'_3').prop('checked',true);	
							break;		
						
						case 'Any':								
							$('#Email_'+ctr+'_0').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_0').prop('checked',false);	
							$('#Call_'+ctr+'_1').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_1').prop('checked',false);	
							$('#Visit_'+ctr+'_2').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_2').prop('checked',false);	
							$('#Other_'+ctr+'_3').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_3').prop('checked',false);	
							$('#Any_'+ctr+'_4').addClass(' btn-success');$('#folloupType_'+ctr+'_4').prop('checked',true);
							break;
					}
				}
			</script> 
			<script>
				$.ajaxSetup({
					beforeSend: function () {
						$("#busy-indicator").show();
					},
					complete: function () {
						$("#busy-indicator").hide();
					}
				});
			</script>
<!--			</div>
      <div class="modal-footer">
	  	<p><small>Close the panel after making the changes, add "Reason for change" and save the form.</small></p>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->			
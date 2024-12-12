<?php
	echo $this->Html->script(array(
		'plugins/jQuery/jQuery-2.2.0.min',
    	'plugins/jQueryUI/jquery-ui.min',
    	'js/bootstrap.min',
    	'dist/js/demo',
    	'dist/js/app.min',
    	));    
	echo $this->fetch('script');
    // echo $this->Html->css(array('flinkiso'));
    // echo $this->fetch('css');
?>
<?php 
		for($i=1;$i<=365;$i = $i+30){
			$options[$i] = round($i / 30) . ' - Months';
		}
		?>
<?php echo $this->Session->flash(); ?>		
<div class="panel panel-default">
		<div class="panel-title"><div class="panel-heading">Set / Reset Rules</div></div>
		<div class="panel-body">	
			<?php echo $this->Form->create('ProposalFollowupRule', array('role' => 'form', 'class' => 'form')); ?>
			<?php echo $this->Form->hidden('id',array('value'=>$this->request->data['ProposalFollowupRule']['id'])); ?>
				<div class="row">
					<div class="col-md-8"><?php echo $this->Form->input('rule',array('value'=>$this->request->data['ProposalFollowupRule']['rule'])); ?></div>
					<div class="col-md-4"><?php echo $this->Form->input('number_of_followups_required',array('label'=>'Duration','options'=>$options,'default'=>$this->request->data['ProposalFollowupRule']['number_of_followups_required'])); ?></div>
					<div id="draw" class="col-md-12">
					
					
					
					<?php 
	$followups = json_decode($this->request->data['ProposalFollowupRule']['followup_sequence'],true);	
	for($n = 1; $n <= $this->request->data['ProposalFollowupRule']['number_of_followups_required']; $n++){
	$x = false;
	$check = '';
		foreach($followups as $key=>$followup): 
			if($followup == 'Email')$type=0;
			if($followup == 'Call')$type=1;
			if($followup == 'Visit')$type=2;
			if($followup == 'Other')$type=3;
			if($followup == 'Any')$type=4;
			if($key == $n):$x = true;	$check = $followup;endif;

		endforeach;
		if($x == true){	
		if($n%3 == 0 or $n == 1)echo "<div class='row'>";
		?>
		
		<div class="col-md-4">
			<div class='btn-group'>	
				<div class='btn btn-sm btn-success' id= 'days<?php echo $n; ?>' onClick=checks(<?php echo $n; ?>)><strong>Day <?php echo $n; ?></strong></div>
				<?php if($check == 'Email'){ ?>			
					<div class='btn btn-sm btn-success' id='Email_<?php echo $n; ?>_0' onClick=reset_checks('Email',<?php echo $n; ?>)>Email 
					<input  style='display:none'  type="checkbox" checked name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Email" id="folloupType_<?php echo $n; ?>_0">
					</div>
				<?php } else { ?>
					<div class='btn btn-sm btn-default disabled' id='Email_<?php echo $n; ?>_0' onClick=reset_checks('Email',<?php echo $n; ?>)>Email 
					<input  style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Email" id="folloupType_<?php echo $n; ?>_0">
					</div>
				<?php } ?>		
			
				
				<?php if($check == 'Call'){ ?>			
					<div class='btn btn-sm btn-success' id='Call_<?php echo $n; ?>_1' onClick=reset_checks('Call',<?php echo $n; ?>)>Call 
					<input style='display:none'  type="checkbox" checked name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Call" id="folloupType_<?php echo $n; ?>_1">
					</div>
				<?php } else { ?>
					<div class='btn btn-sm btn-default disabled' id='Call_<?php echo $n; ?>_1' onClick=reset_checks('Call',<?php echo $n; ?>)>Call 
					<input style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Call" id="folloupType_<?php echo $n; ?>_1">
					</div>
				<?php } ?>
				
				<?php if($check == 'Visit'){ ?>			
					<div class='btn btn-sm btn-success' id='Visit_<?php echo $n; ?>_2' onClick=reset_checks('Visit',<?php echo $n; ?>)>Visit 
					<input style='display:none'  type="checkbox" checked name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Visit" id="folloupType_<?php echo $n; ?>_2">
					</div>
				<?php } else { ?>
					<div class='btn btn-sm btn-default disabled' id='Visit_<?php echo $n; ?>_2' onClick=reset_checks('Visit',<?php echo $n; ?>)>Visit 
					<input style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Visit" id="folloupType_<?php echo $n; ?>_2">
					</div>
				<?php } ?>
				
				<?php if($check == 'Other'){ ?>			
					<div class='btn btn-sm btn-success' id='Other_<?php echo $n; ?>_3' onClick=reset_checks('Other',<?php echo $n; ?>)>Other 
					<input style='display:none'  type="checkbox" checked name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Other" id="folloupType_<?php echo $n; ?>_3">
					</div>
				<?php } else { ?>
					<div class='btn btn-sm btn-default disabled' id='Other_<?php echo $n; ?>_3' onClick=reset_checks('Other',<?php echo $n; ?>)>Other 
					<input style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Other" id="folloupType_<?php echo $n; ?>_3">
					</div>
				<?php } ?>		

				
				<?php if($check == 'Any'){ ?>			
					<div class='btn btn-sm btn-success' id='Any_<?php echo $n; ?>_4' onClick=reset_checks('Any',<?php echo $n; ?>)>Any 
					<input style='display:none'  type="checkbox" checked name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Any" id="folloupType_<?php echo $n; ?>_4">
					</div>
				<?php } else { ?>
					<div class='btn btn-sm btn-default disabled' id='Any_<?php echo $n; ?>_4' onClick=reset_checks('Any',<?php echo $n; ?>)>Any 
					<input style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Any" id="folloupType_<?php echo $n; ?>_4">
					</div>
				<?php } ?>		
			</div>
		</div>
		<?php }else { if($n%3 == 0 or $n == 1)echo "<div class='row'>";?>
		<div class="col-md-4">
			<div class='btn-group'>
				<div class='btn btn-sm btn-default' id= 'days<?php echo $n; ?>' onClick=checks(<?php echo $n; ?>)><strong>Day <?php echo $n; ?></strong></div>
				<div class='btn btn-sm btn-default disabled' id='Email_<?php echo $n; ?>_0' onClick=reset_checks('Email',<?php echo $n; ?>)>Email 
					<input style='display:none'   type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Email" id="folloupType_<?php echo $n; ?>_0">
				</div>
				<div class='btn btn-sm btn-default disabled' id='Call_<?php echo $n; ?>_1' onClick=reset_checks('Call',<?php echo $n; ?>)>Call 
					<input style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Call" id="folloupType_<?php echo $n; ?>_1">
				</div>
				<div class='btn btn-sm btn-default disabled' id='Visit_<?php echo $n; ?>_2' onClick=reset_checks('Call',<?php echo $n; ?>)>Visit 
					<input style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Visit" id="folloupType_<?php echo $n; ?>_2">
				</div>
				<div class='btn btn-sm btn-default disabled' id='Other_<?php echo $n; ?>_3' onClick=reset_checks('Other',<?php echo $n; ?>)>Other 
					<input style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Other" id="folloupType_<?php echo $n; ?>_3">
				</div>
				<div class='btn btn-sm btn-default disabled' id='Any_<?php echo $n; ?>_4' onClick=reset_checks('Any',<?php echo $n; ?>)>Any 
					<input style='display:none'  type="checkbox" name="data[ProposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Any" id="folloupType_<?php echo $n; ?>_4">
				</div>
			</div>
		</div>	
		<?php } ?>
		<?php
		if($n%3 ==0)echo "</div><br />";
}
?>	
					
					
					
					
					</div>
					</div>
					<div id="drawNew" class="col-md-12">
					
					draw here
					</div>
					<div class="col-md-12 hide"><?php echo $this->Form->input('followup_sequence',array('value'=>$this->request->data['ProposalFollowupRule']['followup_sequence'])); ?></div>
					<div class="col-md-12"><?php echo $this->Form->input('notes',array('value'=>$this->request->data['ProposalFollowupRule']['notes'])); ?></div>				
					<div class="col-md-12">	
					<?php
                				if ($showApprovals && $showApprovals['show_panel'] == true) {
									echo $this->element('approval_form');
								} else {
									echo $this->Form->input('publish');
								}
							?>
						<?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
						<?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
						<?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
							echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));
							echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator'));
							echo $this->Form->end(); 
							echo $this->Js->writeBuffer(); 
						?>
					</div>
				</div>
			</div>
	</div>

<script>
	$('document').ready(function(){
		$('#ProposalFollowupRuleNumberOfFollowupsRequired').chosen();	
		$('#ProposalFollowupRulePreparedBy').chosen();
		$('#ProposalFollowupRuleApprovedBy').chosen();
		
		
		$('#ProposalFollowupRuleNumberOfFollowupsRequired').change(function(){
			var	draw = $('#ProposalFollowupRuleNumberOfFollowupsRequired').val();
			var drawHtml = '<br />';
			var x = 1;
			for(i= <?php echo $this->request->data['ProposalFollowupRule']['number_of_followups_required']+1;?> ; i<= draw ; i++){	
					drawHtml  = drawHtml + "<div class='col-md-4'><div class='btn-group'><div class='btn btn-sm btn-default' id= 'days"+i+"' onClick=checks("+i+")><strong>Day "+ i + "</strong></div>";
					drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Email_"+i+"_0' onClick=reset_checks('Email',"+i+")>Email <input style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Email\" id=\"folloupType_" + i + "_0\"></div>";
					drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Call_"+i+"_1' onClick=reset_checks('Call',"+i+")>Call <input  style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Call\" id=\"folloupType_"+ i + "_1\"></div>";
					drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Visit_"+i+"_2' onClick=reset_checks('Visit',"+i+")>Visit <input  style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Visit\" id=\"folloupType_"+ i + "_2\"></div>";
					drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Other_"+i+"_3' onClick=reset_checks('Other',"+i+")>Other <input  style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Other\" id=\"folloupType_"+ i + "_3\"></div>";
					drawHtml  = drawHtml + "<div class='btn btn-sm btn-default disabled' id='Any_"+i+"_4' onClick=reset_checks('Any',"+i+")>Any <input  style=\"display:none\"  type=\"checkbox\" name=\"data[ProposalFollowupRule][folloupType]["+i+"]\" value=\"Any\" id=\"folloupType_"+ i + "_4\"></div></div></div>";
					if(i == 3) drawHtml + "<\/div>";
					if(i % 3 == 0){drawHtml = drawHtml + "<\/div><br /><div class=\"row\">"};
					x++;	
			};
			$('#drawNew').html(drawHtml); 
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
							$('#Visit_'+ctr+'_2').removeClass(' btn-success').addClass(' btn-default');$('#folloupType_'+ctr+'_2').prop('checked',false);	
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
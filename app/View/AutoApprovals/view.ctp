<div id="autoApprovals_ajax_view">
	<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
	<?php echo $this->fetch('script'); ?>
<style type="text/css">
	#AutoApprovalStepViewForm{
		border: 8px solid #ccc;
		background-color: #f8f8f8;
		padding: 20px 0 0 0;
		margin-bottom: 20px;
	}
	#subbut{
		margin-right: 20px
	}
.ui-autocomplete {
	background: #fff;
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
	</style>
	<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
	<div class="autoApprovals form col-md-8">
	<h4><?php echo __('View Auto Approval'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
			<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
			<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
			<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
			</h4>

	<table class="table table-responsive">
			<tr><td width="40%"><?php echo __('Name'); ?></td>
			<td>
				<?php echo h($autoApproval['AutoApproval']['name']); ?>
				&nbsp;
			</td></tr>
			<tr><td><?php echo __('Details'); ?></td>
			<td>
				<?php echo h($autoApproval['AutoApproval']['details']); ?>
				&nbsp;
			</td></tr>
			<tr><td><?php echo __('System Table'); ?></td>
			<td>
				<?php echo h($autoApproval['SystemTable'][$autoApproval['AutoApproval']['system_table']]); ?>
				&nbsp;
			</td></tr>
			<tr><td><?php echo __('Prepared By'); ?></td>

		<td><?php echo h($autoApproval['ApprovedBy']['name']); ?>&nbsp;</td></tr>
			<tr><td><?php echo __('Approved By'); ?></td>

		<td><?php echo h($autoApproval['ApprovedBy']['name']); ?>&nbsp;</td></tr>
			<tr><td><?php echo __('Publish'); ?></td>

			<td>
				<?php if($autoApproval['AutoApproval']['publish'] == 1) { ?>
				<span class="fa fa-check"></span>
				<?php } else { ?>
				<span class="fa fa-ban"></span>
				<?php } ?>&nbsp;</td>
	&nbsp;</td></tr>
			<tr><td><?php echo __('Soft Delete'); ?></td>

			<td>
				<?php if($autoApproval['AutoApproval']['soft_delete'] == 1) { ?>
				<span class="fa fa-check"></span>
				<?php } else { ?>
				<span class="fa fa-ban"></span>
				<?php } ?>&nbsp;</td>
	&nbsp;</td></tr>
	<tr>
		<tr><td colspan="2"><h3>Steps</h3></td></tr>
		<?php 
		for ($i=1; $i <= $maxsteps; $i++) {  ?>
			<tr><td colspan="2">
				<?php echo $this->Html->link('Delete ' . $i . ' Step?',array('action'=>'deletesteps',$autoApproval['AutoApproval']['id'],$i),array('confirm'=>'Are you sure you want to delete all under step '. $i,'class'=>'text-danger'));?>				
			</td></tr>
		<?php } ?>						
	</tr>
	</table>
	<div class="row" id="updatethis">
		<div class="col-md-12">	
			<h3><?php echo __('Steps Added'); ?></h3>
			<div><!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<?php 
						for ($i=1; $i <= $maxsteps; $i++) {  ?>
							<li role="presentation" class=""><a href="#main<?php echo $i?>" aria-controls="main<?php echo $i?>" role="tab" data-toggle="tab">Step : <?php echo $i?></a></li>
						<?php } ?>					
				</ul>
				<div class="tab-content">					
			    	<?php 
			    		// Configure::write('debug',1);
			    		// debug($approvalsteps);
						for ($i=1; $i <= $maxsteps; $i++) {  ?>
							<div role="tabpanel" class="tab-pane" id="main<?php echo $i?>" style="border=5px solid #000">
								<table class="table table-responsive table-bordered">
								<tr>
									<th>Process</th>
									<th>Branch</th>
									<th>Department</th>
									<th>Send To</th>
									<th>Allow Approval?</th>
									<th>Show Details</th>
									<th>Details</th>
									<th width="95">Action</th>
								</tr>
								<?php foreach ($PublishedBranchList as $bkey => $bval) {
									foreach ($PublishedDepartmentList as $dkey => $dvalue) {
										if($approvalsteps[$i][$bval][$dvalue]){ 
											foreach($approvalsteps[$i][$bval][$dvalue] as $step){
												debug($i);
												debug($step);
												echo "<tr>";
												echo "<td>".$step['AutoApprovalStep']['name']."</td>";
												echo "<td>".$PublishedBranchList[$step['AutoApprovalStep']['branch_id']]."</td>";
												echo "<td>".$PublishedDepartmentList[$step['AutoApprovalStep']['department_id']]."</td>";
												// echo "<td>".$PublishedBranchList[$step['AutoApprovalStep']['branch_id']]."</td>";
												echo "<td>".$PublishedUserList[$step['AutoApprovalStep']['user_id']]."</td>";
												echo "<td>".($step['AutoApprovalStep']['allow_approval']?'Yes':'No')."</td>";
												echo "<td>".($step['AutoApprovalStep']['show_details']?'Yes':'No')."</td>";
												echo "<td>".$step['AutoApprovalStep']['details']."</td>";
												echo "<td><div class='btn-group'>";
												echo $this->Html->link('Edit',array('controller'=>'auto_approval_steps','action'=>'edit',$step['AutoApprovalStep']['id']),array('class'=>'btn btn-xs btn-warning'));
												echo $this->Html->link('Delete',array('controller'=>'auto_approval_steps','action'=>'delete_setp','app_id'=> $autoApproval['AutoApproval']['id'], $step['AutoApprovalStep']['id']),array('class'=>'btn btn-xs btn-danger','confirm'=>'Are you sure?'));
												echo "</div></td>";
												echo "</tr>";
											}
										} 
									}
								}?>
								</table>	
							</div>
						<?php } ?>	
								
			    <!-- Tab panes -->			    
			    </div>

			</div>
		</div>
			<?php
				// foreach($autoApproval['AutoApprovalStep'] as $steps):
				// 		echo "<table class='table table-responsive'>";
				// 		echo "<tr><td width='40%'><strong> Step </td><td>".$steps['step_number']."</strong></td><tr/>";
				// 		echo "<tr><td><strong>Number </strong></td><td>".$steps['name']."</td><tr/>";
				// 		echo "<tr><td><strong>Details</strong></td><td>".$steps['details']."</td><tr/>";
				// 		echo "<tr><td><strong>Sent To</strong></td><td>". $PublishedUserList[$steps['user_id']]."</td><tr/>";
				// 		echo "<tr><td><strong>Allow Approval</strong></td><td>";
				// 		if($steps['allow_approval'] == 1) {
				// 			echo "<span class='fa fa-check'></span>";
				// 		} else {
				// 			echo "<span class='glyphicon glyphicon-remove-sign'></span>";
				// 		}
				// 		echo "</td><tr/>";	
				// 		echo "<tr><td><strong>Show Details</strong></td><td>";
				// 		if($steps['show_details'] == 1) {
				// 			echo "<span class='fa fa-check'></span>";
				// 		} else {
				// 			echo "<span class='glyphicon glyphicon-remove-sign'></span>";
				// 		} 
				// 		echo "</td><tr/>";
				// 		echo "</table>";					
				// endforeach;
			?>
	</div>
	<div class="row">
		<div class="col-md-12">
			
			<h3>Add New Steps</h3>
				
	<?php echo $this->Form->create('AutoApprovalStep',array('role'=>'form','class'=>'form','default'=>false)); ?>
				<div class="">
						<fieldset>
							<?php
							$x = 0;
						echo $this->Form->hidden('auto_approval_id',array('default'=>$autoApproval['AutoApproval']['id'])); 
						echo $this->Form->hidden('system_table',array('default'=>$autoApproval['AutoApproval']['system_table'])); 
						echo "<div class='col-md-2 '>".$this->Form->input('step_number',array()) . '</div>'; 
						echo "<div class='col-md-10 ui-widget'>".$this->Form->input('name',array()) . '</div>';
						echo "<div class='col-md-12'>".$this->Form->input('AutoApprovalStep.'.$x.'.branch_id',
							array(
								'name'=>'data[AutoApprovalStep][branch_id][]',
								'options'=>$PublishedBranchList,'multiple')) . '</div>'; 
						echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
						echo "<div class='col-md-7'>".$this->Form->input('allow_approval',array('label'=>'Allow approval at this stage and skip rest of the stages?')) . '</div>';
						echo "<div class='col-md-5'>".$this->Form->input('show_details',array('label'=>'Share details/notes with the user?')) . '</div>';
						echo "<div class='col-md-12'><h4>Link Users</h4></div>";
						$x = $i = 0;?>
						<div id="AutoApprovalDetails_ajax">
				    		<div id="AutoApprovalDetails_ajax<?php echo $i; ?>">
							<?php 
								// foreach ($PublishedDepartmentList as $dkey => $dvalue) {
									echo "<div class=''>";
									echo "<div class='col-md-8'>" . $this->Form->input('AutoApprovalStep.Department.'.$x.'.department_id',array(
										'name'=>'data[AutoApprovalStep][Department]['.$i.'][department_id][]',
										'multiple','onchange'=>'addd('.$i.')','options'=>$PublishedDepartmentList)) . '</div>'; 
									echo "<div class='col-md-4'>".$this->Form->input('AutoApprovalStep.Department.'.$x.'.user_id',array(
										'label'=>'Send record to ',
										'options'=>$PublishedUserList)) . '</div>'; 
									echo "</div>";
									$x++; 
									$i++; 
							?>
								</div>
							</div>
							<?php // } ?>
				<div class="col-md-12"><br /><?php echo $this->Form->hidden('agendaNumber', array('value' => $i)); ?>
            		<?php echo $this->Form->button('+', array('label' => false, 'type' => 'button', 'style'=>'margin-right:10px', 'div' => false, 'class' => 'btn btn-md btn-info pull-right', 'onclick' => 'addAgendaDiv()')); ?>
            	</div>
				</fieldset>
				
				<?php
					echo $this->Form->hidden('sdepartments', array());
					echo $this->Form->hidden('publish', array('default'=>1));
	                echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
	                echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
					echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
				?>
				<br />
				<br />
				<hr />
					<?php echo $this->Form->submit('ADD STEP',array('id'=>'subbut', 'div'=>false,'class'=>'btn btn-primary btn-success pull-right','update'=>'#updatethis','async' => 'false')); ?>
					<?php echo $this->Form->end(); ?>
					<?php echo $this->Js->writeBuffer();?>
					<br />
					<br /><br />
				</div>
			</div>
		</div>		
	</div>		
	<script>
		$( function() {
		    var availableTags = [
				    <?php foreach ($cnames as $key => $value) {
				    	echo '"'.$value.'",';
				    } ?> "other"	   	];
			    $( "#AutoApprovalStepName" ).autocomplete({
			      source: availableTags
			    });
			  } );

	    $.validator.setDefaults({
	    	ignore: null,
	    	errorPlacement: function(error, element) {
	            if (
	                
									$(element).attr('name') == 'data[AutoApprovalStep][auto_approval_id]' ||
									$(element).attr('name') == 'data[AutoApprovalStep][user_id]' ||
									$(element).attr('name') == 'data[AutoApprovalStep][branch_id]' ||
									$(element).attr('name') == 'data[AutoApprovalStep][department_id]' ||
									$(element).attr('name') == 'data[AutoApprovalStep][division_id]')
							{	
	                $(element).next().after(error);
	            } else {
	                $(element).after(error);
	            }
	        },
	        submitHandler: function(form) {
	            $(form).ajaxSubmit({
	                url: "<?php echo Router::url('/', true); ?>auto_approval_steps/add_ajax",
	                type: 'POST',
	                target: '#autoApprovals_ajax_view',
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
	        
	        $('#AutoApprovalStepViewForm').validate({
	            rules: {
										"data[AutoApprovalStep][user_id]": {
	                    	greaterThanZero: true,
										},
										"data[AutoApprovalStep][branch_id]": {
	                    	greaterThanZero: true,
										},
										"data[AutoApprovalStep][department_id]": {
	                    	greaterThanZero: true,
										}
	                
	            }
	        }); 

					$('#AutoApprovalStepUserId').change(function() {
						if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
							$(this).next().next('label').remove();
						}
					});
					$('#AutoApprovalStepBranchId').change(function() {
						if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
							$(this).next().next('label').remove();
						}
					});
					$('#AutoApprovalStepDepartmentId').change(function() {
						if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
							$(this).next().next('label').remove();
						}
					});
					
	    });


	function addAgendaDiv(args) {
    // var i = parseInt($('#AutoApprovalAgendaNumber').val());
	    i = parseInt($('#AutoApprovalStepAgendaNumber').val());
	    // alert($('#AutoApprovalStepAgendaNumber').val());
	    $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_details/" + i + "/" + $("#AutoApprovalStepSdepartments").val(), function(data) {
	        $('#AutoApprovalDetails_ajax').append(data);
	    });
	    i = i+1;
	    $('#AutoApprovalStepAgendaNumber').val(i);
	}
	function removeAgendaDiv(i) {
	    var r = confirm("Are you sure to remove this order details?");
	    if (r == true)
	    {
	        $('#AutoApprovalDetails_ajax' + i).remove();
	    }        
	}
	function addd(i){
		i = parseInt($('#AutoApprovalStepAgendaNumber').val()-1);
		for(x=0;x<=i;x++){
			var output = output + "," + ($("#AutoApprovalStepDepartment"+x+"DepartmentId").val() || []).join(', '); 	
		}
		$("#AutoApprovalStepSdepartments").val(output);
	}
	</script>

	<div class="col-md-4">
		<p><?php echo $this->element('helps'); ?></p>
	</div>
	</div>
	<?php echo $this->Js->get('#list');?>
	<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#autoApprovals_ajax_view')));?>

	<?php echo $this->Js->get('#edit');?>
	<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$autoApproval['AutoApproval']['id'] ,'ajax'),array('async' => true, 'update' => '#autoApprovals_ajax_view')));?>


	<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

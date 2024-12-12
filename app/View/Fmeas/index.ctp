<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fmeas ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'FMEA','modelClass'=>'Fmea','options'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn"),'pluralVar'=>'fmeas'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	
		<div class="table-responsive" style="overflow:auto">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
					<th><?php echo $this->Paginator->sort('name'); ?></th>
					<th><?php echo $this->Paginator->sort('process_id'); ?></th>
					<th><?php echo $this->Paginator->sort('design_id'); ?></th>
					<th><?php echo $this->Paginator->sort('product_id'); ?></th>
					<th><?php echo $this->Paginator->sort('process_step'); ?></th>
					<th><?php echo $this->Paginator->sort('process_sub_step',__('Process Sub-step')); ?></th>
					<th><?php echo $this->Paginator->sort('contribution_of_sub_step',__('Contribution of sub-step')); ?></th>
					<th><?php echo $this->Paginator->sort('potential_failure_mode'); ?></th>
					<th><?php echo $this->Paginator->sort('potential_failure_effects'); ?></th>
					<th><?php echo $this->Paginator->sort('fmea_severity_type_id',__('SEV')); ?></th>
					<th><?php echo $this->Paginator->sort('potential_causes'); ?></th>
					<th><?php echo $this->Paginator->sort('fmea_occurence_id',__('OCC')); ?></th>
					<th><?php echo $this->Paginator->sort('current_controls'); ?></th>
					<th><?php echo $this->Paginator->sort('fmea_detection_id',__('DET')); ?></th>
					<th><?php echo $this->Paginator->sort('rpn',__('RPN')); ?></th>				
					<th><?php echo __('Action Recommened');?></th>
					<th><?php echo __('Resp/Date');?></th>
					<th><?php echo __('Action Taken');?></th>
					<th><?php echo $this->Paginator->sort('new_fmea_severity_type_id',__('SEV')); ?></th>
					<th><?php echo $this->Paginator->sort('new_fmea_occurence_id',__('OCC')); ?></th>
					<th><?php echo $this->Paginator->sort('new_fmea_detection_id',__('DET')); ?></th>
					<th><?php echo $this->Paginator->sort('final_rpn',__('RPN')); ?></th>				
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('current_status'); ?></th>
					<th><?php echo __('Action'); ?></th>					
					<th><?php echo $this->Paginator->sort('publish'); ?></th>
				</tr>
				<tr class="warning text-primary" style="font-size:8px">
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>What is the process step</td>
					<td>Can we define sub-steps</td>
					<td>What is the function of this step?</td>
					<td>What can go wrong in the process step ?</td>
					<td>What is the impact on the customer requirements or business objectives ?</td>
					<td>How Severe is the effect to the customer?</td>
					<td>What causes the step to go wrong ? List more than one</td>
					<td>How often does cause or FM occur?</td>
					<td>What are the existing controls and procedures (inspection and test) that prevent either the cause or the Failure Mode?  Should include an SOP number.</td>
					<td>How well can you detect cause or FM?</td>
					<td><?php echo $this->Paginator->sort('rpn',__('RPN')); ?></td>
					<td>What are the actions for reducing the occurrence of the Cause, or improving detection?  Should have actions only on high RPN's or easy fixes.</td>
					<td>Whose Responsible for the recommended action? When complete?</td>
					<td>What are the completed actions taken with the recalculated RPN?  Be sure to include completion month/year</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>				
					<td></td>		
					<td></td>		
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php if($fmeas){ ?>
<?php foreach ($fmeas as $fmea): ?>
	<?php
		if($fmea['Fmea']['current_status'] == 0)$class = 'danger';
		else $class = 'success';
	?>	
	<tr class="<?php echo $class; ?>">	
	<td class=" actions">			
		<?php echo $this->element('actions', array('created' => $fmea['Fmea']['created_by'], 'postVal' => $fmea['Fmea']['id'], 'softDelete' => $fmea['Fmea']['soft_delete'])); ?>	</td>		
		<td><?php echo h($fmea['Fmea']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($fmea['Process']['title'], array('controller' => 'processes', 'action' => 'view', $fmea['Process']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fmea['Deaign']['name'], array('controller' => 'designs', 'action' => 'view', $fmea['Design']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($fmea['Product']['name'], array('controller' => 'products', 'action' => 'view', $fmea['Product']['id'])); ?>
		</td>
		<td><?php echo h($fmea['Fmea']['process_step']); ?>&nbsp;</td>
		<td><?php echo h($fmea['Fmea']['process_sub_step']); ?>&nbsp;</td>
		<td><?php echo h($fmea['Fmea']['contribution_of_sub_step']); ?>&nbsp;</td>
		<td><?php echo h($fmea['Fmea']['potential_failure_mode']); ?>&nbsp;</td>
		<td><?php echo h($fmea['Fmea']['potential_failure_effects']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($fmea['FmeaSeverityType']['effect'], array('controller' => 'fmea_severity_types', 'action' => 'view', $fmea['FmeaSeverityType']['id'])); ?>
		</td>
		<td><?php echo h($fmea['Fmea']['potential_causes']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($fmea['FmeaOccurence']['probability_of_failure'], array('controller' => 'fmea_occurences', 'action' => 'view', $fmea['FmeaOccurence']['id'])); ?>
		</td>
		<td><?php echo h($fmea['Fmea']['current_controls']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($fmea['FmeaDetection']['detection'], array('controller' => 'fmea_detections', 'action' => 'view', $fmea['FmeaDetection']['id'])); ?>
		</td>
		<td><?php echo h($fmea['Fmea']['rpn']); ?>&nbsp;</td>

		<td><?php echo $fmea['FmeaAction'][0]['actions_recommended'];?></td>
		<td><?php echo $PublishedEmployeeList[$fmea['FmeaAction'][0]['employee_id']];?>/<?php echo $fmea['FmeaAction'][0]['action_taken_date']?></td>
		<td><?php echo $fmea['FmeaAction'][0]['action_taken'];?></td>
		<td>			
			<?php echo $fmeaSeverityTypes[$fmea['Fmea']['new_fmea_severity_type_id']];?>
		</td>
		<td>
			<?php echo $fmeaOccurences[$fmea['Fmea']['new_fmea_occurence_id']];?>
		</td>
		<td>
			<?php echo $fmeaDetections[$fmea['Fmea']['new_fmea_detection_id']];?>
		</td>
		<td><?php echo $fmea['Fmea']['final_rpn'];?></td>

		<td><?php echo h($PublishedEmployeeList[$fmea['Fmea']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fmea['Fmea']['approved_by']]); ?>&nbsp;</td>
		<td>
			<?php
				if($fmea['Fmea']['current_status'] == 0)echo "Open";
				else echo "Close";
			?>
		</td>
		<td>
			
			<?php 
			if(!$fmea['FmeaAction']){
				echo $this->Html->link('Add Actions',array('controller'=>'fmea_actions','action'=>'lists','fmea_id'=>$fmea['Fmea']['id']),array('class'=>'btn btn-xs btn-success'));
			}else{
				echo $this->Html->link('Edit Actions',array('controller'=>'fmea_actions','action'=>'edit', $fmea['FmeaAction'][0]['id'],'fmea_id'=>$fmea['Fmea']['id']),array('class'=>'btn btn-xs btn-success'));
			}
			?>			
		</td>
		<td width="60">
			<?php if($fmea['Fmea']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=90>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#main',
			'evalScripts' => true,
			'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
			));
			
			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
			?>			</p>
			<ul class="pagination">
			<?php
		echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
		echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
		echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
	?>
			</ul>
		</div>
	</div>
	</div>	

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

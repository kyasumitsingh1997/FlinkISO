<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
<div class="capaInvestigations ">
	<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Capa Investigations','modelClass'=>'CapaInvestigation','options'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status"),'pluralVar'=>'capaInvestigations'))); ?>

	<script type="text/javascript">
	$(document).ready(function(){
		$('table th a, .pag_list li span a').on('click', function() {
			var url = $(this).attr("href");
			$('#main').load(url);
			return false;
		});
	});
	</script>	
	<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
		<table cellpadding="0" cellspacing="0" class="table table-bordered">
			<tr>
				<th ><input type="checkbox" id="selectAll"></th>

				<th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
				<th><?php echo $this->Paginator->sort('details'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('target_date'); ?></th>
				<th><?php echo $this->Paginator->sort('proposed_action'); ?></th>
				<th><?php echo $this->Paginator->sort('completed_on_date'); ?></th>
				
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
			</tr>
			<?php if($capaInvestigations){ ?>
			<?php foreach ($capaInvestigations as $capaInvestigation): ?>

			<?php if($capaInvestigation['CapaInvestigation']['current_status'] == 0){ ?>
			<tr class="text-danger on_page_src">
				<?php } else{ ?>
				<tr class="on_page_src"> <?php } ?>
					<td class=" actions">	
						<?php echo $this->element('actions', array('created' => $capaInvestigation['CapaInvestigation']['created_by'], 'postVal' => $capaInvestigation['CapaInvestigation']['id'], 'softDelete' => $capaInvestigation['CapaInvestigation']['soft_delete'])); ?>	</td>		
						<td>
						<?php echo $this->Html->link($capaInvestigation['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaInvestigation['CorrectivePreventiveAction']['id'])); ?>
				</td>
				<td><?php echo h($capaInvestigation['CapaInvestigation']['details']); ?>&nbsp;</td>
				<td>
					<?php echo $this->Html->link($capaInvestigation['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaInvestigation['Employee']['id'])); ?>
				</td>
				<td><?php echo h($capaInvestigation['CapaInvestigation']['target_date']); ?>&nbsp;</td>
				<td><?php echo h($capaInvestigation['CapaInvestigation']['proposed_action']); ?>&nbsp;</td>
				<td>
					<?php if($capaInvestigation['CapaInvestigation']['current_status'] == 1)echo h($capaInvestigation['CapaInvestigation']['completed_on_date']); ?>&nbsp;
					<?php if($capaInvestigation['CapaInvestigation']['current_status'] == 0 && $capaInvestigation['CapaInvestigation']['target_date'] > $capaInvestigation['CapaInvestigation']['completed_on_date']){
						echo "<span class='badge label-danger'>Delayed</span>";
					}else{
						echo "<span class='badge label-success'>In Time</span>";
					}?>
				</td>

				<td><?php echo h($PublishedEmployeeList[$capaInvestigation['CapaInvestigation']['prepared_by']]); ?>&nbsp;</td>
				<td><?php echo h($PublishedEmployeeList[$capaInvestigation['CapaInvestigation']['approved_by']]); ?>&nbsp;</td>

				<td width="60">
					<?php if($capaInvestigation['CapaInvestigation']['publish'] == 1) { ?>
					<span class="fa fa-check"></span>
					<?php } else { ?>
					<span class="fa fa-ban"></span>
					<?php } ?>&nbsp;</td>
				</tr>
			<?php endforeach; ?>
			<?php }else{ ?>
			<tr><td colspan=72>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="capaRevisedDates ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Capa Revised Dates','modelClass'=>'CapaRevisedDate','options'=>array("sr_no"=>"Sr No","target_date"=>"Target Date","new_revised_date_requested"=>"New Revised Date Requested","reason"=>"Reason","revised_date"=>"Revised Date"),'pluralVar'=>'capaRevisedDates'))); ?>

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
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('target_date'); ?></th>
				<th><?php echo $this->Paginator->sort('new_revised_date_requested'); ?></th>
				<th><?php echo $this->Paginator->sort('reason'); ?></th>
				<th><?php echo $this->Paginator->sort('revised_date'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($capaRevisedDates){ ?>
<?php foreach ($capaRevisedDates as $capaRevisedDate): ?>
	    <?php if(!$capaRevisedDate['CapaRevisedDate']['current_status']){ ?>
                <tr class="alert-danger on_page_src">
                    <?php } else{ ?>
                <tr class="on_page_src"> <?php } ?>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $capaRevisedDate['CapaRevisedDate']['created_by'], 'postVal' => $capaRevisedDate['CapaRevisedDate']['id'], 'softDelete' => $capaRevisedDate['CapaRevisedDate']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($capaRevisedDate['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRevisedDate['CorrectivePreventiveAction']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($capaRevisedDate['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaRevisedDate['Employee']['id'])); ?>
		</td>
		<td><?php echo h($capaRevisedDate['CapaRevisedDate']['target_date']); ?>&nbsp;</td>
		<td><?php echo h($capaRevisedDate['CapaRevisedDate']['new_revised_date_requested']); ?>&nbsp;</td>
		<td><?php echo h($capaRevisedDate['CapaRevisedDate']['reason']); ?>&nbsp;</td>
		<td><?php echo h($capaRevisedDate['CapaRevisedDate']['revised_date']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$capaRevisedDate['CapaRevisedDate']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$capaRevisedDate['CapaRevisedDate']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($capaRevisedDate['CapaRevisedDate']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=69>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","target_date"=>"Target Date","new_revised_date_requested"=>"New Revised Date Requested","reason"=>"Reason","revised_date"=>"Revised Date"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","target_date"=>"Target Date","new_revised_date_requested"=>"New Revised Date Requested","reason"=>"Reason","revised_date"=>"Revised Date"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

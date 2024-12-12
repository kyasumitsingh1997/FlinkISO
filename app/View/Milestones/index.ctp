<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="milestones ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Milestones','modelClass'=>'Milestone','options'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","challenges"=>"Challenges","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","users"=>"Users"),'pluralVar'=>'milestones'))); ?>

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
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('details'); ?></th>
				<th><?php echo $this->Paginator->sort('challenges'); ?></th>-->
				<th><?php echo $this->Paginator->sort('estimated_cost'); ?></th> 
				<th><?php echo $this->Paginator->sort('start_date'); ?></th>
				<th><?php echo $this->Paginator->sort('end_date'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('users'); ?></th>
				<th><?php echo $this->Paginator->sort('user_session_id'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($milestones){ ?>
<?php foreach ($milestones as $milestone): ?>
	<tr>
	<td class=" actions">	
		<?php echo $this->element('actions', array('created' => $milestone['Milestone']['created_by'], 'postVal' => $milestone['Milestone']['id'], 'softDelete' => $milestone['Milestone']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($milestone['Project']['title'], array('controller' => 'projects', 'action' => 'view', $milestone['Project']['id'])); ?>
		</td>
		<td><?php echo h($milestone['Milestone']['title']); ?>&nbsp;</td>
		<!-- <td><?php echo h($milestone['Milestone']['details']); ?>&nbsp;</td>
		<td><?php echo h($milestone['Milestone']['challenges']); ?>&nbsp;</td>-->
		<td><?php echo h($milestone['Milestone']['estimated_cost']); ?>&nbsp;</td> 
		<td><?php echo h($milestone['Milestone']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($milestone['Milestone']['end_date']); ?>&nbsp;</td>
		<td><?php if($milestone['Milestone']['current_status'] == 0) echo 'Open'; ?>
			<?php if($milestone['Milestone']['current_status'] == 1) echo 'Close'; ?>			
		&nbsp;</td>
		<!-- <td>
			<?php echo $this->Html->link($milestone['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $milestone['Branch']['id'])); ?>
		</td>
		<td><?php echo h($milestone['Milestone']['users']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($milestone['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $milestone['UserSession']['id'])); ?>
		</td> -->
		<td><?php echo h($PublishedEmployeeList[$milestone['Milestone']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$milestone['Milestone']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($milestone['Milestone']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=81>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>
<?php if($this->request->params['named']['project_id']){
	echo $this->element('projecttimeline',array('project_details'=>$project_details));
}?>			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","challenges"=>"Challenges","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","users"=>"Users"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","challenges"=>"Challenges","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","users"=>"Users"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

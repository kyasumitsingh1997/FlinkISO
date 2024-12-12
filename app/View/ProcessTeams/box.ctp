
<script>
	function getVals(){
		
	var checkedValue = null;
	$("#recs_selected").val(null);
	var inputElements = document.getElementsByTagName('input');
	
	for(var i=0; inputElements[i]; ++i){
		
	      if(inputElements[i].className==="rec_ids" && 
		 inputElements[i].checked){
		   $("#recs_selected").val($("#recs_selected").val() + '+' + inputElements[i].value);
		   
	      }
	}
	}
</script><?php echo $this->Session->flash();?>	
	<div class="processTeams ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Process Teams','modelClass'=>'ProcessTeam','options'=>array("sr_no"=>"Sr No","team"=>"Team","process_type"=>"Process Type","target"=>"Target","measurement_details"=>"Measurement Details","start_date"=>"Start Date","end_date"=>"End Date","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'processTeams'))); ?>
	
		
<script type="text/javascript">
$(document).ready(function(){
$('dl dt a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>
		<div class="container row  row table-responsive">

			<?php foreach ($processTeams as $processTeam): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $processTeam['ProcessTeam']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $processTeam['ProcessTeam']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $processTeam['ProcessTeam']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $processTeam['ProcessTeam']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $processTeam['ProcessTeam']['id']),array('class'=>''), __('Are you sure ?', $processTeam['ProcessTeam']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($processTeam['ProcessTeam']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('process_id') ."</dt><dd>:". $this->Html->link($processTeam['Process']['title'], array('controller' => 'processes', 'action' => 'view', $processTeam['Process']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('objective_id') ."</dt><dd>:". $this->Html->link($processTeam['Objective']['title'], array('controller' => 'objectives', 'action' => 'view', $processTeam['Objective']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('team') ."</dt><dd>: ". h($processTeam['ProcessTeam']['team']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('process_type') ."</dt><dd>: ". h($processTeam['ProcessTeam']['process_type']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('branch_id') ."</dt><dd>:". $this->Html->link($processTeam['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $processTeam['Branch']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('department_id') ."</dt><dd>:". $this->Html->link($processTeam['Department']['name'], array('controller' => 'departments', 'action' => 'view', $processTeam['Department']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('target') ."</dt><dd>: ". h($processTeam['ProcessTeam']['target']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('measurement_details') ."</dt><dd>: ". h($processTeam['ProcessTeam']['measurement_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('start_date') ."</dt><dd>: ". h($processTeam['ProcessTeam']['start_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('end_date') ."</dt><dd>: ". h($processTeam['ProcessTeam']['end_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('system_table') ."</dt><dd>: ". h($processTeam['ProcessTeam']['system_table']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($processTeam['ProcessTeam']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($processTeam['ProcessTeam']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($processTeam['ProcessTeam']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($processTeam['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $processTeam['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($processTeam['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $processTeam['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($processTeam['Company']['name'], array('controller' => 'companies', 'action' => 'view', $processTeam['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$processTeam['ProcessTeam']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","team"=>"Team","process_type"=>"Process Type","target"=>"Target","measurement_details"=>"Measurement Details","start_date"=>"Start Date","end_date"=>"End Date","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","team"=>"Team","process_type"=>"Process Type","target"=>"Target","measurement_details"=>"Measurement Details","start_date"=>"Start Date","end_date"=>"End Date","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
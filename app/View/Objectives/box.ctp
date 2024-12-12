
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
	<div class="objectives ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Objectives','modelClass'=>'Objective','options'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","objective"=>"Objective","desired_output"=>"Desired Output","team"=>"Team","requirments"=>"Requirments","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'objectives'))); ?>
	
		
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

			<?php foreach ($objectives as $objective): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $objective['Objective']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $objective['Objective']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $objective['Objective']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $objective['Objective']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $objective['Objective']['id']),array('class'=>''), __('Are you sure ?', $objective['Objective']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($objective['Objective']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($objective['Objective']['title']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('clauses') ."</dt><dd>: ". h($objective['Objective']['clauses']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('objective') ."</dt><dd>: ". h($objective['Objective']['objective']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('desired_output') ."</dt><dd>: ". h($objective['Objective']['desired_output']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('owner_id') ."</dt><dd>:". $this->Html->link($objective['Owner']['name'], array('controller' => 'users', 'action' => 'view', $objective['Owner']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('team') ."</dt><dd>: ". h($objective['Objective']['team']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('requirments') ."</dt><dd>: ". h($objective['Objective']['requirments']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('system_table') ."</dt><dd>: ". h($objective['Objective']['system_table']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('input_process_id') ."</dt><dd>:". $this->Html->link($objective['InputProcess']['id'], array('controller' => 'input_processes', 'action' => 'view', $objective['InputProcess']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('output_process_id') ."</dt><dd>:". $this->Html->link($objective['OutputProcess']['id'], array('controller' => 'output_processes', 'action' => 'view', $objective['OutputProcess']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($objective['Objective']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($objective['Objective']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($objective['Objective']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($objective['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $objective['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($objective['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $objective['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($objective['Company']['name'], array('controller' => 'companies', 'action' => 'view', $objective['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$objective['Objective']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","objective"=>"Objective","desired_output"=>"Desired Output","team"=>"Team","requirments"=>"Requirments","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","objective"=>"Objective","desired_output"=>"Desired Output","team"=>"Team","requirments"=>"Requirments","system_table"=>"System Table","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
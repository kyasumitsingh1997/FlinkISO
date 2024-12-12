
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
	<div class="capaInvestigations ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Capa Investigations','modelClass'=>'CapaInvestigation','options'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'pluralVar'=>'capaInvestigations'))); ?>
	
		
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

			<?php foreach ($capaInvestigations as $capaInvestigation): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $capaInvestigation['CapaInvestigation']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $capaInvestigation['CapaInvestigation']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $capaInvestigation['CapaInvestigation']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $capaInvestigation['CapaInvestigation']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $capaInvestigation['CapaInvestigation']['id']),array('class'=>''), __('Are you sure ?', $capaInvestigation['CapaInvestigation']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($capaInvestigation['CapaInvestigation']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('corrective_preventive_action_id') ."</dt><dd>:". $this->Html->link($capaInvestigation['CorrectivePreventiveActionId']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaInvestigation['CorrectivePreventiveActionId']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('details') ."</dt><dd>: ". h($capaInvestigation['CapaInvestigation']['details']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($capaInvestigation['EmployeeId']['name'], array('controller' => 'employees', 'action' => 'view', $capaInvestigation['EmployeeId']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('target_date') ."</dt><dd>: ". h($capaInvestigation['CapaInvestigation']['target_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('proposed_action') ."</dt><dd>: ". h($capaInvestigation['CapaInvestigation']['proposed_action']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('completed_on_date') ."</dt><dd>: ". h($capaInvestigation['CapaInvestigation']['completed_on_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('current_status') ."</dt><dd>: ". h($capaInvestigation['CapaInvestigation']['current_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($capaInvestigation['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $capaInvestigation['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($capaInvestigation['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $capaInvestigation['PreparedBy']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($capaInvestigation['CapaInvestigation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($capaInvestigation['CapaInvestigation']['record_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>:". $this->Html->link($capaInvestigation['StatusUserId']['name'], array('controller' => 'users', 'action' => 'view', $capaInvestigation['StatusUserId']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($capaInvestigation['Company']['name'], array('controller' => 'companies', 'action' => 'view', $capaInvestigation['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$capaInvestigation['CapaInvestigation']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
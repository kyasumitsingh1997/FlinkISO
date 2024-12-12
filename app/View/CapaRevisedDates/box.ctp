
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
	<div class="capaRevisedDates ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Capa Revised Dates','modelClass'=>'CapaRevisedDate','options'=>array("sr_no"=>"Sr No","target_date"=>"Target Date","new_revised_date_requested"=>"New Revised Date Requested","reason"=>"Reason","revised_date"=>"Revised Date","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'pluralVar'=>'capaRevisedDates'))); ?>
	
		
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

			<?php foreach ($capaRevisedDates as $capaRevisedDate): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $capaRevisedDate['CapaRevisedDate']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $capaRevisedDate['CapaRevisedDate']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $capaRevisedDate['CapaRevisedDate']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $capaRevisedDate['CapaRevisedDate']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $capaRevisedDate['CapaRevisedDate']['id']),array('class'=>''), __('Are you sure ?', $capaRevisedDate['CapaRevisedDate']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($capaRevisedDate['CapaRevisedDate']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('corrective_preventive_action_id') ."</dt><dd>:". $this->Html->link($capaRevisedDate['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRevisedDate['CorrectivePreventiveAction']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($capaRevisedDate['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaRevisedDate['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('target_date') ."</dt><dd>: ". h($capaRevisedDate['CapaRevisedDate']['target_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('new_revised_date_requested') ."</dt><dd>: ". h($capaRevisedDate['CapaRevisedDate']['new_revised_date_requested']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('reason') ."</dt><dd>: ". h($capaRevisedDate['CapaRevisedDate']['reason']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('revised_date') ."</dt><dd>: ". h($capaRevisedDate['CapaRevisedDate']['revised_date']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($capaRevisedDate['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $capaRevisedDate['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($capaRevisedDate['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $capaRevisedDate['PreparedBy']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($capaRevisedDate['CapaRevisedDate']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($capaRevisedDate['CapaRevisedDate']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($capaRevisedDate['CapaRevisedDate']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($capaRevisedDate['Company']['name'], array('controller' => 'companies', 'action' => 'view', $capaRevisedDate['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$capaRevisedDate['CapaRevisedDate']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","target_date"=>"Target Date","new_revised_date_requested"=>"New Revised Date Requested","reason"=>"Reason","revised_date"=>"Revised Date","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","target_date"=>"Target Date","new_revised_date_requested"=>"New Revised Date Requested","reason"=>"Reason","revised_date"=>"Revised Date","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
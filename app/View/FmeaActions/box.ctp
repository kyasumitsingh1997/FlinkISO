
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
	<div class="fmeaActions ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Fmea Actions','modelClass'=>'FmeaAction','options'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'fmeaActions'))); ?>
	
		
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

			<?php foreach ($fmeaActions as $fmeaAction): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $fmeaAction['FmeaAction']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $fmeaAction['FmeaAction']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $fmeaAction['FmeaAction']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $fmeaAction['FmeaAction']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $fmeaAction['FmeaAction']['id']),array('class'=>''), __('Are you sure ?', $fmeaAction['FmeaAction']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($fmeaAction['FmeaAction']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('fmea_id') ."</dt><dd>:". $this->Html->link($fmeaAction['Fmea']['id'], array('controller' => 'fmeas', 'action' => 'view', $fmeaAction['Fmea']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($fmeaAction['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $fmeaAction['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('actions_recommended') ."</dt><dd>: ". h($fmeaAction['FmeaAction']['actions_recommended']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('target_date') ."</dt><dd>: ". h($fmeaAction['FmeaAction']['target_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('action_taken') ."</dt><dd>: ". h($fmeaAction['FmeaAction']['action_taken']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('action_taken_date') ."</dt><dd>: ". h($fmeaAction['FmeaAction']['action_taken_date']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('fmea_severity_type_id') ."</dt><dd>:". $this->Html->link($fmeaAction['FmeaSeverityType']['id'], array('controller' => 'fmea_severity_types', 'action' => 'view', $fmeaAction['FmeaSeverityType']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('fmea_occurence_id') ."</dt><dd>:". $this->Html->link($fmeaAction['FmeaOccurence']['id'], array('controller' => 'fmea_occurences', 'action' => 'view', $fmeaAction['FmeaOccurence']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('fmea_detection_id') ."</dt><dd>:". $this->Html->link($fmeaAction['FmeaDetection']['id'], array('controller' => 'fmea_detections', 'action' => 'view', $fmeaAction['FmeaDetection']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('rpn') ."</dt><dd>: ". h($fmeaAction['FmeaAction']['rpn']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($fmeaAction['FmeaAction']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($fmeaAction['FmeaAction']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($fmeaAction['FmeaAction']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($fmeaAction['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $fmeaAction['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($fmeaAction['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $fmeaAction['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($fmeaAction['Company']['name'], array('controller' => 'companies', 'action' => 'view', $fmeaAction['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$fmeaAction['FmeaAction']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
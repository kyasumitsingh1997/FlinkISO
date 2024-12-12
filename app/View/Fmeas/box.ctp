
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
	<div class="fmeas ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Fmeas','modelClass'=>'Fmea','options'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'fmeas'))); ?>
	
		
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

			<?php foreach ($fmeas as $fmea): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $fmea['Fmea']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $fmea['Fmea']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $fmea['Fmea']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $fmea['Fmea']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $fmea['Fmea']['id']),array('class'=>''), __('Are you sure ?', $fmea['Fmea']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($fmea['Fmea']['sr_no']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('process_id') ."</dt><dd>:". $this->Html->link($fmea['Process']['title'], array('controller' => 'processes', 'action' => 'view', $fmea['Process']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('product_id') ."</dt><dd>:". $this->Html->link($fmea['Product']['name'], array('controller' => 'products', 'action' => 'view', $fmea['Product']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('process_step') ."</dt><dd>: ". h($fmea['Fmea']['process_step']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('process_sub_step') ."</dt><dd>: ". h($fmea['Fmea']['process_sub_step']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('contribution_of_sub_step') ."</dt><dd>: ". h($fmea['Fmea']['contribution_of_sub_step']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('potential_failure_mode') ."</dt><dd>: ". h($fmea['Fmea']['potential_failure_mode']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('potential_failure_effects') ."</dt><dd>: ". h($fmea['Fmea']['potential_failure_effects']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('fmea_severity_type_id') ."</dt><dd>:". $this->Html->link($fmea['FmeaSeverityType']['id'], array('controller' => 'fmea_severity_types', 'action' => 'view', $fmea['FmeaSeverityType']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('potential_causes') ."</dt><dd>: ". h($fmea['Fmea']['potential_causes']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('fmea_occurence_id') ."</dt><dd>:". $this->Html->link($fmea['FmeaOccurence']['id'], array('controller' => 'fmea_occurences', 'action' => 'view', $fmea['FmeaOccurence']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('current_controls') ."</dt><dd>: ". h($fmea['Fmea']['current_controls']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('fmea_detection_id') ."</dt><dd>:". $this->Html->link($fmea['FmeaDetection']['id'], array('controller' => 'fmea_detections', 'action' => 'view', $fmea['FmeaDetection']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('rpn') ."</dt><dd>: ". h($fmea['Fmea']['rpn']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($fmea['Fmea']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($fmea['Fmea']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($fmea['Fmea']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($fmea['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $fmea['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($fmea['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $fmea['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($fmea['Company']['name'], array('controller' => 'companies', 'action' => 'view', $fmea['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$fmea['Fmea']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","process_step"=>"Process Step","process_sub_step"=>"Process Sub Step","contribution_of_sub_step"=>"Contribution Of Sub Step","potential_failure_mode"=>"Potential Failure Mode","potential_failure_effects"=>"Potential Failure Effects","potential_causes"=>"Potential Causes","current_controls"=>"Current Controls","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
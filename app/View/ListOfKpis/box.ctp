
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
	<div class="listOfKpis ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'List Of Kpis','modelClass'=>'ListOfKpi','options'=>array("sr_no"=>"Sr No","title"=>"Title","kpi_details"=>"Kpi Details","other_details"=>"Other Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'listOfKpis'))); ?>
	
		
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

			<?php foreach ($listOfKpis as $listOfKpi): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $listOfKpi['ListOfKpi']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $listOfKpi['ListOfKpi']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $listOfKpi['ListOfKpi']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $listOfKpi['ListOfKpi']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $listOfKpi['ListOfKpi']['id']),array('class'=>''), __('Are you sure ?', $listOfKpi['ListOfKpi']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($listOfKpi['ListOfKpi']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($listOfKpi['ListOfKpi']['title']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('kpi_details') ."</dt><dd>: ". h($listOfKpi['ListOfKpi']['kpi_details']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('branch_id') ."</dt><dd>:". $this->Html->link($listOfKpi['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $listOfKpi['Branch']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('department_id') ."</dt><dd>:". $this->Html->link($listOfKpi['Department']['name'], array('controller' => 'departments', 'action' => 'view', $listOfKpi['Department']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($listOfKpi['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $listOfKpi['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('other_details') ."</dt><dd>: ". h($listOfKpi['ListOfKpi']['other_details']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($listOfKpi['ListOfKpi']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($listOfKpi['ListOfKpi']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($listOfKpi['ListOfKpi']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($listOfKpi['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $listOfKpi['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($listOfKpi['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $listOfKpi['PreparedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$listOfKpi['ListOfKpi']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","kpi_details"=>"Kpi Details","other_details"=>"Other Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","kpi_details"=>"Kpi Details","other_details"=>"Other Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

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
	<div class="envIdentifications ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Env Identifications','modelClass'=>'EnvIdentification','options'=>array("sr_no"=>"Sr No","title"=>"Title","aspect_number"=>"Aspect Number","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'envIdentifications'))); ?>
	
		
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

			<?php foreach ($envIdentifications as $envIdentification): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $envIdentification['EnvIdentification']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $envIdentification['EnvIdentification']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $envIdentification['EnvIdentification']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $envIdentification['EnvIdentification']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $envIdentification['EnvIdentification']['id']),array('class'=>''), __('Are you sure ?', $envIdentification['EnvIdentification']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($envIdentification['EnvIdentification']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($envIdentification['EnvIdentification']['title']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('env_activity_id') ."</dt><dd>:". $this->Html->link($envIdentification['EnvActivity']['title'], array('controller' => 'env_activities', 'action' => 'view', $envIdentification['EnvActivity']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('aspect_number') ."</dt><dd>: ". h($envIdentification['EnvIdentification']['aspect_number']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('aspect_details') ."</dt><dd>: ". h($envIdentification['EnvIdentification']['aspect_details']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('env_impact_id') ."</dt><dd>:". $this->Html->link($envIdentification['EnvImpact']['name'], array('controller' => 'env_impacts', 'action' => 'view', $envIdentification['EnvImpact']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('impact_details') ."</dt><dd>: ". h($envIdentification['EnvIdentification']['impact_details']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($envIdentification['EnvIdentification']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($envIdentification['EnvIdentification']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($envIdentification['EnvIdentification']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($envIdentification['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $envIdentification['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($envIdentification['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $envIdentification['PreparedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$envIdentification['EnvIdentification']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","aspect_number"=>"Aspect Number","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","aspect_number"=>"Aspect Number","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
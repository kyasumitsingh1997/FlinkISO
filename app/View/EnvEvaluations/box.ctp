
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
	<div class="envEvaluations ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Env Evaluations','modelClass'=>'EnvEvaluation','options'=>array("sr_no"=>"Sr No","title"=>"Title","score"=>"Score","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'envEvaluations'))); ?>
	
		
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

			<?php foreach ($envEvaluations as $envEvaluation): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $envEvaluation['EnvEvaluation']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $envEvaluation['EnvEvaluation']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $envEvaluation['EnvEvaluation']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $envEvaluation['EnvEvaluation']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $envEvaluation['EnvEvaluation']['id']),array('class'=>''), __('Are you sure ?', $envEvaluation['EnvEvaluation']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($envEvaluation['EnvEvaluation']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($envEvaluation['EnvEvaluation']['title']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('env_activity_id') ."</dt><dd>:". $this->Html->link($envEvaluation['EnvActivity']['title'], array('controller' => 'env_activities', 'action' => 'view', $envEvaluation['EnvActivity']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('env_indentification_id') ."</dt><dd>:". $this->Html->link($envEvaluation['EnvIndentification']['title'], array('controller' => 'env_indentifications', 'action' => 'view', $envEvaluation['EnvIndentification']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('score') ."</dt><dd>: ". h($envEvaluation['EnvEvaluation']['score']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('aspect_details') ."</dt><dd>: ". h($envEvaluation['EnvEvaluation']['aspect_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('impact_details') ."</dt><dd>: ". h($envEvaluation['EnvEvaluation']['impact_details']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($envEvaluation['EnvEvaluation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($envEvaluation['EnvEvaluation']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($envEvaluation['EnvEvaluation']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($envEvaluation['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $envEvaluation['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($envEvaluation['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $envEvaluation['PreparedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$envEvaluation['EnvEvaluation']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","score"=>"Score","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","score"=>"Score","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

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
	<div class="envEvaluationScores ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Env Evaluation Scores','modelClass'=>'EnvEvaluationScore','options'=>array("sr_no"=>"Sr No","title"=>"Title","score"=>"Score","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'envEvaluationScores'))); ?>
	
		
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

			<?php foreach ($envEvaluationScores as $envEvaluationScore): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $envEvaluationScore['EnvEvaluationScore']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $envEvaluationScore['EnvEvaluationScore']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $envEvaluationScore['EnvEvaluationScore']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $envEvaluationScore['EnvEvaluationScore']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $envEvaluationScore['EnvEvaluationScore']['id']),array('class'=>''), __('Are you sure ?', $envEvaluationScore['EnvEvaluationScore']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['title']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('env_activity_id') ."</dt><dd>:". $this->Html->link($envEvaluationScore['EnvActivity']['title'], array('controller' => 'env_activities', 'action' => 'view', $envEvaluationScore['EnvActivity']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('env_indentification_id') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['env_indentification_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('env_evaluation_id') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['env_evaluation_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('aspect_id') ."</dt><dd>:". $this->Html->link($envEvaluationScore['Aspect']['name'], array('controller' => 'aspects', 'action' => 'view', $envEvaluationScore['Aspect']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('score') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['score']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('aspect_details') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['aspect_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('impact_details') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['impact_details']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($envEvaluationScore['EnvEvaluationScore']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($envEvaluationScore['EnvEvaluationScore']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($envEvaluationScore['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $envEvaluationScore['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($envEvaluationScore['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $envEvaluationScore['PreparedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$envEvaluationScore['EnvEvaluationScore']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
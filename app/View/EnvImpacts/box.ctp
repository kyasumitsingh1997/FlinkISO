
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
	<div class="envImpacts ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Env Impacts','modelClass'=>'EnvImpact','options'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'envImpacts'))); ?>
	
		
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

			<?php foreach ($envImpacts as $envImpact): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $envImpact['EnvImpact']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $envImpact['EnvImpact']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $envImpact['EnvImpact']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $envImpact['EnvImpact']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $envImpact['EnvImpact']['id']),array('class'=>''), __('Are you sure ?', $envImpact['EnvImpact']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($envImpact['EnvImpact']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($envImpact['EnvImpact']['name']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('environment_questionnaire_category_id') ."</dt><dd>:". $this->Html->link($envImpact['EnvironmentQuestionnaireCategory']['name'], array('controller' => 'environment_questionnaire_categories', 'action' => 'view', $envImpact['EnvironmentQuestionnaireCategory']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('details') ."</dt><dd>: ". h($envImpact['EnvImpact']['details']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($envImpact['EnvImpact']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($envImpact['EnvImpact']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($envImpact['EnvImpact']['status_user_id']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>: ". h($envImpact['EnvImpact']['approved_by']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>: ". h($envImpact['EnvImpact']['prepared_by']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($envImpact['Company']['name'], array('controller' => 'companies', 'action' => 'view', $envImpact['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$envImpact['EnvImpact']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

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
	<div class="aspects ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Aspects','modelClass'=>'Aspect','options'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'aspects'))); ?>
	
		
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

			<?php foreach ($aspects as $aspect): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $aspect['Aspect']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $aspect['Aspect']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $aspect['Aspect']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $aspect['Aspect']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $aspect['Aspect']['id']),array('class'=>''), __('Are you sure ?', $aspect['Aspect']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($aspect['Aspect']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('name') ."</dt><dd>: ". h($aspect['Aspect']['name']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('aspect_category_id') ."</dt><dd>:". $this->Html->link($aspect['AspectCategory']['name'], array('controller' => 'aspect_categories', 'action' => 'view', $aspect['AspectCategory']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('scale_1') ."</dt><dd>: ". h($aspect['Aspect']['scale_1']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_1_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_1_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_2') ."</dt><dd>: ". h($aspect['Aspect']['scale_2']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_2_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_2_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_3') ."</dt><dd>: ". h($aspect['Aspect']['scale_3']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_3_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_3_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_4') ."</dt><dd>: ". h($aspect['Aspect']['scale_4']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_4_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_4_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_5') ."</dt><dd>: ". h($aspect['Aspect']['scale_5']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_5_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_5_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_6') ."</dt><dd>: ". h($aspect['Aspect']['scale_6']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_6_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_6_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_7') ."</dt><dd>: ". h($aspect['Aspect']['scale_7']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_7_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_7_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_8') ."</dt><dd>: ". h($aspect['Aspect']['scale_8']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_8_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_8_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_9') ."</dt><dd>: ". h($aspect['Aspect']['scale_9']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_9_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_9_value']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_10') ."</dt><dd>: ". h($aspect['Aspect']['scale_10']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scale_10_value') ."</dt><dd>: ". h($aspect['Aspect']['scale_10_value']); ?>&nbsp;<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($aspect['Aspect']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($aspect['Aspect']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($aspect['Aspect']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($aspect['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $aspect['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($aspect['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $aspect['PreparedBy']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$aspect['Aspect']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
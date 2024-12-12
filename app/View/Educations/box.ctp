

<script>
$(document).ready(function () {
    $('#selectAll').on('click', function () {
       $(this).closest('form').find(':checkbox').prop('checked', this.checked);
	getVals();
    });    
});

function getVals(){
	var checkedValue = null;
	$("#recs_selected").val(null);
	$("#approve_recs_selected").val(null);
	var inputElements = document.getElementsByTagName('input');
	
	for(var i=0; inputElements[i]; ++i){
		
	      if(inputElements[i].className==="rec_ids" && 
		 inputElements[i].checked)
	      {
		   $("#approve_recs_selected").val($("#approve_recs_selected").val() + '+' + inputElements[i].value);
		   $("#recs_selected").val($("#recs_selected").val() + '+' + inputElements[i].value);
		   
	      }
	}
}
</script>

<?php echo $this->Session->flash();?>	
	<div class="educations ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Educations','modelClass'=>'Education','options'=>array("sr_no"=>"Sr No","title"=>"Title"),'pluralVar'=>'educations'))); ?>
	
		
<script type="text/javascript">
$(document).ready(function(){
$('dl dt a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>
<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?><input type="checkbox" id="selectAll"><label for="selectAll">Select All</label>
		<div class="container row  row table-responsive">

			
<?php 				$count=1; ?>
<?php foreach ($educations as $education): ?>

	<div class='col-md-4'>
<div class='box-pad'>
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $education['Education']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $education['Education']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $education['Education']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $education['Education']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $education['Education']['id']),array('class'=>''), __('Are you sure ?', $education['Education']['id'])); ?></li>
			</ul>
		</div>
		<dl>
		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($education['Education']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($education['Education']['title']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('publish') ?></dt>
			<dd>
			<?php if($education['Education']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;
			</dt>
		</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$education['Education']['id'],'multiple'=>'checkbox','class'=>'rec_ids','onClick'=>'getVals()')); ?>
		</div>
	</div>
		<?php if($count == 3)
					{
			$count= 0;	echo 
"<div class='row'><div class='col-md-12'></div></div>";
				}
	?>
<?php endforeach; ?>
			
		</div>
		<?php echo $this->Form->end();?>		
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
	
<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title"),'PublishedBanchList'=>array($PublishedBanchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title"))); ?>
<?php echo $this->element('export'); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
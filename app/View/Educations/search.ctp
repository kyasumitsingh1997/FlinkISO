
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

<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="educations ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Educations','modelClass'=>'Education','options'=>array("sr_no"=>"Sr No","title"=>"Title","branchid"=>"Branchid","departmentid"=>"Departmentid"),'pluralVar'=>'educations'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	
	
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th colspan="2"><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('sr_no'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				
				</tr>
				<?php if($educations){ ?>
<?php foreach ($educations as $education): ?>
	<tr class="on_page_src">
                    <td width="15"><?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$education['Education']['id'],'multiple'=>'checkbox','class'=>'rec_ids','onClick'=>'getVals()')); ?></td>
		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidance'), array('action' => 'view', $education['Education']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $education['Education']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $education['Education']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $education['Education']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $education['Education']['id']),array('class'=>''), __('Are you sure ?', $education['Education']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($education['Education']['sr_no']); ?>&nbsp;</td>
		<td><?php echo h($education['Education']['title']); ?>&nbsp;</td>

		<td width="60">
			<?php if($education['Education']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($education['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $education['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($education['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $education['DepartmentIds']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=13>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","branchid"=>"Branchid","departmentid"=>"Departmentid"),'PublishedBanchList'=>array($PublishedBanchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","branchid"=>"Branchid","departmentid"=>"Departmentid"))); ?>
<?php echo $this->element('approvals'); ?>
</div>
<?php echo $this->Js->writeBuffer();?>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

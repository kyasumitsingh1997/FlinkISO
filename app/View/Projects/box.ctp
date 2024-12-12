
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
	<div class="projects ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Projects','modelClass'=>'Project','options'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'projects'))); ?>
	
		
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

			<?php foreach ($projects as $project): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $project['Project']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $project['Project']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $project['Project']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $project['Project']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $project['Project']['id']),array('class'=>''), __('Are you sure ?', $project['Project']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($project['Project']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($project['Project']['title']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('goal') ."</dt><dd>: ". h($project['Project']['goal']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('scope') ."</dt><dd>: ". h($project['Project']['scope']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('success_criteria') ."</dt><dd>: ". h($project['Project']['success_criteria']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('challenges') ."</dt><dd>: ". h($project['Project']['challenges']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('project_cost') ."</dt><dd>: ". h($project['Project']['project_cost']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('start_date') ."</dt><dd>: ". h($project['Project']['start_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('end_date') ."</dt><dd>: ". h($project['Project']['end_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('current_status') ."</dt><dd>: ". h($project['Project']['current_status']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('employee_id') ."</dt><dd>:". $this->Html->link($project['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $project['Employee']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('employees') ."</dt><dd>: ". h($project['Project']['employees']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('customers') ."</dt><dd>: ". h($project['Project']['customers']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('suppliers_vendors') ."</dt><dd>: ". h($project['Project']['suppliers_vendors']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('others') ."</dt><dd>: ". h($project['Project']['others']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('branch_id') ."</dt><dd>:". $this->Html->link($project['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $project['Branch']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('users') ."</dt><dd>: ". h($project['Project']['users']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('user_session_id') ."</dt><dd>:". $this->Html->link($project['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $project['UserSession']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($project['Project']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($project['Project']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($project['Project']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($project['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $project['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($project['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $project['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($project['Company']['name'], array('controller' => 'companies', 'action' => 'view', $project['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$project['Project']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
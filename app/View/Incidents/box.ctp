
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
	<div class="incidents ">
		<div id="main">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incidents','modelClass'=>'Incident','options'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'incidents'))); ?>
	
		
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

			<?php foreach ($incidents as $incident): ?>
	<div class='col-md-4'>
<div class='box-pad'>		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-info "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $incident['Incident']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $incident['Incident']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $incident['Incident']['id']),array('style'=>'display:none'), __('Are you sure you want to delete this record ?', $incident['Incident']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $incident['Incident']['id']),array('class'=>''), __('Are you sure ?', $incident['Incident']['id'])); ?></li>
			</ul>
		</div>
<dl>		<dt><?php echo $this->Paginator->sort('sr_no') ."</dt><dd>: ". h($incident['Incident']['sr_no']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('title') ."</dt><dd>: ". h($incident['Incident']['title']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('reported_by') ."</dt><dd>: ". h($incident['Incident']['reported_by']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('department_id') ."</dt><dd>:". $this->Html->link($incident['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incident['Department']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('incident_date') ."</dt><dd>: ". h($incident['Incident']['incident_date']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('incident_reported_lag_time') ."</dt><dd>: ". h($incident['Incident']['incident_reported_lag_time']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('branch_id') ."</dt><dd>:". $this->Html->link($incident['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $incident['Branch']['id'])); ?>
		<dd>
		<dt><?php echo $this->Paginator->sort('location') ."</dt><dd>: ". h($incident['Incident']['location']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('location_details') ."</dt><dd>: ". h($incident['Incident']['location_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('activity') ."</dt><dd>: ". h($incident['Incident']['activity']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('activity_details') ."</dt><dd>: ". h($incident['Incident']['activity_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('damage_details') ."</dt><dd>: ". h($incident['Incident']['damage_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('first_aid_provided') ."</dt><dd>: ". h($incident['Incident']['first_aid_provided']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('first_aid_details') ."</dt><dd>: ". h($incident['Incident']['first_aid_details']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('first_aid_provided_by') ."</dt><dd>: ". h($incident['Incident']['first_aid_provided_by']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('person_responsible_id') ."</dt><dd>:". $this->Html->link($incident['PersonResponsible']['name'], array('controller' => 'employees', 'action' => 'view', $incident['PersonResponsible']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('corrective_preventive_action_id') ."</dt><dd>:". $this->Html->link($incident['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $incident['CorrectivePreventiveAction']['id'])); ?>
		<dd>

		<dt><?php echo $this->Paginator->sort('publish') ?></dt><dd>
			<?php if($incident['Incident']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</dtd>
		<dt><?php echo $this->Paginator->sort('record_status') ."</dt><dd>: ". h($incident['Incident']['record_status']); ?>&nbsp;<dd>
		<dt><?php echo $this->Paginator->sort('status_user_id') ."</dt><dd>: ". h($incident['Incident']['status_user_id']); ?>&nbsp;<dd>

			<dt><?php echo $this->Paginator->sort('approved_by') ."</dt><dd>:". $this->Html->link($incident['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incident['ApprovedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('prepared_by') ."</dt><dd>:". $this->Html->link($incident['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incident['PreparedBy']['id'])); ?>
		<dd>

			<dt><?php echo $this->Paginator->sort('company_id') ."</dt><dd>:". $this->Html->link($incident['Company']['name'], array('controller' => 'companies', 'action' => 'view', $incident['Company']['id'])); ?>
		<dd>
	</dl>
<?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$incident['Incident']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></div></div><?php endforeach; ?>
			
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
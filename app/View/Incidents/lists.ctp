<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidents ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incidents','modelClass'=>'Incident','options'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'incidents'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Incident'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Department'), array('controller' => 'departments', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Branch'), array('controller' => 'branches', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Person Responsible'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Corrective Preventive Action'), array('controller' => 'corrective_preventive_actions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Incident Affected Personal'), array('controller' => 'incident_affected_personals', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Incident Investigation'), array('controller' => 'incident_investigations', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Incident Witness'), array('controller' => 'incident_witnesses', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="incidents_tab_ajax"></div>
</div>

<script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
	ui.jqXHR.error(function() {
	  ui.panel.html(
	    "Error Loading ... " +
	    "Please contact administrator." );
	});
      }
    });
  });
</script>

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
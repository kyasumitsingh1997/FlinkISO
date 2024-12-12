<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidents ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incidents','modelClass'=>'Incident','options'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By"),'pluralVar'=>'incidents'))); ?>

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
		<?php if($incidents){ ?><p class="alert alert-info">Note: Click on Add button to add Incident Affected Person's / Witnesses / Investigation. To edit any of these, click on the Number and then select the record from the list on a new page.</p><?php } ?>
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('reported_by'); ?></th>-->
				<th><?php echo $this->Paginator->sort('department_id'); ?></th>
				<th><?php echo $this->Paginator->sort('incident_date'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('incident_reported_lag_time'); ?></th> -->
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('location'); ?></th>
				<th><?php echo $this->Paginator->sort('location_details'); ?></th> -->
				<!--<th><?php echo $this->Paginator->sort('activity'); ?></th> -->
				<!-- <th><?php echo $this->Paginator->sort('activity_details'); ?></th> 
				<th><?php echo $this->Paginator->sort('damage_details'); ?></th>
				<th><?php echo $this->Paginator->sort('first_aid_provided'); ?></th>
				<th><?php echo $this->Paginator->sort('first_aid_details'); ?></th>
				<th><?php echo $this->Paginator->sort('first_aid_provided_by'); ?></th>-->
				<!--<th><?php echo $this->Paginator->sort('person_responsible_id'); ?></th>-->
				<!-- <th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th> -->
					<th><?php echo __('Afected Persons'); ?></th>
                    <th><?php echo __('Witnesses'); ?></th>
                    <th><?php echo __('Investigation Report'); ?></th>
                    <th><?php echo $this->Paginator->sort('publish'); ?></th>							
				</tr>
				<?php if($incidents){ ?>
<?php foreach ($incidents as $incident): ?>
	<tr class="on_page_src">
                    <td class=" actions">	<?php echo $this->element('actions', array('created' => $incident['Incident']['created_by'], 'postVal' => $incident['Incident']['id'], 'softDelete' => $incident['Incident']['soft_delete'])); ?>	</td>		
    	<td><?php echo h($incident['Incident']['title']); ?>&nbsp;</td>
		<!--<td><?php echo h($incident['Incident']['reported_by']); ?>&nbsp;</td>-->
		<td>
			<?php echo $this->Html->link($incident['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incident['Department']['id'])); ?>
		</td>
		<td><?php echo h($incident['Incident']['incident_date']); ?>&nbsp;</td>
		<!-- <td><?php echo h($incident['Incident']['incident_reported_lag_time']); ?>&nbsp;</td> -->
		<td>
			<?php echo $this->Html->link($incident['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $incident['Branch']['id'])); ?>
		</td>
		<!--<td><?php echo h($incident['Incident']['location']); ?>&nbsp;</td>-->
		<!-- <td><?php echo h($incident['Incident']['location_details']); ?>&nbsp;</td> -->
		<!--<td><?php echo h($incident['Incident']['activity']); ?>&nbsp;</td>-->
		<!-- <td><?php echo h($incident['Incident']['activity_details']); ?>&nbsp;</td> 
		<td><?php echo h($incident['Incident']['damage_details']); ?>&nbsp;</td>
		<td><?php echo h($incident['Incident']['first_aid_provided']); ?>&nbsp;</td>
		<!-- <td><?php echo h($incident['Incident']['first_aid_details']); ?>&nbsp;</td>
		<td><?php echo h($incident['Incident']['first_aid_provided_by']); ?>&nbsp;</td> 
		<td>
			<?php echo $this->Html->link($incident['PersonResponsible']['name'], array('controller' => 'employees', 'action' => 'view', $incident['PersonResponsible']['id'])); ?>
		</td>
		<!-- <td>
			<?php echo $this->Html->link($incident['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $incident['CorrectivePreventiveAction']['id'])); ?>
		</td> -->
		
<!--        <td><?php //cho $this->Html->link('Add','#',array('escape'=>false,'class'=>'btn btn-sm btn-info iap','id'=>$incident['Incident']['id'])); ?></td>-->
        <td> 
        	<div class="btn-group">
        		<a href="#" id="add_affected_person<?php echo $incident['Incident']['id']?>" class="btn btn-xs btn-info"><?php echo __('Add'); ?></a>
        		<?php 
        				echo $this->Html->link(count($incident['IncidentAffectedPersonal']),
        				array('controller'=>'incident_affected_personals','action'=>'index',$incident['Incident']['id']),
        				array('class'=> (count($incident['IncidentAffectedPersonal'])>0)? "btn btn-xs btn-success" : "btn btn-xs btn-danger", 'escape'=>false)
        		);?>

        	</div>	
        </td>

        <td> 
			<div class="btn-group">
        		<a href="#" id="add_incident_witnesses<?php echo $incident['Incident']['id']?>" class="btn btn-xs btn-info"><?php echo __('Add'); ?></a>
				<?php 
        				echo $this->Html->link(count($incident['IncidentWitness']),
        				array('controller'=>'incident_witnesses','action'=>'index',$incident['Incident']['id']),
        				array('class'=> (count($incident['IncidentWitness'])>0)? "btn btn-xs btn-success" : "btn btn-xs btn-danger", 'escape'=>false)
        		);?>
        	</div>        		
        </td>			
<!--        <td> <a href="#" id="add_investigation" class="btn btn-sm btn-info"><?php //echo __('Add'); ?></a></td>-->
        <?php //echo $this->Html->link('Add',array('controller'=>'incident_witnesses','action'=>'lists',$incident['Incident']['id'],'model'=>1),array('id'=> 'add_incident_witnesses','class'=>'btn btn-sm btn-info')); ?>
        <td>
        	<div class="btn-group">
        		<?php echo $this->Html->link('Add',array('controller'=>'incident_investigations','action'=>'lists',$incident['Incident']['id'],'model'=>1),array('class'=>'btn btn-xs btn-info')); ?>
        		<?php 
        				echo $this->Html->link(count($incident['IncidentInvestigation']),
        				array('controller'=>'incident_investigations','action'=>'index',$incident['Incident']['id']),
        				array('class'=> (count($incident['IncidentInvestigation'])>0)? "btn btn-xs btn-success" : "btn btn-xs btn-danger", 'escape'=>false)
        		);?>
        	</div>
        	</td>        
		<td width="60">
			<?php if($incident['Incident']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<script>

$().ready(function(){
$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});	
	
            $('#add_incident_witnesses<?php echo $incident['Incident']['id']?>').click(function(){
                $("#showModal-indicator").show();
                $('#witnessesModal').modal();
                $('#witnessesDetails').load('<?php echo Router::url('/', true); ?>incident_witnesses/add_ajax/<?php echo $incident['Incident']['id']; ?>/1');
            });
            $('#witnessesModal').on('hidden.bs.modal', function (e) {
                $("#showModal-indicator").hide();
            });
            
            $('#add_affected_person<?php echo $incident['Incident']['id']?>').click(function(){
                $("#showModal-indicator").show();
                $('#affectPersonModal').modal();
                $('#affectPersonDetails').load('<?php echo Router::url('/', true); ?>incident_affected_personals/add_ajax/<?php echo $incident['Incident']['id']; ?>/1');
            });
            $('#affectPersonModal').on('hidden.bs.modal', function (e) {
                $("#showModal-indicator").hide();
            });
            
});        
</script>	
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=99>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
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
<div id="loadiap"></div>
<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","reported_by"=>"Reported By","incident_date"=>"Incident Date","incident_reported_lag_time"=>"Incident Reported Lag Time","location"=>"Location","location_details"=>"Location Details","activity"=>"Activity","activity_details"=>"Activity Details","damage_details"=>"Damage Details","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By"))); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->element('approvals'); ?>
</div>

<script>
$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});
$().ready(function(){
$("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly'); 
            });
    </script>
    <style>
    #ui-datepicker-div {z-index: 1151 !important ;}
    </style>
</script>
  <div class="modal fade" id="witnessesModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Incident Witness
                       
                    </h4>
                </div>
                <div class="modal-body" id="witnessesDetails"></div>
                <div class="modal-footer">
                    <p><small></small></p>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    <div class="modal fade" id="affectPersonModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Add Affected Person
                       
                    </h4>
                </div>
                <div class="modal-body" id="affectPersonDetails"></div>
                <div class="modal-footer">
                    <p><small></small></p>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

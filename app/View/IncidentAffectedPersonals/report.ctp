<?php echo $this->Html->script(array('openwysiwyg/scripts/wysiwyg','openwysiwyg/scripts/wysiwyg-settings','jquery-ui'));
 ?><?php echo $this->Html->css(array('openwysiwyg/styles/wysiwyg'));
 ?><script type="text/javascript">
    WYSIWYG.attach('ReportDetails',full);
</script>
<div id="reports_ajax">
    <?php echo $this->Session->flash();?>
	
        <div class="nav">
            <div class="reports form col-md-8">
            <h4>Add Report            <?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info'));
 ?>	    <?php echo $this->Html->link('', '#advanced_search',array('class'=>'fa fa-search h4-title','data-toggle'=>'modal')); ?>
	    <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
	    </h4>
            <?php echo $this->Form->create('Report',array('controller'=>'reports','action'=>'add'),array('role'=>'form','class'=>'form'));
 ?>	<fieldset>
    	<div class="panel panel-default">
            <div class="panel-body">
            Below is the system generated report. You can modify this report and save it under report center. <br/>
            Report is rendered in a Text Editor so that you can make the required changes.<br/>
            You can either publish this report as it is or keep it ready in reports center.
            </div>
        </div>	
        <textarea id="ReportDetails" name="data[Report][details]" style="float:left; clear:both">        
	<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		<tr>
			<td colspan="4"><h2>Company Name (ISO CERTFICATION DOCUMENTS) :</h2>Powered by : FlinkISO ™</td>
		</tr>
		<tr>
			<td>Document Title </td><td>&nbsp;<?php echo h($incidentAffectedPersonal['MasterListOfFormat']['title']); ?></td>
			<td>Document Number</td><td>&nbsp;<?php echo h($incidentAffectedPersonal['MasterListOfFormat']['document_number']); ?></td>
		</tr>
		<tr>
			<td>Revision Number </td><td>&nbsp;<?php echo h($incidentAffectedPersonal['MasterListOfFormat']['revision_number']); ?></td>
			<td>Revision Date</td><td>&nbsp;<?php echo h($incidentAffectedPersonal['MasterListOfFormat']['revision_date']); ?></td>
		</tr>
                </table>
	<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		<tr>
				<th><?php echo h(Inflector::humanize("sr_no"));?></th>
				<th><?php echo h(Inflector::humanize("incident_id"));?></th>
				<th><?php echo h(Inflector::humanize("person_type"));?></th>
				<th><?php echo h(Inflector::humanize("employee_id"));?></th>
				<th><?php echo h(Inflector::humanize("name"));?></th>
				<th><?php echo h(Inflector::humanize("address"));?></th>
				<th><?php echo h(Inflector::humanize("phone"));?></th>
				<th><?php echo h(Inflector::humanize("department_id"));?></th>
				<th><?php echo h(Inflector::humanize("designation_id"));?></th>
				<th><?php echo h(Inflector::humanize("age"));?></th>
				<th><?php echo h(Inflector::humanize("gender"));?></th>
				<th><?php echo h(Inflector::humanize("first_aid_provided"));?></th>
				<th><?php echo h(Inflector::humanize("first_aid_details"));?></th>
				<th><?php echo h(Inflector::humanize("first_aid_provided_by"));?></th>
				<th><?php echo h(Inflector::humanize("follow_up_action_taken"));?></th>
				<th><?php echo h(Inflector::humanize("other"));?></th>
				<th><?php echo h(Inflector::humanize("illhealth_reported"));?></th>
				<th><?php echo h(Inflector::humanize("normal_work_affected"));?></th>
				<th><?php echo h(Inflector::humanize("number_of_work_affected_dates"));?></th>
				<th><?php echo h(Inflector::humanize("incident_investigator_id"));?></th>
				<th><?php echo h(Inflector::humanize("date_of_interview"));?></th>
				<th><?php echo h(Inflector::humanize("investigation_interview_findings"));?></th>
				<th><?php echo h(Inflector::humanize("publish"));?></th>
				<th><?php echo h(Inflector::humanize("record_status"));?></th>
				<th><?php echo h(Inflector::humanize("status_user_id"));?></th>
				<th><?php echo h(Inflector::humanize("branchid"));?></th>
				<th><?php echo h(Inflector::humanize("departmentid"));?></th>
				<th><?php echo h(Inflector::humanize("approved_by"));?></th>
				<th><?php echo h(Inflector::humanize("prepared_by"));?></th>
				<th><?php echo h(Inflector::humanize("company_id"));?></th>
				
				</tr>
	<tr>
	<?php foreach ($incidentAffectedPersonals as $incidentAffectedPersonal): ?>
	<tr>
		<td width="50"><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['sr_no']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['Incident']['title']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['person_type']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['Employee']['name']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['name']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['address']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['phone']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['Department']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['Designation']['name']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['age']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['gender']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_provided']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_details']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_provided_by']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['follow_up_action_taken']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['other']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['illhealth_reported']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['normal_work_affected']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['number_of_work_affected_dates']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['IncidentInvestigator']['name']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['date_of_interview']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['investigation_interview_findings']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['publish']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['status_user_id']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['BranchIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['DepartmentIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['ApprovedBy']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['PreparedBy']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['Company']['name']); ?>&nbsp;</td>
	</tr>
	<?php endforeach; ?>
			</table>
			<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		                <tr>
		                    <td>Prepared By </td><td>&nbsp;</td>
		                    <td>Approved By</td><td>&nbsp;</td>
		                </tr>
			</table>
	        </textarea>
	<?php echo $this->Form->input('title');?>
<?php echo $this->Form->input('branch_id',array('style'=>'width:90%','options'=>$PublishedBranchList));?>
<?php echo $this->Js->link('Add New',array('controller'=>'branchs','action'=>'add'),array('class'=>'label btn-info','update'=>'#reports_ajax'));?>
<?php echo $this->Form->input('department_id',array('style'=>'width:90%','options'=>$PublishedDepartmentList));?>
<?php echo $this->Js->link('Add New',array('controller'=>'departments','action'=>'add'),array('class'=>'label btn-info','update'=>'#reports_ajax'));?>
<?php echo $this->Form->input('master_list_of_format_id',array('style'=>'width:90%'));?>
<?php echo $this->Js->link('Add New',array('controller'=>'master_list_of_formats','action'=>'add'),array('class'=>'label btn-info','update'=>'#reports_ajax'));?>
<?php echo $this->Form->input('description');?>
<?php echo $this->Form->input('report_date');?>
<?php echo $this->Form->input('publish');?>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success'));?>
<?php echo $this->Form->end();?>
<?php echo $this->Js->writeBuffer();?>

</fieldset>
</div>
<script> $("#ReportReportDate").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">

	<p><?php echo $this->element('helps');
 ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');
 ?><?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#reports_ajax')));
 ?>
<?php echo $this->Js->writeBuffer();
 ?>
		<div class="modal fade" id="advanced_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Advanced Search</h4>
		</div>
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>


</div></div></div>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
	
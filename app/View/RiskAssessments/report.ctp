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
			<td>Document Title </td><td>&nbsp;<?php echo h($riskAssessment['MasterListOfFormat']['title']); ?></td>
			<td>Document Number</td><td>&nbsp;<?php echo h($riskAssessment['MasterListOfFormat']['document_number']); ?></td>
		</tr>
		<tr>
			<td>Revision Number </td><td>&nbsp;<?php echo h($riskAssessment['MasterListOfFormat']['revision_number']); ?></td>
			<td>Revision Date</td><td>&nbsp;<?php echo h($riskAssessment['MasterListOfFormat']['revision_date']); ?></td>
		</tr>
                </table>
	<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		<tr>
				<th><?php echo h(Inflector::humanize("sr_no"));?></th>
				<th><?php echo h(Inflector::humanize("title"));?></th>
				<th><?php echo h(Inflector::humanize("process_id"));?></th>
				<th><?php echo h(Inflector::humanize("branch_id"));?></th>
				<th><?php echo h(Inflector::humanize("task"));?></th>
				<th><?php echo h(Inflector::humanize("ra_date"));?></th>
				<th><?php echo h(Inflector::humanize("ra_expert_1"));?></th>
				<th><?php echo h(Inflector::humanize("ra_expert_2"));?></th>
				<th><?php echo h(Inflector::humanize("management"));?></th>
				<th><?php echo h(Inflector::humanize("technical_expert"));?></th>
				<th><?php echo h(Inflector::humanize("risk_control_exprt"));?></th>
				<th><?php echo h(Inflector::humanize("reference_number"));?></th>
				<th><?php echo h(Inflector::humanize("hazard_type_id"));?></th>
				<th><?php echo h(Inflector::humanize("hazard_source_id"));?></th>
				<th><?php echo h(Inflector::humanize("what_could_happan"));?></th>
				<th><?php echo h(Inflector::humanize("accident_type_id"));?></th>
				<th><?php echo h(Inflector::humanize("severiry_type_id"));?></th>
				<th><?php echo h(Inflector::humanize("existing_controls"));?></th>
				<th><?php echo h(Inflector::humanize("likelihood"));?></th>
				<th><?php echo h(Inflector::humanize("risk_rating_id"));?></th>
				<th><?php echo h(Inflector::humanize("additional_control_needed"));?></th>
				<th><?php echo h(Inflector::humanize("person_responsible"));?></th>
				<th><?php echo h(Inflector::humanize("target_date"));?></th>
				<th><?php echo h(Inflector::humanize("completions_date"));?></th>
				<th><?php echo h(Inflector::humanize("process_notes"));?></th>
				<th><?php echo h(Inflector::humanize("task_notes"));?></th>
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
	<?php foreach ($riskAssessments as $riskAssessment): ?>
	<tr>
		<td width="50"><?php echo h($riskAssessment['RiskAssessment']['sr_no']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['title']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['Process']['title']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['Branch']['name']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['task']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['ra_date']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['ra_expert_1']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['ra_expert_2']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['management']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['technical_expert']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['risk_control_exprt']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['reference_number']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['HazardType']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['HazardSource']['name']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['what_could_happan']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['AccidentType']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['SeveriryType']['name']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['existing_controls']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['likelihood']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['risk_rating_id']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['additional_control_needed']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['person_responsible']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['target_date']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['completions_date']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['process_notes']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['task_notes']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['publish']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['status_user_id']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['BranchIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['DepartmentIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['ApprovedBy']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['PreparedBy']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($riskAssessment['Company']['name']); ?>&nbsp;</td>
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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>


</div></div></div>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
	
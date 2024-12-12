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
			<td>Document Title </td><td>&nbsp;<?php echo h($emailTrigger['MasterListOfFormat']['title']); ?></td>
			<td>Document Number</td><td>&nbsp;<?php echo h($emailTrigger['MasterListOfFormat']['document_number']); ?></td>
		</tr>
		<tr>
			<td>Revision Number </td><td>&nbsp;<?php echo h($emailTrigger['MasterListOfFormat']['revision_number']); ?></td>
			<td>Revision Date</td><td>&nbsp;<?php echo h($emailTrigger['MasterListOfFormat']['revision_date']); ?></td>
		</tr>
                </table>
	<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		<tr>
				<th><?php echo h(Inflector::humanize("sr_no"));?></th>
				<th><?php echo h(Inflector::humanize("name"));?></th>
				<th><?php echo h(Inflector::humanize("details"));?></th>
				<th><?php echo h(Inflector::humanize("system_table"));?></th>
				<th><?php echo h(Inflector::humanize("changed_field"));?></th>
				<th><?php echo h(Inflector::humanize("if_added"));?></th>
				<th><?php echo h(Inflector::humanize("if_edited"));?></th>
				<th><?php echo h(Inflector::humanize("if_publish"));?></th>
				<th><?php echo h(Inflector::humanize("if_approved"));?></th>
				<th><?php echo h(Inflector::humanize("if_soft_delete"));?></th>
				<th><?php echo h(Inflector::humanize("recipents"));?></th>
				<th><?php echo h(Inflector::humanize("cc"));?></th>
				<th><?php echo h(Inflector::humanize("bcc"));?></th>
				<th><?php echo h(Inflector::humanize("subject"));?></th>
				<th><?php echo h(Inflector::humanize("template"));?></th>
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
	<?php foreach ($emailTriggers as $emailTrigger): ?>
	<tr>
		<td width="50"><?php echo h($emailTrigger['EmailTrigger']['sr_no']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['name']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['details']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['system_table']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['changed_field']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['if_added']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['if_edited']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['if_publish']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['if_approved']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['if_soft_delete']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['recipents']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['cc']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['bcc']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['subject']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['template']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['publish']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($emailTrigger['EmailTrigger']['status_user_id']); ?>&nbsp;</td>
		<td width="50"><?php echo h($emailTrigger['BranchIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($emailTrigger['DepartmentIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($emailTrigger['ApprovedBy']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($emailTrigger['PreparedBy']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($emailTrigger['Company']['name']); ?>&nbsp;</td>
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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","details"=>"Details","system_table"=>"System Table","changed_field"=>"Changed Field","if_added"=>"If Added","if_edited"=>"If Edited","if_publish"=>"If Publish","if_approved"=>"If Approved","if_soft_delete"=>"If Soft Delete","recipents"=>"Recipents","cc"=>"Cc","bcc"=>"Bcc","subject"=>"Subject","template"=>"Template","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>


</div></div></div>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
	
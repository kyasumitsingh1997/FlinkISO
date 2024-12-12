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
			<td>Document Title </td><td>&nbsp;<?php echo h($aspect['MasterListOfFormat']['title']); ?></td>
			<td>Document Number</td><td>&nbsp;<?php echo h($aspect['MasterListOfFormat']['document_number']); ?></td>
		</tr>
		<tr>
			<td>Revision Number </td><td>&nbsp;<?php echo h($aspect['MasterListOfFormat']['revision_number']); ?></td>
			<td>Revision Date</td><td>&nbsp;<?php echo h($aspect['MasterListOfFormat']['revision_date']); ?></td>
		</tr>
                </table>
	<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		<tr>
				<th><?php echo h(Inflector::humanize("sr_no"));?></th>
				<th><?php echo h(Inflector::humanize("name"));?></th>
				<th><?php echo h(Inflector::humanize("aspect_category_id"));?></th>
				<th><?php echo h(Inflector::humanize("scale_1"));?></th>
				<th><?php echo h(Inflector::humanize("scale_1_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_2"));?></th>
				<th><?php echo h(Inflector::humanize("scale_2_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_3"));?></th>
				<th><?php echo h(Inflector::humanize("scale_3_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_4"));?></th>
				<th><?php echo h(Inflector::humanize("scale_4_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_5"));?></th>
				<th><?php echo h(Inflector::humanize("scale_5_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_6"));?></th>
				<th><?php echo h(Inflector::humanize("scale_6_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_7"));?></th>
				<th><?php echo h(Inflector::humanize("scale_7_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_8"));?></th>
				<th><?php echo h(Inflector::humanize("scale_8_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_9"));?></th>
				<th><?php echo h(Inflector::humanize("scale_9_value"));?></th>
				<th><?php echo h(Inflector::humanize("scale_10"));?></th>
				<th><?php echo h(Inflector::humanize("scale_10_value"));?></th>
				<th><?php echo h(Inflector::humanize("publish"));?></th>
				<th><?php echo h(Inflector::humanize("record_status"));?></th>
				<th><?php echo h(Inflector::humanize("status_user_id"));?></th>
				<th><?php echo h(Inflector::humanize("branchid"));?></th>
				<th><?php echo h(Inflector::humanize("departmentid"));?></th>
				<th><?php echo h(Inflector::humanize("approved_by"));?></th>
				<th><?php echo h(Inflector::humanize("prepared_by"));?></th>
				
				</tr>
	<tr>
	<?php foreach ($aspects as $aspect): ?>
	<tr>
		<td width="50"><?php echo h($aspect['Aspect']['sr_no']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($aspect['AspectCategory']['name']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_1']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_1_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_2']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_2_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_3']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_3_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_4']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_4_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_5']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_5_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_6']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_6_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_7']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_7_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_8']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_8_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_9']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_9_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_10']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['scale_10_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['publish']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($aspect['Aspect']['status_user_id']); ?>&nbsp;</td>
		<td width="50"><?php echo h($aspect['BranchIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($aspect['DepartmentIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($aspect['ApprovedBy']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($aspect['PreparedBy']['name']); ?>&nbsp;</td>
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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>


</div></div></div>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
	
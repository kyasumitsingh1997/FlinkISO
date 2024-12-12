<?php $revision_count = 1 ?>
<div class="panel-group" id="document_revision"  style="margin-bottom:5px">
<?php foreach($revisions as $revision): ?>
	<div class="box box-solid box-success">
		<div class="box-header">
			<h4 class="panel-title"> 
				<?php echo $this->Html->link('Revision '.$revision_count,'#collapserevs'.$revision_count,array('id'=>'link'.$revision_count,'data-toggle'=>'collapse', 'data-parent'=>'#document_revision','escape'=>false,'class'=>'collapsed')); ?> 
				<small class="pull-right"><?php echo $this->Html->link('<span class="glyphicon glyphicon-new-window"></span>',array('controller'=>'document_amendment_record_sheets','action'=>'view',$revision['DocumentAmendmentRecordSheet']['id']),array('escape'=>false)); ?></small> 
			</h4>
		</div>
		<div id="collapserevs<?php echo $revision_count ?>" class="panel-collapse collapse ">
			<div class="box-body">
				<table class="table table-responsive table-bordered">
					<tr>
						<th><?php echo __('Title'); ?></th>
						<td><?php echo $revision['MasterListOfFormatID']['title'] ?>&nbsp;</td>
					</tr>
					<tr>
						<th><?php echo __('Document #'); ?></th>
						<td><?php echo $revision['DocumentAmendmentRecordSheet']['document_number'] ?>&nbsp;</td>
					</tr>
					<tr>
						<th><?php echo __('Issue #'); ?></th>
						<td><?php echo $revision['DocumentAmendmentRecordSheet']['issue_number'] ?>&nbsp;</td>
					</tr>
					<tr>
						<th><?php echo __('Revision'); ?></th>
						<td><?php echo $revision['DocumentAmendmentRecordSheet']['revision_number'] ?>&nbsp;</td>
					</tr>
					<tr>
						<th><?php echo __('Revision Date'); ?></th>
						<td><?php echo $revision['DocumentAmendmentRecordSheet']['revision_date'] ?>&nbsp;</td>
					</tr>
					<tr>
						<th><?php echo __('Reason'); ?></th>
						<td><?php echo $revision['DocumentAmendmentRecordSheet']['reason_for_change'] ?>&nbsp;</td>
					</tr>
					<tr>
						<th><?php echo __('Prepared By'); ?></th>
						<td><?php echo $revision['PreparedBy']['name'] ?>&nbsp;</td>
					</tr>
					<tr>
						<th><?php echo __('Approved By'); ?></th>
						<td><?php echo $revision['ApprovedBy']['name'] ?>&nbsp;</td>
					</tr>					
				</table>
			</div>
		</div>
	</div>	
<?php $revision_count++; ?>
<?php endforeach; ?>
</div>

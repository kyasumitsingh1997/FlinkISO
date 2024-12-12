<div id="documentAmendmentRecordSheets_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="documentAmendmentRecordSheets form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Document Amendment Record Sheet'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <?php 
                if($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['file_upload_id'] == -1 or $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['file_upload_id'] == null){                
            ?>
            <table class="table table-responsive">
                <tr><td><?php echo __('Sr. No'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['sr_no']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Request From'); ?></td>
                    <td>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['branch_id'] != -1) echo "<strong>Branch : </strong>" . h($documentAmendmentRecordSheet['Branch']['name']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['department_id'] != -1) echo "<strong>Department : </strong>" . h($documentAmendmentRecordSheet['Department']['name']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['employee_id'] != -1) echo "<strong>Employee : </strong>" . h($documentAmendmentRecordSheet['Employee']['name']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['customer_id'] != -1) echo "<strong>Customer : </strong>" . h($documentAmendmentRecordSheet['Customer']['name']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['suggestion_form_id'] != -1) echo "<strong>Suggestion Form : </strong>" . h($documentAmendmentRecordSheet['SuggestionForm']['title']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['others'] != '') echo "<strong>Other : </strong>" . h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['others']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Master List Of Format'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['title']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Document Number'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['document_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Issue Number'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['issue_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Revision Number'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['revision_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Revision Date'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['revision_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($PublishedEmployeeList[$documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['prepared_by']]); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($PublishedEmployeeList[$documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['approved_by']]); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr class="hide">
                   	<td colspan="2">
							<h4><?php echo __('Amendment Details'); ?></h4>
                        <?php echo $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['amendment_details']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td><?php echo __('Reason For Change'); ?></td>
					<td>
                        <?php echo $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['reason_for_change']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
            </table>
        
        <?php }else{ ?>
            <table class="table table-responsive">
                <tr><td width="15%"><?php echo __('Sr. No'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['sr_no']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Request From'); ?></td>
                    <td>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['branch_id'] != -1) echo "<strong>Branch : </strong>" . h($documentAmendmentRecordSheet['Branch']['name']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['department_id'] != -1) echo "<strong>Department : </strong>" . h($documentAmendmentRecordSheet['Department']['name']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['employee_id'] != -1) echo "<strong>Employee : </strong>" . h($documentAmendmentRecordSheet['Employee']['name']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['customer_id'] != -1) echo "<strong>Customer : </strong>" . h($documentAmendmentRecordSheet['Customer']['name']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['suggestion_form_id'] != -1) echo "<strong>Suggestion Form : </strong>" . h($documentAmendmentRecordSheet['SuggestionForm']['title']); ?>
                        <?php if ($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['others'] != '') echo "<strong>Other : </strong>" . h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['others']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('File Details'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['FileUpload']['file_details']) .'.'.h($documentAmendmentRecordSheet['FileUpload']['file_type']); ?>
                        &nbsp;
                    </td>
                </tr>
                <!-- <tr><td><?php echo __('Revision Number'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['FileUpload']['version']); ?>
                        &nbsp;
                    </td>
                </tr> -->
                <tr><td><?php echo __('Previous Details'); ?></td>
                    <td>
                        <?php echo ($documentAmendmentRecordSheet['ChangeAdditionDeletionRequest']['current_document_details']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Changes Made'); ?></td>
                    <td>
                        <?php echo ($documentAmendmentRecordSheet['ChangeAdditionDeletionRequest']['proposed_document_changes']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Revision Date'); ?></td>
                    <td>
                        <?php echo h($documentAmendmentRecordSheet['ChangeAdditionDeletionRequest']['modified']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($PublishedEmployeeList[$documentAmendmentRecordSheet['ChangeAdditionDeletionRequest']['prepared_by']]); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($PublishedEmployeeList[$documentAmendmentRecordSheet['ChangeAdditionDeletionRequest']['approved_by']]); ?>
                        &nbsp;
                    </td>
                </tr>                
            </table>
        <?php } ;?>

            <?php 
            if($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['file_upload_id'] == -1){
                echo $this->element('document_amendment_record_sheets_files_view', array('usersId' => $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['created_by'], 'recordId' => $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['id'])); 
            }else{
            //    echo $this->element('document_amendment_record_sheets_files_view', array('usersId' => $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['created_by'], 'recordId' => $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['id'])); 
            }?>
        </div>
        <div class="col-md-4">
			<p><?php echo $this->element('document_revisions'); ?></p>
			<p><?php echo $this->element('helps'); ?></p>
        </div>
		</div>
		</div>
		<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default hide">
                <div class="panel-heading">
                    <h4><?php echo __("Current Document") ?></h4>
                </div>
                <div class="panel-body">
                <?php
                    if($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['file_upload_id'] == -1 or $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['file_upload_id'] == null){
                ?>
                    <table class="table table-striped table-hover table-responsive">
                        <tr>
                            <th><?php echo __("Document Title") ?></th>
                            <th><?php echo __("Document Number") ?></th>
                            <th><?php echo __("Revision Number") ?></th>
                            <th><?php echo __("Revision Date") ?></th>
                            <th><?php echo __("Prepared By") ?></th>
                            <th><?php echo __("Approved By") ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $firstDocument['MasterListOfFormat']['title'] ?></td>
                            <td><?php echo $firstDocument['MasterListOfFormat']['document_number'] ?></td>
                            <td><?php echo $firstDocument['MasterListOfFormat']['revision_number'] ?></td>
                            <td><?php echo $firstDocument['MasterListOfFormat']['revision_date'] ?></td>
                            <td><?php echo $firstDocument['PreparedBy']['name'] ?></td>
                            <td><?php echo $firstDocument['ApprovedBy']['name'] ?></td>
                        </tr>
                    </table>
                <?php } else { ?> 
                            <table class="table table-striped table-hover table-responsive">
                        <tr>
                            <th><?php echo __("Document Title") ?></th>
                            <th><?php echo __("Document Number") ?></th>
                            <th><?php echo __("Revision Number") ?></th>
                            <th><?php echo __("Revision Date") ?></th>
                            <th><?php echo __("Prepared By") ?></th>
                            <th><?php echo __("Approved By") ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $firstDocument['FileUpload']['file_details'] .'.'. $firstDocument['FileUpload']['file_type'] ?></td>
                            <td><?php echo $firstDocument['FileUpload']['version'] ?></td>
                            <td><?php echo $firstDocument['FileUpload']['modified'] ?></td>
                            <td><?php echo $firstDocument['PreparedBy']['name'] ?></td>
                            <td><?php echo $firstDocument['ApprovedBy']['name'] ?></td>
                        </tr>                        
                    </table>
                <?php } ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><?php echo __("Amendment History") ?></h4>
                </div>
                <div class="panel-body">
                <?php
                    if($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['file_upload_id'] == -1 or $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['file_upload_id'] == null){                
                ?>
            
                    <table class="table table-striped table-hover table-responsive">
                        <tr>
                            <th><?php echo __("Document Title") ?></th>
                            <th><?php echo __("Document Number") ?></th>
                            <th><?php echo __("Revision Number") ?></th>
                            <th><?php echo __("Revision Date") ?></th>
                            <th><?php echo __("Prepared By") ?></th>
                            <th><?php echo __("Approved By") ?></th>
                        </tr>
                        <?php foreach($revisionHistorys as $revisionHistory): 
                        ?>
                        <tr>
                            <td><?php echo $this->Html->link($firstDocument['MasterListOfFormat']['title'],array('controller'=>'change_addition_deletion_requests','action'=>'view',$revisionHistory['ChangeAdditionDeletionRequest']['id'])) ?></td>
                            <td><?php echo $revisionHistory['DocumentAmendmentRecordSheet']['document_number'] ?></td>
                            <td><?php echo $revisionHistory['DocumentAmendmentRecordSheet']['revision_number'] ?></td>
                            <td><?php echo $revisionHistory['DocumentAmendmentRecordSheet']['revision_date'] ?></td>
                            <td><?php echo $revisionHistory['PreparedBy']['name'] ?></td>
                            <td><?php echo $revisionHistory['ApprovedBy']['name'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php } else { ?> 
                        <table class="table table-striped table-hover table-responsive">
                        <tr>
                            <th><?php echo __("File Name") ?></th>
                            <th><?php echo __("version") ?></th>
                            <th><?php echo __("Revision Date") ?></th>
                            <th><?php echo __("Prepared By") ?></th>
                            <th><?php echo __("Approved By") ?></th>
                        </tr>
                        <?php foreach($revisionHistorys as $revisionHistory):                         
                        ?>
                        <tr>
                            <td><?php echo $revisionHistory['FileUpload']['file_details'] .'.'. $revisionHistory['FileUpload']['file_type'] ?></td>
                            <td><?php echo $revisionHistory['FileUpload']['version'] ?></td>
                            <td><?php echo $revisionHistory['FileUpload']['modified'] ?></td>
                            <td><?php echo $revisionHistory['PreparedBy']['name'] ?></td>
                            <td><?php echo $revisionHistory['ApprovedBy']['name'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php }?>
                </div>
            </div>
        </div>
    </div>
	</div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#documentAmendmentRecordSheets_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['id'], 'ajax'), array('async' => true, 'update' => '#documentAmendmentRecordSheets_ajax'))); ?>
    <?php $this->Js->get('#add'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'lists', null, 'ajax'), array('async' => true, 'update' => '#documentAmendmentRecordSheets_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>

</div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

<h2><?php  echo __('Document Amendment Record Sheet'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
<!--
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php //echo __('Request From'); ?></td>
		<td>
			<?php //echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['request_from']); ?>
			&nbsp;
		</td></tr>
                -->
                <?php if($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['branch_id'] != -1){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Request From: Branch'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['Branch']['name']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['department_id'] != -1){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Department'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['Department']['name']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['employee_id'] != -1){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['Employee']['name']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['customer_id'] != -1){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Customer'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['Customer']['name']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['suggestion_form_id'] != -1){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Suggestion Form'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['SuggestionForm']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } elseif(!empty($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['others'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Others'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['others']); ?>
			&nbsp;
		</td></tr>
                <?php } ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Change Addition Deletion Request'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['ChangeAdditionDeletionRequest']['request_details']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['MasterListOfFormatID']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Document Number'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['document_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Issue Number'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['issue_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Revision Number'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['revision_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Revision Date'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['MasterListOfFormatID']['revision_date']); ?>
			&nbsp;
		</td></tr>


		<tr bgcolor="#FFFFFF"><td class="head-strong">
                        <strong><?php echo __('Document Details'); ?></strong></td>
                    <td><?php echo $documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['document_details']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong">
                        <strong><?php echo __('Work Instructions'); ?></strong></td>
                    <td><?php echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['work_instructions']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $documentAmendmentRecordSheet['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Amendment Details'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['amendment_details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Reason For Change'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['reason_for_change']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($documentAmendmentRecordSheet['DocumentAmendmentRecordSheet']['modified']); ?>
			&nbsp;
		</td></tr>
<!--
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php // echo __('Master List Of Format'); ?></td>
		<td>
			<?php // echo $documentAmendmentRecordSheet['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
-->
        </table>
        <p>&nbsp;</p>

        <h3><?php echo __("Current Document") ?></h3>
        <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
            <tr bgcolor="#FFFFFF">
                <th><?php echo __("Document Title") ?></th>
                <th><?php echo __("Document Number") ?></th>
                <th><?php echo __("Revision Number") ?></th>
                <th><?php echo __("Revision Date") ?></th>
                <th><?php echo __("Prepared By") ?></th>
                <th><?php echo __("Approved By") ?></th>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td><?php echo $firstDocument['MasterListOfFormat']['title'] ?></td>
                <td><?php echo $firstDocument['MasterListOfFormat']['document_number'] ?></td>
                <td><?php echo $firstDocument['MasterListOfFormat']['revision_number'] ?></td>
                <td><?php echo $firstDocument['MasterListOfFormat']['revision_date'] ?></td>
                <td><?php echo $firstDocument['PreparedBy']['name'] ?></td>
                <td><?php echo $firstDocument['ApprovedBy']['name'] ?></td>
            </tr>
        </table>
        <p>&nbsp;</p>

        <h3><?php echo __("Amendment History") ?></h3>
        <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
            <tr bgcolor="#FFFFFF">
                <th><?php echo __("Document Title") ?></th>
                <th><?php echo __("Document Number") ?></th>
                <th><?php echo __("Revision Number") ?></th>
                <th><?php echo __("Revision Date") ?></th>
                <th><?php echo __("Prepared By") ?></th>
                <th><?php echo __("Approved By") ?></th>
            </tr>
            <?php foreach($revisionHistorys as $revisionHistory): ?>
                <tr bgcolor="#FFFFFF">
                    <td><?php echo $this->Html->link($firstDocument['MasterListOfFormat']['title'],array('controller'=>'change_addition_deletion_requests','action'=>'view',$revisionHistory['ChangeAdditionDeletionRequest']['id'])) ?></td>
                    <td><?php echo $revisionHistory['DocumentAmendmentRecordSheet']['document_number'] ?></td>
                    <td><?php echo $revisionHistory['DocumentAmendmentRecordSheet']['revision_number'] ?></td>
                    <td><?php echo $revisionHistory['DocumentAmendmentRecordSheet']['revision_date'] ?></td>
                    <td><?php echo $revisionHistory['PreparedBy']['name'] ?></td>
                    <td><?php echo $revisionHistory['ApprovedBy']['name'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
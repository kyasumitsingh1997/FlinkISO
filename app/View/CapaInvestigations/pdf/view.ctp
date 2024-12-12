<h4>Corrective Preventive Actions</h4>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
	<tr bgcolor="#FFFFFF">
		<td width="20%"><?php echo __('CAPA Name'); ?></td>
		<td width="80%"><?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['name']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('CAPA Number'); ?></td>
		<td><?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['number']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('CAPA Source'); ?></td>
		<td><?php echo $correctivePreventiveAction['CapaSource']['name']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('CAPA Category'); ?></td>
		<td><?php echo $correctivePreventiveAction['CapaCategory']['name']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Category Name'); ?></td>
		<td>
			<?php
			if ($correctivePreventiveAction['CorrectivePreventiveAction']['internal_audit_id'] != '-1') {
			echo $correctivePreventiveAction['InternalAudit'][''];
			} elseif ($correctivePreventiveAction['CorrectivePreventiveAction']['suggestion_form_id'] != '-1') {
			echo $correctivePreventiveAction['SuggestionForm']['title'];
			} elseif ($correctivePreventiveAction['CorrectivePreventiveAction']['customer_complaint_id'] != '-1') {
			echo $correctivePreventiveAction['CustomerComplaint']['name'];
			} elseif ($correctivePreventiveAction['CorrectivePreventiveAction']['supplier_registration_id'] != '-1') {
			echo $correctivePreventiveAction['SupplierRegistration']['title'];
			} elseif ($correctivePreventiveAction['CorrectivePreventiveAction']['product_id'] != '-1') {
			echo $correctivePreventiveAction['Product']['name'];
			} elseif ($correctivePreventiveAction['CorrectivePreventiveAction']['device_id'] != '-1') {
			echo $correctivePreventiveAction['Device']['name'];
			} elseif ($correctivePreventiveAction['CorrectivePreventiveAction']['material_id'] != '-1') {
			echo $correctivePreventiveAction['Material']['name'];
			}
			?>&nbsp;
			</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Raised By'); ?></td>
		<td><?php $sorce = json_decode($correctivePreventiveAction['CorrectivePreventiveAction']['raised_by'], true); ?>&nbsp;
			<?php echo $this->Html->link($sorce['Soruce'], array('controller' => str_replace(' ', '_', Inflector::pluralize($sorce['Soruce'])), 'action' => 'view', $sorce['id'])); ?>&nbsp;
		</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Initial Remarks'); ?></td>
		<td><?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['initial_remarks']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Proposed Immediate Action'); ?></td>
		<td><?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['proposed_immidiate_action']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Root Cause Analysis Required'); ?></td>
		<td><?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['root_cause_analysis_required']) ? __('Yes') : __('No'); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Current Status'); ?></td>
		<td><?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['current_status'] ? __('Close') : __('Open'); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Document Changes Required'); ?></td>
		<td>
		<?php if($correctivePreventiveAction['CorrectivePreventiveAction']['document_changes_required'] == 1) {
			$docChangeReq = 'Yes';
				echo $docChangeReq;
			} else {
				$docChangeReq = 'No';
				echo $docChangeReq;
			}?>&nbsp;
		</td>
	</tr>
	<?php if($docChangeReq == 'Yes') { ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Master List of Format'); ?></td>
			<td><?php echo h($changeRequiredIn['MasterListOfFormat']['title']); ?>&nbsp;</td>
		</tr>
	<?php }?>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Closure Remarks'); ?></td>
		<td><?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['closure_remarks']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Prepared By'); ?></td>
		<td><?php echo h($correctivePreventiveAction['PreparedBy']['name']);?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Approved By'); ?></td>
		<td><?php echo h($correctivePreventiveAction['ApprovedBy']['name']);?>&nbsp;</td>
	</tr>
</table>
<h2><?php echo __('View Capa Investigation'); ?></h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td width="20%" class="head-strong"><?php echo __('Corrective Preventive Action'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CorrectivePreventiveAction']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['details']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Assigned To'); ?></td>
		<td>
			<?php echo h($capaInvestigation['Employee']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Target Date'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['target_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Proposed Action'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['proposed_action']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Completed On Date'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['completed_on_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Investigation Report'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['investigation_report']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Current Status'); ?></td>
		<td>
			 <?php echo $capaInvestigation['CapaInvestigation']['current_status'] ? __('Close') : __('Open'); ?>
                        &nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($capaInvestigation['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>

	<td><?php echo h($capaInvestigation['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		
</table>

<div id="capaInvestigations_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="capaInvestigations form col-md-8">
<h4><?php echo __('View Capa Investigation'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>
    <table class="table table-responsive">
                <tr><td width="40%"><?php echo __('CAPA Name'); ?></td>
                    <td>
                        <?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['name']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('CAPA Number'); ?></td>
                    <td>
                        <?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['number']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('CAPA Source'); ?></td>
                    <td>
                        <?php echo $this->Html->link($correctivePreventiveAction['CapaSource']['name'], array('controller' => 'capa_sources', 'action' => 'view', $correctivePreventiveAction['CapaSource']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('CAPA Category'); ?></td>
                    <td>
                        <?php echo $this->Html->link($correctivePreventiveAction['CapaCategory']['name'], array('controller' => 'capa_categories', 'action' => 'view', $correctivePreventiveAction['CapaCategory']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Category Name'); ?></td>
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
                <tr><td><?php echo __('Raised By'); ?></td>
                    <td>
                        <?php $sorce = json_decode($correctivePreventiveAction['CorrectivePreventiveAction']['raised_by'], true); ?>&nbsp;
                        <?php echo $this->Html->link($sorce['Soruce'], array('controller' => str_replace(' ', '_', Inflector::pluralize($sorce['Soruce'])), 'action' => 'view', $sorce['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
             
                <tr><td><?php echo __('Initial Remarks'); ?></td>
                    <td>
                        <?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['initial_remarks']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Proposed Immediate Action'); ?></td>
                    <td>
                        <?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['proposed_immidiate_action']); ?>
                        &nbsp;
                    </td>
                </tr>
              
                <tr><td><?php echo __('Root Cause Analysis Required'); ?></td>
                    <td>
                        <?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['root_cause_analysis_required']) ? __('Yes') : __('No'); ?>&nbsp;
                    </td>
                </tr>
              
                <tr><td><?php echo __('Current Status'); ?></td>
                    <td>
                        <?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['current_status'] ? __('Close') : __('Open'); ?>
                        &nbsp;
                    </td>
                </tr>

               
                <tr><td><?php echo __('Document Changes Required'); ?></td>
                    <td>
                        <?php
			    if($correctivePreventiveAction['CorrectivePreventiveAction']['document_changes_required'] == 1) {
				$docChangeReq = 'Yes';
				echo $docChangeReq;
			    } else {
				$docChangeReq = 'No';
				echo $docChangeReq;
			    }
			?>&nbsp;
                    </td>
                </tr>
		<?php if($docChangeReq == 'Yes') { ?>
                <tr><td><?php echo __('Master List of Format'); ?></td>
                    <td>
                        <?php echo h($changeRequiredIn['MasterListOfFormat']['title']); ?>&nbsp;
                    </td>
                </tr>
               
             
		<?php }?>
                   <tr><td><?php echo __('Closure Remarks'); ?></td>
                    <td>
                        <?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['closure_remarks']); ?>&nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($correctivePreventiveAction['PreparedBy']['name']);
                        ?>&nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($correctivePreventiveAction['ApprovedBy']['name']);
                        ?>&nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($correctivePreventiveAction['CorrectivePreventiveAction']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>&nbsp;
                </tr>
            </table>
            <h2><?php echo __('Investigation');?></h2>
<table class="table table-responsive">
		<tr><td width="40%"><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee Id'); ?></td>
		<td>
			<?php echo $this->Html->link($capaInvestigation['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaInvestigation['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Target Date'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['target_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Proposed Action'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['proposed_action']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Completed On Date'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['completed_on_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Investigation Report'); ?></td>
		<td>
			<?php echo h($capaInvestigation['CapaInvestigation']['investigation_report']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td>
			 <?php echo $capaInvestigation['CapaInvestigation']['current_status'] ? __('Close') : __('Open'); ?>
                        &nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($capaInvestigation['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($capaInvestigation['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($capaInvestigation['CapaInvestigation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($capaInvestigation['CapaInvestigation']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $capaInvestigation['CapaInvestigation']['created_by'], 'recordId' => $capaInvestigation['CapaInvestigation']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#capaInvestigations_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$capaInvestigation['CapaInvestigation']['id'] ,'ajax'),array('async' => true, 'update' => '#capaInvestigations_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

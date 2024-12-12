<h2><?php echo __($h2); ?> <small><?php echo $this->Html->link('Edit',array('controller'=>'corrective_preventive_actions','action'=>'edit',$capa['CorrectivePreventiveAction']['id']),array('class'=>'btn btn-sm btn-info')); ?></small></h2>
<!--- corrrective action started -->
<table class="table table-responsive">
   <tr>
      <td width="20%"><?php echo __('CAPA Name'); ?></td>
      <td><?php echo $capa['CorrectivePreventiveAction']['name']; ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('CAPA Number'); ?></td>
      <td><?php echo $capa['CorrectivePreventiveAction']['number']; ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('CAPA Source'); ?></td>
      <td><?php echo $this->Html->link($capa['CapaSource']['name'], array('controller' => 'capa_sources', 'action' => 'view', $capa['CapaSource']['id'])); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('CAPA Category'); ?></td>
      <td><?php echo $this->Html->link($capa['CapaCategory']['name'], array('controller' => 'capa_categories', 'action' => 'view', $capa['CapaCategory']['id'])); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Raised By'); ?></td>
      <td><?php $sorce = json_decode($capa['CorrectivePreventiveAction']['raised_by'], true); ?>&nbsp;
         <?php echo $this->Html->link($sorce['Soruce'], array('controller' => str_replace(' ', '_', Inflector::pluralize($sorce['Soruce'])), 'action' => 'view', $sorce['id'])); ?>&nbsp;
      </td>
   </tr>
   <tr>
      <td><?php echo __('Initial Remarks'); ?></td>
      <td><?php echo h($capa['CorrectivePreventiveAction']['initial_remarks']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Proposed Immediate Action'); ?></td>
      <td><?php echo h($capa['CorrectivePreventiveAction']['proposed_immidiate_action']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Root Cause Analysis Required'); ?></td>
      <td><?php echo h($capa['CorrectivePreventiveAction']['root_cause_analysis_required']) ? __('Yes') : __('No'); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Current Status'); ?></td>
      <td><?php echo $capa['CorrectivePreventiveAction']['current_status'] ? __('Close') : __('Open'); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Document Changes Required'); ?></td>
      <td><?php
         if($capa['CorrectivePreventiveAction']['document_changes_required'] == 1) {
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
   <tr>
      <td><?php echo __('Master List of Format'); ?></td>
      <td><?php echo h($changeRequiredIn['MasterListOfFormat']['title']); ?>&nbsp;</td>
   </tr>
   <?php }?>
   <tr>
      <td><?php echo __('Closure Remarks'); ?></td>
      <td><?php echo h($capa['CorrectivePreventiveAction']['closure_remarks']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Prepared By'); ?></td>
      <td><?php echo h($capa['PreparedBy']['name']);?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Approved By'); ?></td>
      <td><?php echo h($capa['ApprovedBy']['name']);?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Publish'); ?></td>
      <td><?php if ($capa['CorrectivePreventiveAction']['publish'] == 1) { ?>
         <span class="fa fa-check"></span>
         <?php } else { ?>
         <span class="fa fa-ban"></span>
         <?php } ?>&nbsp;
      </td>
   </tr>
</table>
<!-- CAPA Investigations -->
<?php 
   if($capa['CapaInvestigation']){ 
       echo "<h4>CAPA Investigations</h4>";
           foreach ($capa['CapaInvestigation'] as $capaInvestigation): ?>
<table class="table table-responsive">
   <tr>
      <td width="20%"><?php echo __('Details'); ?></td>
      <td><?php echo h($capaInvestigation['details']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Employee'); ?></td>
      <td><?php echo $PublishedEmployeeList[$capaInvestigation['employee_id']] ; ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Target Date'); ?></td>
      <td><?php echo h($capaInvestigation['target_date']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Proposed Action'); ?></td>
      <td><?php echo h($capaInvestigation['proposed_action']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Completed On Date'); ?></td>
      <td><?php echo h($capaInvestigation['completed_on_date']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Investigation Report'); ?></td>
      <td><?php echo h($capaInvestigation['investigation_report']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Current Status'); ?></td>
      <td><?php echo $capaInvestigation['current_status'] ? __('Close') : __('Open'); ?>&nbsp;</td>
   </tr>
</table>
<?php  endforeach; } ?>
<!-- CAPA Investigations cloased -->
<!-- CAPA Root Cause Analysis -->
<?php if($capa['CapaRootCauseAnalysi']){ 
   echo "<h4>Root Cause Analysis</h4>";
   foreach($capa['CapaRootCauseAnalysi'] as $capaRootCauseAnalysi): ?>
<table class="table table-responsive">
   <tr>
      <td width="20%"><?php echo __('Employee'); ?></td>
      <td><?php echo $PublishedEmployeeList[$capaRootCauseAnalysi['employee_id']] ; ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Root Cause Details'); ?></td>
      <td><?php echo h($capaRootCauseAnalysi['root_cause_details']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Determined By'); ?></td>
      <td><?php echo h($PublishedEmployeeList[$capaRootCauseAnalysi['determined_by']]); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Determined On Date'); ?></td>
      <td><?php echo h($capaRootCauseAnalysi['determined_on_date']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Root Cause Remarks'); ?></td>
      <td><?php echo h($capaRootCauseAnalysi['root_cause_remarks']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Proposed Action'); ?></td>
      <td><?php echo h($capaRootCauseAnalysi['proposed_action']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Action Assigned To'); ?></td>
      <td><?php echo h($PublishedEmployeeList[$capaRootCauseAnalysi['action_assigned_to']]); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Action Completed On Date'); ?></td>
      <td><?php echo h($capaRootCauseAnalysi['action_completed_on_date']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Action Completion Remarks'); ?></td>
      <td><?php echo h($capaRootCauseAnalysi['action_completion_remarks']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Effectiveness'); ?></td>
      <td><?php echo h($capaRootCauseAnalysi['effectiveness']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Closure Remarks'); ?></td>
      <td><?php echo h($capaRootCauseAnalysi['closure_remarks']); ?>&nbsp;</td>
   </tr>
   <tr>
      <td><?php echo __('Current Status'); ?></td>
      <td> <?php echo $capaRootCauseAnalysi['current_status'] ? __('Close') : __('Open'); ?>&nbsp;</td>
   </tr>
</table>
<?php endforeach; } ?>
<!-- corrective action ended -->

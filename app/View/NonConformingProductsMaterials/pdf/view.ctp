<h4><?php echo __('View Non Conforming Report'); ?></h4>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">    
    <tr bgcolor="#FFFFFF"><td width="20%"><?php echo __('Name'); ?></td>
        <td>
            <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['title']); ?>
            &nbsp;
        </td>
    </tr>
  
    <?php if(isset($nonConformingProductsMaterial['Material']['name'])) {?>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Material'); ?></td>
        <td>
            <?php echo $nonConformingProductsMaterial['Material']['name']?>
            &nbsp;
        </td>
    </tr>
    <?php } ?>
    <?php if(isset($nonConformingProductsMaterial['Product']['name'])){ ?>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Product'); ?></td>
        <td>
            <?php echo $nonConformingProductsMaterial['Product']['name']?>
            &nbsp;
        </td>
    </tr>
    <?php } ?>
    <?php if(isset($nonConformingProductsMaterial['Process']['title'])){ ?>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Process'); ?></td>
        <td>
            <?php echo $nonConformingProductsMaterial['Process']['title']?>
            &nbsp;
        </td>
    </tr>
    <?php } ?>
    <?php if(isset($nonConformingProductsMaterial['RiskAssessment']['title'])){ ?>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Risk'); ?></td>
        <td>
            <?php echo $nonConformingProductsMaterial['RiskAssessment']['title']?>
            &nbsp;
        </td>
    </tr>
    <?php } ?>
     <tr bgcolor="#FFFFFF"><td><?php echo __('Date'); ?></td>
        <td>
            <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['non_confirmity_date']); ?>
            &nbsp;
        </td>
    </tr>
     <tr bgcolor="#FFFFFF"><td><?php echo __('Violation Of Section'); ?></td>
        <td>
            <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['violation_of_section']); ?>
            &nbsp;
        </td>
    </tr>
      <tr bgcolor="#FFFFFF"><td><?php echo __('Details'); ?></td>
        <td>
            <?php echo h($nonConformingProductsMaterial['NonConformingProductsMaterial']['details']); ?>
            &nbsp;
        </td>
    </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Recorded By'); ?></td>
        <td>
            <?php echo h($PublishedEmployeeList[$nonConformingProductsMaterial['NonConformingProductsMaterial']['reported_by']]); ?>
            &nbsp;
        </td>
    </tr>
     <tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
        <td>
            <?php echo h($PublishedDepartmentList[$nonConformingProductsMaterial['NonConformingProductsMaterial']['department_id']]); ?>
            &nbsp;
        </td>
    </tr>
    
    <tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
        <td>
            <?php echo h($nonConformingProductsMaterial['PreparedBy']['name']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
        <td>
            <?php echo h($nonConformingProductsMaterial['ApprovedBy']['name']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Publish'); ?></td>
        <td>
            <?php if ($nonConformingProductsMaterial['NonConformingProductsMaterial']['publish'] == 1) { ?>
                <span class="fa fa-check"></span>
            <?php } else { ?>
                <span class="fa fa-ban"></span>
            <?php } ?>&nbsp;</td>
        &nbsp;
    </tr>
</table>
<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>
<h3><?php echo  __('Corrective Preventive Action'); ?></h3>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
    <tr bgcolor="#FFFFFF"><td width="20%"><?php echo __('CAPA Name'); ?></td>
        <td>
            <?php echo $correctiveActions['CorrectivePreventiveAction']['name']; ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('CAPA Number'); ?></td>
        <td>
            <?php echo $correctiveActions['CorrectivePreventiveAction']['number']; ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('CAPA Source'); ?></td>
        <td>
            <?php echo $correctiveActions['CapaSource']['name']; ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('CAPA Category'); ?></td>
        <td>
            <?php echo $correctiveActions['CapaCategory']['name']; ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Details'); ?></td>
        <td>
            <?php
                if ($correctiveActions['CorrectivePreventiveAction']['internal_audit_id']) {
                    echo "Internal Audit :" . $correctiveActions['InternalAudit'][''].'<br />';
                } 

                if ($correctiveActions['CorrectivePreventiveAction']['suggestion_form_id']) {
                    echo "Suggestions :" .  $correctiveActions['SuggestionForm']['title'].'<br />';
                } 

                if ($correctiveActions['CorrectivePreventiveAction']['customer_complaint_id']) {
                    echo "Customer Complaints :" .  $correctiveActions['CustomerComplaint']['name'].'<br />';
                } 

                if ($correctiveActions['CorrectivePreventiveAction']['supplier_registration_id']) {
                    echo "Suppliers :" .  $correctiveActions['SupplierRegistration']['title'].'<br />';
                } 

                if ($correctiveActions['CorrectivePreventiveAction']['product_id']) {
                    echo "Product :" .  $correctiveActions['Product']['name'].'<br />';
                } 

                if ($correctiveActions['CorrectivePreventiveAction']['device_id']) {
                    echo "Device :" .  $correctiveActions['Device']['name'].'<br />';
                } 

                if ($correctiveActions['CorrectivePreventiveAction']['material_id']) {
                    echo "Material :" .  $correctiveActions['Material']['name'].'<br />';
                } 

                if ($correctiveActions['CorrectivePreventiveAction']['process_id']) {
                    echo "Process :" .  $correctiveActions['Process']['title'].'<br />';
                }

                if ($correctiveActions['CorrectivePreventiveAction']['risk_assessment_id']) {
                    echo "Risk :" .  $correctiveActions['RiskAssessment']['title'].'<br />';
                }
            ?>&nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Raised By'); ?></td>
        <td>
            <?php $sorce = json_decode($correctiveActions['CorrectivePreventiveAction']['raised_by'], true); ?>&nbsp;
            <?php echo $sorce['Soruce']; ?>
            &nbsp;
        </td>
    </tr>
 
    <tr bgcolor="#FFFFFF"><td><?php echo __('Initial Remarks'); ?></td>
        <td>
            <?php echo h($correctiveActions['CorrectivePreventiveAction']['initial_remarks']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Proposed Immediate Action'); ?></td>
        <td>
            <?php echo h($correctiveActions['CorrectivePreventiveAction']['proposed_immidiate_action']); ?>
            &nbsp;
        </td>
    </tr>
  
    <tr bgcolor="#FFFFFF"><td><?php echo __('Root Cause Analysis Required'); ?></td>
        <td>
            <?php echo h($correctiveActions['CorrectivePreventiveAction']['root_cause_analysis_required']) ? __('Yes') : __('No'); ?>&nbsp;
        </td>
    </tr>
  
    <tr bgcolor="#FFFFFF"><td><?php echo __('Current Status'); ?></td>
        <td>
            <?php echo $correctiveActions['CorrectivePreventiveAction']['current_status'] ? __('Close') : __('Open'); ?>
            &nbsp;
        </td>
    </tr>

   
    <tr bgcolor="#FFFFFF"><td><?php echo __('Document Changes Required'); ?></td>
        <td>
            <?php
            if($correctiveActions['CorrectivePreventiveAction']['document_changes_required'] == 1) {
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
    <tr bgcolor="#FFFFFF"><td><?php echo __('Master List of Format'); ?></td>
        <td>
            <?php echo h($changeRequiredIn['MasterListOfFormat']['title']); ?>&nbsp;
        </td>
    </tr>
   <?php }?>
   <tr bgcolor="#FFFFFF"><td><?php echo __('Closure Remarks'); ?></td>
        <td>
            <?php echo h($correctiveActions['CorrectivePreventiveAction']['closure_remarks']); ?>&nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
        <td>
            <?php echo h($correctiveActions['PreparedBy']['name']);
            ?>&nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
        <td>
            <?php echo h($correctiveActions['ApprovedBy']['name']);
            ?>&nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Publish'); ?></td>
        <td>
            <?php if ($correctiveActions['CorrectivePreventiveAction']['publish'] == 1) { ?>
                <span class="fa fa-check"></span>
            <?php } else { ?>
                <span class="fa fa-ban"></span>
            <?php } ?>&nbsp;</td>&nbsp;
    </tr>
</table>

<!-- CAPA Investigations -->
<?php 
   if($correctiveActions['CapaInvestigation']){ 
      echo "<div style='page-break-after:always'><span style='display:none'>&nbsp;</span></div><h4>CAPA Investigations</h4>";
      foreach ($correctiveActions['CapaInvestigation'] as $capaInvestigation): ?>
         <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
            <tr bgcolor="#FFFFFF"><td width="20%"><?php echo __('Details'); ?></td>
               <td><?php echo h($capaInvestigation['details']); ?>&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
               <td><?php echo __('Employee'); ?></td>
               <td><?php echo $PublishedEmployeeList[$capaInvestigation['employee_id']] ; ?>&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
               <td><?php echo __('Target Date'); ?></td>
               <td><?php echo h($capaInvestigation['target_date']); ?>&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
               <td><?php echo __('Proposed Action'); ?></td>
               <td><?php echo h($capaInvestigation['proposed_action']); ?>&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
               <td><?php echo __('Completed On Date'); ?></td>
               <td><?php echo h($capaInvestigation['completed_on_date']); ?>&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
               <td><?php echo __('Investigation Report'); ?></td>
               <td><?php echo nl2br($capaInvestigation['investigation_report']); ?>&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
               <td><?php echo __('Current Status'); ?></td>
               <td><?php echo $capaInvestigation['current_status'] ? __('Close') : __('Open'); ?>&nbsp;</td>
            </tr>
         </table>
      <?php  endforeach; } ?>
<!-- CAPA Investigations cloased -->

<!-- CAPA Root Cause Analysis -->
      <?php if($correctiveActions['CapaRootCauseAnalysi']){ 
         echo "<div style='page-break-after:always'><span style='display:none'>&nbsp;</span></div><h4>Root Cause Analysis</h4>";
         foreach($correctiveActions['CapaRootCauseAnalysi'] as $capaRootCauseAnalysi): 
         ?>
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
               <tr bgcolor="#FFFFFF">
                  <td width="20%"><?php echo __('Employee'); ?></td>
                  <td><?php echo $PublishedEmployeeList[$capaRootCauseAnalysi['employee_id']] ; ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Root Cause Details'); ?></td>
                  <td><?php echo h($capaRootCauseAnalysi['root_cause_details']); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Determined By'); ?></td>
                  <td><?php echo h($PublishedEmployeeList[$capaRootCauseAnalysi['determined_by']]); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Determined On Date'); ?></td>
                  <td><?php echo h($capaRootCauseAnalysi['determined_on_date']); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Root Cause Remarks'); ?></td>
                  <td><?php echo h($capaRootCauseAnalysi['root_cause_remarks']); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Proposed Action'); ?></td>
                  <td><?php echo h($capaRootCauseAnalysi['proposed_action']); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Action Assigned To'); ?></td>
                  <td><?php echo h($PublishedEmployeeList[$capaRootCauseAnalysi['action_assigned_to']]); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Action Completed On Date'); ?></td>
                  <td><?php echo h($capaRootCauseAnalysi['action_completed_on_date']); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Action Completion Remarks'); ?></td>
                  <td><?php echo h($capaRootCauseAnalysi['action_completion_remarks']); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Effectiveness'); ?></td>
                  <td><?php echo h($capaRootCauseAnalysi['effectiveness']); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Closure Remarks'); ?></td>
                  <td><?php echo h($capaRootCauseAnalysi['closure_remarks']); ?>&nbsp;</td>
               </tr>
               <tr bgcolor="#FFFFFF">
                  <td><?php echo __('Current Status'); ?></td>
                  <td> <?php echo $capaRootCauseAnalysi['current_status'] ? __('Close') : __('Open'); ?>&nbsp;</td>
               </tr>
            </table> 
         <?php endforeach; } ?>
   
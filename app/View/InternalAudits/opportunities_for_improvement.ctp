<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>   
    <div class="internalAudits ">
<!--         <?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Internal Audits','modelClass'=>'InternalAudit','options'=>array("sr_no"=>"Sr No","section"=>"Section","start_time"=>"Start Time","end_time"=>"End Time","clauses"=>"Clauses","question_asked"=>"Question Asked","finding"=>"Finding","opportunities_for_improvement"=>"Opportunities For Improvement","non_conformity_found"=>"Non Conformity Found","current_status"=>"Current Status","employeeId"=>"EmployeeId","target_date"=>"Target Date","notes"=>"Notes"),'pluralVar'=>'internalAudits'))); ?> -->

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
    var url = $(this).attr("href");
    $('#main').load(url);
    return false;
});
});
</script>   
        <div class="table-responsive">
            <h3><?php echo __('Opportunities for improvement'); ?></h3>
        <?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>             
            <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>
                    <th ><input type="checkbox" id="selectAll"></th>
                    
                <!-- <th><?php echo $this->Paginator->sort('internal_audit_plan_id'); ?></th>
                <th><?php echo $this->Paginator->sort('internal_audit_plan_department_id'); ?></th>
                <th><?php echo $this->Paginator->sort('department_id'); ?></th>
                <th><?php echo $this->Paginator->sort('branch_id'); ?></th> -->
                <th><?php echo $this->Paginator->sort('section'); ?></th>
                <!-- <th><?php echo $this->Paginator->sort('start_time'); ?></th>
                <th><?php echo $this->Paginator->sort('end_time'); ?></th>
                <th><?php echo $this->Paginator->sort('list_of_trained_internal_auditor_id'); ?></th>
                <th><?php echo $this->Paginator->sort('employee_id'); ?></th> -->
                <th><?php echo $this->Paginator->sort('clauses'); ?></th>
                <!-- <th><?php echo $this->Paginator->sort('question_asked'); ?></th>-->
                <th><?php echo $this->Paginator->sort('finding'); ?></th> 
                <th><?php echo $this->Paginator->sort('opportunities_for_improvement'); ?></th>
                <!-- <th><?php echo $this->Paginator->sort('non_conformity_found'); ?></th> -->
                <th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
                <th><?php echo $this->Paginator->sort('current_status'); ?></th>
                <th><?php echo $this->Paginator->sort('employeeId'); ?></th>
                <th><?php echo $this->Paginator->sort('target_date'); ?></th>
                <!-- <th><?php echo $this->Paginator->sort('notes'); ?></th>
                <th><?php echo $this->Paginator->sort('division_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>       
                    <th><?php echo $this->Paginator->sort('approved_by'); ?></th>        -->
                    <th><?php echo $this->Paginator->sort('publish'); ?></th>       

                
                </tr>
                <?php if($internalAudits){ ?>
<?php foreach ($internalAudits as $internalAudit): ?>
    <tr>
    <td class=" actions">   <?php echo $this->element('actions', array('created' => $internalAudit['InternalAudit']['created_by'], 'postVal' => $internalAudit['InternalAudit']['id'], 'softDelete' => $internalAudit['InternalAudit']['soft_delete'])); ?> </td>       
    <!--<td>
            <?php echo $this->Html->link($internalAudit['InternalAuditPlan']['title'], array('controller' => 'internal_audit_plans', 'action' => 'view', $internalAudit['InternalAuditPlan']['id'])); ?>
        </td>
         <td>
            <?php echo $this->Html->link($internalAudit['InternalAuditPlanDepartment']['id'], array('controller' => 'internal_audit_plan_departments', 'action' => 'view', $internalAudit['InternalAuditPlanDepartment']['id'])); ?>
        </td>
        <td>
            <?php echo $this->Html->link($internalAudit['Department']['name'], array('controller' => 'departments', 'action' => 'view', $internalAudit['Department']['id'])); ?>
        </td>
        <td>
            <?php echo $this->Html->link($internalAudit['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $internalAudit['Branch']['id'])); ?>
        </td> -->
        <td><?php echo h($internalAudit['InternalAudit']['section']); ?>&nbsp;</td>
        <!-- <td><?php echo h($internalAudit['InternalAudit']['start_time']); ?>&nbsp;</td>
        <td><?php echo h($internalAudit['InternalAudit']['end_time']); ?>&nbsp;</td>
        <td>
            <?php echo $this->Html->link($internalAudit['ListOfTrainedInternalAuditor']['id'], array('controller' => 'list_of_trained_internal_auditors', 'action' => 'view', $internalAudit['ListOfTrainedInternalAuditor']['id'])); ?>
        </td>
        <td>
            <?php echo $this->Html->link($internalAudit['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $internalAudit['Employee']['id'])); ?>
        </td> -->
        <td><?php echo h($internalAudit['InternalAudit']['clauses']); ?>&nbsp;</td>
        <!-- <td><?php echo h($internalAudit['InternalAudit']['question_asked']); ?>&nbsp;</td>-->
        <td><?php echo h($internalAudit['InternalAudit']['finding']); ?>&nbsp;</td> 
        <td><?php echo h($internalAudit['InternalAudit']['opportunities_for_improvement']); ?>&nbsp;</td>
        <!-- <td><?php echo h($internalAudit['InternalAudit']['non_conformity_found']); ?>&nbsp;</td> -->
        <td>
            <?php echo $this->Html->link($internalAudit['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $internalAudit['CorrectivePreventiveAction']['id'])); ?>
        </td>
        <td><?php echo h($internalAudit['InternalAudit']['current_status']); ?>&nbsp;</td>
        <td><?php echo h($internalAudit['EmployeeId']['name']); ?>&nbsp;</td>
        <td><?php echo h($internalAudit['InternalAudit']['target_date']); ?>&nbsp;</td>
        <!-- <td><?php echo h($internalAudit['InternalAudit']['notes']); ?>&nbsp;</td>
        <td><?php echo h($internalAudit['InternalAudit']['division_id']); ?>&nbsp;</td>
        <td><?php echo h($PublishedEmployeeList[$internalAudit['InternalAudit']['prepared_by']]); ?>&nbsp;</td>
        <td><?php echo h($PublishedEmployeeList[$internalAudit['InternalAudit']['approved_by']]); ?>&nbsp;</td>
 -->
        <td width="60">
            <?php if($internalAudit['InternalAudit']['publish'] == 1) { ?>
            <span class="fa fa-check"></span>
            <?php } else { ?>
            <span class="fa fa-ban"></span>
            <?php } ?>&nbsp;</td>
    </tr>
<?php endforeach; ?>
<?php }else{ ?>
    <tr><td colspan=111>No results found</td></tr>
<?php } ?>
            </table>
<?php echo $this->Form->end();?>            
        </div>
            <p>
            <?php
            echo $this->Paginator->options(array(
            'update' => '#main',
            'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
            ));
            
            echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
            ));
            ?>          </p>
            <ul class="pagination">
            <?php
        echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
        echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
        echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
    ?>
            </ul>
        </div>
    </div>
    </div>  

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","section"=>"Section","start_time"=>"Start Time","end_time"=>"End Time","clauses"=>"Clauses","question_asked"=>"Question Asked","finding"=>"Finding","opportunities_for_improvement"=>"Opportunities For Improvement","non_conformity_found"=>"Non Conformity Found","current_status"=>"Current Status","employeeId"=>"EmployeeId","target_date"=>"Target Date","notes"=>"Notes"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","section"=>"Section","start_time"=>"Start Time","end_time"=>"End Time","clauses"=>"Clauses","question_asked"=>"Question Asked","finding"=>"Finding","opportunities_for_improvement"=>"Opportunities For Improvement","non_conformity_found"=>"Non Conformity Found","current_status"=>"Current Status","employeeId"=>"EmployeeId","target_date"=>"Target Date","notes"=>"Notes"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

<div id="internalAudits_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="internalAudits form col-md-8">
            <h4><?php echo __('Internal Audit Report data'); ?>
                <?php echo $this->Html->link(__('List'), array('controller' => 'internal_audit_plans', 'action' => 'index'), array('class' => 'label btn-info')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
        <table class="table table-responsive">
                <tr><td><?php echo __('Standard'); ?></td>
                    <td><?php echo $internalAuditPlan['Standard']['name']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Title'); ?></td>
                    <td><?php echo $internalAuditPlan['InternalAuditPlan']['title']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Audit Date'); ?></td>
                    <td>From : <?php echo $internalAuditPlan['InternalAuditPlan']['schedule_date_from']; ?> To : <?php echo $internalAuditPlan['InternalAuditPlan']['schedule_date_to']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Note'); ?></td>
                    <td><?php echo html_entity_decode($internalAuditPlan['InternalAuditPlan']['note']); ?>
                        &nbsp;
                    </td>
                </tr>
            </table>

        <?php 
        $opps = $ncs = $capas = 0;
            foreach ($audits as $internalAudit) { 
                if($internalAudit['InternalAudit']['opportunities_for_improvement'])$opps = $opps + 1;
                if($internalAudit['InternalAudit']['non_conformity_found'])$ncs = $ncs + 1;
                if($internalAudit['CorrectivePreventiveAction']['name'])$capas = $capas + 1;
        } ?>

        <table class="table table-responsive table-bordered">
            <tr  class="success">
                <td><strong>Opportunities For Improvement</strong></td>
                <td><strong>NCs Found</strong></td>
                <td><strong>CAPA Raised</strong></td>
            </tr>
            <tr>
                <td><?php echo $opps ?></td>
                <td><?php echo $ncs?></td>
                <td><?php echo $capas?></td>
            </tr>
        </table>
        <h3>Audit Findings</h3>
        <?php $i = 1;?>
        <?php foreach ($audits as $internalAudit) { ?>
            <table class="table table-responsive table-bordered">
                <tr>
                    <td rowspan="12"><span class="badge"><?php echo $i;?></span></td>
                    <td width="35%"><strong><?php echo __('Department'); ?></strong></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['Department']['name'], array('controller' => 'departments', 'action' => 'view', $internalAudit['Department']['id'])); ?>
                        &nbsp;
                    </td>
                <td width="35%"><strong><?php echo __('Section'); ?></strong></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['section']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><strong><?php echo __('Start Time'); ?></strong></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['start_time']); ?>
                        &nbsp;
                    </td>
                <td><strong><?php echo __('End Time'); ?></strong></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['end_time']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><strong><?php echo __('Auditor'); ?></strong></td>
                    <td>
                        <?php echo h($PublishedEmployeeList[$internalAudit['ListOfTrainedInternalAuditor']['employee_id']]); ?>
                        &nbsp;
                    </td>
                <td><strong><?php echo __('Auditee'); ?></strong></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $internalAudit['Employee']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><strong><?php echo __('Branch'); ?></strong></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $internalAudit['BranchIds']['id'])); ?>
                        &nbsp;
                    </td>
                <td><strong><?php echo __('Department'); ?></strong></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $internalAudit['DepartmentIds']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><strong><?php echo __('Question asked'); ?></strong></td>
                    <td  colspan="3">
                        <?php echo h($internalAudit['InternalAudit']['question_asked']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><strong><?php echo __('Findings'); ?></strong></td>
                    <td colspan="3">
                        <?php echo ($internalAudit['InternalAudit']['finding']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><strong><?php echo __('Opportunities For Improvement'); ?></strong></td>
                    <td  colspan="3">
                        <?php if($internalAudit['InternalAudit']['opportunities_for_improvement'] == ''){
                            echo "N/A";
                        }else{
                            echo $internalAudit['InternalAudit']['opportunities_for_improvement'];
                        } ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><strong><?php echo __('Non Conformity Found'); ?></strong></td>
                    <td  colspan="3">
                        <?php echo ($internalAudit['InternalAudit']['non_conformity_found']) ? "Yes" : "No"; ?>
                        &nbsp;
                    </td>
                </tr>
                <?php if($internalAudit['InternalAudit']['non_conformity_found']){ ?>
                <tr><td><strong><?php echo __('Corrective Preventive Action'); ?></strong></td>
                    <td>
                        <?php echo h($internalAudit['CorrectivePreventiveAction']['name']); ?>
                        &nbsp;
                    </td>
                <td><strong><?php echo __('Current Status'); ?></strong></td>
                    <td>
                        <strong><?php echo ($internalAudit['InternalAudit']['current_status'])? "Open" : "Close"; ?></strong>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><strong><?php echo __('Target Date'); ?></strong></td>
                    <td  colspan="3">
                        <?php echo h($internalAudit['InternalAudit']['target_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                 <?php } ?>
                <tr><td><strong><?php echo __('Notes'); ?></strong></td>
                    <td  colspan="4">
                        <?php echo ($internalAudit['InternalAudit']['notes']); ?>
                        &nbsp;
                    </td>
                </tr>                
                <tr>                    
                    <td colspan="4">
                    <?php foreach ($internalAudit['CAPA'] as $capa) {
                        if($capa)echo $this->element('capanc',array('correctivePreventiveAction'=>$capa));
                    }?>
                    </td>
                </tr>
            </table>
            <?php // echo $this->element('upload-edit', array('usersId' => $internalAudit['InternalAudit']['created_by'], 'recordId' => $internalAudit['InternalAudit']['id'])); ?>
        <?php $i++ ; } ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

<div id="internalAudits_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="internalAudits form col-md-8">
            <h4><?php echo __('Internal Audit'); ?>
                <?php echo $this->Html->link(__('List'), array('controller' => 'internal_audit_plans', 'action' => 'index'), array('class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), array('action' => 'edit_popup', $internalAudit['InternalAudit']['id']), array('class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Add'), array('action' => 'lists', $internalAudit['InternalAuditPlan']['id']), array('class' => 'label btn-info')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td><?php echo __('Internal Audit Plan'); ?></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['InternalAuditPlan']['title'], array('controller' => 'internal_audit_plans', 'action' => 'view', $internalAudit['InternalAuditPlan']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['Department']['name'], array('controller' => 'departments', 'action' => 'view', $internalAudit['Department']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Section'); ?></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['section']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Start Time'); ?></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['start_time']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('End Time'); ?></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['end_time']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('List Of Trained Internal Auditor'); ?></td>
                    <td>
                        <?php echo h($PublishedEmployeeList[$internalAudit['ListOfTrainedInternalAuditor']['employee_id']]); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Employee'); ?></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $internalAudit['Employee']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $internalAudit['BranchIds']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo $this->Html->link($internalAudit['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $internalAudit['DepartmentIds']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Question asked'); ?></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['question_asked']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Findings'); ?></td>
                    <td>
                        <?php echo ($internalAudit['InternalAudit']['finding']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Opportunities For Improvement'); ?></td>
                    <td>
                        <?php echo ($internalAudit['InternalAudit']['opportunities_for_improvement']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Non Conformity Found'); ?></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['non_conformity_found']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Corrective Preventive Action'); ?></td>
                    <td>
                        <?php echo h($internalAudit['CorrectivePreventiveAction']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Current Status'); ?></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['current_status']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Target Date'); ?></td>
                    <td>
                        <?php echo h($internalAudit['InternalAudit']['target_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Notes'); ?></td>
                    <td>
                        <?php echo ($internalAudit['InternalAudit']['notes']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($internalAudit['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($internalAudit['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($internalAudit['InternalAudit']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
            </table>
            <?php echo $this->element('upload-edit', array('usersId' => $internalAudit['InternalAudit']['created_by'], 'recordId' => $internalAudit['InternalAudit']['id'])); ?>
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

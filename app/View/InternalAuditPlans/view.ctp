<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<script type="text/javascript">
    $().ready(function () {
    
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             //if($('#InternalAuditPlanViewForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
             //}else{
               //  $("#submit_id").removeAttr("disabled");
                 //$("#submit-indicator").hide();
             }

        );
    });

</script>
<div id="internalAuditPlans_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="internalAuditPlans form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Internal Audit Plan'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
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

            <?php foreach ($PublishedBranchList as $key => $value): ?>
                <?php if(count($plan[$key]) > 0){ ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo $this->Html->link($value . " <span class='badge btn-info pull-right'>" . count($plan[$key]) . "</span>", '#' . $key, array('data-toggle' => 'tab', 'escape' => false)); ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-responsive">
                            <tr>
                                <th></th>
                                <th><?php echo __('Department'); ?></th>
                                <th><?php echo __('Clauses'); ?></th>
                                <th><?php echo __('Auditee'); ?></th>
                                <th><?php echo __('Auditor'); ?></th>
                                <th><?php echo __('Schedule'); ?></th>
                                <th><?php echo __('Action'); ?></th>
                            </tr>
                            <?php
                                $i = 1;
                                foreach ($plan[$key] as $finalPlan):
                            ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $finalPlan['Department']['name']; ?>&nbsp;</td>
                                <td><?php echo $finalPlan['InternalAuditPlanDepartment']['clauses']; ?>&nbsp;</td>
                                <td><?php echo $finalPlan['Employee']['name']; ?>&nbsp;</td>
                                <td><?php echo $PublishedEmployeeList[$finalPlan['ListOfTrainedInternalAuditor']['employee_id']]; ?>&nbsp;</td>
                                <td>From : <?php echo $finalPlan['InternalAuditPlanDepartment']['start_time'] ?><br /> To : <?php echo $finalPlan['InternalAuditPlanDepartment']['end_time'] ?>
                                    &nbsp;
                                </td>
                                <td><?php echo $this->Form->input(__('Edit'), array('type' => 'button', 'label' => FALSE, 'class' => 'btn btn-xs btn-info', 'onClick' => "editModal('".$finalPlan['InternalAuditPlanDepartment']['id']."')")); ?>
                                <?php echo $this->Html->image('indicator.gif', array('id' => "modalInd-{$finalPlan['InternalAuditPlanDepartment']['id']}", 'style' => 'display: none;')); ?>
                                </td>
                            </tr>
                            <div id="editModal<?php echo $finalPlan['InternalAuditPlanDepartment']['id']; ?>"></div>
                            <?php
                                $i++;
                                endforeach;
                            ?>
                        </table>
                    </div>
                </div>
                <?php } ?>
            <?php endforeach ?>

<script>
function editModal(edit){
    $('#modalInd-'+edit).show();
    $('#editModal'+edit).load('<?php echo Router::url('/', true); ?>internal_audit_plan_departments/edit/' + edit, function(response, status, xhr){
        $('#modalInd-'+edit).hide();
    });
}
</script>

            <?php echo $this->Form->create('InternalAuditPlan', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->hidden('id', array('value' => $internalAuditPlan['InternalAuditPlan']['id'])); ?>
            <?php echo $this->Form->hidden('title', array('value' => $internalAuditPlan['InternalAuditPlan']['title'])); ?>
            <?php echo $this->Form->hidden('schedule_date_from', array('value' => $internalAuditPlan['InternalAuditPlan']['schedule_date_from'])); ?>
            <?php echo $this->Form->hidden('schedule_date_to', array('value' => $internalAuditPlan['InternalAuditPlan']['schedule_date_to'])); ?>
            <?php echo $this->Form->hidden('note', array('value' => html_entity_decode($internalAuditPlan['InternalAuditPlan']['note']))); ?>
            <?php echo $this->Form->hidden('notify_note', array('value' => strip_tags(html_entity_decode($internalAuditPlan['InternalAuditPlan']['note'])))); ?>
            <?php echo $this->Form->input('show_on_timeline', array('type' => 'checkbox', 'label' => __('Show on Timeline'), 'default' => $internalAuditPlan['InternalAuditPlan']['show_on_timeline'])); ?>
            <?php echo $this->Form->input('notify_users', array('type' => 'checkbox', 'label' => __('Notify Users'), 'default' => $internalAuditPlan['InternalAuditPlan']['notify_users'])); ?>
            <?php echo $this->Form->input('notify_users_emails', array('type' => 'checkbox', 'label' => __('Notify Users Via Emails'), 'default' => $internalAuditPlan['InternalAuditPlan']['notify_users_emails'])); ?>

            <?php
                if (isset($showApprovals) && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish'), 'default' => $internalAuditPlan['InternalAuditPlan']['publish']));
                    echo '<div class="clearfix">&nbsp;</div>';
               }
            ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
            echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->element('upload-edit', array('usersId' => $internalAuditPlan['InternalAuditPlan']['created_by'], 'recordId' => $internalAuditPlan['InternalAuditPlan']['id'])); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    
</div>

<script>
    $(".edit").click(function() {
        $('#open_edit').load($(this).attr('data'))
    });
</script>

<div id="open_edit"></div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#submit-indicator").show();
        }, complete: function() {
            $("#submit-indicator").hide();
        }
    });
</script>

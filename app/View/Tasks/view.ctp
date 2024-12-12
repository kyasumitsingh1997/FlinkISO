<div id="tasks_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="tasks form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Task'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->link(__('Add'), '#add', array('id' => 'add', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td><?php echo __('Sr. No'); ?></td>
                    <td>
                        <?php echo h($task['Task']['sr_no']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Name'); ?></td>
                    <td>
                        <?php echo h($task['Task']['name']); ?>
                        &nbsp;
                        <?php
                            if(isset($task['Task']['revised_due_date'])){
                                if(($task['Task']['task_status'] == 0 && $task['Task']['revised_due_date'] < date('Y-m-d')) || $task['Task']['rag_status'] == 0)$rag_class = 'danger';
                                elseif(($task['Task']['task_status'] == 0 && $task['Task']['revised_due_date'] > date('Y-m-d')) || $task['Task']['rag_status'] == 1) $rag_class = 'warning';
                                // elseif($task['Task']['task_status'] == 1 && $task['Task']['end_date'] < date('Y-m-d')) $rag_class = 'success';
                                else $rag_class = 'success';   
                            }else{
                                if(($task['Task']['task_status'] == 0 && $task['Task']['end_date'] < date('Y-m-d')) || $task['Task']['rag_status'] == 0) $rag_class = 'danger';
                                elseif(($task['Task']['task_status'] == 0 && $task['Task']['end_date'] > date('Y-m-d')) || $task['Task']['rag_status'] == 1)$rag_class = 'warning';
                                // elseif($task['Task']['task_status'] == 1 && $task['Task']['end_date'] < date('Y-m-d')) $rag_class = 'success';
                                else $rag_class = 'success';   
                            }
                        ?>
                        <span class="label btn-<?php echo $rag_class;?> pull-right">&nbsp;&nbsp;</span>
                    </td>
                </tr>
                <tr><td><?php echo __('Task Type'); ?></td>
                    <td>
                        <?php 
                        if($task['Task']['task_type'] == 0)echo "General";
                        elseif($task['Task']['task_type'] == 1)echo "Process Related";
                        elseif($task['Task']['task_type'] == 2)echo "Project Related";
                        elseif($task['Task']['task_type'] == 3)echo "Customer Complaint Related";
                        ?>&nbsp;
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('User'); ?></td>
                    <td>
                        <?php echo $this->Html->link($userNames[$task['Task']['user_id']], array('controller' => 'users', 'action' => 'view', $task['Task']['user_id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Status'); ?></td>
                    <td>
                        <?php 
                        // '0' => 'On going', '1' => 'Completed','2'=>'Not Started','3'=>'Cancelled'
                            if($task['Task']['task_status'] == 0)echo 'On Going'; 
                            if($task['Task']['task_status'] == 1)echo 'Completed'; 
                            if($task['Task']['task_status'] == 2)echo 'Not Started'; 
                            if($task['Task']['task_status'] == 3)echo 'Cancelled'; 
                        ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Process'); ?></td>
                    <td>
                        <?php echo ($task['Process']['title']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Project'); ?></td>
                    <td>
                        <?php echo ($task['Project']['title']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Project Activity'); ?></td>
                    <td>
                        <?php echo ($task['ProjectActivity']['title']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Customer Complaint'); ?></td>
                    <td>
                        <?php echo ($task['CustomerComplaint']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Master List Of Format'); ?></td>
                    <td>
                        <?php echo ($task['MasterListOfFormat']['title']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Description'); ?></td>
                    <td>
                        <?php echo h($task['Task']['description']); ?>
                        &nbsp;
                    </td>
                </tr>

                <tr><td><?php echo __('Start Date'); ?></td>
                    <td>
                        <?php echo h($task['Task']['start_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Original End Date'); ?></td>
                    <td>
                        <?php echo h($task['Task']['end_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Revised End Date'); ?></td>
                    <td>
                        <?php if($task['Task']['revised_due_date'] && $task['Task']['revised_due_date'] != '1970-01-01')echo h($task['Task']['revised_due_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Schedule'); ?></td>
                    <td>
                        <?php echo $this->Html->link($task['Schedule']['name'], array('controller' => 'schedules', 'action' => 'view', $task['Schedule']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($task['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($task['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($task['Task']['publish'] == 1) { ?>
                        <span class="fa fa-check"></span>
                        <?php } else { ?>
                        <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                        &nbsp;
                    </tr>
                <!-- <tr><td><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $this->Html->link($task['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $task['BranchIds']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo $this->Html->link($task['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $task['DepartmentIds']['id'])); ?>
                        &nbsp;
                    </td>
                </tr> -->
            </table>
            <?php 
            if(!empty($task['ProjectActivity']['title']))$showUpload = 'no';
            else $showUpload = '';
            echo $this->element('upload-edit', array('usersId' => $task['Task']['created_by'], 'recordId' => $task['Task']['id'],'showUpload' => $showUpload)); ?>            
        </div>
        <div class="col-md-4">            
            <p><?php echo $this->element('helps'); ?></p>
        </div>        
    </div>

    <?php  echo $this->element('task_view',array('task'=>$task,'task_performed'=>$task_performed,'taskType' => $task['Task']['task_type'])); ?>
    
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#tasks_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $task['Task']['id'], 'ajax'), array('async' => true, 'update' => '#tasks_ajax'))); ?>
    <?php $this->Js->get('#add'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'lists', null, 'ajax'), array('async' => true, 'update' => '#tasks_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

<script>
$.ajaxSetup({
    beforeSend: function () {
        $("#busy-indicator").show();
    },
    complete: function () {
        $("#busy-indicator").hide();
    }
});
</script>

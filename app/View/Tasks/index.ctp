<?php echo $this->element('checkbox-script'); ?>
<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="tasks ">
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Tasks', 'modelClass' => 'Task', 'options' => array("sr_no" => "Sr No", "name" => "Name", "description" => "Description"), 'pluralVar' => 'tasks'))); ?>

        <script type="text/javascript">
        $(document).ready(function() {
            $('table th a, .pag_list li span a').on('click', function() {
                var url = $(this).attr("href");
                $('#main').load(url);
                return false;
            });
        });
        </script>

        <div class="table-responsive">
            <?php echo $this->Form->create(array('class' => 'no-padding no-margin no-background')); ?>
            <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th><?php echo $this->Paginator->sort('sequence','Seq'); ?></th>
                    <th><?php echo $this->Paginator->sort('name'); ?>/<?php echo $this->Paginator->sort('task_completion'); ?></th>
                    <th><?php echo $this->Paginator->sort('task_type'); ?></th>
                    <th><?php echo $this->Paginator->sort('priority'); ?></th>
                    <th><?php echo $this->Paginator->sort('user_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('process_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('project'); ?></th>
                    <th><?php echo $this->Paginator->sort('customer_complaint_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('schedule_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('start_date'); ?></th>
                    <th><?php echo $this->Paginator->sort('end_date','Original End Date'); ?></th>
                    <th><?php echo $this->Paginator->sort('revised_due_date'); ?></th>
                    <th><?php echo $this->Paginator->sort('task_status'); ?></th>
                    <th><?php echo $this->Paginator->sort('rag_status','RAG Status'); ?></th>
                    <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('publish'); ?></th>
                </tr>
                <?php if ($tasks) {
                    $x = 0;
                    foreach ($tasks as $task):
                        ?>
                    <tr class="on_page_src">
                        <td class=" actions">
                            <?php echo $this->element('actions', 
                                array(
                                    'created' => $task['Task']['created_by'], 
                                    'postVal' => $task['Task']['id'], 
                                    'process_id' => $task['Task']['process_id'],
                                    'project_id' => $task['Task']['project_id'],
                                    'project_activity_id' => $task['Task']['project_activity_id'],
                                    'softDelete' => $task['Task']['soft_delete'])
                                ); 
                                ?>
                            </td>
                            <td><?php echo h($task['Task']['sequence']); ?>&nbsp;</td>
                            <td>
                                <?php 
                                    $completion = $this->requestAction('tasks/view/'.$task['Task']['id']);
                                    $total_tasks = $performed = 0;
                                    foreach ($completion[1] as $date => $task_status): 
                                        $total_tasks = $total_tasks + 1;
                                        if (isset($task_status['TaskStatus']['task_performed']) && $task_status['TaskStatus']['task_performed'] == 1){
                                            $performed = $performed + 1;
                                        }
                                    endforeach;
                                    $completion = round($performed/$total_tasks*100);                                    
                                    ?>
                                <?php
                                    if($task['Task']['task_status'] == 1 ){
                                        $cclass = ' progress-bar-danger';
                                        $task['Task']['task_completion'] = 100;
                                        $completion = 100;
                                    }
                                    if($task['Task']['task_completion'] <= 100)$class = ' progress-bar-success';
                                    if($task['Task']['task_completion'] <= 80 )$class = ' progress-bar-aqua';
                                    if($task['Task']['task_completion'] <= 60 )$class = ' progress-bar-yellow';
                                    if($task['Task']['task_completion'] <= 40)$class = ' progress-bar-red';
                                ?>
                                <?php echo h($task['Task']['name']); ?> &nbsp;<span class='label label-info pull-right'><?php echo $completion;?>%</span>&nbsp;
                                <?php // echo $task['Task']['task_completion'];?>
                                    <div class="progress-group">
                                        <div class="progress xs">                                            
                                            <div style="width: <?php echo $completion;?>%" class="progress-bar <?php echo $class;?>"></div>
                                            <div style="width: <?php echo 100 - $completion;?>%" class="progress-bar progress-bar-warning"></div>
                                        </div>
                                    </div>
                                    <!-- <div class="progress">
                                        <div class="progress-bar progress-bar-success" style="width: 35%">
                                            <span class="sr-only">35% Complete (success)</span>
                                        </div>
                                        <div class="progress-bar progress-bar-warning progress-bar-striped" style="width: 20%">
                                            <span class="sr-only">20% Complete (warning)</span>
                                        </div>                                    
                                    </div> -->
                            </td>
                            <td>
                                <?php 
                                if($task['Task']['task_type'] == 0)echo "General";
                                elseif($task['Task']['task_type'] == 1)echo "Process Related";
                                elseif($task['Task']['task_type'] == 2)echo "Project Related";
                                elseif($task['Task']['task_type'] == 3)echo "Customer Complaint";
                                ?>&nbsp;
                            </td>
                            <td><?php 
                                    if($task['Task']['task_status'] == 0)echo "High";
                                    if($task['Task']['task_status'] == 1)echo "Medium";
                                    if($task['Task']['task_status'] == 2)echo "Low";
                                ?>
                            </td>
                            <td>
                                <?php echo $this->Html->link($task['User']['name'], array('controller' => 'users', 'action' => 'view', $task['User']['id'])); ?>
                            </td>
                            <td><?php echo $this->Html->link($task['Process']['title'],array('controller'=>'processess','action'=>'view',$task['Process']['id'])); ?>&nbsp;</td>
                            <td><?php echo $this->Html->link($task['Project']['title'],array('controller'=>'projects','action'=>'view',$task['Project']['id'])); ?>&nbsp;</td>
                            <td><?php echo $this->Html->link($task['CustomerComplaint']['name'],array('controller'=>'customer_complaints','action'=>'view',$task['CustomerComplaint']['id'])); ?>&nbsp;</td>
                            <td>
                                <?php echo $this->Html->link($task['Schedule']['name'], array('controller' => 'schedules', 'action' => 'view', $task['Schedule']['id'])); ?>
                            </td>
                            <td><?php echo h($task['Task']['start_date']); ?>&nbsp;</td>
                            <td><?php echo h($task['Task']['end_date']); ?>&nbsp;</td>
                            <td><?php if($task['Task']['revised_due_date'] && $task['Task']['revised_due_date'] != '1970-01-01')echo h($task['Task']['revised_due_date']); ?>&nbsp;</td>
                            <td><?php 
                                if($task['Task']['task_status'] == 0)echo 'On Going'; 
                                if($task['Task']['task_status'] == 1)echo 'Completed On : <br />'. $task['Task']['task_completion_date']; 
                                if($task['Task']['task_status'] == 2)echo 'Not Started'; 
                                if($task['Task']['task_status'] == 3)echo 'Cancelled';                                 
                                ?>&nbsp;
                            </td>
                            <td>
        <?php
            if(isset($task['Task']['revised_due_date'])){
                if(($task['Task']['task_status'] == 0 && $task['Task']['revised_due_date'] < date('Y-m-d')) || $task['Task']['rag_status'] == 0)$rag_class = 'danger';
                elseif(($task['Task']['task_status'] == 0 && $task['Task']['revised_due_date'] > date('Y-m-d')) || $task['Task']['rag_status'] == 1) $rag_class = 'warning';
                elseif($task['Task']['rag_status'] == 2 && $task['Task']['end_date'] < date('Y-m-d')) $rag_class = 'default';
                else $rag_class = 'success';   
            }else{
                if(($task['Task']['task_status'] == 0 && $task['Task']['end_date'] < date('Y-m-d')) || $task['Task']['rag_status'] == 0) $rag_class = 'danger';
                elseif(($task['Task']['task_status'] == 0 && $task['Task']['end_date'] > date('Y-m-d')) || $task['Task']['rag_status'] == 1)$rag_class = 'warning';
                elseif($task['Task']['rag_status'] == 2 && $task['Task']['end_date'] < date('Y-m-d')) $rag_class = 'default';
                else $rag_class = 'success';   
            }
               if($task['Task']['rag_status'] == 0)$saved_rag = 'danger';
               if($task['Task']['rag_status'] == 1)$saved_rag = 'warning';
               if($task['Task']['rag_status'] == 2)$saved_rag = 'success';
               if($task['Task']['rag_status'] == 3)$saved_rag = 'default';
        ?>

                                <span class="label btn-<?php echo $rag_class;?>">&nbsp;&nbsp;</span>
                                <span class="label btn-<?php echo $saved_rag;?>">&nbsp;&nbsp;</span>
                            </td>
                            <td><?php echo h($task['PreparedBy']['name']); ?>&nbsp;</td>
                            <td><?php echo h($task['ApprovedBy']['name']); ?>&nbsp;</td>
                            <td width="60">
                                <?php if ($task['Task']['publish'] == 1) { ?>
                                <span class="fa fa-check"></span>
                                <?php } else { ?>
                                <span class="fa fa-ban"></span>
                                <?php } ?>&nbsp;</td>
                            </tr>
                            <?php
                            $x++;
                            endforeach;
                        } else {
                            ?>
                            <tr><td colspan=16><?php echo __('No results found'); ?></td></tr>
                            <?php } ?>
                        </table>
                        <?php echo $this->Form->end(); ?>
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
                            ?>
                        </p>
                        <ul class="pagination">
                            <?php
                            echo "<li class='previous'>" . $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')) . "</li>";
                            echo "<li>" . $this->Paginator->numbers(array('separator' => '')) . "</li>";
                            echo "<li class='next'>" . $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')) . "</li>";
                            ?>
                        </ul>
                    </div>
                </div>

                <?php echo $this->element('export'); ?>
                <?php echo $this->element('advanced-search', array('postData' => array("name" => "Name", "description" => "Description"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
                <?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "name" => "Name", "description" => "Description"))); ?>
                <?php echo $this->element('approvals'); ?>
                <?php echo $this->element('common'); ?>
                <?php echo $this->Js->writeBuffer(); ?>

                <script>
                $.ajaxSetup({beforeSend: function() {
                    $("#busy-indicator").show();
                }, complete: function() {
                    $("#busy-indicator").hide();
                }
            });
                </script>

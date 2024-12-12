
    <div class="nav panel panel-default">
        <table class="table table-responsive table-bordered">
            <caption>Feedback Identification Form</caption>
            <tr>
                <th width="320"><?php echo __('Customer')?></th>
                <td><?php echo $customerComplaint['ErpCustomer']['Name']?></td>
            </tr>
            <tr>
                <th><?php echo __('Reference')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['complaint_number']?></td>
            </tr>
            <tr>
                <th><?php echo __('Date')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['complaint_date']?></td>
            </tr>
            <tr>
                <th><?php echo __('Project Name')?></th>
                <td><?php echo $customerComplaint['ErpProject']['OrderNum']?></td>
            </tr>
            <tr>
                <th><?php echo __('Project Number')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['project_number']?></td>
            </tr>
            <tr>
                <th><?php echo __('Country')?></th>
                <td><?php echo $customerComplaint['ErpCountry']['Description']?></td>
            </tr>
            <tr>
                <th><?php echo __('Model')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['model']?></td>
            </tr>
            <!-- <tr>
                <th><?php echo __('Serial no')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['part_serial_number']?></td>
            </tr> -->
            <tr>
                <th><?php echo __('Part name')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['part_name']?></td>
            </tr>
            <tr>
                <th><?php echo __('Part Serial #')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['part_serial_number']?></td>
            </tr>
            <tr>
                <th><?php echo __('Delivary Date')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['project_delivey_date']?></td>
            </tr>
            <tr>
                <th><?php echo __('Commissioning Date')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['project_commissioning_date']?></td>
            </tr>
            <tr>
                <th><?php echo __('Problem Description')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['problem_summary']?></td>
            </tr>
            <tr>
                <th><?php echo __('initial analysis by maintenance person')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['initial_maintenance_analysis']?></td>
            </tr>
            <tr>
                <th><?php echo __('Complaint Source (Name)')?></th>                
                <td>
                    <?php
                    // Configure::write('debug',1);
                    // debug($customerComplaint);
                    // exit;
                        echo $customerComplaint['ComplaintSource']['name']
                        // if ($customerComplaint['CustomerComplaint']['complaint_source'] == 0) {
                        //     echo h($customerComplaint['Product']['name']);
                        // } elseif ($customerComplaint['CustomerComplaint']['complaint_source'] == 1) {
                        //     echo "Service";
                        // } elseif ($customerComplaint['CustomerComplaint']['complaint_source'] == 2) {
                        //     echo "Delivery Challan No: " . h($customerComplaint['DeliveryChallan']['challan_number']);
                        // } else {
                        //     echo "Customer Care";
                        // }
                    ?>&nbsp;
                </td>
            
            </tr>
            <tr>
                <th><?php echo __('Telephone/ Mobile no.')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['telephone']?></td>
            </tr>
            <!-- <tr>
                <th><?php echo __('')?></th>
                <td><?php echo $customerComplaint['CustomerComplaint']['']?></td>
            </tr> -->
        </table>

        <table class="table table-responsive table-bordered">
            <caption>Feedback Identification Form</caption>
            <tr>
                <th>Readings</th>
            </tr>
            <tr>
                <td><?php echo $customerComplaint['CustomerComplaint']['template_details']?></td>
            </tr>

        </table>
       
        <table class="table table-responsive">                
            <tr>
                <th width="320"><?php echo __('Analizer'); ?></th>
                <td>
                    <?php echo $this->Html->link($customerComplaint['Analyzer']['name'], array('controller' => 'employees', 'action' => 'view', $customerComplaint['Analyzer']['id'])); ?>
                    &nbsp;
                </td>
            </tr>
            <tr><th><?php echo __('Current Status'); ?></th>
                <td>
                    <?php echo $customerComplaint['CustomerComplaint']['current_status'] ? __('Close') : __('Open'); ?>
                    &nbsp;
                </td></tr>
            <tr><th><?php echo __('Prepared By'); ?></th>
                <td>
                    <?php echo h($customerComplaint['PreparedBy']['name']); ?>
                    &nbsp;
                </td></tr>
            <tr><th><?php echo __('Approved By'); ?></th>
                <td>
                    <?php echo h($customerComplaint['ApprovedBy']['name']); ?>
                    &nbsp;
                </td></tr>
            <tr><th><?php echo __('Publish'); ?></th>

                <td>
                    <?php if ($customerComplaint['CustomerComplaint']['publish'] == 1) { ?>
                        <span class="fa fa-check"></span>
                    <?php } else { ?>
                        <span class="fa fa-ban"></span>
                    <?php } ?>&nbsp;</td>
                &nbsp;</td></tr>
        </table>

             
            <!-- tasks starts -->
            <h2>Tasks</h2>
                <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                    <tr>                        
                        <th><?php echo $this->Paginator->sort('sequence','Seq'); ?></th>
                        <th><?php echo $this->Paginator->sort('name'); ?>/<?php echo $this->Paginator->sort('task_completion'); ?></th>                        
                        <th><?php echo $this->Paginator->sort('priority'); ?></th>
                        <th><?php echo $this->Paginator->sort('user_id'); ?></th>
                        <th><?php echo $this->Paginator->sort('schedule_id'); ?></th>
                        <th><?php echo $this->Paginator->sort('start_date'); ?></th>
                        <th><?php echo $this->Paginator->sort('end_date','Original End Date'); ?></th>
                        <th><?php echo $this->Paginator->sort('revised_due_date'); ?></th>
                        <th><?php echo $this->Paginator->sort('task_status'); ?></th>                        
                        <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
                        <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
                        <th><?php echo $this->Paginator->sort('publish'); ?></th>
                    </tr>
                    <?php if ($tasks) {
                        $x = 0;
                        foreach ($tasks as $task):?>
                            <tr class="on_page_src">
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
                                <td><?php 
                                        if($task['Task']['task_status'] == 0)echo "High";
                                        if($task['Task']['task_status'] == 1)echo "Medium";
                                        if($task['Task']['task_status'] == 2)echo "Low";
                                    ?>
                                </td>
                                <td>
                                    <?php echo $this->Html->link($task['User']['name'], array('controller' => 'users', 'action' => 'view', $task['User']['id'])); ?>
                                </td>                                
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
                                
                                <td><?php echo h($task['PreparedBy']['name']); ?>&nbsp;</td>
                                <td><?php echo h($task['ApprovedBy']['name']); ?>&nbsp;</td>
                                <td width="60">
                                    <?php if ($task['Task']['publish'] == 1) { ?>
                                    <span class="fa fa-check"></span>
                                    <?php } else { ?>
                                    <span class="fa fa-ban"></span>
                                    <?php } ?>&nbsp;
                                </td>
                            </tr>
                        <?php
                                $x++;
                            endforeach;
                        } else {?>
                        <tr><td colspan=16><?php echo __('No results found'); ?></td></tr>
                    <?php } ?>
                </table>

            <!-- tasks ends -->
            <div class="col-md-12">
                <h2><?php echo __('Corrective Preventive Actions');?></h2>                    
                        <div id="capaTabs" class="">
                            <ul>
                            <?php
                                foreach ($customerComplaint['CorrectivePreventiveAction'] as $capa) {
                                    if($capa['current_status'] == 0)$st = '<span class="badge label label-danger">Open</span>';
                                    else$st = '<span class="badge badge-success">Close</span>';
                                    echo "<li>"  . $this->html->link($capa['name'] . '&nbsp;' . $st,array('controller'=>'corrective_preventive_actions','action'=>'view','type'=>'ajax',$capa['id']),array('escape'=>false)) ."</li>";
                                } 
                            ?>                            
                        </ul>
                </div>
            </div>
        
    </div>
<script>
  $( function() {
    $( "#capaTabs" ).tabs();
  } );
  </script>

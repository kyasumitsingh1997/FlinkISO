<hr />
<?php if (isset($schedules) && $schedules) { ?>
                <?php foreach ($schedules as $key => $schedule_days): ?>
                    <h4 class="text-primary"><?php echo $key ?> <small>
                    <?php if (isset($result) && $result != null) { ?>
                    <?php if ($result <= 80) { ?>
                        <span class="badge btn-danger pull-right"><?php echo $result ?>%</span>
                    <?php } elseif ($result <= 90 && ($result > 80)) { ?>
                        <span class="badge btn-warning pull-right"><?php echo $result ?>%</span>
                    <?php } elseif ($result > 90) { ?>
                        <span class="badge btn-success pull-right"><?php echo $result ?>%</span>
                    <?php } ?>
                <?php } ?></small></h4>
                    <br />
                    <?php foreach ($schedule_days as $taskKey => $tasks): ?>
                        <?php if ($tasks) { ?>
                            <h5 class="text-success"><?php echo $taskKey ?></h5>
                            <table class="table table-responsive table-bordered">
                                <tr>
                                    <th width="25%"><?php echo __('Task') ?></th>
                                    <th width="20%"><?php echo __('Assigned To') ?></th>
                                    <th width="5%"><?php echo __('Status') ?></th>
                                    <th width="40%"><?php echo __('Comments') ?></th>
                                    <th width="10%"></th>
                                </tr>                            
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?php echo $task['Task']['name']; ?></td>
                                    <td><?php echo $task['User']['name']; ?></td>
                                    <td><?php
                                        if (isset($task['TaskStatus']['task_performed']) && $task['TaskStatus']['task_performed'] == 1)
                                            echo "<span class='glyphicon glyphicon-ok text-success'></span>";
                                        else
                                            echo "<span class='glyphicon glyphicon-remove text-danger'></span>";
                                        ?></td>
                                    <td><?php
                                        if (isset($task['TaskStatus']['task_performed']) && $task['TaskStatus']['task_performed'] == 1)
                                            echo "<span class='text-success'>" . $task['TaskStatus']['comments'] . "</span>";
                                        else echo "<span class='text-danger'>Tasks Not Performed</span>";
                                    ?></td>
                                    <td>
                                        <div class="btn-group  pull-right">
                                            <div id="taskcountdiv<?php echo $task['TaskStatus']['id'];?>" class="btn-xs btn btn-primary">0</div>
                                            <?php echo $this->Html->link('Files','#',array('id'=>'taskfilesbtn'.$task['TaskStatus']['id'],'class'=>'btn btn-xs btn-info')); ?>    
                                            </div>                            
                                            <div id="taskfilesdiv<?php echo $task['TaskStatus']['id'];?>"></div>                           
                                            <script>   
                                                $('#taskfilesbtn<?php echo $task["TaskStatus"]["id"];?>').click(function(){
                                                $('#taskfilesdiv<?php echo $task["TaskStatus"]["id"];?>').load("<?php echo Router::url('/', true); ?>tasks/task_ajax/<?php echo $task['TaskStatus']['id'] ?>/<?php echo $task['TaskStatus']['id']; ?>", function(response, status, xhr){});});
                                            </script>
                                            <script>
                                                $().ready(function(){$('#taskcountdiv<?php echo $task['TaskStatus']['id'];?>').load('<?php echo Router::url('/', true); ?>tasks/task_ajax_file_count/<?php echo $task['TaskStatus']['id'] ?>', function(response, status, xhr){});});
                                            </script>
                                            <?php echo $this->Js->writeBuffer(); ?>
                                        </td>
                                </tr>
                                

                            <?php endforeach ?>
                        </table>
                        <?php } else { ?>
                            
                        <?php } ?>
                    <?php endforeach ?>
                <?php endforeach ?>

<?php } else { ?>

                <h5><?php echo __('Please select Objective & Process from below'); ?></h5>

            <?php } ?>
<hr />            
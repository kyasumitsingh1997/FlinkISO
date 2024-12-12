<?php
    if($taskType == 0)$openView = 'task_ajax';
    elseif($taskType == 1)$openView = 'process_task_ajax';
    elseif($taskType == 2)$openView = 'project_task_ajax';
?>
<table class="table table-responsive table-bordered">
        <tr>
            <th><?php echo __('Date');?></th>
            <th><?php echo __('Performed By');?></th>
            <th><?php echo __('Status');?></th>
            <th><?php echo __('Comment');?></th>
            <th><?php echo __('Added By');?></th>
            <th><?php echo __('Files');?></th>
        </tr>
    <?php 
        $total_tasks = $performed = 0;
        $i= 0;
        foreach ($task_performed as $date => $task_status): 
            $total_tasks = $total_tasks + 1;
            if (isset($task_status['TaskStatus']['task_performed']) && $task_status['TaskStatus']['task_performed'] == 1){
                $performed = $performed + 1;
            }
    ?>
        <tr>    
            <td><?php $task_status['TaskStatus']['id']?><?php echo $date; ?> : <?php echo ($task_status['TaskStatus']['task_date']?$task_status['TaskStatus']['task_date']:'Not Performed'); ?></td>
            <td><?php echo $task['User']['name']; ?></td>
            <td><?php
            if (isset($task_status['TaskStatus']['task_performed']) && $task_status['TaskStatus']['task_performed'] == 1)
                echo "<span class='glyphicon glyphicon-ok text-success'></span>";
            else
                echo "<span class='glyphicon glyphicon-remove text-danger'></span>";
            ?></td>
            <td><?php
            if (isset($task_status['TaskStatus']['task_performed']) && $task_status['TaskStatus']['task_performed'] == 1)
                echo "<span class='text-success'>" . $task_status['TaskStatus']['comments'] . "</span>";
            else echo "<span class='text-danger'>Tasks Not Performed</span>";
        ?></td>
            <td><?php echo $PublishedUserList[$task_status['TaskStatus']['created_by']]; ?></td>
            <td>
            <div class="btn-group  pull-right">
                <div id="taskcountdiv_<?php echo $i;?>_<?php echo $task_status['TaskStatus']['id'];?>" class="btn-xs btn btn-primary">...</div>
                <?php echo $this->Html->link('Files','#',array('id'=>'taskfilesbtn_'. $i .'_'.$task_status['TaskStatus']['id'],'class'=>'btn btn-xs btn-info')); ?>    
                <div id="taskfilesdiv_<?php echo $i;?>_<?php echo $task_status['TaskStatus']['id'];?>"></div>                           
                <script>   
                    $('#taskfilesbtn_<?php echo $i;?>_<?php echo $task_status["TaskStatus"]["id"];?>').click(function(){                        
                        $('#taskfilesdiv_<?php echo $i;?>_<?php echo $task_status["TaskStatus"]["id"];?>').load("<?php echo Router::url('/', true); ?>tasks/<?php echo $openView ;?>/<?php echo $task_status['TaskStatus']['id'] ?>/<?php echo $task_status['TaskStatus']['id']; ?>", function(response, status, xhr){});
                    });
                </script>
                <script>
                    $().ready(function(){                        
                        $('#taskcountdiv_<?php echo $i;?>_<?php echo $task_status["TaskStatus"]["id"];?>').load('<?php echo Router::url('/', true); ?>tasks/task_ajax_file_count/<?php echo $task_status["TaskStatus"]["id"] ?>', function(response, status, xhr){});});
                </script>
                <?php echo $this->Js->writeBuffer(); ?>
            </td>    
        </tr>
    <?php
    $i++;
    endforeach ?>
    <tr>
        <td colspan="6" class="text-right"><h1><small>Completion : </small><?php echo round($performed/$total_tasks*100); ?>%</h1></td>
    </tr>
</table>

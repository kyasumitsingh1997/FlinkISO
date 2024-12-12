<?php echo $this->element('checkbox-script'); ?>

<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="productions ">
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Productions', 'modelClass' => 'Production', 'options' => array("sr_no" => "Sr No", "batch_number" => "Batch Number", "details" => "Details", "remarks" => "Remarks"), 'pluralVar' => 'productions'))); ?>

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
                    <th><?php echo $this->Paginator->sort('batch_number'); ?></th>
                    <th><?php echo $this->Paginator->sort('product_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('production_weekly_plan_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('production_date'); ?></th>
                    <th><?php echo $this->Paginator->sort('production_planned'); ?></th>
                    <th><?php echo $this->Paginator->sort('actual_production_number'); ?></th>
                    <th><?php echo $this->Paginator->sort('rejections'); ?></th>
                    <th><?php echo $this->Paginator->sort('balance'); ?></th>
                    <!-- <th><?php echo $this->Paginator->sort('total_balance'); ?></th> -->
                    <th><?php echo $this->Paginator->sort('branch_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('current_status'); ?></th>
                    
                    <th><?php echo __('Add QC/Rejections'); ?></th>
                    <th><?php echo $this->Paginator->sort('publish'); ?></th>
                </tr>
                <?php if ($productions) {
                        $x = 0;
                        foreach ($productions as $production):
                ?>
                <tr class="on_page_src">
                    <td class=" actions">
                        <?php echo $this->element('actions', array('created' => $production['Production']['created_by'], 'postVal' => $production['Production']['id'], 'softDelete' => $production['Production']['soft_delete'])); ?>
                    </td>
                    <td><?php echo h($production['Production']['batch_number']); ?>&nbsp;</td>
                    <td>
                        <?php echo $this->Html->link($production['Product']['name'], array('controller' => 'products', 'action' => 'view', $production['Product']['id'])); ?>
                    </td>
                    <td><?php echo $this->Html->link($production['ProductionWeeklyPlan']['name'],array('controller'=>'production_weekly_plans','action'=>'view',$production['ProductionWeeklyPlan']['id']),array('target'=>'_blank')); ?>&nbsp;</td>
                    <td><?php echo h(date('d M Y',strtotime($production['Production']['production_date']))); ?>&nbsp;</td>
                    

                    <td><?php echo h($production['Production']['production_planned']); ?>&nbsp;</td>
                    <td><?php echo h($production['Production']['actual_production_number']); ?>&nbsp;</td>
                    <td><?php if($production['Production']['rejections'])echo $production['Production']['rejections']; else echo 0;?>&nbsp;</td>
                    <td>
                        <?php echo $production['Production']['balance'];?><?php //echo h($production['Production']['balance']); ?>&nbsp;</td>
                    
                    <!-- <td><?php echo h($production['Production']['total_balance']); ?>&nbsp;</td> -->
                    <td>
                        <?php echo $this->Html->link($production['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $production['Branch']['id'])); ?>
                    </td>
                    <td><?php echo h($production['PreparedBy']['name']); ?>&nbsp;</td>
                    <td><?php echo h($production['ApprovedBy']['name']); ?>&nbsp;</td>
                    <td><?php echo h($currentStatus[$production['Production']['current_status']]); ?>&nbsp;</td>
                    
                    <td><?php 
                        if($production['Production']['actual_production_number'] > 0){
                            if($production['Production']['publish'] == 1){
                                if(count($production['ProductionRejection']) == 0){
                                    echo $this->Html->link('Add QC/Rejections',array('controller'=>'production_rejections','action'=>'lists','production_id'=>$production['Production']['id'],'product_id'=>$production['Production']['product_id'],'production_weekly_plan_id'=>$production['Production']['production_weekly_plan_id']),array('class'=>'btn btn-xs btn-danger'));     
                                }elseif(count($production['ProductionRejection']) > 0 && $this->Session->read('User.is_mr') == 1){
                                    echo $this->Html->link('Update QC/Rejections',array('controller'=>'productions','action'=>'view',$production['Production']['id']),array('class'=>'btn btn-xs btn-success'));     
                                }elseif(count($production['ProductionRejection']) > 0 && $this->Session->read('User.is_mr') == 0){
                                    echo $this->Html->link('View QC/Rejections',array('controller'=>'productions','action'=>'view',$production['Production']['id']),array('class'=>'btn btn-xs btn-success'));     
                                }
                                
                            }
                        }else{
                            echo $this->Html->link('Add Actual Production',array('controller'=>'productions','action'=>'edit',$production['Production']['id']),array('class'=>'btn btn-xs btn-info')); 
                        }
                    ?>
                        &nbsp;</td>
                    <td width="60">
                        <?php if ($production['Production']['publish'] == 1) { ?>
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
                <tr><td colspan=19>No results found</td></tr>
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
<?php echo $this->element('advanced-search', array('postData' => array("sr_no" => "Sr No", "batch_number" => "Batch Number", "details" => "Details", "remarks" => "Remarks"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "batch_number" => "Batch Number", "details" => "Details", "remarks" => "Remarks"))); ?>
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

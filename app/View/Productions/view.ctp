<style type="text/css">
.modal-dialog{width: 90% !important}

/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{    
    overflow-y: auto;
}
</style>
<div id="productions_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="productions form col-md-8">
            <h4>
                <?php echo $this->element('breadcrumbs') . __('View Production'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->link(__('Goto Weekly Plan'), array('controller'=> 'production_weekly_plans','action'=>'index'), array('id' => 'plan', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td><?php echo __('Product'); ?></td>
                    <td>
                        <?php echo $this->Html->link($production['Product']['name'], array('controller' => 'products', 'action' => 'view', $production['Product']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Batch Number'); ?></td>
                    <td>
                        <?php echo h($production['Production']['batch_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Production Date'); ?></td>
                    <td>
                        <?php echo h($production['Production']['production_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Production Weekly plan'); ?></td>
                    <td>
                        <?php echo h($production['ProductionWeeklyPlan']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Details'); ?></td>
                    <td>
                        <?php echo h($production['Production']['details']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $this->Html->link($production['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $production['Branch']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Supervisor'); ?></td>
                    <td>
                        <?php echo $this->Html->link($production['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $production['Employee']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Week'); ?></td>
                    <td>
                        <?php echo $this->Html->link($production['ProductionWeeklyPlan']['week'], array('controller' => 'production_weekly_plans', 'action' => 'view', $production['ProductionWeeklyPlan']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <!-- <tr><td><?php echo __('End Date'); ?></td>
                    <td>
                        <?php echo h(date('d M Y',strtotime($production['Production']['end_date']))); ?>
                        &nbsp;
                    </td>
                </tr> -->
                <tr><td><?php echo __('Production Planned'); ?></td>
                    <td>
                        <?php echo h($production['ProductionWeeklyPlan']['production_planned']); ?>
                        &nbsp;
                    </td>
                </tr><tr><td><?php echo __('Actual Production Number'); ?></td>
                    <td>
                        <?php echo h($production['Production']['actual_production_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Current Status'); ?></td>
                    <td>
                        <?php echo h($currentStatus[$production['Production']['current_status']]); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Remarks'); ?></td>
                    <td>
                        <?php echo h($production['Production']['remarks']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($production['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($production['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($production['Production']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
            </table>
            <h2><?php echo __('Production Rejection Details');?></h2>
            <table class="table table-responsive table-bordered">
                <tr>
                    <th><?php echo __('Inspection Template'); ?></th>
                    <th><?php echo __('Defect Type'); ?></th>
                    <th><?php echo __('Sample Quantity'); ?></th>
                    <th><?php echo __('Quality Check Date'); ?></th>
                    <th><?php echo __('Start Sr Number'); ?></th>
                    <th><?php echo __('End Sr Number'); ?></th>
                    <th><?php echo __('# Of Rejections'); ?></th>
                    <th><?php echo __('Employee'); ?></th>
                    <th><?php echo __('Publish'); ?></th>
                    <th width="85"><?php echo __('Action'); ?></th>
                </tr>
                <?php if($production['ProductionRejection']){ 
                            $total = 0;
                            ?>
                        <?php foreach ($production['ProductionRejection'] as $productionRejections): 
                            // Configure::write('debug',1);
                            debug($productionRejections);
                        ?>
                        <tr>
                            <td colspan="8"><?php echo $productionInspectionTemplates[$productionRejections['production_inspection_template_id']]?></td>
                            <td>
                                <div class="modal fade" id="rectmp1<?php echo $newProductionRejection['id'] ;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel"><?php echo $productionInspectionTemplates[$productionRejections['production_inspection_template_id']]?></h4>
                                      </div>
                                      <div class="modal-body">
                                        <?php echo $productionRejections['inspection_report'];?>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                                                
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                            <td> <button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#rectmp1<?php echo $newProductionRejection['id'] ;?>"><i class="fa fa-file-text" aria-hidden="true"></i></button></td>
                        </tr>
                            <?php foreach ($productionRejections['RejectionDetail'] as $newProductionRejection) { ?>
                                
                            
                                <tr>
                                    <td>
                                        <?php echo $productionRejections['name']; ?>
                                        <!-- Modal -->
                                        <div class="modal fade" id="rectmp<?php echo $newProductionRejection['id'] ;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel"><?php echo $productionInspectionTemplates[$productionRejections['production_inspection_template_id']]?></h4>
                                              </div>
                                              <div class="modal-body">
                                                <?php echo $productionRejections['inspection_report'];?>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                                                
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </td>
                                    <td><?php echo h($newProductionRejection['DefectType']['name']); ?>&nbsp;</td>
                                    <td><?php echo h($productionRejections['sample_quantity']); ?>&nbsp;</td>
                                    <td><?php echo h($productionRejections['quality_check_date']); ?>&nbsp;</td>
                                    <td><?php echo h($productionRejections['start_sr_number']); ?>&nbsp;</td>
                                    <td><?php echo h($productionRejections['end_sr_number']); ?>&nbsp;</td>
                                    <td><?php echo h($newProductionRejection['RejectionDetail']['number_of_rejections']); ?>&nbsp;</td>
                                    <?php 
                                        $total = $newProductionRejection['RejectionDetail']['number_of_rejections'] + $total;
                                    ?>
                                    <td>
                                        <?php echo $PublishedEmployeeList[$productionRejections['employee_id']]; ?>
                                    </td>
                                    <td width="60">
                                        <?php if($newProductionRejection['RejectionDetail']['publish'] == 1) { ?>
                                        <span class="fa fa-check"></span>
                                        <?php } else { ?>
                                        <span class="fa fa-ban"></span>
                                        <?php } ?>&nbsp;
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#rectmp<?php echo $newProductionRejection['id'] ;?>"><i class="fa fa-file-text" aria-hidden="true"></i></button>
                                            
                                            <?php echo $this->Html->link('Edit',array('controller'=>'production_rejections','action'=>'edit',$productionRejections['id']),array('class'=>'btn btn-default btn-xs'));?>
                                            
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php endforeach; ?>

                            <tr>
                                <th colspan="6">&nbsp;</th>
                                <th colspan="3" class="text-danger"><h3><?php echo __('Total Rejections');?></h3></th>
                                <th colspan="2" class="text-danger"><h3><?php echo $total;?></h3></th>
                            </tr>
                            <?php if($total == 0){ ?>
                            <tr>
                                <td colspan="9"></td>
                                <td>
                                    <?php echo $this->Html->link('Add Rejection',array('controller'=>'production_rejections','action'=>'edit',$productionRejections['id']),array('class'=>'btn btn-success btn-sm'));?><?php } ?>
                                </td>
                            </tr>
                        <?php }else{ ?>
                            <tr><td>No results found</td></tr>
                        <?php } ?>
            </table>



            <h2><?php echo __('Batch Details');?></h2>
            <table class="table table-responsive table-bordered">
                <tr>
                    <th><?php echo __('Batch Number');?></th>
                    <th><?php echo __('Material');?></th>
                    <th><?php echo __('Production Date');?></th>
                    <th><?php echo __('Quantity Consumed');?></th>
                </tr>
                <?php foreach ($production['Stock'] as $stocks) { ?>
                    <tr>
                        <td><?php echo $production['Production']['batch_number'];?></td>
                        <td><?php echo $materials[$stocks['material_id']];?></td>
                        <td><?php echo $stocks['production_date'];?></td>
                        <td><?php echo $stocks['quantity_consumed'];?></td>                        
                    </tr>
                <?php } ?>
            </table>
            <?php echo $this->element('upload-edit', array('usersId' => $production['Production']['created_by'], 'recordId' => $production['Production']['id'])); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#productions_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $production['Production']['id'], 'ajax'), array('async' => true, 'update' => '#productions_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

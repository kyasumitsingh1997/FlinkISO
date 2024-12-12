<div id="stocks_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="stocks form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Stock'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php if($stock['Stock']['type'] == 0)echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td><?php echo __('Sr. No'); ?></td>
                    <td>
                        <?php echo h($stock['Stock']['sr_no']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Material'); ?></td>
                    <td>
                        <strong><?php echo $this->Html->link($stock['Material']['name'], array('controller' => 'materials', 'action' => 'view', $stock['Material']['id'])); ?></strong>
                        &nbsp;
                    </td>
                </tr>
                <?php if ($stock['Stock']['type'] == 1) { ?>
                    <tr><td><?php echo __('Purchase Order'); ?></td>
                        <td>
                            <?php
                            echo $this->Html->link($purchaseOrder[$stock['DeliveryChallan']['purchase_order_id']], array('controller' => 'purchase_orders', 'action' => 'view', $stock['DeliveryChallan']['purchase_order_id']));
                            ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Delivery Challan'); ?></td>
                        <td>
                            <?php echo $this->Html->link($stock['DeliveryChallan']['name'], array('controller' => 'delivery_challans', 'action' => 'view', $stock['DeliveryChallan']['id'])); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Received Date'); ?></td>
                        <td>
                            <?php echo h($stock['Stock']['received_date']); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Quantity'); ?></td>
                        <td>
                            <?php echo h($stock['Stock']['quantity']); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Branch'); ?></td>
                        <td>
                            <?php echo $this->Html->link($stock['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $stock['Branch']['id'])); ?>
                            &nbsp;
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($stock['Stock']['type'] == 0) { ?>
                    <tr><td><?php echo __('Batch No.'); ?></td>
                        <td>
                            <?php echo $this->Html->link($stock['Production']['batch_number'], array('controller' => 'productions', 'action' => 'view', $stock['Production']['id'])); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Production Date'); ?></td>
                        <td>
                            <?php echo h($stock['Stock']['production_date']); ?>
                            &nbsp;
                        </td>
                    </tr>
<!--
                    <tr><td><?php echo __('Quantity'); ?></td>
                        <td>
                            <?php echo h($stock['Stock']['quantity']); ?>
                            &nbsp;
                        </td>
                    </tr>
-->
                    <tr><td><?php echo __('Quantity Consumed'); ?></td>
                        <td>
                            <?php echo h($stock['Stock']['quantity_consumed']); ?>
                            &nbsp;
                        </td>
                    </tr>
                <?php } ?>
                <tr><td><?php echo __('Remarks'); ?></td>
                    <td>
                        <?php echo h($stock['Stock']['remarks']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($stock['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($stock['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($stock['Stock']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;</td></tr>
            </table>
            <h3><?php echo __('Quality Check Performed'); ?></h3>
            <table class="table table-responsive">
                <?php $step = 1;  foreach ($checks as $check) { ?>
                    <tr>
                        <td colspan="2"><h5><?php echo __('Step'); ?> : <?php echo $step; ?> : <?php echo $check['MaterialQualityCheck']['name']; ?></h5></td>
                    </tr>
                    <tr>
                        <td><?php echo __('QC Performaed By'); ?></td>
                        <td><?php echo $check['Employee']['name'] ; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Check Performed Date'); ?></td>
                        <td><?php echo $check['MaterialQualityCheckDetail']['check_performed_date']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Quantity Received'); ?></td>
                        <td><?php echo $check['MaterialQualityCheckDetail']['quantity_received']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Auantity Accepted'); ?></td>
                        <td><?php echo $check['MaterialQualityCheckDetail']['quantity_accepted']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('QC Report'); ?></td>
                        <td><?php echo $check['MaterialQualityCheckDetail']['qc_report']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Delivery Challan'); ?></td>
                        <td><?php echo $check['DeliveryChallan']['challan_number']; ?>/<?php echo $check['DeliveryChallan']['challan_date']; ?></td>
                    </tr>
                <?php $step++; } ?>    
            </table>
            <?php echo $this->element('upload-edit', array('usersId' => $stock['Stock']['created_by'], 'recordId' => $stock['Stock']['id'])); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php
        if ($stock['Stock']['type'] == 1){
           echo $this->Js->event('click', $this->Js->request(array('action' => 'index',1), array('async' => true, 'update' => '#stocks_ajax')));

        }else if ($stock['Stock']['type'] == 0){
           echo $this->Js->event('click', $this->Js->request(array('action' => 'index',0), array('async' => true, 'update' => '#stocks_ajax')));
        }?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $stock['Stock']['id'], 'ajax'), array('async' => true, 'update' => '#stocks_ajax'))); ?>
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

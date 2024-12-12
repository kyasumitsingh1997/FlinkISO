<div id="materialQualityCheckDetails_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel">
        <div class="materialQualityCheckDetails form col-md-8">
            <h4><?php echo $materialQualityChecks['MaterialQualityCheck']['name']; ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info pull-right')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info pull-right', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr>
                    <td><strong>Details</strong></td>
                    <td><?php echo ':&nbsp;&nbsp;' . $materialQualityChecks['MaterialQualityCheck']['details']; ?></td>
                </tr>

            <!-- <div class="row">
                    <td><strong>QC Template</strong></div>
                    <div class="col-md-10"><?php echo ':&nbsp;&nbsp;' . $materialQualityChecks['MaterialQualityCheck']['qc_template']; ?></div>
                </div>     -->
                <tr>
                    <td><strong>Material</strong></td>
                    <td><?php echo ':&nbsp;&nbsp;' . $materialQualityChecks['Material']['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Delivery Challan</strong></td>
                    <td><?php echo ':&nbsp;&nbsp;' . $deliveryChallan['DeliveryChallan']['name']; ?></td>
                </tr>
                <tr>&nbsp;</td>
                <tr>
                    <td><strong>Performed By : </strong><br /><?php echo $materialQualityCheckDetail['Employee']['name']; ?></td>
                    <td><strong>Performed Date : </strong><br /><?php echo $materialQualityCheckDetail['MaterialQualityCheckDetail']['check_performed_date']; ?></td>
                </tr>
                <tr>
                    <td><strong>Qty Received : </strong><br /><?php echo $qtyRecd; ?></td>
                    <td><strong>Qty Accepted : </strong><br /><?php echo $materialQualityCheckDetail['MaterialQualityCheckDetail']['quantity_accepted']; ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>QC Report : </strong><br /><?php echo nl2br($materialQualityCheckDetail['MaterialQualityCheckDetail']['qc_report'])?><br /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php echo $this->Form->hidden('delivery_challan_detail_id', array('value'=>$deliveryChallanDetailId)); ?>
                        <h3><?php echo __('Quality Check Template');?></h3>
                        <?php 
                            if($materialQualityCheckDetail['MaterialQualityCheckDetail']['qc_template']){
                                $temp = $materialQualityCheckDetail['MaterialQualityCheckDetail']['qc_template'];
                            }else{
                                $temp = $materialQualityChecks['MaterialQualityCheck']['qc_template'];
                            }
                        ?>
                        <?php echo $temp ?>
                </td>
            </tr>
        </table>
            <?php echo $this->element('upload-edit', array('usersId' => $materialQualityCheckDetail['MaterialQualityCheckDetail']['created_by'], 'recordId' => $materialQualityCheckDetail['MaterialQualityCheckDetail']['id'])); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php echo $this->Js->get('#edit'); ?>
<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $materialQualityCheckDetail['MaterialQualityCheckDetail']['id'], 'ajax'), array('async' => true, 'update' => '#materialQualityCheckDetails_ajax'))); ?>


<?php echo $this->Js->writeBuffer(); ?>

</div>
<script>$.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});</script>

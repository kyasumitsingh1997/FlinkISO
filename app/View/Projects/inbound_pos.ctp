<div class="tab-pane" id="tab_<?php echo $milestone['Milestone']['id']?>_in_po">
    <h4>Inbound POs <small>(To Client)</small></h4>
            <table class="table table-responsive table-bordered table-condensed draggable">
              <tr class="danger">
                <th>PO#</th>
                <th>Title</th>
                <th>Order Date</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
              <?php foreach ($inboundPos as $purchaseOrder) { 
                // if($purchaseOrder['PurchaseOrder']['type'] == 1)$class = 'success'; else $class = 'warning';
                $final[$costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']]] = $costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']] + $purchaseOrder['PurchaseOrder']['po_total'];
              ?>
                <tr class="danger">
                  <td><?php echo $purchaseOrder['PurchaseOrder']['purchase_order_number']?></td>
                  <td><?php echo $purchaseOrder['PurchaseOrder']['title']?></td>
                  <td><?php echo $purchaseOrder['PurchaseOrder']['order_date']?></td>
                  <td><?php echo $this->Number->currency($purchaseOrder['PurchaseOrder']['po_total'],'INR. ')?></td>
                  <td>
                    <?php echo $this->Html->Link('Add Invoice',array('controller'=>'invoices','action'=>'lists',$purchaseOrder['PurchaseOrder']['id']),array('class'=>'btn btn-xs btn-default pull-right','target'=>'_blank'));?>
                    <?php echo $this->Html->Link('View',array('controller'=>'purchase_orders','action'=>'view',$purchaseOrder['PurchaseOrder']['id']),array('class'=>'btn btn-xs btn-default pull-right','target'=>'_blank'));?>
                    
                  </td>
                </tr>
              <?php 
              $pototal = $pototal + $purchaseOrder['PurchaseOrder']['po_total'];
            } ?>
            </table>   
</div>
<div class="tab-pane" id="tab_<?php echo $milestone['Milestone']['id']?>_out_po">
      <h4>Outbound POs <small>(To Vendors/supplers etc)</small></h4>
            <table class="table table-responsive table-bordered table-condensed draggable">
              <tr class="danger">
                <th>Supplier/Vendor</th>
                <th>Cost Category</th>
                <th>PO#</th>
                <th>Title</th>
                <th>Order Date</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
              <?php foreach ($outboundPos as $purchaseOrder) { 
                // if($purchaseOrder['PurchaseOrder']['type'] == 1)$class = 'success'; else $class = 'warning';
                $final[$costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']]] = $costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']] + $purchaseOrder['PurchaseOrder']['po_total'];
              ?>
                <tr class="danger">
                  <td><?php echo $suppliers[$purchaseOrder['PurchaseOrder']['supplier_registration_id']]?></td>
                  <td><?php echo $costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']]?></td>
                  <td><?php echo $purchaseOrder['PurchaseOrder']['purchase_order_number']?></td>
                  <td><?php echo $purchaseOrder['PurchaseOrder']['title']?></td>
                  <td><?php echo $purchaseOrder['PurchaseOrder']['order_date']?></td>
                  <td><?php echo $this->Number->currency($purchaseOrder['PurchaseOrder']['po_total'],'INR. ')?></td>
                  <td><?php echo $this->Html->Link('View',array('controller'=>'purchase_orders','action'=>'view',$purchaseOrder['PurchaseOrder']['id']),array('class'=>'btn btn-xs btn-default pull-right','target'=>'_blank'));?></td>
                </tr>
              <?php 
              $pototal = $pototal + $purchaseOrder['PurchaseOrder']['po_total'];
            } ?>
            </table>  
</div>
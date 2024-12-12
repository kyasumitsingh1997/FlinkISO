
      <h4>Payment Received <small>(To Vendors/supplers etc)</small></h4>
          <table class="table table-responsive table-bordered table-condensed draggable">
            <tr class="success">
              <th><?php echo __('P0'); ?></th>
              <th><?php echo __('Invoice'); ?></th>
              <th><?php echo __('Amount'); ?></th>
              <th><?php echo __('Amount Received'); ?></th>
              <th><?php echo __('Units'); ?></th>
              <th><?php echo __('Invoice Date'); ?></th>
              <th><?php echo __('Received Date'); ?></th>
              <th><?php echo __('Reason for Delay'); ?></th>
              <!-- <th><?php echo __('Prepared_ By'); ?></th>   
              <th><?php echo __('Approved By'); ?></th>    -->
              <!-- <th><?php echo __('Publish'); ?></th>  -->
              <th>Action</th>  
            </tr>
            <?php foreach ($projectPayments as $projectPayment) { 
              // if($purchaseOrder['PurchaseOrder']['type'] == 1)$class = 'success'; else $class = 'warning';
              $final[$costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']]] = $costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']] + $purchaseOrder['PurchaseOrder']['po_total'];
            ?>
              <tr>
                <td>
                  <?php echo $this->Html->link($projectPayment['PurchaseOrder']['name'], array('controller' => 'purchase_orders', 'action' => 'view', $projectPayment['PurchaseOrder']['id'])); ?>
                  </td>
                  <td>
                  <?php echo $this->Html->link($projectPayment['Invoice']['invoice_number'], array('controller' => 'invoices', 'action' => 'view', $projectPayment['Invoice']['id'])); ?>
                  </td>
                  <td><?php echo h($projectPayment['ProjectPayment']['amount']); ?>&nbsp;</td>
                  <td><?php echo h($projectPayment['ProjectPayment']['amount_received']); ?>&nbsp;</td>
                  <td><?php echo h($projectPayment['ProjectPayment']['units']); ?>&nbsp;</td>
                  <td><?php echo h($projectPayment['Invoice']['invoice_date']); ?>&nbsp;</td>
                  <td><?php echo h($projectPayment['ProjectPayment']['received_date']); ?>&nbsp;</td>
                  <td><?php echo h($projectPayment['ProjectPayment']['reason_for_delay']); ?>&nbsp;</td>
                  <!-- <td><?php echo h($PublishedEmployeeList[$projectPayment['ProjectPayment']['prepared_by']]); ?>&nbsp;</td>
                  <td><?php echo h($PublishedEmployeeList[$projectPayment['ProjectPayment']['approved_by']]); ?>&nbsp;</td> -->

                  <!-- <td width="60">
                    <?php if($projectPayment['ProjectPayment']['publish'] == 1) { ?>
                    <span class="fa fa-check"></span>
                    <?php } else { ?>
                    <span class="fa fa-ban"></span>
                    <?php } ?>&nbsp;
                  </td> -->
                  <td>
                    <div class="btn-group">
                      <!-- <?php echo $this->Html->link('Edit',array('controller'=>'project_payments','action'=>'edit',$projectPayment['ProjectPayment']['id']),array('target'=>'_blank','class'=>'btn btn-warning btn-xs')) ?>
                      <?php echo $this->Html->link('Delete',array('controller'=>'project_payments','action'=>'delete',$projectPayment['ProjectPayment']['id']),array('target'=>'_blank','class'=>'btn btn-danger btn-xs')) ?> -->
                    </div>
                  </td>
              </tr>
            <?php 
            $pototal = $pototal + $purchaseOrder['PurchaseOrder']['po_total'];
          } ?>
          </table>  

<div class="tab-pane" id="tab_<?php echo $milestone['Milestone']['id']?>_invoices">
      <h4>Invoices <small>(To Client)</small></h4>
              <table class="table table-responsive table-bordered table-condensed draggable">
                <tr class="info">
                  <th><?php echo __('Invoice #'); ?></th>
                  <th><?php echo __('Customer Contact'); ?></th>
                  <th><?php echo __('Invoice Date'); ?></th>
                  <th><?php echo __('Invoice Due_ Date'); ?></th> 
                  <th><?php echo __('Invoice Total'); ?></th> 
                  <td></td>                         
                </tr>
                <?php foreach ($invoices as $invoice) { ?>
                  <tr class="">
                    <td><?php echo $invoice['Invoice']['invoice_number']?></td>
                    <td><?php echo $invoice['Invoice']['customer_contact_id']?></td>
                    <td><?php echo $invoice['Invoice']['invoice_date']?></td>
                    <td><?php echo $invoice['Invoice']['invoice_due_date']?></td>
                    <td><?php echo $invoice['Invoice']['total']?></td>
                    <td>
                      <?php echo $this->Html->Link('View',array('controller'=>'invoices','action'=>'view',$invoice['Invoice']['id']),array('class'=>'btn btn-xs btn-default pull-right','target'=>'_blank'));?>
                      <?php echo $this->Html->Link('Add Payment Received',array('controller'=>'project_payments','action'=>'lists',
                        'invoice_id'=>$invoice['Invoice']['id'],
                        'project_id'=>$milestone['Milestone']['project_id'],
                        'milestone_id'=>$milestone['Milestone']['id']

                      ),array('class'=>'btn btn-xs btn-default pull-right','target'=>'_blank'));?>
                    </td>
                  </tr>                        
                <?php } ?>
              </table>  

  </div>
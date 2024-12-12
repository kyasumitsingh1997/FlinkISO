<br />
<table class="table table-responsive table-bordered table-condensed summary">
  <tr>
    <td>Title</td>
    <td><?php echo $qucipro['Project']['title']?> </td>
  </tr>
  <tr>
    <td>Customer</td>
    <td><?php echo $qucipro['Customer']['name']?> </td>
  </tr>
  <tr>
    <td>Duration</td>
    <td>
      <?php echo date('d M Y',strtotime($qucipro['Project']['start_date']))?> - <?php echo date('d M Y',strtotime($qucipro['Project']['end_date']))?> 
      (<?php 
      // $date1=date_create("2013-03-15");
      // $date2=date_create("2013-12-12");
      $diff = date_diff(date_create($qucipro['Project']['start_date']),date_create($qucipro['Project']['end_date']));
      echo $diff->format("%a days");
      ?>)
    </td>
  </tr>
   <tr>
    <td>Unit</td>
    <td></td>
  </tr>
  <tr>
    <td width="40%">Estimated Project Cost</td>
    <td><?php echo $this->Number->currency($qucipro['Project']['estimated_project_cost'],'INR. ')?> </td>
  </tr>
  <tr>
    <td>Payment Received</td>
    <td>
      <?php echo $this->Number->currency($qucipro['Project']['payment_received'],'INR. ')?> 
      <?php echo $this->Html->link('Add Customer PO',array('controller'=>'purchase_orders','action'=>'lists',
      'project_id'=>$qucipro['Project']['id'],
      'customer_id'=>$qucipro['Project']['customer_id'],
      'type'=>0,
    ),array('class'=>'btn btn-xs btn-success pull-right'));?> 

      <?php echo $this->Html->link('Add Invoice',array('controller'=>'invoices','action'=>'lists',
      'project_id'=>$qucipro['Project']['id'],
      'customer_id'=>$qucipro['Project']['customer_id'],
      'type'=>0,
    ),array('class'=>'btn btn-xs btn-success pull-right'));?> 
    </td>
  </tr>
  <tr>
    <td>Spent</td>
    <td><?php echo $this->Number->currency($qucipro['Project']['timesheet_cost'] + $qucipro['Project']['po_cost_out'],'INR. ')?> </td>
  </tr>
  <tr>
    <td>Balance</td>
    <?php $bal = $qucipro['Project']['estimated_project_cost'] - $qucipro['Project']['payment_received']; ?>
    <td><?php echo $this->Number->currency($bal,'INR. ')?> </td>
  </tr>
  <tr>
    <td>Estimated Mandays</td>
    <td><?php echo $qucipro['Project']['resource_hours']?> <small> Mandays</small> / <?php echo $qucipro['Project']['total_resources']?> <small> Resources</small></td>
  </tr>
  <tr>
    <td>Mandays Utilized</td>
    <td><?php echo $qucipro['Project']['timesheet_hours']?> <small> Mandays</small></td>
  </tr>

</table>

<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="projectPayments_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav">
		<div class="projectPayments form col-md-8">

<?php
$qucipro = $this->requestAction('projects/projectdates/'.$project_id);
echo $this->element('projectdates',array('qucipro'=>$qucipro));
?>

<div class="row">
	<div class="col-sm-12">
      <h4>InBound POs <small>(To Vendors/supplers etc)</small></h4>
          <table class="table table-responsive table-bordered table-condensed draggable">
            <tr class="">
              <th>Supplier/Vendor</th>
              <th>Cost Category</th>
              <th>PO#</th>
              <th>Title</th>
              <th>Order Date</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
            <?php foreach ($project_details['PurchaseOrder']['in'] as $purchaseOrder) { 
              // if($purchaseOrder['PurchaseOrder']['type'] == 1)$class = 'success'; else $class = 'warning';
              $final[$costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']]] = $costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']] + $purchaseOrder['PurchaseOrder']['po_total'];
            ?>
              <tr class="">
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
	<div class="col-sm-12">
      <h4>Outbound POs <small>(To Vendors/supplers etc)</small></h4>
          <table class="table table-responsive table-bordered table-condensed draggable">
            <tr class="">
              <th>Supplier/Vendor</th>
              <th>Cost Category</th>
              <th>PO#</th>
              <th>Title</th>
              <th>Order Date</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
            <?php foreach ($project_details['PurchaseOrder']['out'] as $purchaseOrder) { 
              // if($purchaseOrder['PurchaseOrder']['type'] == 1)$class = 'success'; else $class = 'warning';
              $final[$costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']]] = $costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']] + $purchaseOrder['PurchaseOrder']['po_total'];
            ?>
              <tr class="">
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
        <div class="col-sm-12">
             <h4>Payment Received <small>(To Vendors/supplers etc)</small></h4>
              <table class="table table-responsive table-bordered table-condensed draggable">
                <tr class="">
                  <th><?php echo __('P0'); ?></th>
                  <th><?php echo __('Invoice'); ?></th>
                  <th><?php echo __('Amount'); ?></th>
                  <th><?php echo __('Amount Received'); ?></th>
                  <th><?php echo __('Units'); ?></th>
                  <th><?php echo __('Received Date'); ?></th>
                  <!-- <th><?php echo __('Prepared_ By'); ?></th>   
                  <th><?php echo __('Approved By'); ?></th>   
                  <th><?php echo __('Publish'); ?></th>  -->
                  <th>Action</th>  
                </tr>
                <?php foreach ($project_details['ProjectPayment'] as $projectPayment) { 
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
                      <td><?php echo h($projectPayment['ProjectPayment']['received_date']); ?>&nbsp;</td>
                      <!-- <td><?php echo h($PublishedEmployeeList[$projectPayment['ProjectPayment']['prepared_by']]); ?>&nbsp;</td>
                      <td><?php echo h($PublishedEmployeeList[$projectPayment['ProjectPayment']['approved_by']]); ?>&nbsp;</td>

                      <td width="60">
                        <?php if($projectPayment['ProjectPayment']['publish'] == 1) { ?>
                        <span class="fa fa-check"></span>
                        <?php } else { ?>
                        <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;
                      </td> -->
                      <td>                        
                          <?php echo $this->Html->link('view',array('controller'=>'project_payments','action'=>'edit',$projectPayment['ProjectPayment']['id']),array('target'=>'_blank','class'=>'btn btn-default btn-xs')) ?>                          
                      </td>
                  </tr>
                <?php 
                $pototal = $pototal + $purchaseOrder['PurchaseOrder']['po_total'];
              } ?>
              </table>     
            </div>
          </div>
	   
      <h4>Invoices <small>(To Client)</small></h4>
          <table class="table table-responsive table-bordered table-condensed draggable">
            <tr class="info">
              <th><?php echo __('Invoice #'); ?></th>
              <th><?php echo __('Customer Contact'); ?></th>
              <th><?php echo __('Invoice Date'); ?></th>
              <th><?php echo __('Invoice Due Date'); ?></th>
              <th><?php echo __('Invoice Total'); ?></th> 
              <td></td>                         
            </tr>
            <?php foreach ($project_details['Invoice'] as $invoice) { ?>
              <tr class="">
                <td><?php echo $invoice['Invoice']['invoice_number']?></td>
                <td><?php echo $invoice['Invoice']['customer_contact_id']?></td>
                <td><?php echo $invoice['Invoice']['invoice_date']?></td>
                <td><?php echo $invoice['Invoice']['invoice_due_date']?></td>
                <td><?php echo $invoice['Invoice']['total']?></td>
                <td>
                  <?php echo $this->Html->Link('View',array('controller'=>'invoices','action'=>'view',$invoice['Invoice']['id']),array('class'=>'btn btn-xs btn-default pull-right','target'=>'_blank'));?>                  
                </td>
              </tr>                        
            <?php } ?>
          </table>   

			<h4>Add Project Payment</h4>
			<?php echo $this->Form->create('ProjectPayment',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-6 hide'>".$this->Form->input('project_id',array('default'=>$qucipro['Project']['id'])) . '</div>'; 
					echo "<div class='col-md-6 hide'>".$this->Form->input('milestone_id',array('default'=>$milestone_id)) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('purchase_order_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('invoice_id',array('default'=>$this->request->params['named']['invoice_id'])) . '</div>'; 
					echo "<div class='col-md-3'>".$this->Form->input('amount',array()) . '</div>'; 
					echo "<div class='col-md-3'>".$this->Form->input('amount_received',array()) . '</div>'; 
					echo "<div class='col-md-3'>".$this->Form->input('units',array()) . '</div>'; 
					echo "<div class='col-md-3'>".$this->Form->input('received_date',array()) . '</div>'; 
          echo "<div class='col-md-12'>".$this->Form->input('reason_for_delay',array('rows'=>2)) . '</div>'; 
	?>
			</fieldset>
			<?php
			    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
			    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
			    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
			?>
		</div>
		<div class="">
<?php

		if ($showApprovals && $showApprovals['show_panel'] == true) {
			echo $this->element('approval_form');
		} else {
			echo $this->Form->input('publish', array('label' => __('Publish')));
		}?>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectPayments_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
		</div>
	</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); 
</script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                
								$(element).attr('name') == 'data[ProjectPayment][project_id]')
						{	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    			});
        }
    });
	$().ready(function() {

		$("#ProjectPaymentPurchaseOrderId").on('change',function(){
			$.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_po_total/po_id:" + $("#ProjectPaymentPurchaseOrderId").val() , function(data) {
                  console.log(data);
                  $("#ProjectPaymentAmount").val(data);
            });
		});

    	$("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        
        $('#ProjectPaymentAddAjaxForm').validate({
            rules: {
									"data[ProjectPayment][project_id]": {
                    	greaterThanZero: true,
									},
                
            }
        }); 

				$('#ProjectPaymentProjectId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				      
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

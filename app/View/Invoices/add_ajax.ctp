<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>


<div id="invoices_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="invoices form col-md-8">
<h4>Add Invoice</h4>
<?php echo $this->Form->create('Invoice',array('role'=>'form','class'=>'form','default'=>false)); ?>

<?php
$qucipro = $this->requestAction('projects/projectdates/'.$this->request->params['named']['project_id']);
echo $this->element('projectdates',array('qucipro'=>$qucipro));
$subsubtotal = 0;
?>
<div class="row">
		<fieldset>
			<table class="table table-responsive">
                        <?php echo $this->Form->hidden('project_id',array('label'=>false,'value'=>$this->request->params['named']['project_id'])); ?>
                        
                        <?php 
                        if(!$this->request->params['named']['milestone_id']){
                            echo "<tr><td colspan='3'>" . $this->Form->input('milestone_id',array('label'=>'Select Milestone')) . "</td></tr>";
                        }elseif($this->request->params['named']['milestone_id']){
                            echo $this->Form->hidden('milestone_id',array('label'=>false,'value'=>$this->request->params['named']['milestone_id']));                         
                        }?>

                        <tr><td>Bill To : 
                            <?php echo $this->Form->input('customer_contact_id',array('label'=>false,'options'=>$customerContacts)); ?>
                            <?php

                                if($this->request->params['pass'][0] && !$this->request->params['named']['project_id']){
                                    echo $purchaseOrder['Customer']['name'] ."<br />";
                                    echo $purchaseOrder['Customer']['residence_address'] ."<br />";

                                    echo $this->Form->hidden('customer_id',array('value'=>$purchaseOrder['Customer']['id'],'label'=>false));
                                    echo $this->Form->hidden('details',array('value'=>$purchaseOrder['PurchaseOrder']['details'],'label'=>false));
                                    echo $this->Form->hidden('purchase_order_id',array('value'=>$purchaseOrder['PurchaseOrder']['id'],'label'=>false));

                                }elseif($this->request->params['named']['project_id']){
                                    echo "<strong>".$project['Customer']['name'] ."</strong><br />";
                                    echo nl2br($project['Customer']['residence_address']) ."<br />";

                                    echo $this->Form->hidden('customer_id',array('value'=>$project['Customer']['id'],'label'=>false));
                                    echo $this->Form->hidden('details',array('value'=>'NIL'));
                                    // echo $this->Form->hidden('purchase_order_id',array('value'=>$purchaseOrder['PurchaseOrder']['id'],'label'=>false));
                                }
                            ?>
                            
                            </td>
                            <th>
                            <table class="table table-responsive table-bordered">
                                <tr>
                                    <td>Date:</td>
                                    <td><?php echo $this->Form->input('invoice_date',array('value'=>date('Y-m-d'),'label'=>false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Invoice #:</td>
                                    <td><?php echo $this->Form->input('invoice_number',array('label'=>false,'value'=>$purchaseOrder['PurchaseOrder']['purchase_order_number'])); ?></td>
                                </tr>
                                <tr>
                                    <td>Customer ID:</td>
                                    <td><?php echo $this->Form->input('work_order_number',array('label'=>false,'value'=>$purchaseOrder['PurchaseOrder']['purchase_order_number'])); ?></td>
                                </tr>
                                <tr>
                                    <td>Work Order #:</td>
                                    <td><?php echo $purchaseOrder['PurchaseOrder']['purchase_order_number']; ?></td>
                                </tr>
                                <tr>
                                    <td>Payment Due by:</td>
                                    <td><?php echo $this->Form->input('invoice_due_date',array('label'=>false,'value'=>date('Y-m-d',strtotime('+ 7 days')))); ?></td>
                                </tr>
                                <tr>
                                    <td>Vat Number:</td>
                                    <td><?php echo $this->Form->input('vat_number',array('label'=>false)); ?></td>
                                </tr>
                            </table>
                        </th></tr>                    
                    </table>
                    <?php echo $this->Form->hidden('currency_id',array('type'=>'text', 'label'=>false,'value'=>$purchaseOrder['Currency']['id'])); ?>
                    <table class="table table-responsive table-bordered">
                        <tr bgcolor="#FFFFFF">
                            <th>#</th>
                            <th><?php echo __('Process'); ?></th>
                            <th><?php echo __('Item Description'); ?></th>
                            <th><?php echo __('Qty'); ?></th>
                            <th><?php echo __('Rate'); ?>(<?php echo $currencies[$projectCurrency];?>)</th>
                            <th><?php echo __('Discount'); ?></th>              
                            <th><?php echo __('Sub Total'); ?>(<?php echo $currencies[$projectCurrency];?>)</th>        
                        </tr>
                        
                        <?php 
                        $i = 0;
                        foreach($projectProcessPlans as $key => $process) { ?>
                        <tr bgcolor="#FFFFFF" class="hide">
                            <td><?php echo $this->Form->checkbox('InvoiceDetail.'.$i.'.invoice_details',array('class'=>'change', 'id'=>$purchaseOrderDetail['PurchaseOrderDetail']['id'], 'label'=>false,'checked')); ?></td>
                            <td><?php
                                            if ($purchaseOrderDetail['PurchaseOrderDetail']['product_id'] != -1) {
                                                echo $purchaseOrderDetail['Product']['name'];
                                            } elseif ($purchaseOrderDetail['PurchaseOrderDetail']['device_id'] != -1) {
                                                echo $purchaseOrderDetail['Device']['name'];
                                            } elseif ($purchaseOrderDetail['PurchaseOrderDetail']['material_id'] != -1) {
                                                echo $purchaseOrderDetail['Material']['name'];
                                            } elseif ($purchaseOrderDetail['PurchaseOrderDetail']['other'] != NULL) {
                                                echo($purchaseOrderDetail['PurchaseOrderDetail']['other']);
                                            }
                                            ?>
                                &nbsp; 
                                <?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['description']); ?> &nbsp;
                                <?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['item_number']); ?> &nbsp; 
                                <?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['quantity']); ?> &nbsp; 
                                <?php
                                            if ($purchaseOrderDetail['PurchaseOrderDetail']['discount'] != NULL) {
                                                echo h($purchaseOrderDetail['PurchaseOrderDetail']['discount']) . "%";
                                            } else {
                                                echo '&#8212;';
                                            }
                                            ?>
                                &nbsp; </td>
                            <td id='<?php echo $purchaseOrderDetail['PurchaseOrderDetail']['id']; ?>_val' align="right"><?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['total']); ?></td>
                            <?php $subtotal = $subtotal + $purchaseOrderDetail['PurchaseOrderDetail']['total']; ?>
                        </tr>
                        <tr>
                            <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.project_id',array('label'=>false,'value'=>$this->request->params['named']['project_id'])); ?>
                            <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.milestone_id',array('label'=>false,'value'=>$this->request->params['named']['milestone_id'])); ?>
                            <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.purchase_order_id',array('label'=>false,'value'=>$purchaseOrderDetail['PurchaseOrderDetail']['purchase_order_id'])); ?>
                            <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.purchase_order_detail_id',array('label'=>false,'value'=>$purchaseOrderDetail['PurchaseOrderDetail']['id'])); ?>
                            <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.product_id',array('label'=>false,'value'=>$purchaseOrderDetail['PurchaseOrderDetail']['product_id'])); ?>
                            <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.device_id',array('label'=>false,'value'=>$purchaseOrderDetail['PurchaseOrderDetail']['device_id'])); ?>
                            <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.material_id',array('label'=>false,'value'=>$purchaseOrderDetail['PurchaseOrderDetail']['material_id'])); ?>
                            <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.other',array('label'=>false,'value'=>$purchaseOrderDetail['PurchaseOrderDetail']['other'])); ?>
                            
                            <td><?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.item_number',array('label'=>false,'value'=>$i)); ?><?php echo $i+1 ?></td>
                            <td><?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.project_process_plan_id',array('label'=>false,'value'=>$key)); ?>
                                <?php echo $process;?>
                            </td>
                            <td><?php echo $this->Form->input('InvoiceDetail.'.$i.'.description',array('label'=>false,'rows'=>1, 'value'=>$purchaseOrderDetail['PurchaseOrderDetail']['description'])); ?></td>
                            
                            <td><?php echo $this->Form->input('InvoiceDetail.'.$i.'.quantity',array('onchange'=>'cals('.$i.')', 'label'=>false,'value'=>$unitsCompleted[$key])); ?>
                                <script type="text/javascript">
                                    $("#InvoiceDetail<?php echo $i;?>Quantity").addClass(' qty');
                                </script>
                            </td>
                            <td><?php echo $this->Form->input('InvoiceDetail.'.$i.'.rate',array('onchange'=>'cals('.$i.')','label'=>false,'value'=>$projectProcessPlanRates[$key])); ?>
                                <script type="text/javascript">
                                    $("#InvoiceDetail<?php echo $i;?>Rate").addClass(' rate');
                                </script>
                            </td>
                            <td><?php echo $this->Form->input('InvoiceDetail.'.$i.'.discount',array('onchange'=>'cals('.$i.')','label'=>false,'value'=>0)); ?>
                                <script type="text/javascript">
                                    $("#InvoiceDetail<?php echo $i;?>Discount").addClass(' disc');
                                </script>
                            </td>
                            <?php
                            $subtotal = 0;
                            $subtotal = $projectProcessPlanRates[$key] * $unitsCompleted[$key];
                            $subsubtotal = $subtotal + $subsubtotal;
                            ?>

                            <td><?php echo $this->Form->input('InvoiceDetail.'.$i.'.total',array('onchange'=>'cals('.$i.')','label'=>false,'value'=>$subtotal)); ?>
                                <script type="text/javascript">
                                    $("#InvoiceDetail<?php echo $i;?>Total").addClass(' subtotal');
                                </script>
                            </td>
                    </tr>
                    <script type="text/javascript">
                        
                    </script>

                        <?php $i++; } ?>
                    </table>                
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <td  width="60%" valign="top">
                                <strong><?php echo __('Banking Details'); ?></strong><br />
                                <?php echo $this->Form->input('banking_details',array('label'=>false, 'value'=>$invoice_settings['InvoiceSetting']['banking_details'])); ?>
                            </td>
                            <td>
                                <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                                    <tr><td><?php echo __('Subtotal'); ?>(<?php echo $currencies[$projectCurrency];?>)</td>
                                    <td><span id="subt"><?php echo $subsubtotal;?></span>
                                        <?php echo $this->Form->hidden('stotal',array('label'=>false,'value'=>$subsubtotal)); ?></td></tr>
                                    <tr><td><?php echo __('Vat'); ?></td><td><?php echo $this->Form->input('vat',array('onchange'=>'taxtotal()', 'label'=>false,'value'=>14)); ?></td></tr>
                                    <tr><td><?php echo __('Sales Tax'); ?></td><td><?php echo $this->Form->input('sales_tax',array('onchange'=>'taxtotal()','label'=>false,'value'=>0)); ?><td></td></tr>
                                    <tr><td><?php echo __('Discount'); ?></td><td><?php echo $this->Form->input('discount',array('onchange'=>'taxtotal()','label'=>false,'value'=>0)); ?></td></tr>
                                    <tr><td><?php
                                        // $subtotal = $subtotal  + ($subtotal * 14 / 100);
                                        echo __('Total'); ?>(<?php echo $currencies[$projectCurrency];?>)
                                    </td>
                                     <td><?php echo $this->Form->input('total',array('label'=>false, 'value'=>$subsubtotal)); 
                                    ?></td></tr>
                                </table>
                            </td>
                        </tr>
                    </table> 
                    <?php echo $this->Form->input('notes',array('disabled', 'value'=>$invoice_settings['InvoiceSetting']['footer'])); ?>   
                    <p class="text-center"><?php echo $invoice_settings['InvoiceSetting']['contact_details']; ?><br />
                    </p>
</fieldset>
<?php
                    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="row">
		

<?php
    if ($showApprovals && $showApprovals['show_panel'] == true) {
        echo $this->element('approval_form');
    } else {
        echo $this->Form->input('publish', array('label' => __('Publish')));
    }
?>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#invoices_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>

    $(".qty, .rate , .disc").on('change',function(){
        cal()
    });

    function cal(){
        var subtotal = 0;
        $(".subtotal").each(function(){
            subtotal = parseFloat(this.value) + subtotal;
        });
        
        $("#subt").html(subtotal);
        $("#InvoiceTotal").val(subtotal);
        taxtotal();
    }

    function cals(i){
        var qty = parseInt($("#InvoiceDetail"+i+"Quantity").val());
        var rate = parseInt($("#InvoiceDetail"+i+"Rate").val());
        var disc = parseInt($("#InvoiceDetail"+i+"Discount").val());

        var subtotal = qty * rate;
        var total = subtotal - (subtotal * disc) / 100;
        $("#InvoiceDetail"+i+"Total").val(total);
        subtot();
        taxtotal();
    }

    function subtot(){
        var total = 0;
        for(i= 1;i<=5;i++){
            var qty = parseInt($("#InvoiceDetail"+i+"Quantity").val());
            var rate = parseInt($("#InvoiceDetail"+i+"Rate").val());
            var disc = parseInt($("#InvoiceDetail"+i+"Discount").val());

            var subtotal = qty * rate;
            total = total + ( subtotal - (subtotal * disc) / 100 );
        }
        // console.log(total);
        $("#subt").html(total);
        taxtotal();
    }

    function taxtotal(){        
        var total = parseInt($("#subt").html());
        
        var vat = parseInt($("#InvoiceVat").val());
        var st = parseInt($("#InvoiceSalesTax").val());
        var id = parseInt($("#InvoiceDiscount").val());

        var ttax = vat + st;

        var ttotal = total + (total * ttax / 100) - id; 
        $("#InvoiceTotal").val(ttotal);
    }

    $.validator.setDefaults({
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
        cal();
        $("#InvoiceMilestoneId").on('change',function(){
            for(i=1;i<=5;i++){
                $("#InvoiceDetail"+i+"MilestoneId").val($("#InvoiceMilestoneId").val());
            }
        });
        
        $("#submit-indicator").hide();
        $('#InvoiceAddAjaxForm').validate();        
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
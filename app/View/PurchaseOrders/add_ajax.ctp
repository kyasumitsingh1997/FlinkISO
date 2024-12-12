<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<?php $i = 0; $j = 1; ?>

<script>
    function shhd(chk) {
	if (chk == 0) {
            $("#supplier").hide();
            $("#other").hide();
            $("#customer").show();
            $("#PurchaseOrderCustomerId_chosen").width('100%');
            $('#PurchaseOrderSupplierRegistrationId').rules('remove');
            if ($('#PurchaseOrderSupplierRegistrationId').next().next('label').hasClass("error")) {
                $('#PurchaseOrderSupplierRegistrationId').next().next('label').remove();
            }
            $('#PurchaseOrderOther').rules('remove');
            if ($('#PurchaseOrderOther').next('label').hasClass("error")) {
                $('#PurchaseOrderOther').next('label').remove();
            }
            $('#PurchaseOrderCustomerId').rules('add', {
                greaterThanZero: true
            });
        } else if (chk == 1) {
            $("#customer").hide();
            $("#other").hide();
            $("#supplier").show();
            $("#PurchaseOrderSupplierRegistrationId_chosen").width('100%');
            $('#PurchaseOrderCustomerId').rules('remove');
            if ($('#PurchaseOrderCustomerId').next().next('label').hasClass("error")) {
                $('#PurchaseOrderCustomerId').next().next('label').remove();
            }
            $('#PurchaseOrderOther').rules('remove');
            if ($('#PurchaseOrderOther').next('label').hasClass("error")) {
                $('#PurchaseOrderOther').next('label').remove();
            }
            $('#PurchaseOrderSupplierRegistrationId').rules('add', {
                greaterThanZero: true
            });
        } else {
            $("#customer").hide();
            $("#supplier").hide();
            $("#other").show();
            $('#PurchaseOrderSupplierRegistrationId').rules('remove');
            if ($('#PurchaseOrderSupplierRegistrationId').next().next('label').hasClass("error")) {
                $('#PurchaseOrderSupplierRegistrationId').next().next('label').remove();
            }
            $('#PurchaseOrderCustomerId').rules('remove');
            if ($('#PurchaseOrderCustomerId').next().next('label').hasClass("error")) {
                $('#PurchaseOrderCustomerId').next().next('label').remove();
            }
            $('#PurchaseOrderOther').rules('add', {
                required: true
            });
        }
    }

    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {

            if ( $(element).attr('name') == 'data[PurchaseOrder][customer_id]' ||
                  $(element).attr('name') == 'data[PurchaseOrder][supplier_registration_id]' ||
		  $(element).attr('name').match(/product_id/g) ||
                  $(element).attr('name').match(/device_id/g) ||
                  $(element).attr('name').match(/material_id/g) ||
                  $(element).attr('name') == 'data[PurchaseOrder][type]') {
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
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
        }
    });

    $().ready(function() {
        $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value.length) == 36);
        }, "Please select the value");
        $('#PurchaseOrderAddAjaxForm').validate({
	    rules: {
                "data[PurchaseOrder][supplier_registration_id]": {
                    greaterThanZero: {
                        depends: function(element) {
                            if ($("[name = 'data[PurchaseOrder][type]']").val() == 0) {
                                return true;

                            } else {
                                return false;
                            }
                        }
                    }
                },
                "data[PurchaseOrder][customer_id]": {
                    greaterThanZero: {
                        depends: function(element) {
                            if ($("[name = 'data[PurchaseOrder][type]']").val() == 1) {
                                return true;

                            } else {
                                return false;
                            }
                        }
                    }
                }
            }
        });
        $('#PurchaseOrderSupplierRegistrationId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#PurchaseOrderCustomerId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $("[name*='product_id']").change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $("[name*='device_id']").change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $("[name*='material_id']").change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#PurchaseOrderPurchaseOrderNumber').blur(function() {

            $("#getPurchaseOrderNumber").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_purchase_order_number/' + encodeURIComponent(this.value), function(response, status, xhr) {
                if (response != "") {
                    $('#PurchaseOrderPurchaseOrderNumber').val('');
                    $('#PurchaseOrderPurchaseOrderNumber').addClass('error');
                } else {
                    $('#PurchaseOrderPurchaseOrderNumber').removeClass('error');
                }
            });
        });
    });
    function addAgendaDiv(args) {
        var i = parseInt($('#PurchaseOrderAgendaNumber').val());
        $('#PurchaseOrderAgendaNumber').val();
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_purchase_order_details/" + i, function(data) {
            $('#purchaseOrderDetails_ajax').append(data);
        });
        i = i + 1;
        $('#PurchaseOrderAgendaNumber').val(i);
    }
    function removeAgendaDiv(i) {
        var r = confirm("Are you sure to remove this order details?");
        if (r == true)
        {
            $('#purchaseOrderDetails_ajax' + i).remove();
        }
    }
</script>

<?php 
$qucipro = $this->requestAction('projects/projectdates/'.$this->request->params['named']['project_id']);
echo $this->element('projectdates',array('qucipro'=>$qucipro));
?>

<div id="purchaseOrders_ajax">
    <?php echo $this->Session->flash(); ?><div class="nav">
        <div class="purchaseOrders form col-md-8">
            <h4><?php echo __('Add Purchase Order'); ?></h4>
            <?php echo $this->Form->create('PurchaseOrder', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('title', array('default'=>$poNumber,'label' => __('Title'))); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('purchase_order_number',array('default'=>$poNumber)); ?>
                    <label id="getPurchaseOrderNumber" class="error" style="clear:both" ></label></div>
                <div class="col-md-3"><?php echo $this->Form->input('order_date', array('label' => __('Order Date'))); ?></div>
            </div>
            <div class="row">
                <div class="col-md-12 text-primary"><br /><strong>Note :</strong> Inbound PO is PO from customers, Outbount PO is PO for suppliers. You can add invoice from Inbound PO and Delivery Challans from Outbound PO.<br /><br /></div>
                
                <div class="col-md-4"><?php
                    if($this->request->params['named']['type']){
                        $default = $this->request->params['named']['type'];    
                    }else{
                        $default = 0;
                    }
                    echo $this->Form->input('type', array(
                        'options' => array('Inbound', 'Outbound', 'Other'),
                        'type' => 'radio',
                        'onClick' => 'shhd(this.value)', 'class' => 'checkbox-2',
                        'legend' => __('Select Type'), 'default' => $default));
                    ?>
                </div>
                <div class="col-md-4">
                    <?php if($this->request->params['named']['customer_id']){
                        $cid = $this->request->params['named']['customer_id'];
                    }elseif($qucipro['Customer']['id']){
                        $cid = $qucipro['Customer']['id'];
                    }?>
                    <div id="customer"><?php echo $this->Form->input('customer_id', array('default'=>$cid,'style' => 'width:100%')); ?></div>
                    <div id="supplier"><?php echo $this->Form->input('supplier_registration_id', array('style' => 'width:100%')); ?></div>
                    <div id="other"><?php echo $this->Form->input('other'); ?></div>
                    <script>shhd(<?php echo $default;?>);</script>
                </div>
                <div class="col-md-4"><?php echo $this->Form->input('project_id', array('default'=>$this->request->params['named']['project_id'], 'style' => 'width:100%')); ?></div>
                <?php 
                    if($this->request->params['named']['project_id']){ ?>
                        <div class="clearfix"></div>
                        <div class="col-md-4"><?php echo $this->Form->input('milestone_id', array('default'=>$this->request->params['named']['milestone_id'],'style' => 'width:100%')); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('project_activity_id', array('style' => 'width:100%')); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('cost_category_id', array('style' => 'width:100%')); ?></div>
                        <div class="clearfix"></div>
                    <?php } ?>
                <div class="col-md-12"><?php echo $this->Form->input('details'); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('intimation',array('default'=>'NIL')); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('currency_id',array('default'=>$default_currency)); ?></div>
                <div class="col-md-1">&nbsp;</div>
                <div class="col-md-5"><?php echo $this->Form->input('expected_delivery_date'); ?></div>
                <?php
                    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
                ?>
            </div>
            <br/><br/>

            <div id="purchaseOrderDetails_ajax">
                <div id="purchaseOrderDetails_ajax<?php echo $i; ?>">
                    <div>
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo __('Order Details'); ?><span class="text-danger glyphicon glyphicon-remove danger pull-right" style="font-size:20px;background:none"type="button" onclick='removeAgendaDiv(<?php echo $i; ?>)'></span></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2"><?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.item_number', array('label' => __('Item Number'))); ?></div>
                                    <div class="col-md-6">
                                    <?php
                                    if($this->request->params['named']['material_id']){
                                        $select_type = 'material';  ?>                                        
                                    <?php }else{
                                        $select_type = 'product'; ?>                                        
                                    <?php }
                                        echo $this->Form->input('PurchaseOrderDetail.' . $i . '.product_type', array(
                                            'options' => array(
                                                'product' => 'Product', 'device' => 'Device', 'material' => 'Material', 'other' => 'Other'),
                                            'value'=>$select_type,
                                            'type' => 'radio',
                                            'onClick' => 'choose_product_' . $i . '(this.value)',
                                            'class' => 'checkbox-2',
                                            'legend' => __('Select Type')));
                                        ?>
                                    </div>
                                    <div class="col-md-4">
                                        <div  id="product_dd<?php echo $i; ?>">
                                            <?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.product_id', array('label' => __('Product'))); ?>
                                        </div>
                                        <div  id="device_dd<?php echo $i; ?>">
                                            <?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.device_id', array('label' => __('Device'))); ?>
                                        </div>
                                        <div  id="material_dd<?php echo $i; ?>">
                                            <?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.material_id', array('label' => __('Material'))); ?>
                                        </div>
                                        <div  id="other_dd<?php echo $i; ?>">
                                            <?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.other', array('label' => __('Other'))); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.quantity', array('label' => __('Quantity <div class="pull-right"  id="u_'.$i.'_unit"></div>'))); ?></div>
                                    <div class="col-md-3"><?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.rate', array('label' => __('Rate'))); ?></div>
                                    <div class="col-md-3">
                                        <?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.discount', array('placeholder' => '%', 'label' => __('Discount (% only)'))); ?>
                                        <div id="error<?php echo $i; ?>" style="color:red;"></div>
                                    </div>
                                    <div class="col-md-3"><?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.total', array('label' => __('Total'), 'readonly' => 'readonly')); ?></div>
                                    <div class="col-md-12"><?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.description', array('label' => __('Description'), 'type' => 'textarea')); ?></div>
                                    <div class="col-md-6"><?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?></div>
                                    <div class="col-md-6"><?php echo $this->Form->input('PurchaseOrderDetail.' . $i . '.departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

<script>
    $('#PurchaseOrderDetail<?php echo $i; ?>Rate').blur(function() {
        var total = $('#PurchaseOrderDetail<?php echo $i; ?>Rate').val() * $('#PurchaseOrderDetail<?php echo $i; ?>Quantity').val();
        if ($('#PurchaseOrderDetail<?php echo $i; ?>Discount').val() != null) {
            var discounted = total - (total * $('#PurchaseOrderDetail<?php echo $i; ?>Discount').val() / 100);
        } else {
            var discounted = total;
        }
        $('#PurchaseOrderDetail<?php echo $i; ?>Total').val(discounted);
    });
    $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').change(function() {
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_material_unit/" + $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').val(), function(data) {
            // alert(data);
            $("#u_<?php echo $i;?>_unit").html("("+data+")");
        });        
    });
    $('#PurchaseOrderDetail<?php echo $i; ?>Quantity').blur(function() {
        var total = $('#PurchaseOrderDetail<?php echo $i; ?>Rate').val() * $('#PurchaseOrderDetail<?php echo $i; ?>Quantity').val();
        if ($('#PurchaseOrderDetail<?php echo $i; ?>Discount').val() != null) {
            var discounted = total - (total * $('#PurchaseOrderDetail<?php echo $i; ?>Discount').val() / 100);
        } else {
            var discounted = total;
        }
        $('#PurchaseOrderDetail<?php echo $i; ?>Total').val(discounted);
    });
    $('#PurchaseOrderDetail<?php echo $i; ?>Discount').blur(function() {
        if ($('#PurchaseOrderDetail<?php echo $i; ?>Discount').val() <= 100) {
             $('#error<?php echo $i; ?>').html('').hide();
            var total = $('#PurchaseOrderDetail<?php echo $i; ?>Rate').val() * $('#PurchaseOrderDetail<?php echo $i; ?>Quantity').val();
            if ($('#PurchaseOrderDetail<?php echo $i; ?>Discount').val()) {
                var discounted = total - (total * $('#PurchaseOrderDetail<?php echo $i; ?>Discount').val() / 100);
            } else {
                var discounted = total;
            }
            $('#PurchaseOrderDetail<?php echo $i; ?>Total').val(discounted);
        }else{
                $('#error<?php echo $i; ?>').addClass('error');
                $('#error<?php echo $i; ?>').html('Please enter value less than or equal to 100').show();
            $('#PurchaseOrderDetail<?php echo $i; ?>Discount').addClass("error");
            $('#PurchaseOrderDetail<?php echo $i; ?>Discount').val('');
            
        }
    });


    function choose_product_<?php echo $i; ?>(chk) {
        if (chk == 'product') {
            $("#device_dd<?php echo $i; ?>").hide();
            $("#other_dd<?php echo $i; ?>").hide();
            $("#material_dd<?php echo $i; ?>").hide();
            $("#product_dd<?php echo $i; ?>").show();
            $("#PurchaseOrderDetail<?php echo $i; ?>ProductId_chosen").width('100%');
            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>Other').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>Other').val("");
            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').rules('remove');

            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').rules('add', {
                greaterThanZero: true
            });
            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').next().next('label').remove();
            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').val(-1).trigger('chosen:updated');
            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').next().next('label').remove();
            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').val(-1).trigger('chosen:updated');
        } else if (chk == 'device') {
            $("#product_dd<?php echo $i; ?>").hide();
            $("#other_dd<?php echo $i; ?>").hide();
            $("#material_dd<?php echo $i; ?>").hide();
            $("#device_dd<?php echo $i; ?>").show();
            $("#PurchaseOrderDetail<?php echo $i; ?>DeviceId_chosen").width('100%');
            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>Other').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>Other').val("");
            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').rules('remove');

            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').rules('add', {
                greaterThanZero: true
            });
            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').next().next('label').remove();
            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').val(-1).trigger('chosen:updated');
            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').next().next('label').remove();
            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').val(-1).trigger('chosen:updated');
        } else if (chk == 'material') {
            $("#product_dd<?php echo $i; ?>").hide();
            $("#other_dd<?php echo $i; ?>").hide();
            $("#device_dd<?php echo $i; ?>").hide();
            $("#material_dd<?php echo $i; ?>").show();
            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>Other').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>Other').val("");

            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').rules('add', {
                greaterThanZero: true
            });
            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').next().next('label').remove();
            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').val(-1).trigger('chosen:updated');
            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').next().next('label').remove();
            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').val(-1).trigger('chosen:updated');
        } else if (chk == 'other') {
            $("#product_dd<?php echo $i; ?>").hide();
            $("#device_dd<?php echo $i; ?>").hide();
            $("#material_dd<?php echo $i; ?>").hide();
            $("#other_dd<?php echo $i; ?>").show();
            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').rules('remove');
            $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').val(-1).trigger('chosen:updated');
            $('#PurchaseOrderDetail<?php echo $i; ?>ProductId').val(-1).trigger('chosen:updated');
            $('#PurchaseOrderDetail<?php echo $i; ?>DeviceId').val(-1).trigger('chosen:updated');
            $('#PurchaseOrderDetail<?php echo $i; ?>Other').rules('add', {
                required: true
            });
        }
    }

    $("#PurchaseOrderMilestoneId").on('change',function(){
        $.ajax({
            url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_activities/" + $('#PurchaseOrderMilestoneId').val(),
            success: function(data, result) {
                $('#PurchaseOrderProjectActivityId').find('option').remove().end().append(data).trigger('chosen:updated');
            }
        });
    });

    $("#PurchaseOrderProjectId").on('change',function(){
        $.ajax({
            url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_milestones/" + $('#PurchaseOrderProjectId').val(),
            success: function(data, result) {
                $('#PurchaseOrderMilestoneId').find('option').remove().end().append(data).trigger('chosen:updated');
            }
        });
    });

    <?php if(isset($this->request->params['named']['material_id'])){ ?>
        choose_product_<?php echo $i; ?>('material');
        $('#PurchaseOrderDetail<?php echo $i; ?>MaterialId').val('<?php echo $this->request->params["named"]["material_id"]?>').trigger('chosen:updated');
    <?php }else{ ?>
        choose_product_<?php echo $i; ?>('product');
    <?php } ?>
    
</script>

                </div>
            <?php $i++; $j++; ?>
            </div>
            <div class="col-md-6"><?php echo $this->Form->input('agendaNumber', array('type' => 'hidden', 'value' => $i)); ?></div>
            <?php echo $this->Form->button('Add New Order Detail', array('label' => false, 'type' => 'button', 'div' => false, 'class' => 'btn btn-md btn-info pull-right', 'onclick' => 'addAgendaDiv()')); ?>
            <div class="clearfix">&nbsp;</div>

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#purchaseOrders_ajax', 'async' => 'false','id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>

<script>
    var startDateTextBox = $('#PurchaseOrderOrderDate');
    var endDateTextBox = $('#PurchaseOrderExpectedDeliveryDate');

    startDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
        changeMonth: true,
        changeYear: true,
        onClose: function(dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate) {
                    endDateTextBox.val(startDateTextBox.val());
                }
            }
            else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function(selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
    endDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
        changeMonth: true,
        changeYear: true,
        onClose: function(dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.val(endDateTextBox.val());
            }
            else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function(selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
</script>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
            <div class="row">
                <div class="col-md-12">
                    <h4><?php echo __('Material Stock Status');?></h4>
                    <table class="table table-bordered table-responsive table-condenced">
                        <tr>
                            <th><?php echo __('Material');?></th>
                            <th><?php echo __('Available Quantity');?></th>                            
                        </tr>
                    <?php 
                    $x = 0;
                    foreach ($stocks as $stock) { ?>
                    <tr>
                            <td><?php echo $stock['material']['name'];?></td>
                            <td><?php echo $stock['stock'];?></td>                            
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

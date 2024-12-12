<div class="">
    <h4><?php echo __('Purchase Dashboard'); ?></h3>
</div>
<div class="main nav panel">
    <div class="nav panel-body">
        <div class="row  panel-default">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Sub-Contractor Registration Form'); ?></h4>
                                <p><?php echo __('Add Sub-Contractor / Contractor or Vendor\'s from here'); ?><br/>
                                    <?php echo $this->Html->link(__('Supplier Categories'), array('controller' => 'supplier_categories', 'action' => 'index'), array('class' => 'text-primary'));?>
                                    <br/></p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'supplier_registrations', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'supplier_registrations', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/SupplierRegistration/count'), array('controller' => 'supplier_registrations', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Sub-Contractor'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Purchase Order'); ?></h4>
                                <p>
                                    <?php
                                        echo __('To add List Of Acceptable Supplier make sure you have already added ');
                                        echo $this->Html->link(__('Suppliers'), array('controller' => 'supplier_registrations', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Customers'), array('controller' => 'customers', 'action' => 'index'), array('class' => 'text-primary')). ', ';
                                        echo $this->Html->link(__('Products'), array('controller' => 'products', 'action' => 'index'), array('class' => 'text-primary')). ', ';
                                        echo $this->Html->link(__('Materials'), array('controller' => 'materials', 'action' => 'index'), array('class' => 'text-primary')). ', ';
                                        echo $this->Html->link(__('Currency'), array('controller' => 'currencies', 'action' => 'index'), array('class' => 'text-primary')). ', ';
                                    ?><br/></p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'purchase_orders', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'purchase_orders', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/PurchaseOrder/count'), array('controller' => 'purchase_orders', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Purchase Orders'))); ?><script>$('.btn').tooltip();</script>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption ">
                                <h4><?php echo __('Delivery Challans'); ?></h4>
                                <p>
                                    <?php
                                        echo __('To add Delivery Challans make sure that you have added ');
                                        echo $this->Html->link(__('Purchase Orders'), array('controller' => 'purchase_orders', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Branches'), array('controller' => 'branches', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Departments'), array('controller' => 'departments', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Customers'), array('controller' => 'customers', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Suppliers'), array('controller' => 'supplier_registrations', 'action' => 'index'), array('class' => 'text-primary'));
                                    ?><br/></p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'delivery_challans', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'delivery_challans', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/DeliveryChallan/count'), array('controller' => 'delivery_challans', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Delivery Challans'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="cleafix">&nbsp;</div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Supplier Evaluation / Re-Evaluation'); ?></h4>
                                <p>
                                    <?php
                                        echo __('To evaluate any supplier make sure you have already added ');
                                        echo $this->Html->link(__('Delivery Challans'), array('controller' => 'delivery_challans', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Purchase Orders'), array('controller' => 'purchase_orders', 'action' => 'index'), array('class' => 'text-primary'));
                                    ?><br/></p>
                                <p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Evaluate'), array('controller' => 'supplier_evaluation_reevaluations', 'action' => 'index'), array('class' => 'btn btn-default btn-primary')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/SupplierEvaluationReevaluation/count'), array('controller' => 'supplier_evaluation_reevaluations', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Supplier Evaluation / Re-Evaluation'))); ?>
                                    <br/>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Summary of Supplier Evaluation'); ?></h4>
                                <p>
                                    <?php echo __('Based on the previous evaluations, this list will be auto populated.'); ?><br/></p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'summery_of_supplier_evaluations', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/SummeryOfSupplierEvaluation/count'), array('controller' => 'summery_of_supplier_evaluations', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Supplier Evaluation Summaries'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('List of Acceptable Suppliers'); ?></h4>
                                <p>
                                    <?php
                                        echo __('To add List Of Acceptable Supplier make sure you have already added ');
                                        echo $this->Html->link(__('Suppliers'), array('controller' => 'supplier_registrations', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Supplier Categories'), array('controller' => 'supplier_categories', 'action' => 'index'), array('class' => 'text-primary'));
                                    ?><br/></p>
                                <div class="btn-group">
                                    <?php echo $this->Html->link(__('Add'), array('controller' => 'list_of_acceptable_suppliers', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'list_of_acceptable_suppliers', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/ListOfAcceptableSupplier/count'), array('controller' => 'list_of_acceptable_suppliers', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Acceptable Suppliers'))); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">    
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><?php echo __('Invoices'); ?></h4>
                                <p>  <strong>Note : </strong>
                                <small>To add invoice, create Inbound Purchase Order first and then Generate Invoice link from dropdown menu of the PO.</small><br/>                                  
                                    <?php
                                        echo __('To add invoices make sure you have already added ');
                                        // echo $this->Html->link(__('Suppliers'), array('controller' => 'supplier_registrations', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        echo $this->Html->link(__('Invoice Settings'), array('controller' => 'invoice_settings', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        // echo $this->Html->link(__('Purchase Orders'), array('controller' => 'purchase_orders', 'action' => 'index'), array('class' => 'text-primary'));
                                    ?></p>
                                <div class="btn-group">
                                    <?php //echo $this->Html->link(__('Add'), array('controller' => 'invoices', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(__('See All'), array('controller' => 'invoices', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                    <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/Invoice/count'), array('controller' => 'invoices', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('Invoices'))); ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-4">
                            <div class="thumbnail">
                                <div class="caption">
                                    <h4><?php echo __('List of Products'); ?></h4>
                                    <p>
                                        <?php
                                            echo __('Before you add new product you may like to add required materials for the products ');
                                            echo $this->Html->link(__('Materials'), array('controller' => 'materials', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                        ?><br /><br /><br />
                                    </p>
                                    <div class="btn-group">
                                        <?php echo $this->Html->link(__('Add'), array('controller' => 'products', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(__('See All'), array('controller' => 'products', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/Product/count'), array('controller' => 'products', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Softwares'))); ?>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    <div class="col-md-4">
                            <div class="thumbnail">
                                <div class="caption">
                                    <h4><?php echo __('Raw Material'); ?></h4>
                                    <p>
                                        <?php
                                            echo __('Raw material is required for products. You can add required raw materials here and add those to existing products.');
                                        ?><br /><br />
                                    <div class="btn-group">
                                        <?php echo $this->Html->link(__('Add'), array('controller' => 'materials', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(__('See All'), array('controller' => 'materials', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/Material/count'), array('controller' => 'materials', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('FlinkISO Users'))); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                     

                </div>
                <div class="cleafix">&nbsp;</div>
<!--
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="list-group-item-heading"><?php echo __('Available Quality Documents (PURCHASE Department)'); ?><span class="glyphicon glyphicon-eye-open pull-right"></span></h3>
                                <p class="list-group-item-text"><?php echo __('You can add/view your company Quality Manuals / Procedures / Objectives / Records / Policies for Purchase department by clicking on the links below.') . '<br />' . __('These documents are available for all users.'); ?></p>
                            </div>
                            <div class="panel-body">
                                <?php echo $this->Element('files',array('filesData' => array('files'=>$files,'action'=>$this->action))); ?>                  
                            </div>
                        </div>
                    </div>
                </div>
-->
                <br/>

                <div class="row" style="display: none">
                    <div class="col-md-12">
                        <div class="alert alert-info  fade in message"><h4>Why do we need this?</h4>
                            <p>Some Management Representative notes on this subject should appear here. <br /> We can extract these from Helps section</p>
                        </div>
                    </div>


                </div>
            </div>
            <script>
                $(function() {
                    $("#tabs").tabs({
                        beforeLoad: function(event, ui) {
                            ui.jqXHR.error(function() {
                                ui.panel.html(
                                        "Error Loading ... " +
                                        "Please contact administrator.");
                            });
                        }
                    });
                });
            </script>

            <div class="col-md-4">
                <?php echo $this->element('helps'); ?>
            </div>
        </div>
    </div>

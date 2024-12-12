
<div  id="main">
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script'); ?>
<script type="text/javascript">

    $.validator.setDefaults({        
        ignore: null,
        errorPlacement: function(error, element) {
            if (
                $(element).attr('name') == 'data[ListOfAcceptableSupplier][supplier_registration_id]' ||
                $(element).attr('name') == 'data[ListOfAcceptableSupplier][supplier_category_id]') 
            {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            CKEDITOR.instances['ListOfAcceptableSupplierEvaluationDetails'].updateElement();
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?>list_of_acceptable_suppliers/add/<?php echo $supplierRegistration['SupplierRegistration']['id'] ?>",
                type: 'POST',
                target: '#main',
                error: function(request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
        });
    }
    });

    $().ready(function() {
        $("#ListOfAcceptableSupplierSupplierEvaluationTemplateId").change(function(){
            $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_template/" + $(this).val(), function(data) {
                CKEDITOR.instances['ListOfAcceptableSupplierEvaluationDetails'].setData(data)
            });
            
        });
        $("#submit-indicator").hide();
        
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#ListOfAcceptableSupplierAddForm').validate({            
            rules: {
            "data[ListOfAcceptableSupplier][supplier_registration_id]": {
                greaterThanZero: true,
            },
            "data[ListOfAcceptableSupplier][supplier_category_id]": {
                greaterThanZero: true,
            },
            }
        });
        
        $('#ListOfAcceptableSupplierSupplierRegistrationId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
            $(this).next().next('label').remove();
            }
        });
        $('#ListOfAcceptableSupplierSupplierCategoryId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
            $(this).next().next('label').remove();
            }
        });
    });
</script>
    <?php echo $this->Session->flash(); ?>
    <div class="supplierEvaluationReevaluations ">
        <?php if($supplierRegistrations) {?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="pane-title"><h4>Supplier Information
                            <?php echo $this->Html->link('View Full Details', array('controller' => 'supplier_registrations', 'action' => 'view', $supplierRegistration['SupplierRegistration']['id']), array('target' => '_blank', 'class' => 'label btn-sm btn-info')); ?></h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class=" col-md-6">
                            <table class="table table-responsive">
                                <tr><td><?php echo __('Title'); ?></td>
                                    <td>
                                        <?php echo $supplierRegistration['SupplierRegistration']['title']; ?>
                                        &nbsp;
                                    </td></tr>
                                <tr><td><?php echo __('Number'); ?></td>
                                    <td>
                                        <?php echo $supplierRegistration['SupplierRegistration']['number']; ?>
                                        &nbsp;
                                    </td></tr>
                                <tr><td><?php echo __('Type Of Company'); ?></td>
                                    <td>
                                        <?php echo $supplierRegistration['SupplierRegistration']['type_of_company']; ?>
                                        &nbsp;
                                    </td></tr>
                                <tr><td><?php echo __('Contact Person Office'); ?></td>
                                    <td>
                                        <?php echo $supplierRegistration['SupplierRegistration']['contact_person_office']; ?>
                                        &nbsp;
                                    </td></tr>
                            </table>
                        </div>
                        <div class=" col-md-6">
                            <table class="table table-responsive">

                                <tr><td><?php echo __('Office Telephone'); ?></td>
                                    <td>
                                        <?php echo $supplierRegistration['SupplierRegistration']['office_telephone']; ?>
                                        &nbsp;
                                    </td></tr>
                                <tr><td><?php echo __('Work Telephone'); ?></td>
                                    <td>
                                        <?php echo $supplierRegistration['SupplierRegistration']['work_telephone']; ?>
                                        &nbsp;
                                    </td></tr>
                                <tr><td><?php echo __('Office Fax'); ?></td>
                                    <td>
                                        <?php echo $supplierRegistration['SupplierRegistration']['office_fax']; ?>
                                        &nbsp;
                                    </td></tr>
                                <tr><td><?php echo __('Publish'); ?></td>
                                    <td>
                                        <?php if ($supplierRegistration['SupplierRegistration']['publish'] == 1) { ?>
                                            <span class="fa fa-check"></span>
                                        <?php } else { ?>
                                            <span class="fa fa-ban"></span>
                                        <?php } ?>&nbsp;</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($evaluations) {?>
                <div class="row">
                    <div class="col-md-12">
                        <h2><?php echo __('Evaluation History');?></h2>
                        <table class="table table-bordered table-responsove">
                            <tr>
                                <th><?php echo __('Evaluation Date');?></th>
                                <th><?php echo __('Done By');?></th>
                                <th><?php echo __('Suppier Category');?></th>
                                <th><?php echo __('Remarks');?></th>
                            </tr>
                            <?php foreach ($evaluationHistories as $evaluationHistory) { ?>
                            <tr>
                                <td><?php echo date('d M Y',strtotime($evaluationHistory['SummeryOfSupplierEvaluation']['evaluation_date']));?></td>
                                <td><?php echo $evaluationHistory['Employee']['name'];?></td>
                                <td><?php echo $evaluationHistory['SupplierCategory']['name'];?></td>
                                <td><?php echo $evaluationHistory['SummeryOfSupplierEvaluation']['remarks'];?></td>
                            </tr>
                            <?php } ?>
                        </table>
                        <h2><?php echo __('Current Results');?></h2>
                        <table class="table table-bordered table-responsove">
                            <tr>
                                <th><?php echo __('Purchase Order');?></th>
                                <th><?php echo __('Delivery');?></th>
                                <th><?php echo __('Order Date');?></th>
                                <th><?php echo __('Material');?></th>
                                <th><?php echo __('Required Delivery Date');?></th>
                                <th><?php echo __('Actual Delivery Date');?></th>
                                <th><?php echo __('Quantity Supplied');?></th>
                                <th><?php echo __('Quantity Accepted');?></th>
                                <th><?php echo __('Quantity Rejected');?></th>
                                <th><?php echo __('Delay');?></th>
                                <th><?php echo __('Score');?></th>
                            </tr>
                            <?php foreach ($evaluations as $evaluation) { ?>
                            <tr>
                                <td><?php echo $evaluation['PurchaseOrder']['name'];?></td>
                                <td><?php echo $evaluation['DeliveryChallan']['name'];?></td>
                                <td><?php echo $evaluation['PurchaseOrder']['order_date'];?></td>
                                <td><?php echo $evaluation['Material']['name'];?></td>
                                <td><?php echo $evaluation['SupplierEvaluationReevaluation']['required_delivery_date'];?></td>
                                <td><?php echo $evaluation['SupplierEvaluationReevaluation']['actual_delivery_date'];?></td>
                                <td><?php echo $evaluation['SupplierEvaluationReevaluation']['quantity_supplied'];?></td>
                                <td><?php echo $evaluation['SupplierEvaluationReevaluation']['quantity_accepted'];?></td>
                                <td><?php echo $evaluation['SupplierEvaluationReevaluation']['quantity_supplied'] - $evaluation['SupplierEvaluationReevaluation']['quantity_accepted'];?></td>
                                <td>
                                    <?php
                                        $datetime1 = date_create(date('Y-m-d',strtotime($evaluation['SupplierEvaluationReevaluation']['required_delivery_date'])));
                                        $datetime2 = date_create(date('Y-m-d',strtotime($evaluation['SupplierEvaluationReevaluation']['actual_delivery_date'])));
                                        
                                        $interval = date_diff($datetime1, $datetime2);
                                        echo $interval->format("%R%a");
                                        // echo ">>" .$interval;
                                        // echo ">>".$datetime1;
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>

            <?php } else { ?> 
                <div class="alert alert-danger">
                    <h4>No data found for evaluation </h4>
                    <p>To evaluate any supplier, system requires Purchase Orders, Delivery Challas as well as information regarding Quantity Orderd, Quantity Supplier, Quantity Accepted and delivery delays if any.</p>
                    <p>Based on your entries in Purchase Orders and Delivery Challas, system automatically adds you supplier's details, challan details & purchase order details in Evaluation Table.</p>
                    <p>You will need to then add, Accepted Quantity manually.</p>
                    <p>Based of that graph will be generated which would enable you to evaluate and add each supplier to correct categories.</p>
                </div>
            </div>
        <?php } ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title"><h4>Evaluate</h4></div>
                        </div>
                        <div class="panel-body" id="evaluate">
                            <div id="listOfAcceptableSuppliers_ajax">
                                <?php echo $this->Session->flash(); ?>
                                <div class="nav">
                                    <div class="listOfAcceptableSuppliers form col-md-8">
                                        <h4><?php echo __('Add List Of Acceptable Supplier'); ?></h4>
                                        <?php echo $this->Form->create('ListOfAcceptableSupplier', array('controller' => 'list_of_acceptabe_suppliers', 'action' => 'add', $supplierRegistration['SupplierRegistration']['id']), array('role' => 'form', 'class' => 'form', 'default' => true)); ?>

                                        <div class="row">
                                            <?php echo $this->Form->hidden('supplier_registration_id', array('value' => $supplierRegistration['SupplierRegistration']['id'])); ?>
                                            <div class="col-md-12"><?php echo $this->Form->input('supplier_category_id', array('style' => 'width:100%', 'label' => __('Supplier Category'))); ?></div>
                                            <div class="col-md-12"><?php echo $this->Form->input('supplier_evaluation_template_id', array('style' => 'width:100%', 'label' => __('Select Evaluation Template'))); ?></div>
                                            <div class="col-md-12"><?php echo $this->Form->input('evaluation_details', array('label' => __('Evaluation Details'))); ?></div>
                                            <div class="col-md-12"><?php echo $this->Form->input('remarks', array('label' => __('Remarks'))); ?></div>
                                            <?php echo $this->Form->input('employee_id', array('type' => 'hidden', 'value' => $this->Session->read('User.employee_id'))); ?>
                                            <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                                            <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                                            <div class="col-md-6"><?php echo $this->Form->input('approved_by',array('type'=>'select','options'=>$PublishedEmployeeList,'default'=>$this->Session->read('User.employee_id')));?></div>
                                            <div class="col-md-6"><?php echo $this->Form->input('prepared_by',array('type'=>'select','options'=>$PublishedEmployeeList));?></div>
                                        </div>
                                        <?php echo $this->Form->input('publish', array('label' => __('Publish')));?><br/><br/>
                                        <?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#main', 'async' => 'false','id'=>'submit_id')); ?>
                                        <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                                        <?php echo $this->Form->end(); ?>
                                        <?php echo $this->Js->writeBuffer(); ?>

                                    </div>
                                    <div class="col-md-4">
                                        <p><?php echo $this->element('helps'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } else { ?>

            <?php } ?>
        </div>
        <?php echo $this->Js->writeBuffer(); ?>
    </div>
</div>
<script type="text/javascript">
    CKEDITOR.replace('ListOfAcceptableSupplierEvaluationDetails', {
        filebrowserBrowseUrl: '<?php echo Router::url("/", true); ?>img/ckeditor/browse.php?dir=<?php echo WWW_ROOT ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>&path=<?php echo Router::url("/", true); ?>img/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
        filebrowserUploadUrl: '<?php echo Router::url("/", true); ?><?php echo $this->request->params["controller"] ?>/ckeditor/<?php echo $this->request->params["controller"] ?>/<?php echo $this->Session->read("User.id");?>',
        toolbar: [
            { name: 'align', items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting' ] },
            { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
            { name: 'insert', items: [ 'Image', 'Table','HorizontalRule','PageBreak'] },
            { name: 'tools', items: ['Radio','Checkbox','TextField','Textarea','Selection', '-', 'Maximize','Source' ] },
            '/',
            { name: 'styles', items: [ 'Format', 'Font', 'FontSize', 'lineheight'] },
            { name: 'links', items: [ 'Link', 'Unlink' ] },
            { name: 'editing', items: [ 'Scayt' ] },
            {name: 'document', items: ['Preview', '-', 'Templates']},
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            
        ],
        customConfig: '',
        disallowedContent: 'img{width,height,float}',
        extraAllowedContent: 'img[width,height,align]',
        extraPlugins: 'tableresize,lineheight,autosave,imagerotate,pastefromexcel,htmlbuttons,forms,fakeobjects',
        height: 800,
        contentsCss: [ '<?php echo Router::url("/", true); ?>css/contents.css', '<?php echo Router::url("/", true); ?>css/mystyles.css' ],
        bodyClass: 'document-editor',
        format_tags: 'p;h1;h2;h3;pre',
        removeDialogTabs: 'image:advanced;link:advanced',
        enterMode:2,forceEnterMode:false,shiftEnterMode:1,
        stylesSet: [
            /* Inline Styles */
            { name: 'Marker', element: 'span', attributes: { 'class': 'marker' } },
            { name: 'Cited Work', element: 'cite' },
            { name: 'Inline Quotation', element: 'q' },
            /* Object Styles */
            {
                name: 'Special Container',
                element: 'div',
                styles: {
                    padding: '5px 10px',
                    background: '#eee',
                    border: '1px solid #ccc'
                }
            },
            {
                name: 'Compact table',
                element: 'table',
                attributes: {
                    cellpadding: '5',
                    cellspacing: '0',
                    border: '1',
                    bordercolor: '#ccc'
                },
                styles: {
                    'border-collapse': 'collapse'
                }
            },
            { name: 'Borderless Table', element: 'table', styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' } },
            { name: 'Square Bulleted List', element: 'ul', styles: { 'list-style-type': 'square' } }
        ]
    });


</script>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
<?php echo $this->Js->writeBuffer(); ?>

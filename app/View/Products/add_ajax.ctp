<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {

            if ($(element).attr('name') == 'data[Product][branch_id]')
                $(element).next().after(error);
            else if ($(element).attr('name') == 'data[Product][department_id]') {
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Product][product_category_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function (form) {

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
                error: function (request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
        },
    });

    $().ready(function () {
        $("#submit-indicator").hide();
        $.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        var $validator = $('#ProductAddAjaxForm').validate({
            ignore: null,
            rules: {
                "data[Product][branch_id]": {
                    greaterThanZero: true,
                    required: true
                },
                "data[Product][department_id]": {
                    greaterThanZero: true,
                    required: true

                },
                "data[Product][product_category_id]": {
                    greaterThanZero: true,
                    required: true

                },
            }
        });

        $('#ProductDepartmentId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProductBranchId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
         $('#ProductCategoryId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>

<div id="products_ajax">
    <?php echo $this->Session->flash(); ?><div class="nav">
        <div class="products form col-md-8">
            <h4><?php echo __('Add Product'); ?></h4>
            <?php echo $this->Form->create('Product', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            
                <div class="row">
                    <div class="col-md-6"><?php echo $this->Form->input('name');?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('product_category_id');?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('description');?></div>
                </div>
                <div class="row">
                    <!-- <div class="col-md-6"><?php echo $this->Form->input('ProductMaterial.material_id', array('name' => 'ProductMaterial.material_id[]', 'type' => 'select',  'options' => $PublishedMaterialList, 'label' => __('Add Required Material'), 'style' => 'width:100%')); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('ProductMaterial.quantity', array('name' => 'ProductMaterial.material_id[]', 'type' => 'select',  'options' => $PublishedMaterialList, 'label' => __('Add Required Material'), 'style' => 'width:100%')); ?></div> -->
                    <div class="col-md-12">
                        <h3><?php echo __('Material required with scale (Qty per product)');?></h3>
                        <table class="table table-responsive table-bordered table-condensed">
                            <tr>
                                <th><?php echo __('Material');?></th>
                                <th><?php echo __('Quantity');?></th>
                                <th><?php echo __('Unit');?></th>                                
                            </tr>
                            <?php 
                            $x = 0;
                            foreach($PublishedMaterialList as $material){ ?>
                            <tr>
                                <td>
                                    <?php echo $material['Material']['name'];?>
                                    <?php echo $this->Form->hidden('ProductMaterial.'.$x.'.material_id',array('default'=>$material['Material']['id']))?>
                                </td>
                                <td><?php echo $this->Form->input('ProductMaterial.'.$x.'.quantity',array('label'=>false, 'default'=>0))?></td>
                                <td><?php echo $material['Unit']['name'];?></td>
                            </tr>
                            <?php $x++; } ?>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'),'type'=>'select','options'=>$PublishedBranchList)); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'),'type'=>'select','options'=>$PublishedDepartmentList)); ?></div>
                </div>

                <?php
                    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
                ?>

                <?php
                    if ($showApprovals && $showApprovals['show_panel'] == true) {
                        echo $this->element('approval_form');
                    } else {
                        echo $this->Form->input('publish', array('label' => __('Publish')));
                    }
                ?>
                <?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#products_ajax', 'async' => 'false','id'=>'submit_id')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
            
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        beforeSend: function () {
            $("#busy-indicator").show();
        },
        complete: function () {
            $("#busy-indicator").hide();
        }
    });
</script>

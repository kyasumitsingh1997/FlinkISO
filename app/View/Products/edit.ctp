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
        }
    });
    $().ready(function () {

        $.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        var $validator = $('#ProductEditForm').validate({
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
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#ProductEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#ProductEditForm").submit();
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
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="products form col-md-8 panel">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Product'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>

            </h4>
            <?php echo $this->Form->create('Product', array('role' => 'form', 'class' => 'form')); ?>

            <fieldset>
                <div class="row">
                    <?php echo $this->Form->input('id'); ?>
                    <div class="col-md-6"><?php echo $this->Form->input('name');?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('product_category_id');?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('description');?></div>
                </div>
                <div class="row">
                    
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
                            foreach($productMaterials as $material){ ?>
                            <tr>
                                <td>
                                    <?php echo $material['Material']['Material']['name'];?>
                                    <?php echo $this->Form->hidden('ProductMaterial.'.$x.'.material_id',array('default'=>$material['Material']['id']))?>
                                </td>
                                <td><?php echo $this->Form->input('ProductMaterial.'.$x.'.quantity',array('label'=>false, 'default'=>$material['ProductMaterial']['quantity']))?></td>
                                <td><?php echo $material['Material']['Unit']['name'];?></td>
                            </tr>
                            <?php $x++; } 
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
                    <div class="col-md-6"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'))); ?></div>
                </div>
                <?php
                    if ($showApprovals && $showApprovals['show_panel'] == true) {
                        echo $this->element('approval_form');
                    } else {
                        echo $this->Form->input('publish', array('label' => __('Publish')));
                    }
                ?>
                <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
            </fieldset>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#products_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>


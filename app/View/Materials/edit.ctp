<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<?php
    // Configure::write('debug',1);
    // debug($this->data);
?>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[Material][unit_id]'){
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        }
    });

    $().ready(function() {
        $.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        var $validator = $('#ProductEditForm').validate({
            ignore: null,
            rules: {
                "data[Material][unit_id]": {
                    greaterThanZero: true,
                    required: true

                }
            }
        });
        
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#MaterialEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#MaterialEditForm").submit();
             }
        });
        $('#MaterialUnitId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#MaterialName').blur(function() {

            $("#getMaterial").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_material_name/' + encodeURIComponent(this.value) + '/<?php echo $this->request->params["pass"][0] ?>' , function(response, status, xhr) {
                if (response != "") {
                    $('#MaterialName').val('');
                    $('#MaterialName').addClass('error');
                } else {
                    $('#MaterialName').removeClass('error');
                }
            });
        });
    });

</script>

<div id="materials_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel">
        <div class="materials form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Material'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>

            </h4>
            <?php echo $this->Form->create('Material', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>
            <div class="row">
                    <div class="col-md-4"><?php echo $this->Form->input('name'); ?></div>
                    <div class="col-md-2"><?php echo $this->Form->input('item_code'); ?></div>
                    <div class="col-md-3"><?php echo $this->Form->input('min_stock',array()); ?></div>
                    <div class="col-md-3"><?php echo $this->Form->input('unit_id',array()); ?></div>
                    <div class="col-md-12"><label id="getMaterial" class="error" style="clear:both" ></label></div>
            </div>
            <div class="row">
                <div class="col-md-12"><h5><?php echo __('Add to List of materials with shelf life?'); ?></h5><span class="hel-text"><?php echo __('Data added below will be saved to "Material List with Shelf Life" table.'); ?></span></div>
                <div class="col-md-6"><?php echo $this->Form->input('MaterialListWithShelfLife.shelflife_by_manufacturer', array('default' => $materialShelfLifeMfg)); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('MaterialListWithShelfLife.shelflife_by_company', array('default' => $materialShelfLifeCo)); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('MaterialListWithShelfLife.remarks', array('default' => $materialShelfLifeRem)); ?></div>
            </div>
            <div class="row">
                <div class="col-md-12"><br /></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <h5><?php echo __('Quality Check Required?'); ?>
                        </h5>
                        <span><?php echo $this->Form->input('qc_required', array('div' => array('style' => array('padding-left:0')), 'legend' => false, 'type' => 'radio', 'options' => array(1 => 'Yes', 0 => 'No'), 'default' => 0)); ?></span>

                    </div>
                </div>
            </div>

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
            ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#materials_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

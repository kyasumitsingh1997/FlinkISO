<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[FireExtinguisher][fire_extinguisher_type_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
    });

    $().ready(function() {
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#FireExtinguisherEditForm').validate({
            rules: {
                "data[FireExtinguisher][fire_extinguisher_type_id]": {
                    greaterThanZero: true,
                },
            }
        });
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#FireExtinguisherEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#FireExtinguisherEditForm").submit();
             }
        });
        $('#FireExtinguisherFireExtinguisherTypeId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>

<div id="fireExtinguishers_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="fireExtinguishers form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Fire Extinguisher'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
            </h4>
            <?php echo $this->Form->create('FireExtinguisher', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>

            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('name', array('label' => __('Name'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('fire_extinguisher_type_id', array('style' => 'width:100%', 'label' => __('Fire Extinguisher Type'))); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('description', array('label' => __('Description'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('company_name', array('label' => __('Company Name'))); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('purchase_date', array('label' => __('Purchase Date'))); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('expeiry_date', array('label' => __('Expiry Date'))); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('warrenty_expiry_date', array('label' => __('Warranty Expiry Date'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('model_type', array('label' => __('Model Type'))); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('other_remarks', array('label' => __('Other Remarks'))); ?></div>
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

        </div>
<script>
    $("[name*='purchase_date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    $("[name*='purchase_date']").datepicker('option', 'maxDate', 0);

    $("[name*='expeiry_date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    $("[name*='expeiry_date']").datepicker('option', 'minDate', 0);

    $("[name*='warrenty_expiry_date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    $("[name*='warrenty_expiry_date']").datepicker('option', 'minDate', 0);
</script>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#fireExtinguishers_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

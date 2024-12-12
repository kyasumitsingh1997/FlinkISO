<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<script>
    $().ready(function() {
        $('#MaterialApproveForm').validate();
         $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#MaterialApproveForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
		 $("#MaterialApproveForm").submit();
             }

        });
        $('#MaterialName').blur(function() {

            $("#getMaterial").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_material_name/' + encodeURIComponent(this.value), function(response, status, xhr) {
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
            <h4><?php echo $this->element('breadcrumbs') . __('Approve Material'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>

            </h4>
            <?php echo $this->Form->create('Material', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>
            <div class="row">
                    <div class="col-md-4"><?php echo $this->Form->input('name'); ?></div>
                    <div class="col-md-2"><?php echo $this->Form->input('item_code'); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('min_stock',array()); ?></div>
                    <div class="col-md-12"><label id="getMaterial" class="error" style="clear:both" ></label></div>
            </div>
            <div class="row">
                <div class="col-md-12"><h5><?php echo __('Add to List of materials with shelf life?'); ?></h5><span class="hel-text"><?php echo __('Data added below will be saved to "Material List with Shelf Life" table.'); ?></span></div>
                <div class="col-md-6"><?php echo $this->Form->input('MaterialListWithShelfLife.shelflife_by_manufacturer', array('default' => $materialShelfLifeMfg)); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('MaterialListWithShelfLife.shelflife_by_company', array('default' => $materialShelfLifeCo)); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('MaterialListWithShelfLife.remarks', array('default' => $materialShelfLifeRem)); ?></div>
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

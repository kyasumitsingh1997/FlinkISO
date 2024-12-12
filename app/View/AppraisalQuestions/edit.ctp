<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults();

    $().ready(function () {
        $('#AppraisalQuestionEditForm').validate();
	$("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#AppraisalQuestionEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#AppraisalQuestionEditForm").submit();
             }

        });
    });
</script>

<div id="appraisalQuestions_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="appraisalQuestions form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Appraisal Question'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
            </h4>
            <?php echo $this->Form->create('AppraisalQuestion', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>
            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('question'); ?></div>
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
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#appraisalQuestions_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

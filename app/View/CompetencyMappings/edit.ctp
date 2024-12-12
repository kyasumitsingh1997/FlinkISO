<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[CompetencyMapping][employee_id]' ||
                $(element).attr('name') == 'data[CompetencyMapping][education_id]' ||
                $(element).attr('name') == 'data[CompetencyMapping][actual_education]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
    });

    $().ready(function () {
        $("#submit-indicator").hide();
         $("#submit_id").click(function(){
             if($('#CompetencyMappingEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#CompetencyMappingEditForm").submit();
             }

        });
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#CompetencyMappingEditForm').validate({
            rules: {
                "data[CompetencyMapping][employee_id]": {
                    greaterThanZero: true,
                },
                "data[CompetencyMapping][education_id]": {
                    greaterThanZero: true,
                },
                "data[CompetencyMapping][actual_education]": {
                    greaterThanZero: true,
                },
            }
        });
        $('#CompetencyMappingEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CompetencyMappingEducationId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CompetencyMappingActualEducation').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>

<div id="competencyMappings_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="competencyMappings form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Competency Mapping'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>

            </h4>

            <?php echo $this->Form->create('CompetencyMapping', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>

            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('employee_id', array('label' => __('Employee'), 'options' => $PublishedEmployeeList)); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('education_id', array('label' => __('Education'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('actual_education', array('options' => $educations, 'label' => __('Actual Education'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('experience_required', array('label' => __('Experience Required'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('actual_experience', array('label' => __('Actual Experience'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('skills_required', array('label' => __('Skills Required'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('skills_possesed', array('label' => __('Skills Possessed'))); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('remarks', array('label' => __('Remarks'))); ?></div>
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
    $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
    });
</script>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#competencyMappings_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

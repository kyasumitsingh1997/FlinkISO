<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[Training][course_id]' ||
                $(element).attr('name') == 'data[Training][trainer_id]' ||
                $(element).attr('name') == 'data[Training][course_type_id]' ||
                $(element).attr('name') == 'EmployeeTraining.employee_id[]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
    });

    $().ready(function () {
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#TrainingEditForm').validate({
            rules: {
                "data[Training][course_id]": {
                    greaterThanZero: true,
                },
                "data[Training][trainer_id]": {
                    greaterThanZero: true,
                },
                "data[Training][course_type_id]": {
                    greaterThanZero: true,
                },
                "EmployeeTraining.employee_id[]": {
                    greaterThanZero: true,
                    required: true,
                },
            }
        });
	$("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#TrainingEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#TrainingEditForm").submit();
             }
        });
        $('#TrainingCourseId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#TrainingTrainerId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#TrainingCourseTypeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#EmployeeTrainingEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>

<div id="trainings_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="trainings form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Training'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>

                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator', 'class' => 'pull-right')); ?>
            </h4>
            <?php echo $this->Form->create('Training', array('role' => 'form', 'class' => 'form')); ?>

            <?php echo $this->Form->input('id'); ?>
            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('course_id', array('style' => 'width:100%', 'label' => __('Course'))); ?></div>
                <div id="get_details"><?php echo $this->element('get_details'); ?></div>
                <div class="col-md-12"></div>
                <div class="col-md-6"><?php echo $this->Form->input('trainer_id', array('style' => 'width:100%', 'label' => __('Trainer'))); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('start_date_time', array('label' => __('Start Date-Time'))); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('end_date_time', array('label' => __('End Date-Time'))); ?></div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12"><?php echo $this->Form->input('EmployeeTraining.employee_id', array('name' => 'EmployeeTraining.employee_id[]', 'type' => 'select', 'multiple', 'options' => $PublishedEmployeeList, 'label' => __('Attendee'), 'style' => 'width:100%', 'default' => $selectedEmp)); ?></div>
                </div>
            </div>

<script>
    $('document').ready(function() {
        $("#TrainingCourseId").change(function() {
            $("#get_details").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_details/' + $("#TrainingCourseId").val());
        });
    });
</script>

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
    var startDateTextBox = $('#TrainingStartDateTime');
    var endDateTextBox = $('#TrainingEndDateTime');

    startDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        timeFormat: 'HH:mm:ss',
        onClose: function (dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.datepicker('setDate', testStartDate);
            } else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
    endDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        timeFormat: 'HH:mm:ss',
        onClose: function (dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.datepicker('setDate', testEndDate);
            } else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
</script>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#trainings_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

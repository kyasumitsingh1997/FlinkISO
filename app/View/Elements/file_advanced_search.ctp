<?php
    $postData = null;
    $postData = array('file_details' => 'Name','version' => 'Version','file_type'=>'File Type','comment'=>'Comment');
?>

    <div class="nav panel panel-default">
        <div class="file_search form col-md-12">
            <h4><?php echo __("Search Documents <small>Files uploaded by users other than quality documents.</small>"); ?></h4>
                <?php echo $this->Form->create($this->name, array('action' => 'file_advanced_search', 'default'=>false, 'role' => 'form', 'class' => 'fileupload_advanced-search-form', 'id' => 'fileupload_advanced-search-form')); ?>
                <div class="row">
                    <div class="col-md-12"><?php echo $this->Form->input('keywords', array('label' => __('Type Keyword & select the field which you want to search from below'))); ?></div>
                    <br />
                    <div class="col-md-12"><?php echo $this->Form->input('search_fields', array('label' => false, 'options' => array($postData), 'multiple' => 'checkbox', 'class' => 'checkbox-inline col-md-2')); ?></div>
                </div>
            
                <div class="col-md-12"><hr /></div>

                
                <div class="row">
                    <div class="col-md-6"><?php echo $this->Form->input('master_list_of_id', array('label' => __('Select Master List Of Format'), 'options' => $masterListOfFormat, 'multiple' => false, 'class' => 'form-control')); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('system_table_id', array('label' => __('Select Table you want to search'), 'options' => $system_table, 'multiple' => false, 'class' => 'form-control')); ?></div>
                </div>
                
                <div class ="row">
                    <div class = "col-md-6"><?php echo $this->Form->input('prepared_by', array('options' => $PublishedEmployeeList, 'style'=>array('width'=>'100%'))); ?></div>
                    <div class = "col-md-6"><?php echo $this->Form->input('approved_by', array('options' => $PublishedEmployeeList)); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('branch_list', array('label' => __('Select branches you want to search'), 'options' => $PublishedBranchList, 'multiple' => true, 'class' => 'form-control')); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('archived', array('label' => __('Archived'), 'options' => array('Yes', 'No'), 'checked' => 1, 'type' => 'radio')); ?></div>
                </div>
                
               <div class="row">
                    <div class="col-md-12"><hr /></div>
                    <div class="col-md-4"><?php echo $this->Form->input('from-date', array('id' => 'ddfrom', 'label' => __('Select start date'))); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('to-date', array('id' => 'ddto', 'label' => __('Select end date'))); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('strict_search', array('label' => __('Strict Search'), 'options' => array('Yes', 'No'), 'checked' => 1, 'type' => 'radio')); ?></div>
                    
                </div>
                
                <div class="row">
                    <div class="col-md-12">
			<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#main_index', 'async' => 'false', 'id'=>'submit_id')); ?>
			<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
    
    </div>

<script>
    function datePicker() {
        $("[name*='date']").datepicker({
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd',
      autoclose:true,
            'showTimepicker': false,
        }).attr('readonly', 'readonly');
    }
</script>
<script>
    var startDateTextBox = $('#ddfrom');
    var endDateTextBox = $('#ddto');

    startDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
        changeMonth: true,
        changeYear: true,
        beforeShow: function(input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function() {
                inst.dpDiv.css({top: (offset.top + height - 260) + 'px'})
            })
        },
        onClose: function(dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate) {
                    endDateTextBox.val(startDateTextBox.val());
                }
            }
            else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function(selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
    endDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
        changeMonth: true,
        changeYear: true,
        beforeShow: function(input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function() {
                inst.dpDiv.css({top: (offset.top + height - 260) + 'px'});
            })
        },
        onClose: function(dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.val(endDateTextBox.val());
            }
            else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function(selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
</script>
<script>
    $().ready(function() {
        $("#submit-indicator").hide();
        $("#fileupload_advanced-search-form").submit(function(){
            // $("#main_index").html('aaaa');
            $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/file_advanced_search",
                    target: "#main_index",
                    data: $(this).serialize(),
                    beforeSend: function(){
                       $("#submit_id").prop("disabled",true);
                        $("#submit-indicator").show();
                        $('#investigationModal').modal('hide');
                    },
                    complete: function() {
                       $("#submit_id").removeAttr("disabled");
                       $("#submit-indicator").hide();                       
                    },                    
                    success: function(responseText, statusText, xhr, $form) {
                       $("#main_index").html(responseText);
                    },
                    error: function (request, status, error) {
                        // alert(request.responseText);
                        alert('Action failed!');
                    }
            })            
        });
    });
</script>
<?php echo $this->Js->writeBuffer();?>
<script>$.ajaxSetup({beforeSend:function(){$("#submit-indicator").show();},complete:function(){$("#submit-indicator").hide();}});</script>

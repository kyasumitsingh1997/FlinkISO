<script type="text/javascript">
$().ready(function() {
    datePicker();
    daterangepicker();
    $('#advanced_search').modal('show');
    $('select').chosen();
    $("#submit-indicator").hide();
    $("#submit_id").click(function(){
        $("#submit_id").prop("disabled",true);
        $("#submit-indicator").show();
        $("#advance-search-form").submit();
    });
});
</script>
<style>
.conwidth{max-width: 120px}
.modal-dialog {width:50%;}
.modal-dialog {width:50%;z-index: 20!important}
.chosen-container, .chosen-container-single, .chosen-select
{min-width: 200px; width:100% !important;}
#ui-datepicker-div,.ui-datepicker,.datepicker{z-index:9999 !important}{z-index: 999999 !important}
/*.modal-footer{text-align: left}*/
</style>
<div class="modal fade " id="advanced_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <?php echo $this->Form->create($this->name, array('action' => 'advance_search', 'role' => 'form', 'class' => 'advanced-search-form', 'id' => 'advance-search-form', 'type' => 'post')); ?>
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo __('Advanced Search'); ?></h4>
                <small>Set your required conditions and click submit to search.</small>
            </div>
            <div class="modal-body">
                <?php 
                    // Configure::write('debug',1);
                    // debug($src);

                ?>
                <div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#sectionone" aria-controls="sectionone" role="tab" data-toggle="tab">Basic</a></li>
                        <li role="presentation" class=""><a href="#sectiontwo" aria-controls="sectiontwo" role="tab" data-toggle="tab">Advanced</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="sectionone">
                            <table class="table table-responsive table-bordered table-stripped">
                                <tr>
                                    <th><?php echo __('Field');?></th>
                                    <th class="conwidth"><?php echo __('Condition');?></th>
                                    <th><?php echo __('Value');?></th>
                                </tr>
                                <?php foreach ($src as $fieldName => $oprators) { ?>
                                    <tr>
                                        <td><?php echo Inflector::Humanize($fieldName);?></td>
                                        <td><?php echo $this->Form->input($fieldName,array('name'=>'data[basic]['.$modal.']['.$fieldName.'][oprator]', 'options'=>$oprators,'default'=>'==', 'label'=>false,'onChange'=>'checkdate(this)'));?></td>
                                        <td><?php echo $this->Form->input($fieldName.'_value',array('name'=>'data[basic]['.$modal.']['.$fieldName.'][value]','label'=>false));?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="sectiontwo">
                            <table class="table table-responsive table-bordered table-stripped">
                                <tr>
                                    <th><?php echo __('Field');?></th>
                                    <th><?php echo __('Condition');?></th>                        
                                </tr>
                                <?php $oprators = array('=='=>'Equal To','!='=>'Not Equal To');?>
                                <?php foreach ($belongsToModels as $fieldName => $records) { ?>
                                    <tr>
                                        <td><?php echo Inflector::Humanize($fieldName);?></td>
                                        <td><?php echo $this->Form->input($fieldName,array('name'=>'data[advance]['.$modal.']['.$records['field_name'].'][oprator]','options'=>$oprators,'default'=>'==', 'label'=>false));?></td>
                                        <td><?php echo $this->Form->input($fieldName.'_value',array('name'=>'data[advance]['.$modal.']['.$records['field_name'].'][value][]','options'=>$records['records'],'multiple', 'label'=>false));?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                </div>
            </div>
            <div class="modal-footer tex-left">
                <?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'id'=>'submit_id')); ?>
                <button type="button" class="btn btn-default" id="model_close" data-dismiss="modal"><?php echo __('Close'); ?></button>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                <?php echo $this->Form->end(); ?>                
            </div>
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

function checkdate(n){
    if(n.value == 'between'){
        
        i = n.id + "Value";
        // alert(i);
        // $("#"+i).addClass('hide');
        $("#"+i).data('datepicker').remove();
        $("#"+i).daterangepicker({
            format: 'MM/DD/YYYY',
            locale: {
                format: 'MM/DD/YYYY'
            },
            autoclose:true,
        }); 
    }
}
// var startDateTextBox = $('#ddfrom');
// var endDateTextBox = $('#ddto');

// startDateTextBox.datepicker({
//     beforeShow: function(input, inst) {
//         var offset = $(input).offset();
//         var height = $(input).height();
//         window.setTimeout(function() {
//             inst.dpDiv.css({top: (offset.top + height - 260) + 'px'})
//         })
//     },
//     onClose: function(dateText, inst) {
//         if (endDateTextBox.val() != '') {
//             var testStartDate = startDateTextBox.datepicker('getDate');
//             var testEndDate = endDateTextBox.datepicker('getDate');
//             if (testStartDate > testEndDate) {
//                 endDateTextBox.val(startDateTextBox.val());
//             }
//         }
//         else {
//             endDateTextBox.val(dateText);
//         }
//     },
//     onSelect: function(selectedDateTime) {
//         endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
//     }
// }).attr('readonly', 'readonly');

// endDateTextBox.datepicker({
//     beforeShow: function(input, inst) {
//         var offset = $(input).offset();
//         var height = $(input).height();
//         window.setTimeout(function() {
//             inst.dpDiv.css({top: (offset.top + height - 260) + 'px'});
//         })
//     },
//     onClose: function(dateText, inst) {
//         if (startDateTextBox.val() != '') {
//             var testStartDate = startDateTextBox.datepicker('getDate');
//             var testEndDate = endDateTextBox.datepicker('getDate');
//             if (testStartDate > testEndDate)
//                 startDateTextBox.val(endDateTextBox.val());
//         }
//         else {
//             startDateTextBox.val(dateText);
//         }
//     },
//     onSelect: function(selectedDateTime) {
//         startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
//     }
// }).attr('readonly', 'readonly');
</script>

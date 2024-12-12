<div class="tab-pane" id="tab_<?php echo $milestone['Milestone']['id']?>_err">                    
  <div class="table-responsive">
    <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
      <tr>
        <th><?php echo __('Name'); ?></th>
        <th width="220"><?php echo __('QC Process'); ?></th>
        <th width="90"><?php echo __('Action'); ?></th>        
      </tr>
      <?php if($fileErrorMasters){ ?>
        <?php foreach ($fileErrorMasters as $fileErrorMaster): ?>
          <tr>
              <td>
                <?php echo $this->Html->link($fileErrorMaster['FileErrorMaster']['name'],'#',array('id'=>'evi-'.$fileErrorMaster['FileErrorMaster']['id']));?>
                &nbsp;
              </td>
              <td><?php echo $this->Form->input('project_process_plan_id',array('id'=>false, 'onchange'=>'changecat("'.$fileErrorMaster['FileErrorMaster']['id'].'",this.value);', 'label'=>false, 'default'=>$fileErrorMaster['FileErrorMaster']['project_process_plan_id'], 'required','required'));?>
              </td>
              <td >  
                <?php echo $this->Html->link('Delete','javascript:void(0)', array('class'=>'btn btn-xs btn-danger', 'onClick'=>'removeCat("'.$fileErrorMaster['FileErrorMaster']['id'].'")', 'confirm'=>'Text'));?>
              </td>
              <script type="text/javascript">
                $(document).ready(function() {$('#evi-<?php echo $fileErrorMaster['FileErrorMaster']['id'] ?>').editable({
                      type:  'text',
                      pk:    '<?php echo $fileErrorMaster['FileErrorMaster']['id'] ?>',
                      name:  'data.FileErrorMaster.name',
                      url:   '<?php echo Router::url('/', true);?>file_error_masters/inplace_edit_name',  
                      title: 'Change Name',
                      placement : 'right'
                    });
                  });
              </script>              
            </tr>
          <?php endforeach; ?>
        <?php }else{ ?>
          <tr><td colspan="3">No results found</td></tr>
      <?php } ?>
    </table>
  </div>
<?php echo $this->Form->create('FileErrorMaster',array('controller'=>'file_errors','action'=>'add_ajax'),array('id'=>'ProjectFile'.$pop, 'role'=>'form','class'=>'form','default'=>true)); ?>
    <table class="table table-responsive table-condensed">
      <tr>
        <td>
          <?php echo $this->Form->input('project_process_plan_id',array('required','required'));?>
          <?php echo $this->Form->hidden('project_id',array('default'=>$this->request->params['pass'][0]));?>
          <?php echo $this->Form->hidden('milestone_id',array('default'=>$milestone['Milestone']['id']));?>
          <?php echo $this->Form->hidden('assigned_date',array('default'=>$milestone['Milestone']['start_date']));?>
          <?php echo $this->Form->input('error',array('label'=>'Copy-Paste errors names seperated by line break (&#10094;Enter&#10095;)', 'type'=>'textarea','rows'=>10));?>
        </td>
      </tr>
</table>
<script type="text/javascript">
  function changecat(id,value){
    $.ajax({
        url: "<?php echo Router::url('/', true); ?>file_error_masters/update_cat/" + id + "/" + value,
        success: function(data, result) {
            alert(data);
        }
    });
  }

  function removeCat(id) {
    var r = confirm("Are you sure to remove this Error Item?");
    if (r == true)
    {
      $.ajax({
        url: "<?php echo Router::url('/', true); ?>file_error_masters/delete/" + id,
          success: function(data, result) {
          alert(data);
      }
    });
  }
}
</script>
<script type="text/javascript">
  $().ready(function(){
    $(".chosen-select").chosen();  

    $("#error_submit_btn_<?php echo $milestone['Milestone']['id'];?>").hide();
    $("#FileErrorMasterProjectProcessPlanId").on('change',function(){
      if($("#FileErrorMasterProjectProcessPlanId").val() != -1){
        $("#error_submit_btn_<?php echo $milestone['Milestone']['id'];?>").show();
      }else{
        $("#error_submit_btn_<?php echo $milestone['Milestone']['id'];?>").hide();
      }
    })

  });
$("#FileErrorMasterError").on('change',function(){ 
var string = $("#FileErrorMasterError").val();
var data = Papa.parse(string);
var csv = Papa.unparse(data);
$("#FileErrorMasterError").val(csv);});
</script>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','id'=>'error_submit_btn_'.$milestone['Milestone']['id'])); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
<?php
  // echo $this->Html->meta('icon');
  // Configure::write('debug',1);
  // debug($projectProcessPlan);
  // debug($projectOverallPlan);
  echo $this->Html->css(array(
      // 'cake.generic',
      // 'bootstrap/css/bootstrap.min',
      // 'dist/css/AdminLTE.min',
      // 'dist/css/skins/_all-skins.min',
      // 'plugins/iCheck/flat/blue',
      // // 'plugins/morris/morris.min',
      // 'plugins/jvectormap/jquery-jvectormap-1.2.2',
      // 'plugins/datepicker/datepicker3',
      // 'plugins/daterangepicker/daterangepicker-bs3',
      // 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min',
      // 'jquery.countdown',
      // 'jquery-ui-1.9.2.custom.min',
      // 'bootstrap-chosen.min',
      // 'jquery.datepicker',
      // 'custom',
      // 'font-awesome.min','icons'
  ));

echo $this->fetch('css');
?>
<?php
echo $this->Html->script(array(
    // 'js/bootstrap.min','js/npm',
    // 'plugins/jQuery/jQuery-2.2.0.min',
    // 'plugins/jQueryUI/jquery-ui.min',
    // // 'jquery-form.min',
    // // 'jquery.validate.min',
    // 'js/bootstrap.min',
    // 'validation',
    // 'chosen.min',
    // 'tooltip.min',
    // 'plugins/daterangepicker/moment.min',
    // 'jquery.datepicker',    
    // 'plugins/daterangepicker/daterangepicker',
    // 'plugins/datepicker/bootstrap-datepicker',
));
echo $this->fetch('script');
?>
<div class="tab-pane" id="tab_<?php echo $milestone['Milestone']['id']?>_checklist">
  <div class="table-responsive">
    <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
      <tr>
        <th><?php echo __('Name'); ?></th>
        <th><?php echo __('Process'); ?></th>
        <th><?php echo __('Action');?></th>
        <!-- <th><?php echo __('Prepared By'); ?></th>
        <th><?php echo __('Publish'); ?></th> -->
      </tr>
      <?php if($projectChecklists){ ?>
        <?php foreach ($projectChecklists as $projectChecklist): ?>
      <tr>        
        <td><?php echo $this->Html->link($projectChecklist['ProjectChecklist']['name'],'javascript:void(0)',array('id'=>'evic-'.$projectChecklist['ProjectChecklist']['id']));?>&nbsp;</td>
        <!-- <td><?php echo h($PublishedEmployeeList[$projectChecklist['ProjectChecklist']['prepared_by']]); ?>&nbsp;</td>                         -->
        <!-- <td width="60">
          <?php if($projectChecklist['ProjectChecklist']['publish'] == 1) { ?>
          <span class="fa fa-check"></span>
          <?php } else { ?>
          <span class="fa fa-ban"></span>
          <?php } ?>&nbsp;</td> -->

          <script type="text/javascript">
            $(document).ready(function() {$('#evic-<?php echo $projectChecklist['ProjectChecklist']['id'] ?>').editable({
                   type:  'text',
                   pk:    '<?php echo $projectChecklist['ProjectChecklist']['id'] ?>',
                   name:  'data.ProjectChecklist.name',
                   url:   '<?php echo Router::url('/', true);?>project_checklists/inplace_edit_name',  
                   title: 'Change Name',
                   placement : 'right'
                });
              });
          </script>
          <td width="150">
            <?php echo $this->Form->input('project_process_plan_id',array('default'=>$projectChecklist['ProjectProcessPlan']['id'], 'label'=>false,'id'=>false,'onchange'=>'updateprocess(this.value,"'.$projectChecklist['ProjectChecklist']['id'].'")'));?>
            <?php // echo $projectChecklist['ProjectProcessPlan']['process']?>
            </td>
            <td width="90">  
            <?php 
          echo $this->Html->link('Delete','javascript:void(0)', array('class'=>'btn btn-xs btn-danger', 'onClick'=>'removeCat("'.$projectChecklist['ProjectChecklist']['id'].'")', 'confirm'=>'Text'));
          ?>

            </td>
      </tr>
  <?php endforeach; ?>
  <?php }else{ ?>
    <tr><td colspan=57>No results found</td></tr>
  <?php } ?>
  </table>
</div>
    <?php echo $this->Form->create('ProjectChecklist',array('controller'=>'project_checklists','action'=>'add_ajax'),array('id'=>'ProjectChecklist'.$pop, 'role'=>'form','class'=>'form','default'=>true)); ?>

                <table class="table table-responsive table-condensed">
                  <tr>
                    <td><?php echo $this->Form->input('project_process_plan_id',array());?></td>
                  </tr>
                  <tr>
                    <td>
                      <?php echo $this->Form->hidden('project_id',array('default'=>$this->request->params['pass'][0]));?>
                      <?php echo $this->Form->hidden('milestone_id',array('default'=>$milestone['Milestone']['id']));?>                                        
                      <?php echo $this->Form->input('name',array('label'=>'Copy-Paste errors checklist items in CSV format', 'type'=>'textarea','rows'=>10));?></td>
                  </tr>
                </table>

<script type="text/javascript">
  function removeCat(id) {
      var r = confirm("Are you sure to remove this Checklist Item?");
      if (r == true)
      {
          $.ajax({
                url: "<?php echo Router::url('/', true); ?>project_checklists/delete/" + id,
                // get: $('#InternalAuditPlanDepartmentDepartmentId').val(),
                success: function(data, result) {
                    // $('#InternalAuditPlanDepartmentClauses').val(data);
                    alert(data);
                }
            });
      }
  }
</script>

<script type="text/javascript">
  $().ready(function(){
    $(".chosen-select").chosen();  

    $("#chk_submit_btn_<?php echo $milestone['Milestone']['id'];?>").hide();
      $("#ProjectChecklistProjectProcessPlanId").on('change',function(){
        if($("#ProjectChecklistProjectProcessPlanId").val() != -1){
          $("#chk_submit_btn_<?php echo $milestone['Milestone']['id'];?>").show();
        }else{
          $("#chk_submit_btn_<?php echo $milestone['Milestone']['id'];?>").hide();
        }
      })

  });
  

$("#FileErrorMasterError").on('change',function(){ 
var string = $("#FileErrorMasterError").val();
// // string = string.replace(/\n\r?/g, ';')
// var rule=/\s{1,}/g;
// string = string.split(rule).join(" ").replace(/\n\r?/g, ';'); 

// // string= string.replace("\n", "<br>");

var data = Papa.parse(string);
var csv = Papa.unparse(data);

$("#FileErrorMasterError").val(csv);
// console.log(csv);
});

  function updateprocess(val,id){
    $.get("<?php echo Router::url('/', true); ?>project_checklists/updateprocess/"+val+"/"+ id , function(data) {
            $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").val(data);
            if(data == 1){
              alert('Checklist Updated.');
            }else{
              alert('Checklist Update Failed.');
            }
    });
  }

</script>

              <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','id'=>'chk_submit_btn_'.$milestone['Milestone']['id'])); ?>
              <?php echo $this->Form->end(); ?>
              <?php echo $this->Js->writeBuffer();?>
</div>
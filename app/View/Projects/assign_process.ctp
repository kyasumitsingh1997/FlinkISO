<?php $pr = 0; ?>

<style type="text/css">
  .nomargintable{
    margin-bottom: 0px;
    border-collapse: collapse !important;
    border-top: 0px !important;
    border-bottom: 0px !important;
  }
  .nomargintable td{
    border-collapse: collapse !important;
    border-top: 0px !important;
    /*border-bottom: 0px !important;*/
  }

  /*.chosen-results{
    z-index: 999;
  }
  .ui-tabs-panel{
    z-index: 1;
  }*/

  /*.chosen-container{position: fixed;}*/

  div.chosen-container-active{
    z-index:9999 !important;
}

.chosen-container.chosen-with-drop .chosen-drop {
    /*position: relative;
    overflow: auto;*/
    z-index:9999 !important;
}



</style>

<script type="text/javascript">
  $(document).ready(function(){
  $("#teamprocessinput<?php echo $tempMid;?>").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#teamtableprocess<?php echo $tempMid;?> tbody tr").filter(function() {
      var classN = this.id;
      $(this).toggle($("#"+classN).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<input type="text" id="teamprocessinput<?php echo $tempMid;?>" name="teamprocessinput<?php echo $tempMid;?>" class="form-control" placeholder="Type name to search"><br />

<h4>Assign Processes To Members</h4>
      <table class="table table-responsive table-condensed table-bordered nomargintable" >
        <tr class="warning">
          <th width="15%">Member</th>
          <th width="10%">Department</th>
          <th width="10%">Designation</th>
          <th width="10%">Skill Set</th>                                      
          <th width="10%">Processes</th>  
          <th width="10%">Priority</th> 
          <th width="10%">TL</th>
          <th width="10%">PL</th>                    
          <!-- <th>Locked Till</th> -->
          <th></th>
        </tr>
      </table>
        <?php 
          // Configure::write('debug',1);
          
          $detailedPlan = array();
          foreach ($planResult as $projectOverallPlan) {
            foreach ($projectOverallPlan['DetailedPlan'] as $p) {  
              $detailedPlan[$p['ProjectProcessPlan']['id']] = $p['ProjectProcessPlan']['process'];
            }
          }
          
          debug($teamMembers);
          foreach ($teamMembers as $employee) { 
          // echo ">> " . $employee['Employee']['tl'];
          // echo ">> " . $employee['Employee']['pm'];
          ?>
          <?php echo $this->Form->create('ProjectResource',array(
                    'controller'=>'project_resources','action'=>'add_ajax',
                    'id'=>'ProjectResource-'. $milestone['Milestone']['id'] .'-'. $pr, 
                    'default'=>false
                  ),array('role'=>'form','class'=>'form','default'=>false)
          ); ?>
          <table class="table table-responsive table-condensed table-bordered nomargintable" id="teamtableprocess<?php echo $tempMid;?>">
          
          <tr class="nomargintable" id="<?php echo $employee['Employee']['id']?><?php echo $pop?><?php echo $pr?>">    
                <td width="15%">                              
                    <?php echo $this->Form->hidden('ProjectResource.'.$milestone['Milestone']['id'].'.'.$pr.'.project_id',array('default'=>$milestone['Milestone']['project_id']));?>
                    <?php echo $this->Form->hidden('ProjectResource.'.$milestone['Milestone']['id'].'.'.$pr.'.milestone_id',array('default'=>$milestone['Milestone']['id']));?>
                    <?php echo $this->Form->hidden('ProjectResource.'.$milestone['Milestone']['id'].'.'.$pr.'.mandays',array('default'=>0));?>
                    <?php echo $this->Form->hidden('ProjectResource.'.$milestone['Milestone']['id'].'.'.$pr.'.employee_id',array('default'=>$employee['Employee']['id']));?>
                  <?php echo $this->Html->link($employee['Employee']['name'],array('controller'=>'employees','action'=>'view',$employee['Employee']['id']),array('target'=>'_blank'));?></td>
                  
                  <td width="10%"><?php echo $PublishedDepartmentList[$employee['Employee']['department_id']];?></td>
                  
                  <td width="10%"><?php echo $PublishedDesignationList[$employee['Employee']['designation_id']];?></td>
                  
                  <td width="10%"></td>
                  <td width="10%">
                    <?php echo $this->Form->input('ProjectResource.'.$milestone['Milestone']['id'].'.'.$pr.'.process',array('label'=>false, 'options'=>$detailedPlan));?>
                  </td>                        
                  <td width="10%"><?php echo $this->Form->input('ProjectResource.'.$milestone['Milestone']['id'].'.'.$pr.'.priority',array('default'=>0, 'label'=>false));?></td>
                  <td width="10%">
                    <?php echo $this->Form->input('ProjectResource.'.$milestone['Milestone']['id'].'.'.$pr.'.team_leader_id',array('label'=>false,'onchange'=>'get_pl(this.value,"'.$milestone['Milestone']['id'].'",'.$pr.');',  'options'=>$teamLeaders))?>
                  </td>
                  <td width="10%">
                    <?php echo $this->Form->input('ProjectResource.'.$milestone['Milestone']['id'].'.'.$pr.'.project_leader_id',array('label'=>false, 'options'=>$projectLeaders))?>
                  </td>
                  <!-- <td><?php echo $employee['Employee']['locked_till'];?></td> -->
                  <td>
                    <div class="btn-group">
                        <?php echo $this->Form->submit('Update',array('id'=>'update-'.$milestone['Milestone']['id'].'-'.$pr, 'div'=>false,'class'=>'btn btn-xs btn-success')); ?>
                        
                        <a href="javascript:void(0)" onClick = "releaseemp('<?php echo $employee['ProjectEmployee']['id']?>','<?php echo $employee['ProjectEmployee']['employee_id']?>','<?php echo $pop?>','<?php echo $pr?>')" class="btn btn-xs btn-warning">Release</a> 
                      </div>
                      <div id="release_<?php echo $employee['ProjectEmployee']['employee_id']?>_<?php echo $pop?>_<?php echo $pr?>"></div>
                  </td>
              
            </tr>
            </table>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer();?> 
            
            <script type="text/javascript">

              function get_pl(val,m,pr){
                console.log(val);
                $.get("<?php echo Router::url('/', true); ?>projects/get_pls/id:" + val, function(data) {
                  // $("#ProjectResource"+m+pr"ProjectLeaderId").html(data).trigger("chosen:updated");
                  // alert(data);
                  $("#ProjectResource"+m+pr+"ProjectLeaderId").val(data).trigger('chosen:updated');
                });
              }

              $(".chosen-select").chosen();  

              // $("#update-<?php echo $milestone['Milestone']['id']?>-<?php echo $pr?>").on('click',function(){
              //   alert('aaa');
              // });

              $().ready(function(){
                $("#ProjectResource-<?php echo $milestone['Milestone']['id']?>-<?php echo $pr?>").submit(function(){   

                // $("#main_index").html('aaaa');
                $.ajax({
                        type: "POST",
                        url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_project_resource",
                        target: "#<?php echo $employee['Employee']['id']?><?php echo $pop?><?php echo $pr?>",
                        data: $(this).serialize(),
                        beforeSend: function(){                          
                            // $("#submit_id").prop("disabled",true);
                            // $("#submit-indicator").show();
                            // $('#investigationModal').modal('hide');
                        },
                        complete: function() {
                           // $("#submit_id").removeAttr("disabled");
                           // $("#submit-indicator").hide();                       
                        },                    
                        success: function(responseText, statusText, xhr, $form) {
                           // $("#main_index").html(responseText);
                           // alert(statusText);
                           $("#<?php echo $employee['Employee']['id']?><?php echo $pop?><?php echo $pr?>").html("<td colspan='9'>" + responseText + "</td>");
                        },
                        error: function (request, status, error) {
                            // alert(request.responseText);
                            alert('Action failed!');
                        }
                })            
            });
                

                // $(".chosen-select").width('220px')
                // $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn").on('click',function(){
                //   // alert('a');
                //     $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_employee_to_project/project_id:<?php echo $milestone['Milestone']['project_id'];?>/employee_id:<?php echo $employee['Employee']['id']?>/milestone_id:<?php echo $milestone['Milestone']['id']?>" , function(data) {
                //     //           console.log(data);
                //               $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn").html('Added');
                //               $("#<?php echo $employee['Employee']['id']?><?php echo $pop?>").addClass('success');
                //               return false;
                //         });
                // });
              });
            </script>
<?php $pr++; } ?>
<div style="height: 350px">&nbsp;</div>
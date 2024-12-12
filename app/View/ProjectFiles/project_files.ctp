<?php if($dontshow == false){ ?>
<?php echo $this->Session->flash();?>
<?php $mid = $this->request->params['pass']['1']; ?>
  <script type="text/javascript">

      $("#ProjectFileSearchFakeProjectFilesFormSubmit<?php echo $mid;?>").on('click',function(){
        event.preventDefault();
        $.ajax({
            type: "GET",
            target: '#project_files_<?php echo $mid;?>',
            dataType: "text",
            data : $("#ProjectFileSearchFakeProjectFilesForm<?php echo $mid;?>").serialize(),
            url: "<?php echo Router::url('/', true); ?>project_files/project_files/<?php echo $milestone['Milestone']['project_id'];?>/<?php echo $mid;?>",
            beforeSend: function(){
                 $("#fa-spin-<?php echo $mid;?>").show();
            },
            success: function(data, result) {                                   
                $("#project_files_<?php echo $mid;?>").html(data);
                $("#fa-spin-<?php echo $mid;?>").hide();
            },                              
        });
      })


      $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if (
                $(element).attr('name') == 'data[ProjectFile][file_category_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },

        submitHandler: function(form) {
            $("#ProjectFileAddAjaxForm<?php echo $mid;?>").ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?>project_files/add_ajax",
                type: 'POST',
                target: '#project_files_<?php echo $mid;?>',
                beforeSend: function(){
                   $("#file_submit_btn").prop("disabled",true);
                    $("#submit-indicator<?php echo $mid;?>").show();
                },
                complete: function() {
                   $("#file_submit_btn").removeAttr("disabled");
                   $("#submit-indicator<?php echo $mid;?>").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
          });
        }
      });

     


      $().ready(function() {
          $("#fa-spin-<?php echo $mid;?>").hide();
          $("#submit-indicator<?php echo $mid;?>").hide();
          $("#fileinput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#filetable<?php echo $mid;?> tbody tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });


          $(".chosen-select").chosen('destroy');
          $(".chosen-select").chosen({width: "100%"}); 
          
          jQuery.validator.addMethod("greaterThanZero", function(value, element) {
              return this.optional(element) || (parseFloat(value) > 0);
          }, "Please select the value");
          
          $('#ProjectFileAddAjaxForm<?php echo $mid;?>').validate({
              rules: {
                  "data[ProjectFile][file_category_id]": {
                      greaterThanZero: true,
                  },
                  "data[ProjectFile][file_category_priority]": {
                      greaterThanZero: true,
                  },
                  "data[ProjectFile][file_names]": {
                      required: true,
                  },
              }
          });  


          $("#refresh-page-<?php echo $mid;?>").on('click',function(){
            $("#refresh-page-<?php echo $mid;?> i").addClass(' fa-spin');
            $("#project_files_<?php echo $mid;?>").load("<?php echo Router::url('/', true); ?>/project_files/project_files/<?php echo $milestone['Milestone']['project_id']?>/<?php echo $milestone['Milestone']['id']?>/<?php echo $pop ?>/<?php echo $por ;?>",function(response, status, xhr ){
              if(status == 'success'){
                $("#refresh-page-<?php echo $mid;?> i").removeClass(' fa-spin');
              }
            });
          });
      });
</script>
<div style="overflow: scroll;"> 

  <div class="row">
    <div class="col-md-3">
      <span class="box box-header box-primary">Total Files: <span class="badge pull-right"><?php echo $totalProjectFiles;?> / <?php echo count($projectFiles);?></span></span>
    </div>
    <div class="col-md-3">
      <span class="box box-header box-success">Total Resourses : <span class="badge pull-right"><?php echo count($teamMembers);?></span></span>
    </div>
    <div class="col-md-3">
      <span class="box box-header box-warning">Resourses With Assigned Processes: <span class="badge pull-right"><?php echo $proempcnt;?></span></span>
    </div>  
    <div class="col-md-3">
      <span class="box box-header box-danger">Resourses Withut Processes: <span class="badge pull-right"><?php echo count($teamMembers) - $proempcnt;?></span></span>
    </div>  
  </div>
  
  <?php echo $this->Form->create('ProjectFileSearchFake',array('id'=>'ProjectFileSearchFakeProjectFilesForm'.$mid),array('default'=>false)); ?>                    
      <div class="row">
        <div class="col-md-3">
          <label>Search</label>
          <input type="text" id="fileinput" name="fileinput" class="form-control" placeholder="Type name to search">
        </div>
        

        <div class="col-md-3">
          <?php echo $this->Form->input('team_members',array('options'=>$teamMembers));?>
        </div>        
        <div class="col-md-3">
          <?php echo $this->Form->input('project_process_plan_id',array('options'=>$existingprocesses));?>
        </div>
        <div class="col-md-3">
          <?php echo $this->Form->input('current_status',array('options'=>$fileStatuses));?>
        </div>
        
        <div class="col-md-3">
          <?php echo $this->Form->input('file_category_id');?>
        </div>
        <div class="col-md-3">
          <?php echo $this->Form->input('cities');?>
        </div>
        <div class="col-md-3">
          <?php echo $this->Form->input('blocks');?>
        </div>        
        <div class="col-md-3"><br />
          <div class="btn-group">
            <?php echo $this->Form->submit('Filter',array('div'=>false, 'class'=>'btn btn-sm btn-success','id'=>'ProjectFileSearchFakeProjectFilesFormSubmit'.$mid));?>
            <div class="btn btn-sm btn-warning" id="refresh-page-<?php echo $mid;?>"><i class="fa fa-refresh"></i></div>            
          </div>
          <i class="fa fa-refresh fa-spin pull-right" id="fa-spin-<?php echo $mid;?>"></i>
        </div>

        <div class="col-md-12"><hr /></div>
      </div>
<?php echo $this->Form->end();?>

<?php if($projectFiles){ ?>
  <h4>Note : Change user functionality is only available for Project Manager, Team Leaders and Project Leaders. In order to change the user, user must keep the file on HOLD.</h4>
    <?php echo $this->Form->create('ProjectFileFake',array(),array('id'=>null, 'default'=>false)); ?>                    
      <table class="table table-responsive table-condensed table-bordered" id="filetable<?php echo $mid;?>">
        <thead>
          <tr>
            <th>#</th>
            <th>File Name</th>
            <th>Category</th>
            <?php if($milestone['Milestone']['single_batch'] == 0){ ?>
              <th>Batch</th>
            <?php } ?>
            <th>City</th>
            <th>Block</th>
            <th>Prioriy</th>
            <!-- <th>Last Process</th> -->
            <th>Current Process</th>
            <!-- <th>Comment</th> -->
            <th>Assigned To</th>
            <th>Status</th>             
            <!-- <th>Assigned Date/Time</th> -->
            <th>Start Time (Current Process)</th>     
            <th>Completed Date (Current Process)</th>
            <th>Estimated Time</th>
            <th>Actual Time</th>
            <?php if(in_array($this->Session->read('User.employee_id'),$emps)){ ?>
            <th>Action</th>
            <?php } ?>            
          </tr>
        </thead>
        <?php
          $newM = 0; 
          $open = $closed = $delayed = $at = $eat =  0;
          
          foreach ($projectFiles as $file) { 
            
            if($file['ProjectFile']['cat_on_hold'] == 1)$catclass = 'warning';
            else $catclass = '';
            
            if($file['ProjectFile']['file_category_id'] == -1){
              $catclass = 'danger';
            }else{
              $catclass = $catclass;
            }

            if($file['ProjectFile']['current_status'] == 5){
              // ??  
            }
            
            if($file['ProjectFile']['current_status'] == 0)$sClass = ' text-bold text-success';
            elseif($file['ProjectFile']['current_status'] == 4)  $sClass = $catclass = '  text-danger';
            else $sClass = '  text-warning';?>
                    
            <tr class="<?php echo $catclass;?> <?php echo $sClass;?>">
              <td><?php echo $newM+1?></td>
              <td>
              <?php 
                echo $this->Html->link($file['ProjectFile']['name'],"javascript:void(0);",
                      array('class'=>'','onclick'=>'openmodel("project_files","view","'.$file['ProjectFile']['id'].'",null,null,null)')); ?>
                <p>(<?php echo $file['ProjectFile']['unit']?>)</p>                
              </td>
              <td>
                <?php                       
                  if($file['ProjectFile']['current_status'] == 5){
                    echo $fileCategories[$file['ProjectFile']['file_category_id']];
                  }else{
                    if($file['ProjectFile']['file_category_id']){
                      echo $this->Form->input('file_category_id',array('id'=>false, 'label'=>false,'default'=>$file['ProjectFile']['file_category_id'], 'style'=>'width : 120px' , 'onchange'=>'updatefilecategory("'.$file['ProjectFile']['id'].'",this.value)'));  
                      }else{
                        echo $this->Form->input('file_category_id',array('id'=>false, 'label'=>false,'style'=>'width : 120px' , 'onchange'=>'updatefilecategory("'.$file['ProjectFile']['id'].'",this.value)'));
                      }
                    }?>
                </td>
                <?php if($milestone['Milestone']['single_batch'] == 0){ ?>
                  <td><?php echo $fileBatches[$file['ProjectFile']['file_batch_id']];?></td>
                <?php } ?>
                <td><?php echo $file['ProjectFile']['city']?></td>
                <td><?php echo $file['ProjectFile']['block']?></td>
                <td id="<?php echo $file['ProjectFile']['id']?>_prioroty"><?php echo $file['ProjectFile']['priority'] ?></td>
                <!-- <td><?php echo $existingprocesses[$file['ProjectFile']['last_process']] ?></td> -->
                <td><?php  if($file['ProjectFile']['current_status'] != 5)echo $existingprocesses[$file['ProjectFile']['project_process_plan_id']] ?></td>
                <!-- <td><?php echo $file['ProjectFile']['last_comment']?></td> -->
                <td><?php
                if($file['ProjectFile']['auto_manual'] == 1 && $file['ProjectFile']['noprocess'] == 0 && $file['ProjectFile']['current_status'] != 0)echo "You must assign this file manually";

                if($file['ProjectFile']['current_status'] != 5){                
                  if($file['ProjectFile']['queued'] != 0){
                    echo $this->Html->link($PublishedEmployeeList[$file['ProjectFile']['last_emp_id']],"javascript:void(0);",
                        array(
                          'class'=>'text-warning',
                          'onclick'=>'openmodel(
                            "employees",
                            "view",
                            "'.$file['ProjectFile']['last_emp_id'].'",
                            null,
                            null,
                            null
                          )'
                        )); 
                  }
                  else{                    
                    if($file['ProjectFile']['file_category_id'] != -1){
                      if($file['Employee']['name']){
                      echo $this->Html->link($file['Employee']['name'],"javascript:void(0);",
                        array(
                          'class'=>'',
                          'onclick'=>'openmodel(
                            "employees",
                            "view",
                            "'.$file['Employee']['id'].'",
                            null,
                            null,
                            null
                          )'
                        )); 
                      }
                      if($file['Employee']['employee_number'])echo " ( ".$file['Employee']['employee_number']." )";
                    }else{
                      echo "<span class='text-danger'>Add category first</span>";
                    }                          
                  }
                }
                ?>
                </td>                
                <td>
                  <?php
                    if($file['ProjectFile']['queued'] == 0){
                      // echo 1;
                      if($file['ProjectFile']['file_category_id'] != -1){
                      //   echo 2;
                      //   if( $file['ProjectFile']['current_status'] != 5){                              
                      //     echo 3;
                      //     echo $fileStatuses[$file['ProjectFile']['current_status']];
                      //   }else{
                      //     echo 4;
                          echo $fileStatuses[$file['ProjectFile']['current_status']];
                        }
                      }else{
                        // echo 5;
                        // if($file['ProjectFile']['current_status'] != 5){
                        //   echo 6;
                        //   echo "<span class='text-danger'>Add category first</span>";
                        //   }else{
                        //     echo 7;
                            echo $fileStatuses[$file['ProjectFile']['current_status']];
                        //   }
                        // }
                      }
                      ?>
                </td>
                <!-- <td><?php echo date('Y-m-d H:i',strtotime($file['ProjectFile']['latest_assigned_date']))?></td> -->
                <td><?php if($file['ProjectFile']['start_time'])echo date('Y-m-d H:i',strtotime($file['ProjectFile']['start_time']));?></td>
                <td><?php if($file['ProjectFile']['end_time'])echo date('Y-m-d H:i',strtotime($file['ProjectFile']['end_time']));?></td>
                
                <td><?php echo $file['ProjectFile']['estimated_time']?><?php  // echo $file['ProjectFile']['estimated_time_1']?></td>
                <td><?php echo substr($file['ProjectFile']['actual_time_from_process'],0,-3);?></td>
                <?php if(in_array($this->Session->read('User.employee_id'),$emps)){ ?>
                <td>
                  <div class="btn-group" style="width: 70px;">
                    <div class="btn btn-xs btn-default" onclick='openmodel("project_files","view","<?php echo $file['ProjectFile']['id'];?>",null,null,null);'><i class='fa fa-file-o'></i></div>
                  <?php

                  // echo $this->Html->link("<i class='fa fa-file-o'></i>","javascript:void(0);",
                      // array('class'=>'','onclick'=>'openmodel("project_files","view","'.$file['ProjectFile']['id'].'",null,null,null)'));

                    // echo $file['ProjectFile']['current_status'] . '<br />';
                    // echo $file['ProjectFile']['start_time'];

                    if($file['ProjectFile']['current_status'] == 5){

                    }elseif(in_array($this->Session->read('User.employee_id'),$emps)){
                      if($file['ProjectFile']['file_category_id'] != -1){
                         if($file['ProjectFile']['current_status'] == 7 || $file['ProjectFile']['current_status'] == 4 || $file['ProjectFile']['current_status'] == 10 || $file['ProjectFile']['start_time'] == '' || $file['ProjectFile']['latest_start_date'] == ''){

                          if($file['ProjectFile']['auto_manual'] == 1 && $file['ProjectFile']['noprocess'] == 0 && $file['ProjectFile']['current_status'] != 0){
                            
                              echo "<div class='btn btn-xs btn-default' onclick='openmodel(\"project_files\",\"assign_to_user\",\"".$file['ProjectFile']['id']."\",null,null,null,null,)'><i class='fa fa-plus'></i></div>";  
                            
                            }else if($file['ProjectFile']['last_process_id']){                            
                            
                              echo "<div class='btn btn-xs btn-default' onclick='openmodel(\"file_processes\",\"changeuser\",\"".$file['ProjectFile']['last_process_id']."\",null,null,null,null,)'><i class='fa fa-random'></i></div>";  
                          }else{                            
                            
                            // echo "<div class='btn btn-xs btn-default' onclick='openmodel(\"file_processes\",\"changeuser\",null,\"".$file['ProjectFile']['id']."\",null,null,null,)'><i class='fa fa-random'></i></div>"; 
                          }
                          
                        }
                       }else{

                       }
                    }?>

                  <?php echo $this->Js->link('<i class="fa fa-trash"></i>',array('controller'=>'project_files','action'=>'delete_project_file',$file['ProjectFile']['id'],$file['ProjectFile']['project_id'],$file['ProjectFile']['milestone_id'],$this->request->params['pass'][2],$this->request->params['pass'][3]),array('class'=>'btn btn-xs btn-danger', 'escape'=>false, 'confirm'=>'Do you want to delete this file?','update'=>'#project_files_'.$mid,'dafault'=>false))?>
                  </div>
                  <?php echo $this->Js->writeBuffer();?>
                </td>
              <?php } ?>
            </tr>
              <script type="text/javascript">
                $("#NewMember<?php echo $newM?>-<?php echo $mid?>").chosen('destroy');
                $("#NewMember<?php echo $newM?>-<?php echo $mid?>").chosen({width: "180px"});

                $("#NewProcessId<?php echo $newM;?>-<?php echo $mid;?>").chosen('destroy');
                $("#NewProcessId<?php echo $newM;?>-<?php echo $mid;?>").chosen({width: "180px"});
              </script>
              <?php 
                  if($file['ProjectFile']['current_status'] == 0)$open++;
                  if($file['ProjectFile']['current_status'] == 1)$closed++;
                  if($file['ProjectFile']['current_status'] == 3)$delayed++;
                  $at = $at + $file['ProjectFile']['actual_time'];
                  $eat = $eat + $file['ProjectFile']['estimated_time'];
                  $newM++; 
                } ?>              
            </table>

<p>
      <?php
      echo $this->Paginator->options(array(
      'update' => '#project_files_'.$mid,
      'evalScripts' => true,
      'before' => $this->Js->get('#busy-indicator-'.$mid)->effect('fadeIn', array('buffer' => false)),
      'complete' => $this->Js->get('#busy-indicator-'.$mid)->effect('fadeOut', array('buffer' => false)),
      ));

      echo $this->Paginator->counter(array(
      'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
      ));
      ?>      </p>
      <ul class="pagination">
      <?php
    echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
    echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
    echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
  ?>
  <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator-'.$mid)); ?></li>
  <?php echo $this->Js->writeBuffer();?>
      </ul>  
      
          <?php }?>  
       <?php 
       // echo $this->Html->link('Delete All',array('controller'=>'project_files','action'=>'deleteallfiles',$milestone['Milestone']['project_id']),array('class'=>'btn btn-xs btn-success pull-right','style'=>'margin:10px 2px','confirm'=>'Do you want to delete all the files?'));
        echo $this->Html->link('Download Sample',Router::url('/', true) . 'files'. DS . 'files_sample.xlsx',array('class'=>'btn btn-xs btn-success pull-right','style'=>'margin:10px 2px'));
       ?>                                 
      <?php echo $this->Form->end(); ?>
    </div>
  <?php echo $this->Session->flash();?>         
    <?php echo $this->Form->create('ProjectFile',array('controller'=>'project_files','action'=>'add_ajax','id'=>'ProjectFileAddAjaxForm'.$mid),array('id'=>'ProjectFile'.$mid, 'role'=>'form','class'=>'form','default'=>false)); ?>
      <table class="table table-responsive table-condensed">
      <tr>
        <td><label>Single/Batch</label></td>
        <td><label>Batch</label></td>
        <td><label>Category</label></td>
        <td><label>Priority</label></td>
        <td><label>Auto/Manual</label></td>
        <td></td>
      </tr>
      <tr>
        <td><?php echo $milestone['Milestone']['single_batch']?'Batch':'Single';?> <?php echo "<div class='col-md-12'>".$this->Form->hidden('single_batch',array('default'=>$milestone['Milestone']['single_batch'])) . '</div>'; ?></td>
        <td><?php echo $this->Form->input('file_batch_id',array('label'=>false,));?></td>
        <td><?php echo $this->Form->input('file_category_id',array('label'=>false,'required'=>'required','onchange'=>'getpriority(this.value)'));?></td>
        <td><?php echo $this->Form->input('file_category_priority',array('label'=>false,'required'=>'required','type'=>'number'));?></td>
        <td><?php echo $this->Form->input('auto_manual',array('legend'=>false,'type'=>'radio','options'=>array(0=>'Auto',1=>'Manual'),'default'=>0));?></td>
        <td></td>
      </tr>
      <tr>
        <td colspan="5">
          <?php echo $this->Form->hidden('pop',array('default'=>$pop));?>
          <?php echo $this->Form->hidden('por',array('default'=>$por));?>
          <?php echo $this->Form->hidden('project_id',array('default'=>$milestone['Milestone']['project_id']));?>
          <?php echo $this->Form->hidden('milestone_id',array('default'=>$milestone['Milestone']['id']));?>
          <?php echo $this->Form->hidden('assigned_date',array('default'=>$milestone['Milestone']['start_date']));?>
          <?php echo $this->Form->input('file_names',array('label'=>'Copy-Paste file names,units,city,block in CSV format', 'type'=>'textarea','rows'=>10,'required'=>'required'));?>
            <span class="text"><strong>Note: </strong>If Manul is chosen add Employee Number at the end of each row : "file names,units,city,block,employee_number". </span>
          </td>
      </tr>
    </table>
    <script type="text/javascript">

    // $("#filetable<?php echo $mid;?>").tableExport(
    //         {
    //           headers: true,                        // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
    //           footers: true,                      // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
    //           formats: ["csv"],             // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
    //           filename: "<?php echo $milestone['Milestone']['title'];?>",                     // (id, String), filename for the downloaded file, (default: 'id')
    //           bootstrap: true,                   // (Boolean), style buttons using bootstrap, (default: true)
    //           exportButtons: true,                // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
    //           position: "bottom",                 // (top, bottom), position of the caption element relative to table, (default: 'bottom')
    //           ignoreRows: null,                   // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
    //           ignoreCols: [2,16,17],                   // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
    //           trimWhitespace: true,               // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
    //           RTL: false,                         // (Boolean), set direction of the worksheet to right-to-left (default: false)
    //           sheetname: "id"                     // (id, String), sheet name for the exported spreadsheet, (default: 'id')
    //         }
    //     );
  
      $("#ProjectFileFileNames").on('change',function(){
        var string = $("#ProjectFileFileNames").val();
        var data = Papa.parse(string);
        var csv = Papa.unparse(data);
        $("#ProjectFileFileNames").val(csv);
      });
    function getpriority(category_id){
        $.ajax({
            url: "<?php echo Router::url('/', true); ?>projects/getpriority/"+category_id,
            success: function(data, result) {
                // alert('File is assigned to a new member');
                $("#ProjectFileFileCategoryPriority").val(data);
            }
        });
    }

   function updatefilecategory(file_id,category_id){
    $.ajax({
            url: "<?php echo Router::url('/', true); ?>projects/updatefilecategory/"+file_id+"/" +category_id,
            success: function(data, result) {                
                $("#"+file_id+"_prioroty").html(data);
            }
        });
   }
  </script>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','id'=>'file_submit_btn')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator'.$mid)); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
<?php } ?>
<script type="text/javascript">
  $("#busy-indicator-<?php echo $mid;?>").hide();
</script>
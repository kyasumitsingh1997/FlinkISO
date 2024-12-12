<h3>Reports & Graphs</h3>
<?php
echo $this->Html->script(array(
  // 'plugins/chartjs/Chart.min',
  // 'plugins/knob/jquery.knob',
  // 'plugins/jvectormap/jquery-jvectormap-1.2.2.min',
  // 'plugins/jvectormap/jquery-jvectormap-world-mill-en',
    'js-xlsx-master/dist/xlsx.core.min', 
    'Blob.js-master/Blob.min', 
    'FileSaver.js-master/FileSaver.min', 
    'TableExport-master/src/stable/js/tableexport.min',
    'tablesorter-master/js/jquery.tablesorter',
    'tablesorter-master/js/jquery.tablesorter.widgets',
));
echo $this->fetch('script');

// echo $this->request->data['Project']['project_id'];
?>


<style type="text/css">
  .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{border : 1px solid #696868;}
</style>


<?php 
// if($project_id);

echo "<div class='row'>";
echo $this->Form->create('Project');
echo "<div class='col-md-3'>".$this->Form->input('project_id',array('default'=>$project_id))."</div>";
echo "<div class='col-md-3'>".$this->Form->input('milestone_id',array('default'=>$milestone_id))."</div>";
echo "<div class='col-md-3'>".$this->Form->input('city_id',array())."</div>";
echo "<div class='col-md-3'>".$this->Form->input('block_id',array('label'=>'Block/State'))."</div>";
echo "<div class='col-md-3'>".$this->Form->input('dates')."</div>";
echo "<div class='col-md-3'><br />".$this->Form->submit('submit',array('class'=>'btn btn-success btn-sm'))."</div>";
echo $this->Form->end();
echo "</div>";
?>
<script type="text/javascript">
  $(".chosen-select").chosen();
  <?php if($this->request->data){ ?>
    $("#ProjectDates").daterangepicker({
    // singleDatePicker: true,
        showDropdowns: true,
        // startDate: moment().add(-1, 'months'),
        // minDate: moment(),
        locale: { 
            format: 'YYYY-MM-DD'
        }
    });
  <?php }else{ ?>
    $("#ProjectDates").daterangepicker({
    // singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().add(-15, 'days'),
        // minDate: moment(),
        locale: { 
            format: 'YYYY-MM-DD'
        }
    });
    <?php } ?>
</script>
<style type="text/css">
  .csv{float: right}
  .btn-toolbar{text-align: right !important}
</style>

<?php if($project_id && $project_id != -1){ ?> 

<?php 
$qucipro = $this->requestAction('projects/projectdates/'.$project_id);
echo $this->element('projectdates',array('qucipro'=>$qucipro));?>


<div style="overflow: scroll;">

<?php foreach ($allFiles as $milestone => $files) { 
$process = NULL;
  ?>
<h3>Milestone : <?php echo $files['Milestone']['title'];?></h3>
<table class="table table-bordered table-responsive table-striped table-hover table-condensed" id="filetable<?php echo $files['Milestone']['id'];?>">  
    <?php foreach ($files['Files'] as $file) { 
      foreach ($file['Processess'] as $pkey => $pvalue) {
        $process[$pkey] = $pkey;
      }      
     } 
     foreach ($file['Errors'] as $ekey => $evalue) {
        $errors[$ekey] = $ekey;
      }
     ?>
     <tr>
        <th rowspan="2">#</th>
        <th rowspan="2">City</th>
        <th rowspan="2">State</th>
        <th rowspan="2">Category</th>
        <th rowspan="2">File name</th>
        <th rowspan="2">Units</th>
        <th rowspan="2">Existing <?php echo $deliverableUnits[$files['Milestone']['unit_id']];?></th>
        <th rowspan="2">Estimated</th>
        <th rowspan="2">Updated</th>
        <th rowspan="2">Total <?php echo $deliverableUnits[$files['Milestone']['unit_id']];?></th>
        <th rowspan="2">Status</th>
        <th rowspan="2">Priority</th>         
        <?php
        foreach ($errors as $eekey => $eevalue) { ?>
          <th rowspan="2" class=""><?php echo $eevalue;?></th>
        <?php } ?>
        
        <th rowspan="2">Status</th>  
        <th rowspan="2">Remarks</th>

        <?php foreach ($process as $key => $value) { ?>
           <th colspan="15" class="warning text-center"><?php echo $processes[$key];?></th>
         <?php } ?>
     </tr>
     
     <tr>
          <?php foreach ($process as $key => $value) { ?>
            <th><?php echo $processes[$key];?> By</th>
            <th>Start Date</th>
            <th>Start Time</th>
            <th>End Date</th>
            <th>End Time</th>
            <th>Estimated Time</th>
            <th>Total Time</th>
            <th>Hold Time</th>
            <th>New <?php echo $deliverableUnits[$files['Milestone']['unit_id']];?></th>
            <th>Existing <?php echo $deliverableUnits[$files['Milestone']['unit_id']];?></th>
            <th>Total Updated <?php echo $deliverableUnits[$files['Milestone']['unit_id']];?></th>
            <th>Status</th>  
            <th>Remarks</th>
            <th>Achieved Metrics</th>
            <th>Efficiency</th>
         <?php } ?>          
     </tr>  

  <?php $x = 1;?>
  <?php foreach ($files['Files'] as $file) {  ?>
    <tr>
      <td><?php echo $x; $x++;?>&nbsp;</td>
      <td><?php echo $file['File']['city'];?>&nbsp;</td>
      <td><?php echo $file['File']['block'];?>&nbsp;</td>
      <td><?php echo $fileCategories[$file['File']['file_category_id']];?>&nbsp;</td>
      <td><?php echo $this->Html->link($file['File']['name'],array('controller'=>'project_files','action'=>'view',$file['File']['id']),array('target'=>'_blank'));?>&nbsp;</td>
      <td><?php echo $file['File']['unit'];?>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php echo $file['File']['estimated_time'];?>&nbsp;</td>
      <td>&nbsp;</td>
      <td><?php echo $file['File']['uc'];?>&nbsp;</td>      
      <td><?php echo $fileStatuses[$file['File']['current_status']];?>&nbsp;</td>
      <td><?php echo $file['File']['priority'];?>&nbsp;</td>
      <?php
      foreach ($file['Errors'] as $ekey => $evalue) { ?>      
        <td class="text-danger"><?php echo $evalue;?></td>
      <?php } ?>      
      <td>&nbsp;</td>
      <td>&nbsp;</td>

      <?php 
        foreach ($file['Processess'] as $pkey => $pvalue) { ?>

<td><?php echo $PublishedEmployeeList[$pvalue['start']['employee_id']]?>
  <?php 
  
  if($pvalue['all_members']){
    foreach ($pvalue['all_members'] as $mem) {
      if(count($pvalue['all_members']) > 0)echo "<br /><span class='text-warning'>";                
        if($pvalue['start']['employee_id'] != $mem)echo "<small>". $PublishedEmployeeList[$mem] .", </small>";
      if(count($pvalue['all_members']) > 0)echo "</span>";
    }  
  }else{
    echo "";
  }
  
  ?>&nbsp;
</td>          

<td><?php 
    if($pvalue['start']['start_time'] && date('Y',strtotime($pvalue['start']['start_time'])) != '1970')echo date('Y-m-d',strtotime($pvalue['start']['start_time'])); 
    else echo "";
  ?>&nbsp;
</td>

<td><?php 
    if($pvalue['start']['start_time'] && date('Y',strtotime($pvalue['start']['start_time'])) != '1970')echo date('H:i:s',strtotime($pvalue['start']['start_time'])); 
    else echo "";
    ?>&nbsp;
</td>

<td><?php 
    if($pvalue['start']['end_time'] && date('Y',strtotime($pvalue['start']['start_time'])) != '1970')echo date('Y-m-d',strtotime($pvalue['end']['end_time'])); 
    else echo "";
  ?>&nbsp;
</td>

<td><?php 
    if($pvalue['start']['end_time'] && date('Y',strtotime($pvalue['start']['start_time'])) != '1970')echo date('H:i:s',strtotime($pvalue['end']['end_time']));
    else echo "";
  ?>&nbsp;
</td>
            <td><?php if($pvalue['start']['estimated_time'])echo $pvalue['start']['estimated_time']; else echo "";?>&nbsp;</td>
            <td>
              <?php echo $pvalue['start']['actual_time_from_process'];?>
              <?php
              // if(($pvalue['start']['start_time'] && date('Y',strtotime($pvalue['start']['start_time'])) != '1970') && ($pvalue['end']['end_time'] && date('Y',strtotime($pvalue['end']['end_time'])) != '1970')){
              //     $date1=strtotime($pvalue['start']['start_time']);
                  
              //     if($pvalue['end']['end_time']){
              //       $date2=strtotime($pvalue['end']['end_time']);  
              //     }else{
              //       $date2 = date('Y-m-d H:i:s');
              //     }
                    
              //     $timetaken = round(($date2 - $date1) / 60/ 60 ,2);
              //     echo $timetaken;
              //     // Configure::write('debug',1);
              //     // debug($timetaken);
              //     // Configure::write('debug',0);
              //     // $diff=date_diff($date1,$date2);                  
              //     // echo $diff->format("%d:%h:%m");
              // }else{
              //   echo "";
              // }
              ?>&nbsp;
            </td>
            
            <td class="text-danger"><?php if($pvalue['total_hold'])echo $pvalue['total_hold']; else echo "";?>&nbsp;</td>
            
            <td><?php 
            $newU = $pvalue['units_completed'] - $file['File']['unit'];
            // if($$newU > 0)echo $newU;
            // else echo "0";

            echo $newU;
            ?>&nbsp;</td>
            
            <td><?php if($pvalue['units_completed'])echo $pvalue['units_completed'];
                      else echo "0";
                      ?>&nbsp;</td>
            
            <td>&nbsp;</td>

            <td><?php echo $fileStatuses[$pvalue['end']['current_status']];?>&nbsp;</td>
            
            <td><?php echo $pvalue['end']['comments'];?>&nbsp;</td>
            
            
            <td>&nbsp;
              <?php 

              // $date1=strtotime($pvalue['start']['start_time']);
              // $date2=strtotime($pvalue['end']['end_time']);
              // $timetaken = $date2 - $date1;
              // $timetaken = $timetaken/60/24;
              // Configure::write('debug',1);
              // debug($pvalue['start']['start_time']);
              // debug($pvalue['end']['start_time']);
              // debug($date1);
              // debug($timetaken);
              // Configure::write('debug',0);
                  // foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
              $timetaken = $this->requestAction('projects/time_to_sec/'. base64_encode($pvalue['start']['actual_time_from_process']));
              // $t = $pvalue['start']['actual_time_from_process'];
              $timetaken = $timetaken / 60;

              echo round($pvalue['units_completed'] / $timetaken,2);?>%
            </td>
            
            <td>&nbsp;</td>
        <?php } ?>  
    </tr>
  <?php } ?>

</table>

<script type="text/javascript">
    $("#filetable<?php echo $files['Milestone']['id'];?>").tableExport(
            {
              headers: true,                        // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
              footers: true,                      // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
              formats: ["csv"],             // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
              filename: "<?php echo $qucipro['Project']['title'];?>",                     // (id, String), filename for the downloaded file, (default: 'id')
              bootstrap: false,                   // (Boolean), style buttons using bootstrap, (default: true)
              exportButtons: true,                // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
              position: "bottom",                 // (top, bottom), position of the caption element relative to table, (default: 'bottom')
              ignoreRows: null,                   // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
              ignoreCols: null,                   // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
              trimWhitespace: true,               // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
              RTL: false,                         // (Boolean), set direction of the worksheet to right-to-left (default: false)
              sheetname: "<?php echo $files['Milestone']['title'];?>"                     // (id, String), sheet name for the exported spreadsheet, (default: 'id')
            }
        );

</script> 

<?php } ?>



</div>
<?php }else{ ?> 
<div class="alert alert-danger">Select project first.</div>
<?php } ?>

<script type="text/javascript">
  $().ready(function(){
    $("#ProjectProjectId").on('change',function(){     
      $('#ProjectMilestoneId').val(-1).trigger('chosen:updated');
      $('#ProjectCityId').val(-1).trigger('chosen:updated');
      $('#ProjectBlockId').val(-1).trigger('chosen:updated'); 
      var selected = $('#ProjectProjectId').val()
        $.ajax({
            url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_milestones/" + selected,
            success: function (data, result) {
              console.log(data);
                $('#ProjectMilestoneId').find('option').remove().end().append(data).trigger('chosen:updated');
            }
        });

    });


    $("#ProjectMilestoneId").on('change',function(){   

      $('#ProjectCityId').val(-1).trigger('chosen:updated');
      $('#ProjectBlockId').val(-1).trigger('chosen:updated'); 

      var selected = $('#ProjectMilestoneId').val()
        $.ajax({
            url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_cb/" + selected,
            success: function (data, result) {
              var obj = jQuery.parseJSON(data);

                $('#ProjectCityId').find('option').remove().end().append(obj[0]).trigger('chosen:updated');
                $('#ProjectBlockId').find('option').remove().end().append(obj[1]).trigger('chosen:updated');
            }
        });

    });

  });
</script>

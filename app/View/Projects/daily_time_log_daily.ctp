<div class="row">
  <div class="projects form col-md-12">
    <h4><?php echo __('Project'); ?>
      <?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
      <?php echo $this->Html->link(__('MIS'), array('action'=>'mis',$this->request->params['pass'][0]),array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
      <?php echo $this->Html->link(__('Reports'), array('action'=>'daily_time_log_daily',$this->request->params['pass'][0]),array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
      <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
    </h4>
  </div>
</div>

<?php 
  if($this->request->data['Project']['project_id'])$pid = $this->request->data['Project']['project_id'];
  else $pid = $this->request->params['pass'][0];
?>
<h3>Reports & Graphs</h3>
<div class="row">
  <div class="col-md-12">
    <div class="btn-group">
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('MIS'), array('controller' => 'projects', 'action' => 'mis',$pid),array()); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('File Tracker'), array('controller' => 'projects', 'action' => 'tracker','project_id'=>$pid),array()); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('Time Sheets'), array('controller' => 'projects', 'action' => 'user_time_sheet','project_id'=>$pid)); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('Production / Quality log'), array('controller' => 'file_processes', 'action' => 'index','project_id'=>$pid)); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('Project team board'), array('controller' => 'projects', 'action' => 'project_team_board')); ?></div>
    </div>
  </div>
</div>
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





<?php 

// if($project_id);

echo "<div class='row'>";
echo $this->Form->create('Project');
echo "<div class='col-md-4'>".$this->Form->input('project_id',array('default'=>$project_id))."</div>";
echo "<div class='col-md-3'>".$this->Form->input('dates')."</div>";
echo "<div class='col-md-3'><br />".$this->Form->input('type',array('type'=>'radio','legend'=>false, 'options'=>array(0=>'day',1=>'week')))."</div>";
echo "<div class='col-md-2'><br />".$this->Form->submit('submit',array('class'=>'btn btn-success btn-sm'))."</div>";
echo $this->Form->end();
echo "</div>";
?>
<script type="text/javascript">
  $("#ProjectProjectId").chosen();
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
        startDate: moment().add(-1, 'months'),
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

<?php 
	echo $this->Html->script(array(
		'plugins/chartjs/Chart-2.min',
		// 'timeknots-master/src/d3.v2.min',
  //   	'timeknots-master/src/timeknots-min',
  //   	'Lightweight-jQuery-Timeline-Plugin-jqtimeline/js/jquery.jqtimeline',
  //   	'PapaParse-5.0.2/papaparse.min',
  //   	'bootstrap-editable.min'
    )); 
    echo $this->fetch('script'); 

  //   echo $this->Html->css(array(
		// 'Lightweight-jQuery-Timeline-Plugin-jqtimeline/css/jquery.jqtimeline',
		// 'bootstrap-editable'
  //   )); 
  //   echo $this->fetch('css'); 

    // Configure::write('debug',1);
    // debug($data);
    // // debug($data);
    // exit;

    $avg = $avgcom = $esttimeavg = 0;
    foreach ($data as $date => $values) {
        $avg = $avg + round($values['FileProcess']['total_time']/60/60);
        $avgcom = $avgcom + round($values['FileProcess']['units_completed']);
        if($values['FileProcess']['est_time'] > 0)$esttimeavg = $esttimeavg + round($values['FileProcess']['est_time']/60/60);
        if($values['FileProcess']['plan_expected'] > 0)$planExpected = $planExpected + round($values['FileProcess']['plan_expected']);
        // if($values['FileProcess']['expected'] > 0)$expected = $expected + round($values['FileProcess']['expected']);
        // if($values['FileProcess']['expected'] > 0)$expected = $values['FileProcess']['expected'];
    }
    
    // echo ">>" . $avg;

    $avg = round($avg / count($data));

    $avgcom = round($avgcom / count($data));
    $esttimeavg = $esttimeavg / count($data);
    $planExpected = $planExpected / count($data);
    
    // echo $esttimeavg;
    // Configure::write('debug',1);
    // debug($data);
    // exit;
    foreach ($data as $date => $values) {
      // debug(json_decode(base64_decode($values['FileProcess']['try']),true));
      // debug($values);
      // debug(json_decode(base64_decode($values['FileProcess']['try']),true));
      
      $provals = json_decode(base64_decode($values['FileProcess']['try']),true);
      // debug($provals);
      $p = 0;
      foreach ($provals as $pkey => $pvalue) {
        $prolables[$date] = $pkey;
        $proarray[$pkey][] = $pvalue;
        $totalFiles[] = $values['FileProcess']['total_files'];
        $p++;
      }

      $provalsuc = json_decode(base64_decode($values['FileProcess']['process_wise_total_units']),true);
      // Configure::write('debug',1);
      // debug($provalsuc);
      // exit;
      $p = 0;
      foreach ($provalsuc as $pkey => $pvalue) {
        $prolablesuc[$date] = $pkey;
        if($pvalue){
          $proarrayuc[$pkey][] = array($pvalue['FileProcess']['total_completed_units'],$pvalue['FileProcess']['total_estimated_units']);
          // $proaccuc[$pkey][] = $pvalue['FileProcess']['total_estimated_units'];
        }else{
          $proarrayuc[$pkey][]= array(0,$pvalue['FileProcess']['total_estimated_units']);
          // $proaccuc[$pkey][]= $pvalue['FileProcess']['total_estimated_units'];
        } 
        $p++;
      }

        $labes[] = $date;
        
        $total_time_avg[] = $avg;
        $total_unit_avg[] = $avgcom;
        $est_time_avg[] = round($esttimeavg);
        $plan_expected[] = round($planExpected);
        $total_files_emp[] = $values['FileProcess']['total_files_emp'];
        $expected[] = $expected_time;


        if($values['FileProcess']['qc_done'] > 0)$qc_done[] = $values['FileProcess']['qc_done'];
        else $qc_done[] = 0;

        if($values['FileProcess']['qc_completed'] > 0)$qc_completed[] = $values['FileProcess']['qc_completed'];
        else $qc_completed[] = 0;

        if($values['FileProcess']['merging_completed'] > 0)$merging_completed[] = $values['FileProcess']['merging_completed'];
        else $merging_completed[] = 0;

        if(!$values['FileProcess']['total_time'])$total_time[$date] = 0;
        else $total_time[$date] = round($values['FileProcess']['total_time']/60/60);


        if(!$values['FileProcess']['delayed_total_time'])$delayed_total_time[$date] = 0;
        else $delayed_total_time[$date] = round($values['FileProcess']['delayed_total_time']);

    
        if(!$values['FileProcess']['total_time'])$total_units[] = 0;
        else $total_units[] = $values['FileProcess']['units_completed'];

        // if(!$values['FileProcess']['expected'])$expected[] = 0;
        // else $expected[] = $values['FileProcess']['expected'];

        $colors1[]  = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
        $colors2[]  = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);


        // $proarray[$date] = array_values(json_decode(base64_decode($values['FileProcess']['try']),true));
    }
    // Configure::write('debug',1);    
    // debug($values);
    // echo json_decode($avgcom);
   ?>
<h4>Holidays/weekends</h4>
  <div class="row">
    <div class="col-md-12">
      <?php foreach ($holidays as $holiday) {
        echo "<span class='badge label-warning'>" . $holiday . "</span>&nbsp;";
      }?>
    </div>
  </div> 
<h3>MIS</h3>
  <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">Expected Hours Per Day</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="expectedhours<?php echo $milestone['Milestone']['id']?>"></canvas></li>
            <div style="overflow: scroll;">
              <table class="table table-responsive table-bordered table-condenced tablesorter" id="ExpectedHoursPerDay">
                <thead>
                  <tr>
                    <th>Date</th>
                    <?php foreach ($total_time as $key => $value) { ?>
                      <th><?php echo $key;?></th>
                    <?php } ?>
                    <th>Total</th>
                  </tr>
                </thead>
                <tr>
                  <td></td>
                  <?php 
                  $total = 0;
                  foreach ($total_time as $key => $value) { ?>
                      <td><?php echo $this->Html->link($value,array(
                        'action'=>'expected_hours',
                        'project_id'=> $project_id, 
                        'date'=>base64_encode($key)),array('target'=>'_blank'));?></td>
                  <?php 
                  $total = $total + $value;
                } ?>
                <th><?php echo $total;?></th>
                </tr>
              </table>
              <script type="text/javascript">
                $("#ExpectedHoursPerDay").tableExport(
                        {
                          headers: true,
                          footers: true,
                          formats: ["csv"],
                          filename: "Expected Hours Per Day",
                          bootstrap: true,
                          exportButtons: true,
                          default: true,
                          position: "bottom",
                          ignoreRows: null,
                          ignoreCols: 7,
                          trimWhitespace: true,
                          RTL: false,
                          sheetname: "id"
                        }
                    );
                </script> 
            </div>

          </ul>

          
          <script>
            var processconfig = {
              type: 'bar',
              // labels : '<?php echo json_encode(array_keys($prolables));?>',
              data: {
                datasets: [
                  {     
                      type: 'bar',                   
                      backgroundColor:"#36a2eb",
                      borderColor:"#36a2eb",
                      label: 'Total Time (Hrs)',
                      fill: false,
                      // borderDash: [5, 5],
                      data: <?php echo json_encode(array_values($total_time),JSON_NUMERIC_CHECK);?>,
                      // yAxisID: 'ty-axis-2122',
                  }, 
                  {     
                      type: 'line',                   
                      backgroundColor:"#f3503a",
                      borderColor:"#f3503a",
                      label: 'Expected Time (Hrs)',
                      fill: false,
                      borderDash: [5, 5],
                      data: <?php echo json_encode($expected,JSON_NUMERIC_CHECK);?>,
                      // yAxisID: 'ty-axis-2122',
                  },                
                    
                ],             
                labels: <?php echo json_encode(array_keys($total_time));?>,
              },
              options: {
                responsive: true,
                legend: {
                  display: true,
                  // fullWidth : true,
                  // display: true,
                  position: 'bottom',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                    yAxes: [                      
                      {
                        ticks: {
                          min: 0,
                          // max: <?php echo $newResourceGraph[0];?>,
                        },
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'right',
                        id: 'ty-axis-2122',
                    },
                    // {
                    //     ticks: {
                    //       // min: 0,
                    //       // max: <?php echo $newResourceGraph[0];?>,
                    //     },
                    //     type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    //     display: true,
                    //     position: 'left',
                    //     id: 'ty-axis-21221',
                    // }
                    ],
                  }
              }
            };

            // $().ready(function(){
              var proresourcecostctx = document.getElementById('expectedhours<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>
      </div>
   </div>


   <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">Delayed Files</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="delayedfiles<?php echo $milestone['Milestone']['id']?>"></canvas></li>
            <div style="overflow: scroll;">
              <table class="table table-responsive table-bordered table-condenced tablesorter" id="DelayedFiles">
                <thead>
                  <tr>
                    <th>Date</th>
                    <?php 
                    $total = 0;
                    foreach ($delayed_total_time as $key => $value) { ?>
                      <th><?php echo $key;?></th>
                    <?php } ?>
                    <th>Total</th>
                  </tr>
                </thead>
                <tr>
                  <td></td>
                  <?php foreach ($delayed_total_time as $key => $value) { ?>
                      <td><?php echo $this->Html->link($value,array('action'=>'delayed_files','project_id'=>$this->request->data['Project']['project_id'], 'date'=>base64_encode($key)),array('target'=>'_blank'));?></td>
                  <?php 
                  $total = $total + $value;
                } ?>
                <th><?php echo $total;?></th>
                </tr>
              </table>
              <script type="text/javascript">
                $("#DelayedFiles").tableExport(
                        {
                          headers: true,
                          footers: true,
                          formats: ["csv"],
                          filename: "Delayed Files",
                          bootstrap: true,
                          exportButtons: true,
                          default: true,
                          position: "bottom",
                          ignoreRows: null,
                          ignoreCols: 7,
                          trimWhitespace: true,
                          RTL: false,
                          sheetname: "id"
                        }
                    );
                </script> 
            </div>

          </ul>

          
          <script>
            var processconfig = {
              type: 'bar',
              // labels : '<?php echo json_encode(array_keys($prolables));?>',
              data: {
                datasets: [
                  {     
                      type: 'bar',                   
                      backgroundColor:"#f3503b",
                      borderColor:"#f3503b",
                      label: 'Total Delayed Files',
                      fill: false,
                      // borderDash: [5, 5],
                      data: <?php echo json_encode(array_values($delayed_total_time),JSON_NUMERIC_CHECK);?>,
                      // yAxisID: 'ty-axis-2122',
                  } 
                ],             
                labels: <?php echo json_encode(array_keys($delayed_total_time));?>,
              },
              options: {
                responsive: true,
                legend: {
                  display: true,
                  // fullWidth : true,
                  // display: true,
                  position: 'bottom',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                    yAxes: [                      
                      {
                        ticks: {
                          min: 0,
                          // max: <?php echo $newResourceGraph[0];?>,
                        },
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'right',
                        id: 'ty-axis-2122d',
                    },
                    // {
                    //     ticks: {
                    //       // min: 0,
                    //       // max: <?php echo $newResourceGraph[0];?>,
                    //     },
                    //     type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    //     display: true,
                    //     position: 'left',
                    //     id: 'ty-axis-21221',
                    // }
                    ],
                  }
              }
            };

            // $().ready(function(){
              var proresourcecostctx = document.getElementById('delayedfiles<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>
      </div>
   </div>

   <?php 
   // Configure::write('debug',1);
   // $prowiseunitscompletedss = json_decode($prowiseunitscompleteds,false);
   // debug($start_date);
   // debug($end_date);
   ?>

   <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">Processwise Units Completed</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="processwiseunits<?php echo $milestone['Milestone']['id']?>"></canvas></li>
            <div style="overflow: scroll;">
              <table class="table table-responsive table-bordered table-condenced tablesorter" id="ProcesswiseUnitsCompleted">
                <thead>
                  <tr>
                    <th>Process</th>
                    <?php foreach ($prowiseunitscompleteds as $key => $value) { ?>
                      <th><?php echo $key;?></th>
                    <?php } ?>
                    <th>Total</th>
                  </tr>
                </thead>
                <tr>
                  <th>Units Completed</th>
                  <?php 
                  $total = 0;
                  foreach ($prowiseunitscompleteds as $key => $value) { ?>
                      <td>                        
                        <?php echo $this->Html->link($value['FileProcess']['total_completed_units'],array('action'=>'pro_wise_units_completed','project_id'=>$this->request->data['Project']['project_id'], 'start_date'=>base64_encode($start_date),'end_date'=>base64_encode($end_date)),array('target'=>'_blank'));?>
                      </td>
                  <?php 
                  $total = $total + $value['FileProcess']['total_completed_units'];
                } ?>
                <th><?php echo $total;?></th>
                </tr>
              </table>
              <script type="text/javascript">
                $("#ProcesswiseUnitsCompleted").tableExport(
                        {
                          headers: true,
                          footers: true,
                          formats: ["csv"],
                          filename: "Processwise Units Completed",
                          bootstrap: true,
                          exportButtons: true,
                          default: true,
                          position: "bottom",
                          ignoreRows: null,
                          ignoreCols: 7,
                          trimWhitespace: true,
                          RTL: false,
                          sheetname: "id"
                        }
                    );
                </script> 
            </div>

          </ul>


          <?php 
          foreach ($prowiseunitscompleteds as $processname => $data) {
            $allprocesslabels[] = $processname;
            if($data['FileProcess']['total_completed_units'] == null)$data['FileProcess']['total_completed_units'] = 0;
            $processcompletedunits[] = $data['FileProcess']['total_completed_units'];
            
            if($data['FileProcess']['total_estimated_units'] == null)$data['FileProcess']['total_estimated_units'] = 0;
            $processexpectedunits[] = $data['FileProcess']['total_estimated_units'];
          }?>  
          
          <script>
            var processconfig = {
              type: 'bar',
              // labels : '<?php echo json_encode(array_keys($prolables));?>',
              data: {
                datasets: [
                // {     
                //       type: 'line',                   
                //       backgroundColor:"#f3503a",
                //       borderColor:"#f3503a",
                //       label: 'Expected Units',
                //       fill: false,
                //       borderDash: [5, 5],
                //       data: <?php echo json_encode($processexpectedunits,JSON_NUMERIC_CHECK);?>,
                //       // yAxisID: 'ty-axis-2122',
                //   },
                  {     
                      type: 'bar',                   
                      backgroundColor:"#36a2eb",
                      borderColor:"#36a2eb",
                      label: 'Total Completed Units',
                      fill: false,
                      // borderDash: [5, 5],
                      data: <?php echo json_encode($processcompletedunits,JSON_NUMERIC_CHECK);?>,
                      // yAxisID: 'ty-axis-2122',
                  }
                    
                ],             
                labels: <?php echo json_encode($allprocesslabels);?>,
              },
              options: {
                responsive: true,
                legend: {
                  display: true,
                  // fullWidth : true,
                  // display: true,
                  position: 'bottom',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                    yAxes: [                      
                      {
                        ticks: {
                          min: 0,
                          // max: <?php echo $newResourceGraph[0];?>,
                        },
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'right',
                        id: 'ty-axis-2122',
                    },
                    // {
                    //     ticks: {
                    //       // min: 0,
                    //       // max: <?php echo $newResourceGraph[0];?>,
                    //     },
                    //     type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    //     display: true,
                    //     position: 'left',
                    //     id: 'ty-axis-21221',
                    // }
                    ],
                  }
              }
            };

            // $().ready(function(){
              var proresourcecostctx = document.getElementById('processwiseunits<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>
      </div>
   </div>


   <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">Processwise Files Completed</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="processwisefiless<?php echo $milestone['Milestone']['id']?>"></canvas></li>
            <div style="overflow: scroll;">
              <table class="table table-responsive table-bordered table-condenced tablesorter" id="ProcesswiseFilesCompleted">
                <thead>
                  <tr>
                    <th>Process</th>
                    <?php foreach ($fileProcessCompleted as $key => $value) { ?>
                      <th><?php echo $key;?></th>
                    <?php } ?>
                    <th>Total</th>
                  </tr>
                </thead>
                <tr>
                    <th>Files Completed</th>                    
                  <?php 
                  $total = 0;
                  foreach ($fileProcessCompleted as $key => $value) { ?>
                      <td><?php echo $value['FileProcess']['total_completed_files'];?></td>
                  <?php 
                  $total = $total + $value['FileProcess']['total_completed_files'];
                } ?>
                <th><?php echo $total;?></th>
                </tr>
              </table>
              <script type="text/javascript">
                $("#ProcesswiseFilesCompleted").tableExport(
                        {
                          headers: true,
                          footers: true,
                          formats: ["csv"],
                          filename: "Processwise Files Completed",
                          bootstrap: true,
                          exportButtons: true,
                          default: true,
                          position: "bottom",
                          ignoreRows: null,
                          ignoreCols: 7,
                          trimWhitespace: true,
                          RTL: false,
                          sheetname: "id"
                        }
                    );
                </script> 
            </div>

          </ul>


          <?php 
          $allprocesslabels = '';
          foreach ($fileProcessCompleted as $processname => $data) {
            $allprocesslabels[] = $processname;
            $processcompletedfiles[] = $data['FileProcess']['total_completed_files'];
            $processexpectedfiles[] = $data['FileProcess']['total_files'];
          }
          ?>  
          
          <script>
            var processconfig = {
              type: 'bar',
              // labels : '<?php echo json_encode(array_keys($prolables));?>',
              data: {
                datasets: [
                {     
                      type: 'line',                   
                      backgroundColor:"#f3503a",
                      borderColor:"#f3503a",
                      label: 'Total Files',
                      fill: false,
                      borderDash: [5, 5],
                      data: <?php echo json_encode($processexpectedfiles,JSON_NUMERIC_CHECK);?>,
                      // yAxisID: 'ty-axis-2122',
                  },
                  {     
                      type: 'bar',                   
                      backgroundColor:"#36a2eb",
                      borderColor:"#36a2eb",
                      label: 'Total Completed Files',
                      fill: false,
                      // borderDash: [5, 5],
                      data: <?php echo json_encode($processcompletedfiles,JSON_NUMERIC_CHECK);?>,
                      // yAxisID: 'ty-axis-2122',
                  }
                    
                ],             
                labels: <?php echo json_encode($allprocesslabels);?>,
              },
              options: {
                responsive: true,
                legend: {
                  display: true,
                  // fullWidth : true,
                  // display: true,
                  position: 'bottom',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                    yAxes: [                      
                      {
                        ticks: {
                          min: 0,
                          // max: <?php echo $newResourceGraph[0];?>,
                        },
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'right',
                        id: 'ty-axis-2122',
                    },
                    // {
                    //     ticks: {
                    //       // min: 0,
                    //       // max: <?php echo $newResourceGraph[0];?>,
                    //     },
                    //     type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    //     display: true,
                    //     position: 'left',
                    //     id: 'ty-axis-21221',
                    // }
                    ],
                  }
              }
            };

            // $().ready(function(){
              var proresourcecostctx = document.getElementById('processwisefiless<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>
      </div>
   </div>
   

   <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">Process wise Files Completed (Daily)</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="process<?php echo $milestone['Milestone']['id']?>"></canvas></li>
            <div style="overflow: scroll;">
              <table class="table table-responsive table-bordered table-condenced tablesorter" id="ProcesswiseFilesCompletedDaily">
                <thead>
                  <tr>
                    <th>Date</th>
                    <?php foreach ($prolables as $key => $value) { 
                      $prolablesnames[] = $key;
                      ?>
                      <th><?php echo $key;?></th>
                    <?php } ?>
                    <th>Total</th>
                  </tr>
                </thead>
                <?php foreach ($proarray as $key => $value) { ?>
                  <tr>
                    <th><?php echo $key;?></th>
                    <?php 
                    $total = 0;
                    foreach ($value as $c) { ?>
                      <td><?php echo $c;?></td>
                    <?php 
                    $total = $total + $c;
                  } ?>
                  <th><?php echo $total;?></th>
                  </tr>
                <?php } ?>              
              </table>
              <script type="text/javascript">
                $("#ProcesswiseFilesCompletedDaily").tableExport(
                        {
                          headers: true,
                          footers: true,
                          formats: ["csv"],
                          filename: "Process wise Files Completed Daily",
                          bootstrap: true,
                          exportButtons: true,
                          default: true,
                          position: "bottom",
                          ignoreRows: null,
                          ignoreCols: 7,
                          trimWhitespace: true,
                          RTL: false,
                          sheetname: "id"
                        }
                    );
                </script> 
            </div>

          </ul>

          
          <script>
            var processconfig = {
              type: 'bar',
              // labels : '<?php echo json_encode(array_keys($prolables));?>',
              data: {
                datasets: [
                  {     
                      type: 'line',                   
                      backgroundColor:"#36a2eb",
                      borderColor:"#36a2eb",
                      label: 'Total Files',
                      fill: false,
                      borderDash: [5, 5],
                      data: <?php echo json_encode($totalFiles,JSON_NUMERIC_CHECK);?>,
                      yAxisID: 'ty-axis-2122',
                  },
                <?php foreach ($proarray as $key => $value) { 
                  $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT)
                  ?>
                  {     
                        label: '<?php echo $key;?>',
                        backgroundColor:"<?php echo $color?>",
                        borderColor:"#<?php echo $color?>",
                        fill: false,
                        data: <?php echo json_encode($value,JSON_NUMERIC_CHECK);?>,
                        yAxisID: 'ty-axis-21221',
                    },
                <?php } ?>
                    
                ],             
                labels: <?php echo json_encode($prolablesnames);?>,
              },
              options: {
                responsive: true,
                legend: {
                  display: true,
                  // fullWidth : true,
                  // display: true,
                  position: 'bottom',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                    yAxes: [                      
                      {
                        ticks: {
                          min: 0,
                          // max: <?php echo $newResourceGraph[0];?>,
                        },
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'right',
                        id: 'ty-axis-2122',
                    },
                    {
                        ticks: {
                          // min: 0,
                          // max: <?php echo $newResourceGraph[0];?>,
                        },
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'left',
                        id: 'ty-axis-21221',
                    }
                    ],
                  }
              }
            };

            // $().ready(function(){
              var proresourcecostctx = document.getElementById('process<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>
      </div>
   </div>


   <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">Process-wise Units Completed</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="process-uc<?php echo $milestone['Milestone']['id']?>"></canvas></li>
            <div style="overflow: scroll;">
              <table class="table table-responsive table-bordered table-condenced tablesorter" id="ProcesswiseUnitsCompleted">
                <thead>
                  <tr>
                    <th>Date</th>
                    <?php foreach ($prolablesuc as $key => $value) { ?>
                      <th><?php echo $key;?></th>
                    <?php } ?>
                    <th>Total</th>
                  </tr>
                </thead>
                <?php foreach ($proarrayuc as $key => $value) { ?>
                  <tr>
                    <th>Planned</th>
                    <?php foreach ($value as $c) { ?>
                      <td><?php echo $c[1];?></td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <th>Actual <?php echo $key;?></th>
                    <?php 
                    $total = 0;
                    foreach ($value as $c) { ?>
                      <td><?php echo $c[0];?></td>
                    <?php 
                    $total = $total + $c[0];
                  } ?>
                  <th><?php 
                  $newtotal = $newtotal + $total;
                  echo $total;?></th>
                  </tr>

                <?php } ?>
                <tr>
                  <th colspan="<?php echo count($prolablesuc) + 1;?>" class="text-right">Total</th>
                  <th><?php echo $newtotal;?></th>
                </tr>
              </table>
              <script type="text/javascript">
                $("#ProcesswiseUnitsCompleted").tableExport(
                        {
                          headers: true,
                          footers: true,
                          formats: ["csv"],
                          filename: "Process-wise Units Completed",
                          bootstrap: true,
                          exportButtons: true,
                          default: true,
                          position: "bottom",
                          ignoreRows: null,
                          ignoreCols: 7,
                          trimWhitespace: true,
                          RTL: false,
                          sheetname: "id"
                        }
                    );
                </script> 
            </div>

          </ul>

          <?php 
          // debug($proarrayuc);
          // foreach ($proarrayuc as $key => $value) {
          //   // debug($value);
          //   // debug(json_encode($value[0],JSON_NUMERIC_CHECK));
          //   foreach ($value as $v) {
          //     // debug($v);
          //     $v1[] = $v[0];
          //     $v2[] = $v[1];
          //   }
          // }
          // debug($v1);
          // debug($v2);
          ?>
          <?php echo json_encode($v1[2],JSON_NUMERIC_CHECK);?>
          <script>
            var processconfig = {
              type: 'bar',
              // labels : '<?php echo json_encode(array_keys($prolables));?>',
              data: {
                labels: <?php echo json_encode(array_keys($prolablesuc));?>,
                datasets: [
                <?php foreach ($proarrayuc as $key => $value) { 
                  $vdata1 = $vdata2 = null;
                  foreach ($value as $v) {                  
                    $vdata1[] = $v[0];
                    $vdata2[] = $v[1];
                  }
                  $color1 = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                  $color2 = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                  ?>
                  
                  {     
                      label: '<?php echo $key;?> : Expected',
                      // type: 'bar',                   
                      backgroundColor:"<?php echo $color2?>",
                      // borderColor:"#<?php echo $color?>",
                      // borderDash: [5, 5],
                      // label: 'Total Files',
                      // fill: false,
                      data: <?php echo json_encode($vdata2,JSON_NUMERIC_CHECK);?>,
                  },
                  {     
                        label: '<?php echo $key;?>',
                        // type: 'bar',                   
                        backgroundColor:"<?php echo $color1?>",
                        // borderColor:"#<?php echo $color?>",
                        // borderDash: [5, 5],
                        // label: 'Total Files',
                        // fill: false,
                        data: <?php echo json_encode($vdata1,JSON_NUMERIC_CHECK);?>,
                    },
                <?php } ?>
                    
                ],                             
              },
              options: {
                responsive: true,
                legend: {
                  display: true,
                  // fullWidth : true,
                  // display: true,
                  position: 'bottom',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                  yAxes: [{
                    // stacked : true,
                    ticks: {
                      // min: 0,                        
                    }
                  }],
                  xAxes: [{
                    // stacked : true,
                    ticks: {
                      // min: 0,                        
                    }
                  }],
                }
              }
            };

            // $().ready(function(){
              var proresourcecostctx = document.getElementById('process-uc<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>
      </div>
   </div>



   

   <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">Output (completed units)</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="dailyunits<?php echo $milestone['Milestone']['id']?>"></canvas>
              <div style="overflow: scroll;">
                <table class="table table-responsive table-bordered table-condenced tablesorter" id="Outputcompletedunits">
                 <thead>
                  <tr>
                    <th>Date</th>
                    <?php foreach ($labes as $key => $value) { ?>
                      <th><?php echo $value;?></th>
                    <?php } ?>
                    <th>Total</th>
                  </tr>
                </thead>
                <tr>
                  <th>1 Total Units</th>
                  <?php 
                  $total = 0;
                  foreach ($total_units as $key => $value) { ?>
                    <td><?php echo $value;?></td>
                  <?php 
                  $total = $total + $value;
                } ?>
                <th><?php echo $total;?></th>
                </tr>
                <tr>
                  <th>2 Total Units Avg</th>
                  <?php foreach ($total_unit_avg as $key => $value) { ?>
                    <td><?php echo $value;?></td>
                  <?php } ?>
                  <th></th>
                </tr>
                <tr>
                  <th>3 Planed Units</th>
                  <?php foreach ($plan_expected as $key => $value) { ?>
                    <td><?php echo $value;?></td>
                  <?php } ?>
                  <th></th>
                </tr>
              </table>

              <script type="text/javascript">
                $("#Outputcompletedunits").tableExport(
                        {
                          headers: true,
                          footers: true,
                          formats: ["csv"],
                          filename: "Output completed units",
                          bootstrap: true,
                          exportButtons: true,
                          default: true,
                          position: "bottom",
                          ignoreRows: null,
                          ignoreCols: 7,
                          trimWhitespace: true,
                          RTL: false,
                          sheetname: "id"
                        }
                    );
                </script> 
            </div>

            </li>
          </ul>

          
          <script>
            var resourcecostconfig = {
              type: 'bar',
              data: {
                datasets: [
                    {     
                        type: 'bar',                   
                        backgroundColor:"#36a2eb",
                        borderColor:"#36a2eb",
                        label: 'Units Completed',
                        fill: false,
                        data: <?php echo json_encode($total_units,JSON_NUMERIC_CHECK);?>,
                        yAxisID: 'y-axis-21',
                    },{     
                        type: 'line',                   
                        backgroundColor:"#36a2eb7d",
                        borderColor:"#36a2eb7d",
                        borderDash: [5, 5],
                        label: 'Avg Units',
                        fill: false,
                        data: <?php echo json_encode($total_unit_avg,JSON_NUMERIC_CHECK);?>,
                        yAxisID: 'y-axis-11',
                    }
                    ,{     
                        type: 'line',                   
                        backgroundColor:"#36a55b7d",
                        borderColor:"#36a55b7d",
                        borderDash: [5, 5],
                        label: 'Plan Expected',
                        fill: false,
                        data: <?php echo json_encode($plan_expected,JSON_NUMERIC_CHECK);?>,
                    }
                ],             
                labels: <?php echo json_encode($labes);?>,
              },
              options: {
                responsive: true,
                legend: {
                  display: true,
                  // fullWidth : true,
                  // display: true,
                  position: 'bottom',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                    yAxes: [
                      {
                        ticks: {
                          min: 0
                        },
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'left',
                        id: 'y-axis-11',
                        }, 
                      {
                        ticks: {
                          min: 0,
                          // max: <?php echo $newResourceGraph[0];?>,
                        },
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'right',
                        id: 'y-axis-21',
                    }],
                  }
              }
            };

            // $().ready(function(){
              var resourcecostctx = document.getElementById('dailyunits<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.resourcecostgraph = new Chart(resourcecostctx, resourcecostconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>

          <script type="text/javascript">
                $("#dailyunits_tbl").tableExport(
                        {
                          headers: true,
                          footers: true,
                          formats: ["csv"],
                          filename: "id",
                          bootstrap: true,
                          exportButtons: true,
                          default: true,
                          position: "bottom",
                          ignoreRows: null,
                          ignoreCols: null,
                          trimWhitespace: true,
                          RTL: false,
                          sheetname: "id"
                        }
                    );

            </script> 
      </div>
   </div>


   <div class="row">
       <div class="col-md-12 draggable">
        <ul class="list-group">
          <li class="list-group-item"><h4 class="text-center">QC/QA Completed</h4></li>
            <li class="list-group-item"><canvas width="800" height="190" id="qcqa<?php echo $milestone['Milestone']['id']?>"></canvas></li>
          </ul>

          
          <script>
            var resourcecostconfig = {
              type: 'bar',
              data: {
                datasets: [
                    {     
                        type: 'bar',                   
                        backgroundColor:"#36a2eb7d",
                        borderColor:"#36a2eb7d",
                        borderDash: [5, 5],
                        label: 'Total Files',
                        fill: false,
                        data: <?php echo json_encode($total_files_emp,JSON_NUMERIC_CHECK);?>,
                    },{     
                        type: 'bar',                   
                        backgroundColor:"#8cc29e",
                        borderColor:"#8cc29e",
                        label: 'QC Completed',
                        fill: false,
                        data: <?php echo json_encode($qc_completed,JSON_NUMERIC_CHECK);?>,
                    },{     
                        type: 'bar',                   
                        backgroundColor:"#219ae2",
                        borderColor:"#219ae2",
                        label: 'Merging Completed',
                        fill: false,
                        data: <?php echo json_encode($merging_completed,JSON_NUMERIC_CHECK);?>,
                    }
                ],             
                labels: <?php echo json_encode($labes);?>,
              },
              options: {
                responsive: true,
                legend: {
                  display: true,
                  // fullWidth : true,
                  // display: true,
                  position: 'bottom',
                  // labels: {
                  //   // fontColor: 'rgb(255, 99, 132)'
                  // }
                },
                scales: {
                  yAxes: [{
                    ticks: {
                      // min: 0,                        
                    }
                  }],
                }
              }
            };

            // $().ready(function(){
              var resourcecostctx = document.getElementById('qcqa<?php echo $milestone['Milestone']['id']?>').getContext('2d');
              window.resourcecostgraph = new Chart(resourcecostctx, resourcecostconfig);
            // });
            // window.onload = function() {

              
            // };

            
          </script>
      </div>
   </div>


   
   <div class="row">
                  <!-- <h2>Graphs</h2> -->
                  <div class="col-md-12 draggable">
                    <ul class="list-group">
                      <li class="list-group-item"><h4 class="text-center">Resource mandays (Hours)</h4></li>
                        <li class="list-group-item"><canvas  width="800" height="190" id="resourcemandays<?php echo $milestone['Milestone']['id']?>"></canvas></li>
                      </ul>
                      <?php
                      
                      // debug($milestone['GraphDataProjectEmployee']);
                      foreach ($graphDataProjectEmployee as $proGraphRes) {
                        
                        $resourceGraph['labels'][] = $proGraphRes['Employee']['name'];
                        // $m = rand(0,50);
                        // $resourceGraph['data'][] = $proGraphRes['ProjectEmployee']['mandays'];
                        $resourceGraph['mandaysdata'][] = $proGraphRes['ProjectEmployee']['mandays'];
                        $resourceGraph['unitscompleted'][] = $proGraphRes['ProjectEmployee']['units_completed'];


                        $fineHours = 0;
                        if($proGraphRes['ProjectEmployee']['file_duration']){
                          $newval = split(':', $proGraphRes['ProjectEmployee']['file_duration']);
                          $hours = $newval[0];
                          $mins = $newval[1]/60;
                          $secs = $mins/60;
                          $fineHours = $hours + $mins + $sec;
                          $resourceGraph['filedata'][] = round($fineHours);
                          $resourceGraph['rescost'][] = round($fineHours * $proGraphRes['ProjectEmployee']['resource_cost']);
                        }else{
                          $resourceGraph['filedata'][] = 0;
                          $resourceGraph['rescost'][] = 0;
                        }
                        
                        
                        // $resourceGraph['colors1'][] = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                        // $resourceGraph['colors2'][] = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                        // $resourceGraph['colors3'][] = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                        $resourceGraph['colors1'][] = "#82b0e6";
                        $resourceGraph['colors2'][] = "#0d73e7";
                        $resourceGraph['colors3'][] = "#d679ba";
                      }
                      // Configure::write('debug',1);
                      ?>         
                      <?php 
                      // Configure::write('debug',1);
                      $newResourceGraph = $resourceGraph['mandaysdata'];
                      sort($newResourceGraph);
                      
                      ?>
                      <script>
                        var resourcemandaysconfig = {
                          type: 'bar',                          
                          data: {
                            labels: <?php echo json_encode($resourceGraph['labels']);?>,
                            datasets: [                            
                            {
                              label: 'Expected Mandays',
                              backgroundColor:<?php echo json_encode($resourceGraph['colors1']);?>,
                              yAxisID: 'y-axis-1',
                              data: <?php echo json_encode($resourceGraph['mandaysdata'],JSON_NUMERIC_CHECK);?>,
                              
                            },{
                              label: 'Completed Mandays',
                              backgroundColor:<?php echo json_encode($resourceGraph['colors2']);?>,
                              // yAxisID: 'y-axis-2',
                              data: <?php echo json_encode($resourceGraph['filedata'],JSON_NUMERIC_CHECK);?>,                             
                            },{
                              label: 'Units Completed',
                              backgroundColor:<?php echo json_encode($resourceGraph['colors3']);?>,
                              yAxisID: 'y-axis-2',
                              data: <?php echo json_encode($resourceGraph['unitscompleted'],JSON_NUMERIC_CHECK);?>,                             
                            }],             
                            
                          },
                          options: {
                            responsive: true,
                            legend: {
                              display: true,
                              // fullWidth : true,
                              // display: true,
                              // position: 'right',
                              // labels: {
                              //   // fontColor: 'rgb(255, 99, 132)'
                              // }
                            },                          
                            scales: {
                              yAxes: [
                                {
                                  ticks: {
                                    min: 0
                                  },
                                  type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                                  display: true,
                                  position: 'left',
                                  id: 'y-axis-1',
                                  }, 
                                {
                                  ticks: {
                                    min: 0,
                                    // max: <?php echo $newResourceGraph[0];?>,
                                  },
                                  type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                                  display: true,
                                  position: 'right',
                                  id: 'y-axis-2',
                              }],
                            }
                          }
                        };

                        // $().ready(function(){
                          var resourcemandaysctx = document.getElementById('resourcemandays<?php echo $milestone['Milestone']['id']?>').getContext('2d');
                          window.resourcemandaygraph = new Chart(resourcemandaysctx, resourcemandaysconfig);
                        // });
                        // window.onload = function() {

                          
                        // };

                        
                      </script>
                      <div style="overflow: scroll;">
                        <table class="table table-responsive table-bordered table-condensed">
                          <tr>
                            <th>Employee</th>
                            <?php 
                            // debug($resourceGraph);
                            foreach ($resourceGraph['labels'] as $key => $value) { 
                              echo "<td>" . $value . "</td>";
                            }?>
                          </tr>
                          <tr>
                            <th>Mandays (Hours)</th>
                            <?php 
                            // debug($resourceGraph);
                            foreach ($resourceGraph['mandaysdata'] as $key => $value) { 
                              echo "<td>" . $value . "</td>";
                            }?>
                          </tr>
                          <tr>
                            <th>Worked On File (Hours)</th>
                            <?php 
                            // debug($resourceGraph);
                            foreach ($resourceGraph['filedata'] as $key => $value) { 
                              $newval = split(':', $value);
                              $hours = $newval[0];
                              $mins = $newval[1]/60;
                              $secs = $mins/60;
                              $newhours = $hours + $mins + $sec;

                              echo "<td>" . $value . "</td>";
                            }?>
                          </tr>
                          <tr>
                            <th>Units Completed</th>
                            <?php 
                            // debug($resourceGraph);
                            foreach ($resourceGraph['unitscompleted'] as $key => $value) { 
                              echo "<td>" . $value . "</td>";
                            }?>
                          </tr>
                        </table>
                      </div>
                  
                  </div>
                  <div class="col-md-12 draggable">
                    <ul class="list-group">
                      <li class="list-group-item"><h4 class="text-center">Resource cost</h4></li>
                        <li class="list-group-item"><canvas width="800" height="190" id="resourcecost<?php echo $milestone['Milestone']['id']?>"></canvas></li>
                      </ul>

                      
                      <script>
                        var resourcecostconfig = {
                          type: 'bar',
                          data: {
                            datasets: [{
                              data: <?php echo json_encode($resourceGraph['rescost'],JSON_NUMERIC_CHECK);?>,
                              backgroundColor:<?php echo json_encode($resourceGraph['colors1']);?>,
                              label: 'Dataset 1'
                            }],             
                            labels: <?php echo json_encode($resourceGraph['labels']);?>
                          },
                          options: {
                            responsive: true,
                            legend: {
                              display: false,
                              // fullWidth : true,
                              // display: true,
                              // position: 'right',
                              // labels: {
                              //   // fontColor: 'rgb(255, 99, 132)'
                              // }
                            },
                            scales: {
                              yAxes: [{
                                ticks: {
                                  min: 0,                        
                                }
                              }],
                            }
                          }
                        };

                        // $().ready(function(){
                          var resourcecostctx = document.getElementById('resourcecost<?php echo $milestone['Milestone']['id']?>').getContext('2d');
                          window.resourcecostgraph = new Chart(resourcecostctx, resourcecostconfig);
                        // });
                        // window.onload = function() {

                          
                        // };

                        
                      </script>
                  </div>
                </div>


<?php 
// debug($topDownData);
// $x = 0;
// foreach ($topDownData as $date => $values) {
//   $top[] = $date;
//   if($values){
//     foreach ($values as $value) {
//       if($value){
//         $emps[$x][] = array($value['Employee']['name'],$value['FileProcess']['total_units_completed']);  
//       }else{
//         $emps[$x][] = array($value['Employee']['name'],0);
//       }
//     }
//   }else{
//     $emps[$x] = array('-',0);
//   }
//   $x++;  
//     // $uc[] = ;
  
//   debug($emps);
// } 
?>

                <div class="row">
                  <div class="col-md-12"><h3>Top Down Performer</h3></div>
                  <div class="col-md-12" style="overflow: scroll;">
                    <table class="table table-bordered table-condensed">
                      <?php foreach ($topDownData as $date => $values) { 
                        if($values){?>
                          <tr>
                            <th colspan="2"><?php echo $date;?></th>
                            </tr>
                          <?php foreach ($values as $e) { ?>
                            <tr>
                                <td><?php echo $e['Employee']['name'];?></td>
                                <td><?php echo $e['FileProcess']['total_units_completed'];?></td>
                              </tr>
                          <?php } ?>

                        <?php } ?>
                      <?php } ?>
                      
                    </table>
                  </div>
                </div>

   <script type="text/javascript">
  $().ready(function(){
    $('.csv').addClass('pull-right');
        // $('#dailytimelog_tbl').tablesorter({sortList: [[1,1]]});
        // $('#dailyunits_tbl').tablesorter({sortList: [[6,1]]});
        // $('#NcSentBackReport').tablesorter({sortList: [[1,1]]});
        // $('#MonthlyCustomerComplaints').tablesorter({sortList: [[14,1]]});
});
</script>
<?php }else{ ?> 
<div class="alert alert-danger">Select project first.</div>
<?php } ?>

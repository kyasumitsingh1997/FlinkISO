<div class="row">
  <div class="projects form col-md-12">
    <h4><?php echo __('Project'); ?>
    <?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
    <?php echo $this->Html->link(__('MIS'), array('action'=>'mis',$this->request->params['pass'][0]),array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
    <?php echo $this->Html->link(__('Edit'), array('action'=>'view',$this->request->params['pass'][0]),array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
    <?php echo $this->Html->link(__('Reports'), array('action'=>'daily_time_log_daily',$this->request->params['pass'][0]),array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
    <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
  </h4>
</div>
</div>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/jQueryUI/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/chartjs/Chart-2.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/js-xlsx-master/dist/xlsx.core.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/Blob.js-master/Blob.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/FileSaver.js-master/FileSaver.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/TableExport-master/src/stable/js/tableexport.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/tablesorter-master/js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/tablesorter-master/js/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/chosen.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/jquery.datepicker.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/daterangepicker/daterangepicker.js"></script>


<?php
// echo $this->Html->script(array(
//   'plugins/chartjs/Chart-2.min',
//     'js-xlsx-master/dist/xlsx.core.min', 
//     'Blob.js-master/Blob.min', 
//     'FileSaver.js-master/FileSaver.min', 
//     'TableExport-master/src/stable/js/tableexport.min',
//     'tablesorter-master/js/jquery.tablesorter',
//     'tablesorter-master/js/jquery.tablesorter.widgets',
// ));
// echo $this->fetch('script');

// echo $this->request->data['Project']['project_id'];

$pid = $project['Project']['id'];

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.js"></script>
<script type="text/javascript">
  Chart.register(ChartDataLabels); 
</script>
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
echo "<div class='row'>";
echo $this->Form->create('Project');
echo "<div class='col-md-3'>".$this->Form->input('project_id',array('default'=>$project_id))."</div>";
echo "<div class='col-md-3'><br />".$this->Form->submit('submit',array('class'=>'btn btn-success btn-sm'))."</div>";
echo $this->Form->end();
echo "</div>";
?>


<?php 
foreach($unitsCompletedResult as $dkey => $value){
  if($expectedResult[$dkey]['hrper'])$efc[$dkey] = round(100 * $value / $expectedResult[$dkey]['hrper'],2);
  else $efc[$dkey] = 0;
}

// if($project_id);

echo "<div class='row hide'>";
echo "<div class='col-md-12'1><h1>MIS</h1></div>";
echo $this->Form->create('Project',array('action'=>'mis/'.$project['Project']['id'],array('class'=>'form')));
echo "<div class='col-md-4'>".$this->Form->input('project_id',array('default'=>$project['Project']['id']))."</div>";
echo "<div class='col-md-4'>".$this->Form->input('dates')."</div>";
// echo "<div class='col-md-3'><br />".$this->Form->input('type',array('type'=>'radio','legend'=>false, 'options'=>array(0=>'day',1=>'week')))."</div>";
echo "<div class='col-md-4'><br />".$this->Form->submit('submit',array('class'=>'btn btn-success btn-sm'))."</div>";
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
    var date = new Date();

    $("#ProjectDates").daterangepicker({
    // singleDatePicker: true,
      showDropdowns: true,
        // startDate: moment().add(-1, 'months'),
      startDate: moment().add(-3, 'months'),
      endDate: moment(date),
      maxDate: new Date(),
      minDate: '<?php echo date("Y-m-d",strtotime($project["Project"]["start_date"]));?>',        
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

<div class="row">
  <div class="col-md-12"><h2><?php echo $project['Project']['title'];?></h2></div>
  <div class="col-md-12">
    <table class="table table-responsive table-bordered">
      <tr>
        <th>Start Date</th><td><?php echo $project['Project']['start_date'];?></td>
        <th>End Date</th><td><?php echo $project['Project']['end_date'];?></td>
        <th>Total Files</th><td><?php echo $totalFiles;?></td>
        <th>Closed Files</th><td><?php echo $closedFiles;?></td>
      </tr>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-md-12"><h4>Project Plan</h4></div>
  <div class="col-md-12">
    <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
      <tr> 
        <th><?php echo __('Seq#'); ?></th>                               
        <th><?php echo __('Process'); ?></th>
        <th><?php echo __('Process Type'); ?></th>
        <th><?php echo __('Est Units'); ?></th>
        <th><?php echo __('Unit Rate'); ?></th>
        <th><?php echo __('Overall Metrics'); ?></th>
        <th><?php echo __('Start'); ?></th>
        <th><?php echo __('End'); ?></th>
        <th><?php echo __('Est Resource'); ?></th>
        <th><?php echo __('Est Manhours'); ?></th>
        <th><?php echo __('Dependancy'); ?></th>  
        <th><?php echo __('Weightage'); ?></th>              
      </tr>
      <?php foreach($projectProcessPlans as $milestone => $projectProcessPlanValues){ ?>
        <tr><th colspan="12"><?php echo $milestones[$milestone];?></th></tr>
        <?php foreach($projectProcessPlanValues as $projectProcessPlan){ ?>
          <tr>                                    
            <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['sequence']); ?>&nbsp;</td>                                    
            <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['process']); ?>&nbsp;</td>
            <td  style="min-width: 120px; padding-left: 20px" class="nre"><?php 
            $qc  = array(0=>'General',1=>'QC',2=>'Merging');
            echo h($qc[$projectProcessPlan['ProjectProcessPlan']['qc']]); ?>&nbsp;
          </td>                
          <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_units']); ?>&nbsp;</td>
          <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['unit_rate']); ?>&nbsp;</td>
          <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['overall_metrics']); ?>&nbsp;</td>
          <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['start_date']); ?>&nbsp;</td>
          <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['end_date']); ?>&nbsp;</td>
          <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_resource']); ?>&nbsp;</td>
          <td>
            <?php 
            if($projectOverallPlan['ProjectOverallPlan']['cal_type'] == 0){
              if($projectProcessPlan['ProjectProcessPlan']['overall_metrics'] && $projectProcessPlan['ProjectProcessPlan']['estimated_units'])echo h(round($projectProcessPlan['ProjectProcessPlan']['estimated_units'] / $projectProcessPlan['ProjectProcessPlan']['overall_metrics']));
              else echo 0;

              if($projectProcessPlan['ProjectProcessPlan']['overall_metrics'] && $projectProcessPlan['ProjectProcessPlan']['estimated_units'])$emr_total = $emr_total + ($projectProcessPlan['ProjectProcessPlan']['estimated_units'] / $projectProcessPlan['ProjectProcessPlan']['overall_metrics']);
              else $emr_total = $emr_total + 0;
            }else{
              echo h(round($projectProcessPlan['ProjectProcessPlan']['estimated_units'] * $projectProcessPlan['ProjectProcessPlan']['overall_metrics']));
              $emr_total = $emr_total + ($projectProcessPlan['ProjectProcessPlan']['estimated_units'] * $projectProcessPlan['ProjectProcessPlan']['overall_metrics']);
            }?>&nbsp;</td>
            <td><?php echo $existingprocesses[$projectProcessPlan['ProjectProcessPlan']['dependancy_id']];?></td> 
            <td><?php echo h($projectProcessPlan['ProjectProcessPlan']['weightage']); ?>%&nbsp;</td>                                  
          </tr>
        <?php } ?>
      <?php } ?>
    </table>
  </div>
</div>

<div class="row">
 <div class="col-md-12 draggable">
  <div id="projectprogressdiv">
    <ul class="list-group" id="projectprogressul">
      <li class="list-group-item"><h4 class="text-center">Overall Project Progress (%) <br /><small>From units completed where status = closed</small></h4></li>
      <li class="list-group-item" style="float: left; overflow: scroll; display: block; width: 100%;">
        <!-- <div style="float: left; overflow: scroll; display: block; width: 100%;"> -->
          <?php if($expectedResult){ ?>
            <canvas height="440" width="<?php echo count($expectedResult) * 80;?>" id="projectprogress" style="float: left;"></canvas>
          <?php }else{ ?>
            <canvas height="440" width="100%" id="projectprogress" style="float: left;"></canvas>
          <?php } ?>

          <!-- </div> -->

        </li>
        <div style="overflow: scroll; float: left; width:100%">
          <table class="table table-responsive table-bordered table-condenced tablesorter" id="projectprogresstable">
            <thead>
              <tr>
                <th>Date</th>
                <?php 
                foreach($expectedResult as $exp){
                  $proplanned[] = $exp['planned_per'];
                  $hrplanned[] = $exp['planned_manhours'];
                }


                $total = 0;
                foreach ($unitsCompletedResult as $key => $value) { ?>
                  <td><small><?php echo $key;?></small></td>
                <?php } ?>
                <th>Total</th>
              </tr>
            </thead>
            <tr>
              <td>Planned.</td>
              <?php foreach ($proplanned as $key => $value) { ?>
                <td><?php echo $value;?>%</td>
                <?php 
                $total = $total + $value;
              } ?>
              <th><?php echo $value;?>%</th>
            </tr>
            <tr>
              <td>Units Completed.</td>
              <?php foreach ($unitsCompletedResult as $key => $value) { ?>
                <td><?php echo $value;?>%</td>
                <?php 
                $total = $total + $value;
              } ?>
              <th><?php echo $value;?>%</th>
            </tr>
          </table>
        </div>
        <script type="text/javascript">
          $("#projectprogresstable").tableExport(
          {
            headers: true,
            footers: true,
            formats: ["csv"],
            filename: "Overall Project Progress (%)",
            bootstrap: true,
            exportButtons: true,
            position: "bottom",
            ignoreRows: null,
            ignoreCols: 7,
            trimWhitespace: true,
            RTL: false,
            sheetname: "id"
          }
          );
        </script>             

      </ul>
    </div>    
    <?php if($unitsCompletedResult && $proplanned){ ?>
      <script>
        var processconfig = {
          type: 'line',
          data: {
            datasets: [
            {     
              type: 'line',                   
              backgroundColor:"#2ea426",
              borderColor:"#2ea426",
              label: 'Overall Project Completion (%)',
              fill: false,
              data: <?php echo json_encode(array_values($unitsCompletedResult),JSON_NUMERIC_CHECK);?>,
              datalabels: {
                color:'#2ea426',
              }                      
            },
            {     
              type: 'line',                   
              backgroundColor:"#666666",
              borderColor:"#666666",
              borderDash:[5],
              label: 'Overall Project Completion Planned (%)',
              fill: false,
              data: <?php echo json_encode($proplanned,JSON_NUMERIC_CHECK);?>,
              datalabels: {
                color:'#666666',
              }                      
            } 
            ],             
            labels: <?php echo json_encode(array_keys($unitsCompletedResult));?>,
          },
          options: {
            layout: {
              padding: 45
            },
            scales: {
              y: {
                ticks: {
                            // Include a dollar sign in the ticks
                  callback: function(value, index, ticks) {
                    return value + '%';
                                // return '$' + Chart.Ticks.formatters.numeric.apply(this, [value, index, ticks]);
                  }
                }
              }
            },
            plugins: {
              legend: {
                display: true,
                position: 'bottom',                  
              },
              datalabels: {
                anchor: 'center',
                align: 'top',
                padding:10,
                formatter: function(value, context) {
                  return value + '%';
                }
              }

            },
            responsive: false,                       
          }
        };
        var proresourcecostctx = document.getElementById('projectprogress').getContext('2d');
        window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
      </script>
    <?php } ?>
  </div>
</div>


<div class="row">
 <div class="col-md-12 draggable">
  <div id="projectprogressdivhrs">
    <ul class="list-group" id="projectprogresshrsul">
      <li class="list-group-item"><h4 class="text-center">Overall Hours</h4></li>
      <li class="list-group-item" style="float: left; overflow: scroll; display: block; width: 100%;">
        <?php if($expectedResult){ ?>
          <canvas height="440" width="<?php echo count($expectedResult) * 80;?>" id="projectprogresshrs"></canvas>
        <?php }else{ ?>
          <canvas height="440" width="100%" id="projectprogresshrs"></canvas>
        <?php } ?>

      </li>            
      <div style="overflow: scroll;">
        <table class="table table-responsive table-bordered table-condenced tablesorter" id="projectprogresshrstable">
          <thead>
            <tr>
              <th>Date</th>
              <?php 
              $total = 0;
              foreach ($hoursCompletedResult as $key => $value) { ?>
                <td><small><?php echo $key;?></small></td>
              <?php } ?>
              <th>Total</th>
            </tr>
          </thead>
          <tr>
            <td>Hours</td>
            <?php foreach ($hoursCompletedResult as $key => $value) { ?>
              <td><?php echo $value;?></td>
              <?php 
              if($value)$total = $total + $value;
            } ?>
            <th><?php echo $total;?></th>
          </tr>
        </table>
      </div>
      <script type="text/javascript">
        $("#projectprogresshrstable").tableExport(
        {
          headers: true,
          footers: true,
          formats: ["csv"],
          filename: "Overall Hours",
          bootstrap: true,
          exportButtons: true,
          position: "bottom",
          ignoreRows: null,
          ignoreCols: 7,
          trimWhitespace: true,
          RTL: false,
          sheetname: "id"
        }
        );
      </script>             

    </ul>
  </div>
  <?php if($hoursCompletedResult && $hrplanned){ ?>
    <script>
      var processconfig = {
        type: 'line',
        data: {
          datasets: [
          {     
            type: 'line',                   
            backgroundColor:"#2ea426",
            borderColor:"#2ea426",
            label: 'Weekly Hours',
            fill: false,
            data: <?php echo json_encode(array_values($hoursCompletedResult),JSON_NUMERIC_CHECK);?>, 
            datalabels: {
              color:'#2ea426',
            }            
          },
          {     
            type: 'line',                   
            backgroundColor:"#666666",
            borderColor:"#666666",
            borderDash:[5],
            label: 'Overall Project Completion Planned (%)',
            fill: false,
            data: <?php echo json_encode($hrplanned,JSON_NUMERIC_CHECK);?>,
            datalabels: {
              color:'#666666',
            }                      
          }  
          ],             
          labels: <?php echo json_encode(array_keys($hoursCompletedResult));?>,
        },
        options: {
          responsive: true,
          layout: {
            padding: 45
          },                
          scales: {

          },
          plugins: {
            legend: {
              display: true,
              position: 'bottom',                  
            },
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,
            }
          },
          responsive: false,
        }
      };
      var proresourcecostctx = document.getElementById('projectprogresshrs').getContext('2d');
      window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
    </script>
  <?php } ?>
</div>
</div>


<div class="row">
 <div class="col-md-12 draggable">
  <div id="planexpectedhrs">
    <ul class="list-group" id="planexpectedul">
      <li class="list-group-item"><h4 class="text-center">Plan Vs Expected (Resources)</h4></li>
      <li class="list-group-item"><canvas height="40" width="100%" id="planexpected"></canvas></li>            
      <div style="overflow: scroll;">
        <table class="table table-responsive table-bordered table-condenced tablesorter" id="planexpectedtablr">
          <thead>
            <tr>
              <th>Date</th>
              <?php 
              $total = 0;
              foreach ($expectedResult as $key => $values) { ?>
                <td><small><?php echo $key;?></small></td>
              <?php } ?>
              <th>Total</th>
            </tr>
            <tr>
              <th>Expected Resources</th>
              <?php foreach ($expectedResult as $key => $values) { ?>                        
                <td><?php echo $values['resources'];?></td>                        
                <?php 
                $resources[$key] = $values['resources'];
                $total1 = $total1 + $values['resources'];
              } ?>
              <th><?php echo $total1;?></th>
            </tr>
            <tr>
              <th>Actual Resource</th>
              <?php foreach ($expectedResult as $key => $values) { ?>                        
                <td><?php echo $values['actual_resources'];?></td>                        
                <?php 
                $actual_resources[$key] = $values['actual_resources'];
                $total2 = $total2 + $values['actual_resources'];
              } ?>
              <th><?php echo $total2;?></th>
            </tr>
          </thead>                    
        </table>
      </div>
      <script type="text/javascript">
        $("#planexpectedtablr").tableExport(
        {
          headers: true,
          footers: true,
          formats: ["csv"],
          filename: "Plan Vs Expected (Resources)",
          bootstrap: true,
          exportButtons: true,
          position: "bottom",
          ignoreRows: null,
          ignoreCols: 7,
          trimWhitespace: true,
          RTL: false,
          sheetname: "id"
        }
        );
      </script>             

    </ul>
  </div>
  <?php if($resources && $actual_resources){ ?>
    <script>
      var processconfig = {
        type: 'line',
        data: {
          datasets: [
          {     
            <?php $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);?>  
            type: 'line',                   
            backgroundColor:"#999999",
            borderColor:"#999999",
            label: 'Expected Resources',
            fill: false,
            data: <?php echo json_encode(array_values($resources),JSON_NUMERIC_CHECK);?>,                      
            borderDash: [5, 5],
          },
          {     
            <?php $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);?>  
            type: 'line',                   
            backgroundColor:"#2ea426",
            borderColor:"#2ea426",
            label: 'Actual Resources',
            fill: false,
            data: <?php echo json_encode(array_values($actual_resources),JSON_NUMERIC_CHECK);?>,
            datalabels: {
              color:'#2ea426',
            }                        
          }  
          ],             
          labels: <?php echo json_encode(array_keys($hoursCompletedResult));?>,
        },
        options: {
          responsive: true,
          layout: {
            padding: 45
          },                
          scales: {

          },
          plugins: {
            legend: {
              display: true,
              position: 'bottom',                  
            },
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,
            }
          },
        }
      };
      var proresourcecostctx = document.getElementById('planexpected').getContext('2d');
      window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
    </script>
  <?php } ?>
</div>
</div> 

<div class="row">
 <div class="col-md-12 draggable">
  <div id="planexpectedhrs1">
    <ul class="list-group" id="planexpectedul">
      <li class="list-group-item"><h4 class="text-center">Plan Vs Expected (Manhours)</h4></li>
      <li class="list-group-item"><canvas height="40" width="100%" id="planexpected1"></canvas></li>  
      <div style="overflow: scroll;">          
        <table class="table table-responsive table-bordered table-condenced tablesorter" id="planexpectedtablr1">
          <thead>
            <tr>
              <th>Date</th>
              <?php 
              $total = 0;
              foreach ($expectedResult as $key => $values) { ?>
                <td><small><?php echo $key;?></small></td>
              <?php } ?>
              <th>Total</th>
            </tr>
            <tr>
              <th>Expected Manhours</th>
              <?php foreach ($expectedResult as $key => $values) { ?>                        
                <td><?php echo $values['manhours'];?></td>                        
                <?php 
                $manhours[$key] = $values['manhours'];
                $total1 = $total1 + $values['manhours'];
              } ?>
              <th><?php echo $total1;?></th>
            </tr>
            <tr>
              <th>Actual Manhours</th>
              <?php foreach ($expectedResult as $key => $values) { ?>                        
                <td><?php echo $values['actual'];?></td>                        
                <?php 
                $actual[$key] = $values['actual'];
                $total2 = $total2 + $values['actual'];
              } ?>
              <th><?php echo $total2;?></th>
            </tr>                    
          </thead>                    
        </table>
      </div>
      <script type="text/javascript">
        $("#planexpectedtablr1").tableExport(
        {
          headers: true,
          footers: true,
          formats: ["csv"],
          filename: "Plan Vs Expected (Manhours)",
          bootstrap: true,
          exportButtons: true,
          position: "bottom",
          ignoreRows: null,
          ignoreCols: 7,
          trimWhitespace: true,
          RTL: false,
          sheetname: "id"
        }
        );
      </script>             

    </ul>
  </div>
  <?php if($manhours && $actual && $hoursCompletedResult){ ?>
    <script>
      var processconfig = {
        type: 'line',
        data: {
          datasets: [
          {    
            <?php $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);?>   
            type: 'line',                   
            backgroundColor:"#999999",
            borderColor:"#999999",
            label: 'Expected Manhours',
            fill: false,
            data: <?php echo json_encode(array_values($manhours),JSON_NUMERIC_CHECK);?>,  
            borderDash: [5, 5],            
          } ,
          {     
            <?php $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);?>  
            type: 'line',                   
            backgroundColor:"#2ea426",
            borderColor:"#2ea426",
            label: 'Actual Manours',
            fill: false,
            data: <?php echo json_encode(array_values($actual),JSON_NUMERIC_CHECK);?>,                      
            datalabels: {
              color:'#2ea426',
            }
          },                
          ],             
          labels: <?php echo json_encode(array_keys($hoursCompletedResult));?>,
        },
        options: {
          layout: {
            padding: 45
          },                
          responsive: true,

          scales: {

          },
          plugins: {
            legend: {
              display: true,
              position: 'bottom',                  
            },
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,                     
            }
          },
        }
      };
      var proresourcecostctx = document.getElementById('planexpected1').getContext('2d');
      window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
    </script>
  <?php } ?>
</div>
</div> 


<div class="row">
 <div class="col-md-12 draggable">
  <div style="overflow: scroll;" id="properdiv">
    <ul class="list-group" id="properul">
      <li class="list-group-item"><h4 class="text-center">Expected Manhours VS Actual (%)</h4></li>
      <li class="list-group-item"><canvas height="40" width="100%" id="proper"></canvas></li> 
      <div style="overflow: scroll;">           
        <table class="table table-responsive table-bordered table-condenced tablesorter" id="propertable">
          <thead>
            <tr>
              <th>Date</th>
              <?php 
              $total = 0;
              foreach ($expectedResult as $key => $value) { ?>
                <td><small><?php echo $key;?></small></td>
              <?php } ?>
              <th>Total</th>
            </tr>
          </thead>
          <tr>
            <td>Planned</td>
            <?php foreach ($expectedResult as $key => $value) { ?>
              <td><?php echo $value['resper'];?></td>
              <?php 
              $per['resper'][$key] = $value['resper'];
              $total1 = $total + $value['resper'];
            } ?>
            <th></th>
          </tr>
          <tr>
            <td>Actual</td>
            <?php foreach ($expectedResult as $key => $value) { ?>
              <td><?php echo $value['hrper'];?>%</td>
              <?php 
              $per['hrper'][$key] = $value['hrper'];
              $total2 = $total + $value['hrper'];
            } ?>
            <th><?php if($total1) echo round($total2 * 100 / $total1);?>%</th>
          </tr>
        </table>
      </div>
      <script type="text/javascript">
        $("#propertable").tableExport(
        {
          headers: true,
          footers: true,
          formats: ["csv"],
          filename: "Expected Manhours VS Actual (%)",
          bootstrap: true,
          exportButtons: true,
          position: "bottom",
          ignoreRows: null,
          ignoreCols: 7,
          trimWhitespace: true,
          RTL: false,
          sheetname: "id"
        }
        );
      </script>             

    </ul>
  </div>
  <?php if($per['resper'] && $per['hrper']){ ?>
    <script>
      var processconfig = {
        type: 'line',
        data: {
          datasets: [
          {     
            <?php $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);?>  
            type: 'line',                   
            backgroundColor:"#999999",
            borderColor:"#999999",
            label: 'Planned Resources',
            fill: false,
            data: <?php echo json_encode(array_values($per['resper']),JSON_NUMERIC_CHECK);?>,
            borderDash: [5, 5],
          } ,
          {  
            <?php $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);?>     
            type: 'line',                   
            backgroundColor:"#2ea426",
            borderColor:"#2ea426",
            label: 'Actual Resources',
            fill: false,
            data: <?php echo json_encode(array_values($per['hrper']),JSON_NUMERIC_CHECK);?>,                    
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,
              color:'#2ea426',
              formatter: function(value, context) {
                return parseInt(Math.round(value)) + '%';
              }
            }           
          } 
          ],             
          labels: <?php echo json_encode(array_keys($hoursCompletedResult));?>,
        },
        options: {
          layout: {
            padding: 45
          },                
          responsive: true,

          scales: {

          },
          plugins: {
            legend: {
              display: true,
              position: 'bottom',                  
            },
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,
              formatter: function(value, context) {
                          // return parseInt(Math.round(value)) + '%';
              }
            }
          },
        }
      };
      var proresourcecostctx = document.getElementById('proper').getContext('2d');
      window.proresourcecostgraph = new Chart(proresourcecostctx, processconfig);
    </script>
  <?php } ?>
</div>
</div>

<div class="row">
 <div class="col-md-12 draggable">
  <div style="overflow: scroll;" id="ofcdiv">
    <ul class="list-group" id="ofcul">
      <li class="list-group-item"><h4 class="text-center">Overall Efficiency (%)</h4></li>
      <li class="list-group-item"><canvas height="40" width="100%" id="ofc"></canvas></li> 
      <div style="overflow: scroll;">           
        <table class="table table-responsive table-bordered table-condenced tablesorter" id="oefc">
          <thead>
            <tr>
              <th>Date</th>
              <?php 
              $total = 0;
              foreach ($efc as $key => $value) { ?>
                <td><small><?php echo $key;?></small></td>
              <?php } ?>
              <!-- <th>Total</th> -->
            </tr>
          </thead>
          <tr>
            <td>Actual</td>
            <?php foreach ($efc as $key => $value) { ?>
              <td><?php echo $value;?>%</td>
              <?php 
              $per['hrper'][$key] = $value;
              $total2 = $total + $value;
            } ?>              
          </tr>
        </table>
      </div>
      <script type="text/javascript">
        $("#ofctable").tableExport(
        {
          headers: true,
          footers: true,
          formats: ["csv"],
          filename: "Expected Manhours VS Actual (%)",
          bootstrap: true,
          exportButtons: true,
          position: "bottom",
          ignoreRows: null,
          ignoreCols: 7,
          trimWhitespace: true,
          RTL: false,
          sheetname: "id"
        }
        );
      </script>             

    </ul>
  </div>
  <?php if($efc && $hoursCompletedResult){ ?>
    <script>
      var ofcconfig = {
        type: 'line',
        data: {
          datasets: [
              // {     
              //   <?php $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);?>  
              //     type: 'line',                   
              //     backgroundColor:"#999999",
              //     borderColor:"#999999",
              //     label: 'Planned Resources',
              //     fill: false,
              //     data: <?php echo json_encode(array_values($per['resper']),JSON_NUMERIC_CHECK);?>,
              //     borderDash: [5, 5],
              // } ,
          {  
            <?php $color = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);?>     
            type: 'line',                   
            backgroundColor:"#2ea426",
            borderColor:"#2ea426",
            label: 'Actual Resources',
            fill: false,
            data: <?php echo json_encode(array_values($efc),JSON_NUMERIC_CHECK);?>,                    
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,
              color:'#2ea426',
              formatter: function(value, context) {
                return value + '%';
              }
            }           
          } 
          ],             
          labels: <?php echo json_encode(array_keys($hoursCompletedResult));?>,
        },
        options: {
          layout: {
            padding: 45
          },                
          responsive: true,

          scales: {

          },
          plugins: {
            legend: {
              display: true,
              position: 'bottom',                  
            },
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,
              formatter: function(value, context) {
                      // return parseInt(Math.round(value)) + '%';
              }
            }
          },
        }
      };
      var oefcctx = document.getElementById('ofc').getContext('2d');
      window.oefcgraph = new Chart(oefcctx, ofcconfig);
    </script>
  <?php } ?>
</div>
</div>

<div class="row">
 <div class="col-md-12 draggable">
  <div style="overflow: scroll;" id="errordiv">
    <ul class="list-group" id="errorul">
      <li class="list-group-item"><h4 class="text-center">Errors</h4></li>
      <li class="list-group-item"><canvas height="40" width="100%" id="error"></canvas></li>
    </ul>
  </div>
  <?php foreach($errResult as $a => $b){
    $total = $total + $b;
    $colors[] = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
  }?>
  <?php foreach($errResult as $a => $b){
    if($total && $b)$pr[] = round(100 - ( $b * 100 / $total));
    else $pr[] = 0;
  }?>
  <?php if($errResult && $pr){ ?>
    <script>
      var errorconfig = {          
        data: {
          datasets: [
          {   
            type: 'bar',                   
            backgroundColor:<?php echo json_encode($colors);?>,
            borderColor:<?php echo json_encode($colors);?>,
            yAxisID: 'y',
            fill: false,
            data: <?php echo json_encode(array_values($errResult),JSON_NUMERIC_CHECK);?>,                    
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,                            
            }           
          },
          {   
            type: 'line',
            tension: 0.1,
            backgroundColor:"#666666",
            borderColor:"#666666",  
            yAxisID: 'y1',
            data: <?php echo json_encode(array_values($pr),JSON_NUMERIC_CHECK);?>,                    
            datalabels: {
              anchor: 'center',
              align: 'top',
              padding:10,
              formatter: function(value, context) {
                return parseInt(Math.round(value)) + '%';
              }            
            },                  
          }              
          ],             
          labels: <?php echo json_encode(array_keys($errResult));?>,
        },
        options: {
          scales: {
            y: 
            {
              ticks: {
                min: 0,
                    // max: 100,
              },
                  type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                  display: true,
                  position: 'left',
                  datalabels: {
                    anchor: 'center',
                    align: 'top',
                    padding:10,                            
                  } 
                },
                y1: 
                {
                  ticks: {
                    min: 0,
                    max: 100,
                  },
                  type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                  display: false,                  
                },
              },
              layout: {
                padding: 45
              },                
              responsive: true,          
              plugins: {
                legend: {
                  display: false,                  
                },
                datalabels: {
                  anchor: 'center',
                  align: 'top',
                  padding:10,                    
                }
              },
            }
          };
          var errorcctx = document.getElementById('error').getContext('2d');
          window.errorgraph = new Chart(errorcctx, errorconfig);
        </script>
      <?php } ?>
    </div>
  </div>

  <div class="row">
   <div class="col-md-6 draggable">
    <div style="overflow: scroll;" id="processhourshdiv">
      <ul class="list-group" id="processhoursul">
        <li class="list-group-item"><h4 class="text-center">Processwise Analysis (Hours)</h4></li>
        <li class="list-group-item"><canvas height="140" width="100%" id="process_wise_hours"></canvas></li>
      </ul>
    </div>
    <?php 
    $colors = array();
    foreach($processwise as $pros){
  // $total = $total + $b;
  // $colors[] = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
      $colors[] = "#2ea426";
      $colors2[] = "#00266d";
    }?>
    <?php foreach($processwise as $pros){
      $estimatedhours[$existingprocesses[$pros['FileProcess']['project_process_plan_id']]] = $pros['FileProcess']['total_hours'];

      if($pros['ProjectProcessPlan']['overall_metrics'] && $pros['FileProcess']['total_completed_files']){
        $actualhour[$existingprocesses[$pros['FileProcess']['project_process_plan_id']]] = round($pros['FileProcess']['total_completed_files']/$pros['ProjectProcessPlan']['overall_metrics']);   
      }else{
        $actualhour[$existingprocesses[$pros['FileProcess']['project_process_plan_id']]] = 0;
      }

    }?>
    <?php if($estimatedhours && $actualhour){ ?>
      <script>
        var processhoursconfig = {

          data: {           
            datasets: [
            {   
              type: 'bar',                   
              backgroundColor:<?php echo json_encode($colors2);?>,
              borderColor:<?php echo json_encode($colors2);?>,
              yAxisID: 'y',
              fill: false,
              label: 'Planned',
              data: <?php echo json_encode(array_values($estimatedhours),JSON_NUMERIC_CHECK);?>,                    
              datalabels: {
                anchor: 'right',
                align: 'right',
                padding:10,                            
              }              
            } , 
            {   
              type: 'bar',                   
              backgroundColor:<?php echo json_encode($colors);?>,
              borderColor:<?php echo json_encode($colors);?>,
              yAxisID: 'y',
              fill: false,
              label: 'Actual',
              data: <?php echo json_encode(array_values($actualhour),JSON_NUMERIC_CHECK);?>,                    
              datalabels: {
                anchor: 'right',
                align: 'right',
                padding:10,                            
              }           
            }
            ],             
            labels: <?php echo json_encode(array_keys($actualhour));?>,
          },
          options: {
            indexAxis: 'y',
            // scales: {
            //   y: 
            //     {
            //       ticks: {
            //         min: 0,
            //         // max: 100,
            //       },
            //       type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
            //       display: true,
            //       position: 'left',
            //       datalabels: {
            //         anchor: 'center',
            //         align: 'top',
            //         padding:10,                            
            //       } 
            //   },
            //   y1: 
            //     {
            //       ticks: {
            //         min: 0,
            //         max: 100,
            //       },
            //       type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
            //       display: false,                  
            //   },
            // },
            layout: {
              padding: 45
            },                
            responsive: true,          
            plugins: {
              legend: {
                  // display: false,                  
              },
              datalabels: {
                anchor: 'right',
                align: 'right',
                padding:10,                    
              }
            },
          }
        };
        var prosscctx = document.getElementById('process_wise_hours').getContext('2d');
        window.errorgraph = new Chart(prosscctx, processhoursconfig);
      </script>
    <?php } ?>      
  </div>


  <!-- Units -->

  <div class="col-md-6 draggable">
    <div style="overflow: scroll;" id="processunitsdiv">
      <ul class="list-group" id="processunitsul">
        <li class="list-group-item"><h4 class="text-center">Processwise Analysis (Units)</h4></li>
        <li class="list-group-item"><canvas height="140" width="100%" id="process_wise_units"></canvas></li>
      </ul>
    </div>
    <?php foreach($processwise as $pros){
  // $total = $total + $b;
  // $colors[] = "#" . str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
      $colors[] = "#2ea426";
      $colors2[] = "#00266d";
    }?>
    <?php foreach($processwise as $pros){
      $plannedUnits[$existingprocesses[$pros['FileProcess']['project_process_plan_id']]] = $pros['ProjectProcessPlan']['overall_metrics'] * $pros['FileProcess']['total_hours'];

      if($pros['ProjectProcessPlan']['overall_metrics'] && $pros['FileProcess']['total_completed_files']){
        $actualhour[$existingprocesses[$pros['FileProcess']['project_process_plan_id']]] = round($pros['FileProcess']['total_completed_files']/$pros['ProjectProcessPlan']['overall_metrics']);  
      }else{
        $actualhour[$existingprocesses[$pros['FileProcess']['project_process_plan_id']]] = 0;
      }      
    }?>
    <?php if($plannedUnits && $actualUnits){ ?>
      <script>
        var processunitsconfig = {          
          data: {
            datasets: [
            {   
              type: 'bar',                   
              backgroundColor:<?php echo json_encode($colors2);?>,
              borderColor:<?php echo json_encode($colors2);?>,
              yAxisID: 'y',
              fill: false,
              label: 'Planned',
              data: <?php echo json_encode(array_values($plannedUnits),JSON_NUMERIC_CHECK);?>,                    
              datalabels: {
                anchor: 'right',
                align: 'right',
                padding:10,                            
              }              
            } , 
            {   
              type: 'bar',                   
              backgroundColor:<?php echo json_encode($colors);?>,
              borderColor:<?php echo json_encode($colors);?>,
              yAxisID: 'y',
              fill: false,
              label: 'Actual',
              data: <?php echo json_encode(array_values($actualUnits),JSON_NUMERIC_CHECK);?>,                    
              datalabels: {
                anchor: 'right',
                align: 'right',
                padding:10,                            
              }           
            }            
            ],             
            labels: <?php echo json_encode(array_keys($plannedUnits));?>,
          },
          options: {
            indexAxis: 'y',
            // scales: {
            //   y: 
            //     {
            //       ticks: {
            //         min: 0,
            //         // max: 100,
            //       },
            //       type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
            //       display: true,
            //       position: 'left',
            //       datalabels: {
            //         anchor: 'center',
            //         align: 'top',
            //         padding:10,                            
            //       } 
            //   },
            //   y1: 
            //     {
            //       ticks: {
            //         min: 0,
            //         max: 100,
            //       },
            //       type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
            //       display: false,                  
            //   },
            // },
            layout: {
              padding: 45
            },                
            responsive: true,
            plugins: {
              legend: {
                  // display: false,                  
              },
              datalabels: {
                anchor: 'right',
                align: 'right',
                padding:10,                    
              }
            },
          }
        };
        var prossunitscctx = document.getElementById('process_wise_units').getContext('2d');
        window.errorgraph = new Chart(prossunitscctx, processunitsconfig);
      </script>      
    <?php } ?>
  </div>

</div>
<div class="row">
  <div class="col-md-12">
    <?php 
    // Configure::write('debug',1);
    // debug($processwise);
    ?>
  </div>
</div>
</div>
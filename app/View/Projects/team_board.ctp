<!-- <input type="text" id="meminputpro<?php echo $this->request->params['pass'][0]?>" name="meminputpro" class="form-control" placeholder="Type name to search"><br /> -->
<?php 
  
  $tempMid = $this->request->params['pass'][1];
?>

<script type="text/javascript">
  $(document).ready(function(){
  $("#teaminput<?php echo $tempMid;?>").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#teamtable<?php echo $tempMid;?> tbody tr").filter(function() {
      var classN = this.id;
      $(this).toggle($("#"+classN).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<input type="text" id="teaminput<?php echo $tempMid;?>" name="teaminput<?php echo $tempMid;?>" class="form-control" placeholder="Type name to search"><br />

  <table class="table table-responsive table-condensed table-bordered table-hover " id="teamtable<?php echo $tempMid;?>">
    <thead>
    <tr>
      <th>Member</th>
      <th>Department</th>
      <th>Designation</th>
      <th>Skill Set</th>
      <th>Prioritywise</th>
      <th>Processe</th>                
      <th>TL</th>
      <th>PL</th>
      <th>Action</th>                                        
    </tr>
    </thead>
    <?php 
    // Configure::write('debug',1);
    // debug($projectResources); 
    ?>
    <tbody>
    <?php foreach ($projectResources as $projectResource) {  ?>
      <?php if($projectResource['Processes']){ ?>
          <tr id="<?php echo $projectResource['Employee']['sr_no'];?>">
            <td rowspan="<?php echo count($projectResource['Processes'])+1?>">
              <?php echo $this->Html->link($PublishedEmployeeList[$projectResource['Employee']['id']],array('controller'=>'employees','action'=>'view',$projectResource['Employee']['id']),array('target'=>'_blank'));?>                        
              ( <?php echo $projectResource['Employee']['employee_number'];?> )
              <span class="badge"><?php echo $projectResource['Employee']['files_assigned'];?></span>
               &nbsp;</td>
            
            <td rowspan="<?php echo count($projectResource['Processes'])+1?>"><?php echo $PublishedDepartmentList[$projectResource['Employee']['department_id']];?> &nbsp;</td>
            <td rowspan="<?php echo count($projectResource['Processes'])+1?>"><?php echo $PublishedDesignationList[$projectResource['Employee']['designation_id']];?> &nbsp;</td>
            <td rowspan="<?php echo count($projectResource['Processes'])+1?>">--  &nbsp;</td>
          </tr>
        <?php 
        $x = 1;
        foreach ($projectResource['Processes'] as $pro) { ?>
          <tr id="<?php echo $projectResource['Employee']['sr_no'];?>">
                <td><?php echo $x;?> &nbsp;</td>
                <td><?php echo $pro['ProjectProcessPlan']['process']; ?>  &nbsp;</td>
                <td><?php echo $PublishedEmployeeList[$pro['ProjectResource']['team_leader_id']] ?>  &nbsp;</td>
                <td><?php echo $PublishedEmployeeList[$pro['ProjectResource']['project_leader_id']] ?>  &nbsp;</td>    
                <td>
                  <div class="btn-group" id="<?php echo $pro['ProjectResource']['id']?>"><?php 
                    // debug($milestone);
                      echo $this->Html->link("View","javascript:void(0);",
                      array(
                        'class'=>'btn btn-xs btn-info',
                        'onclick'=>'openmodel(
                          "employees",
                          "employee_files",
                          "'.$projectResource['Employee']['id'].'",
                          "'.$milestone['Milestone']['project_id'].'",
                          "'.$milestone['Milestone']['id'].'",                          
                          null,
                          null,
                        )'
                      )); 
                      // echo $file['ProjectFile']['current_status'];
                      
                    ?>
                    <?php
                    echo $this->Js->link('delete',array('action'=>'deldel',$pro['ProjectResource']['id']),array('class'=>'btn btn-danger btn-xs ','confirm'=>'do you','escape' => false, 'update' => '#'. $pro['ProjectResource']['id'], 'async' => 'false'));
                    echo $this->Js->writeBuffer();
                    ?></div>  &nbsp;
                    </td>         
              </tr>
          <?php $x++;} ?>

        </tr>
        
      <?php }else{ ?>
        <tr>
          
            <td>
              <?php echo $this->Html->link($PublishedEmployeeList[$projectResource['Employee']['id']],array('controller'=>'employees','action'=>'view',$projectResource['Employee']['id']),array('target'=>'_blank'));?>                        
              <span class="badge"><?php echo $projectResource['Employee']['files_assigned'];?></span>  &nbsp;
              </td>
            
            <td><?php echo $PublishedDepartmentList[$projectResource['Employee']['department_id']];?>  &nbsp;</td>
            <td><?php echo $PublishedDesignationList[$projectResource['Employee']['designation_id']];?>  &nbsp;</td>
            <td>-- &nbsp; </td>
            <td>-- &nbsp; </td>
            <td>-- &nbsp; </td>
            <td>-- &nbsp; </td>
            <td>-- &nbsp; </td>
            <td><?php 
                    // debug($milestone);
                      echo $this->Html->link("View","javascript:void(0);",
                      array(
                        'class'=>'btn btn-xs btn-info',
                        'onclick'=>'openmodel(
                          "employees",
                          "employee_files",
                          "'.$projectResource['Employee']['id'].'",
                          "'.$milestone['Milestone']['project_id'].'",
                          "'.$milestone['Milestone']['id'].'",                          
                          null,
                          null,
                        )'
                      )); 
                      // echo $file['ProjectFile']['current_status'];
                      
                    ?> &nbsp; </td>  
        </tr>
      <?php } ?>
    <?php } ?>
   </tbody>     
  </table>

<script type="text/javascript">

$("#teamtable<?php echo $tempMid;?>").tableExport(
        {
          headers: true,                        // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
          footers: true,                      // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
          formats: ["csv"],             // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
          filename: "<?php echo $milestone['Milestone']['title'];?>-Team Board",                     // (id, String), filename for the downloaded file, (default: 'id')
          bootstrap: true,                   // (Boolean), style buttons using bootstrap, (default: true)
          exportButtons: true,                // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
          position: "bottom",                 // (top, bottom), position of the caption element relative to table, (default: 'bottom')
          ignoreRows: null,                   // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
          ignoreCols: 7,                   // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
          trimWhitespace: true,               // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
          RTL: false,                         // (Boolean), set direction of the worksheet to right-to-left (default: false)
          sheetname: "id"                     // (id, String), sheet name for the exported spreadsheet, (default: 'id')
        }
    );
</script> 

<script type="text/javascript">
  // $(document).ready(function(){
  //   $("#meminputpro<?php echo $this->request->params['pass'][0]?>").on("keyup", function() {
  //     var value = $(this).val().toLowerCase();
  //     $("#memtablepro<?php echo $this->request->params['pass'][0]?> tr").filter(function() {
  //       $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  //     });
  //   });
  // });
</script>
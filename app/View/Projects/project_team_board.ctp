
<h3>Project team board</h3>
<table class="table table-responsive table-condensed table-bordered">
  <tr>
    <th>Member</th>
    <th>Department</th>
    <th>Designation</th>
    <th>Skill Set</th>                                      
    <th colspan="2">Processes / <small>Prioritywise</small></th>
    <th>TL</th>
    <th>PL</th>         
    <th>Process change</th>
    <th>Team</th>
    <th>Reserved</th>
    <th>Release</th>
    <th>Lock till</th>
  </tr>
  <?php 
  // Configure::write('debug',1);
  // debug($milestone['ProjectResource']);
  foreach ($recs as $pemployee_id => $prdata) { 
    if($prdata){
  
  ?>
    <tr class="success">
      <td><?php echo $this->Html->link($PublishedEmployeeList[$pemployee_id],array('controller'=>'employees','action'=>'view',$pemployee_id),array('target'=>'_blank'));?></td>
      <td><?php echo $PublishedDepartmentList[$prdata[0]['Employee']['department_id']];?></td>
      <td><?php echo $PublishedDesignationList[$prdata[0]['Employee']['designation_id']];?></td>
      <td>--</td>                      
      <td colspan="2"><ol><?php 
      foreach ($prdata as $pd) {
        echo '<li>'. $pd['ProjectProcessPlan']['process'].'</li>';
      }
      ?></ol></td>
      <td><?php echo $PublishedEmployeeList[$prdata[0]['ProjectResource']['team_leader_id']] ?></td>
      <td><?php echo $PublishedEmployeeList[$prdata[0]['ProjectResource']['project_leader_id']] ?></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td><?php echo $prdata[0]['ProjectResource']['end_date']?></td>                  
    </tr>
  <?php }} ?>
</table>
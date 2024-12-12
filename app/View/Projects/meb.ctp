<h4>Project distribution board</h4>
<table class="table table-responsive table-bordered">
  <tr>
    <th>Project Nane</th>
    <th>Client</th>
    <th>Editors</th>    
    <th>Teamleaders</th>
    <th>Project Leader</th>
    <th>Project Manager</th>
    <th>Total</th>   
    <th>Action</th> 
  </tr>
  <?php foreach ($projects as $project) { ?>
    <tr>
      <td><?php echo $project['Project']['title']?></td>
      <td><?php echo $project['Customer']['name']?></td>
      <td><?php echo $project['Ms']?></td>
      <td><?php echo $project['TLs']?></td>
      <td><?php echo $project['PLs']?></td>
      <td>0</td>
      <td>
        <?php echo $project['Ms'] + $project['TLs'] + $project['PLs'] ?>
      </td>
      <td>
        <?php echo $this->Html->link('View',array('controller'=>'projects','action'=>'meb_details','project_id'=>$project['Project']['id']),array('class'=>'btn btn-xs btn-info','target'=>'_blank'));?>
      </td>
    </tr>  
  <?php } ?>
</table> 


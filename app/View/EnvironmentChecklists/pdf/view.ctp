<h2><?php echo __('View Environment Checklist'); ?></h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Date Created'); ?></td>
		<td><?php echo h($environmentChecklist['EnvironmentChecklist']['date_created']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Branch'); ?></td>
		<td><?php echo $environmentChecklist['Branch']['name']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Department'); ?></td>
		<td><?php echo $environmentChecklist['Department']['name']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Employee'); ?></td>
		<td><?php echo $environmentChecklist['Employee']['name']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Prepared By'); ?></td>
		<td><?php echo h($environmentChecklist['ApprovedBy']['name']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Approved By'); ?></td>
		<td><?php echo h($environmentChecklist['ApprovedBy']['name']); ?>&nbsp;</td>
	</tr>		
</table>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
<?php	   
    foreach ($questions as $key => $question) {
      echo "<tr bgcolor='#FFFFFF'><th colspan='2'><h3>".$question['name']."</h3></th></tr>";
      foreach ($question['questions'] as $q) {
      debug($q);      
        echo "<tr bgcolor='#FFFFFF'>";
        echo "<td>".$q['EnvironmentQuestionnaire']['title']."</td>";
        echo "<td><strong>".($q['EnvironmentChecklistAnswer']['answer']?'Yes':'No')."</td>";
        if($q['EnvironmentChecklistAnswer']['details'])echo "</tr><tr bgcolor='#FFFFFF'><td colspan='2'>".$q['EnvironmentChecklistAnswer']['details'] . '</td>'; 
        echo "</tr>";
        $i++;
      }      
    
    }    
?>
</table>

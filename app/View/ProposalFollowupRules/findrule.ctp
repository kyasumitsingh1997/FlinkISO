<div class="col-md-12">
<h5><?php echo __('Follow Up Rule'); ?></h5>
<?php 
	$missed = 0;
	foreach($followup_detail['FolowupDetails'] as $followups):
		if($followups['FollowupStatus'] == 'Not Done'){$class = 'danger'; $missed = $missed + 1;}
		elseif($followups['FollowupStatus'] == 'Pending')$class = 'default';
		elseif($followups['FollowupStatus'] == 'Today')$class = 'info';
		elseif($followups['FollowupStatus'] == 'Delayed')$class = 'warning';
		else $class = 'success';
		echo "<div class='btn-group'>";
		echo "<div class='btn btn-xs btn-" . $class. " dropdown-toggle', data-toggle='dropdown' aria-expanded='false'><strong> Day ". $followups['FollowupDay'] . "</strong></div>&nbsp;";
		echo "<ul class='dropdown-menu' role='menu'>";
		if(!$followups['FollowupDate'] && !$followups['FollowupStatus'] == 'Pending')
			{
				echo "<li>" . $this->Html->link('Add Follow Up',array('controller'=>'proposal_followups','action'=>'lists',$followup_detail['Proposal']['id']),array()) . "</li>";
				$followups['FollowupDate'] = 'Not Done';
			}
		echo "<li>" . $this->Html->link($followups['FollowupType'] . " " . $followups['FollowupDate'],'#',array('escape'=>false)). "</li>";
		echo "</ul>";
		//echo "<div class='btn btn-xs btn-" . $class. "'>" . $followups['FollowupType'] . "</div>";
		//echo "<div class='btn btn-xs btn-" . $class. "'>" . $followups['FollowupStatus'] ."</div>";
		echo "</div>";				
	endforeach;
	$count_followups_required = count($followup_detail['FolowupDetails']);			
 ?>
 </div>
 <div class="col-md-12"><br /></div>

<div id="main">
    <?php echo $this->Session->flash(); ?>
<?php if($this->request->params['named']['hide_panel'])	{ ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title"><h4>Proposal Followup Status <small>Sent Proposals <?php echo $this->Html->link('BD', array('controller'=>'dashboards','action'=>'bd'));?></small></h4></div>
		</div>
		<div class="panel-body">	
<?php }else{ ?>
	<div class="panel-body">			
		<h4>Proposal Followup Status <small>Sent Proposals <?php echo $this->Html->link('BD Dashboard', array('controller'=>'dashboards','action'=>'bd'),array('class'=>'pull-right'));?></small></h4>
		<hr />
<?php } ?>
	<div class="row">
		<?php if($followup_details){ 
		$final_score = 0;
		$proposal_count = 0;
		?>
			<?php foreach($followup_details as $followup_detail): ?>
			<div class="col-md-11">
					<h5><?php echo $this->Html->link($followup_detail['Proposal']['title'],array('controller'=>'proposals','action'=>'edit',$followup_detail['Proposal']['id'])); ?> <small>Sent on : <?php echo $followup_detail['Proposal']['proposal_sent_date']; ?> /
					To : <?php echo $followup_detail['Customer']['name']; ?> / Assigned To : <?php echo $followup_detail['Employee']['name']; ?></small></h5>
					<?php 
						$missed = 0;
						foreach($followup_detail['FolowupDetails'] as $followups):
							//echo $followups['FollowupStatus'];
							if($followups['FollowupStatus'] == 'Not Done'){$class = 'danger'; $missed = $missed + 1;}
							elseif($followups['FollowupStatus'] == 'Pending')$class = 'default';
							elseif($followups['FollowupStatus'] == 'Today')$class = 'info';
							elseif($followups['FollowupStatus'] == 'Delayed')$class = 'warning';
							else $class = 'success';
							echo "<div class='btn-group'>";
							echo "<div class='btn btn-xs btn-" . $class. " dropdown-toggle', data-toggle='dropdown' aria-expanded='false'><strong> Day ". $followups['FollowupDay'] . "</strong></div>&nbsp;";
							echo "<ul class='dropdown-menu' role='menu'>";
							if(!$followups['FollowupDate'] && !$followups['FollowupStatus'] == 'Pending' or $class=='info')
								{
									echo "<li>" . $this->Html->link('<span class="glyphicon glyphicon-hand-right text-info"></span> Add Follow Up',array('controller'=>'proposal_followups','action'=>'lists','day'=>$followups['FollowupDay'],'followup_type'=>$followups['FollowupType'],$followup_detail['Proposal']['id']),array('escape'=>false)) . "</li>";
									$followups['FollowupDate'] = 'Pending Today';
								}
							if(!$followups['FollowupDate'] && $class != 'default')echo "<li>" . $this->Html->link("<span class='glyphicon glyphicon-remove text-danger'></span> " . $followups['FollowupType'] . " - Missed ". date('Y-m-d',strtotime($followup_detail['Proposal']['proposal_sent_date'].'+'. ($followups['FollowupDay'] - 1 ).' days')),'#',array('escape'=>false)). "</li>";
							elseif(!$followups['FollowupDate'] && $class == 'default')echo "<li>" . $this->Html->link("<span class='glyphicon glyphicon-remove text-warning'></span> " . $followups['FollowupType'] . " - Due on " . date('Y-m-d',strtotime($followup_detail['Proposal']['proposal_sent_date'].'+' .$followups['FollowupDay']. 'days')),'#',array('escape'=>false)). "</li>";
							elseif($followups['FollowupDate'] == 'Pending Today') echo "<li>" . $this->Html->link("<span class='glyphicon glyphicon-ok text-warning'></span> " . $followups['FollowupType'] . " " . $followups['FollowupDate'],'#',array('escape'=>false)). "</li>";
							else echo "<li>" . $this->Html->link("<span class='glyphicon glyphicon-ok text-success'></span> " . $followups['FollowupType'] . " " . $followups['FollowupDate'],'#',array('escape'=>false)). "</li>";
							echo "</ul>";
							//echo "<div class='btn btn-xs btn-" . $class. "'>" . $followups['FollowupType'] . "</div>";
							//echo "<div class='btn btn-xs btn-" . $class. "'>" . $followups['FollowupStatus'] ."</div>";
							echo "</div>";				
						endforeach;
						$count_followups_required = count($followup_detail['FolowupDetails']);			
					 ?>
					 <?php
						$score = 100 - round(100 * $missed / $count_followups_required);
						if($score < 50)$btn_class = 'danger';
						if($score > 50 && $score <= 79)$btn_class = 'warning';
						if($score > 79 && $score <= 100)$btn_class = 'success';
						$final_score = $final_score + $score;
						$proposal_count++;
					 ?>
				</div>
				<div class="col-md-1"><br /><span class="btn btn-<?php echo $btn_class; ?> pull-right"><?php echo $score ?> % </span></div>			
			<?php endforeach; ?>
			<div class="col-md-12"><br /><hr class="no-margin" /></div>
			<div class="col-md-11 text-right"><h3>Win chances</h3></div>
			<div class="col-md-1"><br /><span class="btn btn-<?php echo $btn_class; ?> pull-right"><?php echo round($final_score/$proposal_count) ?> % </span></div>
			<div class="col-md-12"><br /><hr class="no-margin" /></div>
			
	</div>		
		<?php } else { ?>
				<p>No proposals found</p>
		<?php } ?>
<?php if($this->request->params['named']['hide_panel'])	{ ?>	
		</div>
		<div class="panel-footer">
			<small>
				<span class="btn btn-xs btn-info">&nbsp;&nbsp;</span> Today's Followups 
			   <span class="btn btn-xs btn-default">&nbsp;&nbsp;</span> Upcomming's Followups 
			   <span class="btn btn-xs btn-danger">&nbsp;&nbsp;</span> Missied Followups 
			</small>
<?php } ?>	</div>		</div>
	</div>		
</div>

<?php echo $this->Js->writeBuffer(); ?>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
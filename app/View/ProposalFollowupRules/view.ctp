<?php 
		for($i=1;$i<=365;$i = $i+30){
			$options[$i] = round($i / 30) . ' - Months';
		}
		?>


	<div class="panel panel-default">
		<div class="panel-heading"><div class="panel-title"><h4><?php echo $proposalFollowupRule['ProposalFollowupRule']['rule']; ?> <small><?php echo $proposalFollowupRule['ProposalFollowupRule']['notes']; ?></small></h4></div></div>
		<div class="panel-body no-marging no-padding">
		<br/ >
			<div id="draw" class="">
		<?php 
	$followups = json_decode($proposalFollowupRule['ProposalFollowupRule']['followup_sequence'],true);	
	for($n = 1; $n <= $proposalFollowupRule['ProposalFollowupRule']['number_of_followups_required']; $n++){
	$x = false;
	$check = '';
		foreach($followups as $key=>$followup): 
			if($followup == 'Email')$type=0;
			if($followup == 'Call')$type=1;
			if($followup == 'Visit')$type=2;
			if($followup == 'Other')$type=3;
			if($followup == 'Any')$type=4;
			if($key == $n):$x = true;	$check = $followup;endif;

		endforeach;
		if($x == true){	
		
		?>
		<div class="col-md-12">
			<div class='btn btn-group no-padding'>
				<div class='btn btn-xs btn-xs btn-xs btn-success' id= 'days<?php echo $n; ?>' onClick=checks(<?php echo $n; ?>)><?php echo $n; ?></div>
				<?php if($check == 'Email'){ ?>
				<div class='btn btn-xs btn-xs btn-xs btn-success' id='Email_<?php echo $n; ?>_0' onClick=reset_checks('Email',<?php echo $n; ?>)>Email
					<input  style='display:none'  type="checkbox" checked name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Call" id="folloupType_<?php echo $n; ?>_0">
				</div>
				<?php } else { ?>
				<div class='btn btn-xs btn-default disabled' id='Email_<?php echo $n; ?>_0' onClick=reset_checks('Email',<?php echo $n; ?>)>Email
					<input  style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Call" id="folloupType_<?php echo $n; ?>_0">
				</div>
				<?php } ?>
				<?php if($check == 'Call'){ ?>
				<div class='btn btn-xs btn-success' id='Call_<?php echo $n; ?>_1' onClick=reset_checks('Call',<?php echo $n; ?>)>Call
					<input style='display:none'  type="checkbox" checked name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Call" id="folloupType_<?php echo $n; ?>_1">
				</div>
				<?php } else { ?>
				<div class='btn btn-xs btn-default disabled' id='Call_<?php echo $n; ?>_1' onClick=reset_checks('Call',<?php echo $n; ?>)>Call
					<input style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Call" id="folloupType_<?php echo $n; ?>_1">
				</div>
				<?php } ?>
				<?php if($check == 'Visit'){ ?>
				<div class='btn btn-xs btn-success' id='Visit_<?php echo $n; ?>_2' onClick=reset_checks('Visit',<?php echo $n; ?>)>Visit
					<input style='display:none'  type="checkbox" checked name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Visit" id="folloupType_<?php echo $n; ?>_2">
				</div>
				<?php } else { ?>
				<div class='btn btn-xs btn-default disabled' id='Visit_<?php echo $n; ?>_2' onClick=reset_checks('Any',<?php echo $n; ?>)>Visit
					<input style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Any" id="folloupType_<?php echo $n; ?>_2">
				</div>
				<?php } ?>
				<?php if($check == 'Other'){ ?>
				<div class='btn btn-xs btn-success' id='Other_<?php echo $n; ?>_3' onClick=reset_checks('Any',<?php echo $n; ?>)>Other
					<input style='display:none'  type="checkbox" checked name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Other" id="folloupType_<?php echo $n; ?>_3">
				</div>
				<?php } else { ?>
				<div class='btn btn-xs btn-default disabled' id='Other_<?php echo $n; ?>_3' onClick=reset_checks('Any',<?php echo $n; ?>)>Other
					<input style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Other" id="folloupType_<?php echo $n; ?>_3">
				</div>
				<?php } ?>
				<?php if($check == 'Any'){ ?>
				<div class='btn btn-xs btn-success' id='Any_<?php echo $n; ?>_4' onClick=reset_checks('Any',<?php echo $n; ?>)>Any
					<input style='display:none'  type="checkbox" checked name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Any" id="folloupType_<?php echo $n; ?>_4">
				</div>
				<?php } else { ?>
				<div class='btn btn-xs btn-default disabled' id='Any_<?php echo $n; ?>_4' onClick=reset_checks('Any',<?php echo $n; ?>)>Any
					<input style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Any" id="folloupType_<?php echo $n; ?>_4">
				</div>
				<?php } ?>
			</div>
		</div>
		<?php }else { ?>
		<div class="col-md-12 hide">
			<div class='btn btn-group no-padding'>
				<div class='btn btn-xs btn-default' id= 'days<?php echo $n; ?>' onClick=checks(<?php echo $n; ?>)><?php echo $n; ?></div>
				<div class='btn btn-xs btn-default disabled' id='Email_<?php echo $n; ?>_0' onClick=reset_checks('Email',<?php echo $n; ?>)>Email
					<input style='display:none'   type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Email" id="folloupType_<?php echo $n; ?>_0">
				</div>
				<div class='btn btn-xs btn-default disabled' id='Call_<?php echo $n; ?>_1' onClick=reset_checks('Call',<?php echo $n; ?>)>Call
					<input style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Call" id="folloupType_<?php echo $n; ?>_1">
				</div>
				<div class='btn btn-xs btn-default disabled' id='Visit_<?php echo $n; ?>_2' onClick=reset_checks('Visit',<?php echo $n; ?>)>Visit
					<input style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Visit" id="folloupType_<?php echo $n; ?>_2">
				</div>
				<div class='btn btn-xs btn-default disabled' id='Other_<?php echo $n; ?>_3' onClick=reset_checks('Call',<?php echo $n; ?>)>Other
					<input style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Other" id="folloupType_<?php echo $n; ?>_3">
				</div>
				<div class='btn btn-xs btn-default disabled' id='Any_<?php echo $n; ?>_4' onClick=reset_checks('Any',<?php echo $n; ?>)>Any
					<input style='display:none'  type="checkbox" name="data[PorposalFollowupRule][folloupType][<?php echo $n; ?>]" value="Any" id="folloupType_<?php echo $n; ?>_4">
				</div>
			</div>
		</div>
		<?php } ?>
		<?php
		
}
?>
<div class="col-md-12"><p><br />Contact administrator to add new rules.</p></div>
</div>		
		</div>
	</div>

	

</div>
</div>
</div>
<script>
    $.ajaxSetup({
        beforeSend: function () {
            $("#busy-indicator").show();
        },
        complete: function () {
            $("#busy-indicator").hide();
        }
    });
</script>
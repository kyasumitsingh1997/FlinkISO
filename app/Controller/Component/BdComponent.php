<?php

//
//File: application/Controller/Component/CtrlComponent.php
// Component rewritten, original from : http://cakebaker.42dh.com/2006/07/21/how-to-list-all-controllers/
//

class BdComponent extends Component {
	public function proposalFollowupStatus($startDate = null, $endDate = null, $duration = null){            	
	if($duration == true){
		$date_conditions = array();
	}else{
		$date_conditions = array('Proposal.proposal_date >= '=>$startDate,'Proposal.proposal_date <=' => $endDate);
	}
	
	$proposalModel = ClassRegistry::init('Proposal');
	$proposal_followupModel = ClassRegistry::init('ProposalFollowup');
	
	$pending_proposals = $proposalModel->find('all',array(
		'order'=>array('Proposal.proposal_sent_date'=>'DESC'),
		'conditions'=>array($date_conditions,'Proposal.proposal_status'=>array(1,2,4,5))
		));
		$i = 0;
		foreach($pending_proposals as $proposal_followup):
			
			$followup_rule = json_decode($proposal_followup['ProposalFollowupRule']['followup_sequence'],true);
			$proposal_sent_date = $proposal_followup['Proposal']['proposal_sent_date'];
			foreach($followup_rule as $day => $followup_type):				

					$followups = $proposal_followupModel->find('all',array(
						'recursive'=>-1,
						'conditions'=>array(
						'ProposalFollowup.proposal_id' => $proposal_followup['Proposal']['id'],
						'OR'=>array(
						'ProposalFollowup.followup_day' => $day,
						'ProposalFollowup.followup_date BETWEEN ? AND ? ' =>  array(date('Y-m-d',strtotime($proposal_sent_date. '+ '. ($previous + 1). ' days')),date('Y-m-d',strtotime($proposal_sent_date. '+ '. $day . ' days'))),
						)
					)));
					
					if(!$followups){
						$days_difference = $this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a');
						if($days_difference >= $day)$followup_status = 'Not Done';
						elseif($this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a') == $day-1)$followup_status = 'Today';
						else $followup_status = 'Pending';
					}else {
						if(date('Y-m-d',strtotime($followups[0]['ProposalFollowup']['followup_date'])) > date('Y-m-d',strtotime($proposal_sent_date .'+ ' .$day. ' days'))){
						 	$followup_status = 'Delayed';
						}else{
							$followup_status = 'Done';
						}	
					}
					if(!$followups)$followup_type = $followup_type;
					else $followup_type = "Required :". $followup_type ." ; Follow Up By ". $followups[0]['ProposalFollowup']['followup_type'];				
					
					if($this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a') == $day )$followup_warning = true;
					$followup_details[$i]['Proposal'] = $proposal_followup['Proposal'];
					$followup_details[$i]['Customer'] = $proposal_followup['Customer'];
					$followup_details[$i]['Employee'] = $proposal_followup['Employee'];
					$followup_details[$i]['FolowupDetails'][] = array(						
							'FollowupDay' => $day,
							'FollowupType' => $followup_type,
							'FollowupDate' => $followups[0]['ProposalFollowup']['followup_date'],
							'RequiredFollowUp' => $followups,
							'FollowupStatus' => $followup_status,
						);
			$previous = $day;			
			endforeach;
			$i++;
		endforeach;
		return $followup_details;
	}
	
	public function proposalFollowupStatusCount($startDate = null, $endDate = null, $duration = null){	
	if($duration == true){
		$date_conditions = array();
	}else{
		$date_conditions = array('Proposal.proposal_date > '=>$startDate,'Proposal.proposal_date <' => $endDate);
	}
	$proposalModel = ClassRegistry::init('Proposal');
	$proposal_followupModel = ClassRegistry::init('ProposalFollowup');
	
	$pending_proposals = $proposalModel->find('all',array(
		'fields'=>array('Proposal.id','Proposal.customer_id','Proposal.proposal_sent_date','Proposal.proposal_followup_rule_id','Proposal.proposal_status',
		'ProposalFollowupRule.id','ProposalFollowupRule.followup_sequence'),
		'recursive' => 1,
		'conditions'=>array($date_conditions,'Proposal.proposal_status'=>1)));

		$i = $done = $not_done = $pending = $previous = 0;
		$status = array('Done'=>0,'NotDone'=>0,'Pending'=>0);
		foreach($pending_proposals as $proposal_followup):		
			$followup_rule = json_decode($proposal_followup['ProposalFollowupRule']['followup_sequence'],true);
			$proposal_sent_date = $proposal_followup['Proposal']['proposal_sent_date'];
			foreach($followup_rule as $day => $followup_type):				
					$followups = $proposal_followupModel->find('all',array('conditions'=>array(
						'ProposalFollowup.proposal_id' => $proposal_followup['Proposal']['id'],
						'OR'=>array(
						'ProposalFollowup.followup_day' => $day,
						'ProposalFollowup.followup_date BETWEEN ? AND ? ' =>  array(date('Y-m-d',strtotime($proposal_sent_date. '+ '. ($previous + 1). ' days')),date('Y-m-d',strtotime($proposal_sent_date. '+ '. $day. ' days'))),
						)
					)));
					
					if(!$followups){
						$days_difference = $this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a');
						if($days_difference >= $day)
							{
								$not_done = $not_done + 1;
							}else{
								$pending = $pending + 1;
							}
					}else{
							$done = $done + 1;
					}	
					$status['NotDone'] = $not_done;
					$status['Pending'] = $pending;
					$status['Done'] = $done;		
					$previous = $day;			
			endforeach;
			$i++;
			
		endforeach;
		return $status;
		
	}	
	
	public function _dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
		{
			$datetime1 = date_create($date_1);
			$datetime2 = date_create($date_2);
			
			$interval = date_diff($datetime1, $datetime2);
			
			return $interval->format($differenceFormat);
			
		}
	
	public function proposal_followup_status($proposal_id = null){
            
		$proposalModel = ClassRegistry::init('Proposal');
		$proposal_followupModel = ClassRegistry::init('ProposalFollowup');
		$pending_proposals = $proposalModel->find('first',array('conditions'=>array('Proposal.id'=>$proposal_id)));		
			$followup_rule = json_decode($pending_proposals['ProposalFollowupRule']['followup_sequence'],true);
			$proposal_sent_date = $pending_proposals['Proposal']['proposal_sent_date'];
			foreach($followup_rule as $day => $followup_type):				
					$followups = $proposal_followupModel->find('all',array(
						'recursive'=>-1,
						'conditions'=>array(
						'ProposalFollowup.proposal_id' => $pending_proposals['Proposal']['id'],
						'OR'=>array(
						'ProposalFollowup.followup_day' => $day,
						'ProposalFollowup.followup_date BETWEEN ? AND ? ' =>  array(date('Y-m-d',strtotime($proposal_sent_date. '+ '. ($previous + 1). ' days')),date('Y-m-d',strtotime($proposal_sent_date. '+ '. $day. ' days'))),
						)
					)));
					if(!$followups){
						$days_difference = $this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a');
						if($days_difference > $day)$followup_status = 'Not Done';
						elseif($this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a') == $day )$followup_status = 'Today';
						else $followup_status = 'Pending';
					}else {
						if(date('Y-m-d',strtotime($followups[0]['ProposalFollowup']['followup_date'])) > date('Y-m-d',strtotime($proposal_sent_date .'+ ' .$day. ' days'))){
						 	$followup_status = 'Delayed';
						}else{
							$followup_status = 'Done';
						}	
					}
					if($this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a') == $day )$followup_warning = true;
					$followup_details['Proposal'] = $pending_proposals['Proposal'];
					$followup_details['Customer'] = $pending_proposals['Customer'];
					$followup_details['Employee'] = $pending_proposals['Employee'];
					$followup_details['Day'] = $day;
					$followup_details['FolowupDetails'][] = array(						
							'FollowupDay' => $day,
							'FollowupType' => $followup_type,
							'FollowupDate' => $followups[0]['ProposalFollowup']['followup_date'],
							'RequiredFollowUp' => $followups,
							'FollowupStatus' => $followup_status,
						);
			$previous = $day;
			$followup_details['Days'][]=$day;
			endforeach;	
			return $followup_details;
		
	}
	
	
	public function proposal_graph($startDate = null, $endDate = null, $duration = null){
            if (!$startDate && !$endDate) {
                $startDate = date('Y-m-d',strtotime('-1 months'));
                $endDate = date('Y-m-d');                
            } else {
                $startDate = date('Y-m-d', strtotime($startDate));
                $endDate = date('Y-m-d', strtotime($endDate));
            }
          
	
	if($duration == true){
		$date_conditions = array('Proposal.proposal_status'=>array(1,2,4,5));
	}else{
		$date_conditions = array('Proposal.proposal_status'=>array(1,2,4,5),'Proposal.proposal_date >= '=>$startDate,'Proposal.proposal_date <=' => $endDate);
	}
	
	$graph = array();
	$customerModel = ClassRegistry::init('Customer');
	$proposalModel = ClassRegistry::init('Proposal');
	$proposal_followupModel = ClassRegistry::init('ProposalFollowup');
	
	while (strtotime($startDate) <= strtotime($endDate)) {
		//$graph[$startDate]['MissedCount'] = 0;
		$missed_count = 0;
		$total_customers = $customerModel->find('count',array('conditions'=>array('Customer.publish'=>1 , 'Customer.soft_delete'=>0,'Customer.created BETWEEN ? AND ?' => array(date('Y-m-d 00:00:00.000000', strtotime($startDate)),date("Y-m-d 00:00:00.999999", strtotime("+1 day", strtotime($startDate)))))));
		$total_followups = $proposal_followupModel->find('count',array('conditions'=>array('ProposalFollowup.publish'=>1 , 'ProposalFollowup.soft_delete'=>0,'ProposalFollowup.created BETWEEN ? AND ?' => array(date('Y-m-d 00:00:00.000000', strtotime($startDate)),date("Y-m-d 00:00:00.999999", strtotime("+1 day", strtotime($startDate)))))));
		$total_proposals = $proposalModel->find('count',array('conditions'=>array('Proposal.publish'=>1 , 'Proposal.soft_delete'=>0,'Proposal.created BETWEEN ? AND ?' => array(date('Y-m-d 00:00:00.000000', strtotime($startDate)),date("Y-m-d 00:00:00.999999", strtotime("+1 day", strtotime($startDate)))))));
		
		$proposals = $proposalModel->find('all',array('conditions'=>array('Proposal.proposal_sent_date'=>$startDate),
				'fields'=>array('Proposal.id','Proposal.proposal_followup_date','proposal_followup_rule_id','Proposal.proposal_sent_date','Proposal.proposal_followup_date',
				'ProposalFollowupRule.id','ProposalFollowupRule.followup_sequence','Proposal.proposal_status'),
				'recursive'=>0
		));
		$graph[$startDate]['MissedCount'] = 0;
		foreach($proposals as $proposal):
			$followup_rule = json_decode($proposal['ProposalFollowupRule']['followup_sequence'],true);
				$proposal_sent_date = $proposal['Proposal']['proposal_sent_date'];
				
				foreach($followup_rule as $day => $followup_type):				
						$followups = $proposal_followupModel->find('count',array(
							'conditions'=>array(
								'ProposalFollowup.followup_date' => date('Y-m-d',$proposal['Proposal']['proposal_followup_date']),
								'ProposalFollowup.followup_date' => date('Y-m-d',$startDate)
								)));
						
						if($followups == 0){
							$graph[$startDate]['MissedCount']++;		
						}else{
							$graph[$startDate]['MissedCount'] = 0;
						}
						
			endforeach;
		endforeach;
		//$graph[$startDate]['MissedCount'] = $missed_count;												
		//$graph[$startDate]['MissedCount'] = $graph[$startDate]['MissedCount'] + $missed_count;
		$graph[$startDate]['Customers'] = $graph[$startDate]['Customers'] + $total_customers;
		$graph[$startDate]['Proposals'] = $graph[$startDate]['Proposals'] + $total_proposals; 
		$graph[$startDate]['ProposalFollowUps'] = $graph[$startDate]['ProposalFollowUps'] + $total_followups;
		//$previous = $startDate;
		
		$startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));	
	}
	
	$graph_data = "[['Date','Customers','Proposals', 'Proposal Follow Ups','Missed Followups'],";
        $finaldata = array();
        foreach ($graph as $date=>$data):
            $finaldata['labels'][] = "'". date('d', strtotime($date))."'" ;
            $finaldata['Customers_data'][] =  $data['Customers'];
            $finaldata['Proposals_data'][] =  $data['Proposals'];
            $finaldata['ProposalFollowUps_data'][] =  $data['ProposalFollowUps'];
            $finaldata['MissedCount_data'][] =  $data['MissedCount'];
//	           $graph_data .= "['" . date('d-m-Y', strtotime($date)) . "'," . $data['Customers'] . "," . $data['Proposals'] . ",". $data['ProposalFollowUps'].",". $data['MissedCount'] ."],";
        endforeach;
//        $graph_data .= "]]";
//        $graph_data = str_replace("],]]", "]]", $graph_data);	
        return $finaldata;
		//return $graph_data;
	}
	
	
	public function proposal_graphs(){
		$proposalModel = ClassRegistry::init('Proposal');
		$proposal_followupModel = ClassRegistry::init('ProposalFollowup');
		
		$proposals = $proposalModel->find('all');
		
	}
}

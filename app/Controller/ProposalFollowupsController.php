<?php

App::uses('AppController', 'Controller');

/**
 * ProposalFollowups Controller
 *
 * @property ProposalFollowup $ProposalFollowup
 */
class ProposalFollowupsController extends AppController {

    public function _get_system_table_id() {

        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $sys_id = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $sys_id['SystemTable']['id'];
    }

    public function followup_count($id) {
        $this->loadModel('ProposalFollowup');
        $followupCount = $this->ProposalFollowup->find('count', array('conditions' => array('ProposalFollowup.proposal_id' => $id), 'ProposalFollowup.publish' => 1, 'ProposalFollowup.soft_delete' => 0));
        return $followupCount;
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {

        $conditions = $this->_check_request();
        $this->paginate = array('order' => array('ProposalFollowup.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->ProposalFollowup->recursive = 0;
        $this->set('proposalFollowups', $this->paginate());

        $this->_get_count();
    }
	
	/*public function today() {

        $conditions = $this->_check_request();
		$conditions = array($conditions, array('ProposalFollowup.next_follow_up_date'=>date('Y-m-d')));
        $this->paginate = array('order' => array('ProposalFollowup.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->ProposalFollowup->recursive = 0;
        $this->set('proposalFollowups', $this->paginate());

        $this->_get_count();
    }*/
	
	

    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function advanced_search() {

        $conditions = array();
        if ($this->request->query['keywords']) {
            $searchArray = array();
            if ($this->request->query['strict_search'] == 0) {
                $searchKeys[] = $this->request->query['keywords'];
            } else {
                $searchKeys = explode(" ", $this->request->query['keywords']);
            }

            foreach ($searchKeys as $searchKey):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $searchArray[] = array('ProposalFollowup.' . $search => $searchKey);
                    else
                        $searchArray[] = array('ProposalFollowup.' . $search . ' like ' => '%' . $searchKey . '%');
                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }


        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('ProposalFollowup.branchid' => $branches);
            endforeach;

            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }

        if ($this->request->query['proposal_id'] != -1) {
            $proposalConditions = array('ProposalFollowup.proposal_id' => $this->request->query['proposal_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $proposalConditions);
            else
                $conditions[] = array('or' => $proposalConditions);
        }

        if ($this->request->query['employee_id'] != -1) {
            $employee_conditions = array('ProposalFollowup.employee_id' => $this->request->query['employee_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $employee_conditions);
            else
                $conditions[] = array('or' => $employee_conditions);
        }

        if ($this->request->query['customer_id'] != -1) {
            $customerConditions = array('ProposalFollowup.customer_id' => $this->request->query['customer_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $customerConditions);
            else
                $conditions[] = array('or' => $customerConditions);
        }

        if ($this->request->query['status'] != -1) {
            $statusConditions = array('ProposalFollowup.status' => $this->request->query['status']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $statusConditions);
            else
                $conditions[] = array('or' => $statusConditions);
        }

        if ($this->request->query['followup_date'] != '') {
            $FollowupDateConditions = array('ProposalFollowup.followup_date' => $this->request->query['followup_date']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $FollowupDateConditions);
            else
                $conditions[] = array('or' => $FollowupDateConditions);
        }

        if ($this->request->query['next_followup_date'] != '') {
            $nextFollowupDateConditions = array('ProposalFollowup.next_followup_date' => $this->request->query['next_followup_date']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $nextFollowupDateConditions);
            else
                $conditions[] = array('or' => $nextFollowupDateConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('ProposalFollowup.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'ProposalFollowup.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);

        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('ProposalFollowup.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('ProposalFollowup.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);
        $this->ProposalFollowup->recursive = 0;
        $this->paginate = array('order' => array('ProposalFollowup.sr_no' => 'DESC'), 'conditions' => $conditions, 'ProposalFollowup.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('proposalFollowups', $this->paginate());
        $this->render('index');
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->ProposalFollowup->exists($id)) {
            throw new NotFoundException(__('Invalid proposal followup'));
        }
        $options = array('conditions' => array('ProposalFollowup.' . $this->ProposalFollowup->primaryKey => $id));
        $this->set('proposalFollowup', $this->ProposalFollowup->find('first', $options));
    }

    /**
     * list method
     *
     * @return void
     */
    public function lists($id = NULL) {

        $this->_get_count();
        $this->set('id', $id);
    }

    /**
     * add_ajax method
     *
     * @return void
     */
    public function add_ajax($proposal_id = null) {
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post')) {
            $this->request->data['ProposalFollowup']['system_table_id'] = $this->_get_system_table_id();
            $customer = $this->ProposalFollowup->Proposal->find('first', array('conditions' => array('Proposal.id' => $this->request->data['ProposalFollowup']['proposal_id']), 'fields' => 'customer_id', 'recursive' => -1));
            $this->request->data['ProposalFollowup']['customer_id'] = $customer['Proposal']['customer_id'];

            $this->ProposalFollowup->create();
            if ($this->ProposalFollowup->save($this->request->data)) {
                
                $this->ProposalFollowup->Proposal->read(null, $this->request->data['ProposalFollowup']['proposal_id']);
				
				$proposal['Proposal']['id'] = $this->request->data['ProposalFollowup']['proposal_id']; 
				$proposal['Proposal']['proposal_assigned_to'] = $this->request->data['ProposalFollowup']['followup_assigned_to'];
				$proposal['Proposal']['proposal_followup_date'] = $this->request->data['ProposalFollowup']['next_follow_up_date'];
				$proposal['Proposal']['proposal_status'] = $this->request->data['Proposal']['proposal_status'];
				$proposal['Proposal']['modified'] = date('Y-m-d h:i:s');
				$proposal['Proposal']['modified_by'] = $this->Session->read('User.id');
				$this->ProposalFollowup->Proposal->save($proposal['Proposal'],true);

                if ($this->request->data['ProposalFollowup']['require'] == 1) {
                    $this->loadModel('CustomerMeeting');
                    $this->CustomerMeeting->create();
                    $data['employee_id'] = $this->request->data['ProposalFollowup']['employee_id'];
                    $data['proposal_followup_id'] = $this->ProposalFollowup->id;
                    $data['meeting_date'] = $this->request->data['ProposalFollowup']['followup_date'];
                    $data['action_point'] = $this->request->data['ProposalFollowup']['followup_heading'];
                    $data['details'] = $this->request->data['ProposalFollowup']['followup_details'];
                    $data['next_meeting_date'] = $this->request->data['ProposalFollowup']['next_follow_up_date'];
                    $data['status'] = $this->request->data['ProposalFollowup']['status'];
                    $data['publish'] = 1;
                    $data['soft_delete'] = 0;
                    $data['customer_id'] = $this->request->data['ProposalFollowup']['customer_id'];
                    $data['active_lock'] = $this->request->data['ProposalFollowup']['active_lock'];
                    $this->CustomerMeeting->save($data);
                }

                $this->Session->setFlash(__('The proposal followup has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->ProposalFollowup->id));
                else{
                    if($this->request->data['ProposalFollowup']['redirect'] != ''){
                    $this->redirect(array('controller'=>'proposals','action' => 'index'));}
                    else{
                    $this->redirect(array('action' => 'index'));}
                }

            } else {
                $this->Session->setFlash(__('The proposal followup could not be saved. Please, try again.'));
            }
        }
		//get customer details & set proposals
		if($this->request->params['pass'][0]){
        	$customer = $this->ProposalFollowup->Customer->find('first', array('conditions' => array('Customer.id'=>$this->request->params['pass'][0],'Customer.publish' => 1, 'Customer.soft_delete' => 0)));
			if($customer)$proposals = $this->ProposalFollowup->Proposal->find('list', array('conditions' => array('proposal.customer_id'=>$customer['Customer']['id'],'Proposal.publish' => 1, 'Proposal.soft_delete' => 0)));
			else $proposals = $this->ProposalFollowup->Proposal->find('list', array('conditions' => array('Proposal.id'=>$this->request->params['pass'][0],'Proposal.publish' => 1, 'Proposal.soft_delete' => 0)));
			$this->set('loadData',true);
            $customerContacts = $this->ProposalFollowup->Customer->CustomerContact->find('list',
                    array('conditions'=>array(
                            'CustomerContact.customer_id'=>$customer['Customer']['id'],
                            'CustomerContact.soft_delete'=>0, 'CustomerContact.publish'=>1
                        ))
                );            
            $this->set('customerContacts',$customerContacts);
		}else{
			$customer = null;
			$proposals = $this->ProposalFollowup->Proposal->find('list', array('conditions' => array('Proposal.proposal_status' => 1,'Proposal.publish' => 1, 'Proposal.soft_delete' => 0)));	
		}
        $customers = $this->ProposalFollowup->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $employees = $this->ProposalFollowup->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
		$followupAssignedTos = $this->ProposalFollowup->FollowupAssignedTo->find('list', array('conditions' => array('FollowupAssignedTo.publish' => 1, 'FollowupAssignedTo.soft_delete' => 0)));
        $employee_list = $this->ProposalFollowup->Proposal->find('first', array('conditions' => array('Proposal.id' => $proposal_id, 'Proposal.publish' => 1, 'Proposal.soft_delete' => 0)));
        $employee_id = $employee_list['Proposal']['employee_id'];
        $active_lock = $employee_list['Proposal']['active_lock'];
        $this->set(compact('proposal_id', 'employee_id', 'active_lock', 'proposals', 'customers', 'employees','customer','followupAssignedTos'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {

        if (!$this->ProposalFollowup->exists($id)) {
            throw new NotFoundException(__('Invalid proposal followup'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['ProposalFollowup']['system_table_id'] = $this->_get_system_table_id();
            $customer = $this->ProposalFollowup->Proposal->find('first', array('conditions' => array('Proposal.id' => $this->request->data['ProposalFollowup']['proposal_id']), 'fields' => 'customer_id', 'recursive' => -1));
            $this->request->data['ProposalFollowup']['customer_id'] = $customer['Proposal']['customer_id'];

            if ($this->ProposalFollowup->save($this->request->data)) {
				$proposal['Proposal']['id'] = $this->request->data['ProposalFollowup']['proposal_id']; 
				$proposal['Proposal']['proposal_assigned_to'] = $this->request->data['ProposalFollowup']['followup_assigned_to'];
				$proposal['Proposal']['proposal_followup_date'] = $this->request->data['ProposalFollowup']['next_follow_up_date'];
				$proposal['Proposal']['proposal_status'] = $this->request->data['Proposal']['proposal_status'];
				$proposal['Proposal']['modified'] = date('Y-m-d h:i:s');
				$proposal['Proposal']['modified_by'] = $this->Session->read('User.id');
				$this->ProposalFollowup->Proposal->save($proposal['Proposal'],true);
				$this->loadModel('CustomerMeeting');
                $customermeetings = $this->CustomerMeeting->find('all', array('conditions' => array('CustomerMeeting.proposal_followup_id' => $id)));
                foreach ($customermeetings as $customermeeting):
                    $this->CustomerMeeting->delete($customermeeting['CustomerMeeting']['id']);
                endforeach;
                if ($this->request->data['ProposalFollowup']['require'] == 1) {
                    $this->CustomerMeeting->create();
                    $data['proposal_id'] = $this->request->data['ProposalFollowup']['proposal_id'];
                    $data['employee_id'] = $this->request->data['ProposalFollowup']['employee_id'];
                    $data['proposal_followup_id'] = $this->ProposalFollowup->id;
                    $data['meeting_date'] = $this->request->data['ProposalFollowup']['followup_date'];
                    $data['action_point'] = $this->request->data['ProposalFollowup']['followup_heading'];
                    $data['details'] = $this->request->data['ProposalFollowup']['followup_details'];
                    $data['next_meeting_date'] = $this->request->data['ProposalFollowup']['next_follow_up_date'];
                    $data['status'] = $this->request->data['ProposalFollowup']['status'];
                    $data['publish'] = 1;
                    $data['soft_delete'] = 0;
                    $data['customer_id'] = $this->request->data['ProposalFollowup']['customer_id'];
                    $this->CustomerMeeting->save($data);
                }
                $this->Session->setFlash(__('The proposal followup has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The proposal followup could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('ProposalFollowup.' . $this->ProposalFollowup->primaryKey => $id));
            $this->request->data = $this->ProposalFollowup->find('first', $options);
        }
        //get customer details & set proposals
	
			$customer = null;
			$proposal = $this->ProposalFollowup->Proposal->find('first', array('conditions' => array('Proposal.id' => $this->data['ProposalFollowup']['proposal_id'],'Proposal.publish' => 1, 'Proposal.soft_delete' => 0)));	
        $customers = $this->ProposalFollowup->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $employees = $this->ProposalFollowup->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
		$followupAssignedTos = $this->ProposalFollowup->FollowupAssignedTo->find('list', array('conditions' => array('FollowupAssignedTo.publish' => 1, 'FollowupAssignedTo.soft_delete' => 0)));
        $employee_list = $this->ProposalFollowup->Proposal->find('first', array('conditions' => array('Proposal.id' => $proposal_id, 'Proposal.publish' => 1, 'Proposal.soft_delete' => 0)));
        $employee_id = $employee_list['Proposal']['employee_id'];
        $active_lock = $employee_list['Proposal']['active_lock'];
		$proposal_id = $this->data['Proposal']['id'];
        $this->set(compact('proposal_id', 'employee_id', 'active_lock', 'proposal', 'customers', 'employees','customer','followupAssignedTos'));

    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approval_id = null) {
        if (!$this->ProposalFollowup->exists($id)) {
            throw new NotFoundException(__('Invalid proposal followup'));
        }

        $this->loadModel('Approval');
        if (!$this->Approval->exists($approval_id)) {
            throw new NotFoundException(__('Invalid approval id'));
        }

        $approval = $this->Approval->read(null, $approval_id);
        $this->set('same', $approval['Approval']['user_id']);

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            $customer = $this->ProposalFollowup->Proposal->find('first', array('conditions' => array('Proposal.id' => $this->request->data['ProposalFollowup']['proposal_id']), 'fields' => 'customer_id', 'recursive' => -1));
            $this->request->data['ProposalFollowup']['customer_id'] = $customer['Proposal']['customer_id'];

            if ($this->ProposalFollowup->save($this->request->data)) {

                $this->loadModel('CustomerMeeting');
                $customermeetings = $this->CustomerMeeting->find('all', array('conditions' => array('CustomerMeeting.proposal_followup_id' => $id), 'recursive' => -1));
                foreach ($customermeetings as $customermeeting):
                    $this->CustomerMeeting->delete($customermeeting['CustomerMeeting']['id']);
                endforeach;
                if ($this->request->data['ProposalFollowup']['require'] == 1) {
                    $this->CustomerMeeting->create();
                    $data['proposal_id'] = $this->request->data['ProposalFollowup']['proposal_id'];
                    $data['employee_id'] = $this->request->data['ProposalFollowup']['employee_id'];
                    $data['proposal_followup_id'] = $this->ProposalFollowup->id;
                    $data['meeting_date'] = $this->request->data['ProposalFollowup']['followup_date'];
                    $data['action_point'] = $this->request->data['ProposalFollowup']['followup_heading'];
                    $data['details'] = $this->request->data['ProposalFollowup']['followup_details'];
                    $data['next_meeting_date'] = $this->request->data['ProposalFollowup']['next_follow_up_date'];
                    $data['status'] = $this->request->data['ProposalFollowup']['status'];
                    $data['publish'] = 1;
                    $data['soft_delete'] = 0;
                    $data['customer_id'] = $this->request->data['ProposalFollowup']['customer_id'];
                    $this->CustomerMeeting->save($data);
                }

                $this->Session->setFlash(__('The proposal followup has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The proposal followup could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('ProposalFollowup.' . $this->ProposalFollowup->primaryKey => $id));
            $this->request->data = $this->ProposalFollowup->find('first', $options);
        }
        $proposals = $this->ProposalFollowup->Proposal->find('list', array('conditions' => array('Proposal.publish' => 1, 'Proposal.soft_delete' => 0)));
        $customermeeting = $this->ProposalFollowup->CustomerMeeting->find('first', array('conditions' => array('CustomerMeeting.proposal_followup_id' => $id, 'CustomerMeeting.publish' => 1, 'CustomerMeeting.soft_delete' => 0), 'fields' => array('CustomerMeeting.id')));
        $employees = $this->ProposalFollowup->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $this->set(compact('customermeeting', 'proposals', 'customers', 'employees'));
     }

     public function followups(){
        $conditions = $this->_check_request();
        $conditions = array_merge($conditions,array(
            'ProposalFollowup.followup_assigned_to'=>$this->Session->read('User.id'),
            'Proposal.proposal_status' => array(1,2,4,5),
            'ProposalFollowup.next_follow_up_date >'=>date('Y-m-d'),
            'ProposalFollowup.followup_date'=>date('Y-m-d')));
        $this->paginate = array('order' => array('ProposalFollowup.date' => 'DESC'), 'conditions' => array($conditions));

        $this->ProposalFollowup->recursive = 0;
        $proposalFollowups = $this->paginate();
        $this->set('proposalFollowups', $proposalFollowups);
        // $this->render('index');
        $this->_get_count();
        
        return $proposalFollowups;
     }

}

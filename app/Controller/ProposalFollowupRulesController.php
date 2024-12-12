<?php

App::uses('AppController', 'Controller');

/**
 * Proposals Controller
 *
 * @property Proposal $Proposal
 */
class ProposalFollowupRulesController extends AppController {
	public $components = array('Bd');
	
    public function _get_system_table_id() {

        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
		
    }

    /**
     * index method
     *
     * @return void
     */
    public function index($customer_id = null) {
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        $conditions = $this->_check_request();
		 $this->paginate = array('order' => array('ProposalFollowupRule.sr_no' => 'DESC'));

        $this->ProposalFollowupRule->recursive = 0;
        $this->set('proposalFollowupRules', $this->paginate());

        $this->_get_count();
		
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['ProposalFollowupRule']['number_of_followups_required'] = $this->request->data['ProposalFollowupRule']['number_of_followups_required'] - 1;
			$this->request->data['ProposalFollowupRule']['followup_sequence'] = json_encode($this->request->data['ProposalFollowupRule']['folloupType']);
			$this->request->data['ProposalFollowupRule']['system_table_id'] = $this->_get_system_table_id();	
	
			if ($this->ProposalFollowupRule->save($this->request->data['ProposalFollowupRule'])) {
                $this->Session->setFlash(__('The proposal followup rule has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Proposal->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The proposal followup rule could not be saved. Please, try again.'));
            }
		}
    }
	
	public function edit($id = null) {
		
        if (!$this->ProposalFollowupRule->exists($id)) {
            throw new NotFoundException(__('Invalid ProposalFollowupRule'));
        }
		
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['ProposalFollowupRule']['number_of_followups_required'];
			$this->request->data['ProposalFollowupRule']['followup_sequence'] = json_encode($this->request->data['ProposalFollowupRule']['folloupType']);	
	
            if ($this->ProposalFollowupRule->save($this->request->data['ProposalFollowupRule'])) {

                $this->Session->setFlash(__('The ProposalFollowupRule has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The ProposalFollowupRule could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('ProposalFollowupRule.' . $this->ProposalFollowupRule->primaryKey => $id));
            $this->request->data = $this->ProposalFollowupRule->find('first', $options);

        }
    }

/**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->ProposalFollowupRule->exists($id)) {
            throw new NotFoundException(__('Invalid ProposalFollowupRule'));
        }
       $options = array('conditions' => array('ProposalFollowupRule.' . $this->ProposalFollowupRule->primaryKey => $id));
		$this->set('proposalFollowupRule', $this->ProposalFollowupRule->find('first', $options));		
    }

	public function findrule($id = null){
		$this->loadModel('Proposal');
		$proposal = $this->Proposal->find('first',array('fields'=>array('Proposal.id','Proposal.proposal_followup_rule_id'),'conditions'=>array('Proposal.id'=>$id)));
		$followup_detail = $this->Bd->proposal_followup_status($id);
		$this->set('followup_detail',$followup_detail);

	}
    
}

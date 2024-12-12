<?php

App::uses('AppController', 'Controller');

/**
 * Customers Controller
 *
 * @property Customer $Customer
 */
class CustomersController extends AppController {

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
    public function customer_index($lead_type = null) {

        $conditions = $this->_check_request();
        $conditions = array($conditions,'Customer.lead_type'=>$lead_type);
        if(isset($this->request->params['named']['soft_delete']))$conditions = array($conditions, 'Customer.soft_delete'=>1);
        if(isset($this->request->params['named']['publish']))$conditions = array($conditions, 'Customer.publish'=>$this->request->params['named']['publish']);
        
        $this->paginate = array(
            'limit'=>10,
            'order' => array('Customer.sr_no' => 'DESC'), 
            'fields' => array('Customer.id','Customer.name','Customer.email','Customer.phone','Customer.mobile','Customer.lead_type','Customer.created_by','Customer.customer_type','Customer.publish','Customer.soft_delete'),
            'conditions' => array($conditions));

        $this->Customer->recursive = 0;
        $customers = $this->paginate();
        $this->loadModel('Approval');
        $this->Customer->recursive = 1;
        foreach($customers as $customer):
        $contacts = 0;
            $proposals = NULL;
            $contacts = $this->Customer->CustomerContact->find('all',array('fields'=>array('CustomerContact.id','CustomerContact.name','CustomerContact.publish'),'conditions'=>array('CustomerContact.customer_id'=>$customer['Customer']['id'])));
            $proposals = $this->Customer->Proposal->find('all',array('fields'=>array('Proposal.id','Proposal.title','Proposal.publish','Proposal.customer_id','Proposal.proposal_status'),
                'conditions'=>array('Proposal.customer_id'=>$customer['Customer']['id'])));
            $newProposals = NULL;   
            foreach($proposals as $proposal):           
                $check_approval = $this->Approval->find('count',array('conditions'=>array('Approval.model_name'=>'Proposal','Approval.record'=>$proposal['Proposal']['id'],'Approval.status <> '=>'Approved')));
                if($check_approval > 0)$proposal['Approval'] = true;
                else $proposal['Approval'] = false;             
                $newProposals[] = $proposal;
            endforeach;
            
            //$proposals = $newProposals;
            $proposal_followups = $this->Customer->ProposalFollowup->find('all',array('fields'=>array('ProposalFollowup.id','ProposalFollowup.followup_heading'),'conditions'=>array('ProposalFollowup.customer_id'=>$customer['Customer']['id'])));
            $meetings = $this->Customer->CustomerMeeting->find('all',array('fields'=>array('CustomerMeeting.id','CustomerMeeting.action_point'),'conditions'=>array('CustomerMeeting.customer_id'=>$customer['Customer']['id'])));
            $customer = $customer;
            $customer['CustomerContacts']=array($contacts, 'Count'=>count($contacts));
            $customer['Proposals']=array($newProposals, 'Count'=>count($proposals));
            $customer['ProposalFollowups']= array($proposal_followups, 'Count'=>count($proposal_followups));
            $customer['Meetings'] = array($meetings,'Count'=>count($meetings));
            $newCustomer[] = $customer;     
        endforeach;
        $this->set('customers', $newCustomer);
        $this->_get_count();
    }
    
    public function index($customer_type = null) {
        $leads = $this->Customer->find('count',array('conditions'=>array('Customer.lead_type'=>0)));
        $cust = $this->Customer->find('count',array('conditions'=>array('Customer.lead_type'=>1)));
        $this->set(array('leads'=>$leads,'cust'=>$cust));
    }

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
                        $searchArray[] = array('Customer.' . $search => $searchKey);
                    else
                        $searchArray[] = array('Customer.' . $search . ' like ' => '%' . $searchKey . '%');
                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('Customer.branch_id' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }
        if ($this->request->query['customer_type'] == 0) {
            $customerTypeConditions[] = array('Customer.customer_type' => $this->request->query['customer_type']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $customerTypeConditions);
            else
                $conditions[] = array('or' => $customerTypeConditions);
        }
        elseif ($this->request->query['customer_type'] == 1) {
            $customerTypeConditions[] = array('Customer.customer_type' => $this->request->query['customer_type']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $customerTypeConditions);
            else
                $conditions[] = array('or' => $customerTypeConditions);
        }
        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('Customer.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'Customer.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);



        if ($this->Session->read('User.is_mr') == 0)
            $onlyBranch = array('Customer.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('Customer.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->Customer->recursive = 0;
        $this->paginate = array('order' => array('Customer.sr_no' => 'DESC'), 'conditions' => $conditions, 'Customer.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('customers', $this->paginate());

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
        if (!$this->Customer->exists($id)) {
            throw new NotFoundException(__('Invalid customer'));
        }
        $options = array('conditions' => array('Customer.' . $this->Customer->primaryKey => $id));
        $this->set('customer', $this->Customer->find('first', $options));
    }

    /**
     * list method
     *
     * @return void
     */
    public function lists() {

        $this->_get_count();
    }
    
    /** Add customer contact
    
    **/
    public function add_new_contact($company_id = null){
    if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post')) {
        $this->_add_customer_contact($customer_contact_data = $this->request->data,$customer_id=$company_id);
        }
    }
    
    public function _add_customer_contact($customer_contact_data = null,$customer_id=null){
        if($customer_contact_data){
            $customer_contact_data['CustomerContact']['customer_id'] = $customer_id;
            $customer_contact_data['CustomerContact']['publish'] = $customer_contact_data['Customer']['publish'];
            $customer_contact_data['CustomerContact']['branchid'] = $customer_contact_data['Customer']['branchid'];
            $customer_contact_data['CustomerContact']['departmentid '] = $customer_contact_data['Customer']['departmentid'];
            $customer_contact_data['CustomerContact']['master_list_of_format_id'] = $customer_contact_data['Customer']['master_list_of_format_id'];
            $customer_contact_data['CustomerContact']['prepared_by'] = $customer_contact_data['Customer']['prepared_by'];
            $customer_contact_data['CustomerContact']['approved_by'] = $customer_contact_data['Customer']['approved_by'];
            if($this->Customer->CustomerContact->save($customer_contact_data['CustomerContact'])){
                $this->Session->setFlash(__('The customer contact has been saved'));
                $this->redirect(array('action' => 'index'));   
            }else{
                $this->Session->setFlash(__('The customer contact could not be been saved'));
                $this->redirect(array('action' => 'add_new_contact'));   
            }
        }
    }

    /**
     * add_ajax method
     *
     * @return void
     */
     
    public function add_ajax() {

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post')) {

            if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['Customer']['system_table_id'] = $this->_get_system_table_id();
            $this->Customer->create();

            if ($this->request->data['Customer']['customer_type'] == 0) {
                unset($this->request->data['Customer']['maritial_status']);
                unset($this->request->data['Customer']['date_of_birth']);
            }
            // apend customer code
            //$this->request->data['Customer']['customer_code'] = $this->_add_customer_code();
            if(!$this->request->data['Customer']['lead_type'])$this->request->data['Customer']['lead_type'] = 0;
            if ($this->Customer->save($this->request->data)) {
                    $this->_add_customer_contact($this->request->data,$this->Customer->id);
                $this->Session->setFlash(__('The customer has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Customer->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The customer could not be saved. Please, try again.'));
            }
        }
        $maritalStatus = array('Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed', 'Separated' => 'Separated', 'Divorced' => 'Divorced', 'Other' => 'Other');
        $employees = $this->Customer->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
        $this->set(compact('branches', 'systemTables', 'masterListOfFormats', 'maritalStatus','employees'));
    }


    /** adding customer code **/
    public function _add_customer_code(){
        $code = $this->Customer->find('first',array('order'=>array('Customer.customer_code'=>'DESC')));
        $code = (int)$code['Customer']['customer_code'];
        $code = $code + 1;
        return $code;
    }
    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Customer->exists($id)) {
            throw new NotFoundException(__('Invalid customer'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['Customer']['system_table_id'] = $this->_get_system_table_id();

            if ($this->request->data['Customer']['customer_type'] == 0) {
                $this->request->data['Customer']['maritial_status'] = '';
                $this->request->data['Customer']['date_of_birth'] = '';
            }
            if ($this->Customer->save($this->request->data, false)) {
                $this->Session->setFlash(__('The customer has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The customer could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Customer.' . $this->Customer->primaryKey => $id));
            $this->request->data = $this->Customer->find('first', $options);
        }
        $maritalStatus = array('Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed', 'Separated' => 'Separated', 'Divorced' => 'Divorced', 'Other' => 'Other');
        $this->set(compact('maritalStatus'));
        $employees = $this->Customer->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
        $this->set(compact('branches', 'systemTables', 'masterListOfFormats', 'maritalStatus','employees'));
        
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->Customer->exists($id)) {
            throw new NotFoundException(__('Invalid customer'));
        }

        $this->loadModel('Approval');
        if (!$this->Approval->exists($approvalId)) {
            throw new NotFoundException(__('Invalid approval id'));
        }

        $approval = $this->Approval->read(null, $approvalId);
        $this->set('same', $approval['Approval']['user_id']);

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }

            if ($this->request->data['Customer']['customer_type'] == 0) {
                $this->request->data['Customer']['maritial_status'] = '';
                $this->request->data['Customer']['date_of_birth'] = '';
            }
            if ($this->Customer->save($this->request->data, false)) {
                $this->Session->setFlash(__('The customer has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The customer could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Customer.' . $this->Customer->primaryKey => $id));
            $this->request->data = $this->Customer->find('first', $options);
        }
        $maritalStatus = array('Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed', 'Separated' => 'Separated', 'Divorced' => 'Divorced', 'Other' => 'Other');
        $this->set(compact('maritalStatus'));
        $employees = $this->Customer->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
        $this->set(compact('branches', 'systemTables', 'masterListOfFormats', 'maritalStatus','employees'));
    }

    public function get_unique_values($number = null, $type = null, $id = null) {

        $this->Customer->recursive = -1;

        if ($id) {
            if ($number) {
                if ($type == 'custCode') {
                    $customerCode = $this->Customer->find('all', array('conditions' => array('Customer.customer_code' => $number, 'Customer.id !=' => $id)));
                    $this->set('customerCode', $customerCode);
                } else {
                    $emailId = $this->Customer->find('all', array('conditions' => array('Customer.email' => $number, 'Customer.id !=' => $id)));
                    $this->set('emailId', $emailId);
                }
            }
        } else {
            if ($number) {
                if ($type == 'custCode') {
                    $customerCode = $this->Customer->find('all', array('conditions' => array('customer_code' => $number)));
                    $this->set('customerCode', $customerCode);
                } else {
                    $emailId = $this->Customer->find('all', array('conditions' => array('email' => $number)));
                    $this->set('emailId', $emailId);
                }
            }
        }
        $this->set('type', $type);
    }

}

<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Proposals Controller
 *
 * @property Proposal $Proposal
 */
class ProposalsController extends AppController {
    
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

        $conditions = $this->_check_request();
        if($customer_id)$conditions = array($conditions,'Proposal.customer_id'=>$customer_id);
        else $conditions = $conditions;
        $this->paginate = array('order' => array('Proposal.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->Proposal->recursive = 0;
        $this->set('proposals', $this->paginate());

        $this->_get_count();
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
                        $searchArray[] = array('Proposal.' . $search => $searchKey);
                    else
                        $searchArray[] = array('Proposal.' . $search . ' like ' => '%' . $searchKey . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('Proposal.branchid' => $branches);
            endforeach;

            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }

        if ($this->request->query['customer_id'] != -1) {
            $customerConditions = array('Proposal.customer_id' => $this->request->query['customer_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $customerConditions);
            else
                $conditions[] = array('or' => $customerConditions);
        }

        if ($this->request->query['employee_id'] != -1) {
            $employeeConditions = array('Proposal.employee_id' => $this->request->query['employee_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $employeeConditions);
            else
                $conditions[] = array('or' => $employeeConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('Proposal.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'Proposal.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);



        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('Proposal.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('Proposal.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->Proposal->recursive = 0;
        $this->paginate = array('order' => array('Proposal.sr_no' => 'DESC'), 'conditions' => $conditions, 'Proposal.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('proposals', $this->paginate());

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
        if (!$this->Proposal->exists($id)) {
            throw new NotFoundException(__('Invalid proposal'));
        }
        $this->loadModel('ProposalFollowup');
        $options = array('conditions' => array('Proposal.' . $this->Proposal->primaryKey => $id));
        $followups = $this->ProposalFollowup->find('all', array('conditions' => array('ProposalFollowup.proposal_id' => $id)));
        $this->set('proposal', $this->Proposal->find('first', $options));
        $this->set('followups', $followups);
        
        //check for approval status
        $this->loadModel('Approval');
        $approval_status = $this->Approval->find('count',array('conditions'=>array('Approval.model_name'=>'Proposal','Approval.record'=>$id,'Approval.status <>'=>'Approved')));
        $this->set('approval_status',$approval_status);
    }

    /**
     * list method
     *
     * @return void
     */
    public function lists() {

        $this->_get_count();
    }

    /**
     * add_ajax method
     *
     * @return void
     */
    public function add_ajax($customer_id = null) {
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
$this->loadModel('CustomerContact');
        if ($this->request->is('post')) {
             $this->request->data['Proposal']['system_table_id'] = $this->_get_system_table_id();
            $this->Proposal->create();
            if ($this->Proposal->save($this->request->data)) {

                $this->Session->setFlash(__('The proposal has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Proposal->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The proposal could not be saved. Please, try again.'));
            }
        }
        $customers = $this->Proposal->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        if($customer_id){
            $customerContacts = $this->Proposal->CustomerContact->find('list', array(
            'conditions' => array('CustomerContact.publish' => 1, 'CustomerContact.soft_delete' => 0,'CustomerContact.customer_id'=>$this->request->params['named']['customer_id'])));
        }else{
            $customerContacts = $this->CustomerContact->find('list', array(
            'conditions' => array('CustomerContact.publish' => 1, 'CustomerContact.soft_delete' => 0)));
        }
                
        $proposalFollowupRules = $this->Proposal->ProposalFollowupRule->find('list', array('conditions' => array('ProposalFollowupRule.publish' => 1, 'ProposalFollowupRule.soft_delete' => 0)));
        $proposalAssignedTos = $this->Proposal->ProposalAssignedTo->find('list',array('conditions'=>array('ProposalAssignedTo.publish'=>1,'ProposalAssignedTo.soft_delete'=>0)));
        $this->set(compact('customers','proposalFollowupRules','customerContacts','proposalAssignedTos'));
        
     }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        
        if (!$this->Proposal->exists($id)) {
            throw new NotFoundException(__('Invalid proposal'));
        }
        
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Proposal']['system_table_id'] = $this->_get_system_table_id();
            if ($this->Proposal->save($this->request->data)) {

                $this->Session->setFlash(__('The proposal has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The proposal could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Proposal.' . $this->Proposal->primaryKey => $id));
            $this->request->data = $this->Proposal->find('first', $options);
                if($this->request->data['Proposal']['proposal_status'] != 0){
                    $this->Session->setFlash(__('The proposal already sent.'));
                    $this->redirect(array('action' => 'view',$id)); 
                }
        }
        $customers = $this->Proposal->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $employees = $this->Proposal->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $proposalFollowupRules = $this->Proposal->ProposalFollowupRule->find('list', array('conditions' => array('ProposalFollowupRule.publish' => 1, 'ProposalFollowupRule.soft_delete' => 0)));
        $this->set(compact('customers', 'employees','proposalFollowupRules'));

    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->Proposal->exists($id)) {
            throw new NotFoundException(__('Invalid proposal'));
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
            if ($this->Proposal->save($this->request->data)) {

                $this->Session->setFlash(__('The proposal has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The proposal could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Proposal.' . $this->Proposal->primaryKey => $id));
            $this->request->data = $this->Proposal->find('first', $options);
        }
        $customers = $this->Proposal->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $employees = $this->Proposal->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $proposalFollowupRules = $this->Proposal->ProposalFollowupRule->find('list', array('conditions' => array('ProposalFollowupRule.publish' => 1, 'ProposalFollowupRule.soft_delete' => 0)));
        $this->set(compact('customers', 'employees','proposalFollowupRules'));

    }

    public function send_to_customer($id = null){
        if($this->request->data['Proposal']['proposal_status'] != 0){
            $this->Session->setFlash(__('The proposal already sent.'));
            $this->redirect(array('action' => 'view',$id)); 
        }
            if (!$this->Proposal->exists($id)) {
            throw new NotFoundException(__('Invalid proposal'));
        }
        if ($this->_show_approvals()) {
           // $this->set(array('showApprovals' => $this->_show_approvals()));
        }       
        if ($this->request->is('post') || $this->request->is('put')) {        
            //get followup date form followup rules
            if($this->request->data['Proposal']['proposal_sent_type'])$this->_send_email($this->request->data);
            $rule = $this->Proposal->ProposalFollowupRule->find('first',array('ProposalFollowupRule.id'=>$this->request->data['Proposal']['proposal_followup_rule_id']));
            $followup_days = $rule['ProposalFollowupRule']['followup_sequence'];
            $followup_days = json_decode($followup_days,true);
            $first_key = key($followup_days);
            $followup_day = date('Y-m-d', strtotime('+' . $first_key-1 . ' days'));
            $this->request->data['Proposal']['proposal_followup_date'] = $followup_day;
           
            $this->request->data['Proposal']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['Proposal']['publish'] = 1;
            $this->request->data['Proposal']['proposal_status'] = 1;
            if(!$this->request->data['Proposal']['proposal_sent_date'])$this->request->data['Proposal']['proposal_sent_date'] = date('Y-m-d');
            if ($this->Proposal->save($this->request->data)) {

                $this->Session->setFlash(__('The proposal has been saved & status is updated'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The proposal could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Proposal.' . $this->Proposal->primaryKey => $id));
            $this->request->data = $this->Proposal->find('first', $options);
        }
        $customers = $this->Proposal->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $employees = $this->Proposal->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
         $customerContacts = $this->Proposal->CustomerContact->find('list', array(
            'conditions' => array('CustomerContact.publish' => 1, 'CustomerContact.soft_delete' => 0,'CustomerContact.customer_id'=>$this->request->data['Proposal']['customer_id'])));
        $proposalFollowupRules = $this->Proposal->ProposalFollowupRule->find('list', array('conditions' => array('ProposalFollowupRule.publish' => 1, 'ProposalFollowupRule.soft_delete' => 0)));
        $this->set(compact('customers', 'employees','customerContacts','proposalFollowupRules'));
        
    }
    
    public function proposal_followup_status($start_date = null, $end_date = null, $duration = null){               
        if(!$start_date)$start_date = date('Y-m-d',strtotime('-1 months'));
        if(!$end_date)$end_date = date('Y-m-d');
        if($this->request->params['named']['duration'] == 'all')$duration = true;
        $this->set('followup_details',$this->Bd->proposalFollowupStatus($start_date,$end_date,$duration));          
    }
    
    public function proposal_graph($start_date = null, $end_date = null, $duration = null){ 
        $this->layout = 'ajax';
        if($this->request->params['named']['duration'] == 'all')$duration = true;
        if(!$start_date)$start_date = date('Y-m-d',strtotime('-1 months'));
        if(!$end_date)$end_date = date('Y-m-d');
        $this->set('data',$this->Bd->proposal_graph($start_date,$end_date,$duration));        
    }
    /*public function proposal_followup_status(){       
        $pending_proposals = $this->Proposal->find('all',array('conditions'=>array('Proposal.proposal_status'=>1)));
        $i = 0;
        foreach($pending_proposals as $proposal_followup):
            
            $followup_rule = json_decode($proposal_followup['ProposalFollowupRule']['followup_sequence'],true);
            $proposal_sent_date = $proposal_followup['Proposal']['proposal_sent_date'];
            foreach($followup_rule as $day => $followup_type):              
                    $followups = $this->Proposal->ProposalFollowup->find('all',array('conditions'=>array(
                        'ProposalFollowup.proposal_id' => $proposal_followup['Proposal']['id'],
                        'ProposalFollowup.followup_date BETWEEN ? AND ?' => array(date('Y-m-d'),date('Y-m-d',strtotime($proposal_sent_date. '+ '. $day. ' days'))),
                        'ProposalFollowup.followup_date BETWEEN ? AND ? ' =>  array(date('Y-m-d',strtotime($proposal_sent_date. '+ '. ($previous + 1). ' days')),date('Y-m-d',strtotime($proposal_sent_date. '+ '. $day. ' days'))),
                    )));
                    if(!$followups){
                        $days_difference = $this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a');
                        if($days_difference > $day)$followup_status = 'Not Done';
                        else $followup_status = 'Pending';
                    }else $followup_status = 'Done';
                    $followup_details[$i]['Proposal'] = $proposal_followup['Proposal'];
                    $followup_details[$i]['Customer'] = $proposal_followup['Customer'];
                    $followup_details[$i]['Employee'] = $proposal_followup['Employee'];
                    $followup_details[$i]['FolowupDetails'][] = array(                      
                            'FollowupDay' => $day,
                            'FollowupType' => $followup_type,
                            'RequiredFollowUp' => $followups,
                            'FollowupStatus' => $followup_status
                        );
            $previous = $day;           
            endforeach;
            $i++;
        endforeach;     
        $this->set('followup_details',$followup_details);
    }
    
    public function _dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
        {
            $datetime1 = date_create($date_1);
            $datetime2 = date_create($date_2);
            
            $interval = date_diff($datetime1, $datetime2);
            
            return $interval->format($differenceFormat);
            
        }
*/
public function today_followups() {
        $conditions = $this->_check_request();
        $conditions = array($conditions, array('Proposal.proposal_status' => array(1,2,4,5)));
        $this->paginate = array('order' => array('Proposal.created' => 'DESC'), 'conditions' => $conditions);
        $newProposals = array();
        $previous = 0;
        $this->Proposal->recursive = 0;
        $proposals = $this->paginate();
        foreach($proposals as $proposal):
            $followup_rule = json_decode($proposal['ProposalFollowupRule']['followup_sequence'],true);
            $proposal_sent_date = $proposal['Proposal']['proposal_sent_date'];
            foreach($followup_rule as $day => $followup_type):              
                $followups = $this->Proposal->ProposalFollowup->find('all',array(
                            'recursive'=>-1,
                            'conditions'=>array(
                            'ProposalFollowup.proposal_id' => $proposal['Proposal']['id'],
                            // 'OR'=>array(
                            // 'ProposalFollowup.followup_day' => $day,
                            // 'ProposalFollowup.followup_date BETWEEN ? AND ? ' =>  array(date('Y-m-d',strtotime($proposal_sent_date. '+ '. ($previous + 1). ' days')),date('Y-m-d',strtotime($proposal_sent_date. '+ '. $day. ' days'))),
                            // )
                        )));
                        // if(!$followups){
                        //     $days_difference = $this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a');
                        //     if($this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a') == $day-1 ){
                                $followup_status = 'Today';
                                $newProposals[] = $proposal;
                        //     }
                        // }
                        $previous = $day;
            endforeach;
        endforeach;
        $this->set('proposals', $newProposals);
        $this->_get_count();
        $this->render('index');
    }

public function proposals_not_sent() {
        $conditions = $this->_check_request();
        $conditions = array($conditions, array('Proposal.proposal_status' => 0));
        $this->paginate = array('order' => array('Proposal.created' => 'DESC'), 'conditions' => $conditions);

        $this->Proposal->recursive = 0;
        $this->set('proposals', $this->paginate());
        $this->_get_count();
        $this->render('index');
    }
    
public function proposals_lost() {
        $conditions = $this->_check_request();
        $conditions = array($conditions, array('Proposal.proposal_status' => 3));
        $this->paginate = array('order' => array('Proposal.created' => 'DESC'), 'conditions' => $conditions);

        $this->Proposal->recursive = 0;
        $this->set('proposals', $this->paginate());
        $this->_get_count();
        $this->render('index');
    }   

public function _dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
        {
            $datetime1 = date_create($date_1);
            $datetime2 = date_create($date_2);
            
            $interval = date_diff($datetime1, $datetime2);
            
            return $interval->format($differenceFormat);
            
        }   

public function _send_email($data = null){
    $proposal = $this->Proposal->find('first',array('conditions'=>array('Proposal.id'=>$data['Proposal']['id']), 'recursive'=>0));
    $this->loadModel('FileUpload');
    $files = $this->FileUpload->find('all',array(
        'conditions'=>array('OR'=>array('FileUpload.id'=>$data['add_file'])),
        'recursive'=> -1
        ));
    foreach ($files as $file) {
        $attach[$file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type']] = array('file'=>Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS .$file['FileUpload']['file_dir']);
    }
        if($proposal['CustomerContact']['email']){
            $proposal['CustomerContact']['email'] = str_replace(' ', '',$proposal['CustomerContact']['email']);
            if (strpos($proposal['CustomerContact']['email'],',') !== false) {
                $to = split(',', $proposal['CustomerContact']['email']);
            }else{
                $to = $proposal['CustomerContact']['email'];
            }

            $data['Proposal']['proposal_cc'] = str_replace(' ', '', $data['Proposal']['proposal_cc']);
            if (strpos($data['Proposal']['proposal_cc'],',') !== false) {
                $cc = split(',', $data['Proposal']['proposal_cc']);
            }else{
                $cc = $data['Proposal']['proposal_cc'];
            }

            $data['Proposal']['proposal_bcc'] = str_replace(' ', '', $data['Proposal']['proposal_bcc']);
            if (strpos($data['Proposal']['proposal_bcc'],',') !== false) {
                $bcc = split(',', $data['Proposal']['proposal_bcc']);
            }else{
                $bcc = $data['Proposal']['proposal_bcc'];
            }

            try{    
                
                if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                else $env = "";

                if($this->Session->read('User.is_smtp') == 1)
                    $EmailConfig = new CakeEmail("smtp");
                if($this->Session->read('User.is_smtp') == 0)
                    $EmailConfig = new CakeEmail("default");
                
                $EmailConfig->to($to);
                if($data['Proposal']['proposal_cc'])$EmailConfig->cc($cc);
                if($data['Proposal']['proposal_bcc'])$EmailConfig->bcc($bcc);
                $EmailConfig->subject($data['Proposal']['proposal_heading']);
                $EmailConfig->template('proposal');
                $EmailConfig->viewVars(array('data'=>$data,'proposal'=>$proposal,'env' => $env, 'app_url' => FULL_BASE_URL));
                $EmailConfig->emailFormat('html');
                if($files)$EmailConfig->attachments($attach);            
                $EmailConfig->send();

            } catch(Exception $e) {
                $this->Session->setFlash(__('Email could not be sent. Please check smtp details.', true), 'smtp');
                $this->redirect(array('action' => 'index'));
            }    

        }else{
            $this->Session->setFlash(__('Email for customer contact could not be found. Email sending failed.', true), 'smtp');
            $this->redirect(array('action' => 'index'));
        }        
    }
 
    public function get_contacts($customer_id = null){
        $this->autoRender = false;
        $customerContacts = $this->Proposal->Customer->CustomerContact->find('list',array('conditions'=>array('CustomerContact.customer_id'=>$customer_id)));
        foreach ($customerContacts as $id=>$name) {
            $con_str .= '<option value=' . $id .'>' . $name . '</option>' ;
        }
        return $con_str;
        exit;

    }
}

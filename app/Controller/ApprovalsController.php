<?php

App::uses('AppController', 'Controller');

/**
 * Branches Controller
 *
 * @property Branch $Branch
 */
class ApprovalsController extends AppController {

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
    public function index() {
        $conditions = $this->_check_request();
        if($this->Session->read('User.is_mr') == 0){
            $condition2 = array('OR'=>array('Approval.user_id' =>$this->Session->read('User.id'),'Approval.from' =>$this->Session->read('User.id')));
        }else{
            $condition2 = array();
        }
        $this->paginate = array('group'=>array('Approval.record'), 'order' => array('Approval.modified' => 'DESC'), 'conditions' => array($conditions, $condition2, 'or'=>array("Approval.status" => NULL,"Approval.status" => 'Forwarded') ), 'recursive' => -1);
        
        $approvals = $this->paginate();
        foreach ($approvals as $key=>$approval){
           
            $this->loadModel($approval['Approval']['model_name']);
          
            $records= $this->{$approval['Approval']['model_name']}->find('first', array('conditions'=>array('id'=>$approval['Approval']['record']), 'recursive' => -1));
            
            if($records[$approval['Approval']['model_name']]['publish'] != true){
                $approvals[$key]['Approval']['title'] =  $records[$approval['Approval']['model_name']][$this->{$approval['Approval']['model_name']}->displayField];
                $approvals[$key]['Approval']['app_record_status'] = $records[$approval['Approval']['model_name']]['record_status'];
                $approvals[$key]['record_published'] = 1;
            }
            
        }
        $userList = $this->get_usernames();
        $this->set('approvals', $approvals);
        $this->set('userList', $userList);
        $this->_get_count();
    }

     public function approved() {
        $conditions = $this->_check_request();
        if($this->Session->read('User.is_mr') == 0){
            $condition2 = array('OR'=>array('Approval.user_id' =>$this->Session->read('User.id'),'Approval.from' =>$this->Session->read('User.id')));
        }else{
            $condition2 = array();
        }
        $this->paginate = array('order' => array('Approval.modified' => 'DESC'), 'conditions' => array($conditions, $condition2, "Approval.status " => 'Approved' ), 'recursive' => -1);

       $approvals = $this->paginate();
        foreach ($approvals as $key=>$approval){
           
            $this->loadModel($approval['Approval']['model_name']);
          
            $records= $this->{$approval['Approval']['model_name']}->find('first', array('conditions'=>array('id'=>$approval['Approval']['record']), 'recursive' => -1));
            $approvals[$key]['Approval']['title'] =  $records[$approval['Approval']['model_name']][$this->{$approval['Approval']['model_name']}->displayField];
        }
        $userList = $this->get_usernames();
        $this->set('approvals', $approvals);
        $this->set('userList', $userList);
        $this->_get_count();
    }

    public function cancel($id = null){

    }

    public function unlock_record($id = null){
        $approval = $this->Approval->find('first',array('conditions'=>array('Approval.id'=>$id),'recursive'=>-1));
        $model = $approval['Approval']['model_name'];
        $model = $this->loadModel($model);        
        $record = $this->$approval['Approval']['model_name']->find('first',array('conditions'=>array($approval['Approval']['model_name'].'.id'=>$approval['Approval']['record'])));        
        $this->$approval['Approval']['model_name']->read(null,$approval['Approval']['record']);
        $this->$approval['Approval']['model_name']->set(array('record_status'=>0,'record_status_user'=>$this->Session->read('User.id')));
        $this->$approval['Approval']['model_name']->save(true);
        $this->Session->setFlash(__('Record is unlocked'));
        $this->redirect(array('action' => 'index'));
        
    }

    public function send_reminder($id = null){
        $approval = $this->Approval->find('first',array('conditions'=>array('Approval.id'=>$id),'recursive'=>1));        
        $this->_send_approval_reminder($approval['Approval']['user_id'],date('Y-m-d',strtotime($approval['Approval']['created'])),$approval['From']['name']);
    }

    public function _send_approval_reminder($user_to_send = null, $approval_date = null, $from = null){
        $this->loadModel('User');
        $get_employee = $this->User->find('first',array(
                'fields'=>array('User.id','User.employee_id'),
                'conditions'=>array('User.id'=>$user_to_send),
                'recursive'=>0
                ));
        $this->loadModel('Employee');
        $employee = $this->Employee->find('first',array('conditions'=>array('Employee.id'=>$get_employee['User']['employee_id']), 
            'fields'=>array('id', 'office_email','personal_email'), 'recursive'=>-1));
        $officeEmailId = $employee['Employee']['office_email'];
        $personalEmailId = $employee['Employee']['personal_email'];
        if ($officeEmailId != '') {
            $email = $officeEmailId;
        } else if ($personalEmailId != '') {
            $email = $personalEmailId;
        }
        
        try{
            App::uses('CakeEmail', 'Network/Email');
            if($this->Session->read('User.is_smtp') == 1)$EmailConfig = new CakeEmail("smtp");
            if($this->Session->read('User.is_smtp') == 0)$EmailConfig = new CakeEmail("default");

                if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                else $env = "";
                
                $EmailConfig->to($email);
                $EmailConfig->subject('FlinkISO: Pending approval reminder from ' . $from);
                $EmailConfig->template('approval_reminder');
                $EmailConfig->viewVars(array('user' => $user_to_send,'date'=> $approval_date,'env' => $env, 'app_url' => FULL_BASE_URL));
                $EmailConfig->emailFormat('html');
                $EmailConfig->send();
                $this->Session->setFlash(__('Email is succesfully sent to user.', true), 'smtp');
                $this->redirect(array('action' => 'index'));

            } catch(Exception $e) {
                $this->Session->setFlash(__('The user has been saved but fail to send email. Please check smtp details.', true), 'smtp');
                $this->redirect(array('action' => 'index'));
            }
    }

}

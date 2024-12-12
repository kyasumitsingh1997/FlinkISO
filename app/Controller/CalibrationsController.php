<?php

App::uses('AppController', 'Controller');

/**
 * Calibrations Controller
 *
 * @property Calibration $Calibration
 */
class CalibrationsController extends AppController {

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
        $this->paginate = array('order' => array('Calibration.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->Calibration->recursive = 0;
        $this->set('calibrations', $this->paginate());

        $this->_get_count();

        if ($this->Session->read('User.is_mr') == false)
            $conditions = array('Device.employee_id' => $this->Session->read('User.employee_id'));
        else
            $conditions = null;
        $devices = $this->Calibration->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0, 'Device.calibration_required' => 0, $conditions)));
        $this->set(compact('devices'));
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
                        $searchArray[] = array('Calibration.' . $search => $searchKey);
                    else
                        $searchArray[] = array('Calibration.' . $search . ' like ' => '%' . $searchKey . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('Calibration.branchid' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }
        if ($this->request->query['device_id']) {
            foreach ($this->request->query['device_id'] as $devices):
                $deviceConditions[] = array('Calibration.device_id' => $devices);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $deviceConditions));
            else
                $conditions[] = array('or' => $deviceConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('Calibration.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'Calibration.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);


        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('Calibration.branchid' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('Calibration.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->Calibration->recursive = 0;
        $this->paginate = array('order' => array('Calibration.sr_no' => 'DESC'), 'conditions' => $conditions, 'Calibration.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('calibrations', $this->paginate());

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
        if (!$this->Calibration->exists($id)) {
            throw new NotFoundException(__('Invalid calibration'));
        }
        $options = array('conditions' => array('Calibration.' . $this->Calibration->primaryKey => $id));
        $this->set('calibration', $this->Calibration->find('first', $options));
    }

    /**
     * list method
     *
     * @return void
     */
    public function lists() {

        $this->_get_count();
        if ($this->Session->read('User.is_mr') == false)
            $conditions = array('Device.employee_id' => $this->Session->read('User.employee_id'));
        else
            $conditions = null;
        $devices = $this->Calibration->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0, 'Device.calibration_required' => 0, $conditions)));
        $this->set(compact('devices'));
    }

    public function get_details($id = null) {
        $this->layout = "ajax";
        $device = $this->Calibration->Device->find('first', array('recursive' => 0, 'conditions' => array('Device.id' => $id)));
        $this->set(compact('device'));
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
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['Calibration']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['Calibration']['created_by'] = $this->Session->read('User.id');
            $this->request->data['Calibration']['modified_by'] = $this->Session->read('User.id');

            $this->Calibration->create();
            if ($this->Calibration->save($this->request->data)) {
                $this->Session->setFlash(__('The calibration has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Calibration->id));
                else
                    $this->redirect(str_replace('/lists', '/add_ajax', $this->referer()));
            } else {
                $this->Session->setFlash(__('The calibration could not be saved. Please, try again.'));
            }
        }
        if ($this->Session->read('User.is_mr') == false)
            $conditions = array('Device.employee_id' => $this->Session->read('User.employee_id'));
        else
            $conditions = null;
        $devices = $this->Calibration->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0, 'Device.calibration_required' => 0, $conditions)));
        $this->set(compact('devices'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Calibration->exists($id)) {
            throw new NotFoundException(__('Invalid calibration'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['Calibration']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['Calibration']['modified_by'] = $this->Session->read('User.id');
            if ($this->Calibration->save($this->request->data)) {
                $this->Session->setFlash(__('The calibration has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The calibration could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Calibration.' . $this->Calibration->primaryKey => $id));
            $this->request->data = $this->Calibration->find('first', $options);
        }
        $this->loadModel('User');
        $employees = $this->User->find('first', array('order' => array('User.name' => 'ASC'), 'conditions' => array('User.id' => $this->data['Calibration']['created_by']), 'fields' => 'employee_id'));
        if ($this->Session->read('User.is_mr') == false)
            $conditions = array('Device.employee_id' => $employees['User']['employee_id']);
        else
            $conditions = null;
        $devices = $this->Calibration->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0, 'Device.calibration_required' => 0, $conditions)));

        $this->set(compact('devices'));
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->Calibration->exists($id)) {
            throw new NotFoundException(__('Invalid calibration'));
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
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['Calibration']['system_table_id'] = $this->_get_system_table_id();
            if ($this->Calibration->save($this->request->data)) {
                $this->Session->setFlash(__('The Calibration has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The calibration could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Calibration.' . $this->Calibration->primaryKey => $id));
            $this->request->data = $this->Calibration->find('first', $options);
        }
        $this->loadModel('User');
        $this->User->recursive = 0;
        $employees = $this->User->find('first', array('order' => array('User.name' => 'ASC'), 'conditions' => array('User.id' => $this->data['Calibration']['created_by']), 'fields' => 'employee_id'));
        if ($this->Session->read('User.is_mr') == false)
            $conditions = array('Device.employee_id' => $employees['User']['employee_id']);
        else
            $conditions = null;
        $devices = $this->Calibration->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0, 'Device.calibration_required' => 0, $conditions)));
        $this->set(compact('devices'));
    }

    public function get_next_calibration() {

        $currentDate = date('Y-m-d');

        $this->paginate = array('limit' => 2,
            'conditions' => array('Device.employee_id' => $this->Session->read('User.employee_id'), 'Device.publish' => 1, 'Calibration.next_calibration_date >=' => $currentDate, 'Calibration.publish' => 1),
            'fields' => array('Calibration.id', 'Calibration.calibration_date', 'Calibration.next_calibration_date', 'Device.id', 'Device.name', 'Device.number'),
            'recursive' => 0);

        $nextCalibrations = $this->paginate();
        $countNextCalibrations = count($nextCalibrations);
        $this->set(compact('nextCalibrations', 'countNextCalibrations'));
        $this->render('/Elements/calibration_notifications');
    }

   public function pending_tasks() {

        $conditions = $this->_check_request();
        $this->Calibration->recursive = 0;
        $conditions = array($conditions,'Calibration.next_calibration_date >' => date('Y-m-d'));
        $this->paginate = array('order'=>array('Calibration.sr_no'=>'DESC'),'conditions'=>array($conditions));
        $this->set('calibrations', $this->paginate());

        $this->_get_count();        
    }

    public function send_reminder($id = null){
        $cc = $this->Calibration->find('first',array('recursive'=>0, 'conditions'=>array('Calibration.id'=>$this->request->params['pass'][0])));
        $this->loadModel('Employee');
        $employee = $this->Employee->find('first',array(
            'recursive'=>-1,
            'fields'=>array('Employee.id','Employee.name','Employee.personal_email','Employee.office_email'),
            'conditions'=>array('Employee.id'=>$cc['Device']['employee_id'])));
        $officeEmailId = $employee['Employee']['office_email'];
        $personalEmailId = $employee['Employee']['personal_email'];
        if ($officeEmailId != '') {
            $email = $officeEmailId;
        } else if ($personalEmailId != '') {
            $email = $personalEmailId;
        }
        if($cc && $email){
            $send_message = "Pending Calibration";
            $body = "<p>You have pending calibration to address. Please login to FlinkISO and add details</p>";
            try{
                if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                else $env = "";

                App::uses('CakeEmail', 'Network/Email');                        

                if($this->Session->read('User.is_smtp') == 1)
                    $EmailConfig = new CakeEmail("smtp");

                if($this->Session->read('User.is_smtp') == 0)
                    $EmailConfig = new CakeEmail("default");
                    $EmailConfig->to($email);
                    $EmailConfig->subject($send_message);
                    $EmailConfig->template('emailTrigger');
                    $EmailConfig->viewVars(array(
                        'date_time' => date('Y-m-d h:i:s'),
                        'by_user'=>$this->Session->read('User.username'),
                        'employee'=>$this->Session->read('User.name'),
                        'branch' => $this->Session->read('User.branch'),
                        'department' => $this->Session->read('User.department'),
                        'h2tag'=>$send_message,
                        'msg_content'=>$body,
                        'env' => $env, 'app_url' => FULL_BASE_URL
                        ));
                    $EmailConfig->emailFormat('html');
                    $EmailConfig->send();
                } catch(Exception $e) {
                    echo "<span class='btn btn-xs btn-danger'>Failed!</span>";        
                }
            echo "<span class='btn btn-xs btn-success'>Sent</span>";
        }else{
            echo "<span class='btn btn-xs btn-danger'>Failed!</span>";
        }
        
        exit;
    } 
}

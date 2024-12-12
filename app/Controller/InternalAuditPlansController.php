<?php

App::uses('AppController', 'Controller');

/**
 * InternalAuditPlans Controller
 *
 * @property InternalAuditPlan $InternalAuditPlan
 */
class InternalAuditPlansController extends AppController {

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
        $cats = array();

        if($this->request->params['pass'][0] == 1){
            // $date_condition = array('MONTH(InternalAuditPlan.schedule_date_to) <' => date('m'),'YEAR(InternalAuditPlan.schedule_date_to) <' => date('Y'));
            $date_condition = array('InternalAuditPlan.schedule_date_from < '=> date('Y-m-d'));
        }elseif($this->request->params['pass'][0] == 2){
            $date_condition = array(
                'OR'=>array(
                    array('MONTH(InternalAuditPlan.schedule_date_from)' => date('m'),'YEAR(InternalAuditPlan.schedule_date_from)' => date('Y')),
                    array('MONTH(InternalAuditPlan.schedule_date_to)' => date('m'),'YEAR(InternalAuditPlan.schedule_date_to)' => date('Y'))
                    )
                );
        }elseif($this->request->params['pass'][0] == 3){
            // $date_condition = array('MONTH(InternalAuditPlan.schedule_date_from) >' => date('m'),'YEAR(InternalAuditPlan.schedule_date_to) >' => date('Y'));
            $date_condition = array('InternalAuditPlan.schedule_date_from > '=> date('Y-m-d'));
        }
        // print_r($this->request->params['pass'][0]);
        if($this->request->params['named']['audit_category_id']){
            $cats = array('InternalAuditPlan.audit_category_id'=>$this->request->params['named']['audit_category_id']);
        }
        
        $conditions = $this->_check_request();
        $this->InternalAuditPlan->recursive = 2;
        $this->paginate = array(
            'contain'=>
            array(
                'InternalAuditPlan'=>array('fields'=>array( 
                    'InternalAuditPlan.id',
                    'InternalAuditPlan.plan_type',
                    'InternalAuditPlan.audit_type_master_id',
                    'InternalAuditPlan.title',
                    'InternalAuditPlan.schedule_date_from',
                    'InternalAuditPlan.schedule_date_to',
                    'InternalAuditPlan.publish',
                    'InternalAuditPlan.standard_id',
                    'InternalAuditPlan.audit_category_id'
                )),
                'Standard'=>array('fields'=>array('Standard.id','Standard.name')),
                'PreparedBy'=>array('fields'=>array('PreparedBy.id','PreparedBy.name')),
                'BranchIds'=>array('fields'=>array('BranchIds.id','BranchIds.name')),
                'DepartmentIds'=>array('fields'=>array('DepartmentIds.id','DepartmentIds.name')),
                'AuditTypeMaster'=>array('fields'=>array('AuditTypeMaster.id','AuditTypeMaster.name')),
                'CreatedBy'=>array('fields'=>array('CreatedBy.id','CreatedBy.name')),
                'ModifiedBy'=>array('fields'=>array('ModifiedBy.id','ModifiedBy.name')),
                // 'Employee'=>array('fields'=>array('Employee.id','Employee.name')),
                'ListOfTrainedInternalAuditor'=>array('fields'=>array('ListOfTrainedInternalAuditor.id','ListOfTrainedInternalAuditor.name')),
                'InternalAuditPlanBranch'=>array('fields'=>array('InternalAuditPlanBranch.id','InternalAuditPlanBranch.internal_audit_plan_id','InternalAuditPlanBranch.branch_id','InternalAuditPlanBranch.publish')),
                'InternalAudit'=>array('fields'=>array('InternalAudit.id','InternalAudit.internal_audit_plan_id','InternalAudit.branch_id','InternalAudit.publish')),
                'InternalAuditPlanDepartment'=>array('fields'=>array(
                    'InternalAuditPlanDepartment.id',
                    'InternalAuditPlanDepartment.internal_audit_plan_id',
                    'InternalAuditPlanDepartment.branch_id',
                    'InternalAuditPlanDepartment.clauses',
                    'InternalAuditPlanDepartment.employee_id',
                    'InternalAuditPlanDepartment.list_of_trained_internal_auditor_id',
                    'InternalAuditPlanDepartment.start_time',
                    'InternalAuditPlanDepartment.end_time',
                    'InternalAuditPlanDepartment.publish',
                    'InternalAuditPlanDepartment.prepared_by',
                    'InternalAuditPlanDepartment.approved_by',
                    'InternalAuditPlanDepartment.department_id',
                    'InternalAuditPlanDepartment.division_id',                    
                    ))
            ),
            'order' => array('InternalAuditPlan.schedule_date_from' => 'DESC'), 
            // 'limit'=>2,
            'conditions' => array($conditions,$date_condition,$cats));

        $this->set('internalAuditPlans', $this->paginate());

        $this->_get_count();
        // $this->set('processes',$processes);
        // $auditTypeMasterBefore = $this->InternalAuditPlan->AuditTypeMaster->find('list', array('fields'=>array('AuditTypeMaster.name','AuditTypeMaster.action'), 'conditions' => array('AuditTypeMaster.publish' => 1, 'AuditTypeMaster.soft_delete' => 0,'AuditTypeMaster.action_type'=>0), 'recursive' => 0));
        // $this->set('auditTypeMasterBefore',$auditTypeMasterBefore);
        // $auditTypeMasterAfter = $this->InternalAuditPlan->AuditTypeMaster->find('list', array('fields'=>array('AuditTypeMaster.name','AuditTypeMaster.action'), 'conditions' => array('AuditTypeMaster.publish' => 1, 'AuditTypeMaster.soft_delete' => 0,'AuditTypeMaster.action_type'=>1), 'recursive' => 0));
        // $this->set('auditTypeMasterAfter',$auditTypeMasterAfter);
        // $PublishedDivisionList = $this->InternalAuditPlan->Division->find('list',array('Division.publish'=>1,'Division.soft_delete'=>0));
        // $this->set('PublishedDivisionList',$PublishedDivisionList);
        $auditors = $this->InternalAuditPlan->ListOfTrainedInternalAuditor->find('list',array('conditions'=>array('ListOfTrainedInternalAuditor.publish'=>1,'ListOfTrainedInternalAuditor.soft_delete'=>0)));
        $this->set('auditors',$auditors);
        // $this->loadModel('RiskRating');
        // $riskRatings = $this->RiskRating->find('list',array('conditions'=>array('RiskRating.publish'=>1,'RiskRating.soft_delete'=>0)));
        // $this->set('riskRatings',$riskRatings);
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
            $searchKeys = explode(" ", $this->request->query['keywords']);

            foreach ($searchKeys as $searchKey):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $searchArray[] = array('InternalAuditPlan.' . $search => $searchKey);
                    else
                        $searchArray[] = array('InternalAuditPlan.' . $search . ' like ' => '%' . $searchKey . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('InternalAuditPlan.branchid' => $branches);
            endforeach;
            $conditions[] = array('or' => $branchConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('InternalAuditPlan.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'InternalAuditPlan.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);



        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('InternalAuditPlan.branchid' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('InternalAuditPlan.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->InternalAuditPlan->recursive = 0;
        $this->paginate = array('order' => array('InternalAuditPlan.sr_no' => 'DESC'), 'conditions' => $conditions, 'InternalAuditPlan.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('internalAuditPlans', $this->paginate());

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
        if (!$this->InternalAuditPlan->exists($id)) {
            throw new NotFoundException(__('Invalid internal audit plan'));
        }

        $options = array('conditions' => array('InternalAuditPlan.' . $this->InternalAuditPlan->primaryKey => $id));
        $internalAuditPlan = $this->InternalAuditPlan->find('first', $options);
        $this->set('internalAuditPlan', $internalAuditPlan);
        if ($internalAuditPlan['InternalAuditPlan']['publish'] == 1 && !$this->request->is('post'))
            $this->request->data['InternalAuditPlan']['publish'] = $internalAuditPlan['InternalAuditPlan']['publish'];

        $branches = $this->_get_branch_list();
        foreach ($branches as $key => $value):
            $plan[$key] = $this->InternalAuditPlan->InternalAuditPlanDepartment->find('all', array('conditions' => array('InternalAuditPlanDepartment.soft_delete' => 0, 'InternalAuditPlanDepartment.branch_id' => $key, 'InternalAuditPlanDepartment.internal_audit_plan_id' => $id)));
        endforeach;

        $this->set(array('plan' => $plan));
        $this->loadModel('User');
        $this->User->recursive = 0;
        $userids = $this->User->find('list', array('order' => array('User.name' => 'ASC'), 'conditions' => array('User.publish' => 1, 'User.soft_delete' => 0, 'User.is_approvar' => 1)));
        $this->set(array('userids' => $userids, 'showApprovals' => $this->_show_approvals()));

        if ($this->request->is('post')) {
            $this->request->data['InternalAuditPlan']['note'] = htmlentities($this->request->data['InternalAuditPlan']['note']);
            if ($this->InternalAuditPlan->save($this->request->data)) {
                $internalAuditPlanDepartments = $this->InternalAuditPlan->InternalAuditPlanDepartment->find('all', array('conditions' => array('internal_audit_plan_id' => $this->InternalAuditPlan->id)));
                $internalAuditPlanBranches = $this->InternalAuditPlan->InternalAuditPlanBranch->find('all', array('conditions' => array('internal_audit_plan_id' => $this->InternalAuditPlan->id)));

                $auditDepartmentUsers = array();
                if (count($internalAuditPlanBranches) && count($internalAuditPlanDepartments))
                    foreach ($internalAuditPlanBranches as $branches) {
                        foreach ($internalAuditPlanDepartments as $val) {
                            $auditDepartmentUsers[$val['InternalAuditPlanDepartment']['employee_id']] = $val['InternalAuditPlanDepartment']['employee_id'];
                            $auditDepartmentUsers[$val['ListOfTrainedInternalAuditor']['employee_id']] = $val['ListOfTrainedInternalAuditor']['employee_id'];
                        }
                    }

                if ($this->_show_approvals())$this->_save_approvals();
                    
                if ($this->request->data['InternalAuditPlan']['notify_users']) {

                    //Edit internal audit plan on notification

		       $this->loadModel('NotificationType');
                $notificationType = $this->NotificationType->find('first', array('conditions' => array('NotificationType.name' => 'Internal Audits', 'NotificationType.soft_delete' => 0)));

                    $this->loadModel('Notification');
                    $notifications = $this->Notification->find('first', array('conditions' => array('internal_audit_plan_id' => $this->InternalAuditPlan->id)), false);
                    $notificationsId = $notifications['Notification']['id'];
                    $val = array();
                    if ($notificationsId) {
                        $this->Notification->id = $notificationsId;
                        $val['id'] = $notificationsId;
                    } else
                        $this->Notification->create();
                        $val['notification_type_id'] = isset($notificationType['NotificationType']['id'])? $notificationType['NotificationType']['id'] :'';
                        $val['title'] = $this->request->data['InternalAuditPlan']['title'];
                        $val['message'] = $this->request->data['InternalAuditPlan']['notify_note'];
                        $val['start_date'] = $this->request->data['InternalAuditPlan']['schedule_date_from'];
                        $val['end_date'] = $this->request->data['InternalAuditPlan']['schedule_date_to'];
                        $val['internal_audit_plan_id'] = $this->InternalAuditPlan->id;
                        $val['prepared_by'] = $this->Session->read('User.employee_id');
                        $val['approved_by'] = $this->Session->read('User.employee_id');
                        $val['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                        $val['branchid'] = $this->Session->read('User.branch_id');
                        $val['departmentid'] = $this->Session->read('User.department_id');
                        $val['created_by'] = $this->Session->read('User.id');
                        $val['modified_by'] = $this->Session->read('User.id');
                        $val['system_table_id'] = $this->_get_system_table_id();

                        $this->Notification->save($val, false);


                        //Edit internal audit plan on notification User
                        $this->loadModel('NotificationUser');
                        $this->NotificationUser->deleteAll(array('notification_id' => $this->Notification->id), false);
                        $this->loadModel('User');

                    foreach ($auditDepartmentUsers as $employeeId) {
                        $this->NotificationUser->create();
                        $this->loadModel('User');
                        $user = $this->User->find('first',array(
                            'fields'=>array('User.employee_id','User.id'),
                            'conditions'=>array('User.employee_id'=>$employeeId,'User.publish'=>1, 'User.soft_delete'=>0)));
                        
                        $val = array();
                        $val['notification_id'] = $this->Notification->id;
                        $val['employee_id'] = $employeeId;
                        $val['user_id'] = $user['User']['id'];
                        $val['publish'] = 1;
                        $val['branchid'] = $this->Session->read('User.branch_id');
                        $val['departmentid'] = $this->Session->read('User.department_id');
                        $val['created_by'] = $this->Session->read('User.id');
                        $val['modified_by'] = $this->Session->read('User.id');
                        $val['prepared_by'] = $this->Session->read('User.id');
                        $val['approved_by'] = $this->Session->read('User.id');
                        $val['system_table_id'] = $this->_get_system_table_id();
                        $this->NotificationUser->save($val, false);
                    }
                } else {
                    $this->loadModel('Notification');
                    $this->Notification->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                }

                //Edit internal audit on timeline
                if ($this->request->data['InternalAuditPlan']['show_on_timeline']) {
                    $this->loadModel('Timeline');
                    $this->Timeline->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                    $this->Timeline->create();
                    $val = array();
                    $val['title'] = $this->request->data['InternalAuditPlan']['title'];
                    $val['message'] = $this->request->data['InternalAuditPlan']['notify_note'];
                    $val['start_date'] = $this->request->data['InternalAuditPlan']['schedule_date_from'];
                    $val['end_date'] = $this->request->data['InternalAuditPlan']['schedule_date_to'];
                    $val['internal_audit_plan_id'] = $this->InternalAuditPlan->id;
                    $val['prepared_by'] = $this->Session->read('User.id');
                    $val['approved_by'] = $this->Session->read('User.id');
                    $val['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                    $val['branchid'] = $this->Session->read('User.branch_id');
                    $val['departmentid'] = $this->Session->read('User.department_id');
                    $val['created_by'] = $this->Session->read('User.id');
                    $val['modified_by'] = $this->Session->read('User.id');
                    $val['system_table_id'] = $this->_get_system_table_id();
                    $this->Timeline->save($val, false);
                } else {
                    $this->loadModel('Timeline');
                    $this->Timeline->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                }

                if ($this->request->data['InternalAuditPlan']['notify_users_emails']) {
                        if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                        elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                        else $env = "";

                        App::uses('CakeEmail', 'Network/Email');
                        if($this->Session->read('User.is_smtp') == '1')
                        {
                            $EmailConfig = new CakeEmail("smtp");	
                        }else if($this->Session->read('User.is_smtp') == '0'){
                            $EmailConfig = new CakeEmail("default");
                        }
                        $EmailConfig->subject('FlinkISO: Internal Audit Plan');
                        $internalAuditPlan = $this->InternalAuditPlan->find('first', array('conditions' => array('InternalAuditPlan.id' => $id, 'InternalAuditPlan.publish' => 1, 'InternalAuditPlan.soft_delete' => 0)));
                        $this->loadModel('Employee');
                        $this->Employee->recursive = -1;
                        $emails = array();
                        $k=0;
                        foreach ($auditDepartmentUsers as $employeeId) {
                            $auditorEmail = $this->Employee->find('first', array('conditions' => array('Employee.id' => $employeeId), 'fields' => array('Employee.office_email', 'Employee.name')));
			                 if(isset($auditorEmail['Employee']['office_email']) && $auditorEmail['Employee']['office_email']!='')
                                if($k==0){
                                     $EmailConfig->to($auditorEmail['Employee']['office_email']);
                                }else{
                                    $emails[] = $auditorEmail['Employee']['office_email'];
                                }
                                $k++;
                        }
                        
                        $EmailConfig->bcc($emails);
                        $EmailConfig->template('internalAuditPlan');
                        $EmailConfig->viewVars(array('internalAuditPlan' => $internalAuditPlan,'env' => $env, 'app_url' => FULL_BASE_URL));
                        $EmailConfig->emailFormat('html');
                    try{
                        $EmailConfig->send();
                        $this->Session->setFlash('An email has been sent');
                     } catch(Exception $e) {
                         $this->Session->setFlash(__('Can not notify user using email. Please check SMTP details and email address is correct.'));
			             $this->redirect(array('action' => 'view', $this->InternalAuditPlan->id));

                    }
                }
                $this->redirect(array('action' => 'view', $this->InternalAuditPlan->id));
            }
        }
    }

    public function view_plan($id = null) {
        $this->layout = "ajax";
        if (!$this->InternalAuditPlan->exists($id)) {
            throw new NotFoundException(__('Invalid internal audit plan'));
        }
        $this->InternalAuditPlan->recursive = 0;
        $options = array('conditions' => array('InternalAuditPlan.' . $this->InternalAuditPlan->primaryKey => $id));
        $this->set('internalAuditPlan', $this->InternalAuditPlan->find('first', $options));

        $this->loadModel('InternalAuditPlanDepartment');
        $branches = $this->_get_branch_list();
        foreach ($branches as $key => $value):
            $plan[$key] = $this->InternalAuditPlanDepartment->find('all', array('conditions' => array('InternalAuditPlanDepartment.soft_delete' => 0, 'InternalAuditPlanDepartment.branch_id' => $key, 'InternalAuditPlanDepartment.internal_audit_plan_id' => $id
            )));
        endforeach;
        $this->set(array('plan' => $plan));
    }

    public function plan_report($id = null) {
        $this->layout = "ajax";
        if (!$this->InternalAuditPlan->exists($id)) {
            throw new NotFoundException(__('Invalid internal audit plan'));
        }
        $this->InternalAuditPlan->recursive = 0;
        $options = array('conditions' => array('InternalAuditPlan.' . $this->InternalAuditPlan->primaryKey => $id));
        $this->set('internalAuditPlan', $this->InternalAuditPlan->find('first', $options));

        $this->loadModel('InternalAuditPlanDepartment');
        $branches = $this->_get_branch_list();
        foreach ($branches as $key => $value):
            $plan[$key] = $this->InternalAuditPlanDepartment->find('all', array('conditions' => array('InternalAuditPlanDepartment.soft_delete' => 0, 'InternalAuditPlanDepartment.branch_id' => $key, 'InternalAuditPlanDepartment.internal_audit_plan_id' => $id)));
        endforeach;
        $this->set(array('plan' => $plan));
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
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        // if (!$this->InternalAuditPlan->exists($id)) {
        //     throw new NotFoundException(__('Invalid internal audit plan'));
        // }
        // $this->loadModel('Approval');
        // if (!$this->Approval->exists($approvalId)) {
        //     throw new NotFoundException(__('Invalid approval id'));
        // }
        // $approval = $this->Approval->read(null, $approvalId);
        // $this->set('same', $approval['Approval']['user_id']);
        // if ($this->_show_approvals()) {
        //     $this->set(array('showApprovals' => $this->_show_approvals()));
        // }

        if (!$this->InternalAuditPlan->exists($id)) {
            throw new NotFoundException(__('Invalid internal audit plan'));
        }

        $options = array('conditions' => array('InternalAuditPlan.' . $this->InternalAuditPlan->primaryKey => $id));
        $internalAuditPlan = $this->InternalAuditPlan->find('first', $options);
        $this->set('internalAuditPlan', $internalAuditPlan);
        if ($internalAuditPlan['InternalAuditPlan']['publish'] == 1 && !$this->request->is('post'))
            $this->request->data['InternalAuditPlan']['publish'] = $internalAuditPlan['InternalAuditPlan']['publish'];

        $branches = $this->_get_branch_list();
        foreach ($branches as $key => $value):
            $plan[$key] = $this->InternalAuditPlan->InternalAuditPlanDepartment->find('all', array('conditions' => array('InternalAuditPlanDepartment.soft_delete' => 0, 'InternalAuditPlanDepartment.branch_id' => $key, 'InternalAuditPlanDepartment.internal_audit_plan_id' => $id)));
        endforeach;

        $this->set(array('plan' => $plan));
        $this->loadModel('User');
        $this->User->recursive = 0;
        $userids = $this->User->find('list', array('order' => array('User.name' => 'ASC'), 'conditions' => array('User.publish' => 1, 'User.soft_delete' => 0, 'User.is_approvar' => 1)));
        
        $this->set(array('userids' => $userids, 'showApprovals' => $this->_show_approvals()));
        
        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data['InternalAuditPlan']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['InternalAuditPlan']['note'] = htmlentities($this->request->data['InternalAuditPlan']['note']);

            if ($this->InternalAuditPlan->save($this->request->data, false)) {
                if ($this->_show_approvals())$this->_save_approvals();
                
                //Edit internal audit on timeline
                if ($this->request->data['InternalAuditPlan']['show_on_timeline']) {
                    $this->loadModel('Timeline');
                    $this->Timeline->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                    $this->Timeline->create();
                    $val = array();
                    $val['title'] = $this->request->data['InternalAuditPlan']['title'];
                    $val['message'] = htmlentities($this->request->data['InternalAuditPlan']['note']);
                    $val['start_date'] = $this->request->data['InternalAuditPlan']['schedule_date_from'];
                    $val['end_date'] = $this->request->data['InternalAuditPlan']['schedule_date_to'];
                    $val['internal_audit_plan_id'] = $this->InternalAuditPlan->id;
                    $val['prepared_by'] = $this->Session->read('User.id');
                    $val['approved_by'] = $this->Session->read('User.id');
                    $val['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                    $val['branchid'] = $this->request->data['InternalAuditPlan']['branchid'];
                    $val['departmentid'] = $this->request->data['InternalAuditPlan']['departmentid'];
                    $val['modified_by'] = $this->Session->read('User.id');
                    $val['system_table_id'] = $this->_get_system_table_id();
                    $this->Timeline->save($val, false);
                } else {
                    $this->loadModel('Timeline');
                    $this->Timeline->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                }

                $internalAuditPlanDepartments = $this->InternalAuditPlan->InternalAuditPlanDepartment->find('all', array('conditions' => array('internal_audit_plan_id' => $this->InternalAuditPlan->id)));
                $internalAuditPlanBranches = $this->InternalAuditPlan->InternalAuditPlanBranch->find('all', array('conditions' => array('internal_audit_plan_id' => $this->InternalAuditPlan->id)));

                $auditDepartmentUsers = array();
                if (count($internalAuditPlanBranches) && count($internalAuditPlanDepartments))
                    foreach ($internalAuditPlanBranches as $branches) {
                        foreach ($internalAuditPlanDepartments as $val) {
                            $auditDepartmentUsers[$val['InternalAuditPlanDepartment']['employee_id']] = $val['InternalAuditPlanDepartment']['employee_id'];
                            $auditDepartmentUsers[$val['ListOfTrainedInternalAuditor']['employee_id']] = $val['ListOfTrainedInternalAuditor']['employee_id'];
                        }
                    }
                if ($this->request->data['InternalAuditPlan']['notify_users']) {
                    //Edit internal audit plan on notification
                    $this->loadModel('Notification');
                    $notifications = $this->Notification->find('first', array('conditions' => array('internal_audit_plan_id' => $this->InternalAuditPlan->id)), false);
                    $notificationsId = $notifications['Notification']['id'];
                    $val = array();
                    $val['id'] = $notificationsId;
                    $val['title'] = $this->request->data['InternalAuditPlan']['title'];
                    $val['message'] = htmlentities($this->request->data['InternalAuditPlan']['note']);
                    $val['start_date'] = $this->request->data['InternalAuditPlan']['schedule_date_from'];
                    $val['end_date'] = $this->request->data['InternalAuditPlan']['schedule_date_to'];
                    $val['internal_audit_plan_id'] = $this->InternalAuditPlan->id;
                    $val['prepared_by'] = $this->Session->read('User.id');
                    $val['approved_by'] = $this->Session->read('User.id');
                    $val['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                    $val['branchid'] = $this->request->data['InternalAuditPlan']['branchid'];
                    $val['departmentid'] = $this->request->data['InternalAuditPlan']['departmentid'];
                    $val['modified_by'] = $this->Session->read('User.id');
                    $val['system_table_id'] = $this->_get_system_table_id();
                    $this->Notification->save($val, false);

                    //Edit internal audit plan on notification User
                    $this->loadModel('NotificationUser');
                    $this->NotificationUser->deleteAll(array('notification_id' => $this->Notification->id), false);
                    $this->loadModel('User');

                    foreach ($auditDepartmentUsers as $employeeId) {
                        $this->NotificationUser->create();
                        $val = array();
                        $val['notification_id'] = $this->Notification->id;
                        $val['employee_id'] = $employeeId;
                        $val['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                        $val['branchid'] = $this->Session->read('User.branch_id');
                        $val['departmentid'] = $this->Session->read('User.department_id');
                        $val['created_by'] = $this->Session->read('User.id');
                        $val['modified_by'] = $this->Session->read('User.id');
                        $val['system_table_id'] = $this->_get_system_table_id();
                        $this->NotificationUser->save($val, false);
                    }
                } else {
                    $this->loadModel('Notification');
                    $this->Notification->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                }
                if ($this->request->data['InternalAuditPlan']['notify_users_emails']) {
                    try{

                        if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                        elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                        else $env = "";

                        App::uses('CakeEmail', 'Network/Email');
                        if($this->Session->read('User.is_smtp') == '1')
                        {
                            $EmailConfig = new CakeEmail("smtp");	
                        }else if($this->Session->read('User.is_smtp') == '0'){
                            $EmailConfig = new CakeEmail("default");
                        }
                        $EmailConfig->subject('FlinkISO: Internal Audit Plan');
                        $internalAuditPlan = $this->InternalAuditPlan->find('first', array('conditions' => array('InternalAuditPlan.id' => $id, 'InternalAuditPlan.publish' => 1, 'InternalAuditPlan.soft_delete' => 0)));
                        $this->loadModel('Employee');
                        $this->Employee->recursive = -1;
                        $emails = array();
                        $k=0;
                        foreach ($auditDepartmentUsers as $employeeId) {
                            $auditorEmail = $this->Employee->find('first', array('conditions' => array('Employee.id' => $employeeId), 'fields' => array('Employee.office_email', 'Employee.name')));

                              if($k==0){
                                    $EmailConfig->to($auditorEmail['Employee']['office_email']);
                                }else{
                                    $emails[] = $auditorEmail['Employee']['office_email'];
                                }
                                $k++;
                        }
                        $EmailConfig->bcc($emails);
                        $EmailConfig->template('internalAuditPlan');
                        $EmailConfig->viewVars(array('internalAudit' => $internalAuditPlan,'env' => $env, 'app_url' => FULL_BASE_URL));
                        $EmailConfig->emailFormat('html');
                        $EmailConfig->send();
                        $this->Session->setFlash('An email has been sent');
                        $this->redirect(array('controller' => 'internalAuditPlans', 'action' => 'index'));
                     } catch(Exception $e) {
                         $this->Session->setFlash(__('Can not notify user using email. Please check SMTP details and email address is correct.'));

                    }
                }
                // if($this->_show_approvals()){
                // if ($this->request->data['InternalAuditPlan']['publish'] == 0 && ($this->request->data['Approval']['user_id'] != -1 )) {
                //     $this->loadModel('Approval');
                //     $this->Approval->create();
                //     $this->request->data['Approval']['model_name'] = 'InternalAuditPlan';
                //     $this->request->data['Approval']['controller_name'] = $this->request->params['controller'];
                //     $this->request->data['Approval']['from'] = $this->Session->read('User.id');
                //     $this->request->data['Approval']['user_id'] = $this->request->data['Approval']['user_id'];
                //     $this->request->data['Approval']['created_by'] = $this->Session->read('User.id');
                //     $this->request->data['Approval']['modified_by'] = $this->Session->read('User.id');
                //     $this->request->data['Approval']['record'] = $this->InternalAuditPlan->id;
                //     $this->Approval->save($this->request->data['Approval']);

                //     $this->Session->setFlash(__('The internal audit plan has been saved'));
                // } elseif ($this->request->data['InternalAuditPlan']['publish'] == 1) {
                //         $this->Approval->read(null, $approvalId);
                //         $data['Approval']['status'] = 'Approved';
                //         $data['Approval']['modified_by'] = $this->Session->read('User.id');
                //         $this->Approval->save($data);
                //         $this->Session->setFlash(__('The internal audit plan has been published'));
                //     }
                // }
                $this->redirect(array('action' => 'view', $id));
            } else {
                $this->Session->setFlash(__('The internal audit plan could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('InternalAuditPlan.' . $this->InternalAuditPlan->primaryKey => $id));
            $this->request->data = $this->InternalAuditPlan->find('first', $options);
        }
        $systemTables = $this->InternalAuditPlan->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormats = $this->InternalAuditPlan->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $count = $this->InternalAuditPlan->find('count');
        $published = $this->InternalAuditPlan->find('count', array('conditions' => array('InternalAuditPlan.publish' => 1)));
        $unpublished = $this->InternalAuditPlan->find('count', array('conditions' => array('InternalAuditPlan.publish' => 0)));
        $auditTypeMasters = $this->InternalAuditPlan->AuditTypeMaster->find('list', array('conditions' => array('AuditTypeMaster.publish' => 1, 'AuditTypeMaster.soft_delete' => 0), 'recursive' => 0));
        $this->set(compact('count', 'published', 'unpublished', 'systemTables', 'masterListOfFormats','auditTypeMasters'));
    }

    public function plan_add_ajax($planId = null) {

        if ($planId) {

            if (!$this->InternalAuditPlan->exists($planId)) {
                throw new NotFoundException(__('Invalid internal audit plan'));
            }
            $this->InternalAuditPlan->recursive = 0;
            $options = array('conditions' => array('InternalAuditPlan.' . $this->InternalAuditPlan->primaryKey => $planId));
            $this->set('internalAuditPlan', $this->InternalAuditPlan->find('first', $options));

            $this->loadModel('InternalAuditPlanDepartment');

            $branches = $this->_get_branch_list();
            foreach ($branches as $key => $value):

                $plan[$key] = $this->InternalAuditPlanDepartment->find('all', array('conditions' => array(
                        'InternalAuditPlanDepartment.soft_delete' => 0,
                        'InternalAuditPlanDepartment.branch_id' => $key,
                        'InternalAuditPlanDepartment.internal_audit_plan_id' => $planId
                )));

            endforeach;
            foreach ($plan as $key => $value):
                foreach ($value as $key1 => $department):
                    $ListOfTrainedInternalAuditor = $this->InternalAuditPlanDepartment->ListOfTrainedInternalAuditor->find('first', array('conditions' => array('ListOfTrainedInternalAuditor.id' => $department['InternalAuditPlanDepartment']['list_of_trained_internal_auditor_id']), 'fields' => 'ListOfTrainedInternalAuditor.name', 'recursive' => 0));
                    $plan[$key][$key1]['TrainedInternalAuditor'] = $ListOfTrainedInternalAuditor['ListOfTrainedInternalAuditor']['name'];
                endforeach;
            endforeach;
            $this->set(array('plan' => $plan));
        }


        if ($this->_show_approvals()) {
           $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post')) {

            unset($this->request->data['InternalAuditPlan']['schedule_date_to']);
            $dateRange = split('-', $this->request->data['InternalAuditPlan']['schedule_date_from']);
            $start_date = rtrim(ltrim($dateRange[0]));
            $end_date = rtrim(ltrim($dateRange[1]));
            
            $this->request->data['InternalAuditPlan']['schedule_date_from'] = date('Y-m-d',strtotime($start_date));
            $this->request->data['InternalAuditPlan']['schedule_date_to'] = date('Y-m-d',strtotime($end_date));

            $this->request->data['InternalAuditPlan']['system_table_id'] = $this->_get_system_table_id();
            $this->InternalAuditPlan->create();
            $this->request->data['InternalAuditPlan']['note'] = htmlentities($this->request->data['InternalAuditPlan']['note']);

            if ($this->InternalAuditPlan->save($this->request->data['InternalAuditPlan'], false)) {

                if ($planId) {

                    //save internal audit branch
                    $this->loadModel('InternalAuditPlanBranch');
                    $this->InternalAuditPlanBranch->create();
                    $branchData['InternalAuditPlanBranch']['internal_audit_plan_id'] = $this->InternalAuditPlan->id;
                    $branchData['InternalAuditPlanBranch']['branch_id'] = $this->request->data['InternalAuditPlanDepartment']['branch_id'];
                    $branchData['InternalAuditPlanBranch']['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                    $branchData['InternalAuditPlanBranch']['branchid'] = $this->request->data['InternalAuditPlan']['branchid'];
                    $branchData['InternalAuditPlanBranch']['departmentid'] = $this->request->data['InternalAuditPlan']['departmentid'];
                    $branchData['InternalAuditPlanBranch']['created_by'] = $this->Session->read('User.id');
                    $branchData['InternalAuditPlanBranch']['modified_by'] = $this->Session->read('User.id');
                    $branchData['InternalAuditPlanBranch']['system_table_id'] = $this->_get_system_table_id();
                    $this->InternalAuditPlanBranch->save($branchData['InternalAuditPlanBranch'], false);

                    //save internal audit departments
                    $this->loadModel('InternalAuditPlanDepartment');
                    foreach ($this->request->data['InternalAuditPlanDepartment_department_id'] as $val) {
                        $this->InternalAuditPlanDepartment->create();
                        $valData = array();
                        unset($this->request->data['InternalAuditPlanDepartment']['end_date']);
                        $dateRange = split('-', $this->request->data['InternalAuditPlanDepartment']['start_date']);
                        $start_date = rtrim(ltrim($dateRange[0]));
                        $end_date = rtrim(ltrim($dateRange[1]));
                        
                        $this->request->data['InternalAuditPlanDepartment']['start_date'] = date('Y-m-d',strtotime($start_date));
                        $this->request->data['InternalAuditPlanDepartment']['end_date'] = date('Y-m-d',strtotime($end_date));

                        $valData['internal_audit_plan_id'] = $this->InternalAuditPlan->id;
                        $valData['department_id'] = $val;
                        $valData['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                        $valData['branchid'] = $this->request->data['InternalAuditPlan']['branchid'];
                        $valData['departmentid'] = $this->request->data['InternalAuditPlan']['departmentid'];

                        $valData['created_by'] = $this->Session->read('User.id');
                        $valData['modified_by'] = $this->Session->read('User.id');
                        $valData['system_table_id'] = $this->_get_system_table_id();
                        $this->InternalAuditPlanDepartment->save($valData, false);
                    }
                }


                if ($this->_show_approvals()) {
                    if ((isset($this->request->data['Approval'])) && ($this->request->data['InternalAuditPlan']['publish'] == 0 ) && ($this->request->data['Approval']['user_id'] != -1)) {
                        $this->loadModel('Approval');
                        $this->Approval->create();
                        $this->request->data['Approval']['model_name'] = 'InternalAuditPlan';
                        $this->request->data['Approval']['controller_name'] = $this->request->params['controller'];
                        $this->request->data['Approval']['user_id'] = $this->request->data['Approval']['user_id'];
                        $this->request->data['Approval']['from'] = $this->Session->read('User.id');
                        $this->request->data['Approval']['record'] = $this->InternalAuditPlan->id;
                        $this->Approval->save($this->request->data['Approval']);
                    }
                }
                $this->Session->setFlash(__('The internal audit plan has been saved'));
                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->InternalAuditPlan->id));
                else
                    $this->redirect(array('action' => 'index',1));
            } else {
                $this->Session->setFlash(__('The internal audit plan could not be saved. Please, try again.'));
            }
        }
        $systemTables = $this->InternalAuditPlan->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormats = $this->InternalAuditPlan->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $listOfTrainedInternalAuditors = $this->InternalAuditPlan->ListOfTrainedInternalAuditor->find('list', array('conditions' => array('ListOfTrainedInternalAuditor.publish' => 1, 'ListOfTrainedInternalAuditor.soft_delete' => 0), 'recursive' => 0));
        $auditTypeMasters = $this->InternalAuditPlan->AuditTypeMaster->find('list', array('conditions' => array('AuditTypeMaster.publish' => 1, 'AuditTypeMaster.soft_delete' => 0), 'recursive' => 0));
        $standards = $this->InternalAuditPlan->Standard->find('list',array('conditions'=>array('Standard.publish'=>1,'Standard.soft_delete'=>0) ));
        $processes = $this->InternalAuditPlan->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
        $riskAssessments = $this->InternalAuditPlan->RiskAssessment->find('list',array('conditions'=>array('RiskAssessment.publish'=>1,'RiskAssessment.soft_delete'=>0)));

        $this->set(compact('systemTables', 'masterListOfFormats', 'listOfTrainedInternalAuditors','auditTypeMasters','standards','processes','riskAssessments'));
    }

    public function get_dept_clauses($val) {
        $this->layout = "ajax";

        $this->loadModel('Department');
        $department = $this->Department->find('first', array('conditions' => array('Department.id' => $val), 'fields' => array('id', 'clauses')));
        echo $department['Department']['clauses'];
        exit;
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {

        if (!$this->InternalAuditPlan->exists($id)) {
            throw new NotFoundException(__('Invalid internal audit plan'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['InternalAuditPlan']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['InternalAuditPlan']['note'] = htmlentities($this->request->data['InternalAuditPlan']['note']);

            unset($this->request->data['InternalAuditPlan']['schedule_date_to']);
            $dateRange = split('-', $this->request->data['InternalAuditPlan']['schedule_date_from']);
            $start_date = rtrim(ltrim($dateRange[0]));
            $end_date = rtrim(ltrim($dateRange[1]));
            
            $this->request->data['InternalAuditPlan']['schedule_date_from'] = date('Y-m-d',strtotime($start_date));
            $this->request->data['InternalAuditPlan']['schedule_date_to'] = date('Y-m-d',strtotime($end_date));

            if ($this->InternalAuditPlan->save($this->request->data, false)) {



                //Edit internal audit on timeline
                if ($this->request->data['InternalAuditPlan']['show_on_timeline']) {
                    $this->loadModel('Timeline');
                    $this->Timeline->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                    $this->Timeline->create();
                    $val = array();
                    $val['title'] = $this->request->data['InternalAuditPlan']['title'];
                    $val['message'] = htmlentities($this->request->data['InternalAuditPlan']['note']);
                    $val['start_date'] = $this->request->data['InternalAuditPlan']['schedule_date_from'];
                    $val['end_date'] = $this->request->data['InternalAuditPlan']['schedule_date_to'];
                    $val['internal_audit_plan_id'] = $this->InternalAuditPlan->id;
                    $val['prepared_by'] = $this->Session->read('User.id');
                    $val['approved_by'] = $this->Session->read('User.id');
                    $val['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                    $val['branchid'] = $this->request->data['InternalAuditPlan']['branchid'];
                    $val['departmentid'] = $this->request->data['InternalAuditPlan']['departmentid'];
                    $val['modified_by'] = $this->Session->read('User.id');
                    $val['system_table_id'] = $this->_get_system_table_id();
                    $this->Timeline->save($val, false);
                } else {
                    $this->loadModel('Timeline');
                    $this->Timeline->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                }
                if ($this->request->data['InternalAuditPlan']['notify_users']) {
                    //Edit internal audit plan on notification
                    $this->loadModel('Notification');
                    $notifications = $this->Notification->find('first', array('conditions' => array('internal_audit_plan_id' => $this->InternalAuditPlan->id)), false);
                    $notificationsId = $notifications['Notification']['id'];
                    $val = array();
                    $val['id'] = $notificationsId;
                    $val['title'] = $this->request->data['InternalAuditPlan']['title'];
                    $val['message'] = htmlentities($this->request->data['InternalAuditPlan']['note']);
                    $val['start_date'] = $this->request->data['InternalAuditPlan']['schedule_date_from'];
                    $val['end_date'] = $this->request->data['InternalAuditPlan']['schedule_date_to'];
                    $val['internal_audit_plan_id'] = $this->InternalAuditPlan->id;
                    $val['prepared_by'] = $this->Session->read('User.id');
                    $val['approved_by'] = $this->Session->read('User.id');
                    $val['publish'] = $this->request->data['InternalAuditPlan']['publish'];
                    $val['branchid'] = $this->request->data['InternalAuditPlan']['branchid'];
                    $val['departmentid'] = $this->request->data['InternalAuditPlan']['departmentid'];
                    $val['modified_by'] = $this->Session->read('User.id');
                    $val['system_table_id'] = $this->_get_system_table_id();
                    $this->Notification->save($val, false);
                } else {
                    $this->loadModel('Notification');
                    $this->Notification->deleteAll(array('internal_audit_plan_id' => $this->InternalAuditPlan->id), false);
                }
                if ($this->_show_approvals()) {
                    if ((isset($this->request->data['Approval'])) && ($this->request->data['InternalAuditPlan']['publish'] == 0 ) && ($this->request->data['Approval']['user_id'] != -1))
                    {
                        $this->loadModel('Approval');
                        $this->Approval->create();
                        $this->request->data['Approval']['model_name'] = 'InternalAuditPlan';
                        $this->request->data['Approval']['controller_name'] = $this->request->params['controller'];
                        $this->request->data['Approval']['from'] = $this->Session->read('User.id');
                        $this->request->data['Approval']['user_id'] = $this->request->data['Approval']['user_id'];
                        $this->request->data['Approval']['created_by'] = $this->Session->read('User.id');
                        $this->request->data['Approval']['modified_by'] = $this->Session->read('User.id');
                        $this->request->data['Approval']['record'] = $this->InternalAuditPlan->id;
                        $this->Approval->save($this->request->data['Approval']);
                    }
                }
                $this->Session->setFlash(__('The internal audit plan has been saved'));
                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The internal audit plan could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('InternalAuditPlan.' . $this->InternalAuditPlan->primaryKey => $id));
            $this->request->data = $this->InternalAuditPlan->find('first', $options);
        }
        $systemTables = $this->InternalAuditPlan->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormats = $this->InternalAuditPlan->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $listOfTrainedInternalAuditors = $this->InternalAuditPlan->ListOfTrainedInternalAuditor->find('list', array('conditions' => array('ListOfTrainedInternalAuditor.publish' => 1, 'ListOfTrainedInternalAuditor.soft_delete' => 0), 'recursive' => 0));
        $auditTypeMasters = $this->InternalAuditPlan->AuditTypeMaster->find('list', array('conditions' => array('AuditTypeMaster.publish' => 1, 'AuditTypeMaster.soft_delete' => 0), 'recursive' => 0));
        $standards = $this->InternalAuditPlan->Standard->find('list',array('conditions'=>array('Standard.publish'=>1,'Standard.soft_delete'=>0) ));
        $this->set(compact('systemTables', 'masterListOfFormats', 'listOfTrainedInternalAuditors','auditTypeMasters','standards'));
    }

    public function add_branches() {

        $this->set('branches', $this->InternalAuditPlan->InternalAuditPlanBranch->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0))));
        $this->set('employees', $this->InternalAuditPlan->InternalAuditPlanBranch->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0))));
        $listOfTrainedInternalAuditors = $this->InternalAuditPlan->ListOfTrainedInternalAuditor->find("list", array(
            "joins" => array(
                array(
                    "table" => "employees",
                    "alias" => "Employees",
                    "type" => "LEFT",
                    "conditions" => array(
                        "ListOfTrainedInternalAuditor.employee_id = Employees.id"
                    )
                )
            ), 'fields' => array('ListOfTrainedInternalAuditor.id', 'Employees.name'),
            'conditions' => array(
                'ListOfTrainedInternalAuditor.publish' => 1, 'ListOfTrainedInternalAuditor.soft_delete' => 0
            )
        ));
        $this->set('listOfTrainedInternalAuditors', $listOfTrainedInternalAuditors);

        $this->render('add_branches');
    }

    public function add_departments($i = null) {

        $this->set('branches', $this->InternalAuditPlan->InternalAuditPlanBranch->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0))));
        $this->set('employees', $this->InternalAuditPlan->InternalAuditPlanBranch->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0))));
        $this->set('departments', $this->InternalAuditPlan->InternalAuditPlanDepartment->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0))));
        $listOfTrainedInternalAuditors = $this->InternalAuditPlan->ListOfTrainedInternalAuditor->find("list", array(
            "joins" => array(
                array(
                    "table" => "employees",
                    "alias" => "Employees",
                    "type" => "LEFT",
                    "conditions" => array(
                        "ListOfTrainedInternalAuditor.employee_id = Employees.id"
                    )
                )
            ), 'fields' => array('ListOfTrainedInternalAuditor.id', 'Employees.name'),
            'conditions' => array('ListOfTrainedInternalAuditor.publish' => 1, 'ListOfTrainedInternalAuditor.soft_delete' => 0
            )
        ));
        $this->set('listOfTrainedInternalAuditors', $listOfTrainedInternalAuditors);
        $this->set('i', $i);
        $this->render('add_departments');
    }

    public function dashboard(){
        
        
        $month = $this->request->params['named']['month'];
        $key = base64_decode($this->request->params['named']['key']);
        $vars = json_decode(base64_decode($this->request->params['named']['vars']),true);
        $this->loadModel('InternalAuditPlanDepartment');
        
        if(isset($vars)){
            if($vars['type'] == 'branch'){
                $this->loadModel('Branch');
                $id = $this->Branch->find('first',array('fields'=>array('Branch.id','Branch.name'),'conditions'=>array('Branch.name'=>$key),'recursive'=>-1));
                $conditions = array('InternalAuditPlanDepartment.branch_id'=>$id['Branch']['id']);

            }elseif($vars['type'] == 'department'){
                $this->loadModel('Department');
                $id = $this->Department->find('first',array('fields'=>array('Department.id','Department.name'),'conditions'=>array('Department.name'=>$key),'recursive'=>-1));
                $conditions = array('InternalAuditPlanDepartment.department_id'=>$id['Department']['id']);

            }elseif($vars['type'] == 'employee'){
                $this->loadModel('Employee');
                $id = $this->Employee->find('first',array('fields'=>array('Employee.id','Employee.name'),'conditions'=>array('Employee.name'=>$key),'recursive'=>-1));
                $conditions = array('InternalAuditPlanDepartment.employee_id'=>$id['Employee']['id']);

            }elseif($vars['type'] == 'auditor'){
                $this->loadModel('ListOfTrainedInternalAuditor');
                $id = $this->ListOfTrainedInternalAuditor->find('first',array('fields'=>array('ListOfTrainedInternalAuditor.id','ListOfTrainedInternalAuditor.name'),'conditions'=>array('ListOfTrainedInternalAuditor.name'=>$key),'recursive'=>-1));
                $conditions = array('InternalAuditPlanDepartment.list_of_trained_internal_auditor_id'=>$id['ListOfTrainedInternalAuditor']['id']);
            }        
            
            if($vars['year']){
                $year_conditions = array('YEAR(InternalAuditPlanDepartment.start_time)'=>$vars['year']);
            }else{
                $year_conditions = array('YEAR(InternalAuditPlanDepartment.start_time)'=>date('Y'));
            }

            
        }
        if($month){
                $month_conditions = array('MONTH(InternalAuditPlanDepartment.start_time)'=>$month);
            }
        $final_conditions = array($conditions,$year_conditions,$month_conditions);
        $audits = $audit_number = $this->InternalAuditPlanDepartment->find('all',array(
                    'recursive'=>1,
                    // 'group'=>array('InternalAuditPlan.id'),
                    'conditions'=>array(
                        'InternalAuditPlanDepartment.publish'=>1,
                        'InternalAuditPlanDepartment.soft_delete'=>0,
                        $final_conditions
                    )));
        
        $this->set('internalAuditPlans',$audits);
    }

}

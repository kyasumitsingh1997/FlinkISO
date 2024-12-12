<?php

App::uses('AppController', 'Controller');

/**
 * Tasks Controller
 *
 * @property Task $Task
 */
class TasksController extends AppController {

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

        if($this->request->params['named']['project_activity_id'])$project_conditions = array('Task.project_activity_id'=>$this->request->params['named']['project_activity_id']);
        if($this->Session->read('User.is_mr') == false){
            $user_conditions = array('OR'=>array('Task.created_by' => $this->Session->read('User.id'),'Task.user_id'=>$this->Session->read('User.id')));
        }
        $conditions = $this->_check_request();
        $this->paginate = array('limit'=>100, 'order'=>array('Task.seq'=>'DESC'),'conditions'=>array($conditions,$project_conditions,$user_conditions));

        $this->Task->recursive = 0;
        $tasks = $this->paginate();
        $this->set('tasks', $tasks);
        $this->_get_count();
        return $tasks;
    }

    public function completed() {
        $conditions = $this->_check_request();

        if($this->request->params['named']['project_activity_id'])$project_conditions = array('Task.project_activity_id'=>$this->request->params['named']['project_activity_id']);
        
        $conditions = $this->_check_request();

        $this->paginate = array('order'=>array('Task.sr_no'=>'DESC'),'conditions'=>array($conditions,$project_conditions,'Task.task_status'=>1));

        $this->Task->recursive = 0;
        $this->set('tasks', $this->paginate());
        $this->_get_count();
        $this->render('index');
    }

    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function advanced_search() {
        $conditions = array();
        if ($this->request->query) {
            $searchArray = array();
            if ($this->request->query['strict_search'] == 0) {
                $searchKeys[] = $this->request->query['keywords'];
            } else {
                $searchKeys = explode(" ", $this->request->query['keywords']);
            }
            foreach ($searchKeys as $searchKey):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $searchArray[] = array('Task.' . $search => $searchKey);
                    else
                        $searchArray[] = array('Task.' . $search . ' like ' => '%' . $searchKey . '%');

                    endforeach;
                    endforeach;
                    if ($this->request->query['strict_search'] == 0)
                        $conditions[] = array('and' => array('OR' => $searchArray));
                    else
                        $conditions[] = array('or' => $searchArray);
                }

                if ($this->request->query['user_name'] != -1) {
                    $userConditions = array('Task.user_id' => $this->request->query['user_name']);
                    if ($this->request->query['strict_search'] == 0)
                        $conditions[] = array('and' => $userConditions);
                    else
                        $conditions[] = array('or' => $userConditions);
                }

                if ($this->request->query['task_type']) {
                    $typeConditions = array('Task.task_type' => $this->request->query['task_type']);
                    if ($this->request->query['strict_search'] == 0)
                        $conditions[] = array('and' => $typeConditions);
                    else
                        $conditions[] = array('or' => $typeConditions);
                }

                if ($this->request->query['task_status'] ==0 || $this->request->query['task_status']) {
                    $statusConditions = array('Task.task_status' => $this->request->query['task_status']);
                    if ($this->request->query['strict_search'] == 0)
                        $conditions[] = array('and' => $statusConditions);
                    else
                        $conditions[] = array('or' => $statusConditions);
                }
                
                if ($this->request->query['master_list_id'] != -1) {
                    $masterListIdConditions = array('Task.master_list_of_format_id' => $this->request->query['master_list_id']);
                    if ($this->request->query['strict_search'] == 0)
                        $conditions[] = array('and' => $masterListIdConditions);
                    else
                        $conditions[] = array('or' => $masterListIdConditions);
                }
                if ($this->request->query['schedule_id'] != '') {
                    $scheduleIdConditions[] = array('Task.schedule_id' => $this->request->query['schedule_id']);
                    if ($this->request->query['strict_search'] == 0)
                        $conditions[] = array('and' => array('or'=>$scheduleIdConditions));
                    else
                        $conditions[] = array('or' => $scheduleIdConditions);
                }
                if ($this->request->query['branch_list']) {
                    foreach ($this->request->query['branch_list'] as $branches):
                        $branchConditions[] = array('Task.branchid' => $branches);
                    endforeach;
                    if ($this->request->query['strict_search'] == 0)
                        $conditions[] = array('and' => array('or'=>$branchConditions));
                    else
                        $conditions[] = array('or' => $branchConditions);;
                }

                if (!$this->request->query['to-date'])
                    $this->request->query['to-date'] = date('Y-m-d');
                if ($this->request->query['from-date']) {
                    $conditions[] = array('Task.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'Task.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
                }
                $conditions =  $this->advance_search_common($conditions);
                
                if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
                    $onlyBranch = array('Task.branchid' => $this->Session->read('User.branch_id'));
                if ($this->Session->read('User.is_view_all') == 0)
                    $onlyOwn = array('Task.created_by' => $this->Session->read('User.id'));
                $conditions[] = array($onlyBranch, $onlyOwn);

                $this->Task->recursive = 0;
                $this->paginate = array('order' => array('Task.sr_no' => 'DESC'), 'conditions' => $conditions, 'Task.soft_delete' => 0);
                $this->set('tasks', $this->paginate());
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
        if (!$this->Task->exists($id)) {
            throw new NotFoundException(__('Invalid task'));
        }
        $userNames = $this->requestAction('App/get_usernames');
        $this->set('userNames', $userNames);
        $options = array('conditions' => array('Task.' . $this->Task->primaryKey => $id));
        $task = $this->Task->find('first', $options);
        
        if($this->Session->read('User')){
            if($this->Session->read('User.is_mr') == 0 && $this->Session->read('User.is_view_all') == 0){
                if($task['Task']['created_by'] != $this->Session->read('User.id') && $task['Task']['user_id'] != $this->Session->read('User.id')){
                    $this->Session->setFlash(__('You are not allowed to view this tasks'));
                    if ($this->params->action != 'access_denied')
                     $this->redirect(array('controller' => 'users','action' => 'access_denied'));
                }
            }    
        }
        
        

        // get last task update
        $last_task = $this->Task->TaskStatus->find('first',array('recursive'=>-1,'order'=>array('TaskStatus.task_date'=>'DESC'), 'conditions'=>array('TaskStatus.task_id'=>$task['Task']['id'])));
        

        

        $this->set(compact('task', 'employees'));
        if($task['Task']['revised_due_date'] == '1970-01-01' || $task['Task']['revised_due_date'] == NULL){
            if($task['Task']['end_date'] > date('Y-m-d')){
                $endDate = date('Y-m-d');
            }else{
                $endDate = $task['Task']['end_date'];
            }    
        }else{
            if($task['Task']['revised_due_date'] > date('Y-m-d')){
                $endDate = date('Y-m-d');
            }else{
                $endDate = $task['Task']['revised_due_date'];
            } 
        }
        
        if($endDate < date('Y-m-d',strtotime($last_task['TaskStatus']['task_date']))){            
            $endDate = date('Y-m-d',strtotime($last_task['TaskStatus']['task_date']));
        }else{            
            $endDate = $endDate;
        }

        while ($task['Task']['start_date'] <= $endDate) 
        {            
            if($task['Task']['schedule_id'] == '52487014-1448-45ae-82c3-4f1fc6c3268c'){
                $task_stauts[$task['Task']['start_date']] = $this->Task->TaskStatus->find('first',array(
                    'conditions'=>array(
                        'TaskStatus.task_date'=>$task['Task']['start_date'],
                        'TaskStatus.task_id'=>$task['Task']['id']),'recursive'=>-1));

                $task['Task']['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($task['Task']['start_date']))); 

            }elseif($task['Task']['schedule_id'] == '5248701d-1390-4782-9990-4f1fc6c3268c'){
                $task_stauts[$task['Task']['start_date']] = $this->Task->TaskStatus->find('first',array(
                    'conditions'=>array(
                        'TaskStatus.task_date between ? AND ?'=> array($task['Task']['start_date'],date("Y-m-d", strtotime("+1 week", strtotime($task['Task']['start_date'])))),
                        'TaskStatus.task_id'=>$task['Task']['id']),'recursive'=>-1));

                $task['Task']['start_date'] = date("Y-m-d", strtotime("+1 week", strtotime($task['Task']['start_date']))); 

            }elseif($task['Task']['schedule_id'] == '52487027-260c-4196-8062-543bn6c3268c'){
                $task_stauts[$task['Task']['start_date']] = $this->Task->TaskStatus->find('first',array(
                    'conditions'=>array(
                        'TaskStatus.task_date between ? AND ?'=> array($task['Task']['start_date'],date("Y-m-d", strtotime("+1 month", strtotime($task['Task']['start_date'])))),
                        'TaskStatus.task_id'=>$task['Task']['id']),'recursive'=>-1));

                $task['Task']['start_date'] = date("Y-m-d", strtotime("+1 month", strtotime($task['Task']['start_date'])));   

            }elseif($task['Task']['schedule_id'] == '52487033-b1a8-436f-b0a9-53a7q6c3268c'){
                $task_stauts[$task['Task']['start_date']] = $this->Task->TaskStatus->find('first',array(
                    'conditions'=>array(
                        'TaskStatus.task_date between ? AND ?'=> array($task['Task']['start_date'],date("Y-m-d", strtotime("+3 months", strtotime($task['Task']['start_date'])))),
                        'TaskStatus.task_id'=>$task['Task']['id']),'recursive'=>-1));

                $task['Task']['start_date'] = date("Y-m-d", strtotime("+3 months", strtotime($task['Task']['start_date'])));  

            }elseif($task['Task']['schedule_id'] == '530df9f4-fff8-454e-aa24-71f5b6329416'){
                $task_stauts[$task['Task']['start_date']] = $this->Task->TaskStatus->find('first',array(
                    'conditions'=>array(
                        'TaskStatus.task_date between ? AND ?'=> array($task['Task']['start_date'],date("Y-m-d", strtotime("+12 months", strtotime($task['Task']['start_date'])))),
                        'TaskStatus.task_id'=>$task['Task']['id']),'recursive'=>-1));

                $task['Task']['start_date'] = date("Y-m-d", strtotime("+12 months", strtotime($task['Task']['start_date'])));  

            }elseif($task['Task']['schedule_id'] == '56d15631-8f34-40bb-a577-03a2db1e6cf9'){
                $task_stauts[$task['Task']['start_date']] = $this->Task->TaskStatus->find('first',array(
                    'conditions'=>array(
                        'TaskStatus.task_date between ? AND ?'=> array($task['Task']['start_date'],date("Y-m-d", strtotime("+6 months", strtotime($task['Task']['start_date'])))),
                        'TaskStatus.task_id'=>$task['Task']['id']),'recursive'=>-1));

                $task['Task']['start_date'] = date("Y-m-d", strtotime("+6 months", strtotime($task['Task']['start_date'])));    
            }elseif($task['Schedule']['name'] == 'None'){
                $task_stauts[$task['Task']['start_date']] = $this->Task->TaskStatus->find('first',array(
                    'conditions'=>array(
                        'TaskStatus.task_date'=> array($task['Task']['start_date'],$task['Task']['end_date']),
                        'TaskStatus.task_id'=>$task['Task']['id']),'recursive'=>-1));

                $task['Task']['start_date'] = date("Y-m-d", strtotime("+1 day", strtotime($task['Task']['start_date'])));    
            }              
        }
        
        $this->set('task_performed',$task_stauts);
        return array($task,$task_stauts);
        exit;
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
    public function add_ajax() {

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }   
        
        if($this->request->params['named']['objective_id']){
            $objective = $this->Task->Process->Objective->find('first',array('recursive'=>2, 'conditions'=>array('Objective.id'=>$this->request->params['named']['objective_id'])));            
            $this->set('objective',$objective);
        }

        if ($this->request->is('post')) {
            $this->request->data['Task']['system_table_id'] = $this->_get_system_table_id();
            if(!isset($this->request->data['Task']['task_type']))$this->request->data['Task']['task_status'] = 0;
            $this->request->data['Task']['task_status'] = 0;
            $this->Task->create();

            unset($this->request->data['Task']['end_date']);
            $dateRange = split('-', $this->request->data['Task']['start_date']);
            $start_date = rtrim(ltrim($dateRange[0]));
            $end_date = rtrim(ltrim($dateRange[1]));
            
            $this->request->data['Task']['start_date'] = date('Y-m-d',strtotime($start_date));
            $this->request->data['Task']['end_date'] = date('Y-m-d',strtotime($end_date));

            if ($this->Task->save($this->request->data, false)) {

                $this->Session->setFlash(__('The task has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true){
                    if($this->request->data['Task']['project_id']){
                        $this->redirect(array('controller'=>'projects', 'action' => 'updatealert', $this->request->data['Task']['project_id']));
                    }else{
                        $this->redirect(array('action' => 'view', $this->Task->id));
                    }
                    
                }
                else
                    $this->redirect(str_replace('/lists', '/add_ajax', $this->referer()));
            } else {
                $this->Session->setFlash(__('The task could not be saved. Please, try again.'));
            }
        }
        $miletones = null;
        $sequence = 0;    
        $this->set('sequence',0);
        $processes = $this->Task->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $projects = $this->Task->Project->find('list', array('conditions' => array('Project.publish' => 1, 'Project.soft_delete' => 0)));
        $customerComplaints = $this->Task->CustomerComplaint->find('list',array('conditions'=>array('CustomerComplaint.soft_delete'=>0)));

        if($this->request->params['named']['project_id'])$milestones = $this->Task->Project->Milestone->find('list', array('conditions' => array('Milestone.project_id'=>$this->request->params['named']['project_id'], 'Milestone.publish' => 1, 'Milestone.soft_delete' => 0)));
        $projectActivities = $this->Task->ProjectActivity->find('list', array('conditions' => array('ProjectActivity.publish' => 1, 'ProjectActivity.soft_delete' => 0)));
        $masterListOfFormats = $this->Task->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0, 'MasterListOfFormat.archived' => 0)));
        $users = $this->requestAction('App/get_usernames');
        $schedules = $this->Task->Schedule->find('list', array('conditions' => array('Schedule.publish' => 1, 'Schedule.soft_delete' => 0)));
        
        $this->set(compact('processes', 'projects', 'milestones', 'projectActivities', 'masterListOfFormats','schedules','users','customerComplaints'));
        
        if($this->request->params['named']['project_activity_id']){
            $project_activity_id = $this->request->params['named']['project_activity_id'];
        }elseif($this->request->data['Task']['project_activity_id'] != -1 && $this->request->data['Task']['project_activity_id'] != NULL){
            $project_activity_id = $this->request->data['Task']['project_activity_id'];
        }
        if($project_activity_id){
            $projectActivity = $this->Task->ProjectActivity->find('first', array('conditions' => array('ProjectActivity.id' => $project_activity_id)));
            $this->set('projectActivity',$projectActivity); 
            $project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$projectActivity['ProjectActivity']['project_id']));
            $this->set('project_details',$project_details);
            $project['Project'] = $projectActivity['Project']; 
            $this->set('project',$project);  
            
        }

        if($this->request->params['named']['project_id']){
            $project_id = $this->request->params['named']['project_id'];
        }elseif($this->request->data['Task']['project_id'] != -1 && $this->request->data['Task']['project_id'] != NULL){
            $project_id = $this->request->data['Task']['project_id'];
        }
        
        if($project_id){
            $project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$project_id));
            $this->set('project_details',$project_details);
            $project = $project_details[0]; 
            $this->set('project',$project);            
        }

        if($this->request->params['named']['process_id']){
            $processDates = $this->Task->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.process_id'=>$this->request->params['named']['process_id']),'fields'=>array('ProcessTeam.start_date','ProcessTeam.end_date')));
            $processTeams = $this->Task->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.process_id'=>$this->request->params['named']['process_id'])));
            $count = $this->Task->find('count',array('conditions'=>array('Task.process_id'=>$this->request->params['named']['process_id'])));
            $this->set('sequence',$count + 1);
            $this->set('processTeams',$processTeams);
            $this->set('processDates',$processDates);            
        }        

        $taskTypes = $this->Task->customArray['task_type'];
        $this->set('taskTypes',$taskTypes);
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Task->exists($id)) {
            throw new NotFoundException(__('Invalid task'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Task']['system_table_id'] = $this->_get_system_table_id();

            unset($this->request->data['Task']['end_date']);
            $dateRange = split('-', $this->request->data['Task']['start_date']);
            $start_date = rtrim(ltrim($dateRange[0]));
            $end_date = rtrim(ltrim($dateRange[1]));
            
            $this->request->data['Task']['start_date'] = date('Y-m-d',strtotime($start_date));
            $this->request->data['Task']['end_date'] = date('Y-m-d',strtotime($end_date));


            if ($this->Task->save($this->request->data, false)) {            
                $this->Session->setFlash(__('The task has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The task could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Task.' . $this->Task->primaryKey => $id));
            $this->request->data = $this->Task->find('first', $options);
        }
        $processes = $this->Task->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $projects = $this->Task->Project->find('list', array('conditions' => array('Project.publish' => 1, 'Project.soft_delete' => 0)));
        if($this->request->data['Task']['project_id'])$milestones = $this->Task->Project->Milestone->find('list', array('conditions' => array('Milestone.project_id'=>$this->request->data['Task']['project_id'], 'Milestone.publish' => 1, 'Milestone.soft_delete' => 0)));
        $projectActivities = $this->Task->ProjectActivity->find('list', array('conditions' => array('ProjectActivity.publish' => 1, 'ProjectActivity.soft_delete' => 0)));
        $masterListOfFormats = $this->Task->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0, 'MasterListOfFormat.archived' => 0)));
        $users = $this->requestAction('App/get_usernames');
        $schedules = $this->Task->Schedule->find('list', array('conditions' => array('Schedule.publish' => 1, 'Schedule.soft_delete' => 0)));
        $this->set(compact('processes', 'projects', 'milestones', 'projectActivities', 'masterListOfFormats','schedules','users'));
        
        if($this->request->data['Task']['project_activity_id'] != -1 && $this->request->data['Task']['project_activity_id'] != NULL){
            $projectActivity = $this->Task->ProjectActivity->find('first', array('conditions' => array('ProjectActivity.id' => $this->request->data['Task']['project_activity_id'])));
            $this->set('projectActivity',$projectActivity); 
            $project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$projectActivity['ProjectActivity']['project_id']));
            $this->set('project_details',$project_details);
            $project['Project'] = $projectActivity['Project']; 
            $this->set('project',$project);            
        }

        if($this->request->data['Task']['task_type'] == 1){
            $processDates = $this->Task->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.process_id'=>$this->request->data['Task']['process_id']),'fields'=>array('ProcessTeam.start_date','ProcessTeam.end_date')));
            $processTeams = $this->Task->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.process_id'=>$this->request->data['Task']['process_id'])));
            $count = $this->Task->find('count',array('conditions'=>array('Task.process_id'=>$this->request->data['Task']['process_id'])));
            $this->set('sequence',$count + 1);
            $this->set('processTeams',$processTeams);
            $this->set('processDates',$processDates);          
        } 

        if($this->request->data['Task']['project_id'] != -1 && $this->request->data['Task']['project_id'] != NULL){
            $project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$this->request->data['Task']['project_id']));
            $this->set('project_details',$project_details);
            $project = $project_details[0]; 
            $this->set('project',$project);            
        }
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->Task->exists($id)) {
            throw new NotFoundException(__('Invalid task'));
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
            $this->request->data['Task']['system_table_id'] = $this->_get_system_table_id();

            unset($this->request->data['Task']['end_date']);
            $dateRange = split('-', $this->request->data['Task']['start_date']);
            $start_date = rtrim(ltrim($dateRange[0]));
            $end_date = rtrim(ltrim($dateRange[1]));
            
            $this->request->data['Task']['start_date'] = date('Y-m-d',strtotime($start_date));
            $this->request->data['Task']['end_date'] = date('Y-m-d',strtotime($end_date));


            if ($this->Task->save($this->request->data, false)) {

                $this->Session->setFlash(__('The task has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The task could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Task.' . $this->Task->primaryKey => $id));
            $this->request->data = $this->Task->find('first', $options);
        }
        $processes = $this->Task->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $projects = $this->Task->Project->find('list', array('conditions' => array('Project.publish' => 1, 'Project.soft_delete' => 0)));
        if($this->request->data['Task']['project_id'])$milestones = $this->Task->Project->Milestone->find('list', array('conditions' => array('Milestone.project_id'=>$this->request->data['Task']['project_id'], 'Milestone.publish' => 1, 'Milestone.soft_delete' => 0)));
        $projectActivities = $this->Task->ProjectActivity->find('list', array('conditions' => array('ProjectActivity.publish' => 1, 'ProjectActivity.soft_delete' => 0)));
        $masterListOfFormats = $this->Task->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0, 'MasterListOfFormat.archived' => 0)));
        $users = $this->requestAction('App/get_usernames');
        $schedules = $this->Task->Schedule->find('list', array('conditions' => array('Schedule.publish' => 1, 'Schedule.soft_delete' => 0)));
        $this->set(compact('processes', 'projects', 'milestones', 'projectActivities', 'masterListOfFormats','schedules','users'));
        
        if($this->request->data['Task']['project_activity_id'] != -1 && $this->request->data['Task']['project_activity_id'] != NULL){
            $projectActivity = $this->Task->ProjectActivity->find('first', array('conditions' => array('ProjectActivity.id' => $this->request->data['Task']['project_activity_id'])));
            $this->set('projectActivity',$projectActivity); 
            $project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$projectActivity['ProjectActivity']['project_id']));
            $this->set('project_details',$project_details);
            $project['Project'] = $projectActivity['Project']; 
            $this->set('project',$project);            
        }

        if($this->request->data['Task']['task_type'] == 1){
            $processDates = $this->Task->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.process_id'=>$this->request->data['Task']['process_id']),'fields'=>array('ProcessTeam.start_date','ProcessTeam.end_date')));
            $processTeams = $this->Task->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.process_id'=>$this->request->data['Task']['process_id'])));
            $count = $this->Task->find('count',array('conditions'=>array('Task.process_id'=>$this->request->data['Task']['process_id'])));
            $this->set('sequence',$count + 1);
            $this->set('processTeams',$processTeams);
            $this->set('processDates',$processDates);          
        } 

        if($this->request->data['Task']['project_id'] != -1 && $this->request->data['Task']['project_id'] != NULL){
            $project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$this->request->data['Task']['project_id']));
            $this->set('project_details',$project_details);
            $project = $project_details[0]; 
            $this->set('project',$project);            
        }
    }

    public function get_task($id = null) {
        $this->loadModel('Task');
        $this->loadModel('TaskStatus');
        if ($this->request->is('post')) {
            foreach ($this->request->data['TaskStatus'] as $taskStatus) {
                if (isset($taskStatus['task_performed']) && $taskStatus['task_performed'] > 0) {
                    if (!$taskStatus['id'])
                        $this->TaskStatus->create();
                    $taskStatus['publish'] = 1;
                    $taskStatus['task_date'] = date("Y-m-d");
                    $this->TaskStatus->save($taskStatus, false);

                    if (isset($taskStatus['task_status']) && $taskStatus['task_performed'] == 1 && $taskStatus['task_status'] == 1) {
                        $this->TaskStatus->Task->read(null,$taskStatus['task_id']);
                        $this->TaskStatus->Task->set(array('task_status'=>1,'task_completion_date'=>date('Y-m-d')));
                        $this->TaskStatus->Task->save();
                    }
                }
            }
            $this->Session->setFlash(__('The task status has been saved'));
        }
        $onlyBranch = null;
        $onlyOwn = null;
        $condition1 = null;
        $condition2 = null;
        $condition3 = null;
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('Task.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('Task.created_by' => $this->Session->read('User.id'));
        if ($this->Session->read('User.is_mr') == 0) {
            $condition3 = array('Task.user_id' => $this->Session->read('User.id'));
        }
        $finalCond = array('OR' => array($onlyBranch, $onlyOwn, $condition3));
        if ($this->request->params['named']) {
            if ($this->request->params['named']['published'] == null)
                $condition1 = null;
            else
                $condition1 = array('Task.publish' => $this->request->params['named']['published']);
            if ($this->request->params['named']['soft_delete'] == null)
                $condition2 = null;
            else
                $condition2 = array('Task.soft_delete' => $this->request->params['named']['soft_delete']);
            if ($this->request->params['named']['soft_delete'] == null)
                $conditions = array($onlyBranch, $onlyOwn, $condition1, $condition3, 'Task.soft_delete' => 0);
            else
                $conditions = array($condition1, $condition2, $finalCond);
        }else {
            $conditions = array($finalCond, 'Task.soft_delete' => 0);
        }
        $count = 0;
        $options = array(
           'fields'=>array('Task.id','Task.sr_no','Task.name','Task.process_id', 'Task.start_date','Task.end_date', 'Task.master_list_of_format_id','Task.user_id','Task.description','Task.task_type','Task.schedule_id','Task.task_status','Task.publish','Task.record_status','Task.status_user_id',
            'Task.soft_delete','Task.branchid','Task.departmentid','Task.company_id','Task.system_table_id','User.id','User.name','Schedule.id','Schedule.name','Schedule.sr_no',
            ),
           'order' => array('Task.sr_no' => 'DESC'),
           'conditions' => array($conditions, 'Task.task_status'=>0, 'OR'=>array('Task.task_type'=>0,'Task.task_type '=>NULL)));
        $tasks = $this->Task->find('all', $options);
        if($tasks){
         foreach ($tasks as $key => $task) {
            $count++;
            $test = $this->TaskStatus->find('first', array('order' => array('TaskStatus.sr_no' => 'DESC'), 'conditions' => array('TaskStatus.task_id' => $task['Task']['id'])));
            if (count($test)) {
                if ($task['Schedule']['sr_no'] == 1) {
                    if (date('Y-m-d', strtotime($test['TaskStatus']['created'])) == date('Y-m-d')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 2) {

                    if (date('W', strtotime($test['TaskStatus']['created'])) == date('W')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 4) {

                    if (date('m', strtotime($test['TaskStatus']['created'])) == date('m')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 5) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 7) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 8) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 9) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }
            }
        }
    }
    $this->set('editId', $id);
    $this->set(compact('tasks', 'count'));
    if($this->request->params['named']['render'] != 'no')$this->render('/Elements/task');
    return $count;
}

public function get_project_task($id = null) {
    $this->loadModel('Task');
    $this->loadModel('TaskStatus');
    if ($this->request->is('post')) {
        foreach ($this->request->data['TaskStatus'] as $taskStatus) {
            if (isset($taskStatus['task_performed']) && $taskStatus['task_performed'] > 0) {
                if (!$taskStatus['id'])
                    $this->TaskStatus->create();
                $taskStatus['publish'] = 1;
                $taskStatus['task_date'] = date("Y-m-d");
                $this->TaskStatus->save($taskStatus, false);

                    if (isset($taskStatus['task_status']) && $taskStatus['task_performed'] == 1 && $taskStatus['task_status'] == 1) {
                        $this->TaskStatus->Task->read(null,$taskStatus['task_id']);
                        $this->TaskStatus->Task->set('task_status',1);
                        $this->TaskStatus->Task->save();
                    }
                }
            }
            $this->Session->setFlash(__('The task status has been saved'));
        }
    $onlyBranch = null;
    $onlyOwn = null;
    $condition1 = null;
    $condition2 = null;
    $condition3 = null;
    if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
        $onlyBranch = array('Task.branch_id' => $this->Session->read('User.branch_id'));
    if ($this->Session->read('User.is_view_all') == 0)
        $onlyOwn = array('Task.created_by' => $this->Session->read('User.id'));
    if ($this->Session->read('User.is_mr') == 0) {
        $condition3 = array('Task.user_id' => $this->Session->read('User.id'));
    }
    $finalCond = array('OR' => array($onlyBranch, $onlyOwn, $condition3));
    if ($this->request->params['named']) {
        if ($this->request->params['named']['published'] == null)
            $condition1 = null;
        else
            $condition1 = array('Task.publish' => $this->request->params['named']['published']);
        if ($this->request->params['named']['soft_delete'] == null)
            $condition2 = null;
        else
            $condition2 = array('Task.soft_delete' => $this->request->params['named']['soft_delete']);
        if ($this->request->params['named']['soft_delete'] == null)
            $conditions = array($onlyBranch, $onlyOwn, $condition1, $condition3, 'Task.soft_delete' => 0);
        else
            $conditions = array($condition1, $condition2, $finalCond);
    }else {
        $conditions = array($finalCond, 'Task.soft_delete' => 0);
    }
    $count = 0;
    $options = array(
        'fields'=>array('Task.id','Task.sr_no','Task.name','Task.process_id', 'Task.start_date','Task.end_date', 
            'Task.master_list_of_format_id','Task.user_id','Task.description','Task.task_type','Task.schedule_id','Task.publish','Task.record_status','Task.status_user_id','Task.task_status',
            'Task.soft_delete','Task.branchid','Task.departmentid','Task.company_id','Task.system_table_id','User.id','User.name',
            'Schedule.id','Schedule.name','Schedule.sr_no','ProjectActivity.id','ProjectActivity.title,ProjectActivity.project_id'
            ),
        'order' => array('Task.sr_no' => 'DESC'),
        'conditions' => array($conditions, 'Task.task_status'=>0, 'Task.task_type'=>2,  
            'OR'=>array('Task.end_date >= ' => date('Y-m-d'),'Task.revised_due_date >= ' => date('Y-m-d'))
        ));
    $tasks = $this->Task->find('all', $options);
    if($tasks){
        foreach ($tasks as $key => $task) {
            // get project name
            $project = $this->Task->ProjectActivity->Project->find('first',array(
                'recursive'=>-1,
                'fields'=>array('Project.id','Project.title'),
                'conditions'=>array('Project.id'=>$task['ProjectActivity']['project_id'])));
            
            $tasks[$key]['Project'] = $project['Project'];
            $count++;
            $test = $this->TaskStatus->find('first', array('order' => array('TaskStatus.sr_no' => 'DESC'), 'conditions' => array('TaskStatus.task_id' => $task['Task']['id'])));
            if (count($test)) {
                if ($task['Schedule']['sr_no'] == 1) {
                    if (date('Y-m-d', strtotime($test['TaskStatus']['created'])) == date('Y-m-d')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 2) {

                    if (date('W', strtotime($test['TaskStatus']['created'])) == date('W')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 4) {

                    if (date('m', strtotime($test['TaskStatus']['created'])) == date('m')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 5) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 7) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 8) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 9) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }
            }
        }
    }
    $this->set('editId', $id);
    $this->set(compact('tasks', 'count'));
    if($this->request->params['named']['render'] != 'no')$this->render('/Elements/projecttask');
    return $count;
}

public function get_process_task($id = null) {
    $this->loadModel('Task');
    $this->loadModel('TaskStatus');
    if ($this->request->is('post')) {
        foreach ($this->request->data['TaskStatus'] as $taskStatus) {
            if (isset($taskStatus['task_performed']) && $taskStatus['task_performed'] > 0) {
                if (!$taskStatus['id'])
                    $this->TaskStatus->create();
                $taskStatus['publish'] = 1;
                $taskStatus['task_date'] = date("Y-m-d");
                $this->TaskStatus->save($taskStatus, false);

                    if (isset($taskStatus['task_status']) && $taskStatus['task_performed'] == 1 && $taskStatus['task_status'] == 1) {
                        $this->TaskStatus->Task->read(null,$taskStatus['task_id']);
                        $this->TaskStatus->Task->set('task_status',1);
                        $this->TaskStatus->Task->save();
                    }
                }
            }
            $this->Session->setFlash(__('The task status has been saved'));
        }
    $onlyBranch = null;
    $onlyOwn = null;
    $condition1 = null;
    $condition2 = null;
    $condition3 = null;
    if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
        $onlyBranch = array('Task.branch_id' => $this->Session->read('User.branch_id'));
    if ($this->Session->read('User.is_view_all') == 0)
        $onlyOwn = array('Task.created_by' => $this->Session->read('User.id'));
    if ($this->Session->read('User.is_mr') == 0) {
        $condition3 = array('Task.user_id' => $this->Session->read('User.id'));
    }
    $finalCond = array('OR' => array($onlyBranch, $onlyOwn, $condition3));
    if ($this->request->params['named']) {
        if ($this->request->params['named']['published'] == null)
            $condition1 = null;
        else
            $condition1 = array('Task.publish' => $this->request->params['named']['published']);
        if ($this->request->params['named']['soft_delete'] == null)
            $condition2 = null;
        else
            $condition2 = array('Task.soft_delete' => $this->request->params['named']['soft_delete']);
        if ($this->request->params['named']['soft_delete'] == null)
            $conditions = array($onlyBranch, $onlyOwn, $condition1, $condition3, 'Task.soft_delete' => 0);
        else
            $conditions = array($condition1, $condition2, $finalCond);
    }else {
        $conditions = array($finalCond, 'Task.soft_delete' => 0);
    }
    $count = 0;
    $options = array(
        'fields'=>array('Task.id','Task.sr_no','Task.name','Task.process_id', 'Task.start_date','Task.end_date','Task.rag_status','Task.revised_due_date',
            'Task.master_list_of_format_id','Task.user_id','Task.description','Task.task_type','Task.schedule_id','Task.publish','Task.record_status','Task.status_user_id','Task.task_status',
            'Task.soft_delete','Task.branchid','Task.departmentid','Task.company_id','Task.system_table_id','User.id','User.name',
            'Schedule.id','Schedule.name','Schedule.sr_no','ProjectActivity.id','ProjectActivity.title'
            ),
        'order' => array('Task.end_date' => 'ASC'),
        'conditions' => array($conditions, 'Task.task_status'=>0, 'Task.task_type'=>1, ));
    $tasks = $this->Task->find('all', $options);
    if($tasks){
        foreach ($tasks as $key => $task) {
            $count++;
            $test = $this->TaskStatus->find('first', array('order' => array('TaskStatus.sr_no' => 'DESC'), 'conditions' => array('TaskStatus.task_id' => $task['Task']['id'])));
            if ($this->_count($test)) {
                if ($task['Schedule']['sr_no'] == 1) {
                    if (date('Y-m-d', strtotime($test['TaskStatus']['created'])) == date('Y-m-d')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 2) {

                    if (date('W', strtotime($test['TaskStatus']['created'])) == date('W')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 4) {

                    if (date('m', strtotime($test['TaskStatus']['created'])) == date('m')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 5) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 7) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 8) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 9) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }
            }
        }
    }
    $this->set('editId', $id);
    $this->set(compact('tasks', 'count'));
    if($this->request->params['named']['render'] != 'no')$this->render('/Elements/processtask');
    return $count;
}


public function get_cc_task($id = null) {
    $this->loadModel('Task');
    $this->loadModel('TaskStatus');
    if ($this->request->is('post')) {
        // Configure::write('debug',1);
        // debug($this->request->data);
        foreach ($this->request->data['TaskStatus'] as $taskStatus) {
            if (isset($taskStatus['task_performed']) && $taskStatus['task_performed'] > 0) {
                if (!$taskStatus['id'])
                    $this->TaskStatus->create();
                    $taskStatus['publish'] = 1;
                    $taskStatus['task_date'] = date("Y-m-d");
                    $this->TaskStatus->save($taskStatus, false);
                    
                    // debug($taskStatus);
                    if (isset($taskStatus['Task']['task_status']) && $taskStatus['task_performed'] != '') {
                        // $this->TaskStatus->Task->read(null,$taskStatus['task_id']);
                        // $this->TaskStatus->Task->set('task_status',$taskStatus['Task']['task_status']);
                        // $this->TaskStatus->Task->save();
                        $t = $this->TaskStatus->Task->find('first',array('conditions'=>array('Task.id'=>$taskStatus['task_id']),'recursive'=>-1));
                        $nt = $t['Task'];
                        $nt['task_status'] = $taskStatus['Task']['task_status'];
                        $nt['task_completion_date'] = date('Y-m-d h:i:s');
                        debug($nt);
                        $this->TaskStatus->Task->create();
                        $this->TaskStatus->Task->save($nt,false);
                        $t = $nt = null;
                    }
                }
            }
            $this->Session->setFlash(__('The task status has been saved'));            
            // exit;
        }
        
    $onlyBranch = null;
    $onlyOwn = null;
    $condition1 = null;
    $condition2 = null;
    $condition3 = null;
    if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
        $onlyBranch = array('Task.branch_id' => $this->Session->read('User.branch_id'));
    if ($this->Session->read('User.is_view_all') == 0)
        $onlyOwn = array('Task.created_by' => $this->Session->read('User.id'));
    if ($this->Session->read('User.is_mr') == 0) {
        $condition3 = array('Task.user_id' => $this->Session->read('User.id'));
    }
    $finalCond = array('OR' => array($onlyBranch, $onlyOwn, $condition3));
    if ($this->request->params['named']) {
        if ($this->request->params['named']['published'] == null)
            $condition1 = null;
        else
            $condition1 = array('Task.publish' => $this->request->params['named']['published']);
        if ($this->request->params['named']['soft_delete'] == null)
            $condition2 = null;
        else
            $condition2 = array('Task.soft_delete' => $this->request->params['named']['soft_delete']);
        if ($this->request->params['named']['soft_delete'] == null)
            $conditions = array($onlyBranch, $onlyOwn, $condition1, $condition3, 'Task.soft_delete' => 0);
        else
            $conditions = array($condition1, $condition2, $finalCond);
    }else {
        $conditions = array($finalCond, 'Task.soft_delete' => 0);
    }
    $count = 0;
    $options = array(
        'fields'=>array('Task.id','Task.sr_no','Task.name','Task.customer_complaint_id', 'Task.start_date','Task.end_date', 
            'Task.master_list_of_format_id','Task.user_id','Task.description','Task.task_type','Task.schedule_id','Task.publish','Task.record_status','Task.status_user_id','Task.task_status',
            'Task.soft_delete','Task.branchid','Task.departmentid','Task.company_id','Task.system_table_id','User.id','User.name',
            'Schedule.id','Schedule.name','Schedule.sr_no','CustomerComplaint.id'
            ),
        'order' => array('Task.sr_no' => 'DESC'),
        'conditions' => array($conditions, 'Task.task_status'=>array(0,2), 'Task.task_type'=>3,  
            'OR'=>array('Task.end_date >= ' => date('Y-m-d'),'Task.revised_due_date >= ' => date('Y-m-d'))
        ));
    $tasks = $this->Task->find('all', $options);
    if($tasks){
        foreach ($tasks as $key => $task) {
            // get project name
            $customerComplaint = $this->Task->CustomerComplaint->find('first',array(
                'recursive'=>-1,
                // 'fields'=>array('CustomerComplaint.id','CustomerComplaint.name'),
                'conditions'=>array('CustomerComplaint.id'=>$task['Task']['customer_complaint_id'])));
            
            $tasks[$key]['CustomerComplaint'] = $customerComplaint['CustomerComplaint'];
            $count++;
            $test = $this->TaskStatus->find('first', array('order' => array('TaskStatus.sr_no' => 'DESC'), 'conditions' => array('TaskStatus.task_id' => $task['Task']['id'])));
            if ($this->_count($test)) {
                if ($task['Schedule']['sr_no'] == 1) {
                    if (date('Y-m-d', strtotime($test['TaskStatus']['created'])) == date('Y-m-d')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 2) {

                    if (date('W', strtotime($test['TaskStatus']['created'])) == date('W')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 4) {

                    if (date('m', strtotime($test['TaskStatus']['created'])) == date('m')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                } else if ($task['Schedule']['sr_no'] == 5) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 7) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 8) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }else if ($task['Schedule']['sr_no'] == 9) {

                    if (date('y', strtotime($test['TaskStatus']['created'])) == date('y')) {
                        $tasks[$key]['TaskStatus'] = $test['TaskStatus'];
                    } else {
                        $tasks[$key]['TaskStatus'] = array();
                    }
                }
            }
        }
    }
    $this->set('editId', $id);
    $this->set(compact('tasks', 'count'));
    if($this->request->params['named']['render'] != 'no')$this->render('/Elements/cctask');    
    return $count;
}

public function get_task_name($taskName = null, $id = null) {
    if ($taskName) {
        if ($id) {
            $tasks = $this->Task->find('all', array('conditions' => array('Task.name' => $taskName, 'Task.id !=' => $id)));
        } else {
            $tasks = $this->Task->find('all', array('conditions' => array('Task.name' => $taskName)));
        }
        if ($this->_count($tasks)) {
            echo "Task name already exists, Task name should be unique";
        }
    }
    exit;
}

    public function task_ajax(){
        $this->loadModel('SystemTable');
        $this->loadModel('FileUpload');
        $task_approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'task_statuses'),'fields'=>array('SystemTable.id'),'recursive'=>-1));
        $task_approval_files = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$task_approval_system_table['SystemTable']['id'])));
        $this->set('task_approval_files',$task_approval_files);        
    }

    public function project_task_ajax(){
        $this->loadModel('SystemTable');
        $this->loadModel('FileUpload');
        $task_approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'task_statuses'),'fields'=>array('SystemTable.id'),'recursive'=>-1));
        $task_approval_files = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$task_approval_system_table['SystemTable']['id'])));
        $this->set('task_approval_files',$task_approval_files);        
    }

    public function task_ajax_file_count(){
        $this->loadModel('SystemTable');
        $this->loadModel('FileUpload');
        $task_approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'task_statuses'),'fields'=>array('SystemTable.id'),'recursive'=>-1));
        $task_approval_files = $this->FileUpload->find('count',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$task_approval_system_table['SystemTable']['id'])));
        if(!$task_approval_files)$task_approval_files = 0;
        $this->set('task_approval_files_count',$task_approval_files);
    }
    public function project_task_ajax_file_count(){
        $this->loadModel('SystemTable');
        $this->loadModel('FileUpload');
        $project_task_approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'task_statuses'),'fields'=>array('SystemTable.id'),'recursive'=>-1));
        $project_task_approval_files = $this->FileUpload->find('count',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$project_task_approval_system_table['SystemTable']['id'])));
        $this->set('project_task_approval_files_count',$project_task_approval_files);
    }

    public function customerComplaint_task_ajax_file_count(){
        $this->loadModel('SystemTable');
        $this->loadModel('FileUpload');
        $project_task_approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'task_statuses'),'fields'=>array('SystemTable.id'),'recursive'=>-1));
        $project_task_approval_files = $this->FileUpload->find('count',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$project_task_approval_system_table['SystemTable']['id'])));
        $this->set('task_approval_files_count',$project_task_approval_files);
    }

    public function process_task_ajax(){
        $this->loadModel('SystemTable');
        $this->loadModel('FileUpload');
        $task_approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'task_statuses'),'fields'=>array('SystemTable.id'),'recursive'=>-1));
        $task_approval_files = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$task_approval_system_table['SystemTable']['id'])));
        $this->set('task_approval_files',$task_approval_files);        
    }

    public function process_task_ajax_file_count(){
        $this->loadModel('SystemTable');
        $this->loadModel('FileUpload');
        $process_task_approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'task_statuses'),'fields'=>array('SystemTable.id'),'recursive'=>-1));
        $process_task_approval_files = $this->FileUpload->find('count',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$process_task_approval_system_table['SystemTable']['id'])));
        $this->set('process_task_approval_files_count',$process_task_approval_files);
    }

    public function _taskcorn(){
        $tasks = $this->Task->find('list',array('conditions'=>array('Task.task_status'=>0)));
        foreach ($tasks as $key=>$value) {
            $status = $this->requestAction('tasks/view/'.$key);
            $j = 0;
            $per = 0;
           
            $performed[$status[0]['Task']['user_id']][$value]['performed'] = 0;
            $performed[$status[0]['Task']['user_id']][$value]['not_performed'] = 0;
            foreach ($status[1] as $date => $TaskStatus) {

                if(isset($TaskStatus['TaskStatus']) && $TaskStatus['TaskStatus']['task_performed'] == 1){
                   $performed[$status[0]['Task']['user_id']][$value]['performed']  = $performed[$key]['performed'] + 1;
                }else{
                    $performed[$status[0]['Task']['user_id']][$value]['not_performed']  = $performed[$key]['not_performed'] + 1;
                }
                $rag_status = null;
                if(!empty($TaskStatus) && $TaskStatus['TaskStatus']['task_performed'] == 1){
                    $j++;
                }else{
                    $i ++;
                }
                $per = 100 * $j / $this->_count($status[1]);
                
                // set rag status
                if($status[0]['Task']['revised_due_date'] != '1970-01-01'){
                    if(($status[0]['Task']['task_status'] == 0 && $status[0]['Task']['revised_due_date'] < date('Y-m-d')))$rag_status = 0;
                    elseif(($status[0]['Task']['task_status'] == 0 && $status[0]['Task']['revised_due_date'] > date('Y-m-d'))) $rag_status = 1;
                    elseif($status[0]['Task']['task_status'] == 2)$rag_status = 3;
                    else $rag_status = 2;   
                }else{
                    if(($status[0]['Task']['task_status'] == 0 && $status[0]['Task']['end_date'] < date('Y-m-d')))$rag_status = 0;
                    elseif(($status[0]['Task']['task_status'] == 0 && $status[0]['Task']['end_date'] > date('Y-m-d'))) $rag_status = 1;
                    elseif($status[0]['Task']['task_status'] == 2)$rag_status = 3;
                    else $rag_status = 2;   
                }
                $this->Task->read(null,$key);
                $this->Task->set(array('rag_status'=>$rag_status,'task_completion'=>round($per)));
                $this->Task->save();
            }
        }
        $tasks = $this->Task->find('all',array('conditions'=>array(            
            'Task.task_status'=>array(0,2)),
            // 'Task.task_status'=>array(1,3)),
        'recursive'=>0,
            'fields'=>array('Task.id','Task.name','Task.task_type','Task.task_status','Task.task_completion','Task.user_id','Task.start_date','Task.end_date','Task.revised_due_date', 'User.id','User.name','Task.schedule_id')));
        $this->loadModel('Employee');
        $this->loadModel('User');      

        
        Configure::write('debug',1);
        $result = null;
        foreach ($tasks as $task) {
            // debug($task);    
            if($task['Task']['revised_due_date'] != '' && $task['Task']['revised_due_date'] != '1970-01-01'){                
                if($task['Task']['revised_due_date'] < date('Y-m-d')){
                    $emp = $this->User->find('first',array('conditions'=>array('User.id'=>$task['Task']['user_id']),'recursive'=>-1,'fields'=>array('User.id','User.employee_id')));
                    $result[$emp['User']['employee_id']][] = $task;
                }
            }elseif($task['Task']['end_date'] != '' && $task['Task']['end_date'] != '1970-01-01'){                                
                // echo $task['Task']['end_date'] . "
                // ";
                // echo date('Y-m-d');
                // if($task['Task']['end_date'] > date('Y-m-d')){                    
                 $emp = $this->User->find('first',array('conditions'=>array('User.id'=>$task['Task']['user_id']),'recursive'=>-1,'fields'=>array('User.id','User.employee_id')));   
                 $result[$emp['User']['employee_id']][] = $task;
                 
                // }
            }
            
        }

        
        $this->set('tasks',$tasks);
        
        // if(FULL_BASE_URL == 'https://sit-qms.tranzport.com')$env =  "SIT : ";
        // elseif(FULL_BASE_URL == 'https://qa-qms.tranzport.com')$env =  "QA : ";
        // elseif(FULL_BASE_URL == 'https://dev-qms.tranzport.com')$env = "DEV : ";
        // else $env = "";

        if(Configure::read('evnt') == 'Dev')$env = 'DEV';
        elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
        else $env = "";


        $this->_task_weekly_cron($env);
        // $this->_send_email($result,$env); 
        // $this->_send_not_performed($performed,$env);
        // if(date('w') == 1)

        exit;
    }

    public function _task_weekly_cron($env = null){
        $start_date = date('Y-m-d',strtotime('-7 days'));
        $end_date = date("Y-m-d", strtotime("+7 days", strtotime($start_date)));
        $this->set('start_date',$start_date);
        $this->set('end_date',$end_date);

        $this->Task->hasMany['TaskStatus']['conditions'] = array('TaskStatus.task_date BETWEEN ? and ?'=>array($start_date,$end_date));
        $this->Task->hasMany['TaskStatus']['fields'] = array('TaskStatus.id','TaskStatus.task_id','TaskStatus.task_performed','TaskStatus.task_date');
        $tasks = $this->Task->find('all',array(
            // 'limit'=>20,
            'recursive'=>1,
            'order'=>array('Task.rag_status'=>'ASC'),
            'fields'=>array(
                'User.id','User.name',
                'Task.id','Task.name','Task.user_id','Task.task_type','Task.task_status','Task.rag_status','Task.task_completion','Task.sequence','Task.schedule_id',                    
            ),
            'conditions'=>array('Task.publish'=>1,
                'Task.task_status <>' => 1                
            )));         
        // print_r($tasks);
        // return $tasks;
        $this->loadModel('User');
        
        // $employee = $this->Employee->find('first',array('recursive'=>-1, 'conditions'=>array('Employee.id'=>$emp['User']['employee_id'])));
        // if($employee){
            $officeEmailId = $employee['Employee']['office_email'];
            $personalEmailId = $employee['Employee']['personal_email'];
            if ($officeEmailId != '') {
                $email = $officeEmailId;
            } else if ($personalEmailId != '') {
                $email = $personalEmailId;
            }
            // $users = $this->User->find('first',array('conditions'=>array('User.employee_id'=>$employee['Employee']['id'])));
            $users = $this->User->find('list',array('conditions'=>array('User.publish'=>1)));
            $new_session = $_SESSION;
            try{
                App::uses('CakeEmail', 'Network/Email');
                $new_session = $users;

                // if($new_session['User']['is_smtp'] == 1)
                    $EmailConfig = new CakeEmail("smtp");

                // if($new_session['User']['is_smtp'] == 0)
                    // $EmailConfig = new CakeEmail("default");

                // $EmailConfig->to($email);
                $EmailConfig->to('mayureshvaidya@gmail.com');
                
                $EmailConfig->subject('Weekly Task Report');
                $EmailConfig->template('tasksweekly');
                $EmailConfig->viewVars(array('tasks' => $tasks,'start_date'=>$start_date,'end_date'=>$end_date,'users'=>$users));
                $EmailConfig->emailFormat('html');
                $EmailConfig->send();
                // echo "Send email to " . $email . " for " . $task_name ." 


                // ";
            } catch(Exception $e) {
                CakeLog::write('WeeklyCronFailed',json_encode($e));
            }  
        // }
    }

    public function _send_not_performed($performed = null,$env = null){
        $this->loadModel('User');
        
        foreach ($performed as $user_id => $tasks) {
            foreach ($tasks as $task_name => $status) {
                if($status['not_performed'] != 0){
                    $emp = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id),'recursive'=>-1,'fields'=>array('User.id','User.employee_id')));
                    $employee = $this->Employee->find('first',array('recursive'=>-1, 'conditions'=>array('Employee.id'=>$emp['User']['employee_id'])));
                    if($employee){
                        $officeEmailId = $employee['Employee']['office_email'];
                        $personalEmailId = $employee['Employee']['personal_email'];
                        if ($officeEmailId != '') {
                            $email = $officeEmailId;
                        } else if ($personalEmailId != '') {
                            $email = $personalEmailId;
                        }
                        
                        $users = $this->User->find('first',array('conditions'=>array('User.employee_id'=>$employee['Employee']['id'])));
                        $new_session = $_SESSION;
                        try{
                            App::uses('CakeEmail', 'Network/Email');
                            $new_session = $users;

                            // if($new_session['User']['is_smtp'] == 1)
                                $EmailConfig = new CakeEmail("smtp");

                            // if($new_session['User']['is_smtp'] == 0)
                                // $EmailConfig = new CakeEmail("default");

                            $EmailConfig->to($email);
                            $EmailConfig->subject('Update Required for Task - ' . $task_name .' - ' . date('d M Y') );
                            $EmailConfig->template('tasksnotperformed');
                            $EmailConfig->viewVars(array('task_name' => $task_name,'employee'=>$employee['Employee']['name']));
                            $EmailConfig->emailFormat('html');
                            $EmailConfig->send();
                            // echo "Send email to " . $email . " for " . $task_name ." 


                            // ";
                        } catch(Exception $e) {
                            CakeLog::write('TaskNotPerformedFailed',json_encode($e));
                        }  
                    }
                }
            }
        }
    }
    
    public function _send_email($result = null,$env = null){
        $this->loadModel('Schedule');
        $schedules = $this->Schedule->find('list');
        foreach ($result as $employee_id => $tasks) {
            
            $employee = $this->Employee->find('first',array('recursive'=>-1, 'conditions'=>array('Employee.id'=>$employee_id)));
            if($employee){
                $officeEmailId = $employee['Employee']['office_email'];
                $personalEmailId = $employee['Employee']['personal_email'];
                if ($officeEmailId != '') {
                    $email = $officeEmailId;
                } else if ($personalEmailId != '') {
                    $email = $personalEmailId;
                }
                
                $users = $this->User->find('first',array('conditions'=>array('User.employee_id'=>$employee['Employee']['id'])));
                // $new_session = $_SESSION;
                try{
                    App::uses('CakeEmail', 'Network/Email');
                    $new_session = $users;

                // if($new_session['User']['is_smtp'] == 1)
                    $EmailConfig = new CakeEmail("smtp");

                    // if($new_session['User']['is_smtp'] == 0)
                        // $EmailConfig = new CakeEmail("default");
                    $EmailConfig->to($email);
                    $EmailConfig->subject('This is a task update reminder' .' - ' . date('d M Y'));
                    $EmailConfig->template('tasks');
                    $EmailConfig->viewVars(array('tasks' => $tasks,'schedules'=>$schedules,'env' => $env, 'app_url' => FULL_BASE_URL));
                    $EmailConfig->emailFormat('html');
                    $EmailConfig->send();
                } catch(Exception $e) {
                    CakeLog::write('ReminderFailed',json_encode($e));
                }  
            }
                
        }
              
    }
    
    public function task_calendar(){
        $tasks = $this->Task->find('all',array(
            'fields'=>array(
                'Task.id','Task.name','Task.user_id','Task.task_status','Task.task_completion','Task.start_date','Task.end_date','Task.sequence','Task.priority',
                'User.id','User.name'
            ),
            'conditions'=>array('Task.soft_delete'=>0),'recursive'=>0));
        
        foreach ($tasks as $task) {
            if($task['Task']['task_status'] == 0){
                $backgroundColor = '#dd4b39';
                $borderColor = '#c74433';
            }elseif($task['Task']['task_status'] == 1){
                $backgroundColor = '#00a65a';
                $borderColor = '#008d4c';
            }elseif($task['Task']['task_status'] == 0 && $task['Task']['start_date'] > date()){
                $backgroundColor = '#f39c12';
                $borderColor = '#db8d10';
            }
            $events[] = array(
                    'title'=>$task['Task']['name'] .' /'.$task['User']['name'] .' - ' . $task['Task']['task_completion'] .'%',
                    'start'=> date('Y-m-d H:i:s',strtotime($task['Task']['start_date'])),
                    'end'=> date('Y-m-d H:i:s',strtotime($task['Task']['end_date'])),
                    'backgroundColor' => $backgroundColor,
                    'borderColor'=> $borderColor,
                    'url' => Router::url('/', true)."tasks/view/" .$task['Task']['id'],
                    // 'addurl' => Router::url('/', true)."internal_audit_plans/add_ajax"
                );
        }

        $this->set(compact('events'));
    }

    public function task_report($start_date = null, $end_date = null,$user_id = null){
        if ($this->request->is('post')) {            
            $start_date = $this->request->data['Task']['start_date'];
            $end_date = $this->request->data['Task']['end_date'];

            $this->set('start_date',$start_date);
            $this->set('end_date',$end_date);

            if($this->request->data['Task']['user_id']){
                $userConditions = array('or'=>array('Task.user_id'=>$this->request->data['Task']['user_id']));                
            }else{
                $userConditions = array();
            }
            
            $this->Task->hasMany['TaskStatus']['conditions'] = array('TaskStatus.task_date BETWEEN ? and ?'=>array($start_date,$end_date));
            $this->Task->hasMany['TaskStatus']['fields'] = array('TaskStatus.id','TaskStatus.task_id','TaskStatus.task_performed','TaskStatus.task_date');            
            
            $tasks = $this->Task->find('all',array(
            // 'limit'=>2,
            'recursive'=>1,
            'fields'=>array(
                'Task.id','Task.sr_no','Task.name','Task.user_id','Task.task_type','Task.task_status','Task.rag_status','Task.task_completion','Task.sequence','Task.schedule_id'
                ),
            'conditions'=>array('Task.publish'=>1,
                $userConditions,
                // 'Task.start_date >=' => $start_date,
                // 'Task.start_date <=' => $end_date
                'Task.start_date BETWEEN ? AND ?' => array($start_date,$end_date)
                )));            
        }else{            
            $start_date = date('Y-m-1');
            $end_date = date('Y-m-t');
            $this->set('start_date',$start_date);
            $this->set('end_date',$end_date);

            $this->Task->hasMany['TaskStatus']['conditions'] = array('TaskStatus.task_date BETWEEN ? and ?'=>array($start_date,$end_date));
            $this->Task->hasMany['TaskStatus']['fields'] = array('TaskStatus.id','TaskStatus.task_id','TaskStatus.task_performed','TaskStatus.task_date');
            $tasks = $this->Task->find('all',array(
                // 'limit'=>20,
                'recursive'=>1,
                'fields'=>array(
                    'Task.id','Task.name','Task.user_id','Task.task_type','Task.task_status','Task.rag_status','Task.task_completion','Task.sequence','Task.schedule_id',                    
                ),
                'conditions'=>array('Task.publish'=>1,
                    // 'Task.start_date >=' => $start_date,
                    // 'Task.start_date <=' => $end_date
                    'Task.start_date BETWEEN ? AND ?' => array($start_date,$end_date)
                ))); 
                
        }
        $users = $this->Task->User->find('list',array('conditions'=>array('User.publish'=>1)));
        $this->set('users',$users);
        $this->set('tasks',$tasks);        
    }

    public function tasks_status($task = null,$start_date = null,$end_date = null){
        $task_status = $this->Task->TaskStatus->find('first',array(
            'fields'=>array('TaskStatus.task_id','TaskStatus.task_performed'),
            'recursive'=>-1,
            'conditions'=>array(
                'TaskStatus.task_id'=>$task,
                'TaskStatus.task_date BETWEEN ? and ?' => array($start_date,$end_date)
            )));

        if(!$task_status){
            $result = 0;
        }elseif($task_status['TaskStatus']['task_performed'] == 1){
            $result = 1;
        }
       return $result;
    }

    public function em(){
        try{
            App::uses('CakeEmail', 'Network/Email');
            $EmailConfig = new CakeEmail("smtp");
            // $EmailConfig->to($email);
            $EmailConfig->to('mayureshvaidya@gmail.com');
            $EmailConfig->subject('SIT : This is a task update reminder');
            $EmailConfig->template('approval_reminder');
            $EmailConfig->viewVars(array('tasks' => $tasks,'schedules'=>$schedules));
            $EmailConfig->emailFormat('html');
            $EmailConfig->send();
        } catch(Exception $e) {
            echo "Error Occured";
                        
        } 
    }
}

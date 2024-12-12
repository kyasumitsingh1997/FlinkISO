<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');


/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController
{
    
    public $components = array('Ctrl');
    public $show_nc_alert = null;
    public $dbsize = null;
    public $task_performed = null;
    public $tasks = null;
    public $common_condition = array('OR' => array('History.action' => array('add', 'add_ajax'), array('History.model_name' => array('CakeError', 'NotificationUser', 'History', 'UserSession', 'Page', 'Dashboard', 'Error', 'NotificationType', 'Approval', 'Benchmark', 'FileUpload', 'DataEntry', 'Help', 'MeetingBranch', 'MeetingDepartment', 'MeetingEmployee', 'MeetingTopic', 'Message', 'NotificationUser', 'PurchaseOrderDetail', 'NotificationUser', 'PurchaseOrderDetail', 'MasterListOfFormatBranch', 'MasterListOfFormatDepartment', 'MasterListOfFormatDistributor'), 'History.action <>' => 'delete', 'History.action <>' => 'soft_delete', 'History.action <>' => 'purge', 'History.post_values <>' => '[[],[]]')));
    
    public function _get_system_table_id()
    {
        
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId                = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => $this->request->params['controller']
            )
        ));
        return $systemTableId['SystemTable']['id'];
    }
    
    public function welcome()
    {
        $this->layout = 'welcome';
    }
    
    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        
        $conditions     = $this->_check_request();
        $this->paginate = array(
            'order' => array(
                'User.sr_no' => 'DESC'
            ),
            'conditions' => array(
                $conditions
            )
        );
        
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
        
        $this->_get_count();
    }
    
    private function _sql_query($flag = Null)
    {
        $db = ConnectionManager::getDataSource('default');
        if (isset($flag) && $flag == 'insert')
            $path = WWW_ROOT . "DB" . DS . "insert.sql";
        if (isset($flag) && $flag == 'remove')
            $path = WWW_ROOT . "DB" . DS . "delete.sql";
        $fileName = new File($path);
        if ($fileName) {
            $statements = $fileName->read();
            $statements = explode('@#@', $statements);
            $prefix     = $this->User->tablePrefix;
            
            foreach ($statements as $statement) {
                if (trim($statement) != '') {
                    $statement = str_replace("INSERT INTO `", "INSERT INTO `$prefix", $statement);
                    $query     = $db->query($statement);
                }
            }
            
            return TRUE;
        } else {
            return FALSE;
        }
        
    }
    public function _update_sql($companyId = null)
    {
        $allModelNames = App::objects('Model');
        $excludeModel  = array(
            'AppModel',
            'MasterListOfFormat',
            'MasterListOfFormatBranch',
            'MasterListOfFormatDepartment',
            'MasterListOfFormatDistributor',
            'MasterListOfWorkInstruction'
        );
        foreach ($allModelNames as $allModelName) {
            if (!in_array($allModelName, $excludeModel)) {
                $this->loadModel($allModelName);
                if ($this->$allModelName->hasField('company_id')) {
                    $records = $this->$allModelName->updateAll(array(
                        'company_id' => "'" . $companyId . "'"
                    ));
                }
            }
        }
        $this->sample_file_upload($companyId);
        
    }
    
    public function remove_sample()
    {
        $flag = 'remove';
        if ($this->_sql_query($flag)) {
            $this->loadModel('Company');
            $this->Company->updateAll(array(
                'Company.sample_data' => 0
            ));
            $this->Session->setFlash('All data removed from Database succesfully');
        } else {
            $this->Session->setFlash('All data is not removed from Database');
        }
        $this->redirect(array(
            'action' => 'pm_dashboard'
        ));
    }
    public function insert_sample_data()
    {
        $flag = 'insert';
        if ($this->_sql_query($flag)) {
            $this->Session->setFlash('Data inserted succesfully');
        } else {
            $this->Session->setFlash('Data is not inserted succesfully');
        }
    }
    
    public function sample_file_upload($companyId = null)
    {
        $this->loadModel('FileUpload');
        $this->loadModel('SystemTable');
        /*    $sourcrPath = WWW_ROOT . 'sampleData';
        $destPath = WWW_ROOT . 'files'. DS . $companyId ;
        $folder = new Folder($sourcrPath);
        $a = $folder->copy($destPath);*/
        
    }
    
    
    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function advanced_search()
    {
        $conditions = array();
        if ($this->request->query['keywords']) {
            $searchArray = array();
            if ($this->requset->query['strict_search'] == 0) {
                $searchKeys[] = $this->request->query['keywords'];
            } else {
                $searchKeys = explode(" ", $this->request->query['keywords']);
            }
            foreach ($searchKeys as $searchKey):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $searchArray[] = array(
                            'User.' . $search => $searchKey
                        );
                    else
                        $searchArray[] = array(
                            'User.' . $search . ' like ' => '%' . $searchKey . '%'
                        );
                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array(
                    'and' => array(
                        'OR' => $searchArray
                    )
                );
            else
                $conditions[] = array(
                    'or' => $searchArray
                );
        }
        if ($this->request->query['employee_id'] != -1) {
            $employeeConditions[] = array(
                'User.employee_id' => $this->request->query['employee_id']
            );
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array(
                    'and' => $employeeConditions
                );
            else
                $conditions[] = array(
                    'or' => $employeeConditions
                );
        }
        if ($this->request->query['language_id'] != -1) {
            $languageConditions[] = array(
                'User.language_id' => $this->request->query['language_id']
            );
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array(
                    'and' => $languageConditions
                );
            else
                $conditions[] = array(
                    'or' => $languageConditions
                );
        }
        if ($this->request->query['is_mr'] != '') {
            $mrConditions[] = array(
                'User.is_mr' => $this->request->query['is_mr']
            );
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array(
                    'and' => $mrConditions
                );
            else
                $conditions[] = array(
                    'or' => $mrConditions
                );
        }
        if ($this->request->query['is_view_all'] != '') {
            $viewAllConditions[] = array(
                'User.is_view_all' => $this->request->query['is_view_all']
            );
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array(
                    'and' => $viewAllConditions
                );
            else
                $conditions[] = array(
                    'or' => $viewAllConditions
                );
        }
        if ($this->request->query['is_approvar'] != '') {
            $isApprovarConditions[] = array(
                'User.is_approvar' => $this->request->query['is_approvar']
            );
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array(
                    'and' => $isApprovarConditions
                );
            else
                $conditions[] = array(
                    'or' => $isApprovarConditions
                );
        }
        if ($this->request->query['department_id']) {
            foreach ($this->request->query['department_id'] as $department_id):
                $departmentConditions[] = array(
                    'User.department_id' => $department_id
                );
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array(
                    'and' => array(
                        'or' => $departmentConditions
                    )
                );
            else
                $conditions[] = array(
                    'or' => $departmentConditions
                );
        }
        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array(
                    'User.branch_id' => $branches
                );
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array(
                    'and' => array(
                        'or' => $branchConditions
                    )
                );
            else
                $conditions[] = array(
                    'or' => $branchConditions
                );
        }
        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array(
                'User.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])),
                'User.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date']))
            );
        }
        $conditions = $this->advance_search_common($conditions);
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array(
                'Or' => array(
                    'User.branch_id' => $this->Session->read('User.branch_id'),
                    'User.id' => $this->Session->read('User.id')
                )
            );
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array(
                'Or' => array(
                    'User.created_by' => $this->Session->read('User.id'),
                    'User.id' => $this->Session->read('User.id')
                )
            );
        
        $conditions[]          = array(
            $onlyBranch,
            $onlyOwn
        );
        $this->User->recursive = 0;
        $this->paginate        = array(
            'order' => array(
                'User.sr_no' => 'DESC'
            ),
            'conditions' => $conditions,
            'User.soft_delete' => 0
        );
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('users', $this->paginate());
        
        $this->render('index');
    }
    
    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }
        $options = array(
            'conditions' => array(
                'User.' . $this->User->primaryKey => $id
            )
        );
        $this->set('user', $this->User->find('first', $options));
    }
    
    public function unblock_user($id = null,$redirect = null)
    {
        
        $this->loadModel('User');
        if (!empty($id)) {
            
            $data['id']     = $id;
            $data['status'] = 1;
            if ($this->User->save($data))
                $this->Session->setFlash(__('User Unblocked'));
            else
                $this->Session->setFlash(__('Failed to Unblock'));
        }
        
        $blockedusercount = $this->User->find('count', array(
            'conditions' => array(
                'User.status' => 3,
                'User.publish' => 1,
                'User.soft_delete' => 0
            ),
            'recursive' => -1
        ));
        $blockeduser      = $this->User->find('all', array(
            'conditions' => array(
                'User.status' => 3,
                'User.publish' => 1,
                'User.soft_delete' => 0
            ),
            'recursive' => -1
        ));
        $this->set(compact('blockedusercount', 'blockeduser'));
        $this->Session->setFlash('User is unblocked');
        
        if(isset($this->request->params['named']['redirect'])){
            $this->redirect(array('action' => 'pm_dashboard'));
        }else{
            $this->redirect(array('action' => 'dashboard'));
        }
        
    }
    
    /**
     * list method
     *
     * @return void
     */
    public function lists()
    {
        
        $this->_get_count();
    }
    
    /**
     * add_ajax method
     *
     * @return void
     */
    public function add_ajax()
    {
        
        if ($this->_show_approvals()) {
            $this->set(array(
                'showApprovals' => $this->_show_approvals()
            ));
        }
        
        if ($this->request->is('post')) {
            $this->request->data['User']['system_table_id'] = $this->_get_system_table_id();
            
            if($this->request->data['User']['copy_acl_from'] != -1){
                $userAccess = $this->User->find('first', array('conditions' => array('User.id' => $this->request->data['User']['copy_acl_from']),'fields' => 'User.user_access'));
                if (!$userAccess['User']['user_access'])$userAccess['User']['user_access'] = $this->Ctrl->get_defaults();
            }elseif($this->request->data['User']['copy_acl_from_acl'] != -1){
                $this->loadModel('UserAccessControl');
                $access = $this->UserAccessControl->find('first',array(
                    'fields'=>array('UserAccessControl.id','UserAccessControl.user_access'),
                    'conditions'=>array('UserAccessControl.id'=>$this->request->data['User']['copy_acl_from_acl'])));
                    $userAccess['User']['user_access'] = $access['UserAccessControl']['user_access'];

            }
            
            
            $this->loadModel('Employee');
            $this->Employee->recursive                   = 0;
            $userName                                    = $this->Employee->find('first', array(
                'conditions' => array(
                    'Employee.id' => $this->request->data['User']['employee_id']
                ),
                'fields' => array(
                    'Employee.name',
                    'Employee.office_email',
                    'Employee.personal_email'
                )
            ));
            $pwd                                         = $this->request->data['User']['password'];
            $this->request->data['User']['user_access']  = $userAccess['User']['user_access'];
            $this->request->data['User']['name']         = $userName['Employee']['name'];
            $this->request->data['User']['password']     = Security::hash($this->request->data['User']['password'], 'md5', true);
            $this->request->data['User']['old_password'] = json_encode(array(
                $this->request->data['User']['password']
            ));
            $this->request->data['User']['status']       = 1;
            $this->request->data['User']['agree']        = 0;
            if ($this->request->data['User']['is_mr'] == 1) {
                $this->request->data['User']['user_access'] = '';
                $this->request->data['User']['is_view_all'] = 1;
                $this->request->data['User']['is_approvar'] = 1;
            }
            $this->User->create();
            $this->request->data['User']['pwd_last_modified'] = date('Y-m-d H:i:s');
            if ($this->User->save($this->request->data)) {

                // updates ACL table

                if($this->request->data['User']['copy_acl_from_acl'] != -1){
                $this->loadModel('UserAccessControl');
                    $access = $this->UserAccessControl->find('first',array(
                        'recursive'=>-1,
                        // 'fields'=>array('UserAccessControl.id','UserAccessControl.user_access'),
                        'conditions'=>array('UserAccessControl.id'=>$this->request->data['User']['copy_acl_from_acl'])));
                        // $userAccess['User']['user_access'] = $access['UserAccessControl']['user_access'];
                        $users = json_decode($access['UserAccessControl']['users'],false);
                        $users[] = $this->User->id;
                        $users = json_encode($users);
                        $access['UserAccessControl']['users'] = $users;
                        $aclData = $access['UserAccessControl'];
                        $this->UserAccessControl->create();
                        $this->UserAccessControl->save($aclData,false);

                }

                $webpath   = explode(DS, APP);
                $login_url = FULL_BASE_URL . DS . $webpath[4] . DS . 'users/login';
                
                // if ($userName['Employee']['office_email'] != '') {
                //     $email = $userName['Employee']['office_email'];
                // } else if ($userName['Employee']['personal_email'] != '') {
                //     $email = $userName['Employee']['personal_email'];
                // }
                // if ($email) {
                //     try {
                        
                //         if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                //         elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                //         else $env = "";

                //         App::uses('CakeEmail', 'Network/Email');
                //         if ($this->Session->read('User.is_smtp') == 1)
                //             $EmailConfig = new CakeEmail("smtp");
                //         if ($this->Session->read('User.is_smtp') == 0)
                //             $EmailConfig = new CakeEmail("default");
                //         $EmailConfig->to($email);
                //         $EmailConfig->subject('FlinkISO: Login Details');
                //         $EmailConfig->template('loginDetail');
                //         $EmailConfig->viewVars(array(
                //             'username' => $this->request->data['User']['username'],
                //             'password' => $pwd,
                //             'url' => $login_url,
                //             'env'=>$env
                //         ));
                //         $EmailConfig->emailFormat('html');
                //         $EmailConfig->send();
                //     }
                //     catch (Exception $e) {
                //         $this->Session->setFlash(__('The user has been saved but fail to send email. Please check smtp details.', true), 'smtp');
                //         $this->redirect(array(
                //             'action' => 'index'
                //         ));
                        
                //     }
                    
                // }
                // $this->_reset_benchmarking();
                
                $this->Session->setFlash(__('The user has been saved'));
                
                if ($this->_show_approvals())
                    $this->_save_approvals();
                
                if ($this->_show_evidence() == true)
                    $this->redirect(array(
                        'action' => 'view',
                        $this->User->id
                    ));
                else
                    $this->redirect(array(
                        'action' => 'index'
                    ));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
        $aclUsers  = $this->User->find('list', array(
            'fields' => array(
                'User.username'
            ),
            'conditions' => array(
                'User.is_mr' => 0
            )
        ));
        $languages = $this->User->Language->find('list', array(
            'conditions' => array(
                'Language.publish' => 1,
                'Language.soft_delete' => 0
            )
        ));
        $this->loadModel('PasswordSetting');
        $this->loadModel('Company');
        $this->PasswordSetting->recursive                                 = -1;
        $this->Company->recursive                                         = -1;
        $password_setting                                                 = $this->PasswordSetting->find('first');
        $companies                                                        = $this->Company->find('first');
        $password_setting['PasswordSetting']['activate_password_setting'] = $companies['Company']['activate_password_setting'];
        $this->set('password_setting', $password_setting['PasswordSetting']);
        $this->set(compact('languages', 'aclUsers'));
        $divisions = $this->User->Division->find('list', array(
            'conditions' => array(
                'Division.publish' => 1,
                'Division.soft_delete' => 0
            )
        ));
        $this->set(compact('divisions'));

        $this->loadModel('UserAccessControl');
        $userAccessControls = $this->UserAccessControl->find('list',array('conditions'=>array('UserAccessControl.publish'=>1,'UserAccessControl.soft_delete'=>0)));
        $this->set(compact('userAccessControls'));
    }
    
    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->_show_approvals()) {
            $this->set(array(
                'showApprovals' => $this->_show_approvals()
            ));
        }
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User']['system_table_id'] = $this->_get_system_table_id();
            
            /* if employee changes, name in user's table should also be changed
             * if user is changes to MR, then user_access should be blank            *
             */
            
            if ($this->request->data['User']['is_mr'] == 1) {
                $this->request->data['User']['user_access'] = '';
                $this->request->data['User']['is_view_all'] = 1;
                $this->request->data['User']['is_approvar'] = 1;
            }else if($this->request->data['User']['copy_acl_from'] != -1 || $this->request->data['User']['copy_acl_from_acl'] != -1){
                if($this->request->data['User']['copy_acl_from'] != -1){
                    $userAccess = $this->User->find('first', array('conditions' => array('User.id' => $this->request->data['User']['copy_acl_from']),'fields' => 'User.user_access'));
                    if (!$userAccess['User']['user_access'])$userAccess['User']['user_access'] = $this->Ctrl->get_defaults();
                }elseif($this->request->data['User']['copy_acl_from_acl'] != -1){
                    $this->loadModel('UserAccessControl');
                    $access = $this->UserAccessControl->find('first',array(
                        'fields'=>array('UserAccessControl.id','UserAccessControl.user_access'),
                        'conditions'=>array('UserAccessControl.id'=>$this->request->data['User']['copy_acl_from_acl'])));
                        $userAccess['User']['user_access'] = $access['UserAccessControl']['user_access'];

                }    
            }else {
                $userAccess = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->request->data['User']['id']
                    ),
                    'fields' => 'User.user_access'
                ));
                
                if (!$userAccess['User']['user_access']) {
                    
                    $userAccess['User']['user_access'] = $this->Ctrl->get_defaults();
                }
                
            }


            
            
            
            $employeeName                               = $this->User->Employee->find('first', array(
                'fields' => array(
                    'Employee.id',
                    'Employee.name'
                ),
                'conditions' => array(
                    'Employee.id' => $this->request->data['User']['employee_id']
                )
            ));
            $this->request->data['User']['name']        = $employeeName['Employee']['name'];
            $this->request->data['User']['user_access'] = $userAccess['User']['user_access'];
            
            if ($this->User->save($this->request->data)) {
                
                $this->_reset_benchmarking();
                
                $this->Session->setFlash(__('The user has been saved'));
                
                if ($this->_show_approvals())
                    $this->_save_approvals();
                
                if ($this->_show_evidence() == true)
                    $this->redirect(array(
                        'action' => 'view',
                        $id
                    ));
                else
                    $this->redirect(array(
                        'action' => 'index'
                    ));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $options             = array(
                'conditions' => array(
                    'User.' . $this->User->primaryKey => $id
                )
            );
            $this->request->data = $this->User->find('first', $options);
        }
        $aclUsers  = $this->User->find('list', array(
            'conditions' => array(
                'User.publish' => 1,
                'User.soft_delete' => 0,
                'User.is_mr' => 0
            ),
            'fields' => array(
                'User.username'
            )
        ));
        $languages = $this->User->Language->find('list', array(
            'conditions' => array(
                'Language.publish' => 1,
                'Language.soft_delete' => 0
            )
        ));
        
        $this->set(compact('aclUsers', 'languages'));
        $divisions = $this->User->Division->find('list', array(
            'conditions' => array(
                'Division.publish' => 1,
                'Division.soft_delete' => 0
            )
        ));
        $this->set(compact('divisions'));
        $this->loadModel('UserAccessControl');
        $userAccessControls = $this->UserAccessControl->find('list',array('conditions'=>array('UserAccessControl.publish'=>1,'UserAccessControl.soft_delete'=>0)));
        $this->set(compact('userAccessControls'));
    }
    
    public function _reset_benchmarking()
    {
        
        // add to branch / department benchmark
        $this->loadModel('Benchmark');
        $this->Benchmark->recursive = 0;
        
        $this->User->recursive = 0;
        $userBenchmark         = $this->User->find('all', array(
            'conditions' => array(
                'User.branch_id' => $this->request->data['User']['branch_id'],
                'User.department_id' => $this->request->data['User']['department_id']
            ),
            'fields' => array(
                'User.benchmark'
            )
        ));
        $totalBenchmark        = null;
        foreach ($userBenchmark as $benchmarks):
            $totalBenchmark = $totalBenchmark + $benchmarks['User']['benchmark'];
        endforeach;
        
        $branchList      = $this->_get_branch_list();
        $departmentsList = $this->_get_department_list();
        
        foreach ($branchList as $key => $value):
            foreach ($departmentsList as $dkey => $dvalue):
                $this->Benchmark->deleteAll(array(
                    'Benchmark.branch_id' => $key,
                    'Benchmark.department_id' => $dkey
                ), false);
                $users = $this->User->find('all', array(
                    'conditions' => array(
                        'User.publish' => 1,
                        'User.soft_delete' => 0,
                        'User.branch_id' => $key,
                        'User.department_id' => $dkey
                    ),
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.benchmark',
                        'Branch.id',
                        'Branch.name',
                        'Department.id',
                        'Department.name'
                    )
                ));
                
                $totalBenchmark = 0;
                foreach ($users as $benchmarks):
                    $totalBenchmark = $totalBenchmark + $benchmarks['User']['benchmark'];
                endforeach;
                
                $newData['branch_id']     = $key;
                $newData['department_id'] = $dkey;
                $newData['benchmark']     = $totalBenchmark;
                $newData['branchid']      = $this->Session->read('User.branch_id');
                $newData['departmentid']  = $this->Session->read('User.department_id');
                $newData['created_by']    = $this->Session->read('User.id');
                $newData['modified_by']   = $this->Session->read('User.id');
                $newData['created']       = date('Y-m-d H:i:s');
                $newData['modified']      = date('Y-m-d H:i:s');
                $newData['publish']       = 1;
                $this->Benchmark->create();
                $this->Benchmark->save($newData, false);
            endforeach;
        endforeach;
    }
    
    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null)
    {
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }
        
        $this->loadModel('Approval');
        if (!$this->Approval->exists($approvalId)) {
            throw new NotFoundException(__('Invalid approval id'));
        }
        
        $approval = $this->Approval->read(null, $approvalId);
        $this->set('same', $approval['Approval']['user_id']);
        
        if ($this->_show_approvals()) {
            $this->set(array(
                'showApprovals' => $this->_show_approvals()
            ));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User']['system_table_id'] = $this->_get_system_table_id();
            
            if ($this->request->data['User']['is_mr'] == 1) {
                $this->request->data['User']['user_access'] = '';
                $this->request->data['User']['is_view_all'] = 1;
                $this->request->data['User']['is_approvar'] = 1;
            }else if($this->request->data['User']['copy_acl_from'] != -1 || $this->request->data['User']['copy_acl_from_acl'] != -1){
                if($this->request->data['User']['copy_acl_from'] != -1){
                    $userAccess = $this->User->find('first', array('conditions' => array('User.id' => $this->request->data['User']['copy_acl_from']),'fields' => 'User.user_access'));
                    if (!$userAccess['User']['user_access'])$userAccess['User']['user_access'] = $this->Ctrl->get_defaults();
                }elseif($this->request->data['User']['copy_acl_from_acl'] != -1){
                    $this->loadModel('UserAccessControl');
                    $access = $this->UserAccessControl->find('first',array(
                        'fields'=>array('UserAccessControl.id','UserAccessControl.user_access'),
                        'conditions'=>array('UserAccessControl.id'=>$this->request->data['User']['copy_acl_from_acl'])));
                        $userAccess['User']['user_access'] = $access['UserAccessControl']['user_access'];

                }    
            }else {
                $userAccess = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->request->data['User']['id']
                    ),
                    'fields' => 'User.user_access'
                ));
                
                if (!$userAccess['User']['user_access']) {
                    
                    $userAccess['User']['user_access'] = $this->Ctrl->get_defaults();
                }
                
            }

            $this->request->data['User']['user_access'] = $userAccess['User']['user_access'];
            if ($this->User->save($this->request->data)) {
                
                $this->Session->setFlash(__('The user has been saved.'));
                
                if ($this->_show_approvals())
                    $this->_save_approvals();
                
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $options             = array(
                'conditions' => array(
                    'User.' . $this->User->primaryKey => $id
                )
            );
            $this->request->data = $this->User->find('first', $options);
        }
        $aclUsers  = $this->User->find('list', array(
            'conditions' => array(
                'User.publish' => 1,
                'User.soft_delete' => 0,
                'User.is_mr' => 0
            ),
            'fields' => array(
                'User.username'
            )
        ));
        $languages = $this->User->Language->find('list', array(
            'conditions' => array(
                'Language.publish' => 1,
                'Language.soft_delete' => 0
            )
        ));
        $this->set(compact('aclUsers', 'languages'));
        $divisions = $this->User->Division->find('list', array(
            'conditions' => array(
                'Division.publish' => 1,
                'Division.soft_delete' => 0
            )
        ));
        $this->set(compact('divisions'));

        $this->loadModel('UserAccessControl');
        $userAccessControls = $this->UserAccessControl->find('list',array('conditions'=>array('UserAccessControl.publish'=>1,'UserAccessControl.soft_delete'=>0)));
        $this->set(compact('userAccessControls'));
    }
    
    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null, $parent_id = NULL)
    {
        
        $modelName = $this->modelClass;
        $this->loadModel('Approval');
        if (!empty($id)) {
            if ($id == $this->Session->read('User.id')) {
                
                $this->Session->setFlash(__('Logged-in user can not be deleted!'));
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                
                $approves = $this->Approval->find('all', array(
                    'conditions' => array(
                        'Approval.record' => $id,
                        'Approval.model_name' => $modelName
                    )
                ));
                foreach ($approves as $approve) {
                    $approve['Approval']['soft_delete'] = 1;
                    $this->Approval->save($approve, false);
                }
                $data['id']              = $id;
                $data['soft_delete']     = 1;
                $data['model_action']    = $this->params['action'];
                $data['system_table_id'] = $this->_get_system_table_id();
                $this->$modelName->save($data, false);
            }
        }
        $this->Session->setFlash(__('All selected value deleted'));
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    
    public function delete_all($ids = null)
    {
        $flag = 1;
        if ($_POST['data'][$this->name]['recs_selected'])
            $ids = explode('+', $_POST['data'][$this->name]['recs_selected']);
        
        $modelName = $this->modelClass;
        $this->loadModel('Approval');
        if (!empty($ids)) {
            
            foreach ($ids as $id) {
                if (!empty($id)) {
                    if ($id == $this->Session->read('User.id')) {
                        $flag = 0;
                        $this->Session->setFlash(__('Logged-in user can not be deleted!'));
                    } else {
                        $approves = $this->Approval->find('all', array(
                            'conditions' => array(
                                'Approval.record' => $id,
                                'Approval.model_name' => $modelName
                            )
                        ));
                        foreach ($approves as $approve) {
                            $approve['Approval']['soft_delete'] = 1;
                            $this->Approval->save($approve, false);
                        }
                        $data['id']          = $id;
                        $data['soft_delete'] = 1;
                        $this->$modelName->save($data, false);
                    }
                }
            }
            if (isset($id) && isset($data['id'])) {
                
                $data['model_action']    = $this->params['action'];
                $data['system_table_id'] = $this->_get_system_table_id();
                $modelName               = $this->modelClass;
                $this->$modelName->save($data, false);
                
            }
        }
        if ($flag)
            $this->Session->setFlash(__('All selected users deleted'));
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    
    /**
    /**
    * Bake Following Methods ONLY for USERS model - By TGS
    *
    */
    public function reset_password($params = null, $user = null)
    {
        
        $this->layout = 'login';
        if (empty($params)) {
            
            if ($this->request->is('post') || $this->request->is('put')) {
                
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.username' => $this->data['User']['username']
                    )
                ));
                if (!empty($user)) {
                    if ($user['Employee']['office_email'] != '') {
                        
                        $email = $user['Employee']['office_email'];
                    } else if ($user['Employee']['personal_email'] != '') {
                        $email = $user['Employee']['personal_email'];
                    } else {
                        $this->Session->setFlash(__('No email id for this user, try again.'), 'default', array(
                            'class' => 'alert-danger'
                        ));
                        $this->redirect(array(
                            'action' => 'reset_password'
                        ));
                    }
                    
                    $this->_send_password_reset($email, $this->data['User']['username']);
                } else {
                    $this->Session->setFlash(__('Invalid Username, try again.'), 'default', array(
                        'class' => 'alert-danger'
                    ));
                    $this->redirect(array(
                        'action' => 'reset_password'
                    ));
                }
            } else {
                $this->_send_password_reset();
            }
        } else {
            $this->_check_reset_password($params);
        }
    }
    
    public function _check_reset_password($params = null)
    {
        
        
        $user = $this->User->checkPasswordToken($params);
        if (empty($user)) {
            $this->Session->setFlash(__('Invalid password reset token, try again.'), 'default', array(
                'class' => 'alert-danger'
            ));
            $this->redirect(array(
                'action' => 'reset_password'
            ));
        }
        $this->set('token', $params);
        $this->set('username', $user['User']['username']);
    }
    
    public function save_user_password()
    {
        
        if ($this->request->is('post')) {
            if (!empty($this->request->data)) {
                
                $result                         = false;
                $user                           = $this->User->find('first', array(
                    'conditions' => array(
                        'User.status' => 1,
                        'User.password_token' => $this->request->data['User']['token']
                    ),
                    'recursive' => -1
                ));
                $user['User']['password_token'] = null;
                $user['User']['password']       = Security::hash($this->request->data['User']['password'], 'md5', true);
                $old_pwd                        = json_decode($user['User']['old_password']);
                
                $result = $this->requestAction(array(
                    'plugin' => 'password_setting_manager',
                    'controller' => 'password_settings',
                    'action' => 'check_password_validation',
                    $this->request->data['User']['password'],
                    $user['User']['old_password'],
                    $user['User']['username']
                ));
                
                if (!$result['valid']) {
                    $this->Session->setFlash($result['message'], 'default', array(
                        'class' => 'alert-danger'
                    ));
                    $this->redirect($this->referer());
                }
                
                if (count($old_pwd)) {
                    if (!in_array($user['User']['password'], $old_pwd)) {
                        array_unshift($old_pwd, $user['User']['password']);
                        $pwd_repeat_cnt = $this->requestAction(array(
                            'plugin' => 'password_setting_manager',
                            'controller' => 'password_settings',
                            'action' => 'get_password_repeat_len'
                        ));
                        if (count($old_pwd) > $pwd_repeat_cnt) {
                            $splited_arr = array_chunk($old_pwd, $pwd_repeat_cnt);
                            $old_pwd     = $splited_arr[0];
                            
                        }
                        $user['User']['old_password'] = json_encode($old_pwd);
                    }
                } else {
                    $user['User']['old_password'] = json_encode(array(
                        $user['User']['password']
                    ));
                }
                $user['User']['pwd_last_modified'] = date('Y-m-d H:i:s');
                if ($this->User->save($user, false)) {
                    $this->Session->setFlash(__('Password changed, you can now login with your new password.'), 'default', array(
                        'class' => 'alert-success'
                    ));
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                }
            }
        }
    }
    
    public function _get_mail_instance()
    {
        return new CakeEmail();
    }
    
    public function _send_password_reset($email = null, $username = null)
    {
        
        
        if (!empty($email) && !empty($username)) {
            $user = $this->User->passwordReset($email, $username);
            
            if (!empty($user)) {
                try {
                    $userCount = $this->User->find('count');
                    $companyId = $user['User']['company_id'];
                    
                    $this->loadModel('Company');
                    $smtpSetup = $this->Company->find('first', array(
                        'conditions' => array(
                            'Company.id' => $companyId
                        ),
                        'fields' => array(
                            'smtp_setup',
                            'is_smtp'
                        ),
                        'recursive' => -1
                    ));
                    
                    if (($userCount == 1) && ($smtpSetup['Company']['smtp_setup'] == 0)) {
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'reset_password',
                            $this->User->data['User']['password_token']
                        ));
                    } else {
                        
                        if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                        elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                        else $env = "";

                        App::uses('CakeEmail', 'Network/Email');
                        if ($smtpSetup['Company']['is_smtp'] == 1)
                            $EmailConfig = new CakeEmail("smtp");
                        if ($smtpSetup['Company']['is_smtp'] == 0)
                            $EmailConfig = new CakeEmail("default");
                        $EmailConfig->to($email);
                        $EmailConfig->subject('FlinkISO: Password reset request');
                        $baseurl = Router::url('/', true) . $this->request->params['controller'] . "/reset_password/" . $this->User->data['User']['password_token'];
                        $EmailConfig->template('passwordTmp');
                        $EmailConfig->viewVars(array(
                            'baseurl' => $baseurl,
                            'env'=>$env
                        ));
                        $EmailConfig->emailFormat('html');
                        $EmailConfig->send();
                        
                        $this->Session->setFlash(__('Please check the email you have registered with us. An email has been sent with instruction to reset password.'), 'default', array(
                            'class' => 'alert-success'
                        ));
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                }
                catch (Exception $e) {
                    $this->Session->setFlash(__('Can not notify user using email. Please check SMTP details and email address is correct.'), 'default', array(
                        'class' => 'alert-danger'
                    ));
                }
            }
        }
        $this->render('request_password_change');
    }
    
    public function login()
    {
        
        if (!$this->request->is('post')) {
            $this->loadModel('Company');
            $company_message = $this->Company->find('first', array(
                'recursive' => -1
            ));
            $this->set('company_message', $company_message);
            
        }
        
        if (!file_exists(APP . 'Config/installed.txt') && !file_exists(APP . 'Config/installed_db.txt')) {
            $this->redirect(array(
                'controller' => 'installer',
                'action' => 'index'
            ));
            
        } else if (!file_exists(APP . 'Config/installed.txt') && file_exists(APP . 'Config/installed_db.txt')) {
            // the routes for when the application has been db installed but user not registered
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'register'
            ));
        }
        
        if ($this->request->is('ajax') == true) {
            $str = "Your session has expired, please login to continue";
            $this->Session->setFlash(__($str, true), 'default', array(
                'class' => 'alert-danger'
            ));
            $this->layout = 'ajax';
        } else {
            // $this->layout = 'login';
        }
        
        if ($this->Session->read('User.id')) {
            
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'pm_dashboard'
            ));
        }
        
        
        if ($this->request->is('post')) {
            $this->loadModel('Company');
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.status' => 1,
                    'User.soft_delete' => 0,
                    'User.publish' => 1,
                    'User.username' => $this->data['User']['username']
                )
            ));
            
            if ($user) {
                $allUsers    = $this->User->find('all', array(
                    'conditions' => array(
                        'User.login_status' => 1,
                        'User.id <>' => $user['User']['id']
                    )
                ));
                $currentTime = date('Y-m-d H:i:s');
                foreach ($allUsers as $user) {
                    $lastActTime = date('Y-m-d H:i:s', strtotime('+10 mins', strtotime($user['User']['last_activity'])));
                    if ($lastActTime < $currentTime) {
                        $this->User->read(null, $user['User']['id']);
                        $data['User']['last_activity'] = date('Y-m-d H:i:s');
                        $data['User']['login_status']  = 0;
                        $this->User->save($data, false);
                    }
                }
                
                $companyId   = $user['User']['company_id'];
                $companyData = $this->Company->find('first', array(
                    'conditions' => array(
                        'id' => $companyId
                    ),
                    'recursive' => -1
                ));
                $currentTime = date('Y-m-d H:i:s');
                if ($companyData && $companyData['Company']['allow_multiple_login'] == 0 && $user['User']['login_status'] == 1) {
                    $this->Session->setFlash(__('Already Logged in. Please wait while your earlier session expires.', true), 'default', array(
                        'class' => 'alert-danger'
                    ));
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                }
                if (trim($user['User']['password']) != trim(Security::hash($this->data['User']['password'], 'md5', true))) {
                    if ($this->Session->read('Login.username') == $this->data['User']['username'] && $companyData['Company']['limit_login_attempt']) {
                        $this->Session->write('Login.count', $this->Session->read('Login.count') + 1);
                    } else {
                        $this->Session->write('Login.count', 1);
                    }
                    $this->Session->write('Login.username', $this->data['User']['username']);
                    if (3 <= ($this->Session->read('Login.count'))) {
                        $this->User->read(null, $user['User']['id']);
                        $data['User']['status'] = 3;
                        $this->User->save($data, false);
                        
                        $this->Session->destroy();
                        $this->Session->setFlash(__('Your account is locked', true), 'default', array(
                            'class' => 'alert-danger'
                        ));
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    } else {
                        $this->Session->write('Login.username', $user['User']['username']);
                    }
                    if ($companyData['Company']['limit_login_attempt'])
                        $this->Session->setFlash(__('Incorrect login credential : You have ' . (3 - $this->Session->read('Login.count')) . ' attempts left', true), 'default', array(
                            'class' => 'alert-danger'
                        ));
                    else
                        $this->Session->setFlash(__('Incorrect login credential', true), 'default', array(
                            'class' => 'alert-danger'
                        ));
                    
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                    
                    /* Company Account Expiry Check */
                    /*
                    } elseif ($flinkisoEndDate < $currentTime) {
                    $this->Session->setFlash(__('Your Company account Has been expired...', true));
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                    */
                } else {
                    
                    if ($user['User']['last_login'] == '0000-00-00 00:00:00' && $user['User']['last_activity'] == '0000-00-00 00:00:00') {
                        $this->loadModel('Company');
                        $companyUsers = $this->User->find('all', array(
                            'conditions' => array(
                                'User.company_id' => $companyId,
                                'User.last_login !=' => '0000-00-00 00:00:00',
                                'User.last_activity !=' => '0000-00-00 00:00:00'
                            ),
                            'recursive' => -1
                        ));
                        
                        if (count($companyUsers) == 0) {
                            $CompanyData['Company']['id']                  = $companyId;
                            $CompanyData['Company']['flinkiso_start_date'] = date('Y-m-d H:i:s');
                            $CompanyData['Company']['flinkiso_end_date']   = date('Y-m-d H:i:s', strtotime('+15 days', strtotime(date('Y-m-d H:i:s'))));
                            $this->Company->save($CompanyData, false);
                        }
                    }
                }
                $result = $this->requestAction(array(
                    'plugin' => 'password_setting_manager',
                    'controller' => 'password_settings',
                    'action' => 'get_password_change_remind',
                    urlencode($user['User']['pwd_last_modified'])
                ));
                if (!$result['valid']) {
                    $this->Session->setFlash(__($result['msg']), 'default', array(
                        'class' => 'alert-danger'
                    ));
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'reset_password'
                    ));
                }
                
                $this->User->read(null, $user['User']['id']);
                if ($companyData['Company']['two_way_authentication'] == 1) {
                    $officeEmailId   = $user['Employee']['office_email'];
                    $personalEmailId = $user['Employee']['personal_email'];
                    if ($officeEmailId != '') {
                        $email = $officeEmailId;
                    } else if ($personalEmailId != '') {
                        $email = $personalEmailId;
                    }
                    $otp_code = $this->User->generateToken(6);
                    if ($email) {
                        
                        if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                        elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                        else $env = "";

                        try {
                            App::uses('CakeEmail', 'Network/Email');
                            if ($user['Company']['is_smtp'] == 1)
                                $EmailConfig = new CakeEmail("smtp");
                            if ($user['Company']['is_smtp'] == 0)
                                $EmailConfig = new CakeEmail("default");
                            $EmailConfig->to($email);
                            $EmailConfig->subject('One time OTP Code');
                            $EmailConfig->template('otpCode');
                            $EmailConfig->viewVars(array(
                                'otp_code' => $otp_code,
                                'env'=>$env
                            ));
                            $EmailConfig->emailFormat('html');
                            $EmailConfig->send();
                        }
                        catch (Exception $e) {
                            $this->Session->setFlash(__('Can not email OTP Code. Please check SMTP details and email address is correct.'));
                            $this->redirect(array(
                                'controller' => 'users',
                                'action' => 'login'
                            ));
                        }
                        
                    }
                    
                    if (isset($otp_code)) {
                        $this->Session->write('OPTCode', $otp_code);
                        $this->Session->write('UserIdentity', $user['User']['id']);
                    }
                }
                
                if (isset($otp_code)) {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'opt_check'
                    ));
                } else {
                    $_SESSION['User']['id']        = $user['User']['id'];
                    $data['User']['last_login']    = date('Y-m-d H:i:s');
                    $data['User']['last_activity'] = date('Y-m-d H:i:s');
                    $data['User']['login_status']  = 0; //1
                    $this->User->save($data, false);
                    $this->Session->write('User.id', $user['User']['id']);
                    $this->Session->write('User.employee_id', $user['Employee']['id']);
                    $this->Session->write('User.branch_id', $user['User']['branch_id']);
                    $this->Session->write('User.department_id', $user['User']['department_id']);
                    $this->Session->write('User.branch', $user['Branch']['name']);
                    $this->Session->write('User.department', $user['Department']['name']);
                    $this->Session->write('User.name', $user['Employee']['name']);
                    $this->Session->write('User.username', $user['User']['username']);
                    $this->Session->write('User.lastLogin', $user['User']['last_login']);
                    $this->Session->write('User.is_mr', $user['User']['is_mr']);
                    $this->Session->write('User.company_id', $user['User']['company_id']);
                    $this->Session->write('User.is_smtp', $user['Company']['is_smtp']);
                    $this->Session->write('User.division_id', $user['User']['division_id']);
                    
                    
                    if ($user['User']['is_mr'] == 1)
                        $this->Session->write('User.is_view_all', 1);
                    else
                        $this->Session->write('User.is_view_all', $user['User']['is_view_all']);
                    $this->Session->write('User.is_approvar', $user['User']['is_approvar']);
                    $this->loadModel('Language');
                    $languageData = array();
                    $languageData = $this->Language->find('first', array(
                        'conditions' => array(
                            'Language.id' => $user['User']['language_id']
                        ),
                        'recursive' => -1
                    ));
                    
                    $this->Session->write('SessionLanguage', $languageData['Language']['id']);
                    $this->Session->write('SessionLanguageCode', $languageData['Language']['short_code']);

                    if ($user['User']['agree'] && $user['User']['agree'] != 0) {
                        $this->Session->write('TANDC', 1);
                        $this->loadModel('UserSession');
                        $this->UserSession->create();
                        $data['UserSession']['ip_address']      = $_SERVER['REMOTE_ADDR'];
                        $data['UserSession']['browser_details'] = json_encode($_SERVER);
                        $data['UserSession']['start_time']      = date('Y-m-d H:i:s');
                        $data['UserSession']['end_time']        = date('Y-m-d H:i:s');
                        $data['UserSession']['user_id']         = $this->Session->read('User.id');
                        $data['UserSession']['employee_id']     = $this->Session->read('User.employee_id');
                        $data['UserSession']['company_id']      = $this->Session->read('User.company_id');
                        $data['UserSession']['division_id']     = $this->Session->read('User.division_id');
                        $this->Session->write('User.assigned_branches', $user['User']['assigned_branches']);
                        $this->UserSession->save($data, false);
                        $this->Session->write('User.user_session_id', $this->UserSession->id);
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'terms_and_conditions'
                        ));
                    } else {
                        
                        $this->loadModel('UserSession');
                        $this->UserSession->create();
                        $data['UserSession']['ip_address']      = $_SERVER['REMOTE_ADDR'];
                        $data['UserSession']['browser_details'] = json_encode($_SERVER);
                        $data['UserSession']['start_time']      = date('Y-m-d H:i:s');
                        $data['UserSession']['end_time']        = date('Y-m-d H:i:s');
                        $data['UserSession']['user_id']         = $this->Session->read('User.id');
                        $data['UserSession']['employee_id']     = $this->Session->read('User.employee_id');
                        $data['UserSession']['company_id']      = $this->Session->read('User.company_id');
                        $this->Session->write('User.assigned_branches', $user['User']['assigned_branches']);
                        $data['UserSession']['division_id']     = $this->Session->read('User.division_id');
                        $this->UserSession->save($data, false);
                        $this->Session->write('User.user_session_id', $this->UserSession->id);
                        
                        
                        $this->loadModel('MasterListOfFormat');
                        $checkForms = $this->MasterListOfFormat->find('count', array(
                            'conditions' => array(
                                'MasterListOfFormat.company_id' => $this->Session->read('User.company_id')
                            )
                        ));
                        
                        $this->redirect(array(
                            'action' => 'pm_dashboard'
                        ));                        
                    }
                }
                
            }
            
            $this->Session->setFlash(__('Incorrect Login Credentials or your account is locked or already logged in', true), 'default', array(
                'class' => 'alert-danger'
            ));
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        $this->loadModel('PasswordSetting');
        $this->loadModel('Company');
        $this->PasswordSetting->recursive                                 = -1;
        $this->Company->recursive                                         = -1;
        $password_setting                                                 = $this->PasswordSetting->find('first');
        $companies                                                        = $this->Company->find('first');
        $password_setting['PasswordSetting']['activate_password_setting'] = $companies['Company']['activate_password_setting'];
        $opt_code                                                         = $companies['Company']['two_way_authentication'];
        $this->set('password_setting', $password_setting['PasswordSetting']);
        $this->set('opt_code', $opt_code);
    }
    
    public function logout()
    {
        if ($this->Session->read('User.id')) {
            $this->User->read(null, $this->Session->read('User.id'));

            $data['User']['login_status'] = 0;
            $this->User->save($data, false);
            
            $this->loadModel('UserSession');
            $data['UserSession']['id']       = $this->Session->read('User.user_session_id');
            
            // $data['UserSession']['end_time'] = date('Y-m-d H:i:s');
            debug($this->Session->read('User.user_session_id'));
            $data = $this->UserSession->find('first',array('recursive'=>-1,'conditions'=>array('UserSession.id'=>$this->Session->read('User.user_session_id'))));
            if($data){
                $data['UserSession']['end_time'] = date('Y-m-d H:i:s');
                $this->UserSession->create();
                $this->UserSession->save($data,false);
            }
            

            $this->Session->write('User.id', NULL);
            $this->Session->destroy('User');
        }
        $this->Session->setFlash(__('You have been logged out' . $this->Session->read('User.id'), true), 'default', array(
            'class' => 'alert-danger'
        ));
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'login'
        ));
    }

    public function personal_admin(){
        $this->loadModel('FireExtinguisher');
        $FireExt = $this->FireExtinguisher->find('count', array('conditions' => array('FireExtinguisher.publish' => 1, 'FireExtinguisher.soft_delete' => 0), 'recursive' => -1));
        $this->set('countFirExt', $FireExt);

        $this->loadModel('HousekeepingChecklist');
        $houseKeeping = $this->HousekeepingChecklist->find('count', array('conditions' => array('HousekeepingChecklist.publish' => 1, 'HousekeepingChecklist.soft_delete' => 0), 'recursive' => -1));
        $this->set('countHouseKeeping', $houseKeeping);

        $this->loadModel('HousekeepingResponsibility');
        $houseKeepingResp = $this->HousekeepingResponsibility->find('count', array('conditions' => array('HousekeepingResponsibility.publish' => 1, 'HousekeepingResponsibility.soft_delete' => 0), 'recursive' => -1));
        $this->set('countHouseKeepingResp', $houseKeepingResp);
        //Code for house keeping responsibility
        $this->loadModel('Housekeeping');
        if ($this->request->is('post')) {
            
            foreach ($this->request->data['Housekeeping'] as $houseKeeping) {
                if (!empty($houseKeeping['id']) && (isset($houseKeeping['task_performed']) || isset($houseKeeping['comments']))) {
                    $houseKeeping['backup_date'] = date('Y-m-d');
                    $houseKeeping['publish'] = 1;
                    $houseKeeping['employee_id'] = $this->Session->read('User.employee_id');
                    $houseKeeping['branchid'] = $this->Session->read('User.branch_id');
                    $houseKeeping['departmentid'] = $this->Session->read('User.department_id');
                    $houseKeeping['modified_by'] = $this->Session->read('User.id');
                    $this->Housekeeping->save($houseKeeping, false);
                } else if (empty($houseKeeping['id']) && isset($houseKeeping['task_performed']) && $houseKeeping['task_performed'] > 0) {
                    $this->Housekeeping->create();
                    $houseKeeping['backup_date'] = date('Y-m-d');
                    $houseKeeping['publish'] = 1;
                    $houseKeeping['employee_id'] = $this->Session->read('User.employee_id');
                    $houseKeeping['branchid'] = $this->Session->read('User.branch_id');
                    $houseKeeping['departmentid'] = $this->Session->read('User.department_id');
                    $houseKeeping['created_by'] = $this->Session->read('User.id');
                    $houseKeeping['modified_by'] = $this->Session->read('User.id');
                    $this->Housekeeping->save($houseKeeping, false);
                }
            }
            $this->Session->setFlash(__('The housekeeping has been saved'));
            $this->redirect(array('controller'=>'users', 'action' => 'personal_admin'));
        }
        $onlyBranch = null;
        $onlyOwn = null;
        $condition1 = null;
        $condition2 = null;
        $condition3 = null;
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('HousekeepingResponsibility.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('HousekeepingResponsibility.created_by' => $this->Session->read('User.id'));
        if ($this->Session->read('User.is_mr') == 0) {
            $condition3 = array('HousekeepingResponsibility.employee_id' => $this->Session->read('User.employee_id'));
        }
        $finalCond = array('OR' => array($onlyBranch, $onlyOwn, $condition3));
        if ($this->request->params['named']) {
            if ($this->request->params['named']['published'] == null)
                $condition1 = null;
            else
                $condition1 = array('HousekeepingResponsibility.publish' => $this->request->params['named']['published']);
            if ($this->request->params['named']['soft_delete'] == null)
                $condition2 = null;
            else
                $condition2 = array('HousekeepingResponsibility.soft_delete' => $this->request->params['named']['soft_delete']);
            if ($this->request->params['named']['soft_delete'] == null)
                $conditions = array($onlyBranch, $onlyOwn, $condition1, $condition3, 'HousekeepingResponsibility.soft_delete' => 0);
            else
                $conditions = array($condition1, $condition2, $finalCond);
        }else {
            $conditions = array($finalCond, 'HousekeepingResponsibility.soft_delete' => 0);
        }
        $options = array('order' => array('HousekeepingResponsibility.sr_no' => 'DESC'), 'conditions' => array($conditions));
        $this->HousekeepingResponsibility->recursive = 0;
        $houseKeepings = $this->HousekeepingResponsibility->find('all', $options);
        $this->loadModel('Schedule');
        $scheduleList = $this->Schedule->find('list', array('conditions' => array('Schedule.publish' => 1, 'Schedule.soft_delete' => 0), 'recursive' => -1));
        
        foreach ($houseKeepings as $key => $houseKeeping) {
            $test = $this->Housekeeping->find('first', 
                array('order' => array('Housekeeping.sr_no' => 'DESC'), 
                    'conditions' => array(
                        'Housekeeping.housekeeping_responsibility_id' => $houseKeeping['HousekeepingResponsibility']['id'], 
                        // 'Housekeeping.employee_id' => $houseKeeping['HousekeepingResponsibility']['employee_id']
                        ), 
                    'recursive' => -1)
                );
            
            if (count($test)) {
                if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'dailly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Dailly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'daily' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Daily') {
                    if (date('Y-m-d', strtotime($test['Housekeeping']['created'])) == date('Y-m-d')) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                } else if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'weekly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Weekly') {

                    if (date('W', strtotime($test['Housekeeping']['created'])) == date('W')) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                } else if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'monthly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Monthly') {
                    if (date('m', strtotime($test['Housekeeping']['created'])) == date('m')) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                } else if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'quarterly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Quarterly') {
                    $created = date('Y-m-d', strtotime($houseKeeping['HousekeepingResponsibility']['created']));
                    $currentDate = date('Y-m-d', strtotime($houseKeeping['HousekeepingResponsibility']['created']));
                    $lastQuarter = date('Y-m-d');
                    $nextQuarter = date('Y-m-d');
                    $dateArray = array();
                    $i = 0;
                    while ($currentDate <= $lastQuarter) {
                        $nextQuarter = date('Y-m-d', strtotime('+3 month', strtotime($currentDate)));
                        $dateArray[$i]['currentDate'] = $currentDate;
                        $dateArray[$i]['nextQuarter'] = $nextQuarter;
                        $currentDate = $nextQuarter;
                        $i++;
                    }
                    $count = count($dateArray);

                    if (date('Y-m-d', strtotime($test['Housekeeping']['created'])) >= $dateArray[$count - 1]['currentDate'] && date('Y-m-d', strtotime($test['Housekeeping']['created'])) <= $dateArray[$count - 1]['nextQuarter']) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                } else if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'yearly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Yearly') {
                    if (date('y', strtotime($test['Housekeeping']['created'])) == date('y')) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                }
            }
        }        
        $this->set('editId', $id);
        $this->set('houseKeepings', $houseKeepings);
        return count($houseKeepings);
    }
    
    public function dashboard()
    {
        
        
        $this->set('new_helps', false);
        App::uses('ConnectionManager', 'Model');
        $dataSource = ConnectionManager::getDataSource('default');
        $prefix     = $dataSource->config['prefix'];
        
        $this->set(array(
            'show_nc_alert' => false,
            'dbsize' => null,
            'task_performed' => null,
            'tasks' => null
        ));
        
        $this->loadModel('Company');
        $company = $this->Company->find('first', array(
            'conditions' => array(
                'id' => $this->Session->read('User.company_id')
            ),
            'fields' => 'smtp_setup',
            'recursive' => -1
        ));
        if ($company['Company']['smtp_setup'] == 0)
            $this->set('smtp_alert', true);
        else
            $this->set('smtp_alert', false);
        $company = $this->Company->find('first', array(
            'conditions' => array(
                'id' => $this->Session->read('User.company_id')
            ),
            'fields' => 'sample_data',
            'recursive' => -1
        ));
        
        if ($company['Company']['sample_data'] == 1)
            $this->set('sampleData', true);
        else
            $this->set('sampleData', false);
        
        //Approvals
        $this->loadModel('Approval');
        $this->Approval->recursive = 0;
        
        
        $approvals = $this->Approval->find('all', array(
            'order' => array(
                'Approval.sr_no' => 'desc'
            ),
            'group' => 'Approval.record',
            'conditions' => array(
                'Approval.status != ' => 'Approved',
                'Approval.user_id' => $this->Session->read('User.id'),
                'Approval.soft_delete' => 0,
                'Approval.record_status' => 1
            ),
            'fields' => array(
                'From.name',
                'Approval.id',
                'Approval.model_name',
                'Approval.controller_name',
                'Approval.record',
                'Approval.comments',
                'Approval.created'
            )
        ));
        
        $approvalsCount = count($approvals);
        
        $this->set(compact('approvalsCount', 'approvals'));
        
        if ($this->Session->read('User.is_mr') == true or $this->Session->read('User.department_id') == '5239c2ec-3240-456f-909f-5891c6c3268c') {
            
            
            //Blocked User
            $blockedUserCount = $this->User->find('count', array(
                'conditions' => array(
                    'User.status' => 3,
                    'User.publish' => 1,
                    'User.soft_delete' => 0
                ),
                'recursive' => -1
            ));
            $blockedUser      = $this->User->find('all', array(
                'conditions' => array(
                    'User.status' => 3,
                    'User.publish' => 1,
                    'User.soft_delete' => 0
                ),
                'recursive' => -1
            ));
            $this->set(compact('blockedUserCount', 'blockedUser'));
        }
        
        // Get Material QC Count
        
        $this->loadModel('DeliveryChallanDetail');
        $materialQCrequiredCount = $this->DeliveryChallanDetail->find('count', array(
            'conditions' => array(
                'DeliveryChallanDetail.publish' => 1,
                'DeliveryChallanDetail.soft_delete' => 0,
                'DeliveryChallanDetail.material_qc_required' => 1,
                '(select count(' . $prefix . 'stocks.id) as total from ' . $prefix . 'stocks where ' . $prefix . 'stocks.material_id = DeliveryChallanDetail.material_id AND ' . $prefix . 'stocks.delivery_challan_id  = DeliveryChallanDetail.delivery_challan_id )<=0 '
            ),
            'group' => array(
                'DeliveryChallanDetail.material_id, DeliveryChallanDetail.delivery_challan_id'
            ),
            'joins' => array(
                array(
                    'table' => 'material_quality_checks',
                    'alias' => 'MaterialQualityCheck',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'MaterialQualityCheck.material_id = DeliveryChallanDetail.material_id'
                    )
                )
            )
        ));
        $this->set('materialQCrequiredCount', $materialQCrequiredCount);
        // Get Assigned Calibration Count 
        
        $this->loadModel('Calibration');
        $countNextCalibrations = $this->Calibration->find('count', array(
            'conditions' => array(
                'Device.employee_id' => $this->Session->read('User.employee_id'),
                'Device.publish' => 1,
                'Calibration.next_calibration_date >=' => date('Y-m-d'),
                'Calibration.publish' => 1
            )
        ));
        $this->set('countNextCalibrations', $countNextCalibrations);
        
        $this->_get_dashboard_counts();
        //Get Count for Materials.
        
        $this->loadModel('Material');
        App::uses('ConnectionManager', 'Model');
        $dataSource   = ConnectionManager::getDataSource('default');
        $prefix       = $dataSource->config['prefix'];
        $qcStepsCount = $this->Material->find('count', array(
            'conditions' => array(
                'Material.soft_delete' => 0,
                'Material.publish' => 1,
                'Material.qc_required' => 1,
                'Material.id NOT IN (select material_id from ' . $prefix . 'material_quality_checks)'
            )
        ));
        $this->set('qcStepsCount', $qcStepsCount);
        
        //Get Count for Materials that required QC.
        
        // Get Count Device Maintainance.
        
        $this->loadModel('DeviceMaintenance');
        $currentDate              = date('Y-m-d');
        $nextDate                 = date("Y-m-d", strtotime("$currentDate +7 day"));
        $deviceMaintainancesCount = $deviceMaintainancesCount = $this->DeviceMaintenance->find('count', array(
            'conditions' => array(
                'DeviceMaintenance.next_maintanence_date between ? and ?' => array(
                    $currentDate,
                    $nextDate
                )
            ),
            'recursive' => 0
        ));
        $this->set('deviceMaintainancesCount', $deviceMaintainancesCount);
               
        // get capa by ratings

        $capa_ratings = $this->requestAction(array('controller'=>'corrective_preventive_actions','action'=>'capa_ratings'));
        $this->set('capaRatings',$capa_ratings);
        
        //get upload folder size
        
        $task_count = $this->requestAction(array('controller'=>'tasks','action'=>'get_task','render'=>'no'));
        $project_task_count = $this->requestAction(array('controller'=>'tasks','action'=>'get_project_task','render'=>'no'));
        $process_task_count = $this->requestAction(array('controller'=>'tasks','action'=>'get_process_task','render'=>'no'));
        $cc_task_count = $this->requestAction(array('controller'=>'tasks','action'=>'get_cc_task','render'=>'no'));
        // echo ">>" . $cc_task_count;
        $housekeeping_task_count = $this->requestAction(array('controller'=>'users','action'=>'personal_admin','render'=>'no'));

        $this->set(array('task_count'=>$task_count,'project_task_count'=>$project_task_count,'process_task_count'=>$process_task_count,'housekeeping_task_count'=>$housekeeping_task_count,'cc_task_count'=>$cc_task_count));

        $var        = APP;
        $uploadSize = $this->_folder_size($var . 'webroot/files');
        $uploadSize = $this->_format_file_size($uploadSize[0]);
        $this->set('uploadSize', $uploadSize);
        
        
        $this->loadModel('SuggestionForm');
        
        if ($this->Session->read('User.is_mr') != 1) {
            $this->loadModel('Company');
            $companyMessage = $this->Company->find('first', array(
                'fields' => array(
                    'description',
                    'welcome_message',
                    'quality_policy',
                    'mission_statement',
                    'vision_statement'
                ),
                'recursive' => -1
            ));
            if ($companyMessage)
                $this->set('companyMessage', $companyMessage);
            else
                $this->set('companyMessage', null);
        } else {
            $this->set('companyMessage', null);
        }
        
        
        $this->_meeting_details_reminder();
        $this->_get_data_entry();
        if (file_exists(Configure::read('MediaPath') . "files/" . $this->Session->read('User.company_id') . DS . date('Y-m') . DS . "/rediness.txt")) {
            $file      = new File(Configure::read('MediaPath') . "files/" . $this->Session->read('User.company_id') . DS . date('Y-m') . DS . "/rediness.txt");
            $readiness = $file->read();
        }
        
        //get objectives & processes where this user is involved
        
        $this->loadModel('ProcessTeam');
        $teams = $this->ProcessTeam->find('all', array(
            'conditions' => array(
                'or' => array(
                    'ProcessTeam.team LIKE' => '%' . $this->Session->read('User.id') . '%',
                    'Process.owner_id' => $this->Session->read('User.id')
                )
            ),
            'fields' => array(
                'Process.id',
                'Process.title',
                'Objective.id',
                'Objective.title',
                'ProcessTeam.start_date',
                'ProcessTeam.end_date'
            ),
            'order' => array(
                'ProcessTeam.end_date' => 'ASC'
            ),
            'limit' => 3
        ));
        $this->set('teamObjectives', $teams);

        $this->loadModel('Objective');
        $objectives = $this->Objective->find('all',array(
            'recursive'=>1,
            'conditions'=>array(
                'Objective.current_status'=>0,
                'Objective.employee_id'=>$this->Session->read('User.employee_id'))));
        $this->set('objectives',$objectives);

        
        //get documents by this user
        $this->loadModel('FileUpload');
        $my_docs = $this->FileUpload->find('count', array(
            'conditions' => array(
                'FileUpload.user_id' => $this->Session->read('User.id'),
                'FileUpload.file_status' => 1,
                'FileUpload.publish' => 1,
                'FileUpload.soft_delete' => 0
            )
        ));
        $this->set('my_docs', $my_docs);
        
        $my_materials = $this->Material->find('count', array(
            'Material.publish' => 1,
            'Material.soft_delete' => 0,
            'Material.created_by' => $this->Session->read('User.id')
        ));
        $this->set('my_materials', $my_materials);
        
        $this->loadModel('Product');
        $my_products = $this->Product->find('count', array(
            'Product.publish' => 1,
            'Product.soft_delete' => 0,
            'Product.created_by' => $this->Session->read('User.id')
        ));
        $this->set('my_products', $my_products);
        
        $this->loadModel('ChangeAdditionDeletionRequest');
        $my_change_addition_deletion_requests = $this->ChangeAdditionDeletionRequest->find('count', array(
            'ChangeAdditionDeletionRequest.publish' => 1,
            'ChangeAdditionDeletionRequest.soft_delete' => 0,
            'ChangeAdditionDeletionRequest.created_by' => $this->Session->read('User.id')
        ));
        $this->set('my_change_addition_deletion_requests', $my_change_addition_deletion_requests);
        $this->loadModel('CustomerComplaint');
        $my_customer_complaints = $this->CustomerComplaint->find('count', array(
            'CustomerComplaint.publish' => 1,
            'CustomerComplaint.soft_delete' => 0,
            'CustomerComplaint.created_by' => $this->Session->read('User.id')
        ));
        $this->set('my_customer_complaints', $my_customer_complaints);
        $this->objective_monitoring();  


        // meeting pending actions
        $this->loadModel('MeetingTopic');      
        $meeting_actions = $this->MeetingTopic->find('all',array(
            'fields'=>array('MeetingTopic.id','MeetingTopic.title','MeetingTopic.meeting_id','Meeting.title','MeetingTopic.target_date'),
            'conditions'=>array('MeetingTopic.action_status'=>0, 'MeetingTopic.employee_id'=>$this->Session->read('User.employee_id'))));
        $this->set('meeting_actions',$meeting_actions);

        if($this->request->params['named']['standard_id']){
            $standard = array('MasterListOfFormatCategory.standard_id'=>$this->request->params['named']['standard_id']);
            $this->set('standard_id',$this->request->params['named']['standard_id']);
        }else{
            $standard = array('MasterListOfFormatCategory.standard_id'=>'58511238-fba8-4db9-aad0-833fc20b8995');
            $this->set('standard_id','58511238-fba8-4db9-aad0-833fc20b8995');
        }
        $this->loadModel('MasterListOfFormatCategory');
        $masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list', array('conditions' => array($standard,'MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));

        $this->loadModel('Standard');
        $standards = $this->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));

        $this->set(compact('masterListOfFormatCategories','standards'));
        $this->set('standard',$standard);

        // FMEA actions assigned
        $this->loadModel('FmeaAction');
        $fmeasCount = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.current_status'=>0,'FmeaAction.employee_id'=>$this->Session->read('User.employee_id')),'recursive'=>1));
        
        $this->set(array('fmeaCount'=>$fmeasCount));
        $this->objective_monitoring_employee();
    }
    
    public function _get_dashboard_counts()
    {
        // get Customer Complaints Count
        $this->loadModel('CustomerComplaint');
        $complaintReceived = $this->CustomerComplaint->find('count', array(
            'conditions' => array(
                'CustomerComplaint.soft_delete' => 0,
                'CustomerComplaint.publish' => 1
            ),
            'recursive' => -1
        ));
        
        if($this->Session->read('User.is_mr') != 1){
            $mr_cc_con = array('CustomerComplaint.employee_id' => $this->Session->read('User.employee_id'));
        }else{
            $mr_cc_con = array();
        }
        
        $complaintOpen     = $this->CustomerComplaint->find('count', array(
            'conditions' => array(
                $mr_cc_con,
                'CustomerComplaint.current_status !=' => 1,
                'CustomerComplaint.soft_delete' => 0,
                'CustomerComplaint.publish' => 1
            ),
            'recursive' => -1
        ));
        
        // get CAPA Count
        $this->loadModel('CorrectivePreventiveAction');
        $capaReceived = $this->CorrectivePreventiveAction->find('count', array(
            'conditions' => array(
                'CorrectivePreventiveAction.soft_delete' => 0,
                'CorrectivePreventiveAction.publish' => 1
            ),
            'recursive' => -1
        ));
        $openCapa     = $this->CorrectivePreventiveAction->find('count', array(
            'conditions' => array(
                'CorrectivePreventiveAction.current_status' => 0,
                'CorrectivePreventiveAction.soft_delete' => 0,
                'CorrectivePreventiveAction.publish' => 1
            ),
            'recursive' => -1
        ));
        $closeCapa    = $this->CorrectivePreventiveAction->find('count', array(
            'conditions' => array(
                'CorrectivePreventiveAction.current_status' => 1,
                'CorrectivePreventiveAction.soft_delete' => 0,
                'CorrectivePreventiveAction.publish' => 1
            ),
            'recursive' => -1
        ));
        
        
        $this->loadModel('CapaRootCauseAnalysi');
        $assignedRootCauseAnalysiCapa = $this->CapaRootCauseAnalysi->find('count', array(
            'conditions' => array(
                'OR' => array(
                    'CapaRootCauseAnalysi.employee_id' => $this->Session->read('User.employee_id'),
                    'CapaRootCauseAnalysi.action_assigned_to' => $this->Session->read('User.employee_id'),
                    'CapaRootCauseAnalysi.determined_by' => $this->Session->read('User.employee_id')
                    
                ),
                'CapaRootCauseAnalysi.current_status' => 0,
                'CapaRootCauseAnalysi.soft_delete' => 0,
                'CapaRootCauseAnalysi.publish' => 1
            )
        ));
        
        $this->loadModel('CapaInvestigation');
        $assignedInvestigationCapa = $this->CapaInvestigation->find('count', array(
            'conditions' => array(
                'CapaInvestigation.employee_id' => $this->Session->read('User.employee_id'),
                'CapaInvestigation.current_status' => 0,
                'CapaInvestigation.soft_delete' => 0,
                'CapaInvestigation.publish' => 1
            )
        ));
        
        $this->loadModel('CapaRevisedDate');
        $assignedRevisedDateCapa = $this->CapaRevisedDate->find('count', array(
            'conditions' => array(
                'CapaRevisedDate.employee_id' => $this->Session->read('User.employee_id'),
                'CapaRevisedDate.soft_delete' => 0,
                'CapaRevisedDate.publish' => 1
            )
        ));
        
        $this->set(compact('capaReceived', 'openCapa', 'closeCapa'));
        $this->set(compact('assignedRootCauseAnalysiCapa', 'assignedInvestigationCapa', 'assignedRevisedDateCapa'));
        
        $this->loadModel('NonConformingProductsMaterial');
        $countNCs     = $this->NonConformingProductsMaterial->find('count', array(
            'conditions' => array(
                'NonConformingProductsMaterial.soft_delete' => 0,
                'NonConformingProductsMaterial.publish' => 1
            )
        ));
        $countNCsOpen = $this->NonConformingProductsMaterial->find('count', array(
            'conditions' => array(
                'NonConformingProductsMaterial.status' => 0,
                'NonConformingProductsMaterial.soft_delete' => 0,
                'NonConformingProductsMaterial.publish' => 1
            )
        ));
        
        $this->set(compact('countNCs', 'countNCsOpen'));
        
        // get ChangeAdditionDeletionRequest Count
        $this->loadModel('ChangeAdditionDeletionRequest');
        $docChangeReq = $this->ChangeAdditionDeletionRequest->find('count', array(
            'conditions' => array(
                'ChangeAdditionDeletionRequest.soft_delete' => 0,
                'ChangeAdditionDeletionRequest.publish' => 1
            ),
            'recursive' => -1
        ));
        $this->loadModel('ProposalFollowup');
        $followupconditions = array(
            'ProposalFollowup.followup_assigned_to'=>$this->Session->read('User.id'),
            'Proposal.proposal_status' => array(1,2,4,5),
            'ProposalFollowup.next_follow_up_date >'=>date('Y-m-d'),
            'ProposalFollowup.followup_date'=>date('Y-m-d'));
        
        $todays_followups = $this->ProposalFollowup->find('count',array('conditions'=>$followupconditions));
        $this->set('todays_followups',$todays_followups);
        
        $file = new File(Configure::read('MediaPath') . "/files/" . $this->Session->read('User.company_id') . DS . date('Y-m') . DS . "/rediness.txt");
        if (file_exists($file->path))
            $readiness = $file->read();
        else
            $readiness = 0;
        $this->set(compact('capaReceived', 'complaintReceived', 'complaintOpen', 'countNCs', 'countNCsOpen', 'docChangeReq', 'readiness'));
    }
    
    
    public function refresh_counts()
    {
        
    }
    
    public function _folder_size($dir)
    {
        
        $countSize = 0;
        $count     = 0;
        $dirArray  = scandir($dir);
        foreach ($dirArray as $key => $fileName) {
            if ($fileName != ".." && $fileName != ".") {
                if (is_dir($dir . "/" . $fileName)) {
                    $newFolderSize = $this->_folder_size($dir . "/" . $fileName);
                    $countSize     = $countSize + $newFolderSize[0];
                    $count         = $count + $newFolderSize[1];
                } else if (is_file($dir . "/" . $fileName)) {
                    $countSize = $countSize + filesize($dir . "/" . $fileName);
                    $count++;
                }
            }
        }
        
        return array(
            $countSize,
            $count
        );
    }
    
    public function user_access($id = null)
    {
        ini_set('memory_limit', -1);
        $this->User->recursive = 0;
        if (!$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }
        
        $allControllers                         = $this->Ctrl->get();
        $otherControllers                       = array();
        // $otherControllers['Documents']                 = array(
        //     'evidences',
        //     'file_uploads'
        // );
        $otherControllers['MR']                 = array(
            'change_addition_deletion_requests',
            'document_amendment_record_sheets',
            'master_list_of_formats',
            'meetings',
            'internal_audits',
            'internal_audit_plans',
            'corrective_preventive_actions',
            'capa_investigations',
            'capa_root_cause_analysis',
            'capa_revised_dates',
            'capa_categories',
            'capa_sources',
            'tasks',
            'benchmarks'
        );
        $otherControllers['HR']                 = array(
            'training_need_identifications',
            'courses',
            'course_types',
            'training_evaluations',
            'competency_mappings',
            'trainings',
            'trainers',
            'trainer_types',
            'appraisals',
            'appraisal_questions'
        );
        $otherControllers['BD']                 = array(
            'customer_meetings',
            'proposals',
            'proposal_followups'
        );
        $otherControllers['Purchase']           = array(
            'supplier_registrations',
            'supplier_categories',
            'list_of_acceptable_suppliers',
            'supplier_evaluation_reevaluations',
            'summery_of_supplier_evaluations',
            'delivery_challans',
            'purchase_orders',
            'invoices',
            'invoice_details'
        );
        $otherControllers['Admin']              = array(
            'fire_extinguishers',
            'housekeeping_checklists',
            'housekeeping_responsibilities',
            'fire_extinguisher_types'
        );
        $otherControllers['Quality Control']    = array(
            'customer_complaints',
            'list_of_measuring_devices_for_calibrations',
            'calibrations',
            'customer_feedbacks',
            'customer_feedback_questions',
            'material_quality_checks',
            'device_maintenances'
        );
        $otherControllers['EDP']                = array(
            'username_password_details',
            'list_of_computers',
            'list_of_softwares',
            'list_of_computer_list_of_softwares',
            'databackup_logbooks',
            'daily_backup_details',
            'data_back_ups'
        );
        $otherControllers['Production']         = array(
            'materials',
            'productions',
            'stocks'
        );
        $otherControllers['Data Entry']         = array(
            'branches',
            'departments',
            'designations',
            'employees',
            'users',
            'products',
            'devices',
            'customers',
            'software_types',
            'training_types'
        );
        $otherControllers['Incident Reporting'] = array(
            'risk_assessments',
            'incidents',
            'incident_investigations',
            'incident_affected_personals',
            'incident_classifications',
            'incident_investigators',
            'incident_witnesses'
        );
        $otherControllers['Objectives']         = array(
            'objectives',
            'objective_monitorings',
            'processes',
            'process_teams'
        );
        $otherControllers['Settings']           = array(
            'auto_approvals',
            'system_tables',
            'companies'
        );
        $otherControllers['FMEA']           = array(
            'fmeas',
            'fmea_actions',
            'fmea_severity_types',
            'fmea_occurences',
            'fmea_detections'
        );
        
        $this->loadModel('MasterListOfFormatDepartment');
        foreach ($otherControllers as $key => $controllers):
            foreach ($controllers as $controller):
                $getActions = Inflector::camelize($controller) . 'Controller';
                if (isset($allControllers[$getActions]) && (!in_array("delete", $allControllers[$getActions]))) {
                    $allControllers[$getActions][] = 'delete';
                }
                $deptWise[$key][$controller]['actions'] = $allControllers[$getActions];
            endforeach;
        endforeach;
        
        $this->set('forms', $deptWise);
        

        if ($this->request->is('post') || $this->request->is('put')) 
        {

            // Configure::write('debug',1);
            // debug($this->request->data);
            // exit;

            $this->User->read(null, $id);
            //Default access
            $dashboard['mr']                                         = 1;
            $dashboard['hr']                                         = 1;
            $dashboard['bd']                                         = 1;
            $dashboard['production']                                 = 1;
            $dashboard['personal_admin']                             = 1;
            $dashboard['quality_control']                            = 1;
            $dashboard['edp']                                        = 1;
            $dashboard['purchase']                                   = 1;
            $dashboard['raicm']                                      = 1;
            $this->request->data['ACL']['user_access']['dashboards'] = $dashboard;
            
            $error['error500']                                   = 1;
            $error['error404']                                   = 1;
            $this->request->data['ACL']['user_access']['errors'] = $error;
            
            $help['view']                                       = 1;
            $help['edit']                                       = 1;
            $help['help']                                       = 1;
            $this->request->data['ACL']['user_access']['helps'] = $help;
            
            $MessageUserSents['index']                                       = 1;
            $MessageUserSents['view']                                        = 1;
            $MessageUserSents['add']                                         = 1;
            $MessageUserSents['edit']                                        = 1;
            $MessageUserSents['delete']                                      = 1;
            $MessageUserSents['delete_all']                                  = 1;
            $this->request->data['ACL']['user_access']['message_user_sents'] = $MessageUserSents;
            
            $MessageUserThrashes['index']                                       = 1;
            $MessageUserThrashes['view']                                        = 1;
            $MessageUserThrashes['add']                                         = 1;
            $MessageUserThrashes['edit']                                        = 1;
            $MessageUserThrashes['delete']                                      = 1;
            $MessageUserThrashes['delete_all']                                  = 1;
            $this->request->data['ACL']['user_access']['message_user_thrashes'] = $MessageUserThrashes;
            
            
            $Messages['inbox']                                     = 1;
            $Messages['sent']                                      = 1;
            $Messages['trash']                                     = 1;
            $Messages['reply']                                     = 1;
            $Messages['index']                                     = 1;
            $Messages['view']                                      = 1;
            $Messages['add']                                       = 1;
            $Messages['edit']                                      = 1;
            $Messages['delete']                                    = 1;
            $Messages['delete_all']                                = 1;
            $Messages['inbox_dashboard']                           = 1;
            $this->request->data['ACL']['user_access']['messages'] = $Messages;
            
            $NotificationUsers['display_notifications_initial']              = 1;
            $NotificationUsers['display_notifications']                      = 1;
            $this->request->data['ACL']['user_access']['notification_users'] = $NotificationUsers;
            
            $Notifications['box']                                       = 1;
            $Notifications['search']                                    = 1;
            $Notifications['advanced_search']                           = 1;
            $Notifications['lists']                                     = 1;
            $Notifications['index']                                     = 1;
            $Notifications['view']                                      = 1;
            $Notifications['add_ajax']                                  = 0;
            $Notifications['edit']                                      = 0;
            $Notifications['delete']                                    = 1;
            $Notifications['delete_all']                                = 1;
            $this->request->data['ACL']['user_access']['notifications'] = $Notifications;
            
            $SuggestionForms['box']             = 1;
            $SuggestionForms['search']          = 1;
            $SuggestionForms['advanced_search'] = 1;
            $SuggestionForms['lists']           = 1;
            $SuggestionForms['index']           = 1;
            $SuggestionForms['view']            = 1;
            $SuggestionForms['add_ajax']        = 1;
            $SuggestionForms['edit']            = 1;
            $SuggestionForms['delete']          = 1;
            $SuggestionForms['delete_all']      = 1;
            
            $this->request->data['ACL']['user_access']['suggestion_forms'] = $SuggestionForms;
            
            
            $this->request->data['ACL']['user_access']['users']['reset_password']       = 1;
            $this->request->data['ACL']['user_access']['users']['save_user_password']   = 1;
            $this->request->data['ACL']['user_access']['users']['login']                = 1;
            $this->request->data['ACL']['user_access']['users']['logout']               = 1;
            $this->request->data['ACL']['user_access']['users']['dashboard']            = 1;
            $this->request->data['ACL']['user_access']['users']['access_denied']        = 1;
            $this->request->data['ACL']['user_access']['users']['terms_and_conditions'] = 0;
            $this->request->data['ACL']['user_access']['users']['branches_gauge']       = 1;
            $this->request->data['ACL']['user_access']['users']['change_password']      = 1;
            $this->request->data['ACL']['user_access']['users']['check_email']          = 1;
            $this->request->data['ACL']['user_access']['users']['activate']             = 1;
            $this->request->data['ACL']['user_access']['users']['unblock_user']         = 1;
            $this->request->data['ACL']['user_access']['users']['register']             = 1;
            $this->request->data['ACL']['user_access']['users']['welcome']              = 1;
            $this->request->data['ACL']['user_access']['users']['timelinetabs']         = 1;
            
            $this->request->data['ACL']['user_access']['tasks']['get_task']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['index']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['view']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['task_completion']         = 1;
            $this->request->data['ACL']['user_access']['tasks']['get_project_task'] = 1;
            $this->request->data['ACL']['user_access']['productions']['get_batch']  = 1;
            
            $this->request->data['ACL']['user_access']['appraisals']['appraisal_notification_email'] = 1;
            $this->request->data['ACL']['user_access']['appraisals']['appraisal_review']             = 1;
            $this->request->data['ACL']['user_access']['appraisals']['self_appraisals']              = 1;
            
            $this->request->data['ACL']['user_access']['internal_audit_plans']['get_dept_clauses']  = 1;
            $this->request->data['ACL']['user_access']['branches']['get_branch_name']               = 1;
            $this->request->data['ACL']['user_access']['internal_audits']['send_email']             = 1;
            $this->request->data['ACL']['user_access']['internal_audits']['audit_details_add_ajax'] = 1;
            $this->request->data['ACL']['user_access']['internal_audit_plans']['add_branches']      = 1;
            $this->request->data['ACL']['user_access']['internal_audit_plans']['add_departments']   = 1;
            $this->request->data['ACL']['user_access']['meetings']['add_after_meeting_topics']      = 1;
            $this->request->data['ACL']['user_access']['meetings']['meeting_view']                  = 1;
            
            
            
            $this->request->data['ACL']['user_access']['customers']['get_unique_values']                 = 1;
            $this->request->data['ACL']['user_access']['customer_complaints']['get_customer_complaints'] = 1;
            $this->request->data['ACL']['user_access']['customer_complaints']['check_complaint_number']  = 1;
            $this->request->data['ACL']['user_access']['customer_meetings']['followup_count']            = 1;
            $this->request->data['ACL']['user_access']['proposal_followups']['followup_count']           = 1;
            $this->request->data['ACL']['user_access']['dashboards']['result_mapping']                   = 1;
            
            
            if ($this->request->data['ACL']['user_access']['customer_meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['customer_meetings']['edit'] == 1)
                $this->request->data['ACL']['user_access']['customer_meetings']['add_followups'] = 1;
            else
                $this->request->data['ACL']['user_access']['customer_meetings']['add_followups'] = 0;
            
            //if( $this->request->data['ACL']['user_access']['calibrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['edit'] == 1)
            $this->request->data['ACL']['user_access']['calibrations']['get_details'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['calibrations']['get_details'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['customer_complaints']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['customer_complaints']['edit'] == 1 || $this->request->data['ACL']['user_access']['customer_complaints']['index'] == 1)
            $this->request->data['ACL']['user_access']['customer_complaints']['customer_complaint_status'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['customer_complaints']['customer_complaint_status'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['trainings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['trainings']['edit'] == 1)
            $this->request->data['ACL']['user_access']['trainings']['get_details'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['trainings']['get_details'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['corrective_preventive_actions']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['corrective_preventive_actions']['edit'] == 1)
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_assigned']                  = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_investigation_count']       = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_root_cuase_analysis_count'] = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_revised_dates_count']       = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['get_details']                    = 1;
            $this->request->data['ACL']['user_access']['capa_root_cause_analysis']['capa_assigned']                       = 1;
            $this->request->data['ACL']['user_access']['capa_investigations']['capa_assigned']                            = 1;
            $this->request->data['ACL']['user_access']['capa_revised_dates']['capa_assigned']                             = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['corrective_preventive_actions']['get_details'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['supplier_registrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['supplier_registrations']['edit'] == 1)
            $this->request->data['ACL']['user_access']['supplier_registrations']['get_supplier_registration_title'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['supplier_registrations']['get_supplier_registration_title'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['employees']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['employees']['edit'] == 1)
            $this->request->data['ACL']['user_access']['employees']['get_employee_email'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['employees']['get_employee_email'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['materials']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['materials']['edit'] == 1){
            $this->request->data['ACL']['user_access']['materials']['get_material_name']                = 1;
            $this->request->data['ACL']['user_access']['materials']['get_material_qc_required']         = 1;
            $this->request->data['ACL']['user_access']['materials']['get_purchase_order_number']        = 1;
            //            }else{
            //                $this->request->data['ACL']['user_access']['materials']['get_material_name'] = 0;
            //                $this->request->data['ACL']['user_access']['materials']['get_material_qc_required'] = 0;
            //                $this->request->data['ACL']['user_access']['materials']['get_purchase_order_number'] = 0;
            //            }
            //  if( $this->request->data['ACL']['user_access']['purchase_orders']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['purchase_orders']['edit'] == 1){
            $this->request->data['ACL']['user_access']['purchase_orders']['add_purchase_order_details'] = 1;
            $this->request->data['ACL']['user_access']['purchase_orders']['get_purchase_order_number']  = 1;
            //            }else{
            //                $this->request->data['ACL']['user_access']['purchase_orders']['add_purchase_order_details'] = 0;
            //                $this->request->data['ACL']['user_access']['purchase_orders']['get_purchase_order_number'] = 0;
            //            }
            
            // if( $this->request->data['ACL']['user_access']['list_of_computers']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['list_of_computers']['edit'] == 1)
            $this->request->data['ACL']['user_access']['list_of_computers']['add_new_software'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['list_of_computers']['add_new_software'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['appraisals']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['appraisals']['edit'] == 1)
            $this->request->data['ACL']['user_access']['appraisals']['add_questions'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['appraisals']['add_questions'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['device_maintenances']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['device_maintenances']['edit'] == 1 || $this->request->data['ACL']['user_access']['device_maintenances']['index'] == 1)
            $this->request->data['ACL']['user_access']['device_maintenances']['get_device_maintainance'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['device_maintenances']['get_device_maintainance'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['calibrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['edit'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['index'] == 1)
            $this->request->data['ACL']['user_access']['calibrations']['get_next_calibration'] = 1;
            //            else
            //                   $this->request->data['ACL']['user_access']['calibrations']['get_next_calibration'] = 0;
            
            //            if( $this->request->data['ACL']['user_access']['material_quality_checks']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['material_quality_checks']['edit'] == 1 || $this->request->data['ACL']['user_access']['material_quality_checks']['index'] == 1){
            //
            $this->request->data['ACL']['user_access']['material_quality_checks']['get_process']        = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['get_material_check'] = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['material_count']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['quality_check']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['add_quality_check']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['add_to_stock']     = 1;

            
            //            } else {
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['get_process'] = 0;
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['get_material_check'] = 0;
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['material_count'] = 0;
            //            }
            //if( $this->request->data['ACL']['user_access']['stocks']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['stocks']['edit'] == 1){
            $this->request->data['ACL']['user_access']['stocks']['get_material']                        = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_material_details']                = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_dc_details']                      = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_stock_details']     = 1;
            
            //            }else{
            //               $this->request->data['ACL']['user_access']['stocks']['get_material'] = 0;
            //               $this->request->data['ACL']['user_access']['stocks']['get_material_details'] = 0;
            //               $this->request->data['ACL']['user_access']['stocks']['get_dc_details'] = 0;
            //
            //            }
            if( $this->request->data['ACL']['user_access']['meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['meetings']['edit'] == 1 || $this->request->data['ACL']['user_access']['meetings']['add'] == 1){
                $this->request->data['ACL']['user_access']['meetings']['get_department_employee']                        = 1;
             
            }
            if ($this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['evaluate'] == 1) {
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['index']             = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['view']              = 1;
            } else {
                // $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 0;
            }
            //  if( $this->request->data['ACL']['user_access']['delivery_challans']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['delivery_challans']['edit'] == 1){
            $this->request->data['ACL']['user_access']['delivery_challans']['get_purchase_order']            = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_details']           = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_number']            = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_delivered_material_qc']     = 1;
            //            }else{
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_purchase_order'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_details'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_number'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_delivered_material_qc'] = 0;
            $this->request->data['ACL']['user_access']['tasks']['task_ajax_file_count']                      = 1;
            $this->request->data['ACL']['user_access']['housekeeping_responsibilities']['housekeeping_ajax'] = 1;
            
            $this->request->data['ACL']['user_access']['reports']['report_center']        = 0;
            $this->request->data['ACL']['user_access']['reports']['manual_reports']       = 0;
            $this->request->data['ACL']['user_access']['users']['two_way_authentication'] = 0;
            $this->request->data['ACL']['user_access']['users']['password_setting']       = 0;
            $this->request->data['ACL']['user_access']['users']['smtp_details']           = 0;
            $this->request->data['ACL']['user_access']['email_triggers']['index']         = 0;
            if ($this->request->data['ACL']['user_access']['risk_assessments']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['risk_assessments']['edit'] == 1) {
                
                $this->request->data['ACL']['user_access']['risk_ratings']['index']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['view']       = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['add']        = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['edit']       = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete']     = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['lists']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['report']     = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['approve']    = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete_all'] = 1;
                
                
                $this->request->data['ACL']['user_access']['hazard_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['approve']    = 1;
                
                
                $this->request->data['ACL']['user_access']['accident_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['accident_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['accident_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['accident_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['accident_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['accident_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['accident_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['accident_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['accident_types']['approve']    = 1;
                
                
                $this->request->data['ACL']['user_access']['severiry_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['hazard_sources']['index']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['view']       = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['add']        = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['edit']       = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete']     = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['lists']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['report']     = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['injury_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['injury_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['injury_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['injury_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['injury_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['injury_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['injury_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['injury_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['injury_types']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['body_areas']['index']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['view']                  = 1;
                $this->request->data['ACL']['user_access']['body_areas']['add']                   = 1;
                $this->request->data['ACL']['user_access']['body_areas']['edit']                  = 1;
                $this->request->data['ACL']['user_access']['body_areas']['delete']                = 1;
                $this->request->data['ACL']['user_access']['body_areas']['delete_all']            = 1;
                $this->request->data['ACL']['user_access']['body_areas']['lists']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['purge']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['purge_all']             = 1;
                $this->request->data['ACL']['user_access']['body_areas']['add_ajax']              = 1;
                $this->request->data['ACL']['user_access']['body_areas']['report']                = 1;
                $this->request->data['ACL']['user_access']['body_areas']['approve']               = 1;
                $this->request->data['ACL']['user_access']['meetings']['get_department_employee'] = 1;
            } else {
                
                $this->request->data['ACL']['user_access']['risk_ratings']['index']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['view']       = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['add']        = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['edit']       = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete']     = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['lists']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['report']     = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['approve']    = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete_all'] = 0;
                
                
                $this->request->data['ACL']['user_access']['hazard_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['approve']    = 0;
                
                
                $this->request->data['ACL']['user_access']['accident_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['accident_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['accident_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['accident_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['accident_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['accident_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['accident_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['accident_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['accident_types']['approve']    = 0;
                
                
                $this->request->data['ACL']['user_access']['severiry_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['hazard_sources']['index']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['view']       = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['add']        = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['edit']       = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete']     = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['lists']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['report']     = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['injury_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['injury_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['injury_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['injury_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['injury_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['injury_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['injury_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['injury_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['injury_types']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['body_areas']['index']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['view']       = 0;
                $this->request->data['ACL']['user_access']['body_areas']['add']        = 0;
                $this->request->data['ACL']['user_access']['body_areas']['edit']       = 0;
                $this->request->data['ACL']['user_access']['body_areas']['delete']     = 0;
                $this->request->data['ACL']['user_access']['body_areas']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['body_areas']['lists']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['purge']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['body_areas']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['body_areas']['report']     = 0;
                $this->request->data['ACL']['user_access']['body_areas']['approve']    = 0;

                if( $this->request->data['ACL']['user_access']['meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['meetings']['edit'] == 1 || $this->request->data['ACL']['user_access']['meetings']['add'] == 1){
                    $this->request->data['ACL']['user_access']['meetings']['get_department_employee']                        = 1;
             
                }
            }
            
            
            //            }
            $data['User']['user_access'] = json_encode($this->request->data['ACL']);
           
            if(isset($this->request->data['User_assigned_branches']) && $this->request->data['User_assigned_branches'] != null)$data['User']['assigned_branches'] = json_encode($this->request->data['User_assigned_branches']);
            else unset($data['User']['assigned_branches']);
            
            if ($this->User->save($data, false)) {
                $this->Session->setFlash(__('Saved', true));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__('Not Saved', true));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'index'
                ));
            }
        } else {
            $options                    = array(
                'conditions' => array(
                    'User.' . $this->User->primaryKey => $id
                )
            );
            $this->request->data        = $this->User->find('first', $options);
            $newData                    = json_decode($this->request->data['User']['user_access'], true);
            $this->request->data['ACL'] = $newData;
            
        }
    }
    
    public function access_denied()
    {
        
    }
    
    /**
     * restore method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function _meeting_details_reminder()
    {
        $preMeetingAlert  = null;
        $postMeetingAlert = null;
        $from             = date('Y-m-d 00:00:00');
        $to               = date("Y-m-d 00:00:00", strtotime("+5 days", strtotime($from)));
        
        $this->loadModel('MeetingEmployee');
        $this->MeetingEmployee->recursive = 0;
        $nearest_meeting                  = $this->MeetingEmployee->find('first', array(
            'conditions' => array(
                'MeetingEmployee.employee_id' => $this->Session->read('User.employee_id'),
                'Meeting.publish' => 1,
                'Meeting.soft_delete' => 0,
                'Meeting.scheduled_meeting_from BETWEEN ? AND ? ' => array(
                    $from,
                    $to
                )
            ),
            'fields' => array(
                'Meeting.id',
                'MeetingEmployee.employee_id',
                'Meeting.scheduled_meeting_from',
                'Meeting.publish',
                'Meeting.soft_delete'
            )
        ));
        if ($nearest_meeting) {
            $preMeetingAlert = __('You have a meeting scheduled on ') . $nearest_meeting['Meeting']['scheduled_meeting_from'];
            $this->set(array(
                'meeting_id' => $nearest_meeting['Meeting']['id']
            ));
        }
        
        
        //        $from = date('Y-m-d 00:00:00');
        //        $to = date("Y-m-d 00:00:00", strtotime("+5 days", strtotime($from)));
        
        $postMeeting = $this->MeetingEmployee->find('first', array(
            'conditions' => array(
                'Meeting.publish' => 1,
                'Meeting.soft_delete' => 0,
                'Meeting.scheduled_meeting_from >' => $from,
                'Meeting.actual_meeting_from' => NULL,
                'MeetingEmployee.employee_id' => $this->Session->read('User.employee_id')
            ),
            'fields' => array(
                'Meeting.id',
                'MeetingEmployee.employee_id',
                'Meeting.scheduled_meeting_from',
                'Meeting.publish',
                'Meeting.soft_delete'
            )
        ));
        
        if ($postMeeting) {
            $postMeetingAlert = "Please add meeting details for the recent meeting";
            $this->set(array(
                'meeting_id' => $postMeeting['Meeting']['id']
            ));
        }
        
        $this->set(array(
            'preMeetingAlert' => $preMeetingAlert,
            'postMeetingAlert' => $postMeetingAlert
        ));
    }
    
    public function terms_and_conditions()
    {
        $this->Session->write('TANDC', 0); 
        $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'dashboard'
                ));

        if ($this->request->is('post')) {
            
            $this->Session->write('TANDC', 0);
            
            $data                  = $this->User->read(null, $this->Session->read('User.id'));
            $data['User']['agree'] = 0;
            $this->User->save($data, false);
            
            $this->loadModel('MasterListOfFormat');
            $checkForms = $this->MasterListOfFormat->find('count', array(
                'conditions' => array(
                    'MasterListOfFormat.company_id' => $this->Session->read('User.company_id')
                )
            ));
            
            if (isset($checkForms) && ($checkForms > 0)) {
                $this->Session->setFlash(__('Please change your password', true));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'change_password'
                ));
            } else
                $this->redirect(array(
                    'action' => 'welcome'
                ));
        }
        $employee = $this->User->Employee->find('first', array(
            'recursive' => 1,
            'fields' => array(
                'Employee.id',
                'Employee.name',
                'Designation.name'
            )
        ));
        $this->set('employee', $employee);
        $flinkiso = $this->User->Company->find('first', array(
            'recursive' => -1,
            'fields' => array(
                'Company.flinkiso_start_date',
                'Company.name'
            )
        ));
        $this->set('flinkiso', $flinkiso);
    }
    
    public function _get_data_entry()
    {
        $this->loadModel('Benchmark');
        $this->Benchmark->recursive = -1;
        
        $this->loadModel('History');
        $this->History->recursive = -1;
        
        $this->loadModel('Company');
        $this->Company->recursive = -1;
        
        // get daily benchmark
        $benchmarks   = $this->Benchmark->find('all', array(
            'conditions' => array(
                'Benchmark.benchmark >' => 0,
                'Benchmark.branch_id' => $this->Session->read('User.branch_id')
            ),
            'fields' => array(
                'sum(Benchmark.benchmark) as total_sum'
            )
        ));
        $total        = $this->History->find('count', array(
            'conditions' => array(
                'History.company_id' => $this->Session->read('User.company_id'),
                $this->common_condition
            )
        ));
        $getStartDate = $this->Company->find('first', array(
            'fields' => 'flinkiso_start_date'
        ));
        $StartDate    = new DateTime(date('Y-m-1'));
        $endDate      = new DateTime(date('Y-m-t'));
        $dateDiff     = $StartDate->diff($endDate);
        $days         = $dateDiff->format('%d');
        if ($days > 0 && (isset($benchmarks[0][0]['total_sum']))) {
            if ($total > $benchmarks[0][0]['total_sum']) {
                $avg = 100;
            } else {
                $avg = ($total * 100) / ($dateDiff->days * $benchmarks[0][0]['total_sum']);
            }
        } else {
            $avg = 0;
        }
        $this->set(array(
            'avg' => $avg,
            'benchmark' => $benchmarks[0][0]['total_sum']
        ));
    }
    
    public function change_password()
    {
        
        if ($this->request->is('post')) {
            $userData = $this->User->find('first', array(
                'conditions' => array(
                    'id' => $this->Session->read('User.id')
                ),
                'recursive' => -1
            ));
            if ($userData['User']['password'] == Security::hash($this->request->data['User']['current_password'], 'md5', true)) {
                $newData                     = array();
                $newData['User']['id']       = $userData['User']['id'];
                $newData['User']['password'] = Security::hash($this->request->data['User']['new_password'], 'md5', true);
                $old_pwd                     = json_decode($userData['User']['old_password']);
                $result                      = $this->requestAction(array(
                    'plugin' => 'password_setting_manager',
                    'controller' => 'password_settings',
                    'action' => 'check_password_validation',
                    $this->request->data['User']['new_password'],
                    $userData['User']['old_password'],
                    $userData['User']['username']
                ));
                
                if (!$result['valid']) {
                    $this->Session->setFlash(__($result['message']), 'default', array(
                        'class' => 'alert-danger'
                    ));
                    $this->redirect(array(
                        'action' => 'change_password'
                    ));
                }
                
                if (count($old_pwd)) {
                    if (!in_array($newData['User']['password'], $old_pwd)) {
                        array_unshift($old_pwd, $newData['User']['password']);
                        $pwd_repeat_cnt = $this->requestAction(array(
                            'plugin' => 'password_setting_manager',
                            'controller' => 'password_settings',
                            'action' => 'get_password_repeat_len'
                        ));
                        if (count($old_pwd) > $pwd_repeat_cnt) {
                            $splited_arr = array_chunk($old_pwd, $pwd_repeat_cnt);
                            $old_pwd     = $splited_arr[0];
                            
                        }
                        $newData['User']['old_password'] = json_encode($old_pwd);
                    }
                } else {
                    $newData['User']['old_password'] = json_encode(array(
                        $newData['User']['password']
                    ));
                }
                $newData['User']['pwd_last_modified'] = date('Y-m-d H:i:s');
                if ($this->User->save($newData, false)) {
                    $this->Session->setFlash(__('Your password has been change'));
                    $this->redirect(array(
                        'action' => 'logout'
                    ));
                }
            } else {
                $this->Session->setFlash(__('Your current password does not match with the password you have entered. please try again.'), 'default', array(
                    'class' => 'alert-danger'
                ));
                $this->redirect(array(
                    'action' => 'change_password'
                ));
            }
        }
    }
    
    public function register($downloadkey = NULL)
    {
        if ($this->request->data) {
            //create Company
            $description = '<p>FlinkISO is a web based application (available as SAAS as well as On-site) which automates the entire ISO documentation process and facilitates customers to electronically store &amp;
                    maintain all the relevant documents, quality &amp; procedure manuals and data at single source on cloud. From this extensive information,
                    system can generate &ldquo;eye to detail reports&rdquo;, YOY performance analysis for the management to gauge, scale the organization growth and productivity,
                    and move forward to take corrective actions. Product is divided into 3 categories viz. Standard, Enterprise &amp; SAAS and will be released over a period of 3 years.
                    This categorization will help to serve needs of 3 types of enterprises Micro, Small and Medium.</p>';
            
            $welcomeMessage = '<p>TECHMENTIS GLOBAL SERVICES PVT. LTD offers an array of web business solutions through e-commerce, B2B, B2C and mobile applications.
            Our young and dynamic team not only thrives to create and innovate, but also focuses on building a sustainable business model which enables our clients to remain
            competitive and profitable in the versatile global market.</p>';
            
            $company['Company']['name']                = $this->request->data['User']['company'];
            $company['Company']['sample_data']         = $this->request->data['User']['sample_data'];
            $company['Company']['description']         = $description;
            $company['Company']['welcome_message']     = $welcomeMessage;
            $company['Company']['number_of_branches']  = 1;
            $company['Company']['number_of_users']     = $this->request->data['User']['number_of_users'];
            $company['Company']['limit_login_attempt'] = 100;
            $company['Company']['flinkiso_start_date'] = date('Y-m-d');
            $company['Company']['flinkiso_end_date']   = date('Y-m-d', strtotime('+1 year'));
            $company['Company']['publish']             = 1;
            $company['Company']['soft_delete']         = 0;
            $company['Company']['branchid']            = '0';
            $company['Company']['departmentid']        = '0';
            $company['Company']['created_by']          = '0';
            $company['Company']['modified_by']         = '0';
            $company['Company']['created']             = date('Y-m-d h:i:s');
            $company['Company']['created']             = date('Y-m-d h:i:s');
            $company['Company']['liscence_key']        = $this->request->data['User']['liscence_key_installed'];
            $this->loadModel('Company');
            $this->Company->create();
            
            //check if company exist
            $companyFind = $this->Company->find('first', array(
                'conditions' => array(
                    'Company.name' => $this->request->data['User']['company']
                ),
                'recursive' => -1
            ));
            if ($companyFind) {
                $companyId     = $companyFind['Company']['id'];
                $alreadyExists = true;
            } else {
                if (($this->Company->save($company, false))) {
                    $companyId     = $this->Company->id;
                    $alreadyExists = false;
                }
            }
            
            if ($companyId != null) {
                $branch_name                      = $this->request->data['User']['city'] ? $this->request->data['User']['city'] : 'Default';
                $branch['Branch']['name']         = $branch_name;
                $branch['Branch']['publish']      = 1;
                $branch['Branch']['soft_delete']  = 0;
                $branch['Branch']['branchid']     = '0';
                $branch['Branch']['departmentid'] = '0';
                $branch['Branch']['created_by']   = '0';
                $branch['Branch']['modified_by']  = '0';
                $branch['Branch']['created']      = date('Y-m-d h:i:s');
                $branch['Branch']['created']      = date('Y-m-d h:i:s');
                $this->loadModel('Branch');
                
                $findBranch = $this->Branch->find('first', array(
                    'conditions' => array(
                        'Branch.name' => $branch_name,
                        'Branch.company_id' => $companyId
                    )
                ));
                if ($findBranch) {
                    $branchId = $findBranch['Branch']['id'];
                } else {
                    $this->Branch->create();
                    if ($this->Branch->save($branch, false)) {
                        $branchId = $this->Branch->id;
                    }
                }
                
                if ($branchId != null) {
                    
                    $defaultDepartment = Configure::read('department');
                    $department        = $this->User->Department->find('first', array(
                        'conditions' => array(
                            'name' => $defaultDepartment
                        ),
                        'fields' => array(
                            'id'
                        ),
                        'recursive' => -1
                    ));
                    
                    $this->loadModel('Designation');
                    $defaultDesignation = Configure::read('designation');
                    $designation        = $this->Designation->find('first', array(
                        'conditions' => array(
                            'name' => $defaultDesignation
                        ),
                        'fields' => array(
                            'id'
                        ),
                        'recursive' => -1
                    ));
                    
                    $defaultLanguage = Configure::read('language');
                    $language        = $this->User->Language->find('first', array(
                        'conditions' => array(
                            'name' => $defaultLanguage
                        ),
                        'fields' => array(
                            'id'
                        ),
                        'recursive' => -1
                    ));
                    
                    
                    
                    
                    
                    $this->loadModel('Employee');
                    $employeeCount = $this->Employee->find('count', array(
                        'conditions' => array(
                            'Employee.company_id' => $companyId
                        )
                    ));
                    
                    
                    $employee['Employee']['name']               = $this->request->data['User']['name'];
                    $employee['Employee']['employee_number']    = substr(strtoupper($this->request->data['User']['company']), 0, 3) . '00' . ($employeeCount + 1);
                    $employee['Employee']['branch_id']          = $branchId;
                    $employee['Employee']['designation_id']     = $designation['Designation']['id'];
                    $employee['Employee']['company_id']         = $companyId;
                    $employee['Employee']['joining_date']       = date('Y-m-d');
                    $employee['Employee']['publish']            = 1;
                    $employee['Employee']['soft_delete']        = 0;
                    $employee['Employee']['personal_telephone'] = $this->request->data['User']['phone'];
                    $employee['Employee']['office_telephone']   = $this->request->data['User']['phone'];
                    $employee['Employee']['mobile']             = $this->request->data['User']['phone'];
                    $employee['Employee']['personal_email']     = $this->request->data['User']['email'];
                    $employee['Employee']['office_email']       = $this->request->data['User']['email'];
                    $employee['Employee']['branchid']           = '0';
                    $employee['Employee']['departmentid']       = '0';
                    $employee['Employee']['created_by']         = '0';
                    $employee['Employee']['modified_by']        = '0';
                    $employee['Employee']['created']            = date('Y-m-d h:i:s');
                    $employee['Employee']['created']            = date('Y-m-d h:i:s');
                    
                    //                    $employee['Employee']['departmentid'] = $department['Department']['id'];
                    //                    $employee['Employee']['branchid'] = $branchId;
                    $employee['Employee']['system_table_id']          = '5297b2e7-959c-4892-b073-2d8f0a000005';
                    $employee['Employee']['master_list_of_format_id'] = '523ab4b6-cf7c-4de5-918b-6f22c6c3268c';
                    
                    
                    $this->Employee->create();
                    if ($this->Employee->save($employee, false)) {
                        
                        //create User
                        $encrypt                                  = $this->User->generateToken();
                        $user['User']['employee_id']              = $this->Employee->id;
                        $user['User']['company_id']               = $companyId;
                        $user['User']['name']                     = $this->request->data['User']['name'];
                        $user['User']['username']                 = $this->request->data['User']['email'];
                        $user['User']['password']                 = Security::hash($this->request->data['User']['password'], 'md5', true);
                        $user['User']['is_mr']                    = true;
                        $user['User']['is_view_all']              = true;
                        $user['User']['is_approvar']              = true;
                        $user['User']['status']                   = 1;
                        $user['User']['agree']                    = 0;
                        $user['User']['department_id']            = $department['Department']['id'];
                        $user['User']['branch_id']                = $branchId;
                        $user['User']['language_id']              = $language['Language']['id'];
                        $user['User']['publish']                  = 1;
                        $user['User']['soft_delete']              = 0;
                        $user['User']['master_list_of_format_id'] = '523ae34c-bcc0-4c7d-b7aa-75cec6c3268c';
                        $user['User']['system_table_id']          = '5297b2e7-0a9c-46e3-96a6-2d8f0a000005';
                        $user['User']['allow_multiple_login']     = 1;
                        $user['User']['password_token']           = $encrypt;
                        $user['User']['branchid']                 = '0';
                        $user['User']['departmentid']             = '0';
                        $user['User']['created_by']               = '0';
                        $user['User']['modified_by']              = '0';
                        $user['User']['created']                  = date('Y-m-d h:i:s');
                        $user['User']['created']                  = date('Y-m-d h:i:s');
                        $user['User']['last_login']               = date('Y-m-d H:i:s');
                        $user['User']['last_activity']            = date('Y-m-d H:i:s');
                        $user['User']['copy_acl_from']            = '';
                        $user['User']['user_access']              = $this->Ctrl->get_defaults();
                        $this->User->create();
                        
                        if ($this->User->save($user, false)) {
                            
                            
                            $data                              = null;
                            $data['Employee']['id']            = $this->Employee->id;
                            $data['Employee']['branch_id']     = $branchId;
                            $data['Employee']['department_id'] = $department['Department']['id'];
                            $data['Employee']['created_by']    = $this->User->id;
                            $data['Employee']['company_id']    = $companyId;
                            $this->Employee->save($data, false);
                            
                            $data = null;
                            
                            $data['Branch']['id']         = $branchId;
                            $data['Branch']['branchid']   = $branchId;
                            $data['Branch']['created_by'] = $this->User->id;
                            $data['Branch']['company_id'] = $companyId;
                            $this->Branch->save($data, false);
                            
                            $data                       = null;
                            $data['User']['id']         = $this->User->id;
                            $data['User']['created_by'] = $this->User->id;
                            $data['User']['company_id'] = $companyId;
                            $this->User->save($data, false);
                            
                            $data                          = null;
                            $data['Company']['id']         = $companyId;
                            $data['Company']['created_by'] = $this->User->id;
                            $data['Company']['company_id'] = $companyId;
                            $this->Company->save($data, false);
                            
                            
                            $url = "https://www.flinkiso.com/user_registration.php";
                            $ch  = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                            curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
                            curl_setopt($ch, CURLOPT_HEADER, TRUE);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0"
                            ));
                            $postfields                 = array();
                            $postfields['name']         = urlencode($this->request->data['User']['name']);
                            $postfields['username']     = urlencode($this->request->data['User']['email']);
                            $postfields['company']      = urlencode($this->request->data['User']['company']);
                            $postfields['password']     = urlencode($this->request->data['User']['password']);
                            $postfields['email']        = urlencode($this->request->data['User']['email']);
                            $postfields['phone']        = urlencode($this->request->data['User']['phone']);
                            $postfields['city']         = urlencode($this->request->data['User']['city']);
                            $postfields['state']        = urlencode($this->request->data['User']['state']);
                            $postfields['country']      = urlencode($this->request->data['User']['country']);
                            $postfields['download_key'] = urlencode($this->request->data['User']['liscence_key_installed']);
                            $postfields['seccheck']     = urlencode(md5($this->request->data['User']['name'] . 'FlinkISO' . $this->request->data['User']['email']));
                            
                            
                            curl_setopt($ch, CURLOPT_POST, count($postfields));
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                            $ret = curl_exec($ch);
                            // Close handle
                            
                            
                            curl_close($ch);
                            
                            if ($ret) {
                                $data                           = null;
                                $data['Employee']['id']         = $this->Employee->id;
                                $data['Employee']['registered'] = 1;
                                $this->Employee->save($data, false);
                            }
                            $this->_update_sql($companyId);
                            if ($this->request->data['User']['sample_data'] == 1) {
                                $this->insert_sample_data();
                                $this->_update_sql($companyId);
                            }
                            $this->Session->setFlash(__('Account created'));
                            file_put_contents(APP . 'Config/installed.txt', date('Y-m-d, H:i:s'));
                            unlink(APP . 'Config/installed_db.txt');
                            $this->redirect(array(
                                'action' => 'activate',
                                $encrypt
                            ));
                            
                            
                        } else {
                            $this->Session->setFlash(__('Failed to create account. Error while creating user'), 'default', array(
                                'class' => 'alert-danger'
                            ));
                            $this->redirect(array(
                                'action' => 'register'
                            ));
                        }
                    } else {
                        $this->Session->setFlash(__('Failed to create account. Error while creating employee'), 'default', array(
                            'class' => 'alert-danger'
                        ));
                        $this->redirect(array(
                            'action' => 'register'
                        ));
                    }
                } else {
                    $this->Session->setFlash(__('Failed to create account. Error while creating branch'), 'default', array(
                        'class' => 'alert-danger'
                    ));
                    $this->redirect(array(
                        'action' => 'register'
                    ));
                }
            } else {
                $this->Session->setFlash(__('Failed to create account. Error while creating company'), 'default', array(
                    'class' => 'alert-danger'
                ));
                $this->redirect(array(
                    'action' => 'register'
                ));
            }
            
        }
        $this->layout = 'login';
    }
    
    public function activate($encrypt = null)
    {
        $user = $this->User->find('first');
        if ($user) {
            $this->User->read(null, $user['User']['id']);
            $data['User']['status']         = 1;
            $data['User']['publish']        = 1;
            $data['User']['soft_delete']    = 0;
            $data['User']['password_token'] = null;
            $this->User->save($data, false);
            
            //create company directory
            $dir = Configure::read('MediaPath') . 'files' . DS . $user['User']['company_id'];
            mkdir($dir, 0777, true);
            
            $dir = Configure::read('MediaPath') . 'files' . DS . $user['User']['company_id'] . DS . 'SavedReports';
            mkdir($dir, 0777, true);
            
            $dir = Configure::read('MediaPath') . 'files' . DS . $user['User']['company_id'] . DS . 'uploads';
            mkdir($dir, 0777, true);
            $this->set('user', $user);
            $this->Session->setFlash(__('Congratulations, your account is activated'), 'default', array(
                'class' => 'alert-success'
            ));
            $this->redirect(array(
                'action' => 'smtp_details',
                base64_encode($user['User']['username'])
            ));
        } else {
            $this->Session->setFlash(__('Congratulations, your account is activated'), 'default', array(
                'class' => 'alert-success'
            ));
            //$this->Session->setFlash(__('Can not activate account. Please contact us at +91 9769 866 441 for help'), 'default', array('class'=>'alert-danger'));
            $this->redirect(array(
                'action' => 'login'
            ));
        }
    }
    
    public function check_email($email = null)
    {
        $this->layout   = 'ajax';
        $resultEmployee = $this->User->Employee->find('count', array(
            'recursive' => 0,
            'fields' => array(
                'Employee.office_email'
            ),
            'conditions' => array(
                'Employee.office_email' => $email
            )
        ));
        $resultUser     = $this->User->find('count', array(
            'conditions' => array(
                'User.username' => $email
            )
        ));
        if ($resultEmployee != 0 || $resultUser != 0)
            $this->set('email_response', 'Email / Username already exists. Please select different email');
        else
            return false;
    }
    
    public function check_username($username = null)
    {
        $this->layout   = 'ajax';
        $resultUsername = $this->User->find('count', array(
            'recursive' => -1,
            'conditions' => array(
                'User.username' => $username
            )
        ));
        if ($resultUsername != 0)
            $this->set('username_response', 'Username already exists. Please enter a different username');
        else
            return false;
    }
    
    public function add_formats($timeline = null)
    {
        
        $this->loadModel('MasterListOfFormat');
        $this->loadModel('MasterListOfFormatDepartment');
        $this->loadModel('MasterListOfFormatBranch');
        $this->Session->write('show_all_formats', true);
        
        $this->MasterListOfFormat->updateAll(array(
            'MasterListOfFormat.company_id' => $this->Session->read('User.company_id')
        ), null);
        $this->MasterListOfFormatDepartment->updateAll(array(
            'MasterListOfFormatDepartment.company_id' => $this->Session->read('User.company_id')
        ), null);
        $this->MasterListOfFormatBranch->updateAll(array(
            'MasterListOfFormatBranch.company_id' => $this->Session->read('User.company_id')
        ), null);
        
        
        $this->Session->write('show_all_formats', false);
        if ($timeline == 1)
            $this->_add_timeline();
        $this->_add_trainings();
        $this->Session->setFlash(__('Formats saved.'));
        $this->redirect(array(
            'action' => 'dashboard'
        ));
    }
    
    public function _add_timeline()
    {
        $this->Session->write('show_all_formats', false);
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId                = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => 'timelines'
            )
        ));
        
        $this->loadModel('Timeline');
        $this->Timeline->create();
        $timeline                    = array();
        $timeline['title']           = 'FlinkISO Start Date';
        $timeline['message']         = 'Welcome to FlinkISO. FlinkISO Start day.!';
        $timeline['start_date']      = date('Y-m-d');
        $timeline['end_date']        = date('Y-m-d');
        $timeline['publish']         = 1;
        $timeline['soft_delete']     = 0;
        $timeline['company_id']      = $this->Session->read('User.company_id');
        $timeline['prepared_by']     = $this->Session->read('User.id');
        $timeline['approved_by']     = $this->Session->read('User.id');
        $timeline['system_table_id'] = $systemTableId['SystemTable']['id'];
        $this->Timeline->save($timeline);    
       
        $systemTableId = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => 'list_of_trained_internal_auditors'
            )
        ));
        $this->loadModel('ListOfTrainedInternalAuditor');
        $formatId = $this->ListOfTrainedInternalAuditor->MasterListOfFormat->find('first', array(
            'conditions' => array(
                'MasterListOfFormat.title' => 'LIST OF TRAINERS',
                'MasterListOfFormat.company_id' => $this->Session->read('User.company_id')
            )
        ));
        $this->ListOfTrainedInternalAuditor->create();
        $auditor['employee_id']              = $this->Session->read('User.employee_id');
        $auditor['training_id']              = 'A00000000-A000-A000-A000-A000000000123';
        $auditor['system_table_id']          = $systemTableId['SystemTable']['id'];
        $auditor['master_list_of_format_id'] = $formatId['MasterListOfFormat']['id'];
        $auditor['publish']                  = 1;
        $auditor['soft_delete']              = 0;
        $this->ListOfTrainedInternalAuditor->save($auditor);
    }
    
    public function _add_trainings()
    {
        
        //first add Training types
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId                = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => 'training_types'
            )
        ));
        
        $this->loadModel('TrainingType');
        
        $trainingType                         = array();
        $trainingType['title']                = 'HR Trainings';
        $trainingType['training_description'] = 'All HR related trainings will be under this category';
        $trainingType['mandetory']            = 1;
        $trainingType['publish']              = 1;
        $trainingType['soft_delete']          = 0;
        $trainingType['company_id']           = $this->Session->read('User.company_id');
        $trainingType['system_table_id']      = $systemTableId['SystemTable']['id'];
        $this->TrainingType->create();
        $this->TrainingType->save($trainingType);
        
        $trainingType                         = array();
        $trainingType['title']                = 'MR Trainings';
        $trainingType['training_description'] = 'All MR related trainings will be under this category';
        $trainingType['mandetory']            = 1;
        $trainingType['publish']              = 1;
        $trainingType['soft_delete']          = 0;
        $trainingType['company_id']           = $this->Session->read('User.company_id');
        $trainingType['system_table_id']      = $systemTableId['SystemTable']['id'];
        $this->TrainingType->create();
        $this->TrainingType->save($trainingType);
        
        $trainingType                         = array();
        $trainingType['title']                = 'Technical Trainings';
        $trainingType['training_description'] = 'All Technical trainings will be under this category';
        $trainingType['mandetory']            = 1;
        $trainingType['publish']              = 1;
        $trainingType['soft_delete']          = 0;
        $trainingType['company_id']           = $this->Session->read('User.company_id');
        $trainingType['system_table_id']      = $systemTableId['SystemTable']['id'];
        $this->TrainingType->create();
        $this->TrainingType->save($trainingType);
        
        //Add Training / Courses
        $systemTableId = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => 'course_types'
            )
        ));
        
        $this->loadModel('CourseType');
        
        $courseType                         = array();
        $courseType['title']                = 'HR Courses';
        $courseType['training_description'] = 'All HR related Courses will be under this category';
        $courseType['mandetory']            = 1;
        $courseType['publish']              = 1;
        $courseType['soft_delete']          = 0;
        $courseType['company_id']           = $this->Session->read('User.company_id');
        $courseType['system_table_id']      = $systemTableId['SystemTable']['id'];
        $this->CourseType->create();
        $this->CourseType->save($courseType);
        
        $hrCourseType = $this->CourseType->id;
        
        $courseType                         = array();
        $courseType['title']                = 'MR Courses';
        $courseType['training_description'] = 'All MR related courses will be under this category';
        $courseType['mandetory']            = 1;
        $courseType['publish']              = 1;
        $courseType['soft_delete']          = 0;
        $courseType['company_id']           = $this->Session->read('User.company_id');
        $courseType['system_table_id']      = $systemTableId['SystemTable']['id'];
        $this->CourseType->create();
        $this->CourseType->save($courseType);
        
        $mrCourseType = $this->Course->id;
        
        $courseType                         = array();
        $courseType['title']                = 'Technical Trainings';
        $courseType['training_description'] = 'All Technical courses will be under this category';
        $courseType['mandetory']            = 1;
        $courseType['publish']              = 1;
        $courseType['soft_delete']          = 0;
        $courseType['company_id']           = $this->Session->read('User.company_id');
        $courseType['system_table_id']      = $systemTableId['SystemTable']['id'];
        $this->CourseType->create();
        $this->CourseType->save($courseType);
        
        // Add Traininer Types
        $systemTableId = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => 'trainer_types'
            )
        ));
        $this->loadModel('TrainerType');
        
        $internalType                    = array();
        $internalType['title']           = 'Internal Trainer';
        $internalType['mandetory']       = 1;
        $internalType['publish']         = 1;
        $internalType['soft_delete']     = 0;
        $internalType['company_id']      = $this->Session->read('User.company_id');
        $internalType['system_table_id'] = $systemTableId['SystemTable']['id'];
        $this->TrainerType->create();
        $this->TrainerType->save($internalType);
        
        $internalTrainer = $this->TrainerType->id;
        
        $externalType                    = array();
        $externalType['title']           = 'External Trainer';
        $externalType['mandetory']       = 1;
        $externalType['publish']         = 1;
        $externalType['soft_delete']     = 0;
        $externalType['company_id']      = $this->Session->read('User.company_id');
        $externalType['system_table_id'] = $systemTableId['SystemTable']['id'];
        $this->TrainerType->create();
        $this->TrainerType->save($externalType);
        
        //add new course
        $systemTableId = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => 'courses'
            )
        ));
        $this->loadModel('Course');
        
        $course                    = array();
        $course['title']           = 'HR Induction Courses';
        $course['description']     = 'As per ISO standards it is mandetory to under go induction';
        $course['course_type_id']  = $hrCourseType;
        $course['mandetory']       = 1;
        $course['publish']         = 1;
        $course['soft_delete']     = 0;
        $course['company_id']      = $this->Session->read('User.company_id');
        $course['system_table_id'] = $systemTableId['SystemTable']['id'];
        $this->Course->create();
        $this->Course->save($course);
        
        $course                    = array();
        $course['title']           = 'MR Internal Auditor Training';
        $course['description']     = 'Training / Course attainted related to MR';
        $course['course_type_id']  = $mrCourseType;
        $course['mandetory']       = 1;
        $course['publish']         = 1;
        $course['soft_delete']     = 0;
        $course['company_id']      = $this->Session->read('User.company_id');
        $course['system_table_id'] = $systemTableId['SystemTable']['id'];
        $this->Course->create();
        $this->Course->save($course);
        
        //Add Trainer
        $companyName = $this->_get_company();
        $this->loadModel('Trainer');
        $trainer['trainer_type_id'] = $internalTrainer;
        $trainer['name']            = $this->Session->read('User.name');
        $trainer['company']         = $companyName['Company']['name'];
        $trainer['publish']         = 1;
        $trainer['soft_delete']     = 0;
        $trainer['company_id']      = $this->Session->read('User.company_id');
        $trainer['system_table_id'] = $systemTableId['SystemTable']['id'];
        $this->Trainer->create();
        $this->Trainer->save($trainer);
    }
    
    /* cron job function -- */
    
    public function _send_email($officeEmailId, $personalEmailId, $template, $viewVars)
    {
        
        if ($officeEmailId != '') {
            $email = $officeEmailId;
        } else if ($personalEmailId != '') {
            $email = $personalEmailId;
        }
        if ($email) {
            try {

                if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                else $env = "";

                App::uses('CakeEmail', 'Network/Email');
                if ($this->Session->read('User.is_smtp') == 1)
                    $EmailConfig = new CakeEmail("smtp");
                if ($this->Session->read('User.is_smtp') == 0)
                    $EmailConfig = new CakeEmail("default");
                $EmailConfig->to($email);
                $EmailConfig->subject('FlinkISO: Demo Expiration Reminder');
                $EmailConfig->template($template);
                $EmailConfig->viewVars(array(
                    'view_vars' => $viewVars,
                    'env'=>$env
                ));
                $EmailConfig->emailFormat('html');
                $EmailConfig->send();
            }
            catch (Exception $e) {
                $this->Session->setFlash(__('Can not notify user using email. Please check SMTP details and email address is correct.'));
            }
            
        }
    }
    
    public function expiry_reminder($diff)
    {
        $currentTime = date('Y-m-d');
        $this->loadModel('Company');
        $this->loadModel('Employee');
        $companies = $this->Company->find('all', array(
            'conditions' => array(
                'Company.publish' => 1,
                'Company.soft_delete' => 0
            )
        ));
        if ($companies) {
            foreach ($companies as $company) {
                $flinkisoEndDate = date('Y-m-d', strtotime($company['Company']['flinkiso_end_date']));
                $dateDiff        = round(abs(strtotime($flinkisoEndDate) - strtotime($currentTime)) / 86400);
                $users           = $this->User->find('all', array(
                    'conditions' => array(
                        'User.company_id' => $company['Company']['id'],
                        'User.is_mr' => 1,
                        'User.publish' => 1,
                        'User.soft_delete' => 0
                    ),
                    'recursive' => -1
                ));
                
                foreach ($users as $user) {
                    
                    $userName = $this->Employee->find('first', array(
                        'conditions' => array(
                            'Employee.id' => $user['User']['employee_id']
                        ),
                        'fields' => array(
                            'Employee.name',
                            '   Employee.office_email',
                            'Employee.personal_email'
                        ),
                        'recursive' => -1
                    ));
                    
                    if ($dateDiff >= $diff) {
                        $template = 'expiryReminder';
                        $viewVars = $flinkisoEndDate;
                        
                        $this->_send_email($userName['Employee']['office_email'], $userName['Employee']['personal_email'], $template, $viewVars);
                    }
                }
            }
        }
        exit();
    }
    
    public function login_reminder()
    {
        $currentDate = date('Y-m-d');
        $this->loadModel('Company');
        $this->loadModel('Employee');
        $companies = $this->Company->find('all', array(
            'conditions' => array(
                'Company.   publish' => 1,
                'Company.soft_delete' => 0
            )
        ));
        if ($companies) {
            foreach ($companies as $company) {
                $companyEndDate = date('Y-m-d', strtotime('-3 days', strtotime($company['Company']['flinkiso_end_date'])));
                
                if ($companyEndDate == $currentDate) {
                    
                    $users = $this->User->find('all', array(
                        'conditions' => array(
                            'User.company_id' => $company['Company']['id'],
                            'User.is_mr' => 1,
                            'User.publish' => 1,
                            'User.soft_delete' => 0
                        ),
                        'recursive' => -1
                    ));
                    foreach ($users as $user) {
                        $userName = $this->Employee->find('first', array(
                            'conditions' => array(
                                'Employee.id' => $user['User']['employee_id']
                            ),
                            'fields' => array(
                                'Employee.name',
                                '   Employee.office_email',
                                'Employee.personal_email'
                            )
                        ));
                        $template = 'loginReminder';
                        $viewVars = $company['Company']['flinkiso_end_date'];
                        $this->_send_email($userName['Employee']['office_email'], $userName['Employee']['personal_email'], $template, $viewVars);
                    }
                }
            }
        }
        exit();
    }
    
    public function appraisal_answers($token = null)
    {
        
        if (empty($token) && !$this->request->is('post')) {
            $this->Session->setFlash(__('Invalid performance review token, try again.'), 'default', array(
                'class' => 'alert-danger'
            ));
            $this->redirect(array(
                'action' => 'login'
            ));
        }
        $this->layout = 'login';
        $this->loadModel('EmployeeAppraisalQuestion');
        $this->loadModel('Appraisal');
        $appraisal = $this->Appraisal->find('first', array(
            'conditions' => array(
                'appraisal_token' => $token,
                'appraisal_token_expires >' => date('Y-m-d H:i:s')
            ),
            'recursive' => -1,
            'fields' => 'Appraisal.id'
        ));
        if (count($appraisal)) {
            
            $employeeAppraisals = $this->EmployeeAppraisalQuestion->find('all', array(
                'conditions' => array(
                    'EmployeeAppraisalQuestion.appraisal_id' => $appraisal['Appraisal']['id']
                ),
                'fields' => array(
                    'id',
                    'appraisal_question_id',
                    'answer'
                ),
                'recursive' => -1
            ));
            
            if (count($employeeAppraisals) > 0) {
                if ($this->request->is('post') || $this->request->is('put')) {
                    
                    foreach ($this->request->data['EmployeeAppraisalQuestion'] as $appraisalQuestion):
                        $this->EmployeeAppraisalQuestion->save($appraisalQuestion);
                    endforeach;
                    
                    $data['Appraisal']['id']                    = $appraisal['Appraisal']['id'];
                    $data['Appraisal']['self_appraisal_status'] = 1;
                    $this->Appraisal->save($data['Appraisal'], false);
                    
                    $this->Session->setFlash(__('Appraisal Answers Saved.'), 'default', array(
                        'class' => 'alert-success'
                    ));
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                }
            } else {
                echo __('There are no appraisal questions.');
            }
            $appraisalQuestions = $this->EmployeeAppraisalQuestion->AppraisalQuestion->find('list', array(
                'fields' => 'question'
            ));
            
            $this->set(compact('employeeAppraisals', 'appraisalQuestions'));
        } else {
            $this->Session->setFlash(__('Invalid performance review token, try again.'), 'default', array(
                'class' => 'alert-danger'
            ));
            $this->redirect(array(
                'action' => 'login'
            ));
        }
    }
    
    function smtp_details($username = null)
    {
        $this->loadModel('Company');
        if (!$this->Session->read('User.id')) {
            
            $record = $this->Company->find('first', array(
                'fields' => 'Company.smtp_setup',
                'recursive' => -1
            ));
            if ($record['Company']['smtp_setup'] == 1) {
                
                $this->Session->setFlash(__('Please login to setup SMTP details'), 'default', array(
                    'class' => 'alert-danger'
                ));
                $this->redirect(array(
                    'action' => 'login',
                    $username
                ));
            }
            $this->layout = "login";
        }
        
        $isSmtp     = 0;
        $transport  = null;
        $SmtpDetail = $this->Company->find('first', array(
            'fields' => array(
                'Company.is_smtp',
                'Company.smtp_setup'
            ),
            'recursive' => -1
        ));
        if ($SmtpDetail['Company']['smtp_setup'] == 1) {
            $Email = new CakeEmail();
            $Email->config('smtp');
            $transport = $Email->transport('smtp')->config();
        }
        
        if ($SmtpDetail['Company']['is_smtp'] == 1) {
            $isSmtp = 1;
        }
        $this->set(compact('isSmtp', 'transport'));
        
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->request->data['User']['is_smtp'] == 0) {
                $this->loadModel('Company');
                $id                         = $this->Company->find('first', array(
                    'fields' => 'Company.id',
                    'recursive' => -1
                ));
                $this->Company->id          = $id;
                $data['Company']['is_smtp'] = 0;
                $this->Company->id;
                $this->Company->save($data);
                $string = '<?php
/**
 * This is email configuration file.
 *
 * Use it to configure email transports of Cake.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * Email configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * transport => The name of a supported transport; valid options are as follows:
 *      Mail        - Send using PHP mail function
 *      Smtp        - Send using SMTP
 *      Debug       - Do not send the email, just return the result
 *
 * You can add custom transports (or override existing transports) by adding the
 * appropriate file to app/Network/Email. Transports should be named "YourTransport.php",
 * where "Your" is the name of the transport.
 *
 * from =>
 * The origin email. See CakeEmail::from() about the valid values
 *
 */
class EmailConfig {

    public $default = array(
        "transport" => "Mail",
        "from" => array("' . $this->request->data['User']['default_user'] . '" => "FlinkISO"),
        //"charset" => "utf-8",
        //"headerCharset" => "utf-8",
    );

    public $smtp = array(

                "transport" => "Smtp",
        "from" => array("noreply@flinkiso.com" => "FlinkISO"),
        "host" => "smtp.gmail.com",
        "port" => 465,
        "timeout" => 30,
        "username" => "my@gmail.com",
        "password" => "secret",
        "client" => null,
        "log" => false,
    );

    public $fast = array(
        "from" => "you@localhost",
        "sender" => null,
        "to" => null,
        "cc" => null,
        "bcc" => null,
        "replyTo" => null,
        "readReceipt" => null,
        "returnPath" => null,
        "messageId" => true,
        "subject" => null,
        "message" => null,
        "headers" => null,
        "viewRender" => null,
        "template" => false,
        "layout" => false,
        "viewVars" => null,
        "attachments" => null,
        "emailFormat" => null,
        "transport" => "Smtp",
        "host" => "localhost",
        "port" => 25,
        "timeout" => 30,
        "username" => "user",
        "password" => "secret",
        "client" => null,
        "log" => true,
        //"charset" => "utf-8",
        //"headerCharset" => "utf-8",
    );



}';
                
                $fp = fopen(APP . "Config/email.php", "w");
                fwrite($fp, $string);
                fclose($fp);
                
                $this->Session->setFlash(__('Default email setup done successfully.'), 'default', array(
                    'class' => 'alert-success'
                ));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login',
                    $username
                ));
            } else {
                
                $this->loadModel('Company');
                $id                         = $this->Company->find('first', array(
                    'fields' => 'Company.id',
                    'recursive' => -1
                ));
                $this->Company->id          = $id;
                $data['Company']['is_smtp'] = 1;
                $this->Company->id;
                $this->Company->save($data);
                $string = '<?php
/**
 * This is email configuration file.
 *
 * Use it to configure email transports of Cake.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * Email configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * transport => The name of a supported transport; valid options are as follows:
 *      Mail        - Send using PHP mail function
 *      Smtp        - Send using SMTP
 *      Debug       - Do not send the email, just return the result
 *
 * You can add custom transports (or override existing transports) by adding the
 * appropriate file to app/Network/Email. Transports should be named "YourTransport.php",
 * where "Your" is the name of the transport.
 *
 * from =>
 * The origin email. See CakeEmail::from() about the valid values
 *
 */
class EmailConfig {

    public $default = array(
        "transport" => "Mail",
        "from" => array("noreply@flinkiso.com" => "FlinkISO"),
        //"charset" => "utf-8",
        //"headerCharset" => "utf-8",
    );

    public $smtp = array(

                "transport" => "Smtp",
        "from" => array("' . $this->request->data['User']['smtp_user'] . '" => "FlinkISO"),
        "host" => "' . $this->request->data['User']['smtp_host'] . '",
        "port" => ' . $this->request->data['User']['port'] . ',
        "timeout" => 30,
        "username" => "' . $this->request->data['User']['smtp_user'] . '",
        "password" => "' . $this->request->data['User']['smtp_password'] . '",
        "client" => null,
        "log" => false,
    );

    public $fast = array(
        "from" => "you@localhost",
        "sender" => null,
        "to" => null,
        "cc" => null,
        "bcc" => null,
        "replyTo" => null,
        "readReceipt" => null,
        "returnPath" => null,
        "messageId" => true,
        "subject" => null,
        "message" => null,
        "headers" => null,
        "viewRender" => null,
        "template" => false,
        "layout" => false,
        "viewVars" => null,
        "attachments" => null,
        "emailFormat" => null,
        "transport" => "Smtp",
        "host" => "localhost",
        "port" => 25,
        "timeout" => 30,
        "username" => "user",
        "password" => "secret",
        "client" => null,
        "log" => true,
        //"charset" => "utf-8",
        //"headerCharset" => "utf-8",
    );



}';
                
                $fp = fopen(APP . "Config/email.php", "w");
                fwrite($fp, $string);
                fclose($fp);
                
                $this->loadModel('Employee');
                $userData = $this->Employee->find('first', array(
                    'recursive' => -1
                ));
                
                if ($userData['Employee']['office_email'] != '') {
                    $email = $userData['Employee']['office_email'];
                } else if ($userData['Employee']['personal_email'] != '') {
                    $email = $userData['Employee']['personal_email'];
                }
                if ($email) {
                    
                    
                    try {
                        
                        if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                        elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                        else $env = "";

                        App::uses('CakeEmail', 'Network/Email');
                        $EmailConfig = new CakeEmail("smtp");
                        $EmailConfig->to($email);
                        $EmailConfig->subject('FlinkISO: Smtp Setup details');
                        $EmailConfig->template('smtpSetup');
                        $EmailConfig->viewVars(array(
                            'name' => $userData['Employee']['name'],
                            'env' => $env, 'app_url' => FULL_BASE_URL
                        ));
                        $EmailConfig->emailFormat('html');
                        
                        $EmailConfig->send();
                        $companyData               = array();
                        $companyData['id']         = $userData['Employee']['company_id'];
                        $companyData['smtp_setup'] = 1;
                        $this->loadModel('Company');
                        $this->Company->save($companyData, false);
                        $this->Session->setFlash(__('SMTP setup done successfully.'), 'default', array(
                            'class' => 'alert-success'
                        ));
                        
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login',
                            $username
                        ));
                    }
                    catch (Exception $e) {
                        $exceptionMessage          = $e->getMessage();
                        $invalidPass               = substr($exceptionMessage, 0, 15);
                        $companyData               = array();
                        $companyData['id']         = $userData['Employee']['company_id'];
                        $companyData['smtp_setup'] = 0;
                        $this->loadModel('Company');
                        $this->Company->save($companyData, false);
                        if (($invalidPass === 'SMTP Error: 535') == 1) {
                            $this->Session->setFlash(__('Can not connect with SMTP server: Invalid password'), 'default', array(
                                'class' => 'alert-danger'
                            ));
                        } else {
                            $this->Session->setFlash(__('Can not connect with SMTP server: ' . $e->getMessage()), 'default', array(
                                'class' => 'alert-danger'
                            ));
                        }
                    }
                }
            }
        }
        $isSmtp            = 0;
        $transport         = null;
        $default_transport = null;
        $SmtpDetail        = $this->Company->find('first', array(
            'fields' => array(
                'Company.is_smtp',
                'Company.smtp_setup'
            ),
            'recursive' => -1
        ));
        $Email             = new CakeEmail();
        if ($SmtpDetail['Company']['is_smtp'] == 1) {
            
            $Email->config('smtp');
            $transport = $Email->transport('smtp')->config();
        } else {
            $Email->config('default');
            $default_transport = $Email->transport('default')->config();
        }
        
        if ($SmtpDetail['Company']['is_smtp'] == 1) {
            $isSmtp = 1;
        }
        $this->set(compact('isSmtp', 'transport', 'default_transport', 'username'));
    }
    
    public function dashboard_files()
    {
        
    }
    
    public function check_registration($userEmailID = null, $downloadKey = null)
    {
        if (isset($userEmailID) && isset($downloadKey)) {
            $license_key = base64_decode($downloadKey);
            
            $this->loadModel('Company');
            $registrationCheck = $this->Company->find('count', array(
                'recursive' => -1,
                'conditions' => array(
                    'Company.liscence_key' => $license_key
                )
            ));
            if ($registrationCheck) {
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login',
                    $userEmailID
                ));
            } else {
                $url = "www.flinkiso.com/checkLicenceKey.php";
                $ch  = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
                //                curl_setopt($ch, CURLOPT_HEADER, TRUE );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0"
                ));
                $postfields                 = array();
                $postfields['liscence_key'] = urlencode($license_key);
                
                curl_setopt($ch, CURLOPT_POST, count($postfields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                $ret = curl_exec($ch);
                // Close handle
                curl_close($ch);
                
                $regDetails = json_decode($ret, true);
                $this->loadModel('Employee');
                $emailExists = $this->Employee->find('count', array(
                    'conditions' => array(
                        'Employee.personal_email' => $regDetails['email'],
                        'OR' => array(
                            array(
                                'Employee.office_email' => $regDetails['email']
                            )
                        )
                    ),
                    'recursive' => -1
                ));
                if ($emailExists) {
                    //                    $this->Session->setFlash(__('Email id already exists. Please login.'), 'default', array('class'=>'alert-danger'));
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login',
                        $userEmailID
                    ));
                } else {
                    //new curl request
                    $url = "http://demo.flinkiso.com/users/register";
                    $ch  = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, TRUE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0"
                    ));
                    $postfields                                   = array();
                    $postfields['User']['name']                   = $regDetails['name'];
                    $postfields['User']['company']                = $regDetails['company'];
                    $postfields['User']['email']                  = $regDetails['email'];
                    $postfields['User']['phone']                  = $regDetails['phone'];
                    $postfields['User']['password']               = substr($license_key, 0, 5);
                    $postfields['User']['sample_data']            = 0;
                    $postfields['User']['liscence_key_installed'] = $regDetails['download_key'];
                    $postfields['User']['city']                   = $regDetails['city'];
                    $postfields['User']['state']                  = $regDetails['state'];
                    $postfields['User']['country']                = $regDetails['country'];
                    $postfields['User']['zip']                    = $regDetails['zip'];
                    curl_setopt($ch, CURLOPT_POST, count($postfields));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
                    $ret = curl_exec($ch);
                    curl_close($ch);
                    $this->Session->setFlash(__('Email id already exists. Please login.'), 'default', array(
                        'class' => 'alert-danger'
                    ));
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login',
                        $userEmailID
                    ));
                    
                }
            }
        }
        exit;
    }
    
    
    function password_setting()
    {
        
    }
    
    function two_way_authentication()
    {
        $this->loadModel('Company');
        if ($this->request->is('post') || $this->request->is('put')) {
            $id                                        = $this->Company->find('first', array(
                'fields' => 'Company.id',
                'recursive' => -1
            ));
            $this->Company->id                         = $id;
            $data['Company']['two_way_authentication'] = $this->request->data['User']['two_way_authentication'];
            $this->Company->id;
            $this->Company->save($data);
            if ($this->request->data['User']['two_way_authentication'] == 1)
                $this->Session->setFlash(__('Two way authentication enabled successfully.'), 'default', array(
                    'class' => 'alert-success'
                ));
            else
                $this->Session->setFlash(__('Two way authentication disabled successfully.'), 'default', array(
                    'class' => 'alert-success'
                ));
            
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'two_way_authentication'
            ));
        }
        $authentication = $this->Company->find('first', array(
            'fields' => array(
                'Company.two_way_authentication'
            ),
            'recursive' => -1
        ));
        $this->set(compact('authentication'));
    }
    
    function opt_check()
    {
        $this->layout = 'login';
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->Session->read('UserIdentity'),
                    'User.publish' => 1,
                    'User.soft_delete' => 0
                )
            ));
            if ($this->Session->read('OPTCode') == $this->request->data['User']['opt_code']) {
                $_SESSION['User']['id']        = $user['User']['id'];
                $user['User']['last_login']    = date('Y-m-d H:i:s');
                $user['User']['last_activity'] = date('Y-m-d H:i:s');
                $user['User']['login_status']  = 0; // 1
                $this->User->save($user, false);
                $this->Session->write('User.id', $user['User']['id']);
                $this->Session->write('User.employee_id', $user['Employee']['id']);
                $this->Session->write('User.branch_id', $user['User']['branch_id']);
                $this->Session->write('User.department_id', $user['User']['department_id']);
                $this->Session->write('User.branch', $user['Branch']['name']);
                $this->Session->write('User.department', $user['Department']['name']);
                $this->Session->write('User.name', $user['Employee']['name']);
                $this->Session->write('User.username', $user['User']['username']);
                $this->Session->write('User.lastLogin', $user['User']['last_login']);
                $this->Session->write('User.is_mr', $user['User']['is_mr']);
                $this->Session->write('User.company_id', $user['User']['company_id']);
                $this->Session->write('User.is_smtp', $user['Company']['is_smtp']);
                
                
                if ($user['User']['is_mr'] == 1)
                    $this->Session->write('User.is_view_all', 1);
                else
                    $this->Session->write('User.is_view_all', $user['User']['is_view_all']);
                $this->Session->write('User.is_approvar', $user['User']['is_approvar']);
                $this->loadModel('Language');
                $languageData = array();
                $languageData = $this->Language->find('first', array(
                    'conditions' => array(
                        'Language.id' => $user['User']['language_id']
                    ),
                    'recursive' => -1
                ));
                $this->Session->write('SessionLanguage', null);
                if ($languageData['Language']['short_code']) {
                    $this->Session->write('SessionLanguage', $languageData['Language']['short_code']);
                }
                if ($user['User']['agree'] && $user['User']['agree'] != 0) {
                    $this->Session->write('TANDC', 1);
                    $this->loadModel('UserSession');
                    $this->UserSession->create();
                    $data['UserSession']['ip_address']      = $_SERVER['REMOTE_ADDR'];
                    $data['UserSession']['browser_details'] = json_encode($_SERVER);
                    $data['UserSession']['start_time']      = date('Y-m-d H:i:s');
                    $data['UserSession']['end_time']        = date('Y-m-d H:i:s');
                    $data['UserSession']['user_id']         = $this->Session->read('User.id');
                    $data['UserSession']['employee_id']     = $this->Session->read('User.employee_id');
                    $data['UserSession']['company_id']      = $this->Session->read('User.company_id');
                    $this->UserSession->save($data, false);
                    $this->Session->write('User.user_session_id', $this->UserSession->id);
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'terms_and_conditions'
                    ));
                } else {
                    
                    $this->loadModel('UserSession');
                    $this->UserSession->create();
                    $data['UserSession']['ip_address']      = $_SERVER['REMOTE_ADDR'];
                    $data['UserSession']['browser_details'] = json_encode($_SERVER);
                    $data['UserSession']['start_time']      = date('Y-m-d H:i:s');
                    $data['UserSession']['end_time']        = date('Y-m-d H:i:s');
                    $data['UserSession']['user_id']         = $this->Session->read('User.id');
                    $data['UserSession']['employee_id']     = $this->Session->read('User.employee_id');
                    $data['UserSession']['company_id']      = $this->Session->read('User.company_id');
                    $this->UserSession->save($data, false);
                    $this->Session->write('User.user_session_id', $this->UserSession->id);
                    
                    
                    $this->loadModel('MasterListOfFormat');
                    $checkForms = $this->MasterListOfFormat->find('count', array(
                        'conditions' => array(
                            'MasterListOfFormat.company_id' => $this->Session->read('User.company_id')
                        )
                    ));
                    
                    /* if (isset($checkForms) && ($checkForms > 0)) */
                    $this->redirect(array(
                        'action' => 'dashboard'
                    ));
                    /* else
                    $this->redirect(array('action' => 'welcome')); */
                }
            } else {
                
                $user['User']['login_status'] = 0;
                
                $this->User->save($user, false);
                $this->Session->write('User.id', NULL);
                $this->Session->destroy('User');
                $this->Session->destroy('OPTCode');
                $this->Session->setFlash(__('Invalid OTP Code.'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
        }
        
    }
    public function timelinetabs()
    {
    }
    
    public function dashboardgraphs()
    {
    }
    
    public function objective_monitoring_employee(){
        $this->loadModel('Objective');

        $this->loadModel('Schedule');
        $schedules = $this->Schedule->find('list', array(
            'conditions' => array(
                'Schedule.publish' => 1,
                'Schedule.soft_delete' => 0
            )
        ));
        
        $allEmployees = $this->_get_employee_list();
        if($this->Session->read('User.is_mr')==0)$emmcon = array( 'Objective.employee_id'=>$this->Session->read('User.employee_id'));
        else $emmcon = array();
        $objectives = $this->Objective->find('all',array(
            'fields'=>array(
                'Objective.id','Objective.title','Objective.list_of_kpi_id','Objective.list_of_kpi_ids',
                'Objective.branch_id','Objective.department_id','Objective.employee_id','Objective.schedule_id','Objective.target_date','Objective.current_status',
                'Objective.publish','Objective.soft_delete'
                ),
            'recursive'=>-1,
            'conditions'=>array('Objective.current_status'=>0, 'Objective.publish'=>1,'Objective.soft_delete'=>0,$emmcon)));
        
        if($this->Session->read('User.is_mr')==1){
            $nonmrcon = array();
        }else{
            $nonmrcon = array('Objective.employee_id'=>$this->Session->read('User.employee_id'));
        }
        foreach ($objectives as $objective) {
            //get latest monitoring based on schedule
            switch ($schedules[$objective['Objective']['schedule_id']]) {
                case 'Daily':
                    $monitoring['daily'] = $this->Objective->ObjectiveMonitoring->find('count',array(
                        'conditions'=>array($nonmrcon,
                            'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                date('Y-m-d 00:00:00'),
                                date('Y-m-d 24:59:99')
                            ))
                        ));
                    if($monitoring['daily'] == 0){
                        $result['daily'][$objective['Objective']['title']] = array(
                                'id'=>$objective['Objective']['id'],
                                'title'=>$objective['Objective']['title'],
                                'assigned_to'=>$allEmployees[$objective['Objective']['employee_id']],
                                'target_date'=>$objective['Objective']['target_date'],
                                'status'=>'Pending'
                            );
                    }
                    break;
                case 'Weekly':
                    $first_day_of_week = date('m-d-Y', strtotime('Last Monday', time()));
                    $last_day_of_week  = date('m-d-Y', strtotime('Next Sunday', time()));

                    $monitoring['weekly'] = $this->Objective->ObjectiveMonitoring->find('count',array(
                        'conditions'=>array($nonmrcon,
                            'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                $first_day_of_week,
                                $last_day_of_week
                            ))
                        ));
                    if($monitoring['weekly'] == 0){
                        $result['weekly'][$objective['Objective']['title']] = array(
                                'id'=>$objective['Objective']['id'],
                                'title'=>$objective['Objective']['title'],
                                'assigned_to'=>$allEmployees[$objective['Objective']['employee_id']],
                                'target_date'=>$objective['Objective']['target_date'],
                                'status'=>'Pending'
                            );
                    }
                    break;
                case 'Monthly':
                    $monitoring['monthly'] = $this->Objective->ObjectiveMonitoring->find('count',array(
                        'conditions'=>array($nonmrcon,
                            'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                date('Y-m-1'),
                                date('Y-m-t')
                            ))
                        ));
                    if($monitoring['monthly'] == 0){
                        $result['monthly'][$objective['Objective']['title']] = array(
                                'id'=>$objective['Objective']['id'],
                                'title'=>$objective['Objective']['title'],
                                'assigned_to'=>$allEmployees[$objective['Objective']['employee_id']],
                                'target_date'=>$objective['Objective']['target_date'],
                                'status'=>'Pending'
                            );
                    }
                    break;
                case 'Quarterly':
                    $start_date = date("Y-m-d", strtotime($objective['Objective']['created']));
                    $quarter            = date("Y-m-d", strtotime("+3 month", strtotime($process_start_date)));
                    
                    $monitoring['quarterly'] = $this->Objective->ObjectiveMonitoring->find('count',array(
                        'conditions'=>array($nonmrcon,
                            'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                $start_date,
                                $quarter
                            ))
                        ));
                    if($monitoring['quarterly'] == 0){
                        $result['quarterly'][$objective['Objective']['title']] = array(
                                'id'=>$objective['Objective']['id'],
                                'title'=>$objective['Objective']['title'],
                                'assigned_to'=>$allEmployees[$objective['Objective']['employee_id']],
                                'target_date'=>$objective['Objective']['target_date'],
                                'status'=>'Pending'
                            );
                    }
                    break;
                case 'Yearly':
                    $start_date = date("Y-m-d", strtotime($objective['Objective']['created']));
                    $year            = date("Y-m-d", strtotime("+12 month", strtotime($process_start_date)));
                    $monitoring['yearly'] = $this->Objective->ObjectiveMonitoring->find('count',array(
                        'conditions'=>array($nonmrcon,
                            'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                $start_date,
                                $quarter
                            ))
                        ));
                    if($monitoring['yearly'] == 0){
                        $result['yearly'][$objective['Objective']['title']] = array(
                                'id'=>$objective['Objective']['id'],
                                'title'=>$objective['Objective']['title'],
                                'assigned_to'=>$allEmployees[$objective['Objective']['employee_id']],
                                'target_date'=>$objective['Objective']['target_date'],
                                'status'=>'Pending'
                            );
                    }
                    break;
            }
            
        }
        $this->set('emplolyee_objective_monitoring',$result);
        
    }
    public function objective_monitoring()
    {
        // get objective monitoring based on schedule
        
        $this->loadModel('ProcessTeam');
        $this->loadModel('ObjectiveMonitoring');
        //show to MR
        if (!$this->Session->read('User.is_mr')) {
            $condition = array(
                'Process.owner_id' => $this->Session->read('User.id')
            );
        }
        $processes = $this->ProcessTeam->find('all', array(
            'fields' => array(
                'ProcessTeam.id',
                'ProcessTeam.start_date',
                'ProcessTeam.end_date',
                'ProcessTeam.objective_id',
                'ProcessTeam.process_id',
                'Objective.id',
                'Objective.title',
                'Objective.clauses',
                'Owner.id',
                'Owner.name',
                'Process.id',
                'Process.title',
                'Process.schedule_id'
            ),
            'conditions' => array(
                $condition,
                'ProcessTeam.start_date <= ' => date('Y-m-d'),
                'ProcessTeam.end_date >= ' => date('Y-m-d'),
                'ProcessTeam.publish' => 1,
                'ProcessTeam.soft_delete' => 0
            )
        ));
        
        //throw schedule-wise alerts
        $process_count = 0;
        $this->loadModel('Schedule');
        $schedules = $this->Schedule->find('list', array(
            'conditions' => array(
                'Schedule.publish' => 1,
                'Schedule.soft_delete' => 0
            )
        ));
        
        foreach ($processes as $process) {
            switch ($schedules[$process['Process']['schedule_id']]) {
                case 'Daily':
                case 'daily':
                    # Daily
                    $result = $this->ObjectiveMonitoring->find('count', array(
                        'conditions' => array(
                            'ObjectiveMonitoring.process_id' => $process['Process']['id'],
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                date('Y-m-d 00:00:00'),
                                date('Y-m-d 24:59:99')
                            )
                        )
                    ));
                    if ($result == 0) {
                        $monitoring['Daily'][$process_count]['objective_id']    = $process['Objective']['id'];
                        $monitoring['Daily'][$process_count]['title']           = $process['Objective']['title'];
                        $monitoring['Daily'][$process_count]['owner']           = $process['Owner']['name'];
                        $monitoring['Daily'][$process_count]['clauses']         = $process['Objective']['clauses'];
                        $monitoring['Daily'][$process_count]['process_id']      = $process['Process']['id'];
                        $monitoring['Daily'][$process_count]['process_name']    = $process['Process']['title'];
                        $monitoring['Daily'][$process_count]['process_team_id'] = $process['ProcessTeam']['id'];
                        $monitoring['Daily'][$process_count]['status']          = 'pending';
                    }
                    break;
                case 'Weekly':
                    # Weekly
                    $first_day_of_week = date('m-d-Y', strtotime('Last Monday', time()));
                    $last_day_of_week  = date('m-d-Y', strtotime('Next Sunday', time()));
                    
                    $result = $this->ObjectiveMonitoring->find('count', array(
                        'conditions' => array(
                            'ObjectiveMonitoring.process_id' => $process['Process']['id'],
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                $first_day_of_week,
                                $last_day_of_week
                            )
                        )
                    ));
                    if ($result == 0) {
                        $monitoring['Weekly'][$process_count]['objective_id']    = $process['Objective']['id'];
                        $monitoring['Weekly'][$process_count]['title']           = $process['Objective']['title'];
                        $monitoring['Weekly'][$process_count]['owner']           = $process['Owner']['name'];
                        $monitoring['Weekly'][$process_count]['clauses']         = $process['Objective']['clauses'];
                        $monitoring['Weekly'][$process_count]['process_id']      = $process['Process']['id'];
                        $monitoring['Weekly'][$process_count]['process_name']    = $process['Process']['title'];
                        $monitoring['Weekly'][$process_count]['process_team_id'] = $process['ProcessTeam']['id'];
                        $monitoring['Weekly'][$process_count]['status']          = 'pending';
                    }
                    break;
                case 'Monthly':
                    # Monthly
                    $result = $this->ObjectiveMonitoring->find('count', array(
                        'conditions' => array(
                            'ObjectiveMonitoring.process_id' => $process['Process']['id'],
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                date('Y-m-1'),
                                date('Y-m-t')
                            )
                        )
                    ));
                    if ($result == 0) {
                        $monitoring['Monthly'][$process_count]['objective_id']    = $process['Objective']['id'];
                        $monitoring['Monthly'][$process_count]['title']           = $process['Objective']['title'];
                        $monitoring['Monthly'][$process_count]['owner']           = $process['Owner']['name'];
                        $monitoring['Monthly'][$process_count]['clauses']         = $process['Objective']['clauses'];
                        $monitoring['Monthly'][$process_count]['process_id']      = $process['Process']['id'];
                        $monitoring['Monthly'][$process_count]['process_name']    = $process['Process']['title'];
                        $monitoring['Monthly'][$process_count]['process_team_id'] = $process['ProcessTeam']['id'];
                        $monitoring['Monthly'][$process_count]['status']          = 'pending';
                    }
                    break;
                
                case 'Quarterly':
                    # Quarterly - code pending
                    $process_start_date = date("Y-m-d", strtotime($processes['ProcessTeam']['start_date']));
                    $quarter            = date("Y-m-d", strtotime("+3 month", strtotime($process_start_date)));
                    
                    
                    $monitoring['Quarterly'] = $this->ObjectiveMonitoring->find('count', array(
                        'conditions' => array(
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                $process_start_date,
                                $quarter
                            )
                        )
                    ));
                    if ($monitoring['Quarterly'] == 0) {
                        $monitoring['Quarterly'] = 'Pending';
                    }
                    break;
                
                case 'Yearly':
                    # Yearly
                    $monitoring['Yearly'] = $this->ObjectiveMonitoring->find('count', array(
                        'conditions' => array(
                            'ObjectiveMonitoring.created BETWEEN ? and ?' => array(
                                date('Y-m-d 00:00:00000'),
                                date('Y-m-d 24:59:99999')
                            )
                        )
                    ));
                    if ($monitoring['Yearly'] == 0) {
                        $monitoring['Yearly'] = 'Pending';
                    }
                    break;
            }
            $process_count++;
        }
        $this->set('monitoring', $monitoring);
    }
    
    public function management_team()
    {
        $conditions     = $this->_check_request();
        $this->paginate = array(
            'order' => array(
                'User.sr_no' => 'DESC'
            ),
            'conditions' => array(
                $conditions,
                array(
                    'User.is_mt' => 1
                )
            )
        );
        
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
        
        $this->_get_count();
        
    }
    
    public function pending_tasks(){
        // $allControllers = $this->Ctrl->get();
        
        
        $allControllers = array('CapaInvestigationsController'=>array(),'CapaRootCauseAnalysisController'=>array(),'CustomerComplaintsController'=>array());
        foreach ($allControllers as $key => $value) {
            $model = Inflector::Classify(str_replace('Controller','', $key));
            $this->loadModel($model);
            if(array_key_exists('target_date',$this->$model->schema())){
                $conditions = array();
                if(array_key_exists('current_status',$this->$model->schema())){
                    if($model == 'CapaInvestigation')$conditions = array($model.'.current_status'=>0);
                    else $conditions = array($model.'.current_status'=>1);
                }
                if(array_key_exists('status',$this->$model->schema())){
                    $conditions = array($model.'.status'=>0);
                }
                $result[$model] = $this->$model->find('count',array('conditions'=>array(
                    $conditions)));                
            }
        }
        // for calibration 
        $this->loadModel('Calibration');
        $calibrations = $this->Calibration->find('count',array('conditions'=>array('Calibration.next_calibration_date >'=>date('Y-m-d'))));
        $result['Calibration'] = $calibrations;

        $this->loadModel('DeviceMaintenance');
        $maintenance = $this->DeviceMaintenance->find('count',array('conditions'=>array('DeviceMaintenance.next_maintanence_date >'=>date('Y-m-d'))));
        $result['DeviceMaintenance'] = $maintenance;
        $this->set('results',$result);        
    }    


    public function _get_files_on_hold($employee_id = null,$milestone_id = null){
        $this->loadModel('ProjectFile');

        $last_action = $this->ProjectFile->FileProcess->find('first',array(
            'recursive'=>-1,
            'conditions'=>array(
                'FileProcess.employee_id'=>$employee_id,
                'FileProcess.milestone_id'=>$milestone_id,
                'FileProcess.current_status'=>array(7,10),
                'FileProcess.hold_start_time !='=> NULL,
                'FileProcess.hold_end_time'=> NULL,
            ),
            
            'order'=>array('FileProcess.sr_no'=>'DESC')
        )
    );
        $this->ProjectFile->virtualFields = array(
            'curr_stage' => 'select `file_processes`.`project_process_plan_id` from `file_processes` WHERE  `file_processes`.`project_file_id` = ProjectFile.id AND `file_processes`.`current_status` = 0 AND `file_processes`.`milestone_id` LIKE  ProjectFile.milestone_id ORDER BY `file_processes`.`created` DESC , `file_processes`.`sr_no` DESC LIMIT 1',
            // 'queued' => 'select `count(*) from `file_processes` WHERE  `file_processes`.`project_file_id` = ProjectFile.id AND  `file_processes`.`queued` = 1 LIMIT 1 ',
            'pro_pub' => 'select publish from projects where projects.id = ProjectFile.project_id',
            'pro_sd' => 'select soft_delete from projects where projects.id = ProjectFile.project_id',
            'mile_pub' => 'select publish from milestones where milestones.id = ProjectFile.milestone_id',
            'mile_sd' => 'select soft_delete from milestones where milestones.id = ProjectFile.milestone_id',
            'estimated_time_1'=>'ROUND(ProjectProcessPlan.estimated_units / ProjectProcessPlan.overall_metrics / ProjectProcessPlan.days / ProjectProcessPlan.estimated_resource)',
            'cat_on_hold'=>'select `file_categories`.`status` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
            'curr_employee'=> 'select employee_id from file_processes where file_processes.project_file_id LIKE ProjectFile.id AND file_processes.milestone_id LIKE ProjectFile.milestone_id ORDER BY created DESC , sr_no DESC LIMIT 1'
        );

        $this->ProjectFile->hasMany['FileProcess']['conditions'] = array(
            // 'FileProcess.hold_start_time !=' => null,
            // 'FileProcess.hold_end_time' =>null,
            // 'FileProcess.current_status' =>7,
            // 'FileProcess.employee_id' =>$this->Session->read('User.employee_id'),
        );

        $this->ProjectFile->hasMany['FileProcess']['order'] = array(
            'FileProcess.sr_no'=>'ASC'
        );
        
        $holdFile = $this->ProjectFile->find('first',array(
            'recursive'=>1,
            'conditions'=>array(
                // 'ProjectFile.hold_file >'=>0,
                'ProjectFile.id'=> $last_action['FileProcess']['project_file_id'],
                'ProjectFile.current_status'=> array(7,10),
            )
        ));

        return $holdFile;
    }

    public function pm_dashboard($sesisonid = null){
        if($sesisonid){
            $user = $this->User->find('first',array(
                'recursive'=>0,
                'conditions'=>array(
                    'User.employee_id'=>$sesisonid
                )));
            $this->Session->write('User.id', $user['User']['id']);
            $this->Session->write('User.employee_id', $user['Employee']['id']);
            $this->Session->write('User.branch_id', $user['User']['branch_id']);
            $this->Session->write('User.department_id', $user['User']['department_id']);
            $this->Session->write('User.branch', $user['Branch']['name']);
            $this->Session->write('User.department', $user['Department']['name']);
            $this->Session->write('User.name', $user['Employee']['name']);
            $this->Session->write('User.username', $user['User']['username']);
            $this->Session->write('User.lastLogin', $user['User']['last_login']);
            $this->Session->write('User.is_mr', $user['User']['is_mr']);
            $this->Session->write('User.company_id', $user['User']['company_id']);
            $this->Session->write('User.is_smtp', $user['Company']['is_smtp']);
            $this->Session->write('User.division_id', $user['User']['division_id']);
            

            // $this->_get_files_on_hold($this->Session->read('User.employee_id'));
            
            if ($user['User']['is_mr'] == 1)
                $this->Session->write('User.is_view_all', 1);
            else
                $this->Session->write('User.is_view_all', $user['User']['is_view_all']);
            $this->Session->write('User.is_approvar', $user['User']['is_approvar']);
            $this->loadModel('Language');
            $languageData = array();
            $languageData = $this->Language->find('first', array(
                'conditions' => array(
                    'Language.id' => $user['User']['language_id']
                ),
                'recursive' => -1
            ));
            // $language = $this->Language->find('first',array('recursive'=>-1, 'conditions'=>array('Language.short_code'=>$this->request->params['pass'][0])));
            // if($language){
                $this->Session->write('SessionLanguage', $languageData['Language']['id']);
                $this->Session->write('SessionLanguageCode', $languageData['Language']['short_code']);
        }

        $this->loadModel('Project');

        $this->set('pop',$this->request->params['pass'][2]);
        $this->set('por',$this->request->params['pass'][3]);

        $openProjects = $this->Project->find('count',array('conditions'=>array('Project.current_status'=>0)));
        $closedProjects = $this->Project->find('count',array('conditions'=>array('Project.current_status'=>1)));
        $delayedProjects = $this->Project->find('count',array('conditions'=>array('Project.current_status'=>0,'Project.end_date <' => date('Y-m-d'))));
        $projectChecklists = $this->Project->ProjectChecklist->find('list',array('conditions'=>array()));
        $this->set(compact('openProjects','closedProjects','delayedProjects','projectChecklists'));

        $this->loadModel('ProjectTimesheet');
        $projectResourcess = $this->ProjectTimesheet->Project->ProjectResource->find('all',
            array(
                // 'group'=>array('ProjectResource.project_id'),
                'conditions'=>array(
                    // 'ProjectResource.process_id != '=>array(null,-1),
                    'ProjectResource.user_id'=>$this->Session->read('User.id')
                ),
                'recursive'=>0
            )
        );
        $this->Project->virtualFields = array(
            'userexists' => 'select count(*) from `project_resources` where `project_resources`.`user_id` LIKE "'.$this->Session->read('User.id').'" and `project_resources`.`project_id` LIKE Project.id'
        );

        
        $projects = $this->Project->find('list',array(
            'order'=>array('Project.start_date' => 'ASC'),
            'conditions'=>array(
                'or'=>array(
                     'Project.userexists > '=> 0,
                     'Project.employee_id'=> $this->Session->read('User.employee_id'),
                ),
                'Project.publish'=>1,
                'Project.soft_delete'=>0,
                'Project.current_status !='=>array(3,4),
            )
        ));

        foreach ($projects as $key => $value) {
            $projectActivities[$key] = $this->ProjectTimesheet->Project->ProjectActivity->find('all',array(
                'fields'=>array(
                    'ProjectActivity.id',
                    'ProjectActivity.title',
                    'Milestone.id',
                    'Milestone.title',
                    'Project.id',
                    'Project.title',
                    'ProjectResource.id',
                    'ProjectResource.resource_cost',
                    'ProjectActivity.start_date',
                    'ProjectActivity.end_date',
                ),
                'recursive'=>0,
                // 'group'=>array('ProjectActivity.user_id'),
                'conditions'=>array(
                    'ProjectActivity.project_id'=>$key,
                    'ProjectActivity.current_status'=>0
                )
            ));
        }

        
        $this->set('projects',$projects);
        $this->set('projectActivities',$projectActivities);

        $this->loadModel('ProjectFile');
        
        $this->ProjectFile->virtualFields = array(
            'curr_stage' => 'select `file_processes`.`project_process_plan_id` from `file_processes` WHERE  `file_processes`.`project_file_id` = ProjectFile.id AND `file_processes`.`current_status` = 0 AND `file_processes`.`milestone_id` LIKE  ProjectFile.milestone_id ORDER BY `file_processes`.`created` DESC , `file_processes`.`sr_no` DESC LIMIT 1',
            // 'queued' => 'select `count(*) from `file_processes` WHERE  `file_processes`.`project_file_id` = ProjectFile.id AND  `file_processes`.`queued` = 1 LIMIT 1 ',
            'pro_pub' => 'select publish from projects where projects.id = ProjectFile.project_id',
            'pro_sd' => 'select soft_delete from projects where projects.id = ProjectFile.project_id',
            'mile_pub' => 'select publish from milestones where milestones.id = ProjectFile.milestone_id',
            'mile_sd' => 'select soft_delete from milestones where milestones.id = ProjectFile.milestone_id',
            'estimated_time_1'=>'ROUND(ProjectProcessPlan.estimated_units / ProjectProcessPlan.overall_metrics / ProjectProcessPlan.days / ProjectProcessPlan.estimated_resource)',
            'cat_on_hold'=>'select `file_categories`.`status` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
            'curr_employee'=> 'select employee_id from file_processes where file_processes.project_file_id LIKE ProjectFile.id AND file_processes.milestone_id LIKE ProjectFile.milestone_id ORDER BY created DESC , sr_no DESC LIMIT 1',
            'proheck1' => 'select count(*) from file_processes where file_processes.project_file_id LIKE ProjectFile.id and file_processes.employee_id LIKE "' .$this->Session->read('User.employee_id'). '" and (file_processes.current_status = 0 or file_processes.current_status = 7 or file_processes.current_status = 10 ) order by file_processes.sr_no DESC  LIMIT 1'
        );
        

        // get current use project
        $this->loadModel('ProjectEmployee');
        $thisProject = $this->ProjectEmployee->find('first',array(
            'recursive'=>-1,
            'fields'=>array('ProjectEmployee.id','ProjectEmployee.project_id'),
            'conditions'=>array(
                'ProjectEmployee.employee_id'=> $this->Session->read('User.employee_id')
                )
            ));

        $thisProjectId = $thisProject['ProjectEmployee']['project_id'];
        
        if(isset($this->request->params['named']['project_file_id'])){
            $fileOnHold = $this->ProjectFile->find('first',array(
                    'recursive'=>1,
                    'conditions'=>array(
                    'ProjectFile.proheck1 >'=>0,
                        'ProjectFile.id'=>$this->request->params['named']['project_file_id']
                    ),
                    'order'=>array('ProjectProcessPlan.qc'=>'ASC')
                )); 
        }
         

        if($fileOnHold){
            $this->set('fileOnHold',$fileOnHold);
        }


        /* 
        Loop 1
        if file is already Assigned | Hold | Re-assigned 
        */


        $this->ProjectFile->hasMany['FileProcess']['conditions'] = array(
            // 'FileProcess.hold_start_time !=' => null,
            // 'FileProcess.hold_end_time' =>null,
            // 'FileProcess.current_status' =>array(0,7,10),
            // 'FileProcess.employee_id' =>$this->Session->read('User.employee_id'),
        );

        $this->ProjectFile->hasMany['FileProcess']['order'] = array(
            'FileProcess.sr_no'=>'DESC'
        );

        $projectFile = $this->ProjectFile->find('first',array(
            'recursive'=>1,
            // 'fields'=>array(
            //     'ProjectFile.id',
            //     'ProjectFile.name',
            //     'ProjectFile.employee_id',
            //     'ProjectFile.current_status',
            // ),
            'conditions'=>array(
                'ProjectFile.project_id'=>$thisProjectId,
                'ProjectFile.pro_pub' =>1,
                'ProjectFile.pro_sd' =>0,
                'ProjectFile.mile_pub' =>1,
                'ProjectFile.mile_sd' =>0,
                'ProjectFile.queued'=>0,
                // 'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
                // 'ProjectFile.current_status'=>array(0,2,4,7,8,10,12),
                'ProjectFile.current_status'=>array(8), // Assigned | Hold | Re-assigned
                'ProjectFile.current_status != '=>array(11,5), 
                'ProjectFile.cat_on_hold'=>0,
                // 'ProjectFile.curr_employee'=>$this->Session->read('User.employee_id'),
                'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
                // 'FileProcess.employee_id' => $this->Session->read('User.employee_id')
                'ProjectFile.proheck1 >'=>0,
            ),
            'order'=>array(
                'ProjectFile.modified'=>'DESC',
                'ProjectProcessPlan.qc'=>'ASC',
            )
        ));

        
        if(!$projectFile){
            $projectFile = $this->ProjectFile->find('first',array(
            'recursive'=>1,
            // 'fields'=>array(
            //     'ProjectFile.id',
            //     'ProjectFile.name',
            //     'ProjectFile.employee_id',
            //     'ProjectFile.current_status',
            // ),
            'conditions'=>array(
                'ProjectFile.project_id'=>$thisProjectId,
                'ProjectFile.pro_pub' =>1,
                'ProjectFile.pro_sd' =>0,
                'ProjectFile.mile_pub' =>1,
                'ProjectFile.mile_sd' =>0,
                'ProjectFile.queued'=>0,
                // 'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
                // 'ProjectFile.current_status'=>array(0,2,4,7,8,10,12),
                'ProjectFile.current_status'=>array(0,7,10), // Assigned | Hold | Re-assigned
                'ProjectFile.current_status != '=>array(11,5), 
                'ProjectFile.cat_on_hold'=>0,
                // 'ProjectFile.curr_employee'=>$this->Session->read('User.employee_id'),
                'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
                // 'FileProcess.employee_id' => $this->Session->read('User.employee_id')
                'ProjectFile.proheck1 >'=>0,
            ),
            'order'=>array(
                'ProjectFile.modified'=>'DESC',
                'ProjectProcessPlan.qc'=>'ASC',
            )
        ));

        }
        

        if(!$projectFile){
            
/*
Loop 2 : check
// 2=>'Delayed',
// 4=>'Not Assigned',
// 7=>'Hold',
// 8=>'Reject',
// 10=>'Re-assigned',
// 12=>'For Marging'
*/
        $projectFile = $this->ProjectFile->find('first',array(
            'recursive'=>1,
            'conditions'=>array(
                'ProjectFile.project_id'=>$thisProjectId,
                'ProjectFile.pro_pub' =>1,
                'ProjectFile.pro_sd' =>0,
                'ProjectFile.mile_pub' =>1,
                'ProjectFile.mile_sd' =>0,
                'ProjectFile.current_status'=>array(0,2,4,7,8,10,12),
                // 'ProjectFile.current_status != '=>array(0,11,5), 
                'ProjectFile.cat_on_hold'=>0,
                'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
            ),
            'order'=>array('ProjectProcessPlan.qc'=>'ASC')
        ));

    }

        
        if(!$projectFile){
            $projectFile = $this->ProjectFile->find('first',array(
                'recursive'=>1,
                // 'fields'=>array(
                //     'ProjectFile.id',
                //     'ProjectFile.name',
                //     'ProjectFile.employee_id',
                //     'ProjectFile.current_status',
                // ),
                'conditions'=>array(
                    'ProjectFile.project_id'=>$thisProjectId,
                    'ProjectFile.pro_pub' =>1,
                    'ProjectFile.pro_sd' =>0,
                    'ProjectFile.mile_pub' =>1,
                    'ProjectFile.mile_sd' =>0,
                    'ProjectFile.queued'=>1,
                    // 'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
                    // 'ProjectFile.current_status'=>array(0,2,4,7,8,10,12),
                    // 'ProjectFile.current_status'=>array(0),       
                    'ProjectFile.current_status != '=>array(1,11,5), 
                    'ProjectFile.cat_on_hold'=>0,
                    // 'ProjectFile.curr_employee'=>$this->Session->read('User.employee_id'),
                    'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
                    // 'FileProcess.employee_id' => $this->Session->read('User.employee_id')
                ),
                'order'=>array('ProjectProcessPlan.qc'=>'ASC')
            ));      
        }


        if(!$projectFile){

            // check user skill set from project resources
            $skills = $this->Project->ProjectResource->find('list',array(
                'fields'=>array(
                    'ProjectResource.process_id',
                    'ProjectResource.id',
                    // 'ProjectResource.employee_id',
                    // 'ProjectResource.project_id',
                    
                ),
                'recursive'=>-1,
                'conditions'=>array(
                    'ProjectResource.project_id'=>$thisProjectId,
                    'ProjectResource.employee_id'=>$this->Session->read('User.employee_id')
                ),
                'order'=>array('ProjectResource.priority'=>'DESC')
            ));
            
            $projectFile = $this->ProjectFile->find('first',array(
                'recursive'=>1,
                // 'fields'=>array(
                //     'ProjectFile.id',
                //     'ProjectFile.name',
                //     'ProjectFile.employee_id',
                //     'ProjectFile.current_status',
                // ),
                'conditions'=>array(
                    'ProjectFile.project_id'=>$thisProjectId,
                    'ProjectFile.pro_pub' =>1,
                    'ProjectFile.pro_sd' =>0,
                    'ProjectFile.mile_pub' =>1,
                    'ProjectFile.mile_sd' =>0,
                    'ProjectFile.current_status'=> 4, 
                    'ProjectFile.cat_on_hold'=>0,
                    'ProjectFile.project_process_plan_id'=> array_keys($skills)
                ),
                'order'=>array('ProjectProcessPlan.qc'=>'ASC')
            ));  


            if($projectFile){
                // assign this file to this employee
                $projectFile['ProjectFile']['employee_id'] = $this->Session->read('User.employee_id');
                $projectFile['ProjectFile']['current_status'] = 0;
                $projectFile['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
                
                $this->ProjectFile->create();
                if($this->ProjectFile->save($projectFile,false)){
                    $this->loadModel('FileProcess');
                    $fp['project_id'] = $projectFile['ProjectFile']['project_id'];
                    $fp['milestone_id'] = $projectFile['ProjectFile']['milestone_id'];
                    $fp['employee_id'] = $this->Session->read('User.employee_id');
                    $fp['assigned_date'] = date('Y-m-d H:i:s');
                    $fp['estimated_time'] = $projectFile['ProjectFile']['estimated_time'];
                    $fp['current_status'] = 0;
                    $fp['project_process_plan_id'] = $projectFile['ProjectFile']['project_process_plan_id'];
                    $fp['project_file_id'] = $projectFile['ProjectFile']['id'];
                    $fp['publish'] = 1;
                    $fp['soft_delete'] = 0;  
                    $this->FileProcess->create();
                    $this->FileProcess->save($fp,false);


                    $this->_track_file(
                     $project_file_id = $projectFile['ProjectFile']['project_id'], 
                     $project_id = $projectFile['ProjectFile']['project_id'], 
                     $milestone_id = $projectFile['ProjectFile']['milestone_id'], 
                     $from = '??', 
                     $to = $employee_id, 
                     $by = $this->Session->read('User.employee_id'), 
                     $current_status = 0, 
                     $change_type = 0, 
                     $function = 'add_ajax', 
                     $comments = 'File changed via add_ajax function'
                    );
                }
            }
        }
            // stop incorrect files :
            
            if($projectFile){
                $res_check = $this->requestAction(array('controller'=>'project_resources','action'=>'resource_check',$thisProjectId,$projectFile['ProjectFile']['milestone_id']));
                
                $errocheck = $this->Project->FileErrorMaster->find('count',array(
                    'conditions'=>array(
                        'FileErrorMaster.project_id'=>$thisProjectId,
                        'FileErrorMaster.milestone_id'=>$projectFile['ProjectFile']['milestone_id'],
                        'FileErrorMaster.milestone_id'=>$projectFile['ProjectFile']['milestone_id'],
                        'FileErrorMaster.project_process_plan_id'=>$projectFile['ProjectFile']['project_process_plan_id'],
                    )
                ));

                $checklistcheck = $this->Project->ProjectChecklist->find('count',array(
                    'conditions'=>array(
                        'ProjectChecklist.project_id'=>$thisProjectId,
                        'ProjectChecklist.milestone_id'=>$projectFile['ProjectFile']['milestone_id'],
                    )
                ));

                if($res_check == true){
                    // echo "Please add over all plan / detailed plans/ resources etc before adding files.";   
                    $this->set('dontshow','Please add over all plan / detailed plans/ resources etc before adding files.');
                }else if($errocheck == 0 ){
                    // echo "Please add errors before adding files.";  
                    $this->set('dontshow','Please add errors before adding files.');    
                }else if($checklistcheck == 0 ){
                    // echo "Please add checklist before adding files.";   
                    $this->set('dontshow','Please add checklist before adding files.');    
                }    
                
               
            }else if(!$projectFile && $fileOnHold){
                $projectFile = $fileOnHold;
            }
        
        if($projectFile){
            // Configure::write('debug',1);
            // debug($projectFile);
            // $this->ProjectFile->create();
            // $projectFile['ProjectFile']['employee_id'] = $this->Session->read('User.employee_id');
            // $this->ProjectFile->save($projectFile,false);
        }
        
        // Configure::write('debug',1);
        // debug($projectFile);
        // exit;
        // get project process 
        
        $this->loadModel('ProjectProcessPlan');
        $this->ProjectProcessPlan->virtualFields = array(
            'op'=>'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id',
            'mile_pub' => 'select publish from milestones where milestones.id = ProjectProcessPlan.milestone_id',
            'mile_sd' => 'select soft_delete from milestones where milestones.id = ProjectProcessPlan.milestone_id',

        );
        $projectProcesses = $this->ProjectProcessPlan->find(
            'list',array('conditions'=>
                array(
                    'ProjectProcessPlan.op >'=>0,
                    'ProjectProcessPlan.mile_pub' =>1,
                    'ProjectProcessPlan.mile_sd' =>0,
                    'ProjectProcessPlan.project_id'=> $projectFile['ProjectFile']['project_id'],
                    'ProjectProcessPlan.milestone_id'=> $projectFile['ProjectFile']['milestone_id'],
                ),
                'fields'=>array(
                    'ProjectProcessPlan.id',
                    'ProjectProcessPlan.process'
                ),
                'order'=>array(
                    'ProjectProcessPlan.sequence'=>'ASC'
                )
            )
        );

        
        $this->ProjectFile->FileProcess->virtualFields = array(
            'mile_pub' => 'select publish from milestones where milestones.id = FileProcess.milestone_id',
            'mile_sd' => 'select soft_delete from milestones where milestones.id = FileProcess.milestone_id',

        );

        if($this->request->params['named']['project_id']){
            $con = array('ProjectFile.employee_id'=>$this->Session->read('User.employee_id'));
        }else{
            $con = array('ProjectFile.employee_id'=>$this->Session->read('User.employee_id'));
        }

        $currentProcesses = $this->ProjectFile->FileProcess->find(
            'first',array(
                'conditions'=>array(
                    // 'or'=>array(
                    //     'FileProcess.project_process_plan_id' => array_keys($projectProcesses),
                    //     'ProjectFile.project_process_plan_id' => array_keys($projectProcesses)

                    // ),
                    'FileProcess.mile_pub' =>1,
                    'FileProcess.mile_sd' =>0,
                    'FileProcess.project_process_plan_id != ' => '',
                    'FileProcess.project_id'=>$projectFile['ProjectFile']['project_id'],
                    'FileProcess.milestone_id'=> $projectFile['ProjectFile']['milestone_id'],
                    'FileProcess.project_file_id'=>$projectFile['ProjectFile']['id'],
                    
                    'FileProcess.current_status'=>array(0,2,4,7,8,10,12),   
                    // 'FileProcess.current_status'=>array(0,7,10),

                ),
                'fields'=>array(
                    // 'FileProcess.id',
                    // 'FileProcess.project_process_plan_id',
                ),
                'order'=>array(
                    'FileProcess.sr_no'=>'DESC'
                )
            )
        );
        
        if(!$currentProcesses){
            // create process

        }
        if($currentProcesses && ($currentProcesses['FileProcess']['milestone_id'] == $projectFile['ProjectFile']['milestone_id'])){
            // $currentProcesses['FileProcess']['employee_id'] = $this->Session->read('User.employee_id');
            // $this->ProjectFile->FileProcess->create();
            // $this->ProjectFile->FileProcess->save($currentProcesses,false);
            // update file  (fix)
            $projectFile['ProjectFile']['project_process_plan_id'] = $currentProcesses['FileProcess']['project_process_plan_id'];
            $projectFile['ProjectFile']['queued'] = 0;
            $this->ProjectFile->create();
            $this->ProjectFile->save($projectFile,false);

            $projectFile = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$projectFile['ProjectFile']['id']),'recursive'=>1));

            if($currentProcesses['ProjectProcessPlan']['qc'] == 2){
                // get related files
                $this->ProjectFile->virtualFields = array(
                    'ucompleted' => 'select SUM(units_completed) from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id '
                );

                $toMerge = $this->ProjectFile->find('all',array(
                    'recursive'=>0, 
                    'conditions'=>array(
                        'ProjectFile.file_category_id'=>$currentProcesses['ProjectFile']['file_category_id'],
                        'ProjectFile.current_status !='=>11,
                        'ProjectFile.parent_id'=> NULL,
                        'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
                    )));                
            }
            
            $this->set('toMerge',$toMerge);

            $this->loadModel('FileError');
            $fileErrors = $this->FileError->find('all',array(
                'conditions'=>array(
                    'FileError.project_file_id'=>$projectFile['ProjectFile']['id'],
                    // 'FileError.file_process_id'=>$currentProcesses['FileProcess']['id']
                )
            ));

           
           // $preProcess = $this->requestAction(array('controller'=>'project_files','action'=>'get_pre_process',$projectFile['ProjectFile']['project_id'],$projectFile['ProjectFile']['milestone_id'],$projectFile['ProjectFile']['id']));
            $preProcess = $this->requestAction(array('controller'=>'project_files','action'=>'get_pre_process',
                $projectFile['ProjectFile']['project_id'],
                $projectFile['ProjectFile']['milestone_id'],
                $projectFile['ProjectFile']['id'],
                $currentProcesses['FileProcess']['id']
            ));
            
            $this->set('preProcess',$preProcess[0]);
            $this->set('preProcessPlans',$preProcess[1]);
            $this->set('preEmployee_id',$preProcess[2]);
           
            $this->loadModel('FileErrorMaster');
            $errorMasters = $this->FileErrorMaster->find(
                'list',array(
                    'conditions'=>array(
                        'FileErrorMaster.project_id'=>$projectFile['ProjectFile']['project_id'],
                        'FileErrorMaster.milestone_id'=> $projectFile['ProjectFile']['milestone_id'],
                        // 'FileErrorMaster.project_file_id'=>$projectFile['ProjectFile']['project_id'],
                    ),
                    'order'=>array(
                        'FileErrorMaster.name'=>'ASC'
                    )
                )
            );

            // queue other files
            // $this->requestAction(array('controller'=>'project_files','action'=>'queue_other_files',$this->Session->read('User.employee_id'),$projectFile['ProjectFile']['id']));

            $this->set('projectFile',$projectFile);
            $currentStatuses = $this->ProjectFile->customArray['currentStatuses'];
            $this->set('currentStatuses',$currentStatuses);

            $displayOptions = $this->ProjectFile->customArray['displayOptions'];
            $this->set('displayOptions',$displayOptions);

            $this->set('projectProcesses',$projectProcesses);
            $this->set('currentProcesses',$currentProcesses);
            $this->set('errorMasters',$errorMasters);
            $this->set('fileErrors',$fileErrors);


            $this->loadModel('HoldType');
            $holdTypes = $this->HoldType->find('list');
            $this->set('holdTypes',$holdTypes);


            $projectChecklists = $this->Project->ProjectChecklist->find('list',array(
            'conditions'=>array(
                    'ProjectChecklist.project_id'=>$currentProcesses['FileProcess']['project_id'],
                    'ProjectChecklist.milestone_id'=>$currentProcesses['FileProcess']['milestone_id'],
                    'ProjectChecklist.soft_delete'=>0,
                    'ProjectChecklist.project_process_plan_id' => $currentProcesses['FileProcess']['project_process_plan_id']
                )
            ));
            
            $this->set('projectChecklists',$projectChecklists);
            
        }else{

        }
        

        
        // $this->set('projects',$this->ProjectFile->Project->find('list'));
        $this->set('milestones',$this->ProjectFile->Project->Milestone->find('list'));

        $this->set('PublishedEmployeeList',$this->_get_employee_list());

        $this->loadModel('ProjectReleaseRequest');
        
        $this->ProjectReleaseRequest->virtualFields = array(
            'tl'=>'SELECT team_leader_id FROM project_resources WHERE  project_resources.employee_id = ProjectReleaseRequest.employee_id and project_resources.project_id = ProjectReleaseRequest.current_project_id GROUP BY project_resources.employee_id',
            'pl'=>'SELECT project_leader_id FROM project_resources WHERE  project_resources.employee_id = ProjectReleaseRequest.employee_id and project_resources.project_id = ProjectReleaseRequest.current_project_id GROUP BY project_resources.employee_id'
        );

        // $this->Project->ProjectProcessPlan->virtualFields = array(
        //     'estimated_units'=>0
        // );
        $allprojects = $this->Project->find('all',array(
            'recursive'=>-1,
            'order'=>array('Project.start_date' => 'ASC'),
            'fields'=>array(
                'Project.id',
                'Project.employee_id',
                'Project.team_leader_id',
                'Project.project_leader_id',
            ),
            'conditions'=>array(
                // 'or'=>array(
                //      'Project.userexists > '=> 0,
                //      'Project.employee_id'=> $this->Session->read('User.employee_id'),
                // ),
                'Project.publish'=>1,
                'Project.soft_delete'=>0,
                'Project.current_status !='=>array(3,4),
            )
        ));

        // Configure::Write('debug',1);


        foreach ($allprojects as $allproject) {
            $key = $allproject['Project']['id'];
            $releaseRequests = $this->ProjectReleaseRequest->find('all',array(
                'recursive'=>0,
                'fields'=>array(
                    'ProjectReleaseRequest.id',
                    'ProjectReleaseRequest.sr_no',
                    'CurrentProject.id',
                    'CurrentProject.title',
                    'NewProject.id',
                    'NewProject.title',
                    'ProjectReleaseRequest.id',
                    'ProjectReleaseRequest.employee_id',
                    'Employee.id',
                    'Employee.name',
                    'ProjectReleaseRequest.tl',
                    'ProjectReleaseRequest.pl',
                    'RequestFrom.id',
                    'RequestFrom.name',
                    'CurrentProject.employee_id',
                    'CurrentProject.team_leader_id',
                    'CurrentProject.project_leader_id',
                    'ProjectReleaseRequest.employee_id',
                ),
                'conditions'=>array(
                    'ProjectReleaseRequest.request_status'=>0,
                    'ProjectReleaseRequest.current_project_id != ProjectReleaseRequest.new_project_id',
                    'ProjectReleaseRequest.current_project_id'=>$key,
                    // 'OR'=>array(    
                    //     // 'CurrentProject.employee_id LIKE'=>$this->Session->read('User.employee_id'),
                    //     // 'CurrentProject.team_leader_id LIKE'=>$this->Session->read('User.employee_id'),
                    //     // 'CurrentProject.project_leader_id LIKE'=>$this->Session->read('User.employee_id'),                
                    //     // 'ProjectReleaseRequest.tl'=>$this->Session->read('User.employee_id'),
                    //     // 'ProjectReleaseRequest.pl'=>$this->Session->read('User.employee_id')
                    // )
                    
                )
            ));
            foreach($releaseRequests as $releaseRequest){
                // debug($releaseRequest);
                if(
                    in_array($this->Session->read('User.employee_id'), json_decode($releaseRequest['CurrentProject']['employee_id'],true)) || 
                    in_array($this->Session->read('User.employee_id'), json_decode($releaseRequest['CurrentProject']['team_leader_id'],true)) || 
                    in_array($this->Session->read('User.employee_id'), json_decode($releaseRequest['CurrentProject']['project_leader_id'],true)) ||
                    in_array($this->Session->read('User.employee_id'), json_decode($releaseRequest['ProjectReleaseRequest']['pl'],true)) || 
                    in_array($this->Session->read('User.employee_id'), json_decode($releaseRequest['ProjectReleaseRequest']['tl'],true)) 
                  
                ){
                    $requests[$key][] = $releaseRequest;  
                }    
            }
            
            
        }    

        // debug($this->Session->read('User.employee_id'));
        // debug($projectFile);
        // exit;

        $this->set('releaseRequests',$requests);
        // load check list
        

        if ($this->request->is('post')) {
            // $this->request->data['ProjectTimesheet']['system_table_id'] = $this->_get_system_table_id();
            $this->ProjectTimesheet->create();

            foreach ($this->request->data['ProjectTimesheet'] as $pt) {
                if($pt['total']>0){
                    $this->ProjectTimesheet->create();
                    $this->ProjectTimesheet->save($pt,false);
                    if($pt['current_status'] == 1)  {
                        // update project activity record
                        $this->ProjectTimesheet->ProjectActivity->read(null,$pt['project_activity_id']);
                        $data['ProjectActivity']['current_status'] = 1;
                        debug($this->ProjectTimesheet->ProjectActivity->save($data['ProjectActivity'],false));
                        // debug($data);
                    }

                }
                
            }

        }

        //Approvals
        $this->loadModel('Approval');
        $this->Approval->recursive = 0;
        
        
        $approvals = $this->Approval->find('all', array(
            'order' => array(
                'Approval.sr_no' => 'desc'
            ),
            'group' => 'Approval.record',
            'conditions' => array(
                'Approval.status != ' => 'Approved',
                'Approval.user_id' => $this->Session->read('User.id'),
                'Approval.soft_delete' => 0,
                'Approval.record_status' => 1
            ),
            'fields' => array(
                'From.name',
                'Approval.id',
                'Approval.model_name',
                'Approval.controller_name',
                'Approval.record',
                'Approval.comments',
                'Approval.created'
            )
        ));
        
        $approvalsCount = count($approvals);
        
        $this->set(compact('approvalsCount', 'approvals'));

        $this->loadModel('QueryType');
        $queryTypes = $this->QueryType->find('list',array('conditions'=>array('QueryType.publish'=>1,'QueryType.soft_delete'=>0)));
        $this->set('queryTypes',$queryTypes);

        $this->loadModel('ProjectQuery');
        $projectQueries = $this->ProjectQuery->find('all',array(
            'conditions'=>array(
                // 'ProjectQuery.current_status'=>0,
                'ProjectQuery.project_id'=>$projectFile['ProjectFile']['project_id'],
                'OR'=>array(                                        
                    'ProjectQuery.employee_id'=>$this->Session->read('User.employee_id'),
                    'ProjectQuery.sent_to'=>$this->Session->read('User.employee_id'),
                )
                
            )
        ));

        $this->set('projectQueries', $projectQueries);

        
        $userProjectQueries = $this->ProjectQuery->find('all',array(
            'conditions'=>array(
                'ProjectQuery.current_status'=>0,
                // 'ProjectQuery.project_id'=>$projectFile['ProjectFile']['project_id'],
                // 'OR'=>array(                                        
                    // 'ProjectQuery.employee_id'=>$this->Session->read('User.employee_id'),
                    'ProjectQuery.sent_to'=>$this->Session->read('User.employee_id'),
                // )
                
            )
        ));
        $this->set('userProjectQueries', $userProjectQueries);
        

        // $this->set('projectQueries', $projectQueries);

        $projectQueryStatuses = $this->ProjectQuery->customArray['currentStatuses'];
        $this->set('projectQueryStatuses', $projectQueryStatuses);


        // check for query received
        $responses = $this->ProjectQuery->ProjectQueryResponse->find('all',array('conditions'=>array(
            'ProjectQueryResponse.employee_id'=>$this->Session->read('User.employee_id'),
            'ProjectQueryResponse.response'=>''
        )));

        $this->set($responses);


        if ($this->Session->read('User.is_mr') == true) {
            
            
            //Blocked User
            
            $blockedUser      = $this->User->find('all', array(
                'conditions' => array(
                    'User.status' => 3,
                    'User.publish' => 1,
                    'User.soft_delete' => 0
                ),
                'recursive' => -1
            ));
            $blockedUserCount = count($blockedUser);
            $this->set(compact('blockedUserCount', 'blockedUser'));
        }
        
        Configure::Write('debug',1);
        // debug($projectFile);
        if($projectFile){
            $otherAssigneFiles = $this->ProjectFile->find('all',array(
                'fields'=>array(
                    'ProjectFile.id',
                    'ProjectFile.employee_id',
                    'ProjectFile.current_status',
                    'ProjectFile.name'
                ),
                'conditions'=>array(
                    'ProjectFile.id != '=> $projectFile['ProjectFile']['id'],
                    // 'FileProcess.current_status'=> array(0,10),
                    'ProjectFile.current_status'=> 0,
                    'ProjectFile.employee_id'=>$this->Session->read('User.employee_id')
                ),
                'recursive'=>-1
            ));    
            
            foreach($otherAssigneFiles as $otherAssigneFile){
                $this->ProjectFile->read(null,$otherAssigneFile['ProjectFile']['id']);
                $this->ProjectFile->set(array('current_status'=>4,'employee_id'=>'Not Assigned'));
                $this->ProjectFile->save();
            }
        }
    }

    public function add_blulk_users(){
        // Configure::write('debug',1);
        $this->User->Employee->virtualFields = array(
            'is_user' => 'select count(*) from `users` where `users`.`employee_id` = Employee.id'
        );
        $employees = $this->User->Employee->find('all',array(
            'conditions'=>array('is_user'=>0),
            'recursive'=>-1,
        ));
        

        foreach ($employees as $employee) {
            // debug($employee);
            $user['User']['employee_id'] = $employee['Employee']['id'];
            $user['User']['username'] = $employee['Employee']['office_email'];
            $user['User']['password']     = Security::hash(str_replace('@email.igenesys.com','',$employee['Employee']['office_email']), 'md5', true);
            $user['User']['name'] = $employee['Employee']['name'];
            $user['User']['branch_id'] = $employee['Employee']['branch_id'];
            $user['User']['department_id'] = $employee['Employee']['department_id'];
            $user['User']['designation_id'] = $employee['Employee']['designation_id'];
            $user['User']['language_id'] = '366ac1f4-199b-11e3-9f46-c709d410d2ec';
            $user['User']['login_status'] = 0;
            $user['User']['allow_multiple_login'] = 1;
            $user['User']['limit_login_attempt'] = 1;
            $user['User']['agree'] = 0;
            $user['User']['company_id'] = '56044715-be6c-4298-8678-03e1db1e6cf9';
            $user['User']['is_mr'] = 1;
            $user['User']['publish'] = 1;
            $user['User']['status'] = 1;
            $user['User']['soft_delete'] = 0;
            $user['User']['user_access'] = '';
            $user['User']['is_view_all'] = 1;
            $user['User']['is_approvar'] = 1;
            $user['User']['prepared_by'] = $user['User']['approved_by'] = '56044715-6bb8-49bd-85f2-03e1db1e6cf9';
            $user['User']['system_table_id'] = '5297b2e7-0a9c-46e3-96a6-2d8f0a000005';
            $user['User']['master_list_of_format_id'] = '523ae34c-bcc0-4c7d-b7aa-75cec6c3268c';

            $uchk = $this->User->find('count',array('conditions'=>array('User.username'=>$user['User']['username'])));

            if($uchk == 0){
                $this->User->create();
                $this->User->save($user,false);    
            }
            
        }

        exit;
    }    
}

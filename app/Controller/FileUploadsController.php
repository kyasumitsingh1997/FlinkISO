<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'Spreadsheet_Excel_Reader', array(
    'file' => 'Excel/reader.php'
));
App::import('Vendor', 'PHPExcel', array(
    'file' => 'Excel/PHPExcel.php'
));
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * FileUploads Controller
 *
 * @property FileUpload $FileUpload
 */
class FileUploadsController extends AppController {

    public $components = array(
        'RequestHandler',
        'Session',
        'AjaxMultiUpload.Upload',
    );
    public $helpers = array(
        'Js',
        'Session',
        'Paginator',
        'Tinymce'
    );
    // It will show file on the basis of Fileupload Id
    public function view_media_file(){            
            $file_path = base64_decode($this->request->params['named']['full_base']); 
            $file_data = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id' =>$file_path)));

           

            // allow master list of format documents to be shared with everyone who has access to master list of format
            if($file_data['SystemTable']['name'] == 'Master List Of Formats'){
                // open format details
                $this->loadModel('MasterListOfFormat');
                $format = $this->MasterListOfFormat->find('first',array(
                    'recursive'=>-1,
                    'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.user_id'),
                    'conditions'=>array('MasterListOfFormat.id'=>$file_data['FileUpload']['record'])));
                $users = json_decode($format['MasterListOfFormat']['user_id']);
                debug($users);
                if(in_array($this->Session->Read('User.id'), $users)){
                    $full_path = Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS .  $file_data['FileUpload']['file_dir']; 
                    $this->autoRender = false;
                    $this->response->file($full_path,array('download' => true, 'name' => $file_data['FileUpload']['file_details']. '.' .$file_data['FileUpload']['file_type']));
                    $this->response->send();
                    $this->_update_view($file_data['FileUpload']['id'],$_SESSION['User']['id'],'Download');    
                }else{
                    $this->redirect(array('controller'=>'file_uploads', 'action'=>'request_access',$file_path));                
                }
            }else{
                //check for permissions
                $permissions = $this->FileUpload->FileShare->find('first',array(
                'fields'=>array('FileShare.id','FileShare.users','FileShare.everyone','FileShare.file_upload_id'),
                'recursive'=>-1,
                'conditions'=>
                array('FileShare.file_upload_id' => $file_path,                    
                        'FileShare.branch_id' => $this->Session->read('User.branch_id')
                        )));


                if($permissions['FileShare']['everyone'] != 1 ){
                    if(in_array($this->Session->read('User.id'), json_decode($permissions['FileShare']['users'])) == false)                
                    $this->redirect(array('controller'=>'file_uploads', 'action'=>'request_access',$file_path));                
                }
                $full_path = Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS .  $file_data['FileUpload']['file_dir']; 
                $this->autoRender = false;
                $this->response->file($full_path,array('download' => true, 'name' => $file_data['FileUpload']['file_details']. '.' .$file_data['FileUpload']['file_type']));
                $this->response->send();
                $this->_update_view($file_data['FileUpload']['id'],$_SESSION['User']['id'],'Download');    
            }
            
            
          
            
    }

    public function request_access($id = null){
        $file = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id'=>$id)));
        $file_name = $file['FileUpload']['file_details'] . '.' . $file['FileUpload']['file_type'];
        $user = $this->FileUpload->User->find('first',array('conditions'=>array('User.id'=>$file['FileUpload']['user_id']),'recursive'=>-1));
        $employee = $this->FileUpload->User->Employee->find('first',array('conditions'=>array('Employee.id'=>$user['User']['employee_id']),'recursive'=>-1));
        
        if($employee['Employee']['office_email']){
            $email = $employee['Employee']['office_email'];
        }else{
            $email = $employee['Employee']['personal_email'];
        }

        if($email){
            try{
                App::uses('CakeEmail', 'Network/Email');
                if($this->Session->read('User.is_smtp') == '1')
                {
                    $EmailConfig = new CakeEmail("smtp");   
                }else if($this->Session->read('User.is_smtp') == '0'){
                    $EmailConfig = new CakeEmail("default");
                }
                $EmailConfig->to($email);
                $EmailConfig->subject($employee['Employee']['name'] . ' is requesting file access');
                $EmailConfig->template('file_access');
                $EmailConfig->viewVars(array('employee' => $employee['Employee']['name'],'file_name'=>$file_name));
                // $EmailConfig->attachments(array($path . DS . $fileName));
                $EmailConfig->emailFormat('html');
                $EmailConfig->send();
                 $this->Session->setFlash(__('Email sent succeefully.', true), 'smtp');
            } catch(Exception $e) {
                 $this->Session->setFlash(__('Failed to send email. Please check smtp details.', true), 'smtp');

            }    
        }
    }

    public function view_document_file(){
        
            $file_path = base64_decode($this->request->params['named']['full_base']);             
            $full_path = Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS .  $file_path; 
            $this->autoRender = false;
            $this->response->file($full_path,array('download'=>true,'name'=>$this->request->params['named']['file_name']));
            $this->response->send();
          
            
    }
    // It will show file on the basis of path given
    public function view_saved_file(){
        $path =  base64_decode($this->request->params['named']['path']);

        if($this->request->params['named']['fullpath']){
            $full_path = Configure::read('MediaPath').  $path;
        }else{
           $full_path = Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS . $path;
        }
        $this->autoRender = false;
        $this->response->file($full_path,array('download'=>true,'name'=>$this->request->params['named']['file_name']));
        $this->response->send();
    }

    public function _get_system_table_id() {
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = - 1;
        $systemTableId = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => $this->request->params['controller']
            )
        ));
        return $systemTableId['SystemTable']['id'];
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        
        $conditions = array();
        
        if(isset($this->request->params['named']['users'])){
            $conditions = array('FileUpload.user_id'=>$this->request->params['named']['users']);
        }

        if(isset($this->request->params['named']['approvals'])){

            $conditions = array('FileUpload.approved_by'=>$this->request->params['named']['approvals']);
        }

        if(isset($this->request->params['named']['table'])){
            
            $conditions = array('FileUpload.system_table_id'=>$this->request->params['named']['table']);
        }

        if(isset($this->request->params['named']['archived'])){
            
            $conditions = array('FileUpload.archived'=>1);
        }

        if(isset($this->request->params['named']['deleted'])){
            
            $conditions = array('FileUpload.file_status'=>0);
        }

        if(isset($this->request->params['named']['unpublished'])){
            
            $conditions = array('FileUpload.publish'=>0);
        }

        if(isset($this->request->params['named']['department'])){
            $conditions = array('FileUpload.departmentid'=>$this->request->params['named']['department']);
        }
        
        $this->paginate = array('conditions' => $conditions, 'order'=>array('FileUpload.created'=>'DESC'));
        
        $this->FileUpload->recursive = 0;
        $files = $this->paginate();
        $this->loadModel('Approval');
        $this->loadModel('Evidence');
        

        foreach ($files as $file) {
            $evidence = $this->Evidence->find('first',array(
                'conditions'=>array(
                    'Evidence.publish'=>1,
                    'Evidence.soft_delete'=>0,
                    'Evidence.record'=>$file['FileUpload']['record']
                ),
                'order'=>array('Evidence.sr_no'=>'DESC')
                ));
            $result = $this->Approval->find('first',array(
                'order'=>array('Approval.sr_no'=>'DESC'),
                'recursive'=>-1,
                'fields'=>array('Approval.id','Approval.modified'),
                'conditions'=>array('Approval.status'=>'Approved', 'Approval.controller_name'=>'evidences','Approval.record'=>$evidence['Evidence']['id'])));
            
            $file['FinalApproval'] = $result['Approval']['modified'];
            $new_paginate[] = $file;
        }
        
        $this->set('fileUploads', $new_paginate);
        
        $this->set('employees',$this->_get_employee_list());
        // $this->_get_count();
        $PublishedEmployeeList = $this->requestAction('App/get_model_list/Employee/');
        $PublishedBranchList = $this->requestAction('App/get_model_list/Branch/');
        $system_table = $this->requestAction('App/get_model_list/SystemTable/');
        $masterListOfFormat = $this->requestAction('App/get_model_list/MasterListOfFormat/');
        $this->set(compact('masterListOfFormat','PublishedEmployeeList','PublishedBranchList','system_table'));
    }

    public function by_users(){
        $this->loadModel('Branch');
        $branches = $this->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
        $this->loadModel('Department');
        $departments = $this->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
        $this->loadModel('User');
        foreach ($branches as $bkey => $branch) {
            foreach ($departments as $dkey => $department) {        
                $new_array['department_name'] = $department;
                $new_array['users'] = $this->User->find('list',array('conditions'=>array('User.branch_id'=>$bkey,'User.department_id'=>$dkey,'User.publish'=>1,'User.soft_delete'=>0)));
                if($new_array['users'])$users[$branch][] = $new_array;
            }
        }
        $this->set('users',$users);
        $PublishedEmployeeList = $this->requestAction('App/get_model_list/Employee/');
        $PublishedBranchList = $this->requestAction('App/get_model_list/Branch/');
        $system_table = $this->requestAction('App/get_model_list/SystemTable/');
        $masterListOfFormat = $this->requestAction('App/get_model_list/MasterListOfFormat/');
        $this->set(compact('masterListOfFormat','PublishedEmployeeList','PublishedBranchList','system_table'));
    }

    public function by_approvals(){
        $this->loadModel('Branch');
        $branches = $this->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
        $this->loadModel('Department');
        $departments = $this->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
        $this->loadModel('User');
        foreach ($branches as $bkey => $branch) {
            foreach ($departments as $dkey => $department) {        
                $new_array['department_name'] = $department;
                $new_array['users'] = $this->User->find('list',array('fields'=>array('User.employee_id','User.name'), 'conditions'=>array('User.branch_id'=>$bkey,'User.department_id'=>$dkey,'User.publish'=>1,'User.soft_delete'=>0)));
                if($new_array['users'])$users[$branch][] = $new_array;
            }
        }
        $this->set('users',$users);
        $PublishedEmployeeList = $this->requestAction('App/get_model_list/Employee/');
        $PublishedBranchList = $this->requestAction('App/get_model_list/Branch/');
        $system_table = $this->requestAction('App/get_model_list/SystemTable/');
        $masterListOfFormat = $this->requestAction('App/get_model_list/MasterListOfFormat/');
        $this->set(compact('masterListOfFormat','PublishedEmployeeList','PublishedBranchList','system_table'));
    }

    public function by_table(){
       
        $this->loadModel('SystemTable');
        $tables = $this->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
        $this->set('tables',$tables);
    }

    public function quality_documents($standard_id = null){
        if($this->request->params['named']['standard_id']){
            $standard = array('MasterListOfFormatCategory.standard_id'=>$this->request->params['named']['standard_id']);
            $this->set('standard_id',$this->request->params['named']['standard_id']);
        }else{
            $standard = array('MasterListOfFormatCategory.standard_id'=>'58511238-fba8-4db9-aad0-833fc20b8995');
            $standard_id = '58511238-fba8-4db9-aad0-833fc20b8995';
            $this->set('standard_id','58511238-fba8-4db9-aad0-833fc20b8995');            
        }
        
        $this->loadModel('MasterListOfFormatCategory');
        $masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list', array('conditions' => array($standard,'MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));

        $this->loadModel('Standard');
        $standards = $this->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));

        $this->set(compact('masterListOfFormatCategories','standards'));
        $this->set('standard_id',$standard_id);
        
        // $this->set('parentCategories',$this->requestAction(array('controller'=>'master_list_of_formats','action'=>'category_list','standard_id'=>$standard_id)));
        

    }
    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function file_advanced_search() {
        if($this->request->data['FileUploads']){
            $conditions = array();
            if ($this->request->data['FileUploads']['keywords']) {
                $searchArray = array();
                if ($this->request->data['FileUploads']['strict_search'] == 0) {
                    $searchKeys[] = $this->request->data['FileUploads']['keywords'];
                } else {
                    $searchKeys = explode(" ", $this->request->data['FileUploads']['keywords']);
                }
                foreach ($searchKeys as $searchKey):
                    foreach ($this->request->data['FileUploads']['search_fields'] as $search):
                        if ($this->request->data['FileUploads']['strict_search'] == 0)
                            $searchArray[] = array('FileUpload.' . $search => $searchKey);
                        else
                            if($search == 'version'){
                                $searchArray[] = array('FileUpload.version' => $searchKey );
                            }else{
                                $searchArray[] = array('FileUpload.' . $search . ' like ' => '%' . $searchKey . '%');    
                            }
                            
                    endforeach;
                endforeach;
                if ($this->request->data['FileUploads']['strict_search'] == 0)
                    $conditions[] = array('and' => array('or' => $searchArray));
                else
                    $conditions[] = array('or' => $searchArray);
            }

            if ($this->request->data['FileUploads']['branch_list']) {
                foreach ($this->request->data['FileUploads']['branch_list'] as $branches):
                    $branchConditions[] = array(
                        'FileUpload.branchid' => $branches
                    );
                endforeach;
                if ($this->request->data['FileUploads']['strict_search'] == 0)
                    $conditions[] = array('and' => array('or' => $branchConditions));
                else
                    $conditions[] = array('or' => $branchConditions);
            }

            if ($this->request->data['FileUploads']['approved_by'] != -1) {
                if ($this->request->data['FileUploads']['strict_search'] == 0)
                    $conditions[] = array('and' => array('or' => array('FileUpload.approved_by'=>$this->request->data['FileUploads']['approved_by'])));
                else
                    $conditions[] = array('or' => array('FileUpload.approved_by'=>$this->request->data['FileUploads']['approved_by']));
            }

            if ($this->request->data['FileUploads']['prepared_by'] != -1) {
                if ($this->request->data['FileUploads']['strict_search'] == 0)
                    $conditions[] = array('and' => array('or' => array('FileUpload.prepared_by'=>$this->request->data['FileUploads']['prepared_by'])));
                else
                    $conditions[] = array('or' => array('FileUpload.prepared_by'=>$this->request->data['FileUploads']['prepared_by']));
            }
            
            if ($this->request->data['FileUploads']['system_table_id'] != -1) {
                $systemTableIdConditions[] = array('FileUpload.system_table_id' => $this->request->data['FileUploads']['system_table_id']);
                if ($this->request->data['FileUploads']['strict_search'] == 0)
                    $conditions[] = array('and' => $systemTableIdConditions);
                else
                    $conditions[] = array('or' => $systemTableIdConditions);
            }
            
            if ($this->request->data['FileUploads']['master_list_of_id'] != -1) {
                $masterListConditions[] = array('FileUpload.master_list_of_format_id' => $this->request->data['FileUploads']['master_list_of_id']);
                if ($this->request->data['FileUploads']['strict_search'] == 0)
                    $conditions[] = array('and' => $masterListConditions);
                else
                    $conditions[] = array('or' => $masterListConditions);
            }
            
            if (!$this->request->data['FileUploads']['to-date'])
                $this->request->data['FileUploads']['to-date'] = date('Y-m-d');
            if ($this->request->data['FileUploads']['from-date']) {
                $conditions[] = array(
                'FileUpload.created >' => date('Y-m-d h:i:s', strtotime($this->request->data['FileUploads']['from-date'])),
                'FileUpload.created <' => date('Y-m-d h:i:s', strtotime($this->request->data['FileUploads']['to-date']))
            );
            
            if ($this->request->data['FileUploads']['archived'] == 0)
                        $conditions[] = array('FileUpload.archived'=>1);
                    else
                        $conditions[] = array('FileUpload.archived'=>0);

             $conditions =  $this->advance_search_common($conditions);
            }
            

        
        if ($this->Session->read('User.is_mr') == 0)
            $onlyBranch = array(
                'FileUpload.branchid' => $this->Session->read('User.branch_id')
            );
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array(
                'FileUpload.created_by' => $this->Session->read('User.id')
            );
        $conditions[] = array(
            $onlyBranch,
            $onlyOwn
        );
        $fileUploads = $this->FileUpload->find('all',array('recursive'=>1, 'order' => array('FileUpload.sr_no' => 'DESC'),'conditions' => array($conditions,'FileUpload.soft_delete' => 0)));
        
        foreach ($fileUploads as $f) {
            foreach ($f['FileShare'] as $fs) {
                if(in_array($this->Session->read('User.id'), json_decode($fs['users']))){
                    $final[] = $f;
                }
            }
        }
        
        $this->set('fileUploads', $final);
        
        $PublishedEmployeeList = $this->requestAction('App/get_model_list/Employee/');
        $PublishedBranchList = $this->requestAction('App/get_model_list/Branch/');
        $system_table = $this->requestAction('App/get_model_list/SystemTable/');
        $masterListOfFormat = $this->requestAction('App/get_model_list/MasterListOfFormat/');
        $this->set(compact('masterListOfFormat','PublishedEmployeeList','PublishedBranchList','system_table'));

        $this->render('index');
        }
        $PublishedEmployeeList = $this->requestAction('App/get_model_list/Employee/');
        $PublishedBranchList = $this->requestAction('App/get_model_list/Branch/');
        $system_table = $this->requestAction('App/get_model_list/SystemTable/');
        $masterListOfFormat = $this->requestAction('App/get_model_list/MasterListOfFormat/');
        $this->set(compact('masterListOfFormat','PublishedEmployeeList','PublishedBranchList','system_table'));        
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
        if (!$this->FileUpload->exists($id)) {
            throw new NotFoundException(__('Invalid file upload'));
        }
        $this->FileUpload->hasMany['FileView']['conditions'] = array('FileView.user_id <> '=> $_SESSION['User']['id']);
        $fileUpload = $this->FileUpload->find('first', array('conditions'=>array('FileUpload.id'=>$id)));
        
        $this->FileUpload->hasMany['ChangeAdditionDeletionRequest']['fields'] = array('ChangeAdditionDeletionRequest.id');
        if($fileUpload['FileUpload']['file_key']){
            $archived_files = $this->FileUpload->find('all', array(
                'fields'=>array(
                    'FileUpload.id','FileUpload.file_details','FileUpload.file_dir', 'FileUpload.file_key','FileUpload.file_status',
                    ),
                'conditions'=>array(
                    'FileUpload.id <>' => $fileUpload['FileUpload']['id'],
                    'FileUpload.file_key'=>$fileUpload['FileUpload']['file_key'])));
            
            foreach ($archived_files as $archived_file) {
                if($archived_file['ChangeAdditionDeletionRequest']){
                    foreach ($archived_file['ChangeAdditionDeletionRequest'] as $crs) {
                        $crids[] = $crs['id'];
                    }
                }
            }
        }else{
            
            $this->FileUpload->hasMany['ChangeAdditionDeletionRequest']['fields'] = array('ChangeAdditionDeletionRequest.id');
            $this->FileUpload->hasMany['ChangeAdditionDeletionRequest']['conditions'] = array('ChangeAdditionDeletionRequest.file_upload_id'=>$fileUpload['FileUpload']['id']);
            $archived_files = $this->FileUpload->find('all', array(
                'fields'=>array(
                    'FileUpload.id','FileUpload.file_details','FileUpload.file_dir', 'FileUpload.file_key','FileUpload.file_status',
                    ),
                'conditions'=>array(
                    'FileUpload.id <>' => $fileUpload['FileUpload']['id'],
                    'FileUpload.system_table_id' => $fileUpload['FileUpload']['system_table'],
                    'FileUpload.record' => $fileUpload['FileUpload']['record'],
                    // 'FileUpload.file_upload_id'=>$fileUpload['FileUpload']['id']
                    )));
            
            foreach ($archived_files as $archived_file) {
                if($archived_file['ChangeAdditionDeletionRequest']){
                    foreach ($archived_file['ChangeAdditionDeletionRequest'] as $crs) {
                        $crids[] = $crs['id'];
                    }
                }
            }
                
        }
        
        if($fileUpload['FileUpload']['file_status'] == 3){
            $sys_table = $this->FileUpload->SystemTable->find('first',array('conditions'=>array('SystemTable.id'=>$fileUpload['FileUpload']['system_table_id'])));
            $this->Session->setFlash(__('Please add new document'));
            if(!$sys_table){
                if($fileUpload['FileUpload']['system_table_id'] == 'clauses'){
                    $this->redirect(array('controller'=>'file_uploads','action' => 'add_new_file',
                        'file_upload_id'=>$id,
                        'system_table'=>'clauses',
                        'record'=>$fileUpload['FileUpload']['record'],
                        // 'change_addition_deletion_request_id'=>$id
                        ));
                }
                
                if($fileUpload['FileUpload']['system_table_id'] == 'dashboards'){                                    
                    $controller = 'dashboards';
                    $this->redirect(array('controller'=>'file_uploads','action' => 'add_new_file',
                        'file_upload_id'=>$id,
                        'system_table'=>'dashboards',
                        'record'=>$fileUpload['FileUpload']['record'],
                        'change_addition_deletion_request_id'=>$id
                        ));                                
                }
            }else{                                
                    $controller = $sys_table['SystemTable']['system_name']; 
                    $this->redirect(array('controller'=>'file_uploads','action' => 'add_new_file',
                        'file_upload_id'=>$id,
                        'system_table'=>$controller,
                        'record'=>$fileUpload['FileUpload']['record'],
                        // 'change_addition_deletion_request_id'=>$id
                        ));
            }
        }
        
        if($fileUpload['FileUpload']['system_table_id'] != 'dashboards'){
            if($fileUpload['FileUpload']['system_table_id'] != 'clauses'){
                $model = Inflector::Classify($fileUpload['SystemTable']['name']);
                $this->loadModel($model);
                $rec_data  = $this->$model->find('first',array(
                    'recursive'=>-1,
                    'fields'=>array($model.'.id',$model.'.'.$this->$model->displayField),
                    'conditions'=>array($model.'.id'=>$fileUpload['FileUpload']['record'])));
                $recordDetails['id'] = $rec_data[$model]['id'];
                $recordDetails['display'] = $rec_data[$model][$this->$model->displayField];
                $recordDetails['model'] = $model;
                $this->set('recordDetails',$recordDetails);    
            }else{
                if($fileUpload['FileUpload']['system_table_id'] == 'clauses'){
                    $this->loadModel('Clause');
                    $path = split(DS, $fileUpload['FileUpload']['file_dir']);
                    $clause = $this->Clause->find('first',array('conditions'=>array('Clause.id'=>$path[2])));
                    $this->set(compact('clause'));                    
                }
            }
            

        }else{

        }

        
        $changeAdditionDeletionRequests = $this->FileUpload->ChangeAdditionDeletionRequest->find('all',array('conditions'=>array(
            'ChangeAdditionDeletionRequest.id'=>$crids,
            )));
        $this->set('changeAdditionDeletionRequests',$changeAdditionDeletionRequests);
        if($fileUpload['FileUpload']['created_by'] == $_SESSION['User']['id']){
            $this->set('fileUpload', $fileUpload);
            
        }else{
            foreach ($fileUpload['FileShare'] as $share) {
                if($share['branch_id'] == $_SESSION['User']['branch_id'] && $share['everyone'] == 1){
                    $this->set('fileUpload', $fileUpload);
                    
                }elseif($share['branch_id'] == $_SESSION['User']['branch_id'] && !in_array($_SESSION['User']['id'], json_decode($share['users'],true))){
                    $this->redirect(array('controller' => 'users','action' => 'access_denied'));
                }else{
                    $this->set('fileUpload', $fileUpload);
                    
                }
            }
            
        }
        
        $archived = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.id'=>$fileUpload['FileUpload']['version_key'] ,'FileUpload.archived'=>1)));
        
        $this->_update_view($id,$_SESSION['User']['id'],'View');
        $PublishedUserList = $this->requestAction('App/get_model_list/User/');
        $PublishedBranchList = $this->requestAction('App/get_model_list/Branch/');
        $this->set(compact('fileUpload', 'PublishedUserList','PublishedBranchList','archived'));
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

        if ($this->request->is('post')) {
            $this->request->data['FileUpload']['system_table_id'] = $this->_get_system_table_id();
            $this->FileUpload->create();
            if ($this->FileUpload->save($this->request->data)) {
                if ($this->_show_approvals()) {
                    $this->loadModel('Approval');
                    $this->Approval->create();
                    $this->request->data['Approval']['model_name'] = 'FileUpload';
                    $this->request->data['Approval']['controller_name'] = $this->request->params['controller'];
                    $this->request->data['Approval']['user_id'] = $this->request->data['Approval']['user_id'];
                    $this->request->data['Approval']['from'] = $this->Session->read('User.id');
                    $this->request->data['Approval']['created_by'] = $this->Session->read('User.id');
                    $this->request->data['Approval']['modified_by'] = $this->Session->read('User.id');
                    $this->request->data['Approval']['record'] = $this->FileUpload->id;
                    $this->Approval->save($this->request->data['Approval']);
                }

                $this->Session->setFlash(__('The file upload has been saved'));
                if ($this->_show_evidence() == true)
                    $this->redirect(array(
                        'action' => 'view',
                        $this->FileUpload->id
                    ));
                else
                    $this->redirect(str_replace('/lists', '/add_ajax', $this->referer()));
            }
            else {
                $this->Session->setFlash(__('The file upload could not be saved. Please, try again.'));
            }
        }

        $systemTables = $this->FileUpload->SystemTable->find('list', array(
            'conditions' => array(
                'SystemTable.publish' => 1,
                'SystemTable.soft_delete' => 0
            )
        ));
        $users = $this->FileUpload->User->find('list', array(
            'conditions' => array(
                'User.publish' => 1,
                'User.soft_delete' => 0
            )
        ));
        $userSessions = $this->FileUpload->UserSession->find('list', array(
            'conditions' => array(
                'UserSession.publish' => 1,
                'UserSession.soft_delete' => 0
            )
        ));
        $masterListOfFormats = $this->FileUpload->MasterListOfFormat->find('list', array(
            'conditions' => array(
                'MasterListOfFormat.publish' => 1,
                'MasterListOfFormat.soft_delete' => 0
            )
        ));
        $this->set(compact('systemTables', 'users', 'userSessions', 'masterListOfFormats'));
        $count = $this->FileUpload->find('count');
        $published = $this->FileUpload->find('count', array(
            'conditions' => array(
                'FileUpload.publish' => 1
            )
        ));
        $unpublished = $this->FileUpload->find('count', array(
            'conditions' => array(
                'FileUpload.publish' => 0
            )
        ));
        $this->set(compact('count', 'published', 'unpublished'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        
	if (!$this->FileUpload->exists($id)) {
            throw new NotFoundException(__('Invalid file upload'));
        }
        
        $permissions = $this->FileUpload->FileShare->find('count',array('conditions'=>array(
             'OR'=>array('FileShare.users LIKE ' => '%' . $this->Session->read('User.id') . '%', 'FileShare.everyone'=>1),
                'FileShare.file_upload_id' => $id
        )));
        if($permissions == 0){
           
            $this->Session->setFlash(__('Access Denied: You do not have permission to edit this file'), 'default', array('class'=>'alert-danger'));
           // throw new NotFoundException(__('You do not have permission to edit this file.'));
            $this->redirect($this->referer());
        }

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		$file_data = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id' =>$id)));
        
        $file_dir = explode("-ver-",$file_data['FileUpload']['file_details']);
        $check_versions = $this->FileUpload->find('all',array('conditions'=>array(
			'FileUpload.system_table_id'=>$file_data['FileUpload']['system_table_id'],
			'FileUpload.record'=>$file_data['FileUpload']['record'],
			'FileUpload.id <> ' =>$file_data['FileUpload']['id'],
			'FileUpload.file_dir like' => '%'.$file_dir[0]."-ver-%".$file_data['FileUpload']['file_type']
		)));
        $this->set('revisions',$check_versions);
        $current_file = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.id' =>$this->request->params['pass'][0])));
        $this->set('current_file',$current_file);

        // actual edit
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['FileUpload']['file_details'] = $this->request->data['FileUpload']['file_details']."-ver-". $this->request->data['FileUpload']['version'];
            $this->request->data['FileUpload']['file_details']=str_replace('.','-',$this->request->data['FileUpload']['file_details']);
            $folder_name = explode('/',$this->request->data['FileUpload']['old_file_details']);
            
            if($folder_name[2])$this->request->params['pass'][1] = $folder_name[2];
			 $this->request->data['FileUpload']['file_details'] = str_replace(' ','-',$this->request->data['FileUpload']['file_details']);	
                if($this->request->params['pass'][1] == 'products'){
                    $file_from =  Configure::read("MediaPath") .  'files'. DS . $this->Session->read('User.company_id') . DS .$this->request->data['FileUpload']['old_file_details'];
                    $file_to = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS . "upload" . DS . $this->Session->read('User.id') . DS  . 'products' . DS . $this->request->params['pass'][3] .DS .$this->request->params['pass'][2]. DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];				
                }elseif($this->request->params['pass'][1] == 'users'){
                    $file_from = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS .$this->request->data['FileUpload']['old_file_details'];
                    $file_to = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS . "upload" . DS . 'documents' . DS  .$this->request->params['pass'][2] . DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];				
                }elseif(
                    $this->request->params['pass'][1] == 'quality_system_manual' ||
                    $this->request->params['pass'][1] == 'quality_system_procedures' ||
                    $this->request->params['pass'][1] == 'process_chart' ||
                    $this->request->params['pass'][1] == 'guidelines' ||
                    $this->request->params['pass'][1] == 'work_instructions')
                    {
                        $file_from = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS .$this->request->data['FileUpload']['old_file_details'];
                        $file_from = str_replace($this->request->params['pass'][1].DS.$this->request->params['pass'][1], 'documents'.DS.$this->request->params['pass'][1],$file_from);
                        $file_to = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS . "upload" . DS . 'documents' . DS  .$this->request->params['pass'][1] . DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];				
                    }else{
                        $file_from = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS .$this->request->data['FileUpload']['old_file_details'];
                        $file_to = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS . "upload" . DS . $this->Session->read('User.id') . DS . $folder_name[2] . DS . $this->request->data['FileUpload']['record'] . DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];			
                    }

                    
                    if(rename($file_from,$file_to)==false){
                        $this->Session->setFlash(__('Failed to update'));
                        //$this->redirect(array('controller'=>$this->request->data['FileUpload']['controller'],'action' => 'view',$this->request->data['FileUpload']['record']));
                    }

                    $this->request->data['FileUpload']['file_dir'] = $this->request->data['FileUpload']['file_dir'] . DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];
                    $check_versions = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.system_table_id'=>$this->request->data['FileUpload']['system_table_id'],'FileUpload.record'=>$this->request->data['FileUpload']['record'])));
                    $this->set('revisions',$check_versions);
                    
                    if(count($check_versions) <= 1)$this->request->data['FileUpload']['archive'] = 0;
                    $this->request->data['FileUpload']['file_dir'] = str_replace(Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS, '',$file_to);
                    
                    if ($this->FileUpload->save($this->request->data)) {
				
                        $get_actual_file_name = explode('-ver-',$this->request->data['FileUpload']['file_details']);
				        $get_actual_file_name = $get_actual_file_name[0];
                        $check_versions = $this->FileUpload->find('first',array( 'fields' =>array('FileUpload.id','FileUpload.file_details','FileUpload.record','FileUpload.system_table_id','FileUpload.version'),
                                                                                'order'=>array('FileUpload.version' => 'DESC'),  
                                                                                'conditions'=>array(
                                                                                'FileUpload.system_table_id'=>$this->request->data['FileUpload']['system_table_id'],
                                                                                'FileUpload.record'=>array($this->request->params['pass'][2], $this->request->params['pass'][3]),
                                                                                'FileUpload.file_details like ' => $get_actual_file_name . '%',
                                                                                'FileUpload.file_status' => 1			
                                                                                ),
                                                                                'recursive'=>-1));
				
        				$rev = str_replace(Configure::read("MediaPath")   . 'files' . DS . $this->Session->read('User.company_id') . DS .'upload' , Configure::read("MediaPath")   . 'files' . DS . $this->Session->read('User.company_id') . DS .'revisions', $this->request->data['FileUpload']['file_dir']);
        				$rev = str_replace('.'.$this->request->data['FileUpload']['file_type'],'',$rev);
        				$rev= explode('/',$rev);
        				
        				if(count($rev)==6){
        				    $get_only_name = explode('-ver-',$rev[5]);
        				    $rev = Configure::read("MediaPath")   . 'files' . DS . $this->Session->read('User.company_id') . DS .  'revisions'. DS . $rev[2]. DS . $rev[3] . DS .$rev[4] . DS . '.'.$get_only_name[0];				    
        				}elseif(count($rev)==5) {
        				    $get_only_name = explode('-ver-',$rev[4]);
        				    $rev = Configure::read("MediaPath")  . 'files' . DS . $this->Session->read('User.company_id') . DS .  'revisions' . DS . $rev[1]. DS . $rev[2] . DS . '.'.$get_only_name[0];
        				}else {
        				    $get_only_name = explode('-ver-',$rev[3]);
        				 $rev = Configure::read("MediaPath")  . 'files' . DS . $this->Session->read('User.company_id') . DS .  'revisions' . DS . $rev[1]. DS . $rev[2] . DS . '.'.$get_only_name[0];
                                         $check_versions['FileUpload']['version'];
                                        
        				}
				        $Cfile = new File($rev);
        				
        				$Cfile->write($check_versions['FileUpload']['version'],'w',true);
                        if ($this->_show_approvals()) $this->_save_approvals();                    			
                        $this->Session->setFlash(__('The file upload has been saved'));
                        
                        if($this->request->params['pass'][1] == 'dashboards'){
                            $this->redirect(array('controller'=>'dashboards','action' => strtolower($this->request->data['FileUpload']['record'])));
                        }elseif($this->request->params['pass'][1] == 'users'){
                            $this->redirect(array('controller'=>'users','action' => 'dashboard'));
                        }elseif($this->request->params['pass'][1] == 'products'){
                            $this->redirect(array('controller'=>'products','action' => 'view',$this->request->params['pass'][3]));
                        }else{
                            $this->redirect($this->referer());                            
                        }
                    }else {
				        $this->Session->setFlash(__('The file upload could not be saved. Please, try again.'));
                    }
                } else {
                 $options = array('conditions' => array('FileUpload.' . $this->FileUpload->primaryKey => $id));
                 $this->request->data = $this->FileUpload->find('first', $options);
		}

		$systemTables = $this->FileUpload->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1,'SystemTable.soft_delete' => 0)));
		$users = $this->FileUpload->User->find('list', array('conditions' => array('User.publish' => 1,'User.soft_delete' => 0)));
		
		$masterListOfFormats = $this->FileUpload->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1,'MasterListOfFormat.soft_delete' => 0)));
		$PublishedEmployeeList = $this->_get_employee_list();
		$this->set(compact('systemTables', 'users', 'userSessions', 'masterListOfFormats','PublishedEmployeeList'));
		
		$count = $this->FileUpload->find('count');
		$published = $this->FileUpload->find('count', array('conditions' => array('FileUpload.publish' => 1)));
		$unpublished = $this->FileUpload->find('count', array('conditions' => array('FileUpload.publish' => 0)));
		$this->set(compact('count', 'published', 'unpublished'));
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->FileUpload->exists($id)) {
            throw new NotFoundException(__('Invalid file upload'));
        }

        $this->loadModel('Approval');
        if (!$this->Approval->exists($approvalId)) {
            throw new NotFoundException(__('Invalid approval id'));
        }

        $file_data = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id' =>$this->request->params['pass'][0])));
        $file_dir = explode("-ver-",$file_data['FileUpload']['file_details']);
        $check_versions = $this->FileUpload->find('all',array('conditions'=>array(
            'FileUpload.system_table_id'=>$this->request->params['pass'][1],
            'FileUpload.record'=>$this->request->params['pass'][2],
            'FileUpload.id <> ' =>$this->request->params['pass'][0],
            'FileUpload.file_dir like' => '%'.$file_dir[0]."-ver-%".$file_data['FileUpload']['file_type']
        )));
        $this->set('revisions',$check_versions);
        $current_file = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.id' =>$this->request->params['pass'][0])));
        $this->set('current_file',$current_file);

        $approval = $this->Approval->read(null, $approvalId);
        $this->set('same', $approval['Approval']['user_id']);

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        // actual edit
        if ($this->request->is('post') || $this->request->is('put'))
        {
            $this->request->data['FileUpload']['file_details'] = $this->request->data['FileUpload']['file_details']."-ver-". $this->request->data['FileUpload']['version'];
            $this->request->data['FileUpload']['file_details']=str_replace('.','-',$this->request->data['FileUpload']['file_details']);
            $folder_name = explode('/',$this->request->data['FileUpload']['old_file_details']);
            
            if($folder_name[2])$this->request->params['pass'][1] = $folder_name[2];
             $this->request->data['FileUpload']['file_details'] = str_replace(' ','-',$this->request->data['FileUpload']['file_details']);  
                if($this->request->params['pass'][1] == 'products'){
                    $file_from =  Configure::read("MediaPath") .  'files'. DS . $this->Session->read('User.company_id') . DS .$this->request->data['FileUpload']['old_file_details'];
                    $file_to = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS . "upload" . DS . $this->Session->read('User.id') . DS  . 'products' . DS . $this->request->params['pass'][3] .DS .$this->request->params['pass'][2]. DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];                
                }elseif($this->request->params['pass'][1] == 'users'){
                    $file_from = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS .$this->request->data['FileUpload']['old_file_details'];
                    $file_to = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS . "upload" . DS . 'documents' . DS  .$this->request->params['pass'][2] . DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];             
                }elseif(
                    $this->request->params['pass'][1] == 'quality_system_manual' ||
                    $this->request->params['pass'][1] == 'quality_system_procedures' ||
                    $this->request->params['pass'][1] == 'process_chart' ||
                    $this->request->params['pass'][1] == 'guidelines' ||
                    $this->request->params['pass'][1] == 'work_instructions')
                    {
                        $file_from = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS .$this->request->data['FileUpload']['old_file_details'];
                        $file_from = str_replace($this->request->params['pass'][1].DS.$this->request->params['pass'][1], 'documents'.DS.$this->request->params['pass'][1],$file_from);
                        $file_to = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS . "upload" . DS . 'documents' . DS  .$this->request->params['pass'][1] . DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];             
                    }else{
                        $file_from = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS .$this->request->data['FileUpload']['old_file_details'];
                        $file_to = Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS . "upload" . DS . $this->Session->read('User.id') . DS . $folder_name[2] . DS . $this->request->data['FileUpload']['record'] . DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];           
                    }

                    
                    if(rename($file_from,$file_to)==false){
                        $this->Session->setFlash(__('Failed to update'));                        
                    }

                    $this->request->data['FileUpload']['file_dir'] = $this->request->data['FileUpload']['file_dir'] . DS .$this->request->data['FileUpload']['file_details'].'.'.$this->request->data['FileUpload']['file_type'];
                    $check_versions = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.system_table_id'=>$this->request->data['FileUpload']['system_table_id'],'FileUpload.record'=>$this->request->data['FileUpload']['record'])));
                    $this->set('revisions',$check_versions);
                    
                    if(count($check_versions) <= 1)$this->request->data['FileUpload']['archive'] = 0;
                    $this->request->data['FileUpload']['file_dir'] = str_replace(Configure::read("MediaPath")  .  'files'. DS . $this->Session->read('User.company_id') . DS, '',$file_to);
                    
                    if ($this->FileUpload->save($this->request->data)) {
                
                        $get_actual_file_name = explode('-ver-',$this->request->data['FileUpload']['file_details']);
                        $get_actual_file_name = $get_actual_file_name[0];
                        $check_versions = $this->FileUpload->find('first',array( 'fields' =>array('FileUpload.id','FileUpload.file_details','FileUpload.record','FileUpload.system_table_id','FileUpload.version'),
                                                                                'order'=>array('FileUpload.version' => 'DESC'),  
                                                                                'conditions'=>array(
                                                                                'FileUpload.system_table_id'=>$this->request->data['FileUpload']['system_table_id'],
                                                                                'FileUpload.record'=>array($this->request->params['pass'][2], $this->request->params['pass'][3]),
                                                                                'FileUpload.file_details like ' => $get_actual_file_name . '%',
                                                                                'FileUpload.file_status' => 1           
                                                                                ),
                                                                                'recursive'=>-1));
                
                        $rev = str_replace(Configure::read("MediaPath")   . 'files' . DS . $this->Session->read('User.company_id') . DS .'upload' , Configure::read("MediaPath")   . 'files' . DS . $this->Session->read('User.company_id') . DS .'revisions', $this->request->data['FileUpload']['file_dir']);
                        $rev = str_replace('.'.$this->request->data['FileUpload']['file_type'],'',$rev);
                        $rev= explode('/',$rev);
                        
                        if(count($rev)==6){
                            $get_only_name = explode('-ver-',$rev[5]);
                            $rev = Configure::read("MediaPath")   . 'files' . DS . $this->Session->read('User.company_id') . DS .  'revisions'. DS . $rev[2]. DS . $rev[3] . DS .$rev[4] . DS . '.'.$get_only_name[0];                  
                        }elseif(count($rev)==5) {
                            $get_only_name = explode('-ver-',$rev[4]);
                            $rev = Configure::read("MediaPath")  . 'files' . DS . $this->Session->read('User.company_id') . DS .  'revisions' . DS . $rev[1]. DS . $rev[2] . DS . '.'.$get_only_name[0];
                        }else {
                            $get_only_name = explode('-ver-',$rev[3]);
                         $rev = Configure::read("MediaPath")  . 'files' . DS . $this->Session->read('User.company_id') . DS .  'revisions' . DS . $rev[1]. DS . $rev[2] . DS . '.'.$get_only_name[0];
                                         $check_versions['FileUpload']['version'];
                                        
                        }
                        $Cfile = new File($rev);
                        
                        $Cfile->write($check_versions['FileUpload']['version'],'w',true);
                        if ($this->_show_approvals()) $this->_save_approvals();                             
                        $this->Session->setFlash(__('The file upload has been saved'));

                        if ($this->_show_approvals()) $this->_save_approvals ();
                        
                        if($this->request->params['pass'][1] == 'dashboards'){
                            $this->redirect(array('controller'=>'dashboards','action' => strtolower($this->request->data['FileUpload']['record'])));
                        }elseif($this->request->params['pass'][1] == 'users'){
                            $this->redirect(array('controller'=>'users','action' => 'dashboard'));
                        }elseif($this->request->params['pass'][1] == 'products'){
                            $this->redirect(array('controller'=>'products','action' => 'view',$this->request->params['pass'][3]));
                        }else{
                            $this->redirect($this->referer());                            
                        }
                    }else {
                        $this->Session->setFlash(__('The file upload could not be saved. Please, try again.'));
                    }
                } else {
                 $options = array('conditions' => array('FileUpload.' . $this->FileUpload->primaryKey => $id));
                 $this->request->data = $this->FileUpload->find('first', $options);
        }

        $systemTables = $this->FileUpload->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1,'SystemTable.soft_delete' => 0)));
        $users = $this->FileUpload->User->find('list', array('conditions' => array('User.publish' => 1,'User.soft_delete' => 0)));
        
        $masterListOfFormats = $this->FileUpload->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1,'MasterListOfFormat.soft_delete' => 0)));
        $PublishedEmployeeList = $this->_get_employee_list();
        $this->set(compact('systemTables', 'users', 'userSessions', 'masterListOfFormats','PublishedEmployeeList'));
        
        $count = $this->FileUpload->find('count');
        $published = $this->FileUpload->find('count', array('conditions' => array('FileUpload.publish' => 1)));
        $unpublished = $this->FileUpload->find('count', array('conditions' => array('FileUpload.publish' => 0)));
        $this->set(compact('count', 'published', 'unpublished'));

        $PublishedEmployeeList = $this->requestAction('App/get_model_list/Employee/');
        $PublishedBranchList = $this->requestAction('App/get_model_list/Branch/');
        $system_table = $this->requestAction('App/get_model_list/SystemTable/');
        $masterListOfFormat = $this->requestAction('App/get_model_list/MasterListOfFormat/');
        $this->set(compact('masterListOfFormat','PublishedEmployeeList','PublishedBranchList','system_table'));



    }

    public function export() {
        $this->set('ref', $this->referer());
        $this->set('tableFields', $this->tableFields);
    }

    public function _check_access($modelName = null) {
        $this->loadModel('User');
        $this->User->recursive = 0;
        $userAccess = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Session->read('User.id')
            ),
            'fields' => array(
                'User.user_access'
            )
        ));
        $newData = json_decode($userAccess['User']['user_access'], true);
        if (!$newData) {
            return true;
        } else {
            foreach ($newData['user_access'] as $m):
                $model = array_keys($m);
                if (Inflector::Singularize(Inflector::Classify($model[0])) == $modelName) {

                    if ($m[$model[0]]['allow'] == true) {
                        return true;
                    } else {
                        return false;
                    }
                }

            endforeach;
        }
    }

    public function export_xls() {
        ini_set('memory_limit','256M');
        ini_set('max_execution_time', 600);
        
        $this->loadModel('InternalAuditPlan');
        $fileName = $this->data['file_uploads']['fileName'];
        $varData = array_keys($this->viewVars['tableFields']);

        $sheetId = 1;

        $exportModel = Inflector::Classify($fileName);
        $this->loadModel($exportModel);
        $schema = $this->$exportModel->schema();
	$customArray = $this->$exportModel->customArray;


        foreach ($schema as $key => $value):
            if ($key != 'id' && $key != 'sr_no' && $key != 'publish' && $key != 'soft_delete' && $key != 'branchid' && $key != 'departmentid' && $key != 'created_by' && $key != 'created' && $key != 'modified_by' && $key != 'modified' && $key != 'system_table_id' && $key != 'master_list_of_format_id' && $key != 'company_id' && $key != 'record_status' && $key != 'status_user_id') {
                if ($value['null'] == false)
                    $fields[] = array(
                        'bold' => true,
                        'fieldName' => Inflector::Humanize(str_replace('_id', '', $key))
                    );
                else
                    $fields[] = array(
                        'bold' => false,
                        'fieldName' => Inflector::Humanize(str_replace('_id', '', $key))
                    );
            }

        endforeach;
        $associatedForms = $this->$exportModel->belongsTo;
        $objPHPExcel = new PHPExcel();
        $styleRed = array(
            'font' => array(
                'bold' => false,
                'color' => array(
                    'rgb' => 'FFFFFF'
                ),
                'name' => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'FF0000'
                )
            )
        );
        $styleNil = array(
            'font' => array(
                'bold' => false,
                'color' => array(
                    'rgb' => 'FFFFFF'
                ),
                'name' => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '428BCA'
                )
            )
        );
        $styleHeader = array(
            'font' => array(
                'size' => 16,
                'bold' => false,
                'color' => array(
                    'rgb' => '428BCA'
                ),
                'name' => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ffffff'
                )
            ),
            'alignment' => array(
                'wrapText' => true
            )
        );
        $styleNote = array(
            'font' => array(
                'size' => 9,
                'bold' => false,
                'color' => array(
                    'rgb' => 'ff0000'
                ),
                'name' => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ffffff'
                )
            )
        );

        // Set document properties

        $objPHPExcel->getProperties()->setCreator("FlinkISO")->setLastModifiedBy($this->Session->read('Administrator.id'))->setTitle("FlinkISO Standard Excel Import File for " . Inflector::humanize($fileName))->setSubject("Office 2007 XLSX Test Document")->setDescription("These are standard import formats for data to be importaed to flinkISO application")->setKeywords("office 2007 openxml php")->setCategory("FlinkISO");
        $objPHPExcel->setActiveSheetIndex(0)->setTitle(substr(Inflector::humanize($fileName), 0, 30));
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J1:Q1');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->applyFromArray($styleHeader);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setWrapText(true);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'FlinkISO standard upload format for ' . Inflector::humanize($fileName));
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('J1')->applyFromArray($styleNote);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('J1')->getAlignment()->setWrapText(true);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Fields with Red background are mandatory. For you ease we have also exported the relative data which you might require to add along with these records. You can copy paste required values from those worksheets so that there will be a consistency in data while to import and add this back.');
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(38);
        $y = "A";

        foreach ($fields as $field):

            if ($field['bold'] == true) {
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($y . "2")->applyFromArray($styleRed);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($y . "2", $field['fieldName']);
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($y . "2")->applyFromArray($styleNil);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($y . "2", $field['fieldName']);
            }

            if (in_array(Inflector::Classify($field['fieldName']), array_keys($associatedForms))) {

                $rowCount = null;
                $rowCount = $this->_get_assosciate_data($objPHPExcel, $associatedForms[Inflector::Classify($field['fieldName'])]['className'], $styleHeader, $sheetId, $field['fieldName']);
                $rowCount = $rowCount + 3;
                $objPHPExcel->setActiveSheetIndex(0);
                $objValidation = $objPHPExcel->getActiveSheet()->getCell($y . '3')->getDataValidation();
                $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(true);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1(Inflector::Classify($field['fieldName'] ). '!$A$3:$A$' . $rowCount . '"');

                for ($iRow = 3; $iRow <= 2000; $iRow++) {
                    $objPHPExcel->getActiveSheet()->getCell($y . '' . $iRow)->setDataValidation(clone $objValidation);
                }

                $sheetId++;
	    }

	    if(isset($field['fieldName']) && isset($customArray) && in_array(Inflector::underscore(Inflector::Classify($field['fieldName'])), array_keys($customArray))){

		$rowCount = null;
                $rowCount = $this->_get_custom_array_data($objPHPExcel, $customArray, Inflector::underscore(Inflector::Classify($field['fieldName'])),
		$styleHeader, $sheetId);
                $rowCount = $rowCount + 3;
                $objPHPExcel->setActiveSheetIndex(0);
                $objValidation = $objPHPExcel->getActiveSheet()->getCell($y . '3')->getDataValidation();
                $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(true);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1(Inflector::underscore(Inflector::Classify($field['fieldName'])) . '!$A$3:$A$' . $rowCount . '"');

                for ($iRow = 3; $iRow <= 2000; $iRow++) {
                    $objPHPExcel->getActiveSheet()->getCell($y . '' . $iRow)->setDataValidation(clone $objValidation);
                }

                $sheetId++;

	    }
            $y++;

        endforeach;

	$sheetId = 1;
        unset($associatedForms['BranchIds']);
        unset($associatedForms['DepartmentIds']);
        unset($associatedForms['SystemTable']);
        unset($associatedForms['MasterListOfFormat']);
        foreach ($associatedForms as $key => $value):
            if ($this->_check_access($key) == false)
                unset($associatedForms[$key]);
        endforeach;

        $fileName = "FlinkISO_" . Inflector::humanize($fileName) . ".xls";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; ; charset=utf8-bin');
        header('Content-Disposition: attachment;filename="' . $fileName . '');
        header('Cache-Control: max-age=10');
        header("Pragma: public");
        header("Expires: 0");
        header("Content-Type: application/vnd.ms-excel; charset=utf8-bin");
        header("Content-Type: application/force-download");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function _get_assosciate_data($objPHPExcel = null, $modelName = null, $styleHeader = null, $sheetId = null, $belongToFieldName = null) {
        $datas = null;
        $finalData = null;
       
        if($belongToFieldName == 'Prepared By'){
            $newModel = 'Employee';
            $this->loadModel('Employee');
            $fieldName = 'name';
            $datas = $this->Employee->find('all',array('fields'=>array('Employee.name'),'conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0,'Employee.branch_id'=>$this->Session->read('User.branch_id')),'recursive'=>-1));
            
        }elseif($belongToFieldName == 'Approved By'){
            $newModel = 'Employee';
            $this->loadModel('Employee');
            $fieldName = 'name';
            $datas = $this->Employee->find('all',array('fields'=>array('Employee.name'),'conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0,'Employee.is_approvar'=>1),'recursive'=>-1));
            
        }else{
            $newModel = $modelName;
            $this->loadModel($newModel);
            $fieldName = $this->$newModel->displayField;
            $this->$newModel->recursive = - 1;
            $datas = $this->$newModel->find('all', array(
                'fields' => array(
                    $newModel . '.' . $fieldName
                ),
                'conditions' => array(
                    $newModel . '.publish' => 1,
                    $newModel . '.soft_delete' => 0
                )
            ));
        }
        
        $data = null;
        $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
        $objWorksheet->setTitle(substr(Inflector::Classify($belongToFieldName), 0, 30));
        $objPHPExcel->addSheet($objWorksheet);
        $objPHPExcel->setActiveSheetIndex($sheetId)->getStyle('A1')->applyFromArray($styleHeader);
        $objPHPExcel->setActiveSheetIndex($sheetId)->mergeCells('A1:H1');
        $objPHPExcel->setActiveSheetIndex($sheetId)->setCellValue('A1', 'Related Records for ' . $belongToFieldName);
        foreach ($datas as $data):
            $i = 3;
            $finalData[] = $data[$newModel][$fieldName];
            $final_data_data .= '"' . $data[$newModel][$fieldName] . ',"';
            foreach ($finalData as $d):
                $x = array(
                    $d
                );
                $objPHPExcel->setActiveSheetIndex($sheetId)->mergeCells('A' . $i . ':H' . $i);
                $objPHPExcel->setActiveSheetIndex($sheetId)->fromArray($x, NULL, 'A' . $i);
                $i++;
            endforeach;
        endforeach;
        return count($datas);
    }
   
    public function _get_custom_array_data($objPHPExcel = null, $customArray = null, $customArrayKey = null, $styleHeader = null, $sheetId = null) {
        $datas = null;
        $finalData = null;
        $data = null;
        $objWorksheet = new PHPExcel_Worksheet($objPHPExcel);
        $objWorksheet->setTitle(substr($customArrayKey, 0, 30));
        $objPHPExcel->addSheet($objWorksheet);
        $objPHPExcel->setActiveSheetIndex($sheetId)->getStyle('A1')->applyFromArray($styleHeader);
        $objPHPExcel->setActiveSheetIndex($sheetId)->mergeCells('A1:H1');
        $objPHPExcel->setActiveSheetIndex($sheetId)->setCellValue('A1', 'Related Records for ' . $customArrayKey);
	//$customArray = $this->$exportModel->customArray;
	$datas = $customArray[$customArrayKey];
        foreach ($datas as $data):
            $i = 3;
            $finalData[] = $data;
            $final_data_data .= '"' . $data. ',"';
            foreach ($finalData as $d):
                $x = array(
                    $d
                );
                $objPHPExcel->setActiveSheetIndex($sheetId)->mergeCells('A' . $i . ':H' . $i);
                $objPHPExcel->setActiveSheetIndex($sheetId)->fromArray($x, NULL, 'A' . $i);
                $i++;
            endforeach;
        endforeach;

        return count($datas);
    }

    public function export_xls_data() {
        $records = $this->request->data['file_uploads']['rec_selected'];
        $newRecords = explode("+", $records);
        unset($newRecords[0]);
        $openModel = $this->request->data['file_uploads']['model_name'];
        $this->loadModel($openModel);
        foreach ($this->request->data['file_uploads']['fields'] as $key => $value):
            $newModel = $this->request->data['file_uploads']['model_name'];

	    if (strpos($value, "_id") || $value == 'assigned_to' || $value == 'calibration_frequency' || $value == 'maintenance_frequency') {
		foreach($this->$newModel->belongsTo as $belongToKey=>$belongToVal){
		     if($belongToVal['foreignKey'] == $value){
			 if($this->$belongToVal['className']->isVirtualField($this->$belongToVal['className']->displayField)){
			     $displayField = $this->$belongToVal['className']->getVirtualField($this->$belongToVal['className']->displayField) . ' as '.$belongToKey . '_' .$this->$belongToVal['className']->displayField;

			 }else{
			     $displayField = $belongToKey . '.' . $this->$belongToVal['className']->displayField;
			 }
			  $getFields[] = $displayField;
                          $getTitles[] = Inflector::humanize(str_replace('_id', '', $value));
		     }
		}
            }else if (strpos($value, "_by")) {
		foreach($this->$newModel->belongsTo as $belongToKey=>$belongToVal){
		    if($belongToVal['foreignKey'] == $value){
			 if($this->$belongToVal['className']->isVirtualField($this->$belongToVal['className']->displayField)){
			     $displayField = $this->$belongToVal['className']->getVirtualField($this->$belongToVal['className']->displayField) . ' as '.$belongToKey . '_' .$this->$belongToVal['className']->displayField;
			 }else{
			     $displayField = $belongToKey . '.' .$this->$belongToVal['className']->displayField;
			 }
                         $getFields[] =  $displayField;
                         $getTitles[] = Inflector::humanize(str_replace('_by', '', $value));
		    }
		}
	    }else {
		    $getFields[] = $newModel . '.' . $value;
                $getTitles[] = Inflector::humanize($value);

	    }
        endforeach;

	$this->$openModel->recursive = 0;
        $records = $this->$openModel->find('all', array(
            'fields' => $getFields,
            'conditions' => array(
                'OR' => array(
                    $openModel . '.id' => $newRecords
                )
            )
        ));


        $objPHPExcel = new PHPExcel();

        // Set document properties

        $objPHPExcel->getProperties()->setCreator("FlinkISO")->setLastModifiedBy($this->Session->read('Administrator.id'))->setTitle("FlinkISO Standard Excel Import File ")->setSubject("Office 2007 XLSX Test Document")->setDescription("These are standard import formats for data to be importaed to flinkISO application")->setKeywords("office 2007 openxml php")->setCategory("FlinkISO");
        $styleHeader = array(
            'font' => array(
                'size' => 16,
                'bold' => false,
                'color' => array(
                    'rgb' => '428BCA'
                ),
                'name' => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ffffff'
                )
            ),
            'alignment' => array(
                'wrapText' => true
            )
        );
        $styleNil = array(
            'font' => array(
                'bold' => true,
                'color' => array(
                    'rgb' => 'FFFFFF'
                ),
                'name' => 'Arial'
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '428BCA'
                )
            ),
            'alignment' => array(
                'wrapText' => true
            )
        );

        $styleBorder = array(
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'),),));

        $formatHeader = $this->create_header($openModel);

        $objPHPExcel->setActiveSheetIndex(0)->setTitle(substr($openModel, 0, 30));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'FlinkISO : ' . $openModel . ' data');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:H1')->applyFromArray($styleHeader);
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(25);

        if ($formatHeader) {

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:J3');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A3", $formatHeader['MasterListOfFormat']['title'])->getStyle("A3:J3")->applyFromArray($styleBorder);

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:C4');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A4", "Document Number")->getStyle("A4")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D4", $formatHeader['MasterListOfFormat']['document_number'])->getStyle("D4")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:C5');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A5", "Revision Number")->getStyle("A5")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D5", $formatHeader['MasterListOfFormat']['revision_number'])->getStyle("D5")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:C6');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6", "Revision Date")->getStyle("A6")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D6", $formatHeader['MasterListOfFormat']['revision_date'])->getStyle("D6")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F4:G4');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F4", "Prepared By")->getStyle("F4:F4")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F5:G5');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F5", "Approved By")->getStyle("F5:G5")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H4:J4');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H4", $formatHeader['PreparedBy']['name'])->getStyle("H4:J4")->applyFromArray($styleBorder);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H5:J5');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H5", $formatHeader['PreparedBy']['name'])->getStyle("H5:J5")->applyFromArray($styleBorder);

            $i = 7;
        }

        $i = $i + 2;
        $x = "A";
        foreach ($getTitles as $y) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle($x . $i)->applyFromArray($styleNil);
            $x++;
        }

        $objPHPExcel->setActiveSheetIndex(0)->fromArray($getTitles, NULL, 'A' . $i);
        $i++;
        foreach ($records as $record):
            foreach ($this->request->data['file_uploads']['fields'] as $key => $value):
                $openModel = $this->request->data['file_uploads']['model_name'];
                if (strpos($value, "_id") || strpos($value, "_by") || 
                        $value == 'assigned_to' || 
                        $value == 'calibration_frequency' || 
                        $value == 'maintenance_frequency'
                     ) {
                    foreach($this->$openModel->belongsTo as $belongToKey=>$belongToVal){
                        if($belongToVal['foreignKey'] == $value){
                            if($this->$belongToVal['className']->isVirtualField($this->$belongToVal['className']->displayField)){
                                $a[$openModel][$value]  = $record[0][$belongToKey.'_'.$this->$belongToVal['className']->displayField];
			                 }else
				            $a[$openModel][$value]  = $record[$belongToKey][$this->$belongToVal['className']->displayField];
			             }
                    }

                } else {

                if(array_key_exists($value, $this->$newModel->customArray)){
    			    $a[$openModel][$value] = $this->$newModel->customArray[$value][$record[$openModel][$value]];
    			}else
    			    $a[$openModel][$value] = $record[$openModel][$value];
    	        }
            endforeach;
            $objPHPExcel->setActiveSheetIndex(0)->fromArray($a, NULL, 'A' . $i);


            $i++;
        endforeach;
        if ($this->request->data['file_uploads']['save_type'] == 0) {
            $fileName = "FlinkISO_Data_" . $openModel . ".xls";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; ; charset=utf8-bin');
            header('Content-Disposition: attachment;filename="' . $fileName . '');
            header('Cache-Control: max-age=10');
            header("Pragma: public");
            header("Expires: 0");
            header("Content-Type: application/vnd.ms-excel; charset=utf8-bin");
            header("Content-Type: application/force-download");
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else if($this->request->data['file_uploads']['save_type'] == 1){
            $newData = array();
            $sysTableId = null;
            $fName = "FlinkISO_Data_" . $openModel;
            if($this->request->data['file_uploads']['title']){
                $fileName = str_replace(' ','_',$this->request->data['file_uploads']['title']) . ".xls";
            }else{
                $fileName = "FlinkISO_Data_" . $openModel . ".xls";
            }
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $folder = new Folder();

            $folder->create(Configure::read('MediaPath') . "files" . DS . $this->Session->read('User.company_id') . DS . "SavedReports" . DS . str_replace(" ", "", $this->Session->read('User.name')) . DS . date('d-M-Y') . DS . $openModel, 0777);
            $path = Configure::read('MediaPath') . "files" . DS . $this->Session->read('User.company_id') . DS . "SavedReports" . DS . str_replace(" ", "", $this->Session->read('User.name')) . DS . date('d-M-Y') . DS . $openModel;
            $i = 1;
            while (file_exists($path . DS . $fileName)) {
                $fileName = $fName . "_" . $i . ".xls";
                $i++;
            }
            $objWriter->save(Configure::read('MediaPath') . "files" . DS . $this->Session->read('User.company_id') . DS . "SavedReports" . DS . str_replace(" ", "", $this->Session->read('User.name')) . DS . date('d-M-Y') . DS . $openModel . DS . $fileName);



            $this->loadModel('SystemTable');
            $sysTableId = $this->SystemTable->find('first', array(
                'fields' => array('SystemTable.id', 'SystemTable.system_name'),
                'recursive' => 0,
                'conditions' => array('SystemTable.system_name' => Inflector::tableize($openModel))));


            $this->loadModel('MasterListOfFormat');

            $masterId = $this->MasterListOfFormat->find('first', array(
                'fields' => array('MasterListOfFormat.id', 'MasterListOfFormat.system_table_id'),
                'recursive' => 0,
                'conditions' => array('MasterListOfFormat.system_table_id' => $sysTableId['SystemTable']['id'])));

            $this->loadModel('Report');
            $this->Report->create();
            $newData['Report']['title'] = $this->request->data['file_uploads']['title'];
            $newData['Report']['description'] = $this->request->data['file_uploads']['description'];
            $newData['Report']['details'] = "files" . DS . $this->Session->read('User.company_id') . DS . "SavedReports" . DS . str_replace(" ", "", $this->Session->read('User.name')) . DS . date('d-M-Y') . DS . $openModel . DS . $fileName;
            $newData['Report']['branch_id'] = $this->request->data['file_uploads']['branch_id'];
            $newData['Report']['department_id'] = $this->request->data['file_uploads']['department_id'];
            $newData['Report']['master_list_of_format_id'] = isset($masterId['MasterListOfFormat']['id']) ? $masterId['MasterListOfFormat']['id'] : '-1';
            $newData['Report']['report_date'] = date('Y-m-d');
            $newData['Report']['company_id'] = $this->Session->read('User.company_id');
            $newData['Report']['publish'] = 1;
            $this->Report->save($newData, false);
            $this->Session->setFlash("File saved succesfully on server");
            $controller = Inflector::pluralize($openModel);
            $controller = Inflector::underscore($controller);
            $this->redirect(array('controller'=>$controller,'action'=>'index'));

        }
        else if($this->request->data['file_uploads']['save_type'] == 2){  //Save type 2 for send email
            $newData = array();
            $sysTableId = null;
            $fileName = "FlinkISO_Attachment_" . $openModel . ".xls";
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $folder = new Folder();
            $folder->create(Configure::read('MediaPath') . "files" . DS . $this->Session->read('User.company_id') . DS . "SavedReports" . DS . str_replace(" ", "", $this->Session->read('User.name')) . DS . date('d-M-Y') . DS . $openModel, 0777);
            $path = Configure::read('MediaPath') . "files" . DS . $this->Session->read('User.company_id') . DS . "SavedReports" . DS . str_replace(" ", "", $this->Session->read('User.name')) . DS . date('d-M-Y') . DS . $openModel;
            $objWriter->save(Configure::read('MediaPath') . "files" . DS . $this->Session->read('User.company_id') . DS . "SavedReports" . DS . str_replace(" ", "", $this->Session->read('User.name')) . DS . date('d-M-Y') . DS . $openModel . DS . $fileName);
            if ($this->request->data['file_uploads']['to'] != '') {
                    $email =$this->request->data['file_uploads']['to'];
                    $message = $this->request->data['file_uploads']['description'];
                }
                if ($email) {
                    try{
                        App::uses('CakeEmail', 'Network/Email');
                        if($this->Session->read('User.is_smtp') == '1')
                        {
                            $EmailConfig = new CakeEmail("smtp");	
                        }else if($this->Session->read('User.is_smtp') == '0'){
                            $EmailConfig = new CakeEmail("default");
                        }
                        $EmailConfig->to($email);
                        $EmailConfig->subject('FlinkISO: Excel Report of '.$openModel);
                        $EmailConfig->template('excel_attachment');
                        $EmailConfig->viewVars(array('department' => $openModel,'message'=>$message));
                        $EmailConfig->attachments(array($path . DS . $fileName));
                        $EmailConfig->emailFormat('html');
                        $EmailConfig->send();
                         $this->Session->setFlash(__('Report sent succeefully.', true), 'smtp');
                    } catch(Exception $e) {
                         $this->Session->setFlash(__('Failed to send email. Please check smtp details.', true), 'smtp');

                    }

                }
                $file = new File($path . DS . $fileName);
                $file->delete();
                $this->Session->setFlash("File sent succesfully.");
                $controller = Inflector::pluralize($openModel);
                $controller = Inflector::underscore($controller);
                $this->redirect(array('controller'=>$controller,'action'=>'index'));


        }
    }
    
    public function choose() {
        $this->set('controller_name', $this->request->params['pass'][1]);
        $sysTableId = $this->_get_system_table($this->request->params['pass'][1]);
        $conditions = array(
            'FileUpload.system_table_id' => $sysTableId,
            'FileUpload.created_by' => $this->Session->read('User.id'),
            'FileUpload.file_status' => 1,	   
            array('OR'=> array(array('FileUpload.file_type' => 'xls'), array('FileUpload.file_type' => 'xlsx')))
        );
        $this->paginate = array(
            'limit' => 10,
            'order' => array(
                'FileUpload.sr_no' => 'DESC'
            ),
            'conditions' => array(
                $conditions
            )
        );
        $this->FileUpload->recursive = 0;
        $this->set('fileUploads', $this->paginate());
    }

    public function beforeFilter() {
        session_start();
        $this->Session->write('User', $_SESSION['User']);

        if(!$this->Session->read('User.id')){
      $this->Session->setFlash(__('Access Denied'), 'default', array('class'=>'alert-danger'));
	    $this->redirect(array('controller' => 'users','action' => 'access_denied'));
        }
        if ($this->action != 'index' && $this->action != 'pending_view' && $this->action != 'add_new_file' && $this->action != 'share' && $this->action != 'lists' && $this->action != 'save_imported_data' && $this->action != 'show_file' && $this->action != 'index' && $this->action != 'approval_ajax' && $this->action != 'file_advanced_search' && $this->action !='view_media_file' && $this->action != 'related_files') {
            $ref = str_replace(Router::url('/', true), '', $this->referer());
            $ref = explode('/', $ref);
            $newModel = Inflector::Classify($ref[0]);
            $this->loadModel('SystemTable');
            $this->SystemTable->recursive = - 1;
            $systemTableName = $this->SystemTable->find('first', array(
                'conditions' => array(
                    'SystemTable.system_name' => $ref[0]
                )
            ));
            if(isset($newModel) && $newModel != ''){
                $this->loadModel($newModel);
                $mainSheet = $this->$newModel->schema();
                unset($mainSheet['id']);
                unset($mainSheet['sr_no']);
                unset($mainSheet['publish']);
                unset($mainSheet['soft_delete']);
                unset($mainSheet['branchid']);
                unset($mainSheet['departmentid']);
                unset($mainSheet['modified_by']);
                unset($mainSheet['created_by']);
                unset($mainSheet['created']);
                unset($mainSheet['modified']);
                unset($mainSheet['modified_by']);
                unset($mainSheet['system_table_id']);
                unset($mainSheet['master_list_of_format_id']);
                unset($mainSheet['company_id']);
                foreach (array_keys($mainSheet) as $cleanData):
                    $finalFields[str_replace(' Id', '', Inflector::Humanize($cleanData))] = $cleanData;
                endforeach;
                $this->set('tableFields', $finalFields);
                $prepareOtherTables = $this->$newModel->belongsTo;
                unset($prepareOtherTables['SystemTable']);
                unset($prepareOtherTables['MasterListOfFormat']);
                unset($prepareOtherTables['Timeline']);
                $otherSheet = null;
                $newSheet = null;
                foreach ($prepareOtherTables as $associatedTables):
                    $associatedModel = $associatedTables['className'];
                    $this->loadModel($associatedModel);
                    $otherSheet = $this->$associatedModel->schema();
                    unset($otherSheet['id']);
                    unset($otherSheet['sr_no']);
                    unset($otherSheet['publish']);
                    unset($otherSheet['soft_delete']);
                    unset($otherSheet['branchid']);
                    unset($otherSheet['departmentid']);
                    unset($otherSheet['modified_by']);
                    unset($otherSheet['created_by']);
                    unset($otherSheet['created']);
                    unset($otherSheet['modified']);
                    unset($otherSheet['modified_by']);
                    unset($otherSheet['system_table_id']);
                    unset($otherSheet['master_list_of_format_id']);
                    unset($otherSheet['company_id']);
                    $newSheet[$associatedModel] = array_keys($otherSheet);
                endforeach;
                $this->set('tableFields_associations', $newSheet);
            }
        }
    }

    public  function beforeRender($model, $id, $edit=false) {

        if ($this->action != 'index' && $this->action != 'pending_view' && $this->action != 'add_new_file' && $this->action != 'view'  && $this->action != 'share' && $this->action != 'lists' && $this->action != 'save_imported_data' && $this->action != 'show_file' && $this->action != 'index' && $this->action != 'approval_ajax' && $this->action != 'file_advanced_search' && $this->action !='view_media_file' && $this->action != 'related_files') {
            $ref = str_replace(Router::url('/', true), '', $this->referer());
            $ref = explode('/', $ref);
            $newModel = Inflector::Classify($ref[0]);
            $this->loadModel('SystemTable');
            $this->SystemTable->recursive = 1;
            $systemTableName = $this->SystemTable->find('first', array(
                'conditions' => array(
                    'SystemTable.system_name' => $ref[0]
                )
            ));
            if($systemTableName){
                $mainSheet = $this->$newModel->schema();
                unset($mainSheet['id']);
                unset($mainSheet['sr_no']);
                unset($mainSheet['publish']);
                unset($mainSheet['soft_delete']);
                unset($mainSheet['branchid']);
                unset($mainSheet['departmentid']);
                unset($mainSheet['modified_by']);
                unset($mainSheet['created_by']);
                unset($mainSheet['created']);
                unset($mainSheet['modified']);
                unset($mainSheet['modified_by']);
                unset($mainSheet['system_table_id']);
                unset($mainSheet['master_list_of_format_id']);
                unset($mainSheet['company_id']);
                foreach (array_keys($mainSheet) as $cleanData):
                    $finalFields[str_replace(' Id', '', Inflector::Humanize($cleanData))] = $cleanData;
                endforeach;
                $this->set('tableFields', $finalFields);
                $prepareOtherTables = $this->$newModel->belongsTo;
                unset($prepareOtherTables['SystemTable']);
                unset($prepareOtherTables['MasterListOfFormat']);
                foreach ($prepareOtherTables as $associatedTables):
                    $this->loadModel($associatedTables['className']);
                    $otherSheet = $this->$associatedTables['className']->schema();
                    unset($otherSheet['id']);
                    unset($otherSheet['sr_no']);
                    unset($otherSheet['publish']);
                    unset($otherSheet['soft_delete']);
                    unset($otherSheet['branchid']);
                    unset($otherSheet['departmentid']);
                    unset($otherSheet['modified_by']);
                    unset($otherSheet['created_by']);
                    unset($otherSheet['created']);
                    unset($otherSheet['modified']);
                    unset($otherSheet['modified_by']);
                    unset($otherSheet['system_table_id']);
                    unset($otherSheet['master_list_of_format_id']);
                    unset($otherSheet['company_id']);
                    $sheet[$associatedTables['className']] = array_keys($otherSheet);
                endforeach;
                $this->set('tableFields_associations', $sheet);    
            }
            
        }
    }

    public function import_data($systemTableId = null, $userId = null, $fileName = null) {


        $ref = str_replace(Router::url('/', true), '', $this->referer());
        $ref = explode('/', $ref);
        $ref = Inflector::tableize($ref[0]);
        $this->set('controller_name', $ref);
        $newKeys = $this->viewVars['tableFields_associations'];
        $newTableFields = $this->viewVars['tableFields'];



        // open file and get data in $spreedSheetData

        $newModel = Inflector::Classify($ref);
        $fileToInclude = 'files'.DS .'import' . DS . $this->request->params['pass'][1] . DS . Inflector::tableize($newModel) . DS . $fileName;
        if (file_exists($fileToInclude)) {



            $spreedSheetData = new Spreadsheet_Excel_Reader();
            $spreedSheetData->read($fileToInclude);

            // create array to compare

            foreach ($newTableFields as $tableFieldkey => $tableFieldvalue):
                $valuesFromSchema[] = $tableFieldkey;
            endforeach;

            // ends create
            // read the 2nd row of the file which will contain file names

            $findMissingFields = array_diff($valuesFromSchema, $spreedSheetData->sheets[0]['cells'][2]);
            if (sizeof($findMissingFields) > 0) {
                $getFileName = explode(".", $fileName);
                $this->set('missing_fields', $findMissingFields);
                $getFileDetails = $this->FileUpload->find('first', array(
                    'conditions' => array(
                        'FileUpload.user_id' => $userId,
                        'FileUpload.system_table_id' => $this->_get_system_table($systemTableId),
                        'FileUpload.file_details' => $getFileName[0],
                        'FileUpload.file_type' => $getFileName[1],
                    ),
                    'fields' => array('FileUpload.id', 'FileUpload.file_details'),
                    'recursive' => -1
                ));

                if ($getFileDetails) {
                    $this->FileUpload->read(null, $getFileDetails['FileUpload']['id']);
                    $this->FileUpload->set(array('file_status' => 0, 'result' => 'File is deleted due to mismatching cells'));
                    $this->FileUpload->save();
                }
                unlink($fileToInclude);
            } else {
                foreach ($newKeys as $key => $newKey):
                    $modelFound[] = array_search($key, $spreedSheetData->sheets[0]['cells'][2]);
                endforeach;
                $rowCount = $spreedSheetData->sheets[0]['numRows'];
                for ($i = 3; $i <= $rowCount; $i++) {
                    foreach ($modelFound as $searchData):
                        if ($searchData)
                            $entriesFound[$spreedSheetData->sheets[0]['cells'][2][$searchData]][] = $spreedSheetData->sheets[0]['cells'][$i][$searchData];
                    endforeach;
                }
                foreach ($entriesFound as $modelKeys => $entries):
                    $uniqueEntries[$modelKeys] = array_unique($entries);
                endforeach;

                // now check if any of these records are not in db . if they are not .. cancel import
                if ($entryValues != null || $entryValues != '') {
                    foreach ($uniqueEntries as $entries => $entryValues):
                        $this->loadModel($entries);
                        $this->$entries->recursive = - 1;
                        foreach ($entryValues as $searchValue):
                            $findNames = NULL;
                            $findNames = $this->$entries->find('first', array(
                                'fields' => array(
                                    'name'
                                ),
                                'conditions' => array(
                                    $entries . '.name' => $searchValue
                                )
                            ));
                            if (empty($findNames)) {
                                $missingDataFields[$entries][] = $searchValue;
                            }

                        endforeach;
                    endforeach;
                }
            }

            foreach ($spreedSheetData->sheets[0]['cells'][2] as $xlsFields):
                $fieldsFromXls[$xlsFields] = $xlsFields;
            endforeach;
            $this->set('missing_data_fields', $missingDataFields);
            $this->set('required', $findNames);
            $this->set('fields_from_xls', $fieldsFromXls);
        }else {
            $getFileName = explode(".", $fileName);

            $this->set('missing_fields', $findMissingFields);
            $getFileDetails = $this->FileUpload->find('first', array(
                'conditions' => array(
                    'FileUpload.user_id' => $userId,
                    'FileUpload.system_table_id' => $this->_get_system_table($systemTableId),
                    'FileUpload.file_details' => $getFileName[0],
                    'FileUpload.file_type' => $getFileName[1],
                )
            ));

            if ($getFileDetails) {
                $this->FileUpload->read(null, $getFileDetails['FileUpload']['id']);
                $this->FileUpload->set(array('file_status' => 0, 'result' => 'File is deleted due to mismatching cells'));
                $this->FileUpload->save();
            }
            $this->set('missing_file', true);
        }
    }

    public function compare_data() {

        $spreedSheetData = new Spreadsheet_Excel_Reader();
        $spreedSheetData->read($fileToInclude);
        foreach ($spreedSheetData->sheets[0]['cells'][2] as $xlsFields):
            $fieldsFromXls[$xlsFields] = $xlsFields;
        endforeach;
        $this->set('fields_from_xls', $fieldsFromXls);
    }
    public function _excelToPHP($dateValue = 0, $ExcelBaseDate=0) {
        if ($ExcelBaseDate == 0) {
            $myExcelBaseDate = 25569;
            //  Adjust for the spurious 29-Feb-1900 (Day 60)
            if ($dateValue < 60) {
                --$myExcelBaseDate;
            }
        } else {
            $myExcelBaseDate = 24107;
        }

        // Perform conversion
        if ($dateValue >= 1) {
            $utcDays = $dateValue - $myExcelBaseDate;
            $returnValue = round($utcDays * 86400);
            if (($returnValue <= PHP_INT_MAX) && ($returnValue >= -PHP_INT_MAX)) {
                $returnValue = (integer) $returnValue;
            }
        } else {
            $hours = round($dateValue * 24);
            $mins = round($dateValue * 1440) - round($hours * 60);
            $secs = round($dateValue * 86400) - round($hours * 3600) - round($mins * 60);
            $returnValue = (integer) gmmktime($hours, $mins, $secs);
        }

        // Return
        return $returnValue;
    }
    public function save_imported_data() {
        $this->data['FileUpload']['fileDetails'] = str_replace('\\', DS, $this->data['FileUpload']['fileDetails']);
        $getName = explode( DS, $this->data['FileUpload']['fileDetails']);
        
        $getTableName = $getName[3];
        $fileToInclude = Configure::read('MediaPath').'files' . DS . $this->data['FileUpload']['company_id'] . DS . 'import' . DS . $getName[2] . DS . $getTableName . DS . $getName[4];
        $spreedSheetData = new Spreadsheet_Excel_Reader();
        $spreedSheetData->read($fileToInclude);
        $openModel = Inflector::Classify($getTableName);
        $this->loadModel($openModel);
        $assciatedTables = $this->$openModel->belongsTo;
        $customArray = $this->$openModel->customArray;
        
        $aCount = 0;
        for ($x = 3; $x <= $spreedSheetData->sheets[0]['numRows']; $x++) {
            $c = 1;

            if(array_filter($spreedSheetData->sheets[0]['cells'][$x])) {
                foreach ($spreedSheetData->sheets[0]['cells'][2] as $dataFieldName):

                if (in_array(str_replace(" ", '', $dataFieldName), array_keys(str_replace(" ", '', $assciatedTables)), false) === true) {
                    $data[$aCount][$openModel][$assciatedTables[Inflector::Classify($dataFieldName)]['foreignKey']] = $this->_get_forign_key_value($assciatedTables[Inflector::Classify($dataFieldName)][className], $spreedSheetData->sheets[0]['cells'][$x][$c]);
                }else

                    if(in_array(Inflector::underscore(Inflector::Classify($dataFieldName)), array_keys($customArray))){
                        $data[$aCount][$openModel][(str_replace(' ', '_', Inflector::underscore($dataFieldName)))] = array_search($spreedSheetData->sheets[0]['cells'][$x][$c], $customArray[Inflector::underscore(Inflector::Classify($dataFieldName))]);
                    }

                    else {
                        if ((strpos($dataFieldName, 'Date') === false)) {
                            $data[$aCount][$openModel][(str_replace(' ', '_', Inflector::underscore($dataFieldName)))] = str_replace('?', '', utf8_decode($spreedSheetData->sheets[0]['cells'][$x][$c]));
                        } else {
                            
                            $excelDate = $spreedSheetData->sheets[0]['cells'][$x][$c];
                            $timestamp = $this->_excelToPHP($excelDate);
                            $mysqlDate = date('Y-m-d', $timestamp);
                            
                            $data[$aCount][$openModel][(str_replace(' ', '_', Inflector::underscore($dataFieldName)))] = $mysqlDate;
                        }
                    }

                $c++;
            endforeach;
	    }
            $aCount++;
        }

        $this->set('came_from', $getName);
        $result = $this->_add_imported_data($data, $openModel, $this->data['FileUpload']['company_id']);
        if(in_array(false,$result,true)){
            $this->Session->setFlash(__('Error! There is a error uploading some of your records. Please try again.'));
        }else{
            $this->Session->setFlash(__('Success!Data is being imported successfully.'));
        }        
        $this->redirect(array('controller'=> $getTableName,'action' => 'index'));
    }

    public function _add_imported_data($importedData = null, $importModel, $comapanyId = null) {
        error_reporting(0);
        $importModel = str_replace(' ', '', $importModel);
        $this->loadModel($importModel);
        if (!$this->Session->read('User.id'))
            $this->redirect(array(
                'controller' => 'user',
                'action' => 'login'
            ));
        foreach ($importedData as $newdata):
            $this->$importModel->create();
            $newdata[$importModel]['branchid'] = $this->Session->read('User.branch_id');
            $newdata[$importModel]['departmentid'] = $this->Session->read('User.department_id');
            $newdata[$importModel]['created_by'] = $this->Session->read('User.id');
            $newdata[$importModel]['modified_by'] = $this->Session->read('User.id');
            $newdata[$importModel]['created'] = date('Y-m-d h:i:s');
            $newdata[$importModel]['modified'] = date('Y-m-d h:i:s');
            $newdata[$importModel]['publish'] = 0;
            $newdata[$importModel]['soft_delete'] = 0;
            $newdata[$importModel]['company_id'] = $comapanyId;
            unset($newdata[$importModel]['sr_no']);


            // check if record already exists
            $fds = array($newdata[$importModel]['name'],$newdata[$importModel]['title']);
            $recex = $this->$importModel->find('list',array('conditions'=>array($importModel.'.'.$this->$importModel->displayField => $fds)));
            debug($recex);
            if(count($recex) > 0){
                $alreadyExists[] = $newdata[$importModel];
                continue;
            }else{
                try{
                    $this->$importModel->save($newdata[$importModel],false);
                }catch(Exception $e){
                    $alreadyExists[] = $newdata[$importModel];
                    continue;
                }
            }

        endforeach;
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);
    
        if($alreadyExists){
            $headers = array_keys($this->$importModel->schema());
            unset($headers[0]);
            unset($headers[1]);
            foreach ($headers as $header) {
                $newheader[] = Inflector::Humanize($header);
            }
            // create a file pointer connected to the output stream
            $path = WWW_ROOT . 'files';
            chmod($path, 0777);
            $path = WWW_ROOT . 'files'. DS .'import_errors';
            chmod($path, 0777);
            mkdir($path);

            $path = WWW_ROOT . 'files'. DS .'import_errors' . DS . $this->Session->read('User.username');
            chmod($path, 0777);
            mkdir($path);

            $path = WWW_ROOT . 'files'. DS .'import_errors' . DS . $this->Session->read('User.username') . DS . $importModel;
            chmod($path, 0777);
            mkdir($path);
            $path = $path . DS . date('YMDHIS').'failedrecords.csv';
            
            try{
                $file = fopen($path, 'w');
            }catch(Exception $e){
                
            }
            
            fputcsv($file, $newheader);
            // output each row of the data
            foreach ($alreadyExists as $row)
            {
                $dd = '';
                foreach ($row as $key => $value) {
                    $dd[] = $value;
                }                
                fputcsv($file, $dd);
            }
            
            fclose($file);            
        }
        return $success;
    }

    public function _get_forign_key_value($modelName, $recordName) {

        $modelName = str_replace(' ', '', $modelName);
        $this->loadModel($modelName);

        $this->$modelName->recursive = - 1;
        $keyValue = $this->$modelName->find('first', array(
            'conditions' => array(
                $modelName . '.' . $this->$modelName->displayField => $recordName
            ),
            'fields' => array(
                'id'
            )
        ));
        if ($keyValue[$modelName]['id'])
            return $keyValue[$modelName]['id'];
        else
            return null;
    }

    public function show_file() {
        $this->layout = "ajax";
    }

    public function create_header($newModelName = null, $recs = null) {
        $this->loadModel('SystemTable');
        $systemTableIds = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => Inflector::tableize($newModelName)
            ),
            'fields' => array(
                'SystemTable.id'
            ),
            'recursive' => - 1
        ));

        if (isset($systemTableIds)) {
            if (isset($systemTableIds['SystemTable']['id']) && $systemTableIds['SystemTable']['id'] != null) {
                $this->loadModel('MasterListOfFormat');
                $formatHeader = $this->MasterListOfFormat->find('first', array(
                    'conditions' => array(
                        'MasterListOfFormat.system_table_id' => $systemTableIds['SystemTable']['id']
                    ),
                    'fields' => array(
                        'MasterListOfFormat.title',
                        'MasterListOfFormat.document_number',
                        'MasterListOfFormat.issue_number',
                        'MasterListOfFormat.revision_number',
                        'MasterListOfFormat.revision_date',
                        'PreparedBy.name',
                        'ApprovedBy.name',
                        'Department.name'
                    ),
                    'recursive' => 0
                ));
                if (isset($formatHeader) && $formatHeader != null) {
                    return $formatHeader;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function approval_ajax(){
                    $this->loadModel('SystemTable');
                    $approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'approvals')));
                    $approval_files = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$approval_system_table['SystemTable']['id'])));
                    $this->set('approval_files',$approval_files);
                    $this->set('creator',$this->request->params['pass'][1]);
            }
            
    public function approval_ajax_file_count(){
                    $this->loadModel('SystemTable');
                    $approval_system_table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'approvals')));
                    $approval_files = $this->FileUpload->find('count',array('conditions'=>array('FileUpload.archived'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$approval_system_table['SystemTable']['id'])));
                    $this->set('approval_files_count',$approval_files);
            }

    public function related_files(){        
        $this->layout = 'ajax';        
        $mclass = $this->request->params['pass'][0];
        $id = $this->request->params['pass'][1];
        $this->loadModel($mclass);
        $data = $this->$mclass->find('first',array('conditions'=>array($mclass.'.id'=>$id)));
        unset($data[$mclass]);
        unset($data['Branch']);
        unset($data['Department']);
        unset($data['Schedule']);        
        unset($data['ParentId']);
        unset($data['ApprovedBy']);
        unset($data['PreparedBy']);
        unset($data['SystemTable']);
        unset($data['MasterListOfFormat']);
        unset($data['BranchIds']);
        unset($data['DepartmentId']);
        unset($data['Company']);
        unset($data['StatusUserId']);
        unset($data['CreatedBy']);
        unset($data['ModifiedBy']);
        unset($data['Language']);
        unset($data['DepartmentIds']);
        unset($data['FileUpload']);
        
        foreach ($data as $key => $value) {
            if(isset($value[0])){
                foreach($value as $rec){
                   $every_file[$key][$rec['id']] = $this->FileUpload->find('all',array(
                        'fields'=>array('FileUpload.id','FileUpload.file_dir','FileUpload.file_details','FileUpload.file_type',
                            'FileUpload.id','FileUpload.record','FileUpload.system_table_id','CreatedBy.name','FileUpload.created'),
                        'conditions' => array('FileUpload.record'=>$rec['id'])
                    ));                   
                    $this->loadModel('Approval');
                    $approvals = $this->Approval->find('all',array(
                            'fields'=>array('Approval.id'),
                            'conditions'=>array('Approval.model_name'=>$key,'Approval.record'=>$rec['id'])
                        ));
                    foreach ($approvals as $approval) {
                        $approval_files = $this->FileUpload->find('all',array(
                        'fields'=>array('FileUpload.id','FileUpload.file_dir','FileUpload.file_details','FileUpload.file_type',
                            'FileUpload.id','FileUpload.record','FileUpload.system_table_id','CreatedBy.name','FileUpload.created'),
                        'conditions' => array('FileUpload.record'=>$approval['Approval']['id'])
                    ));                        
                        foreach($approval_files as $approval_file){
                            $final_approval_files[$key][$approval_file['FileUpload']['id']] = $approval_file;
                        }                       
                    }
                 }  
            }else{
                
                if(isset($value['id'])){
                $every_file[$key] = $this->FileUpload->find('all',array(
                        'fields'=>array('FileUpload.id','FileUpload.file_dir','FileUpload.file_details','FileUpload.file_type',
                            'FileUpload.id','FileUpload.record','FileUpload.system_table_id','CreatedBy.name','FileUpload.created'),
                        'conditions' => array('FileUpload.record'=>$value['id'])
                ));
                
                $this->loadModel('Approval');
                    $approvals = $this->Approval->find('all',array(
                            'fields'=>array('Approval.id'),
                            'conditions'=>array('Approval.model_name'=>$key,'Approval.record'=>$value['id'])
                        ));
                    foreach ($approvals as $approval) {
                        $approval_files = $this->FileUpload->find('all',array(
                        'fields'=>array('FileUpload.id','FileUpload.file_dir','FileUpload.file_details','FileUpload.file_type',
                            'FileUpload.id','FileUpload.record','FileUpload.system_table_id','CreatedBy.name','FileUpload.created'),
                        'conditions' => array('FileUpload.record'=>$approval['Approval']['id'])
                    ));                        
                        foreach($approval_files as $approval_file){
                            $final_approval_files[$key][$approval_file['FileUpload']['id']] = $approval_file;
                        }                        
                    }
                }    
            }
        }        
        $this->set('related_uploaded_files',$every_file); 
        $this->set('final_approval_files',$final_approval_files); 
    }
    
    /**
     * delete_file method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete_file($id = null, $producttype = null) {

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        $permissions = $this->FileUpload->FileShare->find('count',array('conditions'=>array(
             'OR'=>array('FileShare.users LIKE ' => '%' . $this->Session->read('User.id') . '%', 'FileShare.everyone'=>1),
                'FileShare.file_upload_id' => $id
        )));
        if($permissions == 0){
            $this->Session->setFlash(__('Access Denied: You do not have permission to delete this file'), 'default', array('class'=>'alert-danger'));
           // throw new NotFoundException(__('You do not have permission to delete this file.'));
            $this->redirect($this->referer());
        }

        $file_data = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id' =>$id)));
        
        $file_dir = explode("-ver-",$file_data['FileUpload']['file_details']);
        $check_versions = $this->FileUpload->find('all',array('conditions'=>array(
			'FileUpload.system_table_id'=>$file_data['FileUpload']['system_table_id'],
			'FileUpload.record'=>$file_data['FileUpload']['record'],
		//	'FileUpload.id <> ' =>$file_data['FileUpload']['id'],
			'FileUpload.file_dir like' => '%'.$file_dir[0]."-ver-%".$file_data['FileUpload']['file_type']
		)));
        $this->set('revisions',$check_versions);
        $this->set('producttype',$producttype);
		

		$systemTable = $this->FileUpload->SystemTable->find('first', array('conditions' => array('SystemTable.publish' => 1,'SystemTable.soft_delete' => 0, 'SystemTable.id'=>$file_data['FileUpload']['system_table_id'])));
		$systemTables = $this->FileUpload->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1,'SystemTable.soft_delete' => 0)));
		$users = $this->FileUpload->User->find('list', array('conditions' => array('User.publish' => 1,'User.soft_delete' => 0)));
		
		$masterListOfFormats = $this->FileUpload->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1,'MasterListOfFormat.soft_delete' => 0)));
		$PublishedEmployeeList = $this->_get_employee_list();
		$this->set(compact('systemTables', 'users', 'userSessions', 'masterListOfFormats','PublishedEmployeeList','systemTable'));
		
		$count = $this->FileUpload->find('count');
		$published = $this->FileUpload->find('count', array('conditions' => array('FileUpload.publish' => 1)));
		$unpublished = $this->FileUpload->find('count', array('conditions' => array('FileUpload.publish' => 0)));
		$this->set(compact('count', 'published', 'unpublished'));
    }
    
    /**
	 * purge method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function purge_file($id = null, $modelName,  $producttype = null) {
            if($modelName != 'dashboards'){
                $modelname_rev = $modelName;
                $modelName = Inflector::camelize(Inflector::singularize($modelName)); 
                $this->loadModel($modelName);
        		$controller = Inflector::variable(Inflector::pluralize($modelName));
        		$record = Inflector::underscore($modelName);
        		$record = Inflector::humanize($record);
        		$this->$modelName->id = $id;
                    }else{
                        $modelname_rev = $modelName;
                    }
        		$this->loadModel('Approval');
        		$this->loadModel('FileUpload');


		$approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
		$file_data = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id'=>$id)));

                $file_dir = explode("-ver-",$file_data['FileUpload']['file_details']);
                $check_versions = $this->FileUpload->find('first',array('conditions'=>array(
                                'FileUpload.system_table_id'=>$file_data['FileUpload']['system_table_id'],
                                'FileUpload.record'=>$file_data['FileUpload']['record'],
                        	'FileUpload.id <> ' =>$file_data['FileUpload']['id'],
                                'FileUpload.file_dir like' => '%'.$file_dir[0]."-ver-%".$file_data['FileUpload']['file_type']
                        ),'order' => array(
                'FileUpload.version' => 'DESC'
            )));
                
              
                $check_revs =  $this->FileUpload->find('all',array('conditions'=>array(
                                'FileUpload.system_table_id'=>$file_data['FileUpload']['system_table_id'],
                                'FileUpload.record'=>$file_data['FileUpload']['record'],
                        	'FileUpload.version > ' =>$file_data['FileUpload']['version'],
                                'FileUpload.file_dir like' => '%'.$file_dir[0]."-ver-%".$file_data['FileUpload']['file_type']
                        ), 'recursive' => - 1,  'order' => array(
                'FileUpload.version' => 'ASC'
            )));

		foreach($approves as $approve)
		{
			if(!($this->Approval->delete($approve['Approval']['id'], true)))
			{
                            $this->Session->setFlash(__('All selected value was not deleted from Approve'));
                            $this->redirect(array('action' => 'index'));
                        }
		}
                        $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS . $file_data['FileUpload']['file_dir'];
                          
                        if(!unlink($path)){
                            $this->Session->setFlash(__('All selected value was not deleted from Upload'));
                            $this->redirect(array('action' => 'delete_file', $file_data['FileUpload']['id'],$producttype ));
                        }else{
                            if(!($this->FileUpload->delete($file_data['FileUpload']['id'], true)))
                            {
                                $this->Session->setFlash(__('All selected value was not deleted from Upload'));
                               $this->redirect(array('action' => 'delete_file', $file_data['FileUpload']['id'],$producttype ));
                            }
                           
                            foreach($check_revs as $check_rev){
                                $file_from = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS . $check_rev['FileUpload']['file_dir'];
                                $old_file_name = $check_rev['FileUpload']['file_details'];
                                $file_name = explode("-ver-",$check_rev['FileUpload']['file_details'])[0];
                                $ver = $check_rev['FileUpload']['version'];
                                $new_ver = (int)$ver - 1;
                                $check_rev['FileUpload']['file_details'] = $file_name."-ver-".$new_ver;
                                
                                $file_dir = explode("-ver-",$check_rev['FileUpload']['file_dir'])[0];
                                $check_rev['FileUpload']['file_dir'] = $file_dir."-ver-".$new_ver.".".$check_rev['FileUpload']['file_type'];
                                $check_rev['FileUpload']['version'] = $new_ver;
                              
                                $this->FileUpload->save($check_rev);
                                $file_to = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS . $check_rev['FileUpload']['file_dir'];
                                rename($file_from,$file_to);
                                
                            }
                            
                            if(count($check_revs)){
                                $check_rev['FileUpload']['archived'] = 0;
                                $check_rev['FileUpload']['result'] = 'File uploaded';
                                $this->FileUpload->save($check_rev);
                                if($modelName == 'dashboards'){
                                    $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. "documents".DS.$check_rev['FileUpload']['record'].DS.".".$file_name;
                                }else{
                                    if($producttype!= null){
                                      $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$check_rev['FileUpload']['record'].DS.$producttype.DS.".".$file_name;
                                    }else{
                                        $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$check_rev['FileUpload']['record'].DS.".".$file_name; 
                                    }
                                }
                               
                               file_put_contents($path, $new_ver);  
                            }elseif(count($check_versions)){
                               
                                    $check_versions['FileUpload']['archived'] = 0;
                                    $check_versions['FileUpload']['result'] = 'File uploaded';
                                    $this->FileUpload->save($check_versions);
                                    
                                    $file_name = explode("-ver-",$check_versions['FileUpload']['file_details'])[0];
                                    $new_ver =   $check_versions['FileUpload']['version'];
                                    if($modelName == 'dashboards'){
                                        $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. "documents".DS.$check_versions['FileUpload']['record'].DS.".".$file_name;
                                    }else{
                                        if($producttype!= null){
                                             $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$check_versions['FileUpload']['record'].DS.$producttype.DS.".".$file_name; 
                                        } else{
                                        $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$check_versions['FileUpload']['record'].DS.".".$file_name; 
                                        }
                                    }
                                    
                                    file_put_contents($path, $new_ver); 
                               

                            }else{
                                 $file_name = explode("-ver-",$file_data['FileUpload']['file_details'])[0];
                                   if($modelName == 'dashboards'){
                                        $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. "documents".DS.$file_data['FileUpload']['record'].DS.".".$file_name;
                                    }else{
                                        if($producttype!= null){
                                              $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$file_data['FileUpload']['record'].DS.$producttype.DS.".".$file_name; 
                                        }else{
                                            $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$file_data['FileUpload']['record'].DS.".".$file_name; 
                                        }
                                    }
                                
                                    if(!unlink($path)){
                                        
                                        $this->Session->setFlash(__('Failed to delete revision file'));
                                        $this->redirect(array('action' => 'delete_file', $file_data['FileUpload']['id'],$producttype ));
                                    } 
                            }
                            $this->redirect(array('action' => 'delete_file', $file_data['FileUpload']['id'], $producttype ));
                        }
		
		
	}
    
 
 public function purge_all_files() {
    if ($_POST['data'][$this->name]['recs_selected'])$ids = explode('+', $_POST['data'][$this->name]['recs_selected']);
    if ($_POST['data'][$this->name]['modelName'])$modelName = $_POST['data'][$this->name]['modelName'];
    if ($_POST['data'][$this->name]['producttype'])$producttype = $_POST['data'][$this->name]['producttype'];

    if($modelName != 'dashboards'){
        $modelname_rev = $modelName;
        $modelName = Inflector::camelize(Inflector::singularize($modelName)); 
        $this->loadModel($modelName);
        $controller = Inflector::variable(Inflector::pluralize($modelName));
        $record = Inflector::underscore($modelName);
        $record = Inflector::humanize($record);
    }else{
        $modelname_rev = $modelName;
    }
    
    $this->loadModel('Approval');
    $this->loadModel('FileUpload');

    foreach($ids as $id){
        if (!empty($id)) {
            if($modelName != 'dashboards'){
                $this->$modelName->id = $id;
            }
		  
            $file_data = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id'=>$id)));
            $file_dir = explode("-ver-",$file_data['FileUpload']['file_details']);
            $check_versions = $this->FileUpload->find('first',array('conditions'=>array(
                            'FileUpload.system_table_id'=>$file_data['FileUpload']['system_table_id'],
                            'FileUpload.record'=>$file_data['FileUpload']['record'],
                            'FileUpload.id <> ' =>$file_data['FileUpload']['id'],
                            'FileUpload.file_dir like' => '%'.$file_dir[0]."-ver-%".$file_data['FileUpload']['file_type']
                        ),'order' => array(
                            'FileUpload.version' => 'DESC')));
                                
            $check_revs =  $this->FileUpload->find('all',array('conditions'=>array(
                            'FileUpload.system_table_id'=>$file_data['FileUpload']['system_table_id'],
                            'FileUpload.record'=>$file_data['FileUpload']['record'],
                            'FileUpload.version > ' =>$file_data['FileUpload']['version'],
                            'FileUpload.file_dir like' => '%'.$file_dir[0]."-ver-%".$file_data['FileUpload']['file_type']
                        ), 'recursive' => - 1,  'order' => array(
                            'FileUpload.version' => 'ASC')));

            $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS . $file_data['FileUpload']['file_dir'];
            if(!unlink($path)){
                $this->Session->setFlash(__('All selected value was not deleted from Upload'));
                $this->redirect(array('action' => 'delete_file', $file_data['FileUpload']['id'],$producttype ));
            }else{
                if(!($this->FileUpload->delete($file_data['FileUpload']['id'], true)))
                    {
                        $this->Session->setFlash(__('All selected value was not deleted from Upload'));
                        //$this->redirect(array('action' => 'delete_file', $file_data['FileUpload']['id'],$producttype ));
                    }
                foreach($check_revs as $check_rev){
                    $file_from = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS . $check_rev['FileUpload']['file_dir'];
                    $old_file_name = $check_rev['FileUpload']['file_details'];
                    $file_name = explode("-ver-",$check_rev['FileUpload']['file_details'])[0];
                    $ver = $check_rev['FileUpload']['version'];
                    $new_ver = (int)$ver - 1;
                    $check_rev['FileUpload']['file_details'] = $file_name."-ver-".$new_ver;
                    
                    $file_dir = explode("-ver-",$check_rev['FileUpload']['file_dir'])[0];
                    $check_rev['FileUpload']['file_dir'] = $file_dir."-ver-".$new_ver.".".$check_rev['FileUpload']['file_type'];
                    $check_rev['FileUpload']['version'] = $new_ver;
                  
                    $this->FileUpload->save($check_rev);
                    $file_to = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS . $check_rev['FileUpload']['file_dir'];
                    rename($file_from,$file_to);
                }
                if(count($check_revs)){
                    $check_rev['FileUpload']['archived'] = 0;
                    $check_rev['FileUpload']['result'] = 'File uploaded';
                    $this->FileUpload->save($check_rev);
                               
                    if($modelName == 'dashboards'){
                        $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. "documents".DS.$check_rev['FileUpload']['record'].DS.".".$file_name;
                    }else{
                        if($producttype!= null){
                            $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$check_rev['FileUpload']['record'].DS.$producttype.DS.".".$file_name; 
                        }else{
                            $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$check_rev['FileUpload']['record'].DS.".".$file_name; 
                        }
                    }
                    file_put_contents($path, $new_ver);  
                }elseif(count($check_versions)){
                        $check_versions['FileUpload']['archived'] = 0;
                        $check_versions['FileUpload']['result'] = 'File uploaded';
                        $this->FileUpload->save($check_versions);
                        $file_name = explode("-ver-",$check_versions['FileUpload']['file_details'])[0];
                        $new_ver =   $check_versions['FileUpload']['version'];
                        if($modelName == 'dashboards'){
                            $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. "documents".DS.$check_versions['FileUpload']['record'].DS.".".$file_name;
                        }else{
                             if($producttype!= null){
                                $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$check_versions['FileUpload']['record'].DS.$producttype.DS.".".$file_name; 
                            } else{
                                $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$check_versions['FileUpload']['record'].DS.".".$file_name; 
                            }
                        }
                        file_put_contents($path, $new_ver); 
                }else{
                     $file_name = explode("-ver-",$file_data['FileUpload']['file_details'])[0];
                       if($modelName == 'dashboards'){
                            $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. "documents".DS.$file_data['FileUpload']['record'].DS.".".$file_name;
                        }else{
                            if($producttype!= null){
                               $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$file_data['FileUpload']['record'].DS.$producttype.DS.".".$file_name;
                            }else{
                                $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS .'revisions'.DS. $modelname_rev.DS.$file_data['FileUpload']['record'].DS.".".$file_name; 
                            }    
                        }       
                        if(!unlink($path)){   
                            $this->Session->setFlash(__('Failed to delete revision file'));
                            $this->redirect(array('action' => 'delete_file', $file_data['FileUpload']['id'],$producttype ));
                        } 
                }
            
            }
		
        }

    } 
    $this->redirect(array('controller'=>$controller, 'action' => 'index' ));
}
    
    public function share(){
            $permissions = $this->FileUpload->FileShare->find('count',array('conditions'=>array(
                'FileShare.users LIKE ' => '%' . $this->Session->read('User.id') . '%',
                'FileShare.file_upload_id' => $this->request->params['pass'][0]
                )));
            
                $file_creator = $this->FileUpload->find('first',array(
                        'conditions'=>array('FileUpload.id'=>$this->request->params['pass'][0]),
                        'fields' => array('FileUpload.id','FileUpload.created_by','FileUpload.user_id'),
                        'recursive'=>-1
                    ));
                if($file_creator['FileUpload']['created_by'] != $this->Session->read('User.id')){
                    $permission = 0;
                    $this->set('permission','0');                        
                }else{
                    $permission = 1;
                    $this->set('permission','1');                    
                }
            if($permission == 0){
            
            }else{

                if ($this->request->is('post')) {
                    $document = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id'=>$this->request->params['pass'][0])));
                    $this->set('document',$document);
                    
                    $this->loadModel('FileShare');
                    $this->FileShare->deleteAll(array('FileShare.file_upload_id'=>$document['FileUpload']['id']),false);
                    foreach($this->request->data['FileUpload'] as $share){
                        if($share['Everyone'] != 1){
                            $data['FileShare']['file_upload_id'] = $document['FileUpload']['id'];
                            $data['FileShare']['branch_id'] = $share['branch_id'] ;
                            $data['FileShare']['everyone'] = 0 ;
                            $data['FileShare']['users'] = json_encode($share['user_id']);
                            $data['FileShare']['publish'] = $document['FileUpload']['publish'];
                            $data['FileShare']['soft_delete'] = $document['FileUpload']['soft_delete'];
                            $data['FileShare']['created_by'] = $document['FileUpload']['created_by'];                    
                            $data['FileShare']['modified_by'] = $document['FileUpload']['modified_by'];                    
                            $data['FileShare']['user_session_id'] = $this->Session->read('User.user_session_id');
                            $data['FileShare']['branchid'] = $this->Session->read('User.branch_id');
                            $data['FileShare']['departmentid'] = $this->Session->read('User.department_id') ;
                            $data['FileShare']['company_id'] = $this->Session->read('User.company_id');
                            $data['FileShare']['master_list_of_format_id'] = $document['FileUpload']['master_list_of_format_id'];
                            $this->FileShare->create();
                            $this->FileShare->save($data['FileShare'],false);
                            
                        }else{
                            $data['FileShare']['file_upload_id'] = $document['FileUpload']['id'];
                            $data['FileShare']['branch_id'] = $share['branch_id'] ;
                            $data['FileShare']['everyone'] = 1 ;
                            $data['FileShare']['users'] = NULL;
                            $data['FileShare']['publish'] = $document['FileUpload']['publish'];
                            $data['FileShare']['soft_delete'] = $document['FileUpload']['soft_delete'];
                            $data['FileShare']['created_by'] = $document['FileUpload']['created_by'];                    
                            $data['FileShare']['modified_by'] = $document['FileUpload']['modified_by'];                    
                            $data['FileShare']['user_session_id'] = $this->Session->read('User.user_session_id');
                            $data['FileShare']['branchid'] = $this->Session->read('User.branch_id');
                            $data['FileShare']['departmentid'] = $this->Session->read('User.department_id') ;
                            $data['FileShare']['company_id'] = $this->Session->read('User.company_id');
                            $data['FileShare']['master_list_of_format_id'] = $document['FileUpload']['master_list_of_format_id'];
                            $this->FileShare->create();
                            $this->FileShare->save($data['FileShare'],false);
                        }
                    }
                    echo "Permissions Saved.";
                    $this->_notify($document['FileUpload']['id']);
                }else{
                    $files = $this->FileUpload->FileShare->find('all',array(
                            'conditions'=>array('FileShare.file_upload_id' => $this->request->params['pass'][0]),
                            'fields'=>array('FileShare.id','FileShare.users','FileShare.everyone','FileShare.branch_id')
                        ));
                    
                    if($files){
                        foreach ($files as $file) {
                            $i = 0;
                            if($file['FileShare']['everyone']){
                                $this->loadModel('User');
                                 $temp_user = $this->User->find('list',array(
                                    'conditions'=>array(
                                        'User.publish'=>1,'User.soft_delete'=>0,
                                        'User.branch_id'=>$file['FileShare']['branch_id'])));

                                         foreach ($temp_user as $key=>$value) {
                                            $sel_users[$i] = $key;
                                            $i++;
                                        }                                        
                            }else{
                                $i = 0;
                                $temp_users = json_decode($file['FileShare']['users'],true);
                                foreach ($temp_users as $temp_user) {
                                    $sel_users[$i] = $temp_user;
                                    $i++;
                                }                                
                            }    
                                
                            
                        }
                    }
                    
                    $this->set('sel_users',$sel_users);
                }
                
                $all_branches = $this->FileUpload->BranchIds->find('list',array('conditions'=>array('BranchIds.publish'=>1,'BranchIds.soft_delete'=>0)));
                foreach ($all_branches as $id=>$name) {
                       $users = $this->FileUpload->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.branch_id'=>$id)));
                       $branches[$id] = array('Name'=>$name,'Users'=>$users);
                }        
                $this->set(compact('branches'));

        }

    }

    public function _notify($id = null){

        $shares = $this->FileUpload->FileShare->find('all',array(
            'fields'=>array('FileShare.id','FileShare.file_upload_id','FileShare.users','FileShare.everyone','FileShare.branch_id'),
            'conditions'=>array('FileShare.file_upload_id'=>$id),
            'recursive'=>-1,
            ));
        $this->loadModel('Employee');
        $this->loadModel('User');
        
        foreach ($shares as $share) {
            if($share['FileShare']['everyone'] == 1 ){
                $employees = $this->Employee->find('list',array('fields'=>array( 'Employee.office_email','Employee.personal_email'),array('conditions'=>array('Employee.branch_id'=>$share['FileShare']['branch_id']))));
                foreach ($employees as $key => $value) {
                    if(empty($key)){
                        $emails[] = $value;
                    }else{
                        $emails[] = $key;
                    }
                }
            }else{
                foreach (json_decode($share['FileShare']['users'],true) as $user) {
                    $users = $this->User->find('list',array('fields'=>array('User.id','User.employee_id'),'conditions'=>array('User.id'=>$user)));
                    // print_r(array_values($users));
                    $employees = $this->Employee->find('list',array('fields'=>array('Employee.office_email','Employee.personal_email'),'conditions'=>array('Employee.id'=>array_values($users))));
                    foreach ($employees as $key => $value) {
                        if(empty($key)){
                            $emails[] = $value;
                        }else{
                            $emails[] = $key;
                        }
                    } 
                }
                
            }
        }
        $this->_sendEmail($emails,$share['FileShare']['file_upload_id']);        
    }

    public function _sendEmail($to = null, $id = null){
        echo "Sending Emails ... ";
        $content = "<p>A new file is shared with you. Login to FlinkISO Application to view the file.</p>";
        try{
            
            if(Configure::read('evnt') == 'Dev')$env = 'DEV';
            elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
            else $env = "";

            App::uses('CakeEmail', 'Network/Email');                        
            if($_SESSION['User']['is_smtp'] == 1)
                $EmailConfig = new CakeEmail("smtp");

            if($_SESSION['User']['is_smtp'] == 0)
                $EmailConfig = new CakeEmail("default");

            $EmailConfig->to($to);
            $EmailConfig->subject('New File Shared  With You');
            $EmailConfig->template('fileShare');
            $EmailConfig->viewVars(array(
                    'date_time' => date('Y-m-d h:i:s'),
                    'by_user'=>$_SESSION['User']['username'],
                    'employee'=>$_SESSION['User']['name'],
                    'branch' => $branch['Branch']['name'],
                    'department' => $department['Department']['name'],
                    'h2tag'=>'New File Shared',
                    'msg_content'=>$content,
                    'env' => $env, 'app_url' => FULL_BASE_URL
                ));
            $EmailConfig->emailFormat('html');
            $EmailConfig->send();
            echo "Emails Sent!";
        } catch(Exception $e) {
            echo "Failed Sending Emails";            
        }
    }

    public function _update_view($id = null ,$user_id = null,$type = null){
        session_start();
        $this->loadModel('FileView');
        $data['file_upload_id'] = $id;
        $data['user_id'] = $user_id;
        $data['type'] = $type;
        $data['user_sessions_id'] = $_SESSION['User']['user_session_id'];
        $data['publish'] = 1;
        $data['soft_delete'] = 0;
        $this->FileView->create();
        $this->FileView->save($data,false);
    }

    public function pending_view(){
        $files = $this->FileUpload->FileShare->find('all',array('conditions'=>array(
            'FileUpload.publish'=>1,'FileUpload.id !=' => NULL, 'FileUpload.soft_delete'=>0,'FileUpload.user_id <> '=>$_SESSION['User']['id'],
            'FileShare.branch_id'=>$_SESSION['User']['branch_id'],
            'or'=>array('FileShare.everyone'=>1,'FileShare.users LIKE '=> '%'.$_SESSION['User']['id'].'%')
            ),
        'order'=>array('FileUpload.created'=>'DESC'),
            'fields'=>array('FileShare.id','FileShare.branch_id','FileShare.everyone','FileShare.users','FileShare.publish','FileShare.soft_delete','FileShare.file_upload_id',
                'FileUpload.id', 'FileUpload.system_table_id','FileUpload.record','FileUpload.file_dir','FileUpload.file_details', 'FileUpload.file_type','FileUpload.user_id','FileUpload.file_status','FileUpload.archived','FileUpload.version','FileUpload.publish','FileUpload.soft_delete',
                'FileUpload.prepared_by','FileUpload.approved_by','FileUpload.created',
                )
        ));
        
        $this->loadModel('FileView');
        $this->loadModel('Employee');
        foreach ($files as $file) {
            $view = $this->FileView->find('count',array('conditions'=>array('FileView.file_upload_id'=>$file['FileUpload']['id'],'FileView.user_id'=>$_SESSION['User']['id'])));
            if($view == 0){
                $not_seen[] = $file;
            }            
        }
        $employees = $this->Employee->find('list');
        $this->set('employees',$employees);
        $this->set('not_seen',$not_seen);
        // exit;
    }

    public function add_new_file(){
        if ($this->request->is('post')) {
            
            if($this->request->data['FileUpload']['document']['error'] != 0){
                $this->Session->setFlash(__('Please add document.'));
                $this->redirect( Router::url( $this->referer(), true ) );
            }
            
            $name = explode(DS , $this->request->data['FileUpload']['file_dir']);
            $filename = $this->request->data['FileUpload']['document']['name'];
            $ext = $this->filename_extension($filename);
            $filename = str_replace($ext, '', $filename);
            // $filename=str_replace(' ','_',$filename);
            // $filename=str_replace('.','-',$filename);
            $filename=str_replace(' ','',$filename);    
            $filename=str_replace('.','',$filename);
            $filename=str_replace('ver-'. $this->request->data['FileUpload']['version'],'',$filename);

            
            $name[count($name)-1] = $filename;
            
            $file_name = NULL;
            foreach ($name as $key => $value) {
                $file_name .= $value . DS;
            }

            $file_name = rtrim($file_name, DS);
            $filename = $filename .'-ver-'. ($this->request->data['FileUpload']['version'] + 1) . '.'.$ext;
            $file_name_only = str_replace('.'.$ext, '', $filename);
            
            $file_name = $file_name .'ver-'. ($this->request->data['FileUpload']['version'] + 1) . '.'.$ext;
            $file_name_dir = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS . $file_name;
            
            $dir = str_replace($filename, '',$file_name_dir);
            $dir = Configure::read('MediaPath') . 'files' . DS . $dir;
            // mkdir($dir);
            
            chmod($dir, 0777);
            // chmod($file_name_dir,0777);
            
            $movefile = move_uploaded_file($this->request->data['FileUpload']['document']['tmp_name'],$file_name_dir); 
            if($movefile){
                $data['FileUpload']['system_table_id'] = $this->request->data['FileUpload']['system_table_id'];
                $data['FileUpload']['record'] = $this->request->data['FileUpload']['record'];
                $data['FileUpload']['file_dir'] = $file_name;
                $data['FileUpload']['file_details'] = $file_name_only;
                $data['FileUpload']['file_type'] = $ext;
                $data['FileUpload']['user_id'] = $this->request->data['FileUpload']['user_id'];
                $data['FileUpload']['prepared_by'] = $this->request->data['FileUpload']['prepared_by'];
                $data['FileUpload']['approved_by'] = $this->request->data['FileUpload']['approved_by'];
                $data['FileUpload']['file_status'] = 1;
                $data['FileUpload']['result'] = 'File Uploaded';
                $data['FileUpload']['archived'] = 0;
                $data['FileUpload']['publish'] = 1;
                $data['FileUpload']['soft_delete'] = 0;
                $data['FileUpload']['version'] = ($this->request->data['FileUpload']['version'] + 1);
                $data['FileUpload']['comment'] = 'Document is updated. Old file is archived';
                $data['FileUpload']['version'] = ($this->request->data['FileUpload']['version'] + 1);
                $data['FileUpload']['record_status'] = 0;
                $data['FileUpload']['company_id'] = $this->Session->read('User.company_id');
                $data['FileUpload']['created_by'] = $this->Session->read('User.id');
                $data['FileUpload']['created'] = date('Y-m-d H:is');
                $data['FileUpload']['user_session_id'] = $this->Session->read('User.user_session_id');
                $data['FileUpload']['branchid'] = $this->Session->read('User.branch_id');
                $data['FileUpload']['departmentid'] = $this->Session->read('User.department_id');
                $hash = Security::hash($fileUpload['FileUpload']['system_table_id'].$fileUpload['FileUpload']['record']); 
                $data['FileUpload']['file_key'] = $hash;
                if($data['FileUpload']['version']>1)$data['FileUpload']['version_key'] = $this->request->data['FileUpload']['id'];
                $this->FileUpload->create();
                $this->FileUpload->save($data,false);
                $redirect_id = $this->FileUpload->id;
                
                $this->FileUpload->read(null,$this->request->data['FileUpload']['id']);
                $this->FileUpload->set('archived',1);
                $this->FileUpload->save();

                $this->Session->setFlash(__('Document updated successfully.'));
                
                $this->redirect(array('controller'=>'file_uploads','action' => 'view',$redirect_id));
            }else{
                $this->Session->setFlash(__('Failed to uploaded document'));
            }
            exit;
        
        }
        
        $file_upload_id = $this->request->params['named']['file_upload_id'];
        $system_table_id = $this->request->params['named']['system_table_id'];
        $record = $this->request->params['named']['record'];
        $fileUpload = $this->FileUpload->find('first',array('conditions'=>array('FileUpload.id'=>$file_upload_id)));
        
        if(isset($this->request->params['named']['change_addition_deletion_request_id'])){
            $change_addition_deletion_request_id = $this->request->params['named']['change_addition_deletion_request_id'];
            $changeAdditionDeletionRequest = $this->FileUpload->ChangeAdditionDeletionRequest->find('first',array('conditions'=>array('ChangeAdditionDeletionRequest.id'=>$change_addition_deletion_request_id)));
        }else{
            $changeAdditionDeletionRequest = $this->FileUpload->ChangeAdditionDeletionRequest->find('first',array('conditions'=>array('ChangeAdditionDeletionRequest.id'=>$fileUpload['ChangeAdditionDeletionRequest'][0]['id'])));
        }
        
        $this->set(array('fileUpload'=>$fileUpload,'changeAdditionDeletionRequest'=>$changeAdditionDeletionRequest));
    }

    public function reset_old_files(){
        $allFiles = $this->FileUpload->find('all',array(
            'recursive'=>-1, 
            'fields'=>array('FileUpload.id', 'FileUpload.name','FileUpload.version','FileUpload.system_table_id','FileUpload.record','FileUpload.version'),
            'conditions'=>array('FileUpload.version'=>1)));
        
        foreach ($allFiles as $file) {
            $name = $file['FileUpload']['name'];
            $aname = $name;
            $name = split('-ver-', $name);
            $name = $name[0];
            
            
            $hash = Security::hash($file['FileUpload']['system_table_id'].$file['FileUpload']['record']); 
            $key = $file['FileUpload']['id'];
            $this->FileUpload->read(null,$file['FileUpload']['id']);
            $this->FileUpload->set(array('version_key'=>$key,'file_key'=>$hash));
            // $this->FileUpload->create();
            $this->FileUpload->save();    


            $oldFiles = $this->FileUpload->find('all',array(
                'recursive'=>-1, 
                'fields'=>array('FileUpload.id', 'FileUpload.name','FileUpload.version','FileUpload.sr_no','FileUpload.system_table_id','FileUpload.record','FileUpload.publish'), 
                'conditions'=>array(
                    // 'FileUpload.version'=>1, 
                    // 'FileUpload.publish'=>1,
                    // 'FileUpload.soft_delete'=>0, 
                    'FileUpload.version >'=>1, 
                    'FileUpload.system_table_id' => $file['FileUpload']['system_table_id'],
                    'FileUpload.record' => $file['FileUpload']['record'],
                    'FileUpload.name LIKE' => '%'. $name .'-ver-%')));
            debug($file);
            debug($oldFiles);

            if($oldFiles){
                
                // $hash = $key = NULL;
                foreach ($oldFiles as $oldFile) {
                        // save hash & key for old file
                        // $hash = Security::hash($oldFile['FileUpload']['system_table_id'].$oldFile['FileUpload']['record']);       
                        // $key = $oldFile['FileUpload']['id'];
                        $this->FileUpload->read(null,$oldFile['FileUpload']['id']);
                        $this->FileUpload->set(array('version_key'=>$key,'file_key'=>$hash));
                        // $this->FileUpload->create();
                        $this->FileUpload->save();                        
                    
                }                
                //save hash & key for new file
                // $hash = Security::hash($oldFile['FileUpload']['system_table_id'].$oldFile['FileUpload']['record']);       
                // $this->FileUpload->read(null,$file['FileUpload']['id']);
                // $this->FileUpload->set(array('version_key'=>$key,'file_key'=>$hash));
                // $this->FileUpload->create();
                // $this->FileUpload->save();    
            }else{
                 // save hash & key for new file
                
            }
            
            
        }
                
        exit;
    }
}

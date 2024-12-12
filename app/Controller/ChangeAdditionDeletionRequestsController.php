<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * ChangeAdditionDeletionRequests Controller
 *
 * @property ChangeAdditionDeletionRequest $ChangeAdditionDeletionRequest
 */
class ChangeAdditionDeletionRequestsController extends AppController {

    public function _get_system_table_id($controller = NULL) {

        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $controller)));
        return $systemTableId['SystemTable']['id'];
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {}
    public function index_filter() {
        $status = $this->request->params['pass'][0];
        if($status == 0)$document_change_accepted = 0;
        if($status == 1)$document_change_accepted = 1;
        if($status == 2)$document_change_accepted = 2;

        $conditions = $this->_check_request();
        $this->paginate = array('order' => array('ChangeAdditionDeletionRequest.modified' => 'DESC'), 'conditions' => array($conditions,'ChangeAdditionDeletionRequest.document_change_accepted'=>$document_change_accepted));

        $this->ChangeAdditionDeletionRequest->recursive = 0;
        $this->set('changeAdditionDeletionRequests', $this->paginate());

        $this->_get_count();
    }
    
    public function open_crs() {

        $conditions = $this->_check_request();
        $conditions = array($conditions,array('ChangeAdditionDeletionRequest.document_change_accepted'=>2));
        $this->paginate = array('order' => array('ChangeAdditionDeletionRequest.modified' => 'DESC'), 'conditions' => array($conditions));

        $this->ChangeAdditionDeletionRequest->recursive = 0;
        $this->set('changeAdditionDeletionRequests', $this->paginate());

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
                $SearchKeys[] = $this->request->query['keywords'];
            } else {
                $SearchKeys = explode(" ", $this->request->query['keywords']);
            }

            foreach ($SearchKeys as $SearchKey):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $searchArray[] = array('ChangeAdditionDeletionRequest.' . $search => $search_key);
                    else
                        $searchArray[] = array('ChangeAdditionDeletionRequest.' . $search . ' like ' => '%' . $search_key . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('ChangeAdditionDeletionRequest.branchid' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }
        if ($this->request->query['customer_id'] != -1) {
            $customerConditions[] = array('ChangeAdditionDeletionRequest.customer_id' => $this->request->query['customer_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $customerConditions);
            else
                $conditions[] = array('or' => $customerConditions);
        }

        //Request From Branch
        if ($this->request->query['branch_id'] != -1) {
            $Branch_conditions[] = array('ChangeAdditionDeletionRequest.branch_id' => $this->request->query['branch_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $Branch_conditions);
            else
                $conditions[] = array('or' => $Branch_conditions);
        }

        if ($this->request->query['department_id'] != -1) {
            $departmentConditions[] = array('ChangeAdditionDeletionRequest.department_id' => $this->request->query['department_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $departmentConditions);
            else
                $conditions[] = array('or' => $departmentConditions);
        }

        if ($this->request->query['employee_id'] != -1) {
            $employeeConditions[] = array('ChangeAdditionDeletionRequest.employee_id' => $this->request->query['employee_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $employeeConditions);
            else
                $conditions[] = array('or' => $employeeConditions);
        }

        if ($this->request->query['suggestion_form_id'] != -1) {
            $SuggestionFormConditions[] = array('ChangeAdditionDeletionRequest.suggestion_form_id' => $this->request->query['suggestion_form_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $SuggestionFormConditions);
            else
                $conditions[] = array('or' => $SuggestionFormConditions);
        }

        if ($this->request->query['others'] != '') {
            $otherConditions[] = array('ChangeAdditionDeletionRequest.others' => $this->request->query['others']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $otherConditions);
            else
                $conditions[] = array('or' => $otherConditions);
        }

        if ($this->request->query['master_list_of_format'] != -1) {
            $documentConditions[] = array('ChangeAdditionDeletionRequest.master_list_of_format' => $this->request->query['master_list_of_format']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $documentConditions);
            else
                $conditions[] = array('or' => $documentConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('ChangeAdditionDeletionRequest.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'ChangeAdditionDeletionRequest.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);



        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('ChangeAdditionDeletionRequest.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('ChangeAdditionDeletionRequest.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->ChangeAdditionDeletionRequest->recursive = 0;
        $this->paginate = array('order' => array('ChangeAdditionDeletionRequest.sr_no' => 'DESC'), 'conditions' => $conditions, 'ChangeAdditionDeletionRequest.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('changeAdditionDeletionRequests', $this->paginate());
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
        if (!$this->ChangeAdditionDeletionRequest->exists($id)) {
            throw new NotFoundException(__('Invalid change addition deletion request'));
        }
        $options = array('conditions' => array('ChangeAdditionDeletionRequest.' . $this->ChangeAdditionDeletionRequest->primaryKey => $id));
		 $cr = $this->ChangeAdditionDeletionRequest->find('first', $options);
        $this->set('changeAdditionDeletionRequest', $cr);
		 $this->document_revisions($cr['ChangeAdditionDeletionRequest']['master_list_of_format']);
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
       
        $existing_crs = $this->ChangeAdditionDeletionRequest->find('count',array(
                'recursive'=>-1,
                'fields'=>array('ChangeAdditionDeletionRequest.id','ChangeAdditionDeletionRequest.master_list_of_format','ChangeAdditionDeletionRequest.document_change_accepted'),
                'conditions'=>array('ChangeAdditionDeletionRequest.document_change_accepted'=>2, 'ChangeAdditionDeletionRequest.master_list_of_format'=>$this->request->params['pass'][0])
            ));
       
        if($existing_crs > 0){
            $this->Session->setFlash(__('This document is already under CR'));
            $this->redirect(array('controller'=>'master_list_of_formats' ,'action' => 'view', $this->request->params['pass'][0]));

        }

        $branches = $this->ChangeAdditionDeletionRequest->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $meetings = $this->ChangeAdditionDeletionRequest->Meeting->find('list', array('conditions' => array('Meeting.publish' => 1, 'Meeting.soft_delete' => 0)));
        $departments = $this->ChangeAdditionDeletionRequest->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $employees = $this->ChangeAdditionDeletionRequest->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $customers = $this->ChangeAdditionDeletionRequest->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $suggestionForms = $this->ChangeAdditionDeletionRequest->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        $fileUploads = $this->ChangeAdditionDeletionRequest->FileUpload->find('list', array('conditions' => array('FileUpload.publish' => 1, 'FileUpload.soft_delete' => 0,'FileUpload.archived' => 1)));
        
       
        $masterListOfFormats = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        if ($this->request->is('post')) {
	
			$this->loadModel('MasterListOfFormat');
			$masterListOfFormat = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('first', array(
				'conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0,'MasterListOfFormat.id'=>$this->request->data['ChangeAdditionDeletionRequest']['master_list_of_format']),
				'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.document_details','MasterListOfFormat.work_instructions')
				));
			
			$this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] = 2;
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
           $this->request->data['ChangeAdditionDeletionRequest']['system_table_id'] = $this->_get_system_table_id($this->request->params['controller']);
			
			$this->request->data['ChangeAdditionDeletionRequest']['current_document_details'] = $masterListOfFormat['MasterListOfFormat']['document_details'];
			$this->request->data['ChangeAdditionDeletionRequest']['current_work_instructions'] = $masterListOfFormat['MasterListOfFormat']['work_instructions'];
           
		   $this->ChangeAdditionDeletionRequest->create();
            if ($this->ChangeAdditionDeletionRequest->save($this->request->data)) {                
                $this->Session->setFlash(__('The change addition deletion request has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->ChangeAdditionDeletionRequest->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The change addition deletion request could not be saved. Please, try again.'));
            }
        }

        $branches = $this->ChangeAdditionDeletionRequest->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $meetings = $this->ChangeAdditionDeletionRequest->Meeting->find('list', array('conditions' => array('Meeting.publish' => 1, 'Meeting.soft_delete' => 0)));
        $departments = $this->ChangeAdditionDeletionRequest->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $employees = $this->ChangeAdditionDeletionRequest->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $customers = $this->ChangeAdditionDeletionRequest->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $suggestionForms = $this->ChangeAdditionDeletionRequest->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        $masterListOfFormats = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $fileUploads = $this->ChangeAdditionDeletionRequest->FileUpload->find('list', array('conditions' => array('FileUpload.publish' => 1, 'FileUpload.soft_delete' => 0,'FileUpload.archived' => 1)));
        $this->set(compact('branches', 'departments', 'employees', 'customers', 'meetings', 'suggestionForms', 'masterListOfFormats','fileUploads'));
    }


    public function add_document_cr() {
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        $file = $this->ChangeAdditionDeletionRequest->FileUpload->find('first', array('conditions' => array('FileUpload.id' =>$this->request->params['pass'][0])));
        $this->set('file',$file);
        if($file['SystemTable']['name']){
            try{
                $model = Inflector::Classify($file['SystemTable']['name']);
                $this->loadModel($model);
                $record_details = $this->$model->find('first',array(
                    'recursive'=>-1,
                    'fields'=>array($model.'.id',$model.'.'.$this->$model->displayField),
                    'conditions'=>array($model.'.id'=>$file['FileUpload']['record'])));
                if($record_details){
                    $record = array(
                            'id'=>$file['FileUpload']['record'],
                            'displayName' => $record_details[$model][$this->$model->displayField]
                        );
                    $this->set('record',$record);
                }
            }catch(Exception $e){

            }
        }
        
        $branches = $this->ChangeAdditionDeletionRequest->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $meetings = $this->ChangeAdditionDeletionRequest->Meeting->find('list', array('conditions' => array('Meeting.publish' => 1, 'Meeting.soft_delete' => 0)));
        $departments = $this->ChangeAdditionDeletionRequest->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $employees = $this->ChangeAdditionDeletionRequest->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $customers = $this->ChangeAdditionDeletionRequest->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $suggestionForms = $this->ChangeAdditionDeletionRequest->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        
        
       
        $masterListOfFormats = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        if ($this->request->is('post')) {
            $this->loadModel('MasterListOfFormat');
            $masterListOfFormat = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('first', array(
                'conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0,'MasterListOfFormat.id'=>$this->request->data['ChangeAdditionDeletionRequest']['master_list_of_format']),
                'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.document_details','MasterListOfFormat.work_instructions')
                ));
            
            $this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] = 2;
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
           $this->request->data['ChangeAdditionDeletionRequest']['system_table_id'] = $this->_get_system_table_id($this->request->params['controller']);
           
           if(isset($this->request->data['ChangeAdditionDeletionRequest']['file_upload_id']) && $this->request->data['ChangeAdditionDeletionRequest']['file_upload_id'] != NULL){}else{ 
            $this->request->data['ChangeAdditionDeletionRequest']['current_document_details'] = $masterListOfFormat['MasterListOfFormat']['document_details'];
            $this->request->data['ChangeAdditionDeletionRequest']['current_work_instructions'] = $masterListOfFormat['MasterListOfFormat']['work_instructions'];
           }
           $this->ChangeAdditionDeletionRequest->create();
           
            if ($this->ChangeAdditionDeletionRequest->save($this->request->data)) { 
                if(isset($this->request->data['ChangeAdditionDeletionRequest']['file_upload_id']) && $this->request->data['ChangeAdditionDeletionRequest']['file_upload_id'] != NULL){
                    $this->ChangeAdditionDeletionRequest->FileUpload->read(null,$this->request->data['ChangeAdditionDeletionRequest']['file_upload_id']);
                    $this->ChangeAdditionDeletionRequest->FileUpload->set(array('file_status'=>2,'comment'=>'This file is under revision'));
                    $this->ChangeAdditionDeletionRequest->FileUpload->save(true);
                }
                
                $this->Session->setFlash(__('The change addition deletion request has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->ChangeAdditionDeletionRequest->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The change addition deletion request could not be saved. Please, try again.'));
            }
        }

        $branches = $this->ChangeAdditionDeletionRequest->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $meetings = $this->ChangeAdditionDeletionRequest->Meeting->find('list', array('conditions' => array('Meeting.publish' => 1, 'Meeting.soft_delete' => 0)));
        $departments = $this->ChangeAdditionDeletionRequest->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $employees = $this->ChangeAdditionDeletionRequest->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $customers = $this->ChangeAdditionDeletionRequest->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $suggestionForms = $this->ChangeAdditionDeletionRequest->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        $masterListOfFormats = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('branches', 'departments', 'employees', 'customers', 'meetings', 'suggestionForms', 'masterListOfFormats'));

        // get clause details etc
        if($file['FileUpload']['system_table_id'] == 'clauses'){
            $this->loadModel('Clause');
            $path = split(DS, $file['FileUpload']['file_dir']);
            $clause = $this->Clause->find('first',array('conditions'=>array('Clause.id'=>$path[2])));
            $this->set(compact('clause'));
        }


    }

    public function _add_data($cad_id=NULL,$data = NULL,$type = NULL) {
        if($type == 'format'){
    		$this->ChangeAdditionDeletionRequest->MasterListOfFormat->read(null, $data['ChangeAdditionDeletionRequest']['master_list_of_format']);
    		$data['NewMasterListOfFormat']['approved_by'] =$data['ChangeAdditionDeletionRequest']['approved_by'];
    		$data['NewMasterListOfFormat']['document_details'] = $data['ChangeAdditionDeletionRequest']['proposed_document_changes'];
    		$data['NewMasterListOfFormat']['work_instructions'] =$data['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes'];
    		$this->ChangeAdditionDeletionRequest->MasterListOfFormat->save($data['NewMasterListOfFormat'], false);
        }
		
		$this->loadModel('DocumentAmendmentRecordSheet');
		
		$ifExist = $this->DocumentAmendmentRecordSheet->find('first',array('conditions'=>array('DocumentAmendmentRecordSheet.change_addition_deletion_request_id'=>$cad_id)));
		if(!($ifExist)){
				$this->DocumentAmendmentRecordSheet->create();
				$sheetData = null;
				$sheetData['DocumentAmendmentRecordSheet']['request_from'] = $data['ChangeAdditionDeletionRequest']['request_from'];
				$sheetData['DocumentAmendmentRecordSheet']['branch_id'] = $data['ChangeAdditionDeletionRequest']['branch_id'];
				$sheetData['DocumentAmendmentRecordSheet']['department_id'] = $data['ChangeAdditionDeletionRequest']['department_id'];
				$sheetData['DocumentAmendmentRecordSheet']['employee_id'] = $data['ChangeAdditionDeletionRequest']['employee_id'];
				$sheetData['DocumentAmendmentRecordSheet']['others'] = $data['ChangeAdditionDeletionRequest']['others'];
				$sheetData['DocumentAmendmentRecordSheet']['suggestion_form_id'] = $data['ChangeAdditionDeletionRequest']['suggestion_form_id'];
				$sheetData['DocumentAmendmentRecordSheet']['customer_id'] = $data['ChangeAdditionDeletionRequest']['customer_id'];
                $sheetData['DocumentAmendmentRecordSheet']['file_upload_id'] = $data['ChangeAdditionDeletionRequest']['file_upload_id'];
				$sheetData['DocumentAmendmentRecordSheet']['master_list_of_format'] = $data['ChangeAdditionDeletionRequest']['master_list_of_format'];
				$sheetData['DocumentAmendmentRecordSheet']['document_title'] = $data['MasterListOfFormat']['title'];
				$sheetData['DocumentAmendmentRecordSheet']['document_number'] = $data['MasterListOfFormat']['document_number'];
				$sheetData['DocumentAmendmentRecordSheet']['issue_number'] = $data['MasterListOfFormat']['issue_number'];
				$sheetData['DocumentAmendmentRecordSheet']['revision_number'] = $data['MasterListOfFormat']['revision_number'];
				$sheetData['DocumentAmendmentRecordSheet']['revision_date'] = $data['MasterListOfFormat']['revision_date'];
				$sheetData['DocumentAmendmentRecordSheet']['document_details'] = $data['ChangeAdditionDeletionRequest']['proposed_document_changes'];
				$sheetData['DocumentAmendmentRecordSheet']['work_instructions'] = $data['MasterListOfFormat']['proposed_work_instruction_changes'];
				$sheetData['DocumentAmendmentRecordSheet']['prepared_by'] = $data['ChangeAdditionDeletionRequest']['prepared_by'];
				$sheetData['DocumentAmendmentRecordSheet']['approved_by'] = $data['ChangeAdditionDeletionRequest']['approved_by'];
				$sheetData['DocumentAmendmentRecordSheet']['amendment_details'] = $data['ChangeAdditionDeletionRequest']['reason_for_change'];
				$sheetData['DocumentAmendmentRecordSheet']['reason_for_change'] = $data['ChangeAdditionDeletionRequest']['reason_for_change'];
				$sheetData['DocumentAmendmentRecordSheet']['change_addition_deletion_request_id'] = $cad_id;		
				$sheetData['DocumentAmendmentRecordSheet']['publish'] = 1;
				$sheetData['DocumentAmendmentRecordSheet']['soft_delete'] = 0;
				$sheetData['DocumentAmendmentRecordSheet']['created_by'] = $this->Session->read('User.id');
				$sheetData['DocumentAmendmentRecordSheet']['modified_by'] = $this->Session->read('User.id');
				$sheetData['DocumentAmendmentRecordSheet']['system_table_id']= $this->_get_system_table_id('document_amendment_record_sheets');
				
	
				
				if ($this->DocumentAmendmentRecordSheet->save($sheetData['DocumentAmendmentRecordSheet'],false)) {
					if($type == 'format'){
                        // also update prepared by & approved by in MasterListOfFormatDepartment & branches
    					$this->loadModel('MasterListOfFormatDepartment');
    					$department_record = $this->MasterListOfFormatDepartment->find('first',array(
    						'fields'=>array('MasterListOfFormatDepartment.id','MasterListOfFormatDepartment.master_list_of_format_id','MasterListOfFormatDepartment.company_id'),
    						'conditions'=>array('MasterListOfFormatDepartment.master_list_of_format_id'=>$data['MasterListOfFormat']['id'],'MasterListOfFormatDepartment.company_id'=>$this->Session->read('User.company_id'))));
    					
    					$this->MasterListOfFormatDepartment->read(null,$department_record['MasterListOfFormatDepartment']['id']);
    					$this->MasterListOfFormatDepartment->set(array(
    						'prepared_by'=>$data['ChangeAdditionDeletionRequest']['prepared_by'],
    						'approved_by'=>$data['ChangeAdditionDeletionRequest']['approved_by']));
    					$this->MasterListOfFormatDepartment->save();			
    					
    					$newRecordId = $this->DocumentAmendmentRecordSheet->id;
    					// get the master list of format id
    					// goto folder and shift existing document to new document amendment id
    					// as user to add updated document to previous id
    					
    					foreach ($this->_get_user_list() as $key => $value):
                                                $copyTo = new Folder(Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id') . DS . 'upload' . DS . $key . DS . 'document_amendment_record_sheets' . DS . $newRecordId . DS, true, 0777);
    						$copyFrom = new Folder(Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id') . DS . 'upload' . DS . $key . DS . 'master_list_of_formats' . DS . $data['ChangeAdditionDeletionRequest']['master_list_of_format'] . DS);
    						$copyFrom->copy(array(
    							'to' => $copyTo->path,
    							'mode' => 0777,
    							'skip' => '.DS_Store',
    							'scheme' => Folder::SKIP));
    						$copyFrom->delete();
    						// We also need to amend the FileUpload records for version controlling.
    						
    						$this->_updateVers($data['MasterListOfFormat']['system_table_id'],$data['MasterListOfFormat']['id'],$newRecordId);
    					endforeach;

                    }elseif($type == 'document'){     

                        $this->ChangeAdditionDeletionRequest->FileUpload->read(null,$this->request->data['ChangeAdditionDeletionRequest']['file_upload_id']);
                        $this->ChangeAdditionDeletionRequest->FileUpload->set(array('file_status'=>3,'comment'=>'Document Change is approved. Please upload new file'));
                        $this->ChangeAdditionDeletionRequest->FileUpload->save(true);
                    }
				}
       		}
    }
	
	public function _updateVers($system_table_id=NULL,$record=NULL,$newRecordId=NULL){
			$this->loadModel('FileUpload');
			$files = $this->FileUpload->find('all',array('conditions'=>array(
				'FileUpload.record'=>$record,
				'FileUpload.system_table_id'=>$this->_get_system_table_id('master_list_of_formats'),
			)));
			
			foreach($files as $file):
				$this->FileUpload->create();
				$data['FileUpload']['id']=$file['FileUpload']['id'];
				$data['FileUpload']['file_dir']=str_replace('master_list_of_formats','document_amendment_record_sheets',str_replace($record,$newRecordId,$file['FileUpload']['file_dir']));
				$data['FileUpload']['record']=$newRecordId;				
				$data['FileUpload']['comment']=$file['FileUpload']['comment'] ." : This file is archived";
				$data['FileUpload']['system_table_id']=$this->_get_system_table_id('document_amendment_record_sheets');
				$data['FileUpload']['publish']=1;				
				$this->FileUpload->save($data);			
			endforeach;
	}

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
		if (!$this->ChangeAdditionDeletionRequest->exists($id)) {
		throw new NotFoundException(__('Invalid change addition deletion request'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        
        if ($this->request->is('post') || $this->request->is('put')) {

            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['ChangeAdditionDeletionRequest']['system_table_id'] = $this->_get_system_table_id($this->request->params['controller']);
				if(isset($this->request->data['ChangeAdditionDeletionRequest']['meeting_topic_id'])) $this->request->data['ChangeAdditionDeletionRequest']['meeting_id']  = $this->request->data['ChangeAdditionDeletionRequest']['meeting_topic_id'];
				else $this->request->data['ChangeAdditionDeletionRequest']['meeting_id'] = -1;
            if ($this->ChangeAdditionDeletionRequest->save($this->request->data)) {
                // updating other tables
                if($this->request->data['ChangeAdditionDeletionRequest']['meeting_id'] > -1){
                    $this->loadModel('MeetingTopic');
                    $meetingTopic['MeetingTopic']['meeting_id'] = $this->request->data['ChangeAdditionDeletionRequest']['meeting_id'];
                    $meetingTopic['MeetingTopic']['change_addition_deletion_request_id'] = $this->request->data['ChangeAdditionDeletionRequest']['id'];
                    $meetingTopic['MeetingTopic']['publish'] = 1;
                    $meetingTopicId = $this->MeetingTopic->find('first',array('conditions'=>array('MeetingTopic.change_addition_deletion_request_id'=>$id),'recursive'=>-1,'fields'=>'id'));
                    $this->MeetingTopic->id = $meetingTopicId['MeetingTopic']['id'];
                    $this->MeetingTopic->id;
                    $this->MeetingTopic->save($meetingTopic,FALSE);
                }
                if ($this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1) {
                        if($this->request->data['ChangeAdditionDeletionRequest']['file_upload_id'] != null && $this->request->data['ChangeAdditionDeletionRequest']['file_upload_id'] != -1){
                            $this->_add_data($id,$this->request->data,'document');
                        }else{                            
                            $this->_add_data($id,$this->request->data,'format');
                        }
                }else{
                            
                }

                if ($this->request->data['ChangeAdditionDeletionRequest']['flinkiso_functionality_change_required'] == 1) {
                    try{
                            $companyDetails = $this->_get_company();
                            App::uses('CakeEmail', 'Network/Email');
                            if($this->Session->read('User.is_smtp') == '1')
                            {
                                $EmailConfig = new CakeEmail("smtp");	
                            }else if($this->Session->read('User.is_smtp') == '0'){
                                $EmailConfig = new CakeEmail("default");
                            }
                            $EmailConfig->to('sales@flinkiso.com');
                            $EmailConfig->subject('Change Addition Deletion Request from: ' . $companyDetails['Company']['name']);
                            $EmailConfig->template('changeAdditionDeletionRequest');
                            $EmailConfig->viewVars(array('details' => $this->request->data['ChangeAdditionDeletionRequest']['flinkiso_functionality_change_details'],'companyName'=> $companyDetails['Company']['name']));
                            $EmailConfig->emailFormat('html');
                            $EmailConfig->send();
                     } catch(Exception $e) {
                         $this->Session->setFlash(__('The change addition deletion request has been saved but fail to send email. Please check smtp details.'));

                    }
                }

                $this->Session->setFlash(__('The change addition deletion request has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    if($this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1 or $this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] == true){
                        if(isset($this->request->data['ChangeAdditionDeletionRequest']['file_upload_id']) && $this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] != NULL){                            
                            if($this->request->data['ChangeAdditionDeletionRequest']['file_upload_system_table_id'] == 'clauses'){
                                $controller = 'clauses';
                                $this->redirect(array('controller'=>$controller,'action' => 'standards'));
                            }else{
                                $sys_table = $this->ChangeAdditionDeletionRequest->SystemTable->find('first',array('conditions'=>array('SystemTable.id'=>$this->request->data['ChangeAdditionDeletionRequest']['file_upload_system_table_id'])));
                                $controller = $sys_table['SystemTable']['system_name'];    
                            }
                            
                            $this->Session->setFlash(__('Document updated successfully. Please add new document files & work intructions.'));
                            $this->redirect(array('controller'=>$controller,'action' => 'view',$this->request->data['ChangeAdditionDeletionRequest']['file_upload_record']));

                        }else{
                            
                            $this->Session->setFlash(__('Document updated successfully. Please add new document files & work intructions.'));
                            $this->redirect(array('controller'=>'master_list_of_formats','action' => 'view',$this->request->data['ChangeAdditionDeletionRequest']['master_list_of_format']));

                            
                        }
					}
                    
            } else {
                $this->Session->setFlash(__('The change request could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('ChangeAdditionDeletionRequest.' . $this->ChangeAdditionDeletionRequest->primaryKey => $id));
            $this->request->data = $this->ChangeAdditionDeletionRequest->find('first', $options);
            if($this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1){
                $this->Session->setFlash(__('The change request is already accepted. You can not make chnages.'));
                 $this->redirect(array('action' => 'view', $id));
            }
            $file = $this->ChangeAdditionDeletionRequest->FileUpload->find('first', array('conditions' => array('FileUpload.id' => $this->request->data['ChangeAdditionDeletionRequest']['file_upload_id'])));
            $this->set('file',$file);
        }
        $branches = $this->ChangeAdditionDeletionRequest->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $meetings = $this->ChangeAdditionDeletionRequest->Meeting->find('list', array('conditions' => array('Meeting.publish' => 1, 'Meeting.soft_delete' => 0)));
        $departments = $this->ChangeAdditionDeletionRequest->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $employees = $this->ChangeAdditionDeletionRequest->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $customers = $this->ChangeAdditionDeletionRequest->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $suggestionForms = $this->ChangeAdditionDeletionRequest->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        $masterListOfFormats = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('branches', 'departments', 'employees', 'customers', 'meetings', 'suggestionForms', 'masterListOfFormats'));
		$this->document_revisions($this->request->data['ChangeAdditionDeletionRequest']['master_list_of_format']);

        if($file['FileUpload']['system_table_id'] == 'clauses'){
            $this->loadModel('Clause');
            $path = split(DS, $file['FileUpload']['file_dir']);
            $clause = $this->Clause->find('first',array('conditions'=>array('Clause.id'=>$path[2])));
            $this->set(compact('clause'));
        }

        if($file['SystemTable']['name']){
            try{
                $model = Inflector::Classify($file['SystemTable']['name']);
                $this->loadModel($model);
                $record_details = $this->$model->find('first',array(
                    'recursive'=>-1,
                    'fields'=>array($model.'.id',$model.'.'.$this->$model->displayField),
                    'conditions'=>array($model.'.id'=>$file['FileUpload']['record'])));
                if($record_details){
                    $record = array(
                            'id'=>$file['FileUpload']['record'],
                            'displayName' => $record_details[$model][$this->$model->displayField]
                        );
                    $this->set('record',$record);
                }
            }catch(Exception $e){

            }
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

        if (!$this->ChangeAdditionDeletionRequest->exists($id)) {
            throw new NotFoundException(__('Invalid change request'));
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
            $this->request->data['ChangeAdditionDeletionRequest']['system_table_id'] = $this->_get_system_table_id($this->request->params['controller']);
                if(isset($this->request->data['ChangeAdditionDeletionRequest']['meeting_topic_id'])) $this->request->data['ChangeAdditionDeletionRequest']['meeting_id']  = $this->request->data['ChangeAdditionDeletionRequest']['meeting_topic_id'];
                else $this->request->data['ChangeAdditionDeletionRequest']['meeting_id'] = -1;
            if ($this->ChangeAdditionDeletionRequest->save($this->request->data,false)) {
                // updating other tables
                if($this->request->data['ChangeAdditionDeletionRequest']['meeting_id'] > -1){
                    $this->loadModel('MeetingTopic');
                    $meetingTopic['MeetingTopic']['meeting_id'] = $this->request->data['ChangeAdditionDeletionRequest']['meeting_id'];
                    $meetingTopic['MeetingTopic']['change_addition_deletion_request_id'] = $this->request->data['ChangeAdditionDeletionRequest']['id'];
                    $meetingTopic['MeetingTopic']['publish'] = 1;
                    $meetingTopicId = $this->MeetingTopic->find('first',array('conditions'=>array('MeetingTopic.meeting_id'=>$this->request->data['ChangeAdditionDeletionRequest']['meeting_topic_id'],'MeetingTopic.change_addition_deletion_request_id'=>$this->request->data['ChangeAdditionDeletionRequest']['id'])));
                    $this->MeetingTopic->id = $meetingTopicId['MeetingTopic']['id'];
                    $this->MeetingTopic->id;
                    $this->MeetingTopic->save($meetingTopic,FALSE);
                }
                if ($this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1) {
                        if($this->request->data['ChangeAdditionDeletionRequest']['file_upload_id'] != null && $this->request->data['ChangeAdditionDeletionRequest']['file_upload_id'] != -1){
                            $this->_add_data($id,$this->request->data,'document');
                        }else{                            
                            $this->_add_data($id,$this->request->data,'format');
                        }
                }else{
                            
                }

                if ($this->request->data['ChangeAdditionDeletionRequest']['flinkiso_functionality_change_required'] == 1) {
                   try{
            $companyDetails = $this->_get_company();

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
                        $EmailConfig->to('sales@flinkiso.com');
                        $EmailConfig->subject('Change Addition Deletion Request from: ' . $companyDetails['Company']['name']);
                        $EmailConfig->template('changeAdditionDeletionRequest');
                        $EmailConfig->viewVars(array('details' => $this->request->data['ChangeAdditionDeletionRequest']['flinkiso_functionality_change_details'],'companyName'=> $companyDetails['Company']['name'],'env' => $env, 'app_url' => FULL_BASE_URL));
                        $EmailConfig->emailFormat('html');
                        $EmailConfig->send();
                    } catch(Exception $e) {
                         $this->Session->setFlash(__('The change addition deletion request has been saved but fail to send email. Please check smtp details.'));

                    }
                }

                $this->Session->setFlash(__('The change addition deletion request has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    if($this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1 or $this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] == true){
                        if(isset($this->request->data['ChangeAdditionDeletionRequest']['file_upload_id']) && $this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] != NULL){                            
                            $sys_table = $this->ChangeAdditionDeletionRequest->SystemTable->find('first',array('conditions'=>array('SystemTable.id'=>$this->request->data['ChangeAdditionDeletionRequest']['file_upload_system_table_id'])));
                            $controller = $sys_table['SystemTable']['system_name'];
                            $this->Session->setFlash(__('Document updated successfully. Please add new document files & work intructions.'));
                            $this->redirect(array('controller'=>$controller,'action' => 'view',$this->request->data['ChangeAdditionDeletionRequest']['file_upload_record']));

                        }else{
                            
                            $this->Session->setFlash(__('Document updated successfully. Please add new document files & work intructions.'));
                            $this->redirect(array('controller'=>'master_list_of_formats','action' => 'view',$this->request->data['ChangeAdditionDeletionRequest']['master_list_of_format']));

                            
                        }
                    }
                    
            } else {
                $this->Session->setFlash(__('The change addition deletion request could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('ChangeAdditionDeletionRequest.' . $this->ChangeAdditionDeletionRequest->primaryKey => $id));
            $this->request->data = $this->ChangeAdditionDeletionRequest->find('first', $options);
            if($this->request->data['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1){
                $this->Session->setFlash(__('The change request is already accepted. You can not make chnages.'));
                 $this->redirect(array('action' => 'view', $id));
            }
            $file = $this->ChangeAdditionDeletionRequest->FileUpload->find('first', array('conditions' => array('FileUpload.id' => $this->request->data['ChangeAdditionDeletionRequest']['file_upload_id'])));
            $this->set('file',$file);            
        }
        $branches = $this->ChangeAdditionDeletionRequest->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $meetings = $this->ChangeAdditionDeletionRequest->Meeting->find('list', array('conditions' => array('Meeting.publish' => 1, 'Meeting.soft_delete' => 0)));
        $departments = $this->ChangeAdditionDeletionRequest->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $employees = $this->ChangeAdditionDeletionRequest->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $customers = $this->ChangeAdditionDeletionRequest->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $suggestionForms = $this->ChangeAdditionDeletionRequest->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        $masterListOfFormats = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));

        $this->set(compact('branches', 'departments', 'employees', 'customers', 'meetings', 'suggestionForms', 'masterListOfFormats'));
        $this->document_revisions($this->request->data['ChangeAdditionDeletionRequest']['master_list_of_format']);
    }


	public function current_details($id = null){
		$master_list_of_format = $this->ChangeAdditionDeletionRequest->MasterListOfFormat->find('first');
	}
	
	public function getRevisions($id=null){
		$this->document_revisions($id);
	}
}

// ALTER TABLE `change_addition_deletion_requests` Add `title` varchar(255) NULL AFTER `others`

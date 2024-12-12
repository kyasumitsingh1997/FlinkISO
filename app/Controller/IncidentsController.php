<?php
App::uses('AppController', 'Controller');
/**
 * Incidents Controller
 *
 * @property Incident $Incident
 */
class IncidentsController extends AppController {

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
		$this->paginate = array('order'=>array('Incident.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->Incident->recursive = 1;
		$this->set('incidents', $this->paginate());
		
		$this->_get_count();
	}


 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Incident.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->Incident->recursive = 0;
		$this->set('incidents', $this->paginate());
		
		$this->_get_count();
	}

/**
 * search method
 * Dynamic by - TGS
 * @return void
 */
	public function search() {
		if ($this->request->is('post')) {
	
	$search_array = array();
		$search_keys = explode(" ",$this->request->data['Incident']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['Incident']['search_field'] as $search):
				$search_array[] = array('Incident.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Incident.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->Incident->recursive = 0;
		$this->paginate = array('order'=>array('Incident.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Incident.soft_delete'=>0 , $cons));
		$this->set('incidents', $this->paginate());
		}
                $this->render('index');
	}

/**
 * adcanced_search method
 * Advanced search by - TGS
 * @return void
 */
	public function advanced_search() {
		if ($this->request->is('get')) {
		$conditions = array();
			if($this->request->query['keywords']){
				$search_array = array();
				$search_keys = explode(" ",$this->request->query['keywords']);
	
				foreach($search_keys as $search_key):
					foreach($this->request->query['search_fields'] as $search):
					if($this->request->query['strict_search'] == 0)$search_array[] = array('Incident.'.$search => $search_key);
					else $search_array[] = array('Incident.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('Incident.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('Incident.incident_date >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Incident.incident_date <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
	
	}
		
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Incident.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Incident.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->Incident->recursive = 0;
		$this->paginate = array('order'=>array('Incident.sr_no'=>'DESC'),'conditions'=>$conditions , 'Incident.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('incidents', $this->paginate());
		}
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
		if (!$this->Incident->exists($id)) {
			throw new NotFoundException(__('Invalid incident'));
		}
		$options = array('recursive'=>2,'conditions' => array('Incident.' . $this->Incident->primaryKey => $id));
		$this->set('incident', $this->Incident->find('first', $options));
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
                        $this->request->data['Incident']['system_table_id'] = $this->_get_system_table_id();
			$this->Incident->create();
			if ($this->Incident->save($this->request->data,false)) {
                $this->Session->setFlash(__('The Incident has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Incident->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Incident could not be saved. Please, try again.'));
            }
		}
		
		$incidentClassifications = $this->Incident->IncidentClassification->find('list',array('conditions'=>array('IncidentClassification.publish'=>1,'IncidentClassification.soft_delete'=>0)));
		$departments = $this->Incident->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$branches = $this->Incident->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$personResponsibles = $this->Incident->PersonResponsible->find('list',array('conditions'=>array('PersonResponsible.publish'=>1,'PersonResponsible.soft_delete'=>0)));
		$correctivePreventiveActions = $this->Incident->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$reportedBies = $this->Incident->ReportedBy->find('list',array('conditions'=>array('ReportedBy.publish'=>1,'ReportedBy.soft_delete'=>0)));
		$systemTables = $this->Incident->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Incident->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Incident->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Incident->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Incident->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Incident->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Incident->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$riskAssessments = $this->Incident->RiskAssessment->find('list',array('conditions'=>array('RiskAssessment.publish'=>1,'RiskAssessment.soft_delete'=>0)));
		$this->set(compact('incidentClassifications', 'departments', 'branches', 'reportedBies','personResponsibles', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','riskAssessments'));
		$count = $this->Incident->find('count');
		$published = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>1)));
		$unpublished = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

	}





/**
 * add method
 *
 * @return void
 */
	public function add() {
	
		if($this->_show_approvals()){
			$this->loadModel('User');
			$this->User->recursive = 0;
			$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_approvar'=>1)));
			$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
		}
		
		if ($this->request->is('post')) {
                        $this->request->data['Incident']['system_table_id'] = $this->_get_system_table_id();
			$this->Incident->create();
			if ($this->Incident->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Incident';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Incident->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The incident has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Incident->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The incident could not be saved. Please, try again.'));
			}
		}
		$incidentClassifications = $this->Incident->IncidentClassification->find('list',array('conditions'=>array('IncidentClassification.publish'=>1,'IncidentClassification.soft_delete'=>0)));
		$departments = $this->Incident->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$branches = $this->Incident->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$personResponsibles = $this->Incident->PersonResponsible->find('list',array('conditions'=>array('PersonResponsible.publish'=>1,'PersonResponsible.soft_delete'=>0)));
		$correctivePreventiveActions = $this->Incident->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$reportedBies = $this->Incident->ReportedBy->find('list',array('conditions'=>array('ReportedBy.publish'=>1,'ReportedBy.soft_delete'=>0)));
		$systemTables = $this->Incident->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Incident->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Incident->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Incident->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Incident->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Incident->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$riskAssessments = $this->Incident->RiskAssessment->find('list',array('conditions'=>array('RiskAssessment.publish'=>1,'RiskAssessment.soft_delete'=>0)));
		$this->set(compact('incidentClassifications', 'departments', 'branches', 'reportedBies','personResponsibles', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','riskAssessments'));
		$count = $this->Incident->find('count');
		$published = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>1)));
		$unpublished = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));


	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Incident->exists($id)) {
			throw new NotFoundException(__('Invalid incident'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['Incident']['system_table_id'] = $this->_get_system_table_id();
			if ($this->Incident->save($this->request->data,false)) {
                $this->Session->setFlash(__('The Incident has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Incident->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Incident could not be saved. Please, try again.'));
            }
		} else {
			$options = array('conditions' => array('Incident.' . $this->Incident->primaryKey => $id));
			$this->request->data = $this->Incident->find('first', $options);
		}
		$incidentClassifications = $this->Incident->IncidentClassification->find('list',array('conditions'=>array('IncidentClassification.publish'=>1,'IncidentClassification.soft_delete'=>0)));
		$departments = $this->Incident->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$branches = $this->Incident->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$personResponsibles = $this->Incident->PersonResponsible->find('list',array('conditions'=>array('PersonResponsible.publish'=>1,'PersonResponsible.soft_delete'=>0)));
		$correctivePreventiveActions = $this->Incident->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$reportedBies = $this->Incident->ReportedBy->find('list',array('conditions'=>array('ReportedBy.publish'=>1,'ReportedBy.soft_delete'=>0)));
		$systemTables = $this->Incident->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Incident->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Incident->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Incident->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Incident->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Incident->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$riskAssessments = $this->Incident->RiskAssessment->find('list',array('conditions'=>array('RiskAssessment.publish'=>1,'RiskAssessment.soft_delete'=>0)));
		$this->set(compact('incidentClassifications', 'departments', 'branches', 'reportedBies','personResponsibles', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','riskAssessments'));
		$count = $this->Incident->find('count');
		$published = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>1)));
		$unpublished = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->Incident->exists($id)) {
			throw new NotFoundException(__('Invalid incident'));
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
			if ($this->Incident->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->Incident->save($this->request->data)) {
                $this->Session->setFlash(__('The incident has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The incident could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The incident could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Incident.' . $this->Incident->primaryKey => $id));
			$this->request->data = $this->Incident->find('first', $options);
		}
		$incidentClassifications = $this->Incident->IncidentClassification->find('list',array('conditions'=>array('IncidentClassification.publish'=>1,'IncidentClassification.soft_delete'=>0)));
		$departments = $this->Incident->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$branches = $this->Incident->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$personResponsibles = $this->Incident->PersonResponsible->find('list',array('conditions'=>array('PersonResponsible.publish'=>1,'PersonResponsible.soft_delete'=>0)));
		$correctivePreventiveActions = $this->Incident->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$reportedBies = $this->Incident->ReportedBy->find('list',array('conditions'=>array('ReportedBy.publish'=>1,'ReportedBy.soft_delete'=>0)));
		$systemTables = $this->Incident->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Incident->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Incident->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Incident->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Incident->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Incident->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$riskAssessments = $this->Incident->RiskAssessment->find('list',array('conditions'=>array('RiskAssessment.publish'=>1,'RiskAssessment.soft_delete'=>0)));
		$this->set(compact('incidentClassifications', 'departments', 'branches', 'reportedBies','personResponsibles', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','riskAssessments'));
		$count = $this->Incident->find('count');
		$published = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>1)));
		$unpublished = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {
		$this->Incident->id = $id;
		if (!$this->Incident->exists()) {
			throw new NotFoundException(__('Invalid incident'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Incident->delete()) {
			$this->Session->setFlash(__('Incident deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Incident was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
	
            $model_name = $this->modelClass;
            if(!empty($id)){
    
            $data['id'] = $id;
            $data['soft_delete'] = 1;
            $model_name=$this->modelClass;
            $this->$model_name->save($data);
    }
    $this->redirect(array('action' => 'index'));
     
    
}
 
	
	
	
	public function report(){
		
		$result = explode('+',$this->request->data['incidents']['rec_selected']);
		$this->Incident->recursive = 1;
		$incidents = $this->Incident->find('all',array('Incident.publish'=>1,'Incident.soft_delete'=>1,'conditions'=>array('or'=>array('Incident.id'=>$result))));
		$this->set('incidents', $incidents);
		
		$incidentClassifications = $this->Incident->IncidentClassification->find('list',array('conditions'=>array('IncidentClassification.publish'=>1,'IncidentClassification.soft_delete'=>0)));
		$departments = $this->Incident->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$branches = $this->Incident->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$personResponsibles = $this->Incident->PersonResponsible->find('list',array('conditions'=>array('PersonResponsible.publish'=>1,'PersonResponsible.soft_delete'=>0)));
		$correctivePreventiveActions = $this->Incident->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$reportedBies = $this->Incident->ReportedBy->find('list',array('conditions'=>array('ReportedBy.publish'=>1,'ReportedBy.soft_delete'=>0)));
		$systemTables = $this->Incident->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Incident->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Incident->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Incident->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Incident->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Incident->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$riskAssessments = $this->Incident->RiskAssessment->find('list',array('conditions'=>array('RiskAssessment.publish'=>1,'RiskAssessment.soft_delete'=>0)));
		$this->set(compact('incidentClassifications', 'departments', 'branches', 'reportedBies','personResponsibles', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','riskAssessments'));
		$count = $this->Incident->find('count');
		$published = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>1)));
		$unpublished = $this->Incident->find('count',array('conditions'=>array('Incident.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

}

public function get_employee_info($employeeId = null){
        $this->loadModel('Employee');
        $employees = $this->Employee->find('first',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0, 'Employee.id'=>$employeeId),'recursive'=>-1));
        $birth_year = date('Y' , strtotime($employees['Employee']['date_of_birth']));
        $this_year = date('Y');
        $diff = $this_year - $birth_year;
        if($employees['Employee']['date_of_birth'])$employees['Employee']['age'] = (string) $diff;
        echo  json_encode($employees); die;
    }
}

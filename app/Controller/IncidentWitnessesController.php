<?php
App::uses('AppController', 'Controller');
/**
 * IncidentWitnesses Controller
 *
 * @property IncidentWitness $IncidentWitness
 */
class IncidentWitnessesController extends AppController {

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
		if($this->request->params['pass'][0])$conditions = array_merge($conditions, array('IncidentWitness.incident_id'=>$this->request->params['pass'][0]));
		$this->paginate = array('order'=>array('IncidentWitness.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->IncidentWitness->recursive = 0;
		$this->set('incidentWitnesses', $this->paginate());
		
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
		$this->paginate = array('order'=>array('IncidentWitness.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->IncidentWitness->recursive = 0;
		$this->set('incidentWitnesses', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['IncidentWitness']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['IncidentWitness']['search_field'] as $search):
				$search_array[] = array('IncidentWitness.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('IncidentWitness.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->IncidentWitness->recursive = 0;
		$this->paginate = array('order'=>array('IncidentWitness.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'IncidentWitness.soft_delete'=>0 , $cons));
		$this->set('incidentWitnesses', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('IncidentWitness.'.$search => $search_key);
					else $search_array[] = array('IncidentWitness.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('IncidentWitness.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('IncidentWitness.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'IncidentWitness.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('IncidentWitness.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('IncidentWitness.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->IncidentWitness->recursive = 0;
		$this->paginate = array('order'=>array('IncidentWitness.sr_no'=>'DESC'),'conditions'=>$conditions , 'IncidentWitness.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('incidentWitnesses', $this->paginate());
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
		if (!$this->IncidentWitness->exists($id)) {
			throw new NotFoundException(__('Invalid incident witness'));
		}
		$options = array('conditions' => array('IncidentWitness.' . $this->IncidentWitness->primaryKey => $id));
		$this->set('incidentWitness', $this->IncidentWitness->find('first', $options));
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
	public function add_ajax($incidentId = null, $modal = null) {
	$this->layout = 'ajax';
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post')) {
                        $this->request->data['IncidentWitness']['system_table_id'] = $this->_get_system_table_id();
			$this->IncidentWitness->create();
			if ($this->IncidentWitness->save($this->request->data, false)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The incident witness has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->IncidentWitness->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The incident witness could not be saved. Please, try again.'));
			}
		}
		$incidents = $this->IncidentWitness->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		$employees = $this->IncidentWitness->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$departments = $this->IncidentWitness->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$designations = $this->IncidentWitness->Designation->find('list',array('conditions'=>array('Designation.publish'=>1,'Designation.soft_delete'=>0)));
		$systemTables = $this->IncidentWitness->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentWitness->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentWitness->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentWitness->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentWitness->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentWitness->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentWitness->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('incidents', 'employees', 'departments', 'designations', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->IncidentWitness->find('count');
		$published = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>1)));
		$unpublished = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>0)));
			
		$this->set(compact('count','published','unpublished','incidentId','modal'));
		$this->_witness($incidentId);
	}


public function _witness($incidentId = null){
		$witnesses = $this->IncidentWitness->find('list',array('conditions'=>array('IncidentWitness.soft_delete'=>0,'IncidentWitness.incident_id'=> $incidentId)));
		$this->set(compact('witnesses'));	
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
                        $this->request->data['IncidentWitness']['system_table_id'] = $this->_get_system_table_id();
			$this->IncidentWitness->create();
			if ($this->IncidentWitness->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='IncidentWitness';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->IncidentWitness->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The incident witness has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->IncidentWitness->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The incident witness could not be saved. Please, try again.'));
			}
		}
		$incidents = $this->IncidentWitness->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		$employees = $this->IncidentWitness->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$departments = $this->IncidentWitness->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$designations = $this->IncidentWitness->Designation->find('list',array('conditions'=>array('Designation.publish'=>1,'Designation.soft_delete'=>0)));
		$systemTables = $this->IncidentWitness->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentWitness->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentWitness->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentWitness->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentWitness->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentWitness->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentWitness->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$investigationInterviewTakenBies = $this->IncidentWitness->InvestigationInterviewTakenBies->find('list',array('conditions'=>array('InvestigationInterviewTakenBies.publish'=>1,'InvestigationInterviewTakenBies.soft_delete'=>0)));
		
		$this->set(compact('incidents', 'employees', 'departments', 'investigationInterviewTakenBies' , 'designations', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		
		$count = $this->IncidentWitness->find('count');
		$published = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>1)));
		$unpublished = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>0)));
			
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
		if (!$this->IncidentWitness->exists($id)) {
			throw new NotFoundException(__('Invalid incident witness'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['IncidentWitness']['system_table_id'] = $this->_get_system_table_id();
			if ($this->IncidentWitness->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The incident witness could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('IncidentWitness.' . $this->IncidentWitness->primaryKey => $id));
			$this->request->data = $this->IncidentWitness->find('first', $options);
		}
		$incidents = $this->IncidentWitness->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		$employees = $this->IncidentWitness->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$departments = $this->IncidentWitness->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$designations = $this->IncidentWitness->Designation->find('list',array('conditions'=>array('Designation.publish'=>1,'Designation.soft_delete'=>0)));
		$systemTables = $this->IncidentWitness->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentWitness->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentWitness->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentWitness->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentWitness->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentWitness->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentWitness->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$investigationInterviewTakenBies = $this->IncidentWitness->InvestigationInterviewTakenBies->find('list',array('conditions'=>array('InvestigationInterviewTakenBies.publish'=>1,'InvestigationInterviewTakenBies.soft_delete'=>0)));
		
		$this->set(compact('incidents', 'employees', 'departments', 'investigationInterviewTakenBies' , 'designations', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		
		$count = $this->IncidentWitness->find('count');
		$published = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>1)));
		$unpublished = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>0)));
			
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
		if (!$this->IncidentWitness->exists($id)) {
			throw new NotFoundException(__('Invalid incident witness'));
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
			if ($this->IncidentWitness->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->IncidentWitness->save($this->request->data)) {
                $this->Session->setFlash(__('The incident witness has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The incident witness could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The incident witness could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('IncidentWitness.' . $this->IncidentWitness->primaryKey => $id));
			$this->request->data = $this->IncidentWitness->find('first', $options);
		}
		
		$incidents = $this->IncidentWitness->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		$employees = $this->IncidentWitness->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$departments = $this->IncidentWitness->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$designations = $this->IncidentWitness->Designation->find('list',array('conditions'=>array('Designation.publish'=>1,'Designation.soft_delete'=>0)));
		$systemTables = $this->IncidentWitness->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentWitness->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentWitness->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentWitness->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentWitness->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentWitness->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentWitness->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$investigationInterviewTakenBies = $this->IncidentWitness->InvestigationInterviewTakenBies->find('list',array('conditions'=>array('InvestigationInterviewTakenBies.publish'=>1,'InvestigationInterviewTakenBies.soft_delete'=>0)));
		
		$this->set(compact('incidents', 'employees', 'departments', 'investigationInterviewTakenBies' , 'designations', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		
		$count = $this->IncidentWitness->find('count');
		$published = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>1)));
		$unpublished = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>0)));
			
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
		$this->IncidentWitness->id = $id;
		if (!$this->IncidentWitness->exists()) {
			throw new NotFoundException(__('Invalid incident witness'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->IncidentWitness->delete()) {
			$this->Session->setFlash(__('Incident witness deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Incident witness was not deleted'));
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
		
		$result = explode('+',$this->request->data['incidentWitnesses']['rec_selected']);
		$this->IncidentWitness->recursive = 1;
		$incidentWitnesses = $this->IncidentWitness->find('all',array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>1,'conditions'=>array('or'=>array('IncidentWitness.id'=>$result))));
		$this->set('incidentWitnesses', $incidentWitnesses);
		
				$incidents = $this->IncidentWitness->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		$employees = $this->IncidentWitness->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$departments = $this->IncidentWitness->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$designations = $this->IncidentWitness->Designation->find('list',array('conditions'=>array('Designation.publish'=>1,'Designation.soft_delete'=>0)));
		$systemTables = $this->IncidentWitness->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentWitness->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentWitness->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentWitness->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentWitness->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentWitness->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentWitness->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$investigationInterviewTakenBies = $this->IncidentWitness->InvestigationInterviewTakenBies->find('list',array('conditions'=>array('InvestigationInterviewTakenBies.publish'=>1,'InvestigationInterviewTakenBies.soft_delete'=>0)));
		
		$this->set(compact('incidents', 'employees', 'departments', 'investigationInterviewTakenBies' , 'designations', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		
		$count = $this->IncidentWitness->find('count');
		$published = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>1)));
		$unpublished = $this->IncidentWitness->find('count',array('conditions'=>array('IncidentWitness.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));
}
}

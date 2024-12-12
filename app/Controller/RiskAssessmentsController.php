<?php
App::uses('AppController', 'Controller');
/**
 * RiskAssessments Controller
 *
 * @property RiskAssessment $RiskAssessment
 */
class RiskAssessmentsController extends AppController {

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
		$this->paginate = array('order'=>array('RiskAssessment.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->RiskAssessment->recursive = 0;
		$this->set('riskAssessments', $this->paginate());
		
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
		$this->paginate = array('order'=>array('RiskAssessment.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->RiskAssessment->recursive = 0;
		$this->set('riskAssessments', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['RiskAssessment']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['RiskAssessment']['search_field'] as $search):
				$search_array[] = array('RiskAssessment.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('RiskAssessment.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->RiskAssessment->recursive = 0;
		$this->paginate = array('order'=>array('RiskAssessment.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'RiskAssessment.soft_delete'=>0 , $cons));
		$this->set('riskAssessments', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('RiskAssessment.'.$search => $search_key);
					else $search_array[] = array('RiskAssessment.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('RiskAssessment.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('RiskAssessment.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'RiskAssessment.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('RiskAssessment.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('RiskAssessment.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->RiskAssessment->recursive = 0;
		$this->paginate = array('order'=>array('RiskAssessment.sr_no'=>'DESC'),'conditions'=>$conditions , 'RiskAssessment.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('riskAssessments', $this->paginate());
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
		if (!$this->RiskAssessment->exists($id)) {
			throw new NotFoundException(__('Invalid risk assessment'));
		}
		$options = array('conditions' => array('RiskAssessment.' . $this->RiskAssessment->primaryKey => $id));
		$this->set('riskAssessment', $this->RiskAssessment->find('first', $options));
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
			$this->request->data['RiskAssessment']['system_table_id'] = $this->_get_system_table_id();
			$this->RiskAssessment->create();
			if ($this->RiskAssessment->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The risk assessment has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->RiskAssessment->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The risk assessment could not be saved. Please, try again.'));
			}
		}
		$processes = $this->RiskAssessment->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		// $projects = $this->RiskAssessment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$branches = $this->RiskAssessment->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$hazardTypes = $this->RiskAssessment->HazardType->find('list',array('conditions'=>array('HazardType.publish'=>1,'HazardType.soft_delete'=>0)));
		$hazardSources = $this->RiskAssessment->HazardSource->find('list',array('conditions'=>array('HazardSource.publish'=>1,'HazardSource.soft_delete'=>0)));
		$accidentTypes = $this->RiskAssessment->AccidentType->find('list',array('conditions'=>array('AccidentType.publish'=>1,'AccidentType.soft_delete'=>0)));
		$severiryTypes = $this->RiskAssessment->SeveriryType->find('list',array('conditions'=>array('SeveriryType.publish'=>1,'SeveriryType.soft_delete'=>0)));
		$injuryTypes = $this->RiskAssessment->InjuryType->find('list',array('conditions'=>array('InjuryType.publish'=>1,'InjuryType.soft_delete'=>0)));
		$systemTables = $this->RiskAssessment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->RiskAssessment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->RiskAssessment->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->RiskAssessment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->RiskAssessment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->RiskAssessment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->RiskAssessment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'projects', 'branches', 'hazardTypes', 'injuryTypes', 'hazardSources', 'accidentTypes', 'severiryTypes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->RiskAssessment->find('count');
		$published = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>1)));
		$unpublished = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>0)));
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
                        $this->request->data['RiskAssessment']['system_table_id'] = $this->_get_system_table_id();
			$this->RiskAssessment->create();
			if ($this->RiskAssessment->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='RiskAssessment';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->RiskAssessment->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The risk assessment has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->RiskAssessment->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The risk assessment could not be saved. Please, try again.'));
			}
		}
		$processes = $this->RiskAssessment->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		// $projects = $this->RiskAssessment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$branches = $this->RiskAssessment->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$hazardTypes = $this->RiskAssessment->HazardType->find('list',array('conditions'=>array('HazardType.publish'=>1,'HazardType.soft_delete'=>0)));
		$hazardSources = $this->RiskAssessment->HazardSource->find('list',array('conditions'=>array('HazardSource.publish'=>1,'HazardSource.soft_delete'=>0)));
		$accidentTypes = $this->RiskAssessment->AccidentType->find('list',array('conditions'=>array('AccidentType.publish'=>1,'AccidentType.soft_delete'=>0)));
		$severiryTypes = $this->RiskAssessment->SeveriryType->find('list',array('conditions'=>array('SeveriryType.publish'=>1,'SeveriryType.soft_delete'=>0)));
		$injuryTypes = $this->RiskAssessment->InjuryType->find('list',array('conditions'=>array('InjuryType.publish'=>1,'InjuryType.soft_delete'=>0)));
		$systemTables = $this->RiskAssessment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->RiskAssessment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->RiskAssessment->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->RiskAssessment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->RiskAssessment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->RiskAssessment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->RiskAssessment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'projects', 'branches', 'hazardTypes', 'injuryTypes', 'hazardSources', 'accidentTypes', 'severiryTypes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->RiskAssessment->find('count');
		$published = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>1)));
		$unpublished = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>0)));
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
		if (!$this->RiskAssessment->exists($id)) {
			throw new NotFoundException(__('Invalid risk assessment'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['RiskAssessment']['system_table_id'] = $this->_get_system_table_id();
			if ($this->RiskAssessment->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The risk assessment could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('RiskAssessment.' . $this->RiskAssessment->primaryKey => $id));
			$this->request->data = $this->RiskAssessment->find('first', $options);
		}
		$processes = $this->RiskAssessment->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		// $projects = $this->RiskAssessment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$branches = $this->RiskAssessment->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$hazardTypes = $this->RiskAssessment->HazardType->find('list',array('conditions'=>array('HazardType.publish'=>1,'HazardType.soft_delete'=>0)));
		$hazardSources = $this->RiskAssessment->HazardSource->find('list',array('conditions'=>array('HazardSource.publish'=>1,'HazardSource.soft_delete'=>0)));
		$accidentTypes = $this->RiskAssessment->AccidentType->find('list',array('conditions'=>array('AccidentType.publish'=>1,'AccidentType.soft_delete'=>0)));
		$severiryTypes = $this->RiskAssessment->SeveriryType->find('list',array('conditions'=>array('SeveriryType.publish'=>1,'SeveriryType.soft_delete'=>0)));
		$injuryTypes = $this->RiskAssessment->InjuryType->find('list',array('conditions'=>array('InjuryType.publish'=>1,'InjuryType.soft_delete'=>0)));
		$systemTables = $this->RiskAssessment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->RiskAssessment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->RiskAssessment->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->RiskAssessment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->RiskAssessment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->RiskAssessment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->RiskAssessment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'projects', 'branches', 'hazardTypes', 'injuryTypes', 'hazardSources', 'accidentTypes', 'severiryTypes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->RiskAssessment->find('count');
		$published = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>1)));
		$unpublished = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>0)));
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
		if (!$this->RiskAssessment->exists($id)) {
			throw new NotFoundException(__('Invalid risk assessment'));
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
			if ($this->RiskAssessment->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->RiskAssessment->save($this->request->data)) {
                $this->Session->setFlash(__('The risk assessment has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The risk assessment could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The risk assessment could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('RiskAssessment.' . $this->RiskAssessment->primaryKey => $id));
			$this->request->data = $this->RiskAssessment->find('first', $options);
		}
		$processes = $this->RiskAssessment->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		// $projects = $this->RiskAssessment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$branches = $this->RiskAssessment->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$hazardTypes = $this->RiskAssessment->HazardType->find('list',array('conditions'=>array('HazardType.publish'=>1,'HazardType.soft_delete'=>0)));
		$hazardSources = $this->RiskAssessment->HazardSource->find('list',array('conditions'=>array('HazardSource.publish'=>1,'HazardSource.soft_delete'=>0)));
		$accidentTypes = $this->RiskAssessment->AccidentType->find('list',array('conditions'=>array('AccidentType.publish'=>1,'AccidentType.soft_delete'=>0)));
		$severiryTypes = $this->RiskAssessment->SeveriryType->find('list',array('conditions'=>array('SeveriryType.publish'=>1,'SeveriryType.soft_delete'=>0)));
		$injuryTypes = $this->RiskAssessment->InjuryType->find('list',array('conditions'=>array('InjuryType.publish'=>1,'InjuryType.soft_delete'=>0)));
		$systemTables = $this->RiskAssessment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->RiskAssessment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->RiskAssessment->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->RiskAssessment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->RiskAssessment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->RiskAssessment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->RiskAssessment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'projects', 'branches', 'hazardTypes', 'injuryTypes', 'hazardSources', 'accidentTypes', 'severiryTypes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->RiskAssessment->find('count');
		$published = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>1)));
		$unpublished = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>0)));
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
		$this->RiskAssessment->id = $id;
		if (!$this->RiskAssessment->exists()) {
			throw new NotFoundException(__('Invalid risk assessment'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->RiskAssessment->delete()) {
			$this->Session->setFlash(__('Risk assessment deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Risk assessment was not deleted'));
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
		
		$result = explode('+',$this->request->data['riskAssessments']['rec_selected']);
		$this->RiskAssessment->recursive = 1;
		$riskAssessments = $this->RiskAssessment->find('all',array('RiskAssessment.publish'=>1,'RiskAssessment.soft_delete'=>1,'conditions'=>array('or'=>array('RiskAssessment.id'=>$result))));
		$this->set('riskAssessments', $riskAssessments);
		
		$processes = $this->RiskAssessment->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		// $projects = $this->RiskAssessment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$branches = $this->RiskAssessment->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$hazardTypes = $this->RiskAssessment->HazardType->find('list',array('conditions'=>array('HazardType.publish'=>1,'HazardType.soft_delete'=>0)));
		$hazardSources = $this->RiskAssessment->HazardSource->find('list',array('conditions'=>array('HazardSource.publish'=>1,'HazardSource.soft_delete'=>0)));
		$accidentTypes = $this->RiskAssessment->AccidentType->find('list',array('conditions'=>array('AccidentType.publish'=>1,'AccidentType.soft_delete'=>0)));
		$severiryTypes = $this->RiskAssessment->SeveriryType->find('list',array('conditions'=>array('SeveriryType.publish'=>1,'SeveriryType.soft_delete'=>0)));
		$injuryTypes = $this->RiskAssessment->InjuryType->find('list',array('conditions'=>array('InjuryType.publish'=>1,'InjuryType.soft_delete'=>0)));
		$systemTables = $this->RiskAssessment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->RiskAssessment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->RiskAssessment->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->RiskAssessment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->RiskAssessment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->RiskAssessment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->RiskAssessment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'projects', 'branches', 'hazardTypes', 'injuryTypes', 'hazardSources', 'accidentTypes', 'severiryTypes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->RiskAssessment->find('count');
		$published = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>1)));
		$unpublished = $this->RiskAssessment->find('count',array('conditions'=>array('RiskAssessment.publish'=>0)));
		$this->set(compact('count','published','unpublished'));
}
}

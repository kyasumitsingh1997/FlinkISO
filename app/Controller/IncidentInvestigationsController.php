<?php
App::uses('AppController', 'Controller');
/**
 * IncidentInvestigations Controller
 *
 * @property IncidentInvestigation $IncidentInvestigation
 */
class IncidentInvestigationsController extends AppController {

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
		if($this->request->params['pass'][0])$conditions = array_merge($conditions, array('IncidentInvestigation.incident_id'=>$this->request->params['pass'][0]));
		$this->paginate = array('order'=>array('IncidentInvestigation.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->IncidentInvestigation->recursive = 0;
		$this->set('incidentInvestigations', $this->paginate());
		
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
		$this->paginate = array('order'=>array('IncidentInvestigation.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->IncidentInvestigation->recursive = 0;
		$this->set('incidentInvestigations', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['IncidentInvestigation']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['IncidentInvestigation']['search_field'] as $search):
				$search_array[] = array('IncidentInvestigation.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('IncidentInvestigation.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->IncidentInvestigation->recursive = 0;
		$this->paginate = array('order'=>array('IncidentInvestigation.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'IncidentInvestigation.soft_delete'=>0 , $cons));
		$this->set('incidentInvestigations', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('IncidentInvestigation.'.$search => $search_key);
					else $search_array[] = array('IncidentInvestigation.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('IncidentInvestigation.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('IncidentInvestigation.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'IncidentInvestigation.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('IncidentInvestigation.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('IncidentInvestigation.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->IncidentInvestigation->recursive = 0;
		$this->paginate = array('order'=>array('IncidentInvestigation.sr_no'=>'DESC'),'conditions'=>$conditions , 'IncidentInvestigation.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('incidentInvestigations', $this->paginate());
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
		if (!$this->IncidentInvestigation->exists($id)) {
			throw new NotFoundException(__('Invalid incident investigation'));
		}
		$options = array('conditions' => array('IncidentInvestigation.' . $this->IncidentInvestigation->primaryKey => $id));
		$incidentInvestigation = $this->IncidentInvestigation->find('first', $options);
		$this->set('incidentInvestigation', $incidentInvestigation);
		
		$incidentId = $incidentInvestigation['IncidentInvestigation']['incident_id'];
		
		if($incidentId)$incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('all',array('conditions'=>array('IncidentAffectedPersonal.soft_delete'=>0,'IncidentAffectedPersonal.incident_id'=>$incidentId)));
		else $incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('all',array('conditions'=>array('IncidentAffectedPersonal.publish'=>1,'IncidentAffectedPersonal.soft_delete'=>0)));
		
		
		if($incidentId)$incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('all',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0,'IncidentWitness.incident_id'=>$incidentId)));
		else $incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('all',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0)));
		$this->set(compact('incidentAffectedPersonals', 'incidentWitnesses'));

	}



/**
 * list method
 *
 * @return void
 */
	public function lists($incidentId = null) {
	
        $this->_get_count();	
        $this->set('incidentId');

	}


/**
 * add_ajax method
 *
 * @return void
 */
	public function add_ajax($incidentId = null) {
	
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post')) {
                        $this->request->data['IncidentInvestigation']['system_table_id'] = $this->_get_system_table_id();
			$this->IncidentInvestigation->create();
			if ($this->IncidentInvestigation->save($this->request->data, false)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The incident investigation has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->IncidentInvestigation->id));
				else  $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The incident investigation could not be saved. Please, try again.'));
			}
		}
		$incidents = $this->IncidentInvestigation->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		
		if($incidentId)$incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('all',array('conditions'=>array('IncidentAffectedPersonal.soft_delete'=>0,'IncidentAffectedPersonal.incident_id'=>$incidentId)));
		else $incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('all',array('conditions'=>array('IncidentAffectedPersonal.publish'=>1,'IncidentAffectedPersonal.soft_delete'=>0)));
		
		
		if($incidentId)$incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('all',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0,'IncidentWitness.incident_id'=>$incidentId)));
		else $incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('all',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0)));
		
		$incidentInvestigators = $this->IncidentInvestigation->IncidentInvestigator->find('list',array('conditions'=>array('IncidentInvestigator.publish'=>1,'IncidentInvestigator.soft_delete'=>0)));
		$correctivePreventiveActions = $this->IncidentInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$systemTables = $this->IncidentInvestigation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('incidents', 'incidentAffectedPersonals', 'incidentWitnesses', 'incidentInvestigators', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->IncidentInvestigation->find('count');
		$published = $this->IncidentInvestigation->find('count',array('conditions'=>array('IncidentInvestigation.publish'=>1)));
		$unpublished = $this->IncidentInvestigation->find('count',array('conditions'=>array('IncidentInvestigation.publish'=>0)));
			
		$this->set(compact('count','published','unpublished','incidentId'));


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
                        $this->request->data['IncidentInvestigation']['system_table_id'] = $this->_get_system_table_id();
			$this->IncidentInvestigation->create();
			if ($this->IncidentInvestigation->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='IncidentInvestigation';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->IncidentInvestigation->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The incident investigation has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->IncidentInvestigation->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The incident investigation could not be saved. Please, try again.'));
			}
		}
		$incidents = $this->IncidentInvestigation->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		$incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('list',array('conditions'=>array('IncidentAffectedPersonal.publish'=>1,'IncidentAffectedPersonal.soft_delete'=>0)));
		$incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('list',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0)));
		$incidentInvestigators = $this->IncidentInvestigation->IncidentInvestigator->find('list',array('conditions'=>array('IncidentInvestigator.publish'=>1,'IncidentInvestigator.soft_delete'=>0)));
		$correctivePreventiveActions = $this->IncidentInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$systemTables = $this->IncidentInvestigation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('incidents', 'incidentAffectedPersonals', 'incidentWitnesses', 'incidentInvestigators', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->IncidentInvestigation->find('count');
	$published = $this->IncidentInvestigation->find('count',array('conditions'=>array('IncidentInvestigation.publish'=>1)));
	$unpublished = $this->IncidentInvestigation->find('count',array('conditions'=>array('IncidentInvestigation.publish'=>0)));
		
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
		if (!$this->IncidentInvestigation->exists($id)) {
			throw new NotFoundException(__('Invalid incident investigation'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['IncidentInvestigation']['system_table_id'] = $this->_get_system_table_id();
			if ($this->IncidentInvestigation->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The incident investigation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('IncidentInvestigation.' . $this->IncidentInvestigation->primaryKey => $id));
			$this->request->data = $this->IncidentInvestigation->find('first', $options);
		}
		$incidents = $this->IncidentInvestigation->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		
		if($incidentId)$incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('all',array('conditions'=>array('IncidentAffectedPersonal.soft_delete'=>0,'IncidentAffectedPersonal.incident_id'=>$incidentId)));
		else $incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('all',array('conditions'=>array('IncidentAffectedPersonal.publish'=>1,'IncidentAffectedPersonal.soft_delete'=>0)));
		
		
		if($incidentId)$incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('all',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0,'IncidentWitness.incident_id'=>$incidentId)));
		else $incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('all',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0)));

		$incidentInvestigators = $this->IncidentInvestigation->IncidentInvestigator->find('list',array('conditions'=>array('IncidentInvestigator.publish'=>1,'IncidentInvestigator.soft_delete'=>0)));
		$correctivePreventiveActions = $this->IncidentInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$systemTables = $this->IncidentInvestigation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('incidents', 'incidentAffectedPersonals', 'incidentWitnesses', 'incidentInvestigators', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->IncidentInvestigation->find('count');
		$published = $this->IncidentInvestigation->find('count',array('conditions'=>array('IncidentInvestigation.publish'=>1)));
		$unpublished = $this->IncidentInvestigation->find('count',array('conditions'=>array('IncidentInvestigation.publish'=>0)));
		
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
		if (!$this->IncidentInvestigation->exists($id)) {
			throw new NotFoundException(__('Invalid incident investigation'));
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
			if ($this->IncidentInvestigation->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->IncidentInvestigation->save($this->request->data)) {
                $this->Session->setFlash(__('The incident investigation has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The incident investigation could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The incident investigation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('IncidentInvestigation.' . $this->IncidentInvestigation->primaryKey => $id));
			$this->request->data = $this->IncidentInvestigation->find('first', $options);
		}
		$incidents = $this->IncidentInvestigation->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		
		if($incidentId)$incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('all',array('conditions'=>array('IncidentAffectedPersonal.soft_delete'=>0,'IncidentAffectedPersonal.incident_id'=>$incidentId)));
		else $incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('all',array('conditions'=>array('IncidentAffectedPersonal.publish'=>1,'IncidentAffectedPersonal.soft_delete'=>0)));
		
		
		if($incidentId)$incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('all',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0,'IncidentWitness.incident_id'=>$incidentId)));
		else $incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('all',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0)));

		$incidentInvestigators = $this->IncidentInvestigation->IncidentInvestigator->find('list',array('conditions'=>array('IncidentInvestigator.publish'=>1,'IncidentInvestigator.soft_delete'=>0)));
		$correctivePreventiveActions = $this->IncidentInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$systemTables = $this->IncidentInvestigation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('incidents', 'incidentAffectedPersonals', 'incidentWitnesses', 'incidentInvestigators', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->IncidentInvestigation->find('count');
		$published = $this->IncidentInvestigation->find('count',array('conditions'=>array('IncidentInvestigation.publish'=>1)));
		$unpublished = $this->IncidentInvestigation->find('count',array('conditions'=>array('IncidentInvestigation.publish'=>0)));
		
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
		$this->IncidentInvestigation->id = $id;
		if (!$this->IncidentInvestigation->exists()) {
			throw new NotFoundException(__('Invalid incident investigation'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->IncidentInvestigation->delete()) {
			$this->Session->setFlash(__('Incident investigation deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Incident investigation was not deleted'));
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
		
		$result = explode('+',$this->request->data['incidentInvestigations']['rec_selected']);
		$this->IncidentInvestigation->recursive = 1;
		$incidentInvestigations = $this->IncidentInvestigation->find('all',array('IncidentInvestigation.publish'=>1,'IncidentInvestigation.soft_delete'=>1,'conditions'=>array('or'=>array('IncidentInvestigation.id'=>$result))));
		$this->set('incidentInvestigations', $incidentInvestigations);
		
				$incidents = $this->IncidentInvestigation->Incident->find('list',array('conditions'=>array('Incident.publish'=>1,'Incident.soft_delete'=>0)));
		$incidentAffectedPersonals = $this->IncidentInvestigation->IncidentAffectedPersonal->find('list',array('conditions'=>array('IncidentAffectedPersonal.publish'=>1,'IncidentAffectedPersonal.soft_delete'=>0)));
		$incidentWitnesses = $this->IncidentInvestigation->IncidentWitness->find('list',array('conditions'=>array('IncidentWitness.publish'=>1,'IncidentWitness.soft_delete'=>0)));
		$incidentInvestigators = $this->IncidentInvestigation->IncidentInvestigator->find('list',array('conditions'=>array('IncidentInvestigator.publish'=>1,'IncidentInvestigator.soft_delete'=>0)));
		$correctivePreventiveActions = $this->IncidentInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$systemTables = $this->IncidentInvestigation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->IncidentInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->IncidentInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->IncidentInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->IncidentInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->IncidentInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->IncidentInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('incidents', 'incidentAffectedPersonals', 'incidentWitnesses', 'incidentInvestigators', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'incidents', 'incidentAffectedPersonals', 'incidentWitnesses', 'incidentInvestigators', 'correctivePreventiveActions', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

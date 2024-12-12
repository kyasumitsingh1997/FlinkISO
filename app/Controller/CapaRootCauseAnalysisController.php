<?php
App::uses('AppController', 'Controller');
/**
 * CapaRootCauseAnalysis Controller
 *
 * @property CapaRootCauseAnalysi $CapaRootCauseAnalysi
 */
class CapaRootCauseAnalysisController extends AppController {

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
		$this->paginate = array('order'=>array('CapaRootCauseAnalysi.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->CapaRootCauseAnalysi->recursive = 0;
		$this->set('capaRootCauseAnalysis', $this->paginate());
		
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
		$this->paginate = array('order'=>array('CapaRootCauseAnalysi.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->CapaRootCauseAnalysi->recursive = 0;
		$this->set('capaRootCauseAnalysis', $this->paginate());
		
		$this->_get_count();
	}
public function capa_assigned() {
		
		
                $this->paginate = array('limit' => 2,
                'order' => array('CapaRootCauseAnalysi.sr_no' => 'DESC'),
                'fields' => array(

                    'CorrectivePreventiveAction.name',
                    'CorrectivePreventiveAction.id',
                    'CapaRootCauseAnalysi.*',
                    'DeterminedBy.name',
                    'DeterminedBy.id'
                   

                ),
                'conditions' => array('OR' => array(
                    'CapaRootCauseAnalysi.employee_id' => $this->Session->read('User.employee_id'),
                       'CapaRootCauseAnalysi.action_assigned_to' => $this->Session->read('User.employee_id'),
                    'CapaRootCauseAnalysi.determined_by' => $this->Session->read('User.employee_id')
                   
                ), 'CapaRootCauseAnalysi.current_status' => 0,
                'CapaRootCauseAnalysi.soft_delete' => 0, 'CapaRootCauseAnalysi.publish' => 1),
            'recursive' => 0);

            $assignedCapas = $this->paginate();
            $this->set(array('capaRootCauseAnalysis' => $assignedCapas));
	}
/**
 * search method
 * Dynamic by - TGS
 * @return void
 */
	public function search() {
		if ($this->request->is('post')) {
	
	$search_array = array();
		$search_keys = explode(" ",$this->request->data['CapaRootCauseAnalysi']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['CapaRootCauseAnalysi']['search_field'] as $search):
				$search_array[] = array('CapaRootCauseAnalysi.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('CapaRootCauseAnalysi.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->CapaRootCauseAnalysi->recursive = 0;
		$this->paginate = array('order'=>array('CapaRootCauseAnalysi.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'CapaRootCauseAnalysi.soft_delete'=>0 , $cons));
		$this->set('capaRootCauseAnalysis', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('CapaRootCauseAnalysi.'.$search => $search_key);
					else $search_array[] = array('CapaRootCauseAnalysi.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('CapaRootCauseAnalysi.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('CapaRootCauseAnalysi.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'CapaRootCauseAnalysi.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('CapaRootCauseAnalysi.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('CapaRootCauseAnalysi.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->CapaRootCauseAnalysi->recursive = 0;
		$this->paginate = array('order'=>array('CapaRootCauseAnalysi.sr_no'=>'DESC'),'conditions'=>$conditions , 'CapaRootCauseAnalysi.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('capaRootCauseAnalysis', $this->paginate());
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
		if (!$this->CapaRootCauseAnalysi->exists($id)) {
			throw new NotFoundException(__('Invalid capa root cause analysi'));
		}
		$options = array('conditions' => array('CapaRootCauseAnalysi.' . $this->CapaRootCauseAnalysi->primaryKey => $id));
		$this->set('capaRootCauseAnalysi', $this->CapaRootCauseAnalysi->find('first', $options));
	}



/**
 * list method
 *
 * @return void
 */
	public function lists($capaId = null) {
	
            $this->_get_count();	
            $this->set(compact('capaId'));

	}


/**
 * add_ajax method
 *
 * @return void
 */
	public function add_ajax($capaId = null, $modal = null) {
	
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post')) {
			$this->request->data['CapaRootCauseAnalysi']['system_table_id'] = $this->_get_system_table_id();
			$this->CapaRootCauseAnalysi->create();
			if ($this->CapaRootCauseAnalysi->save($this->request->data)) {
				$this->root_cause_send_reminder($this->CapaRootCauseAnalysi->id);
				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The capa root cause analysis has been saved'));
				if ($this->_show_evidence() == true) $this->redirect(array('action' => 'view', $this->CapaRootCauseAnalysi->id));
				else $this->redirect(array('action' => 'index'));
				
			} else {
				$this->Session->setFlash(__('The capa root cause analysis could not be saved. Please, try again.'));
			}
		}
		$correctivePreventiveActions = $this->CapaRootCauseAnalysi->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		
		$employees = $this->CapaRootCauseAnalysi->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRootCauseAnalysi->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRootCauseAnalysi->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRootCauseAnalysi->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRootCauseAnalysi->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRootCauseAnalysi->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRootCauseAnalysi->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRootCauseAnalysi->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->CapaRootCauseAnalysi->find('count');
	$published = $this->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.publish'=>1)));
	$unpublished = $this->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.publish'=>0)));
		
	$this->set(compact('count','published','unpublished', 'capaId','modal'));

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
                        $this->request->data['CapaRootCauseAnalysi']['system_table_id'] = $this->_get_system_table_id();
			$this->CapaRootCauseAnalysi->create();
			if ($this->CapaRootCauseAnalysi->save($this->request->data)) {
				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='CapaRootCauseAnalysi';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->CapaRootCauseAnalysi->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The capa root cause analysi has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->CapaRootCauseAnalysi->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The capa root cause analysi could not be saved. Please, try again.'));
			}
		}
		$correctivePreventiveActions = $this->CapaRootCauseAnalysi->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$employees = $this->CapaRootCauseAnalysi->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRootCauseAnalysi->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRootCauseAnalysi->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRootCauseAnalysi->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRootCauseAnalysi->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRootCauseAnalysi->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRootCauseAnalysi->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRootCauseAnalysi->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->CapaRootCauseAnalysi->find('count');
	$published = $this->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.publish'=>1)));
	$unpublished = $this->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.publish'=>0)));
		
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
		if (!$this->CapaRootCauseAnalysi->exists($id)) {
			throw new NotFoundException(__('Invalid capa root cause analysi'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['CapaRootCauseAnalysi']['system_table_id'] = $this->_get_system_table_id();
			if ($this->CapaRootCauseAnalysi->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The capa root cause analysi could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CapaRootCauseAnalysi.' . $this->CapaRootCauseAnalysi->primaryKey => $id));
			$this->request->data = $this->CapaRootCauseAnalysi->find('first', $options);
		}
		$correctivePreventiveActions = $this->CapaRootCauseAnalysi->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$employees = $this->CapaRootCauseAnalysi->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRootCauseAnalysi->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRootCauseAnalysi->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRootCauseAnalysi->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRootCauseAnalysi->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRootCauseAnalysi->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRootCauseAnalysi->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRootCauseAnalysi->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->CapaRootCauseAnalysi->find('count');
		$published = $this->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.publish'=>1)));
		$unpublished = $this->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.publish'=>0)));
		
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
		if (!$this->CapaRootCauseAnalysi->exists($id)) {
			throw new NotFoundException(__('Invalid capa root cause analysi'));
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
			if ($this->CapaRootCauseAnalysi->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->CapaRootCauseAnalysi->save($this->request->data)) {
                $this->Session->setFlash(__('The capa root cause analysi has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The capa root cause analysi could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The capa root cause analysi could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CapaRootCauseAnalysi.' . $this->CapaRootCauseAnalysi->primaryKey => $id));
			$this->request->data = $this->CapaRootCauseAnalysi->find('first', $options);
		}
		$correctivePreventiveActions = $this->CapaRootCauseAnalysi->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$employees = $this->CapaRootCauseAnalysi->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRootCauseAnalysi->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRootCauseAnalysi->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRootCauseAnalysi->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRootCauseAnalysi->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRootCauseAnalysi->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRootCauseAnalysi->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRootCauseAnalysi->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->CapaRootCauseAnalysi->find('count');
		$published = $this->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.publish'=>1)));
		$unpublished = $this->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.publish'=>0)));
		
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
		$this->CapaRootCauseAnalysi->id = $id;
		if (!$this->CapaRootCauseAnalysi->exists()) {
			throw new NotFoundException(__('Invalid capa root cause analysi'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CapaRootCauseAnalysi->delete()) {
			$this->Session->setFlash(__('Capa root cause analysi deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Capa root cause analysi was not deleted'));
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
		
		$result = explode('+',$this->request->data['capaRootCauseAnalysis']['rec_selected']);
		$this->CapaRootCauseAnalysi->recursive = 1;
		$capaRootCauseAnalysis = $this->CapaRootCauseAnalysi->find('all',array('CapaRootCauseAnalysi.publish'=>1,'CapaRootCauseAnalysi.soft_delete'=>1,'conditions'=>array('or'=>array('CapaRootCauseAnalysi.id'=>$result))));
		$this->set('capaRootCauseAnalysis', $capaRootCauseAnalysis);
		
				$correctivePreventiveActions = $this->CapaRootCauseAnalysi->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$employees = $this->CapaRootCauseAnalysi->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRootCauseAnalysi->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRootCauseAnalysi->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRootCauseAnalysi->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRootCauseAnalysi->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRootCauseAnalysi->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRootCauseAnalysi->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRootCauseAnalysi->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function pending_tasks(){
       	if($this->request->params['pass'][0]){
       		$conditions = null;
       		$capa_condition = array('CapaRootCauseAnalysi.corrective_preventive_action_id'=>$this->request->params['pass'][0]);
       	}else{
       		$capa_condition = null;	
       		$conditions = $this->_check_request();
       	} 
       	//1 close 0 open
       	$conditions = array('CapaRootCauseAnalysi.current_status'=>1);
       	$this->paginate = array('order'=>array('CapaRootCauseAnalysi.sr_no'=>'DESC'),'conditions'=>array($conditions,$capa_condition));

       	$this->CapaInvestigation->recursive = 0;
       	$this->set('capaRootCauseAnalysis', $this->paginate());

       	$this->_get_count();		
		// $this->render('index');
       }

	public function root_cause_send_reminder($id = null){
       	if($id)$id = $id;
		else $id = $this->request->params['pass'][0];
       	$cc = $this->CapaRootCauseAnalysi->find('first',array('recursive'=>-1, 'conditions'=>array('CapaRootCauseAnalysi.id'=>$id)));
       	$employee = $this->CapaRootCauseAnalysi->Employee->find('first',array(
       		'recursive'=>-1,
       		'fields'=>array('Employee.id','Employee.name','Employee.personal_email','Employee.office_email'),
       		'conditions'=>array('Employee.id'=>$cc['CapaRootCauseAnalysi']['action_assigned_to'])));
       	$officeEmailId = $employee['Employee']['office_email'];
       	$personalEmailId = $employee['Employee']['personal_email'];
       	if ($officeEmailId != '') {
       		$email = $officeEmailId;
       	} else if ($personalEmailId != '') {
       		$email = $personalEmailId;
       	}
       	if($cc && $email){
       		$send_message = "Pending CAPA Capa Root Cause Analysis For Action";
       		$body = "<p>You have pending capa root cause analysis to address. Please login to FlinkISO and add details</p>";
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
       				'env' => $env, 'app_url' => FULL_BASE_URL));
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

<?php
App::uses('AppController', 'Controller');
/**
 * ProjectEstimates Controller
 *
 * @property ProjectEstimate $ProjectEstimate
 * @property PaginatorComponent $Paginator
 */
class ProjectEstimatesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

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
		$this->paginate = array('order'=>array('ProjectEstimate.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProjectEstimate->recursive = 0;
		$this->set('projectEstimates', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectEstimate.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectEstimate->recursive = 0;
		$this->set('projectEstimates', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectEstimate']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectEstimate']['search_field'] as $search):
				$search_array[] = array('ProjectEstimate.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectEstimate.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectEstimate->recursive = 0;
		$this->paginate = array('order'=>array('ProjectEstimate.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectEstimate.soft_delete'=>0 , $cons));
		$this->set('projectEstimates', $this->paginate());
		}
                $this->render('index');
	}

/**
 * adcanced_search method
 * Advanced search by - TGS
 * @return void
 */
	public function advanced_search() {
		if ($this->request->is('post')) {
		$conditions = array();
			if($this->request->query['keywords']){
				$search_array = array();
				$search_keys = explode(" ",$this->request->query['keywords']);
	
				foreach($search_keys as $search_key):
					foreach($this->request->query['search_fields'] as $search):
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectEstimate.'.$search => $search_key);
					else $search_array[] = array('ProjectEstimate.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectEstimate.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectEstimate.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectEstimate.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectEstimate.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectEstimate.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectEstimate->recursive = 0;
		$this->paginate = array('order'=>array('ProjectEstimate.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectEstimate.soft_delete'=>0 );
		$this->set('projectEstimates', $this->paginate());
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
		if (!$this->ProjectEstimate->exists($id)) {
			throw new NotFoundException(__('Invalid project estimate'));
		}
		$options = array('conditions' => array('ProjectEstimate.' . $this->ProjectEstimate->primaryKey => $id));
		$this->set('projectEstimate', $this->ProjectEstimate->find('first', $options));
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
			$this->request->data['ProjectEstimate']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectEstimate->create();
			if ($this->ProjectEstimate->save($this->request->data)) {

				$this->_updateprojectestimate($this->request->data['ProjectEstimate']['project_id']);
				
				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The project estimate has been saved'));
				// if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectEstimate->id));
				// else $this->redirect(array('action' => 'index'));
				
				// $this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->data['ProjectEstimate']['project_id']));
				
			} else {
				$this->Session->setFlash(__('The project estimate could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectEstimate->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectEstimate->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$costCategories = $this->ProjectEstimate->CostCategory->find('list',array('conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		$systemTables = $this->ProjectEstimate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectEstimate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectEstimate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectEstimate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectEstimate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectEstimate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'costCategories', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','milestones'));
		$count = $this->ProjectEstimate->find('count');
		$published = $this->ProjectEstimate->find('count',array('conditions'=>array('ProjectEstimate.publish'=>1)));
		$unpublished = $this->ProjectEstimate->find('count',array('conditions'=>array('ProjectEstimate.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

	}





/**
 * add method
 *
 * @return void
 */
	// public function add() {
	
	// 	if($this->_show_approvals()){
	// 		$this->loadModel('User');
	// 		$this->User->recursive = 0;
	// 		$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_approvar'=>1)));
	// 		$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
	// 	}
		
	// 	if ($this->request->is('post')) {
 //                        $this->request->data['ProjectEstimate']['system_table_id'] = $this->_get_system_table_id();
	// 		$this->ProjectEstimate->create();
	// 		if ($this->ProjectEstimate->save($this->request->data)) {

	// 			if($this->_show_approvals()){
	// 				$this->loadModel('Approval');
	// 				$this->Approval->create();
	// 				$this->request->data['Approval']['model_name']='ProjectEstimate';
	// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
	// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
	// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['record']=$this->ProjectEstimate->id;
	// 				$this->Approval->save($this->request->data['Approval']);
	// 			}
	// 			$this->Session->setFlash(__('The project estimate has been saved'));
	// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectEstimate->id));
	// 			else $this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The project estimate could not be saved. Please, try again.'));
	// 		}
	// 	}
	// 	$projects = $this->ProjectEstimate->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
	// 	$milestones = $this->ProjectEstimate->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
	// 	$costCategories = $this->ProjectEstimate->CostCategory->find('list',array('conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
	// 	$systemTables = $this->ProjectEstimate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	// 	$masterListOfFormats = $this->ProjectEstimate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	// 	$preparedBies = $this->ProjectEstimate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	// 	$approvedBies = $this->ProjectEstimate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	// 	$createdBies = $this->ProjectEstimate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	// 	$modifiedBies = $this->ProjectEstimate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	// 	$this->set(compact('projects', 'costCategories', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','milestones'));
		
	// 	$count = $this->ProjectEstimate->find('count');
	// 	$published = $this->ProjectEstimate->find('count',array('conditions'=>array('ProjectEstimate.publish'=>1)));
	// 	$unpublished = $this->ProjectEstimate->find('count',array('conditions'=>array('ProjectEstimate.publish'=>0)));
			
	// 	$this->set(compact('count','published','unpublished'));

	// }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ProjectEstimate->exists($id)) {
			throw new NotFoundException(__('Invalid project estimate'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectEstimate']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ProjectEstimate->save($this->request->data)) {
				$this->_updateprojectestimate($this->request->data['ProjectEstimate']['project_id']);
				if ($this->_show_approvals()) $this->_save_approvals();
				
				$this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->data['ProjectEstimate']['project_id']));
				// if ($this->_show_evidence() == true)
				//  $this->redirect(array('action' => 'view', $id));
				// else
		 	// 		$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project estimate could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectEstimate.' . $this->ProjectEstimate->primaryKey => $id));
			$this->request->data = $this->ProjectEstimate->find('first', $options);
		}
		$projects = $this->ProjectEstimate->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectEstimate->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$costCategories = $this->ProjectEstimate->CostCategory->find('list',array('conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		$systemTables = $this->ProjectEstimate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectEstimate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectEstimate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectEstimate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectEstimate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectEstimate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'costCategories', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','milestones'));
		$count = $this->ProjectEstimate->find('count');
		$published = $this->ProjectEstimate->find('count',array('conditions'=>array('ProjectEstimate.publish'=>1)));
		$unpublished = $this->ProjectEstimate->find('count',array('conditions'=>array('ProjectEstimate.publish'=>0)));
		
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
		if (!$this->ProjectEstimate->exists($id)) {
			throw new NotFoundException(__('Invalid project estimate'));
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
			if ($this->ProjectEstimate->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectEstimate->save($this->request->data)) {
                $this->Session->setFlash(__('The project estimate has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project estimate could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project estimate could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectEstimate.' . $this->ProjectEstimate->primaryKey => $id));
			$this->request->data = $this->ProjectEstimate->find('first', $options);
		}
		$projects = $this->ProjectEstimate->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$costCategories = $this->ProjectEstimate->CostCategory->find('list',array('conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		$systemTables = $this->ProjectEstimate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectEstimate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectEstimate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectEstimate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectEstimate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectEstimate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'costCategories', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectEstimate->find('count');
		$published = $this->ProjectEstimate->find('count',array('conditions'=>array('ProjectEstimate.publish'=>1)));
		$unpublished = $this->ProjectEstimate->find('count',array('conditions'=>array('ProjectEstimate.publish'=>0)));
		
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
		$this->ProjectEstimate->id = $id;
		if (!$this->ProjectEstimate->exists()) {
			throw new NotFoundException(__('Invalid project estimate'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectEstimate->delete()) {
			$this->Session->setFlash(__('Project estimate deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project estimate was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $parent_id = NULL) {
	
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
		
		$result = explode('+',$this->request->data['projectEstimates']['rec_selected']);
		$this->ProjectEstimate->recursive = 1;
		$projectEstimates = $this->ProjectEstimate->find('all',array('ProjectEstimate.publish'=>1,'ProjectEstimate.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectEstimate.id'=>$result))));
		$this->set('projectEstimates', $projectEstimates);
		
				$projects = $this->ProjectEstimate->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$costCategories = $this->ProjectEstimate->CostCategory->find('list',array('conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		$systemTables = $this->ProjectEstimate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectEstimate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectEstimate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectEstimate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectEstimate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectEstimate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'costCategories', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'costCategories', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

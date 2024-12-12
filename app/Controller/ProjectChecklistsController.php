<?php
App::uses('AppController', 'Controller');
/**
 * ProjectChecklists Controller
 *
 * @property ProjectChecklist $ProjectChecklist
 * @property PaginatorComponent $Paginator
 */
class ProjectChecklistsController extends AppController {

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
		
		// $conditions = $this->_check_request();
		// $this->paginate = array('order'=>array('ProjectChecklist.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		// $this->ProjectChecklist->recursive = 0;
		// $this->set('projectChecklists', $this->paginate());
		
		// $this->_get_count();
	}


 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('ProjectChecklist.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectChecklist->recursive = 0;
		$this->set('projectChecklists', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectChecklist']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectChecklist']['search_field'] as $search):
				$search_array[] = array('ProjectChecklist.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectChecklist.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectChecklist->recursive = 0;
		$this->paginate = array('order'=>array('ProjectChecklist.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectChecklist.soft_delete'=>0 , $cons));
		$this->set('projectChecklists', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectChecklist.'.$search => $search_key);
					else $search_array[] = array('ProjectChecklist.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectChecklist.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectChecklist.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectChecklist.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectChecklist.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectChecklist.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectChecklist->recursive = 0;
		$this->paginate = array('order'=>array('ProjectChecklist.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectChecklist.soft_delete'=>0 );
		$this->set('projectChecklists', $this->paginate());
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
		if (!$this->ProjectChecklist->exists($id)) {
			throw new NotFoundException(__('Invalid project checklist'));
		}
		$options = array('conditions' => array('ProjectChecklist.' . $this->ProjectChecklist->primaryKey => $id));
		$this->set('projectChecklist', $this->ProjectChecklist->find('first', $options));
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
			$this->request->data['ProjectChecklist']['system_table_id'] = $this->_get_system_table_id();
			
			// Configure::write('debug',1);
			if($this->request->data['ProjectChecklist']['name']){
				$str = split(PHP_EOL, $this->request->data['ProjectChecklist']['name']);
				foreach ($str as $key => $value) {
					if(ltrim(rtrim($value))){
						$data['ProjectChecklist']['name'] = ltrim(rtrim($value));
						$data['ProjectChecklist']['project_process_plan_id'] = $this->request->data['ProjectChecklist']['project_process_plan_id'];
						$data['ProjectChecklist']['project_id'] = $this->request->data['ProjectChecklist']['project_id'];
						$data['ProjectChecklist']['milestone_id'] = $this->request->data['ProjectChecklist']['milestone_id'];
						$data['ProjectChecklist']['publish'] = 1;
						$data['ProjectChecklist']['soft_delete'] = 0;
						$data['ProjectChecklist']['prepared_by'] = $this->Session->read('User.employee_id');
						$this->ProjectChecklist->create();
						$this->ProjectChecklist->save($data,false);	
					}
					
				}
				
			}
				$this->Session->setFlash(__('The project checklist is saved'));
				$this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->data['ProjectChecklist']['project_id']));
			// exit;
			// if ($this->ProjectChecklist->save($this->request->data)) {

			// 	Configure::write('debug',1);
			// 	debug($this->request->data);
			// 	exit;
			// 	if ($this->_show_approvals()) $this->_save_approvals();
			// 	$this->Session->setFlash(__('The project checklist has been saved'));
			// 	if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectChecklist->id));
			// 	else $this->redirect(array('action' => 'index'));
			// } else {
			// 	$this->Session->setFlash(__('The project checklist could not be saved. Please, try again.'));
			// }
		}
		$projects = $this->ProjectChecklist->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectChecklist->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->ProjectChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ProjectChecklist->find('count');
	$published = $this->ProjectChecklist->find('count',array('conditions'=>array('ProjectChecklist.publish'=>1)));
	$unpublished = $this->ProjectChecklist->find('count',array('conditions'=>array('ProjectChecklist.publish'=>0)));
		
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
                        $this->request->data['ProjectChecklist']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectChecklist->create();
			if ($this->ProjectChecklist->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='ProjectChecklist';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->ProjectChecklist->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The project checklist has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectChecklist->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project checklist could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectChecklist->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectChecklist->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->ProjectChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ProjectChecklist->find('count');
	$published = $this->ProjectChecklist->find('count',array('conditions'=>array('ProjectChecklist.publish'=>1)));
	$unpublished = $this->ProjectChecklist->find('count',array('conditions'=>array('ProjectChecklist.publish'=>0)));
		
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
		if (!$this->ProjectChecklist->exists($id)) {
			throw new NotFoundException(__('Invalid project checklist'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectChecklist']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ProjectChecklist->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project checklist could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectChecklist.' . $this->ProjectChecklist->primaryKey => $id));
			$this->request->data = $this->ProjectChecklist->find('first', $options);
		}
		$projects = $this->ProjectChecklist->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectChecklist->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->ProjectChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectChecklist->find('count');
		$published = $this->ProjectChecklist->find('count',array('conditions'=>array('ProjectChecklist.publish'=>1)));
		$unpublished = $this->ProjectChecklist->find('count',array('conditions'=>array('ProjectChecklist.publish'=>0)));
		
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
		if (!$this->ProjectChecklist->exists($id)) {
			throw new NotFoundException(__('Invalid project checklist'));
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
			if ($this->ProjectChecklist->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectChecklist->save($this->request->data)) {
                $this->Session->setFlash(__('The project checklist has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project checklist could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project checklist could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectChecklist.' . $this->ProjectChecklist->primaryKey => $id));
			$this->request->data = $this->ProjectChecklist->find('first', $options);
		}
		$projects = $this->ProjectChecklist->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectChecklist->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->ProjectChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectChecklist->find('count');
		$published = $this->ProjectChecklist->find('count',array('conditions'=>array('ProjectChecklist.publish'=>1)));
		$unpublished = $this->ProjectChecklist->find('count',array('conditions'=>array('ProjectChecklist.publish'=>0)));
		
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
		$this->ProjectChecklist->id = $id;
		if (!$this->ProjectChecklist->exists()) {
			throw new NotFoundException(__('Invalid project checklist'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectChecklist->delete()) {
			$this->Session->setFlash(__('Project checklist deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project checklist was not deleted'));
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
		$this->autoRender = false;
		if($id){
			$this->loadModel('FileProcess');
			// check of there are file
			$files = $this->FileProcess->find('count',array('conditions'=>array('FileProcess.checklist LIKE '=> '%"'.$id.'"%')));
			if($files > 0){
				return "Can not delete checklist as it is linked with file process.";
			}else{
				$this->ProjectChecklist->deleteAll(array('ProjectChecklist.id'=>$id));
				return "Checklist Deleted";
			}
		}else{
			return "Checklist can not be found";
		}
        exit;
    // }	
    // $this->redirect(array('action' => 'index'));
     
    
}
	
	
	
	public function report(){
		
		$result = explode('+',$this->request->data['projectChecklists']['rec_selected']);
		$this->ProjectChecklist->recursive = 1;
		$projectChecklists = $this->ProjectChecklist->find('all',array('ProjectChecklist.publish'=>1,'ProjectChecklist.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectChecklist.id'=>$result))));
		$this->set('projectChecklists', $projectChecklists);
		
				$projects = $this->ProjectChecklist->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectChecklist->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->ProjectChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function inplace_edit_name(){
		$this->autoRender = false;
		if($this->request->data['pk'] && $this->request->data['value']){
			$this->ProjectChecklist->read(null,$this->request->data['pk']);
			$this->ProjectChecklist->set('name',$this->request->data['value']);
			$this->ProjectChecklist->save();
			return true;
		}elseif($this->request->data['pk'] && !$this->request->data['value']){
			// $this->ProjectChecklist->read(null,$this->request->data['pk']);
			// $this->ProjectChecklist->set('soft_delete',1);
			// $this->ProjectChecklist->save();
			return true;
		}else{
			return true;
		}
	}

	public function updateprocess($val = null,$id = null){
		$this->autoRender = false;
		if($val != -1){
			$rec = $this->ProjectChecklist->find('first',array('recursive'=>-1,'conditions'=>array('ProjectChecklist.id'=>$id)));
			$rec['ProjectChecklist']['project_process_plan_id'] = $val;
			$this->ProjectChecklist->create();
			$this->ProjectChecklist->save($rec);
			return true;
		}else{
			return false;
		}
		

	}
}

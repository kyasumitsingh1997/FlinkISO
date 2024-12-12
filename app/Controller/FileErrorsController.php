<?php
App::uses('AppController', 'Controller');
/**
 * FileErrors Controller
 *
 * @property FileError $FileError
 * @property PaginatorComponent $Paginator
 */
class FileErrorsController extends AppController {

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
		$this->paginate = array('order'=>array('FileError.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->FileError->recursive = 0;
		$this->set('fileErrors', $this->paginate());
		
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
		$this->paginate = array('order'=>array('FileError.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->FileError->recursive = 0;
		$this->set('fileErrors', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['FileError']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['FileError']['search_field'] as $search):
				$search_array[] = array('FileError.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('FileError.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->FileError->recursive = 0;
		$this->paginate = array('order'=>array('FileError.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'FileError.soft_delete'=>0 , $cons));
		$this->set('fileErrors', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('FileError.'.$search => $search_key);
					else $search_array[] = array('FileError.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('FileError.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('FileError.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'FileError.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('FileError.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('FileError.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->FileError->recursive = 0;
		$this->paginate = array('order'=>array('FileError.sr_no'=>'DESC'),'conditions'=>$conditions , 'FileError.soft_delete'=>0 );
		$this->set('fileErrors', $this->paginate());
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
		if (!$this->FileError->exists($id)) {
			throw new NotFoundException(__('Invalid file error'));
		}
		$options = array('conditions' => array('FileError.' . $this->FileError->primaryKey => $id));
		$this->set('fileError', $this->FileError->find('first', $options));
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
			// $this->request->data['FileError']['system_table_id'] = $this->_get_system_table_id();
			$this->FileError->create();

			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;
			

			if ($this->FileError->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The file error has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->FileError->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The file error could not be saved. Please, try again.'));
			}
		}
		$projects = $this->FileError->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileError->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$fileErrorMasters = $this->FileError->FileErrorMaster->find('list',array('conditions'=>array('FileErrorMaster.publish'=>1,'FileErrorMaster.soft_delete'=>0)));
		$systemTables = $this->FileError->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileError->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileError->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileError->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileError->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileError->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projects', 'milestones', 'fileErrorMasters', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->FileError->find('count');
	$published = $this->FileError->find('count',array('conditions'=>array('FileError.publish'=>1)));
	$unpublished = $this->FileError->find('count',array('conditions'=>array('FileError.publish'=>0)));
		
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
                        $this->request->data['FileError']['system_table_id'] = $this->_get_system_table_id();
			$this->FileError->create();
			if ($this->FileError->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='FileError';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->FileError->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The file error has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->FileError->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The file error could not be saved. Please, try again.'));
			}
		}
		$projects = $this->FileError->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileError->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$fileErrorMasters = $this->FileError->FileErrorMaster->find('list',array('conditions'=>array('FileErrorMaster.publish'=>1,'FileErrorMaster.soft_delete'=>0)));
		$systemTables = $this->FileError->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileError->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileError->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileError->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileError->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileError->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projects', 'milestones', 'fileErrorMasters', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->FileError->find('count');
	$published = $this->FileError->find('count',array('conditions'=>array('FileError.publish'=>1)));
	$unpublished = $this->FileError->find('count',array('conditions'=>array('FileError.publish'=>0)));
		
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
		if (!$this->FileError->exists($id)) {
			throw new NotFoundException(__('Invalid file error'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['FileError']['system_table_id'] = $this->_get_system_table_id();
			if ($this->FileError->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The file error could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FileError.' . $this->FileError->primaryKey => $id));
			$this->request->data = $this->FileError->find('first', $options);
		}
		$projects = $this->FileError->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileError->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$fileErrorMasters = $this->FileError->FileErrorMaster->find('list',array('conditions'=>array('FileErrorMaster.publish'=>1,'FileErrorMaster.soft_delete'=>0)));
		$systemTables = $this->FileError->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileError->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileError->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileError->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileError->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileError->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'fileErrorMasters', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FileError->find('count');
		$published = $this->FileError->find('count',array('conditions'=>array('FileError.publish'=>1)));
		$unpublished = $this->FileError->find('count',array('conditions'=>array('FileError.publish'=>0)));
		
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
		if (!$this->FileError->exists($id)) {
			throw new NotFoundException(__('Invalid file error'));
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
			if ($this->FileError->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->FileError->save($this->request->data)) {
                $this->Session->setFlash(__('The file error has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The file error could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The file error could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FileError.' . $this->FileError->primaryKey => $id));
			$this->request->data = $this->FileError->find('first', $options);
		}
		$projects = $this->FileError->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileError->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$fileErrorMasters = $this->FileError->FileErrorMaster->find('list',array('conditions'=>array('FileErrorMaster.publish'=>1,'FileErrorMaster.soft_delete'=>0)));
		$systemTables = $this->FileError->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileError->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileError->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileError->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileError->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileError->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'fileErrorMasters', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FileError->find('count');
		$published = $this->FileError->find('count',array('conditions'=>array('FileError.publish'=>1)));
		$unpublished = $this->FileError->find('count',array('conditions'=>array('FileError.publish'=>0)));
		
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
		$this->FileError->id = $id;
		if (!$this->FileError->exists()) {
			throw new NotFoundException(__('Invalid file error'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FileError->delete()) {
			$this->Session->setFlash(__('File error deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('File error was not deleted'));
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
		
		$result = explode('+',$this->request->data['fileErrors']['rec_selected']);
		$this->FileError->recursive = 1;
		$fileErrors = $this->FileError->find('all',array('FileError.publish'=>1,'FileError.soft_delete'=>1,'conditions'=>array('or'=>array('FileError.id'=>$result))));
		$this->set('fileErrors', $fileErrors);
		
				$projects = $this->FileError->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileError->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$fileErrorMasters = $this->FileError->FileErrorMaster->find('list',array('conditions'=>array('FileErrorMaster.publish'=>1,'FileErrorMaster.soft_delete'=>0)));
		$systemTables = $this->FileError->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileError->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileError->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileError->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileError->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileError->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'fileErrorMasters', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'fileErrorMasters', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

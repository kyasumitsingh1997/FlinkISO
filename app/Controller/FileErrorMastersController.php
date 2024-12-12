<?php
App::uses('AppController', 'Controller');
/**
 * FileErrorMasters Controller
 *
 * @property FileErrorMaster $FileErrorMaster
 * @property PaginatorComponent $Paginator
 */
class FileErrorMastersController extends AppController {

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
		$this->paginate = array('order'=>array('FileErrorMaster.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->FileErrorMaster->recursive = 0;
		$this->set('fileErrorMasters', $this->paginate());
		
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
		$this->paginate = array('order'=>array('FileErrorMaster.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->FileErrorMaster->recursive = 0;
		$this->set('fileErrorMasters', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['FileErrorMaster']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['FileErrorMaster']['search_field'] as $search):
				$search_array[] = array('FileErrorMaster.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('FileErrorMaster.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->FileErrorMaster->recursive = 0;
		$this->paginate = array('order'=>array('FileErrorMaster.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'FileErrorMaster.soft_delete'=>0 , $cons));
		$this->set('fileErrorMasters', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('FileErrorMaster.'.$search => $search_key);
					else $search_array[] = array('FileErrorMaster.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('FileErrorMaster.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('FileErrorMaster.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'FileErrorMaster.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('FileErrorMaster.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('FileErrorMaster.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->FileErrorMaster->recursive = 0;
		$this->paginate = array('order'=>array('FileErrorMaster.sr_no'=>'DESC'),'conditions'=>$conditions , 'FileErrorMaster.soft_delete'=>0 );
		$this->set('fileErrorMasters', $this->paginate());
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
		if (!$this->FileErrorMaster->exists($id)) {
			throw new NotFoundException(__('Invalid file error master'));
		}
		$options = array('conditions' => array('FileErrorMaster.' . $this->FileErrorMaster->primaryKey => $id));
		$this->set('fileErrorMaster', $this->FileErrorMaster->find('first', $options));
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
			$this->request->data['FileErrorMaster']['system_table_id'] = $this->_get_system_table_id();
			$this->FileErrorMaster->create();

			if(!$this->request->data['FileErrorMaster']['project_process_plan_id'] || $this->request->data['FileErrorMaster']['project_process_plan_id'] == -1){
				$this->Session->setFlash(__('QC process is required.'));
				$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->data['FileErrorMaster']['project_id']));
			}
			$errors = split(PHP_EOL, $this->request->data['FileErrorMaster']['error']);

			foreach ($errors as $error) {
				$data['FileErrorMaster']['name'] = ltrim(rtrim($error));
				$data['FileErrorMaster']['project_process_plan_id'] = $this->request->data['FileErrorMaster']['project_process_plan_id'];
				$data['FileErrorMaster']['project_id'] = $this->request->data['FileErrorMaster']['project_id'];
				$data['FileErrorMaster']['milestone_id'] = $this->request->data['FileErrorMaster']['milestone_id'];
				$this->FileErrorMaster->create();
				$this->FileErrorMaster->save($data,false);
			}

			$this->Session->setFlash(__('The file error master has been saved'));
			$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->data['FileErrorMaster']['project_id']));
			exit;
			if ($this->FileErrorMaster->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The file error master has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->FileErrorMaster->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The file error master could not be saved. Please, try again.'));
			}
		}
		$projects = $this->FileErrorMaster->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileErrorMaster->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->FileErrorMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileErrorMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileErrorMaster->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileErrorMaster->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileErrorMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileErrorMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->FileErrorMaster->find('count');
	$published = $this->FileErrorMaster->find('count',array('conditions'=>array('FileErrorMaster.publish'=>1)));
	$unpublished = $this->FileErrorMaster->find('count',array('conditions'=>array('FileErrorMaster.publish'=>0)));
		
	$this->set(compact('count','published','unpublished'));

	}

	public function update_cat($id = null, $project_process_plan_id = null){
		$this->autoRender = false;

		if($id && $project_process_plan_id != -1){
			$this->FileErrorMaster->read(null,$id);
			$this->FileErrorMaster->set('project_process_plan_id',$project_process_plan_id);
			if($this->FileErrorMaster->save()){
				return "Error Updated";	
			}else{
				return "Save failed.";	
			}
			
		}else{
			return "Save failed.";
		}
		exit;
	}



/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->FileErrorMaster->exists($id)) {
			throw new NotFoundException(__('Invalid file error master'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['FileErrorMaster']['system_table_id'] = $this->_get_system_table_id();
			if ($this->FileErrorMaster->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The file error master could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FileErrorMaster.' . $this->FileErrorMaster->primaryKey => $id));
			$this->request->data = $this->FileErrorMaster->find('first', $options);
		}
		$projects = $this->FileErrorMaster->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileErrorMaster->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->FileErrorMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileErrorMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileErrorMaster->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileErrorMaster->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileErrorMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileErrorMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FileErrorMaster->find('count');
		$published = $this->FileErrorMaster->find('count',array('conditions'=>array('FileErrorMaster.publish'=>1)));
		$unpublished = $this->FileErrorMaster->find('count',array('conditions'=>array('FileErrorMaster.publish'=>0)));
		
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
		if (!$this->FileErrorMaster->exists($id)) {
			throw new NotFoundException(__('Invalid file error master'));
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
			if ($this->FileErrorMaster->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->FileErrorMaster->save($this->request->data)) {
                $this->Session->setFlash(__('The file error master has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The file error master could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The file error master could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FileErrorMaster.' . $this->FileErrorMaster->primaryKey => $id));
			$this->request->data = $this->FileErrorMaster->find('first', $options);
		}
		$projects = $this->FileErrorMaster->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileErrorMaster->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->FileErrorMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileErrorMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileErrorMaster->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileErrorMaster->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileErrorMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileErrorMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FileErrorMaster->find('count');
		$published = $this->FileErrorMaster->find('count',array('conditions'=>array('FileErrorMaster.publish'=>1)));
		$unpublished = $this->FileErrorMaster->find('count',array('conditions'=>array('FileErrorMaster.publish'=>0)));
		
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
		$this->FileErrorMaster->id = $id;
		if (!$this->FileErrorMaster->exists()) {
			throw new NotFoundException(__('Invalid file error master'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FileErrorMaster->delete()) {
			$this->Session->setFlash(__('File error master deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('File error master was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
// 	public function delete($id = null, $parent_id = NULL) {
	
//             $model_name = $this->modelClass;
//             if(!empty($id)){
    
//             $data['id'] = $id;
//             $data['soft_delete'] = 1;
//             $model_name=$this->modelClass;
//             $this->$model_name->save($data);
//     }
//     $this->redirect(array('action' => 'index'));
     
    
// }

public function delete($id = null, $parent_id = NULL) {
		$this->autoRender = false;
		if($id){
			$this->loadModel('FileProcess');
			// check of there are file
			$files = $this->FileErrorMaster->FileError->find('count',array('conditions'=>array('FileError.file_error_master_id '=> $id)));
			if($files > 0){
				return "Can not delete error as it is linked with file process.";
			}else{
				$this->FileErrorMaster->deleteAll(array('FileErrorMaster.id'=>$id));
				return "Error Deleted";
			}
		}else{
			return "Error can not be found";
		}
        exit;
    // }	
    // $this->redirect(array('action' => 'index'));
     
    
}
 
	
	
	
	public function report(){
		
		$result = explode('+',$this->request->data['fileErrorMasters']['rec_selected']);
		$this->FileErrorMaster->recursive = 1;
		$fileErrorMasters = $this->FileErrorMaster->find('all',array('FileErrorMaster.publish'=>1,'FileErrorMaster.soft_delete'=>1,'conditions'=>array('or'=>array('FileErrorMaster.id'=>$result))));
		$this->set('fileErrorMasters', $fileErrorMasters);
		
				$projects = $this->FileErrorMaster->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileErrorMaster->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->FileErrorMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileErrorMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileErrorMaster->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileErrorMaster->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileErrorMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileErrorMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function inplace_edit_name(){
		$this->autoRender = false;
		if($this->request->data['pk'] && $this->request->data['value']){
			$this->FileErrorMaster->read(null,$this->request->data['pk']);
			$this->FileErrorMaster->set('name',$this->request->data['value']);
			$this->FileErrorMaster->save();
			return true;
		}elseif($this->request->data['pk'] && !$this->request->data['value']){
			// $this->FileErrorMaster->read(null,$this->request->data['pk']);
			// $this->FileErrorMaster->set('soft_delete',1);
			// $this->FileErrorMaster->save();
			return true;
		}else{
			return true;
		}
	}
}

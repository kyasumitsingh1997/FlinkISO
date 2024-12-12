<?php
App::uses('AppController', 'Controller');
/**
 * FileCategories Controller
 *
 * @property FileCategory $FileCategory
 * @property PaginatorComponent $Paginator
 */
class FileCategoriesController extends AppController {

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
		$this->paginate = array('order'=>array('FileCategory.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->FileCategory->recursive = 0;
		$this->set('fileCategories', $this->paginate());
		
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
		$this->paginate = array('order'=>array('FileCategory.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->FileCategory->recursive = 0;
		$this->set('fileCategories', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['FileCategory']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['FileCategory']['search_field'] as $search):
				$search_array[] = array('FileCategory.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('FileCategory.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->FileCategory->recursive = 0;
		$this->paginate = array('order'=>array('FileCategory.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'FileCategory.soft_delete'=>0 , $cons));
		$this->set('fileCategories', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('FileCategory.'.$search => $search_key);
					else $search_array[] = array('FileCategory.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('FileCategory.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('FileCategory.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'FileCategory.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('FileCategory.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('FileCategory.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->FileCategory->recursive = 0;
		$this->paginate = array('order'=>array('FileCategory.sr_no'=>'DESC'),'conditions'=>$conditions , 'FileCategory.soft_delete'=>0 );
		$this->set('fileCategories', $this->paginate());
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
		if (!$this->FileCategory->exists($id)) {
			throw new NotFoundException(__('Invalid file category'));
		}
		$options = array('conditions' => array('FileCategory.' . $this->FileCategory->primaryKey => $id));
		$this->set('fileCategory', $this->FileCategory->find('first', $options));
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
			$this->request->data['FileCategory']['system_table_id'] = $this->_get_system_table_id();
			$this->FileCategory->create();
			if ($this->FileCategory->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The file category has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->FileCategory->id));
				else $this->redirect(array('action' => 'add_ajax',$this->request->data['FileCategory']['project_id'],$this->request->data['FileCategory']['milestone_id'],));
			} else {
				$this->Session->setFlash(__('The file category could not be saved. Please, try again.'));
			}
		}


		// $conditions = $this->_check_request();
		$conditions = array('FileCategory.project_id'=>$this->request->params['pass'][0],'FileCategory.milestone_id'=>$this->request->params['pass'][1]);
		$this->paginate = array('order'=>array('FileCategory.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->FileCategory->recursive = 0;
		$this->set('fileCategories', $this->paginate());
		
		// $this->_get_count();
		// $projects = $this->FileCategory->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		// $milestones = $this->FileCategory->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		// $systemTables = $this->FileCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		// $masterListOfFormats = $this->FileCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		
		// $preparedBies = $this->FileCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		// $approvedBies = $this->FileCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		// $createdBies = $this->FileCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		// $modifiedBies = $this->FileCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		// $this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		// $count = $this->FileCategory->find('count');
		// $published = $this->FileCategory->find('count',array('conditions'=>array('FileCategory.publish'=>1)));
		// $unpublished = $this->FileCategory->find('count',array('conditions'=>array('FileCategory.publish'=>0)));
			
		// $this->set(compact('count','published','unpublished'));

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
                        $this->request->data['FileCategory']['system_table_id'] = $this->_get_system_table_id();
			$this->FileCategory->create();
			if ($this->FileCategory->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='FileCategory';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->FileCategory->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The file category has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->FileCategory->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The file category could not be saved. Please, try again.'));
			}
		}
		$projects = $this->FileCategory->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileCategory->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->FileCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->FileCategory->find('count');
	$published = $this->FileCategory->find('count',array('conditions'=>array('FileCategory.publish'=>1)));
	$unpublished = $this->FileCategory->find('count',array('conditions'=>array('FileCategory.publish'=>0)));
		
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
		if (!$this->FileCategory->exists($id)) {
			throw new NotFoundException(__('Invalid file category'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['FileCategory']['system_table_id'] = $this->_get_system_table_id();
			if ($this->FileCategory->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The file category could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FileCategory.' . $this->FileCategory->primaryKey => $id));
			$this->request->data = $this->FileCategory->find('first', $options);
		}
		$projects = $this->FileCategory->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileCategory->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->FileCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FileCategory->find('count');
		$published = $this->FileCategory->find('count',array('conditions'=>array('FileCategory.publish'=>1)));
		$unpublished = $this->FileCategory->find('count',array('conditions'=>array('FileCategory.publish'=>0)));
		
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
		if (!$this->FileCategory->exists($id)) {
			throw new NotFoundException(__('Invalid file category'));
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
			if ($this->FileCategory->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->FileCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The file category has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The file category could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The file category could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FileCategory.' . $this->FileCategory->primaryKey => $id));
			$this->request->data = $this->FileCategory->find('first', $options);
		}
		$projects = $this->FileCategory->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileCategory->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->FileCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FileCategory->find('count');
		$published = $this->FileCategory->find('count',array('conditions'=>array('FileCategory.publish'=>1)));
		$unpublished = $this->FileCategory->find('count',array('conditions'=>array('FileCategory.publish'=>0)));
		
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
		$this->FileCategory->id = $id;
		if (!$this->FileCategory->exists()) {
			throw new NotFoundException(__('Invalid file category'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FileCategory->delete()) {
			$this->Session->setFlash(__('File category deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('File category was not deleted'));
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
			// check of there are file
			$files = $this->FileCategory->ProjectFile->find('count',array('conditions'=>array('ProjectFile.file_category_id'=>$id)));
			if($files > 0){
				return "Can not delete categorty as it contains files.";
			}else{
				$this->FileCategory->deleteAll(array('FileCategory.id'=>$id));
				return "Category Deleted";
			}
		}else{
			return "Category can not be found";
		}
        exit;
    // }	
    // $this->redirect(array('action' => 'index'));
     
    
}
 
	
	
	
	public function report(){
		
		$result = explode('+',$this->request->data['fileCategories']['rec_selected']);
		$this->FileCategory->recursive = 1;
		$fileCategories = $this->FileCategory->find('all',array('FileCategory.publish'=>1,'FileCategory.soft_delete'=>1,'conditions'=>array('or'=>array('FileCategory.id'=>$result))));
		$this->set('fileCategories', $fileCategories);
		
				$projects = $this->FileCategory->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->FileCategory->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$systemTables = $this->FileCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FileCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->FileCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FileCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FileCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FileCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function inplace_edit(){
		$this->autoRender = false;		

		if(ltrim(rtrim(str_replace(' ','',$this->request->data['value']))) != ''){		
			$cat = $this->FileCategory->find('first',array('conditions'=>array('FileCategory.id'=>$this->request->data['pk']),'recursive'=>-1));

			if($this->request->data['name'] == 'data.FileCategory.name'){			
				$cat['FileCategory']['name'] = $this->request->data['value'];
				$this->FileCategory->create();
				$this->FileCategory->save($cat,false);	
			}elseif($this->request->data['name'] == 'data.FileCategory.priority'){			
				$cat['FileCategory']['priority'] = $this->request->data['value'];
				$this->FileCategory->create();
				if($this->FileCategory->save($cat,false)){

					// change all files priority
					$this->loadModel('ProjectFile');
					$files = $this->ProjectFile->find('all',array(
						'conditions'=>array(
							'ProjectFile.file_category_id'=>$cat['FileCategory']['id']
						),
						'recursive'=>-1,
					));

					foreach ($files as $file) {
						$this->ProjectFile->create();
						$file['ProjectFile']['priority'] = $this->request->data['value'];
						$this->ProjectFile->save($file,false);
					}
				}


			}
		}
		exit;
		
	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * ProjectActivityRequirements Controller
 *
 * @property ProjectActivityRequirement $ProjectActivityRequirement
 */
class ProjectActivityRequirementsController extends AppController {

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

		if($this->request->params['named']['project_id'])$project_conditions = array('ProjectActivityRequirement.project_id'=>$this->request->params['named']['project_id']);
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('ProjectActivityRequirement.sr_no'=>'DESC'),'conditions'=>array($conditions,$project_conditions));
	
		$this->ProjectActivityRequirement->recursive = 0;
		$this->set('projectActivityRequirements', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectActivityRequirement.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectActivityRequirement->recursive = 0;
		$this->set('projectActivityRequirements', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectActivityRequirement']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectActivityRequirement']['search_field'] as $search):
				$search_array[] = array('ProjectActivityRequirement.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectActivityRequirement.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectActivityRequirement->recursive = 0;
		$this->paginate = array('order'=>array('ProjectActivityRequirement.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectActivityRequirement.soft_delete'=>0 , $cons));
		$this->set('projectActivityRequirements', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectActivityRequirement.'.$search => $search_key);
					else $search_array[] = array('ProjectActivityRequirement.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectActivityRequirement.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectActivityRequirement.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectActivityRequirement.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectActivityRequirement.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectActivityRequirement.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectActivityRequirement->recursive = 0;
		$this->paginate = array('order'=>array('ProjectActivityRequirement.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectActivityRequirement.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('projectActivityRequirements', $this->paginate());
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
		if (!$this->ProjectActivityRequirement->exists($id)) {
			throw new NotFoundException(__('Invalid project activity requirement'));
		}
		$options = array('conditions' => array('ProjectActivityRequirement.' . $this->ProjectActivityRequirement->primaryKey => $id));
		$this->set('projectActivityRequirement', $this->ProjectActivityRequirement->find('first', $options));
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
			$this->request->data['ProjectActivityRequirement']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectActivityRequirement->create();
			if ($this->ProjectActivityRequirement->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The project activity requirement has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectActivityRequirement->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project activity requirement could not be saved. Please, try again.'));
			}
		}
		if($this->request->params['named']['project_activity_id']){		
			$projects = $this->ProjectActivityRequirement->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
			$milestones = $this->ProjectActivityRequirement->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
			$projectActivities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		}else{
			$projects = $this->ProjectActivityRequirement->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
			$milestones = $this->ProjectActivityRequirement->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
			$projectActivities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		}
		
		$branches = $this->ProjectActivityRequirement->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivityRequirement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivityRequirement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivityRequirement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivityRequirement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivityRequirement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivityRequirement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'projectActivities', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectActivityRequirement->find('count');
		$published = $this->ProjectActivityRequirement->find('count',array('conditions'=>array('ProjectActivityRequirement.publish'=>1)));
		$unpublished = $this->ProjectActivityRequirement->find('count',array('conditions'=>array('ProjectActivityRequirement.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		if($this->request->params['named']['project_activity_id']){
			$selected_activity = $this->ProjectActivityRequirement->ProjectActivity->find('first',array(
				'recursive'=>-1,
				'fields'=>array('ProjectActivity.id','ProjectActivity.project_id','ProjectActivity.milestone_id'),
				'conditions'=>array('ProjectActivity.id'=>$this->request->params['named']['project_activity_id'])));			
			$this->set('selected_activity',$selected_activity);
			
			$project = $this->ProjectActivityRequirement->Project->find('first',array(
				'recursive'=>-1,
				'conditions'=>array('Project.id'=>$selected_activity['ProjectActivity']['project_id'])));			
			$this->set('project',$project);

			$project_milestones = $this->ProjectActivityRequirement->Milestone->find('all',array(
				'recursive'=>1,
				'conditions'=>array('Milestone.id'=>$selected_activity['ProjectActivity']['milestone_id'])));			
			$this->set('project_milestones',$project_milestones);

			$all_activities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array(
				'conditions'=>array(
					'ProjectActivity.id <>'=>$this->request->params['named']['project_activity_id'],
					'ProjectActivity.project_id'=>$project['Project']['id'],
					)));

			$all_requirements = $this->ProjectActivityRequirement->find('list',array(
				'conditions'=>array(
					'ProjectActivityRequirement.project_id'=>$project['Project']['id'],
					)));				
			
			$this->set('all_activities',$all_activities);
			$this->set('all_requirements',$all_requirements);			
		}
		

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
                        $this->request->data['ProjectActivityRequirement']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectActivityRequirement->create();
			if ($this->ProjectActivityRequirement->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='ProjectActivityRequirement';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->ProjectActivityRequirement->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The project activity requirement has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectActivityRequirement->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project activity requirement could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectActivityRequirement->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectActivityRequirement->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectActivities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$branches = $this->ProjectActivityRequirement->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$userSessions = $this->ProjectActivityRequirement->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivityRequirement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivityRequirement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivityRequirement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivityRequirement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivityRequirement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivityRequirement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'projectActivities', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectActivityRequirement->find('count');
		$published = $this->ProjectActivityRequirement->find('count',array('conditions'=>array('ProjectActivityRequirement.publish'=>1)));
		$unpublished = $this->ProjectActivityRequirement->find('count',array('conditions'=>array('ProjectActivityRequirement.publish'=>0)));
			
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
		if (!$this->ProjectActivityRequirement->exists($id)) {
			throw new NotFoundException(__('Invalid project activity requirement'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectActivityRequirement']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ProjectActivityRequirement->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project activity requirement could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectActivityRequirement.' . $this->ProjectActivityRequirement->primaryKey => $id));
			$this->request->data = $this->ProjectActivityRequirement->find('first', $options);
		}
		$projects = $this->ProjectActivityRequirement->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectActivityRequirement->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectActivities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$branches = $this->ProjectActivityRequirement->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivityRequirement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivityRequirement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivityRequirement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivityRequirement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivityRequirement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivityRequirement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'projectActivities', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectActivityRequirement->find('count');
		$published = $this->ProjectActivityRequirement->find('count',array('conditions'=>array('ProjectActivityRequirement.publish'=>1)));
		$unpublished = $this->ProjectActivityRequirement->find('count',array('conditions'=>array('ProjectActivityRequirement.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		if($this->request->data['ProjectActivityRequirement']['project_id']){
			$selected_activity = $this->ProjectActivityRequirement->ProjectActivity->find('first',array(
				'recursive'=>-1,
				'fields'=>array('ProjectActivity.id','ProjectActivity.project_id','ProjectActivity.milestone_id'),
				'conditions'=>array('ProjectActivity.id'=>$this->request->data['ProjectActivityRequirement']['project_activity_id'])));			
			$this->set('selected_activity',$selected_activity);
			
			$project = $this->ProjectActivityRequirement->Project->find('first',array(
				'recursive'=>-1,
				'conditions'=>array('Project.id'=>$selected_activity['ProjectActivity']['project_id'])));			
			$this->set('project',$project);

			$project_milestones = $this->ProjectActivityRequirement->Milestone->find('all',array(
				'recursive'=>1,
				'conditions'=>array('Milestone.id'=>$selected_activity['ProjectActivity']['milestone_id'])));			
			$this->set('project_milestones',$project_milestones);

			$all_activities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array(
				'conditions'=>array(
					'ProjectActivity.id <>'=>$this->request->params['named']['project_activity_id'],
					'ProjectActivity.project_id'=>$project['Project']['id'],
					)));

			$all_requirements = $this->ProjectActivityRequirement->find('list',array(
				'conditions'=>array(
					'ProjectActivityRequirement.project_id'=>$project['Project']['id'],
					)));				
			
			$this->set('all_activities',$all_activities);
			$this->set('all_requirements',$all_requirements);			
		}
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->ProjectActivityRequirement->exists($id)) {
			throw new NotFoundException(__('Invalid project activity requirement'));
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
			if ($this->ProjectActivityRequirement->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectActivityRequirement->save($this->request->data)) {
                $this->Session->setFlash(__('The project activity requirement has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project activity requirement could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project activity requirement could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectActivityRequirement.' . $this->ProjectActivityRequirement->primaryKey => $id));
			$this->request->data = $this->ProjectActivityRequirement->find('first', $options);
		}
		$projects = $this->ProjectActivityRequirement->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectActivityRequirement->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectActivities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$branches = $this->ProjectActivityRequirement->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivityRequirement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivityRequirement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivityRequirement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivityRequirement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivityRequirement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivityRequirement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'projectActivities', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectActivityRequirement->find('count');
		$published = $this->ProjectActivityRequirement->find('count',array('conditions'=>array('ProjectActivityRequirement.publish'=>1)));
		$unpublished = $this->ProjectActivityRequirement->find('count',array('conditions'=>array('ProjectActivityRequirement.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		if($this->request->data['ProjectActivityRequirement']['project_id']){
			$selected_activity = $this->ProjectActivityRequirement->ProjectActivity->find('first',array(
				'recursive'=>-1,
				'fields'=>array('ProjectActivity.id','ProjectActivity.project_id','ProjectActivity.milestone_id'),
				'conditions'=>array('ProjectActivity.id'=>$this->request->data['ProjectActivityRequirement']['project_activity_id'])));			
			$this->set('selected_activity',$selected_activity);
			
			$project = $this->ProjectActivityRequirement->Project->find('first',array(
				'recursive'=>-1,
				'conditions'=>array('Project.id'=>$selected_activity['ProjectActivity']['project_id'])));			
			$this->set('project',$project);

			$project_milestones = $this->ProjectActivityRequirement->Milestone->find('all',array(
				'recursive'=>1,
				'conditions'=>array('Milestone.id'=>$selected_activity['ProjectActivity']['milestone_id'])));			
			$this->set('project_milestones',$project_milestones);

			$all_activities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array(
				'conditions'=>array(
					'ProjectActivity.id <>'=>$this->request->params['named']['project_activity_id'],
					'ProjectActivity.project_id'=>$project['Project']['id'],
					)));

			$all_requirements = $this->ProjectActivityRequirement->find('list',array(
				'conditions'=>array(
					'ProjectActivityRequirement.project_id'=>$project['Project']['id'],
					)));				
			
			$this->set('all_activities',$all_activities);
			$this->set('all_requirements',$all_requirements);			
		}
	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {
		$this->ProjectActivityRequirement->id = $id;
		if (!$this->ProjectActivityRequirement->exists()) {
			throw new NotFoundException(__('Invalid project activity requirement'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectActivityRequirement->delete()) {
			$this->Session->setFlash(__('Project activity requirement deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project activity requirement was not deleted'));
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
		
		$result = explode('+',$this->request->data['projectActivityRequirements']['rec_selected']);
		$this->ProjectActivityRequirement->recursive = 1;
		$projectActivityRequirements = $this->ProjectActivityRequirement->find('all',array('ProjectActivityRequirement.publish'=>1,'ProjectActivityRequirement.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectActivityRequirement.id'=>$result))));
		$this->set('projectActivityRequirements', $projectActivityRequirements);
		
		$projects = $this->ProjectActivityRequirement->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectActivityRequirement->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectActivities = $this->ProjectActivityRequirement->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$branches = $this->ProjectActivityRequirement->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivityRequirement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivityRequirement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivityRequirement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivityRequirement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivityRequirement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivityRequirement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'projectActivities', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'projectActivities', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

<?php
App::uses('AppController', 'Controller');
/**
 * ProjectProcessPlans Controller
 *
 * @property ProjectProcessPlan $ProjectProcessPlan
 * @property PaginatorComponent $Paginator
 */
class ProjectProcessPlansController extends AppController {

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
		$this->paginate = array('order'=>array('ProjectProcessPlan.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProjectProcessPlan->recursive = 0;
		$this->set('projectProcessPlans', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectProcessPlan.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectProcessPlan->recursive = 0;
		$this->set('projectProcessPlans', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectProcessPlan']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectProcessPlan']['search_field'] as $search):
				$search_array[] = array('ProjectProcessPlan.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectProcessPlan.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectProcessPlan->recursive = 0;
		$this->paginate = array('order'=>array('ProjectProcessPlan.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectProcessPlan.soft_delete'=>0 , $cons));
		$this->set('projectProcessPlans', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectProcessPlan.'.$search => $search_key);
					else $search_array[] = array('ProjectProcessPlan.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectProcessPlan.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectProcessPlan.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectProcessPlan.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectProcessPlan.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectProcessPlan.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectProcessPlan->recursive = 0;
		$this->paginate = array('order'=>array('ProjectProcessPlan.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectProcessPlan.soft_delete'=>0 );
		$this->set('projectProcessPlans', $this->paginate());
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
		if (!$this->ProjectProcessPlan->exists($id)) {
			throw new NotFoundException(__('Invalid project process plan'));
		}
		$options = array('conditions' => array('ProjectProcessPlan.' . $this->ProjectProcessPlan->primaryKey => $id));
		$this->set('projectProcessPlan', $this->ProjectProcessPlan->find('first', $options));
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


			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;
			$this->request->data['ProjectProcessPlan']['system_table_id'] = $this->_get_system_table_id();
			
			foreach ($this->request->data['ProjectProcessPlan'] as $datas) {
				foreach ($datas as $data) {
					// debug($data);
					// $dates = split(' - ', $data['start_date']);

					if($data['dependancy_id'] != ''){
						$d = $this->ProjectProcessPlan->find('first',array(
							'recursive'=>-1,
							'conditions'=>array(
								'ProjectProcessPlan.project_id'=>$data['project_id'],
								'ProjectProcessPlan.milestone_id'=>$data['milestone_id'],
								'ProjectProcessPlan.sequence'=>$data['dependancy_id'],
							)
						));

					}

					$data = $data;
					$data['dependancy_id'] = $d['ProjectProcessPlan']['id'];
					$data['estimated_manhours'] = $data['hours'];
					$data['start_date'] = date('Y-m-d',strtotime($data['start_date']));
					$data['end_date'] = date('Y-m-d',strtotime($data['end_date']));
					$data['publish'] = 1;
					$data['soft_delete'] = 0;
					$data['prepared_by'] = $this->Session->read('User.employee_id');
					// debug($data);
					$this->ProjectProcessPlan->create();
					$this->ProjectProcessPlan->save($data,false);
					$project_id = $data['project_id'];
				}
				
			}

			// exit;
			$this->Session->setFlash(__('The project process plan has been saved'));
			$this->redirect(array('controller'=>'projects', 'action' => 'view', $data['project_id']));

			
			if ($this->ProjectProcessPlan->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The project process plan has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectProcessPlan->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project process plan could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectProcessPlan->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectProcessPlan->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectOverallPlans = $this->ProjectProcessPlan->ProjectOverallPlan->find('list',array('conditions'=>array('ProjectOverallPlan.publish'=>1,'ProjectOverallPlan.soft_delete'=>0)));
		$systemTables = $this->ProjectProcessPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectProcessPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectProcessPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectProcessPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectProcessPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectProcessPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'projectOverallPlans', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectProcessPlan->find('count');
		$published = $this->ProjectProcessPlan->find('count',array('conditions'=>array('ProjectProcessPlan.publish'=>1)));
		$unpublished = $this->ProjectProcessPlan->find('count',array('conditions'=>array('ProjectProcessPlan.publish'=>0)));
			
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
                        $this->request->data['ProjectProcessPlan']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectProcessPlan->create();
			if ($this->ProjectProcessPlan->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='ProjectProcessPlan';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->ProjectProcessPlan->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The project process plan has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectProcessPlan->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project process plan could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectProcessPlan->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectProcessPlan->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectOverallPlans = $this->ProjectProcessPlan->ProjectOverallPlan->find('list',array('conditions'=>array('ProjectOverallPlan.publish'=>1,'ProjectOverallPlan.soft_delete'=>0)));
		$systemTables = $this->ProjectProcessPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectProcessPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectProcessPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectProcessPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectProcessPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectProcessPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projects', 'milestones', 'projectOverallPlans', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ProjectProcessPlan->find('count');
	$published = $this->ProjectProcessPlan->find('count',array('conditions'=>array('ProjectProcessPlan.publish'=>1)));
	$unpublished = $this->ProjectProcessPlan->find('count',array('conditions'=>array('ProjectProcessPlan.publish'=>0)));
		
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
	if (!$this->ProjectProcessPlan->exists($id)) {
		throw new NotFoundException(__('Invalid project process plan'));
	}
		
	if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
	if ($this->request->is('post') || $this->request->is('put')) {
      
		if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        	$this->request->data[$this->modelClass]['publish'] = 0;
      	}
			
      			$this->request->data['ProjectProcessPlan']['system_table_id'] = $this->_get_system_table_id();

			$this->loadModel('ProcessWeeklyPlan');


			$eplan = $this->ProjectProcessPlan->find('first',array(
				'recursive'=>-1,
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.days','ProjectProcessPlan.weightage'),
				'conditions'=>array('ProjectProcessPlan.id'=>$this->request->data['ProjectProcessPlan']['id'])
			));



			if ($this->ProjectProcessPlan->save($this->request->data)) {

				$this->calculate_weightage($this->request->data);

				$this->ProcessWeeklyPlan->deleteAll(array(
							'ProcessWeeklyPlan.project_process_plan_id'=>$this->request->data['ProjectProcessPlan']['id'],
							// 'ProcessWeeklyPlan.year'=>$data['year'],
							// 'ProcessWeeklyPlan.week'=>$data['week']
						));
				
				
				
					foreach($this->request->data['ProcessWeeklyPlan'] as $data){

						$per = $perw = 0;
						// get weightwage percentage
						
						$per = round($data['hours'] * 100 / $this->request->data['ProjectProcessPlan']['hours'],2);
						$perw = round($per * $eplan['ProjectProcessPlan']['weightage'] / 100,2);

						$processWeeklyPlan = array();
						$processWeeklyPlan['year'] = $data['year'];
						$processWeeklyPlan['week'] = $data['week'];
						$processWeeklyPlan['planned'] = $data['planned'];
						$processWeeklyPlan['hours'] = $data['hours'];
						$processWeeklyPlan['units'] = $data['units'];
						$processWeeklyPlan['percentage'] = $per;
						$processWeeklyPlan['weightage_per'] = $perw;
						$processWeeklyPlan['project_id'] = $this->request->data['ProjectProcessPlan']['project_id'];
						$processWeeklyPlan['milestone_id'] = $this->request->data['ProjectProcessPlan']['milestone_id'];
						$processWeeklyPlan['project_process_plan_id'] = $this->request->data['ProjectProcessPlan']['id'];
						$processWeeklyPlan['publish'] = 1;
						$processWeeklyPlan['soft_delete'] = 0;
						// if($processWeeklyPlan['planned'] != 0){
							// first delete current

						// if there is any change in days, do not add new weekly plan data, just delete previouse data
						// check if there is any chage in days
						$flag = false;
						if($data['id']){
							$rec = $this->ProjectProcessPlan->find('first',array('recursive'=>-1,'conditions'=>array(
								'ProjectProcessPlan.id'=>$data['id']
							)));

							if($rec){
								if($rec['ProjectProcessPlan']['hours'] != $data['hours']){
									$flag = true;
								}else{
									$flag = false;
								}
							}else{
								$flag = false;
							}
						}

						if($flag == true || $eplan['ProjectProcessPlan']['days'] == $this->request->data['ProjectProcessPlan']['days']){
							$this->ProcessWeeklyPlan->create();
							$this->ProcessWeeklyPlan->save($processWeeklyPlan,false);
						}
					}
				// }

				


				// add additional units
				$additionalUnits = explode(PHP_EOL,$this->request->data['OtherMeasurableUnit']['additional_units']);
				
				$this->loadModel('OtherMeasurableUnit');
				foreach($additionalUnits as $additionalUnit){
					$unit_name = trim($additionalUnit);
					if($unit_name){
						$otherMeasurableUnit = array();
						$otherMeasurableUnit['unit_name'] = $unit_name;
						$otherMeasurableUnit['project_id'] = $this->request->data['ProjectProcessPlan']['project_id'];
						$otherMeasurableUnit['milestone_id'] = $this->request->data['ProjectProcessPlan']['milestone_id'];
						$otherMeasurableUnit['project_process_plan_id'] = $this->request->data['ProjectProcessPlan']['id'];
						$otherMeasurableUnit['publish'] = 1;
						$otherMeasurableUnit['soft_delete'] = 0;
						$this->OtherMeasurableUnit->create();
						$this->OtherMeasurableUnit->save($otherMeasurableUnit,false);

					}
				}
				

				// exit;
				// $this->Session->setFlash(__('The project process plan has been saved'));
				$this->redirect(array(
					'controller'=>'project_process_plans', 
					'action' => 'edit_pre', 
					'project_id'=>$this->request->data['ProjectProcessPlan']['project_id'],
					'milestone_id'=>$this->request->data['ProjectProcessPlan']['milestone_id'],
					'project_activity_id'=>null,
					'project_overall_plan_id'=>$this->request->data['ProjectProcessPlan']['project_overall_plan_id'],
					$this->request->data['ProjectProcessPlan']['id']
					));
			} else {
				$this->Session->setFlash(__('The project process plan could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectProcessPlan.' . $this->ProjectProcessPlan->primaryKey => $id));
			$this->request->data = $this->ProjectProcessPlan->find('first', $options);
		}
		$project = $this->ProjectProcessPlan->Project->find('first',array('recursive'=>-1, 'conditions'=>array('Project.id'=>$this->request->params['named']['project_id'], 'Project.publish'=>1,'Project.soft_delete'=>0)));

		
		$listOfSoftwares = $this->ProjectProcessPlan->ListOfSoftware->find('list',array('conditions'=>array('ListOfSoftware.publish'=>1,'ListOfSoftware.soft_delete'=>0)));
		$milestones = $this->ProjectProcessPlan->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		
		$projectOverallPlan = $this->ProjectProcessPlan->ProjectOverallPlan->find('first',array('recursive'=>-1, 'conditions'=>array('ProjectOverallPlan.id'=>$this->request->params['named']['project_overall_plan_id'], 'ProjectOverallPlan.soft_delete'=>0)));
			

		$systemTables = $this->ProjectProcessPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectProcessPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectProcessPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectProcessPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectProcessPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectProcessPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('project', 'listOfSoftwares', 'milestones', 'projectOverallPlan', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectProcessPlan->find('count');
		$published = $this->ProjectProcessPlan->find('count',array('conditions'=>array('ProjectProcessPlan.publish'=>1)));
		$unpublished = $this->ProjectProcessPlan->find('count',array('conditions'=>array('ProjectProcessPlan.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		$existingprocesses = $this->ProjectProcessPlan->find('list',array(
			'conditions'=>array(
				'ProjectProcessPlan.project_id'=>$this->request->params['named']['project_id'],
				'ProjectProcessPlan.milestone_id'=>$this->request->params['named']['milestone_id'],
				'ProjectProcessPlan.soft_delete'=>0), 'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process')));
		$this->set('existingprocesses',$existingprocesses);

		$currencies = $this->ProjectProcessPlan->Project->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
        	$this->set('currencies',$currencies);
        	
        	$projectCurrency = $this->ProjectProcessPlan->Project->find('first',array('recursive'=>-1,'fields'=>array('Project.id','Project.currency_id'), 'conditions'=>array('Project.id'=>$this->request->params['named']['project_id'])));
		
		$this->set('projectCurrency',$projectCurrency['Project']['currency_id']);
	}


	public function edit_pre(){
		$this->autoRender = false;
		// Configure::Write('debug',1);
		// debug($this->request->params);
		// exit;

		$this->Session->setFlash(__('The project process plan has been saved'));
		$this->redirect(array(
			'controller'=>'project_process_plans', 
			'action' => 'edit', 
			'project_id'=>$this->request->params['named']['project_id'],
			'milestone_id'=>$this->request->params['named']['milestone_id'],
			'project_activity_id'=>null,
			'project_overall_plan_id'=>$this->request->params['named']['project_overall_plan_id'],
			$this->request->params['pass'][0]
		));


		$options = array('conditions' => array('ProjectProcessPlan.id' => $this->request->params['named']['project_overall_plan_id']));
		$this->request->data = $this->ProjectProcessPlan->find('first', $options);
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->ProjectProcessPlan->exists($id)) {
			throw new NotFoundException(__('Invalid project process plan'));
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
			if ($this->ProjectProcessPlan->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectProcessPlan->save($this->request->data)) {
                $this->Session->setFlash(__('The project process plan has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project process plan could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project process plan could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectProcessPlan.' . $this->ProjectProcessPlan->primaryKey => $id));
			$this->request->data = $this->ProjectProcessPlan->find('first', $options);
		}
		$projects = $this->ProjectProcessPlan->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectProcessPlan->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectOverallPlans = $this->ProjectProcessPlan->ProjectOverallPlan->find('list',array('conditions'=>array('ProjectOverallPlan.publish'=>1,'ProjectOverallPlan.soft_delete'=>0)));
		$systemTables = $this->ProjectProcessPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectProcessPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectProcessPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectProcessPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectProcessPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectProcessPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'projectOverallPlans', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectProcessPlan->find('count');
		$published = $this->ProjectProcessPlan->find('count',array('conditions'=>array('ProjectProcessPlan.publish'=>1)));
		$unpublished = $this->ProjectProcessPlan->find('count',array('conditions'=>array('ProjectProcessPlan.publish'=>0)));
		
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
		$this->ProjectProcessPlan->id = $id;
		if (!$this->ProjectProcessPlan->exists()) {
			throw new NotFoundException(__('Invalid project process plan'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectProcessPlan->delete()) {
			$this->Session->setFlash(__('Project process plan deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project process plan was not deleted'));
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
		
		$result = explode('+',$this->request->data['projectProcessPlans']['rec_selected']);
		$this->ProjectProcessPlan->recursive = 1;
		$projectProcessPlans = $this->ProjectProcessPlan->find('all',array('ProjectProcessPlan.publish'=>1,'ProjectProcessPlan.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectProcessPlan.id'=>$result))));
		$this->set('projectProcessPlans', $projectProcessPlans);
		
				$projects = $this->ProjectProcessPlan->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectProcessPlan->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectOverallPlans = $this->ProjectProcessPlan->ProjectOverallPlan->find('list',array('conditions'=>array('ProjectOverallPlan.publish'=>1,'ProjectOverallPlan.soft_delete'=>0)));
		$systemTables = $this->ProjectProcessPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectProcessPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectProcessPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectProcessPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectProcessPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectProcessPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'projectOverallPlans', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'projectOverallPlans', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function inplace_edit_sequence() {

        $this->layout = "ajax";
        
        if(ltrim(rtrim(str_replace(' ','',$this->request->data['value']))) != ''){
        	$this->ProjectProcessPlan->read(null, $this->request->data['pk']);
	        Configure::write('debug',1);
	        debug($this->request->data);
	        debug(ltrim(rtrim(str_replace(' ','',$this->request->data['value']))));
	        // if($this->request->data['value'] == "Yes")$value = 1;
	        // else $value = 0;
	        $data['ProjectProcessPlan']['sequence'] = preg_replace('/[^0-9.]+/', '', $this->request->data['value']);
	        $this->ProjectProcessPlan->save($data, false);
        }
	        
        exit;
    }

    public function updatedepedancy($did = null, $id = null){
    	$this->layout = "ajax";
        $this->ProjectProcessPlan->read(null, $id);
        $data['ProjectProcessPlan']['dependancy_id'] = $did;
        $this->ProjectProcessPlan->save($data, false);
        exit;
    }


    public function calculate_weightage(){

    	// get sum of total estimated manhours
    	// get current estimated manhours

    	$this->ProjectProcessPlan->virtualFields = array(
    		'total_estimated_manhours' => 'select SUM(`project_process_plans`.`estimated_manhours`) from `project_process_plans` where `project_process_plans`.`project_id` LIKE ProjectProcessPlan.project_id'
    	);
    	// debug($this->request->data['ProjectProcessPlan']['project_id']);
    	
    	$plans = $this->ProjectProcessPlan->find('all',array(
    		'recursive'=>-1, 
    		'conditions'=>array(
    			'ProjectProcessPlan.project_id'=>$this->request->data['ProjectProcessPlan']['project_id']
    		)));
    	
    	foreach($plans as $plan){
    		$weightage = $plan['ProjectProcessPlan']['estimated_manhours'] * 100 / $plan['ProjectProcessPlan']['total_estimated_manhours'];
    		$this->ProjectProcessPlan->create();
    		$plan['ProjectProcessPlan']['weightage'] = $weightage;
    		$this->ProjectProcessPlan->save($plan, false);
    	}

    	// debug($plan);



    }
}

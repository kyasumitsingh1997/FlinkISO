<?php
App::uses('AppController', 'Controller');
/**
 * ProjectFiles Controller
 *
 * @property ProjectFile $ProjectFile
 * @property PaginatorComponent $Paginator
 */
class ProjectFilesController extends AppController {

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


public function project_files($project_id = null, $milestone_id = null){	

		if ($this->request->query['data']) {
			if($this->request->query['data']['ProjectFileSearchFake']['file_category_id'] != -1){
				$con1 = array('ProjectFile.file_category_id' => $this->request->query['data']['ProjectFileSearchFake']['file_category_id']);
			}

			if($this->request->query['data']['ProjectFileSearchFake']['cities'] != -1){
				$con2 = array('ProjectFile.city' => $this->request->query['data']['ProjectFileSearchFake']['cities']);
			}

			if($this->request->query['data']['ProjectFileSearchFake']['blocks'] != -1){
				$con3 = array('ProjectFile.block' => $this->request->query['data']['ProjectFileSearchFake']['blocks']);
			}

			if($this->request->query['data']['ProjectFileSearchFake']['team_members'] != -1){
				$con4 = array('ProjectFile.employee_id' => $this->request->query['data']['ProjectFileSearchFake']['team_members']);
			}

			if($this->request->query['data']['ProjectFileSearchFake']['project_process_plan_id'] != -1){
				$con5 = array('ProjectFile.project_process_plan_id' => $this->request->query['data']['ProjectFileSearchFake']['project_process_plan_id']);
			}

			if($this->request->query['data']['ProjectFileSearchFake']['current_status'] != -1){
				$con6 = array('ProjectFile.current_status' => $this->request->query['data']['ProjectFileSearchFake']['current_status']);
			}

			if($this->request->query['fileinput']){
				$con7 = array('ProjectFile.name LIKE' => '%' . $this->request->query['fileinput'] .'%');
			}



			$projectFakeFormCons = array($con1,$con2,$con3,$con4,$con5,$con6,$con7);
		}else{
			$projectFakeFormCons = array('ProjectFile.current_status != '=>array(2,3,5));
		}

		$totalProjectFiles = $this->ProjectFile->find('count',array('conditions'=>array('ProjectFile.project_id'=>$project_id,'ProjectFile.milestone_id'=>$milestone_id)));

		// get cities
		$cities = $this->ProjectFile->find('list',array(
			'fields'=>array('ProjectFile.city','ProjectFile.city'),
			'group'=>array('ProjectFile.city'),
			'conditions'=>array(
				'ProjectFile.project_id'=>$project_id,
				'ProjectFile.milestone_id'=>$milestone_id)
			)
		);

		// get blocks
		$blocks = $this->ProjectFile->find('list',array(
			'fields'=>array('ProjectFile.block','ProjectFile.block'),
			'group'=>array('ProjectFile.block'),
			'conditions'=>array(
				'ProjectFile.project_id'=>$project_id,
				'ProjectFile.milestone_id'=>$milestone_id)
			)
		);



		$this->set(compact('cities','blocks','totalProjectFiles'));
		// Configure::Write('debug',1);
		// debug($blockes);
		// exit;
		//get team leaders/employee/project leader
		
		$teamsleads = $this->ProjectFile->Project->find('first',array(
			'recursive'=>-1,
			'conditions'=>array('Project.id'=>$project_id),
			'fields'=>array(
				'Project.id',
				'Project.employee_id',
				'Project.team_leader_id',
				'Project.project_leader_id',
			)
		));



		// Configure::Write('debug',1);
		// debug($teamsleads);
		$emp1 = json_decode($teamsleads['Project']['employee_id'],true);
		$emp2 = json_decode($teamsleads['Project']['team_leader_id'],true);
		$emp3 = json_decode($teamsleads['Project']['project_leader_id'],true);
		$emps = array_merge_recursive($emp1,$emp2,$emp3);
		// debug($emps);
		// exit;


		$this->set('emps',$emps);

		// check total files vs resources with processes

		$this->loadModel('ProjectResource');
		$empCnt = $this->ProjectResource->find('count',array(
			'conditions'=>array(
				'ProjectResource.project_id'=>$project_id,
				'ProjectResource.milestone_id'=>$milestone_id,				
			),
			'group'=>array(
				'ProjectResource.employee_id'
			)
		));



		$this->set('proempcnt',$empCnt);

		$this->set('pop',$this->request->params['pass'][2]);
		$this->set('por',$this->request->params['pass'][3]);


		$res_check = $this->requestAction(array('controller'=>'project_resources','action'=>'resource_check',$project_id,$milestone_id));

		// error check
		$qcs = $this->ProjectFile->Project->ProjectProcessPlan->find('all',array('conditions'=>array('ProjectProcessPlan.qc'=>1,'ProjectProcessPlan.milestone_id'=>$milestone_id)));
		
		if($qcs){
			$errocheck = $this->ProjectFile->Project->FileErrorMaster->find('count',array(
				'conditions'=>array(
					'FileErrorMaster.project_id'=>$project_id,
					'FileErrorMaster.milestone_id'=>$milestone_id,
				)
			));
		}else{
			$errocheck = 1;
		}
		
		$checklistcheck = $this->ProjectFile->Project->ProjectChecklist->find('count',array(
			'conditions'=>array(
				'ProjectChecklist.project_id'=>$project_id,
				'ProjectChecklist.milestone_id'=>$milestone_id,
			)
		));



		if($res_check == true){
			echo "Please add over all plan / detailed plans/ resources etc before adding files.";	
			$this->set('dontshow',true);	
		}else if($errocheck == 0 ){
			echo "Please errors before adding files.";	
			$this->set('dontshow',true);	
		}else if($checklistcheck == 0 ){
			echo "Please add checklist before adding files.";	
			$this->set('dontshow',true);	
		}else{
			$this->set('dontshow',false);
			$i = 0;
			$milestone = $this->ProjectFile->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
			$this->set('milestone',$milestone);

			// Configure::write('debug',1);
			// debug($this->request->params['pass']);
			// exit;
			
			// estimated time cal
			$this->loadModel('ProjectProcessPlan');
	        $this->ProjectProcessPlan->virtualFields = array(
	            'op'=>'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id'
	        );
	        $projectProcesses = $existingprocesses = $this->ProjectProcessPlan->find(
	            'list',array('conditions'=>
	                array(
	                    'ProjectProcessPlan.op >'=>0,
	                    'ProjectProcessPlan.project_id'=> $project_id,
	                    'ProjectProcessPlan.milestone_id'=> $milestone_id,
	                ),
	                'fields'=>array(
	                    'ProjectProcessPlan.id',
	                    'ProjectProcessPlan.process'
	                ),
	                'order'=>array(
	                    'ProjectProcessPlan.sequence'=>'ASC'
	                )
	            )
	        );



	        

			// $projectProcesses['ProjectProcessPlan']['estimated_units'] / $projectProcesses['ProjectProcessPlan']['overall_metrics'] / $projectProcesses['ProjectProcessPlan']['days'] / $projectProcesses['ProjectProcessPlan']['estimated_resource'];

			

			$this->ProjectFile->virtualFields = array(
					'cat_on_hold'=>'select `file_categories`.`status` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
					'last_process' => 'select `file_processes`.`project_process_plan_id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',

					'last_process_id' => 'select `file_processes`.`id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',

					'last_emp_id' => 'select `file_processes`.`employee_id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id and file_processes.current_status != 1  ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',
					
					'last_comment' => 'select `file_processes`.`comments` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',

					// 'last_comment_admin' => 'select `file_processes`.`change_user_comments` from `file_processes` where `file_processes`.`change_user_comments` IS NOT NULL and `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',
					
					'start_time' => 'select `file_processes`.`start_time` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id AND `file_processes`.`start_time` IS NOT NULL AND `file_processes`.`project_process_plan_id` LIKE ProjectFile.project_process_plan_id ORDER BY `file_processes`.`start_time` DESC LIMIT 1 ',
					
					// 'end_time' => 'select `file_processes`.`end_time` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id AND (current_status = 1 OR current_status = 5) AND `file_processes`.`start_time` IS NOT NULL  AND `file_processes`.`project_process_plan_id` LIKE ProjectFile.project_process_plan_id ORDER BY `file_processes`.`end_time` DESC LIMIT 1 ',

					'end_time' => 'select `file_processes`.`end_time` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id AND `file_processes`.`start_time` IS NOT NULL AND `file_processes`.`project_process_plan_id` LIKE ProjectFile.project_process_plan_id ORDER BY `file_processes`.`start_time` DESC LIMIT 1 ',
					
					'actual_time_from_process' => 'select SEC_TO_TIME(SUM(TIME_TO_SEC(actual_time))) from file_processes where file_processes.project_file_id LIKE ProjectFile.id',

					'process_start_time'=>'select `start_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`start_time` IS NOT NULL ORDER BY file_processes.sr_no ASC LIMIT 1',
					
					'process_end_time'=>'select `end_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`end_time` IS NOT NULL ORDER BY file_processes.sr_no DESC LIMIT 1',
					
					'total_time'=>'select (timediff((select `end_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`end_time` IS NOT NULL ORDER BY file_processes.sr_no DESC LIMIT 1),(select `start_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`start_time` IS NOT NULL ORDER BY file_processes.sr_no ASC LIMIT 1)))',
					
					'estimated_time_1'=>'ROUND(ProjectFile.unit / ProjectProcessPlan.overall_metrics / ProjectProcessPlan.days / ProjectProcessPlan.estimated_resource,2)',
					'file_qed' => 'select count(*) from file_processes where file_processes.project_file_id LIKE ProjectFile.id and file_processes.employee_id LIKE ProjectFile.employee_id and file_processes.queued = 1 and start_time is NULL',
					// 'latest_assigned'=> $this->_get_latest_assigned($project_id)
					// 'last_process' => 'select `file_processes`.`employee_id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`modified` DESC LIMIT 1 '
					'latest_assigned_date'=>'select assigned_date from file_processes where file_processes.project_file_id LIKE ProjectFile.id order by assigned_date DESC LIMIT 1',


					'latest_start_date'=>'select start_time from file_processes where file_processes.project_file_id LIKE ProjectFile.id order by assigned_date DESC LIMIT 1',

					// this needs to be tested.
					// 'latest_status'=>'select current_status from file_processes where file_processes.project_file_id LIKE ProjectFile.id order by assigned_date DESC LIMIT 1'
					'latest_status'=>'select current_status from file_processes where file_processes.project_file_id LIKE ProjectFile.id order by file_processes.sr_no DESC LIMIT 1',

					'noprocess' => 'select count(*) from file_processes where file_processes.project_file_id LIKE ProjectFile.id LIMIT 1'
				);



				if($projectFakeFormCons){
					// $this->paginate = $this->ProjectFile->find('all',array(
					// // $projectFiles = $this->ProjectFile->find('all',array(
					// 	'conditions'=>array(
					// 		$projectFakeFormCons,
					// 		'ProjectFile.soft_delete'=>0, 
					// 		'ProjectFile.parent_id'=> NULL, 
					// 		'ProjectFile.project_id'=>$milestone['Milestone']['project_id'],
					// 		'ProjectFile.milestone_id'=>$milestone['Milestone']['id']
					// 	),
					// 	'limit'=>5,
					// 	'recursive'=>0,
					// 	'order'=>array(
					// 		'ProjectFile.name'=>'ASC',
					// 		'ProjectFile.current_status'=>'DESC',
					// 		'ProjectFile.employee_id'=>'ASC',					
					// 	)
					// ));	

					$this->paginate = array(
						'limit'=>10, 
						'order'=>array(
							'ProjectFile.created'=>'DESC',
							'ProjectFile.assigned_date'=>'DESC'
						),
						'conditions'=>array(
							$projectFakeFormCons,
							'ProjectFile.soft_delete'=>0, 
							'ProjectFile.parent_id'=> NULL, 
							'ProjectFile.project_id'=>$milestone['Milestone']['project_id'],
							'ProjectFile.milestone_id'=>$milestone['Milestone']['id']

						)
					);
				}
				
				// Configure::Write('debug',1);
				// debug($this->paginate);
				// exit;

				$this->set('projectFiles',$this->paginate());				
				$this->set('existingprocesses',$existingprocesses);

				// exit;
				
				$this->ProjectFile->Project->ProjectEmployee->Employee->virtualFields = array(
					'check_free'=>'select count(*) from `project_files` where (`project_files`.`current_status` = 0 OR `project_files`.`current_status` = 10) AND `project_files`.`project_id` = "'.$project_id.'" AND `project_files`.`employee_id` LIKE Employee.id',
					
					'emp_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id AND `project_employees`.`project_id` LIKE "' .$project_id . '"'
				);
				$teamMembers = $this->ProjectFile->Project->ProjectEmployee->Employee->find(
					'list',array(
						'recursive'=>-1,
						'fields'=>array('Employee.id','Employee.name','Employee.check_free','Employee.emp_check'),
						'conditions'=>array(
							// 'Employee.check_free'=> 0, 
							'Employee.emp_check >'=> 0
						)
				));

				
				// $this->set('teamMembers',$this->team_members($project_id));
				$this->set('teamMembers',$teamMembers);

				$this->set('currentStatuses',$this->Project->customArray['currentStatuses']);
			
				$this->set('fileStatuses',$this->ProjectFile->customArray['currentStatuses']);
				$this->set('fileCategories',$this->ProjectFile->FileCategory->find('list',array('conditions'=>array(
					'FileCategory.publish'=>1,
					'FileCategory.soft_delete'=>0,
					// 'FileCategory.status'=>0,
					'FileCategory.project_id'=>$project_id,
					'FileCategory.milestone_id'=>$milestone_id))));


				$fileBatches = $this->ProjectFile->find('list',array(
					'conditions'=>array(
						'ProjectFile.project_id'=>$project_id,
						// 'ProjectFile.milestone_id'=>$milestone_id,
						'ProjectFile.single_batch'=>1
					)
				));

				$this->set('fileBatches',$fileBatches);

				return $this->_count($projectFiles);
		}

		
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('ProjectFile.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProjectFile->recursive = 0;
		$this->set('projectFiles', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectFile.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectFile->recursive = 0;
		$this->set('projectFiles', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectFile']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectFile']['search_field'] as $search):
				$search_array[] = array('ProjectFile.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectFile.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectFile->recursive = 0;
		$this->paginate = array('order'=>array('ProjectFile.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectFile.soft_delete'=>0 , $cons));
		$this->set('projectFiles', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectFile.'.$search => $search_key);
					else $search_array[] = array('ProjectFile.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectFile.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectFile.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectFile.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectFile.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectFile.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectFile->recursive = 0;
		$this->paginate = array('order'=>array('ProjectFile.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectFile.soft_delete'=>0 );
		$this->set('projectFiles', $this->paginate());
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
		if (!$this->ProjectFile->exists($id)) {
			throw new NotFoundException(__('Invalid project file'));
		}
		$options = array('recursive'=>-1, 'conditions' => array('ProjectFile.' . $this->ProjectFile->primaryKey => $id));

		$this->ProjectFile->virtualFields = array(
			'process_start_time'=>'select `start_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`start_time` IS NOT NULL ORDER BY file_processes.sr_no ASC LIMIT 1',
			'process_end_time'=>'select `end_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`end_time` IS NOT NULL ORDER BY file_processes.sr_no DESC LIMIT 1',
			'total_time'=>'select (timediff((select `end_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`end_time` IS NOT NULL ORDER BY file_processes.sr_no DESC LIMIT 1),(select `start_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`start_time` IS NOT NULL ORDER BY file_processes.sr_no ASC LIMIT 1)))',
			'actual_time_from_process' => 'select SEC_TO_TIME(SUM(TIME_TO_SEC(actual_time))) from file_processes where file_processes.project_file_id LIKE ProjectFile.id AND start_time IS NOT NULL AND end_time IS NOT NULL',
			'hold_time_from_process' => 'select SEC_TO_TIME(SUM(TIME_TO_SEC(hold_time))) from file_processes where file_processes.project_file_id LIKE ProjectFile.id AND hold_time IS NOT NULL AND hold_type_id != "60478d96-efa8-4ff7-9b19-7507ac100145"',
		);

		$projectFile = $this->ProjectFile->find('first', $options);

		 $results = $this->ProjectFile->FileProcess->find('all',array('order'=>array('FileProcess.sr_no'=>'DESC'), 'recursive'=>-1,'conditions'=>array('FileProcess.project_file_id'=>$projectFile['ProjectFile']['id'])));
		

		foreach($results as $result){
			$others = $this->ProjectFile->FileProcess->OtherMeasurableUnitValue->find('all',array(
				'recursive'=>0,
				'conditions'=>array('OtherMeasurableUnitValue.file_process_id'=>$result['FileProcess']['id'])
			));
			$proresults[] = array('FileProcess'=>$result['FileProcess'],'OtherMeasurableUnitValue'=>$others);
		}
		
		$projectFile['FileProcess'] = $proresults;

		$this->loadModel('ProjectProcessPlan');
	        $projectProcesses = $this->ProjectProcessPlan->find(
	            'list',array(
	                'conditions'=>$projectFile['ProjectFile']['process_id'],
	                'fields'=>array(
	                    'ProjectProcessPlan.id',
	                    'ProjectProcessPlan.process'
	                ),
	                'order'=>array(
	                    'ProjectProcessPlan.sequence'=>'ASC'
	                )
	            )
	        );

	 //        Configure::Write('debug',1);
	 //        debug($projectFile);
		// exit;
		$this->set('projectProcesses',$projectProcesses);
		$this->set('PublishedEmployeeList',$this->_get_employee_list());
		$this->set('projectFile', $projectFile);

		$currentStatuses = $this->ProjectFile->customArray['currentStatuses'];
		$this->set('currentStatuses',$currentStatuses);


		// get merged file details
		$mergedFiles = $this->ProjectFile->find('list',array('conditions'=>array('ProjectFile.parent_id'=>$id)));
		$this->set('mergedFiles',$mergedFiles);

		$this->loadModel('HoldType');
		$holdTypes = $this->HoldType->find('list',array(
			// 'conditions'=>array('HoldType.project_id'=>$ProjectFile['ProjectFile']['project_id'])
		));

		$this->set('holdTypes',$holdTypes);		
	}



/**
 * list method
 *
 * @return void
 */
	public function lists() {
	
        $this->_get_count();		

	}


	public function find_free_res($project_id = null){
		$this->loadModel('ProjectResource');

		$this->ProjectResource->virtualFields = array(
			'emp_already_assigned'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id AND `project_files`.`project_id` = ProjectResource.project_id',
			'emp_on_leave'=> 0,
		);

		
		$resources = $this->ProjectResource->find('first',array(
			'conditions'=>array(
				'ProjectResource.project_id'=>$project_id,
				'ProjectResource.emp_already_assigned '=>0,
			),
			'fields'=>array(
				'ProjectResource.id',
				'ProjectResource.priority',
				'Employee.id',
				'Employee.name',
				'User.id',
				'User.name',
				'User.name',
				'ProjectProcessPlan.id',
				'ProjectProcessPlan.process',
				'ProjectResource.emp_already_assigned',
				'ProjectResource.emp_on_leave',
				'Project.id',
				'Project.title',
				'Project.start_date',
				'Project.end_date',
			),
			'Order'=>array('ProjectResource.priority'=>'ASC')
		));
		return $resources;
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

			$this->request->data['ProjectFile']['system_table_id'] = $this->_get_system_table_id();
			// get files
			$files = str_getcsv($this->request->data['ProjectFile']['file_names'],PHP_EOL);
			
			// common conditions 
			// 1. check if employee is working on any other file
			// 2. check if employee is on leave
			// 3. check if its holiday
			// 4. 
			$this->loadModel('ProjectResource');

			// get project process 
	        $this->loadModel('ProjectOverallPlan');
	        $this->loadModel('ProjectProcessPlan');

	        $this->ProjectFile->ProjectProcessPlan->virtualFields = array(
				'ocount'=>'select count(*) from project_overall_plans where  project_overall_plans.id LIKE ProjectProcessPlan.project_overall_plan_id'
			);

	        $projectProcesses = $this->ProjectProcessPlan->find(
	            'first',array(
	                'conditions'=>array(
	                	'ProjectProcessPlan.project_id'=> $this->request->data['ProjectFile']['project_id'],
	                	'ProjectProcessPlan.milestone_id'=> $this->request->data['ProjectFile']['milestone_id'],
	                	'ProjectProcessPlan.ocount >'=> 0,
	                ),
	                'fields'=>array(
	                    'ProjectProcessPlan.id',
	                    'ProjectProcessPlan.process',
	                    'ProjectProcessPlan.estimated_units',
	                    'ProjectProcessPlan.overall_metrics',
	                    'ProjectProcessPlan.days',
	                    'ProjectProcessPlan.estimated_resource',
	                ),
	                'order'=>array(
	                    'ProjectProcessPlan.sequence'=>'ASC'
	                )
	            )
	        );


	        $plan = $this->ProjectOverallPlan->find('first',array(
	        	'recursive'=>-1,
	        	'conditions'=>array('ProjectOverallPlan.project_id'=>$this->request->data['ProjectFile']['project_id'],'ProjectOverallPlan.milestone_id'=>$this->request->data['ProjectFile']['milestone_id'])));

	        	        // exit;

	        // this should be in loop below
	        $this->loadModel('FileProcesses');
	        $x = 0;

	        // Configure::Write('debug',1);
	        // debug($this->request->data);
	        // debug($files);
	        // exit;

	        	$this->ProjectFile->Employee->virtualFields = array(
	        		'userexists'=>'select count(*) from users where users.employee_id LIKE Employee.id'
	        	);
	        	$errStr1 = '';
	        	$errStr2 = '';
			foreach ($files as $file) {
				$f = split(',', $file);
				$d = array();

				// check if file details are correct

				$d['ProjectFile']['name'] = ltrim(rtrim($f[0]));
				$d['ProjectFile']['unit'] = ltrim(rtrim(str_replace('"', '', $f[1])));
				$d['ProjectFile']['city'] = ltrim(rtrim(str_replace('"', '', $f[2])));
				$d['ProjectFile']['block'] = ltrim(rtrim(str_replace('"', '', $f[3])));
				$d['ProjectFile']['employee_number'] = ltrim(rtrim(str_replace('"', '', $f[4])));

				if($d['ProjectFile']['name'] != '' && $d['ProjectFile']['unit'] != ''){
								
					// $estimated_time = $plan['ProjectOverallPlan']['estimated_units'] / $plan['ProjectOverallPlan']['overall_metrics'] / $plan['ProjectOverallPlan']['days'] / $plan['ProjectOverallPlan']['estimated_resource'];

					// $estimated_time = $plan['ProjectOverallPlan']['estimated_units'] / $plan['ProjectOverallPlan']['overall_metrics'] / $plan['ProjectOverallPlan']['days'] / $plan['ProjectOverallPlan']['estimated_resource'];

					$estimated_time = $this->request->data['ProjectFile']['unit'] / $plan['ProjectOverallPlan']['overall_metrics'];
					
					if($estimated_time   == '')$estimated_time = 0;

					$d['ProjectFile']['estimated_time'] = $estimated_time;
					$d['ProjectFile']['name'] = ltrim(rtrim($f[0]));
					// $d['ProjectFile']['unit'] = ltrim(rtrim($f[1]));
					$d['ProjectFile']['single_batch'] = $this->request->data['ProjectFile']['single_batch'];
					$d['ProjectFile']['file_batch_id'] = $this->request->data['ProjectFile']['file_batch_id'];
					$d['ProjectFile']['employee_id'] = 'Not assigned';
					$d['ProjectFile']['project_id'] = $this->request->data['ProjectFile']['project_id'];
					$d['ProjectFile']['milestone_id'] = $this->request->data['ProjectFile']['milestone_id'];
					$d['ProjectFile']['current_status'] = 4;
					$d['ProjectFile']['file_category_id'] = $this->request->data['ProjectFile']['file_category_id'];
					$d['ProjectFile']['priority'] = $this->request->data['ProjectFile']['file_category_priority'];
					// Configure::write('debug',1);
					
					// debug($estimated_time);
					// debug($plan);
					// debug($d);
					// exit;
					$this->loadModel('ProjectEmployee');
					//debug($d);
					// check auto manual
					if($this->request->data['ProjectFile']['auto_manual'] == 1){
						debug($d['ProjectFile']['employee_number']);
						if($d['ProjectFile']['employee_id']){
							$emp = $this->ProjectFile->Employee->find('first',array(
								'recursive'=>-1,
								'fields'=>array('Employee.id','Employee.employee_number','Employee.userexists'),
								'conditions'=>array(
									'Employee.userexists >'=>0,
									'Employee.employee_number'=>$d['ProjectFile']['employee_number'])));							

							if($emp){
								// check if this employee belongs to the project currently

								$thisProject = $this->ProjectEmployee->find('first',array(
							            'recursive'=>-1,
							            'fields'=>array('ProjectEmployee.id','ProjectEmployee.project_id'),
							            'order'=>array('ProjectEmployee.sr_no'=>'DESC'),
							            'conditions'=>array(
							            	'ProjectEmployee.project_id'=>$d['ProjectFile']['project_id'],
							                'ProjectEmployee.employee_id'=> $emp['Employee']['id']
							                )
							        ));

							        if($thisProject){							       

									debug($emp);
									$d['ProjectFile']['employee_id'] = $emp['Employee']['id'];
									$d['ProjectFile']['current_status'] = 0;

									$d['ProjectFile']['auto_manual'] = 0;
							
									// check if file is already added for currnet project 
									$fileChk = $this->ProjectFile->find('count',array(
										'conditions'=>array(
											'ProjectFile.name'=>ltrim(rtrim($f[0])),
											'ProjectFile.project_id'=>$this->request->data['ProjectFile']['project_id'],
											'ProjectFile.milestone_id'=>$this->request->data['ProjectFile']['milestone_id'],
										)
									));

									$d['ProjectFile']['project_process_plan_id'] = $projectProcesses['ProjectProcessPlan']['id'];

									if($fileChk == 0){
										// debug($d);
										$this->ProjectFile->create();
										
										if($this->ProjectFile->save($d,false)){
											$id = $this->ProjectFile->id;
											$this->ProjectFile->create();
											$fp = array();
										            // if($this->ProjectFile->save($projectFile,false)){
										                $this->loadModel('FileProcess');
										                $projectFile = $this->ProjectFile->find('first',array(
										                	'recursive'=>-1,
										                	'conditions'=>array('ProjectFile.id'=>$id)
										                ));

										                debug($projectFile);

										                $fp['project_id'] = $projectFile['ProjectFile']['project_id'];
										                $fp['milestone_id'] = $projectFile['ProjectFile']['milestone_id'];
										                $fp['employee_id'] = $emp['Employee']['id'];
										                $fp['assigned_date'] = date('Y-m-d H:i:s');
										                $fp['estimated_time'] = $projectFile['ProjectFile']['estimated_time'];
										                $fp['current_status'] = 0;                
										                $fp['project_process_plan_id'] = $projectFile['ProjectFile']['project_process_plan_id'];
										                $fp['project_file_id'] = $projectFile['ProjectFile']['id'];
										                $fp['publish'] = 1;
										                $fp['soft_delete'] = 0;  
										                $this->FileProcess->create();
										                $this->FileProcess->save($fp,false);


										                $this->_track_file(
										                 $project_file_id = $projectFile['ProjectFile']['project_id'], 
										                 $project_id = $projectFile['ProjectFile']['project_id'], 
										                 $milestone_id = $projectFile['ProjectFile']['milestone_id'], 
										                 $from = '??', 
										                 $to = $emp['Employee']['id'],
										                 $by = $this->Session->read('User.employee_id'), 
										                 $current_status = 0, 
										                 $change_type = 0, 
										                 $function = 'add_ajax', 
										                 $comments = 'File changed via add_ajax function'
										                );
										            // }
										}
									}else{
										// echo "This file is already added";
										$errStr1 .= 'file : '.  ltrim(rtrim($f[0])) . ' already exists. <br />';
									}
									}else{
							        	$errStr2 .= "This emp ". $d['ProjectFile']['employee_number'] ." does not belong to current project. <br />"  ;
							        }
							}else{
								echo "User doesn't exists";
							}
						}else{
							echo "Employee not found";
						}
					}else{
						$d['ProjectFile']['auto_manual'] = $this->request->data['ProjectFile']['auto_manual'];
					
						// check if file is already added for currnet project 
						$fileChk = $this->ProjectFile->find('count',array(
							'conditions'=>array(
								'ProjectFile.name'=>ltrim(rtrim($f[0])),
								'ProjectFile.project_id'=>$this->request->data['ProjectFile']['project_id'],
								'ProjectFile.milestone_id'=>$this->request->data['ProjectFile']['milestone_id'],
							)
						));

						$d['ProjectFile']['project_process_plan_id'] = $projectProcesses['ProjectProcessPlan']['id'];

						if($fileChk == 0){
							// debug($d);
							$this->ProjectFile->create();
							if($this->ProjectFile->save($d,false)){
								
							}
						}else{
							echo "This file is already added";
						}
					}					

				}
			}	
			$errStr = $errStr1 . ' <br />' . $errStr2;
			if($errStr != ''){
				$this->Session->setFlash(__('The project files has been saved'));	
			}else{
				$this->Session->setFlash(__('Error : <br /> . ' . $errStr));
			}	
			
			$this->redirect(array('controller'=>'project_files', 'action' => 'project_files',$this->request->data['ProjectFile']['project_id'],$this->request->data['ProjectFile']['milestone_id'],$this->request->data['ProjectFile']['pop'],$this->request->data['ProjectFile']['por']));
			
		}
		$projects = $this->ProjectFile->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectFile->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$employees = $this->ProjectFile->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectFile->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectFile->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectFile->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectFile->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectFile->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectFile->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectFile->find('count');
		$published = $this->ProjectFile->find('count',array('conditions'=>array('ProjectFile.publish'=>1)));
		$unpublished = $this->ProjectFile->find('count',array('conditions'=>array('ProjectFile.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

	}







	public function get_employee_sent_back($project_id = null, $milestone_id = null,$employee_id = null,$project_file_id = null,$project_process_plan_id = null){
		$this->loadModel('ProjectResource');
		
		$this->ProjectResource->virtualFields = array(
			'emp_already_assigned_seq'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id  AND `project_files.employee_id` LIKE "'. $employee_id .'"',
			'emp_already_assigned'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id  AND `project_files.employee_id` NOT LIKE "'. $employee_id .'"',
			'emp_on_leave'=> 0,				
		);	
		
		

		$resource = $this->ProjectResource->find('first',array(
			'conditions'=>array(
				'ProjectResource.emp_already_assigned_seq'=> 0,
				'ProjectResource.employee_id' => $employee_id
			),
			'fields'=>array(
				'Employee.id',
				'Employee.name',
				'User.id',
				'User.name',
				'User.name',
				'ProjectResource.emp_already_assigned',
				'ProjectResource.emp_already_assigned_seq'				
			),			
		));

		
		if(!$resource){
			echo "new user";
			$resource = $this->ProjectResource->find('first',array(
			'conditions'=>array(
				'ProjectResource.project_id'=>$project_id,
				'ProjectResource.emp_already_assigned'=> 0,				
			),
			'fields'=>array(
				'Employee.id',
				'Employee.name',
				'User.id',
				'User.name',
				'User.name',
				'ProjectResource.emp_already_assigned',
				'ProjectResource.emp_already_assigned_seq'				
			),
			'Order'=>array('ProjectResource.priority'=>'ASC')
		));
		}
		return $resource;		
	}

	public function sent_back_file($data = null){
		
		// process
		
		// 1. Mark existing file as sent-back with status 8
		// 2. Check availibility of earlier user and see if the user has been assigned any other file currently 

		$employee_id = $this->get_employee_sent_back(
			$data['ProjectFile']['project_id'], 
			$data['ProjectFile']['milestone_id'],
			$data['ProjectFile']['pre_employee_id'],
			$data['ProjectFile']['id'],
			$data['ProjectFile']['pre_project_process_plan_id']
		);

		
		// 3. If yes, add the file in queue 
		// Option 1. Assign this file to ealier user (wait till he completed the earlier file)
		// Option 2. Assign this file to some other user

		// get existing process
		$existingProcess = $this->ProjectFile->FileProcess->find('first',array(
			'conditions'=>array('FileProcess.id'=>$data['ProjectFile']['file_process_id']),
			'recursive'=>1
		));

		// mark existing process as in queue 
		$this->ProjectFile->FileProcess->read(null,$data['ProjectFile']['file_process_id']);
		$this->ProjectFile->FileProcess->set('queued',1);
		$this->ProjectFile->FileProcess->set('sent_back',1);
		$this->ProjectFile->FileProcess->set('pre_project_process_plan_id',$data['ProjectFile']['pre_project_process_plan_id']);
		$this->ProjectFile->FileProcess->set('pre_employee_id',$data['ProjectFile']['pre_employee_id']);
		$this->ProjectFile->FileProcess->set('comments',$data['ProjectFile']['comments'] . ' (This file is sent back.)');
		$this->ProjectFile->FileProcess->save();
		
		// add new 
		// $fp['FileProcess']['id'] = $data['ProjectFile']['file_process_id'];
		$fp['FileProcess']['current_status'] = 0;
		$fp['FileProcess']['units_completed'] = $data['ProjectFile']['units_completed'];
		$fp['FileProcess']['project_id'] = $data['ProjectFile']['project_id'];
		$fp['FileProcess']['milestone_id'] = $data['ProjectFile']['milestone_id'];
		$fp['FileProcess']['employee_id'] = $data['ProjectFile']['pre_employee_id'];
		$fp['FileProcess']['project_process_plan_id'] = $data['ProjectFile']['pre_project_process_plan_id'];
		// $fp['FileProcess']['current_status'] = $data['ProjectFile']['current_status'];
		// $fp['FileProcess']['project_process_plan_id'] = $data['ProjectFile']['curr_stage'];


		$fp['FileProcess']['estimated_time'] = $data['ProjectFile']['estimated_time'];
		$fp['FileProcess']['project_file_id'] = $data['ProjectFile']['id'];
		$fp['FileProcess']['comments'] = $data['ProjectFile']['comments'] . ' this is from employees sent back function 2';
		$fp['FileProcess']['prepared_by'] = $data['ProjectFile']['employee_id'];
		$fp['FileProcess']['publish'] = 1;
		$fp['FileProcess']['sent_back'] = 1;
		$fp['FileProcess']['soft_delete'] = 0;
		$fp['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');

		
		$this->ProjectFile->FileProcess->create();
		$this->ProjectFile->FileProcess->save($fp,false);


		$this->ProjectFile->read(null,$data['ProjectFile']['id']);
		$this->ProjectFile->set('current_status',8);
		$this->ProjectFile->set('employee_id',$data['ProjectFile']['pre_employee_id']);
		$this->ProjectFile->save();
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
 
	public function get_file_updates($id = null){
		$this->autoRender = false;
		$updates = $this->ProjectFile->FileProcess->find('all',array(
			'conditions'=>array('FileProcess.project_file_id'=>$id),
			'order'=>array('ProjectFile.created'=>'ASC')
		));
		return array($id,$updates);
	}
	


	public function start_stop($id = null,$status = null,$hold_type_id = null,$units_completed = null){
		$this->autoRender = false;
		// Configure::Write('debug',1);
		// debug($this->request);
		// debug($this->request->data['OtherMeasurableUnit']);
		$this->request->data['OtherMeasurableUnit'] = str_replace('},]}','}]}',$this->request->data['OtherMeasurableUnit']);
		$otherunitdata = json_decode($this->request->data['OtherMeasurableUnit'],true);		
		
		// first check if this file still belongs to this user
		// to be added later
		// $file = $this->ProjectFile->find('first',array('conditions'=>array(),'recursive'=>01));

		if($hold_type_id == -1){
			echo "Not added anything";
			return false;
		}
		
		if(empty($this->Session->read('User.employee_id'))){
			$this->Session->setFlash('Session Exipred. Login again.');
			return "logout";
			exit;
		}
		
		// exit;
		$this->loadModel('FileProcess');
		$rec = $this->FileProcess->find('first',array(
			'conditions'=>array('FileProcess.id'=>$id),
			'recursive'=>0
		));

		if($rec['FileProcess']['employee_id'] == $this->Session->read('User.employee_id')){

			$newPro['FileProcess'] = $rec['FileProcess'];
			if($rec['FileProcess']['hold_start_time'] && $rec['FileProcess']['hold_end_time'] && $status != 0){ 
				// create a new record
				// (a new process record is created new hold status is added for the process which ahd hold start/end added already)

				unset($newPro['FileProcess']['id']);
				unset($newPro['FileProcess']['hold_end_time']);
				unset($newPro['FileProcess']['sr_no']);
				unset($newPro['FileProcess']['created']);
				unset($newPro['FileProcess']['modified']);
				unset($newPro['FileProcess']['comments']);
				unset($newPro['FileProcess']['units_completed']);
				
				$newPro['FileProcess']['units_completed'] = $units_completed;
				$newPro['FileProcess']['current_status'] = 7;
				$newPro['FileProcess']['hold_type_id'] = $hold_type_id;
				$newPro['FileProcess']['hold_start_time'] = date('Y-m-d H:i:s');			
				$this->FileProcess->create();
				// debug($newPro);
				$this->FileProcess->save($newPro,false);

				$this->loadModel('OtherMeasurableUnitValue');
				// update other units
				foreach($otherunitdata['data'] as $data){
					$newdata = array();
					$newdata['OtherMeasurableUnit']['other_measurable_unit_id'] = $data['id'];
					$newdata['OtherMeasurableUnit']['value'] = $data['value'];
					$newdata['OtherMeasurableUnit']['file_process_id'] = $rec['FileProcess']['id'];
					$newdata['OtherMeasurableUnit']['project_process_plan_id'] = $rec['FileProcess']['project_process_plan_id'];
					$newdata['OtherMeasurableUnit']['project_id'] = $rec['FileProcess']['project_id'];
					$newdata['OtherMeasurableUnit']['milestone_id'] = $rec['FileProcess']['milestone_id'];
					$this->OtherMeasurableUnitValue->create();
					$this->OtherMeasurableUnitValue->save($newdata['OtherMeasurableUnit'],false);
					
				}

				$this->_updateholdfilestatus($newPro['FileProcess']['project_file_id'],7,$this->Session->read('User.employee_id'));

				return 'New Hold Time Added';
			}else{
				if($id){			
					if($status == 0){
						$newPro['FileProcess']['start_time'] = date('Y-m-d H:i:s');
						$this->FileProcess->create();
						$this->FileProcess->save($newPro,false);

						$res = 'Start time added';
					}elseif($status == 1){
						$newPro['FileProcess']['end_time'] = date('Y-m-d H:i:s');
						$res = 'End time added';

						$this->_updateholdfilestatus($newPro['FileProcess']['project_file_id'],0,$this->Session->read('User.employee_id'));

					}elseif($status == 2){
						$newPro['FileProcess']['hold_start_time'] = date('Y-m-d H:i:s');
						$newPro['FileProcess']['hold_type_id'] = $hold_type_id;
						$newPro['FileProcess']['current_status'] = 7;
						$res = 'Hold start time added';

						$this->_updateholdfilestatus($newPro['FileProcess']['project_file_id'],7,$this->Session->read('User.employee_id'));

					}elseif($status == 3){
						$newPro['FileProcess']['hold_end_time'] = date('Y-m-d H:i:s');
						$newPro['FileProcess']['current_status'] = 0; // added new
						$res = 'Hold end time added';

						$this->_updateholdfilestatus($newPro['FileProcess']['project_file_id'],0,$this->Session->read('User.employee_id'));
					}				
					$this->FileProcess->save($newPro,false);

				}
			}
			return $res;

		}else{
			$this->Session->setFlash('This file is no loger available for you to work on. Showing newly assigned file instead');
			$res = 'This file is no loger available for you to work on. Showing newly assigned file instead';
			return $res;
			// exit;
		}		
	}

	public function _updateholdfilestatus($project_file_id = null, $status = null, $employee_id = null){
		// updating the file record

		$file = $this->ProjectFile->find('first',array(
			'recursive'=>-1,
			'conditions'=>array('ProjectFile.id'=>$project_file_id)
		));
		$file['ProjectFile']['queued'] = 0;
		$file['ProjectFile']['current_status'] = $status;
		$file['ProjectFile']['employee_id'] = $employee_id;
		$this->ProjectFile->create();
		$this->ProjectFile->save($file,false);

		// at this stage .. if the start_time is added, system should put all other files / except this as queued. 

		$files = $this->ProjectFile->find(
			'all',array(
				'conditions'=>array(
					'ProjectFile.id !=' => $project_file_id,
					'ProjectFile.employee_id ' => $employee_id,
					'ProjectFile.current_status !=' => array(1,3,5),
					'ProjectFile.queued' => array(0,null),
				),
				'recursive'=> 0
			)
		);

		foreach ($files as $file) {
			$file['ProjectFile']['queued'] = 1;
			$this->ProjectFile->create();
			$this->ProjectFile->save($file,false);

			$this->_track_file(
				$project_file_id = $file['ProjectFile']['id'], 
				$project_id = $file['ProjectFile']['project_id'], 
				$milestone_id = $file['ProjectFile']['milestone_id'], 
				$from = '??', 
				$to = $file['ProjectFile']['employee_id'], 
				$by = $this->Session->read('User.employee_id'), 
				$current_status = $file['ProjectFile']['current_status'], 
				$change_type = 0, 
				$function = 'start_stop_function', 
				$comments = 'File changed via start_stop_function'
			);
			# code...
		}

		$this->_get_file_duration($project_file_id);

		return true;


	}

	public function get_next_process($project_id = null, $milestone_id = null,$project_file_id = null,$current_process = null,$us = 0){

		$this->loadModel('ProjectProcessPlan');	
		$currentProcess = $this->ProjectProcessPlan->find('first',array('conditions'=>array('ProjectProcessPlan.id'=>$current_process)));
		
		if($currentProcess){

			if($us == 1){
				// echo "N1: ";
				$nextProccess = $this->ProjectProcessPlan->find('first',array('conditions'=>array(
					'ProjectProcessPlan.project_id'=>$project_id,
					'ProjectProcessPlan.milestone_id'=>$milestone_id,
					'ProjectProcessPlan.sequence'=> ($currentProcess['ProjectProcessPlan']['sequence']-1),

				)));	
			}else{
				// echo "N2: ";
				// debug($currentProcess['ProjectProcessPlan']['sequence']+1);
				$nextProccess = $this->ProjectProcessPlan->find('first',array('conditions'=>array(
					'ProjectProcessPlan.project_id'=>$project_id,
					'ProjectProcessPlan.milestone_id'=>$milestone_id,
					'ProjectProcessPlan.sequence'=> ($currentProcess['ProjectProcessPlan']['sequence']+1),

				)));
			}

			
		}		
		return $nextProccess['ProjectProcessPlan']['id'];
	}

	public function get_first_process($project_id = null, $milestone_id = null,$project_file_id = null){
		$this->loadModel('ProjectProcessPlan');	

		$this->ProjectProcessPlan->virtualFields = array(
            'op'=>'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id'
        );

		$plan = $this->ProjectProcessPlan->find('list',
				array(
					'fields'=>array(
					'ProjectProcessPlan.id',
					'ProjectProcessPlan.process',
				),
				'conditions'=>array(
					'ProjectProcessPlan.op >'=>0,
					'ProjectProcessPlan.project_id'=>$project_id,
					'ProjectProcessPlan.milestone_id'=>$milestone_id,
				),
				'order'=>array(
					'ProjectProcessPlan.sequence'=>'ASC',	
				)
			)
		);
		
		$getFirstProcess = $this->ProjectFile->FileProcess->find(
			'first',array(
				'limit'=>1,
				'fields'=>array(
					'FileProcess.id',
					'FileProcess.project_process_plan_id',
				),
				'conditions'=>array(
					'FileProcess.project_process_plan_id' => array_keys($plan),
					'FileProcess.project_file_id'=>$project_file_id
				),
				'order'=>array(
					'FileProcess.sr_no'=>'DESC'
				),
				'group'=>array(
					'FileProcess.project_process_plan_id'
				)
			)
		);

		return $getFirstProcess['FileProcess']['project_process_plan_id'];
	}

	public function get_pre_process($project_id = null, $milestone_id = null,$project_file_id = null,$currentProcesses = null){
		$this->autoRender = false;
		if(!$currentProcesses){
			$this->loadModel('ProjectProcessPlan');

			$this->ProjectProcessPlan->virtualFields = array(
	            'op'=>'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id'
	        );

			$plan = $this->ProjectProcessPlan->find('list',
					array(
						'fields'=>array(
						'ProjectProcessPlan.id',
						'ProjectProcessPlan.process',
					),
					'conditions'=>array(
						'ProjectProcessPlan.op >'=>0,
						'ProjectProcessPlan.project_id'=>$project_id,
						'ProjectProcessPlan.milestone_id'=>$milestone_id,
					),
					'order'=>array(
						'ProjectProcessPlan.sequence'=>'DESC',
					)
				)
			);
			
			$getLastProcess = $this->ProjectFile->FileProcess->find(
				'list',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.project_process_plan_id',
					),
					'conditions'=>array(
						'FileProcess.project_process_plan_id' => array_keys($plan),
						'FileProcess.project_file_id'=>$project_file_id
					),
					'order'=>array(
						'FileProcess.created'=>'DESC'
					),
					'group'=>array(
						'FileProcess.project_process_plan_id'
					)
				)
			);
			// find add processess 
			$allProcess = $this->ProjectFile->FileProcess->find(
				'all',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.project_process_plan_id',
						'FileProcess.employee_id',
						'ProjectProcessPlan.id',
						'ProjectProcessPlan.process',
						'Employee.id',
						'Employee.name',
					),
					'conditions'=>array(
						'FileProcess.project_process_plan_id' => array_keys($plan),
						'FileProcess.project_file_id'=>$project_file_id,
						'FileProcess.employee_id !=' =>'Not Assigned'
					),
					'order'=>array(
						'FileProcess.sr_no'=>'DESC'
					),
					'group'=>array(
						'FileProcess.project_process_plan_id',
						'FileProcess.employee_id',
					)
				)
			);
			
			foreach ($getLastProcess as $key => $value) {
				
				if(array_key_exists($value, $plan)){				
					// unset($plan[$value]);
					$vals[] = $value;
					$newPlan[$value] = $plan[$value];
				}
			}
			
			unset($newPlan[$vals[0]]);
			return array(key($newPlan),$newPlan,$allProcess[$this->_count($allProcess)-1]['Employee']['id']);
		
		}else{

			$currentProcess = $this->ProjectFile->FileProcess->find('first',array('conditions'=>array('FileProcess.id'=>$currentProcesses),'recursive'=>0));
			$s = $currentProcess['ProjectProcessPlan']['sequence'] - 1;			
			$prevProccess = $this->ProjectFile->FileProcess->find(
			'first',array(
				'fields'=>array(
					'FileProcess.id',
					'FileProcess.project_process_plan_id',
					'FileProcess.employee_id',
					'ProjectProcessPlan.id',
					'ProjectProcessPlan.process',
					'Employee.id',
					'Employee.name',
				),
				'conditions'=>array(
					// 'FileProcess.project_process_plan_id' => $currentProcess['FileProcess']['project_process_plan_id'],
					'FileProcess.project_file_id'=> $currentProcess['FileProcess']['project_file_id'],
					'ProjectProcessPlan.sequence'=> $s
				),
				'order'=>array(
					'FileProcess.sr_no'=>'DESC'
				),
				'group'=>array(
					'FileProcess.project_process_plan_id',
					'FileProcess.employee_id',
				)
			)
		);	
			
			$plan = $this->ProjectFile->FileProcess->ProjectProcessPlan->find('list',
				array(
						'fields'=>array(
						'ProjectProcessPlan.id',
						'ProjectProcessPlan.process',
					),
					'conditions'=>array(
						'ProjectProcessPlan.op >'=>0,
						'ProjectProcessPlan.project_id'=>$currentProcess['FileProcess']['project_id'],
						'ProjectProcessPlan.milestone_id'=>$currentProcess['FileProcess']['milestone_id'],
					),
					'order'=>array(
						'ProjectProcessPlan.sequence'=>'DESC',
					)
				)
			);
			$getLastProcess = $this->ProjectFile->FileProcess->find(
				'list',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.project_process_plan_id',
					),
					'conditions'=>array(
						// 'FileProcess.project_process_plan_id' => $currentProcess['FileProcess']['project_process_plan_id'],
						// 'FileProcess.project_file_id'=> $currentProcess['FileProcess']['project_file_id'],
					),
					'order'=>array(
						'FileProcess.created'=>'DESC'
					),
					'group'=>array(
						'FileProcess.project_process_plan_id'
					)
				)
			);

			foreach ($getLastProcess as $key => $value) {
				
				if(array_key_exists($value, $plan)){
					$vals[] = $value;
					$newPlan[$value] = $plan[$value];
				}
			}
			if($prevProccess){
				return array($prevProccess['FileProcess']['project_process_plan_id'],$newPlan,$prevProccess['Employee']['id']);
			}			
		}
	}

	public function get_employee(
		$project_id = null, 
		$milestone_id = null,
		$employee_id = null,
		$project_file_id = null,
		$project_process_plan_id = null,
		$process = null){
		
		$this->loadModel('ProjectResource');
		
		if($project_process_plan_id){
			$file_details = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$project_file_id)));	
		}

		if($employee_id){			
			$this->ProjectResource->virtualFields = array(
				'emp_already_assigned_seq'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id  AND ProjectResource.process_id ="' . $project_process_plan_id .'"',
				'emp_already_assigned'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id AND ProjectResource.employee_id NOT LIKE "' . $employee_id .'"',
				'emp_on_leave'=> 0,
				'pro_publish'=>'select `publish` from `projects` where `projects`.`id` = ProjectResource.project_id',
				'pro_delete'=>'select `soft_delete` from `projects` where `projects`.`id` = ProjectResource.project_id',
			);	
			$empCon = array('ProjectResource.employee_id !=' => $employee_id);
		}else{			
			$this->ProjectResource->virtualFields = array(
				'emp_already_assigned_seq'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id  AND ProjectResource.process_id ="' . $project_process_plan_id .'"',
				'emp_already_assigned'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id ',
				'emp_on_leave'=> 0,
				'pro_publish'=>'select `publish` from `projects` where `projects`.`id` = ProjectResource.project_id',
				'pro_delete'=>'select `soft_delete` from `projects` where `projects`.`id` = ProjectResource.project_id',
			);
			$empCon = array();	
		}

		

			$queue = 0;
			$resource = $this->ProjectResource->find('first',array(
				'conditions'=>array(
					'ProjectResource.project_id'=>$project_id,
					'ProjectResource.emp_already_assigned'=> 0,
					// 'ProjectResource.emp_already_assigned_seq'=>0,
					'ProjectResource.pro_publish'=>1,
					'ProjectResource.pro_delete'=>0,
					'ProjectResource.process_id'=>$project_process_plan_id,
					$empCon
				),
				'fields'=>array(
					'ProjectResource.id',
					'ProjectResource.priority',
					'Employee.id',
					'Employee.name',
					'User.id',
					'User.name',
					'User.name',
					'ProjectResource.emp_already_assigned',
					'ProjectResource.process_id',
					'ProjectProcessPlan.id',
					'ProjectProcessPlan.process',
					'ProjectResource.emp_already_assigned',
					// 'ProjectResource.emp_on_leave',
					// 'Project.id',
					// 'Project.title',
					// 'Project.start_date',
					// 'Project.end_date',
				),
				'order'=>array(
					'ProjectResource.priority'=>'ASC',
					'ProjectResource.emp_already_assigned_seq'=>'DESC'
				)
			));

			if(!$resource){
				$queue = 1;
				$resource = $this->ProjectResource->find('first',array(
				'conditions'=>array(
					'ProjectResource.project_id'=>$project_id,
					// 'ProjectResource.emp_already_assigned'=> 0,
					// 'ProjectResource.emp_already_assigned_seq'=>0,
					'ProjectResource.pro_publish'=>1,
					'ProjectResource.pro_delete'=>0,
					'ProjectResource.process_id'=>$project_process_plan_id,
					$empCon
				),
				'fields'=>array(
					'ProjectResource.id',
					'ProjectResource.priority',
					'Employee.id',
					'Employee.name',
					'User.id',
					'User.name',
					'User.name',
					'ProjectResource.emp_already_assigned',
					'ProjectResource.process_id',
					'ProjectProcessPlan.id',
					'ProjectProcessPlan.process',
					'ProjectResource.emp_already_assigned',
					// 'ProjectResource.emp_on_leave',
					// 'Project.id',
					// 'Project.title',
					// 'Project.start_date',
					// 'Project.end_date',
				),
				'order'=>array(
					'ProjectResource.priority'=>'ASC',
					'ProjectResource.emp_already_assigned_seq'=>'DESC'
				)
			));
			}
		return array($resource,$queue);
	}


	/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {

		if(empty($this->Session->read('User.employee_id'))){
			$this->Session->setFlash('Session Exipred. Login again.');
			return "logout";
			exit;
		}
		

		if (!$this->ProjectFile->exists($id)) {
			throw new NotFoundException(__('Invalid project file'));
		}
		


		if ($this->_show_approvals()) {
            		$this->set(array('showApprovals' => $this->_show_approvals()));
        	}
		


		if ($this->request->is('post') || $this->request->is('put')) {
			
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        			$this->request->data[$this->modelClass]['publish'] = 0;
      			}

      			$this->request->data['ProjectFile']['system_table_id'] = $this->_get_system_table_id();
		


			if(!$this->request->data['ProjectFile']['current_status']){
				$this->Session->setFlash(__('You can not submit file which is on hold. Click on Release hold and then mark file as Complete/Reject/Accept and then submit.'));
				$this->redirect(array('controller'=>'users', 'action' => 'pm_dashboard'));
			}			


			// if acceptedm then consider as completed.
			if($this->request->data['ProjectFile']['current_status'] == 9)$this->request->data['ProjectFile']['current_status'] = 1;


			if($this->request->data['ProjectFile']['current_status'] == 7){
				$this->Session->setFlash(__('You can not submit file which is on hold. Click on Release hold and then mark file as Complete/Reject/Accept and then submit.'));
				$this->redirect(array('controller'=>'users', 'action' => 'pm_dashboard'));
			}

			// update other units
			$this->loadModel('OtherMeasurableUnitValue');			
			foreach($this->request->data['OtherMeasurableUnitValue'] as $data){
				$newdata = array();
				$newdata['OtherMeasurableUnit']['other_measurable_unit_id'] = $data['id'];
				$newdata['OtherMeasurableUnit']['value'] = $data['value'];
				$newdata['OtherMeasurableUnit']['file_process_id'] = $this->request->data['ProjectFile']['file_process_id'];
				$newdata['OtherMeasurableUnit']['project_process_plan_id'] = $this->request->data['ProjectFile']['project_process_plan_id'];
				$newdata['OtherMeasurableUnit']['project_id'] = $this->request->data['ProjectFile']['project_id'];
				$newdata['OtherMeasurableUnit']['milestone_id'] = $this->request->data['ProjectFile']['milestone_id'];
				$this->OtherMeasurableUnitValue->create();
				$this->OtherMeasurableUnitValue->save($newdata['OtherMeasurableUnit'],false);				
			}	

			// exit;

			// if files are merged

			if($this->request->data['ProjectFile']['current_status'] == 11){
				$this->request->data['ProjectFile']['checklist'] = json_encode($this->request->data['ProjectChecklist']['name']);			
				//create new file 
				$existing_file = $this->ProjectFile->find('first',array(
					'recursive'=>-1,
					'conditions'=>array('ProjectFile.id'=>$this->request->data['ProjectFile']['id']))
				);
				// VF for completed units
				// all selected files
				$cnt = 0;
				foreach ($this->request->data['ProjectFile']['file_ids'] as $allfiles) {
					if($allfiles != 0){
						$cnt++;

					}
				}
				if($cnt == 0){
					$this->Session->setFlash(__('Select files to merge'));
					$this->redirect(array('controller'=>'users', 'action' => 'pm_dashboard'));
				}
				foreach ($this->request->data['ProjectFile']['file_ids'] as $allfiles) {
					if($allfiles != 0){
						//get units
						$this->ProjectFile->virtualFields = array(
							'completedUnits'=>'select SUM(units_completed) FROM `file_processes` WHERE `file_processes`.`project_file_id` LIKE ProjectFile.id'
						);

						$allfile = $this->ProjectFile->find('first',array(
							// 'fields'=>array(),
							'recursive'=>-1,
							'conditions'=>array('ProjectFile.id'=>$allfiles)
						));
						debug($allfile);
						$units = $units + $allfile['ProjectFile']['unit'];
						$completed_units = $units + $allfile['ProjectFile']['completedUnits'];

						// get last run sequence
						$runedFileProcess = $this->ProjectFile->FileProcess->find('all',array('conditions'=>array('FileProcess.project_file_id'=>$allfiles),'recursive'=>-1));

					}
				}
				
				unset($existing_file['ProjectFile']['id']);
				unset($existing_file['ProjectFile']['sr_no']);
				$new_file = $existing_file;
				$new_file['ProjectFile']['unit'] = $units;
				$new_file['ProjectFile']['name'] = $this->request->data['ProjectFile']['new_filename'];


				if($this->request->data['ProjectFile']['merge_close'] == 1){
					$new_file['ProjectFile']['current_status'] = 5;
					$new_file['ProjectFile']['end_date'] = date('Y-m-d H:i:s');
				}else{
					$new_file['ProjectFile']['current_status'] = $this->request->data['ProjectFile']['current_status'];	
				}
				
				$new_file['ProjectFile']['completed_date'] = date('Y-m-d H:i:s');
				

				$this->ProjectFile->create();
				$this->ProjectFile->save($new_file,false);

				// also replicate the process for this file based on exising process run by last child file
				$new_file_id = $this->ProjectFile->id;


				foreach ($runedFileProcess as $runedFileProces) {
					unset($runedFileProces['FileProcess']['id']);
					unset($runedFileProces['FileProcess']['sr_no']);
					unset($runedFileProces['FileProcess']['created']);
					unset($runedFileProces['FileProcess']['modified']);
					$runedFileProces['FileProcess']['project_file_id'] = $new_file_id ;
					$runedFileProces['FileProcess']['employee_id'] = $this->Session->read('User.employee_id') ;
					$runedFileProces['FileProcess']['comments'] = 'Merged File';
					$this->ProjectFile->FileProcess->create();
					$this->ProjectFile->FileProcess->save($runedFileProces,false);
				}
				// updated existing files with new file's ID as parent ID & marked those files as mearged
				foreach ($this->request->data['ProjectFile']['file_ids'] as $allfiles) {
					if($allfiles != 0){
						$allfile = $this->ProjectFile->find('first',array(
							// 'fields'=>array(),
							'recursive'=>-1,
							'conditions'=>array('ProjectFile.id'=>$allfiles)
						));
						
						$allfile['ProjectFile']['parent_id'] = $new_file_id;
						$allfile['ProjectFile']['current_status'] = 11; // merged						
						$this->ProjectFile->create();
						$this->ProjectFile->save($allfile,false);

					}
				}

				
				
				$this->request->data['ProjectFile']['id'] = $new_file_id;
				unset($this->request->data['ProjectFile']['pre_project_process_plan_id']);
				unset($this->request->data['ProjectFile']['pre_employee_id']);
				
				$this->add_errors($this->request->data,$new_file_id);
				$this->update_file_process($this->request->data);				
			}else{

				// Configure::Write('debug',1);
				// debug($this->request->data);
				

				if($this->request->data['ProjectFile']['current_status'] == 8){ // rejected file
					
					$this->add_errors($this->request->data,$this->request->data['ProjectFile']['id']);
					$this->sent_back_file($this->request->data);
				}else{					
										
					unset($this->request->data['ProjectFile']['pre_project_process_plan_id']);
					unset($this->request->data['ProjectFile']['pre_employee_id']);
					
					$this->add_errors($this->request->data,$this->request->data['ProjectFile']['id']);
					$this->update_file_process($this->request->data);
				}				
			}
			
			// exit;

			$this->Session->setFlash(__('The project file is updated.'));
			$this->redirect(array('controller'=>'users', 'action' => 'pm_dashboard'));	

		} else {
			$options = array('conditions' => array('ProjectFile.' . $this->ProjectFile->primaryKey => $id));
			$this->request->data = $this->ProjectFile->find('first', $options);
		}
		$projects = $this->ProjectFile->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectFile->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$employees = $this->ProjectFile->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectFile->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectFile->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectFile->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectFile->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectFile->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectFile->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectFile->find('count');
		$published = $this->ProjectFile->find('count',array('conditions'=>array('ProjectFile.publish'=>1)));
		$unpublished = $this->ProjectFile->find('count',array('conditions'=>array('ProjectFile.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));
	}


	public function update_file_process($data = null){

		if($data['ProjectFile']['curr_stage']){

			// this fucntion edits existing process ( does not create new)

			
			// if files are not merged, original file id is sent
			// if files are merged, in edit function, exsiting files are updated with new file id as a parent id and parent id is sent to this function.

			// echo "Updating file process table <br />";
			
			if($data['ProjectFile']['current_status'] == 1)$fp['FileProcess']['end_time'] = date('Y-m-d H:i:s');

			
			// updating new/existing file details with 
			$this->loadModel('FileProcess');
			$fp['FileProcess']['id'] = $data['ProjectFile']['file_process_id'];

			if($data['ProjectFile']['current_status'] == 11)unset($fp['FileProcess']['id']); // merged

			$fp['FileProcess']['checklist'] = $data['ProjectFile']['checklist'];

			if($this->request->data['ProjectFile']['merge_close'] == 1){
				$fp['FileProcess']['current_status']['current_status'] = 5;
				$fp['FileProcess']['end_time'] = date('Y-m-d H:i:s');
			}else{
				$fp['FileProcess']['current_status'] = $this->request->data['ProjectFile']['current_status'];	
			}


			$fp['FileProcess']['current_status'] = $data['ProjectFile']['current_status'];
			$fp['FileProcess']['units_completed'] = $data['ProjectFile']['units_completed'];
			$fp['FileProcess']['project_id'] = $data['ProjectFile']['project_id'];
			$fp['FileProcess']['milestone_id'] = $data['ProjectFile']['milestone_id'];

			
			if($data['ProjectFile']['current_status'] == 5 || $data['ProjectFile']['merge_close'] == 1){ // closed
				// $fp['FileProcess']['employee_id'] = 'Closed';
				$fp['FileProcess']['employee_id'] = $this->Session->read('User.employee_id');
				$fp['FileProcess']['end_time'] = date('Y-m-d H:i:s');
				// $fp['FileProcess']['assigned_date'] = NULL;
			}else{
				$fp['FileProcess']['employee_id'] = $data['ProjectFile']['employee_id'];
				$fp['FileProcess']['assigned_date'] = date('Y-m-d H:i:s'); // ????
			}

			if($data['ProjectFile']['current_status'] == 11  || $data['ProjectFile']['merge_close'] == 1)$fp['FileProcess']['end_time'] = date('Y-m-d H:i:s');
			
			// $fp['FileProcess']['current_status'] = $data['ProjectFile']['current_status'];
			$fp['FileProcess']['project_process_plan_id'] = $data['ProjectFile']['curr_stage'];
			$fp['FileProcess']['project_file_id'] = $data['ProjectFile']['id'];
			$fp['FileProcess']['comments'] = $data['ProjectFile']['comments'].' - added by update_file_process function';
			
			// $fp['FileProcess']['prepared_by'] = $data['ProjectFile']['employee_id'];
			$fp['FileProcess']['prepared_by'] = $this->Session->read('User.employee_id');
			$fp['FileProcess']['publish'] = 1;
			$fp['FileProcess']['soft_delete'] = 0;
			
			$this->FileProcess->create();
			$this->FileProcess->save($fp,false);
			
			echo "marking Closed";
			

			$this->_track_file(
				$project_file_id = $fp['FileProcess']['project_file_id'], 
				$project_id = $fp['FileProcess']['project_id'], 
				$milestone_id = $fp['FileProcess']['milestone_id'], 
				$from = '??', 
				$to = $fp['FileProcess']['employee_id'], 
				$by = $this->Session->read('User.employee_id'), 
				$current_status = $data['ProjectFile']['current_status'], 
				$change_type = 0, 
				$function = 'update_file_process', 
				$comments = 'File changed via update_file_process function'
			);
		}
		
		$this->update_project_file($data);

		$this->_get_file_duration($fp['FileProcess']['project_file_id']);

		return true;		
	}

	public function update_project_file($data = null){		
	
		$merging = $this->check_merging($data['ProjectFile']['id']);
		
		if($merging == false){
			if($data['ProjectFile']['current_status'] == 5){
		
				$ufile = $this->ProjectFile->find('first',array(
					'recursive'=>-1,
					'conditions'=>array('ProjectFile.id'=>$data['ProjectFile']['id'])));
				
				$ufile['ProjectFile']['current_status'] = 5;
				$ufile['ProjectFile']['employee_id'] = 'Closed';
				// $ufile['FileProcess']['employee_id'] = $this->Session->read('User.employee_id');
				// $ufile['ProjectFile']['project_process_plan_id'] = $project_process_plan_id = $data['ProjectFile']['project_process_plan_id'];
				$ufile['ProjectFile']['project_process_plan_id'] = $data['ProjectFile']['project_process_plan_id'];

				// debug($ufile);
				// exit;

				$this->ProjectFile->create();
				$this->ProjectFile->save($ufile,false);		
			}else{
				
				if($data['ProjectFile']['merge_close'] != 1){
						
						$sent_back = 0;
						if($data['ProjectFile']['pre_qc_process_id']){
							$project_process_plan_id = $data['ProjectFile']['pre_qc_process_id'];
							$current_status = 8;
							$employee_id = $data['ProjectFile']['pre_qc_employee_id'];
							$sent_back = 1;
						}else{

							if($data['ProjectFile']['returned_file'] == 1)$op1 = 1;
							else $op1 = 0;
							// need to add return files true here

							$project_process_plan_id = $this->get_next_process(
								$data['ProjectFile']['project_id'], 
								$data['ProjectFile']['milestone_id'],
								$data['ProjectFile']['id'],
								$data['ProjectFile']['project_process_plan_id'],
								$op1
							);
							
							if($data['ProjectFile']['current_status'] != 7){
								if(!$employee){
									$employee_id = 'Not Assigned';
									$current_status = 4;
									// $project_process_plan_id = $data['ProjectFile']['project_process_plan_id'];
									// $project_process_plan_id = $project_process_plan_id;
								}else{
									$employee_id = $employee['Employee']['id'];
									$current_status = 0; // ?????

								}
							}else{
								$employee_id = 'Not Assigned';
								$current_status = 4;								
							}
						}
						
						$ufile = $this->ProjectFile->find('first',array(
							'recursive'=>-1,
							'conditions'=>array('ProjectFile.id'=>$data['ProjectFile']['id'])));
						
						$ufile['ProjectFile']['comments'] = 'May be this is adding extra';
						$ufile['ProjectFile']['current_status'] = $current_status;
						$ufile['ProjectFile']['employee_id'] = $employee_id;
						$ufile['ProjectFile']['project_process_plan_id'] = $project_process_plan_id;

						
						$this->ProjectFile->create();
						if($this->ProjectFile->save($ufile,false)){
						
							if($data['ProjectFile']['current_status'] != 7 && $project_process_plan_id != null){
								$fp = array();
								$projectProcesses = $this->ProjectFile->ProjectProcessPlan->find('first',array(
									'conditions'=>array(
										'ProjectProcessPlan.id'=>$project_process_plan_id
									),
									'recursive'=>-1
								));

								$estimated_time = $projectProcesses['ProjectProcessPlan']['estimated_units'] / $projectProcesses['ProjectProcessPlan']['overall_metrics'] / $projectProcesses['ProjectProcessPlan']['days'] / $projectProcesses['ProjectProcessPlan']['estimated_resource'];

								if($estimated_time   == '')$estimated_time = 0;

								$this->loadModel('FileProcess');
								$fp['FileProcess']['current_status'] = $current_status;
								$fp['FileProcess']['units_completed'] = 0;
								$fp['FileProcess']['project_id'] = $data['ProjectFile']['project_id'];
								$fp['FileProcess']['milestone_id'] = $data['ProjectFile']['milestone_id'];
								$fp['FileProcess']['employee_id'] = $employee_id;
								$fp['FileProcess']['estimated_time'] = $estimated_time;
								$fp['FileProcess']['project_process_plan_id'] = $project_process_plan_id;
								$fp['FileProcess']['project_file_id'] = $data['ProjectFile']['id'];
								$fp['FileProcess']['comments'] = 'NIL (update_project_file function)';
								$fp['FileProcess']['prepared_by'] = $data['ProjectFile']['employee_id'];
								$fp['FileProcess']['publish'] = 1;
								$fp['FileProcess']['soft_delete'] = 0;
								$fp['FileProcess']['sent_back'] = $sent_back;
								$fp['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');
								
								if($get_employee[1] == 0){
									$fp['FileProcess']['queued'] = NULL ;
								}else{
									$fp['FileProcess']['queued'] = 1 ;
								}								
							}elseif($project_process_plan_id == null){

							}
				}else{
					
				}
					

				}else{
					$ufile = $this->ProjectFile->find('first',array(
						'recursive'=>-1,
						'conditions'=>array('ProjectFile.id'=>$data['ProjectFile']['id'])));
					
					$ufile['ProjectFile']['current_status'] = 5;
					$ufile['ProjectFile']['employee_id'] = $employee_id;
					$ufile['ProjectFile']['project_process_plan_id'] = NULL;
				}			
			}
		}else{

			}

		
	}


	public function check_merging($file_id = null){
		
		$file = $this->ProjectFile->find('first',array(
			'conditions'=>array('ProjectFile.id'=>$file_id),
			'recursive'=>-1,
		));

		
		$aa = $this->ProjectFile->FileProcess->find('first',array(
			'conditions'=>array(
				'ProjectFile.file_category_id'=>$file['ProjectFile']['file_category_id'],
				'ProjectProcessPlan.qc'=>2,
			)
		));


		$next_project_process_plan_id = $this->get_next_process(
			$file['ProjectFile']['project_id'],
			$file['ProjectFile']['milestone_id'],
			$file['ProjectFile']['id'],
			$file['ProjectFile']['project_process_plan_id'],
			0
		);
		
		// which process
		$whichProcess = $this->ProjectFile->ProjectProcessPlan->find('first',array(
			'recursive'=>0,
			'conditions'=>array('ProjectProcessPlan.id'=>$next_project_process_plan_id)
		));

		
		if($aa && $whichProcess['ProjectProcessPlan']['qc'] == 2){
			// assign this file to same user
			
			$file['ProjectFile']['current_status'] = 12;
			$file['ProjectFile']['project_process_plan_id'] = $aa['FileProcess']['project_process_plan_id'];
			$file['ProjectFile']['employee_id'] = $aa['FileProcess']['employee_id'];
			$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
		
			$this->ProjectFile->create();
			$this->ProjectFile->save($file,false);
			// save this 

			// add project process 
			unset($aa['FileProcess']['id']);
			unset($aa['FileProcess']['sr_no']);
			unset($aa['FileProcess']['created']);
			unset($aa['FileProcess']['modified']);
			$aa['FileProcess']['project_file_id'] = $file_id;

			$this->ProjectFile->FileProcess->create();
			$this->ProjectFile->FileProcess->save($aa,false);
		}

		if($aa && $whichProcess['ProjectProcessPlan']['qc'] == 2){
			// return merging true
			return true;
		}else{
			// return merging false
			return false;
		}
		
		// exit;
	}

	
	public function re_arrange_files($project_id = null, $milestone_id = null,$employee_id = null,$project_process_plan_id = null,$file_id = null){
		return true;		
	}


	public function queue_other_files($employee_id = null,$file_id = null, $update_file = null,$project_process_plan_id = null){

		if($update_file == true){

			$file = $this->ProjectFile->find(
				'first',array(
					'conditions'=>array(
						'ProjectFile.id' => $file_id,						
					),
					'recursive'=> -1
				)
			);

			$file['ProjectFile']['employee_id'] = $employee_id;
			$file['ProjectFile']['current_status'] = 0;
			$file['ProjectFile']['comments'] = 'File Updated By Re-arrange Files Function';
			$file['ProjectFile']['queued'] = 0;
			$file['ProjectFile']['project_process_plan_id'] = $project_process_plan_id;
			$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
			$this->ProjectFile->create();
			$this->ProjectFile->save($file,false);
		}

		$file = array();
		$files = $this->ProjectFile->find(
			'all',array(
				'conditions'=>array(
					'ProjectFile.id !=' => $file_id,
					'ProjectFile.employee_id ' => $employee_id,
					'ProjectFile.current_status !=' => array(1,3,5), // completed / cancled / closed
				),
				'recursive'=> 0
			)
		);

		
		foreach ($files as $file) {
			$file['ProjectFile']['queued'] = 1;
		
			$this->ProjectFile->create();
			$this->ProjectFile->save($file,false);
			# code...
		}
		return true;

	}

	public function add_errors($data = null, $file_process_id = null){

		$this->loadModel('FileError');
		if($data['FileError']){
			foreach ($data['FileError'] as $errData) {
				if($errData['total_errors'] > 0){
					$this->FileError->create();
					$errData['file_process_id'] = $file_process_id;
					$errData['prepared_by'] = $this->Session->read('User.employee_id');
					$errData['publish'] = 1;
					$errData['soft_delete'] = 0;
					$this->FileError->save($errData,false);
				}
			}
		}
	}
	
	public function changeuser($id = null, $employee_id = null,$process_id = null){

		
		// $fileProcess = $this->ProjectFile->FileProcess->find('first',array(
		// 	'conditions'=>array(
		// 		'FileProcess.project_file_id'=>$id
		// 	),
		// 	'order'=>array('FileProcess.sr_no'=>'DESC')
		// ));

		// if(!$fileProcess){
		// 	// create new process
		// 	$file = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$id)));

		// 	$file['ProjectFile']['project_process_plan_id'] = $process_id;
		// 	$file['ProjectFile']['employee_id'] = $employee_id;
		// 	$file['ProjectFile']['current_status'] = 0;
		// 	$file['ProjectFile']['queued'] = 0;
		// 	$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');

		// 	$this->ProjectFile->create();
		// 	$this->ProjectFile->save($file,false);

		// 	// reopen same file again
		// 	$file = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$id)));

			
		// 	$this->loadModel('FileProcess');
		// 	$fp['FileProcess'] = $fileProcess['FileProcess'];
		// 	$fp['FileProcess']['current_status'] = 10; // reassigned file (new status)
		// 	$fp['FileProcess']['comments'] = 're-assigned via changeuser function on ' . date('Y-m-d H:i:s');
		// 	$fp['FileProcess']['units_completed'] = 0;
		// 	$fp['FileProcess']['project_id'] = $file['ProjectFile']['project_id'];
		// 	$fp['FileProcess']['milestone_id'] = $file['ProjectFile']['milestone_id'];
		// 	$fp['FileProcess']['employee_id'] = $employee_id;
		// 	// $fp['FileProcess']['current_status'] = $data['ProjectFile']['current_status'];

		// 	$estimated_time = $file['ProjectProcessPlan']['estimated_units'] / $file['ProjectProcessPlan']['overall_metrics'] / $file['ProjectProcessPlan']['days'] / $file['ProjectProcessPlan']['estimated_resource'];
		// 	if($estimated_time   == '')$estimated_time = 0;
		// 	$fp['FileProcess']['estimated_time'] = $estimated_time;
		// 	$fp['FileProcess']['project_process_plan_id'] = $process_id;
		// 	$fp['FileProcess']['project_file_id'] = $id;
		// 	$fp['FileProcess']['comments'] = 'Re-assigned-0 file via changeuser function on ' . date('Y-m-d H:i:s');
		// 	$fp['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');
		// 	// $fp['FileProcess']['prepared_by'] = $data['ProjectFile']['employee_id'];
		// 	$fp['FileProcess']['prepared_by'] = $this->Session->read('User.employee_id');
		// 	$fp['FileProcess']['publish'] = 1;
		// 	$fp['FileProcess']['queued'] = 0;
		// 	$fp['FileProcess']['soft_delete'] = 0;
			
		// 	$this->FileProcess->create();
		// 	$this->FileProcess->save($fp,false);


		// 	$this->_track_file(
		// 		$project_file_id = $file['ProjectFile']['id'], 
		// 		$project_id = $file['ProjectFile']['project_id'], 
		// 		$milestone_id = $file['ProjectFile']['milestone_id'], 
		// 		$from = '??', 
		// 		$to = $employee_id, 
		// 		$by = $this->Session->read('User.employee_id'), 
		// 		$current_status = 10, 
		// 		$change_type = 0, 
		// 		$function = 'changeuser-1', 
		// 		$comments = 'File changed via changeuser function 1'
		// 	);


		// 	$this->queue_other_files($employee_id,$id, true, $process_id);
		// 	$this->_get_file_duration($file['ProjectFile']['id']);
		// 	return true;
		// }else{
			
		// 	$this->loadModel('FileProcess');
		// 	$fp['FileProcess'] = $fileProcess['FileProcess'];			

		// 	$fp['FileProcess']['prepared_by'] = $this->Session->read('User.employee_id');
		// 	$fp['FileProcess']['current_status'] = 10;
		// 	$fp['FileProcess']['end_time'] = date('Y-m-d H:i:s');
		// 	$fp['FileProcess']['comments'] = '';

		// 	$this->FileProcess->create();
		// 	$this->FileProcess->save($fp,false);

		// 	// also update file // added - 29-06-2021
		// 	$file = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$id)));

		// 	$file['ProjectFile']['project_process_plan_id'] = $process_id;
		// 	$file['ProjectFile']['employee_id'] = $employee_id;
		// 	$file['ProjectFile']['current_status'] = 10;
		// 	$file['ProjectFile']['queued'] = 0;
		// 	$file['ProjectFile']['comments'] = 're-assigned-2 ?? by ' . $this->Session->read('User.username') . ' on '. date('Y-m-d H:i:s');
		// 	$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');

		// 	$this->ProjectFile->create();
		// 	$this->ProjectFile->save($file,false);


		// 	$this->_track_file(
		// 		$project_file_id = $file['ProjectFile']['id'], 
		// 		$project_id = $file['ProjectFile']['project_id'], 
		// 		$milestone_id = $file['ProjectFile']['milestone_id'], 
		// 		$from = '??', 
		// 		$to = $employee_id, 
		// 		$by = $this->Session->read('User.employee_id'), 
		// 		$current_status = 10, 
		// 		$change_type = 0, 
		// 		$function = 'changeuser-2', 
		// 		$comments = 'File changed via changeuser-2 function'
		// 	);

		// 	// change all re-assigned for current process to 10

		// 	$all = $this->ProjectFile->FileProcess->find('all',array(
		// 		'recursive'=>-1,
		// 		'conditions'=>array(
		// 			'FileProcess.project_file_id'=>$id,
		// 			// 'FileProcess.project_process_plan_id'=>$fileProcess['FileProcess']['project_process_plan_id'],
		// 			'FileProcess.comments'=>'re-assigned'
		// 		)
		// 	));	

			
		// 	foreach($all as $a){
		// 		debug($a);
		// 		$a['FileProcess']['current_status'] = 10;
		// 		$a['FileProcess']['end_time'] = date('Y-m-d H:i:s');
		// 		$this->ProjectFile->FileProcess->create();
		// 		$this->ProjectFile->FileProcess->save($a,false);


		// 		$this->_track_file(
		// 			$project_file_id = $a['FileProcess']['project_file_id'], 
		// 			$project_id = $a['FileProcess']['project_id'], 
		// 			$milestone_id = $a['FileProcess']['milestone_id'], 
		// 			$from = '??', 
		// 			$to = $employee_id, 
		// 			$by = $this->Session->read('User.employee_id'), 
		// 			$current_status = 10, 
		// 			$change_type = 0, 
		// 			$function = 'changeuser-2', 
		// 			$comments = 'File changed via changeuser function, resetting all file'
		// 		);
		// 	}
			
		// 	$file = $this->ProjectFile->find('first',array('recursive'=>-1, 'conditions'=>array('ProjectFile.id'=>$id)));

		// 	$estimated_time = $file['ProjectProcessPlan']['estimated_units'] / $file['ProjectProcessPlan']['overall_metrics'] / $file['ProjectProcessPlan']['days'] / $file['ProjectProcessPlan']['estimated_resource'];

		// 	$file['ProjectFile']['employee_id'] = $employee_id;
		// 	$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
		// 	$file['FileProcess']['project_process_plan_id'] = $process_id;
		// 	$file['ProjectFile']['comments'] = 're-assigned-4 (all) via changeuser function on ' . date('Y-m-d H:i:s');
		// 	$file['ProjectFile']['current_status'] = 10;
		// 	$file['ProjectFile']['queued'] = 0;
		// 	$file['FileProcess']['estimated_time'] = $estimated_time;

		// 	$this->ProjectFile->create();
		// 	$this->ProjectFile->save($file,false);


		// 	// create new file process record
		// 	$data['FileProcess'] = $fp['FileProcess'];
		// 	unset($data['FileProcess']['id']);
		// 	unset($data['FileProcess']['sr_no']);
		// 	unset($data['FileProcess']['start_time']);
		// 	unset($data['FileProcess']['end_time']);
		// 	unset($data['FileProcess']['hold_start_time']);
		// 	unset($data['FileProcess']['hold_end_time']);
		// 	unset($data['FileProcess']['hold_type_id']);
		// 	unset($data['FileProcess']['units_completed']);
		// 	$data['FileProcess']['current_status'] = 10;
		// 	$data['FileProcess']['queued'] = 0;
		// 	$data['FileProcess']['employee_id'] = $employee_id;
		// 	$data['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');
		// 	$data['FileProcess']['project_process_plan_id'] = $process_id;

		// 	$this->ProjectFile->FileProcess->create();
		// 	$this->ProjectFile->FileProcess->save($data,false);


		// 	$this->_track_file(
		// 		$project_file_id = $file['ProjectFile']['id'], 
		// 		$project_id = $file['ProjectFile']['project_id'], 
		// 		$milestone_id = $file['ProjectFile']['milestone_id'], 
		// 		$from = '??', 
		// 		$to = $employee_id, 
		// 		$by = $this->Session->read('User.employee_id'), 
		// 		$current_status = 10, 
		// 		$change_type = 0, 
		// 		$function = 'changeuser-3', 
		// 		$comments = 'File changed via changeuser function'
		// 	);
			
		// 	return true;	
		// }

	}

	public function deleteallfiles($project_id = null){
		$files  = $this->ProjectFile->find('list',array(
			'conditions'=>array('ProjectFile.project_id'=>$project_id)
		));
		foreach ($files as $key => $value) {
			$this->ProjectFile->FileProcess->deleteAll(array('FileProcess.project_file_id'=>$key));
			$this->ProjectFile->deleteAll(array('ProjectFile.id'=>$key));
		}
		$this->redirect(array('controller'=>'projects', 'view' => $project_id));	
	}


	public function delete_project_file($id = null,$project_id = null, $milestone_id = null, $pop = null, $por = null){
		$this->autoRender = false;
		
		$data = $this->ProjectFile->find('first',array(
			'conditions'=>array('ProjectFile.id'=>$this->request->params['pass'][0]),
			'recursive'=>-1));

		$this->ProjectFile->deleteAll(array('ProjectFile.id'=>$this->request->params['pass'][0]));
		$this->ProjectFile->FileError->deleteAll(array('FileError.project_file_id'=>$this->request->params['pass'][0]));
		$this->ProjectFile->FileProcess->deleteAll(array('FileProcess.project_file_id'=>$this->request->params['pass'][0]));


		// also delete merged files
		$files = $this->ProjectFile->find('all',array('conditions'=>array('ProjectFile.parent_id'=>$this->request->params['pass'][0])));
		if($files){
			foreach ($files as $file) {
				$this->ProjectFile->deleteAll(array('ProjectFile.id'=>$file['ProjectFile']['id']));
				$this->ProjectFile->FileError->deleteAll(array('FileError.project_file_id'=>$file['ProjectFile']['id']));
				$this->ProjectFile->FileProcess->deleteAll(array('FileProcess.project_file_id'=>$file['ProjectFile']['id']));
			}
		}

		$this->re_arrange_files($this->request->params['pass'][1],$this->request->params['pass'][2],$data['ProjectFile']['employee_id'],null,$data['ProjectFile']['id']);

		
		$this->Session->setFlash(__('The project files has been deleted'));
		$this->redirect(array('controller'=>'project_files', 'action' => 'project_files',$this->request->params['pass'][1],$this->request->params['pass'][2],$this->request->params['pass'][3],$this->request->params['pass'][3]));
	}

	public function rectifyemp(){
		
		$this->ProjectFile->hasMany['FileProcess']['conditions'] = array('OR'=>array('FileProcess.employee_id'=>'Closed' , 'FileProcess.current_status'=>5) );

		$files = $this->ProjectFile->find('all',array('conditions'=>array(
			// 'ProjectFile.current_status'=>5,
			'ProjectFile.employee_id'=>'Closed',
		)));
		

		foreach ($files as $file) {
			// get employee who closed the file
			$modified_by = $this->ProjectFile->ModifiedBy->find('first',array('recursive'=>-1,'conditions'=>array('ModifiedBy.id'=>$file['FileProcess'][0]['modified_by'])));
			$employee_id = $modified_by['ModifiedBy']['employee_id'];
			
			# code...

			// update file_process
			$prodata = $file['FileProcess'][0];
			
			$prodata['employee_id'] = $employee_id;
			$this->ProjectFile->FileProcess->create();
			$this->ProjectFile->FileProcess->save($prodata,false);

			// update project_file
			$fdata = $file['ProjectFile'];
			$fdata['employee_id'] = $employee_id;
			$this->ProjectFile->create();
			$this->ProjectFile->save($fdata,false);

		}

		exit;
	}

	public function test_file_arrange($project_id = null, $milestone_id = null)
	{		
		//first files which are not assigned or work has not started on those files
		$this->ProjectFile->virtualFields = array(
			'start_time'=>'select start_time from file_processes where file_processes.project_file_id LIKE ProjectFile.id ORDER BY file_processes.created DESC LIMIT 1',
			'hold_start_time'=>'select hold_start_time from file_processes where file_processes.project_file_id LIKE ProjectFile.id ORDER BY file_processes.created DESC LIMIT 1'
		);
		$files = $this->ProjectFile->find('all',array(
			'contain'=>array(
				'FileProcess.sr_no',
				'FileProcess.start_time',
				'FileProcess.hold_start_time',
				'FileProcess.hold_end_time',
				'FileProcess.employee_id',
				'FileProcess.created',
			),
			'conditions'=>array(
				'ProjectFile.project_id'=>$project_id,
				// 'ProjectFile.milestone_id'=>$milestone_id,
				'OR'=>array(
					'OR'=>array(
						 'ProjectFile.start_time'=>'',
						  'ProjectFile.start_time'=>NULL
					),
					'OR'=>array(
						 'ProjectFile.hold_start_time'=>'',
						  'ProjectFile.hold_start_time'=>NULL
					),
				)
			)
		));

		// then find employees for which are not assgiend any files and assign these files to them.
		$this->loadModel('ProjectResource');
		$projectResources = $this->ProjectResource->find(
			'all',array(
				'conditions'=>array('ProjectResource.project_id'=>$project_id)
			)
		);
	}


	public function _hrdiff($endTime = null, $startTime = null, $project_id = null, $daily_hours = null){

		// Configure::Write('debug',1);

		$daily_off = 24 - $daily_hours;

		$hourdiff = date_diff(
			date_create(date('Y-m-d H:i',strtotime($endTime))), 
			date_create(date('Y-m-d H:i',strtotime($startTime)))
		);

		debug($hourdiff);


		$daysinsec = $hourdiff->d * 3600 * 24;
		$hoursinsecs = $hourdiff->h * 3600;
		$misinsec = $hourdiff->i * 60;		
		$sec = $hourdiff->s;


		// if days are more than 1 day .. then minus extra non-working hours
		if($hourdiff->d > 0){
			$days = $hourdiff->d;
			$daysgap = $days * ($daily_off  * 3600);
		}else{
			if($hourdiff->h > $daily_hours){
				$daysgap = ($hourdiff->h - $daily_hours)  * 3600;
			}
		}

		debug($daysinsec);
		debug($hoursinsecs);
		debug($misinsec);
		debug($sec);
		debug($daysgap);
		
		// also add holidays here (later)
		$newhourdiff = $daysinsec + $hoursinsecs + $misinsec + $sec;
		
		debug($newhourdiff);
		
		$newhourdiff = $newhourdiff - $daysgap;
		
		debug($newhourdiff);

		$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
				$project_id,
				base64_encode($startTime),
				base64_encode($endTime))
			);
		
		$totalHolidays = 0;

		if($holidays > 0){
			if(in_array(date('Y-m-d',strtotime($startTime)),$holidays))unset($holidays[0]);
			if(in_array(date('Y-m-d',strtotime($endTime)),$holidays))unset($holidays[0]);
			$totalHolidays = ($this->_count($holidays)-1) * 3600 * 24;
		}		
		// debug($startTime);
		// debug($endTime);
		// debug($hourdiff);
		debug($totalHolidays);
		if($totalHolidays > 0)$newhourdiff = $newhourdiff - $totalHolidays;		
		debug($newhourdiff);

		return $newhourdiff;
	}	
	

	public function get_file_duration($id = null){
		$this->autoRender = false;
		$this->_get_file_duration($id);
		// exit;
	}


	public function _get_file_duration($id = null){
		// Configure::Write('debug',1);

		$this->ProjectFile->FileProcess->virtualFields = array(
			'pre_total_time'=>'select TIMEDIFF(end_time,start_time) WHERE start_time IS NOT NULL AND end_time IS NOT NULL',
			'pre_total_time_days'=>'select DATEDIFF(end_time,start_time) WHERE start_time IS NOT NULL AND end_time IS NOT NULL',
			'pre_hold_time'=>'select TIMEDIFF(hold_end_time,hold_start_time) WHERE hold_start_time IS NOT NULL AND hold_end_time IS NOT NULL',
			'final_actual_time' => 'select TIMEDIFF(hold_time,total_time) WHERE hold_time IS NOT NULL'			
		);

		
		if($id)$condition = array('FileProcess.project_file_id'=>$id);
		else $condition = array();
		
		$fileProcesses = $this->ProjectFile->FileProcess->find('all',array(
			'fields'=>array(
				'FileProcess.id',
				'FileProcess.sr_no',
				'FileProcess.project_id',
				'FileProcess.milestone_id',
				'FileProcess.project_file_id',
				'FileProcess.project_process_plan_id',
				'FileProcess.start_time',
				'FileProcess.end_time',
				'FileProcess.hold_start_time',
				'FileProcess.hold_end_time',
				'FileProcess.total_time',
				'FileProcess.hold_time',
				'FileProcess.actual_time',
				'FileProcess.units_completed',
				'FileProcess.pre_total_time',
				'FileProcess.pre_total_time_days',
				'FileProcess.pre_hold_time',
				'FileProcess.final_actual_time',
				'FileProcess.employee_id',
			),
			'order'=>array('FileProcess.sr_no'=>'DESC'),
			'recursive'=>-1,'conditions'=>$condition));
		
		$project = $this->ProjectFile->Project->find('first',array('recursive'=>-1,
			'fields'=>array('Project.id','Project.daily_hours'),
			'conditions'=>array('Project.id'=>$fileProcesses[0]['FileProcess']['project_id'])));

		if($project['Project']['daily_hours'] == 0)$daily_hours = 8;
		else $daily_hours = 12;

		foreach($fileProcesses as $fileProcess){

			// debug($fileProcess);		
			// get process start & process end
			// $startendprocess = $this->ProjectFile->FileProcess->find(
			// 	'first',array(
			// 		'fields'=>array(
			// 			'FileProcess.id',
			// 			'FileProcess.sr_no',
			// 			'FileProcess.project_id',
			// 			'FileProcess.milestone_id',
			// 			'FileProcess.project_file_id',
			// 			'FileProcess.project_process_plan_id',
			// 			'FileProcess.start_time',
			// 			'FileProcess.end_time',
			// 			'FileProcess.hold_start_time',
			// 			'FileProcess.hold_end_time',
			// 			'FileProcess.total_time',
			// 			'FileProcess.hold_time',
			// 			'FileProcess.actual_time',
			// 			'FileProcess.units_completed',
			// 			'FileProcess.pre_total_time',
			// 			'FileProcess.pre_total_time_days',
			// 			'FileProcess.pre_hold_time',
			// 			'FileProcess.final_actual_time',
			// 			'FileProcess.employee_id',
			// 		),
			// 		'conditions'=>array(
			// 		'FileProcess.sr_no != ' => $fileProcess['FileProcess']['so_no'],
			// 		'FileProcess.project_file_id' => $fileProcess['FileProcess']['project_file_id'],
			// 		'FileProcess.project_process_plan_id' => $fileProcess['FileProcess']['project_process_plan_id'],
			// 		// 'FileProcess.start_time !=' => NULL
			// 	),
			// 	'recursive'=>-1,
			// 	'order'=>array('FileProcess.sr_no'=>'ASC')
			// )
			// );

			// $endprocess = $this->ProjectFile->FileProcess->find(				
			// 	'first',array(
			// 		'fields'=>array(
			// 			'FileProcess.id',
			// 			'FileProcess.sr_no',
			// 			'FileProcess.project_id',
			// 			'FileProcess.milestone_id',
			// 			'FileProcess.project_file_id',
			// 			'FileProcess.project_process_plan_id',
			// 			'FileProcess.start_time',
			// 			'FileProcess.end_time',
			// 			'FileProcess.hold_start_time',
			// 			'FileProcess.hold_end_time',
			// 			'FileProcess.total_time',
			// 			'FileProcess.hold_time',
			// 			'FileProcess.actual_time',
			// 			'FileProcess.units_completed',
			// 			'FileProcess.pre_total_time',
			// 			'FileProcess.pre_total_time_days',
			// 			'FileProcess.pre_hold_time',
			// 			'FileProcess.final_actual_time',
			// 			'FileProcess.employee_id',
			// 		),
			// 		'conditions'=>array(
			// 		'FileProcess.employee_id' => $startendprocess['FileProcess']['employee_id'],
			// 		'FileProcess.sr_no != ' => $fileProcess['FileProcess']['so_no'],
			// 		'FileProcess.project_file_id' => $fileProcess['FileProcess']['project_file_id'],
			// 		'FileProcess.project_process_plan_id' => $fileProcess['FileProcess']['project_process_plan_id'],
			// 		// 'FileProcess.start_time !=' => NULL
			// 	),
			// 	'recursive'=>-1,
			// 	'order'=>array('FileProcess.sr_no'=>'DESC')
			// )
			// );

			// // debug($startendprocess);
			// // debug($endprocess);
			
			$startenddiff = $holddiff = $total = $end_time = $hold_end_time = $nextRec = null;
			
			$nextRec = $this->ProjectFile->FileProcess->find('count',array(
					// 'fields'=>array(
					// 	'FileProcess.id',
					// 	'FileProcess.sr_no',
					// 	'FileProcess.employee_id',
					// 	'FileProcess.project_file_id',
					// 	'FileProcess.project_process_plan_id'
					// ),
					'recursive'=>-1,
					'conditions'=>array(
						'DATE(FileProcess.start_time)'=>date('Y-m-d',strtotime($fileProcess['FileProcess']['start_time'])),
						'FileProcess.project_file_id'=>$fileProcess['FileProcess']['project_file_id'],
						'FileProcess.sr_no >'=>$fileProcess['FileProcess']['sr_no'],
						'FileProcess.employee_id'=>$fileProcess['FileProcess']['employee_id'],
						'FileProcess.project_process_plan_id'=>$fileProcess['FileProcess']['project_process_plan_id'],
					),
					'order'=>array('FileProcess.sr_no ASC')
			));

			// $nextBetween = $this->ProjectFile->FileProcess->find('all',array(
			// 		'fields'=>array(
			// 			'FileProcess.id',
			// 			'FileProcess.sr_no',
			// 			'FileProcess.employee_id',
			// 			'FileProcess.project_file_id',
			// 			'FileProcess.project_process_plan_id'
			// 		),
			// 		'recursive'=>-1,
			// 		'conditions'=>array(
			// 			'FileProcess.project_file_id'=>$fileProcess['FileProcess']['project_file_id'],
			// 			'FileProcess.sr_no >'=>$fileProcess['FileProcess']['sr_no'],
			// 			'FileProcess.employee_id != '=>$fileProcess['FileProcess']['employee_id'],
			// 			// 'FileProcess.project_process_plan_id'=>$fileProcess['FileProcess']['project_process_plan_id'],
			// 		),
			// 		'order'=>array('FileProcess.sr_no ASC')
			// ));

			// debug($nextRec);

			// if($fileProcess['FileProcess']['start_time'] && $fileProcess['FileProcess']['end_time']){
			// 	$end_time = $fileProcess['FileProcess']['end_time'];
			// }

			// // if($fileProcess['FileProcess']['start_time'] && $fileProcess['FileProcess']['end_time'] == null){
			// // 	if($nextRec && $nextRec['FileProcess']['start_time']){
			// // 		$end_time = $nextRec['FileProcess']['start_time'];
			// // 	}elseif ($nextRec && $nextRec['FileProcess']['hold_start_time']){
			// // 		$end_time = $nextRec['FileProcess']['hold_start_time'];
			// // 	}else{
			// // 		$end_time = date('Y-m-d H:i:s');
			// // 	}
				
			// // }

			// // $startenddiff = $this->_hrdiff($end_time,$fileProcess['FileProcess']['start_time'],$fileProcess['FileProcess']['project_id']);
			// if($endprocess['FileProcess']['end_time'])$end_time = $endprocess['FileProcess']['end_time'];
			// else $end_time = NULL;
			// debug($fileProcess);

			if($fileProcess['FileProcess']['start_time'] && $fileProcess['FileProcess']['end_time'] && $nextRec == 0){
				$end_time = $fileProcess['FileProcess']['end_time'];
				$startenddiff = $this->_hrdiff($end_time,$fileProcess['FileProcess']['start_time'],$fileProcess['FileProcess']['project_id'],$daily_hours);
			}

			// Configure::Write('debug',1);

			debug($fileProcess['FileProcess']['start_time']);
			debug($fileProcess['FileProcess']['end_time']);
			debug($startenddiff);

			if($fileProcess['FileProcess']['hold_start_time'] && $fileProcess['FileProcess']['hold_end_time']){
				$hold_end_time = $fileProcess['FileProcess']['hold_end_time'];
			}elseif ($fileProcess['FileProcess']['hold_start_time'] && $fileProcess['FileProcess']['hold_end_time'] == null) {
				if ($nextRec && $nextRec['FileProcess']['hold_start_time'] && $nextRec['FileProcess']['hold_end_time']){
					$hold_end_time = $nextRec['FileProcess']['hold_start_time'];
					// $hold_end_time = null;
				}else{
					$hold_end_time = null;
				}
			}			
			if($hold_end_time){
				$holddiff = $this->_hrdiff($hold_end_time,$fileProcess['FileProcess']['hold_start_time'],$fileProcess['FileProcess']['project_id'],$daily_hours);	
			}else{
				$holddiff = null;
			}
			// echo ">". $holddiff ."<br />";
			debug($hold_end_time);
			debug($fileProcess['FileProcess']['hold_start_time']);
			debug($holddiff);
			
			if($holddiff){
				if($hold_end_time && $fileProcess['FileProcess']['end_time']){
					$total = $this->_sectohr($startenddiff + $holddiff);	
				}else if($startenddiff && $fileProcess['FileProcess']['end_time']){
					$total = $this->_sectohr($startenddiff);	
				}else{
					$total = $holddiff;
				}	
			}else{
				$total = $startenddiff;	
			}
			
			// debug($fileProcess);
			
			debug($holddiff);
			debug($startenddiff);
			debug($fileProcess['FileProcess']['sr_no']);

			if(($holddiff && $holddiff > 0 ) || ($startenddiff && $startenddiff > 0)){
				debug($startenddiff);
				
				debug($fileProcess);

				if($startenddiff && $startenddiff > 0){
					echo "aaa";
					$fileProcess['FileProcess']['actual_time'] = $this->_sectohr($startenddiff);
				}else{
					echo "bb";
					$fileProcess['FileProcess']['actual_time'] = '00:00';
				}
				

				if($holddiff > 0)$fileProcess['FileProcess']['hold_time'] = $this->_sectohr($holddiff);
				else $fileProcess['FileProcess']['hold_time'] = '00:00';
				
				
				try{
					$this->ProjectFile->FileProcess->create();
					$this->ProjectFile->FileProcess->save($fileProcess,false);	
				}catch (Exception $e){
					debug($e);
				}

				// $this->ProjectFile->FileProcess->create();
				// $endprocess['FileProcess']['actual_time'] = $total;
				// // $endprocess['FileProcess']['hold_time'] = $this->_sectohr($holddiff);
				// try{
				// 	$this->ProjectFile->FileProcess->save($endprocess,false);	
				// }catch (Exception $e){

				// }	
			}else{

				$this->ProjectFile->FileProcess->create();
				$fileProcess['FileProcess']['actual_time'] = '00:00';
				$fileProcess['FileProcess']['hold_time'] = '00:00';
				debug($fileProcess);
				try{
					$this->ProjectFile->FileProcess->save($fileProcess,false);	
				}catch (Exception $e){

				}

			}
			
			

		}
		// exit;
	}

	// public function _get_file_duration_old($id = null){
	// 	Configure::Write('debug',1);
	// 	$this->ProjectFile->FileProcess->virtualFields = array(
	// 		'pre_total_time'=>'select TIMEDIFF(end_time,start_time) WHERE start_time IS NOT NULL AND end_time IS NOT NULL',
	// 		'pre_total_time_days'=>'select DATEDIFF(end_time,start_time) WHERE start_time IS NOT NULL AND end_time IS NOT NULL',
	// 		'pre_hold_time'=>'select TIMEDIFF(hold_end_time,hold_start_time) WHERE hold_start_time IS NOT NULL AND hold_end_time IS NOT NULL',
	// 		'final_actual_time' => 'select TIMEDIFF(hold_time,total_time) WHERE hold_time IS NOT NULL'			
	// 	);

		
	// 	if($id)$condition = array('FileProcess.project_file_id'=>$id);
	// 	else $condition = array();
		
	// 	$fileProcesses = $this->ProjectFile->FileProcess->find('all',array('recursive'=>-1,
	// 		'conditions'=>$condition,
	// 		// 'conditions'=>array('FileProcess.sr_no'=>4518),			
	// 	));

	// 	foreach($fileProcesses as $fileProcess){

	// 		$final = $hold_time = $holdhourdiff = $hourdiff = $end_time = $daydiff = $nextRec = $minindays = $minusdailyhours = $holidays = NULL;
			
	// 		//if start & end time find diff
	// 		//if end time == null then see if there is next record
	// 		$nextRec = $this->ProjectFile->FileProcess->find('first',array(
	// 				'conditions'=>array(
	// 					'FileProcess.project_file_id'=>$fileProcess['FileProcess']['project_file_id'],
	// 					'FileProcess.sr_no > '=>$fileProcess['FileProcess']['sr_no']
	// 				),
	// 				'order'=>array('FileProcess.sr_no ASC')
	// 		));

	// 		if($fileProcess['FileProcess']['end_time'] != null && $fileProcess['FileProcess']['start_time'] != null){
	// 			$end_time = $fileProcess['FileProcess']['end_time'];
	// 		}else if($nextRec && $fileProcess['FileProcess']['end_time'] == null){
	// 			$end_time = $nextRec['FileProcess']['start_time'];				
	// 		}else{
	// 			$end_time = date('Y-m-d H:i:s');
	// 		}
			

	// 		if($end_time && $fileProcess['FileProcess']['start_time']){
	// 			$hourdiff = $this->_hrdiff($end_time,$fileProcess['FileProcess']['start_time'],$daily_hours);
	// 		}
			
	// 		if($fileProcess['FileProcess']['hold_start_time'] && $fileProcess['FileProcess']['hold_end_time']){
	// 			$holdhourdiff = $this->_hrdiff($fileProcess['FileProcess']['hold_end_time'],$fileProcess['FileProcess']['hold_start_time'],$daily_hours);
	// 		}

			
	// 		$daydiff = date_diff(
	// 			date_create(date('Y-m-d',strtotime($end_time))), 
	// 			date_create(date('Y-m-d',strtotime($fileProcess['FileProcess']['start_time'])))
	// 		);
			
	// 		$minindays = $daydiff->d;			
	// 		if($minindays > 0)$minusdailyhours  = $minindays * (24 - 8);
			
	// 		$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
	// 			$fileProcess['FileProcess']['project_id'],
	// 			base64_encode($fileProcess['FileProcess']['assigned_date']),
	// 			base64_encode($end_time))
	// 		);

	// 		if($holidays > 0){
	// 			if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['start_time'])),$holidays))unset($holidays[0]);
	// 			if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['end_time'])),$holidays))unset($holidays[0]);
	// 			if(in_array(date('Y-m-d',strtotime($end_time)),$holidays))unset($holidays[0]);
	// 		}

	// 		$hdaydiff = date_diff(
	// 			date_create(date('Y-m-d',strtotime($fileProcess['FileProcess']['hold_end_time']))), 
	// 			date_create(date('Y-m-d',strtotime($fileProcess['FileProcess']['hold_start_time'])))
	// 		);
			
	// 		$hminindays = $hdaydiff->d;			
	// 		if($hminindays > 0)$holdhourdiff  = ($hminindays * (24 - 8)) * 3600;
				
	// 		$hourdiff = $hourdiff - ($minusdailyhours * 3600);
	// 		$final = $hourdiff + $holdhourdiff;
			
	// 		$hold_time = $this->_sectohr($holdhourdiff);			
	// 		$final = $this->_sectohr($final);
		
	// 		if($fileProcess['FileProcess']['assigned_date']){
	// 			$this->ProjectFile->FileProcess->create();
	// 			$fileProcess['FileProcess']['actual_time'] = $final;
	// 			$fileProcess['FileProcess']['hold_time'] = $hold_time;
	// 			$this->ProjectFile->FileProcess->save($fileProcess,false);
	// 		}
	// 	}
	// }

	// public function _get_file_duration($id = null){

	// 	Configure::Write('debug',1);
	// 	$this->ProjectFile->FileProcess->virtualFields = array(
	// 		'pre_total_time'=>'select TIMEDIFF(end_time,start_time) WHERE start_time IS NOT NULL AND end_time IS NOT NULL',
	// 		'pre_total_time_days'=>'select DATEDIFF(end_time,start_time) WHERE start_time IS NOT NULL AND end_time IS NOT NULL',
	// 		'pre_hold_time'=>'select TIMEDIFF(hold_end_time,hold_start_time) WHERE hold_start_time IS NOT NULL AND hold_end_time IS NOT NULL',
	// 		'final_actual_time' => 'select TIMEDIFF(hold_time,total_time) WHERE hold_time IS NOT NULL'			
	// 	);

		
	// 	if($id)$condition = array('FileProcess.project_file_id'=>$id);
	// 	else $condition = array();
		
	// 	$fileProcesses = $this->ProjectFile->FileProcess->find('all',array('recursive'=>-1,
	// 		'conditions'=>$condition,
	// 		// 'conditions'=>array('FileProcess.sr_no'=>4518),			
	// 	));

	// 	// debug($fileProcesses);
		
		
	// 	foreach($fileProcesses as $fileProcess){

	// 		// get time worked by either start time or if start time is missing, by assigned time
	// 		if($fileProcess['FileProcess']['start_time'] != NULL && $fileProcess['FileProcess']['end_time'] != NULL){

	// 			echo 1;
	// 			$startTime = $fileProcess['FileProcess']['start_time'];

	// 			// minus holidays
	// 			$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
	// 				$fileProcess['FileProcess']['project_id'],
	// 				base64_encode($fileProcess['FileProcess']['start_time']),
	// 				base64_encode($fileProcess['FileProcess']['end_time']))
	// 			);

	// 			if($holidays > 0){
	// 				if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['start_time'])),$holidays))unset($holidays[0]);
	// 				if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['end_time'])),$holidays))unset($holidays[0]);
	// 			}

	// 		}elseif($fileProcess['FileProcess']['assigned_date'] != NULL && $fileProcess['FileProcess']['end_time'] != NULL){
	// 			echo 2;
	// 			$startTime = $fileProcess['FileProcess']['assigned_date'];
	// 			$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
	// 				$fileProcess['FileProcess']['project_id'],
	// 				base64_encode($fileProcess['FileProcess']['assigned_date']),
	// 				base64_encode($fileProcess['FileProcess']['end_time']))
	// 			);

	// 			if($holidays > 0){
	// 				if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['start_time'])),$holidays))unset($holidays[0]);
	// 				if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['end_time'])),$holidays))unset($holidays[0]);
	// 			}
	// 		}elseif($fileProcess['FileProcess']['start_time'] != NULL && $fileProcess['FileProcess']['end_time'] == NULL && $fileProcess['FileProcess']['hold_end_time'] != NULL){
	// 			echo 3;
	// 			$startTime = $fileProcess['FileProcess']['start_time'];
	// 			$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
	// 				$fileProcess['FileProcess']['project_id'],
	// 				base64_encode($fileProcess['FileProcess']['start_time']),
	// 				base64_encode($fileProcess['FileProcess']['hold_end_time']))
	// 			);

	// 			if($holidays > 0){
	// 				if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['start_time'])),$holidays))unset($holidays[0]);
	// 				if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['hold_end_time'])),$holidays))unset($holidays[0]);
	// 			}

	// 			$fileProcess['FileProcess']['end_time'] = $fileProcess['FileProcess']['hold_end_time'];
	// 		}else{
	// 			echo 4;
	// 		}

	// 		if($fileProcess['FileProcess']['end_time'] != '' || $fileProcess['FileProcess']['end_time'] != null){
	// 			echo 5;
	// 			$endTime = $fileProcess['FileProcess']['end_time'];			
	// 		}

	// 		$holidays = 0;
			
	// 		// calculate hold time
	// 		if(($fileProcess['FileProcess']['hold_start_time'] != '' || $fileProcess['FileProcess']['hold_start_time'] != null)  && ($fileProcess['FileProcess']['hold_end_time'] != ''|| $fileProcess['FileProcess']['hold_end_time'] != null)){
				
	// 			echo 6;

	// 			$holdEndTime = date('Y-m-d H:i:s',strtotime($fileProcess['FileProcess']['hold_end_time']));
	// 			$holdStartTime = date('Y-m-d H:i:s',strtotime($fileProcess['FileProcess']['hold_start_time']));

	// 			$holdhourdiff = $this->_hrdiff($holdEndTime,$holdStartTime);

	// 			$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
	// 				$fileProcess['FileProcess']['project_id'],
	// 				base64_encode($fileProcess['FileProcess']['assigned_date']),
	// 				base64_encode($fileProcess['FileProcess']['end_time']))
	// 			);

	// 			if($holidays > 0){
	// 				if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['hold_start_time'])),$holidays))unset($holidays[0]);
	// 				if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['hold_end_time'])),$holidays))unset($holidays[0]);
	// 			}

	// 		}elseif(($fileProcess['FileProcess']['hold_start_time'] != '' || $fileProcess['FileProcess']['hold_start_time'] != null) && ($fileProcess['FileProcess']['hold_end_time'] == '' || $fileProcess['FileProcess']['hold_end_time'] == nul)){

	// 			echo 7;
	// 			// get next process assigned time
	// 			$nextProcces = $this->ProjectFile->FileProcess->find('first',array(
	// 				'recursive'=>-1,
	// 				'conditions'=>array(
	// 					'FileProcess.project_file_id'=>$fileProcess['FileProcess']['project_file_id'],
	// 					'FileProcess.milestone_id'=>$fileProcess['FileProcess']['milestone_id'],
	// 					'FileProcess.sr_no > '=>$fileProcess['FileProcess']['sr_no'],
	// 				)
	// 			));

				
	// 			if($nextProcces){
	// 				$holdStartTime = date('Y-m-d H:i:s',strtotime($fileProcess['FileProcess']['hold_start_time']));
	// 				$holdEndTime = date('Y-m-d H:i:s',strtotime($nextProcces['FileProcess']['assigned_date']));
					
	// 				$holdhourdiff = $this->_hrdiff($holdEndTime,$holdStartTime);

	// 				if($holidays > 0){
	// 					if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['hold_start_time'])),$holidays))unset($holidays[0]);
	// 					if(in_array(date('Y-m-d',strtotime($nextProcces['FileProcess']['assigned_date'])),$holidays))unset($holidays[0]);
	// 				}
					
	// 			}else{
	// 				$holdStartTime = date('Y-m-d H:i:s',strtotime($fileProcess['FileProcess']['hold_start_time']));
	// 				$holdEndTime = date('Y-m-d H:i:s');
					
	// 				$holdhourdiff = $this->_hrdiff($holdEndTime,$holdStartTime);

	// 				if($holidays > 0){
	// 					if(in_array(date('Y-m-d',strtotime($fileProcess['FileProcess']['hold_start_time'])),$holidays))unset($holidays[0]);
	// 					if(in_array(date('Y-m-d',strtotime($nextProcces['FileProcess']['assigned_date'])),$holidays))unset($holidays[0]);
	// 				}
	// 			}
	// 		}
			
	// 		$hourdiff = $this->_hrdiff($endTime,$startTime);
			
	// 		$final = $this->_sectohr($hourdiff);
			
	// 		// if($fileProcess['FileProcess']['end_time']){
	// 			$this->ProjectFile->FileProcess->create();
	// 			$fileProcess['FileProcess']['actual_time'] = $final;
	// 			$this->ProjectFile->FileProcess->save($fileProcess,false);
	// 		// }
	// 	}		
	// 	exit;
	// 	return true;
	// }

	public function assign_to_user(){
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data['ProjectFile']['id']){
				$file = $this->ProjectFile->find('first',array(
					'recursive'=>-1,
					'conditions'=>array('ProjectFile.id'=>$this->request->data['ProjectFile']['id'])));
				if($file){
					$file['ProjectFile']['employee_id'] = $this->request->data['ProjectFile']['employee_id'];
					$file['ProjectFile']['project_process_plan_id'] = $this->request->data['ProjectFile']['project_process_plan_id'];
					$file['ProjectFile']['comments'] = $this->request->data['ProjectFile']['change_user_comments'];
					$file['ProjectFile']['current_status'] = 0;
					$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
					
					$this->ProjectFile->create();
					if($this->ProjectFile->save($file,false)){
						echo 'Changes saved.';
					}else{						
						echo 'Unable to save changes. Try again.';
						
					}
					exit;

				}else{
					echo 'Unable to save changes. Try again.';
					exit;
				}
			}
		}else{

			$projectFile = $this->ProjectFile->find('first',array('recursive'=>-1, 'conditions'=>array('ProjectFile.id'=>$this->request->params['pass'][0])));

			$projectProcessPlans = $this->ProjectFile->ProjectProcessPlan->find('list',array(
				'limit'=>1,
				'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'conditions'=>array(
					'ProjectProcessPlan.project_id'=>$projectFile['ProjectFile']['project_id'],
					'ProjectProcessPlan.milestone_id'=>$projectFile['ProjectFile']['milestone_id'],
			)
		));
		
		$project_id = $projectFile['ProjectFile']['project_id'];

		$this->ProjectFile->Project->ProjectEmployee->Employee->virtualFields = array(
			'check_free'=>'select count(*) from `project_files` where (`project_files`.`current_status` = 0 OR `project_files`.`current_status` = 10) AND `project_files`.`project_id` = "'.$project_id.'" AND `project_files`.`employee_id` LIKE Employee.id',
			
			'check_free_pro' => 'select count(*) from `file_processes` where `file_processes.employee_id` LIKE Employee.id AND `file_processes.start_time` IS NULL ORDER BY `file_processes.sr_no` DESC',
			
			'emp_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id AND `project_employees`.`project_id` LIKE "' .$project_id . '"'
		);
		
		$teamMembers = $this->ProjectFile->Project->ProjectEmployee->Employee->find(
			'list',array(
				'recursive'=>-1,
				'fields'=>array('Employee.id','Employee.name','Employee.check_free','Employee.emp_check'),
				'conditions'=>array(
					'OR'=>array(
						'check_free_pro'=>0,
						'Employee.check_free'=> 0, 						
					),					
					'Employee.emp_check >'=> 0
				)
		));

		$this->set(compact('projectProcessPlans','teamMembers','projectFile'));
		}
		// exit;
	}

	public function reset_et(){
		$files = $this->ProjectFile->find('all',array(
			'recursive'=>-1,
			// 'limit'=>100
		));
		Configure::Write('debug',1);
		$this->loadModel('ProjectOverallPlan');
		foreach($files as $file){
			$plan = $this->ProjectOverallPlan->find('first',array(
	        	'recursive'=>-1,
	        	'conditions'=>array('ProjectOverallPlan.project_id'=>$file['ProjectFile']['project_id'],'ProjectOverallPlan.milestone_id'=>$file['ProjectFile']['milestone_id'])));


			// $estimated_time = $file['ProjectFile']['unit'] / $plan['ProjectOverallPlan']['overall_metrics'] / $plan['ProjectOverallPlan']['days'] / $plan['ProjectOverallPlan']['estimated_resource'];

			$estimated_time = $file['ProjectFile']['unit'] / $plan['ProjectOverallPlan']['overall_metrics'];

			$estimated_time = round($estimated_time,2);

			$file['ProjectFile']['estimated_time'] = $estimated_time;
			$this->ProjectFile->create();
			$this->ProjectFile->save($file,false);
			debug($estimated_time);
			debug($file['ProjectFile']['estimated_time']);
					
		}

		
		exit;
	}	


}

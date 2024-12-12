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
		$options = array('conditions' => array('ProjectFile.' . $this->ProjectFile->primaryKey => $id));
		$projectFile = $this->ProjectFile->find('first', $options);
		
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
		
		$this->set('projectProcesses',$projectProcesses);
		$this->set('PublishedEmployeeList',$this->_get_employee_list());
		$this->set('projectFile', $this->ProjectFile->find('first', $options));

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

			Configure::write('debug',1);
			// debug($this->request->data);
			// exit;

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

	        // this should be in loop below
	        $this->loadModel('FileProcesses');

			$x = 0;
			foreach ($files as $file) {
				$f = split(',', $file);
				$d = array();

				// check if file details are correct

				$d['ProjectFile']['name'] = ltrim(rtrim($f[0]));
				$d['ProjectFile']['unit'] = ltrim(rtrim(str_replace('"', '', $f[1])));
				$d['ProjectFile']['city'] = ltrim(rtrim(str_replace('"', '', $f[2])));
				$d['ProjectFile']['block'] = ltrim(rtrim(str_replace('"', '', $f[3])));

				if($d['ProjectFile']['name'] != '' && $d['ProjectFile']['unit'] != ''){
			
					// $resources = $this->find_free_res($this->request->data['ProjectFile']['project_id']);

					// if($resources){
					// 	$start_date = $resources['Project']['start_date'];
					// 	$end_date = $resources['Project']['end_date'];
					// }else{
					// 	$getDates = $this->ProjectFile->Project->find('first',array(
					// 		'recursive'=>-1,
					// 		'fields'=>array('Project.id','Project.start_date','Project.end_date'),
					// 		'conditions'=>array('Project.id'=>$this->request->data['ProjectFile']['project_id'])
					// 	));
					// 	if($project){
					// 		$start_date = $resources['Project']['start_date'];
					// 		$end_date = $resources['Project']['end_date'];
					// 	}
					// }

					// $employee_id = $resources['Employee']['id'];

					// if($employee_id){
						
					// 	$estimated_time = $projectProcesses['ProjectProcessPlan']['estimated_units'] / $projectProcesses['ProjectProcessPlan']['overall_metrics'] / $projectProcesses['ProjectProcessPlan']['days'] / $projectProcesses['ProjectProcessPlan']['estimated_resource'];

		   //      		if($estimated_time   == '')$estimated_time = 0;


					// 	$d['ProjectFile']['estimated_time'] = $estimated_time;
					// 	$d['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
					// 	// $d['ProjectFile']['start_date'] = $start_date;
					// 	$d['ProjectFile']['file_category_id'] = $this->request->data['ProjectFile']['file_category_id'];
					// 	$d['ProjectFile']['priority'] = $this->request->data['ProjectFile']['file_category_priority'];
					// 	$d['ProjectFile']['employee_id'] = $employee_id;
					// 	$d['ProjectFile']['project_id'] = $this->request->data['ProjectFile']['project_id'];
					// 	$d['ProjectFile']['milestone_id'] = $this->request->data['ProjectFile']['milestone_id'];
					// 	// $d['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s',strtotime($this->request->data['ProjectFile']['assigned_date']));
					// 	$d['ProjectFile']['current_status'] = 0;

					// 	$x++;						
					// }else{
					// 	$employee_id = "Not Assigned";

						$estimated_time = $projectProcesses['ProjectProcessPlan']['estimated_units'] / $projectProcesses['ProjectProcessPlan']['overall_metrics'] / $projectProcesses['ProjectProcessPlan']['days'] / $projectProcesses['ProjectProcessPlan']['estimated_resource'];
						if($estimated_time   == '')$estimated_time = 0;

						$d['ProjectFile']['estimated_time'] = $estimated_time;
						$d['ProjectFile']['name'] = ltrim(rtrim($f[0]));
						// $d['ProjectFile']['unit'] = ltrim(rtrim($f[1]));
						$d['ProjectFile']['employee_id'] = 'Not assigned';
						$d['ProjectFile']['project_id'] = $this->request->data['ProjectFile']['project_id'];
						$d['ProjectFile']['milestone_id'] = $this->request->data['ProjectFile']['milestone_id'];
						$d['ProjectFile']['current_status'] = 4;
						$d['ProjectFile']['file_category_id'] = $this->request->data['ProjectFile']['file_category_id'];
						$d['ProjectFile']['priority'] = $this->request->data['ProjectFile']['file_category_priority'];
					// 	$x++;						
					// }
										
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
							// if($employee_id != 'Not Assigned'){
								// $this->loadModel('FileProcess');
								// $fp['project_id'] = $this->request->data['ProjectFile']['project_id'];
								// $fp['milestone_id'] = $this->request->data['ProjectFile']['milestone_id'];
								// $fp['employee_id'] = $employee_id;;
								// $fp['assigned_date'] = date('Y-m-d H:i:s');
								// $fp['estimated_time'] = $estimated_time;
								// $fp['current_status'] = 0;
								// $fp['project_process_plan_id'] = $projectProcesses['ProjectProcessPlan']['id'];
								// $fp['project_file_id'] = $this->ProjectFile->id;
								// $fp['publish'] = 1;
								// $fp['soft_delete'] = 0;	
								// $this->FileProcess->create();
								// $this->FileProcess->save($fp,false);


								// $this->_track_file(
								// 	$project_file_id = $this->ProjectFile->id, 
								// 	$project_id = $this->request->data['ProjectFile']['project_id'], 
								// 	$milestone_id = $this->request->data['ProjectFile']['milestone_id'], 
								// 	$from = '??', 
								// 	$to = $employee_id, 
								// 	$by = $this->Session->read('User.employee_id'), 
								// 	$current_status = 0, 
								// 	$change_type = 0, 
								// 	$function = 'add_ajax', 
								// 	$comments = 'File changed via add_ajax function'
								// );

							// }
						}
					}else{
						echo "This file is already added";
					}

				}
			}
			// exit;
			$this->Session->setFlash(__('The project files has been saved'));
			$this->redirect(array('controller'=>'projects', 'action' => 'project_files',$this->request->data['ProjectFile']['project_id'],$this->request->data['ProjectFile']['milestone_id'],$this->request->data['ProjectFile']['pop'],$this->request->data['ProjectFile']['por']));
			
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
		// echo ">>" . $employee_id;
		
		$this->ProjectResource->virtualFields = array(
			'emp_already_assigned_seq'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id  AND `project_files.employee_id` LIKE "'. $employee_id .'"',
			'emp_already_assigned'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id  AND `project_files.employee_id` NOT LIKE "'. $employee_id .'"',
			'emp_on_leave'=> 0,				
		);	
		
		

		$resource = $this->ProjectResource->find('first',array(
			'conditions'=>array(
				// 'ProjectResource.project_id'=>$project_id,
				'ProjectResource.emp_already_assigned_seq'=> 0,
				'ProjectResource.employee_id' => $employee_id
			),
			'fields'=>array(
				// 'ProjectResource.id',
				// 'ProjectResource.priority',
				'Employee.id',
				'Employee.name',
				'User.id',
				'User.name',
				'User.name',
				'ProjectResource.emp_already_assigned',
				'ProjectResource.emp_already_assigned_seq'
				// 'ProjectProcessPlan.id',
				// 'ProjectProcessPlan.process',
				// 'ProjectResource.emp_already_assigned',
				// 'ProjectResource.emp_on_leave',
				// 'Project.id',
				// 'Project.title',
				// 'Project.start_date',
				// 'Project.end_date',
			),
			// 'Order'=>array('ProjectResource.priority'=>'DESC')
		));

		// debug($resource);

		// exit;
		if(!$resource){
			echo "new user";
			$resource = $this->ProjectResource->find('first',array(
			'conditions'=>array(
				'ProjectResource.project_id'=>$project_id,
				'ProjectResource.emp_already_assigned'=> 0,
				// 'ProjectResource.employee_id !=' => $employee_id
			),
			'fields'=>array(
				// 'ProjectResource.id',
				// 'ProjectResource.priority',
				'Employee.id',
				'Employee.name',
				'User.id',
				'User.name',
				'User.name',
				'ProjectResource.emp_already_assigned',
				'ProjectResource.emp_already_assigned_seq'
				// 'ProjectProcessPlan.id',
				// 'ProjectProcessPlan.process',
				// 'ProjectResource.emp_already_assigned',
				// 'ProjectResource.emp_on_leave',
				// 'Project.id',
				// 'Project.title',
				// 'Project.start_date',
				// 'Project.end_date',
			),
			'Order'=>array('ProjectResource.priority'=>'ASC')
		));
		}

		// debug($resource);
		// exit;
		return $resource;		
	}

	public function sent_back_file($data = null){
		// Configure::write('debug',1);
		// process
		// debug($data);
		// exit;
		// 1. Mark existing file as sent-back with status 8
		




		// 2. Check availibility of earlier user and see if the user has been assigned any other file currently 

		$employee_id = $this->get_employee_sent_back(
			$data['ProjectFile']['project_id'], 
			$data['ProjectFile']['milestone_id'],
			$data['ProjectFile']['pre_employee_id'],
			$data['ProjectFile']['id'],
			$data['ProjectFile']['pre_project_process_plan_id']
		);

		// debug($employee_id);
		// exit;
		// 3. If yes, add the file in queue 
		// Option 1. Assign this file to ealier user (wait till he completed the earlier file)
		// Option 2. Assign this file to some other user

		// get existing process
		$existingProcess = $this->ProjectFile->FileProcess->find('first',array(
			'conditions'=>array('FileProcess.id'=>$data['ProjectFile']['file_process_id']),
			'recursive'=>1
		));


		// debug($employee_id);
		// debug($existingProcess);
		// debug($data['ProjectFile']['file_process_id']);
		// exit;

		// mark existing process as in queue 
		$this->ProjectFile->FileProcess->read(null,$data['ProjectFile']['file_process_id']);
		$this->ProjectFile->FileProcess->set('queued',1);
		$this->ProjectFile->FileProcess->set('sent_back',1);
		$this->ProjectFile->FileProcess->set('pre_project_process_plan_id',$data['ProjectFile']['pre_project_process_plan_id']);
		$this->ProjectFile->FileProcess->set('pre_employee_id',$data['ProjectFile']['pre_employee_id']);
		$this->ProjectFile->FileProcess->set('comments',$data['ProjectFile']['comments'] . ' (This file is sent back.)');
		$this->ProjectFile->FileProcess->save();
		echo "updated existing file";
		// exit;
		echo "assigning file to user";
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

		// debug($fp);
		// exit;
		
		$this->ProjectFile->FileProcess->create();
		$this->ProjectFile->FileProcess->save($fp,false);


		$this->ProjectFile->read(null,$data['ProjectFile']['id']);
		$this->ProjectFile->set('current_status',8);
		$this->ProjectFile->set('employee_id',$data['ProjectFile']['pre_employee_id']);
		$this->ProjectFile->save();

		// exit;
		// $this->update_project_file($data);
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
	


	public function start_stop($id = null,$status = null,$hold_type_id = null){
		// echo "sdsa";
		// Configure::write('debug',1);
		// debug($this->request->params);
		// debug($id);
		// debug($status);
		// debug($hold_type_id);
		// exit;

		// echo $id;
		if($hold_type_id == -1){
			echo "Not added anything";
			return false;
		}

		$this->autoRender = false;
		$this->loadModel('FileProcess');
		$rec = $this->FileProcess->find('first',array(
			'conditions'=>array('FileProcess.id'=>$id),
			'recursive'=>0
		));

		// debug($rec);
		// debug($status);
		// debug($hold_type_id);
		// exit;

		$newPro['FileProcess'] = $rec['FileProcess'];



		if($rec['FileProcess']['hold_start_time'] && $rec['FileProcess']['hold_end_time'] && $status != 0){ 
			// echo 1;
			// exit;
			// create a new record
			// (a new process record is created new hold status is added for the process which ahd hold start/end added already)

			unset($newPro['FileProcess']['id']);
			unset($newPro['FileProcess']['hold_end_time']);
			unset($newPro['FileProcess']['sr_no']);
			unset($newPro['FileProcess']['created']);
			unset($newPro['FileProcess']['modified']);
			unset($newPro['FileProcess']['comments']);
			unset($newPro['FileProcess']['units_completed']);


			
			$newPro['FileProcess']['current_status'] = 7;
			$newPro['FileProcess']['hold_type_id'] = $hold_type_id;
			$newPro['FileProcess']['hold_start_time'] = date('Y-m-d H:i:s');			
			$this->FileProcess->create();
			// debug($newPro);
			$this->FileProcess->save($newPro,false);
			$this->_updateholdfilestatus($newPro['FileProcess']['project_file_id'],7,$this->Session->read('User.employee_id'));
			return 'New Hold Time Added';
		}else{

			// echo 2;
			

			if($id){
				// $this->loadModel('FileProcess');
				// $this->FileProcess->read(null,$id);
				// debug($newPro);
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

				debug($newPro);
				$this->FileProcess->save($newPro,false);


			}
		}		
		return $res;
		
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

		// debug($files);

		foreach ($files as $file) {
			$file['ProjectFile']['queued'] = 1;
			// debug($file);
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

		return true;


	}

	public function get_next_process($project_id = null, $milestone_id = null,$project_file_id = null,$current_process = null,$us = 0){

		Configure::write('deubg',1);
		debug($current_process);
		// debug($us);
		// exit;
		// echo "asdasd";
		$this->loadModel('ProjectProcessPlan');	
		$currentProcess = $this->ProjectProcessPlan->find('first',array('conditions'=>array('ProjectProcessPlan.id'=>$current_process)));
		
		if($currentProcess){

			if($us == 1){
				echo "N1: ";
				$nextProccess = $this->ProjectProcessPlan->find('first',array('conditions'=>array(
					'ProjectProcessPlan.project_id'=>$project_id,
					'ProjectProcessPlan.milestone_id'=>$milestone_id,
					'ProjectProcessPlan.sequence'=> ($currentProcess['ProjectProcessPlan']['sequence']-1),

				)));	
			}else{
				echo "N2: ";
				debug($currentProcess['ProjectProcessPlan']['sequence']+1);
				$nextProccess = $this->ProjectProcessPlan->find('first',array('conditions'=>array(
					'ProjectProcessPlan.project_id'=>$project_id,
					'ProjectProcessPlan.milestone_id'=>$milestone_id,
					'ProjectProcessPlan.sequence'=> ($currentProcess['ProjectProcessPlan']['sequence']+1),

				)));
			}

			
		}

		debug($nextProccess['ProjectProcessPlan']['process']);
		// exit;
		// debug($nextProccess);
		// $this->ProjectProcessPlan->virtualFields = array(
  //           'op'=>'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id'
  //       );


			
		// $plan = $this->ProjectProcessPlan->find('list',
		// 		array(
		// 			'fields'=>array(
		// 			'ProjectProcessPlan.id',
		// 			'ProjectProcessPlan.process',
		// 			// 'ProjectProcessPlan.op',
		// 			// 'ProjectProcessPlan.project_overall_plan_id',
		// 		),
		// 		'conditions'=>array(
		// 			'ProjectProcessPlan.op >'=>0,
		// 			'ProjectProcessPlan.project_id'=>$project_id,
		// 			'ProjectProcessPlan.milestone_id'=>$milestone_id,
		// 		),
		// 		'order'=>array(
		// 			'ProjectProcessPlan.sequence'=>'ASC',	
		// 		)
		// 	)
		// );
		// // print_r("<pre>". json_encode($plan)) ;
		// // debug($plan);
		// // exit;

		// $getLastProcess = $this->ProjectFile->FileProcess->find(
		// 	'list',array(
		// 		'fields'=>array(
		// 			'FileProcess.id',
		// 			'FileProcess.project_process_plan_id',
		// 		),
		// 		'conditions'=>array(
		// 			'FileProcess.project_process_plan_id' => array_keys($plan),
		// 			'FileProcess.project_file_id'=>$project_file_id
		// 		),
		// 		'order'=>array(
		// 			'FileProcess.sr_no'=>'DESC'
		// 		),
		// 		'group'=>array(
		// 			'FileProcess.project_process_plan_id'
		// 		)
		// 	)
		// );
		
		// Configure::write('debug',1);

		// // debug($plan);
		// // debug($getLastProcess);
		// // debug($us);
		// // exit;
		// unset($plan[$current_process]);
		// // this is removing the current process from plan
		// foreach ($getLastProcess as $key => $value) {
		// 	if(array_key_exists($value, $plan)){
		// 		if($us == 0){
		// 			unset($plan[$value]);
		// 		}else{
					
		// 		}
		// 	}
		// }

		// debug($plan);
		// debug($getLastProcess);
		
		// if($getLastProcess){
		// 	if(array_key_exists($current_process, $plan)){				
		// 		unset($plan[$current_process]);
		// 	}
		// }
		
		// // asort($plan);
		// debug($plan);
		// debug(key($plan));

		// $nextProcess = key($plan);

		// debug($nextProccess['ProjectProcessPlan']['id']);
		// exit;

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
		

		debug($plan);

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

		debug($getFirstProcess);
		
		// foreach ($getLastProcess as $key => $value) {
		// 	if(array_key_exists($value, $plan)){				
		// 		unset($plan[$value]);
		// 	}
		// }
		
		// $nextProcess = key($plan);
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

			// debug($getLastProcess);
			// exit;

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
				// debug($value);
				if(array_key_exists($value, $plan)){				
					// unset($plan[$value]);
					$vals[] = $value;
					$newPlan[$value] = $plan[$value];
				}
			}
			
			// Configure::write('debug',1);
			// debug($allProcess);
			// debug(count($allProcess)-1)

			unset($newPlan[$vals[0]]);
			return array(key($newPlan),$newPlan,$allProcess[count($allProcess)-1]['Employee']['id']);
		
		}else{

			// Configure::write('debug',1);
			$currentProcess = $this->ProjectFile->FileProcess->find('first',array('conditions'=>array('FileProcess.id'=>$currentProcesses),'recursive'=>0));
			// debug($currentProcess);
			// exit;
			$s = $currentProcess['ProjectProcessPlan']['sequence'] - 1;
			// debug($s);
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
			// Configure::Write('debug',1);
			// debug($prevProccess['FileProcess']['id']);

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
				// debug($value);
				if(array_key_exists($value, $plan)){				
					// unset($plan[$value]);
					$vals[] = $value;
					$newPlan[$value] = $plan[$value];
				}
			}

			// debug($getLastProcess);

			if($prevProccess){
				return array($prevProccess['FileProcess']['project_process_plan_id'],$newPlan,$prevProccess['Employee']['id']);
			}
			// exit;
		}
	}

	public function get_employee(
		$project_id = null, 
		$milestone_id = null,
		$employee_id = null,
		$project_file_id = null,
		$project_process_plan_id = null,
		$process = null){
		// echo "here";
		Configure::write('debug',1);
		debug($project_process_plan_id);
		debug($process);
		// exit;
		$this->loadModel('ProjectResource');
		
		if($project_process_plan_id){
			// echo "check skill set";
			$file_details = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$project_file_id)));
			// debug($file_details);
		}

		if($employee_id){
			// echo "Yes";
			$this->ProjectResource->virtualFields = array(
				'emp_already_assigned_seq'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id  AND ProjectResource.process_id ="' . $project_process_plan_id .'"',
				'emp_already_assigned'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id AND ProjectResource.employee_id NOT LIKE "' . $employee_id .'"',
				'emp_on_leave'=> 0,
				'pro_publish'=>'select `publish` from `projects` where `projects`.`id` = ProjectResource.project_id',
				'pro_delete'=>'select `soft_delete` from `projects` where `projects`.`id` = ProjectResource.project_id',
			);	
			$empCon = array('ProjectResource.employee_id !=' => $employee_id);
		}else{
			// echo "No";
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

			// Configure::write('debug',1);
			// debug($queue);
			// debug($resource);
		// 	// exit;
			
		// debug($resource);
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
			
			// Configure::write('debug',1);
			// debug($this->request->data);
			// debug($this->Session->read('User'));
			// exit;

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
				if($this->request->data['ProjectFile']['current_status'] == 8){ // rejected file
					// echo "here";
					// exit;
					
					$this->add_errors($this->request->data,$this->request->data['ProjectFile']['id']);
					$this->sent_back_file($this->request->data);
				}else{

					// echo "after";
					// exit;		
					// echo "call for update_file_process <br />";
					unset($this->request->data['ProjectFile']['pre_project_process_plan_id']);
					unset($this->request->data['ProjectFile']['pre_employee_id']);
					// exit;
					$this->add_errors($this->request->data,$this->request->data['ProjectFile']['id']);
					$this->update_file_process($this->request->data);
				}
				// exit;
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

		// this fucntion edits existing process ( does not create new)

		// Configure::write('debug',1);
		// debug($data);
		// exit;
		
		// if files are not merged, original file id is sent
		// if files are merged, in edit function, exsiting files are updated with new file id as a parent id and parent id is sent to this function.

		// echo "Updating file process table <br />";
		// exit;
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
		// debug($fp);
		
		// exit;

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
		
		// debug($fp);
		// exit;
		// $this->add_errors($data,$this->FileProcess->id);
		$this->update_project_file($data);
		
		
		return true;		
	}

	public function update_project_file($data = null){		
		// Configure::write('debug',1);
		echo "Updating file table <br />";
		

		$merging = $this->check_merging($data['ProjectFile']['id']);
		debug($merging);
		// exit;
		if($merging == false){

			if($data['ProjectFile']['current_status'] == 5){
				// echo "asasdsad";
				// exit;
				$ufile = $this->ProjectFile->find('first',array(
					'recursive'=>-1,
					'conditions'=>array('ProjectFile.id'=>$data['ProjectFile']['id'])));
				
				$ufile['ProjectFile']['current_status'] = 5;
				// $ufile['ProjectFile']['employee_id'] = 'Closed';
				$ufile['FileProcess']['employee_id'] = $this->Session->read('User.employee_id');
				// $ufile['ProjectFile']['project_process_plan_id'] = $project_process_plan_id = $data['ProjectFile']['project_process_plan_id'];
				$ufile['ProjectFile']['project_process_plan_id'] = $data['ProjectFile']['project_process_plan_id'];

				$this->ProjectFile->create();
				$this->ProjectFile->save($ufile,false);
				// echo ">>>" . $project_process_plan_id;
				// $this->re_arrange_files(
				// 	$data['ProjectFile']['project_id'],
				// 	$data['ProjectFile']['milestone_id'],
				// 	$data['ProjectFile']['employee_id'],
				// 	$data['ProjectFile']['project_process_plan_id'],
				// 	$data['ProjectFile']['id']
				// );
			
			}else{

				// echo "here ---";
				// exit;

				// $project_id = null, $milestone_id = null,$employee_id = null,$project_file_id = null,$project_process_plan_id = null
				// Configure::write('debug',1);
				// debug($data);
				// $project_id = null, $milestone_id = null,$employee_id = null,$project_file_id = null,$project_process_plan_id = null,$process = null
				
				if($data['ProjectFile']['merge_close'] != 1){
						// echo "here ---111";
						// exit;
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

							// debug($project_process_plan_id);
							// exit;
							// $get_employee = $this->get_employee(
							// 	$data['ProjectFile']['project_id'],
							// 	$data['ProjectFile']['milestone_id'],
							// 	$data['ProjectFile']['employee_id'],
							// 	$data['ProjectFile']['id'],
							// 	$project_process_plan_id,
							// 	null);
								
							// // debug($get_employee);
							// if($get_employee[1] == 1){
							// 	// que
							// 	$employee = $get_employee[0];
							// }else{
							// 	$employee = $get_employee[0];
							// }

							// debug($employee);
							// exit;
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
								// $project_process_plan_id = $this->get_next_process($data['ProjectFile']['project_id'], $data['ProjectFile']['milestone_id'],$data['ProjectFile']['id'],$data['ProjectFile']['project_process_plan_id']);
							}
						}
						
						
						// Configure::write('debug',1);
						// debug($project_process_plan_id);
						// exit;
						$ufile = $this->ProjectFile->find('first',array(
							'recursive'=>-1,
							'conditions'=>array('ProjectFile.id'=>$data['ProjectFile']['id'])));
						
						$ufile['ProjectFile']['comments'] = 'May be this is adding extra';
						$ufile['ProjectFile']['current_status'] = $current_status;
						$ufile['ProjectFile']['employee_id'] = $employee_id;
						$ufile['ProjectFile']['project_process_plan_id'] = $project_process_plan_id;

						// debug($ufile);
						// exit;
						$this->ProjectFile->create();
						if($this->ProjectFile->save($ufile,false)){
							// echo "Table Updated <br />";
							// exit;

							// if file is not on hold

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
								// Configure::write('debug',1);
								// debug($fp);
								// exit;
								// $this->FileProcess->create();
								// $this->FileProcess->save($fp,false);


								// $this->_track_file(
								// 	$project_file_id = $data['ProjectFile']['id'], 
								// 	$project_id = $data['FileProcess']['project_id'], 
								// 	$milestone_id = $data['FileProcess']['milestone_id'], 
								// 	$from = '??', 
								// 	$to = $employee_id, 
								// 	$by = $this->Session->read('User.employee_id'), 
								// 	$current_status = $current_status, 
								// 	$change_type = 0, 
								// 	$function = 'update_project_file', 
								// 	$comments = 'File changed via update_project_file function'
								// );

								// $this->re_arrange_files($data['ProjectFile']['project_id'],$data['ProjectFile']['milestone_id'],$data['ProjectFile']['employee_id'],$project_process_plan_id,$data['ProjectFile']['id']);
							}elseif($project_process_plan_id == null){

								// $this->re_arrange_files($data['ProjectFile']['project_id'],$data['ProjectFile']['milestone_id'],$data['ProjectFile']['employee_id'],$project_process_plan_id,$data['ProjectFile']['id']);
							}

				}else{
					// echo "here";
					// exit;
				}
					

				}else{
					$ufile = $this->ProjectFile->find('first',array(
						'recursive'=>-1,
						'conditions'=>array('ProjectFile.id'=>$data['ProjectFile']['id'])));
					
					$ufile['ProjectFile']['current_status'] = 5;
					$ufile['ProjectFile']['employee_id'] = $employee_id;
					$ufile['ProjectFile']['project_process_plan_id'] = NULL;

					// $this->re_arrange_files($data['ProjectFile']['project_id'],$data['ProjectFile']['milestone_id'],$data['ProjectFile']['employee_id'],null,$data['ProjectFile']['id']);
					
					// echo "Some issue";
					// exit;
				}			
			}
		}else{
				// this is merging false
				// $this->re_arrange_files($data['ProjectFile']['project_id'],$data['ProjectFile']['milestone_id'],$data['ProjectFile']['employee_id'],$project_process_plan_id,$data['ProjectFile']['id']);
			}

		
	}


	public function check_merging($file_id = null){
		// Configure::write('debug',1);

		$file = $this->ProjectFile->find('first',array(
			'conditions'=>array('ProjectFile.id'=>$file_id),
			'recursive'=>-1,
		));

		// debug($file);

		// $this->ProjectFile->FileProcess->virtualFields = array(
		// 	'file_cat_id'=>'select  '
		// );
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
		debug($project_process_plan_id);

		// which process
		$whichProcess = $this->ProjectFile->ProjectProcessPlan->find('first',array(
			'recursive'=>0,
			'conditions'=>array('ProjectProcessPlan.id'=>$next_project_process_plan_id)
		));

		// debug($whichProcess);
		// debug($aa);
		// exit;
		if($aa && $whichProcess['ProjectProcessPlan']['qc'] == 2){
			// assign this file to same user
			
			$file['ProjectFile']['current_status'] = 12;
			$file['ProjectFile']['project_process_plan_id'] = $aa['FileProcess']['project_process_plan_id'];
			$file['ProjectFile']['employee_id'] = $aa['FileProcess']['employee_id'];
			$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
			debug($file);
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
		// Configure::write('debug',1);
		// debug($this->request->data);
		// debug($project_id);
		// debug($milestone_id);
		// debug($employee_id);
		// debug($project_process_plan_id);
		// debug($file_id);
		// echo "Reassigning new file to user <br />";
		// exit;

// need following
// check if next process is before or after merging
// do not assign merging files to any other user 
// check if the file is part of any category which has file already sent for merging 







		$this->ProjectFile->virtualFields = array(
			'cat_on_hold'=>'select `file_categories`.`status` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
			'cat_pub'=>'select `file_categories`.`publish` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
			'cat_sd'=>'select `file_categories`.`soft_delete` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
			'mile_pub' => 'select publish from milestones where milestones.id = ProjectFile.milestone_id',
            'mile_sd' => 'select soft_delete from milestones where milestones.id = ProjectFile.milestone_id',
            'pro_pub' => 'select publish from projects where projects.id = ProjectFile.project_id',
            'pro_sd' => 'select soft_delete from projects where projects.id = ProjectFile.project_id',
		);

		$currentFile = $this->ProjectFile->find('first',array(
			'conditions'=>array('ProjectFile.id'=>$file_id)
		));

		// exit;
		if($file_id){
			// echo "e";
			$allFiles = $this->ProjectFile->find('all',array(
				'conditions'=>array(
					'ProjectFile.project_id'=>$project_id,
					'ProjectFile.milestone_id'=>$milestone_id,
					'ProjectFile.id !=' => $file_id,
					'ProjectFile.cat_on_hold'=>0,
					'ProjectFile.cat_pub' =>1,
                	'ProjectFile.cat_sd' =>0,
					'ProjectFile.mile_sd'=>0,
					'ProjectFile.mile_pub'=>1,
					'ProjectFile.pro_pub' =>1,
                	'ProjectFile.pro_sd' =>0,
					'ProjectFile.file_category_id !='=> NULL, // removed this so that no file with empty category will be assigned to anyone
					'OR'=>array(
						'ProjectFile.current_status' => array(1,4,10),
						'OR'=>array(
							'ProjectFile.employee_id' => null,
							'ProjectFile.employee_id' => 'Not Assigned'
						)
					)
					
				),
				'order'=>array('ProjectFile.modified'=>'DESC'),
				'recursive'=>-1
			));	
		}else{
			// echo "a";
			$allFiles = $this->ProjectFile->find('all',array(
				'conditions'=>array(
					'ProjectFile.project_id'=>$project_id,
					'ProjectFile.milestone_id'=>$milestone_id,
					'ProjectFile.cat_on_hold'=>0,
					'ProjectFile.cat_pub' =>1,
                	'ProjectFile.cat_sd' =>0,
					'ProjectFile.mile_sd'=>0,
					'ProjectFile.mile_pub'=>1,
					'ProjectFile.pro_pub' =>1,
                	'ProjectFile.pro_sd' =>0,
					// 'ProjectFile.current_status' => array(1,4,10)
					// 'ProjectFile.id !=' => $file_id
					'OR'=>array(
						'ProjectFile.current_status' => array(1,4,10),
						'ProjectFile.employee_id' => 'Not Assigned',
					)
				),
				'order'=>array('ProjectFile.modified'=>'DESC'),
				'recursive'=>-1
			));
		}
		// debug($currentFile);
		// debug($allFiles);
		// exit;
		// Configure::write('debug',1);
		
		// exit;
		
		// debug($project_id);
		// debug($milestone_id);
		// // // debug($employee_id);
		// // // debug($project_process_plan_id);
		// debug($allFiles);
		// exit;
		// $allFiles = $this->ProjectFile->find('all',array(
		// 	'fields'=>array(
		// 		'ProjectFile.id',
		// 		'ProjectFile.name',
		// 		'ProjectFile.current_status',
		// 	),
		// 	'conditions'=>array(
		// 		'ProjectFile.project_id'=>$project_id,
		// 		'ProjectFile.milestone_id'=>$milestone_id,
		// 		// 'ProjectFile.current_status !=' => array(0,3,4,5,7)
		// 		// 'ProjectFile.id !=' => $file_id
		// 	),
		// 	'order'=>array('ProjectFile.modified'=>'DESC'),
		// 	'recursive'=>-1
		// ));
		
		// debug($allFiles);
		$allEmps = $this->_get_employee_list();

		$this->ProjectFile->ProjectProcessPlan->virtualFields = array(			
            'op'=>	  'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id',
            'plan_check'=>'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id AND `project_overall_plans.milestone_id` LIKE "'.$milestone_id.'" '
        );


		$alpro = $this->ProjectFile->ProjectProcessPlan->find('list',array('conditions'=>array(
			'ProjectProcessPlan.project_id'=>$project_id,
			'ProjectProcessPlan.plan_check > '=> 0,
			'ProjectProcessPlan.op > '=>0,
		), 'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process')));
		debug($alpro);
		// exit;
		foreach ($allFiles as $file) {
			// Configure::write('debug',1);
			// debug($file);
			// if($file['ProjectFile']['current_status'] == 4){
				// first get next processs
				$project_process_plan_id = $this->get_next_process(
					$project_id,
					$milestone_id,
					$file['ProjectFile']['id'],
					$file['ProjectFile']['project_process_plan_id'],
					0
				);
				// debug($file['ProjectFile']['id']);
				
				// if(!$process){
				// 	$process = $this->get_first_process($project_id,$milestone_id,$file['ProjectFile']['id']);
				// }

				// debug($alpro[$project_process_plan_id]);
				// echo ">>>" . $alpro[$project_process_plan_id];

				// then select employee

				// $projectFile['ProjectFile']['project_id'],
    //     		$projectFile['ProjectFile']['milestone_id'],
    //     		$this->Session->read('User.employee_id'),
    //     		$projectFile['ProjectFile']['id'],
    //     		$nextProcess,
    //     		null

				// public function get_employee($project_id = null, $milestone_id = null,$employee_id = null,$project_file_id = null,$project_process_plan_id = null,$process = null){

				$get_employee = $this->get_employee(
					$project_id,
					$milestone_id,
					null,
					$file['ProjectFile']['id'],
					$project_process_plan_id,
					null
				);
				debug($get_employee);
				
				if($get_employee[1] == 1){
					// que
					$employee = $get_employee[0];
				}else{
					$employee = $get_employee[0];
				}

				// check if employee has already assigned any files
				$al_ass = $this->ProjectFile->find('count',array('conditions'=>array(
					'ProjectFile.employee_id'=> $employee['Employee']['id'],
					'ProjectFile.project_id'=>$project_id,
					'ProjectFile.milestone_id'=>$milestone_id,
					'ProjectFile.current_status'=>0
				)));

				if($al_ass > 0){

				}else{



					// Configure::write('debug',1);
					// debug($employee);
					// debug($employee);
					if($employee){
						// echo "Emp not found for " . $alpro[$project_process_plan_id];
					}
					// exit;

					if($employee && $project_process_plan_id){
						// assign file to user
						$file['ProjectFile']['employee_id'] = $employee['Employee']['id'];

						// added new (test)
						$file['ProjectFile']['project_process_plan_id'] = $project_process_plan_id;
						$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
						$file['ProjectFile']['current_status'] = 0;
						
						// debug($file);
						
						$this->ProjectFile->create();
						if($this->ProjectFile->save($file)){
							$fp = array();
							$this->loadModel('FileProcess');

							$projectProcesses = $this->ProjectFile->ProjectProcessPlan->find('first',array(
								'conditions'=>array(
									'ProjectProcessPlan.op >'=> 0,
									'ProjectProcessPlan.id'=>$project_process_plan_id
								),
								'recursive'=>-1
							));


							$estimated_time = $projectProcesses['ProjectProcessPlan']['estimated_units'] / $projectProcesses['ProjectProcessPlan']['overall_metrics'] / $projectProcesses['ProjectProcessPlan']['days'] / $projectProcesses['ProjectProcessPlan']['estimated_resource'];

							if($estimated_time   == '')$estimated_time = 0;

							if($get_employee[1] == 0){
								$fp['FileProcess']['queued'] = NULL ;
							}else{
								$fp['FileProcess']['queued'] = 1 ;
							}

							$fp['FileProcess']['current_status'] = 0;
							$fp['FileProcess']['units_completed'] = $data['ProjectFile']['units_completed'];
							$fp['FileProcess']['project_id'] = $project_id;
							$fp['FileProcess']['milestone_id'] = $milestone_id;
							$fp['FileProcess']['employee_id'] = $employee['Employee']['id'];
							$fp['FileProcess']['assigned_date'] = date('Y-m-d h:i:s');
							$fp['FileProcess']['estimated_time'] = $estimated_time;
							$fp['FileProcess']['project_process_plan_id'] = $project_process_plan_id;
							$fp['FileProcess']['project_file_id'] = $file['ProjectFile']['id'];
							$fp['FileProcess']['comments'] = 'Auto Assigned by system at : ' . date('Y-m-d H:i:s') . ' via re_arrange_files function';
							$fp['FileProcess']['prepared_by'] = $this->Session->read('User.employee_id');
							$fp['FileProcess']['publish'] = 1;
							$fp['FileProcess']['soft_delete'] = 0;
							debug($fp);
							$this->FileProcess->create();
							$this->FileProcess->save($fp,false);

							$this->_track_file(
								$project_file_id = $file['ProjectFile']['id'], 
								$project_id = $project_id, 
								$milestone_id = $milestone_id, 
								$from = '??', 
								$to = $employee['Employee']['id'], 
								$by = $this->Session->read('User.employee_id'), 
								$current_status = $current_status, 
								$change_type = 0, 
								$function = 're_arrange_files', 
								$comments = 'File changed via re_arrange_files function'
							);


							// rest all aother files for this user as queued
							$this->queue_other_files($employee['Employee']['id'],$file['ProjectFile']['id'],true,$project_process_plan_id);
						}

				// }else{
				// 	// echo "New user not found <br />";
				// }
				}else{
					// echo "nothing to do";
					// debug($this->request->data);
				}
			}
		}
		// exit;		
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

		// Configure::write('debug',1);
		// debug($file_id);
		// debug($employee_id);

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

		// debug($files);

		foreach ($files as $file) {
			$file['ProjectFile']['queued'] = 1;
			// debug($file);
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
		// echo $process_id;
		// exit;

		$this->autoRender = false;
		$fileProcess = $this->ProjectFile->FileProcess->find('first',array(
			'conditions'=>array(
				'FileProcess.project_file_id'=>$id
			),
			'order'=>array('FileProcess.sr_no'=>'DESC')
		));


		// Configure::write('debug',1);
		// debug($process_id);
		// debug($fileProcess);
		

		if(!$fileProcess){
			// echo "!";
			// exit;	
			// create new process
			$file = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$id)));

			$file['ProjectFile']['project_process_plan_id'] = $process_id;
			$file['ProjectFile']['employee_id'] = $employee_id;
			$file['ProjectFile']['current_status'] = 0;
			$file['ProjectFile']['queued'] = 0;
			$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');

			$this->ProjectFile->create();
			$this->ProjectFile->save($file,false);

			// reopen same file again
			$file = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$id)));

			// Configure::write('debug',1);
			// debug($file);

			$this->loadModel('FileProcess');
			$fp['FileProcess'] = $fileProcess['FileProcess'];
			$fp['FileProcess']['current_status'] = 10; // reassigned file (new status)
			$fp['FileProcess']['comments'] = 're-assigned via changeuser function on ' . date('Y-m-d H:i:s');
			$fp['FileProcess']['units_completed'] = 0;
			$fp['FileProcess']['project_id'] = $file['ProjectFile']['project_id'];
			$fp['FileProcess']['milestone_id'] = $file['ProjectFile']['milestone_id'];
			$fp['FileProcess']['employee_id'] = $employee_id;
			// $fp['FileProcess']['current_status'] = $data['ProjectFile']['current_status'];

			$estimated_time = $file['ProjectProcessPlan']['estimated_units'] / $file['ProjectProcessPlan']['overall_metrics'] / $file['ProjectProcessPlan']['days'] / $file['ProjectProcessPlan']['estimated_resource'];
			if($estimated_time   == '')$estimated_time = 0;
			$fp['FileProcess']['estimated_time'] = $estimated_time;
			$fp['FileProcess']['project_process_plan_id'] = $process_id;
			$fp['FileProcess']['project_file_id'] = $id;
			$fp['FileProcess']['comments'] = 'Re-assigned-0 file via changeuser function on ' . date('Y-m-d H:i:s');
			$fp['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');
			// $fp['FileProcess']['prepared_by'] = $data['ProjectFile']['employee_id'];
			$fp['FileProcess']['prepared_by'] = $this->Session->read('User.employee_id');
			$fp['FileProcess']['publish'] = 1;
			$fp['FileProcess']['queued'] = 0;
			$fp['FileProcess']['soft_delete'] = 0;
			
			$this->FileProcess->create();
			$this->FileProcess->save($fp,false);


			$this->_track_file(
				$project_file_id = $file['ProjectFile']['id'], 
				$project_id = $file['ProjectFile']['project_id'], 
				$milestone_id = $file['ProjectFile']['milestone_id'], 
				$from = '??', 
				$to = $employee_id, 
				$by = $this->Session->read('User.employee_id'), 
				$current_status = 10, 
				$change_type = 0, 
				$function = 'changeuser-1', 
				$comments = 'File changed via changeuser function 1'
			);


			$this->queue_other_files($employee_id,$id, true, $process_id);

			return true;
			// Configure::write('debug',1);
			// debug($fp);
			// exit;
		}else{
			// Configure::write('debug',1);
			// debug($fileProcess);
			// exit;
			$this->loadModel('FileProcess');
			$fp['FileProcess'] = $fileProcess['FileProcess'];
			// $fp['FileProcess']['current_status'] = 10; // reassigned file (new startus)
			// $fp['FileProcess']['units_completed'] = $data['ProjectFile']['units_completed'];
			// $fp['FileProcess']['project_id'] = $data['ProjectFile']['project_id'];
			// $fp['FileProcess']['milestone_id'] = $data['ProjectFile']['milestone_id'];
			// $fp['FileProcess']['employee_id'] = $data['ProjectFile']['employee_id'];
			// // $fp['FileProcess']['current_status'] = $data['ProjectFile']['current_status'];
			// $fp['FileProcess']['project_process_plan_id'] = $data['ProjectFile']['curr_stage'];
			// $fp['FileProcess']['project_file_id'] = $data['ProjectFile']['id'];
			// $fp['FileProcess']['comments'] = $data['ProjectFile']['comments'];
			// $fp['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');
			// // $fp['FileProcess']['prepared_by'] = $data['ProjectFile']['employee_id'];
			// $fp['FileProcess']['prepared_by'] = $this->Session->read('User.employee_id');
			// $fp['FileProcess']['publish'] = 1;
			// $fp['FileProcess']['soft_delete'] = 0;
			// Configure::write('debug',1);
			// debug($fileProcess['FileProcess']['id']);

			// debug($fp);
			// exit;

			$fp['FileProcess']['prepared_by'] = $this->Session->read('User.employee_id');
			$fp['FileProcess']['current_status'] = 10;
			$fp['FileProcess']['end_time'] = date('Y-m-d H:i:s');
			$fp['FileProcess']['comments'] = 're-assigned-1 ?? by ' . $this->Session->read('User.username') . ' on '. date('Y-m-d H:i:s');

			// debug($fp);

			// exit;

			$this->FileProcess->create();
			$this->FileProcess->save($fp,false);


			// also update file // added - 29-06-2021
			$file = $this->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$id)));

			$file['ProjectFile']['project_process_plan_id'] = $process_id;
			$file['ProjectFile']['employee_id'] = $employee_id;
			$file['ProjectFile']['current_status'] = 10;
			$file['ProjectFile']['queued'] = 0;
			$file['ProjectFile']['comments'] = 're-assigned-2 ?? by ' . $this->Session->read('User.username') . ' on '. date('Y-m-d H:i:s');
			$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');

			$this->ProjectFile->create();
			$this->ProjectFile->save($file,false);


			$this->_track_file(
				$project_file_id = $file['ProjectFile']['id'], 
				$project_id = $file['ProjectFile']['project_id'], 
				$milestone_id = $file['ProjectFile']['milestone_id'], 
				$from = '??', 
				$to = $employee_id, 
				$by = $this->Session->read('User.employee_id'), 
				$current_status = 10, 
				$change_type = 0, 
				$function = 'changeuser-2', 
				$comments = 'File changed via changeuser-2 function'
			);

			// change all re-assigned for current process to 10

			$all = $this->ProjectFile->FileProcess->find('all',array(
				'recursive'=>-1,
				'conditions'=>array(
					'FileProcess.project_file_id'=>$id,
					// 'FileProcess.project_process_plan_id'=>$fileProcess['FileProcess']['project_process_plan_id'],
					'FileProcess.comments'=>'re-assigned'
				)
			));	

			debug($all);

			foreach($all as $a){
				debug($a);
				$a['FileProcess']['current_status'] = 10;
				$a['FileProcess']['end_time'] = date('Y-m-d H:i:s');
				$this->ProjectFile->FileProcess->create();
				$this->ProjectFile->FileProcess->save($a,false);


				$this->_track_file(
					$project_file_id = $a['FileProcess']['project_file_id'], 
					$project_id = $a['FileProcess']['project_id'], 
					$milestone_id = $a['FileProcess']['milestone_id'], 
					$from = '??', 
					$to = $employee_id, 
					$by = $this->Session->read('User.employee_id'), 
					$current_status = 10, 
					$change_type = 0, 
					$function = 'changeuser-2', 
					$comments = 'File changed via changeuser function, resetting all file'
				);
			}

			// exit;
			// $this->FileProcess->read(null,$fileProcess['FileProcess']['id']);
			// $this->FileProcess->set(array('current_status'=>10,'comments'=>'re-assigned'));
			// $this->FileProcess->save();

			// update current record

			// $this->ProjectFile->read(null,$id);
			// $this->ProjectFile->set('employee_id',$employee_id);
			// $this->ProjectFile->set('assigned_date',date('Y-m-d H:i:s'));
			// $this->ProjectFile->save();

			$file = $this->ProjectFile->find('first',array('recursive'=>-1, 'conditions'=>array('ProjectFile.id'=>$id)));

			$estimated_time = $file['ProjectProcessPlan']['estimated_units'] / $file['ProjectProcessPlan']['overall_metrics'] / $file['ProjectProcessPlan']['days'] / $file['ProjectProcessPlan']['estimated_resource'];

			$file['ProjectFile']['employee_id'] = $employee_id;
			$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
			$file['FileProcess']['project_process_plan_id'] = $process_id;
			$file['ProjectFile']['comments'] = 're-assigned-4 (all) via changeuser function on ' . date('Y-m-d H:i:s');
			$file['ProjectFile']['current_status'] = 10;
			$file['ProjectFile']['queued'] = 0;
			$file['FileProcess']['estimated_time'] = $estimated_time;

			$this->ProjectFile->create();
			$this->ProjectFile->save($file,false);


			// create new file process record
			$data['FileProcess'] = $fp['FileProcess'];
			unset($data['FileProcess']['id']);
			unset($data['FileProcess']['sr_no']);
			unset($data['FileProcess']['start_time']);
			unset($data['FileProcess']['end_time']);
			unset($data['FileProcess']['hold_start_time']);
			unset($data['FileProcess']['hold_end_time']);
			unset($data['FileProcess']['hold_type_id']);
			unset($data['FileProcess']['units_completed']);
			$data['FileProcess']['current_status'] = 10;
			$data['FileProcess']['queued'] = 0;
			$data['FileProcess']['employee_id'] = $employee_id;
			$data['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');
			$data['FileProcess']['project_process_plan_id'] = $process_id;

			$this->ProjectFile->FileProcess->create();
			$this->ProjectFile->FileProcess->save($data,false);


			$this->_track_file(
				$project_file_id = $file['ProjectFile']['id'], 
				$project_id = $file['ProjectFile']['project_id'], 
				$milestone_id = $file['ProjectFile']['milestone_id'], 
				$from = '??', 
				$to = $employee_id, 
				$by = $this->Session->read('User.employee_id'), 
				$current_status = 10, 
				$change_type = 0, 
				$function = 'changeuser-3', 
				$comments = 'File changed via changeuser function'
			);
			// exit;
			// debug($data);

			// $this->queue_other_files($employee_id,$id, true, $fileProcess['FileProcess']['project_process_plan_id']);

			return true;	
		}

		// exit;

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
		// echo "hello";
		// exit;

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
		$this->redirect(array('controller'=>'projects', 'action' => 'project_files',$this->request->params['pass'][1],$this->request->params['pass'][2],$this->request->params['pass'][3],$this->request->params['pass'][3]));
	}

	public function rectifyemp(){
		Configure::write('debug',1);

		$this->ProjectFile->hasMany['FileProcess']['conditions'] = array('OR'=>array('FileProcess.employee_id'=>'Closed' , 'FileProcess.current_status'=>5) );

		$files = $this->ProjectFile->find('all',array('conditions'=>array(
			// 'ProjectFile.current_status'=>5,
			'ProjectFile.employee_id'=>'Closed',
		)));
		debug($files);
		

		foreach ($files as $file) {
			// get employee who closed the file
			$modified_by = $this->ProjectFile->ModifiedBy->find('first',array('recursive'=>-1,'conditions'=>array('ModifiedBy.id'=>$file['FileProcess'][0]['modified_by'])));
			$employee_id = $modified_by['ModifiedBy']['employee_id'];
			debug($employee_id);
			# code...

			// update file_process
			$prodata = $file['FileProcess'][0];
			debug($prodata);
			$prodata['employee_id'] = $employee_id;
			$this->ProjectFile->FileProcess->create();
			$this->ProjectFile->FileProcess->save($prodata,false);


			// update project_file
			$fdata = $file['ProjectFile'];
			debug($fdata);
			$fdata['employee_id'] = $employee_id;
			$this->ProjectFile->create();
			$this->ProjectFile->save($fdata,false);

		}

		exit;
	}


	public function corntorun($project_id = null){
		Configure::write('debug',1);
		$milestones = $this->ProjectFile->Project->Milestone->find('list',array(
			'conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0,'Milestone.project_id'=>$project_id)
		));

		$this->ProjectFile->FileProcess->virtualFields = array(
			'seq'=>'select sequence from project_process_plans where project_process_plans.id LIKE FileProcess.project_process_plan_id'
		);

		$this->ProjectFile->virtualFields = array(
			'seqf'=>'select sequence from project_process_plans where project_process_plans.id LIKE ProjectFile.project_process_plan_id'
		);

		foreach ($milestones as $key => $value) {

			

			$project_files = $this->ProjectFile->find('all',array(
				'conditions'=>array(
					'ProjectFile.project_id'=>$project_id,
					'ProjectFile.milestone_id'=>$key
				)
			));

				

			// debug($project_files);
			// exit;
			

			foreach ($project_files as $file) {
				// if($file['ProjectFile']['employee_id'] != $file['FileProcess'][0]['employee_id']){
				// 	$errorfiles[] = $file;
				// }
				// debug($file);
				foreach ($file['FileProcess'] as $process) {

					// $this->ProjectFile->FileProcess->virtualFields = array(
					// 	'seq'=>'select sequence from project_process_plans where project_process_plans.id LIKE FileProcess.project_process_plan_id'
					// );
					
						// debug($process);
					
						if($process['hold_start_time'] && ($process['hold_end_time'] == '' || $process['hold_end_time'] == null)){
							$file['ProjectFile']['current_status'] = 7;
							$file['ProjectFile']['employee_id'] = $process['employee_id'];
							$file['ProjectFile']['project_process_plan_id'] = $process['project_process_plan_id'];
							// update file
							// $this->ProjectFile->create();
							// $this->ProjectFile->save($file,false);

							// delete anything which was added later to this
							$extraprocesses = $this->ProjectFile->FileProcess->find('all',array(
								'recursive'=>-1,
								'conditions'=>array(
									'FileProcess.project_file_id'=>$file['ProjectFile']['id'],
									'FileProcess.seq >' => $file['ProjectFile']['seqf']
								)
							));
							debug($extraprocesses);
							// foreach ($process as $p) {
							// 	// debug($p);
							// 	// $this->ProjectFile->FileProcess->deleteAll(array('FileProcess.id'=>$p['id']));
							// }
							// debug(count($existingProcess));
						}
					
					
				}
			}

			// debug($errorfiles);
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

		Configure::write('debug',1);
		debug($projectResources);
		// debug($files);
		exit;
	}
}

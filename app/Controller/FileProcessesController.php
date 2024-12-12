<?php
App::uses('AppController', 'Controller');
/**
 * FileProcesses Controller
 *
 * @property FileProcess $FileProcess
 * @property PaginatorComponent $Paginator
 */
class FileProcessesController extends AppController {

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

    	public function index() {
		
		// $conditions = $this->_check_request();
		$this->paginate = array('order'=>array('FileProcess.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->FileProcess->recursive = 0;
		$this->set('fileProcesses', $this->paginate());
		
		$this->_get_count();
		$this->loadModel('ProjectProcessPlan');
	        
	        $projectProcesses = $this->ProjectProcessPlan->find(
	            'list',array(
	                // 'conditions'=>$projectFile['ProjectFile']['process_id'],
	                'fields'=>array(
	                    'ProjectProcessPlan.id',
	                    'ProjectProcessPlan.process'
	                ),
	                'order'=>array(
	                    'ProjectProcessPlan.sequence'=>'ASC'
	                )
	            )
	        );
		// debug($projectProcesses);
		// debug($projectFile);
		$this->set('projectProcesses',$projectProcesses);
		$this->set('currentStatuses',$this->FileProcess->ProjectFile->customArray['currentStatuses']) ;
	}



    public function check_skillset($employee_id = null, $project_process_plan_id = null, $project_id = null, $milestone_id = null){
    	$this->autoRender = false;
    	$this->loadModel('ProjectResource');

	$rec = $this->ProjectResource->find('count',array(
		'conditions'=>array(
			'ProjectResource.employee_id'=> $employee_id,
			'ProjectResource.project_id'=>$project_id,
			'ProjectResource.milestone_id'=>$milestone_id,
			'ProjectResource.process_id'=>$project_process_plan_id,
		)
	));

	if($rec == 0){
		return 0;
		// $this->Session->setFlash(__('This user does not have required skill sets.'));
		// $this->redirect(array('controller'=>'file_processes', 'action'=> 'changeuser' , $this->request->data['FileProcess']['id']));
	}else{
		return 1;
	}
    }


	public function changeuser(){
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data['FileProcess']['id']){
				$formdata = $this->request->data;
				
				$options = array('conditions' => array('FileProcess.id' => $this->request->data['FileProcess']['id']));
				$this->request->data = $this->FileProcess->find('first', $options);	
				
				$this->request->data['FileProcess']['current_status'] = 14;
				$this->request->data['FileProcess']['end_time'] = date('Y-m-d H:i:s');
				if($this->request->data['FileProcess']['hold_start_time'])$this->request->data['FileProcess']['hold_end_time'] = date('Y-m-d H:i:s');
				$this->request->data['FileProcess']['change_user_comments'] = $formdata['FileProcess']['change_user_comments'];
					
				// first check if this user has the skill set assigned in project resource table
				$this->loadModel('ProjectResource');

				$rec = $this->ProjectResource->find('count',array(
					'conditions'=>array(
						'ProjectResource.employee_id'=> $formdata['FileProcess']['employee_id'],
						'ProjectResource.project_id'=>$this->request->data['FileProcess']['project_id'],
						'ProjectResource.milestone_id'=>$this->request->data['FileProcess']['milestone_id'],
						'ProjectResource.process_id'=>$formdata['FileProcess']['project_process_plan_id'],
					)
				));

				if($rec == 0){
					echo 'This user does not have required skill sets.';
					exit;
				}else{
					
					// first find the current file and un-assign it
					$currentFile = $this->FileProcess->ProjectFile->find(
						'first',array(
							'conditions'=>array(
								'ProjectFile.employee_id'=>$formdata['FileProcess']['employee_id'],
								// 'OR'=>array(
								// 'ProjectFile.current_status'=>array(0,7),
								// )
							),
							'order'=>array('ProjectFile.sr_no'=>'ASC')
						)
					);

					if($currentFile){
						$currentFile['ProjectFile']['employee_id'] = NULL;
						$currentFile['ProjectFile']['current_status'] = 4;
						$currentFile['ProjectFile']['comments'] = "This......";
						$currentFile['ProjectFile']['change_user_comments'] = "File cancled by admin";
						
						
						$this->FileProcess->ProjectFile->create();
						$this->FileProcess->ProjectFile->save($currentFile,false);

						$currentProcess['FileProcess'] = $currentFile['FileProcess'][0];
						if($currentProcess){
							$this->FileProcess->read(null,$currentProcess['FileProcess']['id']);
							$this->FileProcess->set(
								array(
									'employee_id'=>'Not Assigned',
									'current_status'=>4,
									'change_user_comments'=>'File cancled by admin',
									'end_time'=>date('Y-m-d H:i:s'),
								)
							);

							$this->FileProcess->save();							
						}
					}

					
					$this->FileProcess->create();				
					if($this->FileProcess->save($this->request->data,false)){
						// now create new process
						$data = $this->request->data;
						unset($data['FileProcess']['id']);
						unset($data['FileProcess']['sr_no']);
						$data['FileProcess']['change_user_comments'] = 'Change user by : ' . $this->Session->read('User.employee_name');
						$data['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');
						$data['FileProcess']['current_status'] = 0;
						$data['FileProcess']['employee_id'] = $formdata['FileProcess']['employee_id'];
						$data['FileProcess']['project_process_plan_id'] = $formdata['FileProcess']['project_process_plan_id'];
						$data['FileProcess']['assigned_date'] = date('Y-m-d H:i:s');
						$data['FileProcess']['created'] = date('Y-m-d H:i:s');
						$data['FileProcess']['created_by'] = $this->Session->read('User.id');

						$data['FileProcess']['send_back'] = $data['FileProcess']['queued'] = 0;
						$data['FileProcess']['comments'] = NULL;

						$data['FileProcess']['start_time'] = $data['FileProcess']['end_time'] = $data['FileProcess']['hold_end_time'] = $data['FileProcess']['hold_start_time'] =  $data['FileProcess']['hold_type_id'] = $data['FileProcess']['checklist'] = $data['FileProcess']['actual_time'] = $data['FileProcess']['units_completed'] = NULL;
						
						$this->FileProcess->create();
						if($this->FileProcess->save($data,false)){
							// update file
							$this->loadModel('ProjectFile');
							$projectFile = $this->ProjectFile->find('first',array(
								'conditions'=>array('ProjectFile.id'=>$data['FileProcess']['project_file_id']),
								'recursive'=>-1,
							));

							if($projectFile){
								$projectFile['ProjectFile']['current_status'] = 0;
								$projectFile['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
								$projectFile['ProjectFile']['employee_id'] = $data['FileProcess']['employee_id'];
								$projectFile['ProjectFile']['start_date'] = NULL;
								$projectFile['ProjectFile']['end_date'] = NULL;
								$projectFile['ProjectFile']['comments'] = $data['FileProcess']['comments'];
								$projectFile['ProjectFile']['project_process_plan_id'] = $data['FileProcess']['project_process_plan_id'];
							}

							// $this->ProjectFile->read(null,$data['FileProcess']['project_file_id']);
							
							// $this->ProjectFile->set(array(
							// 	'current_status'=>0,
							// 	'assigned_date'=>date('Y-m-d H:i:s'),
							// 	'employee_id'=>$data['FileProcess']['employee_id'],
							// 	'start_date'=>NULL,
							// 	'end_date'=>NULL,
							// 	'comments'=>$data['FileProcess']['comments'],
							// 	'project_process_plan_id'=>$data['FileProcess']['project_process_plan_id'],
							// ));
							
							if($this->ProjectFile->save($projectFile,false)){

								// $this->Session->setFlash(__('User Updated succuessfully'));	
								echo 'User Updated succuessfully';
								exit;
								// $this->redirect(array('controller'=>'file_processes', 'action'=> 'changeuser' , $this->FileProcess->id));	
							}else{
								// $this->Session->setFlash(__('Unable to update File.'));	
								echo 'Unable to update File.';
								exit;
								// $this->redirect(array('controller'=>'file_processes', 'action'=> 'changeuser' , $this->FileProcess->id));	
							}
							
						}else{
							$this->Session->setFlash(__('Unable to save process'));
							echo 'Unable to save process.';
							exit;
							// $this->redirect(array('controller'=>'file_processes', 'action'=> 'changeuser' , $this->request->data['FileProcess']['id']));
						}
					}else{
						$this->Session->setFlash(__('Incorrect record id passed.'));
						echo 'Incorrect record id passed.';
						exit;
						// $this->redirect(array('controller'=>'file_processes', 'action'=> 'changeuser' . $this->request->data['FileProcess']['id']));
					}
				}

			}else{
				
				// $this->Session->setFlash(__('Incorrect record id passed.'));
				echo 'Incorrect record id passed.';
				exit;
				// $this->redirect(array('controller'=>'file_processes', 'action'=> 'changeuser' , $this->request->data['FileProcess']['id']));
			}

		}else{
			$options = array('conditions' => array('FileProcess.id' => $this->request->params['pass'][0]));
			$this->request->data = $this->FileProcess->find('first', $options);	

		}

		$projectProcessPlans = $this->FileProcess->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'conditions'=>array(
					'ProjectProcessPlan.project_id'=>$this->request->data['FileProcess']['project_id'],
					'ProjectProcessPlan.milestone_id'=>$this->request->data['FileProcess']['milestone_id'],
			)
		));

		$project_id = $this->request->data['FileProcess']['project_id'];

		$this->FileProcess->Project->ProjectEmployee->Employee->virtualFields = array(
			'check_free'=>'select count(*) from `project_files` where (`project_files`.`current_status` = 0 OR `project_files`.`current_status` = 10) AND `project_files`.`project_id` = "'.$project_id.'" AND `project_files`.`employee_id` LIKE Employee.id',
			
			'check_free_pro' => 'select count(*) from `file_processes` where `file_processes.employee_id` LIKE Employee.id AND `file_processes.start_time` IS NULL ORDER BY `file_processes.sr_no` DESC',
			
			'emp_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id AND `project_employees`.`project_id` LIKE "' .$project_id . '"'
		);
		$teamMembers = $this->FileProcess->Project->ProjectEmployee->Employee->find(
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

		$this->set(compact('projectProcessPlans','teamMembers'));
	}

	public function close_file(){

		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data['FileProcess']['id']){
				

			}else{
				
				// $this->Session->setFlash(__('Incorrect record id passed.'));
				echo 'Incorrect record id passed.';
				exit;
				// $this->redirect(array('controller'=>'file_processes', 'action'=> 'changeuser' , $this->request->data['FileProcess']['id']));
			}

		}else{
			$options = array('conditions' => array('FileProcess.id' => $this->request->params['pass'][0]));
			$this->request->data = $this->FileProcess->find('first', $options);	

		}

		$projectProcessPlans = $this->FileProcess->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'conditions'=>array(
					'ProjectProcessPlan.project_id'=>$this->request->data['FileProcess']['project_id'],
					'ProjectProcessPlan.milestone_id'=>$this->request->data['FileProcess']['milestone_id'],
			)
		));

		$project_id = $this->request->data['FileProcess']['project_id'];

		$this->FileProcess->Project->ProjectEmployee->Employee->virtualFields = array(
			'check_free'=>'select count(*) from `project_files` where (`project_files`.`current_status` = 0 OR `project_files`.`current_status` = 10) AND `project_files`.`project_id` = "'.$project_id.'" AND `project_files`.`employee_id` LIKE Employee.id',
			
			'check_free_pro' => 'select count(*) from `file_processes` where `file_processes.employee_id` LIKE Employee.id AND `file_processes.start_time` IS NULL ORDER BY `file_processes.sr_no` DESC',
			
			'emp_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id AND `project_employees`.`project_id` LIKE "' .$project_id . '"'
		);
		$teamMembers = $this->FileProcess->Project->ProjectEmployee->Employee->find(
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

		$this->set(compact('projectProcessPlans','teamMembers'));
	}


	public function closed_open(){

		Configure::write('debug',1);
		$this->FileProcess->ProjectFile->virtualFields = array(
			'closed_file'=>'select sr_no from file_processes where file_processes.project_file_id LIKE ProjectFile.id AND current_status = 5 ORDER BY sr_no ASC LIMIT 1' 
		);
		$files = $this->FileProcess->ProjectFile->find('all',array(
			'conditions'=>array('ProjectFile.closed_file != '=>0),
			'recursive'=>-1));
		
		foreach($files as $file){
			// extra processes
			$fileProcesses = $this->FileProcess->find('all',array(
				'recursive'=>-1,
				'conditions'=>array(
					'FileProcess.sr_no > '=>$file['ProjectFile']['closed_file'],
					'FileProcess.project_file_id'=>$file['ProjectFile']['id'])
			));
			
			if($fileProcesses){

				foreach($fileProcesses as $fileProcess){
					$this->FileProcess->delete($fileProcess['FileProcess']['id']);					
				}
				
				$lastProcess = $this->FileProcess->find('first',array('conditions'=>array('FileProcess.sr_no'=>$file['ProjectFile']['sr_no']),'recursive'=>-1));

				debug($lastProcess);
				
				$file['ProjectFile']['current_status'] = 5;
				$file['ProjectFile']['employee_id'] = $lastProcess['FileProcess']['employee_id'];
				$this->FileProcess->ProjectFile->create();
				$this->FileProcess->ProjectFile->save($file,false);
			}
		}
		exit;
	}


	public function daily_traclksheet($date = null){

		if ($this->request->is('post') || $this->request->is('put')) {

			if($this->request->data['FileProcess']['project_id'] && $this->request->data['FileProcess']['project_id'] != -1)$pcon = array('FileProcess.project_id'=>$this->request->data['FileProcess']['project_id']);
			else $pcon = array();

			if($this->request->data['FileProcess']['date'])$date = date('Y-m-d',strtotime($this->request->data['FileProcess']['date']));
			else $date = date('Y-m-d');


			// Configure::Write('debug',1);
			// debug($date);
			// exit;

			$this->FileProcess->virtualFields = array(
				'actual_time_from_process' => 'select `daily_tracksheet`(FileProcess.id,"'.$date.'",FileProcess.employee_id) as test',
				'matric'=>'select overall_metrics from project_process_plans where project_process_plans.id LIKE FileProcess.project_process_plan_id',
				'ctype'=>'select cal_type from project_overall_plans where project_overall_plans.id LIKE ProjectProcessPlan.project_overall_plan_id',
			);

			$fileProcesses = $this->FileProcess->find('all',array('conditions'=>array(
				$pcon,
				'OR'=>array(
					'DATE(FileProcess.start_time)'=>$date,
					'DATE(FileProcess.end_time)'=>$date,
				)
				),
				'order'=>array('Project.title'=>'ASC', 'ProjectProcessPlan.sequence'=>'ASC','ProjectProcessPlan.process'=>'ASC')				
				// 'recursive'=>-1,
				// 'fields'=>array('FileProcess.start_time','FileProcess.end_time','FileProcess.actual_time_from_process')
			));

			$this->set('fileProcesses',$fileProcesses);


			$this->loadModel('ProjectProcessPlan');
		        
		        $projectProcesses = $this->ProjectProcessPlan->find(
		            'list',array(
		                // 'conditions'=>$projectFile['ProjectFile']['process_id'],
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
		}


		// Configure::Write('debug',1);
		// debug($fileProcesses);
		// exit;

		$projects = $this->FileProcess->Project->find('list',array('conditions'=>array('Project.publish'=>1, 'Project.soft_delete'=>0)));
		$this->set(compact('projects'));

		$this->render('index');
		
    }
}

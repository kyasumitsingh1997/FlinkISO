<?php
App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Helper');

// public $helpers = array('Time');
/**
 * Projects Controller
 *
 * @property Project $Project
 */
class ProjectsController extends AppController {

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
	public function index_projects() {
		
		$conditions = $this->_check_request();
		$this->Project->virtualFields = array(
			'userexists' => 'select count(*) from `project_resources` where 
			(
			`project_resources`.`user_id` LIKE "'.$this->Session->read('User.id').'" 
			OR `project_resources`.`team_leader_id` LIKE "'.$this->Session->read('User.employee_id').'" 
			OR `project_resources`.`project_leader_id` LIKE "'.$this->Session->read('User.employee_id').'" 
			OR `project_resources`.`employee_id` LIKE "'.$this->Session->read('User.employee_id').'" 
			)
			and `project_resources`.`project_id` LIKE Project.id'
		);

		if($this->request->params['named']['published']){
			$pubcon = array('Project.publish'=>0);
		}else{
			$pubcon = array();
		}

		if($this->request->params['named']['soft_delete']){
			$delbcon = array('Project.soft_delete'=>1);
		}else{
			$delcon = array();
		}

		if(isset($this->request->params['named']['current_status'])){
			if($this->request->params['named']['current_status'] != 2){
				$status_conditions = array('Project.current_status'=>$this->request->params['named']['current_status']);
				$pubcon = array('Project.publish'=>1);
				$delcon = array('Project.soft_delete'=>0);
			}else{
				$status_conditions = array(
						'Project.current_status !='=> 4,
						'Project.end_date < '=> date('Y-m-d'),
					);
				$pubcon = array('Project.publish'=>1);
				$delcon = array('Project.soft_delete'=>0);
			}
			
		}else{
			$status_conditions = array();	
		} 

		

		$this->Project->virtualFields = array(
			'total_files'=>'select count(*) from project_files where project_files.project_id LIKE Project.id',
			'closed_files'=>'select count(*) from project_files where project_files.project_id LIKE Project.id AND current_status = 5',
			'total_resources'=>'SELECT count(DISTINCT user_id) FROM `project_resources` where `project_resources`.`project_id` LIKE Project.id',
			'milestone_cost'=>'select sum(estimated_cost) from milestones where milestones.project_id = Project.id AND milestones.soft_delete = 0',
			'activities_cost'=>'select sum(estimated_cost) from project_activities where project_activities.project_id = Project.id AND project_activities.soft_delete = 0',
			'resource_cost'=>'select sum(resource_sub_total) from project_resources where project_resources.project_id = Project.id AND project_resources.soft_delete = 0',
			'resource_hours'=>'select sum(mandays) from project_resources where project_resources.project_id = Project.id AND project_resources.soft_delete = 0',
			'timesheet_cost'=>'select sum(total_cost) from project_timesheets where project_timesheets.project_id = Project.id AND project_timesheets.soft_delete = 0',
			'timesheet_hours'=>'select sum(total) from project_timesheets where project_timesheets.project_id = Project.id AND project_timesheets.soft_delete = 0',
			'other_estimate'=>'select sum(cost) from project_estimates where project_estimates.project_id = Project.id AND project_estimates.soft_delete = 0',
			'po_cost_out'=>'select SUM(total) 
				from purchase_order_details 
				RIGHT JOIN `purchase_orders` ON `purchase_order_details`.`purchase_order_id` = `purchase_orders`.`id` 
					AND `purchase_orders`.`type` = 1 					
					AND `purchase_orders`.`soft_delete` = 0 
					AND `purchase_orders`.`project_id` = "' . $id .'"',
			'po_cost_customer'=>'select SUM(total) 
				from purchase_order_details 
				RIGHT JOIN `purchase_orders` ON `purchase_order_details`.`purchase_order_id` = `purchase_orders`.`id` 
					AND `purchase_orders`.`type` = 0 
					AND `purchase_orders`.`soft_delete` = 0 
					AND `purchase_orders`.`project_id` = "' . $id .'"',
			'userexists' => 'select count(*) from `project_resources` where `project_resources`.`user_id` LIKE "'.$this->Session->read('User.id').'" and `project_resources`.`project_id` LIKE Project.id'
		);

		if(isset($this->request->params['named']['soft_delete'])){

		}else{
			if($this->Session->read('User.is_mr') == false){
				$conditions = array(
					'OR'=>array(
						'Project.created_by'=>$this->Session->read('User.id'),
						'Project.modified_by'=>$this->Session->read('User.id'),
						'Project.userexists > '=> 0
					)
				);		
			}else{
				$conditions = $conditions;
			}
			
		}

		

		$this->paginate = array('order'=>array('Project.end_date'=>'ASC'),'conditions'=>array($conditions,$status_conditions,$pubcon,$delcon));
	
		$this->Project->recursive = 0;
		$projects = $this->paginate();


		foreach ($projects as $project) {
			$project_activities = $this->Project->ProjectActivity->find('list',array(
				'conditions'=> array('ProjectActivity.project_id'=>$project['Project']['id'],
					'ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0,
					)
				));
			// foreach ($project_activities as $activity_key => $activity_value) {
			// 	$project_activity_details = $this->Project->ProjectActivity->ProjectActivityRequirment->find('list',array(
			// 	'conditions'=> array('ProjectActivityRequirment.project_id'=>$project['Project']['id'],
			// 		'ProjectActivityRequirment' =>$activity_key,
			// 		'ProjectActivityRequirment.publish'=>1,'ProjectActivityRequirment.soft_delete'=>0,
			// 		)
			// 	));
			$project_milestones = $this->Project->Milestone->find('list',array(
				'conditions'=> array('Milestone.project_id'=>$project['Project']['id'],
					'Milestone.publish'=>1,'Milestone.soft_delete'=>0,
					)
				));			
			// }
			$project['Milestones'] = $project_milestones;
			$project['ProjectActivities'] = $project_activities;
			$project_details[] = $project;
		}
		
		$this->set('projects', $project_details);
		$this->set('currentStatuses',$this->Project->customArray['currentStatuses']);
		
	}


	public function index(){
		$currentStatuses = $this->Project->customArray['currentStatuses'];
		$this->set('currentStatuses',$currentStatuses);
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
		$this->paginate = array('order'=>array('Project.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->Project->recursive = 0;
		$this->set('projects', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['Project']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['Project']['search_field'] as $search):
				$search_array[] = array('Project.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Project.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->Project->recursive = 0;
		$this->paginate = array('order'=>array('Project.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Project.soft_delete'=>0 , $cons));
		$this->set('projects', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('Project.'.$search => $search_key);
					else $search_array[] = array('Project.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('Project.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('Project.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Project.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Project.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Project.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->Project->recursive = 0;
		$this->paginate = array('order'=>array('Project.sr_no'=>'DESC'),'conditions'=>$conditions , 'Project.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('projects', $this->paginate());
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
		App::uses('CakeTime', 'Helper');
		if (!$this->Project->exists($id)) {
			throw new NotFoundException(__('Invalid project'));
		}
		$options = array('conditions' => array('Project.' . $this->Project->primaryKey => $id),'recursive'=>0);
		$project = $this->Project->find('first', $options);
		$this->set('project', $project);
		
		//get milestone, activities , requirements
		$i = 0 ;
		$milestones = $this->Project->Milestone->find('all',array(
			'recursive'=>0,
			'order'=>array('Milestone.start_date'=>'ASC'),
			'conditions' => array(
				'Milestone.soft_delete'=>0,
				'Milestone.project_id'=>$project['Project']['id'])
			));
		

		$this->Project->PurchaseOrder->virtualFields = array(
			'po_total'=>'select SUM(total) from purchase_order_details where `purchase_order_details`.`purchase_order_id` LIKE PurchaseOrder.id'
		);

		// $project_details['PurchaseOrder']['in'] = $this->Project->PurchaseOrder->find('all',array('conditions'=>array('PurchaseOrder.project_id'=>$id,'PurchaseOrder.type' => 0),'recursive'=>-1));

		$this->loadModel('ProjectResource');

		foreach ($milestones as $milestone) {
			$project_details[$i]['Milestone'] = $milestone['Milestone'];



			// $project_details[$i]['ProjectEstimate'] = $this->Project->ProjectEstimate->find('all',array(
			// 	'conditions'=>array('ProjectEstimate.soft_delete'=>0, 'ProjectEstimate.project_id'=>$id,'ProjectEstimate.milestone_id'=>$milestone['Milestone']['id']),
			// 	'recursive'=>0,
			// ));

			// $this->Project->ProjectFile->virtualFields = array(
			// 	'last_process' => 'select `file_processes`.`project_process_plan_id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`modified` DESC LIMIT 1 ',
			// 	// 'last_process' => 'select `file_processes`.`employee_id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`modified` DESC LIMIT 1 '
			// );
			// $project_details[$i]['ProjectFile'] = $this->Project->ProjectFile->find('all',array(
			// 	'conditions'=>array('ProjectFile.soft_delete'=>0, 'ProjectFile.project_id'=>$id,'ProjectFile.milestone_id'=>$milestone['Milestone']['id']),
			// 	'recursive'=>0,
			// 	'order'=>array('ProjectFile.current_status'=>'ASC')
			// ));


			
			$activities = $this->Project->Milestone->ProjectActivity->find('all',array(
				'recursive'=>0,
				'conditions'=>array('ProjectActivity.milestone_id'=>$milestone['Milestone']['id'])
				));	
			
			
			

			

			// $project_details[$i]['GraphDataProjectEmployee'] = $this->resourceGraph($id,$milestone['Milestone']['id']);

			// $project_details[$i]['GraphDataProcess'] = $this->processGraph($id,$milestone['Milestone']['id']);
			
			$this->Project->Milestone->ProjectProcessPlan->virtualFields = array(
				'total_est_units'=>'select sum(estimated_units) from project_process_plans where project_process_plans.milestone_id LIKE "' .$milestone['Milestone']['id'].'"'
			);
			$est_units = $this->Project->Milestone->ProjectProcessPlan->find('first',array(
				'recursive'=>-1,
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.total_est_units'),
				'conditions'=>array('ProjectProcessPlan.milestone_id'=>$milestone['Milestone']['id'])
			));


			$project_details[$i]['estimated_milestone_units'] = $est_units['ProjectProcessPlan']['total_est_units'] ;
			// Configure::write('debug',1);
			// debug($est_units);
			// Configure::write('debug',0);


			// $this->Project->Invoice->virtualFields = array(
			// 'invoice_total'=>'select SUM(total) from invoice_details where `invoice_details`.`invoice_id` LIKE Invoice.id'
			// );


			// get estimated cost
			$this->Project->Milestone->ProjectEstimate->virtualFields = array(
				'milestone_estimate'=>'select SUM(cost) from project_estimates where project_estimates.milestone_id LIKE "'.$milestone['Milestone']['id'].'"'
			);
			

			$project_details[$i]['Estimated_milestone_cost'] = $estimated_milestone_cost = $this->Project->Milestone->ProjectEstimate->find('first',array(
				'fields'=>array('ProjectEstimate.id','ProjectEstimate.milestone_estimate'),
				'conditions'=>array('ProjectEstimate.milestone_id'=>$milestone['Milestone']['id'])));
			
			// Configure::write('debug',1);
			// debug($project_details[$i]['ProjectPayment']);
			// exit;

			$y = 0;			
			
			foreach ($activities as $activity) {
				$z=0;
				$project_details[$i]['ProjectActivity'][$y] = $activity;


				$project_details[$i]['ProjectActivity'][$y]['ProjectTimesheet'] = $this->Project->ProjectTimesheet->find('all',array(
					'conditions'=>array(
						'ProjectTimesheet.soft_delete'=>0,
						'ProjectTimesheet.project_activity_id'=>$activity['ProjectActivity']['id']
						// ,'ProjectTimesheet.milestone_id'=>$milestone['Milestone']['id']
					),
					'recursive'=>0,
				));

				// $activity_requirements = $this->Project->Milestone->ProjectActivity->ProjectActivityRequirement->find('all',array(
				// 	'recursive'=>0,
				// 	'conditions'=>array('ProjectActivityRequirement.project_activity_id'=>$activity['ProjectActivity']['id'])
				// 	));
				// $tasks = $this->Project->Milestone->ProjectActivity->Task->find('list',array(
				// 	'recursive'=>0,
				// 	'conditions'=>array('Task.project_activity_id'=>$activity['ProjectActivity']['id'])
				// 	));
				// $project_details[$i]['ProjectActivity'][$y]['ProjectActivityRequirement'] = $activity_requirements;
				// $project_details[$i]['ProjectActivity'][$y]['Tasks'] = $tasks;
				$z++;
				$y++;
			}

			$estimated_project_cost = $estimated_project_cost + $estimated_milestone_cost['ProjectEstimate']['milestone_estimate'];

			$i++;	
		}

		// exit;
		
		$this->set('estimated_project_cost',$estimated_project_cost);


		
		$costCategories = $this->Project->ProjectEstimate->CostCategory->find('list',array('conditions'=>array('CostCategory.soft_delete'=>0)));
		$this->set('costCategories',$costCategories);

		$suppliers = $this->Project->PurchaseOrder->SupplierRegistration->find('list',array('conditions'=>array('SupplierRegistration.soft_delete'=>0)));
		$this->set('suppliers',$suppliers);
		// $this->set('costCategories',$costCategories);
		$this->set('projectResources',$projectResources);
		// $this->set('projectTimesheets',$projectTimesheets);
		$this->set('project_details',$project_details);
		// $this->set('projectEstimates',$projectEstimates);

		// catwise POS
		// $this->Project->PurchaseOrder->virtualFields = array(
		// 	'po_sum' => 'SUM(PurchaseOrder.po_total)'
		// );

		// $this->Project->PurchaseOrder->virtualFields = array(
		// 	'po_total'=>'select SUM(total) from purchase_order_details where `purchase_order_details`.`purchase_order_id` LIKE PurchaseOrder.id'
		// );

		// $pos = $this->Project->PurchaseOrder->find('all',array(
		// 	'conditions'=>array('PurchaseOrder.project_id'=>$id),
		// 	'group'=>array('PurchaseOrder.cost_category_id')
		// ));


		$teamLeaders = $this->Project->ProjectResource->find('count',array(
			'conditions'=>array('ProjectResource.project_id'=>$id),
			'group'=>array('ProjectResource.team_leader_id')
		));

		$projectLeaders = $this->Project->ProjectResource->find('count',array(
			'conditions'=>array('ProjectResource.project_id'=>$id),
			'group'=>array('ProjectResource.project_leader_id')
		));

		$members = $this->Project->ProjectResource->find('count',array(
			'conditions'=>array('ProjectResource.project_id'=>$id),
			'group'=>array('ProjectResource.employee_id')
		));

		// $projectManagers = $this->Project->ProjectResource->find('count',array(
		// 	'conditions'=>array('ProjectResource.project_id'=>$id),
		// 	'group'=>array('ProjectResource.employee_id')
		// ));

		$this->set(array('teamLeaders'=>$teamLeaders,'projectLeaders'=>$projectLeaders,'members'=>$members));

		$existingprocesses = $this->Project->ProjectProcessPlan->find('list',array('conditions'=>array('ProjectProcessPlan.soft_delete'=>0), 'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process')));
		$this->set('existingprocesses',$existingprocesses);
		// Configure::write('debug',1);
		// debug($pos);
		// exit;
		$this->set('allProjects',$this->Project->find('list',array('conditions'=>array('Project.soft_delete'=>0,'Project.publish'=>1))));
		$this->set('listOfSoftwares',$this->Project->ProjectProcessPlan->ListOfSoftware->find('list',array('conditions'=>array('ListOfSoftware.soft_delete'=>0))));
		$this->set('PublishedEmployeeList',$this->_get_employee_list());
		$this->set('PublishedDepartmentList',$this->_get_department_list());
		$this->set('PublishedDesignationList',$this->_get_designation_list());

		$this->set('teamMembers',$this->team_members($id));
		// $this->set('allMembers',$this->all_members($id,$project['Project']['start_date'],$project['Project']['end_date']));
		$this->set('currentStatuses',$this->Project->customArray['currentStatuses']);
		
		$this->set('fileStatuses',$this->Project->ProjectFile->customArray['currentStatuses']);

		$deliverableUnits = $this->Project->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));
		$this->set('deliverableUnits',$deliverableUnits);
		
		$milestoneTypes = $this->Project->Milestone->MilestoneType->find('list',array('conditions'=>array('MilestoneType.publish'=>1,'MilestoneType.soft_delete'=>0)));
		$this->set('milestoneTypes',$milestoneTypes);

		$currencies = $this->Project->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
		$this->set('currencies',$currencies);

		$this->set('weekends',$this->Project->customArray['weekends']);


		$singleBatches = $this->Project->ProjectFile->find('list',array(
			'conditions'=>array('ProjectFile.single_batch'=>1,'ProjectFile.project_id'=>$id)
		));
		$this->set('singleBatches',$singleBatches);

		return array($project,$project_details,$projectResources);

	}

	public function over_all_plan($project_id = null, $milestone_id = null,$pop = null, $por = null){
		$i = 0;
		// Configure::write('debug',1);
		// debug($milestone_id);
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$this->Project->ProjectOverallPlan->virtualFields = array(
			'actual_units'=>'select SUM(unit) from project_files where project_files.milestone_id LIKE "'.$milestone_id.'"'
		);

			$overallPlans = $this->Project->ProjectOverallPlan->find('all',array(
				'conditions'=>array(
					'ProjectOverallPlan.project_id'=>$project_id,
					'ProjectOverallPlan.milestone_id'=>$milestone['Milestone']['id']
				),
				'recursive'=>0,
			));
			// Configure::write('debug',1);
			$p = 0;
			foreach ($overallPlans as $plan) {
				// debug($plan);
				$planResult[$p] = $plan;
				$planResult[$p]['DetailedPlan'] = $this->Project->ProjectOverallPlan->ProjectProcessPlan->find('all',array(
					'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
					'conditions'=>array('ProjectProcessPlan.soft_delete'=>0, 'ProjectProcessPlan.project_overall_plan_id'=>$plan['ProjectOverallPlan']['id'])
				));

				// debug($planResult[$p]);
				$existingprocesses[$plan['ProjectOverallPlan']['id']] = $this->Project->ProjectProcessPlan->find('list',array(
					'conditions'=>array(
						'ProjectProcessPlan.publish'=>1,
						'ProjectProcessPlan.project_overall_plan_id'=>$plan['ProjectOverallPlan']['id'],
						'ProjectProcessPlan.soft_delete'=>0), 
					'fields'=>array(
						'ProjectProcessPlan.id','ProjectProcessPlan.process')
				));

				$p++;
			}

			// $milestone['Plan']  = $planResult;
			$this->set('planResult',$planResult);
			$this->set(array('pop'=>$pop,'por'=>$por));

			

			debug($existingprocesses);
			
			$this->set('existingprocesses',$existingprocesses);

			$this->set('listOfSoftwares',$this->Project->ProjectProcessPlan->ListOfSoftware->find('list',array('conditions'=>array('ListOfSoftware.soft_delete'=>0))));

			$this->set('PublishedEmployeeList',$this->_get_employee_list());
			$this->set('PublishedDepartmentList',$this->_get_department_list());
			$this->set('PublishedDesignationList',$this->_get_designation_list());
	}


	public function est_costs($project_id = null, $milestone_id = null){
		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$projectEstimates = $this->Project->ProjectEstimate->find('all',array(
			'conditions'=>array('ProjectEstimate.soft_delete'=>0, 'ProjectEstimate.project_id'=>$milestone['Milestone']['project_id'],'ProjectEstimate.milestone_id'=>$milestone['Milestone']['id']),
			'recursive'=>0,
		));

		$this->set('projectEstimates',$projectEstimates);

		$costCategories = $this->Project->ProjectEstimate->CostCategory->find('list',array('conditions'=>array('CostCategory.soft_delete'=>0)));
		$this->set('costCategories',$costCategories);
	}

	public function inbound_pos($project_id = null, $milestone_id = null){
		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$inboundPos = $this->Project->PurchaseOrder->find('all',array('conditions'=>array('PurchaseOrder.soft_delete'=>0,'PurchaseOrder.project_id'=>$project_id,'PurchaseOrder.milestone_id'=>$milestone['Milestone']['id'], 'PurchaseOrder.type' => 0),'recursive'=>-1));
		$this->set('inboundPos',$inboundPos);
		
	}

	public function invoices($project_id = null, $milestone_id = null){
		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);	

		$invoices = $this->Project->Invoice->find('all',array('conditions'=>array('Invoice.project_id'=>$id,'Invoice.milestone_id'=>$milestone['Milestone']['id']),'recursive'=>-1));
		$this->set('invoices',$invoices);
	}

	public function outbound_pos($project_id = null, $milestone_id = null){
		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$outboundPos = $this->Project->PurchaseOrder->find('all',array('conditions'=>array('PurchaseOrder.soft_delete'=>0, 'PurchaseOrder.project_id'=>$id,'PurchaseOrder.milestone_id'=>$milestone['Milestone']['id'], 'PurchaseOrder.type' => 1),'recursive'=>-1));

		$this->set('outboundPos',$outboundPos);
	}

	public function payment_received($project_id = null, $milestone_id = null){
		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$projectPayments = $this->Project->ProjectPayment->find('all',array(
				'recursive'=>0,
				'fields'=>array(
					'ProjectPayment.id',
					'ProjectPayment.amount',
					'ProjectPayment.amount_received',
					'ProjectPayment.units',
					'ProjectPayment.received_date',
					'ProjectPayment.invoice_id',
					'ProjectPayment.reason_for_delay',
					'Invoice.id',
					'Invoice.invoice_number',
					'Invoice.invoice_date',
				),
				'conditions'=>array('ProjectPayment.soft_delete'=>0, 'ProjectPayment.milestone_id'=>$milestone['Milestone']['id'])
			));

		$this->set('projectPayments',$projectPayments);
		
	}

	public function project_checklists($project_id = null, $milestone_id = null){

		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$projectChecklists = $this->Project->ProjectChecklist->find('all',array('conditions'=>array('ProjectChecklist.soft_delete'=>0,'ProjectChecklist.soft_delete'=>0, 'ProjectChecklist.project_id'=>$milestone['Milestone']['project_id'],'ProjectChecklist.milestone_id'=>$milestone['Milestone']['id']),'recursive'=>0));

		$this->set('projectChecklists',$projectChecklists);


		$this->Project->ProjectProcessPlan->virtualFields = array(
			'ocount'=>'select count(*) from project_overall_plans where  project_overall_plans.id LIKE ProjectProcessPlan.project_overall_plan_id'
		);
		
		$projectProcessPlans = $this->Project->ProjectProcessPlan->find('list',array(
			'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
			'conditions'=>array(				
				'ProjectProcessPlan.ocount > '=> 0,
				'ProjectProcessPlan.project_id'=>$project_id,
				'ProjectProcessPlan.milestone_id'=>$milestone_id
			)));
		$this->set('projectProcessPlans',$projectProcessPlans);
		
	}

	public function project_errors($project_id = null, $milestone_id = null){
		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$fileErrorMasters = $this->Project->FileErrorMaster->find('all',array('conditions'=>array(
			'FileErrorMaster.soft_delete'=>0, 
			'FileErrorMaster.project_id'=>$milestone['Milestone']['project_id'],
			'FileErrorMaster.milestone_id'=>$milestone['Milestone']['id']),'recursive'=>-1));	

		$this->set('fileErrorMasters',$fileErrorMasters);


		$this->Project->ProjectProcessPlan->virtualFields = array(
			'ocount'=>'select count(*) from project_overall_plans where  project_overall_plans.id LIKE ProjectProcessPlan.project_overall_plan_id'
		);
		$projectProcessPlans = $this->Project->FileErrorMaster->ProjectProcessPlan->find('list',array(
			'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
			'conditions'=>array(
				'ProjectProcessPlan.qc'=>1,
				'ProjectProcessPlan.ocount > '=> 0,
				'ProjectProcessPlan.project_id'=>$project_id,
				'ProjectProcessPlan.milestone_id'=>$milestone_id
			)));
		$this->set('projectProcessPlans',$projectProcessPlans);
	}


	public function get_latest_assigned($thisProjectId = null, $employee_id = null,$last_process = null){
		$this->loadModel('FileProcess');
		$pro = $this->FileProcess->find('first',array(
			'recursive'=>-1,
			'conditions'=>array(
				'FileProcess.project_id'=>$thisProjectId,
				'FileProcess.employee_id'=>$employee_id,
				'FileProcess.project_process_plan_id'=>$last_process,
			)
		));

		// $this->ProjectFile->virtualFields = array(
  //           'curr_stage' => 'select `file_processes`.`project_process_plan_id` from `file_processes` WHERE  `file_processes`.`project_file_id` = ProjectFile.id AND `file_processes`.`current_status` = 0 ORDER BY `file_processes`.`created` DESC LIMIT 1',
  //           'queued' => 'select `count(*) from `file_processes` WHERE  `file_processes`.`project_file_id` = ProjectFile.id AND  `file_processes`.`queued` = 1 LIMIT 1 ',
  //           'pro_pub' => 'select publish from projects where projects.id = ProjectFile.project_id',
  //           'pro_sd' => 'select soft_delete from projects where projects.id = ProjectFile.project_id',
  //           'estimated_time_1'=>'ROUND(ProjectProcessPlan.estimated_units / ProjectProcessPlan.overall_metrics / ProjectProcessPlan.days / ProjectProcessPlan.estimated_resource)',
  //           'cat_on_hold'=>'select `file_categories`.`status` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
  //       );

		// $projectFile = $this->ProjectFile->find('first',array(
		// 	'fields'=>array('ProjectFile.id'),
  //           'recursive'=>0,
  //           'conditions'=>array(
  //               'ProjectFile.project_id'=>$thisProjectId,
  //               'ProjectFile.pro_pub' =>1,
  //               'ProjectFile.pro_sd' =>0,
  //               'ProjectFile.queued'=>1,
  //               'ProjectFile.employee_id'=>$employee_id,
  //               'ProjectFile.current_status'=>array(0,2,4,7,8,10,12),       
  //               // 'ProjectFile.current_status != '=>array(11,5), 
  //               'ProjectFile.cat_on_hold'=>0,
  //               // 'ProjectFile.employee_id'=>$this->Session->read('User.employee_id')
  //           ),
  //           'order'=>array('ProjectProcessPlan.qc'=>'ASC')
  //       ));        

        
        

  //       if(!$projectFile){
  //           // echo "asd";
  //           $projectFile = $this->ProjectFile->find('first',array(
  //           	'fields'=>array('ProjectFile.id'),
	 //            'recursive'=>0,
	 //            'conditions'=>array(
	 //                'ProjectFile.project_id'=>$thisProjectId,
	 //                'ProjectFile.pro_pub' =>1,
	 //                'ProjectFile.pro_sd' =>0,
	 //                // 'ProjectFile.queued'=>1,
	 //                'ProjectFile.employee_id'=>$employee_id,
	 //                'ProjectFile.current_status'=>array(0,2,4,7,8,10,12),       
	 //                // 'ProjectFile.current_status != '=>array(11,5), 
	 //                'ProjectFile.cat_on_hold'=>0,
	 //                // 'ProjectFile.employee_id'=>$this->Session->read('User.employee_id')
	 //            ),
	 //            'order'=>array('ProjectProcessPlan.qc'=>'ASC')
  //       	));
  //       }

  //       if(!$projectFile){
  //           // echo "asd";
  //           $projectFile = $this->ProjectFile->find('first',array(
  //               'recursive'=>0,
  //               'fields'=>array('ProjectFile.id'),
  //               'conditions'=>array(
  //                   'ProjectFile.project_id'=>$thisProjectId,
  //                   'ProjectFile.pro_pub' =>1,
  //                   'ProjectFile.pro_sd' =>0,
  //                   'ProjectFile.current_status'=>array(0,2,4,7,8,10,12),       
  //                   'ProjectFile.current_status != '=>array(11,5), 
  //                   'ProjectFile.parent_id'=>NULL,  
  //                   'ProjectFile.cat_on_hold'=>0,
  //                   // 'ProjectFile.employee_id'=>$this->Session->read('User.employee_id'),
  //                   // 'ProjectFile.employee_id'=>$this->Session->read('User.employee_id')
  //               )
  //           ));
  //       }
  //       // // Configure::write('debug',1);
  //       // // debug($projectFile);

  //       $currentProcesses = $this->ProjectFile->FileProcess->find(
  //           'first',array(
  //               'conditions'=>array(
  //                   'or'=>array(
  //                       'FileProcess.project_process_plan_id' => array_keys($projectProcesses),
  //                       'ProjectFile.project_process_plan_id' => array_keys($projectProcesses)

  //                   ),

  //                   'FileProcess.project_process_plan_id != ' => '',
  //                   'FileProcess.project_id'=>$projectFile['ProjectFile']['project_id'],
  //                   'FileProcess.milestone_id'=> $projectFile['ProjectFile']['milestone_id'],
  //                   'FileProcess.project_file_id'=>$projectFile['ProjectFile']['id'],
  //                   'ProjectFile.employee_id'=>$employee_id,
  //                   'FileProcess.current_status'=>array(0,2,4,7,8,10,12),   
  //                   // 'FileProcess.current_status'=>array(0,7,10),

  //               ),
  //               'fields'=>array(
  //                   // 'FileProcess.id',
  //                   // 'FileProcess.project_process_plan_id',
  //               ),
  //               'order'=>array(
  //                   'FileProcess.created'=>'DESC'
  //               )
  //           )
  //       );
		// Configure::write('debug',1);
		// debug($pro['FileProcess']['queued']);
		// debug($employee_id);
		// debug($currentProcesses);
  //       return $currentProcesses['FileProcess']['project_file_id'];
        return $pro['FileProcess']['queued'];
	}

	public function project_files_milestone_count($project_id = null, $milestone_id = null){

		if ($this->request->is('post')) {
			
			
			if($this->request->data['ProjectFileSearchFake']['file_category_id'] != -1){
				$con1 = array('ProjectFile.file_category_id' => $this->request->data['ProjectFileSearchFake']['file_category_id']);
			}

			if($this->request->data['ProjectFileSearchFake']['cities'] != -1){
				$con2 = array('ProjectFile.city' => $this->request->data['ProjectFileSearchFake']['cities']);
			}

			if($this->request->data['ProjectFileSearchFake']['blocks'] != -1){
				$con3 = array('ProjectFile.block' => $this->request->data['ProjectFileSearchFake']['blocks']);
			}

			if($this->request->data['ProjectFileSearchFake']['team_members'] != -1){
				$con4 = array('ProjectFile.employee_id' => $this->request->data['ProjectFileSearchFake']['team_members']);
			}

			if($this->request->data['ProjectFileSearchFake']['project_process_plan_id'] != -1){
				$con5 = array('ProjectFile.project_process_plan_id' => $this->request->data['ProjectFileSearchFake']['project_process_plan_id']);
			}

			if($this->request->data['ProjectFileSearchFake']['current_status'] != -1){
				$con6 = array('ProjectFile.current_status' => $this->request->data['ProjectFileSearchFake']['current_status']);
			}

			$projectFakeFormCons = array($con1,$con2,$con3,$con4,$con5,$con6);
		}else{
			$projectFakeFormCons = array('ProjectFile.current_status != '=>array(2,3,5));
		}
		
		$projectFiles = $this->Project->ProjectFile->find('count',array(
			'conditions'=>array(
				$projectFakeFormCons,
				'ProjectFile.soft_delete'=>0, 
				'ProjectFile.parent_id'=> NULL, 
				'ProjectFile.project_id'=>$project_id,
				'ProjectFile.milestone_id'=>$milestone_id
			),			
		));

		return $projectFiles;
	}

	public function project_files($project_id = null, $milestone_id = null){
		if ($this->request->is('post')) {
			
			
			if($this->request->data['ProjectFileSearchFake']['file_category_id'] != -1){
				$con1 = array('ProjectFile.file_category_id' => $this->request->data['ProjectFileSearchFake']['file_category_id']);
			}

			if($this->request->data['ProjectFileSearchFake']['cities'] != -1){
				$con2 = array('ProjectFile.city' => $this->request->data['ProjectFileSearchFake']['cities']);
			}

			if($this->request->data['ProjectFileSearchFake']['blocks'] != -1){
				$con3 = array('ProjectFile.block' => $this->request->data['ProjectFileSearchFake']['blocks']);
			}

			if($this->request->data['ProjectFileSearchFake']['team_members'] != -1){
				$con4 = array('ProjectFile.employee_id' => $this->request->data['ProjectFileSearchFake']['team_members']);
			}

			if($this->request->data['ProjectFileSearchFake']['project_process_plan_id'] != -1){
				$con5 = array('ProjectFile.project_process_plan_id' => $this->request->data['ProjectFileSearchFake']['project_process_plan_id']);
			}

			if($this->request->data['ProjectFileSearchFake']['current_status'] != -1){
				$con6 = array('ProjectFile.current_status' => $this->request->data['ProjectFileSearchFake']['current_status']);
			}

			$projectFakeFormCons = array($con1,$con2,$con3,$con4,$con5,$con6);
		}else{
			$projectFakeFormCons = array('ProjectFile.current_status != '=>array(2,3,5));
		}

		$totalProjectFiles = $this->Project->ProjectFile->find('count',array('conditions'=>array('ProjectFile.project_id'=>$project_id,'ProjectFile.milestone_id'=>$milestone_id)));

		// get cities
		$cities = $this->Project->ProjectFile->find('list',array(
			'fields'=>array('ProjectFile.city','ProjectFile.city'),
			'group'=>array('ProjectFile.city'),
			'conditions'=>array(
				'ProjectFile.project_id'=>$project_id,
				'ProjectFile.milestone_id'=>$milestone_id)
			)
		);

		// get blocks
		$blocks = $this->Project->ProjectFile->find('list',array(
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
		
		$teamsleads = $this->Project->find('first',array(
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
		$qcs = $this->Project->ProjectProcessPlan->find('all',array('conditions'=>array('ProjectProcessPlan.qc'=>1,'ProjectProcessPlan.milestone_id'=>$milestone_id)));
		
		if($qcs){
			$errocheck = $this->Project->FileErrorMaster->find('count',array(
				'conditions'=>array(
					'FileErrorMaster.project_id'=>$project_id,
					'FileErrorMaster.milestone_id'=>$milestone_id,
				)
			));
		}else{
			$errocheck = 1;
		}
		
		$checklistcheck = $this->Project->ProjectChecklist->find('count',array(
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
			$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
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

	        

			$projectProcesses['ProjectProcessPlan']['estimated_units'] / $projectProcesses['ProjectProcessPlan']['overall_metrics'] / $projectProcesses['ProjectProcessPlan']['days'] / $projectProcesses['ProjectProcessPlan']['estimated_resource'];

			

			$this->Project->ProjectFile->virtualFields = array(
					'cat_on_hold'=>'select `file_categories`.`status` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
					'last_process' => 'select `file_processes`.`project_process_plan_id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',

					'last_process_id' => 'select `file_processes`.`id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',

					'last_emp_id' => 'select `file_processes`.`employee_id` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id and file_processes.current_status != 1  ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',
					
					'last_comment' => 'select `file_processes`.`comments` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',

					// 'last_comment_admin' => 'select `file_processes`.`change_user_comments` from `file_processes` where `file_processes`.`change_user_comments` IS NOT NULL and `file_processes`.`project_file_id` = ProjectFile.id ORDER BY `file_processes`.`sr_no` DESC LIMIT 1 ',
					
					'start_time' => 'select `file_processes`.`assigned_date` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id AND `file_processes`.`assigned_date` IS NOT NULL ORDER BY `file_processes`.`assigned_date` ASC LIMIT 1 ',
					
					'end_time' => 'select `file_processes`.`end_time` from `file_processes` where `file_processes`.`project_file_id` = ProjectFile.id AND (current_status = 1 OR current_status = 5) AND `file_processes`.`start_time` IS NOT NULL ORDER BY `file_processes`.`end_time` DESC LIMIT 1 ',
					
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
					'latest_status'=>'select current_status from file_processes where file_processes.project_file_id LIKE ProjectFile.id order by file_processes.sr_no DESC LIMIT 1'
				);

				if($projectFakeFormCons){
					$projectFiles = $this->Project->ProjectFile->find('all',array(
						'conditions'=>array(
							$projectFakeFormCons,
							'ProjectFile.soft_delete'=>0, 
							'ProjectFile.parent_id'=> NULL, 
							'ProjectFile.project_id'=>$milestone['Milestone']['project_id'],
							'ProjectFile.milestone_id'=>$milestone['Milestone']['id']
						),
						'recursive'=>0,
						'order'=>array(
							'ProjectFile.name'=>'ASC',
							'ProjectFile.current_status'=>'DESC',
							'ProjectFile.employee_id'=>'ASC',					
						)
					));	
				}
				

				$this->set('projectFiles',$projectFiles);
				$this->set('existingprocesses',$existingprocesses);
				
				$this->Project->ProjectEmployee->Employee->virtualFields = array(
					'check_free'=>'select count(*) from `project_files` where (`project_files`.`current_status` = 0 OR `project_files`.`current_status` = 10) AND `project_files`.`project_id` = "'.$project_id.'" AND `project_files`.`employee_id` LIKE Employee.id',
					
					'emp_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id AND `project_employees`.`project_id` LIKE "' .$project_id . '"'
				);
				$teamMembers = $this->Project->ProjectEmployee->Employee->find(
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
			
				$this->set('fileStatuses',$this->Project->ProjectFile->customArray['currentStatuses']);
				$this->set('fileCategories',$this->Project->ProjectFile->FileCategory->find('list',array('conditions'=>array(
					'FileCategory.publish'=>1,
					'FileCategory.soft_delete'=>0,
					// 'FileCategory.status'=>0,
					'FileCategory.project_id'=>$project_id,
					'FileCategory.milestone_id'=>$milestone_id))));


				$fileBatches = $this->Project->ProjectFile->find('list',array(
					'conditions'=>array(
						'ProjectFile.project_id'=>$project_id,
						// 'ProjectFile.milestone_id'=>$milestone_id,
						'ProjectFile.single_batch'=>1
					)
				));

				$this->set('fileBatches',$fileBatches);

				return count($projectFiles);
		}

		
	}

	public function team_board($project_id = null, $milestone_id = null,$pop = null, $por = null){

		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);
		$this->loadModel('ProjectResource');
		
		$this->ProjectResource->virtualFields = array(
			// 'emp_already_assigned_seq'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id  AND ProjectResource.process_id ="' . $project_process_plan_id .'"',
			'emp_already_assigned'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id OR `project_files.modified_by` = ProjectResource.employee_id',
			'emp_on_leave'=> 0,
			'check_deleted'=>'select count(*) from  project_overall_plans where project_overall_plans.id LIKE ProjectProcessPlan.project_overall_plan_id'
		);	
			// $resources = $this->ProjectResource->find('list',array(
			// 	'conditions'=>array(
			// 		'ProjectResource.soft_delete'=>0,
			// 		'ProjectResource.project_id'=>$milestone['Milestone']['project_id'],
			// 		'ProjectResource.emp_already_assigned '=> 0,
			// 		'ProjectResource.employee_id !=' => $employee_id
			// 	),
			// 	'recursive'=>1,
			// 	'fields'=>array(
			// 		// 'ProjectResource.id',
			// 		// 'ProjectResource.priority',
			// 		'Employee.id',
			// 		'Employee.name',
			// 		// 'User.id',
			// 		// 'User.name',
			// 		// 'User.name',
			// 		// 'ProjectProcessPlan.id',
			// 		// 'ProjectProcessPlan.process',
			// 		// 'ProjectResource.emp_already_assigned',
			// 		// 'ProjectResource.emp_on_leave',
			// 		// 'Project.id',
			// 		// 'Project.title',
			// 		// 'Project.start_date',
			// 		// 'Project.end_date',
			// 	),
			// 	'Order'=>array('ProjectResource.priority'=>'ASC')
			// ));
			// Configure::write('debug',1);
			// debug($resources);
			// exit;
			// $project_details[$i]['Resources'] = $resources;
			

			
			// debug($project_details);
			$projectEmployees = $this->Project->ProjectEmployee->find('list',array(
				'fields'=>array('ProjectEmployee.employee_id','ProjectEmployee.milestone_id'),
				// 'order'=>array('Employee.name'=>'ASC'),
				'conditions'=>array(
					// 'ProjectEmployee.milestone_id !=' =>'',
					'ProjectEmployee.soft_delete'=>0,
					'ProjectEmployee.project_id'=>$milestone['Milestone']['project_id'],
					// 'ProjectEmployee.milestone_id'=>$milestone['Milestone']['id']
				)
			));
			// echo $milestone['Milestone']['id'] ."<br />";
			// Configure::write('debug',1);
			// debug($projectEmployees);
			$pp=0;
			foreach ($projectEmployees as $employee_id => $mid) {
				
				$this->Project->ProjectEmployee->Employee->virtualFields = array(
					// 'files_assigned'=> $this->requestAction(
					// 			array('controller'=>'employees','action'=>'employee_files',
					// 				$employee_id,'project_id'=>$project_id,'milestone_id'=>$milestone_id))
'files_assigned'=>'select count(*) from (select * from file_processes where file_processes.employee_id LIKE "'.$employee_id.'" AND file_processes.project_id LIKE "'.$milestone['Milestone']['project_id'].'" AND file_processes.milestone_id LIKE "'.$milestone['Milestone']['id'].'" GROUP BY file_processes.project_file_id) as cnt'
				);

				$projectResources[$pp] = $this->Project->ProjectEmployee->Employee->find('first',array(
					'conditions'=>array('Employee.id'=>$employee_id),
					'recursive'=>-1,
				));
				
				$projectResources[$pp]['Processes'] = $this->Project->ProjectResource->find('all',array(
					'fields'=>array(
						'ProjectResource.id',
						'ProjectResource.employee_id',
						'ProjectResource.team_leader_id',
						'ProjectResource.project_leader_id',
						'ProjectProcessPlan.id',
						'ProjectProcessPlan.process',
						'ProjectProcessPlan.project_overall_plan_id',
						'ProjectResource.priority',
						'Employee.id',
						'Employee.name',
						'Employee.employee_number',
						'Employee.department_id',
						'Employee.designation_id',
						'ProjectResource.check_deleted',
						// 'ProjectResource.files_assigned',
					),
					'conditions'=>array(
						'ProjectResource.soft_delete'=>0,
						'ProjectResource.employee_id'=>$employee_id,
						'ProjectResource.project_id'=>$milestone['Milestone']['project_id'],
						'ProjectResource.milestone_id'=>$milestone['Milestone']['id'],
						'ProjectResource.check_deleted >'=>0
					),
					'order'=>array('ProjectResource.priority'=>'ASC'),
					'recursive'=>0,
				));
				$pp++;
			}
			$pp = '';
			// Configure::write('debug',1);
			// // // debug($milestone['Milestone']['id']);
			// debug($projectResources);
			// $project_details[$i]['ProjectResource'] = $this->Project->ProjectResource->find('all',array(
			// 	'conditions'=>array('ProjectResource.project_id'=>$id,'ProjectResource.milestone_id'=>$milestone['Milestone']['id']),
			// 	'recursive'=>0,
			// ));
			// $project_details[$i]['ProjectResource'] = $recs;
			$this->set('projectResources',$projectResources);
			// $this->set('resources',$resources);
			$this->set('teamMembers',$this->team_members($project_id));

			// $this->set('PublishedEmployeeList',$this->_get_employee_list());
			$this->set('PublishedDepartmentList',$this->_get_department_list());
			$this->set('PublishedDesignationList',$this->_get_designation_list());
	}

	public function assign_process($project_id = null, $milestone_id = null,$pop = null, $por = null){
		$i = 0;
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$project = $this->Project->find('first',array(
			'recursive'=>-1,
			'conditions'=>array('Project.id'=>$project_id)
		));

		// Configure::write('debug',1);
		// debug($project);

		$teamLeaders = $this->Project->ProjectEmployee->Employee->find('list',array('conditions'=>array('Employee.id'=>json_decode($project['Project']['team_leader_id']))));
		$projectLeaders = $this->Project->ProjectEmployee->Employee->find('list',array('conditions'=>array('Employee.id'=>json_decode($project['Project']['project_leader_id']))));
		// debug($projectLeaders);
		$overallPlans = $this->Project->ProjectOverallPlan->find('all',array(
				'conditions'=>array(
					'ProjectOverallPlan.project_id'=>$project_id,
					'ProjectOverallPlan.milestone_id'=>$milestone['Milestone']['id']
				),
				'recursive'=>0,
			));
			// Configure::write('debug',1);
			$p = 0;
			foreach ($overallPlans as $plan) {
				// debug($plan);
				$planResult[$p] = $plan;
				$planResult[$p]['DetailedPlan'] = $this->Project->ProjectOverallPlan->ProjectProcessPlan->find('all',array(
					'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
					'conditions'=>array('ProjectProcessPlan.soft_delete'=>0, 'ProjectProcessPlan.project_overall_plan_id'=>$plan['ProjectOverallPlan']['id'])
				));

				debug($planResult[$p]);
				$p++;
			}

			// $milestone['Plan']  = $planResult;
			$this->set('planResult',$planResult);
			$this->set(array('pop'=>$pop,'por'=>$por));

			$existingprocesses = $this->Project->ProjectProcessPlan->find('list',array('conditions'=>array('ProjectProcessPlan.soft_delete'=>0), 'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process')));
			$this->set('existingprocesses',$existingprocesses);
			$this->set('teamMembers',$this->team_members($project_id));

			$this->set('PublishedEmployeeList',$this->_get_employee_list());
			$this->set('PublishedDepartmentList',$this->_get_department_list());
			$this->set('PublishedDesignationList',$this->_get_designation_list());

			$this->set(array('teamLeaders'=>$teamLeaders,'projectLeaders'=>$projectLeaders));
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
			$this->request->data['Project']['system_table_id'] = $this->_get_system_table_id();
			$this->Project->create();
			
			// unset($this->request->data['Project']['end_date']);
			// $dateRange = explode('-', $this->request->data['Project']['start_date']);
			// $start_date = rtrim(ltrim($dateRange[0]));
			// $end_date = rtrim(ltrim($dateRange[1]));
			
			// $this->request->data['Project']['start_date'] = date('Y-m-d',strtotime($start_date));
			// $this->request->data['Project']['end_date'] = date('Y-m-d',strtotime($end_date));

			$this->request->data['Project']['branch_id'] = $this->Session->read('User.branch_id');

			$this->request->data['Project']['employee_id'] = json_encode($this->request->data['Project']['employee_id']);
			$this->request->data['Project']['team_leader_id'] = json_encode($this->request->data['Project']['team_leader_id']);
			$this->request->data['Project']['project_leader_id'] = json_encode($this->request->data['Project']['project_leader_id']);
			$this->request->data['Project']['weekends'] = json_encode($this->request->data['Project']['weekends']);

			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;

			if ($this->Project->save($this->request->data)) {

				foreach ($this->request->data['Milestone'] as $m) {

					if($m['Milestone']['start_date'] == '')$msd = date('Y-m-d',strtotime($this->request->data['Project']['start_date']));
					else $msd = date('Y-m-d',strtotime($m['Milestone']['start_date']));

					if($m['Milestone']['end_date'] == '')$med = date('Y-m-d',strtotime($this->request->data['Project']['end_date']));
					else $med = date('Y-m-d',strtotime($m['Milestone']['end_date']));

					$m['Milestone']['start_date'] = $msd;
					$m['Milestone']['end_date'] = $med;

					if($m['Milestone']['start_date'] != '' && $m['Milestone']['end_date'] != ''){
						debug($m);

						$milestone['Milestone'] = $m['Milestone'];
						$milestone['Milestone']['branch_id'] = $this->Session->read('User.branch_id');
						$milestone['Milestone']['project_id'] = $this->Project->id;						
						$milestone['Milestone']['publish'] = $this->request->data['Project']['publish'];

						

						debug($milestone);
						$this->Project->Milestone->create();

						if($this->Project->Milestone->save($milestone,false)){
							// exit;

							

							foreach ($m['ProjectOverallPlan'] as $pop) {
								$pop['ProjectOverallPlan']['project_id'] = $this->Project->id;
								$pop['ProjectOverallPlan']['milestone_id'] = $this->Project->Milestone->id;
								$pop['ProjectOverallPlan']['plan_type'] = $pop['plan_type'];
								$pop['ProjectOverallPlan']['qc'] = $pop['qc'];
								$pop['ProjectOverallPlan']['type'] = $pop['type'];
								$pop['ProjectOverallPlan']['lot_process'] = $pop['lot_process'];
								$pop['ProjectOverallPlan']['estimated_units'] = $pop['estimated_units'];
								$pop['ProjectOverallPlan']['overall_metrics'] = $pop['overall_metrics'];
								$pop['ProjectOverallPlan']['start_date'] = $msd;
								$pop['ProjectOverallPlan']['end_date'] = $med;
								$pop['ProjectOverallPlan']['days'] = $pop['days'];
								$pop['ProjectOverallPlan']['estimated_resource'] = $pop['estimated_resource'];
								$pop['ProjectOverallPlan']['estimated_manhours'] = $pop['estimated_manhours'];
								$pop['ProjectOverallPlan']['branchid'] = $this->Session->read('User.branchid');
								$pop['ProjectOverallPlan']['deapartmentid'] = $this->Session->read('User.deapartmentid');
								$pop['ProjectOverallPlan']['publish'] = $this->request->data['Project']['publish'];
								$pop['ProjectOverallPlan']['soft_delete'] = 0;
								$pop['ProjectOverallPlan']['prepared_by'] = $this->request->data['Project']['prepared_by'];
								$this->Project->ProjectOverallPlan->create();
								$this->Project->ProjectOverallPlan->save($pop,false);

							}

							foreach ($m['ProjectResource'] as $pr) {
								$projectResource['ProjectResource']['project_id'] = $this->Project->id;
								$projectResource['ProjectResource']['milestone_id'] = $this->Project->Milestone->id;
								$projectResource['ProjectResource']['user_id'] = $pr['user_id'];
								$projectResource['ProjectResource']['mandays'] = $pr['mandays'];
								$projectResource['ProjectResource']['resource_cost'] = $pr['resource_cost'];
								$projectResource['ProjectResource']['resource_sub_total'] = $pr['resource_sub_total'];
								$projectResource['ProjectResource']['prepared_by'] = $this->request->data['Project']['prepared_by'];
								$projectResource['ProjectResource']['publish'] = $this->request->data['Project']['publish'];
								$this->Project->ProjectResource->create();
								$this->Project->ProjectResource->save($projectResource,false);


								$activities = explode(PHP_EOL, $pr['activities']);
								if($activities){
									foreach ($activities as $act) {
										$proAct['project_resource_id'] = $this->Project->ProjectResource->id;
										$proAct['title'] = $proAct['details'] = $act;
										$proAct['project_id'] = $this->Project->id;
										$proAct['milestone_id'] = $this->Project->Milestone->id;
										$proAct['estimated_cost'] = 0;
										$proAct['start_date'] = $m['Milestone']['start_date'];
										$proAct['end_date'] = $m['Milestone']['end_date'];
										$proAct['sequence'] = 0;
										$proAct['user_id'] = $pr['user_id'];
										$proAct['branchid'] = $this->Session->read('User.branchid');
										$proAct['deapartmentid'] = $this->Session->read('User.deapartmentid');
										$proAct['publish'] = $this->request->data['Project']['publish'];
										$proAct['soft_delete'] = 0;
										$this->Project->ProjectResource->ProjectActivity->create();
										$this->Project->ProjectResource->ProjectActivity->save($proAct,false);

									}
									
								}

							}

							foreach ($m['ProjectEstimate'] as $pe) {
								if($pe['cost'] > 0){
									$costEstimate['ProjectEstimate']['project_id'] = $this->Project->id;
									$costEstimate['ProjectEstimate']['milestone_id'] = $this->Project->Milestone->id;
									$costEstimate['ProjectEstimate']['cost'] = $pe['cost'];
									$costEstimate['ProjectEstimate']['details'] = $pe['details'];
									$costEstimate['ProjectEstimate']['cost_category_id'] = $pe['cost_category_id'];
									$costEstimate['ProjectEstimate']['publish'] = $this->request->data['Project']['publish'];
									$this->Project->ProjectEstimate->create();
									$this->Project->ProjectEstimate->save($costEstimate,false);	
								}
								
							}


							$this->_add_files($this->Project->id,$this->Project->Milestone->id,$m['File']);
						}
					}
					
				}


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The project has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Project->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.'));
			}
		}
		
		$branches = $this->Project->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$employees = $preparedBies = $approvedBies = $this->Project->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$createdBies = $modifiedBies = $this->Project->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$customers = $this->Project->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$currencies = $this->Project->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
		$deliverableUnits = $this->Project->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));

		$count = $this->Project->find('count');
		$published = $this->Project->find('count',array('conditions'=>array('Project.publish'=>1)));
		$unpublished = $this->Project->find('count',array('conditions'=>array('Project.publish'=>0)));
		
		$costCategories = $this->Project->ProjectEstimate->CostCategory->find('list',array('order'=>array('CostCategory.name'=>'ASC'),'conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		
		$this->set('currentStatuses',$this->Project->customArray['currentStatuses']);
		$this->set('weekends',$this->Project->customArray['weekends']);
		$this->set(compact('customers', 'employees', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','deliverableUnits','currencies','count','published','unpublished','costCategories'));

	}





/**
 * add method
 *
 * @return void
 */
	public function add() {
	
	// 	if($this->_show_approvals()){
	// 		$this->loadModel('User');
	// 		$this->User->recursive = 0;
	// 		$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_approvar'=>1)));
	// 		$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
	// 	}
		
	// 	if ($this->request->is('post')) {
 //                        $this->request->data['Project']['system_table_id'] = $this->_get_system_table_id();
	// 		$this->Project->create();
	// 		if ($this->Project->save($this->request->data)) {

	// 			if($this->_show_approvals()){
	// 				$this->loadModel('Approval');
	// 				$this->Approval->create();
	// 				$this->request->data['Approval']['model_name']='Project';
	// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
	// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
	// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['record']=$this->Project->id;
	// 				$this->Approval->save($this->request->data['Approval']);
	// 			}
	// 			$this->Session->setFlash(__('The project has been saved'));
	// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Project->id));
	// 			else $this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The project could not be saved. Please, try again.'));
	// 		}
	// 	}
	// 	$employees = $this->Project->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
	// 	$branches = $this->Project->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
	// 	$userSessions = $this->Project->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
	// 	$masterListOfFormats = $this->Project->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	// 	$companies = $this->Project->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	// 	$preparedBies = $this->Project->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	// 	$approvedBies = $this->Project->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	// 	$createdBies = $this->Project->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	// 	$modifiedBies = $this->Project->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	// 			$this->set(compact('employees', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	// $count = $this->Project->find('count');
	// $published = $this->Project->find('count',array('conditions'=>array('Project.publish'=>1)));
	// $unpublished = $this->Project->find('count',array('conditions'=>array('Project.publish'=>0)));
		
	// $this->set(compact('count','published','unpublished'));

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Project->exists($id)) {
			throw new NotFoundException(__('Invalid project'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
				$this->request->data[$this->modelClass]['publish'] = 0;
      		}
						
			$this->request->data['Project']['system_table_id'] = $this->_get_system_table_id();
			// unset($this->request->data['Project']['end_date']);
			// $dateRange = explode('-', $this->request->data['Project']['start_date']);
			// $start_date = rtrim(ltrim($dateRange[0]));
			// $end_date = rtrim(ltrim($dateRange[1]));
			
			$this->request->data['Project']['start_date'] = date('Y-m-d',strtotime($this->request->data['Project']['start_date']));
			$this->request->data['Project']['end_date'] = date('Y-m-d',strtotime($this->request->data['Project']['end_date']));

			$this->request->data['Project']['employee_id'] = json_encode($this->request->data['Project']['employee_id']);
			$this->request->data['Project']['team_leader_id'] = json_encode($this->request->data['Project']['team_leader_id']);
			$this->request->data['Project']['project_leader_id'] = json_encode($this->request->data['Project']['project_leader_id']);
			$this->request->data['Project']['weekends'] = json_encode($this->request->data['Project']['weekends']);
			
			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;

			if ($this->Project->save($this->request->data)) {

				$milestones = $this->Project->Milestone->find('all',array('conditions'=>array('Milestone.project_id'=>$this->request->data['Project']['id']),'recursive'=>-1));
				if($milestones){
					foreach($milestones as $milestone){
						$milestone['Milestone']['currency_id'] = $this->request->data['Project']['currency_id'];
						$this->Project->Milestone->create();
						$this->Project->Milestone->save($milestone,false);
					}
				}


				foreach ($this->request->data['ProjectResource'] as $pr) {
					if($pr['id'])$projectResource['ProjectResource']['id'] = $pr['id'];
					$projectResource['ProjectResource']['project_id'] = $this->Project->id;
					$projectResource['ProjectResource']['user_id'] = $pr['user_id'];
					$projectResource['ProjectResource']['mandays'] = $pr['mandays'];
					$projectResource['ProjectResource']['resource_cost'] = $pr['resource_cost'];
					$projectResource['ProjectResource']['resource_sub_total'] = $pr['resource_sub_total'];
					$projectResource['ProjectResource']['prepared_by'] = $this->request->data['Project']['prepared_by'];
					$projectResource['ProjectResource']['publish'] = $this->request->data['Project']['publish'];
					$this->Project->ProjectResource->create();
					$this->Project->ProjectResource->save($projectResource,false);
				}

				$this->Project->ProjectEstimate->deleteAll(array('ProjectEstimate.project_id'=>$this->request->data['Project']['id']));
				foreach ($this->request->data['ProjectEstimate'] as $pe) {
					if($pe['cost'] > 0){
						// if($pe['id'])$costEstimate['ProjectEstimate']['id'] = $pe['id'];
						$costEstimate['ProjectEstimate']['project_id'] = $this->request->data['Project']['id'];
						$costEstimate['ProjectEstimate']['cost'] = $pe['cost'];
						$costEstimate['ProjectEstimate']['details'] = $pe['details'];
						$costEstimate['ProjectEstimate']['cost_category_id'] = $pe['cost_category_id'];
						$costEstimate['ProjectEstimate']['publish'] = $this->request->data['Project']['publish'];
						$this->Project->ProjectEstimate->create();
						$this->Project->ProjectEstimate->save($costEstimate,false);
					}
				}

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Project.' . $this->Project->primaryKey => $id));
			$this->request->data = $this->Project->find('first', $options);
		}
		
		$branches = $this->Project->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$employees = $preparedBies = $approvedBies = $this->Project->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$createdBies = $modifiedBies = $this->Project->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$customers = $this->Project->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$currencies = $this->Project->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
		$deliverableUnits = $this->Project->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));

		$count = $this->Project->find('count');
		$published = $this->Project->find('count',array('conditions'=>array('Project.publish'=>1)));
		$unpublished = $this->Project->find('count',array('conditions'=>array('Project.publish'=>0)));
		
		$costCategories = $this->Project->ProjectEstimate->CostCategory->find('list',array('order'=>array('CostCategory.name'=>'ASC'),'conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		
		$this->set('currentStatuses',$this->Project->customArray['currentStatuses']);
		$this->set('weekends',$this->Project->customArray['weekends']);
		$this->set(compact('customers', 'employees', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','deliverableUnits','currencies','count','published','unpublished','costCategories'));


		$pm = array('52979a51-e380-4c21-800e-26500a000005','52979a51-bf58-4fff-bed5-26500a000005','5ff4bb05-ce1c-46d2-900a-17b6db1e6cf9','5ff4bb78-c420-4076-8fe3-1956db1e6cf9');
		$projectManagers = $this->Project->PreparedBy->find('list',array('conditions'=>array('PreparedBy.designation_id'=>$pm)));

		$tl = array('52979a51-6660-493f-aff6-26500a000005','5a7978f5-65f4-4db5-8cd7-0205db1e6cf9','5ff4baa4-0d34-4d45-886e-1957db1e6cf9','5fd20574-bdac-4b77-95e3-7a77db1e6cf9');
		$teamLeaders = $this->Project->PreparedBy->find('list',array('conditions'=>array('PreparedBy.designation_id'=>$tl)));

		$this->set(compact('projectManagers','teamLeaders'));
		// Configure::Write('debug',1);
		// debug($projectManagers);
		// exit;

	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->Project->exists($id)) {
			throw new NotFoundException(__('Invalid project'));
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
			
			// unset($this->request->data['Project']['end_date']);
			// $dateRange = explode('-', $this->request->data['Project']['start_date']);
			// $start_date = rtrim(ltrim($dateRange[0]));
			// $end_date = rtrim(ltrim($dateRange[1]));
			
			// $this->request->data['Project']['start_date'] = date('Y-m-d',strtotime($start_date));
			// $this->request->data['Project']['end_date'] = date('Y-m-d',strtotime($end_date));

			$this->request->data['Project']['start_date'] = date('Y-m-d',strtotime($this->request->data['Project']['start_date']));
			$this->request->data['Project']['end_date'] = date('Y-m-d',strtotime($this->request->data['Project']['end_date']));

			$this->request->data['Project']['employee_id'] = json_encode($this->request->data['Project']['employee_id']);
			$this->request->data['Project']['team_leader_id'] = json_encode($this->request->data['Project']['team_leader_id']);
			$this->request->data['Project']['project_leader_id'] = json_encode($this->request->data['Project']['project_leader_id']);
			$this->request->data['Project']['weekends'] = json_encode($this->request->data['Project']['weekends']);

			if ($this->Project->save($this->request->data)) {
				foreach ($this->request->data['ProjectResource'] as $pr) {
					if($pr['id'])$projectResource['ProjectResource']['id'] = $pr['id'];
					$projectResource['ProjectResource']['project_id'] = $this->Project->id;
					$projectResource['ProjectResource']['user_id'] = $pr['user_id'];
					$projectResource['ProjectResource']['mandays'] = $pr['mandays'];
					$projectResource['ProjectResource']['resource_cost'] = $pr['resource_cost'];
					$projectResource['ProjectResource']['resource_sub_total'] = $pr['resource_sub_total'];
					$projectResource['ProjectResource']['prepared_by'] = $this->request->data['Project']['prepared_by'];
					$projectResource['ProjectResource']['publish'] = $this->request->data['Project']['publish'];
					$this->Project->ProjectResource->create();
					$this->Project->ProjectResource->save($projectResource,false);
				}

				$this->Project->ProjectEstimate->deleteAll(array('ProjectEstimate.project_id'=>$this->request->data['Project']['id']));
				foreach ($this->request->data['ProjectEstimate'] as $pe) {
					if($pe['cost'] > 0){
						// if($pe['id'])$costEstimate['ProjectEstimate']['id'] = $pe['id'];
						$costEstimate['ProjectEstimate']['project_id'] = $this->request->data['Project']['id'];
						$costEstimate['ProjectEstimate']['cost'] = $pe['cost'];
						$costEstimate['ProjectEstimate']['details'] = $pe['details'];
						$costEstimate['ProjectEstimate']['cost_category_id'] = $pe['cost_category_id'];
						$costEstimate['ProjectEstimate']['publish'] = $this->request->data['Project']['publish'];
						$this->Project->ProjectEstimate->create();
						$this->Project->ProjectEstimate->save($costEstimate,false);
					}
				}

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Project.' . $this->Project->primaryKey => $id));
			$this->request->data = $this->Project->find('first', $options);
		}

		$branches = $this->Project->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$employees = $preparedBies = $approvedBies = $this->Project->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$createdBies = $modifiedBies = $this->Project->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$customers = $this->Project->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$currencies = $this->Project->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
		$deliverableUnits = $this->Project->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));

		$count = $this->Project->find('count');
		$published = $this->Project->find('count',array('conditions'=>array('Project.publish'=>1)));
		$unpublished = $this->Project->find('count',array('conditions'=>array('Project.publish'=>0)));
		
		$costCategories = $this->Project->ProjectEstimate->CostCategory->find('list',array('order'=>array('CostCategory.name'=>'ASC'),'conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		
		$this->set('currentStatuses',$this->Project->customArray['currentStatuses']);
		$this->set('weekends',$this->Project->customArray['weekends']);
		$this->set(compact('customers', 'employees', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','deliverableUnits','currencies','count','published','unpublished','costCategories'));
	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {

		// echo "here";
		// exit;


		$this->Project->id = $id;
		if (!$this->Project->exists()) {
			throw new NotFoundException(__('Invalid project'));
		}
		$this->request->onlyAllow('post', 'delete');

		// $deletearray = array_keys($this->Project->hasMany);

		$this->loadModel('Approval');
		$childs = $this->Project->hasMany;
		// debug($childs);
		// exit;
		foreach ($childs as $model => $values) {
			debug($values['foreignKey']);
			if($values['foreignKey'] == 'new_project_id')$prokey = 'current_project_id';
			else $prokey = 'project_id';
			
			$this->loadModel($model);
			
			$recs = $this->$model->find('all',array(
				'recursive'=>-1,
				'conditions'=>array($model.'.'.$prokey=>$id)
			));
			foreach ($recs as $rec) {
				$data[$model]['id'] = $rec[$model]['id'];
				$data[$model]['soft_delete'] = 1;
				$data[$model]['publish'] = 0;
				$this->$model->save($data,false);

				// delete approvals
				$appRecords = $this->Approval->find('all',array(
					'recursive'=>-1,
					'conditions'=>array(
						'Approval.model_name'=>$model,
						'Approval.record'=>$rec[$model]['id'],
					)
				));
				foreach ($appRecords as $appRecord) {
					$this->Approval->deleteAll(array('Approval.id'=>$appRecord['Approval']['id']));
				}

			}				
		}

		if ($this->Project->deleteAll(array('Project.id'=>$id))) {			
			
			$this->Session->setFlash(__('Project deleted'));
			$this->redirect(array('action' => 'index'));
		}else{
			$this->Session->setFlash(__('Project was not deleted'));
			$this->redirect(array('action' => 'index'));	
		}
		
	}
   
	public function deldel($id = null) {
		// Configure::write('debug',1);
		// debug($id);
		
		$this->loadModel('ProjectResource');
		$this->ProjectResource->delete(array('id'=>$id));
		echo "Deleted";
		exit;
	}

       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $parent_id = NULL) {
			// Configure::write('debug',1);
			$this->loadModel('Approval');
			$childs = $this->Project->hasMany;
			// debug($childs);
			// exit;
			foreach ($childs as $model => $values) {
				debug($values['foreignKey']);
				if($values['foreignKey'] == 'new_project_id')$prokey = 'current_project_id';
				else $prokey = 'project_id';
				
				$this->loadModel($model);
				
				$recs = $this->$model->find('all',array(
					'recursive'=>-1,
					'conditions'=>array($model.'.'.$prokey=>$id)
				));

				debug($recs);

				foreach ($recs as $rec) {
					// $data[$model]['id'] = $rec[$model]['id'];
					// $data[$model]['soft_delete'] = 1;
					// $data[$model]['publish'] = 0;
					// $this->$model->save($data,false);

					// delete approvals
					$appRecords = $this->Approval->find('all',array(
						'recursive'=>-1,
						'conditions'=>array(
							'Approval.model_name'=>$model,
							'Approval.record'=>$rec[$model]['id'],
						)
					));

					foreach ($appRecords as $appRecord) {
						debug($appRecord);
						$this->Approval->deleteAll(array('Approval.id'=>$appRecord['Approval']['id']));
					}
					$this->$model->deleteAll(array($model.'.id'=>$rec[$model]['id']));
				}				
			}

            $model_name = $this->modelClass;
            if(!empty($id)){
    
            // delete everything 
	        $this->Project->deleteAll(array('Project.id'=>$id));
            // exit;
            // mark as soft_delete	

            // $data['id'] = $id;
            // $data['soft_delete'] = 1;
            // $data['publish'] = 0;
            // $model_name=$this->modelClass;
            // $this->$model_name->save($data);
    }
    $this->redirect(array('action' => 'index'));
     
    
	}
 
	
	
	
	public function report(){
		
		$result = explode('+',$this->request->data['projects']['rec_selected']);
		$this->Project->recursive = 1;
		$projects = $this->Project->find('all',array('Project.publish'=>1,'Project.soft_delete'=>1,'conditions'=>array('or'=>array('Project.id'=>$result))));
		$this->set('projects', $projects);
		
		$customers = $this->Project->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$employees = $this->Project->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$branches = $this->Project->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$userSessions = $this->Project->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
		$masterListOfFormats = $this->Project->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Project->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Project->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Project->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Project->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Project->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('customers', 'employees', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'employees', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function add_resource(){
		$x = $this->request->params['pass']['1'] + 1;
		$this->set('x',$x);
		$key = $this->request->params['pass']['0'];
		$this->set('key',$key);
	}


	public function projectdates($id = null){
		// Configure::write('debug',1);
		$this->autoRender = false;
		$this->Project->PurchaseOrder->virtualFields = array(
			'in'=>'select SUM(total) from purchase_order_details where `purchase_order_details`.`purchase_order_id` LIKE PurchaseOrder.id AND PurchaseOrder.type = 0',
			'out'=>'select SUM(total) from purchase_order_details where `purchase_order_details`.`purchase_order_id` LIKE PurchaseOrder.id AND PurchaseOrder.type = 1'
		);

		$this->Project->virtualFields = array(
			'milestone_cost'=>'select sum(estimated_cost) from milestones where milestones.project_id = Project.id AND milestones.soft_delete = 0',
			'activities_cost'=>'select sum(estimated_cost) from project_activities where project_activities.project_id = Project.id AND project_activities.soft_delete = 0',
			'resource_cost'=>'select sum(resource_sub_total) from project_resources where project_resources.project_id = Project.id AND project_resources.soft_delete = 0',
			'resource_hours'=>'select sum(mandays) from project_resources where project_resources.project_id = Project.id AND project_resources.soft_delete = 0',
			'timesheet_cost'=>'select sum(total_cost) from project_timesheets where project_timesheets.project_id = Project.id AND project_timesheets.soft_delete = 0',
			'timesheet_hours'=>'select sum(total) from project_timesheets where project_timesheets.project_id = Project.id AND project_timesheets.soft_delete = 0',
			'other_estimate'=>'select sum(cost) from project_estimates where project_estimates.project_id = Project.id AND project_estimates.soft_delete = 0',
			'total_resources'=>'SELECT count(DISTINCT user_id) FROM `project_resources` where `project_resources`.`project_id` LIKE "' . $id .'"',
			'po_cost_out'=>'select SUM(total) 
				from purchase_order_details 
				RIGHT JOIN `purchase_orders` ON `purchase_order_details`.`purchase_order_id` = `purchase_orders`.`id` 
					AND `purchase_orders`.`type` = 1 					
					AND `purchase_orders`.`soft_delete` = 0 
					AND `purchase_orders`.`project_id` = "' . $id .'"',
			'po_cost_customer'=>'select SUM(total) 
				from purchase_order_details 
				RIGHT JOIN `purchase_orders` ON `purchase_order_details`.`purchase_order_id` = `purchase_orders`.`id` 
					AND `purchase_orders`.`type` = 0 
					AND `purchase_orders`.`soft_delete` = 0 
					AND `purchase_orders`.`project_id` = "' . $id .'"',
			'payment_received'=>'select sum(amount_received) from project_payments where project_payments.project_id LIKE "'.$id.'"',
		);

		$project = $this->Project->find('first',array(
				'conditions'=>array('Project.id'=>$id),
				'fields'=>array('Project.id','Project.project_cost','Project.estimated_project_cost','Project.start_date','Project.end_date','Project.current_status',
					'Project.title',
					'Project.milestone_cost',
					'Project.activities_cost',
					'Project.resource_cost',
					'Project.timesheet_cost',
					'Project.po_cost_out',
					'Project.po_cost_customer',	
					'Project.resource_hours',
					'Project.timesheet_hours',
					'Project.other_estimate',
					'Project.total_resources',
					'Project.payment_received',
				),
				'contain'=>array(
					'Milestone'=>array(
						'fields'=>array('Milestone.project_id','Milestone.start_date','Milestone.end_date','Milestone.estimated_cost')
					),
					'ProjectActivity'=>array(
						'fields'=>array('ProjectActivity.project_id','ProjectActivity.milestone_id', 'ProjectActivity.start_date','ProjectActivity.end_date','ProjectActivity.estimated_cost')
					),					
					'ProjectTimesheet'=>array(						
						'fields'=>array('ProjectTimesheet.project_id','ProjectTimesheet.project_activity_id', 'ProjectTimesheet.start_time','ProjectTimesheet.end_time','ProjectTimesheet.total_cost')
					),
					'PurchaseOrder'=>array(
						'fields'=>array('PurchaseOrder.id', 'PurchaseOrder.project_id','PurchaseOrder.project_activity_id', 'PurchaseOrder.milestone_id','PurchaseOrder.order_date',
							'PurchaseOrder.type','PurchaseOrder.in','PurchaseOrder.out'
						)
					),
					'ProjectResource'=>array(
						'fields'=>array('ProjectResource.id', 'ProjectResource.project_id','ProjectResource.resource_sub_total'
						)
					),
					'ProjectEstimate'=>array(
						'fields'=>array('ProjectEstimate.id', 'ProjectEstimate.project_id','ProjectEstimate.cost'
						)
					),
					'Customer'=>array(
						'fields'=>array('Customer.id', 'Customer.name'
						)
					)
				)
			)
		);

		// Configure::write('debug',1);
		// debug($project);
		return $project;
		exit;

	}

	public function addmilestone($key = null,$value = null,$start_date = null, $end_date = null){
		$this->set('key',$this->request->params['pass'][0]);
		$this->set('value',base64_decode($this->request->params['pass'][1]));

		$this->loadModel('CostCategory');
		$costCategories = $this->CostCategory->find('list',array('order'=>array('CostCategory.name'=>'ASC'), 'conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		$this->set('costCategories',$costCategories);

		$milestoneTypes = $this->Project->Milestone->MilestoneType->find('list',array('conditions'=>array('MilestoneType.publish'=>1,'MilestoneType.soft_delete'=>0)));
		$this->set('milestoneTypes',$milestoneTypes);

	}

	public function updatemilestone($key = null,$value = null,$start_date = null, $end_date = null){
		$this->set('key',$this->request->params['pass'][0]);
		$this->set('value',base64_decode($this->request->params['pass'][1]));

		$this->loadModel('CostCategory');
		$costCategories = $this->CostCategory->find('list',array('order'=>array('CostCategory.name'=>'ASC'), 'conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
		$this->set('costCategories',$costCategories);

		$deliverableUnits = $this->Project->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));
		$this->set('deliverableUnits',$deliverableUnits);


		$milestoneTypes = $this->Project->Milestone->MilestoneType->find('list',array('conditions'=>array('MilestoneType.publish'=>1,'MilestoneType.soft_delete'=>0)));
		$this->set('milestoneTypes',$milestoneTypes);

		$currencies = $this->Project->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
		$this->set('currencies',$currencies);

		$projectCurrency = $this->Project->find('first',array('recursive'=>-1,'fields'=>array('Project.id','Project.currency_id'), 'conditions'=>array('Project.id'=>$this->request->params['named']['project_id'])));
		$this->set('projectCurrency',$projectCurrency['Project']['currency_id']);

	}

	public function updatealert(){

	}

	public function delete_childrecs(){
		// Configure::write('debug',1);
		// debug($this->request->params['named']);
		// exit;


		$model = $this->request->params['named']['model'];

		if($model == 'ProjectProcessPlan'){
			$this->loadModel('ProjectProcessPlan');
			$this->Project->ProjectFile->virtualFields = array(
				'processes' => 'select count(*) from file_processes where file_processes.project_file_id LIKE ProjectFile.id and file_processes.project_process_plan_id LIKE "'.$this->request->params['named']['id'].'"'
			);
			$files = $this->Project->ProjectFile->find('count',array(
				'conditions'=>array(
					'ProjectFile.processes > '=> 0,
					'ProjectFile.project_process_plan_id'=>$this->request->params['named']['id']
				)
			));
			if($files == 0){
				$this->Session->setFlash(__('Record is deleted'));
				$this->ProjectProcessPlan->delete(array('ProjectProcessPlan.id'=>$this->request->params['named']['id']));
				$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->params['named']['project_id']));
			}else{
				$this->Session->setFlash(__('Record can not be deleted'));
				$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->params['named']['project_id']));
			}
		}	

		if($model == 'ProjectOverallPlan'){



			$overallplan = $this->Project->ProjectFile->ProjectProcessPlan->ProjectOverallPlan->find('first',array(
				'recursive'=>-1,
				'conditions'=>array('ProjectOverallPlan.id'=>$this->request->params['named']['id'])
			));


			$processes = $this->Project->ProjectFile->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'conditions'=>array('ProjectProcessPlan.project_overall_plan_id'=>$this->request->params['named']['id'])));

			// Configure::write('debug',1);
			debug($processes);
			// $x = 0;
			// exit;
			foreach ($processes as $key => $value) {
				$or .= 'file_processes.project_process_plan_id LIKE "' . $key . '" OR ';
			}

			$or = substr($or, 0 , -3);

			debug($or);
			// exit;
			if($or){

				// $this->Project->ProjectFile->virtualFields = array(
					$vf['op'] = 'select count(*) from file_processes where file_processes.project_file_id LIKE ProjectFile.id and ' . $or;
			}else{
				$vf['op'] = 'select count(*) from file_processes where file_processes.project_file_id LIKE ProjectFile.id';
			}
			// $vf = array('op'=> 'select count(*) from file_processes where file_processes.project_process_plan_id LIKE  "'.array_keys($processes).'" ');
				// );
				// $x++;
			// }
			
			$this->Project->ProjectFile->virtualFields = $vf;
			
			// debug($vf);
			// debug($overallplan);
			// exit;
			// if there are files added under this over plan .. do not delete anything
			// $this->Project->ProjectFile->virtualFields = array(
			// 	'op'=>''
			// );
			$projectFiles = $this->Project->ProjectFile->find('count',array(
				'recursive'=>-1,
				'conditions'=>array(
					'ProjectFile.op > '=>0,
					'ProjectFile.milestone_id'=>$overallplan['ProjectOverallPlan']['milestone_id'],
					'ProjectFile.project_id'=>$overallplan['ProjectOverallPlan']['project_id'],
				)
			));
			// debug($projectFiles);
			// exit;

			if($projectFiles == 0){
				// then delete entire things .. all files , all processes under the plan and all resource allocation 
				$this->loadModel('ProjectProcessPlan');
				$this->loadModel('ProjectProcessPlan');
				$this->loadModel('FileProcess');
				$this->loadModel('ProjectFile');
				$processes = $this->ProjectProcessPlan->find('list',array(
					'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
					'conditions'=>array('ProjectProcessPlan.project_overall_plan_id'=>$this->request->params['named']['id'])));

				// Configure::write('debug',1);
				debug($processes);

				foreach ($processes as $key => $value) {
					$fileProcesses = $this->FileProcess->find('all',array(
						'conditions'=>array('FileProcess.project_process_plan_id'=>$key)
					));
					
					foreach ($fileProcesses as $fileProcesse) {
						$files[$fileProcesse['FileProcess']['project_file_id']] = $fileProcesse['FileProcess']['project_file_id'];
						foreach ($files as $fkey => $fvalue) {
							$this->ProjectFile->delete(array('ProjectFile.id'=>$fkey));
						}
						$this->FileProcess->delete(array('FileProcess.id'=>$fileProcesse['FileProcess']['id']));
						// debug($files);	
					}

					
					debug($files);
					$this->Session->setFlash(__('Record is deleted'));
		
				}	

				$this->loadModel($model);
				$this->$model->delete(array($model.'.id'=>$this->request->params['named']['id']));
				$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->params['named']['project_id']));		
			}else{
				$this->Session->setFlash(__('This Record can not be deleted'));
				$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->params['named']['project_id']));		
			}
			$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->params['named']['project_id']));		

			
		}else{
			$this->loadModel($model);
			$this->$model->delete(array($model.'.id'=>$this->request->params['named']['id']));
		}
		// exit;
		
		
		$this->_updateprojectestimate($this->request->params['named']['project_id']);
		$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->params['named']['project_id']));		
		

	}

	public function deletemilestone($milestone_id = null){
		// Configure::write('debug',1);
		// debug($milestone_id);
		// exit;
		$this->loadModel('Milestone');
		$this->loadModel('Approval');
		$delarray = array('ProjectActivity', 'ProjectResource','PurchaseOrder','ProjectEstimate',
			'ProjectOverallPlan','ProjectProcessPlan','ProjectFile','ProjectFileEmployee','ProjectEmployee',
			'ProjectPayment','Invoice','ProjectChecklist','FileErrorMaster','FileProcess','ProjectQuery'
		);
		foreach ($delarray as $model) {
			$this->loadModel($model);
			debug($model);
			// if($values['foreignKey'] == 'new_project_id')$prokey = 'current_project_id';
			// else $prokey = 'project_id';
			$prokey = 'milestone_id';
			$recs = $this->$model->find('all',array(
				'recursive'=>-1,
				'conditions'=>array($model.'.'.$prokey=>$milestone_id)
			));
			
			// debug($recs);
			
			foreach ($recs as $rec) {
				// $data[$model]['id'] = $rec[$model]['id'];
				// $data[$model]['soft_delete'] = 1;
				// $data[$model]['publish'] = 0;
				// $this->$model->save($data,false);

				// delete approvals
				$appRecords = $this->Approval->find('all',array(
					'recursive'=>-1,
					'conditions'=>array(
						'Approval.model_name'=>$model,
						'Approval.record'=>$rec[$model]['id'],
					)
				));
				foreach ($appRecords as $appRecord) {
					$this->Approval->deleteAll(array('Approval.id'=>$appRecord['Approval']['id']));
				}

				$this->$model->deleteAll(array($model.'.id'=>$rec[$model]['id']));

			}
			// $this->loadModel($model);
			// debug($model);
			// $this->$model->deleteAll(array($model.'.milestone_id'=>$milestone_id));			
		}
		// exit;
		$this->Milestone->delete(array('Milestone.id'=>$milestone_id));
		$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->params['named']['project_id']));		
	}

	public function milestonewise($project_id = null){
		// Configure::write('debug',1);
		$this->autoRender = false;
		$this->Project->PurchaseOrder->virtualFields = array(
			'in'=>'select SUM(total) from purchase_order_details where `purchase_order_details`.`purchase_order_id` LIKE PurchaseOrder.id AND PurchaseOrder.type = 0',
			'out'=>'select SUM(total) from purchase_order_details where `purchase_order_details`.`purchase_order_id` LIKE PurchaseOrder.id AND PurchaseOrder.type = 1'
		);

		$this->Project->Milestone->virtualFields = array(
			'tcost' => 'SELECT SUM(total_cost) FROM `project_timesheets` where `project_timesheets`.`milestone_id` LIKE Milestone.id',
			'ecost' => 'SELECT SUM(cost) FROM `project_estimates` where `project_estimates`.`milestone_id` LIKE Milestone.id',
			'rcost' => 'SELECT SUM(resource_sub_total) FROM `project_resources` where `project_resources`.`milestone_id` LIKE Milestone.id',
		);
		$milestones = $this->Project->Milestone->find('all',array(
			'fields'=>array(
				'Milestone.id',
				'Milestone.title',
				'Milestone.project_id',
				'Milestone.estimated_cost',
				'Milestone.start_date',
				'Milestone.end_date',
				'Milestone.current_status',
				'Milestone.tcost',
				'Milestone.ecost',
				'Milestone.rcost',
			),
			'conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0,'Milestone.project_id'=>$project_id),
			'contain'=>array(
				'ProjectActivity'=>array(
					'fields'=>array('ProjectActivity.project_id','ProjectActivity.milestone_id', 'ProjectActivity.start_date','ProjectActivity.end_date','ProjectActivity.estimated_cost')
				),					
				// 'ProjectTimesheet'=>array(						
				// 	'fields'=>array('ProjectTimesheet.project_id','ProjectTimesheet.project_activity_id', 'ProjectTimesheet.start_time','ProjectTimesheet.end_time','ProjectTimesheet.total_cost')
				// ),
				'PurchaseOrder'=>array(
					'fields'=>array('PurchaseOrder.id', 'PurchaseOrder.project_id','PurchaseOrder.project_activity_id', 'PurchaseOrder.milestone_id','PurchaseOrder.order_date',
						'PurchaseOrder.type','PurchaseOrder.in','PurchaseOrder.out'
					)
				),
				// 'ProjectResource'=>array(
				// 	'fields'=>array('ProjectResource.id', 'ProjectResource.project_id','ProjectResource.resource_sub_total','ProjectResource.user_id'
				// 	)
				// ),
				// 'ProjectEstimate'=>array(
				// 	'fields'=>array('ProjectEstimate.id', 'ProjectEstimate.project_id','ProjectEstimate.cost'
				// 	)
				// )
			)
		));
		return $milestones;
		// debug($milestones);
		// exit;
	}

	public function file_timeline(){
		
	}

	public function addpop(){
		$this->set('key',$this->request->params['pass'][0]);
		$this->set('pop',($this->request->params['pass'][1]+1));
		$this->set('cal_type',($this->request->params['pass'][2]));
		$this->set('listOfSoftwares',$this->Project->ProjectProcessPlan->ListOfSoftware->find('list'));
	}


	public function add_new_detailed_plan($project_id = null, $milestone_id = null,$pop = null, $por = null){
		$i = 0;
		// Configure::write('debug',1);
		// debug($milestone_id);
		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1,'conditions'=>array('Milestone.id'=>$milestone_id)));
		$this->set('milestone',$milestone);

		$this->Project->ProjectOverallPlan->virtualFields = array(
			'actual_units'=>'select SUM(unit) from project_files where project_files.milestone_id LIKE "'.$milestone_id.'"'
		);

			$overallPlans = $this->Project->ProjectOverallPlan->find('all',array(
				'conditions'=>array(
					'ProjectOverallPlan.project_id'=>$project_id,
					'ProjectOverallPlan.milestone_id'=>$milestone['Milestone']['id']
				),
				'recursive'=>0,
			));
			// Configure::write('debug',1);
			$p = 0;
			foreach ($overallPlans as $plan) {
				// debug($plan);
				$planResult[$p] = $plan;
				$planResult[$p]['DetailedPlan'] = $this->Project->ProjectOverallPlan->ProjectProcessPlan->find('all',array(
					'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
					'conditions'=>array('ProjectProcessPlan.soft_delete'=>0, 'ProjectProcessPlan.project_overall_plan_id'=>$plan['ProjectOverallPlan']['id'])
				));

				// debug($planResult[$p]);
				$existingprocesses[$plan['ProjectOverallPlan']['id']] = $this->Project->ProjectProcessPlan->find('list',array(
					'conditions'=>array(
						'ProjectProcessPlan.publish'=>1,
						'ProjectProcessPlan.project_overall_plan_id'=>$plan['ProjectOverallPlan']['id'],
						'ProjectProcessPlan.soft_delete'=>0), 
					'fields'=>array(
						'ProjectProcessPlan.id','ProjectProcessPlan.process')
				));

				$p++;
			}

			// $milestone['Plan']  = $planResult;
			$this->set('planResult',$planResult);
			$this->set(array('pop'=>$pop,'por'=>$por));

			$this->set('existingprocesses',$existingprocesses);

			$this->set('listOfSoftwares',$this->Project->ProjectProcessPlan->ListOfSoftware->find('list',array('conditions'=>array('ListOfSoftware.soft_delete'=>0))));

			$this->set('PublishedEmployeeList',$this->_get_employee_list());
			$this->set('PublishedDepartmentList',$this->_get_department_list());
			$this->set('PublishedDesignationList',$this->_get_designation_list());
	}

	public function addpor(){
		// Configure::write('debug',1);
		// debug($this->request->params);
		// echo "<". $this->request->params['named']['end'];
		// $this->set('key',$this->request->params['pass'][0]);
		$this->set('pop',($this->request->params['pass'][0]));
		$this->set('por',(($this->request->params['pass'][1]+1)));
		$this->set('cal_type',($this->request->params['pass'][2]));
		$this->set('start_date',($this->request->params['named']['start']));
		$this->set('end_date',($this->request->params['named']['end']));
		$this->set('project_id',($this->request->params['named']['project_id']));
		$this->set('milestone_id',($this->request->params['named']['milestone_id']));
		$this->set('op',($this->request->params['named']['op']));
		$this->set('om',($this->request->params['named']['overall_metrics']));
		$this->set('bunits',($this->request->params['named']['bunits']));
		$this->set('bers',($this->request->params['named']['bers']));
		$this->set('est_units',($this->request->params['named']['est_units']));
		$this->set('listOfSoftwares',$this->Project->ProjectProcessPlan->ListOfSoftware->find('list'));

		$milestone = $this->Project->Milestone->find('first',array('recursive'=>-1),array('Milestone.id'=>$this->request->params['named']['milestone_id']));
		$this->set('milestone',$milestone);

	}


	public function all_members($project_id = null,$start_date = null,$end_date = null){
		// echo $project_id;
		// echo $start_date;
		// echo $end_date;
		$this->loadModel('Employee');
		
		$this->Employee->virtualFields = array(
			'pro_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id and `project_employees`.`project_id` = "'. $project_id .'" LIMIT 1',

			'pro_emp_id'=>'select DISTINCT(`project_employees`.`id`) from `project_employees` WHERE `project_employees`.`project_id` !=  "'. $project_id .'" AND `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
			
			'locked_from'=>'select DISTINCT(`project_employees`.`start_date`) from `project_employees` WHERE `project_employees`.`project_id` !=  "'. $project_id .'" AND `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
			
			'locked_till'=>'select DISTINCT(`project_employees`.`end_date`) from `project_employees` WHERE `project_employees`.`project_id` !=  "'. $project_id .'" AND `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
			
			'tl'=>'select DISTINCT(`project_resources`.`team_leader_id`) from `project_resources` WHERE `project_resources`.`employee_id` = Employee.id LIMIT 1 ',
			
			'pm'=>'select DISTINCT(`project_resources`.`project_leader_id`) from `project_resources` WHERE `project_resources`.`employee_id` = Employee.id LIMIT 1',
			
			'curr_project'=>'select DISTINCT(`project_employees`.`project_id`) from `project_employees` WHERE `project_employees`.`project_id` !=  "'. $project_id .'" AND `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
			// 'curr_project'=>'select DISTINCT(`projects`.`title`) from `projects` where `projects`.`id` = "'.$project_id.'"'
			// 'curr_project'=>'select DISTINCT(`projects`.`title`) 
			// 	from project_employees 
			// 	LEFT JOIN `projects` ON `project_employees`.`project_id` = `projects`.`id` 
			// 		AND `project_employees`.`employee_id` = Employee.id LIMIT 1',
		);
		// $employees = $this->Employee->find('all',
		// 	array(
		// 		'conditions'=>array(''),
		// 		'recursive'=>0,
		// )
		// );

		$allMembers = $this->Project->ProjectEmployee->Employee->find('all',array(
			'conditions'=>array(
				'Employee.pro_check'=>0
			),
			'order'=>array('Employee.curr_project'=>'DESC','Employee.name'=>'ASC')
		));
		// Configure::write('debug',1);
		// debug($allMembers);
		// exit;
		return $allMembers;

	}

	public function team_members($project_id = null){
		$this->loadModel('Employee');
		$this->Employee->virtualFields = array(
			'locked_till'=>'select DISTINCT(`project_employees`.`end_date`) from `project_employees` where `project_employees`.`employee_id`= Employee.id GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`end_date` DESC',
			'tl'=>'select DISTINCT(`projects`.`team_leader_id`) from `projects` ORDER BY `projects`.`end_date` DESC LIMIT 1',
			'pm'=>'select DISTINCT(`projects`.`employee_id`) from `projects` ORDER BY `projects`.`end_date` DESC LIMIT 1',
		);
		// $employees = $this->Employee->find('all',
		// 	array(
		// 		'conditions'=>array(''),
		// 		'recursive'=>0,
		// )
		// );

		$projectMembers = $this->Project->ProjectEmployee->find('all',array(
			'conditions'=>array('ProjectEmployee.project_id'=>$project_id),	
			'group'=>array('ProjectEmployee.employee_id')		
		));
		// Configure::write('debug',1);
		// debug($projectMembers);
		// exit;
		return $projectMembers;

	}

	public function add_employee_to_project(){
		$this->autoRender = false;
		$this->loadModel('ProjectEmployee');

		// Configure::write('debug',1);
		// debug($this->request->params['named']);
		// exit;
		if($this->request->params['named']['project_id']){
			$projectEmployee = $this->ProjectEmployee->find('first',array(
				'conditions'=>array(
					'ProjectEmployee.project_id'=>$this->request->params['named']['project_id'],
					'ProjectEmployee.employee_id'=>$this->request->params['named']['employee_id'],
				),
				'recursive'=>-1,
				'fields'=>array('ProjectEmployee.id', 'ProjectEmployee.project_id','ProjectEmployee.employee_id')
			));

			$project = $this->Project->find('first',array(
				'recursive'=> -1,
				'fields'=>array('Project.id','Project.start_date','Project.end_date'),
				'conditions'=>array('Project.id'=>$this->request->params['named']['project_id'])
			));

			if($projectEmployee){
				$this->Project->ProjectEmployee->deleteAll(array(
					'ProjectEmployee.employee_id'=>$this->request->params['named']['employee_id'],
					'ProjectEmployee.project_id'=>$this->request->params['named']['project_id']));

				$projectEmployee = false;
			}

			// debug($projectEmployee['ProjectEmployee']['id']);
			if(!$projectEmployee){
				// if($project['Project']['id'] != $this->request->params['named']['project_id']){
					$data['ProjectEmployee']['project_id'] = $this->request->params['named']['project_id'];
					$data['ProjectEmployee']['employee_id'] = $this->request->params['named']['employee_id'];
					$data['ProjectEmployee']['milestone_id'] = $this->request->params['named']['milestone_id'];
					$data['ProjectEmployee']['start_date'] = $project['Project']['start_date'];
					$data['ProjectEmployee']['end_date'] = $project['Project']['end_date'];
					$data['ProjectEmployee']['current_status'] = $project['Project']['current_status'];
					debug($data);
					$this->ProjectEmployee->create();
					if($this->ProjectEmployee->save($data,false)){
					// echo "aaaa";						
						$this->loadModel('ProjectReleaseRequest');
						$this->ProjectReleaseRequest->deleteAll(array('ProjectReleaseRequest.employee_id'=>$this->request->params['named']['employee_id']));
					}
					
				// }
			}else{
				$this->loadModel('ProjectReleaseRequest');
				$this->ProjectReleaseRequest->deleteAll(array('ProjectReleaseRequest.employee_id'=>$this->request->params['named']['employee_id']));
			}
			
			return 'true';
		}else{
			return 'false';
		}
		
	}

	public function get_employee_to_project(){
		$this->autoRender = false;
		$this->loadModel('ProjectResource');

		$projectResources = $this->ProjectResource->find('first',array(
			'conditions'=>array(
				'ProjectResource.milestone_id'=>$milestone_id,
				'ProjectResource.project_id'=>$project_id,
			),
			'recursive'=>-1,
			// 'fields'=>array('projectEmployee.id', 'ProjectEmployee.project_id','ProjectEmployee.employee_id')
		));

		return $projectResources;
		
	}

	public function add_project_resource(){
		$this->autoRender = false;

		if ($this->request->is('post') || $this->request->is('put')) {
			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;
			$fail = 0;
			$data['ProjectResource'] = array_shift(array_shift(array_shift($this->request->data)));
			// $data['ProjectResource'] = $this->request->data[$this->request->data['ProjectResource']]
			
			// debug($data);

			if($data['ProjectResource']['team_leader_id'] != -1 && $data['ProjectResource']['project_leader_id'] != -1 && $data['ProjectResource']['process'] != -1){
				
				$this->loadModel('ProjectResource');
				
				// foreach ($data['ProjectResource']['process'] as $process) {
					$user = $this->ProjectResource->User->find('first',array(
						'conditions'=>array('User.employee_id'=>$data['ProjectResource']['employee_id']),
						'recursive'=>-1,
						'fields'=>array('User.id','User.employee_id')
					));
					$exsting = $this->ProjectResource->find('count',array(
						'conditions'=>array(
							'ProjectResource.process_id'=>$data['ProjectResource']['process'],
							'ProjectResource.employee_id'=>$data['ProjectResource']['employee_id'],
							'ProjectResource.project_id'=>$data['ProjectResource']['project_id'],
							'ProjectResource.milestone_id'=>$data['ProjectResource']['milestone_id'],
						)
					));

					if($exsting > 0){
						$fail = 'Already added';
						return $fail;
					}
						
					if($user){
						$data['ProjectResource']['user_id'] = $user['User']['id'];
						$data['ProjectResource']['process_id'] = $data['ProjectResource']['process'];
						//check existing 
						// debug($data);
						$this->ProjectResource->create();
						
						if($this->ProjectResource->save($data,false)){


							$this->loadModel('ProjectEmployee');

							$projectEmployee = $this->ProjectEmployee->find('first',array(
								'conditions'=>array(
									'ProjectEmployee.project_id'=>$data['ProjectResource']['project_id'],
									'ProjectEmployee.employee_id'=>$data['ProjectResource']['employee_id'],
								),
								'recursive'=>-1,
								'fields'=>array('ProjectEmployee.id', 'ProjectEmployee.project_id','ProjectEmployee.employee_id')
							));

							$project = $this->Project->find('first',array(
								'recursive'=> -1,
								'fields'=>array('Project.id','Project.start_date','Project.end_date'),
								'conditions'=>array('Project.id'=>$data['ProjectResource']['project_id'])
							));

							// debug($projectEmployee['ProjectEmployee']['id']);
							if(!$projectEmployee){
								// if($project['Project']['id'] != $this->request->params['named']['project_id']){
									$pedata['ProjectEmployee']['project_id'] = $data['ProjectResource']['project_id'];
									$pedata['ProjectEmployee']['employee_id'] = $data['ProjectResource']['employee_id'];
									$pedata['ProjectEmployee']['milestone_id'] = $data['ProjectResource']['milestone_id'];
									$pedata['ProjectEmployee']['start_date'] = $project['Project']['start_date'];
									$pedata['ProjectEmployee']['end_date'] = $project['Project']['end_date'];
									$pedata['ProjectEmployee']['current_status'] = $project['Project']['current_status'];
									
									debug($pedata);
									$this->ProjectEmployee->create();
									$this->ProjectEmployee->save($pedata,false);
								// }
							}
								
								


							// assign files
							$x = $this->requestAction(
								array('controller'=>'project_files','action'=>'re_arrange_files',
									$data['ProjectResource']['project_id'],$data['ProjectResource']['milestone_id'],$data['ProjectResource']['employee_id'] ,$data['ProjectResource']['process'],null
							));
// get_file_status($data['ProjectResource']['project_id'], $data['ProjectResource']['milestone_id'],$data['ProjectResource']['employee_id'] ,$data['ProjectResource']['process'],null);
							$fail = "User Added";

						}else{
							$fail = "Adding falied";
						}	
					}else{
						$fail = "User does not exist!";
					}
					
				// }
				
				
			}else{
				$fail = "Incorrect data";
			}
			// echo $fail;
			return $fail;
		}
		
		// debug(array_shift(array_shift(array_shift($this->request->data))));
		exit;
	}

	public function send_release_request($employee_id = null, $project_id = null, $request_from_id = null){
		$this->autoRender = false;
		// Configure::write('debug',1);
		// debug($this->request->params['named']);
		// echo "Released";
		$this->loadModel('ProjectReleaseRequest');
		$data['ProjectReleaseRequest']['current_project_id'] = $this->request->params['named']['current_project_id'];
		$data['ProjectReleaseRequest']['new_project_id'] = $this->request->params['named']['new_project_id'];
		$data['ProjectReleaseRequest']['employee_id'] = $this->request->params['named']['employee_id'];
		$data['ProjectReleaseRequest']['request_from_id'] = $this->request->params['named']['request_from_id'];
		$data['ProjectReleaseRequest']['project_employee_id'] = $this->request->params['named']['project_employee_id'];
		$data['ProjectReleaseRequest']['request_status'] = 0;
		$data['ProjectReleaseRequest']['publish'] = 1;
		$data['ProjectReleaseRequest']['soft_delete'] = 0;

		// delete earlier requests 
		$this->ProjectReleaseRequest->deleteAll(array(
			'ProjectReleaseRequest.new_project_id'=>$this->request->params['named']['new_project_id'],
			'ProjectReleaseRequest.employee_id'=>$this->request->params['named']['employee_id']));

		$this->ProjectReleaseRequest->create();
		if($this->ProjectReleaseRequest->save($data)){
			return 'Request Sent';
		}else{
			return 'Request Failed';
		}
		exit;
	}

	public function update_request(){
		$this->autoRender = false;
		// Configure::write('debug',1);
		// debug($this->request->params['named']);
		// exit;
		// update request table
		$this->loadModel('ProjectReleaseRequest');
		$this->loadModel('Milestone');
		$this->loadModel('ProjectEmployee');
		$this->ProjectReleaseRequest->read($this->request->params['named']['id'],null);
		$this->ProjectReleaseRequest->set(array('id'=>$this->request->params['named']['id'], 'request_status'=>$this->request->params['named']['request_status']));
		if($this->ProjectReleaseRequest->save()){
		
			if($this->request->params['named']['request_status'] == 1){

				$rec = $this->ProjectReleaseRequest->find('first',array(
					'recursive'=>-1,
					'conditions'=>array('ProjectReleaseRequest.id'=>$this->request->params['named']['id'])
				));
				// Configure::write('debug',1);
				// debug($rec);
				// exit;
				$this->loadModel('ProjectFile');
				$assignedFiles = $this->ProjectFile->find('all',array(
					'recursive'=>-1,
					'conditions'=>array(
						'ProjectFile.current_status'=>0,
						'ProjectFile.employee_id'=>$rec['ProjectReleaseRequest']['employee_id'],
						'ProjectFile.project_id'=>$rec['ProjectReleaseRequest']['current_project_id'],
					)
				));
				// debug($assignedFiles);
				foreach ($assignedFiles as $assignedFile) {
					$pros = $this->ProjectFile->FileProcess->find('all',array(
						'conditions'=>array(
							'FileProcess.project_file_id'=>$assignedFile['ProjectFile']['id'],
							'FileProcess.employee_id'=>$rec['ProjectReleaseRequest']['employee_id'],
							'FileProcess.current_status'=>0,
						),
						'recursive'=>-1,
					));
					foreach ($pros as $pro) {
						$this->ProjectFile->FileProcess->create();
						$pro['FileProcess']['current_status']  = 10;
						$pro['FileProcess']['employee_id']  = 'Not Assigned';
						$this->ProjectFile->FileProcess->save($pro,false);
					}

					$this->ProjectFile->create();
					$assignedFile['ProjectFile']['current_status']  = 10;
					$assignedFile['ProjectFile']['employee_id']  = 'Not Assigned';
					// debug($assignedFile);
					$this->ProjectFile->save($assignedFile,false);
				}

				
				// exit;

				// get milestones
				$milestones = $this->Milestone->find('all',array(
					'recursive'=>-1,
					'fields'=>array('Milestone.id'),				
					'conditions'=>array('Milestone.project_id'=>$rec['ProjectReleaseRequest']['new_project_id'])
				));

				$project = $this->Milestone->Project->find('first',array(
					'recursive'=>-1,
					'fields'=>array(
						'Project.id',
						'Project.start_date',
						'Project.end_date',
						'Project.current_status'
					),
					'conditions'=>array('Project.id'=>$rec['ProjectReleaseRequest']['new_project_id'])
				));

				// Configure::Write('debug',1);
				// debug($rec);
				
				if($milestones){
					foreach ($milestones as $milestone) {
					// debug($milestone);

						$exsting = $this->ProjectEmployee->find('count',array(
							'conditions'=>array(
								// 'ProjectResource.process_id'=>$data['ProjectResource']['process'],
								'ProjectEmployee.id'=>$rec['ProjectReleaseRequest']['project_employee_id'],
								// 'ProjectEmployee.employee_id'=>$rec['ProjectReleaseRequest']['employee_id'],
								// 'ProjectEmployee.project_id'=>$rec['ProjectReleaseRequest']['new_project_id'],
								// 'ProjectEmployee.milestone_id'=>$milestone['Milestone']['id'],
							)
						));

						if($exsting > 0){
							$this->ProjectEmployee->deleteAll(
								array(
									'ProjectEmployee.id'=>$rec['ProjectReleaseRequest']['project_employee_id']
									// 'ProjectEmployee.project_id'=>$rec['ProjectReleaseRequest']['current_project_id'],
									// 'ProjectEmployee.employee_id'=>$rec['ProjectReleaseRequest']['employee_id'],
								)
							);
						}
						
						$data['ProjectEmployee']['project_id'] = $rec['ProjectReleaseRequest']['new_project_id'];
						$data['ProjectEmployee']['employee_id'] = $rec['ProjectReleaseRequest']['employee_id'];
						$data['ProjectEmployee']['milestone_id'] = $milestone['Milestone']['id'];
						$data['ProjectEmployee']['start_date'] = $project['Project']['start_date'];
						$data['ProjectEmployee']['end_date'] = $project['Project']['end_date'];
						$data['ProjectEmployee']['current_status'] = $project['Project']['current_status'];
						$data['ProjectEmployee']['publish'] = 1;
						$data['ProjectEmployee']['soft_delete'] = 0;
						$this->ProjectEmployee->create();
						$this->ProjectEmployee->save($data,false);
						
					}	
				}else{
						$data['ProjectEmployee']['project_id'] = $rec['ProjectReleaseRequest']['new_project_id'];
						$data['ProjectEmployee']['employee_id'] = $rec['ProjectReleaseRequest']['employee_id'];
						// $data['ProjectEmployee']['milestone_id'] = $milestone['Milestone']['id'];
						$data['ProjectEmployee']['start_date'] = $project['Project']['start_date'];
						$data['ProjectEmployee']['end_date'] = $project['Project']['end_date'];
						$data['ProjectEmployee']['current_status'] = $project['Project']['current_status'];
						$data['ProjectEmployee']['publish'] = 1;
						$data['ProjectEmployee']['soft_delete'] = 0;
						$this->ProjectEmployee->create();
						$this->ProjectEmployee->save($data,false);
						
				}
	
				exit;				


				// remove assigned files as well as update files
				// $assignedFiles = $this->ProjectFile->find('all',array(
				// 	'conditions'=>array(
				// 		'ProjectFile.employee_id'=>$rec['ProjectReleaseRequest']['employee_id'],
				// 		'ProjectFile.project_id'=>$rec['ProjectReleaseRequest']['current_project_id'],
				// 	)
				// ));

			}else{
				return false;
			}
			
		}

	}

	public function all_member_lock_board(){
		$projects = $this->Project->find('list',
			array(
			'conditions'=>array(
				'Project.soft_delete'=>0,
				'Project.publish'=>1,
				),
				'order'=>array(
					'Project.start_date'=>'ASC'
				)
		));

		$firstProject = $this->Project->find('first',array(
			'order'=>array('Project.start_date'=>'ASC'),
			'fields'=>array('Project.id','Project.start_date'),
			'recursive'=>-1,
			'conditions'=>array(
				'Project.soft_delete'=>0,
				'Project.publish'=>1,
			)
		));

		$lastProject = $this->Project->find('first',array(
			'order'=>array('Project.start_date'=>'DESC'),
			'fields'=>array('Project.id','Project.end_date'),
			'recursive'=>-1,
			'conditions'=>array(
				'Project.soft_delete'=>0,
				'Project.publish'=>1,
			)
		));

		// Configure::write('debug',1);

		// debug($firstProject);
		// debug($lastProject);
		// debug($projects);

		$mcnt = count($this->_get_employee_list());
		$startDate = date('Y-m-1',strtotime($firstProject['Project']['start_date']));
		$endDate = date('Y-m-t',strtotime($lastProject['Project']['end_date']));

		// $endDate = date("Y-m-t", strtotime("+10 month", strtotime($endDate)));	


		// print_r($startDate);
		// debug($endDate);
		$this->Project->ProjectEmployee->virtualFields = array(
			'smy'=>'DATE_FORMAT(ProjectEmployee.start_date, "%Y-%m")',
			'emy'=>'DATE_FORMAT(ProjectEmployee.end_date, "%Y-%m")'
		);
		while (strtotime($startDate) <= strtotime($endDate)) {
			$t = 0;
			foreach ($projects as $key => $value) {				
				$recs[$value][date('Y-m',strtotime($startDate))] = $this->Project->ProjectEmployee->find('count',array(
					'recursive'=>-1,
					'fields'=>array(
						'ProjectEmployee.smy',
						'ProjectEmployee.emy',
					),
					'conditions'=>array(

						'OR'=>array(
							'DATE_FORMAT(ProjectEmployee.start_date, "%Y-%m") >= '=>date('Y-m',strtotime($startDate)),
							'DATE_FORMAT(ProjectEmployee.end_date, "%Y-%m") >= '=>date('Y-m',strtotime($startDate)),
						),						
						'ProjectEmployee.project_id'=>$key,
					),
					'group'=>array('ProjectEmployee.employee_id')
				));
				
				$t = $t + $recs[$value][date('Y-m',strtotime($startDate))];

			}
			$recs['Idle'][date('Y-m',strtotime($startDate))] = ($mcnt - $t);
			// foreach ($projects as $key => $value) {
			// 	// echo $startDate . '<br />';
			// 	$members = 0;
			// 	$members = $this->Project->ProjectEmployee->find('count',
			// 		array(
			// 			'group'=>array('ProjectEmployee.employee_id'),
			// 			'conditions'=>array(
			// 				'Project.soft_delete'=>0,
			// 				'Project.publish'=>1,
			// 				'ProjectEmployee.project_id'=>$key,
			// 			'or'=>array(
			// 				'ProjectEmployee.start_date >= ' => date('Y-m-1',strtotime($startDate)),
			// 				'ProjectEmployee.end_date <= ' => date('Y-m-t',strtotime($startDate)),
			// 				// 'ProjectEmployee.start_date BETWEEN ? and ?' => array(date('Y-m-d',strtotime($startDate)),date('Y-m-t',strtotime($startDate))),
			// 				// 'ProjectEmployee.end_date BETWEEN ? and ?' => array(date('Y-m-d',strtotime($startDate)),date('Y-m-t',strtotime($startDate))),
			// 			)
			// 		))
			// 	);

			// 	$amembers = $this->Project->ProjectEmployee->find('count',					
			// 		array(
			// 			'group'=>array('ProjectEmployee.employee_id'),
			// 			'conditions'=>array(
			// 				'Project.soft_delete'=>0,
			// 				'Project.publish'=>1,
			// 			// 'ProjectEmployee.project_id'=>$key,
			// 			'or'=>array(
			// 				'ProjectEmployee.start_date >= ' => date('Y-m-1',strtotime($startDate)),
			// 				'ProjectEmployee.end_date <= ' => date('Y-m-t',strtotime($startDate)),
			// 				// 'ProjectEmployee.start_date BETWEEN ? and ?' => array(date('Y-m-1',strtotime($startDate)),date('Y-m-t',strtotime($startDate))),
			// 				// 'ProjectEmployee.end_date BETWEEN ? and ?' => array(date('Y-m-1',strtotime($startDate)),date('Y-m-t',strtotime($startDate))),
			// 			)
			// 		))
			// );
			// 	if($members)$recs[$value][date('Y-m',strtotime($startDate))] = $members;
			// 	else $recs[$value][date('Y-m',strtotime($startDate))] = 0;				
				
			// 	if($amembers)$arecs[date('Y-m',strtotime($startDate))] = $amembers;
			// 	else $arecs[date('Y-m',strtotime($startDate))] = 0;
			// }

			$dates[] = date('Y-m',strtotime($startDate));
			$startDate = date("Y-m-d", strtotime("+1 month", strtotime($startDate)));	
		}



		// Configure::write('debug',1);
		// debug($recs);
		// debug($arecs);
		// debug($dates);
		// exit;
		$this->set('recs',$recs);
		$this->set('arecs',$arecs);
		$this->set('dates',$dates);
		$this->set('memCnt',count($this->_get_employee_list()));
	}

	public function project_team_board(){
		$projects = $this->Project->find('list',array('fields'=>array('Project.id','Project.end_date')));

		$this->loadModel('Employee');
		foreach ($projects as $pkey => $pvalue) {
			// debug($pkey);
			$projectEmployees = $this->Project->ProjectEmployee->find('list',array(
				'fields'=>array('ProjectEmployee.employee_id','ProjectEmployee.milestone_id'),
				'conditions'=>array(
					'ProjectEmployee.project_id'=>$pkey,
					// 'ProjectEmployee.milestone_id'=>$milestone['Milestone']['id']
				)
			));
			// Configure::write('debug',1);
			// debug($projectEmployees);

			foreach ($projectEmployees as $employee_id => $mid) {

				$this->Project->ProjectResource->virtualFields = array(
					'end_date' => 'select `projects`.`end_date` from `projects` where `projects`.`id` LIKE "'.$pkey.'"'
				);

				$recs[$employee_id] = $this->Project->ProjectResource->find('all',array(
					'fields'=>array(
						'ProjectResource.id',
						'ProjectResource.employee_id',
						'ProjectResource.team_leader_id',
						'ProjectResource.project_leader_id',
						'ProjectProcessPlan.id',
						'ProjectProcessPlan.process',
						'ProjectResource.priority',
						'ProjectResource.end_date',
						'Employee.id',
						'Employee.name',
						'Employee.department_id',
						'Employee.designation_id',
					),
					'conditions'=>array(
						'ProjectResource.employee_id'=>$employee_id,
						'ProjectResource.project_id'=>$pkey,
						// 'ProjectResource.milestone_id'=>$milestone['Milestone']['id']
					),
					'order'=>array('ProjectResource.priority'=>'ASC'),
					'recursive'=>0,
				));
				
			}
		

		}		
		// Configure::write('debug',1);
		// debug($recs);
		$this->set('recs',$recs);
	}


	public function meb(){

		$pprojects = $this->Project->find('all',array(			
			'conditions'=>array(
				'OR'=>array(
					'Project.start_date >=' => date('Y-m-d'),
					'Project.current_status  <'=>3,
				)
				
			),
			'recursive'=>0,
			'fields'=>array(
				'Project.id',
				'Project.title',
				'Project.start_date',
				'Customer.id',
				'Customer.name',
			)
		));
		$x = 0;
		foreach ($pprojects as $pproject) {
			$projects[$x] = $pproject;
			
			$projects[$x]['TLs'] = $this->Project->ProjectResource->find('count',array(
				'conditions'=>array('ProjectResource.project_id'=> $pproject['Project']['id']),
				'group'=>array('ProjectResource.team_leader_id')
			));

			$projects[$x]['PLs'] = $this->Project->ProjectResource->find('count',array(
				'conditions'=>array('ProjectResource.project_id'=> $pproject['Project']['id']),
				'group'=>array('ProjectResource.project_leader_id')
			));

			$projects[$x]['Ms'] = $this->Project->ProjectResource->find('count',array(
				'conditions'=>array('ProjectResource.project_id'=> $pproject['Project']['id']),
				'group'=>array('ProjectResource.employee_id')
			));

			$x++;
		}
		
		$this->set(array('projects'=>$projects));
	}

	public function pro_meb_details(){
		
		$this->loadModel('Employee');
		
		if($this->request->params['named']['project_id']){
			$project = $this->Project->find('first',array(			
				'conditions'=>array(
						'Project.soft_delete'=>0,'Project.publish'=>1,
						'Project.id'=>$this->request->params['named']['project_id']
					
					
				),
				'recursive'=>0,
				'fields'=>array(
					'Project.id',
					'Project.title',
					'Project.start_date',	
					'Project.end_date',				
				)
			));
			// Configure::write('debug',1);
			// debug($project);

			$project_id = $this->request->params['named']['project_id'];
			$start_date = date('Y-m-d',strtotime($project['Project']['start_date']));
			$end_date = date('Y-m-d',strtotime($project['Project']['end_date']));

			$this->Employee->virtualFields = array(
				'pro_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id AND `project_employees`.`project_id` LIKE "'.$project_id.'" AND DATE(`project_employees`.`end_date`) >= "'.DATE('Y-m-d').'" LIMIT 1 ',
				

				// 'pro_check'=>'SELECT count(*) FROM project_employees pm LEFT JOIN projects p ON pm.project_id =  p.id where p.soft_delete = 0 and p.publish = 1 and pm.`employee_id` = Employee.id AND pm.`project_id` LIKE "'.$project_id.'" LIMIT 1',

				'pro_emp_id'=>'select DISTINCT(`project_employees`.`id`) from `project_employees` WHERE   `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
				
				'locked_from'=>'select DISTINCT(`project_employees`.`start_date`) from `project_employees` WHERE  `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
				
				'locked_till'=>'select DISTINCT(`project_employees`.`end_date`) from `project_employees` WHERE  `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` DESC LIMIT 1',
				
				'tl'=>'select DISTINCT(`project_resources`.`team_leader_id`) from `project_resources` WHERE `project_resources`.`employee_id` = Employee.id LIMIT 1 ',
				
				'pm'=>'select DISTINCT(`project_resources`.`project_leader_id`) from `project_resources` WHERE `project_resources`.`employee_id` = Employee.id LIMIT 1',
				
				'curr_project'=>'select DISTINCT(`project_employees`.`project_id`) from `project_employees` WHERE `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
				
				'pro_res' => 'select `project_resources`.`project_id` from `project_resources` where `project_resources`.`employee_id` LIKE Employee.id LIMIT 1',
				'release_request' => 'select count(*) from `project_release_requests` where `project_release_requests`.`employee_id` LIKE Employee.id AND `project_release_requests`.`request_status`= 0 '
				// 'curr_project'=>'select DISTINCT(`projects`.`title`) from `projects` where `projects`.`id` = "'.$project_id.'"'
				// 'curr_project'=>'select DISTINCT(`projects`.`title`) 
				// 	from project_employees 
				// 	LEFT JOIN `projects` ON `project_employees`.`project_id` = `projects`.`id` 
				// 		AND `project_employees`.`employee_id` = Employee.id LIMIT 1',
			);
		
			$allMembers = $this->Project->ProjectEmployee->Employee->find('all',array(
				'conditions'=>array(
					'Employee.pro_check >'=>0,
					'Employee.locked_till >' => date('Y-m-d'),
					// 'Employee.release_request'=>0
				),
				'order'=>array('Employee.curr_project'=>'DESC','Employee.name'=>'ASC')
			));
			// echo "this";
		}else{
			

			$this->Employee->virtualFields = array(
				// 'pro_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id LIMIT 1',
				'pro_res' => 'select count(*) from `project_resources` where `project_resources`.`employee_id` LIKE Employee.id LIMIT 1',		
				// 'pro_check'=>'SELECT count(*) FROM project_employees pm LEFT JOIN projects p ON pm.project_id =  p.id where p.soft_delete = 0 and p.publish = 1 and pm.`employee_id` = Employee.id LIMIT 1',	

				// 'pro_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id AND DATE(`project_employees`.`end_date`) <= "'.DATE('Y-m-d').'"',


				'locked_till'=>'select DISTINCT(`project_employees`.`end_date`) from `project_employees` WHERE  `project_employees.employee_id`= Employee.id and `project_employees`.`end_date` <= "' . date('Y-m-d') . '" GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` DESC LIMIT 1',

				'release_request' => 'select count(*) from `project_release_requests` where `project_release_requests`.`employee_id` LIKE Employee.id AND `project_release_requests`.`request_status`= 0 '	
			);

			$allMembers = $this->Project->ProjectEmployee->Employee->find('all',array(
				'recursive'=>0,
				'fields'=>array('Employee.id','Employee.name','Employee.designation_id','Employee.department_id','Employee.locked_till','Department.id','Department.name','Designation.id','Designation.name','Employee.pro_res','Employee.release_request'),
				'conditions'=>array(
					'Employee.release_request'=>0,											
						
					'OR'=>array(
						'Employee.pro_res'=>0,
						
						'Employee.locked_till >' => date('Y-m-d'),
					)
					
				),
				'order'=>array('Employee.name'=>'ASC')
			));

			// Configure::write('debug',1);
			// debug($allMembers);
		}

		$this->set('current_project_id',$this->request->params['named']['current_project_id']);

		
		
		// Configure::write('debug',1);
		// debug($allMembers);

		
		$this->set('allMembers',$allMembers);
		$this->set('allProjects',$this->Project->find('list'));
		$this->set('teamMembers',$this->team_members($project['Project']['id']));


		$this->set('PublishedEmployeeList',$this->_get_employee_list());
		$this->set('PublishedDepartmentList',$this->_get_department_list());
		$this->set('PublishedDesignationList',$this->_get_designation_list());

		return $allMembers;
	}

	public function meb_details($project_id = null){

		$this->loadModel('Employee');

		if($project_id = $this->request->params['named']['project_id']){
			$project = $this->Project->find('first',array(			
				'conditions'=>array(
					
						'Project.id'=>$this->request->params['named']['project_id']
					
					
				),
				'recursive'=>0,
				'fields'=>array(
					'Project.id',
					'Project.title',
					'Project.start_date',	
					'Project.end_date',				
				)
			));
			// Configure::write('debug',1);
			// debug($project);

			$project_id = $this->request->params['named']['project_id'];
			$start_date = date('Y-m-d',strtotime($project['Project']['start_date']));
			$end_date = date('Y-m-d',strtotime($project['Project']['end_date']));

			$this->Employee->virtualFields = array(
				'pro_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id AND `project_employees`.`project_id` LIKE "'.$project_id.'" LIMIT 1',

				'pro_emp_id'=>'select DISTINCT(`project_employees`.`id`) from `project_employees` WHERE   `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
				
				'locked_from'=>'select DISTINCT(`project_employees`.`start_date`) from `project_employees` WHERE  `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
				
				'locked_till'=>'select DISTINCT(`project_employees`.`end_date`) from `project_employees` WHERE  `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") GROUP BY  project_employees`.`employee_id` ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
				
				'tl'=>'select DISTINCT(`project_resources`.`team_leader_id`) from `project_resources` WHERE `project_resources`.`employee_id` = Employee.id LIMIT 1 ',
				
				'pm'=>'select DISTINCT(`project_resources`.`project_leader_id`) from `project_resources` WHERE `project_resources`.`employee_id` = Employee.id LIMIT 1',
				
				'curr_project'=>'select DISTINCT(`project_employees`.`project_id`) from `project_employees` WHERE `project_employees.employee_id`= Employee.id and (`project_employees`.`start_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $start_date . '" or `project_employees`.`end_date` <= "' . $end_date . '" or `project_employees`.`end_date` <= "' . $end_date . '") ORDER BY `project_employees`.`start_date` ASC LIMIT 1',
				
				'pro_res' => 'select `project_resources`.`project_id` from `project_resources` where `project_resources`.`employee_id` LIKE Employee.id LIMIT 1'
				// 'curr_project'=>'select DISTINCT(`projects`.`title`) from `projects` where `projects`.`id` = "'.$project_id.'"'
				// 'curr_project'=>'select DISTINCT(`projects`.`title`) 
				// 	from project_employees 
				// 	LEFT JOIN `projects` ON `project_employees`.`project_id` = `projects`.`id` 
				// 		AND `project_employees`.`employee_id` = Employee.id LIMIT 1',
			);
		
			$allMembers = $this->Project->ProjectEmployee->Employee->find('all',array(
				'conditions'=>array(
					'Employee.pro_check'=>1
				),
				'order'=>array('Employee.curr_project'=>'DESC','Employee.name'=>'ASC')
			));
		
		}else{

			$this->Employee->virtualFields = array(
				'pro_check'=>'select count(*) from `project_employees` where `project_employees`.`employee_id` = Employee.id LIMIT 1',				
			);

			$allMembers = $this->Project->ProjectEmployee->Employee->find('all',array(
				'conditions'=>array(
					'Employee.pro_check'=>0
				),
				'order'=>array('Employee.name'=>'ASC')
			));
		}

		

		
		
			

		
		$this->set('allMembers',$allMembers);
		$this->set('allProjects',$this->Project->find('list'));
		$this->set('teamMembers',$this->team_members($project['Project']['id']));


		$this->set('PublishedEmployeeList',$this->_get_employee_list());
		$this->set('PublishedDepartmentList',$this->_get_department_list());
		$this->set('PublishedDesignationList',$this->_get_designation_list());
	}

	public function holiday_days_from_file_process($project_id = null , $start_date = null, $end_date = null){
		$start_date = base64_decode($start_date);
		$end_date = base64_decode($end_date);
		$result = $this->holiday_days($project_id,$start_date,$end_date);
		// debug($result);
		return $result;
	}

	public function holiday_days($project_id = null , $start_date = null, $end_date = null){
		// Configure::write('debug',1);
		// debug($this->request->params);
		// debug($project_id);
		// debug($end_date);
			$project = $this->Project->find('first',array(
				'fields'=>array(
					'Project.id',
					'Project.daily_hours',
					'Project.weekends',
					// 'Project.start_time',
					// 'Project.end_time',
				),
				'conditions' => array('Project.id'=>$project_id),
				'recursive'=>-1
			));
			
			$weekends = json_decode($project['Project']['weekends']);
			
			$this->loadModel('Holiday');

			while (strtotime($start_date) <= strtotime($end_date)) {
			$t = 0;
			// debug($start_date);
			// debug(date('N',strtotime($start_date)));
			// debug(date('D',strtotime($start_date)));
			
			if(in_array(date('N',strtotime($start_date)), $weekends) == true){
				$holidays[] = date('Y-m-d',strtotime($start_date));
			}

			$holiday = $this->Holiday->find('first',array('recursive'=>-1, 'conditions'=>array('Holiday.date'=>date('Y-m-d',strtotime($start_date)))));
			if($holiday)$holidays[] = date('Y-m-d',strtotime($start_date));

			$start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			}
			if($holidays){
				return $holidays;	
			}else{
				return 0;
			}
			
	}


	public function test_sql($start_date = null, $end_date = null){

		$sql = 'select count(*) from `holidays` where `holidays`.`date` BETWEEN '.$start_date.' AND '.$end_date.' ';
		// $sql2 = "5 * (DATEDIFF(".$end_date.", ".$start_date.") DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(@S) + WEEKDAY(@E) + 1, 1)";
		// $sql2 = "5 * (DATEDIFF(ProjectEmployee.end_date, ProjectEmployee.start_date) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(@S) + WEEKDAY(@E) + 1, 1)";
		// echo $start_date;
		// echo $end_date;
		// $date1 = new DateTime(date('Y-m-d H:i:s',strtotime($start_date)));
		// $date2 = new DateTime(date('Y-m-d H:i:s',strtotime($end_date)));
		// debug($date1);
		// debug($date2);
		// $cnt = 0;
		// while (strtotime($date1) <= strtotime($date2)) {
        
        	// $dayName = date('D',strtotime($date1));
        	// if($dayName == 'Sat' || $dayName == 'Sun'){
        	// 	$cnt++;
        	// }
        
        	// $date1 = date("Y-m-d", strtotime("+1 day", strtotime($date1)));
        
    	// }
	    // echo $cnt;
		return $sql;
	}

	public function processGraph($project_id = null,$milestone_id = null){
		// Configure::write('debug',1);
		$this->loadModel('ProjectProcessPlan');
		$projectProcessPlans = $this->ProjectProcessPlan->find('list',array('fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'), 
			'conditions'=>array(
				'ProjectProcessPlan.project_id'=>$project_id,
				'ProjectProcessPlan.milestone_id'=>$milestone_id,
			)));
		// debug($projectProcessPlans);
		$this->loadModel('FileProcess');
		$this->FileProcess->virtualFields = array(
			'ucom'=>'SUM(units_completed)'
		);
		foreach ($projectProcessPlans as $key => $value) {
			$files[$value] = $this->FileProcess->find('first',array(
				'recursive'=> -1,
				'fields'=>array('FileProcess.id','FileProcess.ucom'),
				'conditions'=>array('FileProcess.project_process_plan_id'=>$key))
			);
		}

		return $files;


	}

	public function resourceGraph($project_id = null,$milestone_id = null){
		// Configure::write('debug',1);
		// // $this->autoRender = false;
		// $this->Project->FileProcess->virtualFields = array(
		// 	'employee'=>'select name from employees where id LIKE FileProcess.employee_id'
		// );
		// $files = $this->Project->FileProcess->find('list',array(
		// 	'recursive'=>-1,
		// 	'fields'=>array('FileProcess.employee_id','FileProcess.employee'),
		// 	'conditions'=>array('FileProcess.project_id'=>$project_id,'FileProcess.milestone_id'=>$milestone_id),
		// 	'group'=>array('FileProcess.employee_id')
		// ));

		// debug($files);
		// exit;

		if($milestone_id){
			$this->Project->ProjectEmployee->virtualFields = array(				
				'mandays'=>'select (DATEDIFF(ProjectEmployee.end_date,ProjectEmployee.start_date) + 1 * 60 * 24)',
				
				'holidays'=>$this->test_sql('ProjectEmployee.start_date','ProjectEmployee.end_date'),

				'total_files_emp'=>'select count(*) from file_processes where file_processes.employee_id LIKE ProjectEmployee.employee_id ',
				// 'holidays'=>$this->test_sql
				'tst'=>'5 * (DATEDIFF(ProjectEmployee.end_date, ProjectEmployee.start_date) DIV 7) + MID("0123444401233334012222340111123400001234000123440", 7 * WEEKDAY(ProjectEmployee.start_date) + WEEKDAY(ProjectEmployee.end_date) + 1, 1) + 1',
				
				// 'rr' => 'select SUM(DATEDIFF(end_time,start_time)) from `file_processes` where `file_processes`.`employee_id` = ProjectEmployee.employee_id AND `file_processes`.`project_id` LIKE ProjectEmployee.project_id AND end_time is NOT NULL GROUP BY `file_processes`.`employee_id`',
				
				// 'file_duration' => 'select SUM(TIMEDIFF(end_time,start_time) DIV 60) from `file_processes` where `file_processes`.`employee_id` = ProjectEmployee.employee_id AND `file_processes`.`project_id` LIKE ProjectEmployee.project_id AND `file_processes`.`milestone_id` LIKE "'.$milestone_id.'" AND end_time is NOT NULL GROUP BY `file_processes`.`employee_id`',

				'total_files'=>'select count(*) from project_files where project_files.project_id LIKE ProjectEmployee.project_id ',	

				'file_duration' => 'select TIMEDIFF(end_time,created) from `file_processes` where `file_processes`.`employee_id` = ProjectEmployee.employee_id AND `file_processes`.`project_id` LIKE ProjectEmployee.project_id AND `file_processes`.`milestone_id` LIKE "'.$milestone_id.'" AND end_time is NOT NULL GROUP BY `file_processes`.`employee_id`',

				// 'file_duration'=>'select SUM(TIMEDIFF(end_time,start_time)) from file_processes where `file_processes`.`employee_id` = ProjectEmployee.employee_id AND `file_processes`.`project_id` LIKE ProjectEmployee.project_id AND end_time is NOT NULL',
				
				'hold_file_duration'=>'select SUM(TIMEDIFF(hold_end_time,hold_start_time)) from file_processes where file_processes.employee_id = ProjectEmployee.employee_id AND file_processes.project_id = ProjectEmployee.project_id AND file_processes.milestone_id = "'.$milestone_id.'"',
				
				'resource_cost' => 'select resource_cost from employees where id = ProjectEmployee.employee_id',

				'units_completed' => 'SELECT SUM(units_completed) FROM file_processes WHERE `file_processes`.`employee_id` LIKE ProjectEmployee.employee_id AND `file_processes`.`milestone_id` LIKE "'.$milestone_id.'"',

				'avg_est_units'=> 'SELECT SUM(estimated_units) from project_overall_plans where project_overall_plans.project_id LIKE ProjectEmployee.project_id'
				
			);
		}else{
			$this->Project->ProjectEmployee->virtualFields = array(
				'mandays'=>'select (DATEDIFF(ProjectEmployee.end_date,ProjectEmployee.start_date) + 1 * 60 * 24)',
				
				'holidays'=>$this->test_sql('ProjectEmployee.start_date','ProjectEmployee.end_date'),

				'total_files'=>'select count(*) from project_files where project_files.project_id LIKE ProjectEmployee.project_id ',	

				'total_files_emp'=>'select count(*) from file_processes where file_processes.employee_id LIKE ProjectEmployee.employee_id ',
				// 'holidays'=>$this->test_sql
				'tst'=>'5 * (DATEDIFF(ProjectEmployee.end_date, ProjectEmployee.start_date) DIV 7) + MID("0123444401233334012222340111123400001234000123440", 7 * WEEKDAY(ProjectEmployee.start_date) + WEEKDAY(ProjectEmployee.end_date) + 1, 1) + 1',
				
				// 'rr' => 'select SUM(DATEDIFF(end_time,start_time)) from `file_processes` where `file_processes`.`employee_id` = ProjectEmployee.employee_id AND `file_processes`.`project_id` LIKE ProjectEmployee.project_id AND end_time is NOT NULL GROUP BY `file_processes`.`employee_id`',
				
				// 'file_duration' => 'select SUM(TIMEDIFF(end_time,start_time) DIV 60) from `file_processes` where `file_processes`.`employee_id` = ProjectEmployee.employee_id AND `file_processes`.`project_id` LIKE ProjectEmployee.project_id AND `file_processes`.`milestone_id` LIKE "'.$milestone_id.'" AND end_time is NOT NULL GROUP BY `file_processes`.`employee_id`',

				'file_duration' => 'select TIMEDIFF(end_time,created) from `file_processes` where `file_processes`.`employee_id` = ProjectEmployee.employee_id AND `file_processes`.`project_id` LIKE ProjectEmployee.project_id AND  end_time is NOT NULL GROUP BY `file_processes`.`employee_id`',

				// 'file_duration'=>'select SUM(TIMEDIFF(end_time,start_time)) from file_processes where `file_processes`.`employee_id` = ProjectEmployee.employee_id AND `file_processes`.`project_id` LIKE ProjectEmployee.project_id AND end_time is NOT NULL',
				
				'hold_file_duration'=>'select SUM(TIMEDIFF(hold_end_time,hold_start_time)) from file_processes where file_processes.employee_id = ProjectEmployee.employee_id AND file_processes.project_id = ProjectEmployee.project_id ',
				
				'resource_cost' => 'select resource_cost from employees where id = ProjectEmployee.employee_id',

				'units_completed' => 'SELECT SUM(units_completed) FROM file_processes WHERE `file_processes`.`employee_id` LIKE ProjectEmployee.employee_id ',

				'avg_est_units'=> 'SELECT SUM(estimated_units) from project_overall_plans where project_overall_plans.project_id LIKE ProjectEmployee.project_id'
				
			);
		}

		
		


		$resources = $this->Project->ProjectEmployee->find('all',			
			array(
				'fields'=>array(
					'ProjectEmployee.employee_id',
					'ProjectEmployee.project_id',
					'ProjectEmployee.start_date',
					'ProjectEmployee.end_date',
					'ProjectEmployee.mandays',
					'ProjectEmployee.holidays',
					'ProjectEmployee.tst',
					'ProjectEmployee.resource_cost',
					'ProjectEmployee.units_completed',
					'ProjectEmployee.avg_est_units',
					// 'ProjectEmployee.rr',
					// 'ProjectEmployee.tt',
					'ProjectEmployee.file_duration',
					'ProjectEmployee.hold_file_duration',
					'ProjectEmployee.total_files_emp',
					'ProjectEmployee.total_files',
					'Employee.id',
					'Employee.name',
				),
				'conditions'=>array(
					'ProjectEmployee.project_id'=>$project_id,
					// 'ProjectEmployee.milestone_id'=>$milestone_id
				),
				'recursive'=>0
			)
		);
		// Configure::write('debug',1);
		// debug($resources);
		// exit;
		return $resources;
		
		
	}


	public function _add_files($project_id = null,$milestone_id = null,$data = null){
		// debug($data);
		if ($project_id) {
			$this->loadModel('ProjectFile');
			// $this->request->data['ProjectFile']['system_table_id'] = $this->_get_system_table_id();
			// get files
			$files = str_getcsv($data['file_data'],PHP_EOL);
			
			// common conditions 
			// 1. check if employee is working on any other file
			// 2. check if employee is on leave
			// 3. check if its holiday
			// 4. 
			$this->loadModel('ProjectResource');

			$this->ProjectResource->virtualFields = array(
				'emp_already_assigned'=> 'select count(*) FROM `project_files` WHERE `project_files.employee_id` = ProjectResource.employee_id',
				'emp_on_leave'=> 0,
			);

			
			$resources = $this->ProjectResource->find('all',array(
				'conditions'=>array(
					'ProjectResource.project_id'=>$project_id,
					'ProjectResource.emp_already_assigned != '=>1,
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

			if($resources){
				$start_date = $resources[0]['Project']['start_date'];
				$end_date = $resources[0]['Project']['end_date'];
			}else{
				$getDates = $this->ProjectFile->Project->find('first',array(
					'recursive'=>-1,
					'fields'=>array('Project.id','Project.start_date','Project.end_date'),
					'conditions'=>array('Project.id'=>$project_id)
				));
				if($project){
					$start_date = $resources['Project']['start_date'];
					$end_date = $resources['Project']['end_date'];
				}
			}

			// get project process 
	        $this->loadModel('ProjectProcessPlan');
	        $projectProcesses = $this->ProjectProcessPlan->find(
	            'first',array(
	                'conditions'=>array(
	                	'ProjectProcessPlan.project_id'=> $project_id
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

	        $estimated_time = $projectProcesses['ProjectProcessPlan']['estimated_units'] / $projectProcesses['ProjectProcessPlan']['overall_metrics'] / $projectProcesses['ProjectProcessPlan']['days'] / $projectProcesses['ProjectProcessPlan']['estimated_resource'];
	        
	        $this->loadModel('FileProcesses');

			$x = 0;
			foreach ($files as $file) {
				$f = explode(',', $file);
				$d = array();
				// select employee 
					$employee_id = $resources[$x]['Employee']['id'];

					if($employee_id){
						echo "Assigining file to " . $resources[$x]['Employee']['name'];

						$d['ProjectFile']['name'] = ltrim(rtrim($f[0]));
						$d['ProjectFile']['unit'] = ltrim(rtrim($f[1]));
						$d['ProjectFile']['estimated_time'] = $estimated_time;
						$d['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
						// $d['ProjectFile']['start_date'] = $start_date;
						// $d['ProjectFile']['end_date'] = $end_date;
						$d['ProjectFile']['employee_id'] = $employee_id;
						$d['ProjectFile']['project_id'] = $project_id;
						$d['ProjectFile']['milestone_id'] = $milestone_id;
						// $d['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s',strtotime($this->request->data['ProjectFile']['assigned_date']));
						$d['ProjectFile']['current_status'] = 0;

						$x++;						
					}else{
						$employee_id = "Not Assigned";

						$d['ProjectFile']['name'] = ltrim(rtrim($f[0]));
						$d['ProjectFile']['unit'] = ltrim(rtrim($f[1]));
						$d['ProjectFile']['employee_id'] = $employee_id;
						$d['ProjectFile']['project_id'] = $project_id;
						$d['ProjectFile']['milestone_id'] = $milestone_id;
						$d['ProjectFile']['current_status'] = 4;
						$x++;						
					}
					
					// check if file is already added for current project 

					$fileChk = $this->ProjectFile->find('count',array(
						'conditions'=>array(
							'ProjectFile.name'=>ltrim(rtrim($f[0])),
							'ProjectFile.project_id'=>$project_id,
							'ProjectFile.milestone_id'=>$milestone_id,
						)
					));

					$d['ProjectFile']['project_process_plan_id'] = $projectProcesses['ProjectProcessPlan']['id'];

					if($fileChk == 0){
						$this->ProjectFile->create();
						if($this->ProjectFile->save($d,false)){
							if($employee_id != 'Not Assigned'){
								$this->loadModel('FileProcess');
								$fp['project_id'] = $project_id;
								$fp['milestone_id'] = $milestone_id;
								$fp['employee_id'] = $employee_id;;
								$fp['assigned_date'] = date('Y-m-d H:i:s');
								$fp['estimated_time'] = $estimated_time;
								$fp['current_status'] = 0;
								$fp['project_process_plan_id'] = $projectProcesses['ProjectProcessPlan']['id'];
								$fp['project_file_id'] = $this->ProjectFile->id;
								$fp['publish'] = 1;
								$fp['soft_delete'] = 0;	
								$this->FileProcess->create();
								$this->FileProcess->save($fp,false);
							}
						}
					}else{
						echo "This file is already added";
					}
			}
		}
		return true;
	}

	public function delete_overall_plans(){
		$milestone_id = $this->request->params['named']['milestone_id'];
		if($milestone_id){
			$this->loadModel('ProjectOverallPlan');
			$recs = $this->Project->ProjectOverallPlan->find('all',array(
				'recursive'=>-1,
				'conditions'=>array('ProjectOverallPlan.milestone_id'=>$milestone_id)
			));

			// Configure::write('debug',1);
			// debug($recs);
			foreach ($recs as $rec) {
				$planResults = $this->Project->ProjectOverallPlan->ProjectProcessPlan->find('all',array(
					'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
					'recursive'=>-1,
					'conditions'=>array('ProjectProcessPlan.soft_delete'=>0, 'ProjectProcessPlan.project_overall_plan_id'=>$rec['ProjectOverallPlan']['id'])
				));
				foreach ($planResults as $planResult) {
					$files = $this->Project->ProjectFile->find('all',array(
						'recursive'=>-1,
						'conditions'=>array('ProjectFile.project_process_plan_id'=>$planResult['ProjectProcessPlan']['id'])
					));
					foreach ($files as $file) {

						$projectResources = $this->Project->Project->ProjectOverallPlan->ProjectProcessPlan->ProjectResource->deleteAll(array('ProjectResource.process_id'=>$planResult['ProjectProcessPlan']['id']));

						$proFiles = $this->Project->ProjectFile->FileProcess->find('all',array(
							'recursive'=>-1,
							'conditions'=>array('FileProcess.project_file_id'=>$file['ProjectFile']['id'])
						));
						foreach ($proFiles as $proFile) {
							$this->Project->ProjectFile->FileProcess->deleteAll(array('FileProcess.id'=>$proFile['FileProcess']['id']));
						}
						$this->Project->ProjectFile->deleteAll(array('ProjectFile.id'=>$file['ProjectFile']['id']));
					}

					$this->Project->ProjectOverallPlan->ProjectProcessPlan->deleteAll(array('ProjectProcessPlan.id'=>$planResult['ProjectProcessPlan']['id']));
				}
				$this->Project->ProjectOverallPlan->deleteAll(array('ProjectOverallPlan.id'=>$rec['ProjectOverallPlan']['id']));
			}
		}
		// exit;
		$this->redirect(array('action' => 'view',$this->request->params['named']['project_id']));
	}

	public function release_member($id = null,$employee_id = null, $project_id = null){
		$this->autoRender = false;
		// Configure::write('debug',1);
		// debug($id);
		// debug($employee_id);
		// debug($pop);
		// exit;

		// check if users is being assigned any file/task
		$file_assigned = $this->Project->ProjectFile->find('count',array(
			'conditions'=>array(
				'ProjectFile.project_id'=>$project_id,
				'ProjectFile.employee_id'=>$employee_id,
				'ProjectFile.current_status'=>array(0,6,7)
			)
		));

		// debug($file_assigned);
		// exit;


		if($file_assigned > 0){
			return 1;
		}else{
			$emp = $this->Project->ProjectEmployee->find('first',array(
				'recursive'=>-1,
				'conditions'=>array('ProjectEmployee.id'=>$id)				
			));
			if($emp){
				$this->Project->ProjectEmployee->deleteAll(array('ProjectEmployee.id'=>$id));
				$this->Project->ProjectResource->deleteAll(array(
					'ProjectResource.employee_id'=>$emp['ProjectEmployee']['employee_id'],
					'ProjectResource.project_id'=>$emp['ProjectEmployee']['project_id']
				));	
			}			
			return 0;	
		}

		
	}



	public function daily_time_log($project_id = null, $milestone_id = null,$start_date = null,$end_date = null,$date = null){
		$this->loadModel('ProjectFile');

		// debug(base64_decode($date));
		if($date){
			$this->ProjectFile->virtualFields = array(
				'total_time' => 'SELECT SUM(TIMEDIFF(end_time,start_time)) FROM file_processes where end_time is NOT NULL AND file_processes.project_file_id = ProjectFile.id AND DATE(start_time) = "'. base64_decode($date).'"',
				
				'units_completed' => 'SELECT SUM(units_completed) FROM file_processes where file_processes.project_file_id = ProjectFile.id AND DATE(start_time) = "' .base64_decode($date).'"',
				
				'hold_time' => 'SELECT SUM(TIMEDIFF(hold_end_time,hold_start_time)) FROM file_processes where hold_end_time is NOT NULL AND hold_start_time is NOT NULL AND file_processes.project_file_id = ProjectFile.id AND DATE(start_time) = "' .base64_decode($date).'"',
			);			
		}else{
			$this->ProjectFile->virtualFields = array(
				'total_time' => 'SELECT SUM(TIMEDIFF(end_time,start_time)) FROM file_processes where end_time is NOT NULL AND file_processes.project_file_id = ProjectFile.id',
				'units_completed' => 'SELECT SUM(units_completed) FROM file_processes where file_processes.project_file_id = ProjectFile.id',
				'hold_time' => 'SELECT SUM(TIMEDIFF(hold_end_time,hold_start_time)) FROM file_processes where hold_end_time is NOT NULL AND hold_start_time is NOT NULL AND file_processes.project_file_id = ProjectFile.id'
			);	
		}

		$files = $this->ProjectFile->find('all',array(
			'recursive'=>-1,
			'fields'=>array(
				'ProjectFile.id',
				'ProjectFile.name',
				'ProjectFile.employee_id',				
				'ProjectFile.total_time',
				'ProjectFile.units_completed',
				'ProjectFile.hold_time'
			),
			'conditions'=>array(
				'ProjectFile.total_time > ' => 0
			)
		));

		return $files;
		
	}

	public function getd($project_id = null,$date = null){
		// $this->autoRender = false;
		// $val = array('50','20','200');
		// Configure::write('debug',1);
		// debug($date);
		// debug(base64_encode(json_encode($val)));
		// get all processes
		$this->loadModel('ProjectProcessPlan');
		$plans = $this->ProjectProcessPlan->find('list',array(
			'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
			'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
		));
		// debug($plans);
		$x = 0;
		foreach ($plans as $key => $value) {
			$val["(".$x.") " .$value] = $this->Project->FileProcess->find('count',array('conditions'=>array(
				'FileProcess.project_process_plan_id'=>$key,
				'FileProcess.current_status'=>array(1,5),
				'DATE(FileProcess.start_time)'=>$date
			)
			));
			$x++;
		}
		// debug($val);
		// exit;
		$v = '"' .base64_encode(json_encode($val)) .'"';
		return $v;
	}

	public function get_comp($project_id = null,$date = null){

		if($date){
			$this->loadModel('ProjectProcessPlan');
			$plans = $this->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
				'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
			));
			// debug($plans);
			$x = 0 ;
			foreach ($plans as $key => $value) {
				// echo $key;
				$this->Project->FileProcess->virtualFields = array(
					'total_completed_units'=>'select SUM(units_completed) from file_processes where project_id LIKE "'.$project_id.'" AND project_process_plan_id LIKE "'.$key.'" and 
					(DATE(start_time) = "' . date('Y-m-d',strtotime($date)) .'" OR DATE(end_time) = "' . date('Y-m-d',strtotime($date)) .'")',
					'total_estimated_units'=>'select AVG(estimated_units) from project_process_plans where id LIKE "'.$key.'"'
				);
				$val["(".$x.") " .$value]  = $this->Project->FileProcess->find('first',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.total_completed_units',
						'FileProcess.total_estimated_units'
					),
					'conditions'=>array(
						'FileProcess.project_process_plan_id'=>$key,
						'FileProcess.current_status'=>array(1,5),
						'DATE(FileProcess.start_time)'=>date('Y-m-d',strtotime($date))
					)
				));
				$x++;
			}
			$v = '"' .base64_encode(json_encode($val)) .'"';
			return $v;	
		}else{
			$this->loadModel('ProjectProcessPlan');
			$plans = $this->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
				'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
			));
			// debug($plans);
			foreach ($plans as $key => $value) {
				$this->Project->FileProcess->virtualFields = array(
					'total_completed_units'=>'select SUM(units_completed) from file_processes where project_process_plan_id LIKE "'.$key.'"',
					'total_estimated_units'=>'select AVG(estimated_units) from project_process_plans where id LIKE "'.$key.'"'
				);
				$val[$value] = $this->Project->FileProcess->find('first',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.total_completed_units',
						'FileProcess.total_estimated_units'
					),
					'conditions'=>array(
						'FileProcess.project_process_plan_id'=>$key,
						'FileProcess.current_status'=>array(1,5),
						// 'DATE(FileProcess.start_time)'=>$date
					)
				));
			}
			// $v = '"' .base64_encode(json_encode($val)) .'"';
			return $val;	
		}
		// $this->autoRender = false;
		
	}

	public function _get_comp_pro_wise($project_id = null,$start_date = null,$end_date = null){
		// Configure::write('debug',1);
		// debug($start_date);
		// debug($end_date);
		if($start_date && $end_date){
			// echo "Asd";
			$this->loadModel('ProjectProcessPlan');
			$plans = $this->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
				'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
			));
			// Configure::write('debug',1);
			// debug($plans);
			$x = 0;
			foreach ($plans as $key => $value) {
				debug($value);
				debug($key);
				$this->Project->FileProcess->virtualFields = array(
					'total_completed_units'=>'select SUM(units_completed) from file_processes where project_process_plan_id LIKE "'.$key.'" and 
						(
						DATE(start_time) BETWEEN "'.date('Y-m-d',strtotime($start_date)).'" AND "'.date('Y-m-d',strtotime($end_date)).'" 
						OR 
						DATE(end_time) BETWEEN "'.date('Y-m-d',strtotime($start_date)).'" AND "'.date('Y-m-d',strtotime($end_date)).'" 
						)',
					'total_estimated_units'=>'select AVG(estimated_units) from project_process_plans where id LIKE "'.$key.'"'
				);
				$val["(".$x.") " .$value] = $this->Project->FileProcess->find('first',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.total_completed_units',
						'FileProcess.total_estimated_units',
						'FileProcess.start_time',
						'FileProcess.end_time',
						'FileProcess.project_process_plan_id',
						'FileProcess.project_file_id',
					),
					'conditions'=>array(
						'FileProcess.project_process_plan_id'=>$key,
						'FileProcess.current_status'=>array(1,5),
						// 'DATE(FileProcess.start_time)'=>$date
					)
				));
				$x++;
			}
			// debug($val);
			// $v = '"' .base64_encode(json_encode($val)) .'"';
			
			return $val;	
		}else{
			$this->loadModel('ProjectProcessPlan');
			$plans = $this->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
				'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
			));
			// debug($plans);
			foreach ($plans as $key => $value) {
				$this->Project->FileProcess->virtualFields = array(
					'total_completed_units'=>'select SUM(units_completed) from file_processes where project_process_plan_id LIKE "'.$key.'"',
					'total_estimated_units'=>'select AVG(estimated_units) from project_process_plans where id LIKE "'.$key.'"'
				);
				$val[$value] = $this->Project->FileProcess->find('first',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.total_completed_units',
						'FileProcess.total_estimated_units'
					),
					'conditions'=>array(
						'FileProcess.project_process_plan_id'=>$key,
						'FileProcess.current_status'=>array(1,5),
						// 'DATE(FileProcess.start_time)'=>$date
					)
				));
			}
			// $v = '"' .base64_encode(json_encode($val)) .'"';
			return $val;	
		}
		// $this->autoRender = false;
		
	}

	public function get_total_files_completed($project_id = null,$start_date = null,$end_date){
		// Configure::write('debug',1);
		if($date){
			// $this->loadModel('ProjectProcessPlan');
			// $plans = $this->ProjectProcessPlan->find('list',array(
			// 	'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
			// 	'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
			// 	'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
			// ));
			// // debug($plans);
			// foreach ($plans as $key => $value) {
			// 	$this->Project->FileProcess->virtualFields = array(
			// 		// 'total_completed_files'=>'select count(*) from file_processes where project_process_plan_id LIKE "'.$key.'" and (current_status = 1 or current_status = 5) and DATE(start_time) = "' . $date .'"',
			// 		'total_files'=>'select count(&) from project_files where project_id LIKE "'.$project_id.'"'
			// 	);
			// 	$val[$value] = $this->Project->FileProcess->find('first',array(
			// 		'fields'=>array(
			// 			'FileProcess.id',
			// 			// 'FileProcess.total_completed_files',
			// 			'FileProcess.total_files'
			// 		),
			// 		'conditions'=>array(
			// 			'FileProcess.project_process_plan_id'=>$key,
			// 			'FileProcess.current_status'=>array(1,5),
			// 			'DATE(FileProcess.start_time)'=>$date
			// 		)
			// 	));
			// }
			// $v = '"' .base64_encode(json_encode($val)) .'"';
			// return $v;	
		}else{
			$this->loadModel('ProjectProcessPlan');
			$plans = $this->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
				'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
			));
			// debug($plans);
			$x = 0;
			foreach ($plans as $key => $value) {
				$this->Project->FileProcess->virtualFields = array(
					'total_completed_files'=>'select count(*) from file_processes where project_process_plan_id LIKE "'.$key.'" and (current_status = 1 or current_status = 5) AND 
						(
						DATE(start_time) BETWEEN "'.date('Y-m-d',strtotime($start_date)).'" AND "'.date('Y-m-d',strtotime($end_date)).'" 
						OR 
						DATE(end_time) BETWEEN "'.date('Y-m-d',strtotime($start_date)).'" AND "'.date('Y-m-d',strtotime($end_date)).'" 
						)',
					'total_files'=>'select count(*) from project_files where project_id LIKE "'.$project_id.'"'

				);
				$val["(".$x.") " .$value] = $this->Project->FileProcess->find('first',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.total_completed_files',
						'FileProcess.total_files'
					),
					'group'=>array('FileProcess.project_file_id'),
					'conditions'=>array(
						'FileProcess.project_process_plan_id'=>$key,
						'FileProcess.current_status'=>array(1,5),
						// 'DATE(FileProcess.start_time)'=>$date
					)
				));
				$x++;
			}
			// $v = '"' .base64_encode(json_encode($val)) .'"';
			// debug($val);
			// exit;
			return $val;	
		}
		// $this->autoRender = false;		
	}	

	public function qc_completed($date = null, $project_id = null){

		$cnt = $this->Project->FileProcess->find('count',array(
			'conditions'=>array(
				'DATE(FileProcess.start_time)'=>date('Y-m-d',strtotime($date)),
				'FileProcess.project_id'=>$project_id,
				'ProjectProcessPlan.qc'=>1
			)
		));
		return $cnt;
	}

	public function merging_completed($date = null, $project_id = null){

		$cnt = $this->Project->FileProcess->find('count',array(
			'conditions'=>array(
				'DATE(FileProcess.start_time)'=>date('Y-m-d',strtotime($date)),
				'FileProcess.project_id'=>$project_id,
				'ProjectProcessPlan.qc'=>2
			)
		));
		return $cnt;
	}

	public function planed_consumed($date = null, $project_id = null){
		// Configure::write('debug',1);
		$this->Project->virtualFields = array(
			'estimated_manhours'=>'select SUM(estimated_manhours) from project_overall_plans where project_overall_plans.project_id LIKE Project.id',
			'resource_count'=>'select count(*) from project_resources where project_resources.project_id LIKE Project.id',
			'span'=>'select DATEDIFF(end_date,start_date) from projects where projects.id LIKE Project.id'
		);
		$project = $this->Project->find('first',array(
			'fields'=>array(
				'Project.id',
				'Project.estimated_manhours',
				'Project.resource_count',
				'Project.daily_hours',
				'Project.span'
			),
			'recursive'=>-1,
			'conditions'=>array('Project.id'=>$project_id)
		));
		debug($project);
		if($project['Project']['daily_hours'] == 1)$hrs = 8;
		else $hrs = 12;
		debug($project['Project']['resource_count'] * $hrs * $project['Project']['span']);
		exit;

	}

	public function daily_time_log_daily($project_id = null, $milestone_id = null,$start_date = null,$end_date = null){
		
		if ($this->request->is('post')) {
			// Configure::write('debug',1);
			$project_id = $this->request->data['Project']['project_id'];
			$this->set('project_id',$project_id);

			$dates = explode(" - ", $this->request->data['Project']['dates']);
			$start_date = date('Y-m-d',strtotime($dates[0]));
			$end_date = date('Y-m-d',strtotime($dates[1]));


			$this->set(array('start_date'=>$start_date,'end_date'=>$end_date));			
			// debug($start_date);
			// debug($end_date);
			while (strtotime($start_date) <= strtotime($end_date)) {

				// $data[$start_date] = $this->daily_time_log($project_id,$milestone_id,base64_encode($start_date),base64_encode($end_date),base64_encode($start_date));

				if($this->request->data['Project']['type'] == 0){
					$data[$start_date] = $this->daily_time_log_total($project_id,$milestone_id,base64_encode($start_date),base64_encode($end_date),base64_encode($start_date),null);
					$topDownData[$start_date] = $this->top_down($project_id,$milestone_id,base64_encode($start_date),base64_encode($end_date),base64_encode($start_date),1);
					$start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));					
				}else{

					$data[date('W',strtotime($start_date))] = $this->daily_time_log_total($project_id,$milestone_id,base64_encode($start_date),base64_encode($end_date),base64_encode($start_date),1);
					$topDownData[date('W',strtotime($start_date))] = $this->top_down($project_id,$milestone_id,base64_encode($start_date),base64_encode($end_date),base64_encode($start_date));

					$start_date = date("Y-m-d", strtotime("+1 week", strtotime($start_date)));	
				}
				
			}
			$this->set('data',$data);
			$this->set('topDownData',$topDownData);
			$graphDataProjectEmployee = $this->resourceGraph($project_id,null);
			// Configure::write('debug',1);
			// debug($graphDataProjectEmployee);
			$this->set('graphDataProjectEmployee',$graphDataProjectEmployee);
			// $this->set($graphDataProjectEmployee ,$this->resourceGraph($project_id,null));

			$allprocesses = $this->get_comp($project_id,null);
			$this->set('allprocesses',$allprocesses);

			$fileProcessCompleted = $this->get_total_files_completed($project_id,date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1])));
			$this->set('fileProcessCompleted',$fileProcessCompleted);
			// Configure::write('debug',1);
			// debug($allprocess);
			// exit;

			$prowiseunitscompleteds = $this->_get_comp_pro_wise($project_id,date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1])));
			$this->set('prowiseunitscompleteds',$prowiseunitscompleteds);

			$holidays = $this->holiday_days($project_id,date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1])));
			$this->set('holidays',$holidays);
			// Configure::write('debug',1);
			// debug($prowiseunitscompleteds);
			// exit;				

		}else if($this->request->params['pass'][0]){

			Configure::write('debug',1);
			
			$project_id = $this->request->params['pass'][0];
			// debug($project_id);
			$this->set('project_id',$project_id);

			$pro = $this->Project->find('first',array(
				'conditions'=>array('Project.id'=>$project_id),
				'fields'=>array('Project.id','Project.start_date','Project.end_date'),
				'recursive'=>-1
			));

			debug($pro);
			// exit;

			// $dates = explode(" - ", $this->request->data['Project']['dates']);
			// $dates[0] = $pro['Project']['start_date'];
			// $dates[1] = $pro['Project']['end_date'];

			$dates[0] = date('Y-m-d',strtotime('-1 month'));
			$dates[1] = date('Y-m-d');
				

			$start_date = $dates[0];
			$end_date = $dates[1];
			// debug($dates);

			// exit;

			$this->set(array('start_date'=>$start_date,'end_date'=>$end_date));

			while (strtotime($start_date) <= strtotime($end_date)) {

				// $data[$start_date] = $this->daily_time_log($project_id,$milestone_id,base64_encode($start_date),base64_encode($end_date),base64_encode($start_date));
				$data[$start_date] = $this->daily_time_log_total($project_id,$milestone_id,base64_encode($start_date),base64_encode($end_date),base64_encode($start_date),null);
				$topDownData[$start_date] = $this->top_down($project_id,$milestone_id,base64_encode($start_date),base64_encode($end_date),base64_encode($start_date),1);
				$start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));	
			}

			// exit;

			$this->set('data',$data);
			$this->set('topDownData',$topDownData);
			$graphDataProjectEmployee = $this->resourceGraph($project_id,null);

			// exit;
			// Configure::write('debug',1);
			// debug($graphDataProjectEmployee);
			$this->set('graphDataProjectEmployee',$graphDataProjectEmployee);
			// $this->set($graphDataProjectEmployee ,$this->resourceGraph($project_id,null));

			$allprocesses = $this->get_comp($project_id,null);
			$this->set('allprocesses',$allprocesses);

			$fileProcessCompleted = $this->get_total_files_completed($project_id,null);
			$this->set('fileProcessCompleted',$fileProcessCompleted);

			$holidays = $this->holiday_days($project_id,date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1])));
			$this->set('holidays',$holidays);
			// $project_id = null;			
			// $start_date = date('Y-m-d',strtotime('-15 days'));
			// $end_date = date('Y-m-d');

			

			// $this->Session->setFlash(__('Select project.'));
			// $this->redirect(array('action' => 'daily_time_log_daily'));
			

		}else{
			// echo "sad";
		}

		$projects = $this->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$this->set(compact('projects'));

		// echo ">>" .$project_id;

		

		

		

		// Configure::write('debug',1);
		// debug($topDownData);
		// exit;
	}

	public function daily_time_log_total($project_id = null, $milestone_id = null,$start_date = null,$end_date = null,$date = null,$type = null){
		$this->loadModel('FileProcess');
		// Configure::write('debug',1);
		// debug($type);
		// debug(base64_decode($start_date));
		// debug(base64_decode($end_date));
		// debug(base64_decode($date));

		// get all processes
		// $this->loadModel('ProjectProcessPlan');
		// $plans = $this->ProjectProcessPlan->find('list',array(
		// 	'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
		// 	'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
		// ));
		// debug($plans);
		// foreach ($plans as $key => $value) {
		// 	$this->FileProcess->virtualFields = array(

		// 	);
		// }

		// select if(status = 'Y', 'active'  , if(status = 'N','not active',"may be not active")) from emp_table;

		// get project shift hours / #of resources
		$this->Project->virtualFields = array(
			'resources'=>'select count(*) from project_resources where project_resources.project_id LIKE Project.id'
		);
		$prodetails = $this->Project->find('first',array(
			'conditions'=>array(),
			'recursive'=>-1,
			'fields'=>array('Project.id','Project.daily_hours','Project.resources')
		));
		if($shift_hours == 0){
			$expected = $prodetails['Project']['resources'] * 8;
		}elseif($shift_hours == 1){
			$expected = $prodetails['Project']['resources'] * 12;
		}
		// debug($expected);
		// exit;

		$this->set('expected_time',$expected);
		if($date){

			// echo base64_decode($date) ."<br />";

			$this->FileProcess->virtualFields = array(

				// 'total_time'=> '					
				// 	IF(start_time != NULL) then
				// 			select SUM(TIMEDIFF(end_time,start_time)) from file_processes;
				// 	ELSE
				// 		select SUM(TIMEDIFF(created,start_time)) from file_processes;
				// 	END IF',

				'expected'=>$expected,

				//current working
				// 'total_time' => 'SELECT SUM(TIMEDIFF(end_time,start_time)) FROM `file_processes` where DATE(start_time) = "'. base64_decode($date).'" AND `file_processes`.`project_id` LIKE "%'.$project_id.'%"',

				// new 
				'total_time' => $this->file_duration_by_project($project_id,base64_decode($date),null,false),

				'delayed_total_time' => $this->file_duration_by_project($project_id,base64_decode($date),null,true),
				
				// 'units_completed' => 'SELECT SUM(units_completed) FROM file_processes where  DATE(start_time) LIKE "%' .base64_decode($date).'%" OR DATE(end_time) LIKE "%'. base64_decode($date).'%" AND project_id = "'.$project_id.'"',

				'units_completed' => 'SELECT SUM(units_completed) FROM file_processes where  DATE(assigned_date) LIKE "%' .base64_decode($date).'%" AND project_id = "'.$project_id.'"',
				
				'hold_time' => 'SELECT SUM(TIMEDIFF(hold_end_time,hold_start_time)) FROM file_processes where (hold_end_time is NOT NULL AND hold_start_time is NOT NULL AND DATE(modified) = "' .base64_decode($date).'" OR DATE(created) = "'. base64_decode($date).'") AND project_id ="'.$project_id.'"',

				'est_time' => 'SELECT SUM(estimated_time) FROM `file_processes`',				

				// take from overall plan matrix
				'plan_expected'=>'SELECT round(AVG(overall_metrics) * AVG(estimated_manhours)) from `project_process_plans` where `project_process_plans`.`id` = FileProcess.`project_process_plan_id` AND project_id ="'.$project_id.'"',
				
				'qc_done'=>'SELECT count(*) from `file_errors` where  DATE(`file_errors`.`created`) = "'.base64_decode($date).'" GROUP BY `file_errors`.`project_file_id` AND project_id ="'.$project_id.'"',

				'qc_completed'=> $this->qc_completed(base64_decode($date),$project_id),

				'merging_completed'=> $this->merging_completed(base64_decode($date),$project_id),

				'total_files'=>'SELECT count(*) from `project_files` where project_id = "'.$project_id.'"',
				
				'try' => $this->getd($project_id,base64_decode($date)),
				'process_wise_total_units' => $this->get_comp($project_id,base64_decode($date)),
			);			
		}else{
			$this->FileProcess->virtualFields = array(
				'expected'=>$expected,
				'total_time' => 'SELECT SUM(TIMEDIFF(end_time,start_time)) FROM file_processes WHERE end_time is NOT NULL',
				'units_completed' => 'SELECT SUM(units_completed) FROM file_processes',
				'hold_time' => 'SELECT SUM(TIMEDIFF(hold_end_time,hold_start_time)) FROM file_processes where hold_end_time is NOT NULL AND hold_start_time is NOT NULL ',
				'overall_metrics'=>'SELECT AVG(overall_metrics) from `project_process_plans` where `project_process_plans`.`id` = FileProcess.`project_process_plan_id`',
				// 'est_time' => 'SELECT SUM(estimated_time) FROM `file_processes`',
				// 'plan_expected'=>'SELECT round(AVG(overall_metrics) * AVG(estimated_manhours)) from `project_process_plans` where `project_process_plans`.`id` = FileProcess.`project_process_plan_id`',
				// 'qc_done'=>'SELECT count(*) from `file_errors` where  DATE(`file_errors`.`created`) = "'.base64_decode($date).'" GROUP BY `file_errors`.`project_file_id`',
				// 'total_files'=>'SELECT count(*) from `project_files`',
				// 'try' => 0,
				// 'qc_completed'=> 0,
				// 'merging_completed'=> 0,
				// 'process_wise_total_units' => 0,
				// 'delayed_total_time' => 0,
			);	
		}

		$files = $this->FileProcess->find('first',array(
			'recursive'=>-1,
			'fields'=>array(
				'FileProcess.id',				
				'FileProcess.total_time',
				'FileProcess.units_completed',
				'FileProcess.hold_time',
				'FileProcess.est_time',
				'FileProcess.plan_expected',
				'FileProcess.qc_done',
				'FileProcess.total_files',
				'FileProcess.try',
				'FileProcess.qc_completed',
				'FileProcess.merging_completed',
				'FileProcess.expected',
				'FileProcess.process_wise_total_units',
				'FileProcess.delayed_total_time'
			),
			'conditions'=>array(
				'FileProcess.project_id'=>$project_id,
				'FileProcess.total_time > ' => 0
			),
			'group' => array('FileProcess.start_time')
		));
		// $this->set('files',$files);
		// Configure::write('debug',1);
		// debug($files);
		return $files;
		
	}

	public function get_childs($ids = null){
		$this->autoRender = false;
		// Configure::write('debug',1);



		$str = '<option value="-1">Select</option>';
		$ids = base64_decode($this->request->params['named']['ids']);
		$ids = explode(',', $ids);
		// foreach ($ids as $id) {
		$employees = $this->Project->Employee->find('list',array('conditions'=>array('Employee.parent_id'=>$ids)));
		// }
		foreach ($employees as $key => $value) {
			$str .= '<option value="'.$key.'">'.$value.'</option>';
		}
		// debug($employees);

		echo $str;
		// print_r(json_decode($this->request->params['named']['ids']));

	}

	public function get_pls($id = null){
		$this->autoRender = false;
		// Configure::write('debug',1);
		// debug($this->request->params['named']['id']);
		$employee = $this->Project->Employee->find('first',array('fields'=>array('Employee.id','Employee.parent_id'), 'recursive'=>-1, 'conditions'=>array('Employee.id'=>$this->request->params['named']['id'])));

		$parent = $this->Project->Employee->find('first',array('fields'=>array('Employee.id'), 'recursive'=>-1, 'conditions'=>array('Employee.id'=>$employee['Employee']['parent_id'])));
		// debug($parent);
		return $parent['Employee']['id'];

	}


	public function removeemployee($project_employee_id = null, $employee_id = null, $cnt = null){
		$this->autoRender = false;
		// get pro details 
		$proEmp = $this->Project->ProjectEmployee->find('first',array('conditions'=>array('ProjectEmployee.id'=>$project_employee_id)));
		// Configure::write('debug',1);
		// debug($employee_id);
		// exit;

		$this->loadModel('ProjectFile');
		$assignedFiles = $this->ProjectFile->find('all',array(
			'recursive'=>-1,
			'conditions'=>array(
				'ProjectFile.current_status'=>0,
				'ProjectFile.employee_id'=>$employee_id,
				'ProjectFile.project_id'=>$proEmp['ProjectEmployee']['project_id'],
			)
		));
		// debug($assignedFiles);
		foreach ($assignedFiles as $assignedFile) {
			$pros = $this->ProjectFile->FileProcess->find('all',array(
				'conditions'=>array(
					'FileProcess.project_file_id'=>$assignedFile['ProjectFile']['id'],
					'FileProcess.employee_id'=>$employee_id,
					'FileProcess.current_status'=>0,
				),
				'recursive'=>-1,
			));
			foreach ($pros as $pro) {
				$this->ProjectFile->FileProcess->create();
				$pro['FileProcess']['current_status']  = 10;
				$pro['FileProcess']['employee_id']  = 'Not Assigned';
				$this->ProjectFile->FileProcess->save($pro,false);
			}

			$this->ProjectFile->create();
			$assignedFile['ProjectFile']['current_status']  = 10;
			$assignedFile['ProjectFile']['employee_id']  = 'Not Assigned';
			debug($assignedFile);
			$this->ProjectFile->save($assignedFile,false);
		}



		$this->Project->ProjectEmployee->deleteAll(array('ProjectEmployee.id'=>$proEmp['ProjectEmployee']['id']));
			
		return 0;
		// }
	}


	public function updatefilecategory($id = null, $file_category_id = null){

		$this->autoRender = false;
		
		$cat = $this->Project->ProjectFile->FileCategory->find('first',array('recursive'=>-1,'conditions'=>array('FileCategory.id'=>$file_category_id)));
		if($cat){
			$file = $this->Project->ProjectFile->find('first',array('recursive'=>-1,'conditions'=>array('ProjectFile.id'=>$id)));
				if($file){
					$file['ProjectFile']['file_category_id'] = $file_category_id;
					$file['ProjectFile']['priority'] = $cat['FileCategory']['priority'];
					$this->Project->ProjectFile->create();
					$this->Project->ProjectFile->save($file,false);
					return $cat['FileCategory']['priority'];
				}else{
					return false;
				}	
		}else{
			return false;
		}
	}


	public function getpriority($file_category_id = null){
		$this->autoRender = false;
		$this->loadModel('FileCategory');
		$cat = $this->FileCategory->find('first',array('conditions'=>array('FileCategory.id'=>$file_category_id),'recursive'=>-1));
		if($cat){
			return $cat['FileCategory']['priority'];
		}else{
			return 0;
		}
		// exit;
	}

	public function holdcat($id = null,$stuatus = null){

		$this->autoRender = false;
		$this->loadModel('FileCategory');
		
		$cat = $this->FileCategory->find('first',array(
			'recursive'=>-1,
			'conditions'=>array('FileCategory.id'=>$id)
		));

		$status = $this->request->params['pass'][1];
		// Configure::write('debug',1);
		// // debug($status);
		// debug($cat);

		if($cat){
			if($status == 1){
				$cat['FileCategory']['status'] = 1;
				$data = 'On Hold';

				// check if there are files in other categories which are not assigned and assigne them to these files
				// Configure::write('debug',1);
				
				$existingfiles = $this->Project->ProjectFile->find('all',array(
					'conditions'=>array(
						'ProjectFile.file_category_id' => $id,
						'ProjectFile.project_id'=>$cat['FileCategory']['project_id'],
						'ProjectFile.milestone_id'=>$cat['FileCategory']['milestone_id'],
						'ProjectFile.employee_id !=' => 'Not Assigned',
					)
				));
				
				foreach ($existingfiles as $existingfile) {
					// debug($existingfile['ProjectFile']['employee_id']);
					$employees[] = $existingfile['ProjectFile']['employee_id'];
				}
				// debug($existingfiles);
				// debug($employees);


				// pending to check if cat on holg
				$this->Project->ProjectFile->virtualFields = array(
					'existingprocesses'=>'select count(*) from file_processes where file_processes.project_file_id LIKE ProjectFile.id',
					'cat_on_hold'=>'select `file_categories`.`status` from `file_categories` where `file_categories.id` = ProjectFile.file_category_id',
				);

				$files = $this->Project->ProjectFile->find('all',array(
					'conditions'=>array(
						'ProjectFile.file_category_id !=' => $id,
						'ProjectFile.project_id'=>$cat['FileCategory']['project_id'],
						'ProjectFile.milestone_id'=>$cat['FileCategory']['milestone_id'],
						'ProjectFile.employee_id' => 'Not Assigned',
						'ProjectFile.existingprocesses'=>0,
						'ProjectFile.cat_on_hold'=>0
					)
				));

				// debug($files);
				// exit;

				$x = 0;
				$this->loadModel('FileProcess');
				foreach ($files as $file) {
					if($employees[$x]){
						$file['ProjectFile']['employee_id'] = $employees[$x];
						$file['ProjectFile']['assigned_date'] = date('Y-m-d H:i:s');
						$file['ProjectFile']['current_status'] = 0;


						$this->Project->ProjectFile->ProjectProcessPlan->virtualFields = array(
				            'op'=>'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id',
				            'plan_check'=>'select count(*) from `project_overall_plans` where `project_overall_plans`.`id` LIKE  ProjectProcessPlan.project_overall_plan_id AND `project_overall_plans.milestone_id` LIKE "'.$file['ProjectFile']['milestone_id'].'" '
				        );


						if($this->Project->ProjectFile->save($file)){
							$fp = array();
							$this->loadModel('FileProcess');

							$project_process_plan_id = $file['ProjectFile']['project_process_plan_id'];

							$projectProcesses = $this->Project->ProjectFile->ProjectProcessPlan->find('first',array(
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
							$fp['FileProcess']['units_completed'] = 0;
							$fp['FileProcess']['project_id'] = $file['ProjectFile']['project_id'];
							$fp['FileProcess']['milestone_id'] = $file['ProjectFile']['milestone_id'];
							$fp['FileProcess']['employee_id'] = $employees[$x];
							$fp['FileProcess']['assigned_date'] = date('Y-m-d h:i:s');
							$fp['FileProcess']['estimated_time'] = $estimated_time;
							$fp['FileProcess']['project_process_plan_id'] = $project_process_plan_id;
							$fp['FileProcess']['project_file_id'] = $file['ProjectFile']['id'];
							$fp['FileProcess']['comments'] = 'NIL (holdcat)';
							$fp['FileProcess']['prepared_by'] = $this->Session->read('User.employee_id');
							$fp['FileProcess']['publish'] = 1;
							$fp['FileProcess']['soft_delete'] = 0;
							debug($fp);
							$this->FileProcess->create();
							$this->FileProcess->save($fp,false);
					}
						
						$x++;
						// rest all aother files for this user as queued
						// $this->queue_other_files($employee['Employee']['id'],$file['ProjectFile']['id'],true,$project_process_plan_id);
					}
				}
				
				// debug($files);
				// exit;

			}else{
				$cat['FileCategory']['publish'] = 1;
				$cat['FileCategory']['status'] = 0;
				$data = 'Released';
			}

			$this->FileCategory->create();
			$this->FileCategory->save($cat,false);
		}

		return $data;
	}



	 /// reports -- 18-02-2021


	public function employee_reports($employee_id = null){


		if ($this->request->is('post')) {
			// Configure::write('debug',1);
			// debug($this->request->data);
			if($this->request->data['Project']['employee_id'] == -1){
				$this->Session->setFlash('Please select employee');
			}

			if($this->request->data['Project']['date_range']){
				$dates = explode(' - ', $this->request->data['Project']['date_range']);
				$start_date = date('Y-m-d H:i:s',strtotime($dates[0]));
				$end_date = date('Y-m-d H:i:s',strtotime($dates[1]));
				debug($start_date);
				debug($end_date);
				$dateCon = 
				array(
					'OR'=>array(
						'FileProcess.start_time BETWEEN ? and ?' => array($start_date,$end_date),
						'FileProcess.assigned_date BETWEEN ? and ?' => array($start_date,$end_date),
						'FileProcess.end_time BETWEEN ? and ?' => array($start_date,$end_date)
					)
				);
				
			}

			// debug($dateCon);

			$projects = $this->Project->ProjectResource->find('list',array(
				'fields'=>array('ProjectResource.id','ProjectResource.project_id'),
				'conditions'=>array('ProjectResource.employee_id'=>$this->request->data['Project']['employee_id'])));


			$this->Project->FileProcess->virtualFields = array(
				'total_time' => 'SELECT SUM(TIMEDIFF(end_time,start_time)) FROM file_processes WHERE end_time is NOT NULL AND `file_processes`.`employee_id` LIKE "%' . $this->request->data['Project']['employee_id'] . '%"',
				'units_completed' => 'SELECT SUM(units_completed) FROM file_processes WHERE `file_processes`.`employee_id` LIKE "%' . $this->request->data['Project']['employee_id'] . '%"',
				'hold_time' => 'SELECT SUM(TIMEDIFF(hold_end_time,hold_start_time)) FROM file_processes where hold_end_time is NOT NULL AND hold_start_time is NOT NULL AND `file_processes`.`employee_id` LIKE "%' . $this->request->data['Project']['employee_id'] . '%"',
				'overall_metrics'=>'SELECT AVG(overall_metrics) from `project_process_plans` where `project_process_plans`.`id` = FileProcess.`project_process_plan_id`'
			);	

			foreach ($projects as $key => $project_id) {
				$res[$project_id] = $this->Project->FileProcess->find('all',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.project_id',
						'FileProcess.milestone_id',
						'FileProcess.project_process_plan_id',
						'FileProcess.start_time',
						'FileProcess.end_time',
						'FileProcess.assigned_date',
						'FileProcess.total_time',
						'FileProcess.units_completed',
						'FileProcess.hold_time',
						'FileProcess.overall_metrics',
						'Project.title',
						'ProjectProcessPlan.process'
					),
					'conditions'=>array(
						'FileProcess.project_id'=> $project_id,
						'FileProcess.employee_id'=> $this->request->data['Project']['employee_id'],
						$dateCon
					),
					'group'=>array(
						'FileProcess.project_process_plan_id'
					)
				));
				debug($res);
			}
			$this->set('res',$res);
			// debug($projects);

		}

		$employees = $this->Project->ProjectResource->Employee->find('list');
		$this->set(compact('employees'));




	}


	public function top_down($project_id = null, $milestone_id = null ,$start_date = null, $end_date = null, $date = null){
		$this->Project->FileProcess->virtualFields = array(
			'total_units_completed'=>'select SUM(units_completed) from file_processes where file_processes.employee_id LIKE FileProcess.employee_id AND DATE(file_processes.start_time) = "'.base64_decode($date).'"'
		);
		$file_processes = $this->Project->FileProcess->find('all',array(
			'fields'=>array(
				'FileProcess.id',
				'FileProcess.total_units_completed',
				'Project.title',
				'Employee.name'
			),
			'conditions'=>array(
				'FileProcess.project_id'=>$project_id,
				'DATE(FileProcess.start_time)'=>base64_decode($date),
			),
			'group'=>array('FileProcess.employee_id'),
			'order'=>array('FileProcess.total_units_completed'=>'DESC')
		));
		return $file_processes;
		// Configure::write('debug',1);
		// debug($file_processes);
		// exit;

	}


	public function f_holidays($project_id = null, $project_file_id = null, $start_date = null,$end_date = null){
		// debug($start_date);
		// debug($project_file_id);
		// debug($end_date);
		// exit;
		if(!$end_time)$end_time = date('Y-m-d H:i:s');
		$this->loadModel('Holiday');
		
		if($end_date){
			$project = $this->Project->find('first',array(
				'fields'=>array(
					'Project.id',
					'Project.daily_hours',
					'Project.weekends',
					// 'Project.start_time',
					// 'Project.end_time',
				),
				'conditions' => array('Project.id'=>$project_id),
				'recursive'=>-1
			));
			// debug($project);
			if($project['Project']['daily_hours'] == 1)$hours = 8;
			else $hours = 12;

			$weekends = json_decode($project['Project']['weekends']);
			// debug($weekends);
			// debug($hours);

			// hours between end - start
			$hourdiff = round((strtotime($end_date) - strtotime($start_date))/3600, 1);

			// days between end - start
			$daysdiff = round((strtotime($end_date) - strtotime($start_date))/3600/24, 1);


			// debug($hourdiff);
			// debug($daysdiff);
			// get weekends
			while (strtotime($start_date) <= strtotime($end_date)) {
			$t = 0;
			
			$start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			// debug($start_date);
			if(in_array(date('N',strtotime($start_date)), $weekends)){
				$hourdiff = $hourdiff - 24;
				$daysdiff = $daysdiff - 1;
			}else{
				// check if its holiday
				$holiday = $this->Holiday->find('first',array('recursive'=>-1, 'conditions'=>array('Holiday.date'=>date('Y-m-d',strtotime($start_date)))));
				if($holiday){
					$hourdiff = $hourdiff - 24;
					$daysdiff = $daysdiff - 1;	
				}
			}

			

		}

		// actual hours
		// debug($daysdiff * $hours);
		// Configure::write('debug',1);
		// debug($daysdiff);
		
		// return ($hourdiff * 60 * 60 );
		

			// debug($hourdiff);
			// debug($daysdiff);
			// debug($hourdiff/24);


		}
	}

	public function file_duration_by_project($project_id = null,$start_date = null, $end_date = null,$delayed = null){
		// Configure::write('debug',1);
		// $start_date  = "2021-02-10";
		// $end_date  = date('Y-m-d');
		// exit;
		// $this->Project->FileProcess->virtualFields = array(
		// 	'file_name'=>'select name from project_files where id LIKE FileProcess.project_file_id'
		// );
		// echo $start_date;
		if($delayed == false){
			$fileProcesses = $this->Project->FileProcess->find('all',array(
				'recursive'=>-1,
				'fields'=>array('FileProcess.id','FileProcess.project_file_id'),
				'conditions'=>array(
					'FileProcess.project_id'=>$project_id,
					'DATE(FileProcess.start_time)'=>$start_date,
					'FileProcess.current_status'=>array(1,5)
				)
			));
			$total_time = 0;
			// debug($end_date);
			// exit;
			foreach ($fileProcesses as $fileProcess) {
				// debug($project_file_id);
				$timeid[$fileProcess['FileProcess']['project_file_id']] =  $this->file_duration($fileProcess['FileProcess']['project_file_id'],$start_date,$end_date);
				// $timeid[$id] = $this->file_duration($project_id);
				// debug($time);
			}
			foreach ($timeid as $key => $value) {
				$total_time = $total_time + $value;
			}
			// echo $total_time . '<br />';
			// debug($time);
			// debug(count($time));
			return $total_time;
			// debug($timeid);
			// debug(count($timeid));	
		}else{	

			// only for delayed files

			$fileProcesses = $this->Project->FileProcess->find('all',array(
			'recursive'=>-1,
			'fields'=>array('FileProcess.id','FileProcess.project_file_id','FileProcess.assigned_date'),
			'conditions'=>array(
				'FileProcess.project_id'=>$project_id,
				'DATE(FileProcess.assigned_date)'=>$start_date,
				'FileProcess.current_status !='=>array(1,5)
			)
		));
		$total_time = 0;
		// debug($end_date);
		// exit;
		foreach ($fileProcesses as $fileProcess) {
			// debug($project_file_id);
			// $timeid[$fileProcess['FileProcess']['project_file_id']] =  $this->delayed_file_duration($fileProcess['FileProcess']['project_file_id'],$start_date,$end_date);
			$timeid[$start_date][] =  $this->delayed_file_duration($fileProcess['FileProcess']['project_file_id'],$start_date,$end_date);
			// $timeid[$id] = $this->file_duration($project_id);
			// debug($time);
		}
		// Configure::write('debug',1);
		// debug($timeid);
		foreach ($timeid as $key => $value) {
			$total_time = count($value);
		}
		// echo $total_time . '<br />';
		// debug($time);
		// debug(count($time));
		return $total_time;
		// debug($timeid);
		// debug(count($timeid));
		}

		
		
		
		// exit;
	}


	public function file_duration($project_file_id = null,$start_date = null, $end_date = null){
		// $this->autoRender = false;
		// Configure::write('debug',1);
		// debug($project_file_id);
		// $start_date  = "2021-02-10";
		// $end_date  = date('Y-m-d');
		$times = 0;
		$this->Project->FileProcess->virtualFields = array(
			'file_name'=>'select name from project_files where id LIKE FileProcess.project_file_id'
		);

		if($start_date && $end_date){
			$files = $this->Project->FileProcess->find('all',array(
				'recursive'=>-1,
				'conditions'=>array(
					'FileProcess.project_file_id'=>$project_file_id,
					'DATE(FileProcess.start_time)'=>$start_date,
				)
			));	
		}else{
			$files = $this->Project->FileProcess->find('all',array(
				'fields'=>array(
					'FileProcess.id',
					'FileProcess.project_file_id',
					'FileProcess.start_time',
					'FileProcess.end_time',
					'FileProcess.project_id',
				),
			'recursive'=>-1,
			'conditions'=>array('FileProcess.project_file_id'=>$project_file_id)));
		}
		
		// Configure::write('debug',1);
		// debug($files);
		foreach ($files as $file) {
			if($file['FileProcess']['start_time']){
				// $a = array($project_file_id)
				$holidays[$file['FileProcess']['id']] = array(
					// 'pro_id'=>$file['FileProcess']['id'],
					// 'file'=>$file['FileProcess']['file_name'],
					// 'file_id'=>$file['FileProcess']['project_file_id'],
					// 'start'=>$file['FileProcess']['start_time'],
					// 'end'=>$file['FileProcess']['end_time'],
					'time' => $this->f_holidays($file['FileProcess']['project_id'],$file['FileProcess']['id'],$file['FileProcess']['start_time'],$file['FileProcess']['end_time'])
				);			
			}
		}
		
		foreach ($holidays as $key => $value) {
			$times = $times + $value['time'];
		}
		// debug($times);
		return $times;
		
		exit;

	}

	public function delayed_file_duration($project_file_id = null,$start_date = null, $end_date = null){
		// $this->autoRender = false;
		// Configure::write('debug',1);
		// debug($start_date);
		// debug($project_file_id);
		// $start_date  = "2021-02-10";
		// $end_date  = date('Y-m-d');

		$this->Project->FileProcess->virtualFields = array(
			'file_name'=>'select name from project_files where id LIKE FileProcess.project_file_id'
		);

		if($start_date && $end_date){
			$files = $this->Project->FileProcess->find('all',array(
				'recursive'=>-1,
				'conditions'=>array(
					'FileProcess.project_file_id'=>$project_file_id,
					'DATE(FileProcess.assigned_date)'=>$start_date,
				)
			));	
		}else{
			$files = $this->Project->FileProcess->find('all',array('conditions'=>array('FileProcess.project_file_id'=>$project_file_id)));
		}
		
		// Configure::write('debug',1);
		// debug($files);
		foreach ($files as $file) {
			if($file['FileProcess']['assigned_date'] && !$file['FileProcess']['start_time']){
				// $a = array($project_file_id)
				// $holidays[$file['FileProcess']['id']] = array(
				$holidays[$start_date][] = array(
					'pro_id'=>$file['FileProcess']['id'],
					'file'=>$file['FileProcess']['file_name'],
					'file_id'=>$file['FileProcess']['project_file_id'],
					'start'=>$file['FileProcess']['start_time'],
					'end'=>$file['FileProcess']['end_time'],
					'time' => $this->f_holidays($file['FileProcess']['project_id'],$file['FileProcess']['id'],$file['FileProcess']['assigned_date'],date('Y-m-d'))
				);			
			}
		}

		// debug($holidays);
		
		foreach ($holidays as $key => $value) {
			$times = $times + $value['time'];
		}
		// debug($start_date);
		return $times;
		
		// exit;

	}

	public function _get_process_details($project_id = null, $project_file_id = null , $qc = null){
		// $processes = $this->Project->ProjectProcessPlan->find('list',array(
		// 	'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
		// 	'conditions'=>array(
		// 		'ProjectProcessPlan.project_id'=>$this->request->data['Project']['project_id'],
		// 		'ProjectProcessPlan.qc'=>$key
		// 	)));
		// debug($project_file_id);
		$fileProcess = $this->Project->FileProcess->find('all',array(
			'recursive'=>-1,
			'conditions'=>array('FileProcess.project_id'=>$project_id)
		));
		// $fileProcess = array('asdas','asd');
		$v = '"' . base64_encode(json_encode($fileProcess)) . '"';
		return $v;
	}


	public function tracker($project_id = null, $start_date = null, $end_date = null){
		if ($this->request->is('post')) {
			if($this->request->data['Project']['project_id']){
				// echo $this->request->data['Project']['project_id'];
				// Configure::write('debug',1);
				// debug($this->request->data);
				if($this->request->data['Project']['dates']){
					$dates = explode(' - ', $this->request->data['Project']['dates']);
					debug($dates);
					$start_date = date('Y-m-d',strtotime($dates[0]));
					$end_date = date('Y-m-d',strtotime($dates[1]));
				}

				// exit;
				
				$processes = $this->Project->ProjectProcessPlan->find('list',array(
				'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
				'conditions'=>array(
					'ProjectProcessPlan.project_id'=>$this->request->data['Project']['project_id'],
					// 'ProjectProcessPlan.qc'=>$key
				)));

				if($this->request->data['Project']['milestone_id']){
					$milestones = $this->Project->Milestone->find(
						'all',array(
							'recursive'=>-1,
							'conditions'=>array('Milestone.project_id'=>$this->request->data['Project']['project_id'],
								'Milestone.id'=>$this->request->data['Project']['milestone_id']
						),
							'order'=>array('Milestone.start_date'=>'ASC')
					));
				}else{
					echo "Select milestone_estimate";
					exit;
				}

				// debug($milestones);
				// debug($processes);
				$qcs  = array(0=>'General',1=>'QC',2=>'Merging');

				foreach ($milestones as $milestone) {

					// first get processes
					$mprocesses = $this->Project->ProjectProcessPlan->find('list',array(
							'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
							'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
							'conditions'=>array(
								'ProjectProcessPlan.project_id'=>$this->request->data['Project']['project_id'],
								'ProjectProcessPlan.milestone_id'=>$milestone['Milestone']['id'],
								// 'ProjectProcessPlan.qc'=>$key
							)));


					$fileErrors = $this->Project->FileErrorMaster->find('list',array(
							// 'fields'=>array('FileErrorMaster.id','ProjectProcessPlan.process'),
							// 'order'=>array('ProjectProcessPlan.sequence'=>'ASC'),
							'conditions'=>array(
								'FileErrorMaster.project_id'=>$this->request->data['Project']['project_id'],
								'FileErrorMaster.milestone_id'=>$milestone['Milestone']['id'],
								// 'ProjectProcessPlan.qc'=>$key
							)));

					$aa = array();					
					
					$table[$mvalue] = $mprocesses;


					// 'mandays'=>'select (DATEDIFF(ProjectEmployee.end_date,ProjectEmployee.start_date) + 1 * 60 * 24)',

					// foreach ($mprocesses as $mpkey => $mpvalue) {
					// 	$aa[$mpkey] = 'select (DATEDIFF (
					// 		(select end_time from file_processes where file_processes.project_file_id LIKE ProjectFile.id AND file_processes.project_process_plan_id LIKE "'.$mpkey.'" AND end_time != "" ORDER BY end_time DESC LIMIT 1),
					// 		(select start_time from file_processes where file_processes.project_file_id LIKE ProjectFile.id AND file_processes.project_process_plan_id LIKE "'.$mpkey.'" AND start_time != "" ORDER BY start_time ASC LIMIT 1)							
					// 	)
					// )';
					// }

					// debug($aa);

					if($this->request->data['Project']['city_id'] != -1){
						$c = array('ProjectFile.city'=>$this->request->data['Project']['city_id']);
					}else{
						$c = array();
					}

					if($this->request->data['Project']['block_id'] != -1){
						$b = array('ProjectFile.block'=>$this->request->data['Project']['block_id']);
					}else{
						$b = array();
					}

					$files = $this->Project->ProjectFile->find('list',array(
						'order'=>array('ProjectFile.priority'=>'ASC'),
						'conditions'=>array(
							// 'ProjectFile.current_status !='=>4,
							'ProjectFile.created BETWEEN ? and ?' => array($start_date,$end_date),
							'ProjectFile.project_id'=>$this->request->data['Project']['project_id'],
							'ProjectFile.milestone_id'=>$this->request->data['Project']['milestone_id'],
							$c,$b
						),
						'recursive'=>-1
					));

					// $processes[$mvalue]['Files']
					$x = 0;
					foreach ($files as $fkey => $fvalue) {

						$this->Project->ProjectFile->virtualFields = array(
							'uc'=>'select SUM(units_completed) from file_processes where start_time between DATE('.$start_date.') and DATE('.$end_date.') AND file_processes.project_file_id LIKE ProjectFile.id AND file_processes.project_process_plan_id != ""',
						);


						$file = $this->Project->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=>$fkey),'recursive'=>-1));
						$allFiles[$milestone['Milestone']['id']]['Milestone'] = $milestone['Milestone'];
						$allFiles[$milestone['Milestone']['id']]['Files'][$x]['File'] = $file['ProjectFile'];

						foreach ($mprocesses as $mpkey => $mpvalue) {
							// echo $mpkey .'<br />';
							// echo $mpvalue .'<br />';
							// $allFiles[$milestone['Milestone']['id']]['Files'][$x]['Processess'][$mpvalue]['total_hold'] = 0;
							$this->Project->FileProcess->virtualFields = array(
								'total_hold'=>'select SUM(HOUR(TIMEDIFF(hold_end_time,hold_start_time))) from file_processes where hold_start_time between DATE('.$start_date.') and DATE('.$end_date.') AND file_processes.project_file_id LIKE FileProcess.project_file_id AND file_processes.project_process_plan_id LIKE "'.$mpkey.'"',

								// 'process_end_time'=>'select `end_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`end_time` IS NOT NULL ORDER BY file_processes.sr_no DESC LIMIT 1',
								// 'total_time'=>'select (timediff((select `end_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`end_time` IS NOT NULL ORDER BY file_processes.sr_no DESC LIMIT 1),(select `start_time` from `file_processes` where `file_processes`.`project_file_id` LIKE ProjectFile.id AND `file_processes`.`start_time` IS NOT NULL ORDER BY file_processes.sr_no ASC LIMIT 1)))',
								
								'actual_time_from_process' => 'select SEC_TO_TIME(SUM(TIME_TO_SEC(actual_time))) from file_processes where file_processes.project_process_plan_id LIKE "'.$mpkey.'" AND file_processes.project_file_id LIKE "'.$fkey.'" AND start_time IS NOT NULL AND end_time IS NOT NULL',
								
								// 'hold_time_from_process' => 'select SEC_TO_TIME(SUM(TIME_TO_SEC(hold_time))) from file_processes where file_processes.project_file_id LIKE ProjectFile.id AND hold_time IS NOT NULL AND hold_type_id != "60478d96-efa8-4ff7-9b19-7507ac100145"',

							);

							$start = $this->Project->FileProcess->find('first',array(
								'recursive'=>-1,
								'order'=>array('FileProcess.assigned_date'=>'ASC'),
								'conditions'=>array(
									'FileProcess.start_time BETWEEN ? and ?' => array($start_date,$end_date),
									'FileProcess.start_time !=' => null,
									'FileProcess.project_file_id'=>$fkey,
									'FileProcess.project_process_plan_id'=>$mpkey
								)
							));
							
							$end = $this->Project->FileProcess->find('first',array(
								'recursive'=>-1,
								'order'=>array('FileProcess.assigned_date'=>'DESC'),
								'conditions'=>array(
									'FileProcess.start_time BETWEEN ? and ?' => array($start_date,$end_date),
									'FileProcess.start_time !=' => null,
									'FileProcess.project_file_id'=>$fkey,
									'FileProcess.project_process_plan_id'=>$mpkey
								)
							));

							$allFiles[$milestone['Milestone']['id']]['Files'][$x]['Processess'][$mpkey]['start'] = $start['FileProcess'];
							$allFiles[$milestone['Milestone']['id']]['Files'][$x]['Processess'][$mpkey]['end'] = $end['FileProcess'];

							foreach ($fileErrors as $ekey => $evalue) {
								$errorCount[$evalue] = $this->Project->FileError->find('count',array(
									'conditions'=>array(
										'FileError.project_file_id'=>$fkey,
										// 'FileError.file_process_id'=>$fkey,
										'FileError.file_error_master_id'=>$ekey)
								));
							}
							
							$allPros = array();
							$allPros = $this->Project->FileProcess->find('all',array(
								'recursive'=>-1,
								'fields'=>array(									
									'FileProcess.units_completed',
									'FileProcess.employee_id',
									'FileProcess.hold_start_time',
									'FileProcess.hold_end_time',
									'FileProcess.total_hold',
									'FileProcess.actual_time_from_process'
								),
								'order'=>array('FileProcess.assigned_date'=>'DESC'),
								'conditions'=>array(
									'FileProcess.start_time BETWEEN ? and ?' => array($start_date,$end_date),
									'FileProcess.start_time !=' => null,
									'FileProcess.project_file_id'=>$fkey,
									'FileProcess.project_process_plan_id'=>$mpkey
								)
							));

							// debug($allPros);

							$units = 0;

							$allEmmployees = array();
							foreach ($allPros as $pro) {
								$allEmmployees[$pro['FileProcess']['employee_id']] = $pro['FileProcess']['employee_id'];
								$units = $units + $pro['FileProcess']['units_completed'];
								$hold = $pro['FileProcess']['total_hold'];
								// find hold start-end
								// echo ">" . $pro['FileProcess']['total_hold'] . "<br />";
								
							}
							if($allPros){
								$allFiles[$milestone['Milestone']['id']]['Files'][$x]['Processess'][$mpkey]['units_completed'] = $units;
								$allFiles[$milestone['Milestone']['id']]['Files'][$x]['Processess'][$mpkey]['all_members'] = $allEmmployees;
								$allFiles[$milestone['Milestone']['id']]['Files'][$x]['Processess'][$mpkey]['total_hold'] = $hold;								
							}
							$allFiles[$milestone['Milestone']['id']]['Files'][$x]['Errors'] = $errorCount;	
						}
						$hold = 0;						
						$x++;	
					}
					
					// foreach ($qcs as $key => $value) {
					// 	$processes[$mvalue][$value] = $this->Project->ProjectProcessPlan->find('list',array(
					// 		'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
					// 		'conditions'=>array(
					// 			'ProjectProcessPlan.project_id'=>$this->request->data['Project']['project_id'],
					// 			'ProjectProcessPlan.milestone_id'=>$mkey,
					// 			'ProjectProcessPlan.qc'=>$key
					// 		)));
					// }
				}
				
				// Configure::Write('debug',1);
				// debug($allFiles);
				// exit;
				// 
				// $X = 0;
				// foreach ($processes as $key => $value) {
				// 	// debug($value);
				// 	// $this->Project->ProjectFile->virtualFields = array(
				// 	// 	'general' => $this->_get_process_details($this->request->data['Project']['project_id'],@ProjectFile.id,$value),
				// 	// );

				// 	$projectFiles  = $this->Project->ProjectFile->find('list',array(
				// 		'conditions'=>array('ProjectFile.project_id'=>$this->request->data['Project']['project_id']),
				// 		'recursive'=>-1
				// 	));
				// 	$x++;
				// }
			}

			// Configure::write('debug',1);
			// debug($allFiles);
			// // debug($projectFiles);
			// exit;
			// foreach ($projectFiles as $id => $name) {
			// 	$this->Project->FileProcess->virtualFields = array(
			// 		'first_date'=>'select start_time from file_processes where file_processes.project_file_id LIKE FileProcess.project_file_id ORDER BY start_time DESC LIMIT 1',
			// 		'last_date'=>'select end_time from file_processes where file_processes.project_file_id LIKE FileProcess.project_file_id ORDER BY end_time DESC LIMIT 1',
			// 		// 'last_date'=>0,
			// 	);
			// 	$general = $this->Project->FileProcess->find('all',array(
			// 		'contain'=>array('ProjectProcessPlan'),
			// 		'recursive'=>0,
			// 		'conditions'=>array('FileProcess.project_file_id'=>$id,'ProjectProcessPlan.qc'=>0)
			// 	));

			// 	$qc = $this->Project->FileProcess->find('all',array(
			// 		'contain'=>array('ProjectProcessPlan'),
			// 		'recursive'=>0,
			// 		'conditions'=>array('FileProcess.project_file_id'=>$id,'ProjectProcessPlan.qc'=>1)
			// 	));

			// 	$merging = $this->Project->FileProcess->find('all',array(
			// 		'contain'=>array('ProjectProcessPlan'),
			// 		'recursive'=>0,
			// 		'conditions'=>array('FileProcess.project_file_id'=>$id,'ProjectProcessPlan.qc'=>2)
			// 	));

			// 	$file = $this->Project->ProjectFile->find('first',array(
			// 		'recursive'=>-1,
			// 		'conditions'=>array('ProjectFile.id'=>$id)
			// 	));


			// 	$allFiles[$id]['ProjectFile'] = $file['ProjectFile'];
			// 	$allFiles[$id]['general'] = $general;
			// 	$allFiles[$id]['qc'] = $qc;
			// 	$allFiles[$id]['merging'] = $merging;
			// }
			// debug($allFiles);

			if($this->request->data['Project']['project_id']){
				$milestones = $this->Project->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->data['Project']['project_id'])));
				$this->set('milestones',$milestones);
			}

			if($this->request->data['Project']['milestone_id']){
				$cities = $this->Project->ProjectFile->find('list',array(
			    		'fields'=>array('ProjectFile.city','ProjectFile.city'),
			    		'conditions'=>array('ProjectFile.milestone_id'=>$this->request->data['Project']['milestone_id'])));

				$blocks = $this->Project->ProjectFile->find('list',array(
			    		'fields'=>array('ProjectFile.block','ProjectFile.block'),
			    		'conditions'=>array('ProjectFile.milestone_id'=>$this->request->data['Project']['milestone_id'])));

				$this->set('cities',$cities);
				$this->set('blocks',$blocks);
			}

			$this->set('table',$table);
			$this->set('allFiles',$allFiles);
			$this->set('processes',$processes);
			$this->set('project_id',$this->request->data['Project']['project_id']);
			$this->set('milestone_id',$this->request->data['Project']['milestone_id']);
			$this->set('city_id',$this->request->data['Project']['city_id']);
			$this->set('block_id',$this->request->data['Project']['block_id']);
			$this->set('fileStatuses',$this->Project->ProjectFile->customArray['currentStatuses']);

			$deliverableUnits = $this->Project->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));
			$this->set('deliverableUnits',$deliverableUnits);


			$this->set('fileCategories',$this->Project->ProjectFile->FileCategory->find('list',array('conditions'=>array(
			'FileCategory.publish'=>1,
			'FileCategory.soft_delete'=>0,
			// 'FileCategory.status'=>0,
			'FileCategory.project_id'=>$this->request->data['Project']['project_id'],
			// 'FileCategory.milestone_id'=>$milestone_id
			))));

		}

		$projects = $this->Project->find('list',array('conditions'=>array('Project.publish'=>1, 'Project.soft_delete'=>0)));
		$this->set(compact('projects'));

	}


	public function reorg($project_id = null){

    //     $milestones = $this->Project->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$project_id)));
    //     Configure::write('debug',1);
    //     debug($milestones);

    //     $this->loadModel('ProjectProcessPlan');

    //     $this->loadModel('FileProcess');
    //     // skipping over all plan delete condition for now
    //     foreach ($milestones as $key => $value) {

    //     	$plan = $this->ProjectProcessPlan->find('list',array('fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'), 'conditions'=>array('ProjectProcessPlan.milestone_id'=>$key)));
    //     	debug($plan);
    //     	$processes[$value] = $this->FileProcess->find('count',
    //     		array(
    //     			'conditions'=>array(
    //     				'FileProcess.milestone_id'=>$key,
    //     				'ProjectProcessPlan.milestone_id NOT LIKE FileProcess.milestone_id'
    //     			),
    //     			'group'=>array('FileProcess.project_file_id'),
    //     			'order'=>array('FileProcess.created'=>'DESC')
    //     		)       			
    //     	);
    //     }

    //     debug($processes);
    // }

		// Configure::write('debug',1);
        // debug($milestones);

		$projects = $this->Project->find('list',array('order'=>array('Project.created'=>'DESC'),));
		foreach ($projects as $pkey => $pvalue) {
			$milestones = $this->Project->Milestone->find('list',array(
				'conditions'=>array(
					'Milestone.publish'=>1,
					'Milestone.soft_delete'=>0,
					'Milestone.project_id'=>$pkey)));
        

	        $this->loadModel('ProjectProcessPlan');

	        $this->loadModel('FileProcess');
	        // skipping over all plan delete condition for now
	        foreach ($milestones as $key => $value) {

	        	$plan = $this->ProjectProcessPlan->find('list',array('fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'), 'conditions'=>array('ProjectProcessPlan.milestone_id'=>$key)));
	        	// debug($plan);
	        	$processes[$pvalue][$value]['total_files'] = $this->FileProcess->ProjectFile->find('count',
	        		array(
	        			'conditions'=>array(
	        				'ProjectFile.milestone_id'=>$key,
	        				// 'ProjectProcessPlan.milestone_id NOT LIKE FileProcess.milestone_id'
	        			),
	        			// 'group'=>array('FileProcess.project_file_id'),
	        			// 'order'=>array('FileProcess.created'=>'DESC')
	        		)       			
	        	);
	        	$processes[$pvalue][$value]['incorrect_files'] = $this->FileProcess->find('count',
	        		array(
	        			'conditions'=>array(
	        				'FileProcess.milestone_id'=>$key,
	        				'ProjectProcessPlan.milestone_id NOT LIKE FileProcess.milestone_id'
	        			),
	        			'group'=>array('FileProcess.project_file_id'),
	        			'order'=>array('FileProcess.created'=>'DESC')
	        		)       			
	        	);
	        }

	        
		}

        debug($processes);
    }

    public function plan_del_check($id = null){
    	$this->loadModel('FileProcess');
    	$cnt = $this->FileProcess->find('count',array('conditions'=>array('FileProcess.project_process_plan_id'=>$id)));
    	return $cnt;
    }


    public function expected_hours(){
    	$this->loadModel('ProjectFile');
    	$project_id = $this->request->params['named']['project_id'];
    	$date = base64_decode($this->request->params['named']['date']);
    	// Configure::write('debug',1);

    	$this->ProjectFile->virtualFields = array(
    		'total_time' => 'SELECT (SUM(Hour(TIMEDIFF(end_time,start_time)))) FROM file_processes where start_time != "" AND `file_processes`.`project_file_id` = ProjectFile.id AND DATE(start_time) = "'. $date.'"',
    		'pro_start_time' => 'SELECT DATE(start_time) FROM file_processes where end_time is NOT NULL AND file_processes.project_file_id = ProjectFile.id AND DATE(start_time) = "'. $date.'" LIMIT 1',
    		// 'total_time'=>$this->file_duration(ProjectFile.id,$date,$date)
    	);

    	$files = $this->ProjectFile->find('all',array(
    		'recursive'=>1,
    		// 'fields'=>array('ProjectFile.id','ProjectFile.name','ProjectFile.pro_start_time'),
    		'conditions'=>array(
    			'ProjectFile.pro_start_time' => $date,
    			// 'ProjectFile.current_status'=>array(0,1,5,7,10),
    			'ProjectFile.project_id'=>$project_id,
    			'ProjectFile.total_time !='=> NULL,
    		)
    	));

    	// debug($files);
    	// exit;

    	foreach ($files as $file) {
    		$file['ProjectFile']['total_time'] = $this->file_duration($file['ProjectFile']['id'],$date,$date);
    		$tt = $tt + $this->file_duration($file['ProjectFile']['id'],$date,$date);
    		$newFiles[] = $file;
    	}
    	$this->set('files',$newFiles);
    	debug($tt/60/60);

    }

    public function delayed_files(){
    	$this->loadModel('ProjectFile');
    	$project_id = $this->request->params['named']['project_id'];
    	$date = base64_decode($this->request->params['named']['date']);
    	// Configure::write('debug',1);
    	debug($date);
    	debug($project_id);
    	$tt = $this->file_duration_by_project($project_id,$date,$date,false);
    	// $this->ProjectFile->virtualFields = array(
    	// 	'total_time' => 'SELECT (SUM(Hour(TIMEDIFF(end_time,start_time)))) FROM file_processes where end_time is NOT NULL AND file_processes.project_file_id = ProjectFile.id AND DATE(start_time) = "'. $date.'"',
    	// 	'pro_start_time' => 'SELECT start_time FROM file_processes where end_time is NOT NULL AND file_processes.project_file_id = ProjectFile.id AND DATE(start_time) = "'. $date.'"',
    	// 	// 'total_time'=>$this->file_duration(ProjectFile.id,$date,$date)
    	// );

    	// $files = $this->ProjectFile->find('all',array(
    	// 	'recursive'=>-1,
    	// 	// 'fields'=>array('ProjectFile.id','ProjectFile.name','ProjectFile.pro_start_time'),
    	// 	'conditions'=>array(
    	// 		'ProjectFile.project_id'=>$project_id,
    	// 		'ProjectFile.total_time !='=> NULL,
    	// 	)
    	// ));

    	// debug($files);

    	// foreach ($files as $file) {
    	// 	debug($file);
    	// 	// $tt = $tt + $this->file_duration($file['ProjectFile']['id'],$date,$date);
    	// 	$tt[] = $this->file_duration_by_project($project_id,$date,null,false);
    	// 	// $tt =  $this->delayed_file_duration($file['ProjectFile']['id'],$date,$date);
    	// 	debug($tt);
    	// }

    	debug($tt);

    }

    function pro_wise_units_completed(){
    	$project_id = $this->request->params['named']['project_id'];
    	$start_date = base64_decode($this->request->params['named']['start_date']);
    	$end_date = base64_decode($this->request->params['named']['end_date']);

    	$processes = $this->_get_comp_pro_wise($project_id,$start_date,$end_date);

    	// Configure::write('debug',1);
    	debug($processes);
    	foreach($processes as $process ){
    		$files[] = $this->Project->ProjectFile->find('first',array('conditions'=>array('ProjectFile.id'=> $process['FileProcess']['project_file_id'])));
    	}

    	
    	debug($files);
    	exit;
    	// debug($end_date);
    	// debug($x);
    }

    public function project_execution_plan($project_id = null){
    	Configure::write('debug',1);
    	// $project_id = '5ff7ebb5-e5f0-4eea-82e7-5779ac100145';

    	$project = $this->Project->find('first',array(
    		'recursive'=>-1,
    		'conditions'=>array('Project.id'=>$project_id)
    	));


    	$startDate = $project['Project']['start_date'];
    	$endDate = $project['Project']['end_date'];
    	debug($startDate);
    	debug($endDate);
    	if($project['Project']['daily_hours'] == 0)$hours = 8;
    	else $hours = 12;

    	$processes = $this->Project->ProjectProcessPlan->find('list',array('fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'), 'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)));

    	while (strtotime($startDate) <= strtotime($endDate)) {
    		foreach($processes as $pkey => $pvalue){
	    		$projectResources = $this->Project->ProjectResource->find('count',array(
	    				'conditions'=>array(
		    				'ProjectResource.project_id'=>$project_id,
		    				'ProjectResource.process_id'=>$pkey
	    				)
	    			)
	    		);	

	    		$this->Project->FileProcess->virtualFields = array(
	    			'actual_resource'=>'select count(*) from (select * from file_processes where file_processes.project_process_plan_id LIKE "'.$pkey.'" GROUP BY file_processes.employee_id) as cnt',

	    			'actual_time_from_process' => 'select SUM(HOUR(actual_time)) from file_processes where file_processes.project_file_id LIKE ProjectFile.id',
	    			
	    			'toal_units'=>'SELECT SUM(units_completed) from file_processes WHERE file_processes.project_process_plan_id LIKE FileProcess.project_process_plan_id'
	    		);
	    		$fileProcess = $this->Project->FileProcess->find('first',array(
	    			'fields'=>array('FileProcess.id','FileProcess.actual_time_from_process','FileProcess.actual_time','FileProcess.toal_units','FileProcess.actual_resource','FileProcess.project_process_plan_id'),
	    			'conditions'=>array(
	    				'DATE(FileProcess.end_time)'=>date('Y-m-d',strtotime($startDate)),
		    			'FileProcess.project_id'=>$project_id,
		    			'FileProcess.project_process_plan_id'=>$pkey,
	    			)
	    		));

	    		// if($fileProcess){
	    			$resuslt[$pkey][date("W", strtotime($startDate))][$startDate] = array(
		    			'process'=>$pvalue,
		    			'resources'=> $projectResources,
		    			'planned_hours'=> $hours * $projectResources,
		    			'actual_hours'=>$fileProcess['FileProcess']['actual_time_from_process'],
		    			'actual_units'=>$fileProcess['FileProcess']['toal_units'],
		    			'actual_resource'=>$fileProcess['FileProcess']['actual_resource'],
		    		);	
	    		// }
	    		
	    	}
	    	
    		$startDate = date("Y-m-d", strtotime("+1 week", strtotime($startDate)));	
	}

	// debug($processes);
    	debug($resuslt);
    	exit;
    }

    public function user_time_sheet($employee_id = null){
    	$this->loadModel('Employee');

    	if ($employee_id || $this->request->is('post') && $this->request->data['Project']['employee_id']) {

    		if($employee_id){
    			$this->request->data['Project']['employee_id'] = $employee_id;
    			$this->request->data['Project']['search_type'] = 0;
    			$this->request->data['Project']['date_range'] = date('m/d/Y',strtotime("-1 week")) . ' - ' . date('m/d/Y');
    			$this->request->data['Project']['project_id'] = -1;
    			
	    	}

	    	if($this->Session->read('User.is_mr') == 1){
	    		$employee = $this->Employee->find('first',array('recursive'=>-1,'fields'=>array('Employee.id','Employee.name'), 'conditions'=>array('Employee.id'=>$this->request->data['Project']['employee_id'])));	
	    	}else{
	    		$employee = $this->Employee->find('first',array('recursive'=>-1,'fields'=>array('Employee.id','Employee.name'), 'conditions'=>array('Employee.id'=>$this->Session->read('User.employee_id'))));
	    	}
    		
    		
    		
    		$this->Project->ProjectFile->hasMany['FileProcess']['conditions'] = array('FileProcess.employee_id'=>$employee['Employee']['id']);
    		
    		$this->Project->ProjectFile->FileProcess->virtualFields = array(
    			'actual_time_from_process' => 'select SEC_TO_TIME(SUM(TIME_TO_SEC(actual_time))) from file_processes  where file_processes.id LIKE FileProcess.id',
    		);

    		$this->Project->ProjectFile->virtualFields = array(
    			
    			'find_employees'=>'select count(*) from file_processes where file_processes.employee_id LIKE "'.$employee['Employee']['id'].'" ',
    			
    			'units_completed'=>'select SUM(units_completed) from file_processes where  file_processes.employee_id LIKE "'.$employee['Employee']['id'].'" ',
    			
    			'first_assigned'=>'select assigned_date from file_processes where  file_processes.employee_id LIKE "'.$employee['Employee']['id'].'" ORDER BY assigned_date DESC LIMIT 1 ',

    			'last_end_time'=>'select end_time from file_processes where file_processes.end_time IS NOT NULL AND  file_processes.employee_id LIKE "'.$employee['Employee']['id'].'" ORDER BY assigned_date ASC LIMIT 1 ',

    			'last_status'=>'select current_status from file_processes where  file_processes.employee_id LIKE "'.$employee['Employee']['id'].'" ORDER BY sr_no DESC LIMIT 1 ',

    			'last_start_time'=>'select start_time from file_processes where file_processes.start_time IS NOT NULL AND file_processes.employee_id LIKE "'.$employee['Employee']['id'].'" ORDER BY assigned_date ASC LIMIT 1 ',

    			'overall_metrics'=>'SELECT AVG(overall_metrics) from project_process_plans where project_process_plans.id LIKE ProjectFile.project_process_plan_id'
    		);

    		if($employee){

    			if($this->request->data['Project']['result_type'] != 1){
    				$dateRange = explode(' - ',$this->request->data['Project']['date_range']);
	    			$start_date = date('Y-m-d',strtotime($dateRange[0]));
	    			$end_date = date('Y-m-d',strtotime($dateRange[1]));

	    			if($this->request->data['Project']['project_id'] != -1)$condition = array('ProjectFile.project_id'=>$this->request->data['Project']['project_id']);

	    			$daterangecondition = array('DATE(ProjectFile.assigned_date BETWEEN ? AND ?)'=> array(date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date))));
	    			
	    			if($this->request->data['Project']['search_type'] == 0){
	    				// $this->Project->ProjectFile->hasMany['FileProcess']['conditions'][] = array('FileProcess.start_time BETWEEN ? AND ?'=>array($start_date,$end_date));	
	    			}else{
	    				$this->Project->ProjectFile->hasMany['FileProcess']['conditions'][] = array('DATE(FileProcess.start_time)'=>date('Y-m-d',strtotime($this->request->data['Project']['date'])));	
	    			}

	    			if($this->request->params['named']['limit']){
	    				$projectFiles = $this->Project->ProjectFile->find('all',array(
	    				'limit'=>2,
	    				'order'=>array('ProjectFile.assigned_date'=>'DESC'),
	    				'conditions'=>array(
	    					$condition,   
	    					$daterangecondition,
	    					// $datecondition,
	    					'ProjectFile.find_employees >'=>0)));
	    			}else{
	    				$projectFiles = $this->Project->ProjectFile->find('all',array(
	    				// 'limit'=>10,
	    				'order'=>array('ProjectFile.assigned_date'=>'DESC'),
	    				'conditions'=>array(
	    					$condition,   
	    					// $daterangecondition,
	    					// $datecondition,
	    					'ProjectFile.find_employees >'=>0)));
	    			}
	    			
	    			
    			}else{    				
    				$dateRange = explode(' - ',$this->request->data['Project']['date_range']);
    				$startDate = date('Y-m-d',strtotime($dateRange[0]));
    				$endDate = date('Y-m-d',strtotime($dateRange[1]));
    				// Configure::write('debug',1);
    				// debug($dateRange);
    				while (strtotime($startDate) <= strtotime($endDate)) {

    					$this->Project->FileProcess->virtualFields = array(
    						'hours'=>'TIMEDIFF(end_time,start_time)',
    						'hold_diff'=>'TIMEDIFF(hold_end_time,hold_start_time)',
    						// 'chk_date'=> $startDate
    					);
    					$res[$startDate] = $this->Project->FileProcess->find('first',array(
    						'fields'=>array(
    							'FileProcess.id',
    							'FileProcess.sr_no',
    							'FileProcess.units_completed',
    							'FileProcess.start_time',
    							'FileProcess.end_time',
    							'FileProcess.actual_time',
    							'FileProcess.hold_start_time',
    							'FileProcess.hold_end_time',
    							'FileProcess.hold_time',
    							'FileProcess.employee_id',
    							'FileProcess.project_file_id',
    							'FileProcess.project_process_plan_id',
    							'FileProcess.hours',
    							'ProjectFile.id',
    							'ProjectFile.name',
    							'Project.id',
    							'Project.title',
    							'Project.daily_hours',
    							'Milestone.id',
    							'Milestone.title',
    							'Employee.id',
    							'Employee.name',
    							'ProjectProcessPlan.id',
    							'ProjectProcessPlan.process', 							
    							'FileProcess.hold_diff'
    						),
    						'recursive'=>0,
    						'conditions'=>array(
    							'"'.$startDate.'" BETWEEN DATE(FileProcess.start_time) AND DATE(FileProcess.end_time)',
    							'FileProcess.employee_id'=>$employee['Employee']['id']
    						)
    					));

    		// 			foreach($res[$startDate] as $rec){
    		// 				$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
						// 	$rec['Project']['id'],
						// 	base64_encode($startDate),
						// 	base64_encode($startDate))
						// );
	    	// 				// debug($res[$startDate]['FileProcess']['project_id']);
	    	// 				if($holidays){
	    	// 					$res[$startDate] = array();
	    	// 					$res[$startDate]['ProjectFile']['name'] = 'Holiday';
	    	// 				}	
    		// 			}

    					$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
						$res[$startDate]['Project']['id'],
						base64_encode($startDate),
						base64_encode($startDate))
					);
    					// debug($res[$startDate]['FileProcess']['project_id']);
    					if($holidays){
    						$res[$startDate] = array();
    						$res[$startDate]['ProjectFile']['name'] = 'Holiday';
    					}

    				$startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));
    				}

    				$this->set('daylyResults',$res);
    			}
    		}else if($this->request->is('post') && $this->request->data['Project']['project_id']){
    			echo "pro";

			$dateRange = explode(' - ',$this->request->data['Project']['date_range']);
			$startDate = date('Y-m-d',strtotime($dateRange[0]));
			$endDate = date('Y-m-d',strtotime($dateRange[1]));
			// Configure::write('debug',1);
			// debug($dateRange);
			while (strtotime($startDate) <= strtotime($endDate)) {

				$this->Project->FileProcess->virtualFields = array(
					'hours'=>'TIMEDIFF(end_time,start_time)',
					'hold_diff'=>'TIMEDIFF(hold_end_time,hold_start_time)',
					'emp_code'=>'select employee_number from employees where id LIKE FileProcess.employee_id'
					// 'chk_date'=> $startDate
				);
				$res[$startDate] = $this->Project->FileProcess->find('all',array(
					'fields'=>array(
						'FileProcess.id',
						'FileProcess.sr_no',
						'FileProcess.units_completed',
						'FileProcess.start_time',
						'FileProcess.end_time',
						'FileProcess.actual_time',
						'FileProcess.hold_start_time',
						'FileProcess.hold_end_time',
						'FileProcess.hold_time',
						'FileProcess.employee_id',
						'FileProcess.project_file_id',
						'FileProcess.project_process_plan_id',
						'FileProcess.hours',
						'ProjectFile.id',
						'ProjectFile.name',
						'Project.id',
						'Project.title',
						'Project.daily_hours',
						'Milestone.id',
						'Milestone.title',
						'Employee.id',
						'Employee.name',
						'ProjectProcessPlan.id',
						'ProjectProcessPlan.process', 							
						'FileProcess.hold_diff',
						'FileProcess.emp_code'
					),
					'recursive'=>0,
					'group'=>array('FileProcess.employee_id'),
					'conditions'=>array(
						'"'.$startDate.'" BETWEEN DATE(FileProcess.start_time) AND DATE(FileProcess.end_time)',
						'FileProcess.project_id'=>$this->request->data['Project']['project_id']
					)
				));

	// 			foreach($res[$startDate] as $rec){
	// 				$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
				// 	$rec['Project']['id'],
				// 	base64_encode($startDate),
				// 	base64_encode($startDate))
				// );
	// 				// debug($res[$startDate]['FileProcess']['project_id']);
	// 				if($holidays){
	// 					$res[$startDate] = array();
	// 					$res[$startDate]['ProjectFile']['name'] = 'Holiday';
	// 				}	
	// 			}

				$holidays = $this->requestAction(array('controller'=>'projects','action'=>'holiday_days_from_file_process',
				$res[$startDate]['Project']['id'],
				base64_encode($startDate),
				base64_encode($startDate))
			);
				// debug($res[$startDate]['FileProcess']['project_id']);
				if($holidays){
					$res[$startDate] = array();
					$res[$startDate]['ProjectFile']['name'] = 'Holiday';
				}

			$startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));
			}

			$this->set('daylyResults',$res);
    		}    		
	}
		echo "1";
	    	
	    	// Configure::write('debug',1);
	    	debug($res);
		debug($this->request->data);
		// debug($projectFiles);
		// debug($end_date);
	    	
	    	// exit;
	    	$this->set('projectFiles',$projectFiles);
	    	$this->set('fileStatuses',$this->Project->ProjectFile->customArray['currentStatuses']);
	    	
	    	$processes = $this->Project->ProjectProcessPlan->find('list',array('fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process')));
	    	$this->set('processes',$processes);

	    	$projects = $this->Project->find('list');
	    	$this->set('projects',$projects);
    }


    public function mis($project_id = null){
    	
    	if($project_id){
    		$project = $this->Project->find('first',array('recursive'=>-1, 'conditions'=>array('Project.id'=>$project_id)));
    		$this->set('project',$project);    		
    	}else{
    		$project = $this->Project->find('first',array('recursive'=>-1, 'conditions'=>array('Project.id'=>$this->request->data['Project']['project_id'])));
    		$project_id = $this->request->data['Project']['project_id'];
    		$this->set('project',$project);    		
    	}

    	$projects = $this->Project->find('list',array());    		
    	$this->set('projects',$projects);

    	if($this->request->is('post') || $project_id){
    		// 

    		if(!$this->request->is('post')){
    			$this->request->data['Project']['project_id'] = $project_id;    			
    			$startDate = date('Y-m-d',strtotime('-3 months'));
    			$endDate = date('Y-m-d');

    			$this->request->data['Project']['dates'] = $startDate ." - ".$endDate;
    		}
    		
    		// get milestone and project plan
    		$milestones = $this->Project->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->data['Project']['project_id'])));
    		foreach($milestones as $mkey => $milestone){
    			$projectProcessPlans[$mkey] = $this->Project->Milestone->ProjectProcessPlan->find('all',array('conditions'=>array('ProjectProcessPlan.milestone_id'=>$mkey)));	
    		}

    		$existingprocesses = $this->Project->Milestone->ProjectProcessPlan->find('list',array('fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'), 'conditions'=>array('ProjectProcessPlan.project_id'=>$this->request->data['Project']['project_id'])));
    		

    		if($this->request->data['Project']['project_id']){
    			$project = $this->Project->find('first',array('recursive'=>-1, 'conditions'=>array('Project.id'=>"5ff7ebb5-e5f0-4eea-82e7-5779ac100145")));	
		}else{
			$project = $this->Project->find('first',array('recursive'=>-1, 'conditions'=>array('Project.id'=>$this->request->params['pass'][0])));	
		}	

    		
    		if($project){

    		}else{
    			$this->Session->setFlash(__('Project not found'));
			$this->redirect(array('controller'=>'projects', 'action' => 'index'));
    		}
		

    		// get the min start date of the process
    		$first_date = $this->Project->ProjectProcessPlan->find('first',array(
			'recursive'=>-1,
			'conditions'=>array('ProjectProcessPlan.project_id'=>$this->request->data['Project']['project_id']),
			'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.start_date'),
			'order'=>array('ProjectProcessPlan.end_date'=>'ASC')
		));
    		$startDate = date('Y-m-d',strtotime($first_date['ProjectProcessPlan']['start_date']));    		

		// get the max last date of the process
		$last_date = $this->Project->ProjectProcessPlan->find('first',array(
			'recursive'=>-1,
			'conditions'=>array('ProjectProcessPlan.project_id'=>$this->request->data['Project']['project_id']),
			'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.end_date'),
			'order'=>array('ProjectProcessPlan.end_date'=>'DESC')
		));
    		$endDate = date('Y-m-d',strtotime($last_date['ProjectProcessPlan']['end_date']));    		


    		$this->Project->ProjectFile->virtualFields = array(
    			'total_units'=>'select sum(unit) from project_files where project_files.project_id LIKE ProjectFile.project_id',
    		);
    		$totalUnits = $this->Project->ProjectFile->find('first',array('recursive'=>-1,'conditions'=>array('ProjectFile.project_id'=>$project['Project']['id'])));

    		$totalFiles = $this->Project->ProjectFile->find('count',array('conditions'=>array('ProjectFile.project_id'=>$project['Project']['id'])));
    		$closedFiles = $this->Project->ProjectFile->find('count',array('conditions'=>array('ProjectFile.current_status'=>5, 'ProjectFile.project_id'=>$project['Project']['id'])));
    		
    		$startDate = date('Y-m-d',strtotime('monday 0 week',strtotime($startDate)));
    		$this->loadModel('ProcessWeeklyPlan');
    		
    		$this->ProcessWeeklyPlan->virtualFields = array(
    			'total_planned_manhours'=>'select SUM(process_weekly_plans.hours) from process_weekly_plans where project_id LIKE "'.$project['Project']['id'].'"'
    		);

    		$total_planned_manhours = $this->ProcessWeeklyPlan->find('first',array(
    			'recursive'=>-1,
    			'fields'=>array('ProcessWeeklyPlan.id','ProcessWeeklyPlan.total_planned_manhours'),
    			'conditions'=>array('ProcessWeeklyPlan.project_id'=>$project['Project']['id'])));

    		while (strtotime($startDate) <= strtotime($endDate)) {
    			$this->Project->FileProcess->virtualFields = array(

    				// calculating units only for closed files
    				'total_completed_units'=>'select sum(units_completed) from file_processes where file_processes.project_id LIKE FileProcess.project_id AND file_processes.current_status = 5 and WEEK(end_time) = "'.date('W',strtotime($startDate)).'" ',

    				// calcualting total actual_time for all processes
    				'total_hours'=>'select SUM(actual_time) from file_processes where file_processes.project_id LIKE FileProcess.project_id AND file_processes.start_time is NOT NULL and file_processes.end_time is NOT NULL and WEEK(end_time) = "'.date('W',strtotime($startDate)).'" GROUP BY file_processes.project_id',

    				// calcualting total resourses for all processes
    				'resources'=>'select SUM(estimated_resource) from project_process_plans where project_process_plans.project_id LIKE FileProcess.project_id AND "'.date('Y-m-d',strtotime($startDate)).'" BETWEEN DATE(project_process_plans.start_date) AND DATE(project_process_plans.end_date) ',

    				'manhours'=>'select ROUND(AVG(estimated_manhours)) from project_process_plans where project_process_plans.project_id LIKE FileProcess.project_id AND "'.date('Y-m-d',strtotime($startDate)).'" BETWEEN DATE(project_process_plans.start_date) AND DATE(project_process_plans.end_date) ',

    				// 'actual_manhours'=>'select SUM(actual_time) from file_processes where project_process_plans.project_id LIKE FileProcess.project_id AND "'.date('Y-m-d',strtotime($startDate)).'" BETWEEN DATE(project_process_plans.start_date) AND DATE(project_process_plans.end_date) ',

    				'actual_manhours'=>'select SUM(actual_time) from file_processes where file_processes.project_id LIKE FileProcess.project_id 
    					AND file_processes.start_time IS NOT NULL AND file_processes.end_time IS NOT NULL
    					AND WEEK(file_processes.end_time) = '.date('W',strtotime($startDate)).'
    					AND MONTH(file_processes.end_time) = '.date('m',strtotime($startDate)).'
    					AND YEAR(file_processes.end_time) = '.date('Y',strtotime($startDate)).'
    					',


    				'actual_resources'=>'select count(*) from (select * from file_processes where file_processes.project_id LIKE "'.$project['Project']['id'].'" AND "'.date('Y-m-d',strtotime($startDate)).'" BETWEEN DATE(file_processes.start_time) AND DATE(file_processes.end_time) GROUP BY file_processes.employee_id) as cnt',


    				// 'actual_resource'=>'select count(*) from (select * from file_processes where file_processes.project_process_plan_id LIKE "'.$pkey.'" GROUP BY file_processes.employee_id) as cnt',

    				// 'actual_manhours'=>'select HOUR(SUM(actual_time)) from file_processes where file_processes.project_id LIKE FileProcess.project_id AND DATE(file_processes.start_time) < "'.date('Y-m-d',strtotime($startDate)).'" AND DATE(file_processes.end_time) > "'.date('Y-m-d',strtotime($startDate)) .'" AND file_processes.start_time IS NOT NULL AND file_processes.end_time IS NOT NULL',
    			);
    			
    			$expected = $this->Project->FileProcess->find('first',array(
    				'fields'=>array(
    					'FileProcess.id',
    					'FileProcess.project_id',
    					'FileProcess.units_completed',
    					'FileProcess.total_completed_units',
    					'FileProcess.total_hours',
    					'FileProcess.resources',
    					'FileProcess.manhours',
    					'FileProcess.actual_manhours',
    					'FileProcess.actual_resources',
    				),
    				'recursive'=>-1,
    				'conditions'=>array(
    					// 'WEEK(FileProcess.end_time)'=>date('W',strtotime($startDate)),
    					// 'MONTH(FileProcess.end_time)'=>date('m',strtotime($startDate)),
    					// 'YEAR(FileProcess.end_time)'=>date('Y',strtotime($startDate)),
    					// 'FileProcess.current_status'=>5,
    					'FileProcess.project_id'=>$project['Project']['id']
    				)));

    			$completedUnits = $this->Project->FileProcess->find('first',array(
    				'fields'=>array(
    					'FileProcess.id',
    					'FileProcess.project_id',
    					'FileProcess.units_completed',
    					'FileProcess.total_completed_units',
    					'FileProcess.total_hours',
    					'FileProcess.resources',
    					'FileProcess.manhours',
    				),
    				'recursive'=>-1,
    				'conditions'=>array(
    					'WEEK(FileProcess.end_time)'=>date('W',strtotime($startDate)),
    					'MONTH(FileProcess.end_time)'=>date('m',strtotime($startDate)),
    					'YEAR(FileProcess.end_time)'=>date('Y',strtotime($startDate)),
    					'FileProcess.current_status'=>5,
    					'FileProcess.project_id'=>$project['Project']['id']
    				)));
    			// debug($startDate);
    			// debug($expected);

    			$completedHours = $this->Project->FileProcess->find('first',array(
    				'fields'=>array(
    					'FileProcess.id',
    					'FileProcess.project_id',
    					'FileProcess.units_completed',
    					'FileProcess.total_completed_units',
    					'FileProcess.total_hours'
    				),
    				'recursive'=>-1,
    				'conditions'=>array(
    					'WEEK(FileProcess.end_time)'=>date('W',strtotime($startDate)),
    					'MONTH(FileProcess.end_time)'=>date('m',strtotime($startDate)),
    					'YEAR(FileProcess.end_time)'=>date('Y',strtotime($startDate)),
    					// 'FileProcess.current_status'=>5,
    					'FileProcess.project_id'=>$project['Project']['id']
    				)));

    			$preunits = $unitsCompletedResult[$week];
    			$week = date('d/m/y',strtotime($startDate)).' - ' . date('d/m/y',strtotime('+ 7 days',strtotime($startDate)));

    			if($totalUnits['ProjectFile']['total_units'])$unitsCompletedResult[$week] = round($preunits + $completedUnits['FileProcess']['total_completed_units'] * 100 / $totalUnits['ProjectFile']['total_units'],2);
    			else $unitsCompletedResult[$week] = 0;
    			


    			$hoursCompletedResult[$week] = substr($this->_sectohr($completedHours['FileProcess']['total_hours']),0,-3);



    			$this->ProcessWeeklyPlan->virtualFields = array(
    			'total_planned'=>'select SUM(`process_weekly_plans`.`planned`) from `process_weekly_plans` where `process_weekly_plans`.`project_id` LIKE ProcessWeeklyPlan.project_id AND `process_weekly_plans`.`year` = "'.date('y',strtotime($startDate)).'" AND `process_weekly_plans`.`week` = "'.date('W',strtotime($startDate)).'"',
    			'total_planned_units'=>'select SUM(`process_weekly_plans`.`units`) from `process_weekly_plans` where `process_weekly_plans`.`project_id` LIKE ProcessWeeklyPlan.project_id AND `process_weekly_plans`.`year` = "'.date('y',strtotime($startDate)).'" AND `process_weekly_plans`.`week` = "'.date('W',strtotime($startDate)).'"',
    			'total_planned_hours'=>'select SUM(`process_weekly_plans`.`hours`) from `process_weekly_plans` where `process_weekly_plans`.`project_id` LIKE ProcessWeeklyPlan.project_id AND `process_weekly_plans`.`year` = "'.date('y',strtotime($startDate)).'" AND `process_weekly_plans`.`week` = "'.date('W',strtotime($startDate)).'"',
    			'weightage'=>'select project_process_plans.weightage from project_process_plans where project_process_plans.id LIKE ProcessWeeklyPlan.project_process_plan_id',
    			

    			// 'total_planned_manhours'=>$total_planned_manhours['ProcessWeeklyPlan']['total_planned_manhours'],
    			
    			'planned_per'=>'select SUM(`process_weekly_plans`.`weightage_per`) from `process_weekly_plans` where `process_weekly_plans`.`project_id` LIKE ProcessWeeklyPlan.project_id AND `process_weekly_plans`.`year` = "'.date('y',strtotime($startDate)).'" AND `process_weekly_plans`.`week` = "'.date('W',strtotime($startDate)).'"',
    			);
    			
    				$planned = $this->ProcessWeeklyPlan->find('first',array(
	    				'recursive'=>-1,
	    				'conditions'=>array(
	    					'ProcessWeeklyPlan.year'=>date('y',strtotime($startDate)),
	    					'ProcessWeeklyPlan.week'=>date('W',strtotime($startDate)),
	    					'ProcessWeeklyPlan.project_id'=>$project['Project']['id'],	    					
	    					)
	    				)
	    			);

	    		
    			$planned_per = $planned['ProcessWeeklyPlan']['planned_per'] + $planned_per ;


    			
    			if($expected['FileProcess']['actual_manhours'])$actual = $this->_sectohr($expected['FileProcess']['actual_manhours']);
    			else $actual = 0;
    			

    			if($expected['FileProcess']['resources'] != null && $expected['FileProcess']['actual_resources'] != null)$expected_resper = round($expected['FileProcess']['actual_resources']*100/$expected['FileProcess']['resources'],1);
    			else $expected_resper = 0;
    				
    			if($expected['FileProcess']['actual_manhours'])$hrper = substr($expected['FileProcess']['actual_manhours'],0,-5);
    			else $hrper = 0;

    			if($planned['ProcessWeeklyPlan']['total_planned'])$total_planned = $planned['ProcessWeeklyPlan']['total_planned'];
    			else $total_planned = 0;

    			if($planned['ProcessWeeklyPlan']['total_planned'])$planned_resources  = $planned['ProcessWeeklyPlan']['total_planned'];
    			else $planned_resources = 0;

    			if($planned['ProcessWeeklyPlan']['total_planned_hours'])$planned_manhours = $planned['ProcessWeeklyPlan']['total_planned_hours'];
    			else $planned_manhours = 0;

    			// if($planned_per)$planned_per = $planned_per;
    			// esle $planned_per = 0;

    			if($planned['ProcessWeeklyPlan']['total_planned_units'])$planned_units = $planned['ProcessWeeklyPlan']['total_planned_units'];
    			else $planned_units = 0;
    			

    			$expectedResult[$week] = 
    			array(
				'resources'=>($expected['FileProcess']['resources']?$expected['FileProcess']['resources']:0),
				'actual_resources'=>($expected['FileProcess']['actual_resources']?$expected['FileProcess']['actual_resources']:0),
    				'manhours'=>($expected['FileProcess']['manhours']?$expected['FileProcess']['manhours']:0),    				
    				'actual'=>($actual),
    				'resper'=>$expected_resper,
    				'hrper'=>round($hrper),
    				'planned'=>round($total_planned,1),
    				'planned_resources'=>round($total_planned,1),
    				'planned_manhours'=>round($planned_manhours,1),
    				'planned_per' => round($planned_per,1),
    				'planned_units'=> round($planned_units,1),
    			);
    			
    			
    			// $expectedManhours[date('W',strtotime($startDate)).' '.date('m/y',strtotime($startDate))] = ($expected['FileProcess']['manhours']?$expected['FileProcess']['manhours']:0);    			
    			// echo ">>" . $expectedResult[$week]['planned_per']."<br />";
    			$startDate = date("Y-m-d", strtotime("+1 week", strtotime($startDate)));
    		}

    		// add processwise hours & Units completed
    		$this->Project->FileProcess->virtualFields = array(
    			'total_completed_units'=>'select sum(units_completed) from file_processes where file_processes.project_id LIKE FileProcess.project_id AND  file_processes.project_process_plan_id LIKE FileProcess.project_process_plan_id AND (file_processes.current_status = 1 OR file_processes.current_status = 5)',

    			
    			'total_completed_files'=>'select count(*) from file_processes where file_processes.project_id LIKE FileProcess.project_id AND file_processes.project_process_plan_id LIKE FileProcess.project_process_plan_id AND (file_processes.current_status = 1 OR file_processes.current_status = 5)',
    			// 'total_completed_files'=>'select count(*) from project_files where project_files.project_id LIKE FileProcess.project_id AND project_files.current_status = 5 AND project_files.project_process_plan_id LIKE FileProcess.project_process_plan_id ',

    				// calcualting total actual_time for all processes
    			'total_hours'=>'select SUM(HOUR(TIMEDIFF(start_time,end_time))) from file_processes where file_processes.project_id LIKE FileProcess.project_id AND file_processes.start_time is NOT NULL and file_processes.end_time is NOT NULL AND file_processes.project_process_plan_id LIKE FileProcess.project_process_plan_id AND (file_processes.current_status = 1 OR file_processes.current_status = 5) GROUP BY file_processes.project_id ',
    		);
		foreach($existingprocesses as $key => $value){

			$pro_mtc = $this->Project->FileProcess->ProjectProcessPlan->find('first',array(
				'recursive'=>-1,
				'conditions'=>array('ProjectProcessPlan.id'=>$key)
			));
			// Configure::Write('debug',1);
			// debug($pro_mtc);

			$processwise[$key] = $this->Project->FileProcess->find('first',array(
				'fields'=>array(
					'FileProcess.id',
					'FileProcess.project_process_plan_id',
					'FileProcess.total_completed_units',
					'FileProcess.total_hours',
					'FileProcess.total_completed_files',
					'FileProcess.actual_time',
				),
				'recursive'=>-1,
				'conditions'=>array(
				'FileProcess.project_process_plan_id'=>$key
				)
			));

			$processwise[$key]['ProjectProcessPlan']  = $pro_mtc['ProjectProcessPlan'];
		}
		
		
		// debug($processwise);
		// exit;


    		// errors
    		$this->loadModel('FileErrorMaster');
    		$fileErrorMasters = $this->FileErrorMaster->find('list',array('conditions'=>array('FileErrorMaster.project_id'=>$project['Project']['id'])));

    		// debug($fileErrorMasters);
    		// debug($expectedManhours);    		
    		// debug($hoursCompletedResult);
    		$this->FileErrorMaster->FileError->virtualFields = array(
    			'total'=>'SUM(total_errors)'
    		);
    		foreach($fileErrorMasters as $id => $error){
    			$res = $this->FileErrorMaster->FileError->find('first',array('conditions'=>array('FileError.file_error_master_id'=>$id),'fields'=>array('FileError.id','FileError.total'),'recursive'=>-1));

    			$errResult[$error] = $res['FileError']['total']?$res['FileError']['total']:0;
    		}
    		arsort($errResult);
    		// debug($errResult);
    		// exit;

    		$this->set('unitsCompletedResult',$unitsCompletedResult);
    		$this->set('hoursCompletedResult',$hoursCompletedResult);
    		$this->set('expectedResult',$expectedResult);
    		$this->set('project',$project);
    		$this->set('milestones',$milestones);
    		$this->set('projectProcessPlans',$projectProcessPlans);
    		$this->set('existingprocesses',$existingprocesses);
    		$this->set('totalFiles',$totalFiles);
    		$this->set('closedFiles',$closedFiles);
    		$this->set('fileErrorMasters',$fileErrorMasters);
    		$this->set('errResult',$errResult);
    		$this->set('processwise',$processwise);

    		// exit;
    	}
    }

    public function get_milestones(){
    	$this->autoRender = false;
    	$con_str .= '<option value=-1>Select</option>' ;
    	$milestones = $this->Project->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->params['pass'][0])));
    	foreach($milestones as $key => $value){
    		$con_str .= '<option value=' . $key .'>' . $value . '</option>' ;
    	}
    	return $con_str;
    }

    public function get_cb(){
    	$this->autoRender = false;
    	$con_str1 .= '<option value=-1>Select</option>' ;
    	$cities = $this->Project->ProjectFile->find('list',array(
    		'fields'=>array('ProjectFile.city','ProjectFile.city'),
    		'conditions'=>array('ProjectFile.milestone_id'=>$this->request->params['pass'][0])));
    	

    	foreach($cities as $key => $value){
    		$con_str1 .= '<option value=' . $key .'>' . $value . '</option>' ;
    	}


    	$con_str2 .= '<option value=-1>Select</option>' ;
    	$blocks = $this->Project->ProjectFile->find('list',array(
    		'fields'=>array('ProjectFile.block','ProjectFile.block'),
    		'conditions'=>array('ProjectFile.milestone_id'=>$this->request->params['pass'][0])));
    	

    	foreach($blocks as $key => $value){
    		$con_str2 .= '<option value=' . $key .'>' . $value . '</option>' ;
    	}

    	return $con_str = json_encode(array($con_str1,$con_str2));
    }

    public function process_weightage($project_id = null, $process_id = null){
    	$this->autoRender = false;
    	$this->Project->ProjectProcessPlan->virtualFields = array(
    		'total_manhours'=>'select SUM(estimated_manhours) from project_process_plans where project_process_plans.project_id LIKE "'.$project_id.'"'
    	);
    	$processes  = $this->Project->ProjectProcessPlan->find('all',array(
    		'recursive'=>-1,
    		'fields'=>array(
    			'ProjectProcessPlan.id',
    			'ProjectProcessPlan.estimated_manhours',
    			'ProjectProcessPlan.total_manhours'
    		),
    		'conditions'=>array('ProjectProcessPlan.project_id'=>$project_id)
    	));

    	foreach($processes as $process){
    		$pro[$process['ProjectProcessPlan']['id']] = round(100 * $process['ProjectProcessPlan']['estimated_manhours'] / $process['ProjectProcessPlan']['total_manhours'],2);
    	}

    	return $pro;
    	// exit;
    }    

    public function time_to_sec($time = null) {    
    	    $time = base64_decode($time);
	    $sec = 0;
	    foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
	    return $sec;
	}


	public function weekly_report(){
		if($project_id){
	    		$project = $this->Project->find('first',array('recursive'=>-1, 'conditions'=>array('Project.id'=>$project_id)));
	    		$this->set('project',$project);    		
	    	}else{
	    		$project = $this->Project->find('first',array('recursive'=>-1, 'conditions'=>array('Project.id'=>$this->request->data['Project']['project_id'])));
	    		$project_id = $this->request->data['Project']['project_id'];
	    		$this->set('project',$project);    		
	    	}

	    	$projects = $this->Project->find('list',array());    		
	    	$this->set('projects',$projects);

	    	$processes = $this->Project->ProjectProcessPlan->find('list',array(
	    		'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
	    		'conditions'=>array('ProjectProcessPlan.project_id'=>$this->request->data['Project']['project_id'])));


	    	// exit;

	    	if($this->request->is('post') || $project_id){
	    		// Configure::Write('debug',1);
	    		// debug($this->request->data);

	    		$dates = explode(" - ",$this->request->data['Project']['dates']);

	    		$startDate = date('Y-m-d',strtotime($dates[0]));
			$startDate = date('Y-m-d',strtotime('monday 0 week',strtotime($startDate)));
			$endDate = date('Y-m-d',strtotime($dates[1]));    		

			debug($dates);
			$this->loadModel('ProcessWeeklyPlan');
			while (strtotime($startDate) <= strtotime($endDate)) {
				
				
				foreach($processes as $pKey => $pValue){

					// get planned

					$this->ProcessWeeklyPlan->virtualFields = array(
					
					'units_completed'=>'select SUM(units_completed) from file_processes where file_processes.project_process_plan_id LIKE "'.$pKey.'"',
					'cal_type' =>'select cal_type from project_overall_plans where project_overall_plans.id LIKE ProjectProcessPlan.project_overall_plan_id ',		
					'actual_manhours'=>'select SUM(actual_time) from file_processes 
						where file_processes.project_id LIKE ProcessWeeklyPlan.project_id 
	    					AND file_processes.start_time IS NOT NULL AND file_processes.end_time IS NOT NULL
	    					AND WEEK(file_processes.end_time) = '.date('W',strtotime($startDate)).'
	    					AND MONTH(file_processes.end_time) = '.date('m',strtotime($startDate)).'
	    					AND YEAR(file_processes.end_time) = '.date('Y',strtotime($startDate)).'
	    					',


	    				'actual_resources'=>'select count(*) from (select count(*) from file_processes 
						where 
							file_processes.project_process_plan_id LIKE "'.$pKey.'"
							AND file_processes.start_time IS NOT NULL OR file_processes.end_time IS NOT NULL
		    					
		    					AND
		    					((WEEK(file_processes.start_time) = '.date('W',strtotime($startDate)).'
		    					AND WEEK(file_processes.start_time) = '.date('Y',strtotime($startDate)).')
		    					OR (WEEK(file_processes.end_time) = '.date('W',strtotime($startDate)).'		    					
		    					AND YEAR(file_processes.end_time) = '.date('Y',strtotime($startDate)).'))
		    							    					
						
						GROUP BY file_processes.employee_id) as cnt',
					);

					$planned = $this->ProcessWeeklyPlan->find('first',array(
						'recursive'=>0,
						'conditions'=>array(
							'ProcessWeeklyPlan.project_process_plan_id'=>$pKey,
							'ProcessWeeklyPlan.week'=>date('W',strtotime($startDate)),
							'ProcessWeeklyPlan.year'=>date('Y',strtotime($startDate)),
						)
					));


					$result[$startDate][$pKey] = $planned;
				}


				$startDate = date("Y-m-d", strtotime("+1 week", strtotime($startDate)));				
			}


			debug($result);
	    	}


	    	// Configure::Write('debug',1);
	    	// debug($result);
	    	// exit;

	    	$this->set('result',$result);
	    	$this->set('processes',$processes);
	}


	public function milestonename(){
		$this->loadModel('Milestone');
		$milestone = $this->Milestone->find('first',array(
			'recursive'=>-1,
			'fields'=>array('Milestone.id','Milestone.name'),
			'conditions'=>array('Milestone.id'=>$id)
		));

		return $milestone['Milestone']['name'];
	}
}



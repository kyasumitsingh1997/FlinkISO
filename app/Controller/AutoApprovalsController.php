<?php
App::uses('AppController', 'Controller');
/**
 * AutoApprovals Controller
 *
 * @property AutoApproval $AutoApproval
 */
class AutoApprovalsController extends AppController {

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
		$this->paginate = array('order'=>array('AutoApproval.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->AutoApproval->recursive = 1;
		$this->set('autoApprovals', $this->paginate());
		
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
		$this->paginate = array('order'=>array('AutoApproval.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->AutoApproval->recursive = 0;
		$this->set('autoApprovals', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['AutoApproval']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['AutoApproval']['search_field'] as $search):
				$search_array[] = array('AutoApproval.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('AutoApproval.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->AutoApproval->recursive = 0;
		$this->paginate = array('order'=>array('AutoApproval.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'AutoApproval.soft_delete'=>0 , $cons));
		$this->set('autoApprovals', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('AutoApproval.'.$search => $search_key);
					else $search_array[] = array('AutoApproval.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('AutoApproval.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('AutoApproval.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'AutoApproval.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('AutoApproval.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('AutoApproval.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->AutoApproval->recursive = 0;
		$this->paginate = array('order'=>array('AutoApproval.sr_no'=>'DESC'),'conditions'=>$conditions , 'AutoApproval.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('autoApprovals', $this->paginate());
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
		if (!$this->AutoApproval->exists($id)) {
			throw new NotFoundException(__('Invalid auto approval'));
		}
		$options = array('conditions' => array('AutoApproval.' . $this->AutoApproval->primaryKey => $id));
		$autoApproval = $this->AutoApproval->find('first', $options);
		$this->set('autoApproval', $autoApproval);

		//Configure::write('debug',1);
		// debug($autoApproval);
		// foreach ($autoApproval['AutoApprovalStep'] as $AutoApprovalStep) {
		// 	debug($AutoApprovalStep);
		// }
		$branches = $this->_get_branch_list();
		$departments = $this->_get_department_list();

		// get max steps
		$max  = $this->AutoApproval->AutoApprovalStep->find('first',array(
					'recursive'=>-1,
					'conditions'=>array('AutoApprovalStep.auto_approval_id'=>$id),
					'order'=>array('AutoApprovalStep.step_number'=>'DESC')));
		$max = $max['AutoApprovalStep']['step_number'];
		// debug($max);
		// exit;
		for($i = 1; $i <= $max; $i ++){
			$s = array();
			foreach ($branches as $bid => $bvalue) {
				// debug($bid);
				foreach ($departments as $dkey => $dvalue) {					
					$steps[$i][$bvalue][$dvalue] = $this->AutoApproval->AutoApprovalStep->find('all',array(
						'recursive'=>-1,
						'conditions'=>array(
							'AutoApprovalStep.step_number'=>$i,
							'AutoApprovalStep.branch_id'=>$bid,
							'AutoApprovalStep.department_id'=>$dkey,
							'AutoApprovalStep.auto_approval_id'=>$id
							),
						'order'=>array('AutoApprovalStep.step_number'=>'DESC')));
					// $steps[$i][$bvalue][$bvalue] = $s;
				}
			}
		}
		$this->set('approvalsteps',$steps);
		$this->set('maxsteps',$max);

		// get step names for auto complete
		$cnames = $this->AutoApproval->AutoApprovalStep->find('list',array(
			'group'=>array('AutoApprovalStep.name'),
			'conditions'=>array(),'fields'=>array('AutoApprovalStep.id','AutoApprovalStep.name')));
		
		$this->set('cnames',$cnames);
		//debug($steps);
		//exit;
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
                        $this->request->data['AutoApproval']['system_table_id'] = $this->_get_system_table_id();
			$this->AutoApproval->create();
			if ($this->AutoApproval->save($this->request->data)) {
				$this->Session->setFlash(__('The auto approval has been saved, continue to add steps.'));
				$this->redirect(array('action' => 'view',$this->AutoApproval->id));
			
			// $this->loadModel('AutoApprovalStep');
			// // $all_branches = $this->_get_branch_list();
			// // Configure::write('debug',1);
			// // debug($this->request->data);
			// // exit;
			// foreach($all_branches as $key=>$value):
			// 	foreach($this->request->data[$key]['AutoApprovalStep'] as $steps):
							
			// 		if($steps['user_id'] != '-1'){

			// 				$this->loadModel('AutoApprovalStep');
			// 				$this->AutoApprovalStep->create();
			// 				$step['AutoApprovalStep']['step_number'] = $steps['step_number'];
			// 				$step['AutoApprovalStep']['name'] = $steps['name'];
			// 				$step['AutoApprovalStep']['user_id'] = $steps['user_id'];
			// 				$step['AutoApprovalStep']['branch_id'] = $key;
			// 				$step['AutoApprovalStep']['department_id'] = $steps['department_id'];;
			// 				$step['AutoApprovalStep']['details'] = $steps['details'];
			// 				$step['AutoApprovalStep']['allow_approval'] = $steps['allow_approval'];
			// 				$step['AutoApprovalStep']['show_details'] = $steps['show_details'];
			// 				$step['AutoApprovalStep']['auto_approval_id'] = $this->AutoApproval->id;
			// 				$step['AutoApprovalStep']['created_by'] = $this->Session->read('User.id');
			// 				$step['AutoApprovalStep']['prepared_by'] = $this->Session->read('User.id');
			// 				$step['AutoApprovalStep']['publish'] = $this->request->data['AutoApproval']['publish'];
			// 				$step['AutoApprovalStep']['system_table'] = $this->request->data['AutoApproval']['system_table'];
			// 				$step['AutoApprovalStep']['system_table_id'] = $this->request->data['AutoApproval']['system_table'];
			// 				$step['AutoApprovalStep']['branchid'] = $this->Session->read('User.branch_id');
			// 				$step['AutoApprovalStep']['departmentid'] = $this->Session->read('User.branch_id');
			// 				$step['AutoApprovalStep']['master_list_of_format_id'] = $this->request->data['AutoApproval']['master_list_of_format_id'];
			// 				$step['AutoApprovalStep']['soft_delete'] = 0;
							
			// 				$this->AutoApprovalStep->save($step,false);
			// 		}
			// 		endforeach;
			// endforeach;	

			//exit;
			
				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='AutoApproval';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->AutoApproval->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The auto approval has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->AutoApproval->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The auto approval could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->AutoApproval->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AutoApproval->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->AutoApproval->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->AutoApproval->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->AutoApproval->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->AutoApproval->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApproval->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->AutoApproval->find('count');
	$published = $this->AutoApproval->find('count',array('conditions'=>array('AutoApproval.publish'=>1)));
	$unpublished = $this->AutoApproval->find('count',array('conditions'=>array('AutoApproval.publish'=>0)));
		
	$this->set(compact('count','published','unpublished'));
	
	$all_branches = $this->_get_branch_list();
		$this->set('allBranches',$all_branches);	
			foreach($all_branches as $key=>$value):
				
				$users = $this->AutoApproval->CreatedBy->find('list',
					array('conditions'=>array('CreatedBy.branch_id'=>$key,'CreatedBy.publish'=>1, 'CreatedBy.soft_delete' => 0)));
				
				foreach ($users as $ukey => $uvalue) {
						$allUsers[$ukey] = $value . ' -> '  . $uvalue;
					}	
			endforeach;		
		
		$this->set('fwd_users',$allUsers);
		

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
                        $this->request->data['AutoApproval']['system_table_id'] = $this->_get_system_table_id();
			$this->AutoApproval->create();
			if ($this->AutoApproval->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='AutoApproval';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->AutoApproval->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The auto approval has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->AutoApproval->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The auto approval could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->AutoApproval->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AutoApproval->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->AutoApproval->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->AutoApproval->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->AutoApproval->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->AutoApproval->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApproval->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->AutoApproval->find('count');
	$published = $this->AutoApproval->find('count',array('conditions'=>array('AutoApproval.publish'=>1)));
	$unpublished = $this->AutoApproval->find('count',array('conditions'=>array('AutoApproval.publish'=>0)));
		
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
		// $this->Session->setFlash(__('To make changes to auto-approval, click on View.'));
		// $this->redirect(array('controller'=>'auto_approvals', 'action' => 'index'));

		if (!$this->AutoApproval->exists($id)) {
			throw new NotFoundException(__('Invalid auto approval'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['AutoApproval']['system_table_id'] = $this->_get_system_table_id();
			if ($this->AutoApproval->save($this->request->data)) {

			$this->loadModel('AutoApprovalStep');
			$all_branches = $this->_get_branch_list();
			$new = $this->request->data;
			unset($new['AutoApproval']);
			foreach($new as $key=>$value):
				foreach($value['AutoApprovalStep'] as $count => $steps):

					if(!empty($steps)){	

						if($steps['user_id'] != '-1'){							
							if($steps['id'])
							{
								$this->loadModel('AutoApprovalStep');																
								$new_data = array();
								$this->AutoApprovalStep->read(null , $steps['id']);
								$new_data = $steps;
								$this->AutoApprovalStep->set($steps);								
								try{
									$this->AutoApprovalStep->save();
								}catch(Exception $e){
									
								}
							}else{
								
								$this->loadModel('AutoApprovalStep');
								$this->AutoApprovalStep->create();
								$step = array();
								$step['AutoApprovalStep']['step_number'] = $steps['step_number'];
								$step['AutoApprovalStep']['name'] = $steps['name'];
								$step['AutoApprovalStep']['user_id'] = $steps['user_id'];
								$step['AutoApprovalStep']['branch_id'] = $key;
								$step['AutoApprovalStep']['department_id'] = $steps['department_id'];;
								$step['AutoApprovalStep']['details'] = $steps['details'];
								$step['AutoApprovalStep']['allow_approval'] = $steps['allow_approval'];
								$step['AutoApprovalStep']['show_details'] = $steps['show_details'];
								$step['AutoApprovalStep']['auto_approval_id'] = $this->request->data['AutoApproval']['id'];
								$step['AutoApprovalStep']['created_by'] = $this->Session->read('User.id');
								$step['AutoApprovalStep']['prepared_by'] = $this->Session->read('User.id');
								$step['AutoApprovalStep']['publish'] = $this->request->data['AutoApproval']['publish'];
								$step['AutoApprovalStep']['system_table'] = $this->request->data['AutoApproval']['system_table'];
								$step['AutoApprovalStep']['system_table_id'] = $this->request->data['AutoApproval']['system_table'];
								$step['AutoApprovalStep']['branchid'] = $this->Session->read('User.branch_id');
								$step['AutoApprovalStep']['departmentid'] = $this->Session->read('User.branch_id');
								$step['AutoApprovalStep']['master_list_of_format_id'] = $this->request->data['AutoApproval']['master_list_of_format_id'];
								$step['AutoApprovalStep']['soft_delete'] = 0;
								$this->AutoApprovalStep->save($step,false);
							}	
						}
					}
					endforeach;
			endforeach;
			
				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The auto approval could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AutoApproval.' . $this->AutoApproval->primaryKey => $id));
			$this->request->data = $this->AutoApproval->find('first', $options);
		}
		$systemTables = $this->AutoApproval->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AutoApproval->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->AutoApproval->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->AutoApproval->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->AutoApproval->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->AutoApproval->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApproval->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->AutoApproval->find('count');
		$published = $this->AutoApproval->find('count',array('conditions'=>array('AutoApproval.publish'=>1)));
		$unpublished = $this->AutoApproval->find('count',array('conditions'=>array('AutoApproval.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		$all_branches = $this->_get_branch_list();
		$this->set('allBranches',$all_branches);	
			foreach($all_branches as $key=>$value):
				
				$users = $this->AutoApproval->CreatedBy->find('list',
					array('conditions'=>array('CreatedBy.branch_id'=>$key,'CreatedBy.publish'=>1, 'CreatedBy.soft_delete' => 0)));
				
				foreach ($users as $ukey => $uvalue) {
						$allUsers[$ukey] = $value . ' -> '  . $uvalue;
					}	
			endforeach;		
		
		$this->set('fwd_users',$allUsers);
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->AutoApproval->exists($id)) {
			throw new NotFoundException(__('Invalid auto approval'));
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
			if ($this->AutoApproval->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->AutoApproval->save($this->request->data)) {
                $this->Session->setFlash(__('The auto approval has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The auto approval could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The auto approval could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AutoApproval.' . $this->AutoApproval->primaryKey => $id));
			$this->request->data = $this->AutoApproval->find('first', $options);
		}
		$systemTables = $this->AutoApproval->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AutoApproval->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->AutoApproval->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->AutoApproval->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->AutoApproval->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->AutoApproval->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApproval->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->AutoApproval->find('count');
		$published = $this->AutoApproval->find('count',array('conditions'=>array('AutoApproval.publish'=>1)));
		$unpublished = $this->AutoApproval->find('count',array('conditions'=>array('AutoApproval.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));


		
		
		$all_branches = $this->_get_branch_list();
		$this->set('allBranches',$all_branches);	
			foreach($all_branches as $key=>$value):
				
				$users = $this->AutoApproval->CreatedBy->find('list',
					array('conditions'=>array('CreatedBy.branch_id'=>$key,'CreatedBy.publish'=>1, 'CreatedBy.soft_delete' => 0)));
				
				foreach ($users as $ukey => $uvalue) {
						$allUsers[$ukey] = $value . ' -> '  . $uvalue;
					}	
			endforeach;		
		
		$this->set('fwd_users',$allUsers);
	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {
		$this->AutoApproval->id = $id;
		if (!$this->AutoApproval->exists()) {
			throw new NotFoundException(__('Invalid auto approval'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->AutoApproval->delete()) {
			$this->Session->setFlash(__('Auto approval deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Auto approval was not deleted'));
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
		
		$result = explode('+',$this->request->data['autoApprovals']['rec_selected']);
		$this->AutoApproval->recursive = 1;
		$autoApprovals = $this->AutoApproval->find('all',array('AutoApproval.publish'=>1,'AutoApproval.soft_delete'=>1,'conditions'=>array('or'=>array('AutoApproval.id'=>$result))));
		$this->set('autoApprovals', $autoApprovals);
		
		$systemTables = $this->AutoApproval->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AutoApproval->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->AutoApproval->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->AutoApproval->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->AutoApproval->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->AutoApproval->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApproval->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}

	public function steps($branch_id = null){
		$branch_id = $this->request->params['pass'][0];
		//echo $branch_id;
		$this->layout = 'ajax';
		$all_branches = $this->_get_branch_list();
		
		foreach($all_branches as $key=>$value):
			
			$users = $this->AutoApproval->CreatedBy->find('list',
				array('conditions'=>array('CreatedBy.branch_id'=>$key,'CreatedBy.publish'=>1, 'CreatedBy.soft_delete' => 0)));
			
			foreach ($users as $ukey => $uvalue) {
					$allUsers[$ukey] = $value . ' -> '  . $uvalue;
				}	
		endforeach;		
		$this->set('fwd_users',$allUsers);
		$this->set('branch_id',$branch_id);
	}

	public function add_details($i = null,$deps = null){
		$departments = $this->request->params['pass'][1];
		$departments = str_replace('undefined,',',', $departments);
		$departments = str_replace(',,',',', $departments);
		$selected_departments = explode(',',$departments);
		// Configure::Write('debug',1);
		// debug($selected_departments);
		foreach ($selected_departments as $key => $value) {
			if($value != '' && $value != 'undefined,'){
				$sels[ltrim(rtrim($value))] = ltrim(rtrim($value));
			}
		}
		// debug($sels);
		$this->loadModel('Department');
		$departments = $this->Department->find('list',array('conditions'=>array('Department.id != '=> $sels)));
		$this->set('departments',$departments);
		debug($departments);
		$this->set('i',$i);
		// exit;

	}

	public function deletesteps($auto_approval_id = null, $step_number = null){
		$this->loadModel('AutoApprovalStep');
		$this->AutoApprovalStep->deleteAll(array('AutoApprovalStep.auto_approval_id'=>$auto_approval_id,'AutoApprovalStep.step_number'=>$step_number));
		$this->redirect(array('action' => 'view',$auto_approval_id));		
	}

}

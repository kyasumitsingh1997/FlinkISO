<?php
App::uses('AppController', 'Controller');
/**
 * EnvEvaluations Controller
 *
 * @property EnvEvaluation $EnvEvaluation
 */
class EnvEvaluationsController extends AppController {

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
		$this->paginate = array('order'=>array('EnvEvaluation.sr_no'=>'DESC'),'conditions'=>array($conditions));
		$envs = $this->paginate();
		$this->loadModel('EnvEvaluationScore');
		
		foreach ($envs as $env) {
			$scores = $this->EnvEvaluationScore->find('all',array('fields'=>
				array(
					'EnvEvaluationScore.id',
					'EnvEvaluationScore.score',
					'EnvEvaluationScore.env_activity_id',
					'EnvEvaluationScore.env_identification_id',
					'EnvEvaluationScore.env_evaluation_id',
					'EnvActivity.id',
					'EnvActivity.title',
					'EnvIdentification.id',
					'EnvIdentification.title',
					'EnvEvaluation.id',
					'EnvEvaluation.title',
					'EvaluationCriteria.id',
					'EvaluationCriteria.name',
					),'conditions'=>array('EnvEvaluationScore.env_evaluation_id'=>$env['EnvEvaluation']['id']),'recursive'=>1,'order'=>array('EvaluationCriteria.name'=>'ASC')));
			$env['EvaluationCriteria'] = $scores;
			$envEvaluations[] = $env;

		}
		$this->EnvEvaluation->recursive = 0;
		$this->set('envEvaluations', $envEvaluations);
		
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
		$this->paginate = array('order'=>array('EnvEvaluation.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->EnvEvaluation->recursive = 0;
		$this->set('envEvaluations', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['EnvEvaluation']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['EnvEvaluation']['search_field'] as $search):
				$search_array[] = array('EnvEvaluation.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('EnvEvaluation.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->EnvEvaluation->recursive = 0;
		$this->paginate = array('order'=>array('EnvEvaluation.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'EnvEvaluation.soft_delete'=>0 , $cons));
		$this->set('envEvaluations', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('EnvEvaluation.'.$search => $search_key);
					else $search_array[] = array('EnvEvaluation.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('EnvEvaluation.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('EnvEvaluation.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'EnvEvaluation.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('EnvEvaluation.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('EnvEvaluation.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->EnvEvaluation->recursive = 0;
		$this->paginate = array('order'=>array('EnvEvaluation.sr_no'=>'DESC'),'conditions'=>$conditions , 'EnvEvaluation.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('envEvaluations', $this->paginate());
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
		if (!$this->EnvEvaluation->exists($id)) {
			throw new NotFoundException(__('Invalid env evaluation'));
		}
		$options = array('conditions' => array('EnvEvaluation.' . $this->EnvEvaluation->primaryKey => $id));
		$envEvaluation = $this->EnvEvaluation->find('first', $options);
		$this->set('envEvaluation', $envEvaluation);
		$this->loadModel('EnvEvaluationScore');
		$scores = $this->EnvEvaluationScore->find('all',array('fields'=>
		array(
			'EnvEvaluationScore.id',
			'EnvEvaluationScore.score',
			'EnvEvaluationScore.env_activity_id',
			'EnvEvaluationScore.env_identification_id',
			'EnvEvaluationScore.env_evaluation_id',
			'EnvActivity.id',
			'EnvActivity.title',
			'EnvIdentification.id',
			'EnvIdentification.title',
			'EnvEvaluation.id',
			'EnvEvaluation.title',
			'EvaluationCriteria.id',
			'EvaluationCriteria.name',
			),'conditions'=>array('EnvEvaluationScore.env_evaluation_id'=>$envEvaluation['EnvEvaluation']['id']),'recursive'=>1));
		
		$this->set('scores',$scores);		
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
			$this->request->data['EnvEvaluation']['system_table_id'] = $this->_get_system_table_id();
			$this->EnvEvaluation->create();
			if ($this->EnvEvaluation->save($this->request->data)) {
				foreach ($this->request->data['EnvEvaluationScore'] as $score) {
					$this->_add_scores($this->request->data, $score, 'add_ajax',$this->EnvEvaluation->id);
				}	
					
				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The env evaluation has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->EnvEvaluation->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The env evaluation could not be saved. Please, try again.'));
			}
		}
		$envActivities = $this->EnvEvaluation->EnvActivity->find('list',array('conditions'=>array('EnvActivity.publish'=>1,'EnvActivity.soft_delete'=>0)));
		$envIdentifications = $this->EnvEvaluation->EnvIdentification->find('list',array('conditions'=>array('EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));
		$systemTables = $this->EnvEvaluation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EnvEvaluation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->EnvEvaluation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EnvEvaluation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EnvEvaluation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EnvEvaluation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('envActivities', 'envIdentifications', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->EnvEvaluation->find('count');
		$published = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.publish'=>1)));
		$unpublished = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.publish'=>0)));
		$this->set(compact('count','published','unpublished'));

		$this->loadModel('EvaluationCriteria');
		$cats = $this->EvaluationCriteria->find('list');
		$this->set('cats',$cats);

	}



/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->EnvEvaluation->exists($id)) {
			throw new NotFoundException(__('Invalid env evaluation'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      		if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        	$this->request->data[$this->modelClass]['publish'] = 0;
      	}
			$this->request->data['EnvEvaluation']['system_table_id'] = $this->_get_system_table_id();
			if ($this->EnvEvaluation->save($this->request->data)) {
				
				//update scores
				$this->loadModel('EnvEvaluationScore');
				$this->EnvEvaluationScore->deleteAll(array('EnvEvaluationScore.env_evaluation_id'=>$id));
				foreach ($this->request->data['EnvEvaluationScore'] as $score) {
					$this->_add_scores($this->request->data, $score, 'add_ajax',$id);
				}	
				
				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The env evaluation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EnvEvaluation.' . $this->EnvEvaluation->primaryKey => $id));
			$this->request->data = $this->EnvEvaluation->find('first', $options);
		}
		$envActivities = $this->EnvEvaluation->EnvActivity->find('list',array('conditions'=>array('EnvActivity.publish'=>1,'EnvActivity.soft_delete'=>0)));
		$envIdentifications = $this->EnvEvaluation->EnvIdentification->find('list',array('conditions'=>array('EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));
		$systemTables = $this->EnvEvaluation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EnvEvaluation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->EnvEvaluation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EnvEvaluation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EnvEvaluation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EnvEvaluation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('envActivities', 'envIdentifications', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->EnvEvaluation->find('count');
		$published = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.publish'=>1)));
		$unpublished = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		$this->loadModel('EnvEvaluationScore');
		$scores = $this->EnvEvaluationScore->find('all',array(
			'fields'=>
			array(
				'EnvEvaluationScore.id',
				'EnvEvaluationScore.score',
				'EnvEvaluationScore.env_activity_id',
				'EnvEvaluationScore.env_identification_id',
				'EnvEvaluationScore.env_evaluation_id',
				'EnvActivity.id',
				'EnvActivity.title',
				'EnvIdentification.id',
				'EnvIdentification.title',
				'EnvEvaluation.id',
				'EnvEvaluation.title',
				'EvaluationCriteria.id',
				'EvaluationCriteria.name',
				),
			'conditions'=>array(
				'EnvEvaluationScore.env_activity_id' => $this->request->data['EnvEvaluation']['env_activity_id'],
				'EnvEvaluationScore.env_identification_id' => $this->request->data['EnvEvaluation']['env_identification_id'],
			)));
		$this->set('scores',$scores);
		$this->loadModel('EvaluationCriteria');
		$cats = $this->EvaluationCriteria->find('list');
		$this->set('cats',$cats);
		if(!$scores){
			// $this->loadModel('EvaluationCriteria');
			// $cats = $this->EvaluationCriteria->find('list');
			// $this->set('cats',$cats);
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
		if (!$this->EnvEvaluation->exists($id)) {
			throw new NotFoundException(__('Invalid env evaluation'));
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
			if ($this->EnvEvaluation->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            
            if ($this->EnvEvaluation->save($this->request->data)) {
                $this->Session->setFlash(__('The env evaluation has been saved.'));
                //update scores
				
				$this->loadModel('EnvEvaluationScore');
				$this->EnvEvaluationScore->deleteAll(array('EnvEvaluationScore.env_evaluation_id'=>$id));
				foreach ($this->request->data['EnvEvaluationScore'] as $score) {
					$this->_add_scores($this->request->data, $score, 'add_ajax',$id);
				}	
                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The env evaluation could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The env evaluation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EnvEvaluation.' . $this->EnvEvaluation->primaryKey => $id));
			$this->request->data = $this->EnvEvaluation->find('first', $options);
		}
		$envActivities = $this->EnvEvaluation->EnvActivity->find('list',array('conditions'=>array('EnvActivity.publish'=>1,'EnvActivity.soft_delete'=>0)));
		$envIdentifications = $this->EnvEvaluation->EnvIdentification->find('list',array('conditions'=>array('EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));
		$systemTables = $this->EnvEvaluation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EnvEvaluation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->EnvEvaluation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EnvEvaluation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EnvEvaluation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EnvEvaluation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('envActivities', 'envIdentifications', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->EnvEvaluation->find('count');
		$published = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.publish'=>1)));
		$unpublished = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));
		$this->loadModel('EnvEvaluationScore');
		$scores = $this->EnvEvaluationScore->find('all',array(
			'fields'=>
			array(
				'EnvEvaluationScore.id',
				'EnvEvaluationScore.score',
				'EnvEvaluationScore.env_activity_id',
				'EnvEvaluationScore.env_identification_id',
				'EnvEvaluationScore.env_evaluation_id',
				'EnvActivity.id',
				'EnvActivity.title',
				'EnvIdentification.id',
				'EnvIdentification.title',
				'EnvEvaluation.id',
				'EnvEvaluation.title',
				'EvaluationCriteria.id',
				'EvaluationCriteria.name',
				),
			'conditions'=>array(
				'EnvEvaluationScore.env_activity_id' => $this->request->data['EnvEvaluation']['env_activity_id'],
				'EnvEvaluationScore.env_identification_id' => $this->request->data['EnvEvaluation']['env_identification_id'],
			)));
		$this->set('scores',$scores);
		$this->loadModel('EvaluationCriteria');
		$cats = $this->EvaluationCriteria->find('list');
		$this->set('cats',$cats);
		if(!$scores){
			// $this->loadModel('EvaluationCriteria');
			// $cats = $this->EvaluationCriteria->find('list');
			// $this->set('cats',$cats);
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
		$this->EnvEvaluation->id = $id;
		if (!$this->EnvEvaluation->exists()) {
			throw new NotFoundException(__('Invalid env evaluation'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->EnvEvaluation->delete()) {
			$this->Session->setFlash(__('Env evaluation deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Env evaluation was not deleted'));
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
		
		$result = explode('+',$this->request->data['envEvaluations']['rec_selected']);
		$this->EnvEvaluation->recursive = 1;
		$envEvaluations = $this->EnvEvaluation->find('all',array('EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>1,'conditions'=>array('or'=>array('EnvEvaluation.id'=>$result))));
		$this->set('envEvaluations', $envEvaluations);
		
		$envActivities = $this->EnvEvaluation->EnvActivity->find('list',array('conditions'=>array('EnvActivity.publish'=>1,'EnvActivity.soft_delete'=>0)));
		$envIdentifications = $this->EnvEvaluation->EnvIdentification->find('list',array('conditions'=>array('EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));
		$systemTables = $this->EnvEvaluation->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EnvEvaluation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->EnvEvaluation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EnvEvaluation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EnvEvaluation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EnvEvaluation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('envActivities', 'envIdentifications', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'envActivities', 'envIdentifications', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function get_identifications($id = null){
		$envIdentifications = $this->EnvEvaluation->EnvIdentification->find('all',array('conditions'=>array('EnvIdentification.env_activity_id'=> $id, 'EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));
		$envIndentification_lists = $this->EnvEvaluation->EnvIdentification->find('list',array('conditions'=>array('EnvIdentification.env_activity_id'=> $id, 'EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));
		$this->set('envIdentifications',$envIdentifications);
		$this->set('envIndentification_lists',$envIndentification_lists);
	}

	public function _add_scores($data = null, $env = null, $type = null,$id=null){
		$this->loadModel('EnvEvaluationScore');
		$f_score['EnvEvaluationScore']['env_activity_id'] = $data['EnvEvaluation']['env_activity_id'];
		$f_score['EnvEvaluationScore']['env_identification_id'] = $data['EnvEvaluation']['env_identification_id'];
		$f_score['EnvEvaluationScore']['env_evaluation_id'] = $id;
		$f_score['EnvEvaluationScore']['evaluation_criteria_id'] = $env['evaluation_criteria_id'];
		$f_score['EnvEvaluationScore']['score'] = $env['score'];
		$f_score['EnvEvaluationScore']['publish'] = $data['EnvEvaluation']['publish'];
		$f_score['EnvEvaluationScore']['soft_delete'] = $data['EnvEvaluation']['soft_delete'];
		$f_score['EnvEvaluationScore']['created_by'] = $data['EnvEvaluation']['created_by'];
		$f_score['EnvEvaluationScore']['modified_by'] = $data['EnvEvaluation']['modified_by'];
		$this->EnvEvaluationScore->create();
		$this->EnvEvaluationScore->save($f_score,false);		
	}
}

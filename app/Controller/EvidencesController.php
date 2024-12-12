	<?php
	session_start();
	App::uses('AppController', 'Controller');
	/**
	 * Evidences Controller
	 *
	 * @property Evidence $Evidence
	 */
	class EvidencesController extends AppController {

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
	public $skip = array('Products','Dashboards','file_uploads','File Uploads',
		'approvals','benchmarks','branch_benchmarks','companies','company_benchmarks',
		'custom_templates','file_shares','helps','meeting_attendees','meeting_branches',
		'meeting_employees','meeting_topics','messages','message_user_inboxes','message_user_sents',
		'message_user_thrashes','notification_users','process_teams','product_materials','system_tables',
		'timelines','training_schedules','training_schedule_branches','training_schedule_departments',
		'training_schedule_employees','user_sessions','user_groups','histories','languages','errors','evidences',
		'meeting_departments','order_details_forms','order_registers','pages','reports','task_status',
		'master_list_of_format_branches','master_list_of_format_departments','master_list_of_format_distributors','master_list_of_work_instructions');
	
	public function index() {
		$this->loadModel('SystemTable');
		$models = $this->SystemTable->find('list');
		$this->set(compact('models'));
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Evidence.sr_no'=>'DESC'),'conditions'=>array($conditions));
		$this->Evidence->recursive = 0;
		$records = $this->paginate();
		
		foreach($records as $record){			
			$modelToLoad = $record['Evidence']['model_name'];
			$modelToLoad = Inflector::Classify($models[$modelToLoad]);
			     
	                if($record['Evidence']['model_name'] != 0 && $record['Evidence']['model_name'] != 1 && $record['Evidence']['model_name'] != 2){
			$model = $this->loadModel($modelToLoad);				
				$record_details = $this->$modelToLoad->find('first',array(
					'conditions'=>array($modelToLoad.'.id'=>$record['Evidence']['record']),
					'fields'=>array($this->$modelToLoad->displayField,$modelToLoad.'.id'),
					'recursive' => '-1'
					));
				$record['RecordDetails']['id'] = $record_details[$modelToLoad]['id'];
				$record['RecordDetails']['name'] =  $record_details[$modelToLoad][$this->$modelToLoad->displayField];
				$record['RecordDetails']['model_name'] =  $modelToLoad;
			

		}elseif($record['Evidence']['model_name'] == 0){		
	                $special = $this->_get_specials();
	               	$record['TableDetails'] =  array_keys($special);
	                $record['RecordDetails']['name'] = $special[$record['TableDetails'][0]][$record['Evidence']['record']]; 
	                $record['RecordDetails']['model_name'] = $record['TableDetails'][0]; 
	               
			
		}elseif($record['Evidence']['model_name'] == 1 ){
	                $special = $this->_get_specials();
	                $this->loadModel('Product');
	                $products = $this->Product->find('first',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0, 
	                'Product.id'=>$record['Evidence']['record'])));
	                $record['TableDetails'][0] =  array_keys($special)[1];
			$record['RecordDetails']['name'] = $products['Product']['name'].' ('.$special[$record['TableDetails'][0]][$record['Evidence']['record_type']].')';
			$record['RecordDetails']['model_name'] = $record['TableDetails'][0];
			$record['RecordDetails']['id'] = $record['Evidence']['record'];
	     	}
                elseif($record['Evidence']['model_name'] == 2 ){
	                $special = $this->_get_specials();
	                $this->loadModel('DesignHistoryFile');
	                $designHistoryFiles = $this->DesignHistoryFile->find('first',array('conditions'=>array('DesignHistoryFile.publish'=>1,'DesignHistoryFile.soft_delete'=>0, 
	                'DesignHistoryFile.id'=>$record['Evidence']['record'])));
	                $record['TableDetails'][0] =  array_keys($special)[2];
			$record['RecordDetails']['name'] = $designHistoryFiles['DesignHistoryFile']['name'].' ('.$special[$record['TableDetails'][0]][$record['Evidence']['record_type']].')';
			$record['RecordDetails']['model_name'] = $record['TableDetails'][0];
			$record['RecordDetails']['id'] = $record['Evidence']['record'];
	     	}

			$result[] = $record;

		}	
	      
		$this->set('evidences', $result);
		$this->_get_count();

	}

	public function lists() {$this->_get_count();}

	/**
	 * adcanced_search method
	 * Advanced search by - TGS
	 * @return void
	 */
	public function advanced_search() {

        $conditions = array();
        $this->loadModel('SystemTable');
        $models = $this->SystemTable->find('list');
        if ($this->request->query['keywords']) {
           
            $searchArray = array();
            if ($this->request->query['strict_search'] == 0) {
                $SearchKeys[] = $this->request->query['keywords'];
            } else {
                $SearchKeys = explode(" ", $this->request->query['keywords']);
            }

            foreach ($SearchKeys as $SearchKey):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $searchArray[] = array('Evidence.' . $search => $SearchKey);
                    else
                        $searchArray[] = array('.' . $search . ' like ' => '%' . $SearchKey . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }
        if ($this->request->query['model_name'] != -1) {
            $modelConditions[] = array('Evidence.model_name' => $this->request->query['model_name']);
      
          
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $modelConditions);
            else
                $conditions[] = array('or' => $modelConditions);
        }
        if ($this->request->query['record'] != -1) {
            $recordConditions[] = array('Evidence.record' => $this->request->query['record']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $recordConditions);
            else
                $conditions[] = array('or' => $recordConditions);
        }
        if ($this->request->query['record_type'] != -1) {
            $recordTypeConditions[] = array('Evidence.record_type' => $this->request->query['record_type']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $recordTypeConditions);
            else
                $conditions[] = array('or' => $recordTypeConditions);
        }
        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $evidenceConditions[] = array('Evidence.branch_id' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $evidenceConditions));
            else
                $conditions[] = array('or' => $evidenceConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('Evidence.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'Evidence.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
	
	$conditions =  $this->advance_search_common($conditions);



        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('Evidence.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('Evidence.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->Evidence->recursive = -1;
        $this->paginate = array('order' => array('Evidence.sr_no' => 'DESC'), 'conditions' => $conditions, 'Evidence.soft_delete' => 0);
        $records = $this->paginate();
		
		foreach($records as $record){			
			$modelToLoad = $record['Evidence']['model_name'];
			$modelToLoad = Inflector::Classify($models[$modelToLoad]);
		
	                
	                if($record['Evidence']['model_name'] != 0 && $record['Evidence']['model_name'] != 1){
			$model = $this->loadModel($modelToLoad);				
				$record_details = $this->$modelToLoad->find('first',array(
					'conditions'=>array($modelToLoad.'.id'=>$record['Evidence']['record']),
					'fields'=>array($this->$modelToLoad->displayField,$modelToLoad.'.id'),
					'recursive' => '-1'
					));
				$record['RecordDetails']['id'] = $record_details[$modelToLoad]['id'];
				$record['RecordDetails']['name'] =  $record_details[$modelToLoad][$this->$modelToLoad->displayField];
				$record['RecordDetails']['model_name'] =  $modelToLoad;
			

		}elseif($record['Evidence']['model_name'] == 0){		
	                $special = $this->_get_specials();
	               	$record['TableDetails'] =  array_keys($special);
	                $record['RecordDetails']['name'] = $special[$record['TableDetails'][0]][$record['Evidence']['record']]; 
	                $record['RecordDetails']['model_name'] = $record['TableDetails'][0]; 
	               
			
		}elseif($record['Evidence']['model_name'] == 1 ){
	                $special = $this->_get_specials();
	                $this->loadModel('Product');
	                $products = $this->Product->find('first',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0, 
	                'Product.id'=>$record['Evidence']['record'])));
	                $record['TableDetails'][0] =  array_keys($special)[1];
			$record['RecordDetails']['name'] = $products['Product']['name'].' ('.$special[$record['TableDetails'][0]][$record['Evidence']['record_type']].')';
			$record['RecordDetails']['model_name'] = $record['TableDetails'][0];
			$record['RecordDetails']['id'] = $record['Evidence']['record'];
	     	}

			$result[] = $record;

		}	
	      
		$this->set('evidences', $result);

        $this->render('index');
    }

	//

	public function add_ajax() {

		if ($this->_show_approvals()) {
			$this->set(array('showApprovals' => $this->_show_approvals()));
		}
		if ($this->request->is('post')) 
		{
			if(!$this->request->data['Evidence']['document']){
				$this->Session->setFlash(__('You have not uploaded any document. Please upload document.'));
				$this->redirect(array('action' => 'index'));
			}
			
			if ($this->Evidence->save($this->request->data,false)) 
			{
				if(isset($this->request->data['Evidence']['document']['error']) && $this->request->data['Evidence']['document']['error'] == 0)
				{
					//get type of upload
					//get path
					$folder = $this->_defineFolder($this->request->data);
					$folder = $folder['path'];
					$this->_file_save_version($this->request->data, $this->Evidence->id ,$folder);					
				}

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true){
					$this->redirect(array('action' => 'view', $this->Evidence->id));
				}else{
					$this->redirect(array('action' => 'index'));
				}
			} else {
				$this->Session->setFlash(__('The evidence could not be saved. Please, try again.'));
			}
			
			//echo "asdas";
		}
		
		$this->loadModel('SystemTable');
		//$skip = array('Products','Dashboards','File Uploads','approvals','benchmarks','branch_benchmarks','companies','company_benchmarks','custom_templates','file_shares','helps','meeting_attendees','meeting_branches','meeting_employees','meeting_topics','messages','message_user_inboxes','message_user_sents','message_user_thrashes','notification_users','process_teams','product_materials','system_tables','timelines','training_schedules','training_schedule_branches','training_schedule_departments','training_schedule_employees','user_sessions','user_groups');
		$models = $this->SystemTable->find('list',array('conditions'=>array('SystemTable.system_name != '=> $this->skip)));
		$clause_key = array_search('Clauses',$models,false);
		$approvedBies = $this->Evidence->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->Evidence->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies','models','clause_key'));
		$count = $this->Evidence->find('count');
		$published = $this->Evidence->find('count',array('conditions'=>array('Evidence.publish'=>1)));
		$unpublished = $this->Evidence->find('count',array('conditions'=>array('Evidence.publish'=>0)));
		$this->set(compact('count','published','unpublished'));

		if(isset($this->request->params['named']['model'])){
			$a = $this->get_records(Inflector::Classify($this->request->params['named']['model']),null,false);
			if($a){
				$b = $this->get_records(Inflector::Classify($this->request->params['named']['model']),$this->request->params['named']['record'],false);
				$this->set('all_recs',$b);
				$this->set('selected_record',$this->request->params['named']['record']);
				
				if($this->request->params['named']['model'] == 'products'){
					$pro_special = $this->_get_specials();
					$pro_special = $pro_special['Product Files'];
					$this->set('pro_special',$pro_special);
				}elseif($this->request->params['named']['model'] == 'dashboard_files'){
					$pro_special = $this->_get_specials();
					$pro_special = $pro_special['Dashboard Files'];
					$this->set('pro_special',$pro_special);
				}elseif($this->request->params['named']['model'] == 'clauses'){
					$this->loadModel('Clause');
					$clause_special = $this->Clause->find('first',array('conditions'=>array('Clause.id'=>$this->request->params['named']['record'])));
					$clause_special = explode(',', $clause_special['Clause']['tabs']);
					$clause_special_selected = array_search($this->request->params['named']['record_type'], $clause_special);
					$this->set('clause_special',$clause_special);
					$this->set('clause_special_selected',$clause_special_selected);
				}
			}			
		}

	}

	//


	public function get_records($model_name =null,$record = null,$render = null){
		$this->loadModel('SystemTable');
		$models = $this->SystemTable->find('list',array('conditions'=>array('SystemTable.name != '=> $this->skip)));
		$clause_key = array_search('Clauses',$models,false);
		
		if(isset($render) && $render == false){			
			$model_names = $this->SystemTable->find('first',array('fields'=>array('id','name'),'recursive'=>-1, 'conditions'=>array('SystemTable.system_name'=> Inflector::tableize($model_name))));
			$model_name = $model_names['SystemTable']['id'];
			$this->set('selected_model',$model_name);
		}
		if($model_name != 0 && $model_name != 1 && $model_name != 2 && $model_name != 'product_files' && $model_name != 'design_history_files' && $model_name != $clause_key){
            $this->loadModel('SystemTable');
			$table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.id'=>$model_name)));
			$table = Inflector::Classify($table['SystemTable']['system_name']);
			$model = $this->loadModel($table);
			$all_recs = $this->$table->find('list');

		}else{
			if($model_name == 'product_files'){
				$special = $this->_get_specials();
				$all_recs = $special['Product Files'];
			}elseif($model_name == $clause_key){
				$this->loadModel('Clause');
				$all_recs = $this->Clause->find('list',array('order'=>array('Clause.standard'=>'ASC','Clause.sub-clause'=>'ASC')));
			}elseif($model_name == 'clauses'){
				$this->loadModel('Clause');
				$rec = $this->Clause->find('first',array('conditions'=> array('Clause.id'=>$record)));
				$all_recs = explode(',', $rec['Clause']['tabs']);
			}elseif($model_name == 'design_history_files'){
                $special = $this->_get_specials();
				$all_recs = $special['Design History Files'];
			}elseif($model_name == 0){
				$special = $this->_get_specials();
				$all_recs = $special['Dashboard Files'];
			}elseif($model_name == 2){
				$this->loadModel('SystemTable');
				$table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>$system_name)));
				$table = Inflector::Classify($table['SystemTable']['system_name']);
				$model = $this->loadModel($table);
				$all_recs = $this->$table->find('list');
			}elseif($model_name == 1){
				$this->loadModel('SystemTable');
				$table = $this->SystemTable->find('first',array('conditions'=>array('SystemTable.system_name'=>'products')));
				$table = Inflector::Classify($table['SystemTable']['system_name']);
				$model = $this->loadModel($table);
				$all_recs = $this->$table->find('list');
			}elseif($model_name == '5297b2e6-2098-4689-9301-2d8f0a000005'){
				$this->loadModel('Calibration');
				$all_recs = $this->Calibration->find('all',array('fields'=>array('Calibration.id','Device.name','Calibration.calibration_date')));
				foreach ($all_recs as $rec) {
					$new_recs[$rec['Calibration']['id']] = $rec['Device']['name'] .'-'.$rec['Calibration']['calibration_date'];					
				}
				$all_recs  = $new_recs;
			}
		}		
	      
		$this->set('options', $all_recs);
		if(!isset($render)){
			$this->render('ajax_options');
		}else{
			return $all_recs;
		}

	}


	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		if (!$this->Evidence->exists($id)) {
			throw new NotFoundException(__('Invalid evidence'));
		}

		if ($this->_show_approvals()) {
			$this->set(array('showApprovals' => $this->_show_approvals()));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if(!$this->request->data['Evidence']['document']){
				$this->Session->setFlash(__('You have not uploaded any document. Please upload document.'));
				$this->redirect(array('action' => 'index'));
			}

			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
				$this->request->data[$this->modelClass]['publish'] = 0;
			}

			$this->request->data['Evidence']['system_table_id'] = $this->_get_system_table_id();
			if ($this->Evidence->save($this->request->data,false)) {
				if(isset($this->request->data['Evidence']['document']['error']) && $this->request->data['Evidence']['document']['error'] == 0){
					//get type of upload
					//get path
					$folder = $this->_defineFolder($this->request->data);
					$folder = $folder['path'];
					$this->_file_save_version($this->request->data, $this->Evidence->id ,$folder);
				}

				if ($this->_show_approvals()) $this->_save_approvals();
				if ($this->_show_evidence() == true)
					$this->redirect(array('action' => 'index', $id));
				else
					$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The evidence could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Evidence.' . $this->Evidence->primaryKey => $id));
			$this->request->data = $this->Evidence->find('first', $options);
			$folder = $this->_defineFolder($this->request->data);
			$folder = $folder['path'];
			$this->set('foler_path',$folder);

		}
		$this->loadModel('SystemTable');
		$models = $this->SystemTable->find('list',array('conditions'=>array('SystemTable.system_name != '=> $this->skip)));
		$clause_key = array_search('Clauses',$models,false);
		$approvedBies = $this->Evidence->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->Evidence->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies','models','clause_key'));
		
		$count = $this->Evidence->find('count');
		$published = $this->Evidence->find('count',array('conditions'=>array('Evidence.publish'=>1)));
		$unpublished = $this->Evidence->find('count',array('conditions'=>array('Evidence.publish'=>0)));
		$this->set(compact('count','published','unpublished'));
		$this->set('records',$this->_getRecordDetails($this->request->data, $models));
    }

    public function approve($id = null) {
		if (!$this->Evidence->exists($id)) {
			throw new NotFoundException(__('Invalid evidence'));
		}

		if ($this->_show_approvals()) {
			$this->set(array('showApprovals' => $this->_show_approvals()));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if(!$this->request->data['Evidence']['document']){
				$this->Session->setFlash(__('You have not uploaded any document. Please upload document.'));
				$this->redirect(array('action' => 'index'));
			}

			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
				$this->request->data[$this->modelClass]['publish'] = 0;
			}

			$this->request->data['Evidence']['system_table_id'] = $this->_get_system_table_id();
			if ($this->Evidence->save($this->request->data,false)) {
				if(isset($this->request->data['Evidence']['document']['error']) && $this->request->data['Evidence']['document']['error'] == 0){
					//get type of upload
					//get path
					$folder = $this->_defineFolder($this->request->data);
					$folder = $folder['path'];
					$this->_file_save_version($this->request->data, $this->Evidence->id ,$folder);
				}

				if ($this->_show_approvals()) $this->_save_approvals();
				if ($this->_show_evidence() == true)
					$this->redirect(array('action' => 'index', $id));
				else
					$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The evidence could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Evidence.' . $this->Evidence->primaryKey => $id));
			$this->request->data = $this->Evidence->find('first', $options);
			$folder = $this->_defineFolder($this->request->data);
			$folder = $folder['path'];
			$new_folder = str_replace($this->Session->read('User.id'), $this->request->data['Evidence']['created_by'], $folder);
			$folder = $new_folder;
			$this->set('foler_path',$folder);
			
		}
		$this->loadModel('SystemTable');
		$models = $this->SystemTable->find('list',array('conditions'=>array('SystemTable.system_name != '=> $this->skip)));
		$clause_key = array_search('Clauses',$models,false);
		$approvedBies = $this->Evidence->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->Evidence->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies','models','clause_key'));
		
		$count = $this->Evidence->find('count');
		$published = $this->Evidence->find('count',array('conditions'=>array('Evidence.publish'=>1)));
		$unpublished = $this->Evidence->find('count',array('conditions'=>array('Evidence.publish'=>0)));
		$this->set(compact('count','published','unpublished'));
		$this->set('records',$this->_getRecordDetails($this->request->data, $models));
    }


    /**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		if (!$this->Evidence->exists($id)) {
			throw new NotFoundException(__('Invalid evidence'));
		}
		$options = array('conditions' => array('Evidence.' . $this->Evidence->primaryKey => $id));
		$evidence = $this->Evidence->find('first', $options);
		$this->set('evidence', $evidence);

		$this->loadModel('SystemTable');
		$models = $this->SystemTable->find('list',array('conditions'=>array('SystemTable.name != '=> $this->skip)));
			
		if ($this->request->is('post')) {
			$this->loadModel('FileUpload');
			$this->FileUpload->create();
			if ($this->Evidence->save($this->request->data)) {
				$this->_save_file($this->request->data,$this->Evidence->id);
				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The evidence has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Evidence->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The evidence could not be saved. Please, try again.'));
			}
		}
		$this->loadModel('FileUpload');
		$existing_files = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.record'=>$evidence['Evidence']['record'])));
		$this->set('existing_files',$existing_files);
		$folder = $this->_defineFolder($evidence);
		$folder = $folder['path'];
		$this->set('folder_path',$folder);
		$rec = $this->_getRecordDetails($evidence);
		$this->set('record',$rec[$evidence['Evidence']['record']]);
		$this->set('model',$models[$evidence['Evidence']['model_name']]);

		$approvedBies = $this->Evidence->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->Evidence->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies'));
	}

	public function arrange_files(){
		$selected_files = ltrim(rtrim($this->request->data['Evidence']['selected_files']));
		$selected_files = explode(',',$selected_files);
		foreach ($selected_files as $key => $value) {
			if($value != '')$files_to_add[] = $value;
		}
		$records = $this->_defineFolder($this->request->data);
		$original_dir = $records['path'];
		
		foreach ($files_to_add as $key => $value) {
			$file_name = explode('-ver', $value);
			$original_file_name = ltrim(rtrim($file_name[0]));
			$extension = explode('.', $file_name[1]);
			$filename = ltrim(rtrim($file_name[0]));
			$ext =  $extension[1];
			$message = 'Added after approval';
			if($records['system_name'] !='documents' && $records['system_name'] !='clauses'  && $records['type'] !='products'){
				$dir = str_replace('evidences', 'upload' .DS . $this->Session->read('User.id'), $records['path']);
				$base = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id') .DS ;
				$dir = str_replace($base, '', $dir);
				$dir = ltrim($dir,DS);
				$rev_folder = $rev_folder_path = $base . 'revisions' . DS . $records['system_name']  . DS . $this->request->data['Evidence']['record'];
			}else{
				$dir = str_replace('evidences', 'upload', $records['path']);
				$base = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id') .DS ;
				$dir = str_replace($base, '', $dir);
				$dir = ltrim($dir,DS);
				if($records['type']=='products'){
					$dir = $dir;
					$dir = str_replace($this->request->data['Evidence']['record'], Inflector::Classify($this->request->data['Evidence']['record']), $dir);
					$rev_folder = $rev_folder_path = $base . 'revisions' . DS . 'products'  . DS . $this->request->data['Evidence']['record'] . DS . Inflector::Classify($this->request->data['Evidence']['record_type']);
				}else{
					$rev_folder = $rev_folder_path = $base . 'revisions' . DS . $records['system_name']  . DS . $this->request->data['Evidence']['record'] . DS .  $records['record_types'][$this->request->data['Evidence']['record_type']];
				}				
			}
				
			$rev_folder = new Folder($rev_folder);
			$all_files = $rev_folder->find();
			
			if(in_array('.'.$filename, $all_files)){
				$file = new File($rev_folder_path . DS . '.'.$filename);
			    $contents = $file->read();
			    $contents = $contents + 1;
			    $filename = $filename .'-ver-'. $contents;
			    $file->write($contents);
			    $file->close();			    
			}else{
				mkdir($rev_folder_path,0777,true);
				$file = new File($rev_folder_path . DS . '.'.$filename);
			    $file->write(1);
			    $file->close(); // Be sure to close the file when you're done	
				$filename = $filename .'-ver-1';
			}
			//move file 
			// get file
			// rename
			// move
			$copy_from = $records['path'] . DS . rtrim(ltrim($value));
			$copy_to = $base . $dir;
			$copy_to = $copy_to . DS . $filename.'.'.$ext;
			chmod($base.$dir, 0777);
			if(!file_exists($base.$dir))mkdir($base.$dir,0777,true);			
			if(!copy($copy_from,$copy_to)){
				$this->Session->setFlash(__('Destination folder is not writable.'), 'default', array('class' => 'alert alert-danger'));
				$this->redirect(array('action' => 'view', $this->request->data['Evidence']['id']));
			}else{
				$this->_upload_add($filename,$ext,$message,$dir,$this->request->data['Evidence']['prepared_by']);
				unlink($copy_from);
				$this->Session->setFlash(__('File moved.'), 'default', array('class' => 'alert alert-success'));				
			}			

		}
		if($this->request->data['Evidence']['selected_files'] == NULL){
			$this->Session->setFlash(__('Nothing to move, please drag & drop files to move.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'view', $this->request->data['Evidence']['id']));
		}else{

		}
		$this->redirect(array('action' => 'view', $this->request->data['Evidence']['id']));
	}

	public function _defineFolder($data = null){
		$base = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id');
		$this->loadModel('SystemTable');
		$models = $this->SystemTable->find('list',array('fields'=>array('SystemTable.id','SystemTable.system_name'), 'conditions'=>array('SystemTable.name != '=> $this->skip)));
		$clause_key = array_search('Clauses',$models,false);
		
		switch ($data['Evidence']['model_name']) {
			case 0:
				# code... for dashboard files
				# path upload / documents / tab_name / files
				$path = $base . DS . 'evidences/documents/' . $data['Evidence']['record']; 
				$model_name = 'documents';
				$system_table_id = 'dashboards';
				$system_name = 'documents';
				$type = 'documents';
				//echo 0;
				break;

			case 1:
				# code... for product files
				# upload / user_id / products / product_id / tab_name
				//$path = $base . DS . 'evidences' . DS . $this->Session->read('User.id') . DS . 'products' . DS . $data['Evidence']['record'] . DS . Inflector::Classify($data['Evidence']['record_type']);
				$path = $base . DS . 'evidences' . DS . 'products' . DS . $data['Evidence']['record'] . DS . Inflector::Classify($data['Evidence']['record_type']);
				$model_name = $data['Evidence']['model_name'];
				$system_table_id = $data['Evidence']['model_name'];
				$system_name = Inflector::tableize($models[$data['Evidence']['model_name']]);
				$special = $this->_get_specials();
				$record_types = $special['Product Files'];
				$record_types = Inflector::Classify($record_types[$data['Evidence']['record_types']]);
				$type = 'products';
				//echo 1;
				break;	
			case $clause_key:
				# code... for clauses
				# upload / clauses / record_id / ? record_type (tab_name)
				$this->loadModel('Clause');
				$records = $this->Clause->find('list');
				$record_types = $this->Clause->find('first',array('fields'=>array('Clause.id','Clause.tabs'), 'conditions'=>array('Clause.id'=>$data['Evidence']['record'])));
				$record_types = explode(',', $record_types['Clause']['tabs']);
				
				$path = $base . DS . 'evidences/clauses' . DS . $data['Evidence']['record'] . DS . $record_types[$data['Evidence']['record_type']]; 
				$model_name = 'clauses';
				$system_table_id = 'clauses';
				$system_name = 'clauses';
				$type = 'clauses';
				//echo 'clause';
				break;

			default:
				# code... for rest of the models
				# upload/  user_id / model_actual_name_purl / record_id/
				$path = $base . DS . 'evidences' .  DS . Inflector::underscore(Inflector::variable($models[$data['Evidence']['model_name']])) . DS . $data['Evidence']['record'];
				$model_name = $data['Evidence']['model_name'];
				$system_table_id = $data['Evidence']['model_name'];
				$system_name = Inflector::tableize($models[$data['Evidence']['model_name']]);
				$type = 'general';
				break;			
		}
		return array('path'=>$path,'model_name'=>$model_name,'system_table_id'=>$system_table_id,'record_types'=>$record_types,'system_name'=>$system_name,'type'=>$type);
	}

	public function _getRecordDetails($data = null, $models = null){
		$this->loadModel('SystemTable');
		$models = $this->SystemTable->find('list',array('conditions'=>array('SystemTable.name != '=> $this->skip)));
		$clause_key = array_search('Clauses',$models,false);
		
		switch ($data['Evidence']['model_name']) {
			case 0:
				# code... for dashboard files
				$special = $this->_get_specials();
				return $special['Dashboard Files']; 
				break;

			case 1:
				# code... for product files
				$this->loadModel('Product');
				$records = $this->Product->find('list');
				$special = $this->_get_specials();
				$this->set('record_types',$special['Product Files']);
				break;	
			case $clause_key:
				# code... for clauses
				# upload / clauses / record_id / ? record_type (tab_name)
				$this->loadModel('Clause');
				$records = $this->Clause->find('list');
				$record_types = $this->Clause->find('first',array('fields'=>array('Clause.id','Clause.tabs'), 'conditions'=>array('Clause.id'=>$data['Evidence']['record'])));
				$record_types = explode(',', $record_types['Clause']['tabs']);
				$this->set('record_types',$record_types);
				//$this->set($this->request->data['Evidence']['record_type'],$this->request->data['Evidence']['record_type']+1);
				break;

			default:
				$model = Inflector::Classify($models[$data['Evidence']['model_name']]);
				$this->loadModel($model);
				$records = $this->$model->find('list');
				break;			
		}
		return $records;
	}


	public function _file_save_version($data = null, $id = null  ,$folder_name = null){
		
		$file = new File($data['Evidence']['document'], FALSE);
		$fileinfo = $file->info();
		if (filesize($data['Evidence']['document']['tmp_name']) > 5000000){
			$this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'view', $id));
		}

		if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
			$this->Session->setFlash(__('Document file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'view', $id));
		}

		if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
			$nameLengthCheck = false;
			$this->Session->setFlash(__('Document file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'view', $id));
		}
	    
	    $pos = $this->filename_extension($this->request->data['Evidence']['document']['name']);
	    $ext = substr($this->request->data['Evidence']['document']['name'], $pos+1);
	    
	    $name = substr($this->request->data['Evidence']['document']['name'], 0, $pos);
	    if(preg_match ( "/-ver-[0-9]+/" , $name,$new_name)){
	        $name = explode($new_name[0], $name);
	        $name = $name[0];
	    }
	    $split_name[0] = $name;
	    $split_name[0] = str_replace('.', '', $split_name[0]);
		$split_name[1] = $ext;
		$split_name[0] = str_replace(' ' ,'', $split_name[0]);
	   		
	   	$folder = new Folder($folder_name , TRUE, 0755);
		$all_files = $folder->find($split_name[0].'*.*', true);
		$last = count($all_files);
		if($last > 0){
			$rev = $last + 1;
			$split_name[0] = str_replace(' ' ,'', $split_name[0]);
			$name = str_replace('.', '', $split_name[0]) . '-ver-'. $rev . '.' .$ext;				
		}else{
			$split_name[0] = str_replace(' ' ,'', $split_name[0]);
			$name = str_replace('.', '', $split_name[0]).'-ver-1.'.$ext;			
		}
		// echo $folder_name;
		// echo $name;
	   	$this->_saveFile($name, $folder_name, $data['Evidence']['document']['tmp_name']);
		return $name;
	}

	function filename_extension($filename) {
	   return  $pos = strrpos($filename, '.');
	}

	function _saveFile($file_name = null ,$path = null, $file = null){
		$movefile = move_uploaded_file($file, $path . DS . $file_name); 
		
		if(!$movefile){
			$this->Session->setFlash(__('Document upload was not successful. Please try again.'), 'default', array('class' => 'alert alert-danger'));
			$this->redirect(array('action' => 'view', $id));
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
		$this->Evidence->id = $id;
		if (!$this->Evidence->exists()) {
			throw new NotFoundException(__('Invalid evidence'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Evidence->delete()) {
			$this->Session->setFlash(__('Evidence deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Evidence was not deleted'));
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
		$result = explode('+',$this->request->data['evidences']['rec_selected']);
		$this->Evidence->recursive = 1;
		$evidences = $this->Evidence->find('all',array('Evidence.publish'=>1,'Evidence.soft_delete'=>1,'conditions'=>array('or'=>array('Evidence.id'=>$result))));
		$this->set('evidences', $evidences);
		$approvedBies = $this->Evidence->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->Evidence->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$fileUploads = $this->Evidence->FileUpload->find('list',array('conditions'=>array('FileUpload.publish'=>1,'FileUpload.soft_delete'=>0)));
		$userSessions = $this->Evidence->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
		$systemTables = $this->Evidence->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Evidence->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Evidence->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$statusUserIds = $this->Evidence->StatusUserId->find('list',array('conditions'=>array('StatusUserId.publish'=>1,'StatusUserId.soft_delete'=>0)));
		$createdBies = $this->Evidence->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Evidence->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies', 'fileUploads', 'userSessions', 'systemTables', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies', 'approvedBies', 'preparedBies', 'fileUploads', 'userSessions', 'systemTables', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
	}
	

	public function isValidGuid($guid){
    	return !empty($guid) && preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $guid);
	}	
}

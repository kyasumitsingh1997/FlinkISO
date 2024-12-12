<?php

App::uses('AppController', 'Controller');

/**
 * MasterListOfFormatDepartments Controller
 *
 * @property MasterListOfFormatDepartment $MasterListOfFormatDepartment
 */
class MasterListOfFormatDepartmentsController extends AppController {

    public function _get_system_table_id() {

        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $sys_id = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $sys_id['SystemTable']['id'];
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {

        $conditions = $this->_check_request();
        if($this->Session->read('User.is_mr')==0){
            $access_conditions = array('MasterListOfFormat.user_id LIKE'=>'%'.$this->Session->read('User.id').'%');    
        }else{
            $access_conditions = array();
        }
        $this->paginate = array('order' => array('MasterListOfFormatDepartment.sr_no' => 'DESC'), 'conditions' => array($access_conditions,$conditions));

        $this->MasterListOfFormatDepartment->recursive = 0;
        $this->set('masterListOfFormatDepartments', $this->paginate());

        $this->_get_count();
    }

    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function advanced_search() {

        // $conditions = array();
        // if ($this->request->query['keywords']) {
        //     $search_array = array();
        //     $search_keys = explode(" ", $this->request->query['keywords']);

        //     foreach ($search_keys as $search_key):
        //         foreach ($this->request->query['search_fields'] as $search):
        //             if ($this->request->query['strict_search'] == 0)
        //                 $search_array[] = array('MasterListOfFormatDepartment.' . $search => $search_key);
        //             else
        //                 $search_array[] = array('MasterListOfFormatDepartment.' . $search . ' like ' => '%' . $search_key . '%');

        //         endforeach;
        //     endforeach;
        //     if ($this->request->query['strict_search'] == 0)
        //         $conditions[] = array('and' => $search_array);
        //     else
        //         $conditions[] = array('or' => $search_array);
        // }

        // if ($this->request->query['branch_list']) {
        //     foreach ($this->request->query['branch_list'] as $branches):
        //         $branch_conditions[] = array('MasterListOfFormatDepartment.branch_id' => $branches);
        //     endforeach;
        //     $conditions[] = array('or' => $branch_conditions);
        // }

        // if (!$this->request->query['to-date'])
        //     $this->request->query['to-date'] = date('Y-m-d');
        // if ($this->request->query['from-date']) {
        //     $conditions[] = array('MasterListOfFormatDepartment.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'MasterListOfFormatDepartment.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        // }
        // unset($this->request->query);


        // if ($this->Session->read('User.is_mr') == 0)
        //     $onlyBranch = array('MasterListOfFormatDepartment.branch_id' => $this->Session->read('User.branch_id'));
        // if ($this->Session->read('User.is_view_all') == 0)
        //     $onlyOwn = array('MasterListOfFormatDepartment.created_by' => $this->Session->read('User.id'));
        // $conditions[] = array($onlyBranch, $onlyOwn);

        // $this->MasterListOfFormatDepartment->recursive = 0;
        // $this->paginate = array('order' => array('MasterListOfFormatDepartment.sr_no' => 'DESC'), 'conditions' => $conditions, 'MasterListOfFormatDepartment.soft_delete' => 0);
        // if(isset($_GET['limit']) && $_GET['limit'] != 0){
        //      $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        // }
        // $this->set('masterListOfFormatDepartments', $this->paginate());

        // $this->render('index');
        $this->redirect(array('controller'=>'users', 'action' => 'dashboard'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->redirect(array('controller'=>'users', 'action' => 'dashboard'));
        // if (!$this->MasterListOfFormatDepartment->exists($id)) {
        //     throw new NotFoundException(__('Invalid master list of format department'));
        // }
        // $options = array('conditions' => array('MasterListOfFormatDepartment.' . $this->MasterListOfFormatDepartment->primaryKey => $id));
        // $this->set('masterListOfFormatDepartment', $this->MasterListOfFormatDepartment->find('first', $options));
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
        $this->redirect(array('controller'=>'users', 'action' => 'dashboard'));

        // if ($this->_show_approvals()) {
        //     $this->set(array('showApprovals' => $this->_show_approvals()));
        // }

        // if ($this->request->is('post')) {
        //     $this->request->data['MasterListOfFormatDepartment']['system_table_id'] = $this->_get_system_table_id();
        //     $this->MasterListOfFormatDepartment->create();
        //     if ($this->MasterListOfFormatDepartment->save($this->request->data)) {

        //         $this->Session->setFlash(__('The master list of format department has been saved'));

        //         if ($this->_show_approvals()) $this->_save_approvals ();

        //         if ($this->_show_evidence() == true)
        //             $this->redirect(array('action' => 'view', $this->MasterListOfFormatDepartment->id));
        //         else
        //             $this->redirect(array('action' => 'index'));
        //     } else {
        //         $this->Session->setFlash(__('The master list of format department could not be saved. Please, try again.'));
        //     }
        // }
        // $masterListOfFormats = $this->MasterListOfFormatDepartment->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        // $departments = $this->MasterListOfFormatDepartment->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        // $systemTables = $this->MasterListOfFormatDepartment->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        // $this->set(compact('masterListOfFormats', 'departments', 'systemTables'));
        // $count = $this->MasterListOfFormatDepartment->find('count');
        // $published = $this->MasterListOfFormatDepartment->find('count', array('conditions' => array('MasterListOfFormatDepartment.publish' => 1)));
        // $unpublished = $this->MasterListOfFormatDepartment->find('count', array('conditions' => array('MasterListOfFormatDepartment.publish' => 0)));

        // $this->set(compact('count', 'published', 'unpublished'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        // if (!$this->MasterListOfFormatDepartment->exists($id)) {
        //     throw new NotFoundException(__('Invalid master list of format department'));
        // }
        // if ($this->_show_approvals()) {
        //     $this->set(array('showApprovals' => $this->_show_approvals()));
        // }
        // if ($this->request->is('post') || $this->request->is('put')) {
        //     $this->request->data['MasterListOfFormatDepartment']['system_table_id'] = $this->_get_system_table_id();
        //     if ($this->MasterListOfFormatDepartment->save($this->request->data)) {

        //         $this->Session->setFlash(__('The master list of format department has been saved'));

        //         if ($this->_show_approvals()) $this->_save_approvals ();

        //         if ($this->_show_evidence() == true)
        //             $this->redirect(array('action' => 'view', $id));
        //         else
        //             $this->redirect(array('action' => 'index'));
        //     } else {
        //         $this->Session->setFlash(__('The master list of format department could not be saved. Please, try again.'));
        //     }
        // } else {
        //     $options = array('conditions' => array('MasterListOfFormatDepartment.' . $this->MasterListOfFormatDepartment->primaryKey => $id));
        //     $this->request->data = $this->MasterListOfFormatDepartment->find('first', $options);
        // }
        // $masterListOfFormats = $this->MasterListOfFormatDepartment->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        // $departments = $this->MasterListOfFormatDepartment->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        // $systemTables = $this->MasterListOfFormatDepartment->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        // $this->set(compact('masterListOfFormats', 'departments', 'systemTables'));
        // $count = $this->MasterListOfFormatDepartment->find('count');
        // $published = $this->MasterListOfFormatDepartment->find('count', array('conditions' => array('MasterListOfFormatDepartment.publish' => 1)));
        // $unpublished = $this->MasterListOfFormatDepartment->find('count', array('conditions' => array('MasterListOfFormatDepartment.publish' => 0)));

        // $this->set(compact('count', 'published', 'unpublished'));
        $this->redirect(array('controller'=>'users', 'action' => 'dashboard'));
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approval_id = null) {
        // if (!$this->MasterListOfFormatDepartment->exists($id)) {
        //     throw new NotFoundException(__('Invalid master list of format department'));
        // }

        // $this->loadModel('Approval');
        // if (!$this->Approval->exists($approval_id)) {
        //     throw new NotFoundException(__('Invalid approval id'));
        // }

        // $approval = $this->Approval->read(null, $approval_id);
        // $this->set('same', $approval['Approval']['user_id']);

        // if ($this->_show_approvals()) {
        //     $this->set(array('showApprovals' => $this->_show_approvals()));
        // }
        // if ($this->request->is('post') || $this->request->is('put')) {
        //     if ($this->MasterListOfFormatDepartment->save($this->request->data)) {

        //         $this->Session->setFlash(__('The master list of format department has been saved'));

        //         if ($this->_show_approvals()) $this->_save_approvals ();

        //     } else {
        //         $this->Session->setFlash(__('The master list of format department could not be saved. Please, try again.'));
        //     }
        // } else {
        //     $options = array('conditions' => array('MasterListOfFormatDepartment.' . $this->MasterListOfFormatDepartment->primaryKey => $id));
        //     $this->request->data = $this->MasterListOfFormatDepartment->find('first', $options);
        // }
        // $masterListOfFormats = $this->MasterListOfFormatDepartment->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        // $departments = $this->MasterListOfFormatDepartment->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        // $systemTables = $this->MasterListOfFormatDepartment->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        // $this->set(compact('masterListOfFormats', 'departments', 'systemTables'));
        // $count = $this->MasterListOfFormatDepartment->find('count');
        // $published = $this->MasterListOfFormatDepartment->find('count', array('conditions' => array('MasterListOfFormatDepartment.publish' => 1)));
        // $unpublished = $this->MasterListOfFormatDepartment->find('count', array('conditions' => array('MasterListOfFormatDepartment.publish' => 0)));

        // $this->set(compact('count', 'published', 'unpublished'));
        $this->redirect(array('controller'=>'users', 'action' => 'dashboard'));
    }

    public function listing() {
        if($this->Session->read('User.is_mr')==0){
            $access_conditions = array('MasterListOfFormat.user_id LIKE'=>'%'.$this->Session->read('User.id').'%');    
        }else{
            $access_conditions = array();
        }
		$options = array('conditions' => array(
                    $access_conditions,
                    'MasterListOfFormat.standard_id'=>$this->request->params['named']['standard_id'],
                    'MasterListOfFormat.master_list_of_format_category_id' => $this->request->params['named']['category_id'],
                    'MasterListOfFormatDepartment.department_id' => $this->request->params['pass']['0'],
                    'MasterListOfFormat.archived' => 0 ,
                    'MasterListOfFormat.publish' => 1,
					  'MasterListOfFormat.soft_delete' => 0
                    ),
                  'order'=>array('MasterListOfFormat.title'=>'asc'),
					'fields' => array('MasterListOfFormat.id', 
						'MasterListOfFormat.title', 
                        'MasterListOfFormat.master_list_of_format_category_id', 
						'MasterListOfFormat.system_table_id', 
						'MasterListOfFormat.document_number', 
						'MasterListOfFormat.issue_number', 
						'MasterListOfFormat.revision_number', 
						'MasterListOfFormat.revision_date', 
						'MasterListOfFormat.prepared_by', 
						'MasterListOfFormat.approved_by',
						'MasterListOfFormat.document_details',
						'MasterListOfFormat.work_instructions',
                        'MasterListOfFormat.document_status',
						'PreparedBy.name',
						'ApprovedBy.name',
						'MasterListOfFormatDepartment.master_list_of_format_id'
						),
                    );
		//find change requests
		$formats = $this->MasterListOfFormatDepartment->find('all', $options);
		$this->loadModel('ChangeAdditionDeletionRequest');
		$i=0;
		foreach($formats as $format):
			$req= FALSE;
			$flag = $this->ChangeAdditionDeletionRequest->find('first',
				array('conditions'=>array('ChangeAdditionDeletionRequest.master_list_of_format'=>$format['MasterListOfFormatDepartment']['master_list_of_format_id'],'ChangeAdditionDeletionRequest.document_change_accepted'=> 2)));
			if(count($flag) > 0)$req = TRUE;
			$newFormat[$i]['MasterListOfFormat'] = $format['MasterListOfFormat'];
			$newFormat[$i]['PreparedBy'] = $format['PreparedBy'];			
			$newFormat[$i]['ApprovedBy'] = $format['ApprovedBy'];						
			$newFormat[$i]['flag'] = $req;
			$newFormat[$i]['flag_id'] = $flag['ChangeAdditionDeletionRequest']['id'];
			$i++;
		endforeach;
		$this->set('masterListOfFormatDepartment', $newFormat);
		$this->set('PublishedUserList',$this->_get_user_list());
        $this->loadModel('MasterListOfFormatCategory');
        $masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list', array('conditions' => array('MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));
        $documentStatuses = $this->MasterListOfFormatDepartment->MasterListOfFormat->customArray['document_status'];
        $this->set(compact('masterListOfFormatCategories','documentStatuses'));
        
	
	}

}

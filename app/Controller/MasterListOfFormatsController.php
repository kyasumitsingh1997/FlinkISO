<?php

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('CakePdf', 'CakePdf.Pdf');
/**
 * MasterListOfFormats Controller
 *
 * @property MasterListOfFormat $MasterListOfFormat
 */
class MasterListOfFormatsController extends AppController {

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
        if($this->Session->read('User.is_mr')==0){
            $access_conditions = array('MasterListOfFormat.user_id LIKE'=>'%'.$this->Session->read('User.id').'%');    
        }else{
            $access_conditions = array();
        }
        
        $conditions = $this->_check_request();
        $this->paginate = array('order' => array('MasterListOfFormat.sr_no' => 'DESC'),
            'conditions' => array($conditions,$access_conditions,array('MasterListOfFormat.archived'=>0)));

        $this->MasterListOfFormat->recursive = 0;

        $master_list_of_formats = $this->paginate();
        foreach ($master_list_of_formats as $key => $master_list_of_format) {
            $masterListOfFormatBranches = array();
            $masterListOfFormatBranches = $this->MasterListOfFormat->MasterListOfFormatBranch->find('all', array('conditions' => array('MasterListOfFormatBranch.master_list_of_format_id' => $master_list_of_format['MasterListOfFormat']['id'], 'MasterListOfFormatBranch.soft_delete' => 0, 'MasterListOfFormatBranch.publish' => 1), 'fields' => 'Branch.name', 'order' => array('Branch.name' => 'DESC')));

            $branches = array();
            foreach ($masterListOfFormatBranches as $masterListOfFormatBranch)
                if ($masterListOfFormatBranch['Branch']['name'])
                    $branches[] = $masterListOfFormatBranch['Branch']['name'];
            $master_list_of_formats[$key]['MasterListOfFormat']['Branches'] = implode(', ', $branches);

            $depts = array();
            $masterListOfFormatDepartments = $this->MasterListOfFormat->MasterListOfFormatDepartment->find('all', array('conditions' => array('MasterListOfFormatDepartment.master_list_of_format_id' => $master_list_of_format['MasterListOfFormat']['id'], 'MasterListOfFormatDepartment.soft_delete' => 0, 'MasterListOfFormatDepartment.publish' => 1), 'fields' => 'Department.name',
                'order' => array('Department.name' => 'DESC')));
            foreach ($masterListOfFormatDepartments as $masterListOfFormatDepartment)
                if ($masterListOfFormatDepartment['Department']['name'])
                    $depts[] = $masterListOfFormatDepartment['Department']['name'];
            $master_list_of_formats[$key]['MasterListOfFormat']['Departments'] = implode(', ', $depts);
        }
        $this->set('masterListOfFormats', $master_list_of_formats);

        $this->_get_count();
        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set('documentStatuses',$documentStatuses);
    }

    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function _advanced_search() {
        $this->_get_count();

        if($this->Session->read('User.is_mr')==0){
            $access_conditions = array('MasterListOfFormat.user_id LIKE'=>'%'.$this->Session->read('User.id').'%');    
        }else{
            $access_conditions = array();
        }
        
        $conditions = $this->_check_request();
       
        // $conditions = array();
        if ($this->request->query['keywords']) {
            $search_array = array();
            if ($this->request->query['strict_search'] == 0) {
                $search_keys[] = $this->request->query['keywords'];
            } else {
                $search_keys = explode(" ", $this->request->query['keywords']);
            }

            foreach ($search_keys as $search_key):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $search_array[] = array('MasterListOfFormat.' . $search => $search_key);
                    else
                        $search_array[] = array('MasterListOfFormat.' . $search . ' like ' => '%' . $search_key . '%');
                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $search_array));
            else
                $conditions[] = array('or' => $search_array);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branch_conditions[] = array('MasterListOfFormat.branchid' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branch_conditions));
            else
                $conditions[] = array('or' => $branch_conditions);
        }

        if ($this->request->query['department_id'] != -1) {
            $department_conditions[] = array('MasterListOfFormat.departmentid' => $this->request->query['department_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $department_conditions);
            else
                $conditions[] = array('or' => $department_conditions);
        }

        if ($this->request->query['archived'] != '') {
            $archived_conditions[] = array('MasterListOfFormat.archived' => $this->request->query['archived']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $archived_conditions);
            else
                $conditions[] = array('or' => $archived_conditions);
        }

        if ($this->request->query['system_id'] != -1) {
            $system_conditions[] = array('MasterListOfFormat.system_table_id' => $this->request->query['system_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $system_conditions);
            else
                $conditions[] = array('or' => $system_conditions);
        }

        if ($this->request->query['prepared_by'] != -1) {
            $preparedby_conditions[] = array('MasterListOfFormat.prepared_by' => $this->request->query['prepared_by']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $preparedby_conditions);
            else
                $conditions[] = array('or' => $preparedby_conditions);
        }

        if ($this->request->query['approved_by'] != -1) {
            $approver_conditions[] = array('MasterListOfFormat.approved_by' => $this->request->query['approved_by']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $approver_conditions);
            else
                $conditions[] = array('or' => $approver_conditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('MasterListOfFormat.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'MasterListOfFormat.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);


        if ($this->Session->read('User.is_mr') == 0)
            $onlyBranch = array('MasterListOfFormat.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('MasterListOfFormat.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->MasterListOfFormat->recursive = 0;
        $this->paginate = array('order' => array('MasterListOfFormat.sr_no' => 'DESC'), 'conditions' => $conditions, 'MasterListOfFormat.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }

        // $this->MasterListOfFormat->recursive = 0;

        $master_list_of_formats = $this->paginate();
        foreach ($master_list_of_formats as $key => $master_list_of_format) {
            $masterListOfFormatBranches = array();
            $masterListOfFormatBranches = $this->MasterListOfFormat->MasterListOfFormatBranch->find('all', array('conditions' => array('MasterListOfFormatBranch.master_list_of_format_id' => $master_list_of_format['MasterListOfFormat']['id'], 'MasterListOfFormatBranch.soft_delete' => 0, 'MasterListOfFormatBranch.publish' => 1), 'fields' => 'Branch.name', 'order' => array('Branch.name' => 'DESC')));

            $branches = array();
            foreach ($masterListOfFormatBranches as $masterListOfFormatBranch)
                if ($masterListOfFormatBranch['Branch']['name'])
                    $branches[] = $masterListOfFormatBranch['Branch']['name'];
            $master_list_of_formats[$key]['MasterListOfFormat']['Branches'] = implode(', ', $branches);

            $depts = array();
            $masterListOfFormatDepartments = $this->MasterListOfFormat->MasterListOfFormatDepartment->find('all', array('conditions' => array('MasterListOfFormatDepartment.master_list_of_format_id' => $master_list_of_format['MasterListOfFormat']['id'], 'MasterListOfFormatDepartment.soft_delete' => 0, 'MasterListOfFormatDepartment.publish' => 1), 'fields' => 'Department.name',
                'order' => array('Department.name' => 'DESC')));
            foreach ($masterListOfFormatDepartments as $masterListOfFormatDepartment)
                if ($masterListOfFormatDepartment['Department']['name'])
                    $depts[] = $masterListOfFormatDepartment['Department']['name'];
            $master_list_of_formats[$key]['MasterListOfFormat']['Departments'] = implode(', ', $depts);
        }
        $this->set('masterListOfFormats', $master_list_of_formats);

        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set('documentStatuses',$documentStatuses);
        
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
        if (!$this->MasterListOfFormat->exists($id)) {
            throw new NotFoundException(__('Invalid master list of format'));
        }
        if($this->Session->read('User.is_mr')==0){
            $access_conditions = array('MasterListOfFormat.user_id LIKE'=>'%'.$this->Session->read('User.id').'%');    
        }else{
            $access_conditions = array();
        }
        $options = array('conditions' => array($access_conditions,'MasterListOfFormat.' . $this->MasterListOfFormat->primaryKey => $id));
        
        $masterListOfFormat = $this->MasterListOfFormat->find('first', $options);
        if(!$masterListOfFormat){
            throw new NotFoundException(__('You do not have permission to view this'));
        }
        $this->set('masterListOfFormat', $masterListOfFormat);
        $this->document_revisions($id);  
        if($masterListOfFormat['MasterListOfFormat']['archived']==1){
            $header = Router::url('/', true) .'files/pdf_header_archived.html';
        }else{
            $header = Router::url('/', true) .'files/pdf_header.html';
        }
        $this->pdfConfig = array(
            'options' => array(
                  'header-html' => $header,
                  'footer-center'     => 'Page [page] of [toPage]',
                  'footer-right'     => 'Confidential Document. All rights reserved.',
                  'footer-font-size'     => '6',
                  'footer-line'=>true,
                  'header-line'=>true,
                  'header-font-name'=>'Trebuchet MS',
                  'footer-font-name'=>'Trebuchet MS',
              ),
                    'protect'=> true,
                    'ownerPassword'=>'123',                    
                    'filename'=> $masterListOfFormat['MasterListOfFormat']['document_number'] .'-Issue-'. $masterListOfFormat['MasterListOfFormat']['issue_number'] .'-Rev-'. $masterListOfFormat['MasterListOfFormat']['revision_number'] .'-'. $masterListOfFormat['MasterListOfFormat']['title'].'.pdf'
            ); 

        $issues = $this->MasterListOfFormat->find('all',array(
            'recursive'=>1,
            'order'=>array('MasterListOfFormat.created'=>'ASC'),
            'conditions'=>array('OR'=>array('MasterListOfFormat.parent_id'=>$id,'MasterListOfFormat.id'=>$id)),
            'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.parent_id','MasterListOfFormat.title','MasterListOfFormat.issue_number', 'MasterListOfFormat.revision_number', 'MasterListOfFormat.document_number', 'MasterListOfFormat.created',
                'CreatedBy.id', 'CreatedBy.name', 'PreparedBy.id','PreparedBy.name','ApprovedBy.id','ApprovedBy.name'
                ),
            ));
        $this->set('issues',$issues);

        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set('documentStatuses',$documentStatuses);

        if($masterListOfFormat['MasterListOfFormat']['document_status'] == 0){
            $last_version = $this->MasterListOfFormat->find('first',array(
                    'conditions'=>array('MasterListOfFormat.parent_id'=>$id),
                    'order'=>array('MasterListOfFormat.created'=>'ASC'),
                    'recursive'=>-1,
                    'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.created', 'MasterListOfFormat.revision_number','MasterListOfFormat.document_status','MasterListOfFormat.publish')
                ));
            $this->set('last_version',$last_version);
            $this->set('document_history',$this->_get_document_history($id));
        }
        
        
        if($masterListOfFormat['MasterListOfFormat']['parent_id'] != null){
            $draft_version = $this->MasterListOfFormat->find('first',array(
                    'conditions'=>array('MasterListOfFormat.parent_id'=>$masterListOfFormat['MasterListOfFormat']['parent_id']),
                    'order'=>array('MasterListOfFormat.created'=>'ASC'),
                    'recursive'=>-1,
                    'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.created', 'MasterListOfFormat.revision_number','MasterListOfFormat.document_status','MasterListOfFormat.publish')
                ));
            $this->set('draft_version',$draft_version);

            $issues = $this->MasterListOfFormat->find('all',array(
            'recursive'=>1,
            'order'=>array('MasterListOfFormat.created'=>'ASC'),
            'conditions'=>array('OR'=>array('MasterListOfFormat.id'=>$masterListOfFormat['MasterListOfFormat']['parent_id'],'MasterListOfFormat.parent_id'=>$masterListOfFormat['MasterListOfFormat']['parent_id'])),
            'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.parent_id','MasterListOfFormat.title','MasterListOfFormat.issue_number', 'MasterListOfFormat.revision_number','MasterListOfFormat.document_number', 'MasterListOfFormat.created',
                'CreatedBy.id', 'CreatedBy.name', 'PreparedBy.id','PreparedBy.name','ApprovedBy.id','ApprovedBy.name'
                ),
            ));
        $this->set('issues',$issues);
    }
        $systemTables = $this->MasterListOfFormat->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormatCategories = $this->MasterListOfFormat->MasterListOfFormatCategory->find('list', array('conditions' => array('MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));
        $standards = $this->MasterListOfFormat->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));
        $this->set(compact('systemTables','masterListOfFormatCategories','standards'));

        $count = $this->MasterListOfFormat->find('count');
        $published = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 1)));
        $unpublished = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 0)));
        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set(compact('count', 'published', 'unpublished','documentStatuses'));
        
        // get parent & linked document details
        $parent_document = $this->MasterListOfFormat->find('first',array('conditions'=>array('MasterListOfFormat.id'=>$masterListOfFormat['MasterListOfFormat']['parent_id']),'recursive'=>0));
        if($parent_document){
            $this->set('parent_document',$parent_document);
        }
        
        $linked = json_decode($masterListOfFormat['MasterListOfFormat']['linked_formats'],true);
        $linked_documents = $this->MasterListOfFormat->find('all',array('conditions'=>array('MasterListOfFormat.id'=>$linked),'recursive'=>0));
        if($linked_documents){
            $this->set('linked_documents',$linked_documents);
        }

        
        
}

    public function generate_pdf($id = null){        

        if (!$this->MasterListOfFormat->exists($id)) {
            throw new NotFoundException(__('Invalid master list of format'));
        }
        $options = array('conditions' => array('MasterListOfFormat.' . $this->MasterListOfFormat->primaryKey => $id));
        $masterListOfFormat = $this->MasterListOfFormat->find('first', $options);
        $this->set('masterListOfFormat', $masterListOfFormat);
        $this->document_revisions($id);
        
        $masterListOfFormat['MasterListOfFormat']['document_details'] = str_replace(Router::url("/", true), WWW_ROOT, $masterListOfFormat['MasterListOfFormat']['document_details']);
        $masterListOfFormat['MasterListOfFormat']['work_instructions'] = str_replace(Router::url("/", true), WWW_ROOT, $masterListOfFormat['work_instructions']['document_details']);

        $CakePdf = new CakePdf();
        $CakePdf->template('quality', 'layout_quality');
        $CakePdf->viewVars(array('masterListOfFormat' => $masterListOfFormat,'revisions'=>$revisions));
        $pdf = $CakePdf->output();
        
        $path = Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS . 'quality_docs' . DS . $this->Session->read('User.id');
        try{
            $dir = Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS . 'quality_docs';
            if(!file_exists($dir)){
                mkdir($dir);    
            }

            if(!file_exists($path)){
                mkdir($path);
            }
            chmod($dir,0777);
            chmod($path,0777);
        }catch(Exception $e){            
        }
        $filename = preg_replace('/\s+/', '',$masterListOfFormat['MasterListOfFormat']['title']);
        $filename = str_replace('&', '', $filename);
        $secure_filename = 'secure_'.$filename;
        $pdf = $CakePdf->write($path . DS . $filename.'.pdf');
        $pdf = $path . DS . $filename.'.pdf';
        $pdf_secure = $path . DS . $secure_filename.'.pdf';
        // Local
        // exec('/usr/local/bin/pdftk '. $pdf . ' output ' .$pdf_secure .' owner_pw 123');
        // Server
        exec('/usr/bin/pdftk '. $pdf . ' output ' .$pdf_secure .' owner_pw 123');
        
        // unlink($pdf);
        $this->set('link',$pdf_secure);
    }

    /**
     * ajax_view method // only for change addition deletion request.
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function ajax_view($id = null,$show = null,$cr = null, $referCheck = null) {
        if (!$this->MasterListOfFormat->exists($id)) {
            throw new NotFoundException(__('Invalid master list of format'));
        }
        $options = array('conditions' => array('MasterListOfFormat.' . $this->MasterListOfFormat->primaryKey => $id));
        $masterListOfFormat = $this->MasterListOfFormat->find('first', $options);
        $this->set('masterListOfFormat',$masterListOfFormat);
        $this->document_revisions($id);
        if($show == 1)$this->set("show_details",true);
        if($cr){
            $crs = $this->MasterListOfFormat->ChangeAdditionDeletionRequest->read(null,$cr);
            $this->set(compact('referCheck', 'crs'));
        }
    }

    /**
     * Gettting list of revisions
     *
     */

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

            $this->MasterListOfFormat->create();
            $this->request->data['MasterListOfFormat']['created_by'] = $this->Session->read('User.id');
            $this->request->data['MasterListOfFormat']['modified_by'] = $this->Session->read('User.id');
            $this->request->data['MasterListOfFormat']['document_status'] = 0;

            foreach ($this->request->data['MasterListOfFormat']['user_id'] as $key => $value) {
                foreach ($value['user_id'] as $everyone => $users) {
                    $user_ids[] = $users;
                }
            }
            $this->request->data['MasterListOfFormat']['user_id'] = json_encode($user_ids);
            
            $this->request->data['MasterListOfFormat']['linked_formats'] = json_encode($this->request->data['MasterListOfFormat']['user_id']);
            
            if ($this->MasterListOfFormat->save($this->request->data)) {
                $find_imgs = explode('<img', $this->request->data['MasterListOfFormat']['document_details']);
                foreach ($find_imgs as $find_img) {
                        $img = ltrim(rtrim(str_replace(Router::url("/", true), WWW_ROOT, $this->_get_between($find_img, 'alt=""', '/>'))));                        
                        $path = $this->_get_between($img, 'src="','"');
                        $path = str_replace('//', '/', $path);
                        $file = new File($path);
                        if($file->exists()){
                            $move_to = WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'master_list_of_formats' . DS . $this->request->data['MasterListOfFormat']['standard_id'] . DS . $this->MasterListOfFormat->id . DS . $this->request->data['MasterListOfFormat']['issue_number'] . DS . $this->request->data['MasterListOfFormat']['revision_number'];
                            mkdir($move_to,0775,true);
                            $new_file = $move_to . DS .  $file->name;
                            try{
                                copy($file->path,$new_file);
                                $url = FULL_BASE_URL.$this->request->base;
                                $url = $url . '/img/ckeditor/master_list_of_formats/' . $this->Session->read('User.id');
                                $new_destination_url = FULL_BASE_URL.$this->request->base . DS . 'app' . DS . 'webroot'. DS .'img' . DS . $this->Session->read('User.company_id') . DS . 'master_list_of_formats' . DS . $this->request->data['MasterListOfFormat']['standard_id'] . DS . $this->MasterListOfFormat->id . DS . $this->request->data['MasterListOfFormat']['issue_number'] . DS . $this->request->data['MasterListOfFormat']['revision_number'];
                                $newDetails = str_replace($url,$new_destination_url, $this->request->data['MasterListOfFormat']['document_details']);
                                $data['MasterListOfFormat']['document_details'] = $newDetails;
                                
                                $this->MasterListOfFormat->read(null,$this->MasterListOfFormat->id);
                                $this->MasterListOfFormat->set('document_details',$newDetails);
                                $this->MasterListOfFormat->save();
                            }catch(Exception $e){
                                
                            }                        
                        }
                        
                    }    
                


                $this->_add_branches_and_departments(
                    $this->request->data['MasterListOfFormatBranch_branch_id'],
                    $this->request->data['MasterListOfFormatDepartment_department_id'],
                    $this->request->data['MasterListOfFormat']['system_table_id'],
                    $this->MasterListOfFormat->id,
                    1,
                    $this->request->data['MasterListOfFormat']['prepared_by'],
                    $this->request->data['MasterListOfFormat']['approved_by']
                    );

                if($this->request->data['MasterListOfFormat']['publish'] == 1)$this->_send_email($this->MasterListOfFormat->id);
                $this->Session->setFlash(__('The master list of format has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->MasterListOfFormat->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The master list of format could not be saved. Please, try again.'));
            }
        }
        
        $systemTables = $this->MasterListOfFormat->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormatCategories = $this->MasterListOfFormat->MasterListOfFormatCategory->find('list', array('conditions' => array('MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));
        $standards = $this->MasterListOfFormat->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));
        $parentDocuments = $this->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $parents = $this->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('systemTables','masterListOfFormatCategories','standards','parentDocuments','parents'));

        $count = $this->MasterListOfFormat->find('count');
        $published = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 1)));
        $unpublished = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 0)));
        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set(compact('count', 'published', 'unpublished','documentStatuses'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */

    public function edit($id = null) {

        // $this->Session->setFlash(__('You can not edit these records with out reaising a change request'));
        // $this->redirect(array('controller' => 'dashboards', 'action' => 'mr')); 

        if (!$this->MasterListOfFormat->exists($id)) {
            throw new NotFoundException(__('Invalid master list of format'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            foreach ($this->request->data['MasterListOfFormat']['user_id'] as $key => $value) {
                foreach ($value['user_id'] as $everyone => $users) {
                    $user_ids[] = $users;
                }
            }
            $this->request->data['MasterListOfFormat']['user_id'] = json_encode($user_ids);
            $this->request->data['MasterListOfFormat']['linked_formats'] = json_encode($this->request->data['MasterListOfFormat']['linked_formats']);

            $this->MasterListOfFormat->read(null, array($this->MasterListOfFormat->primaryKey => $id));

            

            $find_imgs = explode('<img', $this->request->data['MasterListOfFormat']['document_details']);
            foreach ($find_imgs as $find_img) {
                $img = ltrim(rtrim(str_replace(Router::url("/", true), WWW_ROOT, $this->_get_between($find_img, 'alt=""', '/>'))));
                
                $path = $this->_get_between($img, 'src="','"');
                $path = str_replace('//', '/', $path);
                $file = new File($path);
                
                if($file->exists()){
                    $style = $this->_get_between($img, 'style="','"');
                    $thumbnail_height = $this->_get_between($style,'height:','px');
                    $thumbnail_width = $this->_get_between($style,'width:','px');
                      

                    if($file->ext()=="png"){

                        list($width_orig, $height_orig) = getimagesize($path);   
                        if($width_orig != $thumbnail_width && $height_orig != $thumbnail_height){
                            $x_mid = $thumbnail_width/2;  //horizontal middle
                            $y_mid = $thumbnail_height/2; //vertical middle    

                            $myImage = imagecreatefrompng($path);
                            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);                         
                            $process = imagecreatetruecolor(round($thumbnail_width), round($thumbnail_height)); 
                            imagecopyresampled($process, $myImage, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width_orig, $height_orig);
                            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
                            imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

                            
                            
                            imagedestroy($process);
                            imagedestroy($myImage);
                            imagejpeg($thumb, $path, 100);    
                        }
                        
                    }
                    if($file->ext()=="jpg" || $file->ext()=="jpeg"){

                        list($width_orig, $height_orig) = getimagesize($path);   
                        if($width_orig != $thumbnail_width && $height_orig != $thumbnail_height){
                            $x_mid = $thumbnail_width/2;  //horizontal middle
                            $y_mid = $thumbnail_height/2; //vertical middle    

                            $myImage = imagecreatefromjpeg($path);
                            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);                         
                            $process = imagecreatetruecolor(round($thumbnail_width), round($thumbnail_height)); 
                            imagecopyresampled($process, $myImage, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width_orig, $height_orig);
                            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
                            imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

                            
                            
                            imagedestroy($process);
                            imagedestroy($myImage);
                            imagejpeg($thumb, $path, 100);    
                        }
                        
                    }
                }
                

                
            }  
                $this->_img_move($this->request);
                $newdata = $this->request->data['MasterListOfFormat'];    
                $this->_check_update($id);
                
                if ($this->MasterListOfFormat->save($newdata,false)) {
                // add branches
                $this->MasterListOfFormat->MasterListOfFormatBranch->deleteAll(array('MasterListOfFormatBranch.master_list_of_format_id' => $id));
                $this->MasterListOfFormat->MasterListOfFormatDepartment->deleteAll(array('MasterListOfFormatDepartment.master_list_of_format_id' => $id));

                $this->_add_branches_and_departments(
                    $this->request->data['MasterListOfFormatBranch_branch_id'],
                    $this->request->data['MasterListOfFormatDepartment_department_id'],
                    $this->request->data['MasterListOfFormat']['system_table_id'],
                    $this->MasterListOfFormat->id,
                    1,
                    $this->request->data['MasterListOfFormat']['prepared_by'],
                    $this->request->data['MasterListOfFormat']['approved_by'] );

                $this->Session->setFlash(__('The master list of format has been saved'));

                $revision_number = $this->request->data['MasterListOfFormat']['revision_number'] + 1;
                $revision_date = date('Y-m-d H:i:s');
                
                if($this->request->data['MasterListOfFormat']['save_copy'] == true){
                    $this->_check_revision($id,$revision_number,$revision_date);
                }else{
                    $this->_check_revision($id,$revision_number,$revision_date);
                }
            

                if ($this->request->data['MasterListOfFormat']['evidence_required'] == true) {
                    $sys_data['SystemTable']['evidence_required'] = 1;
                } else {
                    $sys_data['SystemTable']['evidence_required'] = 0;
                }
                if ($this->request->data['MasterListOfFormat']['approvals_required'] == true) {
                    $sys_data['SystemTable']['approvals_required'] = 1;
                } else {
                    $sys_data['SystemTable']['approvals_required'] = 0;
                }
                
                if($this->request->data['MasterListOfFormat']['system_table_id'] && $this->request->data['MasterListOfFormat']['system_table_id'] != -1){
                    $sys_data['SystemTable']['master_list_of_format_id'] = $id;
                    $this->loadModel('SystemTable');
                    $this->SystemTable->read(null, $this->request->data['MasterListOfFormat']['system_table_id']);
                    $this->SystemTable->set($sys_data['SystemTable']);
                    $this->SystemTable->save();    
                }
                
                if($this->request->data['MasterListOfFormat']['publish'] == 1 && $this->request->data['MasterListOfFormat']['document_status'] == 1)$this->_send_email($this->request->data['MasterListOfFormat']['id']);

                if ($this->_show_approvals()) $this->_save_approvals();
                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'view',$id));
            } else {
                $this->Session->setFlash(__('The master list of format could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('MasterListOfFormat.' . $this->MasterListOfFormat->primaryKey => $id));
            $this->request->data = $this->MasterListOfFormat->find('first', $options);
            if($this->request->data['MasterListOfFormat']['archived'] == 1){
                $this->Session->setFlash(__('You can not edit archived document'));
                $this->redirect(array('action' => 'view',$id));
            }
        }
        
        $systemTables = $this->MasterListOfFormat->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormatCategories = $this->MasterListOfFormat->MasterListOfFormatCategory->find('list', array('conditions' => array('MasterListOfFormatCategory.standard_id'=>$this->request->data['MasterListOfFormat']['standard_id'], 'MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));
        $clauses = $this->MasterListOfFormat->Clause->find('list', array('conditions' => array('Clause.standard_id'=>$this->request->data['MasterListOfFormat']['standard_id'], 'Clause.publish' => 1, 'Clause.soft_delete' => 0)));
        $standards = $this->MasterListOfFormat->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));
        $parents = $this->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.id <>'=>$id, 'MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('systemTables','masterListOfFormatCategories','standards','clauses','parents'));
        
        $count = $this->MasterListOfFormat->find('count');
        $published = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 1)));
        $unpublished = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 0)));

        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set(compact('count', 'published', 'unpublished','documentStatuses'));

        $bbranches = $this->MasterListOfFormat->MasterListOfFormatBranch->find('all', array('conditions' => array('MasterListOfFormatBranch.master_list_of_format_id' => $id)));
        
        $selected_branches = array();
        foreach ($bbranches as $bb):
            $selected_branches[] = $bb['Branch']['id'];
            $branch_list[$bb['Branch']['id']] = $bb['Branch']['name'];
        endforeach;
        $this->set('selected_branches', $selected_branches);

        $selected_depts = $this->MasterListOfFormat->MasterListOfFormatDepartment->find('all', array('conditions' => array('MasterListOfFormatDepartment.master_list_of_format_id' => $id)));
        $selected_departments = array();
        foreach ($selected_depts as $dd):
            $selected_departments[] = $dd['Department']['id'];
        endforeach;
        $this->set('selected_depts', $selected_departments);

        $this->loadModel('User');
        
        
        if($branch_list != null){
            foreach ($branch_list as $id=>$name) {                
               $users = $this->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.branch_id'=>$id,'User.department_id'=>$selected_departments)));
               $branches[$id] = array('Name'=>$name,'Users'=>$users);
            }        
        }else{
            $branches = null;
        }
        
        
        $preparedBies = $this->MasterListOfFormat->PreparedBy->find('list');
        $approvedBies = $this->MasterListOfFormat->ApprovedBy->find('list');
        $this->set(compact('preparedBies', 'approvedBies','branches'));
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approval_id = null) {
        if (!$this->MasterListOfFormat->exists($id)) {
            throw new NotFoundException(__('Invalid master list of format'));
        }

        $this->loadModel('Approval');
        if (!$this->Approval->exists($approval_id)) {
            throw new NotFoundException(__('Invalid approval id'));
        }

        $approval = $this->Approval->read(null, $approval_id);
        $this->set('same', $approval['Approval']['user_id']);

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            
            foreach ($this->request->data['MasterListOfFormat']['user_id'] as $key => $value) {
                foreach ($value['user_id'] as $everyone => $users) {
                    $user_ids[] = $users;
                }
            }
            $this->request->data['MasterListOfFormat']['user_id'] = json_encode($user_ids);
            $this->request->data['MasterListOfFormat']['linked_formats'] = json_encode($this->request->data['MasterListOfFormat']['user_id']);

            $this->MasterListOfFormat->read(null, array($this->MasterListOfFormat->primaryKey => $id));

            $find_imgs = explode('<img', $this->request->data['MasterListOfFormat']['document_details']);
            foreach ($find_imgs as $find_img) {
                $img = ltrim(rtrim(str_replace(Router::url("/", true), WWW_ROOT, $this->_get_between($find_img, 'alt=""', '/>'))));
                
                $path = $this->_get_between($img, 'src="','"');
                $path = str_replace('//', '/', $path);
                $file = new File($path);
                
                if($file->exists()){
                    $style = $this->_get_between($img, 'style="','"');
                    $thumbnail_height = $this->_get_between($style,'height:','px');
                    $thumbnail_width = $this->_get_between($style,'width:','px');
                
                    if($file->ext()=="png"){

                        list($width_orig, $height_orig) = getimagesize($path);   
                        if($width_orig != $thumbnail_width && $height_orig != $thumbnail_height){
                            $x_mid = $thumbnail_width/2;  //horizontal middle
                            $y_mid = $thumbnail_height/2; //vertical middle    

                            $myImage = imagecreatefrompng($path);
                            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);                         
                            $process = imagecreatetruecolor(round($thumbnail_width), round($thumbnail_height)); 
                            imagecopyresampled($process, $myImage, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width_orig, $height_orig);
                            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
                            imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

                            
                            
                            imagedestroy($process);
                            imagedestroy($myImage);
                            imagejpeg($thumb, $path, 100);    
                        }
                        
                    }
                    if($file->ext()=="jpg" || $file->ext()=="jpeg"){

                        list($width_orig, $height_orig) = getimagesize($path);   
                        if($width_orig != $thumbnail_width && $height_orig != $thumbnail_height){
                            $x_mid = $thumbnail_width/2;  //horizontal middle
                            $y_mid = $thumbnail_height/2; //vertical middle    

                            $myImage = imagecreatefromjpeg($path);
                            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);                         
                            $process = imagecreatetruecolor(round($thumbnail_width), round($thumbnail_height)); 
                            imagecopyresampled($process, $myImage, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width_orig, $height_orig);
                            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
                            imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

                            
                            
                            imagedestroy($process);
                            imagedestroy($myImage);
                            imagejpeg($thumb, $path, 100);    
                        }
                        
                    }
                }
                

                
            }   
            
            
            if ($this->request->data['MasterListOfFormat']['evidence_required'] == true) {
                    $sys_data['SystemTable']['evidence_required'] = 1;
                } else {
                    $sys_data['SystemTable']['evidence_required'] = 0;
                }
                if ($this->request->data['MasterListOfFormat']['approvals_required'] == true) {
                    $sys_data['SystemTable']['approvals_required'] = 1;
                } else {
                    $sys_data['SystemTable']['approvals_required'] = 0;
                }
                if($this->request->data['MasterListOfFormat']['system_table_id'] != -1){
                    $sys_data['SystemTable']['master_list_of_format_id'] = $id;
                    $this->loadModel('SystemTable');
                    $this->SystemTable->read(null, $this->request->data['MasterListOfFormat']['system_table_id']);
                    $this->SystemTable->set($sys_data['SystemTable']);
                    $this->SystemTable->save();    
            }
            
                $this->_img_move($this->request);
                $newdata = $this->request->data['MasterListOfFormat'];    
                $this->_check_update($id);
                 
                if ($this->MasterListOfFormat->save($newdata,false)) {
                // add branches
                $this->MasterListOfFormat->MasterListOfFormatBranch->deleteAll(array('MasterListOfFormatBranch.master_list_of_format_id' => $id));
                $this->MasterListOfFormat->MasterListOfFormatDepartment->deleteAll(array('MasterListOfFormatDepartment.master_list_of_format_id' => $id));

                $this->_add_branches_and_departments(
                    $this->request->data['MasterListOfFormatBranch_branch_id'],
                    $this->request->data['MasterListOfFormatDepartment_department_id'],
                    $this->request->data['MasterListOfFormat']['system_table_id'],
                    $this->MasterListOfFormat->id,
                    1,
                    $this->request->data['MasterListOfFormat']['prepared_by'],
                    $this->request->data['MasterListOfFormat']['approved_by'] );

                $this->Session->setFlash(__('The master list of format has been saved'));

                $revision_number = $this->request->data['MasterListOfFormat']['revision_number'] + 1;
                $revision_date = date('Y-m-d H:i:s');
                

                
                if($this->request->data['MasterListOfFormat']['save_copy'] == true){
                    $this->_check_revision($id,$revision_number,$revision_date);
                }else{
                    $this->_check_revision($id,$revision_number,$revision_date);
                }
                if($this->request->data['MasterListOfFormat']['publish'] == 1 && $this->request->data['MasterListOfFormat']['document_status'] == 1)$this->_send_email($this->request->data['MasterListOfFormat']['id']);
                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'view',$id));
            } else {
                $this->Session->setFlash(__('The master list of format could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('MasterListOfFormat.' . $this->MasterListOfFormat->primaryKey => $id));
            $this->request->data = $this->MasterListOfFormat->find('first', $options);
            if($this->request->data['MasterListOfFormat']['archived'] == 1){
                $this->Session->setFlash(__('You can not edit archived document'));
                $this->redirect(array('action' => 'view',$id));
            }
        }
        
        $systemTables = $this->MasterListOfFormat->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormatCategories = $this->MasterListOfFormat->MasterListOfFormatCategory->find('list', array('conditions' => array('MasterListOfFormatCategory.standard_id'=>$this->request->data['MasterListOfFormat']['standard_id'], 'MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));
        $clauses = $this->MasterListOfFormat->Clause->find('list', array('conditions' => array('Clause.standard_id'=>$this->request->data['MasterListOfFormat']['standard_id'], 'Clause.publish' => 1, 'Clause.soft_delete' => 0)));
        $standards = $this->MasterListOfFormat->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));
        $parentDocuments = $this->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('systemTables','masterListOfFormatCategories','standards','clauses','parentDocuments'));

        $count = $this->MasterListOfFormat->find('count');
        $published = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 1)));
        $unpublished = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 0)));

        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set(compact('count', 'published', 'unpublished','documentStatuses'));

        $bbranches = $this->MasterListOfFormat->MasterListOfFormatBranch->find('all', array('conditions' => array('MasterListOfFormatBranch.master_list_of_format_id' => $id)));
        
        $selected_branches = array();
        foreach ($bbranches as $bb):
            $selected_branches[] = $bb['Branch']['id'];
            $branch_list[$bb['Branch']['id']] = $bb['Branch']['name'];
        endforeach;
        $this->set('selected_branches', $selected_branches);

        $selected_depts = $this->MasterListOfFormat->MasterListOfFormatDepartment->find('all', array('conditions' => array('MasterListOfFormatDepartment.master_list_of_format_id' => $id)));
        $selected_departments = array();
        foreach ($selected_depts as $dd):
            $selected_departments[] = $dd['Department']['id'];
        endforeach;
        $this->set('selected_depts', $selected_departments);

        $this->loadModel('User');
        // $users = $this->User->find('list',array('conditions'=>array('User.branch_id'=>$selected_branches,'User.department_id'=>$selected_departments)));
        // foreach ($users as $key => $value) {
        //     foreach ($selected_branches as $key => $value) {
        //         $branches[$key]
        //     }
        // }
        
        
        if($branch_list != null){
            foreach ($branch_list as $id=>$name) {                
               $users = $this->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.branch_id'=>$id,'User.department_id'=>$selected_departments)));
               $branches[$id] = array('Name'=>$name,'Users'=>$users);
            }        
        }else{
            $branches = null;
        }
        
        
        $preparedBies = $this->MasterListOfFormat->PreparedBy->find('list');
        $approvedBies = $this->MasterListOfFormat->ApprovedBy->find('list');
        $this->set(compact('preparedBies', 'approvedBies','branches'));
    }


    /**
     * add_new_ajax method
     *
     * @return void
     */
    public function add_new_ajax() {

        if ($this->_show_approvals()) {
            $this->loadModel('User');
            $this->User->recursive = 0;
            $userids = $this->User->find('list', array('order' => array('User.name' => 'ASC'), 'conditions' => array('User.publish' => 1, 'User.soft_delete' => 0, 'User.is_approvar' => 1)));
            $this->set(array('userids' => $userids, 'showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post')) {

            $this->MasterListOfFormat->create();
            $this->request->data['MasterListOfFormat']['created_by'] = $this->Session->read('User.id');
            $this->request->data['MasterListOfFormat']['modified_by'] = $this->Session->read('User.id');
            //$this->request->data['MasterListOfFormat']['system_table_id'] = $this-> _get_system_table_id(); // Please do not remove this line
            if ($this->MasterListOfFormat->save($this->request->data)) {
                $this->_add_branches_and_departments(
                    $this->request->data['MasterListOfFormatBranch_branch_id'],
                    $this->request->data['MasterListOfFormatDepartment_department_id'],
                    $this->request->data['MasterListOfFormat']['system_table_id'],
                    $this->MasterListOfFormat->id,
                    1,
                    $this->request->data['MasterListOfFormat']['prepared_by'],
                    $this->request->data['MasterListOfFormat']['approved_by']
                    );

                $this->Session->setFlash(__('The master list of format has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->MasterListOfFormat->id));
                else
                    $this->redirect(array('controller' => 'master_list_of_formats', 'action' => 'add_new_ajax'));
            } else {
                $this->Session->setFlash(__('The master list of format could not be saved. Please, try again.'));
            }
        }

        $systemTables = $this->MasterListOfFormat->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormatCategories = $this->MasterListOfFormat->MasterListOfFormatCategory->find('list', array('conditions' => array('MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));
        $standards = $this->MasterListOfFormat->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));
        $parentDocuments = $this->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('systemTables','masterListOfFormatCategories','standards','parentDocuments'));

        $count = $this->MasterListOfFormat->find('count');
        $published = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 1)));
        $unpublished = $this->MasterListOfFormat->find('count', array('conditions' => array('MasterListOfFormat.publish' => 0)));

        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set(compact('count', 'published', 'unpublished','documentStatuses'));
    }

    public function _add_branches_and_departments($branches, $departments, $system_table_id, $newid, $publish, $prepared_by, $approved_by) {

        $new_data = array();

        foreach ($branches as $branchids):
            $this->MasterListOfFormat->MasterListOfFormatBranch->create();
            $new_data['MasterListOfFormatBranch']['master_list_of_format_id'] = $newid;
            $new_data['MasterListOfFormatBranch']['prepared_by'] = $prepared_by;
             $new_data['MasterListOfFormatBranch']['approved_by'] = $approved_by;
            $new_data['MasterListOfFormatBranch']['branch_id'] = $branchids;
            $new_data['MasterListOfFormatBranch']['system_table_id'] = $system_table_id;
            $new_data['MasterListOfFormatBranch']['publish'] = $publish;
            $new_data['MasterListOfFormatBranch']['soft_delete'] = 0;
            $new_data['MasterListOfFormatBranch']['created_by'] = $this->Session->read('User.id');
            $new_data['MasterListOfFormatBranch']['modified_by'] = $this->Session->read('User.id');
            $new_data['MasterListOfFormatBranch']['branchid'] = $this->Session->read('User.branch_id');
            $new_data['MasterListOfFormatBranch']['departmentid'] = $this->Session->read('User.branch_id');

            $this->MasterListOfFormat->MasterListOfFormatBranch->save($new_data);
        endforeach;

        $new_data = array();
        foreach ($departments as $departmentids):
            $this->MasterListOfFormat->MasterListOfFormatDepartment->create();
            $new_data['MasterListOfFormatDepartment']['master_list_of_format_id'] = $newid;
            $new_data['MasterListOfFormatDepartment']['prepared_by'] = $prepared_by;
             $new_data['MasterListOfFormatDepartment']['approved_by'] = $approved_by;
            $new_data['MasterListOfFormatDepartment']['department_id'] = $departmentids;
            $new_data['MasterListOfFormatDepartment']['system_table_id'] = $system_table_id;
            $new_data['MasterListOfFormatDepartment']['publish'] = $publish;
            $new_data['MasterListOfFormatDepartment']['soft_delete'] = 0;
            $new_data['MasterListOfFormatDepartment']['created_by'] = $this->Session->read('User.id');
            $new_data['MasterListOfFormatDepartment']['modified_by'] = $this->Session->read('User.id');
            $new_data['MasterListOfFormatDepartment']['branchid'] = $this->Session->read('User.branch_id');
            $new_data['MasterListOfFormatDepartment']['departmentid'] = $this->Session->read('User.branch_id');
            $this->MasterListOfFormat->MasterListOfFormatDepartment->save($new_data);
        endforeach;
    }

    public function category_list($master_list_of_format_category_id = null,$standard_id = null){
        if($this->request->params['named']['standard_id']){
            $standard = array('MasterListOfFormatCategory.standard_id'=>$this->request->params['named']['standard_id']);
            $this->set('standard_id',$this->request->params['named']['standard_id']);
        }else{
            $standard = array('MasterListOfFormatCategory.standard_id'=>'58511238-fba8-4db9-aad0-833fc20b8995');
            $standard_id = '58511238-fba8-4db9-aad0-833fc20b8995';
            $this->set('standard_id','58511238-fba8-4db9-aad0-833fc20b8995');            
        }
        
        $this->loadModel('MasterListOfFormatCategory');
        $masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list', array('conditions' => array($standard,'MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));

        $this->loadModel('Standard');
        $standards = $this->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));

        $this->set(compact('masterListOfFormatCategories','standards'));

    }

    public function show_cats(){
        
        if($standard_id){
            $standards = array('MasterListOfFormat.standard_id'=>$standard_id);            
        }elseif(isset($this->request->params['named']['standard_id'])){
            $standards = array('MasterListOfFormat.standard_id'=>$this->request->params['named']['standard_id']);
            $standard_id = $this->request->params['named']['standard_id'];
        }
        
        $parentCategories = $this->MasterListOfFormat->MasterListOfFormatCategory->find(
            'threaded',array(
                'conditions'=>array(
                    // 'MasterListOfFormatCategory.parent_id'=>$master_list_of_format_category_id,
                    'MasterListOfFormatCategory.standard_id'=>$standard_id
                    )));
        $a = $this->_show_cats($parentCategories);
        $this->set('parentCategories',$a);
    }

    public function _show_cats($array,$x = 1) {   
        $x = $x + 1;
        if (count($array)) {            
                echo "\n<ul>\n";
            foreach ($array as $vals) {

                        echo "<li id=\"".$vals['MasterListOfFormatCategory']['id']."\"><a href='#' style='color:#0009' class='sub-cats' id='".$vals['MasterListOfFormatCategory']['id']."''>".$vals['MasterListOfFormatCategory']['name']."</a>";
                        
                        if (count($vals['children'])) {
                                $this->_show_cats($vals['children']);
                        }
                        echo "</li>\n";
            }
                echo "</ul>\n";
        }
    }

    public function categorywise_files($master_list_of_format_category_id = null,$standard_id = null){
        
        if(!$master_list_of_format_category_id){
            $master_list_of_format_category_id = $this->request->params['named']['category_id'];
        }
        if($standard_id){
            $standards = array('MasterListOfFormat.standard_id'=>$standard_id);            
        }elseif(isset($this->request->params['named']['standard_id'])){
            $standards = array('MasterListOfFormat.standard_id'=>$this->request->params['named']['standard_id']);
            $standard_id = $this->request->params['named']['standard_id'];
        }
        // $masterListOfFormats = $this->MasterListOfFormat->find('all',array('conditions'=>array($standards,'MasterListOfFormat.master_list_of_format_category_id'=>$master_list_of_format_category_id)));
        // $this->set('masterListOfFormats',$masterListOfFormats);

        if($this->Session->read('User.is_mr')==0){
            $access_conditions = array('MasterListOfFormat.user_id LIKE'=>'%'.$this->Session->read('User.id').'%');    
        }else{
            $access_conditions = array();
        }
        $options = array('conditions' => array(
                    $standards,
                    $access_conditions,
                    'MasterListOfFormat.master_list_of_format_category_id' => $master_list_of_format_category_id,
                    'MasterListOfFormat.archived' => 0 ,
                    'MasterListOfFormat.publish' => 1,
                      'MasterListOfFormat.soft_delete' => 0
                    ),
                  'order'=>array('MasterListOfFormat.title'=>'asc'),
                    'fields' => array('MasterListOfFormat.id', 
                        'MasterListOfFormat.title', 
                        'MasterListOfFormat.standard_id', 
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
                        // 'MasterListOfFormatDepartment.master_list_of_format_id'
                        ),
                    );
        //find change requests
        $formats = $this->MasterListOfFormat->find('all', $options);
        $this->loadModel('ChangeAdditionDeletionRequest');
        $i=0;
        foreach($formats as $format):
            $req= FALSE;
            $flag = $this->ChangeAdditionDeletionRequest->find('first',
                array('conditions'=>array('ChangeAdditionDeletionRequest.master_list_of_format'=>$format['MasterListOfFormat']['id'],'ChangeAdditionDeletionRequest.document_change_accepted'=> 2)));
            if(count($flag) > 0)$req = TRUE;
            $newFormat[$i]['MasterListOfFormat'] = $format['MasterListOfFormat'];
            $newFormat[$i]['PreparedBy'] = $format['PreparedBy'];           
            $newFormat[$i]['ApprovedBy'] = $format['ApprovedBy'];                       
            $newFormat[$i]['flag'] = $req;
            $newFormat[$i]['flag_id'] = $flag['ChangeAdditionDeletionRequest']['id'];
            $i++;
        endforeach;
        $this->set('masterListOfFormats', $newFormat);
        $this->set('PublishedUserList',$this->_get_user_list());
        $this->loadModel('MasterListOfFormatCategory');
        $masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list', array('conditions' => array('MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));
        $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
        $this->set(compact('masterListOfFormatCategories','documentStatuses'));
    }

    
public function _check_revision($id = null, $revision_number = null , $revision_date = null){
    $m = $this->MasterListOfFormat->find('list',array('fields'=>array('MasterListOfFormat.title','MasterListOfFormat.revision_number'),'conditions'=>array('MasterListOfFormat.parent_id'=>$this->request->data['MasterListOfFormat']['id'])));
    if($this->request->data['MasterListOfFormat']['revision_update'] == true){
        $issue['MasterListOfFormat']['revision_number'] = $revision_number;
        $this->MasterListOfFormat->create();
        $data['id'] = $id;
        $data['revision_date'] = date('Y-m-d h:i:s');
        $data['revision_number'] = $revision_number;
        
        $this->MasterListOfFormat->save($data,false);
    } 
}

public function _check_update($id = null){    
    if($this->request->data['MasterListOfFormat']['save_copy'] == true){
        $this->_old_copy($this->request->data,$id);         
        }            
    }
public function _old_copy($data = null,$id = null){
        $this->MasterListOfFormat->create();
        
        unset($data['MasterListOfFormat']['sr_no']);
        unset($data['MasterListOfFormat']['id']);
        $data['MasterListOfFormat']['title'] = $data['MasterListOfFormat']['pre_title'];
        $data['MasterListOfFormat']['parent_id'] = $id;
        $data['MasterListOfFormat']['issue_number'] = $this->request->data['MasterListOfFormat']['issue_number'];
        $data['MasterListOfFormat']['document_details'] = $this->request->data['MasterListOfFormat']['pre_document_details'];
        $data['MasterListOfFormat']['work_instructions'] = $this->request->data['MasterListOfFormat']['pre_work_instructions'];
        $data['MasterListOfFormat']['date_created'] = date('Y-m-d');
        $data['MasterListOfFormat']['archived'] = 1;
        $data['MasterListOfFormat']['publish'] = 0;
        if ($this->MasterListOfFormat->save($data['MasterListOfFormat'],false)) {
            // do nothing
            return $this->MasterListOfFormat->id;
        }else{
            $this->Session->setFlash(__('The master list of format could not be saved. Please, try again.'));
            $this->redirect(array('action' => 'id', $this->MasterListOfFormat->id));
        }
    }

    public function _get_document_history($id = null){
        $document_history = $this->MasterListOfFormat->find('all',array(
            'order'=>array('MasterListOfFormat.created'=>'DESC'),
            'conditions'=>array('or'=>array( 'MasterListOfFormat.parent_id'=>$id,'MasterListOfFormat.id'=>$id))));
        return $document_history;

    }

    public function get_categories($standard_id = null){
        $this->loadModel('MasterListOfFormatCategory');
        $masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list',array('conditions'=>array('MasterListOfFormatCategory.standard_id'=>$standard_id,'MasterListOfFormatCategory.publish'=>1,'MasterListOfFormatCategory.soft_delete'=>0)));
        $this->set('masterListOfFormatCategories',$masterListOfFormatCategories);
        $this->layout = 'ajax';

    }

    public function get_clauses($standard_id = null){
        $this->loadModel('Clause');
        $clauses = $this->Clause->find('list',array('conditions'=>array('Clause.standard_id'=>$standard_id,'Clause.publish'=>1,'Clause.soft_delete'=>0)));
        $this->set('clauses',$clauses);
        $this->layout = 'ajax';

    }

    public function _img_move(){
        $find_imgs = explode('<img', $this->request->data['MasterListOfFormat']['document_details']);
            foreach ($find_imgs as $find_img) {
                    $img = ltrim(rtrim(str_replace(Router::url("/", true), WWW_ROOT, $this->_get_between($find_img, 'alt=""', '/>'))));
                    $path = $this->_get_between($img, 'src="','"');
                    $path = str_replace('//', '/', $path);
                    $file = new File($path);
                    if($file->exists()){
                        $move_to = WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'master_list_of_formats' . DS . $this->request->data['MasterListOfFormat']['standard_id'] . DS . $this->request->data['MasterListOfFormat']['id'] . DS . $this->request->data['MasterListOfFormat']['issue_number'] . DS . $this->request->data['MasterListOfFormat']['revision_number'];
                        mkdir($move_to,0775,true);
                        $new_file = $move_to . DS .  $file->name;
                        try{
                            copy($file->path,$new_file);
                            $url = Router::url("/", true);
                            $url = $url . 'img/' . '/ckeditor/master_list_of_formats/' . $this->Session->read('User.id');
                            $new_destination_url = Router::url("/", true) . 'img' . DS . $this->Session->read('User.company_id') . DS . 'master_list_of_formats' . DS . $this->request->data['MasterListOfFormat']['standard_id'] . DS . $this->request->data['MasterListOfFormat']['id'] . DS . $this->request->data['MasterListOfFormat']['issue_number'] . DS . $this->request->data['MasterListOfFormat']['revision_number'];
                            
                            $this->request->data['MasterListOfFormat']['document_details'] = str_replace('//img','/img', $this->request->data['MasterListOfFormat']['document_details']);
                            $newDetails = str_replace($url,$new_destination_url, $this->request->data['MasterListOfFormat']['document_details']);
                            $data['MasterListOfFormat']['document_details'] = $newDetails;
                            
                            $this->MasterListOfFormat->read(null,$this->request->data['MasterListOfFormat']['id']);
                            $this->MasterListOfFormat->set('document_details',$newDetails);
                            $this->MasterListOfFormat->save();
                            $this->request->data['MasterListOfFormat']['document_details'] = $newDetails;
                            // unlink($file->path);
                        }catch(Exception $e){
                            debug($e);
                        }                        
                    }
                    
                }   
    }


    public function mlfuserlist($branch_id = null, $department_id = null,$format_id = null){
        

        $format_id = $this->request->params['named']['format_id'];        
        if($format_id){
            $format = $this->MasterListOfFormat->find('first',array(
                'recursive'=>-1,
                'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.user_id'),
                'conditions'=>array('MasterListOfFormat.id'=>$format_id)
                ));
            $this->set('sel_users',$format['MasterListOfFormat']['user_id']);            
        }
        $branchess = explode(',', $branch_id);
        $departmentss = explode(',', $department_id);
        $this->loadModel('Branch');
        $this->loadModel('Department');
        
        $bbranches = $this->Branch->find('all', array('order'=>array('Branch.name'=>'ASC'), 'conditions' => array('Branch.id' => $branchess)));
        
        $selected_branches = array();
        foreach ($bbranches as $bb):
            $selected_branches[] = $bb['Branch']['id'];
            $branch_list[$bb['Branch']['id']] = $bb['Branch']['name'];
        endforeach;
        $this->set('selected_branches', $selected_branches);

        $selected_depts = $this->Department->find('all', array('conditions' => array('Department.id' => $departmentss)));
        $selected_departments = array();
        foreach ($selected_depts as $dd):
            $selected_departments[] = $dd['Department']['id'];
        endforeach;
        $this->set('selected_depts', $selected_departments);

        $this->loadModel('User');
        if($branch_list != null){
            foreach ($branch_list as $id=>$name) {                
               $users = $this->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.branch_id'=>$id,'User.department_id'=>$selected_departments)));
               $branches[$id] = array('Name'=>$name,'Users'=>$users);
            }        
        }else{
            $branches = null;
        }
        $this->set('branches',$branches);
        $this->render('/Elements/mlfuserlist');


    }

    public function _send_email($format_id = null){
        return true;
        $this->loadModel('User');
        $this->loadModel('Employee');
        if($format_id){
            $format = $this->MasterListOfFormat->find('first',array(
                // 'recursive'=>-1,
                // 'fields'=>array('MasterListOfFormat.id','MasterListOfFormat.user_id'),
                'conditions'=>array('MasterListOfFormat.id'=>$format_id)
                ));            
        }

        $html = "<p>Dear user,</p>";
        $html .= "<p>Admin has shared a new document with you. Document details :</p>";
        $html .= "<table width='100%' cellpadding='5px'>";
        $html .= "<tr><td>Document Name:</td><td>".$format['MasterListOfFormat']['title']."</td></tr>";
        $html .= "<tr><td>Standard:</td><td>".$format['Standard']['name']."</td></tr>";
        $html .= "<tr><td>Format Categort:</td><td>".$format['MasterListOfFormatCategory']['name']."</td></tr>";
        $html .= "</table><p>Login to FlinkISO application to view/downlod the document.</p>";
        
        if($format){
            $users = json_decode($format['MasterListOfFormat']['user_id']);
            $users = $this->User->find('list',array('fields'=>array('User.id','User.employee_id'), 'conditions'=>array('User.id'=>$users)));
            foreach ($users as $user_id=>$employee_id) {
                $employee = $this->Employee->find('first',array('conditions'=>array('Employee.id'=>$employee_id), 
                    'fields'=>array('id', 'office_email','personal_email'), 'recursive'=>-1));
                  $officeEmailId = $employee['Employee']['office_email'];
                  $personalEmailId = $employee['Employee']['personal_email'];
                  if ($officeEmailId != '') {
                    $email = $officeEmailId;
                  } else if ($personalEmailId != '') {
                    $email = $personalEmailId;
                  }

                  try{
                    App::uses('CakeEmail', 'Network/Email');
                    if($this->Session->read('User.is_smtp') == 1)
                      $EmailConfig = new CakeEmail("smtp");
                    if($this->Session->read('User.is_smtp') == 0)
                      $EmailConfig = new CakeEmail("default");
                    $EmailConfig->to($email);
                   
                    $model = Inflector::classify($this->request->controller);
                    $this->loadModel($model);
                    $title = 'New quality document shared.';
                    
                    
                    
                    // if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                    // elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                    // else 
                    $env = "";

                    $EmailConfig->subject('FlinkISO : New quality document is shared with you.');
                    // $EmailConfig->template('approval_request');
                    $EmailConfig->template('quality_document_shared');
                    $EmailConfig->viewVars(array('html' => $html,'title'=>$title,'env' => $env, 'app_url' => FULL_BASE_URL));
                    $EmailConfig->emailFormat('html');
                    $EmailConfig->send();
                  } catch(Exception $e) {
                    $this->Session->setFlash(__('The user has been saved but fail to send email. Please check smtp details.', true), 'smtp');
                    $this->redirect(array('action' => 'index'));
                  }
            }

        }        
    }
}

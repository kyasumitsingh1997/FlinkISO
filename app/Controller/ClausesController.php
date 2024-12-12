<?php

App::uses('AppController', 'Controller');

/**
 * Branches Controller
 *
 * @property Branch $Branch
 */
class ClausesController extends AppController {

public function _get_system_table_id() {
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
    }

    public function Introduction(){

    }

    public function analysis_and_improvement(){
        
    }

    public function customer_focus(){
        
    }

    public function management_responsibility(){
        
    }

    public function process_and_process_approach(){
        
    }

    public function process_planning(){
        
    }

    public function product_realization(){
        
    }

    public function quality_management_system(){
        
    }

    public function resource_management(){
        
    }    
  
    
/**
 * index method
 *
 * @return void
 */
    public function home() {
        
         $this->redirect(array('action' => 'standards'));
    }

    public function index() {
       
        if($this->request->params['pass'][0])$clause_conditions =  array('Clause.standard_id'=>$this->request->params['pass'][0], 'Clause.sub-clause'=>'','Clause.publish'=>1,'Clause.soft_delete'=>0);
        else $clause_conditions = array();

        $conditions = $this->_check_request();
        $this->paginate = array('order'=>array('Clause.sr_no'=>'desc'),'conditions'=>array($conditions,$clause_conditions));
    
        $this->Clause->recursive = 0;
        $this->set('clauses', $this->paginate());
        
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
        $this->paginate = array('order'=>array('Clause.sr_no'=>'DESC'),'conditions'=>array($conditions));
        
        $this->Clause->recursive = 0;
        $this->set('clauses', $this->paginate());
        
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
        $search_keys = explode(" ",$this->request->data['Clause']['search']);
    
        foreach($search_keys as $search_key):
            foreach($this->request->data['Clause']['search_field'] as $search):
                $search_array[] = array('Clause.'.$search .' like' => '%'.$search_key.'%');
            endforeach;
        endforeach;
        
        if($this->Session->read('User.is_mr') == 0)
            {
                $cons = array('Clause.branch_id'=>$this->Session->read('User.branch_id'));
            }
        
        $this->Clause->recursive = 0;
        $this->paginate = array('order'=>array('Clause.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Clause.soft_delete'=>0 , $cons));
        $this->set('clauses', $this->paginate());
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
                    if($this->request->query['strict_search'] == 0)$search_array[] = array('Clause.'.$search => $search_key);
                    else $search_array[] = array('Clause.'.$search.' like ' => '%'.$search_key.'%');
                        
                    endforeach;
                endforeach;
                if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
                else $conditions[] = array('or'=>$search_array);
            }
            
        if($this->request->query['branch_list']){
            foreach($this->request->query['branch_list'] as $branches):
                $branch_conditions[]=array('Clause.branch_id'=>$branches);
            endforeach;
            $conditions[]=array('or'=>$branch_conditions);
        }
        
        if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
        if($this->request->query['from-date']){
            $conditions[] = array('Clause.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Clause.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
        }
        unset($this->request->query);
        
        
        if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Clause.branch_id'=>$this->Session->read('User.branch_id'));
        if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Clause.created_by'=>$this->Session->read('User.id'));
        $conditions[] = array($onlyBranch,$onlyOwn);
        
        $this->Clause->recursive = 0;
        $this->paginate = array('order'=>array('Clause.sr_no'=>'DESC'),'conditions'=>$conditions , 'Clause.soft_delete'=>0 );
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('clauses', $this->paginate());
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
        if (!$this->Clause->exists($id)) {
            throw new NotFoundException(__('Invalid Clause'));
        }
        $options = array('conditions' => array('Clause.' . $this->Clause->primaryKey => $id));
        $this->set('clause', $this->Clause->find('first', $options));
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
            $this->request->data['Clause']['system_tables'] = json_encode($this->request->data['system_tables']);
            if($this->request->data['Clause']['new_tabs'] != ''){
                $tabs = explode(',', $this->request->data['Clause']['new_tabs']);
                foreach ($tabs as $tab) {
                    $tab_name .= ltrim(rtrim($tab)) .',';
                    if($tab_name != ','){
                        $new_tabs = $tab_name;
                    }
                }
                if($this->request->data['Clause']['tabs'] == ''){
                    $this->request->data['Clause']['tabs'] = substr_replace($new_tabs ,"",-1);
                }else{
                    $this->request->data['Clause']['tabs'] = $this->request->data['Clause']['tabs'] .",".substr_replace($new_tabs ,"",-1);    
                }
                
            }
            $this->request->data['Clause']['system_table_id'] = $this->_get_system_table_id();
            $this->Clause->create();
            if ($this->Clause->save($this->request->data)) {


                if ($this->_show_approvals()) $this->_save_approvals();
                $this->Session->setFlash(__('The Clause has been saved'));
                if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Clause->id));
                else $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Clause could not be saved. Please, try again.'));
            }
        }
        $standards = $this->Clause->Standard->find('list',array('conditions'=>array('Standard.publish'=>1,'Standard.soft_delete'=>0)));
        $systemTables = $this->Clause->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
        $companies = $this->Clause->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
        $preparedBies = $this->Clause->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
        $approvedBies = $this->Clause->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
        $createdBies = $this->Clause->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
        $modifiedBies = $this->Clause->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
        $this->set(compact('standards', 'systemTables', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
        $count = $this->Clause->find('count');
        $published = $this->Clause->find('count',array('conditions'=>array('Clause.publish'=>1)));
        $unpublished = $this->Clause->find('count',array('conditions'=>array('Clause.publish'=>0)));
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
                        $this->request->data['Clause']['system_table_id'] = $this->_get_system_table_id();
            $this->Clause->create();
            if ($this->Clause->save($this->request->data)) {

                if($this->_show_approvals()){
                    $this->loadModel('Approval');
                    $this->Approval->create();
                    $this->request->data['Approval']['model_name']='Clause';
                    $this->request->data['Approval']['controller_name']=$this->request->params['controller'];
                    $this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
                    $this->request->data['Approval']['from']=$this->Session->read('User.id');
                    $this->request->data['Approval']['created_by']=$this->Session->read('User.id');
                    $this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
                    $this->request->data['Approval']['record']=$this->Clause->id;
                    $this->Approval->save($this->request->data['Approval']);
                }
                $this->Session->setFlash(__('The Clause has been saved'));
                if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Clause->id));
                else $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Clause could not be saved. Please, try again.'));
            }
        }
        $systemTables = $this->Clause->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
        $companies = $this->Clause->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
        $preparedBies = $this->Clause->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
        $approvedBies = $this->Clause->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
        $createdBies = $this->Clause->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
        $modifiedBies = $this->Clause->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
        $this->set(compact('systemTables', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
        $count = $this->Clause->find('count');
        $published = $this->Clause->find('count',array('conditions'=>array('Clause.publish'=>1)));
        $unpublished = $this->Clause->find('count',array('conditions'=>array('Clause.publish'=>0)));
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
        if (!$this->Clause->exists($id)) {
            throw new NotFoundException(__('Invalid Clause'));
        }
        
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
        $this->request->data['Clause']['system_tables'] = json_encode($this->request->data['system_tables']);
        if($this->request->data['Clause']['new_tabs'] != ''){
            $tabs = explode(',', $this->request->data['Clause']['new_tabs']);
            foreach ($tabs as $tab) {
                $tab_name .= ltrim(rtrim($tab)) .',';
                if($tab_name != ','){
                    $new_tabs = $tab_name;
                }
            }
            if($this->request->data['Clause']['tabs'] == ''){
                $this->request->data['Clause']['tabs'] = substr_replace($new_tabs ,"",-1);
            }else{
                $this->request->data['Clause']['tabs'] = $this->request->data['Clause']['tabs'] .",".substr_replace($new_tabs ,"",-1);    
            }
            
        }
        if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
            $this->request->data[$this->modelClass]['publish'] = 0;
        }
                        
            $this->request->data['Clause']['system_table_id'] = $this->_get_system_table_id();
            if ($this->Clause->save($this->request->data)) {

                if ($this->_show_approvals()) $this->_save_approvals();
                
                if ($this->_show_evidence() == true)
                 $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'standards'));
            } else {
                $this->Session->setFlash(__('The Clause could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Clause.' . $this->Clause->primaryKey => $id));
            $this->request->data = $this->Clause->find('first', $options);
        }
        $systemTables = $this->Clause->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
        $companies = $this->Clause->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
        $preparedBies = $this->Clause->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
        $approvedBies = $this->Clause->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
        $createdBies = $this->Clause->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
        $modifiedBies = $this->Clause->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
        $this->set(compact('systemTables', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
        $count = $this->Clause->find('count');
        $published = $this->Clause->find('count',array('conditions'=>array('Clause.publish'=>1)));
        $unpublished = $this->Clause->find('count',array('conditions'=>array('Clause.publish'=>0)));
        $selected_tabels = $this->Clause->SystemTable->find('list',array('conditions'=>array('SystemTable.id'=>json_decode($this->request->data['Clause']['system_tables']))));
        foreach($selected_tabels as $key => $value){
            $selected[] = $key;
        }
        $this->set('selected_tabels',$selected);        
        $this->set(compact('count','published','unpublished'));
    }

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function approve($id = null, $approvalId = null) {
        if (!$this->Clause->exists($id)) {
            throw new NotFoundException(__('Invalid Clause'));
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
            if ($this->Clause->save($this->request->data)) {
                if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->Clause->save($this->request->data)) {
                $this->Session->setFlash(__('The Clause has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The Clause could not be saved. Please, try again.'));
            }
                
            } else {
                $this->Session->setFlash(__('The Clause could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Clause.' . $this->Clause->primaryKey => $id));
            $this->request->data = $this->Clause->find('first', $options);
        }
        $systemTables = $this->Clause->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
        $companies = $this->Clause->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
        $preparedBies = $this->Clause->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
        $approvedBies = $this->Clause->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
        $createdBies = $this->Clause->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
        $modifiedBies = $this->Clause->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
        $this->set(compact('systemTables', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
        $count = $this->Clause->find('count');
        $published = $this->Clause->find('count',array('conditions'=>array('Clause.publish'=>1)));
        $unpublished = $this->Clause->find('count',array('conditions'=>array('Clause.publish'=>0)));
        
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
        $this->Clause->id = $id;
        if (!$this->Clause->exists()) {
            throw new NotFoundException(__('Invalid Clause'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Clause->delete()) {
            $this->Session->setFlash(__('Clause deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Clause was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete($id = null, $parent_id = null) {
    
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
        
        $result = explode('+',$this->request->data['clauses']['rec_selected']);
        $this->Clause->recursive = 1;
        $clauses = $this->Clause->find('all',array('Clause.publish'=>1,'Clause.soft_delete'=>1,'conditions'=>array('or'=>array('Clause.id'=>$result))));
        $this->set('clauses', $clauses);
        
        $systemTables = $this->Clause->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
        $companies = $this->Clause->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
        $preparedBies = $this->Clause->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
        $approvedBies = $this->Clause->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
        $createdBies = $this->Clause->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
        $modifiedBies = $this->Clause->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
        $this->set(compact('systemTables', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'systemTables', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}

    public function standards(){
        $standards = $this->Clause->Standard->find('list',array('order'=>array('Standard.sr_no'=>'DESC'), 'conditions'=>array('Standard.publish'=>1,'Standard.soft_delete'=>0)));
        $this->set(compact('standards'));

    }
    public function documents(){
        $clauses = $this->Clause->find('all',
            array(
                'conditions'=>array('Clause.standard_id'=>$this->request->params['pass'][0], 'Clause.sub-clause'=>'','Clause.publish'=>1,'Clause.soft_delete'=>0),
                'order'=>array('Clause.intclause'=>'ASC'), 
                'recursive'=>-1                
            ));
        foreach ($clauses as $clause) {
            $sub_clause = $this->Clause->find('all',array('recursive'=>-1, 'order'=>array('Clause.sub-clause'=>'asc'),'conditions'=>array('Clause.publish'=>1,'Clause.soft_delete'=>0,'Clause.standard_id'=>$this->request->params['pass'][0],'Clause.clause'=>$clause['Clause']['clause'],'Clause.sub-clause !=' => '')));
            $final[$clause['Clause']['clause']]['clause'] = $clause['Clause']['clause'];
            $final[$clause['Clause']['clause']]['title']  = $clause['Clause']['title'];
            $final[$clause['Clause']['clause']]['id']  = $clause['Clause']['id'];
            $final[$clause['Clause']['clause']]['sub']  = $sub_clause;            
        }
        $this->set('final',$final);  

        $this->loadModel('MasterListOfFormatCategory');      
        $masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list',array('conditions'=>array('MasterListOfFormatCategory.publish'=>1,'MasterListOfFormatCategory.soft_delete'=>0,'MasterListOfFormatCategory.standard_id'=>$this->request->params['pass'][0])));
        $this->set(compact('masterListOfFormatCategories'));

    }

    public function files($id = null, $standard_id = null){
        $id = str_replace('clause-', '', $id);
        $clause_record = $this->Clause->find('first',array('recursive'=>-1, 'conditions'=>array('Clause.id'=>$id)));
        
        $options = array('conditions' => array('Clause.id' => $id));
        $this->request->data = $this->Clause->find('first', $options);

        $sys = $this->Clause->SystemTable->find('all',array('recursive'=>-1,'conditions'=>array('SystemTable.id'=>json_decode($this->request->data['Clause']['system_tables']))));
        $this->set('tables',$sys);
        
        $this->loadModel('FileUpload');
        $files = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.record'=>$id)));
        $this->set('files',$files);   

        // get master list of format
        $this->loadModel('MasterListOfFormat');
        $masterListOfFormats = $this->MasterListOfFormat->find('all',array('conditions'=>array('MasterListOfFormat.clause_id'=>$id),'recursive'=>0));
        $this->set(compact('masterListOfFormats'));
    }

    public function clausefiles(){

    }

    public function _move_files($tab = null,$sub_clause = null){}

    public function change_tab($id = null, $name = null){
        if (!$this->Clause->exists($id)) {
            throw new NotFoundException(__('Invalid Clause'));
        }
        
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $clause = $this->Clause->find('first',array(
                'recursive' => -1,
                'conditions'=>array('Clause.id'=>$id)));
            $this->request->data['Clause']['tabs'] = str_replace(base64_decode($name), $this->request->data['Clause']['new_tab_name'], $clause['Clause']['tabs']);
            if ($this->Clause->save($this->request->data)) {
                $this->_update_folders($this->request->data['Clause']['id'],base64_decode($name),$this->request->data['Clause']['new_tab_name']);    
                if ($this->_show_approvals()) $this->_save_approvals();
                
                if ($this->_show_evidence() == true)
                 $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'standards'));
            } else {
                $this->Session->setFlash(__('The Clause could not be saved. Please, try again.'));
            }            
        }else {
            $options = array('conditions' => array('Clause.' . $this->Clause->primaryKey => $id));
            $this->request->data = $this->Clause->find('first', $options);
        }        

    }

    public function _update_folders($id =null, $name = null,$new_name = null){
        $this->loadModel('FileUpload');
        $files = $this->FileUpload->find('all',array(
            'recursive' => -1,            
            'conditions'=>array(
                'FileUpload.system_table_id'=>'clauses', 
                'FileUpload.record' => $id,
                'FileUpload.file_dir LIKE' => '%'.$name.'%',
            )));
         foreach ($files as $file) {
            $file['FileUpload']['file_dir'] = str_replace($name,$new_name,$file['FileUpload']['file_dir']);
            $file['FileUpload']['comment'] = $file['FileUpload']['comment'] . ' , "'. $name .'" was changed to "' . $new_name .  '" on : ' . date('Y-m-d h:i:s');
            $this->FileUpload->read(null,$file['FileUpload']['id']);
            $this->FileUpload->save($file,false);
        }
        
        $dir_from = APP . 'files' . DS . $this->Session->read('User.company_id') . DS . 'upload/clauses' .  DS . $id . DS .  $name;
        $dir_to = APP . 'files' . DS . $this->Session->read('User.company_id') . DS . 'upload/clauses' .  DS . $id . DS .  $new_name;
        rename($dir_from, $dir_to);

        $dir_from = APP . 'files' . DS . $this->Session->read('User.company_id') . DS . 'revisions/clauses' .  DS . $id . DS .  $name;
        $dir_to = APP . 'files' . DS . $this->Session->read('User.company_id') . DS . 'revisions/clauses' .  DS . $id . DS .  $new_name;
        rename($dir_from, $dir_to);

    }
}

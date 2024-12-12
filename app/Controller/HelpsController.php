<?php

App::uses('AppController', 'Controller');

/**
 * Helps Controller
 *
 * @property Help $Help
 */
class HelpsController extends AppController {

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

        $this->paginate = array(
            
            'order' => array('Help.table_name' => 'ASC'));

        $this->Help->recursive = 0;
        $this->set('sys_helps', $this->paginate());
        $this->_get_count($this->paginate());
    }

    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function advanced_search() {
        $conditions = array();
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
                        $searchArray[] = array('Help.' . $search => $SearchKey);
                    else
                        $searchArray[] = array('Help.' . $search . ' like ' => '%' . $SearchKey . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('Help.branch_id' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('Help.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'Help.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions = $this->advance_search_common($conditions);
        unset($this->request->query);


        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('Help.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('Help.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->Help->recursive = 0;
        $this->paginate = array('order' => array('Help.sr_no' => 'DESC'), 'conditions' => $conditions, 'Help.soft_delete' => 0);
        $this->set('sys_helps', $this->paginate());

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
        if (!$this->Help->exists($id)) {
            throw new NotFoundException(__('Invalid help'));
        }
        $options = array('conditions' => array('Help.' . $this->Help->primaryKey => $id));
        $this->set('help', $this->Help->find('first', $options));
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
            $this->request->data['Help']['system_table_id'] = $this->_get_system_table_id();
            
            foreach ($this->request->data['Help']['Translations'] as $language_id => $data) {
                $this->Help->create();
                $fdata['Help']['table_name'] = $this->request->data['Help']['table_name'];
                $fdata['Help']['action_name'] = $this->request->data['Help']['action_name'];
                $fdata['Help']['language_id'] = $language_id;
                $fdata['Help']['title'] = $data['title'];
                $fdata['Help']['help_text'] = $data['help_text'];
                $fdata['Help']['sequence'] = $this->request->data['Help']['sequence'];
                $fdata['Help']['branchid'] = $this->request->data['Help']['branchid'];
                $fdata['Help']['departmentid'] = $this->request->data['Help']['departmentid'];
                $fdata['Help']['master_list_of_format_id'] = $this->request->data['Help']['master_list_of_format_id'];
                $fdata['Help']['prepared_by'] = $this->request->data['Help']['prepared_by'];
                $fdata['Help']['approved_by'] = $this->request->data['Help']['approved_by'];
                $fdata['Help']['publish'] = $this->request->data['Help']['publish'];
                $fdata['Help']['system_table_id'] = $this->request->data['Help']['system_table_id'];
                $this->Help->save($fdata);
            }
            // exit;
            // if ($this->Help->save($this->request->data)) {

                $this->Session->setFlash(__('The help has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Help->id));
                else
                    $this->redirect(array('action' => 'index'));
            // } else {
            //     $this->Session->setFlash(__('The help could not be saved. Please, try again.'));
            // }
        }
        $languages = $this->Help->Language->find('list', array('conditions' => array('Language.publish' => 1, 'Language.soft_delete' => 0)));
        $systemTables = $this->Help->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormats = $this->Help->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('languages', 'systemTables', 'masterListOfFormats'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Help->exists($id)) {
            throw new NotFoundException(__('Invalid help'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            foreach ($this->request->data['Help']['Translations'] as $language_id => $data) {
                $this->Help->create();
                $fdata['Help']['id'] = $data['id'];
                $fdata['Help']['table_name'] = $this->request->data['Help']['table_name'];
                $fdata['Help']['action_name'] = $this->request->data['Help']['action_name'];
                $fdata['Help']['language_id'] = $language_id;
                $fdata['Help']['title'] = $data['title'];
                $fdata['Help']['help_text'] = $data['help_text'];
                $fdata['Help']['sequence'] = $this->request->data['Help']['sequence'];
                $fdata['Help']['branchid'] = $this->request->data['Help']['branchid'];
                $fdata['Help']['departmentid'] = $this->request->data['Help']['departmentid'];
                $fdata['Help']['master_list_of_format_id'] = $this->request->data['Help']['master_list_of_format_id'];
                $fdata['Help']['prepared_by'] = $this->request->data['Help']['prepared_by'];
                $fdata['Help']['approved_by'] = $this->request->data['Help']['approved_by'];
                $fdata['Help']['publish'] = $this->request->data['Help']['publish'];
                $fdata['Help']['system_table_id'] = $this->request->data['Help']['system_table_id'];
                
                $this->Help->save($fdata,false);
            }
            $this->request->data['Help']['system_table_id'] = $this->_get_system_table_id();
                $this->Session->setFlash(__('The help has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                // if ($this->_show_evidence() == true)
                //     $this->redirect(array('action' => 'view', $id));
                // else
                    $this->redirect(array('action' => 'index'));            
        } else {
            $help = $this->Help->find('first',array('conditions'=>array('Help.id'=>$id),'recursive'=>-1));
            $languages = $this->Help->Language->find('list', array('conditions' => array('Language.publish' => 1, 'Language.soft_delete' => 0)));
            foreach ($languages as $key => $value) {
                $result = $this->Help->find('first',array(
                    'conditions'=>array(
                    'Help.table_name'=>$help['Help']['table_name'],
                    'Help.action_name'=>$help['Help']['action_name'],
                    'Help.sequence'=>$help['Help']['sequence'],
                    'Help.language_id'=>$key
                )));                
                $helps[$key] = $result;
            }
            
            $this->request->data['Help'] = $helps;            
        }
        $languages = $this->Help->Language->find('list', array('conditions' => array('Language.publish' => 1, 'Language.soft_delete' => 0)));
        $systemTables = $this->Help->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormats = $this->Help->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('languages', 'systemTables', 'masterListOfFormats'));
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->Help->exists($id)) {
            throw new NotFoundException(__('Invalid help'));
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
            if ($this->Help->save($this->request->data)) {

                $this->Session->setFlash(__('The help has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The help could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Help.' . $this->Help->primaryKey => $id));
            $this->request->data = $this->Help->find('first', $options);
        }
        $languages = $this->Help->Language->find('list', array('conditions' => array('Language.publish' => 1, 'Language.soft_delete' => 0)));
        $systemTables = $this->Help->SystemTable->find('list', array('conditions' => array('SystemTable.publish' => 1, 'SystemTable.soft_delete' => 0)));
        $masterListOfFormats = $this->Help->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0)));
        $this->set(compact('languages', 'systemTables', 'masterListOfFormats'));
    }

    public function help() {

    }
}

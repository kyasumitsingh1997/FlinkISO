<?php

App::uses('AppController', 'Controller');

/**
 * Employees Controller
 *
 * @property Employee $Employee
 */
class EmployeesController extends AppController {

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
        $this->paginate = array('order' => array('Employee.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->Employee->recursive = 0;
        $employees = $this->paginate();
        foreach ($employees as $employee) {
            $users = 0;
            $users = $this->Employee->User->find('count',array('conditions'=>array('User.employee_id'=>$employee['Employee']['id'])));
            $employee['User'] = $users;
            $newEmployees[] = $employee;
        }
        $this->set('employees', $newEmployees);

        $this->_get_count();
    }

    public function employee_kra($id = null, $employeeKra = null, $edit = null) {
        if (!isset($id)){
            $id = $this->request->data['Employee']['id'];
         }
         $this->loadModel('EmployeeKra');
         if($edit == 2){
            if (isset($employeeKra)){
            $this->loadModel('EmployeeKra');
            $this->EmployeeKra->delete($employeeKra,true);
         }
         $edit =1;
        }
        $this->set(compact('employeeKra','edit'));
        if ($this->request->is('post') || $this->request->is('put')) {
        foreach ($this->request->data['Employee'] as $value) {
                $data = array();
                $data['EmployeeKra']['title'] = $value['title'];
                $data['EmployeeKra']['target'] = $value['target'];
                $data['EmployeeKra']['description'] = $value['description'];
                if (isset($value['edit']) && $value['edit'] == 1 && $value['edit'] != 2) {
                    if ((!empty($value['title'])) || (!empty($value['target'])) || (!empty($value['description']))) {
                        $this->EmployeeKra->id = $this->request->data['Employee']['Kraid'];
                        $this->EmployeeKra->save($data['EmployeeKra'], false);
                    }
                }

                else if ($value['edit'] != 1 && $value['edit'] != 2) {
                    $data['EmployeeKra']['branchid'] = $value['branchid'];
                    $data['EmployeeKra']['departmentid'] = $value['departmentid'];
                    $data['EmployeeKra']['master_list_of_format_id'] = $value['master_list_of_format_id'];
                    $data['EmployeeKra']['system_table_id'] = $this->_get_system_table_id();
                    $data['EmployeeKra']['employee_id'] = $this->request->data['Employee']['id'];
                    if ((!empty($value['title'])) || (!empty($value['target'])) || (!empty($value['description']))) {
                        $this->EmployeeKra->create();
                        $this->EmployeeKra->save($data['EmployeeKra'], false);
                    }


                }
            }
        }
        $options = array('conditions' => array('Employee.id' => $id));
        $this->loadModel('EmployeeKra');
        $kraLists = $this->EmployeeKra->find('all', array('conditions' => array('EmployeeKra.employee_id' => $id)));
        $this->set('employee', $this->Employee->find('first', $options));
        $this->set('kraLists', $kraLists);
        $this->render('/Elements/add_kra');
    }
   

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null, $employee_kra = null) {
        if (!$this->Employee->exists($id)) {
            throw new NotFoundException(__('Invalid employee'));
        }
        $options = array('conditions' => array('Employee.' . $this->Employee->primaryKey => $id));
        $this->loadModel('EmployeeKra');
        $kraLists = $this->EmployeeKra->find('all', array('conditions' => array('EmployeeKra.employee_id' => $id)));
        $this->set('employee', $this->Employee->find('first', $options));
        $this->set('kraLists', $kraLists);
        $this->set('employee_kra', $employee_kra);
        $this->_etni($id);

        //get emmployee use
        $this->loadModel('User');
        $employee_users = $this->User->find('count',array('conditions'=>array('User.employee_id'=> $id)));
        $this->set('nouser',$employee_users);
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
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }

            $this->request->data['Employee']['qualification'] = implode(",", $this->request->data['qualification']);
            $this->request->data['Employee']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['Employee']['created_by'] = $this->Session->read('User.id');
            $this->request->data['Employee']['modified_by'] = $this->Session->read('User.id');
            $this->request->data['Employee']['created'] = date('Y-m-d H:i:s');
            $this->request->data['Employee']['modified_by'] = date('Y-m-d H:i:s');

            $this->Employee->create();
            if ($this->Employee->save($this->request->data, false)) {

   if(isset($this->request->data['Employee']['certificate']['error']) && $this->request->data['Employee']['certificate']['error'] == 0){
             
                            $file = new File($this->request->data['Employee']['certificate']['name'], FALSE);
                            $fileinfo = $file->info();
   
                            if (filesize($this->request->data['Employee']['certificate']['tmp_name']) > 5000000){
                                $this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
                            $fileinfo['basename'] = str_replace(' ', '', $fileinfo['basename']);
                            // if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
                            //     $this->Session->setFlash(__('Certificate file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                            //     $this->redirect(array('action' => 'view', $id));
                            // }

                            if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
                                $nameLengthCheck = false;
                                $this->Session->setFlash(__('Certificate file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }


                            
                            if(!in_array($fileinfo['extension'], array('crt'))){
                                $this->Session->setFlash(__('Certificate file type is invalid. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            if(!file_exists(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS .'PdfCertificate' . DS .  $this->Employee->id)){
                                new Folder(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS .'PdfCertificate' . DS .  $this->Employee->id, TRUE, 0755);
                            }
                            
                            $fileinfo['basename'] = "pdf.crt";
                            $moveLogo = move_uploaded_file($this->request->data['Employee']['certificate']['tmp_name'], 
                            Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS .'PdfCertificate' . DS .  $this->Employee->id. DS . $fileinfo['basename']); 
                            
                            if($moveLogo){
                                $dir_name = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS .'PdfCertificate' . DS;
                                $dir = opendir($dir_name);
                                chdir($dir_name);

                                $imgFile = getimagesize($fileinfo['basename']);
                                $format = $imgFile['mime'];
                                $this->request->data['Employee']['certificate'] = $fileinfo['basename'];
                            } else {
                                $this->Session->setFlash(__('Certificate upload was not successful. Please verify folder permissions & try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
            }
            
            
            
             if(isset($this->request->data['Employee']['signature']['error']) && $this->request->data['Employee']['signature']['error'] == 0){
             
                            $file = new File($this->request->data['Employee']['signature']['name'], FALSE);
                            $fileinfo = $file->info();
   
                            if (filesize($this->request->data['Employee']['signature']['tmp_name']) > 5000000){
                                $this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
                            $fileinfo['basename'] = str_replace(' ','',$fileinfo['basename']);
                            // if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
                            //     $this->Session->setFlash(__('Signature file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                            //     $this->redirect(array('action' => 'view', $id));
                            // }

                            if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
                                $nameLengthCheck = false;
                                $this->Session->setFlash(__('Signature file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            
                            if(!in_array($fileinfo['extension'], array('png'))){
                                $this->Session->setFlash(__('Signature file type is invalid.Please upload .png image.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            if(!file_exists(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS .'PdfCertificate' . DS . $this->Employee->id)){
                                new Folder(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS .'PdfCertificate' . DS .  $this->Employee->id, TRUE, 0755);
                            }
                            
                            $fileinfo['basename'] = "signature.png";
                            $moveLogo = move_uploaded_file($this->request->data['Employee']['signature']['tmp_name'], 
                            Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS .'PdfCertificate' . DS .  $this->Employee->id. DS . $fileinfo['basename']); 
                            
                            if($moveLogo){
                                $dir_name = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS .'PdfCertificate' . DS;
                                $dir = opendir($dir_name);
                                chdir($dir_name);

                                $imgFile = getimagesize($fileinfo['basename']);
                                $format = $imgFile['mime'];

                                if ($format != '') {
                                    list($width, $height) = $imgFile;
                                    $ratio = $width / $height;
                                    $newheight = 80;
                                    $newwidth = 80 * $ratio;
                                    switch ($format) {
                                        case 'image/png';
                                            $source = imagecreatefrompng($fileinfo['basename']);
                                            break;
                                    }
                                    $dest = imagecreatetruecolor($newwidth, $newheight);
                                    imagealphablending($dest, false);
                                    imagesavealpha($dest, true);
                                    imagecopyresampled($dest, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                                    switch ($format) {
                                        case 'image/png';
                                            imagedestroy($source);
                                            @imagepng($dest, $fileinfo['basename'], 9);
                                            imagedestroy($dest);
                                            break;
                                    }
                                }

                                $this->request->data['Employee']['signature'] = $fileinfo['basename'];
                            } else {
                                $this->Session->setFlash(__('Signature upload was not successful. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
            }
              

                $this->Session->setFlash(__('The employee has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Employee->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The employee could not be saved. Please, try again.'));
            }
        }
        $maritalStatus = array('Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed', 'Separated' => 'Separated', 'Divorced' => 'Divorced', 'Other' => 'Other');
        $branches = $this->Employee->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $designations = $this->Employee->Designation->find('list', array('conditions' => array('Designation.publish' => 1, 'Designation.soft_delete' => 0)));
        $divisions = $this->Employee->Division->find('list', array('conditions' => array('Division.publish' => 1, 'Division.soft_delete' => 0)));
        $departments = $this->Employee->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $this->set(compact('branches', 'divisions', 'designations', 'maritalStatus','departments'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Employee->exists($id)) {
            throw new NotFoundException(__('Invalid employee'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if($this->request->data['qualification'])$this->request->data['Employee']['qualification'] = implode(",", $this->request->data['qualification']);
           // $this->request->data['Employee']['system_table_id'] = $this->_get_system_table_id();
            // $this->request->data['Employee']['modified_by'] = $this->Session->read('User.id');
            // $this->request->data['Employee']['modified_by'] = date('Y-m-d H:i:s');
            
            
            
            
            
            
            
            if(isset($this->request->data['Employee']['certificate']['error']) && $this->request->data['Employee']['certificate']['error'] == 0){

             
                            $file = new File($this->request->data['Employee']['certificate']['name'], FALSE);
                            $fileinfo = $file->info();
   
                            if (filesize($this->request->data['Employee']['certificate']['tmp_name']) > 5000000){
                                $this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            $fileinfo['basename'] = str_replace(' ', '', $fileinfo['basename']);
                            // if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
                            //     $this->Session->setFlash(__('Certificate file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                            //     $this->redirect(array('action' => 'view', $id));
                            // }

                            if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
                                $nameLengthCheck = false;
                                $this->Session->setFlash(__('Certificate file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

//                            if(!in_array($fileinfo['extension'], array('gif','jpg','jpe','jpeg','png'))){
//                                $this->Session->setFlash(__('Certificate file type is invalid. Please try again.'), 'default', array('class' => 'alert alert-danger'));
//                                $this->redirect(array('action' => 'view', $id));
//                            }
                            
                            if(!in_array($fileinfo['extension'], array('crt'))){
                                $this->Session->setFlash(__('Certificate file type is invalid. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            if(!file_exists(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id'])){
                                new Folder(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id'], TRUE, 0755);
                            }
                            
                            $fileinfo['basename'] = "pdf.crt";
                            $moveLogo = move_uploaded_file($this->request->data['Employee']['certificate']['tmp_name'], 
                            Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id']. DS . $fileinfo['basename']); 
                            
                            if($moveLogo){
                                $dir_name = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS;
                                $dir = opendir($dir_name);
                                chdir($dir_name);

                                $imgFile = getimagesize($fileinfo['basename']);
                                $format = $imgFile['mime'];

//                                if ($format != '') {
//                                    list($width, $height) = $imgFile;
//                                    $ratio = $width / $height;
//                                    $newheight = 80;
//                                    $newwidth = 80 * $ratio;
//                                    switch ($format) {
//                                        case 'image/jpeg':
//                                            $source = imagecreatefromjpeg($fileinfo['basename']);
//                                            break;
//                                        case 'image/gif';
//                                            $source = imagecreatefromgif($fileinfo['basename']);
//                                            break;
//                                        case 'image/png';
//                                            $source = imagecreatefrompng($fileinfo['basename']);
//                                            break;
//                                    }
//                                    $dest = imagecreatetruecolor($newwidth, $newheight);
//                                    imagealphablending($dest, false);
//                                    imagesavealpha($dest, true);
//                                    imagecopyresampled($dest, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
//
//                                    switch ($format) {
//                                        case 'image/jpeg':
//                                            imagedestroy($source);
//                                            @imagejpeg($dest, $fileinfo['basename'], 100);
//                                            imagedestroy($dest);
//                                            break;
//                                        case 'image/gif';
//                                            imagedestroy($source);
//                                            @imagejpeg($dest, $fileinfo['basename'], 100);
//                                            imagedestroy($dest);
//                                            break;
//                                        case 'image/png';
//                                            imagedestroy($source);
//                                            @imagepng($dest, $fileinfo['basename'], 9);
//                                            imagedestroy($dest);
//                                            break;
//                                    }
//                                }
//                                $oldLogo = $this->Company->find('first', array('conditions' => array('Company.id' => $id), 'fields' => array('Company.company_logo')));
//                                if(!empty($oldLogo)){
//                                    $oldLogoFile = new File(WWW_ROOT . 'logo'. DS . $oldLogo['Company']['company_logo']);
//                                    $oldLogoFile->delete();
//                                }

                                $this->request->data['Employee']['certificate'] = $fileinfo['basename'];
                            } else {
                                $this->Session->setFlash(__('Certificate upload was not successful. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
            }
            
            
            
             if(isset($this->request->data['Employee']['signature']['error']) && $this->request->data['Employee']['signature']['error'] == 0){
             
                            $file = new File($this->request->data['Employee']['signature']['name'], FALSE);
                            $fileinfo = $file->info();
   
                            if (filesize($this->request->data['Employee']['signature']['tmp_name']) > 5000000){
                                $this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            $fileinfo['basename'] = str_replace(' ', '', $fileinfo['basename']);
                            // if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
                            //     $this->Session->setFlash(__('Signature file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                            //     $this->redirect(array('action' => 'view', $id));
                            // }

                            if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
                                $nameLengthCheck = false;
                                $this->Session->setFlash(__('Signature file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            
                            if(!in_array($fileinfo['extension'], array('png'))){
                                $this->Session->setFlash(__('Signature file type is invalid.Please upload .png image.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            if(!file_exists(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id'])){
                                new Folder(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id'], TRUE, 0755);
                            }
                            
                            $fileinfo['basename'] = "signature.png";
                            $moveLogo = move_uploaded_file($this->request->data['Employee']['signature']['tmp_name'], 
                            Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id']. DS . $fileinfo['basename']); 
                            
                            if($moveLogo){
                                $dir_name = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS;
                                $dir = opendir($dir_name);
                                chdir($dir_name);

                                $imgFile = getimagesize($fileinfo['basename']);
                                $format = $imgFile['mime'];

                                if ($format != '') {
                                    list($width, $height) = $imgFile;
                                    $ratio = $width / $height;
                                    $newheight = 80;
                                    $newwidth = 80 * $ratio;
                                    switch ($format) {
                                        case 'image/png';
                                            $source = imagecreatefrompng($fileinfo['basename']);
                                            break;
                                    }
                                    $dest = imagecreatetruecolor($newwidth, $newheight);
                                    imagealphablending($dest, false);
                                    imagesavealpha($dest, true);
                                    imagecopyresampled($dest, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                                    switch ($format) {
                                        case 'image/png';
                                            imagedestroy($source);
                                            @imagepng($dest, $fileinfo['basename'], 9);
                                            imagedestroy($dest);
                                            break;
                                    }
                                }

                                $this->request->data['Employee']['signature'] = $fileinfo['basename'];
                            } else {
                                $this->Session->setFlash(__('Signature upload was not successful. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
            }

// adding avatar
    if(isset($this->request->data['Employee']['avatar']['error']) && $this->request->data['Employee']['avatar']['error'] == 0){
             
        $file = new File($this->request->data['Employee']['avatar']['name'], FALSE);
        $fileinfo = $file->info();

        if (filesize($this->request->data['Employee']['avatar']['tmp_name']) > 5000000){
            $this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('action' => 'view', $id));
        }

        $fileinfo['basename'] = str_replace(' ', '', $fileinfo['basename']);
        // if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
        //     $this->Session->setFlash(__('avatar file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
        //     $this->redirect(array('action' => 'view', $id));
        // }

        if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
            $nameLengthCheck = false;
            $this->Session->setFlash(__('Avatar file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('action' => 'view', $id));
        }

        
        if(!in_array($fileinfo['extension'], array('png','jpg','jpeg'))){
            $this->Session->setFlash(__('Avatar file type is invalid.Please upload image.'), 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('action' => 'view', $id));
        }

        if(!file_exists(WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->request->data['Employee']['id'])){
            new Folder(WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->request->data['Employee']['id'], TRUE, 0755);
        }
        

        if($fileinfo['extension'] == 'jpg' || $fileinfo['extension'] == 'jpeg'){
            $output_file = WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->request->data['Employee']['id']. DS . '_avatar.png';
            imagepng(imagecreatefromstring(file_get_contents($this->request->data['Employee']['avatar']['tmp_name'])), $output_file); 
            $target_file = $output_file;
            $resized_file = WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->request->data['Employee']['id']. DS . 'avatar.png';   
            $wmax = 250;
            $hmax = 250;
            
            // $this->ak_img_resize($target_file,$resized_file,$wmax,$hmax,'png');
            $this->_CroppedThumbnail($target_file,$resized_file,$wmax,$hmax,'png');
            unlink($target_file);
        }elseif($fileinfo['extension'] == 'png'){
            $fileinfo['basename'] = "avatar.png";
            $moveLogo = move_uploaded_file($this->request->data['Employee']['avatar']['tmp_name'], 
            WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->request->data['Employee']['id']. DS . $fileinfo['basename']);             
            if($moveLogo){
                $dir_name = WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS;
                $dir = opendir($dir_name);
                chdir($dir_name);

                $imgFile = getimagesize($fileinfo['basename']);
                $format = $imgFile['mime'];

                if ($format != '') {
                    list($width, $height) = $imgFile;
                    $ratio = $width / $height;
                    $newheight = 100;
                    $newwidth = 100 * $ratio;
                    switch ($format) {
                        case 'image/png';
                            $source = imagecreatefrompng($fileinfo['basename']);
                            break;
                    }
                    $dest = imagecreatetruecolor($newwidth, $newheight);
                    imagealphablending($dest, false);
                    imagesavealpha($dest, true);
                    imagecopyresampled($dest, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                    switch ($format) {
                        case 'image/png';
                            imagedestroy($source);
                            @imagepng($dest, $fileinfo['basename'], 9);
                            imagedestroy($dest);
                            break;
                    }
                }
            $target_file = WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->request->data['Employee']['id']. DS . $fileinfo['basename'];
            $resized_file = WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->request->data['Employee']['id']. DS . 'avatar.png';
            $wmax = 250;
            $hmax = 250;
            // $this->ak_img_resize($target_file,$resized_file,$wmax,$hmax,'png');
            $this->_CroppedThumbnail($target_file,$resized_file,$wmax,$hmax,'png');
         }           
        } else {
            $this->Session->setFlash(__('Avatar upload was not successful. Please try again.'), 'default', array('class' => 'alert alert-danger'));
            $this->redirect(array('action' => 'view', $id));
        }
}
            $this->request->data['Employee']['avatar'] = '';
            if($this->request->data['Employee']['parent_id'] == '-1')$this->request->data['Employee']['parent_id'] = NULL;
            
            if ($this->Employee->save($this->request->data,false)) {
                $this->Session->setFlash(__('The employee has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)$this->redirect(array('action' => 'view', $id));
                else $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The employee could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Employee.' . $this->Employee->primaryKey => $id));
            $this->request->data = $this->Employee->find('first', $options);
        }
        
        $maritalStatus = array('Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed', 'Separated' => 'Separated', 'Divorced' => 'Divorced', 'Other' => 'Other');
        $branches = $this->Employee->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $designations = $this->Employee->Designation->find('list', array('conditions' => array('Designation.publish' => 1, 'Designation.soft_delete' => 0)));
        $divisions = $this->Employee->Division->find('list', array('conditions' => array('Division.publish' => 1, 'Division.soft_delete' => 0)));
        $departments = $this->Employee->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $this->set(compact('branches', 'divisions', 'designations', 'maritalStatus','departments'));
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->Employee->exists($id)) {
            throw new NotFoundException(__('Invalid employee'));
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
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['Employee']['qualification'] = implode(",", $this->request->data['qualification']);
            $this->request->data['Employee']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['Employee']['modified_by'] = $this->Session->read('User.id');
            $this->request->data['Employee']['modified_by'] = date('Y-m-d H:i:s');

	    
if(isset($this->request->data['Employee']['certificate']['error']) && $this->request->data['Employee']['certificate']['error'] == 0){
             
                            $file = new File($this->request->data['Employee']['certificate']['name'], FALSE);
                            $fileinfo = $file->info();
   
                            if (filesize($this->request->data['Employee']['certificate']['tmp_name']) > 5000000){
                                $this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            $fileinfo['basename'] = str_replace(' ', '', $fileinfo['basename']);
                            // if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
                            //     $this->Session->setFlash(__('Certificate file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                            //     $this->redirect(array('action' => 'view', $id));
                            // }

                            if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
                                $nameLengthCheck = false;
                                $this->Session->setFlash(__('Certificate file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

//                            if(!in_array($fileinfo['extension'], array('gif','jpg','jpe','jpeg','png'))){
//                                $this->Session->setFlash(__('Certificate file type is invalid. Please try again.'), 'default', array('class' => 'alert alert-danger'));
//                                $this->redirect(array('action' => 'view', $id));
//                            }
                            
                            if(!in_array($fileinfo['extension'], array('crt'))){
                                $this->Session->setFlash(__('Certificate file type is invalid. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            if(!file_exists(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id'])){
                                new Folder(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id'], TRUE, 0755);
                            }
                            
                            $fileinfo['basename'] = "pdf.crt";
                            $moveLogo = move_uploaded_file($this->request->data['Employee']['certificate']['tmp_name'], 
                            Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id']. DS . $fileinfo['basename']); 
                            
                            if($moveLogo){
                                $dir_name = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS;
                                $dir = opendir($dir_name);
                                chdir($dir_name);

                                $imgFile = getimagesize($fileinfo['basename']);
                                $format = $imgFile['mime'];

//                                if ($format != '') {
//                                    list($width, $height) = $imgFile;
//                                    $ratio = $width / $height;
//                                    $newheight = 80;
//                                    $newwidth = 80 * $ratio;
//                                    switch ($format) {
//                                        case 'image/jpeg':
//                                            $source = imagecreatefromjpeg($fileinfo['basename']);
//                                            break;
//                                        case 'image/gif';
//                                            $source = imagecreatefromgif($fileinfo['basename']);
//                                            break;
//                                        case 'image/png';
//                                            $source = imagecreatefrompng($fileinfo['basename']);
//                                            break;
//                                    }
//                                    $dest = imagecreatetruecolor($newwidth, $newheight);
//                                    imagealphablending($dest, false);
//                                    imagesavealpha($dest, true);
//                                    imagecopyresampled($dest, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
//
//                                    switch ($format) {
//                                        case 'image/jpeg':
//                                            imagedestroy($source);
//                                            @imagejpeg($dest, $fileinfo['basename'], 100);
//                                            imagedestroy($dest);
//                                            break;
//                                        case 'image/gif';
//                                            imagedestroy($source);
//                                            @imagejpeg($dest, $fileinfo['basename'], 100);
//                                            imagedestroy($dest);
//                                            break;
//                                        case 'image/png';
//                                            imagedestroy($source);
//                                            @imagepng($dest, $fileinfo['basename'], 9);
//                                            imagedestroy($dest);
//                                            break;
//                                    }
//                                }
//                                $oldLogo = $this->Company->find('first', array('conditions' => array('Company.id' => $id), 'fields' => array('Company.company_logo')));
//                                if(!empty($oldLogo)){
//                                    $oldLogoFile = new File(WWW_ROOT . 'logo'. DS . $oldLogo['Company']['company_logo']);
//                                    $oldLogoFile->delete();
//                                }

                                $this->request->data['Employee']['certificate'] = $fileinfo['basename'];
                            } else {
                                $this->Session->setFlash(__('Certificate upload was not successful. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
            }
            
            
            
             if(isset($this->request->data['Employee']['signature']['error']) && $this->request->data['Employee']['signature']['error'] == 0){
             
                            $file = new File($this->request->data['Employee']['signature']['name'], FALSE);
                            $fileinfo = $file->info();
   
                            if (filesize($this->request->data['Employee']['signature']['tmp_name']) > 5000000){
                                $this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            $fileinfo['basename'] = str_replace(' ', '', $fileinfo['basename']);    
                            // if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
                            //     $this->Session->setFlash(__('Signature file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                            //     $this->redirect(array('action' => 'view', $id));
                            // }

                            if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
                                $nameLengthCheck = false;
                                $this->Session->setFlash(__('Signature file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            
                            if(!in_array($fileinfo['extension'], array('png'))){
                                $this->Session->setFlash(__('Signature file type is invalid.Please upload .png image.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            if(!file_exists(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id'])){
                                new Folder(Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id'], TRUE, 0755);
                            }
                            
                            $fileinfo['basename'] = "signature.png";
                            $moveLogo = move_uploaded_file($this->request->data['Employee']['signature']['tmp_name'], 
                            Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->data['Employee']['id']. DS . $fileinfo['basename']); 
                            
                            if($moveLogo){
                                $dir_name = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS;
                                $dir = opendir($dir_name);
                                chdir($dir_name);

                                $imgFile = getimagesize($fileinfo['basename']);
                                $format = $imgFile['mime'];

                                if ($format != '') {
                                    list($width, $height) = $imgFile;
                                    $ratio = $width / $height;
                                    $newheight = 80;
                                    $newwidth = 80 * $ratio;
                                    switch ($format) {
                                        case 'image/png';
                                            $source = imagecreatefrompng($fileinfo['basename']);
                                            break;
                                    }
                                    $dest = imagecreatetruecolor($newwidth, $newheight);
                                    imagealphablending($dest, false);
                                    imagesavealpha($dest, true);
                                    imagecopyresampled($dest, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                                    switch ($format) {
                                        case 'image/png';
                                            imagedestroy($source);
                                            @imagepng($dest, $fileinfo['basename'], 9);
                                            imagedestroy($dest);
                                            break;
                                    }
                                }

                                $this->request->data['Employee']['signature'] = $fileinfo['basename'];
                            } else {
                                $this->Session->setFlash(__('Signature upload was not successful. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
            }

        if ($this->Employee->save($this->request->data)) {
                $this->Session->setFlash(__('The employee has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The employee could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Employee.' . $this->Employee->primaryKey => $id));
            $this->request->data = $this->Employee->find('first', $options);
        }
        
        $maritalStatus = array('Single' => 'Single', 'Married' => 'Married', 'Widowed' => 'Widowed', 'Separated' => 'Separated', 'Divorced' => 'Divorced', 'Other' => 'Other');
        $branches = $this->Employee->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $designations = $this->Employee->Designation->find('list', array('conditions' => array('Designation.publish' => 1, 'Designation.soft_delete' => 0)));
        $divisions = $this->Employee->Division->find('list', array('conditions' => array('Division.publish' => 1, 'Division.soft_delete' => 0)));
        $departments = $this->Employee->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $this->set(compact('branches', 'divisions', 'designations', 'maritalStatus','departments'));
    }

    public function get_employee_email($employeeEmail = null, $id = null) {
        if ($employeeEmail) {
            if ($id) {
                $employeeEmails = $this->Employee->find('all', array('conditions' => array('Employee.office_email' => $employeeEmail, 'Employee.id !=' => $id)));
            } else {
                $employeeEmails = $this->Employee->find('all', array('conditions' => array('Employee.office_email' => $employeeEmail)));
            }
            $this->set('employeeEmails', $employeeEmails);
        }
    }

    public function get_employee_number($employeenumber = null, $id = null) {
        if ($employeenumber) {
            if ($id) {
                $employeenumbers = $this->Employee->find('all', array('conditions' => array('Employee.employee_number' => $employeenumber, 'Employee.id !=' => $id)));
            } else {
                $employeenumbers = $this->Employee->find('all', array('conditions' => array('Employee.employee_number' => $employeenumber)));
            }
            $this->set('employeenumbers', $employeenumbers);
        }
    }

    public function _etni($id = null) {
        $trainings = $this->Employee->TrainingNeedIdentification->find('all', array('conditions' => array('TrainingNeedIdentification.employee_id' => $id)));
        $employeeTrainings = $this->Employee->EmployeeTraining->find('all', array(
            'fields'=>array('EmployeeTraining.id','EmployeeTraining.training_id','EmployeeTraining.publish','EmployeeTraining.soft_delete',
                'Training.id','Training.title','Training.start_date_time','Training.end_date_time'
                ),
            'conditions' => array('EmployeeTraining.employee_id' => $id)));
        $this->set(compact('trainings', 'employeeTrainings'));
    }

    public function delete($id = NULL, $parent_id = NULL) {
        $modelName = $this->modelClass;
        $record = Inflector::underscore($modelName);
        $record = Inflector::humanize($record);
        $this->loadModel('Approval');
        if (!empty($id)) {
	    if($id == $this->Session->read('User.employee_id')){
		$this->Session->setFlash(__('Logged-in user can not delete his own \'Employee Record\'.', 'default', array('class'=>'alert alert-danger')));
	    } else {
		$approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
			foreach($approves as $approve)
			{
			    $approve['Approval']['soft_delete']=1;
			    $this->Approval->save($approve, false);
			}
		$data['id'] = $id;
		$data['soft_delete'] = 1;
                $data['model_action'] = $this->params['action'];
                $data['system_table_id'] = $this->_get_system_table_id();
		$modelName = $this->modelClass;
		$this->$modelName->save($data, false);
		$this->Session->setFlash(__('Selected %s deleted',$record));
	    }
        }
        $this->redirect(array('action' => 'index'));
    }

    public function delete_all($ids = null) {
	$flag = 1;
	$count=0;
	if ($_POST['data'][$this->name]['recs_selected'])
	    $ids = explode('+', $_POST['data'][$this->name]['recs_selected']);

	$modelName = $this->modelClass;
	$this->loadModel('Approval');
	if (!empty($ids)) {

	    foreach ($ids as $id) {
		if (!empty($id)) {
		    if ($id == $this->Session->read('User.employee_id')) {
			$flag = 0;
		    }else{
			$approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
			foreach($approves as $approve)
			{
			    $approve['Approval']['soft_delete']=1;
			    $this->Approval->save($approve, false);
			}
			$data['id'] = $id;
			$data['soft_delete'] = 1;
			$this->$modelName->save($data, false);
			$count++;
		    }
		}
	    }
            
            if(isset($id) && isset( $data['id'])){
                $data['model_action'] = $this->params['action'];
                $data['system_table_id'] = $this->_get_system_table_id();
                $modelName = $this->modelClass;
                $this->$modelName->save($data, false);
            
            }
            
                        }
	if ($flag) {
	    $this->Session->setFlash(__('All selected employees deleted'));
	} else {
	    $this->Session->setFlash(__('Selected %s employees deleted.<br />Logged-in users can not delete their own \'Employee record\'.', $count));
	}
	$this->redirect(array('action' => 'index'));
    }


    public function org_chart($result = array()){
        mkdir(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id'));
        chmod(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id'),0777);
        // chmod(APP."Config/database.php", 0755);
        $employees = $this->Employee->find('threaded',array(
            'conditions'=>array('Employee.publish'=>1),
            'fields'=>array('Employee.id','Employee.name','ParentId.id','ParentId.name','Employee.designation_id','Employee.parent_id','Designation.id','Designation.name'),
            ));
        mkdir(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar');
        chmod(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar',0777);
        foreach ($employees as $employee) {
            if(file_exists(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $employee['Employee']['id'] . DS . 'avatar.png')){
                  // echo $this->Html->image($this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->Session->read('User.employee_id') . DS . 'avatar.png',array('class'=>'img-circle user-image'));
                // echo "As";
              }else{
                try{
                    mkdir(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $employee['Employee']['id'] . DS );
                    chmod(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $employee['Employee']['id'],0777);
                }catch(Exception $e){
                    // echo "failed!";
                }
                $file = WWW_ROOT . 'img' . DS . 'img' . DS . 'avatar.png';
                $newfile = WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $employee['Employee']['id'] . DS . 'avatar.png';

                if (!copy($file, $newfile)) {
                    // echo "failed to copy $file...\n";
                }
              }

            if(!empty($employee['children'])){
                $result[] = array('id'=>$employee['Employee']['id'], 'name'=>$employee['Employee']['name'],'title'=>$employee['Designation']['name'], 'className'=>'top-level', 'children' => $this->renderPosts($employee['children'],$result));                
            }else{
                $result[] = array($employee['Employee']['name'], 'name'=>$employee['Employee']['name'],'title'=>$employee['Designation']['name'],'className'=>'top-level',);
            }
        }
        $this->set('employees_orgchart',$result);
    }

    public function renderPosts($employeesArray, $tmpModel){
        if(!isset($return)){ $return = array(); }
            foreach ($employeesArray as $child_employee){
                if(file_exists(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $child_employee['Employee']['id'] . DS . 'avatar.png')){
                  // echo $this->Html->image($this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->Session->read('User.employee_id') . DS . 'avatar.png',array('class'=>'img-circle user-image'));                
              }else{
                try{
                    mkdir(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $child_employee['Employee']['id'] . DS );
                }catch(Exception $e){
                    echo "failed!";
                }
                $file = WWW_ROOT . 'img' . DS . 'img' . DS . 'avatar.png';
                $newfile = WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $child_employee['Employee']['id'] . DS . 'avatar.png';

                if (!copy($file, $newfile)) {
                    // echo "failed to copy $file...\n";
                }
              }

                if(!empty($child_employee['children'])){            
                    $children['id'] = $child_employee['Employee']['id'];
                    $children['className'] = 'middle-level';
                    $children['name'] = $child_employee['Employee']['name'];
                    $children['title'] = $child_employee['Designation']['name'];
                    $children['children'] = $this->renderPosts($child_employee['children'],$tmpModel);
                    $return[] = $children;
                }else{
                    $return[] = array('id'=>$child_employee['Employee']['id'],'name'=>$child_employee['Employee']['name'],'title'=>$child_employee['Designation']['name'],'className'=>'middle-level');
                }            
        }
        return $return;
    }
    
    public function ak_img_resize($target, $newcopy, $w, $h, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $scale_ratio = $h_orig / $w_orig;
        if (($w / $h) > $scale_ratio) {
               $w = $h * $scale_ratio;
        } else {
               $h = $w / $scale_ratio;
        }
        $img = "";
        $ext = strtolower($ext);
        if ($ext == "gif"){ 
          $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
          $img = imagecreatefrompng($target);
        } else { 
          $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        imagejpeg($tci, $newcopy, 100);
    }

    public function _CroppedThumbnail($imgSrc,$newcopy, $thumbnail_width,$thumbnail_height,$ext) { //$imgSrc is a FILE - Returns an image resource.
        list($width_orig, $height_orig) = getimagesize($imgSrc);   
        $myImage = imagecreatefrompng($imgSrc);
        $ratio_orig = $width_orig/$height_orig;
        
        if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
           $new_height = $thumbnail_width/$ratio_orig;
           $new_width = $thumbnail_width;
        } else {
           $new_width = $thumbnail_height*$ratio_orig;
           $new_height = $thumbnail_height;
        }
        $x_mid = $new_width/2;  //horizontal middle
        $y_mid = $new_height/2; //vertical middle
        
        $process = imagecreatetruecolor(round($new_width), round($new_height)); 
        
        imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
        $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
        imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

        imagedestroy($process);
        imagedestroy($myImage);
        imagejpeg($thumb, $newcopy, 100);
        return true;
    }

    public function update_parent($parent_id = null,$id = null ){
        $this->autoRender = false;
        $employee = $this->Employee->find('first',array('conditions'=>array('Employee.id'=>$id),'recursive'=>-1));
        if($employee){
            $data['Employee'] = $employee['Employee'];
            $data['Employee']['parent_id'] = $parent_id;
            $this->Employee->create();
            if($this->Employee->save($data,false)){
                echo "Parent id added";
            }else{
                echo "Parent id could not be added. Please try again.";
            }
        }else{
            echo "Incorrect employee";
        }
        exit;
    }

    public function addusers(){
        // Configure::write('debug',1);
        $this->loadModel('User');
        $employees = $this->Employee->find('all',array(
                'conditions'=>array(),
                'recursive'=>-1,
                // 'limit'=>10,
                'fields'=>array('Employee.id','Employee.department_id','Employee.branch_id','Employee.name'),
            ));
        // echo "<table border='1'>";
        // echo "<tr><th>Employee</th><th>Username</th>";
        foreach ($employees as $employee) {
            $finduser = $this->User->find('first',array(
                'recursive'=>-1,
                'fields'=>array('User.name','User.username','User.employee_id'),
                'conditions'=>array('User.employee_id'=>$employee['Employee']['id'])));
            debug($finduser);
            if(!$finduser){
                $user = array();
                $username = str_replace('Dr.', '', $employee['Employee']['name']);
                $username = str_replace('Mr.', '', $username);
                $username = str_replace('Ms.', '', $username);
                $username = str_replace('Mrs.', '', $username);
                $username = str_replace(' ', '', $username);
                $username = str_replace('.', '', $username);
                $username = strtolower($username);
                // echo "<tr><td>" . $value . "</td><td>" . $username .  "</td></tr>";
                $user['employee_id'] = $employee['Employee']['id'];
                $user['name'] = $employee['Employee']['name'];
                $user['username'] = $username;
                $user['password'] = 'ac163edb81a69fa9f847a6a9346b5487';
                $user['is_mr'] = 0;
                $user['is_view_all'] = 0;
                $user['is_approvar'] = 0;
                $user['status'] = 1;
                if(!$employee['Employee']['department_id']){
                    $employee['Employee']['department_id'] = -1;
                    $user['department_id'] = $employee['Employee']['department_id'];
                }
                $user['branch_id'] = $employee['Employee']['branch_id'];               
                $user['login_status'] = 0;
                $user['allow_multiple_login'] = 0;
                $user['limit_login_attempt'] = 3;
                $user['benchmark'] = 10;
                $user['publish'] = 1;
                $user['soft_delete'] = 0;
                $user['agree'] = 1;
                $user['assigned_branches'] = "[".$employee['Employee']['branch_id']."]";
                $user['company_id'] = '5297b2e7-0a9c-46e3-96a6-2d8f0a000005';
                $user['system_table_id'] = '56044715-be6c-4298-8678-03e1db1e6cf9';
                $user['language_id'] = '366ac1f4-199b-11e3-9f46-c709d410d2ec';
                $user['copy_acl_from'] = -1;
                $user['created_by'] = $user['prepared_by'] = $user['approved_by'] = '56044715-6bb8-49bd-85f2-03e1db1e6cf9';
                $user['master_list_of_format_id'] = '523ae34c-bcc0-4c7d-b7aa-75cec6c3268c';
                debug($user);
                $checkusername = $this->User->find('first',array(
                    'recursive'=>-1,
                    'fields'=>array('User.name','User.username','User.employee_id'),
                    'conditions'=>array('User.username'=>$username)));
                if(!$checkusername){
                    $this->User->create();
                    $this->User->save($user,false);    
                }
                
            }
        }
        // echo "</table>";
        exit;
    }

    public function imp(){
// $emps = 
// "chandram,      A.Karishma Rani Patra,          Production Executive,   Chandra Sekhar;
// akashna,        Akash Vilas Nagvekar,           Production Executive,   Chandra Sekhar;
// anilas,         Anila Kumar Sahu,               Team Lead,              Npattnaik;
// ashitoshga,     Ashitosh Lalasaheb Gaikwad,     Production Executive,   Chandra Sekhar;
// Bindiam,        Bindia Mishra,                  Production Executive,   Chandra Sekhar;
// chandram,       Chandra Sekhar,                 Team Lead,              Npattnaik;
// chevvakulak,    Ch Kishore,                     QC Executive,           Chandra Sekhar; 
// chinmayp,       Chinmay Kumar Patanayak,        QC Executive,           Chandra Sekhar;
// devalo,         Deva Lomate,                    Production Executive,   Chandra Sekhar;
// devanathanv,    Devanathan v,                   QC Executive,           Chandra Sekhar;
// jagadishas,     Jagadisha Sahu,                 Production Executive,   Chandra Sekhar;
// kunapareddyk,   Ravi,                           QC Executive,           Chandra Sekhar;
// mamtav,         Mamta Verma,                    Production Executive,   Chandra Sekhar;
// manojc,         Manoj s. Choundaj,              Team Lead,              Chandra Sekhar;
// Monalid,        Monali Das,                     Production Executive,   Chandra Sekhar;
// narayanna,      Narayan Nayak,                  Production Executive,   Chandra Sekhar;
// Neelamsa,       Neelam Amardas Saroj,           Production Executive,   Chandra Sekhar;
// nilanchals,     Nilanchal Sahu,                 Production Executive,   Chandra Sekhar;
// nileshm,        Nilesh Mishra,                  Production Executive,   Chandra Sekhar;
// paramanandam,   Paramananda Maharana,           Team Lead,              Npattnaik;
// ramuk,          Ramu,                           QC Executive,           Chandra Sekhar;
// sameerna,       Sameer Nayak,                   QC Executive,           Chandra Sekhar;
// sharanurp,      Sharanu. r. Pateel,             QC Executive,           Chandra Sekhar;
// shubhamka,      Shubham Rajendra Kadam,         Production Executive,   Chandra Sekhar;
// sradhanjalip,   Sradhanjali Panda,              Production Executive,   Chandra Sekhar;
// Swapneswarip,   Swapneswari Panda,              Production Executive,   Chandra Sekhar;
// vaibhavde,      Vaibhav Laxman Desai,           Production Executive,   Chandra Sekhar;
// varnakaviv,     Sanditya,                       QC Executive,           Chandra Sekhar;
// vinodkum,       Vinod Bapu Kumbhar,             Production Executive,   Chandra Sekhar;
// nityanandp,     NP,                             Project Manager,        Pmenon;";
$x = 50;

$emps = "
Ramsing Katre,GI2385,GI2385,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,57459fb5-8430-43b5-ad27-05b2db1e6cf9,2016-05-15,1995-01-05,7977670801,ramsingh.katre@igenesys.com,1,1;
Anila Shau,GI2343,GI2343,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,52979a51-6660-493f-aff6-26500a000005,2016-01-20,1988-07-14,9372013686,anilas@email.igenesys.com,1,1;
Mamta Verma,GW4079,GW4079,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,57459fb5-8430-43b5-ad27-05b2db1e6cf9,2021-08-16,1993-08-14,9121867401,mamtav@email.igenesys.com,1,1;
Sudipta Kundu,GW4137,GW4137,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2022-01-03,1996-03-18,9733830999,sudiptak@email.igenesys.com,Single,1,1;
Arya Singh,GW4138,GW4138,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2022-01-06,1998-08-02,7970648581,aryas@email.igenesys.com,1,1;
Grishma Mehta,GI3096,GI3096,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2022-05-02,1992-10-07,7878384940,grishmam@email.igenesys.com,1,1;
Samir Vypari,GW1436,GW1436,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,57459fb5-8430-43b5-ad27-05b2db1e6cf9,2012-06-01,1975-07-21,9833249495,samirv@email.igenesys.com,1,1;
Sachin Turde,GW1037,GW1037,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,57459fb5-8430-43b5-ad27-05b2db1e6cf9,2012-01-06,1981-02-22,9869801934,Sachint@email.igenesys.com,1,1;
Vivek Murugesan,GI3097,GI3097,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,57459fb5-8430-43b5-ad27-05b2db1e6cf9,2022-05-02,1994-07-21,9042270110,vivekmu@email.igenesys.com,1,1;
Sisir Kumar Jena,GI2625,GI2625,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2018-01-18,1996-06-06,9137475219,sisirkj@email.igenesys.com,Single,1,1;
Deeptirani Das,GW4076,GW4076,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2021-10-08,1996-07-31,8249323423,deeptiranid@email.igenesys.com,Single,1,1;
Subham Kumar Dash,GW4077,GW4077,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2021-08-12,1995-04-11,8457909612,Shubhamkd@gmail.igenesys.com,1,1;
Rajesh Raja,GI3105,GI3105,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2022-02-05,1991-07-20,9916385363,rajeshra@email.igenesys.com,1,1;
Jamil Jafar,GI3087,GI3087,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2022-04-25,1994-02-21,8637419449,jamilj@email.igenesys.com,1,1;
B kanha Dora,GW4075,GW4075,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2021-08-06,1996-03-29,9082508247,bkanha@email.igenesys.com,Single,1,1;
Prasanta Behera,GW4080,GW4080,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,57459fb5-8430-43b5-ad27-05b2db1e6cf9,2021-09-16,1988-04-30,9372566806,prasantab@email.igenesys.com,1,1;
Dillip Kumar Panda,GW4078,GW4078,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2021-08-12,1991-03-04,8328979890,dilipkp@email.igenesys.com,MARRIED,1,1;
Sasmita Sahu,GI3088,GI3088,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-4584-4643-a2c7-9d9cdb1e6cf9,2022-04-25,1995-06-20,9827610901,sasmitasa@email.igenesys.com,married,1,1;
C.Somasekar,GI3094,GI3094,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2022-05-02,1988-05-23,9843795443,somasekarc@email.igenesys.com,Married,Yes,Active,251,,,,
Dinesh Kumar Gouda,GW4074,GW4074,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2021-03-08,1991-05-05,8618078709,dineshkg@email.igenesys.com,Single,1,1;
Vishal Koli,GI2901,GI2901,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2022-02-01,1996-08-24,8390462516,vishalko@email.igenesys.com,Single,1,1;
kalpana swain,GW3621,GW3621,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,57459fb5-8430-43b5-ad27-05b2db1e6cf9,2019-04-15,1992-04-13,9321572097,kalpanas@email.igenesys.com,1,1;
Sital Sahu,GW2425,GW2425,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2017-11-04,1996-04-18,7205925716,sitals@email.igenesys.com,Single,1,1;
Champeswar pradhan,GW4075,GW4075,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,62de7b2d-9d6c-464b-aa66-2bd4ac100145,58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9,2017-04-20,1994-11-05,8355818098,champeswarp@gmail.igenesys.com,1,1;";

// "Garima Shukla,             GW2546,  52979a51-6660-493f-aff6-26500a000005, garimas@email.igenesys.com,       523a0abb-21e0-4b44-a219-6142c6c32689;
// Dnyaneshwar Bandagar,       GI971,   5a7978f5-65f4-4db5-8cd7-0205db1e6cf9, dnyaneshwarb@email.igenesys.com,  523a0abb-21e0-4b44-a219-6142c6c32689;
// Sharmila Sonawalkar,        GW3171,  57459fb5-8430-43b5-ad27-05b2db1e6cf9, sharmilas@email.igenesys.com,     523a0abb-21e0-4b44-a219-6142c6c32689;
// Rohit Rajkumar Koli ,       GI2508,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, rohitrk@email.igenesys.com,       523a0abb-21e0-4b44-a219-6142c6c32689;
// Shraddha Vilas Thakur,      GW2837,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, shraddhat@email.igenesys.com,     523a0abb-21e0-4b44-a219-6142c6c32689;
// Ganesh Venkatesh Kongati,   GI2659,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, ganeshvk@email.igenesys.com,      523a0abb-21e0-4b44-a219-6142c6c32689;
// Mahesh Rajendra Pawar,      GW3634,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, maheshrajpa@email.igenesys.com,   523a0abb-21e0-4b44-a219-6142c6c32689;
// Kunal Mahadev Chhapre,      GI2780,  58a4a59f-4584-4643-a2c7-9d9cdb1e6cf9, kunalmchh@email.igenesys.com,     523a0abb-21e0-4b44-a219-6142c6c32689;
// Abrar Hiroli,               GI2867,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, abrarh@email.igenesys.com,        523a0abb-21e0-4b44-a219-6142c6c32689;
// Mohd Akmal Mohd Akram,      GW3596,  57459fb5-8430-43b5-ad27-05b2db1e6cf9, mohdama@email.igenesys.com,       523a0abb-21e0-4b44-a219-6142c6c32689;
// Sibgatullah Iqbal Vasta,    GW3665,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, sibgatullahiv@email.igenesys.com, 523a0abb-21e0-4b44-a219-6142c6c32689;
// Santosh Devidas Phalke,     GW3588,  58a4a59f-4584-4643-a2c7-9d9cdb1e6cf9, santoshdevidp@email.igenesys.com, 523a0abb-21e0-4b44-a219-6142c6c32689;
// Namita Namdev Shinde,       GW2943,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, namitash@email.igenesys.com,      523a0abb-21e0-4b44-a219-6142c6c32689;
// Ankita Rajabhau Ghudaji,    GW3156,  58a4a59f-4584-4643-a2c7-9d9cdb1e6cf9, ankitag@email.igenesys.com,       523a0abb-21e0-4b44-a219-6142c6c32689;
// Omkar Ravindra Ghorpade,    GI2601,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, omkargh@email.igenesys.com,       523a0abb-21e0-4b44-a219-6142c6c32689;
// Hemant Thakur,              GI626,   57459fb5-8430-43b5-ad27-05b2db1e6cf9, hemantt@email.igenesys.com,       523a0abb-21e0-4b44-a219-6142c6c32689;
// Kiran M Patil,              GI1294,  57459fb5-8430-43b5-ad27-05b2db1e6cf9, kiranp@email.igenesys.com,        523a0abb-21e0-4b44-a219-6142c6c32689;
// Yogesh Raul,                GI2187,  57459fb5-8430-43b5-ad27-05b2db1e6cf9, yogeshr@email.igenesys.com,       523a0abb-21e0-4b44-a219-6142c6c32689;
// Harshali Sanjay Dhobale,    GW2666,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, harshalid@email.igenesys.com,     523a0abb-21e0-4b44-a219-6142c6c32689;
// Hina Chavan,                GW2295,  58a4a59f-224c-4f01-9b47-9d9cdb1e6cf9, hinac@email.igenesys.com,         523a0abb-21e0-4b44-a219-6142c6c32689;";

        Configure::write('debug',1);
        $es = explode(','.'1;', $emps);
        debug($es);
        // exit;
        foreach ($es as $e) {
            $emp = split(',', $e);

            // debug($emp);

            $ee['name'] = rtrim(ltrim($emp['0']));
            $ee['employee_number'] = $ee['identification_number'] = rtrim(ltrim($emp['1']));
            $x++;
            $ee['branch_id'] = rtrim(ltrim($emp['3']));
            $ee['department_id'] = rtrim(ltrim($emp['4']));
            $ee['designation_id'] = rtrim(ltrim($emp['5']));

            // if(rtrim(ltrim($emp['2'])) == 'Team Lead')$des = '52979a51-6660-493f-aff6-26500a000005';
            // if(rtrim(ltrim($emp['2'])) == 'Production Executive')$des = '5f679f59-2ee0-4221-8542-da19db1e6cf9';
            // if(rtrim(ltrim($emp['2'])) == 'QC Executive')$des = '5f679f8f-befc-4436-bb4e-e455db1e6cf9';
            // if(rtrim(ltrim($emp['2'])) == 'Project Manager')$des = '52979a51-e380-4c21-800e-26500a000005';


            // $ee['designation_id'] = rtrim(ltrim($emp['2']));
            $ee['office_email'] = rtrim(ltrim($emp['9']));
            $ee['employee_status'] = 1;
            $ee['is_approvar'] = $ee['is_mr'] = 1;
            $ee['publish'] = 1;
            $ee['soft_delete'] = 0;
            $ee['resource_cost'] = 5000;
            // $ee['name'] = rtrim(ltrim($emp['']));
            // $ee['name'] = rtrim(ltrim($emp['']));
            debug($ee);
            $ex = $this->Employee->find('count',array('conditions'=>array('Employee.employee_number'=>$ee['employee_number'])));
            if($ee['name'] && $ex == 0){
                $this->Employee->create();
                $this->Employee->save($ee,false);
            }
        }
    exit;
    }


    public function created(){
        $curl = curl_init();
        // Configure::write('debug',1);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://genesysapi.hrone.cloud/api/EmployeeMgmt/EmployeeMgmt/GetEmployeeFixedColumn",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "Accesskey=VjkazdQFewLDFGTr~genesys&createdDateFrom=10%2F02%2F2019&createdDateTo=10%2F03%2F2020",
          CURLOPT_HTTPHEADER => array(
            "authorization: VjkazdQFewLDFGTr~genesys",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "domaincode: genesys",
            "postman-token: ab3f53c9-7d02-9a44-455b-66ea205400ec"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
            // debug($response);
            $response = json_decode($response,true);
            // debug($response['responseResult']);
          foreach ($response['responseResult'] as $employee) {
            // debug($employee);
            $this->import_employee($employee);
          }
          

        }

        exit;
    }


// 10 %2F 01 %2F 2010

    public function updated($start_date = null,$end_date = null){

        // $start_date = str_replace('-','%2F',date('d-m-Y'));
        // $end_date = str_replace('-','%2F',date('d-m-Y',strtotime('-90 day')));

        $end_date = date('d/m/Y');
        $start_date = date('d/m/Y',strtotime('-360 day'));

        // Configure::write('debug',1);
        debug($start_date);
        debug($end_date);
        // exit;
        $curl = curl_init();
        // Configure::write('debug',1);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://genesysapi.hrone.cloud/api/EmployeeMgmt/EmployeeMgmt/GetEmployeeFixedColumn",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "Accesskey=VjkazdQFewLDFGTr~genesys&UpdatedDateFrom=".$start_date."&UpdatedDateTo=".$end_date,
          CURLOPT_HTTPHEADER => array(
            "authorization: VjkazdQFewLDFGTr~genesys",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "domaincode: genesys",
            "postman-token: da327067-20c9-e48c-5ee4-cbe7ede7e7c3"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $response = json_decode($response,true);          
            debug($response);
          foreach ($response['responseResult'] as $employee) {
            debug($employee);
              $this->import_employee($employee);
          }
        }
        exit;
    }

    public function bycode($code = null){
        // Configure::write('debug',1);
        $curl = curl_init();
        $code = $this->request->params['pass'][0];
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://genesysapi.hrone.cloud/api/EmployeeMgmt/EmployeeMgmt/GetEmployeeFixedColumn",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "Accesskey=VjkazdQFewLDFGTr~genesys&employeeCode=" . $code,
          CURLOPT_HTTPHEADER => array(
            "authorization: VjkazdQFewLDFGTr~genesys",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "domaincode: genesys",
            "postman-token: 23f61fa6-7de9-f3fc-49d5-be0a52ae6ec9"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          // debug($response);
          $employee = json_decode($response,true);
          // debug($employee);
          $empChk = $this->Employee->find('first',array('recursive'=>-1, 'fields'=>array('Employee.id','Employee.employee_number'), 'conditions'=>array('Employee.employee_number'=>$employee['responseResult'][0]['employeeCode'])));

          if($empChk){
                return $empChk['Employee']['id'];
            }else{            
                $id = $this->import_employee($employee['responseResult'][0]);
                return $id;
            }
        }
        // exit;
    }


    public function empj(){
        $str = '
{
    "responseResult": [
        {
            "employeeCode": "GW2478",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Prasad Vivek Ganpat Gandhi",
            "firstName": "Prasad",
            "middleName": "Vivek Ganpat",
            "lastName": "Gandhi",
            "mobileNo": "8149299573",
            "reportingManagerCode": "GI166",
            "reportingManagerName": "Sachin Shankar Pawar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "02/05/2017",
            "dateofBirth": "03/09/1989",
            "employeeStatus": "Confirmed",
            "fatherName": "Vivek Ganpat Gandhi",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "prasadg@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "29/10/2017",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "786, Rajapurkar Colony, Udyam Nagar Ratnagiri Maharahstra 415612",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "415612",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI166",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AWAPG7847B",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "06/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3708",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Vijaykumar Ramprem Gupta",
            "firstName": "Vijaykumar",
            "middleName": "Ramprem",
            "lastName": "Gupta",
            "mobileNo": "8652554756",
            "reportingManagerCode": "GI1166",
            "reportingManagerName": "Yogesh Sitaram Pawar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "18/03/2019",
            "dateofBirth": "15/06/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Ramprem",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "vijaykumarramg@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "14/10/2019",
            "currentAddress": "Room No 2930/0027 Midc Road, Yadav Nagar, Navi Mumbai, Airoli, Airoli, Digha Thane, Maharashtra 400708",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400708",
            "permanentAddress": "Room No 2930/0027 Midc Road, Yadav Nagar, Navi Mumbai, Airoli, Airoli, Digha Thane, Maharashtra 400708",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400708",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1166",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BTPPG0504C",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3894",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Anil Popat Dange",
            "firstName": "Anil",
            "middleName": "Popat",
            "lastName": "Dange",
            "mobileNo": "9518312335",
            "reportingManagerCode": "GW1147",
            "reportingManagerName": "Vishnu Uttamrao Wankhede",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "18/04/2019",
            "dateofBirth": "16/06/1994",
            "employeeStatus": "Confirmed",
            "fatherName": "Popat",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "anilpod@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "15/10/2019",
            "currentAddress": "308 Building No 05, Sariput Nagar, Andheri East, Mumbai, Maharashtra 400065",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400065",
            "permanentAddress": "At. Post Shivarai, Shivarai, Shivrai, Aurangabad, Maharashtra - 423703",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "423703",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1147",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "DBZPD7709E",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD740",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Rahul Sureshanand Goswami",
            "firstName": "Rahul",
            "middleName": "Sureshanand",
            "lastName": "Goswami",
            "mobileNo": "8936979782",
            "reportingManagerCode": "GW2522",
            "reportingManagerName": "Shiva Lalitha Rajappan",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Apprentice Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "21/11/2018",
            "dateofBirth": "20/11/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Sureshanand",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "rahulg@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/12/2018",
            "currentAddress": "Nehrugram P.O. Shantikunj Lane No.2 , H.No. 46, Dehradun -248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Nehrugram P.O. Shantikunj Lane No.2 , H.No. 46, Dehradun -248001",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW2522",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3786",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Reshmi Yadav",
            "firstName": "Reshmi",
            "middleName": "",
            "lastName": "Yadav",
            "mobileNo": "9532219182",
            "reportingManagerCode": "GW1283",
            "reportingManagerName": "Sameer Gopalrao Shinde",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Telecom",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "01/04/2019",
            "dateofBirth": "12/05/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Ramdeen Yadav",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "reshmiy@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "28/09/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "D/O Ramdeen Yadav, Oran Rod, Oran Road, Musanagar, Atarra, Banda, Uttar Pradesh - 210201",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "210201",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1283",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2416",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sarbjit Jarnail Singh Multani",
            "firstName": "Sarbjit",
            "middleName": "Jarnail Singh",
            "lastName": "Multani",
            "mobileNo": "9167449422",
            "reportingManagerCode": "GI1190",
            "reportingManagerName": "Mustafa Bashu Shaikh",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Assistant Project Manager",
            "department": "Support",
            "subDepartment": "ITD",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "06/09/2016",
            "dateofBirth": "30/04/1982",
            "employeeStatus": "Confirmed",
            "fatherName": "Jarnail Singh Multani",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Sarbjit.Multani@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "05/03/2017",
            "currentAddress": "E-52, Saptarishi Shrishti Sector, R2 Mira Road East, Thane-401107",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "401107",
            "permanentAddress": "E-52, Saptarishi Shrishti Sector, R2 Mira Road East, Thane-401107",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "401107",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1190",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AOAPM1387E",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2807",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Kiran Kailas Bagale",
            "firstName": "Kiran",
            "middleName": "Kailas",
            "lastName": "Bagale",
            "mobileNo": "9397840210",
            "reportingManagerCode": "GI1166",
            "reportingManagerName": "Yogesh Sitaram Pawar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "25/03/2019",
            "dateofBirth": "02/11/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Kailas",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "kirankaibag@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "21/10/2019",
            "currentAddress": "D/O Kailas Tapiram Bangali, Plot No 19 A, Aayodhya Nagar, Near Bangali, File, Amalner, Amalner, Jalgaon, Maharashtra 425401",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "425401",
            "permanentAddress": "D/O Kailas Tapiram Bangali, Plot No 19 A, Aayodhya Nagar, Near Bangali, File, Amalner, Amalner, Jalgaon, Maharashtra 425401",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "425401",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1166",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CACPB2807G",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2492",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Vijendra Shankarlal Kumawat",
            "firstName": "Vijendra",
            "middleName": "Shankarlal",
            "lastName": "Kumawat",
            "mobileNo": "9649507249",
            "reportingManagerCode": "GI1447",
            "reportingManagerName": "Rajesh Ramchandra More",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "CG_Bharat Net",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "02/05/2017",
            "dateofBirth": "08/01/1991",
            "employeeStatus": "Confirmed",
            "fatherName": "Shankarlal Kumawat",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "vijendraku@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "29/10/2017",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vpa Guru Th Udaipurwati Dist Jhunjhunu Rajasthan 333053",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "333053",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1447",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BYUPK2076F",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD850",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Shelly Gyan Singh Rawat",
            "firstName": "Shelly",
            "middleName": "Gyan Singh",
            "lastName": "Rawat",
            "mobileNo": "7409429177",
            "reportingManagerCode": "GWD576",
            "reportingManagerName": "Naval Kishore Pant",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "20/12/2018",
            "dateofBirth": "27/03/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Gyan Singh Rawat",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "shellyr@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "292/354 Old Dalanwala",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD576",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "EBDPR8361F",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD673",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Dharmendra Kedar Singh",
            "firstName": "Dharmendra",
            "middleName": "Kedar",
            "lastName": "Singh",
            "mobileNo": "7895922279",
            "reportingManagerCode": "GWD718",
            "reportingManagerName": "Vishal Ranjeet Singh Bhandari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "17/09/2018",
            "dateofBirth": "05/03/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Kedar Singh",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "dharmendrasing@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/11/2018",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Matholi Po-Tiphri, Uttarkashi,Uttarakhand, 249151",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD718",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "GVKPS6139D",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2503",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Pravin Prabhakar Randive",
            "firstName": "Pravin",
            "middleName": "Prabhakar",
            "lastName": "Randive",
            "mobileNo": "7045259939",
            "reportingManagerCode": "GI2861",
            "reportingManagerName": "Nitin Vithal Kamble",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "03/05/2017",
            "dateofBirth": "08/09/1983",
            "employeeStatus": "Confirmed",
            "fatherName": "Prabhakar Randive",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "pravinran@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "30/10/2017",
            "currentAddress": "A/503, Siddhivinayak Sra Chs, Jamil Nagar, Bhandup West Mumbai Maharashtra 400078",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400078",
            "permanentAddress": "A/503, Siddhivinayak Sra Chs, Jamil Nagar, Bhandup West Mumbai Maharashtra 400078",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400078",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2861",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AJZPR0446F",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3965",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Amaan Shakil Hunerkar",
            "firstName": "Amaan",
            "middleName": "Shakil",
            "lastName": "Hunerkar",
            "mobileNo": "9075189746",
            "reportingManagerCode": "GI280",
            "reportingManagerName": "Manoj Shantaram Hatimkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/06/2019",
            "dateofBirth": "12/11/2000",
            "employeeStatus": "Confirmed",
            "fatherName": "Shakil",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "amaansha@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "28/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "588, Rabbani Mohalla, Shirgaon, Ratnagiri, Maharashtra - 415629",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI280",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BBTPH1206E",
            "createdDate": "13/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3617",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Mangesh Manohar Durge",
            "firstName": "Mangesh",
            "middleName": "Manohar",
            "lastName": "Durge",
            "mobileNo": "9960099353",
            "reportingManagerCode": "GI1190",
            "reportingManagerName": "Mustafa Bashu Shaikh",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Project Manager",
            "department": "Support",
            "subDepartment": "ITD",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "11/02/2019",
            "dateofBirth": "21/05/1973",
            "employeeStatus": "Relieved",
            "fatherName": "Manohar",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "GW3617@test.com",
            "personalEmail": "",
            "noticePeriod": 60,
            "dateofConfirmation": "10/08/2019",
            "currentAddress": "C/O Anant Durge Ci710 Raj Splendour Vikhroli West Mumbai Maharashtra 400096",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "S/O Manohar Durge, Near Ganesh Temple I/10, Tatya Tope Nagar, Nagpur, Ranapratap Nagar,Ranapratap Nagar Nagpur Nagpur Maharashtra 440022",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1190",
            "bioMetricId": "",
            "dateofLeaving": "19/06/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "31/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "18/06/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3942",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Priyanka Vasudev Koli",
            "firstName": "Priyanka",
            "middleName": "Vasudev",
            "lastName": "Koli",
            "mobileNo": "9604188389",
            "reportingManagerCode": "GI280",
            "reportingManagerName": "Manoj Shantaram Hatimkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "08/05/2019",
            "dateofBirth": "18/04/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Vasudev Koli",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "priyankavk@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "04/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Dhavalves, Shala No13 Javal, Tasgaon, Tasgaon M.D.G, Sangli, Maharashtra - 416312",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI280",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3901",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Subham Patel",
            "firstName": "Subham",
            "middleName": "",
            "lastName": "Patel",
            "mobileNo": "9937782426",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "25/04/2019",
            "dateofBirth": "15/06/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Nimain Charan Patel",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "subhampa@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "22/10/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Deuli, Bhalugad, Deuli, Sundargarh, Odisha, 770019",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CRUPP6447D",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3952",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Umesh Devaji Kokare",
            "firstName": "Umesh",
            "middleName": "Devaji",
            "lastName": "Kokare",
            "mobileNo": "8087719938",
            "reportingManagerCode": "GI2861",
            "reportingManagerName": "Nitin Vithal Kamble",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/06/2019",
            "dateofBirth": "10/11/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Devaji",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "umeshdk@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "28/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "AT Jummaptti, Post Neral, Tel Karjat, Dist Raigad, Maharashtra 410101",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI2861",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CZMPK4644M",
            "createdDate": "13/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2253",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Priyanka Satya Raman",
            "firstName": "Priyanka",
            "middleName": "Satya",
            "lastName": "Raman",
            "mobileNo": "7385935905",
            "reportingManagerCode": "GW3670",
            "reportingManagerName": "Kalpesh Pradyumna Gor",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Team Lead",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Team Manager",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "19/12/2016",
            "dateofBirth": "09/09/1992",
            "employeeStatus": "Relieved",
            "fatherName": "Satya",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "GW2253@test.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "17/06/2017",
            "currentAddress": "Plot No-5, Sahas Colony, Pratap Nagar, Nagpur, Maharashtra-440022",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Plot No-5, Sahas Colony, Pratap Nagar, Nagpur, Maharashtra-440022",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW3670",
            "bioMetricId": "",
            "dateofLeaving": "07/06/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "23/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "07/06/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2694",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Nitin Kumbhar",
            "firstName": "Nitin",
            "middleName": "",
            "lastName": "Kumbhar",
            "mobileNo": "9702768796",
            "reportingManagerCode": "GW3670",
            "reportingManagerName": "Kalpesh Pradyumna Gor",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "17/07/2017",
            "dateofBirth": "29/09/1972",
            "employeeStatus": "Relieved",
            "fatherName": "Jyotsna",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "GW2694@test.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "13/01/2018",
            "currentAddress": "2/15, Krishnabai, Niwas, Ekveera Devi Road, Tembi Pada, Bhandup West Mumbai Maharashtra",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "2/15, Krishnabai, Niwas, Ekveera Devi Road, Tembi Pada, Bhandup West Mumbai Maharashtra",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "N",
            "nationality": "",
            "reportingManager": "GW3670",
            "bioMetricId": "",
            "dateofLeaving": "17/05/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "10/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "17/05/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI497",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Navnath Tukaram Veer",
            "firstName": "Navnath",
            "middleName": "Tukaram",
            "lastName": "Veer",
            "mobileNo": "23445",
            "reportingManagerCode": "GI365",
            "reportingManagerName": "Sagar Shashikant Patil",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Project Manager",
            "department": "Production",
            "subDepartment": "MCGM/Wonobo",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Manager",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "20/03/2006",
            "dateofBirth": "05/06/1976",
            "employeeStatus": "Confirmed",
            "fatherName": "Tukaram",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Navnath.Veer@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 60,
            "dateofConfirmation": "12/09/2006",
            "currentAddress": "Pls /B-15, Sector-9, Khanda Colony, New Panvel (W).",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Flat No-5, Plot No- 20 ,Serve No-22, Minakshi Opp Balaji Nagar, Dhankawadi, Pune 400043.",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400043",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI365",
            "bioMetricId": "GI0497",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "ACSPV8298B",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD587",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Mukul Moolchand Sharma",
            "firstName": "Mukul",
            "middleName": "Moolchand Sharma",
            "lastName": "",
            "mobileNo": "8923433587",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "23/07/2018",
            "dateofBirth": "03/04/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Moolchand Sharma",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "mukul@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/09/2018",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Shivpuri Colony, Lane No. 4, Kankhal Haridwar - 249408",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "EQXPM0143M",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD137",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Suchitra Nagendra Kumar Beura",
            "firstName": "Suchitra",
            "middleName": "Nagendra Kumar",
            "lastName": "Beura",
            "mobileNo": "7377530572",
            "reportingManagerCode": "GI2193",
            "reportingManagerName": "Raju Dhiraj Vavare",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "16/08/2017",
            "dateofBirth": "14/07/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Nagendra Kumar Beura",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "suchitrabe@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/10/2017",
            "currentAddress": "Nautiyal Nivas Near Primary School, Lane No.3,Danda Lakhond, Sahastradhara Road, It Park, D.Dun 248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Q.No-E19/4,Upper Police Colony,Tulasipur,Bidanasi,Cuttack,Odisha,753008",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2193",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BKYPB0630G",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3913",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Santosh Kumar Padhihari",
            "firstName": "Santosh",
            "middleName": "Kumar",
            "lastName": "Padhihari",
            "mobileNo": "9776202681",
            "reportingManagerCode": "GWD718",
            "reportingManagerName": "Vishal Ranjeet Singh Bhandari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "25/04/2019",
            "dateofBirth": "20/05/1983",
            "employeeStatus": "Confirmed",
            "fatherName": "Duryodhana Padhihari",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "santoshkumpad@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "22/10/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "S/O Duryodhana Padhihari, Rajanapalli, Chatrapur Rajanapalli, Rajpur Ganjam, Orissa 761020",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GWD718",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BUFPP3229N",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI581",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Nilesh Padmanath Valwaikar",
            "firstName": "Nilesh",
            "middleName": "Padmanath",
            "lastName": "Valwaikar",
            "mobileNo": "777666",
            "reportingManagerCode": "GI1190",
            "reportingManagerName": "Mustafa Bashu Shaikh",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Tech Lead",
            "department": "Support",
            "subDepartment": "ITD",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "03/07/2006",
            "dateofBirth": "15/03/1973",
            "employeeStatus": "Confirmed",
            "fatherName": "Padmanath",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Nilesh.Valwaikar@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "30/12/2006",
            "currentAddress": "13/C, Pipeline, Santacruz (E), 400055.",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400055",
            "permanentAddress": "13/C, Pipeline, Santacruz (E), 400055.",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400055",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1190",
            "bioMetricId": "GI0581",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "ACPPV6517J",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GI2417",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Murugadoss Srinivasan Murugadoss S",
            "firstName": "Murugadoss",
            "middleName": "Srinivasan",
            "lastName": "Murugadoss S",
            "mobileNo": "9004792055",
            "reportingManagerCode": "GI1190",
            "reportingManagerName": "Mustafa Bashu Shaikh",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Associate Technical Architect",
            "department": "Support",
            "subDepartment": "ITD",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "B+",
            "gender": "Male",
            "dateofJoining": "07/09/2016",
            "dateofBirth": "05/06/1981",
            "employeeStatus": "Confirmed",
            "fatherName": "Srinivasan",
            "motherName": "Malliga",
            "maritalStatus": "Married",
            "emailAddress": "Muruga.Doss@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "06/03/2017",
            "currentAddress": "252, Dr. Rp Road, Siddarth Nagar, Behind Church, Mulund, Mumbai-400080",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400080",
            "permanentAddress": "11 Athur, Madurantakam, Tamil Nadu, Kancheepuram-603310",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "603310",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI1190",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "APNPM0865R",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "02/03/2020",
            "updatedByCode": "GI907",
            "updatedByName": "Shailesh",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GW2504",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Shivendra Giriendra Dutt Tripathi",
            "firstName": "Shivendra",
            "middleName": "Giriendra Dutt",
            "lastName": "Tripathi",
            "mobileNo": "9769997224",
            "reportingManagerCode": "GI2635",
            "reportingManagerName": "Kuldeep Moholkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Vice President Business Development",
            "department": "Support",
            "subDepartment": "Sales & BD",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "02/05/2017",
            "dateofBirth": "03/12/1966",
            "employeeStatus": "Confirmed",
            "fatherName": "Giriendra Dutt Tripathi",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Shivendra.Tripathi@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 90,
            "dateofConfirmation": "29/10/2017",
            "currentAddress": "Tower 14, Flat No 12 A02, Paras Tierea Sector 137 Noida Uttar Pradesh 201305",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "201305",
            "permanentAddress": "Tower 14, Flat No 12 A02, Paras Tierea Sector 137 Noida Uttar Pradesh 201305",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2635",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AAPPT6825P",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2375",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sameer Islam Ansari",
            "firstName": "Sameer",
            "middleName": "Islam",
            "lastName": "Ansari",
            "mobileNo": "7398568071",
            "reportingManagerCode": "GW455",
            "reportingManagerName": "Balaji Haridas Deshmukh",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Field Executive",
            "department": "Production",
            "subDepartment": "MMT",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "27/04/2016",
            "dateofBirth": "02/08/1992",
            "employeeStatus": "Confirmed",
            "fatherName": "Islam Ansari",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sameera@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "24/10/2016",
            "currentAddress": "Baghad, Chauri Chaura,Gorakhpur, Uttar Pradesh, Pin-273201",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "273201",
            "permanentAddress": "Baghad, Chauri Chaura,Gorakhpur, Uttar Pradesh, Pin-273201",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "273201",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW455",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BNWPA0885M",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2654",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sagar Janardan B Golambade",
            "firstName": "Sagar",
            "middleName": "Janardan B",
            "lastName": "Golambade",
            "mobileNo": "9920237029",
            "reportingManagerCode": "GW1019",
            "reportingManagerName": "Vandana Anant Tambe",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Field Senior Executive",
            "department": "Production",
            "subDepartment": "MMT",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "05/07/2017",
            "dateofBirth": "22/04/1989",
            "employeeStatus": "Confirmed",
            "fatherName": "Janardan B Golambade",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sagargo@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2018",
            "currentAddress": "Jay Bhawani Jagruti Society, Jay Malhar Nagar Khandoba Tekdi Golibar Road, Ghatkopar West Mumbai Maharashtra 400086",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400086",
            "permanentAddress": "Jay Bhawani Jagruti Society, Jay Malhar Nagar Khandoba Tekdi Golibar Road, Ghatkopar West Mumbai Maharashtra 400086",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400086",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1019",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AOKPG2684Q",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW1511",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Kunal Bhaurao Patil",
            "firstName": "Kunal",
            "middleName": "Bhaurao",
            "lastName": "Patil",
            "mobileNo": "9588771122",
            "reportingManagerCode": "GI2280",
            "reportingManagerName": "Rushabh M Shethia",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Team Lead",
            "department": "Production",
            "subDepartment": "MMT",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Team Manager",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "05/06/2012",
            "dateofBirth": "05/06/1989",
            "employeeStatus": "Confirmed",
            "fatherName": "Bhaurao",
            "motherName": "Asha Bhaurao Patil",
            "maritalStatus": "Single",
            "emailAddress": "Kunal.Patil@igenesys.com",
            "personalEmail": "kp2290@gmail.com",
            "noticePeriod": 30,
            "dateofConfirmation": "02/12/2012",
            "currentAddress": "Room No.2360, Surekha Smurti, Jaimalhar Nagar, Near Tata Power House Pisiwligaon. Kalyan. Maharashtra - 421306",
            "currentCountry": "India",
            "currentState": "Maharashtra",
            "currentCity": "Kalyan-Dombivali",
            "currentZip": "421306",
            "permanentAddress": "Room No.2360, Surekha Smurti, Jaimalhar Nagar, Near Tata Power House Pisiwligaon. Kalyan. Maharashtra - 421306",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "Kalyan-Dombivali",
            "permanentZip": "421306",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI2280",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "Hindu",
            "panNumber": "ATHPP4713D",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GWD739",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Brijesh Surender Kumar",
            "firstName": "Brijesh",
            "middleName": "Surender",
            "lastName": "Kumar",
            "mobileNo": "7409986716",
            "reportingManagerCode": "GWD576",
            "reportingManagerName": "Naval Kishore Pant",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Apprentice Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "21/11/2018",
            "dateofBirth": "02/01/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Surender Kumar",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "brijeshk@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/12/2018",
            "currentAddress": "35 Block, Deepnagar, Ajabpur Kalan, Dehradun-248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "35 Block, Deepnagar, Ajabpur Kalan, Dehradun-248001",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD576",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "ELIPK9541K",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3959",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Labrej Kutbuddin Bandivadekar",
            "firstName": "Labrej",
            "middleName": "Kutbuddin",
            "lastName": "Bandivadekar",
            "mobileNo": "8097138244",
            "reportingManagerCode": "GI280",
            "reportingManagerName": "Manoj Shantaram Hatimkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/06/2019",
            "dateofBirth": "04/06/1992",
            "employeeStatus": "Confirmed",
            "fatherName": "Kutbuddin",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "labrejkb@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "28/11/2019",
            "currentAddress": "Madjatti Venila Tank, Kokan Nagar, Malad West, 400061",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Dhalavali, Korle, Koria, Sindhudurg, Maharashtra, 416703",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI280",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BJIPB5288H",
            "createdDate": "13/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI365",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sagar Shashikant Patil",
            "firstName": "Sagar",
            "middleName": "Shashikant",
            "lastName": "Patil",
            "mobileNo": "9821674111",
            "reportingManagerCode": "GI2635",
            "reportingManagerName": "Kuldeep Moholkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Vice President",
            "department": "Production",
            "subDepartment": "MCGM/Wonobo",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Management",
            "level": "",
            "bloodGroup": "O+",
            "gender": "Male",
            "dateofJoining": "06/08/2001",
            "dateofBirth": "20/07/1974",
            "employeeStatus": "Confirmed",
            "fatherName": "Shashikant",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Sagar.Patil@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 90,
            "dateofConfirmation": "02/02/2002",
            "currentAddress": "Cosmos Jewels, Solitaire B-Wing, Flat 25A, Ghoudbunder Road, Kavesar, Near D-Mart, Thane West, Pin Code 400615",
            "currentCountry": "India",
            "currentState": "Maharashtra",
            "currentCity": "Thane",
            "currentZip": "400615",
            "permanentAddress": "Cosmos Jewels, Solitaire B-Wing, Flat 25A, Ghoudbunder Road, Kavesar, Near D-Mart, Thane West, Pin Code 400615",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "Thane",
            "permanentZip": "400615",
            "spouseName": "Dimple",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2635",
            "bioMetricId": "GI0365",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AHSPP9198D",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2868",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Archana Durgesh Tripathi",
            "firstName": "Archana",
            "middleName": "Durgesh",
            "lastName": "Tripathi",
            "mobileNo": "8850811262",
            "reportingManagerCode": "GI166",
            "reportingManagerName": "Sachin Shankar Pawar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "01/06/2019",
            "dateofBirth": "27/07/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Durgesh",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "archanadut@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "28/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "B-26, Hitwardhak Chawl, Akurli Road, Near Vir Hanuman Mandir, Kranti Nagar, Mumbai, Kandivali East, Maharashtra, 400101",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI166",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BBUPT4219C",
            "createdDate": "13/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2275",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Aniruddha J.K. Roy",
            "firstName": "Aniruddha",
            "middleName": "J.K.",
            "lastName": "Roy",
            "mobileNo": "9999700936",
            "reportingManagerCode": "GI2635",
            "reportingManagerName": "Kuldeep Moholkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Senior Vice President Technology Solution",
            "department": "Support",
            "subDepartment": "Sales & BD",
            "branch": "Mumbai",
            "subBranch": "MUM-Delhi",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/07/2015",
            "dateofBirth": "22/10/1965",
            "employeeStatus": "Confirmed",
            "fatherName": "J.K. Roy",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Ani.Roy@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 90,
            "dateofConfirmation": "28/12/2015",
            "currentAddress": "M301, Habitat Cghs, B-19, Vasundhra Enclave, Delhi 110096",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "110096",
            "permanentAddress": "M301, Habitat Cghs, B-19, Vasundhra Enclave, Delhi 110096",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "110096",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2635",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AAFPR6512F",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI197",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Bijendra Ramdev Yadav",
            "firstName": "Bijendra",
            "middleName": "Ramdev",
            "lastName": "Yadav",
            "mobileNo": "9869635055",
            "reportingManagerCode": "GW556",
            "reportingManagerName": "Ujjwal Kishore Pakhare",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Project Manager",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Manager",
            "level": "",
            "bloodGroup": "O+",
            "gender": "Male",
            "dateofJoining": "15/01/2001",
            "dateofBirth": "04/04/1975",
            "employeeStatus": "Confirmed",
            "fatherName": "Ramdev",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "bijendra.yadav@igenesys.com",
            "personalEmail": "bry_101@rediffmail.com",
            "noticePeriod": 60,
            "dateofConfirmation": "15/07/2001",
            "currentAddress": "4/Sadhu Bhaiya Chawl, Datt Mandir Road, Vakola Bridge, Santacruz(E), 55.",
            "currentCountry": "India",
            "currentState": "Maharashtra",
            "currentCity": "Mumbai",
            "currentZip": "400055",
            "permanentAddress": "4/Sadhu Bhaiya Chawl, Datt Mandir Road, Vakola Bridge, Santacruz(E), 55.",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "Mumbai",
            "permanentZip": "400055",
            "spouseName": "Sangeeta",
            "dateofMarriage": "15/05/1995",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GW556",
            "bioMetricId": "GI0197",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "Hindu",
            "panNumber": "AATPY5184C",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "04/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GW3937",
            "salutation": "Mrs.",
            "employeeType": "Regular",
            "employeeName": "Somisetty Suvarna",
            "firstName": "Somisetty",
            "middleName": "",
            "lastName": "Suvarna",
            "mobileNo": "9849010907",
            "reportingManagerCode": "GI2610",
            "reportingManagerName": "Pampana Venkata Bapesh Rao",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "03/05/2019",
            "dateofBirth": "03/06/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Yagantappa Somisetty",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "somisettysuv@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "30/10/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "D/O Somisetty Yagantappa, 1/7, Darga Street, Meerapuram, Kurnool, Andhra Pradesh - 518124",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI2610",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "DRGPS0469R",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD552",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Ashish Gopal Singh Rawat",
            "firstName": "Ashish",
            "middleName": "Gopal Singh",
            "lastName": "Rawat",
            "mobileNo": "8126767143",
            "reportingManagerCode": "GI2610",
            "reportingManagerName": "Pampana Venkata Bapesh Rao",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "09/07/2018",
            "dateofBirth": "06/07/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Gopal Singh Rawat",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "ashishrsha@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/09/2018",
            "currentAddress": "C/O Rajindir Singh Bisht, H.No.-144,Dhobalvalla, Dehradun-248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vill-Kukeda, P.O-Thadiyar, Teh.-Mori,Dist.-Uttarkashi-249185",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2610",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2783",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Kahnu Charan Badatya",
            "firstName": "Kahnu",
            "middleName": "Charan",
            "lastName": "Badatya",
            "mobileNo": "7504979852",
            "reportingManagerCode": "GI1444",
            "reportingManagerName": "Vilas Anaji Sawant",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "CG_Bharat Net",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "22/02/2019",
            "dateofBirth": "11/03/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Charan",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "kahnucb@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "21/10/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Kali Mandir, Sahi, Dengapadar, Dengapadar, Dengapadar, Ganjam, Odisha, 761146",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "761146",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1444",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "EENPB1276L",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD548",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Jaypal Digpal Singh",
            "firstName": "Jaypal",
            "middleName": "Digpal",
            "lastName": "Singh",
            "mobileNo": "9997025453",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "09/07/2018",
            "dateofBirth": "27/08/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Digpal Singh",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "jaypals@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/09/2018",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vill Dharshal, P.O.-Parkhandi, Rudrapryag-",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "JUFPS8934D",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2426",
            "salutation": "Mrs.",
            "employeeType": "Regular",
            "employeeName": "Rupali Kamble",
            "firstName": "Rupali",
            "middleName": "",
            "lastName": "Kamble",
            "mobileNo": "9022617391",
            "reportingManagerCode": "GW2506",
            "reportingManagerName": "Suruchi Raina",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Executive",
            "department": "Support",
            "subDepartment": "Sales & BD",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "13/10/2016",
            "dateofBirth": "11/04/1989",
            "employeeStatus": "Confirmed",
            "fatherName": "Paidas Manaji Kamble",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Rupali.Kamble@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "11/04/2017",
            "currentAddress": "E-1, Omkareshwar Nagar, Sainath Nagar Naka, Chandan Sar Road, Virar East, Mumbai-401305",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "401305",
            "permanentAddress": "E-1, Omkareshwar Nagar, Sainath Nagar Naka, Chandan Sar Road, Virar East, Mumbai-401305",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "401305",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW2506",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CEJPK7617A",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GI907",
            "updatedByName": "Shailesh",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GWD440",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sanjay Jaspal Singh",
            "firstName": "Sanjay",
            "middleName": "Jaspal",
            "lastName": "Singh",
            "mobileNo": "8057673157",
            "reportingManagerCode": "GW1253",
            "reportingManagerName": "Ramprashad Panigrahy",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "09/04/2018",
            "dateofBirth": "04/05/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Jaspal Singh",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sanjaysi@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/06/2018",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vill-Kusel Kanda, Po-Kanda Talla, Distt- Pauri Garhwal 246276",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1253",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2841",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Monika Suresh Pal",
            "firstName": "Monika",
            "middleName": "Suresh",
            "lastName": "Pal",
            "mobileNo": "7008161450",
            "reportingManagerCode": "GI2621",
            "reportingManagerName": "Uday Keshavrao Bhagwat",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "03/10/2017",
            "dateofBirth": "01/06/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Suresh Pal",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "monikapa@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/04/2018",
            "currentAddress": "At -Charitira, Po -Barapada , Dist- Bhadrak State -Odisha 756113",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "756113",
            "permanentAddress": "At -Charitira, Po -Barapada , Dist- Bhadrak State -Odisha 756113",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "756113",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2621",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CNYPP6866L",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2651",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Zubair Abdulrashid Shaikh",
            "firstName": "Zubair",
            "middleName": "Abdulrashid",
            "lastName": "Shaikh",
            "mobileNo": "8450923366",
            "reportingManagerCode": "GI2280",
            "reportingManagerName": "Rushabh M Shethia",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Field Executive",
            "department": "Production",
            "subDepartment": "MMT",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "05/07/2017",
            "dateofBirth": "31/12/1992",
            "employeeStatus": "Confirmed",
            "fatherName": "Abdulrashid Shaikh",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "zubairs@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2018",
            "currentAddress": "102/A, Galaxy Bldg 1 & 2, Beverly Park, Miraroad Thane Maharashtra 401107",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "401107",
            "permanentAddress": "102/A, Galaxy Bldg 1 & 2, Beverly Park, Miraroad Thane Maharashtra 401107",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "401107",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2280",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "ECFPS0680R",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3969",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Swapnil Shivaji Chavan",
            "firstName": "Swapnil",
            "middleName": "Shivaji",
            "lastName": "Chavan",
            "mobileNo": "7303662386",
            "reportingManagerCode": "GI1071",
            "reportingManagerName": "Vilas Bhagwat Kshirasagar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Telecom",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "20/06/2019",
            "dateofBirth": "27/08/1994",
            "employeeStatus": "Confirmed",
            "fatherName": "Shivaji Chavan",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "swapnilsc@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "17/12/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "S/O Shivaji Chavan, Room - 2, Chagan Mangal Chawl W E Highway, Ambewadi Vile Parle East, Mumbai, Mumbai, Maharashtra - 400099",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI1071",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BCSPC3420R",
            "createdDate": "16/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD087",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Arpita Om Prakash Banga",
            "firstName": "Arpita",
            "middleName": "Om Prakash",
            "lastName": "Banga",
            "mobileNo": "8393018396",
            "reportingManagerCode": "GI2621",
            "reportingManagerName": "Uday Keshavrao Bhagwat",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "01/08/2017",
            "dateofBirth": "01/05/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Om Prakash Bahga",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "arpitaba@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/09/2017",
            "currentAddress": "45 Mdda Colony Laxman Chawk Kanwali Road D.Dun,248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "45 Mdda Colony Laxman Chawk Kanwali Road D.Dun,248001",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2621",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3926",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Somanath Patra",
            "firstName": "Somanath",
            "middleName": "",
            "lastName": "Patra",
            "mobileNo": "7008673008",
            "reportingManagerCode": "GW2522",
            "reportingManagerName": "Shiva Lalitha Rajappan",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "25/04/2019",
            "dateofBirth": "05/05/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Fakir Mohan Patra",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "somanathpat@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "22/10/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "S/O Fakir Mohan Patra, Kothakaranasahi, Tangi, Khorda, Odisha - 752023",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GW2522",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "FBNPP7277P",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3967",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Suyog Sitaram Mandavkar",
            "firstName": "Suyog",
            "middleName": "Sitaram",
            "lastName": "Mandavkar",
            "mobileNo": "9768178506",
            "reportingManagerCode": "GI308",
            "reportingManagerName": "Shaikh Shanazir",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "System Engineer",
            "department": "Support",
            "subDepartment": "ITIS",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "03/06/2019",
            "dateofBirth": "06/10/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Sitaram",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "suyogsim@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "30/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "S/O Sitaram Mandavkar, Behind Seepz Colony Room No 2, Thakur Chawl, Central Road, Chakala Midc S.O, Mumbai Maharashtra 400093",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI308",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CNMPM4916L",
            "createdDate": "13/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3785",
            "salutation": "Mrs.",
            "employeeType": "Regular",
            "employeeName": "Pragna Rashmi Sahu",
            "firstName": "Pragna",
            "middleName": "Rashmi",
            "lastName": "Sahu",
            "mobileNo": "9658322054",
            "reportingManagerCode": "GI1166",
            "reportingManagerName": "Yogesh Sitaram Pawar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "01/04/2019",
            "dateofBirth": "14/04/1989",
            "employeeStatus": "Relieved",
            "fatherName": "Rashmi",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "GW3785@test.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "28/09/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Rama Krushana Sahu, Plot No - 627, Jagannath Road No -16, Bhubaneswar, Khordha, Orissa - 751025",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1166",
            "bioMetricId": "",
            "dateofLeaving": "31/05/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "31/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "15/05/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD718",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Vishal Ranjeet Singh Bhandari",
            "firstName": "Vishal",
            "middleName": "Ranjeet Singh",
            "lastName": "Bhandari",
            "mobileNo": "9760958190",
            "reportingManagerCode": "GWD477",
            "reportingManagerName": "Davender Baldev Raj",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Project Lead",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Team Manager",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/11/2018",
            "dateofBirth": "15/05/1983",
            "employeeStatus": "Confirmed",
            "fatherName": "Ranjeet Singh Bhandari",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "vishalbh@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/12/2018",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "G 72, Hathi Barkala Estate, Survey Of India, Dehradun",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD477",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AQMPB5554G",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD647",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Aarti Gambhir Singh Bhandari",
            "firstName": "Aarti",
            "middleName": "Gambhir Singh",
            "lastName": "Bhandari",
            "mobileNo": "8006131135",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "27/08/2018",
            "dateofBirth": "13/12/1994",
            "employeeStatus": "Confirmed",
            "fatherName": "Gambhir Singh Bhandari",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "aartib@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/10/2018",
            "currentAddress": "8/2 Indara Nagar Colony, Dehadun-248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "8/2 Indara Nagar Colony, Dehadun-248001",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BTXPB8149J",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD861",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Asif Saleem Ahamad",
            "firstName": "Asif",
            "middleName": "Saleem Ahamad",
            "lastName": "",
            "mobileNo": "8755447249",
            "reportingManagerCode": "GW2522",
            "reportingManagerName": "Shiva Lalitha Rajappan",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Apprentice Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "20/12/2018",
            "dateofBirth": "25/07/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Saleem Ahamad",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "asif@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Jamanipur Herbertpur Dehradun",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW2522",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CWYPA1357C",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD052",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Lakhpat Vikram Singh",
            "firstName": "Lakhpat",
            "middleName": "Vikram",
            "lastName": "Singh",
            "mobileNo": "7895197704",
            "reportingManagerCode": "GW3670",
            "reportingManagerName": "Kalpesh Pradyumna Gor",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "03/07/2017",
            "dateofBirth": "02/02/1990",
            "employeeStatus": "Confirmed",
            "fatherName": "Vikram Singh",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "lakhpats@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/08/2017",
            "currentAddress": "49- Dl Road, Karanpur, Dehradun",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vill & Po- Gershira, Chamoli (Uk)",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW3670",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "EIIPS0938F",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2620",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sanjeeb Chamru Megha",
            "firstName": "Sanjeeb",
            "middleName": "Chamru",
            "lastName": "Megha",
            "mobileNo": "9658657981",
            "reportingManagerCode": "GW3670",
            "reportingManagerName": "Kalpesh Pradyumna Gor",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "16/06/2017",
            "dateofBirth": "24/03/1989",
            "employeeStatus": "Confirmed",
            "fatherName": "Chamru Megha",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sanjeebm@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "13/12/2017",
            "currentAddress": "Sainath Nagar, Near Marol Pipe Line, Midc  Andheri East Mumbai Maharashtra 400047",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400047",
            "permanentAddress": "At - Medical Colony, Q No 1, R - 227, Po - Borza, Dist - Samballpur, Odisha 768017",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "768017",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW3670",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BRMPM6100R",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2399",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Mulayam Ramashray Yadav",
            "firstName": "Mulayam",
            "middleName": "Ramashray",
            "lastName": "Yadav",
            "mobileNo": "9918320635",
            "reportingManagerCode": "GW1019",
            "reportingManagerName": "Vandana Anant Tambe",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Field Executive",
            "department": "Production",
            "subDepartment": "MMT",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "07/06/2016",
            "dateofBirth": "07/08/1990",
            "employeeStatus": "Confirmed",
            "fatherName": "Ramashray Yadav",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "mulayamy@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "04/12/2016",
            "currentAddress": "Villa Naikot, Post Bahadiya, Saidpur, Ghazipur, Uttarpradesh-233223",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "233223",
            "permanentAddress": "Villa Naikot, Post Bahadiya, Saidpur, Ghazipur, Uttarpradesh-233223",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "233223",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1019",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "ALGPY9516Q",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2683",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Rashmi Ramshakal Jaiswal",
            "firstName": "Rashmi",
            "middleName": "Ramshakal",
            "lastName": "Jaiswal",
            "mobileNo": "8412925980",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "20/06/2018",
            "dateofBirth": "01/02/1996",
            "employeeStatus": "Relieved",
            "fatherName": "Ramshakal",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "GI2683@test.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "17/12/2018",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "H.N. 287,Hingna Road, Lokmany Nagar M.I.D.C Digdoh, Nagpur (Urban), Indl. Area, Nagpur Maharashtra- 440016",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "N",
            "nationality": "",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "21/08/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "21/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "21/08/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD689",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Karuna Kishore Mani",
            "firstName": "Karuna",
            "middleName": "Kishore",
            "lastName": "Mani",
            "mobileNo": "9997154833",
            "reportingManagerCode": "GW3975",
            "reportingManagerName": "Mahesh Madipadige",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "29/10/2018",
            "dateofBirth": "10/01/1997",
            "employeeStatus": "Resigned",
            "fatherName": "Kishore Mani",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "karunam@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2019",
            "currentAddress": "53/13 Rajpur Road, Dehradun 248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vill- Mandakhal P.O Buwakhal, Pauri Garhwal",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW3975",
            "bioMetricId": "",
            "dateofLeaving": "24/10/2020",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "DSDPM9460G",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": "24/09/2020",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2971",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "A.Karishma Rani Patra",
            "firstName": "A.Karishma",
            "middleName": "Rani",
            "lastName": "Patra",
            "mobileNo": "9337328265",
            "reportingManagerCode": "GI2481",
            "reportingManagerName": "Chandra Sekhar Mandal",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "CG_Bharat Net",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "22/11/2017",
            "dateofBirth": "04/09/1995",
            "employeeStatus": "Relieved",
            "fatherName": "Rani",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "GW2971@test.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "21/05/2018",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Bellagunta, Penth Street, Ganjam Odisha 761119",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "N",
            "nationality": "",
            "reportingManager": "GI2481",
            "bioMetricId": "",
            "dateofLeaving": "20/05/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "13/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "14/05/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD844",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Rajat Virendra Singh Nakoti",
            "firstName": "Rajat",
            "middleName": "Virendra Singh",
            "lastName": "Nakoti",
            "mobileNo": "8865027818",
            "reportingManagerCode": "GWD576",
            "reportingManagerName": "Naval Kishore Pant",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "14/12/2018",
            "dateofBirth": "10/03/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Virendra Singh Nakoti",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "rajatn@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2019",
            "currentAddress": "Selaqui Dehradun",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Rishikesh Road Chamba Tehri Garhwal (U.K)",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD576",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BUPPN8886B",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2508",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Rohit Rajkumar Koli",
            "firstName": "Rohit",
            "middleName": "Rajkumar",
            "lastName": "Koli",
            "mobileNo": "8976634236",
            "reportingManagerCode": "GI1201",
            "reportingManagerName": "Pritam Minanath Sarang",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "MCGM/Wonobo",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "15/05/2017",
            "dateofBirth": "26/04/1992",
            "employeeStatus": "Confirmed",
            "fatherName": "Rajkumar Koli",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "rohitrk@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "11/11/2017",
            "currentAddress": "Singhni Estate Near Shreyas Cinema Kadar Chal, Ghatkopar West",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Ap Vakawali Nava Nagar Tal Dapoli Dist Ratnagiri Maharashtra 415711",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "415711",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1201",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BXIPK5548M",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3964",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Salim Ilyas Sagvekar",
            "firstName": "Salim",
            "middleName": "Ilyas",
            "lastName": "Sagvekar",
            "mobileNo": "9359423200",
            "reportingManagerCode": "GI2861",
            "reportingManagerName": "Nitin Vithal Kamble",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/06/2019",
            "dateofBirth": "19/08/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Ilyas",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "salimisa@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "28/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "194, Sultani Mohalla, Purnagad, Ratnagiri, Purnagad, Maharashtra, 415616",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI2861",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "13/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD745",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Prerna Narottam Singh Negi",
            "firstName": "Prerna",
            "middleName": "Narottam Singh",
            "lastName": "Negi",
            "mobileNo": "8171198478",
            "reportingManagerCode": "GI2610",
            "reportingManagerName": "Pampana Venkata Bapesh Rao",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "21/11/2018",
            "dateofBirth": "01/07/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Narottam Singh Negi",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "prernan@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/02/2019",
            "currentAddress": "27 Kalidas Road, Hathbarkala, Dehradun-248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Gaon - Gudrasu, Post-Aadwani, Adwani, Uttarakhand-246146",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2610",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BVXPN0129G",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2866",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Prasad Subhash Shinde",
            "firstName": "Prasad",
            "middleName": "Subhash",
            "lastName": "Shinde",
            "mobileNo": "9604757375",
            "reportingManagerCode": "GI2861",
            "reportingManagerName": "Nitin Vithal Kamble",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "08/05/2019",
            "dateofBirth": "04/05/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Subhash Dattu Shinde",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "prasadsush@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "04/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Kalvade, Kalawade, Satara, Maharashtra, 415539",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI2861",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "HUFPS0252C",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW1087",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Nikhil Anantrai Jani",
            "firstName": "Nikhil",
            "middleName": "Anantrai",
            "lastName": "Jani",
            "mobileNo": "9167220762",
            "reportingManagerCode": "GI2635",
            "reportingManagerName": "Kuldeep Moholkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Vice President",
            "department": "Production",
            "subDepartment": "CG_Bharat Net",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Management",
            "level": "",
            "bloodGroup": "B+",
            "gender": "Male",
            "dateofJoining": "22/02/2012",
            "dateofBirth": "26/05/1975",
            "employeeStatus": "Confirmed",
            "fatherName": "Anantrai",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Nikhil.Jani@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 90,
            "dateofConfirmation": "20/08/2012",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "Maharashtra",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Lodha Splendora, Platino B Flat 1003, Bhayenderpada, Ghodbandar Road, Thane 400615",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "411036",
            "spouseName": "",
            "dateofMarriage": "28/01/1999",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI2635",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AASPJ2549K",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GI907",
            "updatedByName": "Shailesh",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GWD202",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sachin Singh Negi",
            "firstName": "Sachin",
            "middleName": "Singh",
            "lastName": "Negi",
            "mobileNo": "8126516419",
            "reportingManagerCode": "GWD718",
            "reportingManagerName": "Vishal Ranjeet Singh Bhandari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "04/09/2017",
            "dateofBirth": "07/07/1994",
            "employeeStatus": "Confirmed",
            "fatherName": "Ranbir Singh Negi",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sachinne@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/10/2017",
            "currentAddress": "Karanpur,D.Dun.248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vill-Siran,Po-Shanti Sadan,Tehsill-Karanprayag,Distt-Chamoli,246429",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD718",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3761",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Shyamsundar Vasant Pawar",
            "firstName": "Shyamsundar",
            "middleName": "Vasant",
            "lastName": "Pawar",
            "mobileNo": "7709225530",
            "reportingManagerCode": "GI280",
            "reportingManagerName": "Manoj Shantaram Hatimkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/04/2019",
            "dateofBirth": "24/08/1990",
            "employeeStatus": "Confirmed",
            "fatherName": "Vasant",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "shyamsundarvp@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "27/11/2019",
            "currentAddress": "Mu - Chawalkheda, Po - Pimpri Kh, Dist- Jalgaon, Tal - Dharangaon, Chavalkhede, Jalgaon, Maharashtra - 425104",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "425104",
            "permanentAddress": "Mu - Chawalkheda, Po - Pimpri Kh, Dist- Jalgaon, Tal - Dharangaon, Chavalkhede, Jalgaon, Maharashtra - 425104",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "425104",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI280",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BYBPP8790E",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3902",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Subhranshu Kumar Parida",
            "firstName": "Subhranshu",
            "middleName": "Kumar",
            "lastName": "Parida",
            "mobileNo": "8847872715",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "25/04/2019",
            "dateofBirth": "23/05/1994",
            "employeeStatus": "Confirmed",
            "fatherName": "Prasanna Kumar Parida",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "subhranshukup@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "22/10/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Jagannathprasad, Jagannathprasad, Nayagarh, Odisha, - 752093",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CZOPP1439F",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3951",
            "salutation": "Mrs.",
            "employeeType": "Regular",
            "employeeName": "Priya Chandrkant More",
            "firstName": "Priya",
            "middleName": "Chandrkant",
            "lastName": "More",
            "mobileNo": "8454924842",
            "reportingManagerCode": "GI1201",
            "reportingManagerName": "Pritam Minanath Sarang",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "MCGM/Wonobo",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "10/05/2019",
            "dateofBirth": "11/11/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Chandrakant Ananda More",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "priyam@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "06/11/2019",
            "currentAddress": "Sneadeep BLDG Four Floor, A Wing, Kisan Nagar Wagle Estate, Thane Mumbai 400604",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Pophalwane, More Wadi Dapoli, Pophalwane, Pophlavane, Ratnagiri, Maharashtra, 415727",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI1201",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "EEPPM9324L",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD619",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Shivani Suresh Saklani",
            "firstName": "Shivani",
            "middleName": "Suresh",
            "lastName": "Saklani",
            "mobileNo": "7536003840",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "06/08/2018",
            "dateofBirth": "17/12/1998",
            "employeeStatus": "Confirmed",
            "fatherName": "Suresh Saklani",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "shivanisa@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/10/2018",
            "currentAddress": "Vill- Pitamberpur, Arkedia Grant, P.O Badowala, Dehradun- 248007",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vill- Pitamberpur, Arkedia Grant, P.O Badowala, Dehradun- 248007",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "KNUPS8474P",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3024",
            "salutation": "Mrs.",
            "employeeType": "Regular",
            "employeeName": "Pooja Sudhakar Hindlekar",
            "firstName": "Pooja",
            "middleName": "Sudhakar",
            "lastName": "Hindlekar",
            "mobileNo": "9821225679",
            "reportingManagerCode": "GW3619",
            "reportingManagerName": "Mohamed Ali Kasam Gazge",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Java Developer",
            "department": "Support",
            "subDepartment": "ITD",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "20/03/2018",
            "dateofBirth": "13/02/1990",
            "employeeStatus": "Relieved",
            "fatherName": "Sudhakar",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "GW3024@test.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "16/09/2018",
            "currentAddress": "Room No. 34, 1 Floor, Natakwala Police Line, S.V. Road, Near Collector Office, Borivali, West, S.O. Mumbai, Maharashtra, 400092",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Room No. 34, 1 Floor, Natakwala Police Line, S.V. Road, Near Collector Office, Borivali, West, S.O. Mumbai, Maharashtra, 400092",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "N",
            "nationality": "",
            "reportingManager": "GW3619",
            "bioMetricId": "",
            "dateofLeaving": "04/06/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "23/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "04/06/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3922",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Himalaya Mahaling",
            "firstName": "Himalaya",
            "middleName": "",
            "lastName": "Mahaling",
            "mobileNo": "9973843743",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "25/04/2019",
            "dateofBirth": "27/05/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Kunja Bihari Mahaling",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "himalayamah@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "22/10/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "QR No Min - 19 Mining Colony Uditnagar, Rourkela, Uditnagar, Raghunathapali Sundergarh, Odisha 769012",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3939",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Pushplata Chandrakant Hatankar",
            "firstName": "Pushplata",
            "middleName": "Chandrakant",
            "lastName": "Hatankar",
            "mobileNo": "8390734219",
            "reportingManagerCode": "GI280",
            "reportingManagerName": "Manoj Shantaram Hatimkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "08/05/2019",
            "dateofBirth": "25/02/1994",
            "employeeStatus": "Confirmed",
            "fatherName": "Chandrakant Dhondi Hatankar",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "pushplatach@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "04/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "10, Tatarbav Wadi, Asarondi, Asrondi, Sindhudurg, Malwan, Maharashtra, 416602",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI280",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AXUPH8165N",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2503",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Pranav Praman Tillu",
            "firstName": "Pranav",
            "middleName": "Praman",
            "lastName": "Tillu",
            "mobileNo": "9867350244",
            "reportingManagerCode": "GI1166",
            "reportingManagerName": "Yogesh Sitaram Pawar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Team Lead",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Team Manager",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "08/05/2017",
            "dateofBirth": "23/05/1991",
            "employeeStatus": "Confirmed",
            "fatherName": "Praman J Tillu",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "pranavt@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "04/11/2017",
            "currentAddress": "Flat No 503, 504 Savani Millennium Tower, Dharamveer Road, Pnach Pakahdi Thane Maharashtra 400602",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400602",
            "permanentAddress": "Flat No 503, 504 Savani Millennium Tower, Dharamveer Road, Pnach Pakahdi Thane Maharashtra 400602",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400602",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1166",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AJFPT1549M",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3931",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Kandagatla Balakrishna",
            "firstName": "Kandagatla",
            "middleName": "",
            "lastName": "Balakrishna",
            "mobileNo": "9032822362",
            "reportingManagerCode": "GI2610",
            "reportingManagerName": "Pampana Venkata Bapesh Rao",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "02/05/2019",
            "dateofBirth": "30/06/1986",
            "employeeStatus": "Confirmed",
            "fatherName": "Kandagatla Madhusudhan",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "kandagatlab@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "29/10/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "S/O Kandagatla Madhusudhan, H No 6-95/1, Choutuppal Mandal, Mandolla Guder Nalgonda, Andhra Pradesh, 508252",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI2610",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BWYPK5962Q",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2705",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Rohini Abhimanyu Daswadkar",
            "firstName": "Rohini",
            "middleName": "Abhimanyu",
            "lastName": "Daswadkar",
            "mobileNo": "9867829884",
            "reportingManagerCode": "CO056",
            "reportingManagerName": "Maya Honyalkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Front Desk  Executive",
            "department": "Support",
            "subDepartment": "Administration",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "23/07/2018",
            "dateofBirth": "02/06/1998",
            "employeeStatus": "Confirmed",
            "fatherName": "Abhimanyu",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "rohinida@email.igenesys.com",
            "personalEmail": "rohiniadaswadkar123@gmail.com",
            "noticePeriod": 30,
            "dateofConfirmation": "19/01/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Room No 601, Building No 10/C, Police Colony  Andheri East Mumbai Chakala Midc Mumbai Maharashtra- 400093",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400093",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "CO056",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CPXPD6558N",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2516",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Arman Mansur Gaonkhadkar",
            "firstName": "Arman",
            "middleName": "Mansur",
            "lastName": "Gaonkhadkar",
            "mobileNo": "9552222383",
            "reportingManagerCode": "GI1447",
            "reportingManagerName": "Rajesh Ramchandra More",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Telecom",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "08/05/2017",
            "dateofBirth": "25/04/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Mansur Gaonkhadkar",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "armang@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "04/11/2017",
            "currentAddress": "223, Korba Mithagar Mahatma Phule Wadi, Wadala East Mumbai Maharashtra 400037",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400037",
            "permanentAddress": "258, Azad Mohalla Purnagad, Ratnagiri Maharashtra 415616",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "415616",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1447",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BTEPG4417P",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2213",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Umesh Vasant Tambe",
            "firstName": "Umesh",
            "middleName": "Vasant",
            "lastName": "Tambe",
            "mobileNo": "9730266068",
            "reportingManagerCode": "GW1283",
            "reportingManagerName": "Sameer Gopalrao Shinde",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "VTL",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/06/2015",
            "dateofBirth": "02/07/1981",
            "employeeStatus": "Confirmed",
            "fatherName": "Vasant Sakharam Tambe",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "umesht@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "28/11/2015",
            "currentAddress": "C/105 Om Suyog Chs Samel Pada ,Umarale Road,  Nallasopara (W) Thane- 401203",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "401203",
            "permanentAddress": "C/105 Om Suyog Chs, Samel Pada ,Umarale Road,  Nallasopara (W) Thane- 401203",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "401203",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1283",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AITPT1958M",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3895",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sunil Bharat Dethe",
            "firstName": "Sunil",
            "middleName": "Bharat",
            "lastName": "Dethe",
            "mobileNo": "9922298823",
            "reportingManagerCode": "GW1147",
            "reportingManagerName": "Vishnu Uttamrao Wankhede",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "18/04/2019",
            "dateofBirth": "07/05/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Bharat",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sunilbhade@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "15/10/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Dethvasti, Korti, Takil, Korti, Solapur, Maharashtra, 413304",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "413304",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1147",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "DARPD2567R",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD873",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sumit Singh Bisht",
            "firstName": "Sumit",
            "middleName": "Singh",
            "lastName": "Bisht",
            "mobileNo": "8954539713",
            "reportingManagerCode": "GW2522",
            "reportingManagerName": "Shiva Lalitha Rajappan",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Apprentice Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "20/12/2018",
            "dateofBirth": "16/04/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Alam Singh Bisht",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sumitsib@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Alam Singh, Near Gru Ram Rai Inter College , Nehrugram D.Dun",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW2522",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "DHAPB6393B",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3954",
            "salutation": "Mrs.",
            "employeeType": "Regular",
            "employeeName": "Jayashree Nandu Shimpi",
            "firstName": "Jayashree",
            "middleName": "Nandu",
            "lastName": "Shimpi",
            "mobileNo": "9764675061",
            "reportingManagerCode": "GI166",
            "reportingManagerName": "Sachin Shankar Pawar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "01/06/2019",
            "dateofBirth": "20/10/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Nandu",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "jayashreens@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "28/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Nav Amey Apartment 003, New Shinde Ali, Apatewadi Shirgaon Ramnagar Badlapur East, Badlapur, Kulgaon Ambarnath Thane, Maharashtra 421503",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI166",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "HMGPS7938E",
            "createdDate": "13/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2515",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Prashant Pratibha Rege",
            "firstName": "Prashant",
            "middleName": "Pratibha",
            "lastName": "Rege",
            "mobileNo": "9825303130",
            "reportingManagerCode": "GI280",
            "reportingManagerName": "Manoj Shantaram Hatimkar",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "08/05/2017",
            "dateofBirth": "08/07/1983",
            "employeeStatus": "Confirmed",
            "fatherName": "Pratibha Rege",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "prashantr@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "04/11/2017",
            "currentAddress": "Prashant Prabhakar Rege, H No 186, Bhosale Chawl, Shivtekadi, Jogeshwari East Mumbai Maharashtra 400060",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400060",
            "permanentAddress": "Prashant Prabhakar Rege, H No 186, Bhosale Chawl, Shivtekadi, Jogeshwari East Mumbai Maharashtra 400060",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400060",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI280",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "ARGPR2685D",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI1294",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Kiran Madhukar Patil",
            "firstName": "Kiran",
            "middleName": "Madhukar",
            "lastName": "Patil",
            "mobileNo": "2141693298",
            "reportingManagerCode": "GI1201",
            "reportingManagerName": "Pritam Minanath Sarang",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "MCGM/Wonobo",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "05/05/2008",
            "dateofBirth": "22/01/1982",
            "employeeStatus": "Confirmed",
            "fatherName": "Madhukar",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "kiranp@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/11/2008",
            "currentAddress": "Behind Sai Sagar Hotel, Kasheat Road, Azad Nagar, Thane (W) .",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "At- Sagaon, Post -Khandala, Tal- Alibag, Dist - Raigad 402201",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "402201",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI1201",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BCOPP3483P",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD700",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Himani Rakesh Singh Sajwan",
            "firstName": "Himani",
            "middleName": "Rakesh Singh",
            "lastName": "Sajwan",
            "mobileNo": "8979526112",
            "reportingManagerCode": "GI2610",
            "reportingManagerName": "Pampana Venkata Bapesh Rao",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "30/10/2018",
            "dateofBirth": "01/04/1996",
            "employeeStatus": "Resigned",
            "fatherName": "Rakesh Singh Sajwan",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "himanis@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2019",
            "currentAddress": "Lane No-3, Vani Bihar, Adhoiwala, Dehradun-248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Lane No-3, Vani Bihar, Adhoiwala, Dehradun-248001",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2610",
            "bioMetricId": "",
            "dateofLeaving": "21/10/2020",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "JSAPS3458B",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": "21/09/2020",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2517",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Sukanta Mangulu Behera",
            "firstName": "Sukanta",
            "middleName": "Mangulu",
            "lastName": "Behera",
            "mobileNo": "9040122803",
            "reportingManagerCode": "GI2273",
            "reportingManagerName": "Aazam Ali",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "KSA",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/06/2017",
            "dateofBirth": "14/05/1989",
            "employeeStatus": "Confirmed",
            "fatherName": "Mangulu Behera",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sukantab@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "28/11/2017",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "At- Korapalli, Post  - Narayanpur, Via - Gopalpur  Dn Sea Ganjam Berhampur Odisha 761002",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "761002",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2273",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AQTPB1334R",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD169",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Jagnyaseni Ajayachandra Biswal",
            "firstName": "Jagnyaseni",
            "middleName": "Ajayachandra",
            "lastName": "Biswal",
            "mobileNo": "8895594831",
            "reportingManagerCode": "GI2193",
            "reportingManagerName": "Raju Dhiraj Vavare",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "28/08/2017",
            "dateofBirth": "15/05/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Ajaya Chandra Biswal",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "jagenyasenb@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/10/2017",
            "currentAddress": "Plot No.127,Govinda Nagar,Sahastrdhara Road,D.Dun",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Amalapada,Po-Phulbani,Distt-Kandhamala,762001",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2193",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BMMPB4100M",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2508",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Ajay Ashok More",
            "firstName": "Ajay",
            "middleName": "Ashok",
            "lastName": "More",
            "mobileNo": "9028162607",
            "reportingManagerCode": "GI2344",
            "reportingManagerName": "Chand Mohammad Ansari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Telecom",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "08/05/2017",
            "dateofBirth": "30/09/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Ashok G More",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "ajaym@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "04/11/2017",
            "currentAddress": "Jimmy Tower, Sai Mandir, Kopar Khairane Sector 18, New Mumbai, Maharashtra 400709",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400709",
            "permanentAddress": "At- Post- Mazgaon, Tal- Murud Janjira, Dist Raigad, Maharashtra 402401",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "402401",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2344",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "DTHPM3441Q",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD834",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Rahul Bijendra Kumar",
            "firstName": "Rahul",
            "middleName": "Bijendra",
            "lastName": "Kumar",
            "mobileNo": "8445292400",
            "reportingManagerCode": "GWD718",
            "reportingManagerName": "Vishal Ranjeet Singh Bhandari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Apprentice Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "14/12/2018",
            "dateofBirth": "02/04/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Bijendra Kumar",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "rahulkum@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/01/2019",
            "currentAddress": "Lane No9 Rajendra Nagar Near O N G C Chowk Dehradun",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Vill Saini Saray Deoband Sharnpur Pin-247554",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD718",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2224",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Anil Popat Kamthe",
            "firstName": "Anil",
            "middleName": "Popat",
            "lastName": "Kamthe",
            "mobileNo": "9763611937",
            "reportingManagerCode": "GW1283",
            "reportingManagerName": "Sameer Gopalrao Shinde",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "VTL",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "08/06/2015",
            "dateofBirth": "11/11/1979",
            "employeeStatus": "Confirmed",
            "fatherName": "Popat Baburao Kamthe",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "anilkam@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "05/12/2015",
            "currentAddress": "Sr. No. 209, Colony No.8, Sambhajinagar, Dighi Road, Bhosari, Pune - 411039",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "411039",
            "permanentAddress": "At-Post - Lonawala,Tal - Parner, Dist - Ahmednagar - 414307",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "414307",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1283",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BHXPK2990Q",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3671",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Priyanka Ghosh",
            "firstName": "Priyanka",
            "middleName": "",
            "lastName": "Ghosh",
            "mobileNo": "8240037673",
            "reportingManagerCode": "GI487",
            "reportingManagerName": "Durvesh Tukaram Kedari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "06/03/2019",
            "dateofBirth": "14/06/1995",
            "employeeStatus": "Relieved",
            "fatherName": "Swapan Ghosh",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "priyankaghosh5999@gmail.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "02/09/2019",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "D/O Swapan Ghosh, 44/1, Shib Chandra Chatterjee, Street, Bally (M), Belur, Bazar, Howrah, West, Bengal, 711202",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "711202",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI487",
            "bioMetricId": "",
            "dateofLeaving": "19/10/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "27/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "19/10/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3264",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Saroj Sidhnath Sah",
            "firstName": "Saroj",
            "middleName": "Sidhnath",
            "lastName": "Sah",
            "mobileNo": "9717737456",
            "reportingManagerCode": "GI2874",
            "reportingManagerName": "Venkata Suresh Narasimhaswamy Tirumala",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "20/06/2018",
            "dateofBirth": "05/09/1978",
            "employeeStatus": "Confirmed",
            "fatherName": "Sidhnath Sah",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "sarojsa@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "17/12/2018",
            "currentAddress": "Sunderam Reheja Complex,Block No. 602, Malad East . Mumbai, Maharashtra-",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Beta-1 Greater Noida, Rampur Jagir, I.A Surajpur Uttar Pradesh- 201306",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "201306",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2874",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BLMPS6115G",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "CO056",
            "salutation": "Mrs.",
            "employeeType": "Regular",
            "employeeName": "Maya Honyalkar",
            "firstName": "Maya",
            "middleName": "",
            "lastName": "Honyalkar",
            "mobileNo": "9920446611",
            "reportingManagerCode": "CO045",
            "reportingManagerName": "Tukaram Bhanudas Ature",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Assistant Manager",
            "department": "Support",
            "subDepartment": "Administration",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "24/12/2007",
            "dateofBirth": "09/08/1976",
            "employeeStatus": "Confirmed",
            "fatherName": "Balasaheb",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "maya.honyalkar@igenesys.com",
            "personalEmail": "honyalkar.maya@gmail.com",
            "noticePeriod": 30,
            "dateofConfirmation": "21/06/2008",
            "currentAddress": "Flat No 302 C Wing, Panchvati Dham Chs Ltd, Shiv Vallabh Road, Ashokvan, Dahisar East Mumbai Maharashtra 400068",
            "currentCountry": "India",
            "currentState": "Maharashtra",
            "currentCity": "Mumbai",
            "currentZip": "400068",
            "permanentAddress": "Flat No 302 C Wing, Panchvati Dham Chs Ltd, Shiv Vallabh Road, Ashokvan, Dahisar East Mumbai Maharashtra 400068",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "Mumbai",
            "permanentZip": "400068",
            "spouseName": "Balasaheb",
            "dateofMarriage": "24/04/2004",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "CO045",
            "bioMetricId": "CO0056",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "Hindu",
            "panNumber": "AUCPS5259H",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GI2215",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Aarti Vitthal Patil",
            "firstName": "Aarti",
            "middleName": "Vitthal",
            "lastName": "Patil",
            "mobileNo": "9920109650",
            "reportingManagerCode": "GW1283",
            "reportingManagerName": "Sameer Gopalrao Shinde",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "CG_Bharat Net",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "01/06/2015",
            "dateofBirth": "28/10/1991",
            "employeeStatus": "Confirmed",
            "fatherName": "Vitthal Sakharam Patil",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "aartip@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "28/11/2015",
            "currentAddress": "2/16,  Mangeshi Prasad ,Tanaji Nagar , Murbad Highway Road , Kalyan ( W)- 421306",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "421306",
            "permanentAddress": "2/16,  Mangeshi Prasad ,Tanaji Nagar , Murbad Highway Road , Kalyan ( W)- 421306",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "421306",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1283",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BZLPP1548R",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2095",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Rizwan Fakih Mohammd Khatib",
            "firstName": "Rizwan",
            "middleName": "Fakih Mohammd",
            "lastName": "Khatib",
            "mobileNo": "8879620788",
            "reportingManagerCode": "GW2293",
            "reportingManagerName": "Prakash Singh Dhapola",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Team Manager",
            "department": "Production",
            "subDepartment": "MMT",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Team Manager",
            "level": "",
            "bloodGroup": "O+",
            "gender": "Male",
            "dateofJoining": "05/02/2013",
            "dateofBirth": "02/08/1975",
            "employeeStatus": "Confirmed",
            "fatherName": "Fakih Mohammd",
            "motherName": "Aminabi",
            "maritalStatus": "Married",
            "emailAddress": "Rizwan.Khatib@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "04/08/2013",
            "currentAddress": "G4 Vinayak Krupa, Near Hdfc Bank,Opp Asmiya Super Market, Naya Nagar, Mira Road-401107.",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "401107",
            "permanentAddress": "G4 Vinayak Krupa, Near Hdfc Bank,Opp Asmiya Super Market, Naya Nagar, Mira Road-401107.",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "401107",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GW2293",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "Muslim",
            "panNumber": "AMHPK3632K",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GI2491",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Champeswar Janeswar Pradhan",
            "firstName": "Champeswar",
            "middleName": "Janeswar",
            "lastName": "Pradhan",
            "mobileNo": "8895120902",
            "reportingManagerCode": "GI2344",
            "reportingManagerName": "Chand Mohammad Ansari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Carmera",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "20/04/2017",
            "dateofBirth": "03/05/1994",
            "employeeStatus": "Confirmed",
            "fatherName": "Janeswar Pradhan",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "champeswarp@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "17/10/2017",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Parthmaha Basabadi Kandhamal Orisa 762104",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "762104",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2344",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "CTNPP4888B",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD519",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Ashish Hukum Singh Kaintura",
            "firstName": "Ashish",
            "middleName": "Hukum Singh",
            "lastName": "Kaintura",
            "mobileNo": "8445116889",
            "reportingManagerCode": "GW1253",
            "reportingManagerName": "Ramprashad Panigrahy",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "19/06/2018",
            "dateofBirth": "12/10/1994",
            "employeeStatus": "Confirmed",
            "fatherName": "Hukum Singh Kaintura",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "ashishki@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/09/2018",
            "currentAddress": "Madhav Vihar Nathuwala Near Rawat Colony Shamshergarh Raipur, Dehradun 248001",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Madhav Vihar Nathuwala Near Rawat Colony Shamshergarh Raipur, Dehradun 248001",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW1253",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "FIAPK7247E",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GWD714",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Abhay Gulab Chand Ramola",
            "firstName": "Abhay",
            "middleName": "Gulab Chand",
            "lastName": "Ramola",
            "mobileNo": "8279843340",
            "reportingManagerCode": "GWD576",
            "reportingManagerName": "Naval Kishore Pant",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "01/11/2018",
            "dateofBirth": "08/10/1997",
            "employeeStatus": "Confirmed",
            "fatherName": "Gulab Chand Ramola",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "abhayra@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/12/2018",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "H. No. 193, Vill Saud Madhye Jadipani, Po Kanatal, Tehri - 249145, Uttarakhand",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD576",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "PANNOTAVBL",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2867",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Abrar Mahmood Hiroli",
            "firstName": "Abrar",
            "middleName": "Mahmood",
            "lastName": "Hiroli",
            "mobileNo": "9768645377",
            "reportingManagerCode": "GI1201",
            "reportingManagerName": "Pritam Minanath Sarang",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "MCGM/Wonobo",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "10/05/2019",
            "dateofBirth": "13/04/1995",
            "employeeStatus": "Confirmed",
            "fatherName": "Mahmood Hiroli",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "abrarh@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "06/11/2019",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "S/O Mahmood Hiroli, Room No 22., BKC Road, Opp Chawl No 44, Bharat Nagar, Mumbai, Bandra East, Maharashtra, 400051",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI1201",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "ALOPH4940D",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3897",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Dibakar Jena",
            "firstName": "Dibakar",
            "middleName": "",
            "lastName": "Jena",
            "mobileNo": "7008437227",
            "reportingManagerCode": "GWD718",
            "reportingManagerName": "Vishal Ranjeet Singh Bhandari",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Trainee",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "25/04/2019",
            "dateofBirth": "30/04/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Surendra Jena",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "dibakarj@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "22/10/2019",
            "currentAddress": "AT/ Po - Dashamundali, PS - Sheragada, Ganjam, Odisha 761106",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "Dasamundali, Dasamundali, Dasamundali, Ganjam, Odisha, 761106",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GWD718",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "BUGPJ5986F",
            "createdDate": "08/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW2877",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Dipti Ranjan Panda",
            "firstName": "Dipti",
            "middleName": "Ranjan",
            "lastName": "Panda",
            "mobileNo": "7978776635",
            "reportingManagerCode": "GI2730",
            "reportingManagerName": "Nakka Divya",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "24/10/2017",
            "dateofBirth": "07/05/1992",
            "employeeStatus": "Relieved",
            "fatherName": "Ramesh  Panda",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "diptiranjan111@gmail.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "22/04/2018",
            "currentAddress": "NA",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "At-Cuttack Choudwar Otm Phandi Post-Choudwar Odissa 754025",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "Indian",
            "reportingManager": "GI2730",
            "bioMetricId": "",
            "dateofLeaving": "06/05/2019",
            "noticeType": "",
            "religion": "",
            "panNumber": "CJIPP7988M",
            "createdDate": "10/01/2020",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "10/01/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": "16/04/2019",
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GW3760",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Gaurav Santosh Kamble",
            "firstName": "Gaurav",
            "middleName": "Santosh",
            "lastName": "Kamble",
            "mobileNo": "9082357168",
            "reportingManagerCode": "GI2861",
            "reportingManagerName": "Nitin Vithal Kamble",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "25/03/2019",
            "dateofBirth": "18/10/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Santosh",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "gauravsank@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 0,
            "dateofConfirmation": "21/09/2019",
            "currentAddress": "Sairaj Building, Varad Siddhivinayak Hall Dombivli East Maharashtra India Mumbai Maharashtra 421201",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "421201",
            "permanentAddress": "H.N 731, Near Radha Krishna Temple, Bhuse Wadi, Dhamapur Tarf Sangameshwar, Dhamapur, Ratnagiri, Maharashtra, 415608",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "415608",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2861",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "EQXPK3854D",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2436",
            "salutation": "Ms.",
            "employeeType": "Regular",
            "employeeName": "Varsha Raju Mahendrakar",
            "firstName": "Varsha",
            "middleName": "Raju",
            "lastName": "Mahendrakar",
            "mobileNo": "8108372200",
            "reportingManagerCode": "GW619",
            "reportingManagerName": "Dr. Rajshekhar Nyamati",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Senior Executive",
            "department": "Support",
            "subDepartment": "Sales & BD",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Female",
            "dateofJoining": "19/12/2016",
            "dateofBirth": "24/05/1988",
            "employeeStatus": "Confirmed",
            "fatherName": "Raju Mahendrakar",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "Varsha.Mahendrakar@igenesys.com",
            "personalEmail": "varshalawand24@gmail.com",
            "noticePeriod": 30,
            "dateofConfirmation": "17/06/2017",
            "currentAddress": "C 501, Bhanushanti Complex, Rani Sati Marg, Pimpari Pada, Dindoshi, Gorego East, Mumbai-400097",
            "currentCountry": "India",
            "currentState": "Maharashtra",
            "currentCity": "Mumbai",
            "currentZip": "400097",
            "permanentAddress": "B-601, Hill Road Society, Jivdani Road, Virar (E), Thane-401305",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "Mumbai",
            "permanentZip": "401305",
            "spouseName": "",
            "dateofMarriage": "09/02/2011",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GW619",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "Hindu",
            "panNumber": "AEHPL7741L",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": "",
            "technical Skill": "",
            "other Skill": "",
            "vertical Domain": ""
        },
        {
            "employeeCode": "GWD630",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Upendra Devendra Singh Kanwasi",
            "firstName": "Upendra",
            "middleName": "Devendra Singh",
            "lastName": "Kanwasi",
            "mobileNo": "8938923759",
            "reportingManagerCode": "GWD576",
            "reportingManagerName": "Naval Kishore Pant",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys Worldeye Limited",
            "designation": "Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "20/08/2018",
            "dateofBirth": "06/07/1996",
            "employeeStatus": "Confirmed",
            "fatherName": "Devendra Singh Kanwasi",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "upendrak@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "01/10/2018",
            "currentAddress": "Tehri Nagar Near Bengali Kothi, Mothorowala Chowk, Dehardun, Uttarakhand",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "125A, Gweelon, Ward No-8, Gopeshwar, Chamoli, Uttarakhand-248001",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GWD576",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "HBSPK0582H",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "09/03/2020",
            "updatedByCode": "GI2774",
            "updatedByName": "Gauri",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "CO059",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Ratan Das",
            "firstName": "Ratan",
            "middleName": "",
            "lastName": "Das",
            "mobileNo": "6",
            "reportingManagerCode": "",
            "reportingManagerName": "",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Chief Financial Officer",
            "department": "Support",
            "subDepartment": "Finance & Accounts",
            "branch": "Mumbai",
            "subBranch": "Mumbai",
            "grade": "Support",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "05/02/2008",
            "dateofBirth": "30/08/1963",
            "employeeStatus": "Confirmed",
            "fatherName": "Abani Kanta Das",
            "motherName": "",
            "maritalStatus": "Married",
            "emailAddress": "ratan.das@igenesys.com",
            "personalEmail": "",
            "noticePeriod": 90,
            "dateofConfirmation": "03/08/2008",
            "currentAddress": "Flat No-602, Keshav Kunj 2, Plot No-3, Sector 15, Sanpada (E), Off Palm Beach Road, Navi Mumbai - 400075",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "400075",
            "permanentAddress": "Flat No-602, Keshav Kunj 2, Plot No-3, Sector 15, Sanpada (E), Off Palm Beach Road, Navi Mumbai - 400075",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "400075",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "",
            "bioMetricId": "CO0059",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "AABPD7066J",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "03/03/2020",
            "updatedByCode": "GW2391",
            "updatedByName": "Chitra",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        },
        {
            "employeeCode": "GI2466",
            "salutation": "Mr.",
            "employeeType": "Regular",
            "employeeName": "Tripati Prafulla Nayak",
            "firstName": "Tripati",
            "middleName": "Prafulla",
            "lastName": "Nayak",
            "mobileNo": "9777780231",
            "reportingManagerCode": "GI2193",
            "reportingManagerName": "Raju Dhiraj Vavare",
            "functionalManagerCode": "",
            "functionalManagerName": "",
            "subCompany": "Genesys International Corporation Limited",
            "designation": "Senior Executive",
            "department": "Production",
            "subDepartment": "Falcon",
            "branch": "Mumbai",
            "subBranch": "MUM-Hyderabad",
            "grade": "Operator",
            "level": "",
            "bloodGroup": "",
            "gender": "Male",
            "dateofJoining": "20/03/2017",
            "dateofBirth": "07/03/1993",
            "employeeStatus": "Confirmed",
            "fatherName": "Prafulla Nayak",
            "motherName": "",
            "maritalStatus": "Single",
            "emailAddress": "tripatin@email.igenesys.com",
            "personalEmail": "",
            "noticePeriod": 30,
            "dateofConfirmation": "16/09/2017",
            "currentAddress": "",
            "currentCountry": "India",
            "currentState": "",
            "currentCity": "",
            "currentZip": "",
            "permanentAddress": "At. Bahadaguda, Po. Dhaugaon, Via. Aska Dist Ganjam, Odisha 761110",
            "permanentCountry": "India",
            "permanentState": "",
            "permanentCity": "",
            "permanentZip": "761110",
            "spouseName": "",
            "dateofMarriage": "",
            "spouseDateofBirth": "",
            "selfService": "Y",
            "nationality": "",
            "reportingManager": "GI2193",
            "bioMetricId": "",
            "dateofLeaving": "",
            "noticeType": "DAY",
            "religion": "",
            "panNumber": "APCPN3965L",
            "createdDate": "31/12/2019",
            "createdByCode": "GW2391",
            "createdByName": "Chitra",
            "updatedDate": "05/03/2020",
            "updatedByCode": "GI1106",
            "updatedByName": "Sunil",
            "dateOfResignation": null,
            "soft Skill": null,
            "technical Skill": null,
            "other Skill": null,
            "vertical Domain": null
        }
    ]
}
        ';

        $employees = json_decode($str,true);
        // Configure::write('debug',1);
        // debug($employees);
        // exit;
        foreach ($employees['responseResult'] as $employee) {
        // debug($employee);
        $this->import_employee($employee);
      }
      exit;
    }


    public function import_employee($employee = null){

        // check if employee exixts
        
        // debug($employee);
        // exit;
        $newEmployee = array();
        $empChk = $this->Employee->find('first',array(
            'recursive'=>-1, 
            // 'fields'=>array('Employee.id','Employee.employee_number'), 
            'conditions'=>array('Employee.employee_number'=>$employee['employeeCode'])));
        if($empChk){
            // debug($empChk);
            // return $empChk['Employee']['id'];
            $newEmployee['Employee'] = $empChk['Employee'];
            $newEmployee['Employee']['id'] = $empChk['Employee']['id'];
            $newEmployee['Employee']['soft_skills'] = $employee['soft Skill'];
            $newEmployee['Employee']['technical_skills'] = $employee['technical Skill'];
            $newEmployee['Employee']['technical_skills'] = $employee['other Skill'];
            $newEmployee['Employee']['vertical_domain'] = $employee['vertical Domain'];
            $newEmployee['Employee']['current_status'] = $employee['employeeStatus'];

            if($employee['employeeStatus'] == 'Resigned')$newEmployee['Employee']['employment_status'] = 1;
            else if($employee['employeeStatus'] == 'Relieved')$newEmployee['Employee']['employment_status'] = 2;
            else $newEmployee['Employee']['employment_status'] = 0;

            $newEmployee['Employee']['joining_date'] = date('Y-m-d',strtotime(str_replace('/', '-', $employee['dateofJoining'])));
            $newEmployee['Employee']['date_of_birth'] = date('Y-m-d',strtotime(str_replace('/', '-', $employee['dateofBirth']) ));
            
            // debug($employee['dateofJoining']);
            // debug($employee['dateofBirth']);
            // debug($employee['employeeStatus']);
            debug($newEmployee);
            $this->Employee->create();
            try {
              $this->Employee->save($newEmployee);
            }catch(Exception $e) {
              debug($e);
            }
            // debug($this->Employee->save($newEmployee,false));
        }else{
            $newEmployee['Employee']['employee_number'] = $employee['employeeCode'];
            $newEmployee['Employee']['name'] = $employee['employeeName'];
            $newEmployee['Employee']['joining_date'] = date('Y-m-d',strtotime(str_replace('/', '-', $employee['dateofJoining'])));
            $newEmployee['Employee']['date_of_birth'] = date('Y-m-d',strtotime(str_replace('/', '-', $employee['dateofBirth']) ));
            $newEmployee['Employee']['office_email'] = $employee['emailAddress'];

            if($employee['employeeStatus'] == 'Resigned')$newEmployee['Employee']['employment_status'] = 1;
            else if($employee['employeeStatus'] == 'Relieved')$newEmployee['Employee']['employment_status'] = 2;
            else $newEmployee['Employee']['employment_status'] = 0;
            // $newEmployee['Employee']['parent_id'] = $this->bycode($employee['reportingManagerCode']);

            // get disgnation 
            $desg = $this->Employee->Designation->find('first',array('recursive'=>-1,'conditions'=>array('Designation.name'=>$employee['designation'])));
            if($desg){
                $newEmployee['Employee']['designation_id'] = $desg['Designation']['id'];
            }else{
                $this->Employee->Designation->create();
                $desg['Designation']['name'] = $employee['designation'];
                $desg['Designation']['prepared_by'] = $desg['Designation']['approved_by'] = $this->Session->read('User.employee_id');
                $desg['Designation']['publish'] = 1 ;
                $desg['Designation']['publish'] = 0 ;
                // debug($desg);
                // exit;
                $this->Employee->Designation->save($desg,false);
                $newEmployee['Employee']['designation_id'] = $this->Designation->id;
            }

            // get disgnation 
            $desg = $this->Employee->Department->find('first',array('recursive'=>-1, 'conditions'=>array('Department.name'=>$employee['department'])));
            if($desg){
                $newEmployee['Employee']['department_id'] = $desg['Department']['id'];
            }else{
                $this->Employee->Department->create();
                $desg['Department']['name'] = $employee['department'];
                $desg['Department']['prepared_by'] = $desg['Department']['approved_by'] = $this->Session->read('User.employee_id');
                $desg['Department']['publish'] = 1 ;
                $desg['Department']['publish'] = 0 ;
                $this->Employee->Department->save($desg,false);
                $newEmployee['Department']['department_id'] = $this->Department->id;
            }

            // get disgnation 
            $desg = $this->Employee->Branch->find('first',array('recursive'=>-1, 'conditions'=>array('Branch.name'=>$employee['branch'])));
            if($desg){
                $newEmployee['Employee']['branch_id'] = $desg['Branch']['id'];
            }else{
                $this->Employee->Branch->create();
                $desg['Branch']['name'] = $employee['branch'];
                $desg['Branch']['prepared_by'] = $desg['Branch']['approved_by'] = $this->Session->read('User.employee_id');
                $desg['Branch']['publish'] = 1 ;
                $desg['Branch']['publish'] = 0 ;
                $this->Employee->Branch->save($desg,false);
                $newEmployee['branch']['branch_id'] = $this->Branch->id;
            }
            // $newEmployee['Employee']['parent_id'] = $this->bycode($employee['reportingManagerCode']);
            // Configure::write('debug',1);
            debug($newEmployee);
            if(
                $newEmployee['Employee']['branch_id'] && 
                $newEmployee['Employee']['department_id'] && 
                $newEmployee['Employee']['designation_id'] && 
                $newEmployee['Employee']['employee_number'] &&
                $newEmployee['Employee']['name'] &&
                $newEmployee['Employee']['office_email'] &&
                $newEmployee['Employee']['joining_date'] && 
                $newEmployee['Employee']['date_of_birth']
            ){
                $this->Employee->create();
                $this->Employee->save($newEmployee,false);    
            }
            
            // return $this->Employee->id;
            
        }
        // exit;
    }

    
    public function resigned() {

        $conditions = $this->_check_request();
        // $conditions = array('Employee.employment_status'=> 1);
        $this->paginate = array('order' => array('Employee.sr_no' => 'DESC'), 'conditions' => array($conditions,'Employee.employment_status !='=> 0));

        $this->Employee->recursive = 0;
        $employees = $this->paginate();
        foreach ($employees as $employee) {
            $users = 0;
            $users = $this->Employee->User->find('count',array('conditions'=>array('User.employee_id'=>$employee['Employee']['id'])));
            $employee['User'] = $users;
            $newEmployees[] = $employee;
        }
        $this->set('employees', $newEmployees);

        $employmentStatuses = $this->Employee->customArray['employment_status'];
        $this->set('employmentStatuses', $employmentStatuses);        

        $this->_get_count();
    }    


    public function repman(){
        $str = 
"
Sagar G. Tarkar,Ravindra Shinde;
Lochan Kadam,Rupesh Pujari;
Rajesh Kumar,Sameer Gopalrao Shinde;
Jyoti Praveen ingole,Rupesh Pujari;
Manoj Kumar Nath,Ravindra Shinde;
Devanand Maurya,Sameer Gopalrao Shinde;
Sachin Shinde,Sameer Gopalrao Shinde;
Manoj Patil,Sagar G. Tarkar;
Sameer Gawade,Sagar G. Tarkar;
Kahnu Charan Badatya,Sameer Gopalrao Shinde;
Pravin G Patil,Manoj Kumar Nath;
Punam Jalindar Gunjal,Rupesh Pujari;
Vijendra Kumawat,Rajesh More;
Vilas Kshirasagar,Ravindra Shinde;
Swapnil yashawant Bagat,Sagar G. Tarkar;
D. Rakesh Raghavendra,Ravindra Shinde;
Mahendra Narayan Marotkar,Rupesh Pujari;
Santosh Chavan,Ajit Dhanawade;
Rajesh More,Ravindra Shinde;
Neha Vijay Birje,Rajesh More;
Madhuri Phaphale,Pritesh Sadashiv Pingale;
Imran Liyakat Mirkar,Sameer Gopalrao Shinde;
Sanjay Kadam,Prashant Gawand;
Prajakta Jitendra Tandel,Sameer Gopalrao Shinde;
Vishal Meshram,Sameer Gopalrao Shinde;
Devendra Gudekar,Rajesh More;
Aarti Patil,Sameer Gopalrao Shinde;
Nivas Jadhav,Rajesh More;
Somadatta Chandrabhan Sonawane ,Sameer Gopalrao Shinde;
Avinash Atmaram Patil ,Sameer Gopalrao Shinde;
Sonali Bare,Manoj Kumar Nath;
Mohd. Waseem Ansari,Manoj Kumar Nath;
Dilshad Hussain,Nagesh Digambar Nandoskar;
Pravin Revansiddha Kamble ,Sameer Gopalrao Shinde;
Swagatika Mohanty,Sameer Gopalrao Shinde;
Qayamuddin Mohammed Hussain Khan ,Sameer Gopalrao Shinde;
Sanket Ashok Haral ,Sameer Gopalrao Shinde;
Rupesh Pujari,Ravindra Shinde;
Prashant Dukhande,Sameer Gopalrao Shinde;
Govind Kanhaiya Prasad Gupta,Manoj Kumar Nath;
Harshad Mohan Shikari,Rajesh More;
Ashvini Charudatt Sarvagod,Rajesh More;
Dinesh Tatu Sawant,Rupesh Pujari;
Prashant Nanaware ,Rupesh Pujari;
Vilas Sawant,Rajesh More;
Amit Patil,Ajit Dhanawade;
Atul Madhukar Patil,Rupesh Pujari;
Prajyot Dattatrey Chavan,Rupesh Pujari;
Dinesh Dattatrey Naik,Pritesh Sadashiv Pingale;
Dnyaneshwar Mohan Sonavane,Pritesh Sadashiv Pingale;
Nilesh Bhagwan Shelar ,Pritesh Sadashiv Pingale;
Sangram Malojirao Kadam,Pritesh Sadashiv Pingale;
Soniya Dhanaji Desai,Pritesh Sadashiv Pingale;
Vaibhav Jagannath Rambade,Pritesh Sadashiv Pingale;
Dinesh Gulab Chaudhari,Pritesh Sadashiv Pingale;
Harshada Jadhav ,Pritesh Sadashiv Pingale;
Arun Bhaskar Rasam ,Pritesh Sadashiv Pingale;
Ashish Dethe,Pritesh Sadashiv Pingale;
Madhuri Anandrao Bhatuse,Pritesh Sadashiv Pingale;
Nazim parvez,Pritesh Sadashiv Pingale;
Bhavesh Barrot,Pritesh Sadashiv Pingale;
Akshay Tanaji Patil,Pritesh Sadashiv Pingale;
Prashant Dattatray Kumbhar,Pritesh Sadashiv Pingale;
Vikas Walunj,Pritesh Sadashiv Pingale;
Amay Rane,Pritesh Sadashiv Pingale;
Sonali Keshav Ghadi ,Pritesh Sadashiv Pingale;
Sanjay Gajanan Patil,Sameer Gopalrao Shinde;
Samir P. Rahate,Sameer Gopalrao Shinde;
Nilesh Ganpat Phapale,Sameer Gopalrao Shinde;
Swapnil Adangale,Sameer Gopalrao Shinde;
Yogesh Lahu Jadhav,Sameer Gopalrao Shinde;
Umesh Tambe,Sameer Gopalrao Shinde;
Udatha Sreenivasulu,Pritesh Sadashiv Pingale;
Gorakh Borse,Sameer Gopalrao Shinde;
Abhimanu Pradhan ,Sameer Gopalrao Shinde;
Siddheshwar Bayaji Bhise,Sameer Gopalrao Shinde;
Amrapali Pradip kambale,Pritesh Sadashiv Pingale;
Abhay Gedam,Sameer Gopalrao Shinde;
Amol Sampatrao Jagtap,Sameer Gopalrao Shinde;
Arvind  Baliram Devkatte,Sameer Gopalrao Shinde;
Jally Behera,Sameer Gopalrao Shinde;
Vikrant Vilas Kadam,Sameer Gopalrao Shinde;
Prasad Sanjay More,Sameer Gopalrao Shinde;
Sampada Dattaram Narvekar,Sameer Gopalrao Shinde;
Mahendra Satpute,Sameer Gopalrao Shinde;
Suresh Bisen,Sameer Gopalrao Shinde;
Manoj R Bhoir,Sameer Gopalrao Shinde;
Anil Kamthe,Sameer Gopalrao Shinde;
Pooja Nakade,Sameer Gopalrao Shinde;
Ganesh Gore ,Sameer Gopalrao Shinde;
Sudhir Anavakar ,Sameer Gopalrao Shinde;
Meenino Raju Dorairaj,Sameer Gopalrao Shinde;
Samir P Vyapari ,Chand Mohammad Ansari;
Sandeep Sitaram More,Chand Mohammad Ansari;
Praveenkumar K Kadam ,Chand Mohammad Ansari;
Ajay More ,Chand Mohammad Ansari;
Basith Kedvedkar,Chand Mohammad Ansari;
Sharad Shivaji Karande,Sameer Gopalrao Shinde;
Renuka Eknath Perker,Sameer Gopalrao Shinde;
Swati Sonwane,Sameer Gopalrao Shinde;
Bajirao Shivajirao Waghmode ,Sameer Gopalrao Shinde;
Selina Suresh Pawar ,Sameer Gopalrao Shinde;
Bhimrao D Bhandare,Sameer Gopalrao Shinde;
Rajpal Jaysing Girase ,Sameer Gopalrao Shinde;
Vinod Jagannath Karande,Sameer Gopalrao Shinde;
Sachin Turde,Sameer Gopalrao Shinde;
Aparna Narvekar,Sameer Gopalrao Shinde;
Savita Poojari,Sameer Gopalrao Shinde;
Ramsingh Pratapsingh Katre ,Rajesh More;
Nikhil Jani,Kuldeep Moholkar;
Prashant Gawand,Nikhil Jani;
Mahesh Bhatia,Nikhil Jani;
Ansari Najeeb,Nikhil Jani;
Ravindra Shinde,Prashant Gawand;
Girish Kiradoo,Nikhil Jani;
Sameer Gopalrao Shinde,Mahesh Bhatia;
Ajit Dhanawade,Ravindra Shinde;
Pritesh Sadashiv Pingale,Ansari Najeeb;
Mahesh Daji Padwal,Mahesh Bhatia;
Saheb Hussain ,Sameer Gopalrao Shinde;
Apurva Subhash Juikar ,Rupesh Pujari;
Vaibhav Atmaram Tamhankar ,Sameer Gopalrao Shinde;
Jit Jagdish Patil ,Sameer Gopalrao Shinde;
Nazim Jainuddin Gadkari ,Sameer Gopalrao Shinde;
Mohammed Samaak Ismail Rajpurkar ,Pritesh Sadashiv Pingale;
Madhavi Vaman Harkulkar ,Rupesh Pujari;
Kalpana Swain,Chand Mohammad Ansari;
Arman Gaonkhadkar ,Rajesh More;
Rani Pardhe ,Sameer Gopalrao Shinde;
Siddhesh Santosh Nakti ,Rajesh More;
Sakib Alauddin Mulla ,Sameer Gopalrao Shinde;
Mahadev Yashavant Kalebag ,Sameer Gopalrao Shinde;
Dayanand A. Gaganwane ,Sameer Gopalrao Shinde;
Anjal Allauddin Solkar ,Sameer Gopalrao Shinde;
Jagadish Padhy ,Sameer Gopalrao Shinde;
Suvarna H. Wakchaure ,Sameer Gopalrao Shinde;
Snehal Aniket Gujar,Sameer Gopalrao Shinde;
Avinash Zunjar ,Sameer Gopalrao Shinde;
Ashok Ramdas Jogdand ,Sameer Gopalrao Shinde;
Dipak Patil ,Sameer Gopalrao Shinde;
Anant Padurang Dhulap ,Sameer Gopalrao Shinde;
Mohammad Shafik Alli Gaibi,Pritesh Sadashiv Pingale;
Juned Siraj Kalu,Sameer Gopalrao Shinde;
Bhimrao Vhanoli,Sameer Gopalrao Shinde;
Aakif Shabbir Hodekar ,Sagar G. Tarkar;
Shahabaj Mustaq Davat ,Sameer Gopalrao Shinde;
Kailash Changdeo Karne ,Sameer Gopalrao Shinde;
Swapnil Chavan,Vilas Kshirasagar;
Masooda Moinuddin Solkar,Pritesh Sadashiv Pingale;
Ubaid Jalil Mukadam,Sameer Gopalrao Shinde;
Sandeep A Kulkarni ,Sameer Gopalrao Shinde;
Sandip Dattu Sonawane ,Sameer Gopalrao Shinde;
Siddhesh More,Sameer Gopalrao Shinde;
Vaibhav D Kap ,Rupesh Pujari;
Sital Kumari Sahu ,Chand Mohammad Ansari;
Champeswar Pradhan ,Chand Mohammad Ansari;
A. Pooja Patra ,Chand Mohammad Ansari;
Narayan Nayak ,Chand Mohammad Ansari;
Juned Khan ,Chand Mohammad Ansari;
B N Dubey,Sameer Gopalrao Shinde;
Vinod Bapu Kumbhar,Chandra Sekhar Mandal;
Faraz Mujeeb Kotawdekar,Chandra Sekhar Mandal;
Chinmay Kumar Patanayak ,Chandra Sekhar Mandal;
Nilambar Sahoo,Chandra Sekhar Mandal;
Rakesh Appaso Kamble,Chandra Sekhar Mandal;
Videsh Pawar,Ravindra Shinde;
";

    $emps = split(';', $str);

    // Configure::write('debug',1);
    // debug($emps);
    foreach ($emps as $emp) {
        $employees = split(',', $emp);
        $employee = ltrim(rtrim($employees[0]));
        $reportsTo = ltrim(rtrim($employees[1]));
        
        if($employee){
            $id = $this->Employee->find('first',array('recursive'=>-1, 'conditions'=>array('Employee.name'=>$employee)));
            $rid = $this->Employee->find('first',array('recursive'=>-1, 'conditions'=>array('Employee.name'=>$reportsTo)));
            if($id && $rid){
                $this->Employee->create();
                $id['Employee']['parent_id'] = $rid['Employee']['id'];
                $this->Employee->create();
                $this->Employee->save($id,false);

            }
        }
    }
    exit;

    }


    public function link_emps(){
$str = "
GI2258  GI317   2015/11/06
GI2259  GI317   2015/11/06
GI2578  GW176   2017/09/20
GI2597  GW176   2017/11/01
GI2601  GI317   2017/01/11
GI2784  GW176   2019/02/22
GI2892  GI317   2022/03/03
GI2893  GI317   2021/01/11
GI2894  GI317   2021/11/08
GI2910  GW176   2022/02/08
GI2911  GW176   2022/02/08
GI2912  GW176   2022/02/08
GI2914  GW176   2022/02/08
GI2915  GW176   2022/02/08
GI2916  GW176   2022/02/08
GI2917  GW176   2022/02/08
GI2918  GI317   2022/02/08
GI2919  GW176   2022/02/08
GI2920  GW176   2022/02/08
GI2921  GW176   2022/02/07
GI2922  GW176   2021/02/07
GI2923  GI317   2022/02/08
GI2924  GI317   2022/02/08
GI2925  GW176   2022/02/08
GI2926  GW176   2022/02/08
GI2927  GW176   2022/02/08
GI2934  GW176   2022/02/22
GI2935  GI317   2022/02/22
GI2936  GI317   2022/02/22
GI2937  GW176   2022/02/22
GI2938  GW176   2022/02/22
GI2940  GW176   2022/02/22
GI2943  GI317   2022/02/22
GI2946  GI317   2022/02/22
GI2952  GI317   2022/03/03
GI2954  GI317   2022/03/03
GI2957  GI317   2022/03/03
GI2961  GW176   2022/03/03
GI2962  GI317   2022/03/03
GI2963  GI317   2022/03/03
GI2964  GI317   2022/03/03
GI317   GW1283  2001/03/30
GW1051  GI317   2012/01/20
GW1286  GI317   2012/05/02
GW1571  GI317   2012/12/06
GW176   GW1283  2010/05/10
GW2210  GW176   2012/12/03
GW2330  GW176   2017/03/01
GW2536  GI317   2017/05/15
GW2655  GI317   2017/10/07
GW2970  GI317   2017/11/20
GW3149  GI317   2018/05/24
GW3583  GI317   2019/02/01
GW3584  GI317   2019/02/01
GW3599  GW176   2019/02/08
GW3630  GW176   2019/02/15
GW3642  GI317   2019/02/15
GW3654  GW176   2019/02/22
GW3665  GI317   2019/02/22
GW4026  GW176   2021/01/01
GW4091  GI317   2021/09/20
GW4103  GI317   2021/10/25
GW4107  GW176   2021/11/01
GW4113  GW176   2021/11/08
GW4115  GI317   2021/11/08
GW4119  GI317   2021/11/08
GI3011  GW176   2022/04/01
GI3012  GW176   2022/04/01
GI3014  GW176   2022/04/01
GI3015  GW176   2022/04/01
GI3016  GW176   2022/04/01
GI3017  GW176   2022/04/01
GI3018  GW176   2022/04/01
GI3019  GW176   2022/04/01
GI3020  GW176   2022/04/01
GI3021  GI317   2022/04/01
GI3022  GW176   2022/04/01
GI3023  GI317   2022/04/01
";
Configure::write('debug',1);

$emps = explode(PHP_EOL,$str);

foreach($emps as $e){
    if($e){
        $employee = explode('  ',$e);
        // debug($employee);  
        $employee_number = $employee[0];
        $parent_employee_number = $employee[1];
        $dateofjoining = date('Y-m-d',strtotime(ltrim(rtrim($employee[2]))));
        // debug($dateofjoining);
        $pempid = $this->Employee->find('first',array('recursive'=>-1, 'conditions'=>array('Employee.employee_number'=>$parent_employee_number),'fields'=>array('Employee.id','Employee.name','Employee.employee_number')));
        debug($pempid);
        $ee = $this->Employee->find('first',array('recursive'=>-1, 'conditions'=>array('Employee.employee_number'=>$employee_number)));
        debug($ee);
        $ee['Employee']['parent_id'] = $pempid['Employee']['id'];
        $ee['Employee']['joining_date'] = $dateofjoining;
        debug($ee);
        $this->Employee->create();
        $this->Employee->save($ee,false);
    }    
}

exit;
    }

    public function bulk_import(){
$emps = "Umesh Tambe,GI2213,57346c3f-7ae8-4dfd-96b6-0291db1e6cf9,5ff4b969-4cbc-40f5-8249-08a0db1e6cf9,57459fb5-8430-43b5-ad27-05b2db1e6cf9,42156,umesht@email.igenesys.com,Confirmed,Sameer Shinde";

    
    $employees = explode(PHP_EOL,$emps);
    // $employees = explode($emps, PHP_EOL);
    debug($employees);
    foreach($employees as $employee){
        $vals = explode(',',$employee);
        // debug($vals);
        $data = array();
        $data['Employee']['name'] = $vals[0];
        $data['Employee']['employee_number'] = $data['Employee']['indentification_number'] = $vals[1];
        $data['Employee']['branch_id'] = $vals[2];
        $data['Employee']['department_id'] = $vals[3];
        $data['Employee']['designation_id'] = $vals[4];
        $data['Employee']['office_email'] = $vals[6];
        $data['Employee']['employment_status'] = 1;
        $data['Employee']['prepared_by'] = '5ff4bffa-9fb0-48ba-9db3-1957db1e6cf9';
        $data['Employee']['publish'] = 1;
        $data['Employee']['soft_delete'] = 0;

        // debug($data);
        // check if employee already exists;
        $empChk = $this->Employee->find('count',array('conditions'=>array(
            'OR'=>array(
                'Employee.office_email'=>$data['Employee']['office_email'],
                'Employee.employee_number'=>$data['Employee']['employee_number'],                
            )            
        )));

        if($empChk == 0){
            $this->Employee->create();
            try{
                $this->Employee->save($data,false);    
            }catch (Exception $e){
                debug($e);
                continue;
            }
        }else{
            echo $data['Employee']['name'] . " exits.<br />";
        }
    }
exit;
    }

    public function employee_files($employee_id = null, $project_id = null, $milestone_id = null){

        $this->loadModel('FileProcess');
        
        // $this->loadModel('FileProcess');

        $this->FileProcess->virtualFields = array(
            'fname'=>'select name from project_files where project_files.id LIKE FileProcess.project_file_id'
        );

        $files = $this->FileProcess->find('list',array(
            'conditions'=>array(
                'FileProcess.employee_id'=>$this->request->params['pass'][0],
                'FileProcess.project_id'=>$this->request->params['named']['project_id'],
                'FileProcess.milestone_id'=>$this->request->params['named']['milestone_id'],
            ),
            
            'fields'=>array(
                'FileProcess.project_file_id',
                'FileProcess.fname',
            )
        ));

        // Configure::write('debug',1);
        // debug($employee_id);
        // debug($project_id);
        // debug($milestone_id);

        
        foreach ($files as $key => $value) {
            $this->FileProcess->virtualFields = array(
                // 'sum_total'=>'select TIMEDIFF(end_time,start_time) from file_processes where start_time IS NOT NULL AND end_time IS NOT NULL AND employee_id LIKE "'.$this->request->params['pass'][0].'" AND project_file_id LIKE FileProcess.project_file_id',

                'sum_total' => 'select TIMEDIFF(end_time,start_time)',
                
                'sum_hold'=>'select TIMEDIFF(hold_end_time,hold_start_time) from file_processes where file_processes.id = FileProcess.id',
                
                'start_delay'=>'select TIMEDIFF(start_time,created) from file_processes where file_processes.id = FileProcess.id'
            );

            $user_id = $this->FileProcess->Employee->User->find('first',array(
                'fields'=>array('User.id'),
                'recursive'=>-1,
                'conditions'=>array('User.employee_id'=>$this->request->params['pass'][0])
            ));

            $edata[$value] = $this->FileProcess->find('all',array(
                'conditions'=>array(
                    'or'=>array(
                        'FileProcess.employee_id'=>$this->request->params['pass'][0],
                        // 'FileProcess.modified_by'=>$user_id['User']['id'],
                    ),
                    
                    'FileProcess.project_id'=>$this->request->params['named']['project_id'],
                    'FileProcess.milestone_id'=>$this->request->params['named']['milestone_id'],
                    'FileProcess.project_file_id' => $key
                ),
                
                // 'fields'=>array(
                //     'FileProcess.project_file_id',
                //     'FileProcess.project_id',
                // )
            ));
        }

        
        debug($edata);
        $this->set('edata',$edata);
        
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


        $currentStatuses = $this->FileProcess->ProjectFile->customArray['currentStatuses'];
        $this->set('currentStatuses',$currentStatuses);
        // debug($this->request->params);

        return count($edata);
        // exit;
    }


    public function update_cost(){
        // Configure::write('debug',1);
        // debug($this->request->data);
        $this->autoRender = false;
        if($this->request->data['pk'] && is_numeric($this->request->data['value'])){
            $employee = $this->Employee->find('first',array('recursive'=>-1,'conditions'=>array('Employee.id'=>$this->request->data['pk'])));
            $employee['Employee']['resource_cost'] = $this->request->data['value'];
            $this->Employee->create();
            $this->Employee->save($employee,false);
            return true;
        }
        exit;
    }


    public function cost(){
        if ($this->request->is('post')) {
            // Configure::write('debug',1);
            // debug($this->request->data['Employee']['data']);
            // foreach ($this->request->data['Employee']['data'] as $costdata) {
                $newdatas = split(PHP_EOL, $this->request->data['Employee']['data']);
                foreach ($newdatas as $newdata) {
                    $employee = null;
                    $data = split(',', $newdata);
                    $employee = $this->Employee->find('first',array(
                        'recursive'=>-1,
                        'conditions'=>array('Employee.employee_number'=>ltrim(rtrim($data[0])))
                    ));

                    if($employee){
                        $employee['Employee']['resource_cost'] = ltrim(rtrim($data[1]));
                        $this->Employee->create();
                        $this->Employee->save($employee,false);
                        // debug($employee);

                    }
                }
            // }
            // exit;
            $this->Session->setFlash(__('Updated'), 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('action' => 'index'));
        }else{
                $this->Session->setFlash(__('Add Data'), 'default', array('class' => 'alert alert-success'));
                // $this->redirect(array('action' => 'index'));
            }
    }
}

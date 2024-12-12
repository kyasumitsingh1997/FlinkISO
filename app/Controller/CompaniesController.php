<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * Companies Controller
 *
 * @property Company $Company
 */
class CompaniesController extends AppController {

public function _get_system_table_id($controller = NULL) {
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $controller)));
        return $systemTableId['SystemTable']['id'];
    }

/**
 * request handling by - TGS
 *
 */
/**
 * _check_request method
 *
 * @return void
 */


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Company->exists($id)) {
			throw new NotFoundException(__('Invalid company'));
		}
		$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $id));
		$this->set('company', $this->Company->find('first', $options));


        $document_types = $this->_get_specials()['Dashboard Files'];
        $this->set('document_types', $document_types);        
	}



/**
 * 
 * 
 * 
 * 
 * 
 * 
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Company->exists($id)) {
			throw new NotFoundException(__('Invalid company'));
		}
		if($this->_show_approvals()){
			$this->loadModel('User');
			$this->User->recursive = 0;
			$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
			$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
		}


		if ($this->request->is('post') || $this->request->is('put')) {
			$company_name = $this->_get_company();
                        $this->request->data['Company']['system_table_id'] = $this->_get_system_table_id();
			$this->request->data['Company']['name'] = $company_name['Company']['name'];
                        $this->loadModel('Branch');
                        $this->Branch->recursive = 0;
			$this->request->data['Company']['number_of_branches'] = $this->Branch->find('count',array('conditions'=>array('Branch.soft_delete'=>0, 'Branch.publish'=>1)));

                        if(($this->request->data['Company']['logo'] == 1) && isset($this->request->data['Company']['company_logo']['error']) && $this->request->data['Company']['company_logo']['error'] == 0){
                            $file = new File($this->request->data['Company']['company_logo']['name'], FALSE);
                            $fileinfo = $file->info();

                            if (filesize($this->request->data['Company']['company_logo']['tmp_name']) > 5000000){
                                $this->Session->setFlash(__('Uploaded file exceeds maximum upload size limit. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            // if (!preg_match("`^[-0-9A-Z_\.]+$`i",$fileinfo['basename'])){
                            //     $this->Session->setFlash(__('Logo file name contains invalid characters. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                            //     $this->redirect(array('action' => 'view', $id));
                            // }

                            if (mb_strlen($fileinfo['basename'],"UTF-8") > 225){
                                $nameLengthCheck = false;
                                $this->Session->setFlash(__('Logo file name is too long. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            if(!in_array($fileinfo['extension'], array('gif','jpg','jpe','jpeg','png'))){
                                $this->Session->setFlash(__('Logo file type is invalid. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }

                            if(!file_exists(WWW_ROOT . DS . 'img' . DS . 'logo')){
                                new Folder(WWW_ROOT . DS . 'img' . DS . 'logo', TRUE, 0777);
                            }

                         $moveLogo = move_uploaded_file($this->request->data['Company']['company_logo']['tmp_name'], WWW_ROOT . DS . 'img' . DS . 'logo' . DS . $fileinfo['basename']); //die;
                         if($moveLogo){

                         }else{
                            // echo "Asdad";
                         }
                         
                            if($moveLogo){
                                $dir_name = WWW_ROOT . DS . 'img' . DS . 'logo' ;
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
                                        case 'image/jpeg':
                                            $source = imagecreatefromjpeg($fileinfo['basename']);
                                            break;
                                        case 'image/gif';
                                            $source = imagecreatefromgif($fileinfo['basename']);
                                            break;
                                        case 'image/png';
                                            $source = imagecreatefrompng($fileinfo['basename']);
                                            break;
                                    }
                                    $dest = imagecreatetruecolor($newwidth, $newheight);
                                    imagealphablending($dest, false);
                                    imagesavealpha($dest, true);
                                    imagecopyresampled($dest, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                                    switch ($format) {
                                        case 'image/jpeg':
                                            imagedestroy($source);
                                            @imagejpeg($dest, $fileinfo['basename'], 100);
                                            imagedestroy($dest);
                                            break;
                                        case 'image/gif';
                                            imagedestroy($source);
                                            @imagejpeg($dest, $fileinfo['basename'], 100);
                                            imagedestroy($dest);
                                            break;
                                        case 'image/png';
                                            imagedestroy($source);
                                            @imagepng($dest, $fileinfo['basename'], 9);
                                            imagedestroy($dest);
                                            break;
                                    }
                                }
                                $oldLogo = $this->Company->find('first', array('conditions' => array('Company.id' => $id), 'fields' => array('Company.company_logo')));
                                if(!empty($oldLogo)){
                                    $oldLogoFile = new File(WWW_ROOT . DS . 'img' . DS . 'logo'. DS . $oldLogo['Company']['company_logo']);
                                    //$oldLogoFile->delete();
                                }

                                $this->request->data['Company']['company_logo'] = $fileinfo['basename'];
                            } else {
                                $this->Session->setFlash(__('Logo upload was not successful. Please try again.'), 'default', array('class' => 'alert alert-danger'));
                                $this->redirect(array('action' => 'view', $id));
                            }
                        } else if(($this->request->data['Company']['logo'] == 1) && isset($this->request->data['Company']['company_logo']['error']) && $this->request->data['Company']['company_logo']['error'] == 1){
                            $this->Session->setFlash(__('The uploaded file exceeds specified maximum file size. Contact your system administrator and try again.'), 'default', array('class' => 'alert alert-danger'));
                            $this->redirect(array('action' => 'view', $id));
                        } else if($this->request->data['Company']['logo'] == 0) {
                            $oldLogo = $this->Company->find('first', array('conditions' => array('Company.id' => $id), 'fields' => array('Company.company_logo')));
                            if(!empty($oldLogo)){
                                $oldLogoFile = new File(WWW_ROOT . DS . 'img' . DS . 'logo'. DS . $oldLogo['Company']['company_logo']);
                                $oldLogoFile->delete();
                            }
                            $this->request->data['Company']['company_logo'] = '';
                        }else{
                            unset( $this->request->data['Company']['company_logo'] );
                        }

			if ($this->Company->save($this->request->data)) {
				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Company';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Company->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The company has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$id));
				else $this->redirect(array('action' => 'view',$id));
			} else {
				$this->Session->setFlash(__('The company could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $id));
			$this->request->data = $this->Company->find('first', $options);
		}
		$systemTables = $this->Company->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Company->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$schedules = $this->Company->Schedule->find('list');

		$this->set(compact('systemTables', 'masterListOfFormats','schedules'));
		$count = $this->Company->find('count');
		$published = $this->Company->find('count',array('conditions'=>array('Company.publish'=>1)));
		$unpublished = $this->Company->find('count',array('conditions'=>array('Company.publish'=>0)));

		$this->set(compact('count','published','unpublished'));
	}

    private function _sql_query($flag = Null) {
        $db = ConnectionManager::getDataSource('default');
        if(isset($flag) && $flag == 'insert')
            $path = WWW_ROOT . "DB" . DS . "insert.sql";
        if(isset($flag) && $flag == 'remove')
            $path = WWW_ROOT . "DB" . DS . "delete.sql";
        $fileName = new File($path);
        if($fileName)
        {
            $statements = $fileName->read();
            $statements = explode(';', $statements);
            $this->loadModel('User');
            $prefix = $this->User->tablePrefix;
            foreach ($statements as $statement) {
                if (trim($statement) != '') {
		   $statement = str_replace("TRUNCATE TABLE `", "TRUNCATE TABLE `$prefix",  $statement);
                   $query =  $db->query($statement);
                }
            }
            return TRUE;
        }else{
            return FALSE;
        }
    }
    public function remove_sample($id = null) {
        $flag = 'remove';
        if($this->_sql_query($flag)){
            $this->loadModel('Company');
            $this->Company->updateAll(array('Company.sample_data'=>0));
            $this->Session->setFlash('All data removed from Database succesfully');
        }
        else{
            $this->Session->setFlash('All data is not removed from Database');
        }
         $this->redirect(array('action'=>'view',$id));
    }

    public function pdf_header(){
        
        if ($this->request->is('post') || $this->request->is('put')) {
// header for active file
$str = 
'<!DOCTYPE html>
    <html><head>
    <style>body{ 
            font-size:10px; color:#262626; font:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif} 
    </style>
    </head><body style="border:0; margin: 0;">';
    $str .= $this->request->data['Company']['qc_header'];
$str .= '</body></html>';            

            $header_file = WWW_ROOT . DS . 'files' . DS . 'pdf_header.html';
            $myfile = fopen($header_file, "w") or die("Unable to open file!");
            
            fwrite($myfile, $str);
            fclose($myfile);
            
// header for archived files
$str = 
'<!DOCTYPE html>
    <html><head>
    <style>body{ 
            font-size:10px; color:#262626; font:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif} 
    </style>
    </head><body style="border:0; margin: 0;">';
    $str .= $this->request->data['Company']['qc_header'];
    $str .= '<span style="font-color:red;font-size:8px;font-weight:bold;width:100%;text-align:center">This file is archived.</span>';
$str .= '</body></html>';            

            $pdf_header_archived = WWW_ROOT . DS . 'files' . DS . 'pdf_header_archived.html';
            $myfile = fopen($pdf_header_archived, "w") or die("Unable to open file!");
            
            fwrite($myfile, $str);
            fclose($myfile);


            $this->Session->setFlash(__('Header is added successfully'));
            $this->redirect(array('action' => 'pdf_header'));            
        } 
        $header_file = WWW_ROOT . DS . 'files' . DS . 'pdf_header.html';
        // $header = fread($header_file);
        $file = new File($header_file);
        $contents = $file->read();
        // $file->write('I am overwriting the contents of this file');
        // $file->append('I am adding to the bottom of this file.');
        // $file->delete(); // I am deleting this file
        $file->close(); // Be sure to close the file when you're done
        
        $this->set('pdf_header',$contents);

    }


}

<?php
/**
 *
 * Dual-licensed under the GNU GPL v3 and the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Suman (srs81 @ GitHub)
 * @package       plugin
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *                and/or GNU GPL v3 (http://www.gnu.org/copyleft/gpl.html)
 */
 
class FileUploadsController extends AjaxMultiUploadAppController {

	public $name = "Upload";
	public $uses = null;

	// list of valid extensions, ex. array("jpeg", "xml", "bmp")
	public $allowedExtensions = array();

	public function upload($dir=null,$flag=null) {
		if(empty($_SESSION['User']['company_id']))exit;
		// max file size in bytes
		$size = Configure::read ('AMU.filesizeMB');
		if (strlen($size) < 1) $size = 4;
		$relPath = Configure::read ('AMU.directory');
		if (strlen($relPath) < 1) $relPath = "files". DS . $_SESSION['User']['company_id'];

		$sizeLimit = $size * 1024 * 1024;
                $this->layout = "ajax";
		$directory = Configure::read('MediaPath'). $relPath;
 
		if ($dir === null) {
			$this->set("result", "{\"error\":\"Upload controller was passed a null value.\"}");
			return;
		}
		// Replace underscores delimiter with slash
		$dir = str_replace ("___", DS , $dir);
		// dir for saving in model
		$get_dir = $dir;
		$dir = $directory . DS . "$dir" . DS ;
		
		if (!file_exists($dir)) {
			mkdir($dir, 0755, true);
		}
		if($flag==1)$allowedExtensions = array('xls','xlxs');
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		// check if the file is under revision 

		$this->loadModel('FileUpload');
		$a = split($this->Session->read('User.company_id'), $dir);
		$a = split( DS , $a[1]);
		
		$extantion = substr(strrchr($this->request->query['qqfile'], "."), 1);
		$filename = str_replace($extantion, '', $this->request->query['qqfile']);	
		$filename = str_replace(' ','',$filename);
		$filename = str_replace('.','',$filename);
		$filename = preg_replace('/\s+/', '',$filename);
		
		if($a[1]=='documents' && $a[2] == NULL){
			// echo "1";
		    $newFileUploadData['FileUpload']['system_table_id'] = $this->_get_system_table('users');
		    if(!$a[3])$newFileUploadData['FileUpload']['record'] = $a[2];
		  }elseif($a[1]=='clauses'){
		  	// echo "2";
		    $newFileUploadData['FileUpload']['system_table_id'] = "clauses";
		    $newFileUploadData['FileUpload']['record'] = $a[2];
		  }elseif($a[1]=='documents' && $a[2] != NULL){
		  	// echo "3";
		    $newFileUploadData['FileUpload']['system_table_id'] = 'dashboards';
		    if(!$a[3])$newFileUploadData['FileUpload']['record'] = $a[2];
		  }elseif($a[1]=='products' && $a[2] != NULL){
		  	// echo "4";
		    $newFileUploadData['FileUpload']['system_table_id'] = $this->_get_system_table($a[1]);
		    $newFileUploadData['FileUpload']['record'] = $a[2];            
		  }else{
		  	// echo "5";
		    // if(!$this->_get_system_table($a[2]))$newFileUploadData['FileUpload']['system_table_id'] = 'dashboards';
		    // else 
		    	$newFileUploadData['FileUpload']['system_table_id'] = $this->_get_system_table($a[3]);
		    
		    if(!$a[3])$newFileUploadData['FileUpload']['record'] = $a[2];
		    else $newFileUploadData['FileUpload']['record'] = $a[3];
		  }

		$extantion = str_replace('.','',  $extantion);
		
		$conditions = array(
				'FileUpload.record' => $a[4],
				'FileUpload.system_table_id' => $newFileUploadData['FileUpload']['system_table_id'],
				'FileUpload.file_details LIKE ' => '%'. $filename .'ver-%',
				'FileUpload.file_type LIKE ' => '%'. $extantion,
				'FileUpload.archived'=> 0
				);
		
		$findFile = $this->FileUpload->find('count',array(
			'conditions'=>$conditions
			));
		
		if($findFile == 0){
			$users = $this->_get_user_list();
			$result = $uploader->handleUpload($dir,FALSE,$flag,$users);	
			$this->set("result", htmlspecialchars(json_encode($result), ENT_NOQUOTES));
			$this->_upload_add($result['details']['filename'],$result['details']['ext'],$result['details']['message'],$get_dir,null);	
		}else{
			$result = array('error'=> 'Could not save uploaded file. File is under revision. ' . 'The upload was cancelled','details'=>array('filename'=>$filename,'size'=>$size,'ext'=>$ext,'message'=>'Issue'),'preload'=>$flag);
			$this->set("result", htmlspecialchars(json_encode($result), ENT_NOQUOTES));
		}

	}

	/**
	 *
	 * delete a file
	 *
	 * Thanks to traedamatic @ github
	 */
	public function delete($id = NULL, $parent_id = NULL) {
		
				$this->loadModel('FileUpload');
				$this->FileUpload->recursive = -1;
				$conditions = array('FileUpload.id'=>$this->request->params['pass']['0']);
				$file_find = $this->FileUpload->find('first', array('conditions'=> $conditions));
				$data['id'] = $file_find['FileUpload']['id'];
				$data['publish'] = 1;
				$data['file_status'] = 0;
				$data['result'] = 'File Deleted';
                            if($this->FileUpload->save($data)) {
				$file = Configure::read('MediaPath').'files' . DS . $this->Session->read('User.company_id') . DS . $file_find['FileUpload']['file_dir'];
				unlink($file);
				$this->Session->setFlash(__('File deleted!'));				
		} else {
				$this->Session->setFlash(__('Unable to delete File'));					
		}
		$this->redirect($this->referer());	
	}

	public function _get_user_list() {
		$this->loadModel('User');
		$users = $this->User->find('list', array('conditions' => array('User.soft_delete'=> 0,'User.publish'=>1)));
		$this->set('PublishedUserList', $users);
		return ($users);
	}
}

?>

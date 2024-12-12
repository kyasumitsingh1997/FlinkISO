<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){
            //return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file(str_replace(' ','',$_FILES['qqfile']['tmp_name']), $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return preg_replace('/\s+/', '',$_FILES['qqfile']['name']);
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct($allowedExtensions = array(), $sizeLimit = 10485760){
        $allowedExtensions = array();
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings(){
       /* $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        } */
    }

    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }
    function delete_all_between($beginning, $end, $string) {
      $beginningPos = strpos($string, $beginning);
      $endPos = strpos($string, $end);
      if ($beginningPos === false || $endPos === false) {
        return $string;
      }

      $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

      return str_replace($textToDelete, '', $string);
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE,$flag=NULL,$users = null){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory $uploadDirectory isn't writable. Please ask server admin to change permissions.");
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty!');
        }
/*
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large. Please ask server admin to increase the file upload limit.');
        }
*/
        $pathinfo = pathinfo(preg_replace('/\s+/', '',$this->file->getName()));
        $filename = $pathinfo['filename'].'-ver-';
		$filename=str_replace(' ','',$filename);	
		$filename=str_replace('.','',$filename);	
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        // latest 04/06/2017
        if(!$replaceOldFile){
        	$dir = new Folder($uploadDirectory);
			$newFile = explode('-ver-',$filename);
			$rev = str_replace('upload','revisions',$uploadDirectory);
            foreach ($users as $key => $value) {
                $rev = str_replace($key.DS, '', $rev);
            }
            // $rev = $this->delete_all_between('revisions',DS,$rev);
			$dir = new Folder($rev, true, 0777);
            
            $Cfile = new File($rev . DS . '.'.$newFile[0]);
			$cnt = $Cfile->read();
            $cnt = $cnt + 1;
            $Cfile->write($cnt,'w',true);						
            $filename = $newFile[0] . '-ver-' . $cnt;
        }
        	
		if($cnt == 1){
            if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
                return array('success'=>true,'details'=>array('filename'=>$filename,'size'=>$size,'ext'=>$ext,'message'=>'File uploaded'),'preload'=>$flag);
            } else {
                return array('error'=> 'Could not save uploaded file.' . 'The upload was cancelled, or server error encountered','details'=>array('filename'=>$filename,'size'=>$size,'ext'=>$ext,'message'=>'Issue'),'preload'=>$flag);
            }    
        }else{
                $Cfile = new File($rev . DS . '.'.$newFile[0]);
                $cnt = $Cfile->read();
                $cnt = $cnt - 1;
                $Cfile->write($cnt,'w',true);                       
                return array('error'=> 'File already exists.' . 'If you want to replce the file add change request to current file.','details'=>array('filename'=>$filename,'size'=>$size,'ext'=>$ext,'message'=>'Issue'),'preload'=>$flag);  
        }
        



    }
}

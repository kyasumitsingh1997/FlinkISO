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
 
require_once (ROOT . DS . APP_DIR . "/Plugin/AjaxMultiUpload/Vendor/valums/upload.php");

class AjaxMultiUploadAppController extends AppController {

	public function beforeRender ($model, $id, $edit=false) {
		$this->loadModel('FileUpload');
		$files = $this->FileUpload->find('all');
		
	
	}

	

}


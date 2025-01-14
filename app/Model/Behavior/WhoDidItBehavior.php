<?php
/**
 * WhoDidIt Model Behavior for CakePHP
 *
 * Handles created_by, modified_by fields for a given Model, if they exist in the Model DB table.
 * It's similar to the created, modified automagic, but it stores the logged User id
 * in the models that actsAs = array('WhoDidIt')
 * 
 * This is useful to track who created records, and the last user that has changed them
 *
 * @package behaviors
 * @author Daniel Vecchiato
 * @version 1.2
 * @date 01/03/2009
 * @copyright http://www.4webby.com
 * @licence MIT
 * @repository  https://github.com/danfreak/4cakephp/tree
 **/
class WhoDidItBehavior extends ModelBehavior {
/**
   * Default settings for a model that has this behavior attached.
   *
   * @var array
   * @access protected
   */
  protected $_defaults = array(
    'auth_session' => 'Auth',  //name of Auth session key
    'user_model' => 'User',    //name of User model
	'created_by_field' => 'created_by',    //the name of the "created_by" field in DB (default 'created_by')
	'modified_by_field' => 'modified_by',  //the name of the "modified_by" field in DB (default 'modified_by')
	'department_id_field' => 'departmentid',
	'company_id_field' => 'company_id',
	'branch_id_field' => 'branchid',
	'auto_bind' => true     //automatically bind the model to the User model (default true)
  );
/**
 * Initiate WhoMadeIt Behavior
 *
 * @param object $model
 * @param array $config  behavior settings you would like to override
 * @return void
 * @access public
 */
	function setup(Model $model, $config = array()) {
		
		if($model->alias != 'Dashboard' && $model->alias != 'NotificationType'  && $model->alias != 'Page'  ) {
				//assigne default settings
				$this->settings[$model->alias] = $this->_defaults;
				
				//merge custom config with default settings
				$this->settings[$model->alias] = array_merge($this->settings[$model->alias], (array)$config);
				
				$hasFieldCreatedBy = $model->hasField($this->settings[$model->alias]['created_by_field']);
				$hasFieldModifiedBy = $model->hasField($this->settings[$model->alias]['modified_by_field']);
				
				$this->settings[$model->alias]['has_created_by'] = $hasFieldCreatedBy;
				$this->settings[$model->alias]['has_modified_by'] = $hasFieldModifiedBy;
				
				//handles model binding to the User model
				//according to the auto_bind settings (default true)
				if($this->settings[$model->alias]['auto_bind'])
				{
					if ($hasFieldCreatedBy) {
						$commonBelongsTo = array(
							'CreatedBy' => array('className' => $this->settings[$model->alias]['user_model'],
												'foreignKey' => $this->settings[$model->alias]['created_by_field'],
												'fields' => array('id','name'),
												),
												);
						$model->bindModel(array('belongsTo' => $commonBelongsTo), false);
					}
					if ($hasFieldModifiedBy) {
						$commonBelongsTo = array(
							'ModifiedBy' => array('className' => $this->settings[$model->alias]['user_model'],
												'foreignKey' => $this->settings[$model->alias]['modified_by_field'],
												'fields' => array('id','name'),
												));
						$model->bindModel(array('belongsTo' => $commonBelongsTo), false);
					}
				}
		}
	}
/**
 * Before save callback
 *
 * @param object $model Model using this behavior
 * @return boolean True if the operation should continue, false if it should abort
 * @access public
 */
	function beforeSave(Model $model, $options = array()) {
		//if($model->alias != 'History' && $model->alias != 'User' && $model->alias != 'UserSession')$this->redirect(array('controller'=>'user','action'=>'dashboard'));
		//$this->redirect(array('action'=>'index'));
		if ($this->settings[$model->alias]['has_created_by'] || $this->settings[$model->alias]['has_modified_by']) {
			// We can't use this as there is no Auth / Sessions in CMS,
			$AuthSession = $this->settings[$model->alias]['auth_session'];
			$UserSession = $this->settings[$model->alias]['user_model'];
			
			if(isset($_SESSION['User'])){
				$userId = $_SESSION['User']['id'];
				$dept_id = $_SESSION['User']['department_id'];
				$bran_id = $_SESSION['User']['branch_id'];
				$comp_id = $_SESSION['User']['company_id'];
				if ($userId) {
					$data = array($this->settings[$model->alias]['modified_by_field'] => $userId);
					if (!$model->exists()) {
						$data[$this->settings[$model->alias]['created_by_field']] = $userId;
						$data[$this->settings[$model->alias]['department_id_field']] = $dept_id ;
						$data[$this->settings[$model->alias]['branch_id_field']] = $bran_id ;
						$data[$this->settings[$model->alias]['company_id_field']] = $comp_id ;
					}
					$model->set($data);
				}
			}else{
				$userId = '0';
				$dept_id = '0';
				$bran_id = '0';
				$comp_id = '0';
				if ($userId) {
					$data = array($this->settings[$model->alias]['modified_by_field'] => $userId);
					if (!$model->exists()) {
						$data[$this->settings[$model->alias]['created_by_field']] = $userId;
						$data[$this->settings[$model->alias]['department_id_field']] = $dept_id ;
						$data[$this->settings[$model->alias]['branch_id_field']] = $bran_id ;
						$data[$this->settings[$model->alias]['company_id_field']] = $comp_id ;
					}
					$model->set($data);
				}
			}
		}	
		
		return true;
	}
}
?>
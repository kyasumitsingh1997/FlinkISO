<?php
App::uses('AppModel', 'Model');
/**
 * FileUpload Model
 *
 * @property SystemTable $SystemTable
 * @property User $User
 * @property UserSession $UserSession
 * @property MasterListOfFormat $MasterListOfFormat
 * @property Evidence $Evidence
 */
class FileUpload extends AppModel {

  public $virtualFields = array(
    'name' => 'CONCAT(FileUpload.file_details, ".", FileUpload.file_type)'
);
    public $displayField = 'name';
    // public $actsAs = array('Containable');
 
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'sr_no' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'file_details' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'user_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'file_type' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'file_status' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'result' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'user_session_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'branchid' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'departmentid' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'created_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'modified_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
	    'ApprovedBy' => array(
	            'className' => 'Employee',
	            'foreignKey' => 'approved_by',
	            'conditions' => '',
	            'fields' => array('id', 'name'),
	            'order' => '' ),
	    'PreparedBy' => array(
	            'className' => 'Employee',
	            'foreignKey' => 'prepared_by',
	            'conditions' => '',
	            'fields' => array('id', 'name'),
	            'order' => ''
	        ),
		'SystemTable' => array(
			'className' => 'SystemTable',
			'foreignKey' => 'system_table_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'UserSession' => array(
			'className' => 'UserSession',
			'foreignKey' => 'user_session_id',
			'conditions' => '',
			'fields' => array('id'),
			'order' => ''
		),
		'MasterListOfFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format_id',
			'conditions' => '',
			'fields' => array('id', 'title', 'system_table_id'),
			'order' => ''
		),
		'BranchIds' => array(
			'className' => 'Branch',
			'foreignKey' => 'branchid',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'DepartmentIds' => array(
			'className' => 'Department',
			'foreignKey' => 'departmentid',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'StatusUserId' => array(
			'className' => 'User',
			'foreignKey' => 'status_user_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Evidence' => array(
			'className' => 'Evidence',
			'foreignKey' => 'file_upload_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'ChangeAdditionDeletionRequest' => array(
			'className' => 'ChangeAdditionDeletionRequest',
			'foreignKey' => 'file_upload_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'FileShare' => array(
			'className' => 'FileShare',
			'foreignKey' => 'file_upload_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'FileView' => array(
			'className' => 'FileView',
			'foreignKey' => 'file_upload_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
}

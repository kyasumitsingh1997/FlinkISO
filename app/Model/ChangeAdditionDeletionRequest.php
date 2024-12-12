<?php
App::uses('AppModel', 'Model');
/**
 * ChangeAdditionDeletionRequest Model
 *
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 */
class ChangeAdditionDeletionRequest extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
        //public $displayField = 'request_details' ;
	public $validate = array(
		'sr_no' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
                'prepared_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'proposed_document_changes' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'reason_for_change' => array(
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
                'Branch' => array(
			'className' => 'Branch',
			'foreignKey' => 'branch_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Customer' => array(
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'SuggestionForm' => array(
			'className' => 'SuggestionForm',
			'foreignKey' => 'suggestion_form_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'FileUpload' => array(
			'className' => 'FileUpload',
			'foreignKey' => 'file_upload_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
                'SystemTable' => array(
			'className' => 'SystemTable',
			'foreignKey' => 'system_table_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'MasterListOfFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format',
			'conditions' => '',
			'fields' => '',
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
		'Meeting' => array(
			'className' => 'Meeting',
			'foreignKey' => 'meeting_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
                  'PreparedBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'prepared_by',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
                'ApprovedBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'approved_by',
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
        
         public $customArray = array(
		    'document_change_accepted' => array(
			'0' => 'No',
			'1' => 'Yes',
                    ),
                   
	    );

    public $report = array(
    	'Change_Requests' => array(
	    		'Branch' => array('model'=>'Branch','key_field'=>'branch_id'),
	    		'Department' => array('model'=>'Department','key_field'=>'department_id'),
	    		'Employee' =>array('model'=>'Employee','key_field'=>'employee_id'),
	    		'Customer' => array('model'=>'Customer','key_field'=>'customer_id'),
	    		'Documents' => array('model'=>'FileUpload','key_field'=>'file_upload_id')	    		
    		)
    	);
}

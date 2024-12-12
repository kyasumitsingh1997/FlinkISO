<?php
App::uses('AppModel', 'Model');

class MasterListOfFormat extends AppModel {
public $displayField = "title";
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
		'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'document_number' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'issue_number' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		)
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
        'MasterListOfFormatCategory' => array(
			'className' => 'MasterListOfFormatCategory',
			'foreignKey' => 'master_list_of_format_category_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'Clause' => array(
			'className' => 'Clause',
			'foreignKey' => 'clause_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'Standard' => array(
			'className' => 'Standard',
			'foreignKey' => 'standard_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'SystemTable' => array(
			'className' => 'SystemTable',
			'foreignKey' => 'system_table_id',
			'conditions' => '',
			'fields' => array('id', 'name','system_name','evidence_required','approvals_required'),
			'order' => ''
		),
		'BranchIds' => array(
			'className' => 'Branch',
			'foreignKey' => 'branchid',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'departmentid',
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
		),'ParentDocumentId' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'parent_document_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),'LinkEdFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'linked_formats',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */

	public $hasMany = array(
           'MasterListOfFormatDepartment' => array(
			'className' => 'MasterListOfFormatDepartment',
			'foreignKey' => 'master_list_of_format_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'MasterListOfFormatBranch' => array(
			'className' => 'MasterListOfFormatBranch',
			'foreignKey' => 'master_list_of_format_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'DocumentAmendmentRecordSheet' => array(
			'className' => 'DocumentAmendmentRecordSheet',
			'foreignKey' => 'master_list_of_format',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ChangeAdditionDeletionRequest' => array(
			'className' => 'ChangeAdditionDeletionRequest',
			'foreignKey' => 'master_list_of_format',
			'dependent' => true,
			//'conditions' => array('ChangeAdditionDeletionRequest.document_change_accepted'=>0),
			'conditions' => array(),
			'fields' => '',
			'order' => array('ChangeAdditionDeletionRequest.modified'=>'DESC'),
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public $customArray = array(
			'document_status' => array(
				'0' => 'Draft',
				'1' => 'Published',
				'2' => 'Under Revision',
				'3' => 'Archived'
		    )
	    );
}

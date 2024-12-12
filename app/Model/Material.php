<?php
App::uses('AppModel', 'Model');
/**
 * Material Model
 *
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property Company $Company
 * @property CorrectivePreventiveAction $CorrectivePreventiveAction
 * @property DeliveryChallanDetail $DeliveryChallanDetail
 * @property MaterialListWithShelfLife $MaterialListWithShelfLife
 * @property MaterialQualityCheck $MaterialQualityCheck
 * @property PurchaseOrderDetail $PurchaseOrderDetail
 * @property Stock $Stock
 */
class Material extends AppModel {

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
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'unit_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'description' => array(
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
		'Unit' => array(
			'className' => 'Unit',
			'foreignKey' => 'unit_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
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
		'MasterListOfFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format_id',
			'conditions' => '',
			'fields' => array('id', 'title', 'system_table_id'),
			'order' => ''
		),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
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
		'MaterialListWithShelfLife' => array(
			'className' => 'MaterialListWithShelfLife',
			'foreignKey' => 'material_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array('id', 'shelflife_by_manufacturer',  'shelflife_by_company','remarks'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'MaterialQualityCheck' => array(
			'className' => 'MaterialQualityCheck',
			'foreignKey' => 'material_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array('sr_no','id', 'name', 'details', 'active_status'),
			'order' => 'sr_no',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'NonConformingProductsMaterial' => array(
			'className' => 'NonConformingProductsMaterial',
			'foreignKey' => 'material_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array(),
			'order' => array('NonConformingProductsMaterial.non_confirmity_date'=>'DESC'),
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	public $customArray = array(

		    'qc_required' => array(
			'0' => 'No',
			'1' => 'Yes',
		    )
	    );
}

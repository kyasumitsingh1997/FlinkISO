<?php
App::uses('AppModel', 'Model');
/**
 * SupplierRegistration Model
 *
 * @property SupplierCategory $SupplierCategory
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property CorrectivePreventiveAction $CorrectivePreventiveAction
 * @property DeliveryChallan $DeliveryChallan
 * @property Device $Device
 * @property ListOfAcceptableSupplier $ListOfAcceptableSupplier
 * @property ListOfComputer $ListOfComputer
 * @property OrderRegister $OrderRegister
 * @property PurchaseOrder $PurchaseOrder
 * @property SummeryOfSupplierEvaluation $SummeryOfSupplierEvaluation
 * @property SupplierEvaluationReevaluation $SupplierEvaluationReevaluation
 */
class SupplierRegistration extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
    public $displayField = 'title';
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
		'number' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'type_of_company' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'contact_person_office' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'designition_in_office' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'office_address' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'office_telephone' => array(
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
		'SupplierCategory' => array(
			'className' => 'SupplierCategory',
			'foreignKey' => 'supplier_category_id',
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

	public $customArray = array(
		    'iso_certified' => array(
			'0' => 'No',
			'1' => 'Yes',
		    ),
                    'supplier_selected'=>array(
                        '0' => 'Yes',
			'1' => 'No',
                        '2' =>'On hold'
                    ),
            'type_of_company' => array('Sole Proprietorship' => 'Sole Proprietorship', 'HUF' => 'HUF', 'Chartered Company' => 'Chartered Company', 'Statutory Company' => 'Statutory Company', 'Registered Company' => 'Registered Company', 'Limited Liability Company' => 'Limited Liability Company', 'Unlimited Liability Company' => 'Unlimited Liability Company', 'Private Limited Company' => 'Private Limited Company', 'Private Limited Company' => 'Private Limited Company', 'Public Limited Company' => 'Public Limited Company', 'Holding Company' => 'Holding Company', 'Subsidiary Company' => 'Subsidiary Company', 'Government Company' => 'Government Company', 'Non-Government Company' => 'Non-Government Company', 'Foreign Company' => 'Foreign Company')
	    );
}

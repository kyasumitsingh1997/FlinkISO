<?php
/**
 * BranchFixture
 *
 */
class BranchFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'sr_no' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 120, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'publish' => array('type' => 'boolean', 'null' => true, 'default' => '0', 'comment' => '0=Un 1=Pub'),
		'record_status' => array('type' => 'boolean', 'null' => true, 'default' => '0', 'comment' => '0=Un-locked, 1=Locked'),
		'status_user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'soft_delete' => array('type' => 'boolean', 'null' => true, 'default' => '0', 'comment' => '1=deleted'),
		'branchid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'comment' => 'system defined automatically add', 'charset' => 'utf8'),
		'departmentid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'comment' => 'system defined automatically add', 'charset' => 'utf8'),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'comment' => 'system defined automatically add', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null, 'comment' => 'system defined automatically add'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'comment' => 'system defined automatically add', 'charset' => 'utf8'),
		'approved_by' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'prepared_by' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'comment' => 'system defined automatically add'),
		'system_table_id' => array('type' => 'string', 'null' => true, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'master_list_of_format_id' => array('type' => 'string', 'null' => true, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'division_id' => array('type' => 'string', 'null' => true, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'company_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'sr_no' => array('column' => 'sr_no', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '57760c2d-0888-4627-bcbd-28b9db1e6cf9',
			'sr_no' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'publish' => 1,
			'record_status' => 1,
			'status_user_id' => 'Lorem ipsum dolor sit amet',
			'soft_delete' => 1,
			'branchid' => 'Lorem ipsum dolor sit amet',
			'departmentid' => 'Lorem ipsum dolor sit amet',
			'created_by' => 'Lorem ipsum dolor sit amet',
			'created' => '2016-07-01 11:52:37',
			'modified_by' => 'Lorem ipsum dolor sit amet',
			'approved_by' => 'Lorem ipsum dolor sit amet',
			'prepared_by' => 'Lorem ipsum dolor sit amet',
			'modified' => '2016-07-01 11:52:37',
			'system_table_id' => 'Lorem ipsum dolor sit amet',
			'master_list_of_format_id' => 'Lorem ipsum dolor sit amet',
			'division_id' => 'Lorem ipsum dolor sit amet',
			'company_id' => 'Lorem ipsum dolor sit amet'
		),
	);

}

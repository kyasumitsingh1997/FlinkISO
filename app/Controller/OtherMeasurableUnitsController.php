<?php
App::uses('AppController', 'Controller');
/**
 * OtherMeasurableUnits Controller
 *
 * @property OtherMeasurableUnit $OtherMeasurableUnit
 * @property PaginatorComponent $Paginator
 */
class OtherMeasurableUnitsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function _get_system_table_id() {
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
    }




/**
 * index method
 *
 * @return void
 */
	public function inplace_edit() {
		$this->autoRender = false;
		
		$otherMeasurableUnit = $this->OtherMeasurableUnit->find('first',array('conditions'=>array('OtherMeasurableUnit.id'=>$this->data['pk'])));

		if($otherMeasurableUnit){
			if($this->data['value']){
				$otherMeasurableUnit['OtherMeasurableUnit']['unit_name'] = $this->data['value'];
				$this->OtherMeasurableUnit->create();
				$this->OtherMeasurableUnit->save($otherMeasurableUnit,false);	
			}else{
				if($this->data['pk']){
					$this->OtherMeasurableUnit->delete($this->data['pk'],true);	
				}				
			}			
		}
		return true;
		exit;
	}
		
		
}

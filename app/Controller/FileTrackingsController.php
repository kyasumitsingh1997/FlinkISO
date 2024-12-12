<?php
App::uses('AppController', 'Controller');
/**
 * FileTrackings Controller
 *
 * @property FileTracking $FileTracking
 * @property PaginatorComponent $Paginator
 */
class FileTrackingsController extends AppController {

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
	public function index() {
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('FileTracking.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->FileTracking->recursive = 0;
		$this->set('fileTrackings', $this->paginate());
		
		$this->_get_count();
	}


 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('FileTracking.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->FileTracking->recursive = 0;
		$this->set('fileTrackings', $this->paginate());
		
		$this->_get_count();
	}

/**
 * search method
 * Dynamic by - TGS
 * @return void
 */
	public function search() {
		if ($this->request->is('post')) {
	
	$search_array = array();
		$search_keys = explode(" ",$this->request->data['FileTracking']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['FileTracking']['search_field'] as $search):
				$search_array[] = array('FileTracking.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('FileTracking.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->FileTracking->recursive = 0;
		$this->paginate = array('order'=>array('FileTracking.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'FileTracking.soft_delete'=>0 , $cons));
		$this->set('fileTrackings', $this->paginate());
		}
                $this->render('index');
	}

/**
 * adcanced_search method
 * Advanced search by - TGS
 * @return void
 */
	public function advanced_search() {
		if ($this->request->is('post')) {
		$conditions = array();
			if($this->request->query['keywords']){
				$search_array = array();
				$search_keys = explode(" ",$this->request->query['keywords']);
	
				foreach($search_keys as $search_key):
					foreach($this->request->query['search_fields'] as $search):
					if($this->request->query['strict_search'] == 0)$search_array[] = array('FileTracking.'.$search => $search_key);
					else $search_array[] = array('FileTracking.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('FileTracking.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('FileTracking.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'FileTracking.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('FileTracking.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('FileTracking.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->FileTracking->recursive = 0;
		$this->paginate = array('order'=>array('FileTracking.sr_no'=>'DESC'),'conditions'=>$conditions , 'FileTracking.soft_delete'=>0 );
		$this->set('fileTrackings', $this->paginate());
		}
                $this->render('index');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->FileTracking->exists($id)) {
			throw new NotFoundException(__('Invalid file tracking'));
		}
		$options = array('conditions' => array('FileTracking.' . $this->FileTracking->primaryKey => $id));
		$this->set('fileTracking', $this->FileTracking->find('first', $options));
	}



/**
 * list method
 *
 * @return void
 */
	public function lists() {
	
        $this->_get_count();		

	}



}

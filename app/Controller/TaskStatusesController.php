<?php

App::uses('AppController', 'Controller');

/**
 * TaskStatuses Controller
 *
 * @property TaskStatus $TaskStatus
 */
class TaskStatusesController extends AppController {

    public function _get_system_table_id() {

        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
    }

    public function lists(){
        $userNames = $this->requestAction('App/get_usernames');
        $this->set('users', $userNames);
        $schedules = $this->TaskStatus->Task->Schedule->find('list');
        $this->set('schedules',$schedules);
        $processes = $this->TaskStatus->Task->Process->find('list');
        $this->set('processes',$processes);
        $projectActivities = $this->TaskStatus->Task->ProjectActivity->find('list');
        $this->set('projectActivities',$projectActivities);

        if ($this->request->is('post') || $this->request->is('put')) {
            if($this->data['Task']['to_date'] && !$this->data['Task']['from_date']){
                $this->Session->setFlash(__('Please select from date'));
            }

            if($this->request->params['pass'][0] == 0)$task_type = 0;
            if($this->request->params['pass'][0] == 1)$task_type = 1;
            if($this->request->params['pass'][0] == 2)$task_type = 2;

            
            if($this->request->data['Task']['task_status'] == 0 || $this->request->data['Task']['task_status'] ==1){
                $conditions[] = array('Task.task_status'=>$this->request->data['Task']['task_status']);
            }
            if($this->request->data['Task']['user_id'] && $this->request->data['Task']['user_id'] != -1){
                $conditions[] = array('Task.user_id'=>$this->request->data['Task']['user_id']);   
            }
            if($this->request->data['Task']['process_id'] && $this->request->data['Task']['process_id'] != 0 && $this->request->data['Task']['process_id'] != -1){
                $conditions[] = array('Task.process_id'=>$this->request->data['Task']['process_id']);   
            }
            if($this->request->data['Task']['project_activity_id'] && $this->request->data['Task']['project_activity_id'] != 0 && $this->request->data['Task']['project_activity_id'] != -1){
                $conditions[] = array('Task.project_activity_id'=>$this->request->data['Task']['project_activity_id']);   
            }
            if($this->request->data['Task']['from_date'] && $this->request->data['Task']['to_date']){
                $conditions[] = array('Task.start_date >'=>$this->request->data['Task']['from_date'],'Task.end_date <'=>$this->request->data['Task']['to_date']);   
            }
            if($this->request->data['Task']['to_date'] && !$this->request->data['Task']['from_date']){
                    $this->Session->setFlash(__('Please select from date'));
            }
                       
        }else{
            $conditions = array('Task.task_status'=>0);
        }
        
        $date_conditions = array('OR'=>array('Task.end_date >= ' => date('Y-m-d'),'Task.revised_due_date >= ' => date('Y-m-d')));
        
        $all_tasks = $this->TaskStatus->Task->find('count',array('conditions'=>array('Task.task_type'=>0,$conditions,$date_conditions),'recursive'=>0));
        $process_tasks = $this->TaskStatus->Task->find('count',array('conditions'=>array('Task.task_type'=>1,$conditions,$date_conditions),'recursive'=>0));
        $project_tasks = $this->TaskStatus->Task->find('count',array('conditions'=>array('Task.task_type'=>2,$conditions,$date_conditions),'recursive'=>0));
        
        $this->set(array('all_tasks'=>$all_tasks,'process_tasks'=>$process_tasks,'project_tasks'=>$project_tasks));

    }
    public function index(){
        
        if($this->request->params['pass'][0] == 0)$task_type = 0;
        if($this->request->params['pass'][0] == 1)$task_type = 1;
        if($this->request->params['pass'][0] == 2)$task_type = 2;

       
        if($this->request->params['named']['taskstuatus'] == 0 || $this->request->params['named']['taskstuatus'] == 1){
            $taskstuatus_conditions = array('Task.task_status'=>$this->request->params['named']['taskstuatus']);            
        }else{
            $taskstuatus_conditions = array();            
        }
        if($this->request->params['named']['processid'] && $this->request->params['named']['processid'] != -1 && $this->request->params['named']['processid'] != 0){
            $conditions[] = array('Task.process_id'=>$this->request->params['named']['processid']);
        }
        if($this->request->params['named']['projectactivityid'] && $this->request->params['named']['projectactivityid'] != -1 && $this->request->params['named']['projectactivityid'] != 0){
            $conditions[] = array('Task.project_activity_id'=>$this->request->params['named']['projectactivityid']);
        }
        
        if($this->request->params['named']['users']){
            $conditions[] = array('Task.user_id'=>$this->request->params['named']['users']);   
        }
        if($this->request->params['named']['fromdate'] && $this->request->params['named']['todate']){
            $conditions[] = array('Task.start_date >'=>$this->request->params['named']['fromdate'],'Task.end_date <'=>$this->request->params['named']['todate']);   
        }
        if($this->request->params['named']['todate'] && !$this->request->params['named']['fromdate']){
                $this->Session->setFlash(__('Please select from date'));
        }
        
        if($this->Session->read('User.is_mr') == false){
            $user_conditions = array('OR'=>array('Task.created_by' => $this->Session->read('User.id'),'Task.user_id'=>$this->Session->read('User.id')));
        }

        if($task_type){
            $tasks = $this->TaskStatus->Task->find('all',array('conditions'=>array('Task.task_type'=>$task_type,$user_conditions, $taskstuatus_conditions,$conditions,$date_conditions),'recursive'=>0));    
        }else{
            $date_conditions = array('OR'=>array( 'Task.task_type !='=>array(1,2)),'OR'=>array('Task.task_status'=>0,'OR'=>array('Task.end_date >= ' => date('Y-m-d'),'Task.revised_due_date >= ' => date('Y-m-d'))));
            $tasks = $this->TaskStatus->Task->find('all',array('conditions'=>$user_conditions, $taskstuatus_conditions, $date_conditions,'recursive'=>0));    
        } 
        

        foreach ($tasks as $task) {
            $status = $this->requestAction('tasks/view/'.$task['Task']['id']);
            $i = 0;
            $j = 0;
            $per = 0;
            if($status[1]){
                foreach ($status[1] as $date => $TaskStatus) {

                    if(!empty($TaskStatus) && $TaskStatus['TaskStatus']['task_performed'] == 1){
                        $j++;
                    }else{
                        $i ++;
                    }
                    $per = 100 * $j / $this->_count($status[1]);
                    $results[$task['Task']['id']] = array('Task'=>$task['Task'],'Required'=>$this->_count($status[1]), 'Performed'=>$j,'per'=>round($per));
                }
            }else{
                    $results[$task['Task']['id']] = array('Task'=>$task['Task'],'Required'=>$this->_count($status[1]), 'Performed'=>$j,'per'=>round($per));
            }
                
        }
        
        $this->set('results',$results);     
        $userNames = $this->requestAction('App/get_usernames');
        $this->set('userNames', $userNames);
        $schedules = $this->TaskStatus->Task->Schedule->find('list');
        $this->set('schedules',$schedules);
        $processes = $this->TaskStatus->Task->Process->find('list');
        $this->set('processes',$processes);
        $projectActivities = $this->TaskStatus->Task->ProjectActivity->find('list');
        $this->set('projectActivities',$projectActivities);
    }

    public function _index($span = null, $report = false, $reportFromDate = null, $reportToDate = null, $type = null) {
        if ($report) {
            $from = $reportFromDate;
            $to = $reportToDate;
        } else if ($this->request->data) {
            $from = $this->request['data']['TaskStatus']['from_date'];
            $to = $this->request['data']['TaskStatus']['to_date'];
        }

        if (isset($this->request->data) && $this->request['data']['TaskStatus']['user_id'] != -1) {
            $userCondition = array('Task.user_id' => $this->request['data']['TaskStatus']['user_id']);
        } else {
            $userCondition = null;
        }

        if (isset($this->request->data) && $this->request['data']['TaskStatus']['process_id'] != -1) {
            $processCondition = array('Task.process_id' => $this->request['data']['TaskStatus']['process_id']);
        } else {
            $processCondition = null;
        }

        if (isset($this->request->data) && $this->request['data']['TaskStatus']['project_activity_id'] != -1) {
            $projectCondition = array('Task.project_activity_id' => $this->request['data']['TaskStatus']['project_activity_id']);
        } else {
            $projectCondition = null;
        }

        if (isset($this->request->data) && $this->request['data']['TaskStatus']['task_id'] != -1) {
            $taskCondition = array('Task.id' => $this->request['data']['TaskStatus']['task_id']);
        } else {
            $taskCondition = null;
        }
        
        $taskAssigned = 0;
        $taskPerformed = 0;

        $this->loadModel('Schedule');
        $schedulesList = $this->Schedule->find('list', array('conditions' => array('Schedule.publish' => 1, 'Schedule.soft_delete' => 0)));

        $this->loadModel('Task');
        $this->Task->recursive = 0;
        $schedules = array();
        if ($from) {
            foreach ($schedulesList as $key => $value):
                if ($value == 'dailly' || $value == 'daily' || $value == 'Dailly' || $value == 'Daily') {
                    while ($from <= $to) {
                        $schedules[$value][$from] = $this->Task->find('all', array(
                            'conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0, 'Task.schedule_id' => $key,
                                'Task.start_date < ' => date('Y-m-d 59:59:59', strtotime($from)),
                                $userCondition, $processCondition , $projectCondition,$taskCondition),
                            
                            'recursive' => 0,
                            'fields' => array('Task.id','Task.name', 'Task.process_id','Task.project_activity_id','Task.start_date','Task.end_date', 'MasterListOfFormat.title', 'User.name')));

                        $i = 0;
                        foreach ($schedules[$value][$from] as $task):
                            $taskStatus = $this->TaskStatus->find('first', array(
                                'conditions' => array(
                                    'TaskStatus.task_date' => $from,
                                    'TaskStatus.task_id' => $task['Task']['id'])));
                            $schedules[$value][$from][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
                            if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
                                $taskPerformed++;
                            $i++;
                            $taskAssigned++;
                        endforeach;
                        $from = date("Y-m-d", strtotime("+1 day", strtotime($from)));
                    }

                    if ($report) {
                        $from = $reportFromDate;
                    } else {
                        $from = $this->request['data']['TaskStatus']['from_date'];
                    }
                } else if ($value == 'weekly' || $value == 'Weekly') {
                    $startDateUnix = strtotime($from);
                    $endDateUnix = strtotime($to);
                    $currentDateUnix = $startDateUnix;
                    $weekNumbers = array();
                    while ($currentDateUnix < $endDateUnix) {
                        $year = date('Y', $currentDateUnix);
                        $weekNumbers[$year][] = date('W', $currentDateUnix);
                        $currentDateUnix = strtotime('+1 week', $currentDateUnix);
                    }
                    foreach ($weekNumbers as $yy => $yweek) {
                        foreach ($yweek as $ww) {
                            $schedules[$value][$yy . '-Week-' . $ww] = $this->Task->find('all', array(
                                'conditions' => array('Task.publish' => 1,
                                    'Task.soft_delete' => 0,
                                    'Task.schedule_id' => $key,
                                    array("OR" => array("AND" => array(
                                                "WEEK(Task.created) <=" => $ww,
                                                "YEAR(Task.created) =" => $yy),
                                            array("YEAR(Task.created) <" => $yy))),
                                    $userCondition, $processCondition , $projectCondition),
                                'fields' => array(
                                    'Task.id',
                                    'Task.name',
                                    'MasterListOfFormat.title',
                                    'User.name',
                            )));
                            $i = 0;
                            foreach ($schedules[$value][$yy . '-Week-' . $ww] as $task):
                                $schedules[$value][$yy . '-Week-' . $ww][$i]['TaskStatus'] = null;
                                $taskStatus = $this->TaskStatus->find('first', array(
                                    'conditions' => array(
                                        'WEEK(TaskStatus.task_date)' => $ww,
                                        'YEAR(TaskStatus.task_date)' => $yy,
                                        'TaskStatus.task_id' => $task['Task']['id'])));
                                $schedules[$value][$yy . '-Week-' . $ww][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
                                if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
                                    $taskPerformed++;
                                $i++;
                                $taskAssigned++;
                            endforeach;
                        }
                    }
                }else if ($value == 'monthly' || $value == 'Monthly') {
                    $startDateUnix = strtotime($from);
                    $endDateUnix = strtotime($to);
                    $currentDateUnix = $startDateUnix;
                    $monthNumbers = array();
                    while ($currentDateUnix < $endDateUnix) {
                        $year = date('Y', $currentDateUnix);
                        $monthNumbers[$year][] = IntVal(date('m', $currentDateUnix));
                        $currentDateUnix = strtotime('+1 month', $currentDateUnix);
                    }

                    foreach ($monthNumbers as $yy => $ymonth) {
                        foreach ($ymonth as $ww) {
                            $schedules[$value][$yy . '-Month-' . $ww] = $this->Task->find('all', array(
                                'conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0, 'Task.schedule_id' => $key,
                                    array("OR" => array("AND" => array("MONTH(Task.created) <=" => $ww,
                                                "YEAR(Task.created) =" => $yy), array("YEAR(Task.created) <" => $yy))), $userCondition, $processCondition , $projectCondition,
                                ),
                                'fields' => array(
                                    'Task.id',
                                    'Task.name',
                                    'MasterListOfFormat.title',
                                    'User.name',
                                )
                            ));
                            $i = 0;
                            foreach ($schedules[$value][$yy . '-Month-' . $ww] as $task):
                                $schedules[$value][$yy . '-Month-' . $ww][$i]['TaskStatus'] = null;
                                $taskStatus = $this->TaskStatus->find('first', array(
                                    'conditions' => array(
                                        'MONTH(TaskStatus.task_date) <= ' => $ww,
                                        'YEAR(TaskStatus.task_date) <= ' => $yy,
                                        'TaskStatus.task_id' => $task['Task']['id'])));
                                $schedules[$value][$yy . '-Month-' . $ww][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
                                if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
                                    $taskPerformed++;
                                $i++;
                                $taskAssigned++;
                            endforeach;
                        }
                    }
                }else if ($value == 'quarterly' || $value == 'Quarterly') {
                    $taskDetails = $this->Task->find('all', array(
                        'conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0, 'Task.schedule_id' => $key,
                            "DATE_FORMAT(Task.created, '%Y-%m-%d') <=" => $to, $userCondition, $processCondition , $projectCondition,
                        ),
                        'fields' => array(
                            'Task.id',
                            'Task.name',
                            'Task.created',
                            'MasterListOfFormat.title',
                            'User.name',
                        )
                    ));

                    foreach ($taskDetails as $taskDetail) {
                        $created = date('Y-m-d', strtotime($taskDetail['Task']['created']));
                        $currentDate = date('Y-m-d', strtotime($taskDetail['Task']['created']));
                        $lastQuarter = $to;
                        $nextQuarter = $to;

                        $dateArray = array();
                        $k = 0;
                        while ($currentDate <= $lastQuarter) {
                            $nextQuarter = date('Y-m-d', strtotime('+3 month', strtotime($currentDate)));
                            if ($currentDate >= $from) {
                                $dateArray[$k]['currentDate'] = $currentDate;
                                $dateArray[$k]['nextQuarter'] = $nextQuarter;
                                $k++;
                            }
                            $currentDate = $nextQuarter;
                        }
                        $i = 0;
                        foreach ($dateArray as $ww => $quarter) {
                            $cDate = $quarter['currentDate'];
                            $nextQuarter = $quarter['nextQuarter'];
                            $qNumber = $ww + 1;
                            $schedules[$value]['Quarter-' . $qNumber][$i] = $taskDetail;
                            $schedules[$value]['Quarter-' . $qNumber][$i]['TaskStatus'] = null;
                            $taskStatus = $this->TaskStatus->find('first', array(
                                'conditions' => array(
                                    "DATE_FORMAT(TaskStatus.task_date, '%Y-%m-%d') >=" => $cDate, "DATE_FORMAT(TaskStatus.task_date, '%Y-%m-%d') <=" => $nextQuarter,
                                    'TaskStatus.task_id' => $taskDetail['Task']['id'])));

                            $schedules[$value]['Quarter-' . $qNumber][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
                            if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
                                $taskPerformed++;
                            $i++;
                            $taskAssigned++;
                        }
                    }
                }else if ($value == 'yearly' || $value == 'Yearly') {
                    $startDateUnix = strtotime($from);
                    $endDateUnix = strtotime($to);
                    $currentDateUnix = $startDateUnix;
                    $yearNumbers = array();
                    while ($currentDateUnix < $endDateUnix) {
                        $year = date('Y', $currentDateUnix);
                        $yearNumbers[$year][] = IntVal(date('Y', $currentDateUnix));
                        $currentDateUnix = strtotime('+1 year', $currentDateUnix);
                    }

                    foreach ($yearNumbers as $yy => $yyear) {
                        foreach ($yyear as $ww) {
                            $schedules[$value][$yy . '-Year-' . $ww] = $this->Task->find('all', array(
                                'conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0, 'Task.schedule_id' => $key,
                                    "YEAR(Task.created) <=" => $yy, $userCondition, $processCondition , $projectCondition,
                                ),
                                'fields' => array(
                                    'Task.id',
                                    'Task.name',
                                    'Task.created',
                                    'MasterListOfFormat.title',
                                    'User.name',
                                )
                            ));
                            $i = 0;
                            foreach ($schedules[$value][$yy . '-Year-' . $ww] as $task):
                                $schedules[$value][$yy . '-Year-' . $ww][$i]['TaskStatus'] = null;
                                $taskStatus = $this->TaskStatus->find('first', array(
                                    'conditions' => array(
                                        'YEAR(TaskStatus.task_date) <= ' => $yy,
                                        'TaskStatus.task_id' => $task['Task']['id'])));
                                $schedules[$value][$yy . '-Year-' . $ww][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
                                if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
                                    $taskPerformed++;
                                $i++;
                                $taskAssigned++;
                            endforeach;
                        }
                    }
                }else if ($value == 'half-yearly' || $value == 'Half-Yearly') {
                    $startDateUnix = strtotime($from);
                    $endDateUnix = strtotime($to);
                    $currentDateUnix = $startDateUnix;
                    $yearNumbers = array();
                    while ($currentDateUnix < $endDateUnix) {
                        $year = date('Y', $currentDateUnix);
                        $yearNumbers[$year][] = IntVal(date('Y', $currentDateUnix));
                        $currentDateUnix = strtotime('+6 months', $currentDateUnix);
                    }

                    foreach ($yearNumbers as $yy => $yyear) {
                        foreach ($yyear as $ww) {
                            $schedules[$value][$yy . '-Year-' . $ww] = $this->Task->find('all', array(
                                'conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0, 'Task.schedule_id' => $key,
                                    "YEAR(Task.created) <=" => $yy, $userCondition, $processCondition , $projectCondition,
                                ),
                                'fields' => array(
                                    'Task.id',
                                    'Task.name',
                                    'Task.created',
                                    'MasterListOfFormat.title',
                                    'User.name',
                                )
                            ));
                            $i = 0;
                            foreach ($schedules[$value][$yy . '-Year-' . $ww] as $task):
                                $schedules[$value][$yy . '-Year-' . $ww][$i]['TaskStatus'] = null;
                                $taskStatus = $this->TaskStatus->find('first', array(
                                    'conditions' => array(
                                        'YEAR(TaskStatus.task_date) <= ' => $yy,
                                        'TaskStatus.task_id' => $task['Task']['id'])));
                                $schedules[$value][$yy . '-Year-' . $ww][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
                                if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
                                    $taskPerformed++;
                                $i++;
                                $taskAssigned++;
                            endforeach;
                        }
                    }
                }
            endforeach;
        }
        if ($report == true) {
            return $schedules;
        }
        $users = $this->requestAction('App/get_usernames');
        if ($taskPerformed > 0 && $taskAssigned > 0)
            $result = round($taskPerformed * 100 / $taskAssigned);
        else
            $result = 0;
        
        $from = $this->request['data']['TaskStatus']['from_date'];
	
        $tasks = $this->TaskStatus->Task->find('list',array('conditions'=>array('Task.publish'=>1,'Task.soft_delete'=>0)));
        $processes = $this->TaskStatus->Task->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
        $projectActivities = $this->TaskStatus->Task->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
      
        $this->set(compact('schedules', 'from', 'to', 'users', 'result','processes','projectActivities','tasks'));

    }

    public function task_completion(){ 
        // print_r($this->request->params['pass'][0]);
        $this->loadModel('ProjectActivity');       
        $projectActivities = $this->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.project_id'=>$this->request->params['pass'][0])));
        $id = array_keys($projectActivities);
        // print_r($id);
        $conditions = array('Task.task_type'=>2,'Task.project_activity_id'=>$id);
        $tasks = $this->TaskStatus->Task->find('all',array('conditions'=>array($conditions),'recursive'=>0));
        foreach ($tasks as $task) {
            $status = $this->requestAction('tasks/view/'.$task['Task']['id']);
            $j = 0;
            $per = 0;
            foreach ($status[1] as $date => $TaskStatus) {

                if(!empty($TaskStatus) && $TaskStatus['TaskStatus']['task_performed'] == 1){
                    $j++;
                }else{
                    $i ++;
                }
                $per = 100 * $j / $this->_count($status[1]);
                $results[$task['Task']['id']] = array('Required'=>$this->_count($status[1]), 'Performed'=>$j,'per'=>round($per));
            }
        }
        
        foreach ($results as $key => $value) {
            $per = $per + $value['per'];
        }
        if($this->_count($results))$completion = $per/$this->_count($results);
        return $completion;
        exit;
    }
}

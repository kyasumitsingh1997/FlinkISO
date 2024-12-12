<?php
    $controller = Inflector::humanize($this->request->params['controller']);
    if (isset($breadcrumbs[$this->request->params['controller']]['dashboard'])){
        if(isset($breadcrumbs[$this->request->params['controller']]['controller'])){
            $controller = $breadcrumbs[$this->request->params['controller']]['controller'];
        }if(
            $breadcrumbs[$this->request->params['controller']]['dashboard'] == 'Project Management' ||
            $breadcrumbs[$this->request->params['controller']]['dashboard'] == 'Milestone' ||
            $breadcrumbs[$this->request->params['controller']]['dashboard'] == 'ProjectActivityRequirement'
            ){
            $controller = 'projects';
        }else{
            $controller = 'dashboards';
        }
        echo $this->Breadcrumb->create(array(
            array(
                'title' => $breadcrumbs[$this->request->params['controller']]['dashboard'],
                'url' => array('controller' => $controller, 'action' => $breadcrumbs[$this->request->params['controller']]['dashboard_action']),
                'class' => 'text',
            )
        ));
    }
    $this->Js->writeBuffer();
    // echo $this->Session->flash();
?>

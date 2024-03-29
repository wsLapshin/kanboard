<?php

namespace Kanboard\Plugin\AutoSubtasks\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Action\Base;

class AutoCreateSubtask extends Base
{

  public function getDescription()
  {
    return t('Create one or more Subtasks Automatically');
  }

  public function getCompatibleEvents()
  {

    return array(
      TaskModel::EVENT_CREATE,
      TaskModel::EVENT_MOVE_COLUMN,
    );
  }

  public function getActionRequiredParameters()
  {
    //changed 'titles' to 'multitasktitles' to have a clean way to render the title-textfield as a textarea
    return array(
      'column_id' => t('Column'),
      'user_id' => t('Assignee'),
      'multitasktitles' => t('Subtask Title(s)'),
      'time_estimated' => t('Estimated Time in Hours'),
      'duration' => t('Duration in days'),
      'check_box' => t('Apply to all Columns'),
    );
  }

  public function getEventRequiredParameters()
  {
    return array(
      'task_id',
      'task' => array(
        'project_id',
        'column_id',
        'title',
      ),
    );
  }

  public function doAction(array $data)
  {
    //get the value of 'multitasktitles' in stead of the original 'titles'
    $title_test = $this->getParam('multitasktitles');
    $title_test = preg_replace("/^\s+/m", $data['task']['title'] . "\r\n", $title_test);

    $user_id = ( empty($this->getParam('user_id')))?$this->userSession->getId():$this->getParam('user_id');
    $values = array(
      'title' => $title_test,
      'task_id' => $data['task_id'],
      'user_id' => $user_id ,
//CDEV Patch #94      
//      'user_id' => $this->getParam('user_id'),
      'time_estimated' => $this->getParam('time_estimated'),
      'time_spent' => 0,
      'status' => 0,
      'due_date' => strtotime('+'.$this->getParam('duration').'days'),
    );

    $subtasks = explode("\r\n", isset($values['title']) ? $values['title'] : '');
    $subtasksAdded = 0;

    foreach ($subtasks as $subtask) {
      $subtask = trim($subtask);

      if (! empty($subtask)) {

        //CDEV patch, check if subtask with this name is exists already
        $s = $this->subtaskModel->getQuery()->eq('task_id', $values['task_id'])->eq('title', $subtask)->findOne();
        if ( null !== $s ) {
            continue;
        }
        //END Patch

        $subtaskValues = $values;
        $subtaskValues['title'] = $subtask;

        list($valid, $errors) = $this->subtaskValidator->validateCreation($subtaskValues);

        if (! $valid) {
          $this->create($values, $errors);
          return false;
        }

        if (! $this->subtaskModel->create($subtaskValues)) {
          $this->flash->failure(t('Unable to create your sub-task.'));
          $this->response->redirect($this->helper->url->to('TaskViewController', 'show', array('project_id' => $task['project_id'], 'task_id' => $task['id']), 'subtasks'), true);
          return false;
        }

        $subtasksAdded++;
      }
    }
    //restore the messaging with a flash but this message doesn't seem to appear in the flash area. Only the create message from (kanboard/app/Controller/ActionCreationController.php). 
    if ($subtasksAdded > 0) {
      if ($subtasksAdded === 1) {
        $this->flash->success(t('Subtask added successfully.'));
      } else {
        $this->flash->success(t('%d subtasks added successfully.', $subtasksAdded));
      }
    }
  }

  public function hasRequiredCondition(array $data)
  {
    
    if ($this->getParam('check_box')) {
    return $data['task']['column_id'] == $data['task']['column_id'];
    } else {
    return $data['task']['column_id'] == $this->getParam('column_id');
    }
  }
}

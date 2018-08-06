<?php

namespace Kanboard\Plugin\Timetrackingeditor\Controller;

use Kanboard\Plugin\Timetrackingeditor\Controller\TimeTrackingEditorController;
use Kanboard\Model\SubtaskModel;
use Kanboard\Plugin\TimetrackingEditor\Model\SubtaskTimeTrackingModel;

/**
 * SubtaskStatusController.
 *
 * @author CDEV
 */
class SubtaskStatusController extends \Kanboard\Controller\SubtaskStatusController
{
    /**
     * Start/stop timer for subtasks
     *
     * @access public
     */
    public function timer()
    {
        /*Здесь проверка, не запущен ли другой таймер*/
        /*if( $this->subtaskStatusModel->hasSubtaskInProgress($this->userSession->getId()) ) {
        $subTask = $this->subtaskStatusModel->getSubtaskInProgress($this->userSession->getId());
        if($subTask['id'] != $values['subtask_id']) {
            $this->request->setParams(
                [
                    'subtask_id'=>$subTask['id'],
                    'task_id'=>$subTask['task_id']
                ]
            );
            $restrictController = new SubtaskRestrictionController($this->container);
            $restrictController->show();
            exit();
        }
   }   */

        $task = $this->getTask();
        $subtask = $this->getSubtask($task);
        $timer = $this->request->getStringParam('timer');

        if ($timer === 'start') {
            $this->subtaskTimeTrackingModel->logDefaultStartTimeExtended($subtask['id'], $this->userSession->getId());
            $this->redirectCurrent();
        } elseif ($timer === 'stop') {
            $this->timerStopForm();
        }
    }

    /**
     * Реальная остановка таймера
     */
    public function timerStop()
    {
        $values = $this->request->getValues();
        $task = $this->getTask();
        $subtask = $this->getSubtask($task);
        $subtask_id = $subtask['id'];

        /*Если это вызов из формы пользователя, то сохраняем данные формы*/
        if( isset($values['is_form_request']) && $values['is_form_request'] == 1 ) {
            $this->subtaskTimeTrackingModel->logEndTimeExtended(
                $subtask_id, 
                $this->userSession->getId(), 
                isset($values['comment']) ? $values['comment'] : '', 
                isset($values['is_billable']) ? $values['is_billable'] : 0 
            );
        } else { //иначе по-дефолту
            $this->subtaskTimeTrackingModel->logDefaultEndTimeExtended($subtask_id, $this->userSession->getId() );
        }

        //@task Кажется,это должно быть не здесь, убрать куда то на уровень моделей, но разобраться,можно ли это связать как-то с методами ручного создания entries
        $this->subtaskTimeTrackingModel->updateTaskTimeTracking($task['id']);
        $this->redirectCurrent(); 
    }

    /**
     * Показывает форму перед остановкой таймера 
     */
    protected function timerStopForm(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();

        if (empty($values)) {
            $values = array('project_id' => $project['id'],
                'task_id' => $this->request->getIntegerParam('task_id'),
                'subtask_id' => $this->request->getIntegerParam('subtask_id')
            );
        }

        $values['subtask'] = $this->subtaskModel->getById($values['subtask_id']);

        $timetracking = $this->subtaskTimeTrackingEditModel
            ->getOpenTimer(
            $this->userSession->getId(), $values['subtask_id']
        );

        $values['comment'] = $timetracking["comment"];
        $values['is_billable'] = $timetracking['is_billable'];

        $this->response->html($this->template->render('Timetrackingeditor:stop', array(
                'values' => $values,
                'errors' => $errors,
                'project' => $project,
                'title' => t('Stop a timer')
        )));
    }

    protected function redirectCurrent()
    {
        $project = $this->getProject();
        $task = $this->getTask();
        //@task - странно, но при таком ответе редирект на фронте не срабатывает при включении таймера. Приходится делать редирект на js
        return $this->response->redirect($this->helper->url->to('TaskViewController', 'show', array(
                    'project_id' => $project['id'],
                    'task_id' => $task['id'],
                )), true);
    }
}

?>

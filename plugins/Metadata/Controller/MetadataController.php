<?php

namespace Kanboard\Plugin\Metadata\Controller;

use Kanboard\Controller\BaseController;

/**
 * Metadata.
 *
 * @author  BlueTeck
 */
class MetadataController extends BaseController
{
    public function project()
    {
        $project = $this->getProject();

        $metadata = $this->projectMetadataModel->getAll($project['id']);

        $this->response->html($this->helper->layout->project('metadata:project/metadata', ['title' => t('Metadata'),
                    'project'                                                                      => $project,
                    'metadata'                                                                     => $metadata, ]));
    }

    public function task()
    {
        $project = $this->getProject();
        $task = $this->getTask();

        $metadata = $this->taskMetadataModel->getAll($task['id']);

        $this->response->html($this->helper->layout->task('metadata:task/metadata', ['title' => t('Metadata'),
                    'task'                                                                   => $task,
                    'add_form'                                                               => true,
                    'project'                                                                => $project,
                    'metadata'                                                               => $metadata, ]));
    }

    public function task_footer()
    {
        $project = $this->getProject();
        $task = $this->getTask();

        $metadata = $this->taskMetadataModel->getAll($task['id']);

        $this->response->html($this->helper->layout->task('metadata:task/metadata', ['title' => t('Metadata'),
                    'task'                                                                   => $task,
                    'add_form'                                                               => false,
                    'project'                                                                => $project,
                    'metadata'                                                               => $metadata, ]));
    }

    public function user()
    {
        $user = $this->getUser();
        $metadata = $this->userMetadataModel->getAll($user['id']);

        $this->response->html($this->helper->layout->user('metadata:user/metadata', ['title' => t('Metadata'),
                    'user'                                                                   => $user,
                    'metadata'                                                               => $metadata, ]));
    }

    public function saveUser()
    {
        $user = $this->getUser();
        $values = $this->request->getValues();

        $this->userMetadataModel->save($user['id'], [$values['key'] => $values['value']]);

        return $this->response->redirect($this->helper->url->to('MetadataController', 'user', ['plugin' => 'metadata', 'user_id' => $user['id']]), true);
    }

    public function saveTask()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        $this->taskMetadataModel->save($task['id'], [$values['key'] => $values['value']]);

        return $this->response->redirect($this->helper->url->to('MetadataController', 'task', ['plugin' => 'metadata', 'task_id' => $task['id'], 'project_id' => $task['project_id']]), true);
    }

    public function saveProject()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        $this->projectMetadataModel->save($project['id'], [$values['key'] => $values['value']]);

        return $this->response->redirect($this->helper->url->to('MetadataController', 'project', ['plugin' => 'metadata', 'project_id' => $project['id']]), true);
    }

    public function confirmTask()
    {
        $project = $this->getProject();
        $task = $this->getTask();
        $key = $this->request->getStringParam('key');

        $this->response->html($this->template->render('metadata:task/remove', [
                    'task'    => $task,
                    'project' => $project,
                    'key'     => $key,
        ]));
    }

    public function confirmProject()
    {
        $project = $this->getProject();
        $key = $this->request->getStringParam('key');

        $this->response->html($this->template->render('metadata:project/remove', [
                    'project' => $project,
                    'key'     => $key,
        ]));
    }

    public function confirmUser()
    {
        $type = $this->request->getStringParam('type');
        $id = $this->request->getStringParam('id');
        $key = $this->request->getStringParam('key');

        $this->response->html($this->template->render('metadata:user/remove', [
                    'id'   => $id,
                    'type' => $type,
                    'key'  => $key,
        ]));
    }

    public function removeTask()
    {
        $task = $this->getTask();
        $key = $this->request->getStringParam('key');

        $this->taskMetadataModel->remove($task['id'], $key);

        return $this->response->redirect($this->helper->url->to('MetadataController', 'task', ['plugin' => 'metadata', 'task_id' => $task['id'], 'project_id' => $task['project_id']]), true);
    }

    public function removeProject()
    {
        $project = $this->getProject();
        $key = $this->request->getStringParam('key');

        $this->projectMetadataModel->remove($project['id'], $key);

        return $this->response->redirect($this->helper->url->to('MetadataController', 'project', ['plugin' => 'metadata', 'project_id' => $project['id']]), true);
    }

    public function removeUser()
    {
        $user = $this->getUser();
        $key = $this->request->getStringParam('key');

        $this->userMetadataModel->remove($user['id'], $key);

        return $this->response->redirect($this->helper->url->to('MetadataController', 'user', ['plugin' => 'metadata', 'user_id' => $user['id']]), true);
    }

    public function editProject()
    {
        $project = $this->getProject();
        $key = $this->request->getStringParam('key');
        $metadata = $this->projectMetadataModel->get($project['id'], $key);

        $this->response->html($this->template->render('metadata:project/form', [
                    'project'       => $project,
                    'form_headline' => t('Edit Metadata'),
                    'values'        => ['key' => $key, 'value' => $metadata],
        ]));
    }

    public function editUser()
    {
        $user = $this->getUser();
        $key = $this->request->getStringParam('key');
        $metadata = $this->userMetadataModel->get($user['id'], $key);

        $this->response->html($this->template->render('metadata:user/form', [
                    'user'          => $user,
                    'form_headline' => t('Edit Metadata'),
                    'values'        => ['key' => $key, 'value' => $metadata],
        ]));
    }

    public function editTask()
    {
        $project = $this->getProject();
        $task = $this->getTask();
        $key = $this->request->getStringParam('key');
        $metadata = $this->taskMetadataModel->get($task['id'], $key);

        $this->response->html($this->template->render('metadata:task/form', [
                    'project'       => $project,
                    'task'          => $task,
                    'form_headline' => t('Edit Metadata'),
                    'values'        => ['key' => $key, 'value' => $metadata],
        ]));
    }
}

<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        // TODO retrieve all tasks from database
        $tasks = [
            0 => [
                'id' => 0,
                'title' => 'Task 0',
                'completed' => false,
            ],
            1 => [
                'id' => 1,
                'title' => 'Task 1',
                'completed' => false,
            ],
            2 => [
                'id' => 2,
                'title' => 'Task 2',
                'completed' => true,
            ],
        ];
        return new ViewModel(['tasks' => $tasks]);
    }

    public function storeAction()
    {
        // TODO store new task in database

        $title = $this->params()->fromPost('title', '-');
        return new JsonModel([
            'status' => 1,
            'task' => [
                'id' => rand(),
                'title' => $title,
                'completed' => false
            ],
        ]);
    }

    public function updateAction()
    {
        // TODO update task with id = $taskId
        $taskId = $this->params()->fromRoute('id', '-');
        $completed = rand() % 2;

        return new JsonModel([
            'status' => 1,
            'task' => [
                'id' => $taskId,
                'completed' => $completed
            ],
        ]);
    }

    public function deleteAction()
    {
        // TODO delete task with id = $taskId

        $taskId = $this->params()->fromRoute('id', '-');
        return new JsonModel([
            'status' => 1,
            'task' => [
                'id' => $taskId
            ],
        ]);
    }

    public function deleteCompletedAction()
    {
        // TODO delete all tasks that are marked completed
        return new JsonModel(['status' => 1]);
    }

    public function deleteAllAction()
    {
        // TODO delete all tasks
        return new JsonModel(['status' => 1]);
    }
}

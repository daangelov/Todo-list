<?php

namespace Application\Controller;

use Application\Model\Entity\Task;
use Application\Model\Service\TaskManager;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Task manager
     * @var TaskManager
     */
    private $taskManager;

    public function __construct($entityManager, $taskManager)
    {
        $this->entityManager = $entityManager;
        $this->taskManager = $taskManager;
    }

    public function indexAction()
    {
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

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
        $taskId = $this->params()->fromRoute('id', -1);
        $taskCompleted = $this->params()->fromRoute('completed', false);

        if ($taskId < 0) {
            return new JsonModel(['status' => -1, 'msg' => 'Task id is not found']);
        }

        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        if ($task == null) {
            return new JsonModel(['status' => -1, 'msg' => 'Task not found!']);
        }

        $this->taskManager->updateTask($task, $taskCompleted);

        return new JsonModel(['status' => 1, 'task' => $task]);
    }

    public function deleteAction()
    {
        $taskId = $this->params()->fromRoute('id', -1);
        if ($taskId < 0) {
            return new JsonModel(['status' => -1, 'msg' => 'Task id is not found']);
        }

        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        if ($task == null) {
            return new JsonModel(['status' => -1, 'msg' => 'Task not found!']);
        }

        $this->taskManager->deleteTask($task);

        return new JsonModel(['status' => 1, 'taskId' => $taskId]);
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

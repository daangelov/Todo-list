<?php

namespace Application\Controller;

use Application\Model\Entity\Task;
use Application\Model\Service\TaskManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Exception;
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
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        $title = $this->params()->fromPost('title', '');
        if (!$title) {
            return new JsonModel(['status' => -1, 'msg' => 'Please provide a task title']);
        }

        $data = [
            'title' => $title,
            'completed' => 0
        ];

        try {
            $task = $this->taskManager->addTask($data);
        } catch (Exception $e) {
            return new JsonModel(['status' => -1, 'msg' => 'System error!']);
        }

        return new JsonModel(['status' => 1, 'task' => $task->getTaskProperties()]);
    }

    public function updateAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        $taskId = $this->params()->fromRoute('id', -1);
        $taskCompleted = $this->params()->fromPost('completed', 0);

        if ($taskId < 0) {
            return new JsonModel(['status' => -1, 'msg' => 'Task id is not found']);
        }

        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        if ($task == null) {
            return new JsonModel(['status' => -1, 'msg' => 'Task not found!']);
        }

        try {
            $this->taskManager->updateTask($task, $taskCompleted);
        } catch (Exception $e) {
            return new JsonModel(['status' => -1, 'msg' => 'System error!']);
        }

        return new JsonModel(['status' => 1, 'task' => $task->getTaskProperties()]);
    }

    public function deleteAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        $taskId = $this->params()->fromRoute('id', -1);
        if ($taskId < 0) {
            return new JsonModel(['status' => -1, 'msg' => 'Task id is not found']);
        }

        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        if ($task == null) {
            return new JsonModel(['status' => -1, 'msg' => 'Task not found!']);
        }

        try {
            $this->taskManager->deleteTask($task);
        } catch (Exception $e) {
            return new JsonModel(['status' => -1, 'msg' => 'System error!']);
        }

        return new JsonModel(['status' => 1, 'taskId' => $taskId]);
    }

    public function deleteCompletedAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        // TODO delete all tasks that are marked completed
        return new JsonModel(['status' => 1]);
    }

    public function deleteAllAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        // TODO delete all tasks
        return new JsonModel(['status' => 1]);
    }
}

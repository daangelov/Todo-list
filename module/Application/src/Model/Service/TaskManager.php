<?php

namespace Application\Model\Service;

use Application\Model\Entity\Task;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;

class TaskManager
{
    /**
     * Doctrine entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Add new task
     * @param array $data
     * @return Task
     * @throws Exception
     */
    public function addTask($data)
    {
        $task = new Task();
        $task->setTitle($data['title']);
        $task->setCompleted($data['completed']);
        $task->setCreatedOn((new DateTime())->format('Y-m-d H:i:s'));
        $task->setUpdatedOn((new DateTime())->format('Y-m-d H:i:s'));

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * Update task (completed: true/false)
     * @param Task|object $task
     * @param boolean $completed
     * @throws Exception
     */
    public function updateTask($task, $completed)
    {
        $task->setCompleted($completed);
        $task->setUpdatedOn((new DateTime())->format('Y-m-d H:i:s'));

        $this->entityManager->flush();
    }

    /**
     * @param $task
     * @throws Exception
     */
    public function deleteTask($task)
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    /**
     * @param $tasks
     * @throws Exception
     */
    public function deleteMultipleTasks($tasks)
    {
        foreach ($tasks as $task) {
            $this->entityManager->remove($task);
        }
        $this->entityManager->flush();
    }
}

<?php

namespace Application\Model\Service;

use Application\Model\Entity\Task;
use DateTime;

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
     */
    public function addTask($data)
    {
        $task = new Task();
        $task->setTitle($data['title']);
        $task->setCompleted($data['completed']);
        $task->setCreatedOn((new DateTime())->format('Y-m-d H:i:s'));
        $task->setUpdatedOn((new DateTime())->format('Y-m-d H:i:s'));

        // $this->entityManager->persist($task);

        $this->entityManager->flush();
    }

    /**
     * Update task (completed: true/false)
     * @param Task|object $task
     * @param bool $completed
     */
    public function updateTask($task, $completed)
    {
        $task->setCompleted($completed);
        $task->setUpdatedOn((new DateTime())->format('Y-m-d H:i:s'));

        $this->entityManager->flush();
    }

    public function deleteTask($task)
    {
        $this->entityManager->remove($task);

        $this->entityManager->flush();
    }
}
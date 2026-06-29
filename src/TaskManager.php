<?php

declare(strict_types=1);

class TaskManager
{
    private const VALID_STATUSES = [
        'todo',
        'in-progress',
        'done',
    ];

    public function __construct(
        private JsonTaskRepository $repository
    ) {
    }

    public function addTask(string $description): array
    {
        $description = trim($description);

        if ($description === '') {
            throw new InvalidArgumentException(
                'Task description cannot be empty.'
            );
        }

        $tasks = $this->repository->getAll();
        $now = date(DATE_ATOM);

        $task = [
            'id' => $this->generateNextId($tasks),
            'description' => $description,
            'status' => 'todo',
            'createdAt' => $now,
            'updatedAt' => $now,
        ];

        $tasks[] = $task;

        $this->repository->save($tasks);

        return $task;
    }

    public function updateTask(
        int $id,
        string $description
    ): array {
        $description = trim($description);

        if ($description === '') {
            throw new InvalidArgumentException(
                'Task description cannot be empty.'
            );
        }

        $tasks = $this->repository->getAll();
        $index = $this->findTaskIndex($tasks, $id);

        $tasks[$index]['description'] = $description;
        $tasks[$index]['updatedAt'] = date(DATE_ATOM);

        $this->repository->save($tasks);

        return $tasks[$index];
    }

    public function deleteTask(int $id): void
    {
        $tasks = $this->repository->getAll();
        $index = $this->findTaskIndex($tasks, $id);

        array_splice($tasks, $index, 1);

        $this->repository->save($tasks);
    }

    public function markTask(
        int $id,
        string $status
    ): array {
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException(
                "Invalid task status: {$status}"
            );
        }

        $tasks = $this->repository->getAll();
        $index = $this->findTaskIndex($tasks, $id);

        $tasks[$index]['status'] = $status;
        $tasks[$index]['updatedAt'] = date(DATE_ATOM);

        $this->repository->save($tasks);

        return $tasks[$index];
    }

    public function getTasks(?string $status = null): array
    {
        $tasks = $this->repository->getAll();

        if ($status === null) {
            return $tasks;
        }

        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException(
                'Status must be todo, in-progress, or done.'
            );
        }

        $filteredTasks = array_filter(
            $tasks,
            fn(array $task): bool => $task['status'] === $status
        );

        return array_values($filteredTasks);
    }

    private function findTaskIndex(array $tasks, int $id): int
    {
        foreach ($tasks as $index => $task) {
            if ($task['id'] === $id) {
                return $index;
            }
        }

        throw new InvalidArgumentException(
            "Task with ID {$id} was not found."
        );
    }

    private function generateNextId(array $tasks): int
    {
        if ($tasks === []) {
            return 1;
        }

        $ids = array_column($tasks, 'id');

        return max($ids) + 1;
    }
}
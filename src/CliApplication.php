<?php

declare(strict_types=1);

class CliApplication
{
    public function __construct(
        private TaskManager $taskManager
    ) {
    }

    public function run(array $arguments): void
    {
        $command = $arguments[1] ?? null;

        switch ($command) {
            case 'add':
                $this->add($arguments);
                break;

            case 'update':
                $this->update($arguments);
                break;

            case 'delete':
                $this->delete($arguments);
                break;

            case 'mark-in-progress':
                $this->markInProgress($arguments);
                break;

            case 'mark-done':
                $this->markDone($arguments);
                break;

            case 'list':
                $this->list($arguments);
                break;

            case 'help':
            case null:
                $this->printUsage();
                break;

            default:
                throw new InvalidArgumentException(
                    "Unknown command: {$command}"
                );
        }
    }

    private function add(array $arguments): void
    {
        $description = $arguments[2] ?? '';

        $task = $this->taskManager->addTask($description);

        echo "Task added successfully (ID: {$task['id']})"
            . PHP_EOL;
    }

    private function update(array $arguments): void
    {
        $id = $this->getId($arguments);
        $description = $arguments[3] ?? '';

        $this->taskManager->updateTask($id, $description);

        echo "Task {$id} updated successfully."
            . PHP_EOL;
    }

    private function delete(array $arguments): void
    {
        $id = $this->getId($arguments);

        $this->taskManager->deleteTask($id);

        echo "Task {$id} deleted successfully."
            . PHP_EOL;
    }

    private function markInProgress(array $arguments): void
    {
        $id = $this->getId($arguments);

        $this->taskManager->markTask(
            $id,
            'in-progress'
        );

        echo "Task {$id} marked as in-progress."
            . PHP_EOL;
    }

    private function markDone(array $arguments): void
    {
        $id = $this->getId($arguments);

        $this->taskManager->markTask(
            $id,
            'done'
        );

        echo "Task {$id} marked as done."
            . PHP_EOL;
    }

    private function list(array $arguments): void
    {
        $status = $arguments[2] ?? null;

        $tasks = $this->taskManager->getTasks($status);

        if ($tasks === []) {
            echo 'No tasks found.' . PHP_EOL;
            return;
        }

        foreach ($tasks as $task) {
            echo sprintf(
                '[%d] [%s] %s%s',
                $task['id'],
                $task['status'],
                $task['description'],
                PHP_EOL
            );
        }
    }

    private function getId(array $arguments): int
    {
        $id = filter_var(
            $arguments[2] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id === null || $id < 1) {
            throw new InvalidArgumentException(
                'A valid task ID is required.'
            );
        }

        return $id;
    }

    private function printUsage(): void
    {
        echo <<<TEXT
            Task Tracker CLI

            Usage:
            php task-cli.php add "Task description"
            php task-cli.php update <id> "New description"
            php task-cli.php delete <id>
            php task-cli.php mark-in-progress <id>
            php task-cli.php mark-done <id>
            php task-cli.php list
            php task-cli.php list todo
            php task-cli.php list in-progress
            php task-cli.php list done

            TEXT;
    }
}
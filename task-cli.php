<?php

declare(strict_types=1);

require_once __DIR__ . '/src/JsonTaskRepository.php';
require_once __DIR__ . '/src/TaskManager.php';
require_once __DIR__ . '/src/CliApplication.php';

$repository = new JsonTaskRepository(
    __DIR__ . '/tasks.json'
);

$taskManager = new TaskManager($repository);

$application = new CliApplication($taskManager);

try {
    $application->run($argv);
} catch (Throwable $exception) {
    fwrite(
        STDERR,
        'Error: ' . $exception->getMessage() . PHP_EOL
    );

    exit(1);
}
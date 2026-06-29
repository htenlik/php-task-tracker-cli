<?php

declare(strict_types=1);

class JsonTaskRepository
{
    public function __construct(
        private string $filePath
    ) {
    }

    public function getAll(): array
    {
        if (!file_exists($this->filePath)) {
            $this->save([]);
            return [];
        }

        $content = file_get_contents($this->filePath);

        if ($content === false) {
            throw new RuntimeException('Tasks file could not be read.');
        }

        if (trim($content) === '') {
            return [];
        }

        try {
            $tasks = json_decode(
                $content,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $exception) {
            throw new RuntimeException(
                'tasks.json contains invalid JSON: '
                . $exception->getMessage()
            );
        }

        if (!is_array($tasks)) {
            throw new RuntimeException(
                'tasks.json must contain a JSON array.'
            );
        }

        return $tasks;
    }

    public function save(array $tasks): void
    {
        try {
            $json = json_encode(
                $tasks,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_THROW_ON_ERROR
            );
        } catch (JsonException $exception) {
            throw new RuntimeException(
                'Tasks could not be converted to JSON: '
                . $exception->getMessage()
            );
        }

        $result = file_put_contents(
            $this->filePath,
            $json,
            LOCK_EX
        );

        if ($result === false) {
            throw new RuntimeException(
                'Tasks file could not be written.'
            );
        }
    }
}
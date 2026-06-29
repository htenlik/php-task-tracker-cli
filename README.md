# Task Tracker CLI

A command-line task tracking application built with PHP.

This project is a solution for the [Task Tracker project](https://roadmap.sh/projects/task-tracker) on roadmap.sh.

## Features

- Add tasks
- Update tasks
- Delete tasks
- Mark tasks as in progress
- Mark tasks as done
- List all tasks
- Filter tasks by status
- Store tasks in a JSON file

## Requirements

- PHP 8.0 or newer

## Usage

Add a task:

```bash
php task-cli.php add "Buy groceries"
```

Update a task:

```bash
php task-cli.php update 1 "Buy groceries and cook dinner"
```

Delete a task:

```bash
php task-cli.php delete 1
```

Mark a task as in progress:

```bash
php task-cli.php mark-in-progress 1
```

Mark a task as done:

```bash
php task-cli.php mark-done 1
```

List all tasks:

```bash
php task-cli.php list
```

List tasks by status:

```bash
php task-cli.php list todo
php task-cli.php list in-progress
php task-cli.php list done
```

## Project Structure

```text
php-task-tracker-cli/
├── src/
│   ├── CliApplication.php
│   ├── JsonTaskRepository.php
│   └── TaskManager.php
├── task-cli.php
└── README.md
```

## Project URL

https://roadmap.sh/projects/task-tracker

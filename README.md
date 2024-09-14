# TaskManager

TaskManager is a web application built with PHP and Symfony to help users manage their daily tasks effectively. 
It allows users to create, edit, delete, and view tasks, providing a seamless to-do list experience.

# 🌟 Features

 - User registration and login functionality
 - Create, view, edit, and delete tasks
 - Separate views for today's tasks and future tasks
 - Task management based on user sessions
 - Responsive design with Bootstrap
# 🚀 Installation

Clone this repository and set up the project:

```bash
git clone https://github.com/IvanYanishevskyi/TaskManager.git
cd TaskManager
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
symfony server:start
```

# ⚙️ Requirements

- PHP 8.0+
- Symfony 5.4+
- Composer
- MySQL or PostgreSQL

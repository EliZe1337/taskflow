# TaskFlow — Корпоративная система управления задачами

> **Портфолио-проект** демонстрирует: Laravel 11, роли (Spatie), Eloquent ORM, Docker, MySQL, Redis, Blade-шаблоны, Policies, Soft Deletes, Activity Log.

## Стек технологий

| Слой | Технология |
|------|-----------|
| Backend | PHP 8.2, Laravel 11 |
| Роли и права | spatie/laravel-permission |
| База данных | MySQL 8.0 |
| Кэш и очереди | Redis 7 |
| Веб-сервер | Nginx + PHP-FPM |
| Контейнеризация | Docker + Docker Compose |
| Frontend | Blade + Tailwind CSS CDN + Alpine.js |
| UI для БД | phpMyAdmin |

---

## Роли и права доступа

| Роль | Проекты | Задачи | Пользователи |
|------|---------|--------|-------------|
| **admin** | Всё | Всё | Создание, удаление |
| **manager** | Создание, редактирование | Создание, редактирование, удаление | Только просмотр |
| **developer** | Только просмотр | Создание и редактирование своих | — |
| **viewer** | Только просмотр | Только просмотр | — |

---

## Быстрый старт (5 минут)

### Требования

- **Docker Desktop** — https://www.docker.com/products/docker-desktop/
- **Git** — https://git-scm.com/
- Свободные порты: **8080** (сайт), **8081** (phpMyAdmin), **3306** (MySQL), **6379** (Redis)

### 1. Клонировать репозиторий

```bash
git clone https://github.com/ВАШ_ЛОГИН/taskflow.git
cd taskflow
```

### 2. Запустить одной командой

```bash
make setup
```

Или вручную по шагам:

```bash
# Скопировать конфиг окружения
cp .env.example .env

# Запустить контейнеры в фоне
docker compose up -d

# Подождать 15 секунд пока MySQL запустится
sleep 15

# Установить PHP-зависимости через Composer
docker compose exec app composer install

# Сгенерировать APP_KEY (обязательно!)
docker compose exec app php artisan key:generate

# Создать таблицы и заполнить тестовыми данными
docker compose exec app php artisan migrate --seed

# Создать symlink для хранилища файлов
docker compose exec app php artisan storage:link
```

### 3. Открыть в браузере

| Адрес | Что открывается |
|-------|----------------|
| http://localhost:8080 | TaskFlow — основное приложение |
| http://localhost:8081 | phpMyAdmin — управление базой данных |

---

## Тестовые аккаунты

| Роль | Email | Пароль |
|------|-------|--------|
| Администратор | admin@taskflow.local | password |
| Менеджер | manager@taskflow.local | password |
| Разработчик | maria@taskflow.local | password |
| Наблюдатель | viewer@taskflow.local | password |

> На странице входа есть кнопки для быстрого входа под каждой ролью — не нужно вводить вручную.

---

## Команды разработки

```bash
# Управление сервисами
make up          # Запустить все контейнеры
make down        # Остановить контейнеры
make logs        # Логи приложения (live)
make shell       # Bash внутри контейнера app
make tinker      # Artisan tinker (REPL Laravel)
make fresh       # Пересоздать БД с тестовыми данными

# Напрямую через docker compose
docker compose exec app php artisan route:list
docker compose exec app php artisan make:model ModelName -mf
docker compose exec app php artisan queue:work
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
```

---

## Структура проекта

```
taskflow/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php       # Вход / выход
│   │   ├── DashboardController.php  # Главная страница
│   │   ├── ProjectController.php    # CRUD проектов
│   │   ├── TaskController.php       # CRUD задач
│   │   ├── CommentController.php    # Комментарии
│   │   └── UserController.php       # Управление пользователями
│   ├── Models/
│   │   ├── User.php        # Пользователь (HasRoles trait)
│   │   ├── Project.php     # Проект (SoftDeletes, scopes)
│   │   ├── Task.php        # Задача (SoftDeletes, scopes)
│   │   ├── Comment.php     # Комментарии
│   │   ├── Tag.php         # Теги задач
│   │   ├── ActivityLog.php # История изменений
│   │   └── Attachment.php  # Вложения
│   ├── Policies/
│   │   ├── ProjectPolicy.php  # Кто что может с проектами
│   │   ├── TaskPolicy.php     # Кто что может с задачами
│   │   └── UserPolicy.php     # Кто управляет пользователями
│   └── Providers/
│       └── AppServiceProvider.php  # Регистрация Policy
├── database/
│   ├── migrations/    # История структуры БД
│   └── seeders/
│       └── DatabaseSeeder.php  # Тестовые данные + роли
├── resources/views/
│   ├── layouts/app.blade.php  # Общий layout с sidebar
│   ├── auth/login.blade.php   # Страница входа
│   ├── dashboard/             # Дашборд
│   ├── projects/              # Проекты (index, show, create, edit)
│   ├── tasks/                 # Задачи (index, show, create, edit)
│   └── users/                 # Пользователи (index, create, edit)
├── routes/
│   └── web.php        # Все маршруты приложения
├── docker/
│   ├── php/Dockerfile       # PHP 8.2-FPM образ
│   ├── php/php.ini          # Настройки PHP
│   ├── nginx/default.conf   # Конфиг Nginx
│   └── mysql/my.cnf         # Настройки MySQL
├── docker-compose.yml   # Оркестрация всех сервисов
├── .env.example         # Шаблон переменных окружения
└── Makefile             # Удобные команды
```

---

## Архитектурные решения

### MVC + Policies
Вся авторизация вынесена в Policy-классы — контроллеры не содержат if-проверок прав.
Каждый Policy-метод проверяет роль через Spatie Permission.

### Eloquent Scopes
Переиспользуемые фрагменты запросов:
```php
// Только проекты пользователя (владелец ИЛИ участник)
Project::forUser($user)->active()->with("tasks")->get();

// Просроченные задачи
Task::overdue()->assignedTo($user->id)->get();
```

### Soft Deletes
Проекты и задачи не удаляются физически — помечаются как удалённые.
Восстановление через `$model->restore()`.

### Activity Log
Каждое изменение статуса задачи записывается в `activity_logs` с хранением
старого и нового значения в JSON-поле `properties`.

### Роли (Spatie)
4 роли с нарастающими правами: viewer → developer → manager → admin.
Роли назначаются администратором через UI.

---

## База данных (ERD)

```
users ──────────────────────────────────────────────────────────
  id, name, email, password, position, department, is_active

roles / permissions (spatie tables) ────────────────────────────
  users <-> roles через model_has_roles
  roles <-> permissions через role_has_permissions

projects ───────────────────────────────────────────────────────
  id, name, description, color, status, start_date, due_date
  owner_id → users.id

project_user (pivot) ───────────────────────────────────────────
  project_id, user_id, role (manager/developer/viewer)

tasks ──────────────────────────────────────────────────────────
  id, title, description, status, priority
  project_id → projects.id
  creator_id → users.id
  assignee_id → users.id (nullable)
  parent_id → tasks.id (подзадачи, nullable)
  due_date, estimated_hours, actual_hours, position

tags / task_tag (pivot) ────────────────────────────────────────
  tags: id, name, color
  task_tag: task_id, tag_id

comments ───────────────────────────────────────────────────────
  id, body, task_id, user_id

activity_logs ──────────────────────────────────────────────────
  id, event, description, task_id, user_id, properties (JSON)

attachments ────────────────────────────────────────────────────
  id, original_name, path, mime_type, size, task_id, user_id
```

---

## Возможные расширения (для углубления портфолио)

- [ ] **REST API** — добавить `routes/api.php` + `ApiResource` классы
- [ ] **Тесты** — `php artisan make:test TaskControllerTest --feature`
- [ ] **Уведомления** — email при назначении задачи (Laravel Notifications)
- [ ] **Очереди** — тяжёлые операции через `dispatch()`
- [ ] **Экспорт** — задачи в Excel через `maatwebsite/excel`
- [ ] **Kanban drag & drop** — библиотека SortableJS
- [ ] **Real-time** — статус задачи через Laravel Echo + Pusher
- [ ] **2FA** — двухфакторная аутентификация

---

## Для работодателя

Этот проект демонстрирует владение:

- **Laravel 11** — роутинг, Eloquent, Blade, Artisan, Middleware
- **ООП и SOLID** — Policy-классы, Service Provider, отдельные модели
- **Безопасность** — CSRF, авторизация через Gate/Policy, хэширование паролей
- **Базы данных** — миграции, foreign keys, soft deletes, JSON поля, индексы
- **Docker** — многоконтейнерная разработка, Nginx, PHP-FPM
- **Архитектурные паттерны** — MVC, Repository-like через Eloquent scopes
- **Командная работа** — .gitignore, .env.example, README, Makefile
# taskflow
# taskflow
# taskflow

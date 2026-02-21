<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Роли ─────────────────────────────────────────────
        $roles = [
            'admin'     => 'Администратор — полный доступ',
            'manager'   => 'Менеджер — управление проектами и командой',
            'developer' => 'Разработчик — работа с задачами',
            'viewer'    => 'Наблюдатель — только просмотр',
        ];

        $createdRoles = [];
        foreach ($roles as $name => $description) {
            $createdRoles[$name] = Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // ── Права ─────────────────────────────────────────────
        $permissions = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'projects.create', 'projects.edit', 'projects.delete',
            'tasks.create', 'tasks.edit', 'tasks.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Назначаем права ролям
        $createdRoles['admin']->givePermissionTo(Permission::all());
        $createdRoles['manager']->givePermissionTo([
            'users.view', 'projects.create', 'projects.edit',
            'tasks.create', 'tasks.edit', 'tasks.delete',
        ]);
        $createdRoles['developer']->givePermissionTo([
            'tasks.create', 'tasks.edit',
        ]);

        // ── Теги ──────────────────────────────────────────────
        $tags = [
            ['name' => 'Backend',   'color' => '#3B82F6'],
            ['name' => 'Frontend',  'color' => '#8B5CF6'],
            ['name' => 'Database',  'color' => '#10B981'],
            ['name' => 'DevOps',    'color' => '#F59E0B'],
            ['name' => 'Bug',       'color' => '#EF4444'],
            ['name' => 'Feature',   'color' => '#06B6D4'],
            ['name' => 'Testing',   'color' => '#84CC16'],
            ['name' => 'Design',    'color' => '#EC4899'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag['name']], $tag);
        }

        // ── Пользователи ──────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@taskflow.local'],
            [
                'name'       => 'Администратор Системы',
                'password'   => Hash::make('password'),
                'position'   => 'CTO',
                'department' => 'IT',
                'is_active'  => true,
            ]
        );
        $admin->assignRole('admin');

        $manager = User::firstOrCreate(
            ['email' => 'manager@taskflow.local'],
            [
                'name'       => 'Иван Петров',
                'password'   => Hash::make('password'),
                'position'   => 'Project Manager',
                'department' => 'Разработка',
                'is_active'  => true,
            ]
        );
        $manager->assignRole('manager');

        $developers = [
            ['name' => 'Мария Козлова',  'email' => 'maria@taskflow.local',  'position' => 'Senior PHP Developer'],
            ['name' => 'Алексей Смирнов','email' => 'alex@taskflow.local',   'position' => 'Frontend Developer'],
            ['name' => 'Елена Новикова', 'email' => 'elena@taskflow.local',  'position' => 'Backend Developer'],
            ['name' => 'Дмитрий Волков', 'email' => 'dmitry@taskflow.local', 'position' => 'DevOps Engineer'],
        ];

        $devUsers = [];
        foreach ($developers as $dev) {
            $user = User::firstOrCreate(
                ['email' => $dev['email']],
                [
                    'name'       => $dev['name'],
                    'password'   => Hash::make('password'),
                    'position'   => $dev['position'],
                    'department' => 'Разработка',
                    'is_active'  => true,
                ]
            );
            $user->assignRole('developer');
            $devUsers[] = $user;
        }

        $viewer = User::firstOrCreate(
            ['email' => 'viewer@taskflow.local'],
            [
                'name'       => 'Ольга Белова',
                'password'   => Hash::make('password'),
                'position'   => 'Business Analyst',
                'department' => 'Аналитика',
                'is_active'  => true,
            ]
        );
        $viewer->assignRole('viewer');

        // ── Проекты ───────────────────────────────────────────
        $project1 = Project::firstOrCreate(
            ['name' => 'TaskFlow 2.0 — Рефакторинг'],
            [
                'description' => 'Полный рефакторинг системы управления задачами. Переход на Laravel 11, Inertia.js, улучшение производительности.',
                'color'       => '#3B82F6',
                'status'      => 'active',
                'start_date'  => now()->subMonths(2),
                'due_date'    => now()->addMonths(2),
                'owner_id'    => $manager->id,
            ]
        );

        $project2 = Project::firstOrCreate(
            ['name' => 'Мобильное приложение iOS/Android'],
            [
                'description' => 'Разработка мобильного приложения для TaskFlow с использованием React Native.',
                'color'       => '#10B981',
                'status'      => 'active',
                'start_date'  => now()->subMonth(),
                'due_date'    => now()->addMonths(4),
                'owner_id'    => $manager->id,
            ]
        );

        $project3 = Project::firstOrCreate(
            ['name' => 'Интеграция с Jira API'],
            [
                'description' => 'Двусторонняя синхронизация задач с Jira для enterprise клиентов.',
                'color'       => '#F59E0B',
                'status'      => 'on_hold',
                'owner_id'    => $admin->id,
            ]
        );

        // Добавляем участников в проекты
        foreach ($devUsers as $dev) {
            $project1->members()->syncWithoutDetaching([$dev->id => ['role' => 'developer']]);
            $project2->members()->syncWithoutDetaching([$dev->id => ['role' => 'developer']]);
        }

        // ── Задачи ────────────────────────────────────────────
        $taskData = [
            // Проект 1
            [
                'title'           => 'Обновить Laravel до версии 11',
                'description'     => 'Провести обновление фреймворка. Проверить совместимость всех пакетов. Обновить конфигурационные файлы.',
                'status'          => 'done',
                'priority'        => 'critical',
                'project_id'      => $project1->id,
                'creator_id'      => $manager->id,
                'assignee_id'     => $devUsers[0]->id,
                'due_date'        => now()->subDays(10),
                'estimated_hours' => 8,
                'actual_hours'    => 12,
                'tags'            => [1, 3], // Backend, Database
            ],
            [
                'title'           => 'Написать тесты для TaskController',
                'description'     => 'Покрыть все эндпоинты Feature-тестами. Минимальное покрытие — 80%.',
                'status'          => 'in_progress',
                'priority'        => 'high',
                'project_id'      => $project1->id,
                'creator_id'      => $manager->id,
                'assignee_id'     => $devUsers[0]->id,
                'due_date'        => now()->addDays(3),
                'estimated_hours' => 16,
                'tags'            => [7], // Testing
            ],
            [
                'title'           => 'Настроить Redis кэширование для дашборда',
                'description'     => 'Закэшировать статистику дашборда. Инвалидация кэша при изменении задач.',
                'status'          => 'todo',
                'priority'        => 'medium',
                'project_id'      => $project1->id,
                'creator_id'      => $devUsers[2]->id,
                'assignee_id'     => $devUsers[2]->id,
                'due_date'        => now()->addDays(7),
                'estimated_hours' => 6,
                'tags'            => [1, 4], // Backend, DevOps
            ],
            [
                'title'           => 'Redesign: карточки задач в Kanban-борде',
                'description'     => 'Новый дизайн карточек по макету из Figma. Добавить drag & drop.',
                'status'          => 'review',
                'priority'        => 'high',
                'project_id'      => $project1->id,
                'creator_id'      => $manager->id,
                'assignee_id'     => $devUsers[1]->id,
                'due_date'        => now()->addDays(2),
                'estimated_hours' => 20,
                'tags'            => [2, 8], // Frontend, Design
            ],
            // Проект 2
            [
                'title'           => 'Настроить React Native проект',
                'description'     => 'Expo, навигация, state management (Zustand), API клиент.',
                'status'          => 'done',
                'priority'        => 'critical',
                'project_id'      => $project2->id,
                'creator_id'      => $manager->id,
                'assignee_id'     => $devUsers[1]->id,
                'estimated_hours' => 12,
                'actual_hours'    => 10,
                'tags'            => [2], // Frontend
            ],
            [
                'title'           => 'Экран авторизации и регистрации',
                'description'     => 'JWT авторизация через API. Хранение токена в SecureStore.',
                'status'          => 'in_progress',
                'priority'        => 'high',
                'project_id'      => $project2->id,
                'creator_id'      => $manager->id,
                'assignee_id'     => $devUsers[1]->id,
                'due_date'        => now()->addDays(5),
                'estimated_hours' => 16,
                'tags'            => [2, 6], // Frontend, Feature
            ],
            [
                'title'           => 'REST API для мобильного приложения',
                'description'     => 'Эндпоинты для авторизации, задач, проектов. Версионирование /api/v1/.',
                'status'          => 'in_progress',
                'priority'        => 'critical',
                'project_id'      => $project2->id,
                'creator_id'      => $devUsers[0]->id,
                'assignee_id'     => $devUsers[0]->id,
                'due_date'        => now()->addDays(4),
                'estimated_hours' => 24,
                'tags'            => [1, 6], // Backend, Feature
            ],
        ];

        foreach ($taskData as $data) {
            $tags = $data['tags'] ?? [];
            unset($data['tags']);

            $task = Task::firstOrCreate(
                ['title' => $data['title'], 'project_id' => $data['project_id']],
                $data
            );

            if (!empty($tags)) {
                $task->tags()->syncWithoutDetaching($tags);
            }
        }

        $this->command->info('✅ База данных заполнена тестовыми данными!');
        $this->command->info('');
        $this->command->info('👤 Тестовые аккаунты:');
        $this->command->table(
            ['Роль', 'Email', 'Пароль'],
            [
                ['Администратор', 'admin@taskflow.local',   'password'],
                ['Менеджер',      'manager@taskflow.local', 'password'],
                ['Разработчик',   'maria@taskflow.local',   'password'],
                ['Наблюдатель',   'viewer@taskflow.local',  'password'],
            ]
        );
    }
}

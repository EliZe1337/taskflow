<img width="1919" height="996" alt="Снимок экрана 2026-02-21 в 22 09 10" src="https://github.com/user-attachments/assets/ff835ebf-7b43-4c84-8689-b0a953e1d8b2" /># TaskFlow — Корпоративная система управления задачами

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

<img width="1925" height="995" alt="Снимок экрана 2026-02-21 в 22 09 05" src="https://github.com/user-attachments/assets/69b1398e-46fd-45a5-9f16-72acdc1c3c0c" />
<img width="1919" height="996" alt="Снимок экрана 2026-02-21 в 22 09 10" src="https://github.com/user-attachments/assets/667fdb92-abd6-42b7-828d-81efed2de178" />
<img width="1931" height="996" alt="Снимок экрана 2026-02-21 в 22 09 14" src="https://github.com/user-attachments/assets/cd816af6-99d6-445f-9055-6cc0383243ef" />
<img width="1932" height="995" alt="Снимок экрана 2026-02-21 в 22 09 18" src="https://github.com/user-attachments/assets/9f34e8fd-413a-4d8a-b671-e56ac2ce7cc9" />
<img width="1930" height="1002" alt="Снимок экрана 2026-02-21 в 22 09 22" src="https://github.com/user-attachments/assets/e6baebf1-ecd9-4d5e-b092-9e1dc978d754" />


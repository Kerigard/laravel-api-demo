# Laravel API Demo

API-приложение на Laravel с поддержкой развёртывания в Docker-окружении и встроенной аутентификацией с использованием JWT.

## Технологии

- `PHP` = `8.4`
- `Laravel` = `12`
- `Docker` >= `20.10`

## Сборка и запуск

### Локальная разработка (dev)

```bash
WWWUSER="$(id -u)" WWWGROUP="$(id -g)" docker compose up -d
sail artisan migrate --seed
```

> [!NOTE]
> Чтобы избежать проблем с правами доступа к файлам и папкам, необходимо запускать команды от обычного пользователя (не root).<br>
> Рекомендуется использовать `sail`, так как он автоматически задаёт переменные окружения `WWWUSER` и `WWWGROUP`.

### Продакшн-среда (prod)

```bash
cp .env.example .env
docker compose -f docker-compose.prod.yml up -d --build
docker compose -f docker-compose.prod.yml exec app php artisan migrate
```

> [!NOTE]
> Перед запуском продакшн-окружения необходимо настроить переменные окружения в `.env`-файле.

## Разработка и тестирование

### Миграции и сидирование

```bash
sail artisan migrate --seed
```

### Запуск тестов

```bash
sail test
```

### Проверка стиля кода

```bash
sail pint
```

### Статический анализ

```bash
sail bin phpstan analyse --memory-limit=1G
```

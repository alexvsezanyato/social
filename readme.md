```
docker compose up -d
docker compose exec -w /var/www -it php sh -c "composer install"
docker compose exec -w /var/www -it php sh -c "composer migration -n migrate"
docker compose run --rm -w /var/www frontend sh -c "npm i"
docker compose run --rm -w /var/www frontend sh -c "npm run build"
docker compose run --rm -w /var/www frontend sh -c "npm run postinstall"
```

Функционал:
- Регистрация, Вход
- Создание постов
    - Текст
    - Прикрепление изображений
    - Прикрепление файлов
- Комментирование постов

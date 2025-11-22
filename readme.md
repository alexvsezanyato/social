```
docker compose -f compose.yaml -f compose.development.yaml up -d
docker compose -f compose.yaml -f compose.development.yaml exec -w /var/www -it php sh -c "composer install"
docker compose -f compose.yaml -f compose.development.yaml exec -w /var/www -it php sh -c "composer migration -n migrate"
docker compose -f compose.yaml -f compose.development.yaml run --rm -w /var/www frontend sh -c "npm i"
docker compose -f compose.yaml -f compose.development.yaml run --rm -w /var/www frontend sh -c "npm run build"
docker compose -f compose.yaml -f compose.development.yaml run --rm -w /var/www frontend sh -c "npm run postinstall"
```

Функционал:
- Регистрация, Вход
- Создание постов
    - Текст
    - Прикрепление изображений
    - Прикрепление файлов
- Комментирование постов

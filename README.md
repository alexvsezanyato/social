```
ln -s compose.development.yaml compose.override.yaml
docker compose run --rm frontend npm i
docker compose up -d
docker compose exec php composer install
docker compose exec php composer migration -n migrate
docker compose exec frontend npm i
docker compose exec frontend npm run build
```

Функционал:
- Регистрация, Вход
- Создание постов
    - Текст
    - Прикрепление изображений
    - Прикрепление файлов
- Комментирование постов

## About Tapp

> Task + app = Tapp

Test project for CSIHA.

## For developers

```bash
# copy .env.example to .env
# and fill the variables
cp .env.example .env

# download composer...
# install composer packages
composer install

# start laravel sail
./vendor/bin/sail up

# install npm packages
./vendor/bin/sail npm i -D

# generate app key
./vendor/bin/sail artisan key:generate

# migrate database with seed
./vendor/bin/sail artisan migrate:fresh --seed

# run vite dev server
./vendor/bin/sail npm run dev
```

### Environment variables

| Variable                          | Description                                        | Value     |
|-----------------------------------|----------------------------------------------------|-----------|
| `VITE_HTTP_REQUEST_DELAY_SECONDS` | Delay for http requests in seconds. `0.0`=disabled | `0.0`-... |
| `VITE_HTTP_REQUEST_LOG`           | Log http requests to browser console.              | `0`, `1`  |

## Authors

- [Márton Somogyi](https://github.com/kamarton)

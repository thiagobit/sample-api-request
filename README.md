# Sample API Request

# Instalation
* Create the `.env` file based on `.env.example`:
```shell
cp .env.example .env
```

* Since it's using [Laravel Sail](https://laravel.com/docs/9.x/sail), you need to execute the following command to first install the dependencies and be able to run Sail commands:
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

* Create Docker containers:
```shell
./vendor/bin/sail up
```

* Generate the application key:
```shell
./vendor/bin/sail artisan key:generate
```

* Run migrations:
```shell
./vendor/bin/sail artisan migrate
```

## Executing tests
You may use Sail's test command to run your applications feature and unit tests:
```shell
./vendor/bin/sail artisan test
```
Any CLI options that are accepted by [PHPUnit](https://phpunit.readthedocs.io/en/9.5/textui.html) may also be passed to the `test` command.

---

**Tip:** Instead of repeatedly typing `./vendor/bin/sail` to execute Sail commands, you may wish to configure a Bash alias that allows you to execute Sail's commands more easily:
```shell
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```
Once the Bash alias has been configured, you may execute Sail commands by simply typing `sail`:
```shell
sail up
sail artisan test
```

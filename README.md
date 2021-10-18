# Upcoming birthdays

## Requirements
* PHP >= 7.3
* ext-mongodb installed

## Installing and run
* clone this repository and open project directory
* `composer install --prefer-dist`
* add database configuration to .env file (`cp .env.example .env`)
* `php -S localhost:8000 -t public`

## Add new person CURL example
<pre>
curl -s POST 'http://localhost:8000/person/' --header 'Content-Type: application/json' \
--data-raw '{"name": "Ken Thompson", "birthdate": "1943-02-04", "timezone": "America/New_York"}' \
| jq .
</pre>

## Get persons list CURL example
<pre>
curl -s http://localhost:8000/person/ | jq .
</pre>

## Run tests
<pre>
php  ./vendor/bin/phpunit -c phpunit.xml
</pre>

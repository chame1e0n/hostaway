# hostaway
A project to display developer's skills.

## Legend:
<placeholder|default_value>: section where you have to set your own data, or to use suggested default value.\
[optional_data]: section with optional data

## Installation and Configuration

1: Create and visit your already existing project folder.
```
>cd <disk>:\<folders>\<project_folder>
```
2: Download project package in your existing project folder.
``` bash
>composer create-project chameleon/hostaway . --stability=dev --repository="{\"type\": \"vcs\", \"url\": \"https://github.com/chame1e0n/hostaway.git\"}"
```
3. Launch your docker machine, which is usually with "default" name.
```
>docker-machine start <docker_machine|default>
```
4. Check that docker machine is "Running".
```
>docker-machine status <docker_machine|default>
```
5. Launch all services, which are defined in the docker-compose.yaml, in your docker machine in background mode.
``` bash
>docker-compose up -d
```
6. Check that all defined services are in "Up" State.
``` bash
>docker-compose ps
```
7. Check ip of the docker machine and set this ip in /config/config.php file of the project, in the 'host' property.
```
>docker-machine ip <docker_machine|default>
```
8. Do a migration from Doctrine ORM code to tables in docker MySQL service.
``` bash
>vendor\bin\doctrine orm:schema-tool:update --force --dump-sql
```
9. Launch web-server on locale machine with root directory in /public.
``` bash
>php -S 127.0.0.1:8000 -t public
```

## API Usage
1. Get list of items.
Get list of existing phone entities, optionally filtered by specified parameter with specified value and optionally paginated with specified offset and specified amount
```
GET http://127.0.0.1:8000/phone[/<offset>/<amount>?<parameter>=<value>]
```
2. Get the specified item.
Get the existing phone entity of the specified identificator.
```
GET http://127.0.0.1:8000/phone/<id>
```
3. Create a new item.
Create a new phone entity with specified data.
```
POST http://127.0.0.1:8000/phone
```
Body:
``` json
{
"first_name": "<first_name>",
"last_name": "<last_name>",
"phone_number": "<phone_number>",
"country_code": "<country_code>",
"time_zone": "<time_zone>"
}
```
4. Update existing item.
Update the existing phone entity of the specified identificator with new data containing optional parameters.
```
PUT http://127.0.0.1:8000/phone/<id>
```
Body:
``` json
{
["first_name": "<first_name>",]
["last_name": "<last_name>",]
["phone_number": "<phone_number>",]
["country_code": "<country_code>",]
["time_zone": "<time_zone>"]
}
```
5. Delete the specified item.
Delete the existing phone entity of the specified identificator.
```
DELETE http://127.0.0.1:8000/phone/<id>
```

## Comments
OAuth 2 feature is not added, because it requires to display data of html format (login form), which is not connected with default json format of all data in API.

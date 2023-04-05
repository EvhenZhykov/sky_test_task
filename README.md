# SKY TEST TASK

For correct installation, your server must have > PHP 7.2 and Composer installed.
To install, do the following:

- To clone the project to the local machine and enter the project folder
```bash
git clone https://github.com/EvhenZhykov/sky_test_task.git
cd sky_test_task/
```
- Install all application dependencies using [Composer](https://getcomposer.org/)
```bash
composer install
```
- Create an application database, for example (or create DB in phpMyAdmin)
```sql
CREATE DATABASE `sky` COLLATE 'utf8_general_ci'
```
- Set up a connection to the MySQL database in the file **.env**
```bash
DATABASE_URL="mysql://!ChangeMe!:!ChangeMe!@127.0.0.1:3306/!ChangeMe!"
```
- Run the database table generation script
```bash
php bin/console doctrine:schema:update --force
```
- Run the command for fill stars
```bash
php bin/console fill-star-table
```
- Run the web server
```bash
symfony server:start
```
- For testing api you can use api documentation
```bash
!localServerHost!/api/doc
```
- or you can use this postman collection, just import this to the postman
```json
{
  "info": {
    "_postman_id": "dccea5b0-48e8-4718-8724-55ad0305306c",
    "name": "Sky",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
    "_exporter_id": "2999561"
  },
  "item": [
    {
      "name": "Create Star",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Sky-authorization",
            "value": "main300",
            "type": "text"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"name\": \"Large Magellanic Cloud111222\",\n    \"galaxy\": \"Large Magellanic Cloud\",\n    \"radius\": 17000,\n    \"temperature\": 6500,\n    \"rotation_frequency\": 0.0000018,\n    \"atoms_found\": [\"1\", \"2\", \"3\", \"4\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\"]\n}",
          "options": {
            "raw": {
              "language": "json"
            }
          }
        },
        "url": {
          "raw": "http://127.0.0.1:8001/api/create",
          "protocol": "http",
          "host": [
            "127",
            "0",
            "0",
            "1"
          ],
          "port": "8001",
          "path": [
            "api",
            "create"
          ]
        }
      },
      "response": []
    },
    {
      "name": "Get Star",
      "request": {
        "method": "GET",
        "header": [
          {
            "key": "Sky-authorization",
            "value": "main300",
            "type": "text"
          }
        ],
        "url": {
          "raw": "http://127.0.0.1:8001/api/read/38",
          "protocol": "http",
          "host": [
            "127",
            "0",
            "0",
            "1"
          ],
          "port": "8001",
          "path": [
            "api",
            "read",
            "38"
          ],
          "query": [
            {
              "key": "",
              "value": "",
              "disabled": true
            }
          ]
        }
      },
      "response": []
    },
    {
      "name": "Update Star",
      "request": {
        "method": "PATCH",
        "header": [
          {
            "key": "Sky-authorization",
            "value": "main300",
            "type": "text"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"name\": \"Large Magellanic Cloud232323\",\n    \"galaxy\": \"Large Magellanic Cloud\",\n    \"radius\": 17000,\n    \"temperature\": 6500,\n    \"rotation_frequency\": 0.0000018,\n    \"atoms_found\": [\"1\", \"2\", \"3\", \"4\", \"5\", \"6\", \"7\", \"8\", \"9\", \"10\"]\n}",
          "options": {
            "raw": {
              "language": "json"
            }
          }
        },
        "url": {
          "raw": "http://127.0.0.1:8001/api/update/5001",
          "protocol": "http",
          "host": [
            "127",
            "0",
            "0",
            "1"
          ],
          "port": "8001",
          "path": [
            "api",
            "update",
            "5001"
          ],
          "query": [
            {
              "key": "starId",
              "value": "1",
              "disabled": true
            }
          ]
        }
      },
      "response": []
    },
    {
      "name": "Delete Star",
      "request": {
        "method": "DELETE",
        "header": [
          {
            "key": "Sky-authorization",
            "value": "main300",
            "type": "text"
          }
        ],
        "url": {
          "raw": "http://127.0.0.1:8001/api/delete/38",
          "protocol": "http",
          "host": [
            "127",
            "0",
            "0",
            "1"
          ],
          "port": "8001",
          "path": [
            "api",
            "delete",
            "38"
          ]
        }
      },
      "response": []
    },
    {
      "name": "Get unique Stars",
      "protocolProfileBehavior": {
        "disableBodyPruning": true
      },
      "request": {
        "method": "GET",
        "header": [
          {
            "key": "Sky-authorization",
            "value": "main300",
            "type": "text"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "",
          "options": {
            "raw": {
              "language": "json"
            }
          }
        },
        "url": {
          "raw": "http://127.0.0.1:8001/api/uniqueStars?foundIn=Galaxy A&notFoundIn=Galaxy B&sortBy=size&viewType=basic&atomsList[0]=25&atomsList[1]=7&atomsList[2]=42&atomsList[3]=25&atomsList[4]=48&atomsList[5]=20",
          "protocol": "http",
          "host": [
            "127",
            "0",
            "0",
            "1"
          ],
          "port": "8001",
          "path": [
            "api",
            "uniqueStars"
          ],
          "query": [
            {
              "key": "foundIn",
              "value": "Galaxy A"
            },
            {
              "key": "notFoundIn",
              "value": "Galaxy B"
            },
            {
              "key": "",
              "value": "",
              "disabled": true
            },
            {
              "key": "sortBy",
              "value": "size"
            },
            {
              "key": "viewType",
              "value": "basic"
            },
            {
              "key": "atomsList[0]",
              "value": "25"
            },
            {
              "key": "atomsList[1]",
              "value": "7"
            },
            {
              "key": "atomsList[2]",
              "value": "42"
            },
            {
              "key": "atomsList[3]",
              "value": "25"
            },
            {
              "key": "atomsList[4]",
              "value": "48"
            },
            {
              "key": "atomsList[5]",
              "value": "20"
            },
            {
              "key": "atomsList[6]",
              "value": "31",
              "disabled": true
            },
            {
              "key": "atomsList[7]",
              "value": "27",
              "disabled": true
            },
            {
              "key": "atomsList[8]",
              "value": "26",
              "disabled": true
            },
            {
              "key": "atomsList[9]",
              "value": "34",
              "disabled": true
            },
            {
              "key": "atomsList[0]",
              "value": "40",
              "disabled": true
            },
            {
              "key": "atomsList[1]",
              "value": "43",
              "disabled": true
            },
            {
              "key": "atomsList[2]",
              "value": "100",
              "disabled": true
            }
          ]
        }
      },
      "response": []
    }
  ]
}
```
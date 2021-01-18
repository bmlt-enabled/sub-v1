sub
===
Allows you to send out mass text messages to a list of subscribers.

### Installation

1) Download zip of the repository.
2) Run `composer install` from inside the target dir
3) Create a database `sub`
4) Run `database.sql` on the `sub` database.
5) Add the connection info for the database in `config.php`.
6) Set up a twilio account.
7) Add the twilio info in `config.php`.
8) Upload everything to a server for hosting.
9) Provision a new phone number.
10) Point the webhook to your https://your-web-server/subscribe.php.  HTTP POST should be the method used.
11) `$subscribe_keyword` and `$unsubscribe_keyword` are the trigger words for your subscribers to be added and removed.
12) Once your admin (or broadcaster, the person who is sending out the notifications) has been enrolled in the database, you can set the `is_admin` field to `1`.

### Admin Usage
An admin can send an SMS with `broadcast` and then some message.  That message will be sent to all subscribers.

Lots of room for improvement.

### Local MySQL Container for Development
#### Start the mysql container
```
docker run --name sub-mysql \
  -e MYSQL_ROOT_PASSWORD=sub \
  -e MYSQL_DATABASE=sub \
  -e MYSQL_USER=sub \
  -e MYSQL_PASSWORD=sub \
  -p 3306:3306 \
  -d mysql:8
```

#### Create the schema
```
docker run -i --network=host mysql mysql --host=0.0.0.0 --user=sub --password=sub sub < database.sql
```

#### Connect to the db from the command line
```
docker run -it --network=host mysql mysql --host=0.0.0.0 --user=sub --password=sub sub
```

#### Modify config.php
```
static $mysql_hostname = "0.0.0.0";
static $mysql_username = "sub";
static $mysql_password = "sub";
static $mysql_database = "sub";
```
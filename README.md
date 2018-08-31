# TODOLISTS

### Installation

Create your database and Enter commands like the following for clone.

```sh
$ git clone https://github.com/codegoe/todolist.git
```
Update composer using commands like this.
```sh
$ cd todolist
$ composer install
```

Folow this commands for copy .env.example And configure the .env file
```sh
$ cp .env.example .env
```

Do migrate and seeding to fill tables using faker, type like this.

```sh
$ php artisan migrate --seed
```

### Packages

There are some pacakage used, such as:

| Plugin | README |
| ------ | ------ |
| Datatable | [https://yajrabox.com/docs/laravel-datatables/master/installation][PlDb] |
| Faker | [https://github.com/fzaninotto/Faker][PlGh] |

License
----

MIT

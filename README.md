# Hosting Panel  'Guten' v1.1.2

## About Project

Hosting Panel  'Guten' is a starter kit for small and medium projects, based on Laravel framework.
It contains: 
* Basic authorization(use Laravel Breeze +  sending letters);
* User and Admin roles(use Spatie Permission);
* Simple admin-panel:
* * settings page;
* * articles page(with CKEditor 5);
* Simple user account
* * settings page.


## Install

Run commands

`
composer create-project chirukinbb/hosting-panel-guten path --repository-url=https://github.com/chirukinbb/hosting-panel-guten
`

next you must create tables in DB(before it create DB and configure connection in .env file) run

`
artisan migrate
`

after, to create roles

`
artisan db:seed
`

in the end of installing, create symbolic links

`
artisan storage:link
`

to run self pusher - running socket-server

`
php artisan websockets:serve
`

it use queue, because you must run

`
php artisan queue:work
`

project ready to be developed. Good luck!

## License

Hosting Panel  'Guten' is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

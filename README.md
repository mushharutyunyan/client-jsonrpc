<p>Service can help you to calculate client debit/credit transactions</p>

## Installation

Clone this repository
```bash
composer install
```
```bash
php artisan migrate
```
Create .env file in route folder and fill required credentials

Run this command if OS is linux, this command manage dispatched jobs
```bash
nohup php artisan queue:listen --daemon &
```




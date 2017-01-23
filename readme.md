# SNSGAL

[![CircleCI](https://circleci.com/gh/BinotaLIU/snsgal-easyform/tree/master.svg?style=svg&circle-token=9adc6b8fd499e985fa72547af680ede8d5741942)](https://circleci.com/gh/BinotaLIU/snsgal-easyform/tree/master)

## Building
```
composer install
cp .env.example .env
vi .env
php artisan key:generate
php artisan migrate
yarn install
gulp --production # or just gulp if you're in developing
```

## License
Copyright (c) snsgal.com, all rights reserved.

These package might including some open source software,
 please refer to their license files.

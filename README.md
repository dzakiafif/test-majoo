## TEST MAJOO BACKEND

Sebelum running project ini, langkah-langkah yang harus dilakukan:
- setelah melakukan git clone/ download project ini, running `composer install`
- buat file bernama `.env`
- copy isi file `.env.example` ke dalam `.env`
- isi hal-hal penting di dalam `.env` seperti `DB_DATABASE`,`DB_USERNAME`,`DB_PASSWORD`
- sebelum running projectnya, harus ketik terlebih dahulu `php artisan jwt:secret` kemudian `php artisan storage:link`
- running projectnya dengan cara ketik `php artisan serve`
- list url nya: `/register` , `/login` , `/profile`, `/update`, `/delete`, `/upload-photo`

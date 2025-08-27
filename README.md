## SEDESE

##### Sistema criado por desenvolvedores da DGDIT.


### Versões:

- PHP: 8.1
- Laravel: 10.10

### Domínio:


##

### Como rodar local:
###### Copie e cole o .env.exemple e renomeie para .env, depois altere os dados para o seu banco de dados local.

```bash 
npm install && composer install

```
```bash
php artisan vendor:publish && php artisan migrate
```
```bash
php artisan key:generate && php artisan db:seed --class=YourTableSeeder
```
```bash
npm run dev && php artisan serve
```

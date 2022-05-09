## Laravel CRUD API
proyecto básico con Laravel de servicios y pagos

----

### How to Run:
1. Descargar proyecto
2. Ir a la carpeta del proyecto y con el comando `composer install` instalar las dependencias
3. Crear archivo `.env` o quitar el .example `.env.example` y acomodar datos de conexión en las variables siguientes:
------------------------
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=user
DB_PASSWORD=password
------------------------
4. Crear base de datos - `prueba_teko` en donde se ejecutará la aplicación.
5. Ejecutar el script que se adjunta en la carpeta `database/sql/prueba_teko.sql`
6. Crear el proyecto con un host virtual en la maquina donde se desea correr o ejecutar el siguiente comando `php artisan serve`

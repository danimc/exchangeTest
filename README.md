# Api Exchange

## Acerca de este proyecto

Este Proyecto genera una API que consume a su vez 3 diferentes servicios para proveer
una respuesta conjunta de estos.


Se desarrollo en Laravel, utilizando Sanctum para la autneticacion por medio de * Tokens *
y para fines practicos una Bd .Sqlite

### Configuracion del sistema

Instale todas las dependencias necesarias ejecutqando el comando

    composer install

una vez echo esto, cree en la carpeta app/database un fichero llamado database.sqlite, donde se alamacenaran los usuarios
y tokens generados en el sistema.

configure la ruta de acceso en en .env para la conexón a la BD. modificando los siguientes parametros:


    DB_CONNECTION=sqlite
    DB_HOST=127.0.0.1 // SU HOST
    DB_PORT=3306        // PUERTO POR DEFECTO
    DB_DATABASE= <Ruta_absoluta>\database\database.sqlite
    DB_USERNAME=root
    DB_PASSWORD=

con la nueeva configuración realizada, ejecute el comando

    php artisan migrate

para configurar la base de datos con las tablas y campos necesarios. 

### API Keys 

las apikeys para consumir los servicios de Banxico y Fixer se deben indicar en el .env con las siguentes claves

banxico:

    BANXICO_TOKEN = ecc29a8c92a6f342b48683de018b2f154324aa69ad7c041dfcbb0357667ef0f4
  
Fixer

    FIXER_KEY=c0bef04b1e0d3b06a0739e9d88990545

## Funcionamiento

### Registro y Login    

Para poder utilizar la API exchange, lo primero que se debe realizar, es registrarse, para ello debe consumir el endpoint

    POST: api/registrar

Debera enviar en el Body de la solicitud los parametros obligatorios:
- name
- email
- password

si realizo conrrectamente el paso anterior, se le asignara un token de autentificacion con una caducidad de 3 minutos,
una vez pasado ese timepo, puede renovarlo haciendo login en el sistema con las credenciales que ingreso anteriormente.
para ello debe consumir el endpoint:

    POST: api/login
Y debera mandar los parametros
-email
-pasword

Esta accion le proveera de un nuevo Token que podra usar en el endpoint Exchange.

### Uso de Exchange

Para consumir la api Exchange, es necesario contar con su token previamente solicitado en los pasos anteriores,
posteriormente, debe configurar el parametro del header de la peticion accept a:

    Accept: application/json'

y mandar su token como tipo BEARER TOKEN en la autorizacion de la peticion
    
    Bearer Token: <TOKEN>

Obtendra como respuesta la comparacion entre 3 diferentes servicios de tipo de cambio.


## Pruebas 

Para ejecutar las pruebas unitarias generadas para el sistema, ejecute el comando

    php artisan test





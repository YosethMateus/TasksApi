TasksProject - API RESTful (Laravel 11)
Descripción
Este proyecto proporciona una API RESTful para la gestión de tareas. Permite crear, listar, editar, eliminar, comentar, hacer un seguimiento de tiempo, y adjuntar archivos a las tareas. Utiliza Laravel 11 con autenticación vía Laravel Sanctum, autorización mediante Policies y manejo de tareas con Soft Deletes para recuperación.

Requisitos del Sistema
PHP 8.2 o superior.
Composer: Para gestionar dependencias de PHP.
Base de datos: MySQL o compatible.

Funcionalidades:
Autenticación
Registro de usuario: POST /register
Login de usuario: POST /login
Logout de usuario: POST /logout (middleware: auth:sanctum)

Tareas
Crear tarea: POST /tasks (middleware: auth:sanctum)
Listar tareas: GET /tasks (middleware: auth:sanctum)
Obtener tarea específica: GET /tasks/{id} (middleware: auth:sanctum)
Asignar tarea: PUT /tasks/{id} (middleware: auth:sanctum)
Eliminar tarea: DELETE /tasks/{id} (middleware: auth:sanctum)
Restaurar tarea (soft delete): PATCH /tasks/{id}/restore (middleware: auth:sanctum)

Comentarios
Agregar comentario a tarea: POST /tasks/{id}/comments (middleware: auth:sanctum)
Listar comentarios de tarea: GET /tasks/{id}/comments (middleware: auth:sanctum)

Registro de tiempo
Registrar tiempo en tarea: POST /tasks/{id}/time-log (middleware: auth:sanctum)
Listar tiempos registrados en tarea: GET /tasks/{id}/time-log (middleware: auth:sanctum)

Archivos
Subir archivo a tarea: POST /tasks/{id}/upload (middleware: auth:sanctum)
Listar archivos adjuntos a tarea: GET /tasks/{id}/files (middleware: auth:sanctum)

Autorización
El acceso a las rutas está protegido mediante Laravel Sanctum, que proporciona un sistema de autenticación basada en tokens. Además, la autorización de acciones específicas sobre las tareas se maneja mediante Policies, asegurando que solo los usuarios autorizados puedan realizar ciertas operaciones, como agregar comentarios a tareas a las que no están asignados.

Soft Deletes
El proyecto implementa Soft Deletes para permitir la recuperación de tareas eliminadas. Se puede restaurar una tarea eliminada con el endpoint PATCH /tasks/{id}/restore.

Envío de correos
Cuando se asigna una tarea, se envía un correo electrónico al usuario responsable de la tarea. El correo es enviado usando el servicio de correo configurado en el archivo .env de Laravel. Se utiliza el sistema de notificaciones de Laravel para gestionar el envío.

Dependencias
Laravel 11

Laravel Sanctum: Para la autenticación basada en tokens.

Soft Deletes: Para la recuperación de tareas eliminadas.

Policies de Laravel: Para manejar la autorización.


Instalación
Para instalar y ejecutar este proyecto en tu entorno local, sigue estos pasos:

1. Clonar el Repositorio
Clona el repositorio en tu máquina local usando Git:
git clone <https://github.com/YosethMateus/TasksApi/>

2. Instalación de Dependencias
Accede a la carpeta del proyecto y ejecuta el siguiente comando para instalar las dependencias de PHP:
cd <TasksProject>
composer install
Esto instalará todas las dependencias necesarias definidas en el archivo composer.json.

3. Configuración del Entorno
Copia el archivo .env.example a .env para configurar las variables de entorno:
Abre el archivo .env y configura las siguientes variables:

DB_CONNECTION: mysql (o tu base de datos preferida).
DB_HOST: 127.0.0.1 o la IP de tu servidor de base de datos.
DB_PORT: 3306 (o el puerto que esté usando tu base de datos).
DB_DATABASE: El nombre de la base de datos que deseas usar.
DB_USERNAME: Tu nombre de usuario de base de datos.
DB_PASSWORD: Tu contraseña de base de datos.
MAIL_MAILER: smtp (o el servicio que uses para enviar correos).
MAIL_HOST: El host del servicio de correo (ej. smtp.gmail.com).
MAIL_PORT: El puerto del servicio de correo (ej. 587 para Gmail).
MAIL_USERNAME: Tu correo electrónico.
MAIL_PASSWORD: Tu contraseña de correo o la contraseña de aplicación (en el caso de Gmail).

4. Generar la Clave de la Aplicación
Ejecuta el siguiente comando para generar la clave de la aplicación:
php artisan key:generate

5. Ejecutar Migraciones
php artisan migrate
Esto ejecutará las migraciones de Laravel y creará las tablas necesarias en la base de datos.

7. Probar la API
Puedes utilizar herramientas como Postman para probar la API. Asegúrate de enviar los tokens de autenticación obtenidos a través de la ruta login para las rutas que requieren autenticación.

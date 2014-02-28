# Estructura del proyecto

-+Raíz
-+ controllers/ - Controladores de la aplicación.
-+ core/ - Algunos archivos necesarios para que funcione la aplicación.
--+ auth.php - Manejador de las sesiones de usuario.
--+ config.php - Configuración de la aplicación.
--+ controller.php - Define los métodos básidos de los controladores. Los controladores extienden de él.
--+ db.php - Clase para manejar la base de datos.
--+ helpers.php - Funciones de ayuda.
--+ model.php - Define los métodos básidos de los modelos. Los modelos extienden de él.
--+ view.php - Carga las vistas e introduce los datos en ellas.
-+ models/ - Modelos de la aplicación.
-+ public/ - Archivos públicos. Todos los archivos CSS, JS, imágenes, audio, vídeo...
--+ index.php - Archivo principal desde el que se ejecuta la aplicación.
-+ views/ - Vistas de la aplicación.
-+ start.php - En este archivo se hacen todos los require o include de los archivos que se necesitan en todas las páginas y se inician las clases necesarias.
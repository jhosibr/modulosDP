# Módulo de ejemplo para Drupal 8 o 9

Este módulo proporciona un formulario personalizado que se muestra en la ruta `/example-module/form` y permite enviar datos a una tabla llamada `example_users`. También proporciona una ruta `/example-module/data` que muestra los datos almacenados en la tabla `example_users`.

## Desarrollo

Este módulo fue desarrollado siguiendo las especificaciones proporcionadas por el usuario. Se implementó un formulario personalizado usando la API de Formularios de Drupal y se aplicaron estilos para mostrar los campos del formulario en 2 columnas. También se implementó un controlador personalizado para mostrar los datos almacenados en la tabla `example_users`.

## Contenido

Este módulo contiene los siguientes archivos:

- `example_module.info.yml`: Define la información básica del módulo.
- `example_module.routing.yml`: Define las rutas del módulo y especifica qué controlador o formulario se debe usar para cada ruta.
- `src/Form/ExampleForm.php`: Implementa el formulario que se muestra en la ruta `/example-module/form`.
- `src/Controller/ExampleController.php`: Implementa el controlador que se usa para la ruta `/example-module/data` y muestra los datos almacenados en la tabla `example_users`.
- `example_module.install`: Define la estructura de la tabla `example_users` que se crea al momento de instalar el módulo.
- `example_module.libraries.yml`: Define una biblioteca de estilos para el formulario.
- `css/example-form.css`: Define los estilos para mostrar los campos del formulario en 2 columnas.

## Funciones

Este módulo proporciona las siguientes funciones:

- Mostrar un formulario personalizado en la ruta `/example-module/form`.
- Validar y enviar los datos del formulario a una tabla llamada `example_users`.
- Mostrar los datos almacenados en la tabla `example_users` en la ruta `/example-module/data`.

## Uso

Para usar este módulo, sigue estos pasos:

1. En la ruta modules/custom poner la carpeta example_module.
2. Instala el módulo en tu instalación de Drupal 8 o 9.
3. Ve a la ruta `/example-module/form` para ver el formulario personalizado.
4. Llena y envía el formulario para guardar los datos en la tabla `example_users`.
5. Ve a la ruta `/example-module/data` para ver los datos almacenados en la tabla `example_users`.

## Compatibilidad con Drupal 9

Este módulo es compatible con Drupal 9 sin necesidad de realizar cambios adicionales.

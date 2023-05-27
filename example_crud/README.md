# Módulo CRUD de ejemplo para Drupal 8 o 9

Este módulo proporciona un CRUD de servicios RESTful que permite manejar usuarios de ejemplo almacenados en una tabla llamada `example_users`.

## Desarrollo

Este módulo fue desarrollado utilizando la API RESTful de Drupal. Se implementó un recurso RESTful personalizado que permite manejar usuarios de ejemplo a través de servicios RESTful.

## Contenido

Este módulo contiene los siguientes archivos:

- `example_crud.info.yml`: Define la información básica del módulo y especifica que depende del módulo `rest`.
- `example_crud.routing.yml`: Define la ruta `/example-crud/data` y especifica que se debe usar el recurso RESTful `example_user`.
- `src/Plugin/rest/resource/ExampleUserResource.php`: Implementa el recurso RESTful `example_user` que permite manejar usuarios de ejemplo a través de servicios RESTful.

## Funciones

Este módulo proporciona las siguientes funciones:

- Obtener usuarios de ejemplo a través de servicios RESTful.
- Crear nuevos usuarios de ejemplo a través de servicios RESTful.
- Actualizar usuarios de ejemplo existentes a través de servicios RESTful.
- Eliminar usuarios de ejemplo existentes a través de servicios RESTful.

## Uso

Para usar este módulo, sigue estos pasos:

1. En la ruta modules/custom poner la carpeta example_crud.
2. Instala el módulo en tu instalación de Drupal 8 o 9.
3. Habilita el recurso RESTful `Example User` en la página de administración de servicios RESTful.
4. Usa servicios RESTful para manejar usuarios de ejemplo almacenados en la tabla `example_users`.

## Compatibilidad con Drupal 9

Este módulo es compatible con Drupal 9 sin necesidad de realizar cambios adicionales.

<?php

namespace Drupal\example_crud\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Psr\Log\LoggerInterface;

/**
 * Proporciona un recurso RESTful para manejar usuarios de ejemplo.
 *
 * @RestResource(
 *   id = "example_user",
 *   label = @Translation("Example User"),
 *   uri_paths = {
 *     "canonical" = "/example-crud/data/{id}",
 *     "https://www.drupal.org/link-relations/create" = "/example-crud/data"
 *   }
 * )
 */
class ExampleUserResource extends ResourceBase {

  /**
   * El usuario actual.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Construye una nueva instancia del recurso ExampleUserResource.
   *
   * @param array $configuration
   *   La configuración del plugin.
   * @param string $plugin_id
   *   El ID del plugin.
   * @param mixed $plugin_definition
   *   La definición del plugin.
   * @param array $serializer_formats
   *   Los formatos disponibles para serialización.
   * @param \Psr\Log\LoggerInterface[] $loggers
   *   Los loggers disponibles.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   El usuario actual.
   */
  public function __construct(array$configuration,$plugin_id,$plugin_definition,array$serializer_formats,array$loggers,\Drupal\Core\Session\AccountProxyInterface$current_user) {
parent::__construct($configuration,$plugin_id,$plugin_definition,$serializer_formats,$loggers);
$this -> currentUser =$current_user;
}

/**
* {@inheritdoc}
*/
public static function create(ContainerInterface$container,array$configuration,$plugin_id,$plugin_definition) {
return new static(
$configuration,
$plugin_id,
$plugin_definition,
$container -> getParameter('serializer.formats'),
$container -> get('logger.factory')->getLoggers(),
$container -> get('current_user')
);
}

/**
* Responde a las solicitudes GET para obtener un usuario de ejemplo por su ID.
*
* @param int$id(optional)
* El ID del usuario a obtener. Si no se proporciona ningún ID,
* se devolverán todos los usuarios.
*
* @return \Drupal\rest\ResourceResponse
* La respuesta RESTful con los datos del usuario o usuarios solicitados.
*/
public function get($id=NULL) {
// Obtener los datos de la tabla example_users.
$query = \Drupal::database()->select('example_users', 'eu');
$query->fields('eu', ['name', 'identification', 'birthdate', 'position', 'status']);
if ($id) {
$query->condition('eu.identification', $id);
}
$results = $query->execute()->fetchAll();

// Crear una respuesta RESTful con los datos obtenidos.
$response = new ResourceResponse($results);
$response->addCacheableDependency($results);
return $response;
}

/**
* Responde a las solicitudes POST para crear un nuevo usuario de ejemplo.
*
* @param array$data
* Los datos del nuevo usuario a crear.
*
* @return \Drupal\rest\ResourceResponse
* La respuesta RESTful con el resultado de la operación.
*/
public function post(array$data) {
// Validar los datos recibidos.
if (empty($data['name']) || !ctype_alnum($data['name'])) {
throw new HttpException(400, 'El campo "Nombre" es requerido y solo puede contener caracteres alfanuméricos.');
}
if (empty($data['identification']) || !is_numeric($data['identification'])) {
throw new HttpException(400, 'El campo "Identificación" es requerido y solo puede contener números.');
}
if (empty($data['birthdate'])) {
throw new HttpException(400, 'El campo "Fecha de nacimiento" es requerido.');
}
if (empty($data['position']) || !in_array($data['position'], ['admin', 'webmaster', 'developer'])) {
throw new HttpException(400, 'El campo "Cargo" es requerido y debe ser uno de los siguientes valores: "admin", "webmaster", "developer".');
}

// Insertar los datos en la tabla example_users.
\Drupal::database()->insert('example_users')
->fields([
'name' => $data['name'],
'identification' => $data['identification'],
'birthdate' => $data['birthdate'],
'position' => $data['position'],
'status' => ($data['position'] == 'admin') ? 1 : 0,
])
->execute();

// Crear una respuesta RESTful con el resultado de la operación.
$response = new ResourceResponse(['success' => TRUE]);
$response->addCacheableDependency(['success' => TRUE]);
return $response;
}

/**
* Responde a las solicitudes PATCH para actualizar un usuario de ejemplo existente por su ID.
*
* @param int$id
* El ID del usuario a actualizar.
* @param array$data
* Los nuevos datos del usuario a actualizar.
*
* @return \Drupal\rest\ResourceResponse
* La respuesta RESTful con el resultado de la operación.
*/
public function patch($id,array$data) {
// Validar que se haya proporcionado un ID válido.
if (!$id || !is_numeric($id)) {
throw new HttpException(400, 'Se debe proporcionar un ID válido para actualizar un usuario.');
}

// Validar los datos recibidos.
if (isset($data['name']) && !ctype_alnum($data['name'])) {
throw new HttpException(400, 'El campo "Nombre" solo puede contener caracteres alfanuméricos.');
}
if (isset($data['identification']) && !is_numeric($data['identification'])) {
throw new HttpException(400, 'El campo "Identificación" solo puede contener números.');
}
if (isset($data['position']) && !in_array($data['position'], ['admin', 'webmaster', 'developer'])) {
throw new HttpException(400, 'El campo "Cargo" debe ser uno de los siguientes valores: "admin", "webmaster", "developer".');
}

// Preparar los datos a actualizar en la tabla example_users.
$fields = [];
if (isset($data['name'])) {
$fields['name'] = $data['name'];
}
if (isset($data['identification'])) {
$fields['identification'] = $data['identification'];
}
if (isset($data['birthdate'])) {
$fields['birthdate'] = $data['birthdate'];
}
if (isset($data['position'])) {
$fields['position'] = $data['position'];
$fields['status'] = ($data['position'] == 'admin') ? 1 : 0;
}

// Actualizar los datos en la tabla example_users.
\Drupal::database()->update('example_users')
->fields($fields)
->condition('identification', $id)
->execute();

// Crear una respuesta RESTful con el resultado de la operación.
$response = new ResourceResponse(['success' => TRUE]);
$response->addCacheableDependency(['success' => TRUE]);
return $response;
}

/**
* Responde a las solicitudes DELETE para eliminar un usuario de ejemplo existente por su ID.
*
* @param int$id
* El ID del usuario a eliminar.
*
* @return \Drupal\rest\ResourceResponse
* La respuesta RESTful con el resultado de la operación.
*/
public function delete($id) {
// Validar que se haya proporcionado un ID válido.
if (!$id || !is_numeric($id)) {
throw new HttpException(400, 'Se debe proporcionar un ID válido para eliminar un usuario.');
}

// Eliminar el usuario de la tabla example_users.
\Drupal::database()->delete('example_users')
->condition('identification', $id)
->execute();

// Crear una respuesta RESTful con el resultado de la operación.
$response = new ResourceResponse(['success' => TRUE]);
$response->addCacheableDependency(['success' => TRUE]);
return $response;
}

}
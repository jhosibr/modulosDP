<?php

namespace Drupal\example_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Implementa el controlador de ejemplo.
 */
class ExampleController extends ControllerBase {

  /**
   * Muestra los datos almacenados en la tabla example_users.
   */
  public function data() {
    
     // Obtener los datos de la tabla example_users.
     $query = \Drupal::database()->select('example_users', 'eu');
     $query->fields('eu', ['name', 'identification', 'birthdate', 'position', 'status']);
     $results = $query->execute()->fetchAll();

     // Crear una tabla para mostrar los datos.
     $header = [$this->t('Nombre'),$this -> t ('Identificación'),$this -> t ('Fecha de nacimiento'),$this -> t ('Cargo'),$this -> t ('Estado')];
     $rows = [];
     
     foreach ($results as$result) {
       array_push($rows,[
         (string)$result -> name,
         (string)$result -> identification,
         (string)$result -> birthdate,
         (string)$result -> position,
         (string)(($result->status == 1) ? $this->t('Activo') : $this->t('Inactivo'))
       ]);
     }
     
     return [
       '#theme'=>'table',
       '#header'=>$header,
       '#rows'=>$rows
     ];
     
  }

}
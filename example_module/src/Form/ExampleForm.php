<?php

namespace Drupal\example_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Implementa el formulario de ejemplo.
 */
class ExampleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'example_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Agregar una biblioteca de estilos al formulario.
    $form['#attached']['library'][] = 'example_module/form-styles';

    // Agregar un contenedor para los campos del formulario.
    $form['fields'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['form-fields'],
      ],
    ];

    // Agregar los campos del formulario al contenedor.
    $form['fields']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => TRUE,
    ];
    $form['fields']['identification'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Identificación'),
      '#required' => TRUE,
    ];
    $form['fields']['birthdate'] = [
      '#type' => 'date',
      '#title' => $this->t('Fecha de nacimiento'),
      '#description' => $this->t('Formato: AAAA-MM-DD'),
    ];
    $form['fields']['position'] = [
      '#type' => 'select',
      '#title' => $this->t('Cargo'),
      '#options' => [
        'admin' => $this->t('Administrador'),
        'webmaster' => $this->t('Webmaster'),
        'developer' => $this->t('Desarrollador'),
      ],
    ];

    // Agregar el botón de enviar fuera del contenedor de campos.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
      '#ajax' => [
        'callback' => '::submitFormAjax',
        'wrapper' => 'example-form',
        'progress' => [
          'type' => 'throbber',
          'message' => t('Enviando...'),
        ],
      ],
    ];

    // Agregar un contenedor para mostrar los mensajes de estado.
    $form['status_messages'] = [
      '#type' => 'status_messages',
      '#weight' => -10,
    ];

    // Agregar un ID al formulario para poder actualizarlo con AJAX.
    $form['#prefix'] = '<div id="example-form">';
    $form['#suffix'] = '</div>';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!preg_match('/^[a-zA-Z0-9\s]+$/', $form_state->getValue('name'))) {
      $form_state->setErrorByName('name', $this->t('El nombre solo puede contener caracteres alfanuméricos y espacios.'));
    }
    if (!is_numeric($form_state->getValue('identification'))) {
      $form_state->setErrorByName('identification', $this->t('La identificación solo puede contener números.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Insertar los datos en la tabla example_users.
    \Drupal::database()->insert('example_users')
      ->fields([
        'name' => $form_state->getValue('name'),
        'identification' => $form_state->getValue('identification'),
        'birthdate' => $form_state->getValue('birthdate'),
        'position' => $form_state->getValue('position'),
        'status' => ($form_state->getValue('position') == 'admin') ? 1 : 0,
      ])
      ->execute();
    // Mostrar un mensaje en pantalla al finalizar el envío del formulario.
    drupal_set_message($this->t('El formulario ha sido enviado correctamente.'));
  }

  /**
   * Maneja el envío del formulario usando AJAX.
   */
  public function submitFormAjax(array &$form, FormStateInterface $form_state) {
    // Enviar el formulario.
    $this->submitForm($form, $form_state);

    // Crear una respuesta AJAX para actualizar el formulario y mostrar los mensajes de estado.
    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#example-form', render($form)));
    return $response;
  }

}
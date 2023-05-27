<?php

namespace Drupal\example_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => TRUE,
    ];
    $form['identification'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Identificación'),
      '#required' => TRUE,
    ];
    $form['birthdate'] = [
      '#type' => 'date',
      '#title' => $this->t('Fecha de nacimiento'),
      '#description' => $this->t('Formato: AAAA-MM-DD'),
    ];
    $form['position'] = [
      '#type' => 'select',
      '#title' => $this->t('Cargo'),
      '#options' => [
        'admin' => $this->t('Administrador'),
        'webmaster' => $this->t('Webmaster'),
        'developer' => $this->t('Desarrollador'),
      ],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!ctype_alnum($form_state->getValue('name'))) {
      $form_state->setErrorByName('name', $this->t('El nombre solo puede contener caracteres alfanuméricos.'));
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

}

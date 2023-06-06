<?php

namespace Drupal\cvs_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Class form.
 */
class Form extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'Form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['field_fullname'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Nome Completo'),
            '#required' => FALSE,
            '#maxlength' => 60,
        ];

        $form['field_morada'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Morada'),
            '#required' => FALSE,
            '#maxlength' => 100,
        ];

        $form['field_distrito'] = [
            '#type' => 'select',
            '#title' => $this->t('Distrito'),
            '#required' => FALSE,
            '#options' => [
                '01' => $this->t('Aveiro'),
                '02' => $this->t('Beja'),
                '03' => $this->t('Braga'),
                '04' => $this->t('Bragança'),
                '05' => $this->t('Castelo Branco'),
                '06' => $this->t('Coimbra'),
                '07' => $this->t('Évora'),
                '08' => $this->t('Faro'),
                '09' => $this->t('Guarda'),
                '10' => $this->t('Leiria'),
                '11' => $this->t('Lisboa'),
                '12' => $this->t('Portalegre'),
                '13' => $this->t('Porto'),
                '14' => $this->t('Santarém'),
                '15' => $this->t('Setúbal'),
                '16' => $this->t('Viana do Castelo'),
                '17' => $this->t('Vila Real'),
                '18' => $this->t('Viseu'),
            ],
        ];

        $form['field_idade'] = [
            '#type' => 'number',
            '#title' => $this->t('Idade'),
            '#required' => FALSE,
            '#min' => 15,
            '#max' => 99,
        ];

        $form['field_file'] = [
            '#type' => 'managed_file',
            '#title' => $this->t('Anexar CV'),
            '#required' => FALSE,
            '#upload_validators' => [
                'file_validate_extensions' => ['pdf doc docx'],
                'file_validate_size' => [5600000],
            ],
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $formField = $form_state->getValues();

        $fullname = trim($formField['field_fullname']);
        if (!preg_match("/^([a-zA-Z'\w\s]+)$/", $fullname)) {
            $form_state->setErrorByName('field_fullname', $this->t('Enter the valid first name'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        $node = Node::create(array(
            'type' => 'cvs',
            'title' => $form_state->getValue('field_fullname'),
            'field_cv_fullname' => $form_state->getValue('field_fullname'),
            'field_cv_morada' => $form_state->getValue('field_morada'),
            'field_cv_distrito' => $form_state->getValue('field_distrito'),
            'field_cv_idade' => $form_state->getValue('field_idade'),
            'field_cv_file' => $form_state->getValue('field_file'),
        ));
        $node->save();

        $this->messenger()->addStatus($this->t('CVS data has been saved successsfully.'));
    }
}

<?php

namespace Drupal\ai_video_studio\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Contact form for AI Video Studio.
 */
final class ContactForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ai_video_studio_contact_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#attached']['library'][] = 'ai_video_studio/studio';
    $form['#attributes']['class'][] = 'avs-contact';

    $form['hero'] = [
      '#markup' => '<header class="avs-page__header avs-reveal"><div class="avs-home__eyebrow">Contact</div><h1>Let us build your AI video workflow.</h1><p>Ask about integrations, AI providers, Drupal implementation, pricing, or custom video editing pipelines.</p></header>',
    ];

    $form['layout'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['avs-contact__layout']],
    ];

    $form['layout']['fields'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['avs-contact__card']],
    ];

    $form['layout']['fields']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];
    $form['layout']['fields']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['layout']['fields']['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#required' => TRUE,
    ];
    $form['layout']['fields']['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#rows' => 6,
      '#required' => TRUE,
    ];
    $form['layout']['fields']['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send Message'),
        '#button_type' => 'primary',
      ],
    ];

    $form['layout']['details'] = [
      '#markup' => '<aside class="avs-contact__details"><h2>Company details</h2><p><strong>Email</strong><br>hello@aivideostudio.local</p><p><strong>Phone</strong><br>+91 98765 43210</p><p><strong>Office</strong><br>AI Creative Tower, New Delhi, India</p><div class="avs-socials"><a href="#">in</a><a href="#">x</a><a href="#">yt</a></div><div class="avs-map">Google Map Embed Area</div></aside>',
    ];

    $form['faq'] = [
      '#markup' => '<section class="avs-faq avs-reveal"><h2>FAQ</h2><details open><summary>Can this connect to AI video APIs?</summary><p>Yes. The module already normalizes provider-ready job payloads.</p></details><details><summary>Can users upload videos?</summary><p>Yes. Drupal managed files handle the upload flow.</p></details><details><summary>Can it support custom pricing?</summary><p>Yes. Pricing pages and contact workflows can be wired to Drupal content or commerce later.</p></details></section>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('Thank you. Your message has been received.'));
    $form_state->setRebuild(TRUE);
  }

}

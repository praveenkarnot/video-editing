<?php

namespace Drupal\ai_video_studio\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ai_video_studio\Service\AiVideoGenerator;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for uploading, editing, and generating AI videos.
 */
final class VideoStudioForm extends FormBase {

  private ?AiVideoGenerator $aiVideoGenerator = NULL;

  public function __construct(?AiVideoGenerator $aiVideoGenerator = NULL) {
    $this->aiVideoGenerator = $aiVideoGenerator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('ai_video_studio.generator'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ai_video_studio_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#attached']['library'][] = 'ai_video_studio/studio';
    $form['#attributes']['class'][] = 'ai-video-studio';
    $assetBase = base_path() . \Drupal::service('extension.list.module')->getPath('ai_video_studio') . '/images/';

    $form['intro'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['ai-video-studio__header']],
      'logo' => [
        '#markup' => '<div class="avs-brand avs-brand--dark"><img src="' . $assetBase . 'ai-video-logo.svg" alt="AI Video Studio logo"><span>AI Video Studio</span></div>',
      ],
      'eyebrow' => [
        '#markup' => '<div class="ai-video-studio__eyebrow">Creative workspace</div>',
      ],
      'title' => [
        '#markup' => '<h2>Create a new video with AI</h2>',
      ],
      'copy' => [
        '#markup' => '<p>Upload a source video, choose edits, and describe the new version you want. The studio turns your brief into a provider-ready generation job.</p>',
      ],
      'badges' => [
        '#markup' => '<div class="ai-video-studio__badges"><span>Smart captions</span><span>Social formats</span><span>AI prompt</span></div>',
      ],
    ];

    $form['source'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Source video'),
      '#attributes' => ['class' => ['ai-video-studio__panel', 'ai-video-studio__upload-panel']],
      '#description' => $this->t('Drop in the raw footage you want to transform.'),
    ];

    $form['source']['video'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Upload video'),
      '#description' => $this->t('Allowed formats: MP4, MOV, WebM, and AVI. Maximum upload size depends on your Drupal/PHP configuration.'),
      '#upload_location' => 'public://ai-video-studio/source/',
      '#upload_validators' => [
        'FileExtension' => ['extensions' => 'mp4 mov webm avi'],
      ],
      '#required' => FALSE,
    ];

    $form['source']['upload_ui'] = [
      '#markup' => '<div class="avs-upload-enhancer"><div class="avs-upload-icon"></div><strong>Drag and drop your video here</strong><span>or use the upload control above</span><video class="avs-video-preview" controls hidden></video><div class="avs-progress" aria-hidden="true"><i></i></div></div>',
    ];

    $form['status'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['ai-video-studio__panel', 'avs-status-panel']],
      'title' => ['#markup' => '<h3>AI processing status</h3>'],
      'body' => ['#markup' => '<div class="avs-status-list"><span>Upload ready</span><span>Prompt waiting</span><span>Render queue idle</span></div>'],
    ];

    $form['editing'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Edit options'),
      '#attributes' => ['class' => ['ai-video-studio__grid', 'ai-video-studio__panel']],
    ];

    $form['editing']['trim_start'] = [
      '#type' => 'number',
      '#title' => $this->t('Trim start'),
      '#description' => $this->t('Seconds to remove from the beginning.'),
      '#min' => 0,
      '#default_value' => 0,
    ];

    $form['editing']['trim_end'] = [
      '#type' => 'number',
      '#title' => $this->t('Trim end'),
      '#description' => $this->t('Seconds to remove from the end.'),
      '#min' => 0,
      '#default_value' => 0,
    ];

    $form['editing']['aspect_ratio'] = [
      '#type' => 'select',
      '#title' => $this->t('Format'),
      '#options' => [
        'original' => $this->t('Original'),
        '16:9' => $this->t('Landscape 16:9'),
        '9:16' => $this->t('Vertical 9:16'),
        '1:1' => $this->t('Square 1:1'),
      ],
      '#default_value' => 'original',
    ];

    $form['editing']['overlay_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Overlay text'),
      '#maxlength' => 120,
      '#placeholder' => $this->t('Optional headline or caption'),
    ];

    $form['editing']['mute_audio'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Mute original audio'),
    ];

    $form['editing']['captions'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Generate captions'),
      '#default_value' => TRUE,
    ];

    $form['generation'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('AI generation'),
      '#attributes' => ['class' => ['ai-video-studio__panel', 'ai-video-studio__generation-panel']],
    ];

    $form['generation']['prompt'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Describe the new video'),
      '#placeholder' => $this->t('Example: Create a modern product teaser with fast cuts, upbeat music, captions, and a premium look.'),
      '#rows' => 5,
      '#required' => TRUE,
    ];

    $form['generation']['style'] = [
      '#type' => 'select',
      '#title' => $this->t('Visual style'),
      '#options' => [
        'cinematic' => $this->t('Cinematic'),
        'social_ad' => $this->t('Social ad'),
        'documentary' => $this->t('Documentary'),
        'tutorial' => $this->t('Tutorial'),
        'motion_graphics' => $this->t('Motion graphics'),
      ],
      '#default_value' => 'cinematic',
    ];

    $form['generation']['duration'] = [
      '#type' => 'number',
      '#title' => $this->t('New video duration'),
      '#field_suffix' => $this->t('seconds'),
      '#min' => 5,
      '#max' => 120,
      '#default_value' => 15,
    ];

    $form['generation']['resolution'] = [
      '#type' => 'select',
      '#title' => $this->t('Resolution'),
      '#options' => [
        '720p' => $this->t('720p'),
        '1080p' => $this->t('1080p'),
        '4k' => $this->t('4K'),
      ],
      '#default_value' => '1080p',
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create AI video'),
      '#button_type' => 'primary',
    ];

    $form['actions']['generate'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate AI Video'),
      '#submit' => ['::submitForm'],
      '#attributes' => ['class' => ['avs-action-secondary']],
    ];

    $form['actions']['draft'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Draft'),
      '#limit_validation_errors' => [],
      '#submit' => ['::saveDraft'],
      '#attributes' => ['class' => ['avs-action-muted']],
    ];

    $form['actions']['export'] = [
      '#type' => 'submit',
      '#value' => $this->t('Export Video'),
      '#submit' => ['::submitForm'],
      '#attributes' => ['class' => ['avs-action-gold']],
    ];

    if ($job = $form_state->get('ai_video_job')) {
      $form['result'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['ai-video-studio__result']],
        'title' => ['#markup' => '<h3>' . $this->t('Generation request queued') . '</h3>'],
        'job' => [
          '#theme' => 'item_list',
          '#items' => [
            $this->t('Job ID: @id', ['@id' => $job['id']]),
            $this->t('Status: @status', ['@status' => $job['status']]),
            $this->t('Prompt: @prompt', ['@prompt' => $job['prompt']]),
          ],
        ],
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if ((int) $form_state->getValue('trim_start') + (int) $form_state->getValue('trim_end') > 600) {
      $form_state->setErrorByName('trim_start', $this->t('Total trim time should be 600 seconds or less.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $sourceVideo = $this->loadUploadedVideo($form_state);

    if ($sourceVideo) {
      $sourceVideo->setPermanent();
      $sourceVideo->save();
    }

    $job = $this->aiVideoGenerator()->createJob($sourceVideo, [
      'trim_start' => $form_state->getValue('trim_start'),
      'trim_end' => $form_state->getValue('trim_end'),
      'aspect_ratio' => $form_state->getValue('aspect_ratio'),
      'mute_audio' => $form_state->getValue('mute_audio'),
      'captions' => $form_state->getValue('captions'),
      'overlay_text' => $form_state->getValue('overlay_text'),
      'prompt' => $form_state->getValue('prompt'),
      'style' => $form_state->getValue('style'),
      'duration' => $form_state->getValue('duration'),
      'resolution' => $form_state->getValue('resolution'),
    ]);

    $form_state->set('ai_video_job', $job);
    $form_state->setRebuild(TRUE);
    $this->messenger()->addStatus($this->t('Your AI video request has been queued.'));
  }

  /**
   * Saves the current form as a draft placeholder.
   */
  public function saveDraft(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('Draft saved. You can continue editing later.'));
    $form_state->setRebuild(TRUE);
  }

  private function loadUploadedVideo(FormStateInterface $form_state): ?FileInterface {
    $fileIds = $form_state->getValue('video');
    if (empty($fileIds[0])) {
      return NULL;
    }

    $file = File::load($fileIds[0]);
    return $file instanceof FileInterface ? $file : NULL;
  }

  private function aiVideoGenerator(): AiVideoGenerator {
    if (!$this->aiVideoGenerator) {
      $this->aiVideoGenerator = \Drupal::service('ai_video_studio.generator');
    }

    return $this->aiVideoGenerator;
  }

}

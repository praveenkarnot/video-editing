<?php

namespace Drupal\ai_video_studio\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleExtensionList;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Builds the AI Video Studio marketing homepage.
 */
final class HomeController extends ControllerBase {

  public function __construct(
    private readonly ModuleExtensionList $moduleExtensionList,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('extension.list.module'),
    );
  }

  /**
   * Returns the homepage render array.
   */
  public function page(): array {
    $assetBase = base_path() . $this->moduleExtensionList->getPath('ai_video_studio') . '/images/';

    return [
      '#attached' => [
        'library' => ['ai_video_studio/studio'],
      ],
      '#type' => 'container',
      '#attributes' => ['class' => ['avs-home']],
      'hero' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['avs-home__hero']],
        'content' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['avs-home__hero-content']],
          'logo' => [
            '#markup' => '<div class="avs-brand"><img src="' . $assetBase . 'ai-video-logo.svg" alt="AI Video Studio logo"><span>AI Video Studio</span></div>',
          ],
          'eyebrow' => ['#markup' => '<div class="avs-home__eyebrow">Upload. Edit. Generate.</div>'],
          'title' => ['#markup' => '<h1>AI Video Studio</h1>'],
          'copy' => ['#markup' => '<p>Turn raw clips into polished videos with guided edits, smart captions, format presets, and AI-generated creative direction.</p>'],
          'actions' => [
            '#type' => 'container',
            '#attributes' => ['class' => ['avs-home__actions']],
            'start' => [
              '#type' => 'link',
              '#title' => $this->t('Start creating'),
              '#url' => \Drupal\Core\Url::fromRoute('ai_video_studio.studio'),
              '#attributes' => ['class' => ['avs-button', 'avs-button--primary']],
            ],
            'workflow' => [
              '#type' => 'link',
              '#title' => $this->t('View workflow'),
              '#url' => \Drupal\Core\Url::fromUri('internal:/ai-video-studio/home#workflow'),
              '#attributes' => ['class' => ['avs-button', 'avs-button--ghost']],
            ],
          ],
        ],
        'visual' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['avs-home__visual']],
          'image' => [
            '#markup' => '<img class="avs-home__hero-image" src="' . $assetBase . 'ai-video-dashboard.svg" alt="AI video editing dashboard preview">',
          ],
        ],
      ],
      'workflow' => [
        '#type' => 'container',
        '#attributes' => [
          'id' => 'workflow',
          'class' => ['avs-home__workflow'],
        ],
        'title' => ['#markup' => '<h2>From upload to new video in one flow</h2>'],
        'items' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['avs-home__steps']],
          'upload' => $this->stepCard($assetBase . 'upload-flow.svg', 'Upload your clip', 'Add MP4, MOV, WebM, or AVI files and keep the original safely stored in Drupal.'),
          'edit' => $this->stepCard($assetBase . 'edit-flow.svg', 'Choose edits', 'Trim, resize, mute, add captions, and place overlay text for the final format.'),
          'generate' => $this->stepCard($assetBase . 'generate-flow.svg', 'Create with AI', 'Describe the new video style, duration, and resolution, then queue the generation job.'),
        ],
      ],
      'callout' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['avs-home__callout']],
        'text' => ['#markup' => '<h2>Ready to make your next video?</h2><p>The studio is built for social ads, product explainers, tutorials, and cinematic short-form edits.</p>'],
        'link' => [
          '#type' => 'link',
          '#title' => $this->t('Open studio'),
          '#url' => \Drupal\Core\Url::fromRoute('ai_video_studio.studio'),
          '#attributes' => ['class' => ['avs-button', 'avs-button--primary']],
        ],
      ],
    ];
  }

  private function stepCard(string $image, string $title, string $copy): array {
    return [
      '#type' => 'container',
      '#attributes' => ['class' => ['avs-home__step']],
      'image' => [
        '#markup' => '<img class="avs-home__step-image" src="' . $image . '" alt="">',
      ],
      'title' => ['#markup' => '<h3>' . $title . '</h3>'],
      'copy' => ['#markup' => '<p>' . $copy . '</p>'],
    ];
  }

}

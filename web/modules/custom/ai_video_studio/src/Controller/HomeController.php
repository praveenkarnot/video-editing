<?php

namespace Drupal\ai_video_studio\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Builds the AI Video Studio homepage.
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
      '#attached' => ['library' => ['ai_video_studio/studio']],
      '#type' => 'container',
      '#attributes' => ['class' => ['avs-home']],
      'theme_toggle' => [
        '#markup' => '<button class="avs-theme-toggle" type="button" aria-pressed="false">Light mode</button>',
      ],
      'hero' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['avs-home__hero', 'avs-reveal']],
        'content' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['avs-home__hero-content']],
          'logo' => [
            '#markup' => '<div class="avs-brand"><img src="' . $assetBase . 'ai-video-logo.svg" alt="AI Video Studio logo"><span>AI Video Studio</span></div>',
          ],
          'eyebrow' => ['#markup' => '<div class="avs-home__eyebrow">AI video editing for creators, brands, and teams</div>'],
          'title' => ['#markup' => '<h1>Create studio-quality videos with AI.</h1>'],
          'copy' => ['#markup' => '<p>Upload raw footage, edit in a visual workspace, generate subtitles and voiceovers, then export polished short-form videos for every social channel.</p>'],
          'actions' => [
            '#type' => 'container',
            '#attributes' => ['class' => ['avs-home__actions']],
            'upload' => $this->linkButton('Upload Video', 'ai_video_studio.studio', 'avs-button--primary'),
            'create' => $this->linkButton('Create AI Video', 'ai_video_studio.studio', 'avs-button--gold'),
            'edit' => $this->linkButton('Start Editing', 'ai_video_studio.dashboard', 'avs-button--ghost'),
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
      'features' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['avs-section', 'avs-reveal']],
        'title' => ['#markup' => '<h2>Everything your AI editing pipeline needs</h2>'],
        'grid' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['avs-feature-grid']],
          'generation' => $this->featureCard('sparkles', 'AI Video Generation', 'Generate new scenes, short clips, product teasers, and social-ready edits from a creative prompt.'),
          'editing' => $this->featureCard('cut', 'Smart Video Editing', 'Trim, resize, polish, and reframe videos with presets for landscape, square, and vertical formats.'),
          'subtitles' => $this->featureCard('captions', 'Auto Subtitle Generation', 'Create readable subtitles and branded captions for accessible, engaging video content.'),
          'voice' => $this->featureCard('mic', 'AI Voiceover', 'Plan voice tracks and narration in the same workflow as your video generation brief.'),
          'export' => $this->featureCard('share', 'Social Media Export', 'Prepare final clips for reels, shorts, ads, launches, tutorials, and campaigns.'),
        ],
      ],
      'workflow' => [
        '#type' => 'container',
        '#attributes' => [
          'id' => 'workflow',
          'class' => ['avs-home__workflow', 'avs-reveal'],
        ],
        'title' => ['#markup' => '<h2>From upload to new video in one premium workflow</h2>'],
        'items' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['avs-home__steps']],
          'upload' => $this->stepCard($assetBase . 'upload-flow.svg', 'Upload your clip', 'Use a guided upload flow with preview, draft saving, and AI processing status.'),
          'edit' => $this->stepCard($assetBase . 'edit-flow.svg', 'Edit with precision', 'Trim clips, add subtitles, transitions, voiceovers, music, and format presets.'),
          'generate' => $this->stepCard($assetBase . 'generate-flow.svg', 'Generate with AI', 'Create new videos, variants, and short clips from your source footage and prompt.'),
        ],
      ],
      'demos' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['avs-section', 'avs-reveal']],
        'title' => ['#markup' => '<h2>AI-generated demo video ideas</h2>'],
        'cards' => [
          '#markup' => '<div class="avs-demo-grid"><div><span>Launch Reel</span><strong>15s product teaser</strong></div><div><span>Tutorial Cut</span><strong>Voiceover explainer</strong></div><div><span>Ad Variant</span><strong>Vertical social clip</strong></div></div>',
        ],
      ],
      'logos' => [
        '#markup' => '<section class="avs-logo-strip avs-reveal"><span>Trusted workflow for</span><strong>Creators</strong><strong>Agencies</strong><strong>Startups</strong><strong>Educators</strong><strong>Brands</strong></section>',
      ],
      'testimonials' => [
        '#markup' => '<section class="avs-testimonials avs-reveal"><h2>Teams move faster with AI Video Studio</h2><div class="avs-testimonial-grid"><blockquote>We can go from raw clip to campaign-ready video in one focused flow.<cite>Riya Sharma, Growth Lead</cite></blockquote><blockquote>The editor dashboard makes AI generation feel practical, not complicated.<cite>Arjun Mehta, Creative Director</cite></blockquote></div></section>',
      ],
      'newsletter' => [
        '#markup' => '<section class="avs-newsletter avs-reveal"><div><h2>Get AI video workflow updates</h2><p>Product tips, editing templates, and launch notes delivered monthly.</p></div><form class="avs-newsletter__form"><input type="email" placeholder="you@example.com" aria-label="Email address"><button type="submit">Subscribe</button></form></section>',
      ],
      'callout' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['avs-home__callout', 'avs-reveal']],
        'text' => ['#markup' => '<h2>Ready to make your next video?</h2><p>Open the studio and start building a premium AI video workflow inside Drupal.</p>'],
        'link' => $this->linkButton('Open studio', 'ai_video_studio.studio', 'avs-button--primary'),
      ],
      'chat' => [
        '#markup' => '<button class="avs-chat-widget" type="button">Live chat</button>',
      ],
    ];
  }

  private function linkButton(string $label, string $route, string $modifier): array {
    return [
      '#type' => 'link',
      '#title' => $this->t($label),
      '#url' => Url::fromRoute($route),
      '#attributes' => ['class' => ['avs-button', $modifier]],
    ];
  }

  private function featureCard(string $icon, string $title, string $copy): array {
    return [
      '#markup' => '<article class="avs-feature-card"><div class="avs-icon avs-icon--' . $icon . '"></div><h3>' . $title . '</h3><p>' . $copy . '</p></article>',
    ];
  }

  private function stepCard(string $image, string $title, string $copy): array {
    return [
      '#type' => 'container',
      '#attributes' => ['class' => ['avs-home__step']],
      'image' => ['#markup' => '<img class="avs-home__step-image" src="' . $image . '" alt="">'],
      'title' => ['#markup' => '<h3>' . $title . '</h3>'],
      'copy' => ['#markup' => '<p>' . $copy . '</p>'],
    ];
  }

}

<?php

namespace Drupal\ai_video_studio\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;

/**
 * Builds supporting AI Video Studio pages.
 */
final class SitePagesController extends ControllerBase {

  /**
   * Builds the editor dashboard.
   */
  public function dashboard(): array {
    return $this->page('Editor workspace', 'AI Video Editor Dashboard', 'Trim videos, add transitions, subtitles, AI voiceovers, music, and automatically generate short clips.', '<section class="avs-dashboard">
      <div class="avs-editor-preview">
        <div class="avs-editor-screen"><div class="avs-play-pulse"></div></div>
        <div class="avs-editor-timeline"><span></span><span></span><span></span><span></span></div>
      </div>
      <div class="avs-tool-panel">
        <h2>Editing tools</h2>
        <div class="avs-tool-grid">
          <button type="button">Trim Video</button>
          <button type="button">Add Transitions</button>
          <button type="button">Add Subtitles</button>
          <button type="button">AI Voiceover</button>
          <button type="button">Background Music</button>
          <button type="button">Generate Short Clips</button>
        </div>
        <div class="avs-processing"><strong>AI processing</strong><span>Ready for upload</span><div><i style="width: 42%"></i></div></div>
      </div>
    </section>');
  }

  /**
   * Builds the about page.
   */
  public function about(): array {
    return $this->page('About', 'Built for modern video teams', 'AI Video Studio brings upload, editing, generation, and publishing workflows into one Drupal-powered product experience.', '<div class="avs-copy-grid"><section><h2>Our mission</h2><p>We help creators and businesses turn raw media into finished videos faster with AI-assisted editing, automation, and structured content workflows.</p></section><section><h2>Why Drupal</h2><p>Drupal gives the platform strong content modeling, user permissions, REST readiness, and room for future AI provider integrations.</p></section></div>');
  }

  /**
   * Builds the pricing page.
   */
  public function pricing(): array {
    return $this->page('Pricing', 'Plans for every video workflow', 'Start simple, then scale into advanced AI generation, team collaboration, and social export automation.', '<div class="avs-pricing"><article><span>Starter</span><h2>$19</h2><p>Upload, edit, captions, and draft exports.</p><a href="/ai-video-studio">Start</a></article><article class="is-featured"><span>Pro</span><h2>$49</h2><p>AI generation, voiceovers, templates, and short clip automation.</p><a href="/ai-video-studio">Choose Pro</a></article><article><span>Studio</span><h2>Custom</h2><p>Team workflows, API support, and premium provider integrations.</p><a href="/contact-us">Contact</a></article></div>');
  }

  /**
   * Builds the features page.
   */
  public function features(): array {
    return $this->page('Features', 'Premium tools for AI video creation', 'A complete editing experience designed for fast campaigns, creator workflows, and reusable video systems.', '<div class="avs-feature-grid"><article class="avs-feature-card"><h3>REST API Ready</h3><p>Provider requests are normalized so AI APIs and queue workers can be added cleanly.</p></article><article class="avs-feature-card"><h3>Responsive Editor</h3><p>Mobile, tablet, and desktop layouts keep upload and edit flows comfortable.</p></article><article class="avs-feature-card"><h3>SEO Friendly</h3><p>Landing pages use semantic structure, clear CTAs, and fast SVG visuals.</p></article><article class="avs-feature-card"><h3>Dark/Light Mode</h3><p>A lightweight front-end toggle makes the interface feel polished and flexible.</p></article></div>');
  }

  /**
   * Builds the privacy page.
   */
  public function privacy(): array {
    return $this->page('Legal', 'Privacy Policy', 'A clear starter privacy policy for the AI video editing website.', '<div class="avs-policy"><h2>Data we collect</h2><p>Uploaded videos, form submissions, account details, and AI generation prompts may be processed to provide the service.</p><h2>How data is used</h2><p>Data is used to store drafts, queue editing jobs, generate videos, provide support, and improve platform reliability.</p><h2>Third-party AI providers</h2><p>Future AI integrations may send selected prompts, media, or metadata to configured providers based on site settings.</p></div>');
  }

  /**
   * Builds the terms page.
   */
  public function terms(): array {
    return $this->page('Legal', 'Terms & Conditions', 'Starter terms for using the AI video editing platform.', '<div class="avs-policy"><h2>Use of service</h2><p>Users are responsible for owning or having permission to edit, upload, generate, and export video content.</p><h2>AI output</h2><p>Generated media should be reviewed before publication for accuracy, brand fit, and legal compliance.</p><h2>Availability</h2><p>AI processing depends on configured providers, queues, infrastructure, and site administrator settings.</p></div>');
  }

  private function page(string $eyebrow, string $title, string $intro, string $content): array {
    return [
      '#attached' => ['library' => ['ai_video_studio/studio']],
      '#theme' => 'ai_video_studio_page',
      '#eyebrow' => $eyebrow,
      '#title' => $title,
      '#intro' => $intro,
      '#content' => [
        '#markup' => Markup::create($content),
      ],
    ];
  }

}

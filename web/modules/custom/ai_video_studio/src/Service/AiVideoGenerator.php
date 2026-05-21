<?php

namespace Drupal\ai_video_studio\Service;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\file\FileInterface;

/**
 * Creates normalized AI video generation jobs.
 *
 * This service intentionally keeps provider-specific code out of the form so
 * the site can later call a real video generation API or queue worker here.
 */
final class AiVideoGenerator {

  public function __construct(
    private readonly FileUrlGeneratorInterface $fileUrlGenerator,
    private readonly TimeInterface $time,
  ) {}

  /**
   * Builds a provider-ready job payload.
   *
   * @param \Drupal\file\FileInterface|null $sourceVideo
   *   Optional source video supplied by the user.
   * @param array<string, mixed> $options
   *   Edit and generation options from the studio form.
   *
   * @return array<string, mixed>
   *   A normalized job payload suitable for persistence or queueing.
   */
  public function createJob(?FileInterface $sourceVideo, array $options): array {
    $sourceUrl = $sourceVideo
      ? $this->fileUrlGenerator->generateAbsoluteString($sourceVideo->getFileUri())
      : NULL;

    return [
      'id' => 'avs_' . $this->time->getRequestTime() . '_' . substr(hash('sha256', serialize($options)), 0, 8),
      'status' => 'queued',
      'source_video_url' => $sourceUrl,
      'prompt' => trim((string) ($options['prompt'] ?? '')),
      'edit_options' => [
        'trim_start' => (int) ($options['trim_start'] ?? 0),
        'trim_end' => (int) ($options['trim_end'] ?? 0),
        'aspect_ratio' => (string) ($options['aspect_ratio'] ?? 'original'),
        'mute_audio' => (bool) ($options['mute_audio'] ?? FALSE),
        'captions' => (bool) ($options['captions'] ?? FALSE),
        'overlay_text' => trim((string) ($options['overlay_text'] ?? '')),
      ],
      'generation_options' => [
        'style' => (string) ($options['style'] ?? 'cinematic'),
        'duration' => (int) ($options['duration'] ?? 15),
        'resolution' => (string) ($options['resolution'] ?? '1080p'),
      ],
      'created' => $this->time->getRequestTime(),
      'provider_note' => 'Replace this placeholder with a queue item or provider API request.',
    ];
  }

}

# AI Video Studio for Drupal

This repository contains a Drupal project scaffold and a custom module that adds an AI video creation page at `/ai-video-studio`.

## Features

- A polished front page with animated workflow visuals.
- Upload a source video through Drupal's managed file system.
- Choose basic edit options: trim, format, overlay text, mute audio, and captions.
- Describe a new AI-generated video with style, duration, and resolution controls.
- AI editor dashboard mock workflow for trimming, subtitles, voiceovers, music, transitions, and short clip generation.
- Designed Contact, About, Pricing, Features, Privacy Policy, and Terms & Conditions pages.
- Dark/light mode toggle, testimonials, client logos, demo video ideas, newsletter section, and live chat entry point.
- Queue a normalized AI video job payload through `AiVideoGenerator`, ready to connect to a real provider or queue worker.

## Install

1. Install Drupal dependencies:

   ```bash
   composer install
   ```

2. Install Drupal through the browser or Drush using your preferred database settings.
3. Enable the custom module:

   ```bash
   drush en ai_video_studio -y
   drush cr
   ```

4. Give users the `Use AI Video Studio` permission.
5. Visit `/ai-video-studio`.

When the module is installed, it sets Drupal's front page to `/ai-video-studio/home`.

## Pages

- `/ai-video-studio/home`
- `/ai-video-studio`
- `/ai-video-studio/dashboard`
- `/features`
- `/pricing`
- `/about-us`
- `/contact-us`
- `/privacy-policy`
- `/terms-conditions`

## Next Integration Step

Replace the placeholder job handling in `Drupal\ai_video_studio\Service\AiVideoGenerator` with a queue item, FFmpeg pipeline, or provider API call. The form already passes the source video URL, edit options, generation prompt, style, duration, and resolution in one normalized payload.

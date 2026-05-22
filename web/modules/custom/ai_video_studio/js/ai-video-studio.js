(function (Drupal, once) {
  Drupal.behaviors.aiVideoStudio = {
    attach(context) {
      once('ai-video-prompt-count', '.ai-video-studio textarea[name="prompt"]', context).forEach((prompt) => {
        const counter = document.createElement('div');
        counter.className = 'description';
        prompt.insertAdjacentElement('afterend', counter);

        const updateCounter = () => {
          counter.textContent = `${prompt.value.trim().length} characters`;
        };

        prompt.addEventListener('input', updateCounter);
        updateCounter();
      });

      once('ai-video-upload-preview', '.ai-video-studio', context).forEach((studio) => {
        const fileInput = studio.querySelector('input[type="file"]');
        const preview = studio.querySelector('.avs-video-preview');
        const progress = studio.querySelector('.avs-progress i');
        const dropArea = studio.querySelector('.avs-upload-enhancer');

        if (!fileInput || !preview || !progress || !dropArea) {
          return;
        }

        const showPreview = (file) => {
          if (!file || !file.type.startsWith('video/')) {
            return;
          }
          preview.src = URL.createObjectURL(file);
          preview.hidden = false;
          dropArea.classList.add('has-preview');
          progress.style.width = '100%';
        };

        fileInput.addEventListener('change', () => {
          progress.style.width = '36%';
          window.setTimeout(() => {
            showPreview(fileInput.files[0]);
          }, 260);
        });

        ['dragenter', 'dragover'].forEach((eventName) => {
          dropArea.addEventListener(eventName, (event) => {
            event.preventDefault();
            dropArea.classList.add('is-dragging');
          });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
          dropArea.addEventListener(eventName, (event) => {
            event.preventDefault();
            dropArea.classList.remove('is-dragging');
          });
        });

        dropArea.addEventListener('drop', (event) => {
          showPreview(event.dataTransfer.files[0]);
        });
      });

      once('ai-video-reveal', '.avs-reveal', context).forEach((element) => {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              observer.unobserve(entry.target);
            }
          });
        }, { threshold: 0.18 });

        observer.observe(element);
      });

      once('ai-video-theme', '.avs-theme-toggle', context).forEach((button) => {
        button.addEventListener('click', () => {
          const enabled = document.documentElement.classList.toggle('avs-dark');
          button.textContent = enabled ? 'Dark mode' : 'Light mode';
          button.setAttribute('aria-pressed', enabled ? 'true' : 'false');
        });
      });
    },
  };
})(Drupal, once);

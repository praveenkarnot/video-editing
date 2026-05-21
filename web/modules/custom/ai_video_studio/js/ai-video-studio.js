(function (Drupal, once) {
  Drupal.behaviors.aiVideoStudio = {
    attach(context) {
      once('ai-video-studio', '.ai-video-studio textarea[name="prompt"]', context).forEach((prompt) => {
        const counter = document.createElement('div');
        counter.className = 'description';
        prompt.insertAdjacentElement('afterend', counter);

        const updateCounter = () => {
          counter.textContent = `${prompt.value.trim().length} characters`;
        };

        prompt.addEventListener('input', updateCounter);
        updateCounter();
      });
    },
  };
})(Drupal, once);

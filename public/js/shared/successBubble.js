document.addEventListener('DOMContentLoaded', () => {
    const bubble = document.querySelector('[data-success-bubble]');
    if (!bubble) return;

    const hide = () => bubble.classList.add('success-bubble--hidden');

    const timer = window.setTimeout(hide, 4000);

    bubble.addEventListener('mouseenter', () => {
        window.clearTimeout(timer);
        hide();
    }, { once: true });
});

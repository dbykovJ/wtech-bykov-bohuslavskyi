function closeElement(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;
    element.style.display = 'none';
}
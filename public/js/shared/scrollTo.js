function scrollToId(id, smooth = true) {
    const el = document.getElementById(id);
    if (!el) return;
    const offset = 100;
    const top = el.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: smooth ? 'smooth' : 'instant' });
}

document.addEventListener("DOMContentLoaded", function () {
    const scrollBtns = document.querySelectorAll("[data-scroll]");
    scrollBtns.forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            scrollToId(this.getAttribute("data-scroll"));
        });
    });

    if (window.location.hash) {
        const id = window.location.hash.slice(1);
        setTimeout(() => scrollToId(id, false), 100);
    }
});
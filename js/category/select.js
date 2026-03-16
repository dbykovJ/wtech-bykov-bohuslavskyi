document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.select').forEach(function (select) {
        const selected = select.querySelector('.select__selected');
        const dropdown = select.querySelector('.select__dropdown');

        selected.addEventListener('click', function (e) {
            e.stopPropagation();
            select.classList.toggle('open');
        });

        dropdown.querySelectorAll('li').forEach(function (item) {
            item.addEventListener('click', function () {
                dropdown.querySelector('li.active')?.classList.remove('active');
                this.classList.add('active');
                selected.querySelector('span').textContent = this.textContent;
                select.classList.remove('open');
            });
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.select.open').forEach(function (s) {
            s.classList.remove('open');
        });
    });
});

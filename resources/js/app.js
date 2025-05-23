import './bootstrap';

window.addEventListener('scroll', function () {
    const header = document.getElementById('mainHeader');
    if (window.scrollY > 10) {
        header.classList.add('sticky');
    } else {
        header.classList.remove('sticky');
    }
});

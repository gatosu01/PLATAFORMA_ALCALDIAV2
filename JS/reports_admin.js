document.addEventListener('DOMContentLoaded', function() {
    const filtroBtns = document.querySelectorAll('.filtro-btn');
    const secciones = document.querySelectorAll('.seccion-filtro');

    filtroBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filtroBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            secciones.forEach(sec => sec.style.display = 'none');
            document.getElementById(this.dataset.section).style.display = 'block';
        });
    });

});


document.addEventListener('DOMContentLoaded', function() {
    const tipoBtns = document.querySelectorAll('.tipo-btn');
    const filtroBtns = document.querySelectorAll('.filtro-btn');
    const secciones = document.querySelectorAll('.seccion-filtro');

    // Leer estado guardado en localStorage o usar valores por defecto
    let tipoActivo = localStorage.getItem('tipoActivo') || 'denuncias';
    let estadoActivo = localStorage.getItem('estadoActivo') || 'esperando';

    function mostrarSeccion() {
        secciones.forEach(sec => sec.style.display = 'none');
        if (tipoActivo === 'denuncias') {
            const el = document.getElementById(estadoActivo);
            if(el) el.style.display = 'block';
        } else {
            const el = document.getElementById(estadoActivo + '-queja');
            if(el) el.style.display = 'block';
        }

        // Actualizar clases activas de botones
    tipoBtns.forEach(b => b.classList.remove('active'));
    filtroBtns.forEach(b => b.classList.remove('active'));

    tipoBtns.forEach(b => {
            if(b.getAttribute('data-tipo') === tipoActivo) b.classList.add('active');
        });
        filtroBtns.forEach(b => {
            if(b.getAttribute('data-section') === estadoActivo) b.classList.add('active');
        });
    }
    tipoBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tipoActivo = this.getAttribute('data-tipo');
            localStorage.setItem('tipoActivo', tipoActivo); // Guardar estado
            location.reload(); // Recargar para refrescar contenido
        });
    });

    filtroBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            estadoActivo = this.getAttribute('data-section');
            localStorage.setItem('estadoActivo', estadoActivo); // Guardar estado
            location.reload(); // Recargar para refrescar contenido
        });
    });
    mostrarSeccion();
});

// 🔍 Búsqueda en tiempo real
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search-bar");
    if (!searchInput) return; // por si no existe el input
    const noResults = document.getElementById("no-results");

    searchInput.addEventListener("input", () => {
    const filter = searchInput.value.toLowerCase();
    const allItems = document.querySelectorAll(".denuncia, .queja");
    let visibleCount = 0;
        allItems.forEach(item => {
            const texto = item.textContent.toLowerCase();
            if (texto.includes(filter)) {
                item.style.display = "";
                visibleCount++;
            } else {
                item.style.display = "none";
            }
        });
        noResults.style.display = (visibleCount === 0) ? "block" : "none";
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const tipoBtns = document.querySelectorAll('.tipo-btn');
    const filtroBtns = document.querySelectorAll('.filtro-btn');
    const secciones = document.querySelectorAll('.seccion-filtro');

    tipoBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tipoBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const tipo = this.getAttribute('data-tipo');
            const filtroActivo = document.querySelector('.filtro-btn.active').getAttribute('data-section');
            secciones.forEach(sec => {
                if (sec.getAttribute('data-tipo') === tipo && sec.getAttribute('id') === (tipo === 'quejas' ? filtroActivo + '-queja' : filtroActivo)) {
                    sec.style.display = 'block';
                } else {
                    sec.style.display = 'none';
                }
            });
        });
    });

    filtroBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filtroBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const estado = this.getAttribute('data-section');
            const tipoActivo = document.querySelector('.tipo-btn.active').getAttribute('data-tipo');
            secciones.forEach(sec => {
                if (sec.getAttribute('data-tipo') === tipoActivo && sec.getAttribute('id') === (tipoActivo === 'quejas' ? estado + '-queja' : estado)) {
                    sec.style.display = 'block';
                } else {
                    sec.style.display = 'none';
                }
            });
        });
    });
});

// 🔍 Búsqueda en tiempo real
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search-bar");
    if (!searchInput) return; // por si no existe el input

    const noResults = document.getElementById("no-results");

    searchInput.addEventListener("input", () => {
        const filter = searchInput.value.toLowerCase();
        const items = document.querySelectorAll(".complaint-card, .queja-card");
        let visibleCount = 0;
        items.forEach(item => {
            const title = item.querySelector(".complaint-title, .queja-title").textContent.toLowerCase();
            if (title.includes(filter)) {
                item.style.display = "block";
                visibleCount++;
            } else {
                item.style.display = "none";
            }
        });
        if (noResults) noResults.style.display = visibleCount === 0 ? "block" : "none";
    });
});

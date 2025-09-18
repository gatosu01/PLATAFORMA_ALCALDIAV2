function eliminarDenuncia(btn) {
    let denunciaDiv = btn.closest('.denuncia, .queja');
    if (denunciaDiv) {
        denunciaDiv.style.display = 'none';
        // Guarda el id en localStorage
        let id = denunciaDiv.getAttribute('data-id');
        if (id) {
            let ocultas = JSON.parse(localStorage.getItem('denuncias_ocultas') || '[]');
            if (!ocultas.includes(id)) {
                ocultas.push(id);
                localStorage.setItem('denuncias_ocultas', JSON.stringify(ocultas));
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Oculta las denuncias guardadas en localStorage
    let ocultas = JSON.parse(localStorage.getItem('denuncias_ocultas') || '[]');
    ocultas.forEach(function(id) {
        let div = document.querySelector('.denuncia[data-id="' + id + '"], .queja[data-id="' + id + '"]');
        if (div) div.style.display = 'none';
    });

    document.querySelectorAll('.btn-eliminar').forEach(function(btn) {
        btn.addEventListener('click', function() {
            eliminarDenuncia(this);
        });
    });
});
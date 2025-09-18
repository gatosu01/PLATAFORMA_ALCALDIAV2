// AJAX para cargar subdepartamentos dinámicamente
document.getElementById("departamento").addEventListener("change", function() {
    let deptoId = this.value;
    let subSelect = document.getElementById("subdepartamento");
    subSelect.innerHTML = "<option value=''>Cargando...</option>";

    if (deptoId) {
        fetch("../PHP/get_subdepartament.php?departamento_id=" + deptoId)
            .then(res => res.json())
            .then(data => {
                subSelect.innerHTML = "<option value=''>Seleccione un subdepartamento</option>";
                data.forEach(sub => {
                    subSelect.innerHTML += `<option value="${sub.id}">${sub.nombre}</option>`;
                });
            });
    } else {
        subSelect.innerHTML = "<option value=''>Seleccione un subdepartamento</option>";
    }
});

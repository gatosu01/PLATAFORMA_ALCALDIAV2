document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search-bar");
    const noResults = document.getElementById("no-results");

    fetch("../PHP/load_procedures.php")
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById("procedures-container");
            const categorias = {};

            // Agrupar trámites por categoría
            data.forEach(proc => {
                if (!categorias[proc.categoria]) {
                    categorias[proc.categoria] = [];
                }
                categorias[proc.categoria].push(proc);
            });

            // Crear secciones por categoría
            for (let categoria in categorias) {
                const section = document.createElement("div");
                section.classList.add("category-section");

                const title = document.createElement("h2");
                title.textContent = categoria;
                section.appendChild(title);

                categorias[categoria].forEach(proc => {
                    const item = document.createElement("div");
                    item.classList.add("procedure-item");

                    const question = document.createElement("button");
                    question.classList.add("procedure-title");
                    question.textContent = proc.nombre;

                    const answer = document.createElement("div");
                    answer.classList.add("procedure-details");
                    answer.innerHTML = `<p>${proc.requisitos.replace(/\n/g, '<br>')}</p>`;

                    question.addEventListener("click", () => {
                        answer.classList.toggle("active");
                    });

                    item.appendChild(question);
                    item.appendChild(answer);
                    section.appendChild(item);
                });

                container.appendChild(section);
            }

            // Función de búsqueda
            searchInput.addEventListener("input", () => {
                const filter = searchInput.value.toLowerCase();
                const allItems = document.querySelectorAll(".procedure-item");
                let visibleCount = 0;

                allItems.forEach(item => {
                    const title = item.querySelector(".procedure-title").textContent.toLowerCase();
                    if (title.includes(filter)) {
                        item.style.display = "";
                        visibleCount++;
                    } else {
                        item.style.display = "none";
                    }
                });

                // Mostrar/ocultar categorías según si tienen trámites visibles
                const categories = document.querySelectorAll(".category-section");
                categories.forEach(category => {
                    const items = category.querySelectorAll(".procedure-item");
                    const hasVisible = Array.from(items).some(item => item.style.display !== "none");
                    category.style.display = hasVisible ? "" : "none";
                });




                // Mostrar mensaje si no hay resultados
                noResults.style.display = (visibleCount === 0) ? "block" : "none";
            });
        })
        .catch(error => console.error("Error cargando tramites:", error));


});


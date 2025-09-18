document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("photo-container");
  const addBtn = document.getElementById("add-photo");
  let count = 1;

  addBtn.addEventListener("click", () => {
    if (count < 3) {
      const input = document.createElement("input");
      input.type = "file";
      input.name = "photos[]";
      input.accept = "image/*";
      container.appendChild(input);
      count++;
    } else {
      alert("Solo puedes subir hasta 3 imágenes.");
    }
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const radios = document.querySelectorAll('input[name="radio-btn"]');
  let current = 0;
  if (radios.length === 0) return;
  setInterval(() => {
    radios[current].checked = false;
    current = (current + 1) % radios.length;
    radios[current].checked = true;
  }, 4000); // Cambia cada 4 segundos
});

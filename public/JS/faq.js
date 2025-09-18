document.addEventListener('DOMContentLoaded', function() {
  // Oculta todas las respuestas al inicio
  document.querySelectorAll('.faq-answer').forEach(a => {
    a.style.display = 'none';
  });

  // Muestra/oculta la respuesta al hacer clic en la pregunta
  document.querySelectorAll('.faq-question').forEach(q => {
    q.addEventListener('click', function() {
      const answer = this.nextElementSibling;
      answer.style.display = (answer.style.display === 'none' || answer.style.display === '') ? 'block' : 'none';
    });
  });
});

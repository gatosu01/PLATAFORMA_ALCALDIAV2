document.querySelectorAll('.scroll-button').forEach(btn => {
  btn.addEventListener('click', function() {
    const sectionId = btn.getAttribute('data-section-id');
    const section = document.getElementById(sectionId);
    if (section) {
      section.scrollIntoView({ behavior: 'smooth' });
    }
  });
});

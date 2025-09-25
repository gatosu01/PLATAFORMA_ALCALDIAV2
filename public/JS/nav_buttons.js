document.querySelectorAll('.nav-button').forEach(btn => {
  btn.addEventListener('click', function() {
    window.location.href = btn.getAttribute('data-href');
  });
});

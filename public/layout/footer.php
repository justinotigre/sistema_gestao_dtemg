</main>
<footer class="app-footer">
  <span>Sistema de Gestão de Especialistas</span>
  <span>V3 · <?= date('Y') ?></span>
</footer>
</div>
</div>
<script>
(function () {
  const root = document.documentElement;
  const btn = document.getElementById('btnTema');
  const storageKey = 'se_modo';

  function aplicar(modo) {
    const escuro = modo === 'escuro';
    root.classList.toggle('modo-escuro', escuro);
    if (btn) {
      btn.textContent = escuro ? '☀ Claro' : '☾ Escuro';
      btn.setAttribute('aria-label', escuro ? 'Ativar modo claro' : 'Ativar modo escuro');
    }
  }

  aplicar(localStorage.getItem(storageKey) === 'escuro' ? 'escuro' : 'claro');

  if (btn) {
    btn.addEventListener('click', function () {
      const novo = root.classList.contains('modo-escuro') ? 'claro' : 'escuro';
      localStorage.setItem(storageKey, novo);
      aplicar(novo);
    });
  }
})();
</script>
</body>
</html>

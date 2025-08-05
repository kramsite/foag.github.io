// Exemplo de interação simples com JavaScript
document.addEventListener('DOMContentLoaded', function () {
    console.log("Página carregada com sucesso!");

    
    const logoutModal = document.getElementById('logout-modal');
    const confirmLogout = document.getElementById('confirm-logout');
    const cancelLogout = document.getElementById('cancel-logout');

    document.getElementById('icon-sair').addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });

    confirmLogout.addEventListener('click', () => {
      window.location.href = '../index/index.php';
    });

    cancelLogout.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });

    logoutModal.addEventListener('click', e => {
      if (e.target === logoutModal) {
        logoutModal.style.display = 'none';
      }
    });
});

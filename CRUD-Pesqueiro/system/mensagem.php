<?php
// Adiciona a verificação que estava em falta
if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])): 
?>

  <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
    <?= $_SESSION['mensagem']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>

<?php
  // Limpa a mensagem da sessão para que não apareça novamente
  unset($_SESSION['mensagem']);
endif;
?>
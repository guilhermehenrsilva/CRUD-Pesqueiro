  <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
    <?= $_SESSION['mensagem']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>

<?php
  unset($_SESSION['mensagem']);
endif;
?>

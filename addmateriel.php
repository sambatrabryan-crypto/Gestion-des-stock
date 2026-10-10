<?php
// Démarrer la mise en tampon de sortie
ob_start();

include_once("header.php");
include_once("main.php");

// Variables pour les toasts
$toastMessage = "";
$toastType = "";

// Requête pour récupérer les fournisseurs
$queryFournisseurs = "SELECT Id, Nom FROM fournisseur";
$stmtFournisseurs = $pdo->prepare($queryFournisseurs);
$stmtFournisseurs->execute();
$fournisseurs = $stmtFournisseurs->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer les bailleurs
$queryBailleurs = "SELECT Id, Nom FROM bailleur";
$stmtBailleurs = $pdo->prepare($queryBailleurs);
$stmtBailleurs->execute();
$bailleurs = $stmtBailleurs->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer les PCOPs
$queryPcop = "SELECT Id, Compte FROM pcop";
$stmtPcop = $pdo->prepare($queryPcop);
$stmtPcop->execute();
$pcops = $stmtPcop->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si le formulaire a été soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si tous les champs obligatoires sont remplis
    if (
        !empty($_POST["inputdesignation"]) &&
        !empty($_POST["inputfournisseur"]) &&
        !empty($_POST["inputbailleur"]) &&
        !empty($_POST["inputpcop"]) &&
        !empty($_POST["inputdate"]) &&
        !empty($_POST["inputquantite"])
    ) {
        try {
            // Préparer la requête d'insertion
            $query = "INSERT INTO materiel (Designation, Fournisseur, Bailleur, Pcop, Date_entree, Quantite) 
                      VALUES (:Designation, :Fournisseur, :Bailleur, :Pcop, :Date_entree, :Quantite)";
            $pdostmt = $pdo->prepare($query);

            // Exécuter la requête avec les données du formulaire
            $pdostmt->execute([
                "Designation"  => trim($_POST["inputdesignation"]),
                "Fournisseur"  => $_POST["inputfournisseur"],
                "Bailleur"     => $_POST["inputbailleur"],
                "Pcop"         => $_POST["inputpcop"],
                "Date_entree"  => $_POST["inputdate"],
                "Quantite"     => (int)$_POST["inputquantite"],
            ]);

            // Fermer le curseur
            $pdostmt->closeCursor();

            // Rediriger vers la page précédente ou une autre page après le succès
            header("Location: stock.php?success=1");
            exit();
        } catch (PDOException $e) {
            $toastMessage = "Erreur lors de l'ajout : " . $e->getMessage();
            $toastType = "danger";
        }
    } else {
        $toastMessage = "Veuillez remplir tous les champs.";
        $toastType = "warning";
    }
}
?>

<!-- ====== STYLES DOCTOPLAN (référence) ====== -->
<style>
  .doctoplan-page {
    background: #fafcff;
    padding: 32px;
    min-height: 100vh;
  }

  .doctoplan-page .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
  }

  .doctoplan-page .section-header h2 {
    font-size: 22px;
    font-weight: 700;
    color: #0b1e33;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .doctoplan-page .section-header h2 i {
    color: #2563eb;
  }

  .form-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    max-width: 900px;
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  .form-group {
    display: flex;
    flex-direction: column;
  }

  .form-group.full {
    grid-column: 1 / -1;
  }

  .form-card label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
  }

  .form-card label .required {
    color: #ef4444;
    margin-left: 3px;
  }

  .form-card input[type="text"],
  .form-card input[type="number"],
  .form-card input[type="date"],
  .form-card select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    font-size: 14px;
    outline: none;
    transition: 0.15s;
    background: #fafcff;
    font-family: inherit;
    color: #0b1e33;
    appearance: none;
    -webkit-appearance: none;
  }

  .form-card select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 40px;
  }

  .form-card input:focus,
  .form-card select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    background: #ffffff;
  }

  .form-card input::placeholder {
    color: #94a3b8;
  }

  .form-card .input-icon {
    position: relative;
  }

  .form-card .input-icon i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
    pointer-events: none;
  }

  .form-card .input-icon input,
  .form-card .input-icon select {
    padding-left: 42px;
  }

  .form-card .form-hint {
    font-size: 13px;
    color: #64748b;
    margin-top: 6px;
  }

  .form-actions {
    display: flex;
    gap: 12px;
    margin-top: 28px;
    flex-wrap: wrap;
    grid-column: 1 / -1;
  }

  .btn-docto {
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: 0.15s;
    text-decoration: none;
    font-family: inherit;
  }

  .btn-docto.primary {
    background: #2563eb;
    color: white;
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
  }
  .btn-docto.primary:hover {
    background: #1d4ed8;
    transform: scale(0.98);
    color: white;
  }

  .btn-docto.secondary {
    background: #f1f5f9;
    color: #334155;
  }
  .btn-docto.secondary:hover {
    background: #e2e8f0;
    color: #1e293b;
  }

  /* Section séparateur */
  .section-title {
    grid-column: 1 / -1;
    font-size: 15px;
    font-weight: 700;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-bottom: 8px;
    border-bottom: 2px solid #eef4ff;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* Toast */
  .toast-container {
    position: fixed;
    bottom: 30px;
    right: 30px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 1000;
  }

  .toast-docto {
    background: #1e293b;
    color: white;
    padding: 14px 24px;
    border-radius: 40px;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideUp 0.3s ease;
    min-width: 220px;
    max-width: 400px;
  }

  .toast-docto.success i { color: #4ade80; }
  .toast-docto.info i    { color: #60a5fa; }
  .toast-docto.warning i { color: #fbbf24; }
  .toast-docto.danger i  { color: #f87171; }

  @keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 700px) {
    .doctoplan-page { padding: 20px; }
    .form-card { padding: 20px; }
    .form-grid { grid-template-columns: 1fr; }
    .form-actions { flex-direction: column; }
    .btn-docto { justify-content: center; width: 100%; }
  }
</style>

<!-- ====== CONTENU PAGE ====== -->
<div class="doctoplan-page">

  <!-- En-tête de section -->
  <div class="section-header">
    <h2><i class="fas fa-box-open"></i> Ajout Matériel</h2>
    <a href="stock.php" class="btn-docto secondary">
      <i class="fas fa-arrow-left"></i> Retour au stock
    </a>
  </div>

  <!-- Carte formulaire -->
  <div class="form-card">
    <form method="POST" action="">
      <div class="form-grid">

        <div class="section-title">
          <i class="fas fa-info-circle"></i> Informations du matériel
        </div>

        <div class="form-group full">
          <label for="inputdesignation">Désignation <span class="required">*</span></label>
          <div class="input-icon">
            <i class="fas fa-tag"></i>
            <input 
              type="text" 
              id="inputdesignation" 
              name="inputdesignation" 
              placeholder="Ex: Ordinateur portable HP"
              maxlength="150"
              required>
          </div>
        </div>

        <div class="section-title">
          <i class="fas fa-handshake"></i> Partenaires
        </div>

        <div class="form-group">
          <label for="inputfournisseur">Fournisseur <span class="required">*</span></label>
          <div class="input-icon">
            <i class="fas fa-truck"></i>
            <select id="inputfournisseur" name="inputfournisseur" required>
              <option value="" selected>Choisir...</option>
              <?php foreach($fournisseurs as $fournisseur): ?>
                  <option value="<?= htmlspecialchars($fournisseur['Id']) ?>"><?= htmlspecialchars($fournisseur['Nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="inputbailleur">Bailleur <span class="required">*</span></label>
          <div class="input-icon">
            <i class="fas fa-user-tie"></i>
            <select id="inputbailleur" name="inputbailleur" required>
              <option value="" selected>Choisir...</option>
              <?php foreach($bailleurs as $bailleur): ?>
                  <option value="<?= htmlspecialchars($bailleur['Id']) ?>"><?= htmlspecialchars($bailleur['Nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group full">
          <label for="inputpcop">PCOP <span class="required">*</span></label>
          <div class="input-icon">
            <i class="fas fa-file-invoice-dollar"></i>
            <select id="inputpcop" name="inputpcop" required>
              <option value="" selected>Choisir...</option>
              <?php foreach($pcops as $pcop): ?>
                  <option value="<?= htmlspecialchars($pcop['Id']) ?>"><?= htmlspecialchars($pcop['Compte']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-hint">
            <i class="fas fa-info-circle"></i> Le PCOP correspond au compte budgétaire associé.
          </div>
        </div>

        <div class="section-title">
          <i class="fas fa-calendar-alt"></i> Livraison & Quantité
        </div>

        <div class="form-group">
          <label for="inputdate">Date de livraison <span class="required">*</span></label>
          <div class="input-icon">
            <i class="fas fa-calendar-check"></i>
            <input type="date" id="inputdate" name="inputdate" required>
          </div>
        </div>

        <div class="form-group">
          <label for="inputquantite">Quantité <span class="required">*</span></label>
          <div class="input-icon">
            <i class="fas fa-sort-numeric-up"></i>
            <input 
              type="number" 
              id="inputquantite" 
              name="inputquantite" 
              placeholder="Ex: 10"
              min="1"
              required>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-docto primary">
            <i class="fas fa-plus-circle"></i> Ajouter
          </button>
          <a href="stock.php" class="btn-docto secondary">
            <i class="fas fa-times"></i> Annuler
          </a>
        </div>

      </div>
    </form>
  </div>

</div>

<!-- ====== TOAST CONTAINER ====== -->
<div class="toast-container" id="toastContainer"></div>

<!-- ====== SCRIPT TOAST ====== -->
<script>
  function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-docto ${type}`;
    const iconMap = {
      success: 'fa-check-circle',
      info:    'fa-info-circle',
      warning: 'fa-exclamation-triangle',
      danger:  'fa-times-circle'
    };
    toast.innerHTML = `<i class="fas ${iconMap[type] || 'fa-info-circle'}"></i> ${message}`;
    container.appendChild(toast);
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(20px)';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  // Message PHP -> toast JS
  <?php if (!empty($toastMessage)): ?>
    showToast(<?= json_encode($toastMessage) ?>, <?= json_encode($toastType) ?>);
  <?php endif; ?>

  // Message de succès après redirection ?success=1
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    showToast('✅ Matériel ajouté avec succès !', 'success');
    if (window.history.replaceState) {
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  <?php endif; ?>
</script>

<?php
include_once("footer.php");

// Vider le tampon de sortie
ob_end_flush();
?>
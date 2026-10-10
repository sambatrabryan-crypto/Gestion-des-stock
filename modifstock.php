<?php
// Démarrer la mise en tampon de sortie
ob_start();

include_once("header.php");
include_once("main.php");

// Requête pour récupérer les désignations depuis la table materiel
$queryDesignation = "SELECT Id, Designation FROM materiel";
$stmtDesignation = $pdo->prepare($queryDesignation);
$stmtDesignation->execute();
$designations = $stmtDesignation->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si l'ID est spécifié dans l'URL
if (!empty($_GET["Id"])) {
    // Préparer la requête de sélection pour récupérer les informations du stock à modifier
    $query = "SELECT * FROM stock WHERE Id = :Id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(["Id" => $_GET["Id"]]);

    // Vérifier si des données ont été trouvées
    if ($row = $pdostmt->fetch(PDO::FETCH_ASSOC)) {
        ?>

<!-- ====== STYLES DOCTOPLAN ====== -->
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
    margin: 0;
  }

  .doctoplan-page .section-header h2::before {
    content: "\f494";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: #2563eb;
  }

  .doctoplan-page .btn-back {
    background: #f1f5f9;
    color: #334155;
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
  }

  .doctoplan-page .btn-back::before {
    content: "\f060";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }

  .doctoplan-page .btn-back:hover {
    background: #e2e8f0;
    color: #1e293b;
  }

  .doctoplan-page .form-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    max-width: 820px;
  }

  .doctoplan-page .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  .doctoplan-page .form-group {
    display: flex;
    flex-direction: column;
  }

  .doctoplan-page .form-group.full {
    grid-column: 1 / -1;
  }

  .doctoplan-page .form-card label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
  }

  .doctoplan-page .form-card label .required {
    color: #ef4444;
    margin-left: 3px;
  }

  .doctoplan-page .form-card input[type="text"],
  .doctoplan-page .form-card input[type="number"],
  .doctoplan-page .form-card input[type="date"],
  .doctoplan-page .form-card select {
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

  .doctoplan-page .form-card select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 40px;
  }

  .doctoplan-page .form-card input:focus,
  .doctoplan-page .form-card select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    background: #ffffff;
  }

  .doctoplan-page .form-actions {
    display: flex;
    gap: 12px;
    margin-top: 28px;
    flex-wrap: wrap;
    grid-column: 1 / -1;
  }

  .doctoplan-page .btn-docto {
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

  .doctoplan-page .btn-docto.primary {
    background: #2563eb;
    color: white;
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
  }

  .doctoplan-page .btn-docto.primary::before {
    content: "\f0c7";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }

  .doctoplan-page .btn-docto.primary:hover {
    background: #1d4ed8;
    transform: scale(0.98);
    color: white;
  }

  .doctoplan-page .btn-docto.secondary {
    background: #f1f5f9;
    color: #334155;
  }

  .doctoplan-page .btn-docto.secondary::before {
    content: "\f00d";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }

  .doctoplan-page .btn-docto.secondary:hover {
    background: #e2e8f0;
    color: #1e293b;
  }

  /* Carte erreur */
  .doctoplan-page .error-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 32px 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    max-width: 520px;
    margin: 40px auto;
    text-align: center;
  }

  .doctoplan-page .error-card .icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #fee2e2;
    color: #b91c1c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto 16px;
  }

  .doctoplan-page .error-card p {
    font-size: 15px;
    color: #475569;
    line-height: 1.5;
    margin: 0;
  }

  @media (max-width: 700px) {
    .doctoplan-page { padding: 20px; }
    .doctoplan-page .section-header {
      flex-direction: column;
      align-items: flex-start;
    }
    .doctoplan-page .form-grid { grid-template-columns: 1fr; }
    .doctoplan-page .form-actions { flex-direction: column; }
    .doctoplan-page .btn-docto { justify-content: center; width: 100%; }
  }
</style>

<div class="doctoplan-page">

  <!-- En-tête de section -->
  <div class="section-header">
    <h2>Modifier Stock</h2>
    <a href="fruit.php" class="btn-back">Retour à la liste</a>
  </div>

  <!-- Carte formulaire -->
  <div class="form-card">
    <form method="POST" action="" onsubmit="return confirmModification();">
      <input type="hidden" name="Id" value="<?php echo htmlspecialchars($row['Id']); ?>">

      <div class="form-grid">

        <div class="form-group full">
          <label for="inputdesignation">Désignation <span class="required">*</span></label>
          <select id="inputdesignation" name="inputdesignation" required>
            <option value="" selected>Choisir...</option>
            <?php foreach($designations as $designation): ?>
              <option value="<?= htmlspecialchars($designation['Id']) ?>"
                <?php if ($designation['Id'] == $row['Designation']) echo 'selected'; ?>>
                <?= htmlspecialchars($designation['Designation']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="inputdate_entree">Date d'entrée <span class="required">*</span></label>
          <input 
            type="date" 
            id="inputdate_entree" 
            name="inputdate_entree"
            value="<?php echo htmlspecialchars($row["Date_entree"]); ?>" 
            required>
        </div>

        <div class="form-group">
          <label for="inputquantite">Quantité <span class="required">*</span></label>
          <input 
            type="number" 
            id="inputquantite" 
            name="inputquantite"
            value="<?php echo htmlspecialchars($row["Quantite"]); ?>" 
            min="1"
            required>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-docto primary">Modifier</button>
          <a href="fruit.php" class="btn-docto secondary">Annuler</a>
        </div>

      </div>
    </form>
  </div>

</div>

<script>
  function confirmModification() {
    return confirm("Êtes-vous sûr de vouloir modifier ce stock ?");
  }
</script>

        <?php
    } else {
        echo '<div class="doctoplan-page">
                <div class="error-card">
                  <div class="icon">✗</div>
                  <p>Aucun stock trouvé avec cet ID.</p>
                </div>
              </div>';
    }

    // Traitement de la soumission du formulaire pour modifier le stock
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['Id'])) {
        // Récupérer les données du formulaire
        $id = $_POST['Id'];
        $designation = $_POST['inputdesignation'];
        $date_entree = $_POST['inputdate_entree'];
        $quantite = $_POST['inputquantite'];

        // Préparer la requête de mise à jour
        $updateQuery = "UPDATE stock SET Designation = :designation, Date_entree = :date_entree, Quantite = :quantite WHERE Id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            'designation' => $designation,
            'date_entree' => $date_entree,
            'quantite' => $quantite,
            'id' => $id,
        ]);

        // Rediriger vers la liste des stocks après la mise à jour
        header("Location: fruit.php");
        exit();
    }
} else {
    echo '<div class="doctoplan-page">
            <div class="error-card">
              <div class="icon">✗</div>
              <p>ID non spécifié.</p>
            </div>
          </div>';
}

include_once("footer.php");

// Vider le tampon de sortie
ob_end_flush();
?>
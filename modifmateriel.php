<?php
// Démarrer la mise en tampon de sortie
ob_start();

include_once("header.php");
include_once("main.php");

// Récupérer la liste des fournisseurs
$queryFournisseurs = "SELECT * FROM fournisseur";
$fournisseursStmt = $pdo->prepare($queryFournisseurs);
$fournisseursStmt->execute();
$fournisseurs = $fournisseursStmt->fetchAll(PDO::FETCH_ASSOC);

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

// Vérifier si l'ID est spécifié dans l'URL
if (!empty($_GET["Id"])) {
    // Préparer la requête de sélection
    $query = "SELECT * FROM materiel WHERE Id = :Id";
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
    content: "\f466";
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
    max-width: 900px;
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

  .doctoplan-page .section-title {
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
    <h2>Modifier Matériel</h2>
    <a href="stock.php" class="btn-back">Retour à la liste</a>
  </div>

  <!-- Carte formulaire -->
  <div class="form-card">
    <form method="POST" action="" onsubmit="return confirmModification();">
      <input type="hidden" name="Id" value="<?php echo htmlspecialchars($row['Id']); ?>">

      <div class="form-grid">

        <div class="section-title">
          <i class="fas fa-info-circle"></i> Informations du matériel
        </div>

        <div class="form-group full">
          <label for="inputdesignation">Désignation <span class="required">*</span></label>
          <input 
            type="text" 
            id="inputdesignation" 
            name="inputdesignation" 
            value="<?php echo htmlspecialchars($row["Designation"]); ?>" 
            required>
        </div>

        <div class="section-title">
          <i class="fas fa-handshake"></i> Partenaires
        </div>

        <div class="form-group">
          <label for="inputfournisseur">Fournisseur <span class="required">*</span></label>
          <select id="inputfournisseur" name="inputfournisseur" required>
            <option value="" selected>Choisissez...</option>
            <?php foreach ($fournisseurs as $fournisseur): ?>
              <option value="<?= htmlspecialchars($fournisseur['Id']) ?>" <?= ($fournisseur['Id'] == $row["Fournisseur"]) ? 'selected' : '' ?>>
                <?= htmlspecialchars($fournisseur['Nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="inputbailleur">Bailleur <span class="required">*</span></label>
          <select id="inputbailleur" name="inputbailleur" required>
            <option value="" selected>Choisissez...</option>
            <?php foreach ($bailleurs as $bailleur): ?>
              <option value="<?= htmlspecialchars($bailleur['Id']) ?>" <?= ($bailleur['Id'] == $row["Bailleur"]) ? 'selected' : '' ?>>
                <?= htmlspecialchars($bailleur['Nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group full">
          <label for="inputpcop">PCOP <span class="required">*</span></label>
          <select id="inputpcop" name="inputpcop" required>
            <option value="" selected>Choisissez...</option>
            <?php foreach ($pcops as $pcop): ?>
              <option value="<?= htmlspecialchars($pcop['Id']) ?>" <?= ($pcop['Id'] == $row["Pcop"]) ? 'selected' : '' ?>>
                <?= htmlspecialchars($pcop['Compte']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="section-title">
          <i class="fas fa-calendar-alt"></i> Livraison & Quantité
        </div>

        <div class="form-group">
          <label for="inputdate">Date d'entrée <span class="required">*</span></label>
          <input 
            type="date" 
            id="inputdate" 
            name="inputdate" 
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
          <a href="stock.php" class="btn-docto secondary">Annuler</a>
        </div>

      </div>
    </form>
  </div>

</div>

<script>
  function confirmModification() {
    return confirm("Êtes-vous sûr de vouloir modifier ce matériel ?");
  }
</script>

        <?php
    } else {
        echo '<div class="doctoplan-page">
                <div class="error-card">
                  <div class="icon">✗</div>
                  <p>Aucun matériel trouvé avec cet ID.</p>
                </div>
              </div>';
    }

    // Traitement de la soumission du formulaire pour modifier le matériel
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['Id'])) {
        // Récupérer les données du formulaire
        $id = $_POST['Id'];
        $designation = $_POST['inputdesignation'];
        $fournisseur = $_POST['inputfournisseur'];
        $bailleur = $_POST['inputbailleur'];
        $pcop = $_POST['inputpcop'];
        $date = $_POST['inputdate'];
        $quantite = $_POST['inputquantite'];

        // Préparer la requête de mise à jour
        $updateQuery = "UPDATE materiel SET Designation = :designation, Fournisseur = :fournisseur, Bailleur = :bailleur, Pcop = :pcop, Date_entree = :date, Quantite = :quantite WHERE Id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            'designation' => $designation,
            'fournisseur' => $fournisseur,
            'bailleur' => $bailleur,
            'pcop' => $pcop,
            'date' => $date,
            'quantite' => $quantite,
            'id' => $id,
        ]);

        // Rediriger vers la liste des matériels après la mise à jour
        header("Location: stock.php");
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
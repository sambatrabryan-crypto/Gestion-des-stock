<?php
// Démarrer la mise en tampon de sortie
ob_start();

include_once("header.php");
include_once("main.php");

// Récupération des stocks
$querystocks = "SELECT Id, (SELECT Designation FROM materiel WHERE Id = stock.Designation) AS Designation, Quantite FROM stock";
$stmtstocks = $pdo->prepare($querystocks);
$stmtstocks->execute();
$stocks = $stmtstocks->fetchAll(PDO::FETCH_ASSOC);

// Récupération des détenteurs
$queryDetenteurs = "SELECT Id, Nom FROM detenteur";
$stmtDetenteurs = $pdo->prepare($queryDetenteurs);
$stmtDetenteurs->execute();
$detenteurs = $stmtDetenteurs->fetchAll(PDO::FETCH_ASSOC);

// Récupération des dépositaires
$queryDepositaire = "SELECT Id, Nom FROM depositaire";
$stmtDepositaire = $pdo->prepare($queryDepositaire);
$stmtDepositaire->execute();
$depositaires = $stmtDepositaire->fetchAll(PDO::FETCH_ASSOC);

// Récupération des ordonnateurs
$queryOrdonnateurs = "SELECT Id, Nom FROM ordonnateur";
$stmtOrdonnateurs = $pdo->prepare($queryOrdonnateurs);
$stmtOrdonnateurs->execute();
$ordonnateurs = $stmtOrdonnateurs->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si l'ID est spécifié dans l'URL
if (!empty($_GET["Id"])) {
    // Préparer la requête de sélection
    $query = "SELECT * FROM inventaire WHERE Id = :Id";
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
    content: "\f044";
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

  /* Message de succès */
  .doctoplan-alert {
    background: #dcfce7;
    color: #15803d;
    padding: 14px 24px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 900px;
    margin: 0 auto 20px;
    animation: slideUp 0.3s ease;
  }

  .doctoplan-alert::before {
    content: "\f058";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }

  @keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
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
    <h2>Modifier Inventaire</h2>
    <a href="inventaire.php" class="btn-back">Retour à la liste</a>
  </div>

  <!-- Carte formulaire -->
  <div class="form-card">
    <form method="POST" action="">
      <input type="hidden" name="Id" value="<?= htmlspecialchars($row['Id']) ?>">

      <div class="form-grid">

        <div class="section-title">
          <i class="fas fa-info-circle"></i> Informations du matériel
        </div>

        <div class="form-group full">
          <label for="inputdesignation">Désignation du matériel <span class="required">*</span></label>
          <select id="inputdesignation" name="inputdesignation" required>
            <option value="" selected>Choisir...</option>
            <?php foreach($stocks as $item): ?>
              <option value="<?= htmlspecialchars($item['Id']) ?>" <?= $item['Id'] == $row['Designation'] ? 'selected' : '' ?> data-quantite="<?= htmlspecialchars($item['Quantite']) ?>">
                <?= htmlspecialchars($item['Designation']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="section-title">
          <i class="fas fa-users"></i> Intervenants
        </div>

        <div class="form-group">
          <label for="inputdetenteur">Détenteur <span class="required">*</span></label>
          <select id="inputdetenteur" name="inputdetenteur" required>
            <option value="" selected>Choisir...</option>
            <?php foreach($detenteurs as $detenteur): ?>
              <option value="<?= htmlspecialchars($detenteur['Id']) ?>" <?= $detenteur['Id'] == $row['Detenteur'] ? 'selected' : '' ?>><?= htmlspecialchars($detenteur['Nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="inputdepositaire">Dépositaire <span class="required">*</span></label>
          <select id="inputdepositaire" name="inputdepositaire" required>
            <option value="" selected>Choisir...</option>
            <?php foreach($depositaires as $depositaire): ?>
              <option value="<?= htmlspecialchars($depositaire['Id']) ?>" <?= $depositaire['Id'] == $row['Depositaire'] ? 'selected' : '' ?>><?= htmlspecialchars($depositaire['Nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group full">
          <label for="inputordonnateur">Ordonnateur <span class="required">*</span></label>
          <select id="inputordonnateur" name="inputordonnateur" required>
            <option value="" selected>Choisir...</option>
            <?php foreach($ordonnateurs as $ordonnateur): ?>
              <option value="<?= htmlspecialchars($ordonnateur['Id']) ?>" <?= $ordonnateur['Id'] == $row['Ordonnateur'] ? 'selected' : '' ?>><?= htmlspecialchars($ordonnateur['Nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="section-title">
          <i class="fas fa-calendar-alt"></i> Dates & Quantités
        </div>

        <div class="form-group">
          <label for="inputdatentree">Date d'entrée <span class="required">*</span></label>
          <input type="date" id="inputdatentree" name="inputdatentree" value="<?= htmlspecialchars($row['Datentree']) ?>" required>
        </div>

        <div class="form-group">
          <label for="inputquantite">Quantité sortie <span class="required">*</span></label>
          <input type="number" id="inputquantite" name="inputquantite" value="<?= htmlspecialchars($row['Quantite']) ?>" required min="1">
        </div>

        <div class="form-group full">
          <label for="inputdatesort">Date de sortie <span class="required">*</span></label>
          <input type="date" id="inputdatesort" name="inputdatesort" value="<?= htmlspecialchars($row['Datesort']) ?>" required>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-docto primary">Modifier</button>
          <a href="inventaire.php" class="btn-docto secondary">Annuler</a>
        </div>

      </div>
    </form>
  </div>

</div>

        <?php
        // Lorsque le formulaire est soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupérer les valeurs du formulaire
            $Id = $_POST['Id'];
            $inputdesignation = $_POST['inputdesignation'];
            $inputdetenteur = $_POST['inputdetenteur'];
            $inputdepositaire = $_POST['inputdepositaire'];
            $inputordonnateur = $_POST['inputordonnateur'];
            $inputdatentree = $_POST['inputdatentree'];
            $inputquantite = $_POST['inputquantite'];
            $inputdatesort = $_POST['inputdatesort'];

            // Récupérer la quantité actuelle dans le stock avant la modification
            $old_quantity_query = "SELECT Quantite FROM stock WHERE Id = :Id";
            $stmt = $pdo->prepare($old_quantity_query);
            $stmt->execute(["Id" => $inputdesignation]);
            $old_quantity = $stmt->fetchColumn();

            // Calculer la différence de quantité sortie (ancienne - nouvelle)
            $quantity_difference = $old_quantity - $inputquantite;

            // Mettre à jour le stock : soit diminuer ou augmenter
            $update_stock_query = "UPDATE stock SET Quantite = Quantite - :quantity_difference WHERE Id = :Id";
            $stmt = $pdo->prepare($update_stock_query);
            $stmt->execute([
                ":quantity_difference" => abs($quantity_difference),
                ":Id" => $inputdesignation
            ]);

            // Mise à jour de l'inventaire dans la table
            $update_inventory_query = "UPDATE inventaire SET Designation = :designation, Detenteur = :detenteur, Depositaire = :depositaire, Ordonnateur = :ordonnateur, Datentree = :datentree, Quantite = :quantite, Datesort = :datesort WHERE Id = :Id";
            $stmt = $pdo->prepare($update_inventory_query);
            $stmt->execute([
                ":designation" => $inputdesignation,
                ":detenteur" => $inputdetenteur,
                ":depositaire" => $inputdepositaire,
                ":ordonnateur" => $inputordonnateur,
                ":datentree" => $inputdatentree,
                ":quantite" => $inputquantite,
                ":datesort" => $inputdatesort,
                ":Id" => $Id
            ]);

            echo '<div class="doctoplan-alert">Inventaire mis à jour avec succès !</div>';
        }
    }
}
?>
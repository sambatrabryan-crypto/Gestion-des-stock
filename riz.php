<?php
include_once("header.php");
include_once("main.php");

// Vérifier si un ID a été soumis
if (isset($_POST['inputquantite']) && !empty($_POST['inputquantite'])) {
    $inventaireId = intval($_POST['inputquantite']);
    
    // Requête pour récupérer les données de l'inventaire avec les informations supplémentaires
    $query = "
        SELECT 
            inventaire.Id,
            materiel.Designation AS MaterielDesignation,
            fournisseur.Nom AS FournisseurNom,
            detenteur.Nom AS DetenteurNom,
            depositaire.Nom AS DepositaireNom,
            ordonnateur.Nom AS OrdonnateurNom,
            materiel.Bailleur AS BailleurId, -- Récupérer l'ID du bailleur
            materiel.Pcop AS PcopId, -- Récupérer l'ID du PCOP
            inventaire.Datentree AS DateEntree,
            inventaire.Quantitentre AS QuantiteEntree,
            inventaire.Datesort AS DateSortie,
            inventaire.Quantite AS QuantiteSortie,
            (inventaire.Quantitentre - inventaire.Quantite) AS Reste
        FROM inventaire
        JOIN stock ON inventaire.Designation = stock.Id
        JOIN materiel ON stock.Designation = materiel.Id
        JOIN fournisseur ON materiel.Fournisseur = fournisseur.Id
        JOIN detenteur ON inventaire.Detenteur = detenteur.Id
        JOIN depositaire ON inventaire.Depositaire = depositaire.Id
        JOIN ordonnateur ON inventaire.Ordonnateur = ordonnateur.Id
        WHERE inventaire.Id = :inventaireId
    ";
    
    $pdostmt = $pdo->prepare($query);
    $pdostmt->bindParam(':inventaireId', $inventaireId, PDO::PARAM_INT);
    $pdostmt->execute();

    // Vérifier si des données ont été trouvées
    $result = $pdostmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
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
    content: "\f15c";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: #2563eb;
  }

  .doctoplan-page .section-header .badge-id {
    background: #eef4ff;
    color: #2563eb;
    padding: 4px 14px;
    border-radius: 40px;
    font-size: 13px;
    font-weight: 600;
    margin-left: 10px;
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
    float: none !important;
    margin-top: 0 !important;
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

  /* Tableau */
  .doctoplan-page .table-wrapper {
    background: #ffffff;
    border-radius: 20px;
    padding: 8px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    overflow-x: auto;
    margin-bottom: 24px;
  }

  .doctoplan-page table.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    color: #0b1e33;
    margin: 0;
  }

  .doctoplan-page table.table thead th {
    text-align: left;
    padding: 16px 18px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #eef2f6;
    background: transparent;
    white-space: nowrap;
  }

  .doctoplan-page table.table tbody td {
    padding: 16px 18px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    white-space: nowrap;
  }

  .doctoplan-page table.table tbody tr:last-child td {
    border-bottom: none;
  }

  .doctoplan-page table.table tbody tr:hover {
    background: #fafcff;
  }

  /* Bloc Bailleur + PCOP */
  .doctoplan-page .bailleur-pcop-info {
    background: #ffffff;
    border-radius: 20px;
    padding: 24px 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    margin-bottom: 24px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  .doctoplan-page .bailleur-pcop-info h3 {
    font-size: 15px;
    font-weight: 700;
    color: #0b1e33;
    margin: 0 0 6px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .doctoplan-page .bailleur-pcop-info h3::before {
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: #2563eb;
    font-size: 16px;
  }

  .doctoplan-page .bailleur-pcop-info h3:nth-of-type(1)::before {
    content: "\f19c";
  }

  .doctoplan-page .bailleur-pcop-info h3:nth-of-type(2)::before {
    content: "\f571";
  }

  .doctoplan-page .bailleur-pcop-info p {
    font-size: 14px;
    color: #475569;
    margin: 0;
    line-height: 1.5;
  }

  /* Formulaire PDF */
  .doctoplan-page form[action="pdf.php"] {
    background: #ffffff;
    border-radius: 20px;
    padding: 24px 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    max-width: 520px;
    margin-bottom: 24px;
  }

  .doctoplan-page form[action="pdf.php"] button.btn-danger {
    background: #2563eb !important;
    color: white !important;
    border: none;
    padding: 12px 28px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: 0.15s;
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
    width: 100%;
    justify-content: center;
  }

  .doctoplan-page form[action="pdf.php"] button.btn-danger::before {
    content: "\f1c1";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }

  .doctoplan-page form[action="pdf.php"] button.btn-danger:hover {
    background: #1d4ed8 !important;
    transform: scale(0.98);
    color: white !important;
  }

  /* Bouton retour */
  .doctoplan-page .btn-secondary {
    background: #f1f5f9 !important;
    color: #334155 !important;
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
    float: none !important;
    margin-top: 0 !important;
  }

  .doctoplan-page .btn-secondary::before {
    content: "\f060";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }

  .doctoplan-page .btn-secondary:hover {
    background: #e2e8f0 !important;
    color: #1e293b !important;
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
    .doctoplan-page .bailleur-pcop-info {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="doctoplan-page">

  <!-- En-tête de section -->
  <div class="section-header">
    <h2>Détails de l'inventaire <span class="badge-id">ID : <?php echo htmlspecialchars($inventaireId); ?></span></h2>
    <a href="inventaire.php" class="btn-back">Retour à l'inventaire</a>
  </div>

  <!-- Tableau -->
  <div class="table-wrapper">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Id</th>
          <th>Désignation-Matériel</th>
          <th>Fournisseur</th>
          <th>Dépositaire</th>
          <th>Dépositaire</th>
          <th>Ordonnateur</th>
          <th>Date d'entrée</th>
          <th>Quantité entrée</th>
          <th>Date de sortie</th>
          <th>Quantité sortie</th>
          <th>Reste</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo htmlspecialchars($result["Id"]); ?></td>
          <td><?php echo htmlspecialchars($result["MaterielDesignation"]); ?></td>
          <td><?php echo htmlspecialchars($result["FournisseurNom"]); ?></td>
          <td><?php echo htmlspecialchars($result["DetenteurNom"]); ?></td>
          <td><?php echo htmlspecialchars($result["DepositaireNom"]); ?></td>
          <td><?php echo htmlspecialchars($result["OrdonnateurNom"]); ?></td>
          <td><?php echo htmlspecialchars($result["DateEntree"]); ?></td>
          <td><?php echo htmlspecialchars($result["QuantiteEntree"]); ?></td>
          <td><?php echo htmlspecialchars($result["DateSortie"]); ?></td>
          <td><?php echo htmlspecialchars($result["QuantiteSortie"]); ?></td>
          <td><?php echo htmlspecialchars($result["Reste"]); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <?php
    // Récupérer le nom du Bailleur
    $bailleurId = $result["BailleurId"];
    $queryBailleur = "SELECT Nom FROM bailleur WHERE Id = :bailleurId";
    $stmtBailleur = $pdo->prepare($queryBailleur);
    $stmtBailleur->bindParam(':bailleurId', $bailleurId, PDO::PARAM_INT);
    $stmtBailleur->execute();
    $bailleur = $stmtBailleur->fetch(PDO::FETCH_ASSOC);

    // Récupérer les informations du PCOP
    $pcopId = $result["PcopId"];
    $queryPcop = "SELECT Compte, Designe FROM pcop WHERE Id = :pcopId";
    $stmtPcop = $pdo->prepare($queryPcop);
    $stmtPcop->bindParam(':pcopId', $pcopId, PDO::PARAM_INT);
    $stmtPcop->execute();
    $pcop = $stmtPcop->fetch(PDO::FETCH_ASSOC);
  ?>

  <!-- Bloc Bailleur + PCOP -->
  <div class="bailleur-pcop-info">
    <h3>Bailleur de fond : <?php echo htmlspecialchars($bailleur['Nom']); ?></h3>
    <h3>PCOP : <?php echo htmlspecialchars($pcop['Compte']); ?></h3>
    <p>Designation : <?php echo htmlspecialchars($pcop['Designe']); ?></p>
  </div>

  <!-- Formulaire pour générer le PDF -->
  <form action="pdf.php" method="POST">
    <input type="hidden" name="inputquantite" value="<?php echo $inventaireId; ?>">
    <button type="submit" class="btn btn-danger">Générer le PDF</button>
  </form>

</div>

<?php
    } else {
        echo '<div class="doctoplan-page">
                <div class="error-card">
                  <div class="icon">✗</div>
                  <p>Aucun inventaire trouvé pour l\'ID: ' . htmlspecialchars($inventaireId) . '</p>
                </div>
              </div>';
    }
} else {
    echo '<div class="doctoplan-page">
            <div class="error-card">
              <div class="icon">✗</div>
              <p>Veuillez entrer un ID d\'inventaire.</p>
            </div>
          </div>';
}
?>

<?php
include_once("footer.php");
?>
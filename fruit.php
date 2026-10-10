<?php
include_once("header.php");
include_once("main.php");

?>

<!-- ====== STYLES DOCTOPLAN ====== -->
<style>
  .doctoplan-page {
    background: #fafcff;
    padding: 32px;
    min-height: 100vh;
  }

  .doctoplan-page .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
  }

  .doctoplan-page .page-header h1 {
    font-size: 22px;
    font-weight: 700;
    color: #0b1e33;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
  }

  .doctoplan-page .page-header h1::before {
    content: "\f494";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: #2563eb;
  }

  .doctoplan-page .btn-add {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: 0.15s;
    text-decoration: none;
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
    float: none !important;
    margin-bottom: 0 !important;
  }

  .doctoplan-page .btn-add:hover {
    background: #1d4ed8;
    transform: scale(0.98);
    color: white;
  }

  .doctoplan-page .btn-add svg {
    width: 16px;
    height: 16px;
  }

  /* Tableau */
  .doctoplan-page .table-wrapper {
    background: #ffffff;
    border-radius: 20px;
    padding: 8px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    overflow-x: auto;
  }

  .doctoplan-page table#Tablefor {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    color: #0b1e33;
  }

  .doctoplan-page table#Tablefor thead th {
    text-align: left;
    padding: 16px 18px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #eef2f6;
    background: transparent;
  }

  .doctoplan-page table#Tablefor tbody td {
    padding: 16px 18px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
  }

  .doctoplan-page table#Tablefor tbody tr:last-child td {
    border-bottom: none;
  }

  .doctoplan-page table#Tablefor tbody tr:hover {
    background: #fafcff;
  }

  /* Boutons d'action */
  .doctoplan-page table#Tablefor .btn {
    border: none;
    padding: 8px 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.15s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    margin-right: 6px;
  }

  .doctoplan-page table#Tablefor .btn svg {
    width: 16px;
    height: 16px;
  }

  .doctoplan-page table#Tablefor .btn-success {
    background: #dcfce7;
    color: #15803d;
  }

  .doctoplan-page table#Tablefor .btn-success:hover {
    background: #bbf7d0;
    color: #166534;
  }

  .doctoplan-page table#Tablefor .btn-danger {
    background: #fee2e2;
    color: #b91c1c;
  }

  .doctoplan-page table#Tablefor .btn-danger:hover {
    background: #fecaca;
    color: #991b1b;
  }

  @media (max-width: 700px) {
    .doctoplan-page { padding: 20px; }
    .doctoplan-page .page-header {
      flex-direction: column;
      align-items: flex-start;
    }
  }
</style>

<div class="doctoplan-page">

  <!-- En-tête avec titre + bouton ajout -->
  <div class="page-header">
    <h1 class="mt-5">Les Stocks</h1>
    <a href="addstock.php" class="btn btn-primary btn-add">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
      </svg>
      Ajouter
    </a>
  </div>

  <?php
  // Requête SQL pour récupérer la désignation du matériel avec le stock et la quantité
  $query = "SELECT stock.Id, materiel.Designation AS MaterielDesignation,stock.Date_entree, stock.Quantite 
            FROM stock 
            JOIN materiel ON stock.Designation = materiel.Id";  // Join sur Designation (ID du matériel)
  $pdostmt = $pdo->prepare($query);
  $pdostmt->execute();
  ?>

  <!-- Tableau -->
  <div class="table-wrapper">
    <table id="Tablefor" class="display">
      <thead>
        <tr>
          <th>Id</th>
          <th>Designation</th>
          <th>Date d' entrée</th>
          <th>Quantite</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($ligne = $pdostmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
          <td><?php echo $ligne["Id"]; ?></td>
          <td><?php echo htmlspecialchars($ligne["MaterielDesignation"]); ?></td>  <!-- Affichage de la désignation -->
          <td><?php echo $ligne["Date_entree"]; ?></td>
          <td><?php echo $ligne["Quantite"]; ?></td>
          <td>
            <a href="modifstock.php?Id=<?php echo $ligne["Id"]?>" class="btn btn-success">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
              </svg>
            </a>

            <!-- Formulaire de suppression -->
            <form action="deletestock.php" method="POST" style="display:inline-block;">
              <input type="hidden" name="id" value="<?php echo $ligne['Id']; ?>">
              <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce stock ?');">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                  <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                </svg>
              </button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>

</main>

<?php
include_once("footer.php");
?>
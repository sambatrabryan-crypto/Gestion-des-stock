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

// Vérifier si le formulaire a été soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si tous les champs obligatoires sont remplis
    if (!empty($_POST["inputdesignation"])&& !empty($_POST["inputdate_entree"]) && !empty($_POST["inputquantite"])) {
        // Préparer la requête d'insertion
        $query = "INSERT INTO stock (Designation,Date_entree, Quantite) VALUES (:Designation,:Date_entree,:Quantite)";
        $pdostmt = $pdo->prepare($query);

        // Exécuter la requête avec les données du formulaire
        $pdostmt->execute([
            "Designation" => $_POST["inputdesignation"],
            "Date_entree" => $_POST["inputdate_entree"],
            "Quantite" => $_POST["inputquantite"]
        ]);

        // Fermer le curseur
        $pdostmt->closeCursor();

        // Rediriger vers la page précédente ou une autre page après le succès
        header("Location: fruit.php");
        exit();  // Nécessaire après un header() pour stopper l'exécution du script
    } else {
        echo "<p>Veuillez remplir tous les champs.</p>";
    }
}
?>

<!-- ====== STYLES DOCTOPLAN ====== -->
<style>
  .doctoplan-page {
    background: #fafcff;
    padding: 32px;
    min-height: 100vh;
  }

  .doctoplan-page h1 {
    font-size: 22px;
    font-weight: 700;
    color: #0b1e33;
    letter-spacing: -0.3px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .doctoplan-page h1::before {
    content: "\f494";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: #2563eb;
  }

  .doctoplan-page form.row {
    background: #ffffff;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    max-width: 820px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  .doctoplan-page form.row .col-md-6 {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 100%;
    flex: unset;
    padding: 0;
  }

  .doctoplan-page form.row .col-12 {
    grid-column: 1 / -1;
    padding: 0;
  }

  .doctoplan-page form.row label.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
  }

  .doctoplan-page form.row input.form-control,
  .doctoplan-page form.row select.form-select {
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

  .doctoplan-page form.row select.form-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 40px;
  }

  .doctoplan-page form.row input.form-control:focus,
  .doctoplan-page form.row select.form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    background: #ffffff;
  }

  .doctoplan-page form.row button.btn.btn-primary {
    background: #2563eb;
    color: white;
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
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
  }

  .doctoplan-page form.row button.btn.btn-primary::before {
    content: "\f055";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }

  .doctoplan-page form.row button.btn.btn-primary:hover {
    background: #1d4ed8;
    transform: scale(0.98);
  }

  @media (max-width: 700px) {
    .doctoplan-page { padding: 20px; }
    .doctoplan-page form.row {
      grid-template-columns: 1fr;
      padding: 20px;
    }
  }
</style>

<div class="doctoplan-page">

<h1 class="mt-5">Ajout Stock</h1>

<!-- Formulaire avec méthode POST -->
<form class="row g-3" method="POST">
    <div class="col-md-6">
        <label for="inputdesignation" class="form-label">Désignation</label>
        <select id="inputdesignation" name="inputdesignation" class="form-select" required>
            <option value="" selected>Choose...</option>
            <?php foreach($designations as $designation): ?>
                <option value="<?= htmlspecialchars($designation['Id']) ?>"><?= htmlspecialchars($designation['Designation']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
  
    <div class="col-md-6">
        <label for="inputdate_entree" class="form-label">Date d'entrée</label>
        <input type="Date" class="form-control" id="inputdate_entree" name="inputdate_entree" required>
    </div>

    <div class="col-md-6">
        <label for="inputquantite" class="form-label">Quantité</label>
        <input type="number" class="form-control" id="inputquantite" name="inputquantite" required>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </div>
</form>

</div>

<?php
include_once("footer.php");

// Vider le tampon de sortie
ob_end_flush();
?>
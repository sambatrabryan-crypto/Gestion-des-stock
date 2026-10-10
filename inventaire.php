<?php
$inventaire = true;
include_once("header.php");
include_once("main.php");

// Récupération des inventaires avec toutes les jointures
$query = "
    SELECT 
        inventaire.Id, 
        inventaire.Datesort,
        materiel.Designation AS MaterielDesignation, 
        detenteur.Nom AS DetenteurNom, 
        depositaire.Nom AS DepositaireNom, 
        ordonnateur.Nom AS OrdonnateurNom, 
        inventaire.Datentree AS DateEntree,
        inventaire.Quantitentre AS QuantiteEntree,
        inventaire.Quantite AS QuantiteSortie,
        (inventaire.Quantitentre - inventaire.Quantite) AS Reste
    FROM inventaire
    JOIN stock ON inventaire.Designation = stock.Id
    JOIN materiel ON stock.Designation = materiel.Id
    JOIN detenteur ON inventaire.Detenteur = detenteur.Id
    JOIN depositaire ON inventaire.Depositaire = depositaire.Id
    JOIN ordonnateur ON inventaire.Ordonnateur = ordonnateur.Id
";
$pdostmt = $pdo->prepare($query);
$pdostmt->execute();
$inventaires = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($inventaires);
?>

<!-- ====== STYLES DOCTOPLAN ====== -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  /* Reset local scopé */
  .doctoplan-page *,
  .doctoplan-page *::before,
  .doctoplan-page *::after {
    box-sizing: border-box;
  }

  .doctoplan-page {
    --blue-900: #0a2a5e;
    --blue-600: #2563eb;
    --blue-500: #3b82f6;
    --teal-400: #2dd4bf;
    --ink: #0b1e33;
    --muted: #64748b;
    --line: #e8eef6;
    --bg: #f4f8fd;
    --surface: #ffffff;

    font-family: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
    background:
      radial-gradient(900px 400px at 100% 0%, rgba(45, 212, 191, 0.10), transparent 60%),
      radial-gradient(800px 400px at 0% 20%, rgba(37, 99, 235, 0.08), transparent 60%),
      var(--bg);
    padding: 24px 40px 48px;
    min-height: 100vh;
    color: var(--ink);
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
  }

  /* ---------- En-tête de page ---------- */
  .doctoplan-page .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
    flex-wrap: wrap;
  }

  .doctoplan-page .page-header h1 {
    margin: 0;
    font-size: 26px;
    font-weight: 800;
    color: var(--ink);
    letter-spacing: -0.6px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .doctoplan-page .page-header h1::before {
    content: "\f46d";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: var(--blue-600);
    font-size: 22px;
    background: #eef4ff;
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .doctoplan-page .btn-add {
    position: relative;
    overflow: hidden;
    background: var(--blue-600);
    color: #fff;
    border: none;
    padding: 12px 24px;
    min-height: 48px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 15px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 10px 24px -8px rgba(37, 99, 235, 0.5);
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
  }
  .doctoplan-page .btn-add:hover {
    transform: translateY(-3px);
    background: var(--blue-900);
    color: #fff;
    box-shadow: 0 16px 30px -10px rgba(37, 99, 235, 0.6);
  }
  .doctoplan-page .btn-add svg { width: 18px; height: 18px; }

  /* ---------- Barre d'outils ---------- */
  .doctoplan-page .toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 16px;
    flex-wrap: wrap;
  }

  .doctoplan-page .search {
    position: relative;
    flex: 1 1 320px;
    max-width: 420px;
  }
  .doctoplan-page .search svg {
    position: absolute;
    left: 16px; top: 50%;
    width: 18px; height: 18px;
    transform: translateY(-50%);
    color: var(--muted);
    pointer-events: none;
    z-index: 2;
  }
  .doctoplan-page .search input {
    width: 100%;
    height: 48px;
    padding: 0 18px 0 46px;
    border-radius: 999px;
    border: 1.5px solid var(--line);
    background: var(--surface);
    font: inherit;
    font-size: 15px;
    color: var(--ink);
    box-shadow: 0 2px 6px rgba(15, 40, 80, 0.04);
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
  }
  .doctoplan-page .search input:focus {
    border-color: var(--blue-500);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
  }

  .doctoplan-page .result-count { font-size: 14px; color: var(--muted); font-weight: 500; }

  /* ---------- Tableau ---------- */
  .doctoplan-page .table-wrapper {
    background: var(--surface);
    border-radius: 24px;
    padding: 10px;
    border: 1px solid var(--line);
    box-shadow: 0 18px 40px -26px rgba(15, 40, 80, 0.35);
    overflow-x: auto;
    margin-bottom: 28px;
  }

  .doctoplan-page table#Tablefor {
    width: 100% !important;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
    color: var(--ink);
    margin: 0;
  }

  .doctoplan-page table#Tablefor thead th {
    text-align: left;
    padding: 14px 16px;
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    letter-spacing: 0.3px;
    text-transform: uppercase;
    border-bottom: 2px solid var(--line);
    background: var(--surface);
    white-space: nowrap;
  }

  .doctoplan-page table#Tablefor tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    transition: background 0.2s ease;
    white-space: nowrap;
  }

  .doctoplan-page table#Tablefor tbody tr:last-child td { border-bottom: none; }
  .doctoplan-page table#Tablefor tbody tr:hover td { background: #f3f8ff; }
  .doctoplan-page table#Tablefor tbody tr:hover td:first-child { box-shadow: inset 4px 0 0 var(--blue-600); }

  .doctoplan-page .id-tag {
    display: inline-block;
    min-width: 34px;
    text-align: center;
    padding: 3px 10px;
    border-radius: 999px;
    background: #eef4ff;
    color: var(--blue-600);
    font-weight: 700;
    font-size: 13px;
  }

  /* Pastille "Reste" */
  .doctoplan-page .badge-rest {
    display: inline-block;
    min-width: 34px;
    text-align: center;
    padding: 4px 12px;
    border-radius: 999px;
    background: #dcfce7;
    color: #15803d;
    font-weight: 700;
    font-size: 13px;
  }
  .doctoplan-page .badge-rest.warn { background: #fef3c7; color: #a16207; }
  .doctoplan-page .badge-rest.danger { background: #fee2e2; color: #b91c1c; }

  .doctoplan-page .muted { color: var(--muted); }

  /* ---------- Boutons d'action ---------- */
  .doctoplan-page .actions { display: flex; align-items: center; gap: 8px; }
  .doctoplan-page .actions form { margin: 0; display: inline-flex; }

  .doctoplan-page table#Tablefor .btn {
    border: none;
    width: 38px; height: 38px;
    padding: 0;
    border-radius: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    margin: 0;
    transition: transform 0.18s ease, background 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
  }
  .doctoplan-page table#Tablefor .btn svg { width: 16px; height: 16px; }
  .doctoplan-page table#Tablefor .btn:hover { transform: translateY(-2px); }
  .doctoplan-page table#Tablefor .btn:active { transform: scale(0.92); }

  .doctoplan-page table#Tablefor .btn-success { background: #dcfce7; color: #15803d; }
  .doctoplan-page table#Tablefor .btn-success:hover { background: #16a34a; color: #fff; }
  .doctoplan-page table#Tablefor .btn-danger { background: #fee2e2; color: #b91c1c; }
  .doctoplan-page table#Tablefor .btn-danger:hover { background: #dc2626; color: #fff; }

  /* ---------- État vide ---------- */
  .doctoplan-page .empty {
    display: none;
    text-align: center;
    padding: 48px 20px;
    color: var(--muted);
  }
  .doctoplan-page .empty.show { display: block; }
  .doctoplan-page .empty strong { display: block; font-size: 17px; color: var(--ink); margin-bottom: 4px; }

  /* Masque les éléments DataTables résiduels */
  .doctoplan-page .dataTables_filter,
  .doctoplan-page .dataTables_length,
  .doctoplan-page .dataTables_info,
  .doctoplan-page .dataTables_paginate { display: none !important; }

  /* ---------- Actions en bas de page ---------- */
  .doctoplan-page .bottom-actions {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 8px;
  }

  /* Bouton STOCK */
  .doctoplan-page .btn-stock {
    background: #1e293b;
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 15px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    cursor: pointer;
    transition: 0.15s;
    box-shadow: 0 10px 24px -8px rgba(30, 41, 59, 0.5);
  }
  .doctoplan-page .btn-stock::before {
    content: "\f494";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }
  .doctoplan-page .btn-stock:hover {
    background: #0f172a;
    transform: translateY(-3px);
    color: #fff;
    box-shadow: 0 16px 30px -10px rgba(30, 41, 59, 0.6);
  }

  /* ---------- Formulaire PDF ---------- */
  .doctoplan-page .pdf-form {
    background: var(--surface);
    border-radius: 20px;
    padding: 24px 28px;
    border: 1px solid var(--line);
    box-shadow: 0 18px 40px -26px rgba(15, 40, 80, 0.35);
    max-width: 560px;
    margin-top: 24px;
  }

  .doctoplan-page .pdf-form label {
    display: block;
    font-size: 14px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 10px;
  }

  .doctoplan-page .pdf-form label::before {
    content: "\f1c1";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: var(--blue-600);
    margin-right: 8px;
  }

  .doctoplan-page .pdf-form .form-row {
    display: flex;
    gap: 12px;
    align-items: stretch;
    flex-wrap: wrap;
  }

  .doctoplan-page .pdf-form input[type="number"] {
    flex: 1 1 140px;
    padding: 12px 16px;
    border: 1.5px solid var(--line);
    border-radius: 16px;
    font-size: 15px;
    font-family: inherit;
    color: var(--ink);
    background: var(--bg);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  }
  .doctoplan-page .pdf-form input[type="number"]:focus {
    border-color: var(--blue-500);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
    background: #fff;
  }

  .doctoplan-page .pdf-form button {
    background: var(--blue-600);
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: 0.15s;
    box-shadow: 0 10px 24px -8px rgba(37, 99, 235, 0.5);
    white-space: nowrap;
  }
  .doctoplan-page .pdf-form button::before {
    content: "\f15c";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
  }
  .doctoplan-page .pdf-form button:hover {
    background: var(--blue-900);
    transform: translateY(-2px);
    box-shadow: 0 16px 30px -10px rgba(37, 99, 235, 0.6);
  }

  /* ---------- Responsive ---------- */
  @media (max-width: 900px) {
    .doctoplan-page { padding: 20px 24px 40px; }
  }

  @media (max-width: 760px) {
    .doctoplan-page { padding: 16px 16px 32px; }
    .doctoplan-page .page-header h1 { font-size: 22px; }
    .doctoplan-page .search { max-width: none; }

    .doctoplan-page table#Tablefor thead { display: none; }
    .doctoplan-page table#Tablefor,
    .doctoplan-page table#Tablefor tbody,
    .doctoplan-page table#Tablefor tr,
    .doctoplan-page table#Tablefor td { display: block; width: 100%; }
    .doctoplan-page table#Tablefor tbody tr {
      border: 1px solid var(--line);
      border-radius: 18px;
      margin-bottom: 12px;
      padding: 6px 4px;
    }
    .doctoplan-page table#Tablefor tbody td { border: none; padding: 8px 14px; white-space: normal; }
    .doctoplan-page table#Tablefor tbody td::before {
      content: attr(data-label);
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      margin-bottom: 2px;
    }
    .doctoplan-page table#Tablefor tbody td[data-label="Id"] { display: none; }
    .doctoplan-page table#Tablefor tbody td[data-label="Désignation-Matériel"]::before,
    .doctoplan-page table#Tablefor tbody td[data-label="Action"]::before { display: none; }
  }
</style>

<!-- ====== CONTENU DANS <main> ====== -->
<main class="flex-shrink-0">
  <div class="doctoplan-page">

    <!-- En-tête -->
    <div class="page-header">
      <h1>Registre des matériels</h1>
      <a href="addinventaire.php" class="btn-add">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
        </svg>
        Ajouter
      </a>
    </div>

    <!-- Recherche -->
    <div class="toolbar">
      <div class="search">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg>
        <input type="search" id="searchInput" placeholder="Rechercher par matériel, détenteur, dépositaire, ordonnateur..." aria-label="Rechercher" autocomplete="off">
      </div>
      <div class="result-count" id="resultCount" aria-live="polite"></div>
    </div>

    <!-- Tableau -->
    <div class="table-wrapper">
      <table id="Tablefor" class="display">
        <thead>
          <tr>
            <th>Id</th>
            <th>Désignation-Matériel</th>
            <th>Détenteur</th>
            <th>Dépositaire</th>
            <th>Ordonnateur</th>
            <th>Date d'entrée</th>
            <th>Qté entrée</th>
            <th>Date de sortie</th>
            <th>Qté sortie</th>
            <th>Reste</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($inventaires as $ligne):
            $reste = (int)$ligne["Reste"];
            $qteEntree = (int)$ligne["QuantiteEntree"];
            // Couleur du badge Reste
            $resteClass = "";
            if ($qteEntree > 0) {
              $ratio = $reste / max($qteEntree, 1);
              if ($ratio >= 0.5) $resteClass = "";
              elseif ($ratio > 0) $resteClass = "warn";
              else $resteClass = "danger";
            }
          ?>
          <tr>
            <td data-label="Id"><span class="id-tag"><?php echo (int)$ligne["Id"]; ?></span></td>
            <td data-label="Désignation-Matériel"><strong><?php echo htmlspecialchars($ligne["MaterielDesignation"], ENT_QUOTES, 'UTF-8'); ?></strong></td>
            <td data-label="Détenteur"><?php echo htmlspecialchars($ligne["DetenteurNom"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td data-label="Dépositaire"><?php echo htmlspecialchars($ligne["DepositaireNom"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td data-label="Ordonnateur"><?php echo htmlspecialchars($ligne["OrdonnateurNom"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td data-label="Date d'entrée" class="muted"><?php echo htmlspecialchars($ligne["DateEntree"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td data-label="Qté entrée"><?php echo (int)$ligne["QuantiteEntree"]; ?></td>
            <td data-label="Date de sortie" class="muted"><?php echo htmlspecialchars($ligne["Datesort"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td data-label="Qté sortie"><?php echo (int)$ligne["QuantiteSortie"]; ?></td>
            <td data-label="Reste"><span class="badge-rest <?php echo $resteClass; ?>"><?php echo $reste; ?></span></td>
            <td data-label="Action">
              <div class="actions">
                <a href="modifinventaire.php?Id=<?php echo (int)$ligne["Id"]; ?>" class="btn btn-success" title="Modifier">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                  </svg>
                </a>

                <form action="deleteinventaire.php" method="POST">
                  <input type="hidden" name="id" value="<?php echo (int)$ligne['Id']; ?>">
                  <button type="submit" class="btn btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet inventaire?');">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                    </svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="empty" id="emptyState">
        <strong>Aucun inventaire trouvé</strong>
        Essayez un autre mot-clé ou ajoutez un nouvel inventaire.
      </div>
    </div>

    <!-- Actions en bas -->
    <div class="bottom-actions">
      <a href="fruit.php" class="btn-stock">Voir le STOCK</a>
    </div>

    <!-- Formulaire PDF -->
    <form class="pdf-form" action="riz.php" method="POST">
      <label for="inputquantite">Entrer l'Id de l'inventaire pour le générer en PDF</label>
      <div class="form-row">
        <input type="number" id="inputquantite" name="inputquantite" placeholder="Ex: 3001" required min="1">
        <button type="submit">Voir les données à générer</button>
      </div>
    </form>

  </div>
</main>

<script>
  (function () {
    var input = document.getElementById('searchInput');
    var count = document.getElementById('resultCount');
    var empty = document.getElementById('emptyState');
    var rows  = Array.prototype.slice.call(document.querySelectorAll('#Tablefor tbody tr'));
    var total = rows.length;

    function label(n) {
      return n + ' résultat' + (n > 1 ? 's' : '') + ' sur ' + total;
    }

    function update(visible) {
      count.textContent = input.value.trim() ? label(visible) : '';
      empty.classList.toggle('show', visible === 0);
    }

    input.addEventListener('input', function () {
      var q = input.value.trim().toLowerCase();
      var visible = 0;

      rows.forEach(function (tr) {
        var match = tr.textContent.toLowerCase().indexOf(q) !== -1;
        tr.style.display = match ? '' : 'none';
        if (match) visible++;
      });

      update(visible);
    });

    if (total === 0) empty.classList.add('show');
  })();
</script>

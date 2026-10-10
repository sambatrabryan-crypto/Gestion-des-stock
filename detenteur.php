<?php
$depositaire = true;
include_once("header.php");
include_once("main.php");

// Récupération des détenteurs
$query = "SELECT * FROM detenteur";
$pdostmt = $pdo->prepare($query);
$pdostmt->execute();
$detenteurs = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($detenteurs);
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
    content: "\f505";
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
  }

  .doctoplan-page table#Tablefor {
    width: 100% !important;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14.5px;
    color: var(--ink);
    margin: 0;
  }

  .doctoplan-page table#Tablefor thead th {
    text-align: left;
    padding: 14px 18px;
    font-size: 13px;
    font-weight: 700;
    color: var(--muted);
    letter-spacing: 0.2px;
    border-bottom: 2px solid var(--line);
    background: var(--surface);
    white-space: nowrap;
  }

  .doctoplan-page table#Tablefor tbody td {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    transition: background 0.2s ease;
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

  .doctoplan-page .supplier { display: flex; align-items: center; gap: 12px; font-weight: 600; }
  .doctoplan-page .avatar {
    flex: none;
    width: 40px; height: 40px;
    border-radius: 14px;
    display: grid; place-items: center;
    font-weight: 800;
    font-size: 16px;
    color: #fff;
    background: linear-gradient(135deg, hsl(var(--h) 80% 55%), hsl(calc(var(--h) + 35) 80% 45%));
    box-shadow: 0 6px 14px -6px hsl(var(--h) 80% 40%);
    transition: transform 0.25s cubic-bezier(.3, 1.6, .5, 1);
  }
  .doctoplan-page table#Tablefor tbody tr:hover .avatar { transform: rotate(-6deg) scale(1.08); }

  .doctoplan-page .cell-link {
    color: inherit;
    text-decoration: none;
    background-image: linear-gradient(var(--blue-600), var(--blue-600));
    background-size: 0 2px;
    background-position: 0 100%;
    background-repeat: no-repeat;
    transition: background-size 0.25s ease, color 0.2s;
  }
  .doctoplan-page .cell-link:hover { color: var(--blue-600); background-size: 100% 2px; }

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
    .doctoplan-page table#Tablefor tbody td { border: none; padding: 8px 14px; }
    .doctoplan-page table#Tablefor tbody td::before {
      content: attr(data-label);
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      margin-bottom: 2px;
    }
    .doctoplan-page table#Tablefor tbody td[data-label="Id"] { display: none; }
    .doctoplan-page table#Tablefor tbody td[data-label="Nom & prénom"]::before,
    .doctoplan-page table#Tablefor tbody td[data-label="Action"]::before { display: none; }
  }
</style>

<!-- ====== CONTENU DANS <main> ====== -->
<main class="flex-shrink-0">
  <div class="doctoplan-page">

    <!-- En-tête -->
    <div class="page-header">
      <h1>Détenteurs</h1>
      <a href="adddetenteur.php" class="btn-add">
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
        <input type="search" id="searchInput" placeholder="Rechercher par nom, fonction, grade ou adresse" aria-label="Rechercher un détenteur" autocomplete="off">
      </div>
      <div class="result-count" id="resultCount" aria-live="polite"></div>
    </div>

    <!-- Tableau -->
    <div class="table-wrapper">
      <table id="Tablefor" class="display">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nom & prénom</th>
            <th>Fonction</th>
            <th>Grade</th>
            <th>Adresse</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($detenteurs as $ligne):
            $nom   = (string)$ligne["Nom"];
            $init  = mb_strtoupper(mb_substr(trim($nom), 0, 1)) ?: '?';
            $hue   = 190 + (abs(crc32($nom)) % 140);
          ?>
          <tr>
            <td data-label="Id"><span class="id-tag"><?php echo (int)$ligne["Id"]; ?></span></td>
            <td data-label="Nom & prénom">
              <div class="supplier">
                <span class="avatar" style="--h: <?php echo $hue; ?>"><?php echo htmlspecialchars($init, ENT_QUOTES, 'UTF-8'); ?></span>
                <span><?php echo htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
            </td>
            <td data-label="Fonction"><?php echo htmlspecialchars($ligne["Fonction"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td data-label="Grade"><?php echo htmlspecialchars($ligne["Direction"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td data-label="Adresse" class="muted"><?php echo htmlspecialchars($ligne["Adresse"], ENT_QUOTES, 'UTF-8'); ?></td>
            <td data-label="Action">
              <div class="actions">
                <a href="modifdetenteur.php?Id=<?php echo (int)$ligne["Id"]; ?>" class="btn btn-success" title="Modifier">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                  </svg>
                </a>

                <form action="deletedetenteur.php" method="POST">
                  <input type="hidden" name="id" value="<?php echo (int)$ligne['Id']; ?>">
                  <button type="submit" class="btn btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Détenteur ?');">
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
        <strong>Aucun détenteur trouvé</strong>
        Essayez un autre mot-clé ou ajoutez un nouveau détenteur.
      </div>
    </div>

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


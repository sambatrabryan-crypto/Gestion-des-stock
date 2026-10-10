<?php
$index = true;
include_once("header.php");
include_once("main.php");

// Récupération des statistiques globales
$stats = [
    'fournisseurs' => 0,
    'bailleurs'    => 0,
    'materiels'    => 0,
    'inventaires'  => 0,
];

try {
    $stats['fournisseurs'] = (int)$pdo->query("SELECT COUNT(*) FROM fournisseur")->fetchColumn();
    $stats['bailleurs']    = (int)$pdo->query("SELECT COUNT(*) FROM bailleur")->fetchColumn();
    $stats['materiels']    = (int)$pdo->query("SELECT COUNT(*) FROM materiel")->fetchColumn();
    $stats['inventaires']  = (int)$pdo->query("SELECT COUNT(*) FROM inventaire")->fetchColumn();
} catch (PDOException $e) {
    // En cas d'erreur, on garde les valeurs par défaut
}
?>

<!-- ====== STYLES DOCTOPLAN ====== -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
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
    overflow-x: hidden;
  }

  /* ---------- Titre de section ---------- */
  .doctoplan-page .section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 22px;
    font-weight: 800;
    color: var(--ink);
    margin: 0 0 24px;
    letter-spacing: -0.4px;
  }
  .doctoplan-page .section-title::before {
    content: "\f201";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: var(--blue-600);
    font-size: 18px;
    background: #eef4ff;
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  /* ---------- Grille stats ---------- */
  .doctoplan-page .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
  }

  .doctoplan-page .stat-card {
    background: var(--surface);
    border-radius: 20px;
    padding: 24px;
    border: 1px solid var(--line);
    box-shadow: 0 4px 14px -8px rgba(15, 40, 80, 0.15);
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .doctoplan-page .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px -20px rgba(37, 99, 235, 0.35);
    border-color: #c7d9f8;
  }

  .doctoplan-page .stat-card .icon {
    width: 52px; height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #fff;
    transition: transform 0.3s ease;
  }
  .doctoplan-page .stat-card:hover .icon {
    transform: scale(1.08) rotate(-6deg);
  }

  .doctoplan-page .stat-card.blue .icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
  .doctoplan-page .stat-card.teal .icon { background: linear-gradient(135deg, #2dd4bf, #0d9488); }
  .doctoplan-page .stat-card.violet .icon { background: linear-gradient(135deg, #a78bfa, #7c3aed); }
  .doctoplan-page .stat-card.amber .icon { background: linear-gradient(135deg, #fbbf24, #d97706); }

  .doctoplan-page .stat-card .value {
    font-size: 34px;
    font-weight: 800;
    line-height: 1;
    color: var(--ink);
    letter-spacing: -1px;
  }

  .doctoplan-page .stat-card .label {
    font-size: 14px;
    font-weight: 600;
    color: var(--muted);
  }

  /* ---------- Responsive ---------- */
  @media (max-width: 900px) {
    .doctoplan-page { padding: 20px 24px 40px; }
  }
  @media (max-width: 760px) {
    .doctoplan-page { padding: 16px 16px 32px; }
    .doctoplan-page .stats-grid { grid-template-columns: 1fr; }
  }
</style>

<!-- ====== CONTENU ====== -->
<main class="flex-shrink-0">
  <div class="doctoplan-page">

    <!-- STATISTIQUES -->
    <h2 class="section-title">Aperçu général</h2>
    <div class="stats-grid">

      <div class="stat-card blue">
        <div class="icon"><i class="fas fa-truck"></i></div>
        <div class="value"><?php echo $stats['fournisseurs']; ?></div>
        <div class="label">Fournisseur<?php echo $stats['fournisseurs'] > 1 ? 's' : ''; ?></div>
      </div>

      <div class="stat-card teal">
        <div class="icon"><i class="fas fa-landmark"></i></div>
        <div class="value"><?php echo $stats['bailleurs']; ?></div>
        <div class="label">Bailleur<?php echo $stats['bailleurs'] > 1 ? 's' : ''; ?> de fond</div>
      </div>

      <div class="stat-card violet">
        <div class="icon"><i class="fas fa-box-open"></i></div>
        <div class="value"><?php echo $stats['materiels']; ?></div>
        <div class="label">Matériel<?php echo $stats['materiels'] > 1 ? 's' : ''; ?> enregistré<?php echo $stats['materiels'] > 1 ? 's' : ''; ?></div>
      </div>

      <div class="stat-card amber">
        <div class="icon"><i class="fas fa-clipboard-list"></i></div>
        <div class="value"><?php echo $stats['inventaires']; ?></div>
        <div class="label">Mouvement<?php echo $stats['inventaires'] > 1 ? 's' : ''; ?> d'inventaire</div>
      </div>

    </div>

  </div>
</main>

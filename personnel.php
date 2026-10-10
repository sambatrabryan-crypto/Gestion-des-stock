<?php
$personnel = true;
include_once("header.php");
include_once("main.php");
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
    content: "\f0c0";
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

  /* ---------- Fil d'Ariane (info) ---------- */
  .doctoplan-page .info-banner {
    background: linear-gradient(120deg, #eff6ff 0%, #eef4ff 100%);
    border-radius: 16px;
    padding: 16px 22px;
    border: 1px solid #dbeafe;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14.5px;
    color: #1e40af;
    font-weight: 500;
  }

  .doctoplan-page .info-banner::before {
    content: "\f05a";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    color: var(--blue-600);
    font-size: 18px;
    flex-shrink: 0;
  }

  /* ---------- Grille des cartes ---------- */
  .doctoplan-page .cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
  }

  /* ---------- Carte ---------- */
  .doctoplan-page .card {
    position: relative;
    background: var(--surface);
    border-radius: 24px;
    border: 1px solid var(--line);
    box-shadow: 0 4px 14px -8px rgba(15, 40, 80, 0.15);
    overflow: hidden;
    transition: transform 0.3s cubic-bezier(.2, .8, .2, 1), box-shadow 0.3s ease, border-color 0.3s ease;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
  }

  .doctoplan-page .card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent 60%, rgba(37, 99, 235, 0.04));
    pointer-events: none;
    transition: opacity 0.3s ease;
    opacity: 0;
  }

  .doctoplan-page .card:hover {
    transform: translateY(-6px);
    box-shadow: 0 24px 50px -20px rgba(37, 99, 235, 0.35);
    border-color: #c7d9f8;
  }

  .doctoplan-page .card:hover::before {
    opacity: 1;
  }

  /* Icône principale */
  .doctoplan-page .card .icon {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 0 16px;
  }

  .doctoplan-page .card .icon i {
    width: 72px;
    height: 72px;
    border-radius: 22px;
    background: #eef4ff;
    color: var(--blue-600);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    transition: transform 0.4s cubic-bezier(.3, 1.6, .5, 1), background 0.3s ease, color 0.3s ease;
  }

  .doctoplan-page .card:hover .icon i {
    background: var(--blue-600);
    color: #fff;
    transform: scale(1.08) rotate(-6deg);
  }

  /* Titre de la carte */
  .doctoplan-page .card .card-title {
    padding: 0 24px 8px;
    font-size: 20px;
    font-weight: 800;
    color: var(--ink);
    text-align: center;
    letter-spacing: -0.4px;
    margin: 0;
  }

  /* Description */
  .doctoplan-page .card .card-desc {
    padding: 0 24px 24px;
    font-size: 14px;
    color: var(--muted);
    text-align: center;
    line-height: 1.5;
    margin: 0;
  }

  /* Pied de carte */
  .doctoplan-page .card .card-footer {
    background: #fafcff;
    border-top: 1px solid var(--line);
    padding: 16px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
  }

  .doctoplan-page .card .card-footer .link {
    font-size: 14px;
    font-weight: 700;
    color: var(--blue-600);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: gap 0.25s ease, color 0.2s ease;
  }

  .doctoplan-page .card .card-footer .arrow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #eef4ff;
    color: var(--blue-600);
    font-size: 13px;
    transition: transform 0.25s ease, background 0.25s ease, color 0.25s ease;
  }

  .doctoplan-page .card:hover .card-footer .link {
    gap: 12px;
  }

  .doctoplan-page .card:hover .card-footer .arrow {
    background: var(--blue-600);
    color: #fff;
    transform: translateX(4px);
  }

  /* ---------- Responsive ---------- */
  @media (max-width: 900px) {
    .doctoplan-page { padding: 20px 24px 40px; }
  }

  @media (max-width: 760px) {
    .doctoplan-page { padding: 16px 16px 32px; }
    .doctoplan-page .page-header h1 { font-size: 22px; }
    .doctoplan-page .cards-grid { grid-template-columns: 1fr; }
  }
</style>

<!-- ====== CONTENU DANS <main> ====== -->
<main class="flex-shrink-0">
  <div class="doctoplan-page">

    <!-- En-tête -->
    <div class="page-header">
      <h1>Les Personnels</h1>
    </div>

    <!-- Info banner -->
    <div class="info-banner">
      Cliquez sur une carte pour gérer les personnels correspondants (ajout, modification, suppression).
    </div>

    <!-- Grille des 3 cartes -->
    <div class="cards-grid">

      <!-- Carte Détenteurs -->
      <a href="detenteur.php" class="card">
        <div class="icon">
          <i class="fa fa-fw fa-user-shield"></i>
        </div>
        <h3 class="card-title">Détenteurs</h3>
        <p class="card-desc">Gérez les détenteurs de matériels et leurs informations.</p>
        <div class="card-footer">
          <span class="link">Voir les détails</span>
          <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </div>
      </a>

      <!-- Carte Ordonnateurs -->
      <a href="ordonnateur.php" class="card">
        <div class="icon">
          <i class="fa fa-fw fa-user-tie"></i>
        </div>
        <h3 class="card-title">Ordonnateurs</h3>
        <p class="card-desc">Accédez à la liste des ordonnateurs et à leurs coordonnées.</p>
        <div class="card-footer">
          <span class="link">Voir les détails</span>
          <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </div>
      </a>

      <!-- Carte Dépositaires -->
      <a href="depositaire.php" class="card">
        <div class="icon">
          <i class="fa fa-fw fa-user-plus"></i>
        </div>
        <h3 class="card-title">Dépositaires</h3>
        <p class="card-desc">Consultez et gérez les dépositaires enregistrés.</p>
        <div class="card-footer">
          <span class="link">Voir les détails</span>
          <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </div>
      </a>

    </div>

  </div>
</main>

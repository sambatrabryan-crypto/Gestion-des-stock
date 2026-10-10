<?php
// Inclure le fichier de configuration de la base de données
include_once("main.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $id = $_POST['id'];

    // Préparer et exécuter la requête de suppression
    $query = "DELETE FROM detenteur WHERE Id = :id";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->execute(['id' => $id]);

    // Rediriger vers la liste des fournisseurs après suppression
    header("Location: detenteur.php");
    exit();
} else {
    // Gérer les erreurs si l'ID est manquant ou incorrect
    echo "<style>
            .doctoplan-error-card {
                background: #ffffff;
                border-radius: 20px;
                padding: 32px 28px;
                border: 1px solid #edf2f7;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
                max-width: 520px;
                margin: 40px auto;
                text-align: center;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                color: #0b1e33;
            }
            .doctoplan-error-card .icon {
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
            .doctoplan-error-card h2 {
                font-size: 18px;
                font-weight: 700;
                color: #0b1e33;
                margin-bottom: 10px;
            }
            .doctoplan-error-card p {
                font-size: 14px;
                color: #475569;
                line-height: 1.5;
                margin-bottom: 20px;
            }
            .doctoplan-error-card a {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: #2563eb;
                color: white;
                padding: 10px 24px;
                border-radius: 40px;
                font-weight: 600;
                font-size: 14px;
                text-decoration: none;
                transition: 0.15s;
                box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
            }
            .doctoplan-error-card a:hover {
                background: #1d4ed8;
                transform: scale(0.98);
                color: white;
            }
          </style>";

    echo "<div class='doctoplan-error-card'>";
    echo "  <div class='icon'>✗</div>";
    echo "  <h2>Erreur : ID non spécifié</h2>";
    echo "  <p>L'identifiant du détenteur à supprimer est manquant ou invalide.</p>";
    echo "  <a href='detenteur.php'>← Retour à la liste</a>";
    echo "</div>";
}
?>
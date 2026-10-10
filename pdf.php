<?php
// Inclure la bibliothèque FPDF
require('D:\LOGICIEL\fpdf186\fpdf.php');
include_once("main.php"); // Connexion à la base de données

// Vérifier si un ID a été soumis via POST
if (isset($_POST['inputquantite']) && !empty($_POST['inputquantite'])) {
    $inventaireId = intval($_POST['inputquantite']);
    
    // Requête pour récupérer les données de l'inventaire, du fournisseur, du detenteur, du depositaire, du bailleur, du pcop
    $query = "
    SELECT 
    inventaire.Id,
    materiel.Designation AS MaterielDesignation,
    fournisseur.Nom AS FournisseurNom,
    inventaire.Datentree AS DateEntree,
    inventaire.Quantitentre AS QuantiteEntree,
    inventaire.Datesort AS DateSortie,
    inventaire.Quantite AS QuantiteSortie,
    (inventaire.Quantitentre - inventaire.Quantite) AS Reste,
    detenteur.Nom AS DetenteurNom,
    detenteur.Fonction AS DetenteurFonction,
    detenteur.Direction AS DetenteurDirection,
    detenteur.Adresse AS DetenteurAdresse,  -- Ajout de l'adresse
    depositaire.Nom AS DepositaireNom,
    depositaire.Fonction AS DepositaireFonction,
    depositaire.Direction AS DepositaireDirection,
    ordonnateur.Nom AS OrdonnateurNom,
    bailleur.Nom AS BailleurNom,
    pcop.Compte AS PcopCompte,
    pcop.Designe AS PcopDesigne
FROM inventaire
JOIN stock ON inventaire.Designation = stock.Id
JOIN materiel ON stock.Designation = materiel.Id
JOIN fournisseur ON materiel.Fournisseur = fournisseur.Id
JOIN detenteur ON inventaire.Detenteur = detenteur.Id
JOIN depositaire ON inventaire.Depositaire = depositaire.Id
JOIN ordonnateur ON inventaire.Ordonnateur = ordonnateur.Id
JOIN bailleur ON materiel.Bailleur = bailleur.Id
JOIN pcop ON materiel.Pcop = pcop.Id
WHERE inventaire.Id = :inventaireId
";
    $pdostmt = $pdo->prepare($query);
    $pdostmt->bindParam(':inventaireId', $inventaireId, PDO::PARAM_INT);
    $pdostmt->execute();

    // Récupérer les données
    $result = $pdostmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // Créer une instance de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Affichage des informations Bailleur et PCOP avant le titre
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, mb_convert_encoding('BUDGET: ' . $result["BailleurNom"], 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        
        // Affichage du Compte PCOP et de sa Désignation entre guillemets
        $pdf->Cell(0, 10, mb_convert_encoding('Compte : ' . $result["PcopCompte"] . ' "' . $result["PcopDesigne"] . '"', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        
        $pdf->Ln(5); // Espacement avant le titre

        // Titre du PDF - "INVENTAIRE DE MATERIEL" en gras
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, mb_convert_encoding('INVENTAIRE DE MATERIEL', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
          // Saut de ligne entre les titres

        // Sous-titres - Texte normal pour "REMIS A UN DETENTEUR EFFECTIF" et "(BON DE DETENTEUR EFFECTIF)"
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, mb_convert_encoding('REMIS A UN DETENTEUR EFFECTIF', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        // Saut de ligne avant le dernier titre
        $pdf->Cell(0, 10, mb_convert_encoding('(BON DE DETENTEUR EFFECTIF)', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(10); // Un peu plus d'espace avant le reste du contenu

        // Position à gauche pour les informations Detenteur et Depositaire
        $pdf->SetX(10);  // Positionner à 10 mm du bord gauche pour éviter trop d'écart

        // Ajouter les informations du Detenteur et Depositaire sur la même ligne


        $pdf->SetX(10);  // Positionner à 10 mm du bord gauche
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, mb_convert_encoding('Nom, grade et fonctions du dépositaire-comptable: '.  $result["DepositaireNom"] . ', ' . $result["DepositaireDirection"] . ', ' . $result["DepositaireFonction"], 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(5);  // Espacement après le Depositaire
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, mb_convert_encoding('Nom, grade, fonctions et adresse exacte du détenteur: '.  $result["DetenteurNom"] . ', ' . $result["DetenteurDirection"] . ', ' . $result["DetenteurFonction"] . ', ' . $result["DetenteurAdresse"], 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(5);  // Espacement après le Detenteur
        
        

        // Ajouter un saut de ligne supplémentaire pour créer un espace avant le tableau
        $pdf->Ln(10);  // Espacement avant le tableau
        
        // Ajouter un tableau de données
        $pdf->SetFont('Arial', 'B', 12);
    
        $pdf->Cell(50, 10, mb_convert_encoding('Désignation-Matériel', 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(40, 10, mb_convert_encoding('Fournisseur', 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(40, 10, mb_convert_encoding('Date d\'entrée', 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(30, 10, mb_convert_encoding('Quantité entrée', 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(40, 10, mb_convert_encoding('Date de sortie', 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(30, 10, mb_convert_encoding('Quantité sortie', 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(30, 10, mb_convert_encoding('Reste', 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Ln();  // Saut de ligne après l'en-tête du tableau
        
        // Ajouter les données récupérées dans le tableau
        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(50, 10, mb_convert_encoding($result["MaterielDesignation"], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(40, 10, mb_convert_encoding($result["FournisseurNom"], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(40, 10, mb_convert_encoding($result["DateEntree"], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(30, 10, mb_convert_encoding($result["QuantiteEntree"], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(40, 10, mb_convert_encoding($result["DateSortie"], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(30, 10, mb_convert_encoding($result["QuantiteSortie"], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(30, 10, mb_convert_encoding($result["Reste"], 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Ln();

        $pdf->Ln(10);  // Espacement avant les noms

      // Positionner le Detenteur à gauche et le Depositaire à droite sur la même ligne
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(95, 10, mb_convert_encoding('Le Detenteur ', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');  // Détenteur à gauche

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(95, 10, mb_convert_encoding('Le Depositaire Comptable ', 'ISO-8859-1', 'UTF-8'), 0, 0, 'R');  // Dépositaire à droite
$pdf->Ln(20);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, mb_convert_encoding($result["DetenteurNom"], 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');  // Nom du détenteur à gauche

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, mb_convert_encoding($result["DepositaireNom"], 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');  // Nom du dépositaire à droite

$pdf->Ln(10);  // Espacement après les deux informations



        // Enregistrer le PDF sur le serveur
        $pdfFileName = 'inventaire_' . $inventaireId . '.pdf';
        $pdf->Output('F', 'pdf/' . $pdfFileName); // 'F' pour sauvegarder le fichier sur le serveur

        // Afficher un message indiquant que le PDF a été généré avec succès
        echo "<script>alert('Le PDF a été généré avec succès.');</script>";
    } else {
        echo "Aucun inventaire trouvé pour l'ID: " . htmlspecialchars($inventaireId);
    }
} else {
    echo "ID d'inventaire manquant.";
}
?>

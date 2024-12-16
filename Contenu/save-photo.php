<?php
// Vérifie si une image a été envoyée
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['image'])) {
    $imageData = $data['image'];

    // Extraction des données de l'image (base64)
    $imageData = explode(',', $imageData)[1];
    $decodedImage = base64_decode($imageData);

    // Chemin de sauvegarde (dans le dossier Resultat)
    $directory = __DIR__ . '/Resultat';
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true); // Crée le dossier si nécessaire
    }
    $filePath = $directory . '/photo_' . time() . '.png';

    // Écrit le fichier
    if (file_put_contents($filePath, $decodedImage)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'path' => $filePath]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Impossible de sauvegarder l\'image.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Aucune image reçue.']);
}

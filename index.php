<?php
require_once 'h5p-config.php';
require_once 'db.php';

$contentId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT id, params, embed_type FROM h5p_contents WHERE id = ?");
$stmt->execute([$contentId]);
$content = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$content) {
    die("Contenido no encontrado.");
}

$H5P_Core->h5pF->setLibraryFoldername('H5P.InteractiveVideo-1.22'); // Ejemplo de tipo de contenido
$H5P_Core->h5pF->setContentId($contentId);

$assets = $H5P_Core->getDependenciesFiles($content);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visualizar H5P</title>
    <?php foreach ($assets['scripts'] as $script): ?>
        <script src="<?= $script ?>"></script>
    <?php endforeach; ?>
    <?php foreach ($assets['styles'] as $style): ?>
        <link rel="stylesheet" href="<?= $style ?>" />
    <?php endforeach; ?>
</head>
<body>
<div class="h5p-content" data-content-id="<?= $contentId ?>">
    <?= $H5P_Core->embed($content['id']) ?>
</div>
<script>
    H5PIntegration = <?= json_encode([
        'baseUrl' => '',
        'url' => "content/{$contentId}",
        'contents' => [
            "cid-{$contentId}" => [
                'library' => 'H5P.InteractiveVideo 1.22',
                'jsonContent' => json_decode($content['params'], true),
                'contentUrl' => "content/{$contentId}",
                'contentUserData' => [],
            ]
        ]
    ]) ?>;
</script>
</body>
</html>

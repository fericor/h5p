<?php

require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'MyH5PIntegration.php'; // Carga nuestra implementación

// ID del contenido H5P que queremos mostrar (debe existir en la carpeta /content/1/)
$contentId = 1;

// Instanciar nuestra implementación y el Core de H5P
$h5pIntegration = new \MyH5PApp\MyH5PIntegration();
$h5pCore = new \H5PCore($h5pIntegration, $h5p_config['upload_path'], $h5p_config['library_path'], $h5p_config['baseUrl']);
$h5pCore->aggregateAssets = true; // Agregar JS y CSS para mejor rendimiento (opcional)

// --- Obtener datos para el frontend ---

// Cargar los datos del contenido (usa $h5pIntegration->loadContent internamente)
// Nota: H5PCore espera que loadContent devuelva un array con claves como
// 'id', 'params', 'embedType', 'library' (array), 'filtered', 'disable'
$contentData = $h5pCore->loadContent($contentId);

if (!$contentData) {
    die("Error: No se pudo cargar el contenido H5P con ID: " . $contentId);
}

// Obtener los archivos JS y CSS necesarios para este contenido
// Esto calcula todas las dependencias (JS/CSS del core + JS/CSS de las librerías usadas)
$preloadedDependencies = $h5pCore->loadContentDependencies($contentId, 'preloaded');
$files = $h5pCore->getDependenciesFiles($preloadedDependencies, H5P_RELATIVE_PATH); // Usa URL relativa

// Construir la configuración H5P para el JavaScript del frontend
$h5pJson = $h5pCore->getH5PJson($contentId); // Obtiene el JSON combinado para H5P.init

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejemplo H5P</title>

    <?php // Incluir los CSS agregados ?>
    <?php foreach ($files['styles'] as $style): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($style); ?>">
    <?php endforeach; ?>

    <style>
        /* Estilos básicos para el contenedor */
        .h5p-container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Contenido H5P</h1>

    <div class="h5p-container">
        <?php // El div donde H5P se renderizará ?>
        <div class="h5p-content" data-content-id="<?php echo $contentId; ?>">
            Cargando H5P...
        </div>
    </div>

    <?php // Incluir jQuery (H5P aún puede depender de él en algunas librerías) ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <?php // O usa tu propia copia ?>

    <?php // Incluir los JS agregados (el core y las librerías) ?>
    <?php foreach ($files['scripts'] as $script): ?>
        <script src="<?php echo htmlspecialchars($script); ?>"></script>
    <?php endforeach; ?>

    <script>
        (function($) {
            $(function() {
                if (window.H5P && window.H5P.externalDispatcher) {
                    // Configuración global de H5P (URLs, etc.)
                    // H5PIntegrationSettings se genera con getH5PJson
                    window.H5PIntegration = <?php echo $h5pJson; ?>;

                    // Inicializar H5P en el contenedor específico
                     const contentDiv = $('.h5p-content[data-content-id="<?php echo $contentId; ?>"]');
                     if (contentDiv.length) {
                         H5P.init(contentDiv.get(0)); // Pasar el elemento DOM
                     } else {
                        console.error("Contenedor H5P no encontrado para ID:", <?php echo $contentId; ?>);
                     }

                     // Opcional: Escuchar eventos de H5P (ej. xAPI)
                     H5P.externalDispatcher.on('xAPI', function (event) {
                         console.log('Evento xAPI recibido:', event.data.statement);
                         // Aquí podrías enviar el statement a tu LRS (Learning Record Store)
                     });

                } else {
                     console.error("H5P no está definido o H5P.externalDispatcher no está disponible.");
                     $('.h5p-content').text('Error al cargar H5P.');
                }
            });
        })(jQuery);
    </script>

</body>
</html>
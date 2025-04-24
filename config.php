<?php

define('H5P_ROOT_PATH', __DIR__);
define('H5P_BASE_URL', '/tu-proyecto-h5p'); // La URL base de tu proyecto

// Rutas a las carpetas (relativas a H5P_ROOT_PATH o absolutas)
define('H5P_CORE_PATH', H5P_ROOT_PATH . '/vendor/h5p/h5p-core');
define('H5P_EDITOR_PATH', H5P_ROOT_PATH . '/vendor/h5p/h5p-editor'); // Si usas editor
define('H5P_CONTENT_PATH', H5P_ROOT_PATH . '/content');
define('H5P_LIBRARY_PATH', H5P_ROOT_PATH . '/libraries');
define('H5P_TEMP_PATH', H5P_ROOT_PATH . '/temp');

// URLs correspondientes a las carpetas (importante para JS/CSS)
define('H5P_CORE_URL', H5P_BASE_URL . '/vendor/h5p/h5p-core');
define('H5P_EDITOR_URL', H5P_BASE_URL . '/vendor/h5p/h5p-editor'); // Si usas editor
define('H5P_CONTENT_URL', H5P_BASE_URL . '/content');
define('H5P_LIBRARY_URL', H5P_BASE_URL . '/libraries');

// Configuración simple (sin base de datos en este ejemplo)
$h5p_config = [
    'baseUrl' => H5P_BASE_URL,
    'url' => H5P_BASE_URL, // A veces usado como alias
    'core' => [
        'path' => H5P_CORE_PATH,
        'url' => H5P_CORE_URL
    ],
     'editor' => [ // Si usas editor
         'path' => H5P_EDITOR_PATH,
         'url' => H5P_EDITOR_URL
     ],
    'upload_path' => H5P_CONTENT_PATH, // O H5P_TEMP_PATH para subidas temporales
    'library_path' => H5P_LIBRARY_PATH,
    'content_path' => H5P_CONTENT_PATH, // Donde se guardan los contenidos
    'temporary_path' => H5P_TEMP_PATH,
    'content_url' => H5P_CONTENT_URL,
    'library_url' => H5P_LIBRARY_URL,
    'ajax' => [
        'setFinished' => H5P_BASE_URL . '/ajax.php?action=finish', // Necesitarías crear ajax.php
        'contentUserData' => H5P_BASE_URL . '/ajax.php?action=contentUserData&content_id=:contentId&data_type=:dataType&sub_content_id=:subContentId' // Necesitarías crear ajax.php
    ],
    'saveFreq' => false, // Desactivar guardado automático de estado para este ejemplo simple
    'hubIsEnabled' => false, // Desactivar conexión al Hub H5P para este ejemplo
    'reportingIsEnabled' => false, // Desactivar reportes xAPI
    // 'storage' => ['h5p' => 'tu_implementacion_storage'] // Aquí iría tu clase de almacenamiento si usaras DB
    // 'framework' => 'MiFramework' // Nombre opcional
];

// Simplificación: Copiar los assets JS/CSS del core a una ubicación accesible por URL
// En una implementación real, esto se haría con un gestor de assets o enlaces simbólicos.
// Asegúrate de que las carpetas h5p-core/ y h5p-editor/ (si la usas) existan en la raíz
// y contengan los archivos JS/CSS de vendor/h5p/h5p-core/js, vendor/h5p/h5p-core/styles, etc.
// ¡ESTO ES SOLO PARA SIMPLIFICAR EL EJEMPLO!
// $core_js_path = H5P_ROOT_PATH . '/h5p-core/js';
// if (!is_dir($core_js_path)) mkdir($core_js_path, 0777, true);
// foreach(glob(H5P_CORE_PATH . '/js/*.js') as $file) copy($file, $core_js_path . '/' . basename($file));
// Similar para CSS y para el editor si lo usas.
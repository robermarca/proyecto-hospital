<?php
require_once __DIR__ . '/auth/auth.php';
require_once __DIR__ . '/../app/config/conexion.php';

$idTraslado = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "
SELECT
    uo.nodo_mapa AS origen_nodo,
    ud.nodo_mapa AS destino_nodo
FROM traslados t
INNER JOIN ubicaciones uo
    ON t.id_origen = uo.id_ubicacion
INNER JOIN ubicaciones ud
    ON t.id_destino = ud.id_ubicacion
WHERE t.id_traslado = ?
";

$stmt = $conexion->prepare($sql);
$stmt->execute([$idTraslado]);
$datosRuta = $stmt->fetch(PDO::FETCH_ASSOC);

$origenNodo = $datosRuta['origen_nodo'] ?? '';
$destinoNodo = $datosRuta['destino_nodo'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ver ruta - CelCare</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body class="dashboard-page">

<div class="ruta-wrapper">

    <h1 class="ruta-titulo">
        Ruta hospitalaria
    </h1>

    <div class="selector-plantas">

        <button
            class="btn-planta btn-pb"
            onclick="cambiarPlanta(0)">
            Planta Baja
        </button>

        <button
            class="btn-planta btn-p1"
            onclick="cambiarPlanta(1)">
            Planta 1
        </button>

        <button
            class="btn-planta btn-p2"
            onclick="cambiarPlanta(2)">
            Planta 2
        </button>

    </div>

    <div class="mapa-container">

        <img
            src="imagenes/rutas/planta_baja.png"
            id="mapaHospital"
            alt="Plano hospitalario"
        >

        <svg id="capaRutas"></svg>

    </div>

    <div id="panelCambioPlanta" class="panel-cambio-planta oculto">

        <p id="textoCambioPlanta">
            La ruta continúa en otra planta.
        </p>

        <button
            id="btnContinuarRuta"
            class="btn-continuar-ruta"
            onclick="continuarRuta()">
            Continuar ruta
        </button>

    </div>

    <button
        id="btnRetrocederRuta"
        class="btn-continuar-ruta oculto"
        onclick="retrocederRuta()">
        Volver a planta anterior
    </button>

    <a href="listar_traslados.php" class="btn-volver-ruta">
        Volver al listado
    </a>

</div>

<script>
    const ORIGEN_RUTA = <?= json_encode($origenNodo) ?>;
    const DESTINO_RUTA = <?= json_encode($destinoNodo) ?>;
</script>

<script src="rutas_js/nodos.js"></script>
<script src="rutas_js/rutas.js"></script>
<script src="rutas_js/mapa.js"></script>

</body>
</html>
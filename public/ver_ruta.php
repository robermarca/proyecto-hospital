<?php
require_once __DIR__ . '/auth/auth.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Ver ruta - CelCare
    </title>

    <link rel="stylesheet" href="styles.css">

</head>

<body class="dashboard-page">

<div class="ruta-wrapper">

    <h1 class="ruta-titulo">
        Ruta hospitalaria
    </h1>

    <div class="selector-plantas">

        <button
            class="btn-planta btn-pb" onclick="cambiarPlanta(0)">
            Planta Baja
        </button>

        <button class="btn-planta btn-p1" onclick="cambiarPlanta(1)">
            Planta 1
        </button>

        <button class="btn-planta btn-p2" onclick="cambiarPlanta(2)">
            Planta 2
        </button>

    </div>

    <div class="mapa-container">

        <img
            src="imagenes/rutas/planta_baja.png"
            id="mapaHospital"
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

</div>

<script src="rutas_js/nodos.js"></script>
<script src="rutas_js/rutas.js"></script>
<script src="rutas_js/mapa.js"></script>

</body>
</html>
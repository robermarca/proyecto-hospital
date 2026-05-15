let plantaActual = 1;

function cambiarPlanta(planta){

    const mapa = document.getElementById("mapaHospital");

    if(planta === 0){
        mapa.src = "imagenes/rutas/planta_baja.png";
    }

    if(planta === 1){
        mapa.src = "imagenes/rutas/planta1.png";
    }

    if(planta === 2){
        mapa.src = "imagenes/rutas/planta2.png";
    }

    /* BOTON ACTIVO */

    document
        .querySelectorAll(".btn-planta")
        .forEach(btn =>
            btn.classList.remove("activa")
        );

    if(planta === 0){
        document
            .querySelector(".btn-pb")
            .classList.add("activa");
    }

    if(planta === 1){
        document
            .querySelector(".btn-p1")
            .classList.add("activa");
    }

    if(planta === 2){
        document
            .querySelector(".btn-p2")
            .classList.add("activa");
    }

    plantaActual = planta;

    limpiarSVG();
}

function limpiarSVG(){

    document.getElementById("capaRutas")
        .innerHTML = "";
}
/* CAMBIO DE PLANTA */

let siguientePlanta = null;

function mostrarCambioPlanta(plantaDestino) {

    siguientePlanta = plantaDestino;

    const panel =
        document.getElementById("panelCambioPlanta");

    const texto =
        document.getElementById("textoCambioPlanta");

    let nombrePlanta = "";

    if(plantaDestino === 0){
        nombrePlanta = "Planta Baja";
    }

    else if(plantaDestino === 1){
        nombrePlanta = "Planta 1";
    }

    else if(plantaDestino === 2){
        nombrePlanta = "Planta 2";
    }

    texto.textContent =
        "La ruta continúa en " + nombrePlanta;

    panel.classList.remove("oculto");
}

function continuarRuta(){

    if(siguientePlanta === null){
        return;
    }


    document
        .getElementById("panelCambioPlanta")
        .classList.add("oculto");

    siguientePlanta = null;

    dibujarTramoActual();
}

/* RETROCEDER RUTA */

function retrocederRuta(){

    if(indiceTramoActual <= 0){
        return;
    }

    indiceTramoActual--;

    while(
        indiceTramoActual > 0 &&
        nodos[
            rutaCompleta[indiceTramoActual]
        ].planta ===
        nodos[
            rutaCompleta[indiceTramoActual - 1]
        ].planta
    ){
        indiceTramoActual--;
    }

    document
        .getElementById("panelCambioPlanta")
        .classList.add("oculto");

    dibujarTramoActual();
}
const contenedorMapa =
    document.querySelector(".mapa-container");

contenedorMapa.addEventListener("click", function(e){

    const rect =
        contenedorMapa.getBoundingClientRect();

    const x =
        ((e.clientX - rect.left) / rect.width) * 100;

    const y =
        ((e.clientY - rect.top) / rect.height) * 100;

    console.log(
        "x:",
        x.toFixed(2) + ",",
        "y:",
        y.toFixed(2) + ","
    );
});

/* DIBUJAR LINEAS SVG */

function dibujarLinea(nodo1, nodo2){

    const svg =
        document.getElementById("capaRutas");

    const linea =
        document.createElementNS(
            "http://www.w3.org/2000/svg",
            "line"
        );

    linea.setAttribute("x1", nodo1.x + "%");
    linea.setAttribute("y1", nodo1.y + "%");

    linea.setAttribute("x2", nodo2.x + "%");
    linea.setAttribute("y2", nodo2.y + "%");

    linea.setAttribute("stroke", "#ff3b30");
    linea.setAttribute("stroke-width", "9");
    linea.setAttribute("stroke-linecap", "round");

    svg.appendChild(linea);
}
/* DIBUJAR CIRCULO */

function dibujarCirculo(nodo, color){

    const svg =
        document.getElementById("capaRutas");

    const circulo =
        document.createElementNS(
            "http://www.w3.org/2000/svg",
            "circle"
        );

    circulo.setAttribute("cx", nodo.x + "%");
    circulo.setAttribute("cy", nodo.y + "%");

    circulo.setAttribute("r", "12");
    circulo.setAttribute("fill", color);
    circulo.setAttribute("stroke", "white");
    circulo.setAttribute("stroke-width", "4");

    svg.appendChild(circulo);
}

/* DIBUJAR RUTA */

function dibujarRuta(listaNodos){

    limpiarSVG();

    for(let i = 0; i < listaNodos.length - 1; i++){

        const nodoActual =
            nodos[listaNodos[i]];

        const nodoSiguiente =
            nodos[listaNodos[i + 1]];

        dibujarLinea(
            nodoActual,
            nodoSiguiente
        );
    }


    /* ORIGEN */

    dibujarCirculo(
        nodos[listaNodos[0]],
        "#00ff66"
    );

    /* DESTINO */

    dibujarCirculo(
        nodos[listaNodos[
            listaNodos.length - 1
        ]],
        "#ff2d2d"
    );
}

/* CALCULAR RUTA */

function calcularRuta(inicio, destino){

    const cola = [[inicio]];

    const visitados = [];

    while(cola.length > 0){

        const rutaActual = cola.shift();

        const nodoActual =
            rutaActual[rutaActual.length - 1];

        if(nodoActual === destino){
            return rutaActual;
        }

        if(!visitados.includes(nodoActual)){

            visitados.push(nodoActual);

            const vecinos =
                conexiones[nodoActual];

            for(let vecino of vecinos){

                const nuevaRuta =
                    [...rutaActual, vecino];

                cola.push(nuevaRuta);
            }
        }
    }

    return null;
}

let rutaCompleta = [];
let indiceTramoActual = 0;

function iniciarRuta(origen, destino){

    rutaCompleta = calcularRuta(origen, destino);

    if(!rutaCompleta){
        alert("No se ha encontrado una ruta para este traslado.");
        return;
    }

    indiceTramoActual = 0;

    console.log(rutaCompleta);

    dibujarTramoActual();
}
function dibujarTramoActual(){


    const inicio = indiceTramoActual;

    const btnRetroceder =
        document.getElementById("btnRetrocederRuta");

    if(inicio > 0){
        btnRetroceder.classList.remove("oculto");
    }else{
        btnRetroceder.classList.add("oculto");
    }

    const plantaTramo =
        nodos[rutaCompleta[inicio]].planta;

    cambiarPlanta(plantaTramo);

    const tramo = [
        rutaCompleta[inicio]
    ];

    let i = inicio + 1;

    while(
        i < rutaCompleta.length &&
        nodos[rutaCompleta[i]].planta === plantaTramo
    ){
        tramo.push(rutaCompleta[i]);
        i++;
    }

    dibujarRuta(tramo);

    if(i < rutaCompleta.length){

        indiceTramoActual = i;

        const plantaSiguiente =
            nodos[rutaCompleta[i]].planta;

        mostrarCambioPlanta(plantaSiguiente);
    }
}


if(ORIGEN_RUTA && DESTINO_RUTA){

    iniciarRuta(
        ORIGEN_RUTA,
        DESTINO_RUTA
    );
}
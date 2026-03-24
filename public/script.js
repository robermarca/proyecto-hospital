function activarAutocomplete(inputId, listaId, hiddenId, datos) {

    const input = document.getElementById(inputId);
    const lista = document.getElementById(listaId);
    const hidden = document.getElementById(hiddenId);

    if (!input || !lista || !hidden) return;

    input.addEventListener("input", function () {

        const texto = this.value.toLowerCase().trim();

        lista.innerHTML = "";
        hidden.value = "";

        if (texto.length < 1) return;

        const resultados = datos.filter(item =>
            item.nombre.toLowerCase().includes(texto)
        );

        resultados.forEach(item => {

            const opcion = document.createElement("div");
            opcion.textContent = item.nombre;
            opcion.classList.add("opcion-sugerencia");

            opcion.addEventListener("click", function () {
                input.value = item.nombre;
                hidden.value = item.id;
                lista.innerHTML = "";
            });

            lista.appendChild(opcion);
        });
    });
}

activarAutocomplete("buscar_paciente", "lista_pacientes", "id_paciente", pacientes);
activarAutocomplete("buscar_origen", "lista_origen", "id_origen", ubicaciones);
activarAutocomplete("buscar_destino", "lista_destino", "id_destino", ubicaciones);

const form = document.querySelector("form");
const origenSelect = document.getElementById("id_origen");
const destinoSelect = document.getElementById("id_destino");

form.addEventListener("submit", function(e) {
    const origen = origenSelect.value;
    const destino = destinoSelect.value;

    if (origen && destino && origen === destino) {
        alert("El origen y el destino no pueden ser el mismo.");
        e.preventDefault();
    }
});
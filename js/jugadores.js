const cargarEquiposSelect = async () => {
    try {
        let datos = new FormData();
        datos.append("action", "cargarEquipos");
        const respuesta = await fetch("php/crud.php", { method: 'POST', body: datos });
        let equipos = await respuesta.json();
        let selectEquipo = ``;

        equipos.map(equipo => {
            selectEquipo += `<option value="${equipo.idequipo}">${equipo.nombre}</option>`;
        });

        document.querySelector("#equipoJugador").innerHTML = selectEquipo;
        document.querySelector("#eeequipoJugador").innerHTML = selectEquipo;
    } catch (error) {
        //Swal.fire('Error', 'Error al cargar los equipos', 'error');
    }
};

const registrarJ = async () => {
    let nombre = document.getElementById("nombre").value;
    let edad = document.getElementById("edad").value;
    let pais = document.getElementById("pais").value;
    let foto = document.getElementById("foto").files[0];
    let idequipo = document.getElementById("equipoJugador").value;

    if (!nombre || !edad || !pais || !idequipo) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'error');
        return;
    }

    let datos = new FormData();
    datos.append("nombre", nombre);
    datos.append("edad", edad);
    datos.append("pais", pais);
    datos.append("foto",foto)
    datos.append("idequipo", idequipo);
    datos.append("action", "agregarJugador");

    try {
        let respuesta = await fetch("php/crud.php", { method: 'POST', body: datos });
        let json = await respuesta.json();
        if (json.success === true) {
            Swal.fire('Éxito', 'Jugador agregado correctamente', 'success');
            limpiar();
        } else {
            Swal.fire('Error', json.mensaje, 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Error al guardar el jugador', 'error');
    }
};

const limpiar = () =>{
    let nombre = document.getElementById("nombre");
    let edad = document.getElementById("edad");
    let pais = document.getElementById("pais");
    let foto = document.getElementById("foto");
    let idequipo = document.getElementById("equipoJugador");

    nombre.value="";
    edad.value="";
    pais.value="";
    foto.value="";
    idequipo="America"
}

cargarEquiposSelect();

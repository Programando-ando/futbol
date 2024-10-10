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
        document.querySelector("#eequipoJugador").innerHTML = selectEquipo;
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
    datos.append("foto", foto);
    datos.append("idequipo", idequipo);
    datos.append("action", "agregarJugador");

    try {
        let respuesta = await fetch("php/crud.php", { method: 'POST', body: datos });
        let json = await respuesta.json();
        if (json.success === true) {
            Swal.fire('Éxito', 'Jugador agregado correctamente', 'success');
            cargarJugador();
            limpiar();
        } else {
            Swal.fire('Error', json.mensaje, 'error');
        }
    } catch (error) {
        Swal.fire('Error', 'Error al guardar el jugador', 'error');
    }

    cargarJugador();
};


const cargarJugador = async (idequipo) => {
    let datos = new FormData();
    datos.append("action", "cargarJugador");
    datos.append("idequipo", idequipo);

    const respuesta = await fetch("php/crud.php", {method: 'POST',body: datos});
    let json = await respuesta.json();

    document.getElementById("jugador").innerHTML = "";

    json.map(jugador => {
        const fotoJugador = jugador.foto ? `jugador/${jugador.foto}?v=${new Date().getTime()}` : 'img/images.jpeg';
    
        let tablaHTML = `
            <tr>
                <td><img src="${fotoJugador}" alt="${jugador.nombre}" width="100px" height="100px"></td>
                <td>${jugador.nombre}</td>
                <td>${jugador.edad}</td>
                <td>${jugador.pais}</td>
                <td>${jugador.nombre_equipo}</td>
                <td>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editarJ" onclick="mostrarE(${jugador.id_j}, ${jugador.id_equipo})">
                        Editar
                    </button>
                    <button class="btn btn-danger" onclick="eliminar(${jugador.id_j})">Eliminar</button>
                </td>
            </tr>
        `;
        document.getElementById("jugador").innerHTML += tablaHTML;
    });
    
    
};



const eliminar = async (idequipo) => {
    Swal.fire({
        title: "¿Estás seguro de eliminar este equipo?",
        showDenyButton: true,
        confirmButtonText: "Sí, estoy seguro",
        confirmButtonColor: '#20c997',
        denyButtonText: "No estoy seguro"
    }).then(async (result) => {
        if (result.isConfirmed) {
            let contactoid = new FormData();
            contactoid.append('id', idequipo);
            contactoid.append('action', 'deleteJugador');

            let respuesta = await fetch("php/crud.php", {method: 'POST', body: contactoid});
            let json = await respuesta.json();

            if (json.success === true) {
                Swal.fire({title: "¡Se eliminó con éxito!", text: json.mensaje, icon: "success"});
                cargarJugador();
            } else {
                Swal.fire({title: "ERROR", text: json.mensaje, icon: "error"});
            }
        }
    });
};

const mostrarE = async (id_j) => {
    let datos = new FormData();
    datos.append("id", id_j);
    datos.append("action", "find2");

    const respuesta = await fetch("php/crud.php", {method: 'POST', body: datos});
    let json = await respuesta.json();

    if (json.success) {
        document.getElementById('id').value = json.id_j;
        document.getElementById("enombre").value = json.nombre_jugador;
        document.getElementById("eedad").value = json.edad;
        document.getElementById("epais").value = json.pais;
        document.getElementById('file-name').textContent = json.foto;
        await cargarEquiposSelect(json.id_equipo);
        let selectEquipo = document.getElementById('eequipoJugador');
        if (selectEquipo) {
            selectEquipo.value = json.id_equipo;
        } else {
            console.error('El elemento select con ID "eeequipoJugador" no existe en el DOM.');
        }
    } else {
        console.error(json.mensaje); 
    }
    
    
};

const actualizarEquipo = async () => {
    var id = document.querySelector("#id").value;
    var nombre = document.querySelector("#enombre").value;
    var edad = document.querySelector("#eedad").value;
    var pais = document.querySelector("#epais").value;
    var equipo = document.querySelector("#eequipoJugador").value;
    var fotoInput = document.querySelector("#efoto");

    if (nombre.trim() === "" || edad.trim() === "" || pais.trim() === "" || equipo.trim() === "") {
        Swal.fire({ title: "ERROR", text: "Tienes campos vacíos", icon: "error" });
        return;
    }

    let datos = new FormData();
    datos.append("id", id);
    datos.append("nombre", nombre);
    datos.append("edad", edad);
    datos.append("pais", pais);
    datos.append("equipo", equipo);
    datos.append("action", "updateJugador");

    if (fotoInput.files.length > 0) {
        datos.append("foto", fotoInput.files[0]);
    }

    let respuesta = await fetch("php/crud.php", { method: 'POST', body: datos });
    let json = await respuesta.json();

    if (json.success === true) {
        Swal.fire({ title: "¡ACTUALIZACIÓN EXITOSA!", text: json.mensaje, icon: "success" });
        document.getElementById("efoto").value ="";
        cargarJugador();
    } else {
        Swal.fire({ title: "ERROR", text: json.mensaje, icon: "error" });
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

cargarJugador();
cargarEquiposSelect();

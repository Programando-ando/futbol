var equipo = localStorage.getItem("equipo") || [];

const registrarE = async () => {
    var equipo = document.getElementById("nombre").value;
    var cantidad = document.getElementById("cantidad").value;
    var logotipo = document.getElementById("logo").files[0];

    if (equipo.trim() === "" || cantidad.trim() === "") {
        Swal.fire({title: "ERROR", text: "Campos vacíos", icon: "error"});
        return;
    }

    let datos = new FormData();
    datos.append('equipo', equipo);
    datos.append('cantidad', cantidad);
    if (logotipo) {
        datos.append('foto', logotipo);
    }
    datos.append('action', 'registrarEquipo');

    const respuesta = await fetch("php/crud.php", {method: 'POST', body: datos});
    let json = await respuesta.json();

    if (json.success === true) {
        Swal.fire({title: "¡REGISTRADO CON EXITO!", text: json.mensaje, icon: "success"});
        let equipos = JSON.parse(localStorage.getItem("equipo"));
        if (!Array.isArray(equipos)) {
            equipos = [];
        }
        const nuevoEquipo = {idequipo: json.idequipo, equipo: json.nombre, cantidad: json.cantidad, logotipo: json.foto};
        equipos.push(nuevoEquipo);
        localStorage.setItem("equipo", JSON.stringify(equipos));
        console.log(json);
        limpiar();
    } else {
        Swal.fire({title: "ERROR", text: json.mensaje, icon: "error"});
    }

    cargarEquipo();
}

const cargarEquipo = async () => {
    let datos = new FormData();
    datos.append("action", "cargarEquipos");
    const respuesta = await fetch("php/crud.php", { method: 'POST', body: datos });
    let json = await respuesta.json();

    document.getElementById("equipos").innerHTML = "";

    json.map(equip => {
        let tablaHTML = `
            <tr>
                <td>${equip.nombre}</td>
                <td>${equip.cantidad}</td>
                <td><img src="${equip.logotipo}" alt="${equip.nombre}" width="100px" height="100px"></td>
                <td>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editarE" onclick="mostrarE(${equip.idequipo})"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 64 64"><path fill="#ffce31" d="M7.934 41.132L39.828 9.246l14.918 14.922l-31.895 31.886z"/><path fill="#ed4c5c" d="m61.3 4.6l-1.9-1.9C55.8-.9 50-.9 46.3 2.7l-6.5 6.5l15 15l6.5-6.5c3.6-3.6 3.6-9.5 0-13.1"/><path fill="#93a2aa" d="m35.782 13.31l4.1-4.102l14.92 14.92l-4.1 4.101z"/><path fill="#c7d3d8" d="m37.338 14.865l4.1-4.101l11.739 11.738l-4.102 4.1z"/><path fill="#fed0ac" d="m7.9 41.1l-6.5 17l4.5 4.5l17-6.5z"/><path fill="#333" d="M.3 61.1c-.9 2.4.3 3.5 2.7 2.6l8.2-3.1l-7.7-7.7z"/><path fill="#ffdf85" d="m7.89 41.175l27.86-27.86l4.95 4.95l-27.86 27.86z"/><path fill="#ff8736" d="m17.904 51.142l27.86-27.86l4.95 4.95l-27.86 27.86z"/></svg></button>
                    <button class="btn btn-danger" onclick="eliminar(${equip.idequipo})"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 26 26"><path fill="currentColor" d="M10.875 0a1 1 0 0 0-.594.281L5.562 5H3c-.551 0-1 .449-1 1v2c0 .551.449 1 1 1h.25l2.281 13.719v.062c.163.788.469 1.541 1.032 2.157A3.26 3.26 0 0 0 8.938 26h8.124a3.26 3.26 0 0 0 2.375-1.031c.571-.615.883-1.405 1.032-2.219v-.031L22.78 9H23c.551 0 1-.449 1-1V6c0-.551-.449-1-1-1h-1.563l-2.812-3.5a.81.81 0 0 0-.719-.313a.8.8 0 0 0-.343.125L14.688 3.25L11.717.281A1 1 0 0 0 10.876 0zM11 2.438L13.563 5H8.436L11 2.437zm6.844.656L19.375 5h-2.938l-.593-.594zM5.25 9h.688l1.187 1.188l-1.438 1.406zm2.094 0h.937l-.469.469zm2.312 0h1.688l.906.906l-2 2l-1.75-1.75zm3.125 0h.344l-.156.188L12.78 9zm1.781 0h1.688l1.156 1.156l-1.75 1.75l-2-2.031zm3.063 0h.938l-.47.469L17.626 9zm2.344 0h.812l-.437 2.688l-1.532-1.532zm-7.032 1.594l2.032 2l-2.031 2l-2-2l2-2zm-5.124.281l1.718 1.719l-2 2l-1.625-1.625l-.031-.156zm10.28 0l2 2l-1.718 1.75l-2-2.031l1.719-1.719zm-7.843 2.438l2 2l-2 2l-2-2zm5.406 0l2.031 2l-2 2l-2.03-2zm4.188 1.25l-.219 1.312l-.563-.563l.782-.75zm-13.657.093l.657.656l-.469.47zM7.532 16l2 2l-2 2.031l-.562-.562l-.407-2.5zm5.407 0l2.03 2.031l-2 2L10.939 18zm5.437 0l1.063 1.063l-.407 2.28l-.656.657l-2-2zm-8.125 2.719l2 2l-2 2.031l-2-2zm5.406 0l2 2l-2 2l-2-2zm-8.094 2.718l2 2L9 24h-.063c-.391 0-.621-.13-.874-.406a2.65 2.65 0 0 1-.594-1.188v-.031l-.125-.75l.218-.188zm5.407 0l2 2l-.563.563H11.5l-.563-.563l2.032-2zm5.406 0l.281.282l-.125.656c-.002.01.002.02 0 .031c-.095.49-.316.922-.562 1.188c-.252.27-.509.406-.907.406h-.125l-.562-.563z"/></svg></button>
                </td>
            </tr>
        `;
        document.getElementById("equipos").innerHTML += tablaHTML;
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
            contactoid.append('action', 'delete');

            let respuesta = await fetch("php/crud.php", {method: 'POST', body: contactoid});
            let json = await respuesta.json();

            if (json.success === true) {
                Swal.fire({title: "¡Se eliminó con éxito!", text: json.mensaje, icon: "success"});
                cargarEquipo();
            } else {
                Swal.fire({title: "ERROR", text: json.mensaje, icon: "error"});
            }
        }
    });
};

const mostrarE = async (idequipo) => {
    let datos = new FormData();
    datos.append("id", idequipo);
    datos.append("action", "find");

    const respuesta = await fetch("php/crud.php", {method: 'POST', body: datos});
    let json = await respuesta.json();

    if (json.success) {
        document.getElementById('id').value = json.id_e; 
        document.getElementById('enombre').value = json.nombre;
        document.getElementById('ecantidad').value = json.cantidad;
        document.getElementById('file-name').textContent = json.logotipo;
    } else {
        console.error(json.mensaje); 
    }
};

const actualizarEquipo = async () => {
    var id = document.querySelector("#id").value;
    var nombre = document.querySelector("#enombre").value;
    var cantidad = document.querySelector("#ecantidad").value;
    var logotipoInput = document.querySelector("#elogo"); // Campo de tipo file

    if (nombre.trim() === "" || cantidad.trim() === "") {
        Swal.fire({ title: "ERROR", text: "Tienes campos vacíos", icon: "error" });
        return;
    }

    let datos = new FormData();
    datos.append("id", id);
    datos.append("nombre", nombre);
    datos.append("cantidad", cantidad);
    
    // Si se seleccionó un archivo de logotipo, lo añadimos al FormData
    if (logotipoInput.files.length > 0) {
        datos.append("logotipo", logotipoInput.files[0]); // Añadir el archivo seleccionado
    }

    datos.append("action", "update");

    let respuesta = await fetch("php/crud.php", { method: 'POST', body: datos });
    let json = await respuesta.json();

    if (json.success === true) {
        Swal.fire({ title: "¡ACTUALIZACIÓN EXITOSA!", text: json.mensaje, icon: "success" });
        document.getElementById("elogo").value = "";
    } else {
        Swal.fire({ title: "ERROR", text: json.mensaje, icon: "error" });
    }

    cargarEquipo(); // Actualiza la lista de equipos
};


const limpiar = () => {
    document.getElementById("nombre").value = "";
    document.getElementById("cantidad").value = "";
    document.getElementById("logo").value = ""; 
}

cargarEquipo();

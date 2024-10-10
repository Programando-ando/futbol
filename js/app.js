var equipo = localStorage.getItem("equipo") || [];

const registrarE = async () =>{
    var equipo = document.getElementById("nombre");
    var cantidad = document.getElementById("cantidad");
    var logotipo = document.getElementById("logo");

    if (equipo.trim()=="" || cantidad.trim()=="" || logotipo.trim()=="") {
        Swal.fire({title:"Campos vacios", text:"Completa los campos", icon:"error"});
    }

    let datos = new FormData();
    datos.append("equipo",equipo);
    datos.append("cantidad",cantidad);
    datos.append("logo",logotipo);
    datos.append("action","registrar");

    let respuesta = await fetch("php/crud.php", {method: 'POST', body: datos});
    let json = await respuesta.json();

    if (json.success == true) {
        Swal.fire({title:"Registrado con exito", text:json.mensaje, icon:"success"});
        
    }else{

    }
}
<?php
require_once "bd.php";

if ($_POST) {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'registrarEquipo':
            $valido['success'] = array('success' => false, 'mensaje' => "", 'id_e' => null, 'nombre' => "", 'cantidad' => null, 'logotipo' => "");
        
            $a = $_POST['equipo'];
            $b = $_POST['cantidad'];
            $tipo = $_FILES['foto']['type'];
            $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $filename = "img_" . time() . "." . $extension;
            $fileTmpName = $_FILES['foto']['tmp_name'];
            $uploadDirectory = '../img_profile/';
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }
        
            $filePath = $uploadDirectory . basename($filename);
            $filePath2 = "img_profile/" . basename($filename);
        
            if (move_uploaded_file($fileTmpName, $filePath)) {
                $check = "SELECT * FROM equipo WHERE nombre='$a'";
                $res = $cx->query($check);
        
                if ($res->num_rows == 0) {
                    $sql = "INSERT INTO equipo VALUES (null,'$a', '$b', '$filePath2')";
        
                    if ($cx->query($sql)) {
                        $lastId = $cx->insert_id;
        
                        $valido['success'] = true;
                        $valido['mensaje'] = "REGISTRO EXITOSO";
                        $valido['id_e'] = $lastId;
                        $valido['nombre'] = $a;
                        $valido['cantidad'] = $b;
                        $valido['foto'] = $filePath2;
                    } else {
                        $valido['success'] = false;
                        $valido['mensaje'] = "ALGO SALIO MAL EN EL REGISTRO";
                    }
                } else {
                    $valido['success'] = false;
                    $valido['mensaje'] = "USUARIO NO DISPONIBLE";
                }
            }
        
            echo json_encode($valido);
            break;

            case 'cargarEquipos':
                header('Content-Type: application/json'); // Asegúrate de que se retorne JSON
            
                // Verifica la conexión a la base de datos
                if ($cx->connect_error) {
                    echo json_encode(['error' => 'Error de conexión: ' . $cx->connect_error]);
                    exit();
                }
            
                $result = $cx->query("SELECT * FROM equipo");
                $rows = array();
            
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $rows[] = array(
                            'idequipo' => $row['id_e'],
                            'nombre' => $row['nombre'],
                            'cantidad' => $row['cantidad'],
                            'logotipo' => $row['logotipo']
                        );
                    }
                } else {
                    // Manejo de error en caso de que la consulta falle
                    echo json_encode(['error' => 'Error al ejecutar la consulta: ' . $cx->error]);
                    exit();
                }
            
                // Devuelve el JSON, incluso si no se encontraron equipos
                echo json_encode($rows);
                break;
            
            
            
                case 'find':
                    $id = $_POST['id'];
                    $sql = "SELECT * FROM equipo WHERE id_e = $id";
                    $res = $cx->query($sql);
                
                    $valido = array();
                
                    if ($res) {
                        if ($res->num_rows > 0) {
                            $row = $res->fetch_array();
                            $valido['success'] = true;
                            $valido['mensaje'] = "SE ENCONTRO REGISTRO";
                            $valido['id_e'] = $row['id_e'];  // Reemplaza con nombres de columnas
                            $valido['nombre'] = $row['nombre'];
                            $valido['cantidad'] = $row['cantidad'];
                            $valido['logotipo'] = $row['logotipo'];
                        } else {
                            $valido['success'] = false;
                            $valido['mensaje'] = "NO SE ENCONTRO EL REGISTRO";
                        }
                    } else {
                        $valido['success'] = false;
                        $valido['mensaje'] = "ERROR EN LA CONSULTA: " . $cx->error; 
                    }
                    
                    header('Content-Type: application/json');
                    echo json_encode($valido);
                    break;
                

        case 'delete':
            $id = $_POST['id'];
            $sqle = "DELETE FROM equipo WHERE id_e = $id";

            if ($cx->query($sqle)) {
                $valido['success'] = true;
                $valido['mensaje'] = "SE ELIMINO CORRECTAMENTE";
            } else {
                $valido['success'] = false;
                $valido['mensaje'] = "ERROR AL ELIMINAR EN BD";
            }
            echo json_encode($valido);  
            break;

            case 'update':
            
                $id = $_POST['id']; 
                $a = $_POST['nombre'];  
                $b = $_POST['cantidad'];  
                $logotipoNuevo = isset($_FILES['logotipo']) ? $_FILES['logotipo'] : null; 

                if ($logotipoNuevo && $logotipoNuevo['error'] == UPLOAD_ERR_OK) {
                    $tipo = $logotipoNuevo['type'];
                    $extension = pathinfo($logotipoNuevo['name'], PATHINFO_EXTENSION);
                    $filename = "img_" . time() . "." . $extension;
                    $fileTmpName = $logotipoNuevo['tmp_name'];
                    $uploadDirectory = '../img_profile/';

                    if (!is_dir($uploadDirectory)) {
                        mkdir($uploadDirectory, 0755, true);
                    }

                    $filePath = $uploadDirectory . basename($filename);
                    $filePath2 = "img_profile/" . basename($filename);

                    if (move_uploaded_file($fileTmpName, $filePath)) {
                        $sql = "UPDATE equipo SET nombre='$a', cantidad='$b', logotipo='$filePath2' WHERE id_e = $id";
                    } else {
                        $valido['success'] = false;
                        $valido['mensaje'] = "ERROR AL SUBIR EL NUEVO LOGOTIPO";
                        echo json_encode($valido);
                        exit;
                    }
                } else {
                    $sql = "UPDATE equipo SET nombre='$a', cantidad='$b' WHERE id_e = $id";
                }

                if ($cx->query($sql)) {
                    $valido['success'] = true;
                    $valido['mensaje'] = "SE ACTUALIZO CORRECTAMENTE";
                } else {
                    $valido['success'] = false;
                    $valido['mensaje'] = "ERROR AL ACTUALIZAR EN BD";
                }
            
                echo json_encode($valido);
                break;

                case 'agregarJugador':
                    $nombre = $_POST['nombre'];
                    $edad = $_POST['edad'];
                    $pais = $_POST['pais'];
                    $idequipo = $_POST['idequipo'];

                    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                        $targetDir = "jugador/";

                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }
                        
                        $targetFile = $targetDir . basename($_FILES['foto']['name']);

                        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
                            $logo = $targetFile;
                        } else {
                            echo "Error al mover el archivo.";
                            $logo = 'img/images.jpeg';
                        }
                    } else {
                        $logo = 'img/images.jpeg';
                    }
                    
            
                    $sql = "INSERT INTO jugador VALUES (null, '$nombre', $edad, '$pais','$logo', $idequipo)";
                    if ($cx->query($sql)) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'mensaje' => 'Error al registrar jugador']);
                    }
                    break;
            
    }
}
?>

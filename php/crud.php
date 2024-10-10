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
                header('Content-Type: application/json');

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
                    echo json_encode(['error' => 'Error al ejecutar la consulta: ' . $cx->error]);
                    exit();
                }
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
                            $valido['id_e'] = $row['id_e'];
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
                    // Mantener el logotipo existente si no se sube uno nuevo
                    $result = $cx->query("SELECT logotipo FROM equipo WHERE id_e='$id'");
                    $row = $result->fetch_assoc();
                    $logotipoExistente = $row['logotipo'];
            
                    $sql = "UPDATE equipo SET nombre='$a', cantidad='$b', logotipo='$logotipoExistente' WHERE id_e = $id";
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

                        $targetDir = "../jugador/";
                    
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }
                    
                        $fileExtension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                        $validExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    
                        if (in_array(strtolower($fileExtension), $validExtensions)) {
                            $newFileName = $_FILES['foto']['name'];
                            $targetFile = $targetDir . $newFileName;
                    
                            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
                                // Guardamos solo el nombre del archivo en la base de datos
                                $logo = $newFileName;
                            } else {
                                // En caso de fallo al subir la imagen, se usa una imagen por defecto
                                $logo = 'jugador_6705cedf896103.34673529.jpeg'; 
                            }
                        } else {
                            // Si el archivo tiene una extensión no válida, se asigna una imagen por defecto
                            $logo = 'jugador_6705cedf896103.34673529.jpeg';
                        }
                    } else {
                        // Si no se subió una imagen, se asigna una imagen por defecto
                        $logo = 'jugador_6705cedf896103.34673529.jpeg';
                    }
                    
                    // Inserción en la base de datos
                    $sql = "INSERT INTO jugador VALUES (null,'$nombre', '$edad', '$pais', '$logo', '$idequipo')";
                    
                    if ($cx->query($sql)) {
                        echo json_encode(['success' => true, 'mensaje' => 'SE AGREGÓ CORRECTAMENTE']);
                    } else {
                        echo json_encode(['success' => false, 'mensaje' => 'Error al registrar jugador']);
                    }
                    
                    break;

                    case 'cargarJugador':
                    
                        $sql = "SELECT jugador.id_j, jugador.nombre, jugador.edad, jugador.pais, jugador.foto, equipo.nombre AS nombre_equipo
                        FROM jugador INNER JOIN equipo ON jugador.id_e = equipo.id_e";
                
                
                        $result = $cx->query($sql);
                        $jugadores = [];
                        while ($row = $result->fetch_assoc()) {
                            $jugadores[] = $row;
                        }

                        error_log("Jugadores: " . json_encode($jugadores));
                    
                        echo json_encode($jugadores);
                        break;
                    

                        case 'deleteJugador':
                            $id = $_POST['id'];
                            $sqle = "DELETE FROM jugador WHERE id_j = $id";
                
                            if ($cx->query($sqle)) {
                                $valido['success'] = true;
                                $valido['mensaje'] = "SE ELIMINO CORRECTAMENTE";
                            } else {
                                $valido['success'] = false;
                                $valido['mensaje'] = "ERROR AL ELIMINAR EN BD";
                            }
                            echo json_encode($valido);  
                            break;

                            case 'find2':
                                $id = $_POST['id']; 
                                $sql = "
                                    SELECT jugador.id_j, jugador.nombre, jugador.edad, jugador.pais, jugador.foto, 
                                           equipo.id_e, equipo.nombre AS nombre_equipo 
                                    FROM jugador 
                                    INNER JOIN equipo ON jugador.id_e = equipo.id_e 
                                    WHERE jugador.id_j = $id";
                                
                                $res = $cx->query($sql);
                            
                                $valido = array();
                            
                                if ($res) {
                                    if ($res->num_rows > 0) {
                                        $row = $res->fetch_array();
                                        $valido['success'] = true;
                                        $valido['mensaje'] = "SE ENCONTRO REGISTRO";
                                        $valido['id_j'] = $row['id_j'];
                                        $valido['nombre_jugador'] = $row['nombre'];
                                        $valido['edad'] = $row['edad'];
                                        $valido['pais'] = $row['pais'];
                                        $valido['foto'] = $row['foto'];
                                        $valido['id_equipo'] = $row['id_e'];
                                        $valido['nombre_equipo'] = $row['nombre_equipo'];
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

                                case 'updateJugador':
                                    $id = $_POST['id'];
                                    $nombre = $_POST['nombre'];
                                    $edad = $_POST['edad'];
                                    $pais = $_POST['pais'];
                                    $idequipo = $_POST['equipo'];
                                
                                    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                                        $targetDir = "../jugador/";
                                        $targetFile = $targetDir . basename($_FILES['foto']['name']);
                                        
                                        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
                                            $logo = basename($_FILES['foto']['name']);
                                            $sql = "UPDATE jugador SET nombre='$nombre', edad='$edad', pais='$pais', foto='$logo', id_e='$idequipo' WHERE id_j='$id'";
                                        } else {
                                            echo json_encode(['success' => false, 'mensaje' => 'Error al subir la imagen']);
                                            exit;
                                        }
                                    } else {
                                        $result = $cx->query("SELECT foto FROM jugador WHERE id_j='$id'");
                                        $row = $result->fetch_assoc();
                                        $logo = $row['foto'];
                                
                                        $sql = "UPDATE jugador SET nombre='$nombre', edad='$edad', pais='$pais', id_e='$idequipo' WHERE id_j='$id'";
                                    }
                                
                                    if ($cx->query($sql)) {
                                        echo json_encode(['success' => true, 'mensaje' => 'Jugador actualizado correctamente']);
                                    } else {
                                        echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar jugador']);
                                    }
                                
                                    break;
                                
                                
                                
                                
                                
                                
    }
}
?>

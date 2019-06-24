<?php
include "datos_conexion.php";
// 1. Crear conexión a la Base de Datos
$conexion = new mysqli($host, $usuario, $clave, $bd);
// Verificar conexión
if ($conexion->connect_errno) {
	printf("Conexión fallida: %s", $conexion->connect_error);
	exit();
}

$conexion->set_charset("utf8");

$nombres = $_POST["nombres"];
$apellidos = $_POST["apellidos"];
$nom_usuario = $_POST["nom_usuario"];
$clave = $_POST["clave"];
$correo = $_POST["correo"];
$codigo_perfil = 1;

// Consultar el número total de registros de la tabla usuario
$sql = "SELECT COUNT(*) FROM usuario";
if (!($resultado = $conexion->query($sql))) {
	printf("Error al ejecutar la consulta %s", $conexion->error);
}

$fila = $resultado->fetch_row();
$cantidad_filas = $fila[0] + 1; // Aumentamos en 1 las filas en caso que ya exista el id

// 2. Tomar los campos provenientes de la tabla
$sql = "INSERT INTO usuario (codigo_usuario, nombres, apellidos, nom_usuario, clave, correo, codigo_perfil) VALUES (?, ?, ?, ?, ?, ?, ?)";

// Crear una sentencia preparada
if (!($stmt = $conexion->prepare($sql))) {
	printf("Error al preparar la consulta: %s", $conexion->error);
	exit();
}

// Ligar parámetros para marcadores
$stmt->bind_param("isssssi", $cantidad_filas, $nombres, $apellidos, $nom_usuario, $clave, $correo, $codigo_perfil);

$respuesta = array();
// Ejecutar la consulta
if ($stmt->execute()) {
	$respuesta["success"] = true;
} else {
	$respuesta["success"] = false;
	printf("Ha ocurrido un error: %s\n", $conexion->error);
}

// Imprimir respuesta que recibirá la aplicación del teléfono en formato JSON
echo json_encode($respuesta);

// Cerrar sentencia
$stmt->close();

// Cerrar conexión
$conexion->close();

?>
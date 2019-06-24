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

$cod_usuario = $_POST["codigo_usuario"];
$cod_cita = $_POST["codigo_cita"];

// 2. Tomar los campos provenientes de la tabla
$sql = "UPDATE cita SET codigo_usuario = ? WHERE codigo_cita = ?";

// Crear una sentencia preparada
if (!($stmt = $conexion->prepare($sql))) {
	printf("Error al preparar la consulta: %s", $conexion->error);
	exit();
}

// Ligar parámetros para marcadores
$stmt->bind_param("ii", $cod_usuario, $cod_cita);

$respuesta = array();
// Ejecutar la consulta
if ($stmt->execute()) {
	$respuesta["success"] = true;
} else {
	$respuesta["success"] = false;
	printf("Ha ocurrido un error ejecutando la consulta: %s\n", $conexion->error);
}

// Imprimir respuesta que recibirá la aplicación del teléfono en formato JSON
echo json_encode($respuesta);

// Cerrar sentencia
$stmt->close();

// Cerrar conexión
$conexion->close();

?>
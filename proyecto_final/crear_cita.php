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

$lugar = $_POST["lugar"];
$direccion = $_POST["direccion"];
$tipo_cita = $_POST["tipo_cita"];
$consultorio = $_POST["consultorio"];
$fecha = $_POST["fecha"];
$hora = $_POST["hora"];
$cod_doctor = $_POST["codigo_doctor"];

// Consultar el número total de registros de la tabla cita
$sql = "SELECT COUNT(*) FROM cita";
if (!($resultado = $conexion->query($sql))) {
	printf("Error al ejecutar la consulta %s", $conexion->error);
}

$fila = $resultado->fetch_row();
$cantidad_filas = $fila[0] + 1; // Aumentamos en 1 las filas en caso que ya exista el id

// 2. Tomar los campos provenientes de la tabla
$sql = "INSERT INTO cita(codigo_cita, lugar, direccion, tipo_cita, consultorio, fecha, hora, codigo_doctor) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// Crear una sentencia preparada
if (!($stmt = $conexion->prepare($sql))) {
	printf("Error al preparar la consulta: %s", $conexion->error);
	exit();
}

// Ligar parámetros para marcadores
$stmt->bind_param("issssssi", $cantidad_filas, $lugar, $direccion, $tipo_cita, $consultorio, $fecha, $hora, $cod_doctor);

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
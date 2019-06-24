<?php
include "datos_conexion.php";
// 1. Crear conexión a la Base de Datos
$conexion = new mysqli($host, $usuario, $clave, $bd);
// Verificar conexión
if ($conexion->connect_errno) {
	printf("Conexión fallida: %s\n", $conexion->connect_error);
	exit();
}

$conexion->set_charset("utf8");

$codigo_usuario = $_GET["codigo_usuario"];

// 2. Tomar los campos provenientes de la tabla
$sql = "SELECT codigo_cita, CONCAT(nombres, CONCAT(' ', apellidos)) AS nombre_doctor, lugar, direccion, tipo_cita, consultorio, fecha, hora"
		. " FROM cita INNER JOIN doctor ON cita.codigo_doctor = doctor.codigo_doctor"
		. " WHERE codigo_usuario = " . $codigo_usuario;

if (!($resultados = $conexion->query($sql))) {
	printf("Ha ocurrido un error en la consulta: " . $conexion->error);
	exit();
}

$arreglo = array();
while ($fila = $resultados->fetch_assoc()) { // Obtener una fila de resultados como un array asociativo
	array_push($arreglo, $fila);
}

// Devolver como respuesta al telefono un arreglo en formato JSON con la lista de citas agendadas
//  por el usuario pasado como parámetro
echo json_encode($arreglo);

// Cerrar conexión
$conexion->close();
?>

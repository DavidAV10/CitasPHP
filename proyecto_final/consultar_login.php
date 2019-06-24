<?php
include "datos_conexion.php";
// Crear conexión a la Base de Datos
$conexion = new mysqli($host, $usuario, $clave, $bd);
// Verificar conexión
if ($conexion->connect_errno) {
	printf("Conexión fallida: %s", $conexion->connect_error);
	exit();
}

$conexion->set_charset("utf8");

$nom_usu = $_POST["nom_usu"];
$clave = $_POST["clave"];

// Primero consultamos la tabla de usuario
$sql = "SELECT codigo_usuario, nombres, apellidos, codigo_perfil FROM usuario WHERE nom_usuario = ? AND clave = ?";

// Crear una sentencia preparada
if (!($stmt = $conexion->prepare($sql))) {
	printf("Error al preparar la consulta: %s", $conexion->error);
	exit();
}
// Ligar parámetros para marcadores
$stmt->bind_param("ss", $nom_usu, $clave);

// Ejecutar la consulta
$stmt->execute();

// Obtener un resultset con los datos de la tabla
$resultado = $stmt->get_result();

$numero_filas = $resultado->num_rows;
if ($numero_filas == 1) {
	$fila = $resultado->fetch_assoc(); // Obtener una fila de resultados como un array asociativo
	$fila["success"] = true;
} else {
	// Si no se encuentran resultados en la tabla de usuarios, se busca si existe un nombre
	//  de usuario y contraseña que coincida con los parámetros enviados por POST
	$sql = "SELECT codigo_doctor, nombres, apellidos, codigo_perfil FROM doctor WHERE nom_usuario = ? AND clave = ?";

	// Crear una sentencia preparada
	if (!($stmt = $conexion->prepare($sql))) {
		printf("Error al preparar la consulta: %s", $conexion->error);
		exit();
	}
	// Ligar parámetros para marcadores
	$stmt->bind_param("ss", $nom_usu, $clave);

	// Ejecutar la consulta
	$stmt->execute();

	// Obtener un resultset con los datos de la tabla
	$resultado = $stmt->get_result();

	$numero_filas = $resultado->num_rows;
	if ($numero_filas == 1) {
		$fila = $resultado->fetch_assoc(); // Obtener una fila de resultados como un array asociativo
		$fila["success"] = true;
	} else {
		$fila = array();
		$fila["success"] = false;
	}
}

echo json_encode($fila);

// Cerrar sentencia
$stmt->close();

// Cerrar conexión
$conexion->close();

?>
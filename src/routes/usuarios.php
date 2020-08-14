<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = new \Slim\App;

//GET para obtener todos los usuarios
$app->get('/api/usuarios', function ( Request $request, Response $response){
    $sql = 'SELECT *  FROM usuarios';
    try {
        $db = new DataBase();
        $db = $db->conectDB();
        $result = $db->query($sql);
        if ($result->rowCount() > 0){
            $usuarios = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($usuarios);
        }else{
            echo json_encode('No existen usuarios en la Base de datos');
        }
        $result = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error": '.$e->getMessagee().'}';
    }
});

//POST para insertar un nuevo usuario
$app->post('/api/usuarios/nuevo', function ( Request $request, Response $response){
    $nombre = $request->getParam('nombre');
    $edad = $request->getParam('edad');
    $sexo = $request->getParam('sexo');

    $sql = 'INSERT INTO usuarios (nombre, edad, sexo) VALUES (:nombre, :edad, :sexo)';
    try {
        $db = new DataBase();
        $db = $db->conectDB();
        $result = $db->prepare($sql);
        $result->bindParam(':nombre', $nombre);
        $result->bindParam(':edad', $edad);
        $result->bindParam(':sexo', $sexo);

        $result->execute();
        echo json_encode('Nuevo usuario insertado');
        $result = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error": '.$e->getMessagee().'}';
    }
});

//PUT para modificar un usuario
$app->put('/api/usuarios/modificar/{id}', function ( Request $request, Response $response){
    $id_usuario = $request->getAttribute('id');
    $nombre = $request->getParam('nombre');
    $edad = $request->getParam('edad');
    $sexo = $request->getParam('sexo');

    $sql = "UPDATE usuarios SET nombre = :nombre, edad = :edad, sexo = :sexo WHERE id = $id_usuario";
    try {
        $db = new DataBase();
        $db = $db->conectDB();
        $result = $db->prepare($sql);
        $result->bindParam(':nombre', $nombre);
        $result->bindParam(':edad', $edad);
        $result->bindParam(':sexo', $sexo);

        $result->execute();
        echo json_encode('usuario actualizado');
        $result = null;
        $db = null;
    } catch (PDOException $e) {
        echo '{"error": '.$e->getMessage().'}';
    }
});
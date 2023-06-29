<?php

include '../app/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$data = [];
if (!empty($_POST)) {
    $data = [
        "id" => $_POST["id"],
        "ambiente" => $_POST["ambiente"],
        "perfil" => $_POST["perfil"],
        "dt_emissao" => $_POST["emissao"],
        "dt_expiraEm" => $_POST["expiraEm"],
        "token" => sha1($_POST["token"])
    ];

    $host = 'mysqldb';
    $db   = getenv('MYSQL_DATABASE');
    $user = getenv('MYSQL_ROOT_USER');
    $pass = getenv('MYSQL_ROOT_PASSWORD');
    $port = "3306";

    $options = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);

        try {
            $sql = "INSERT INTO DataApi (cpf, ambiente, perfil, dt_emissao, dt_expiraEm, token) VALUES 
                ('{$_POST["id"]}', '{$_POST["ambiente"]}', '{$_POST["perfil"]}', '{$_POST["emissao"]}', '{$_POST["expiraEm"]}', '{$data["token"]}')";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            echo "successfully"; 
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

}

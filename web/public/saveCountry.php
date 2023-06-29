<?php

include '../app/vendor/autoload.php';

use Dotenv\Dotenv;

$response = $_POST;
$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$data = [];
if (!empty($response)) {
    foreach ($response['cidades'] as $cities) {
        $data = [
            "siglaCity" => $cities["coCidade"],
            "cities" => $cities["noCidade"],
            "codeCity" => $cities["sgPais"]
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
                $sql = "INSERT INTO CountryApi (cities, siglaCity, codeCity) VALUES ('{$data['cities']}', '{$data['siglaCity']}', '{$data['codeCity']}')";
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

}

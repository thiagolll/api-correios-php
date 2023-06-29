<?php
include '../app/vendor/autoload.php';

use Dotenv\Dotenv;

$response = json_decode($_POST['csv'], true);
$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$data = [];
if (!empty($response)) {
    foreach($response as $csv) {

        $dados = [
            'pedido' => $csv[0],
            'documento_comprador' => $csv[1],
            'data_entrega' => $csv[2],
            'data_venda' => $csv[3],
            'valor_venda' => $csv[4],
            'id_produto' => $csv[5],
            'quantidade' => $csv[6],
            'obs' => $csv[7]
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
                $sql = "INSERT INTO csv (pedido, documento_comprador, data_entrega, data_venda, valor_venda, id_produto, quantidade, obs) VALUES 
                ('{$dados['pedido']}', '{$dados['documento_comprador']}', '{$dados['data_entrega']}', '{$dados['data_venda']}', '{$dados['valor_venda']}', '{$dados['id_produto']}', '{$dados['quantidade']}', '{$dados['obs']}')";
    
                $stmt = $pdo->prepare($sql);
                $stmt->execute($dados);
                echo "successfully"; 
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

    }


}

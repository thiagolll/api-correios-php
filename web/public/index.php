<?php

include '../app/vendor/autoload.php';

use App\WebService\Correios\ConsulterDataApi;

if (!empty($_POST['sigla'])) {
    $siglaCountry = strval($_POST['sigla']);
    $responseCountry = ConsulterDataApi::consulterCountry($siglaCountry);
}

if (!empty($_POST['user'] && $_POST['password'])) {
    $response = ConsulterDataApi::consulterApi($_POST['user'], $_POST['password']);
}
include __DIR__ . "/includes/header.php";

include __DIR__ . "/includes/form.php"; 

if (!empty($_FILES)) {
	// Pasta onde o arquivo vai ser salvo
    $dir = "../public/";

    // recebendo o arquivo multipart 
    $file = $_FILES["Filedata"]; 
    
    // Move o arquivo da pasta temporaria de upload para a pasta de destino 
    if (move_uploaded_file($file["tmp_name"], "$dir/".$file["name"])) { 
        echo "Arquivo enviado com sucesso!"; 
        echo "</br>";
    } 
    else { 
        echo "Erro, o arquivo n&atilde;o pode ser enviado."; 
    }

    $row = 1;
    $rowSkip = 2;
    $csv = [];
        if (($handle = fopen($file["name"], "r", "$dir/".$file["name"])) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                if ($row < $rowSkip) {
                    $row++;
                    continue;
                }
                
                $dados = [];

                $dados = [
                    'pedido' => $data[0],
                    'documento_comprador' => $data[1],
                    'data_entrega' => $data[2],
                    'data_venda' => $data[3],
                    'valor_venda' => $data[4],
                    'id_produto' => $data[5],
                    'quantidade' => $data[6],
                    'obs' => $data[7]
                ];

                $row++;
                array_push($csv, $data); 
        }
        $csv = json_encode($csv);
        $arr = [];
        $arr = [
            'csv' => $csv
        ];
        $arrCsv = json_encode($arr);
        echo ("<script>
                Swal.fire({
                    title: 'Deseja Salvar os dados do CSV no Banco de Dados ?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    denyButtonText: `Não`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        type: 'POST',
                        url: 'saveCsv.php',
                        data: $arrCsv,
                        success: function(response) {
                            if (response) {
                                Swal.fire(
                                    'Bom Trabalho!',
                                    'Dados do Salvo com Sucesso!',
                                    'success'
                                )
                            }
                        }
                    });
                    } else if (result.isDenied) {
                    Swal.fire('Autenticação Cancelada', '', 'info')
                    }
                })
            </script>");

        fclose($handle);
    }
}

if (!empty($responseCountry)) {
    echo ("<script>
            Swal.fire({
                title: 'Deseja Confirmar a Consulta com esta Sigla ?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: `Não`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'POST',
                    url: 'saveCountry.php',
                    data: $responseCountry,
                    success: function(response) {
                        if (response) {
                            Swal.fire(
                                'Bom Trabalho!',
                                'Dados da API Salvo com Sucesso!',
                                'success'
                            )
                        }
                    }
                });
                } else if (result.isDenied) {
                Swal.fire('Autenticação Cancelada', '', 'info')
                }
            })
        </script>");
}

if (!empty($response)) {
    echo ("<script>
            Swal.fire({
                title: 'Deseja Confirmar a Autenticação na API ?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: `Não`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'POST',
                    url: 'saveData.php',
                    data: $response,
                    success: function(response) {
                        if (response === 'successfully') {
                            Swal.fire(
                                'Bom Trabalho!',
                                'Dados da API Salvo com Sucesso!',
                                'success'
                            )
                        }
                    }
                });
                } else if (result.isDenied) {
                Swal.fire('Autenticação Cancelada', '', 'info')
                }
            })
        </script>");
}


include __DIR__ . "/includes/footer.php";

include __DIR__ . "/includes/header-paises.php";
include __DIR__ . "/includes/form-paises.php";
include __DIR__ . "/includes/importar.php";

?>
</html>


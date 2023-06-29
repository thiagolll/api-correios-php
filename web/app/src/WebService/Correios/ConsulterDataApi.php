<?php
namespace App\WebService\Correios;

session_start();

class ConsulterDataApi
{
    /**
     * Url Base do serviço da API dos Correios
     * @var string
     */
    const URL_AUTENTICATION = 'https://api.correios.com.br/token/v1/autentica';

    public static function consulterApi($user, $password)
    {
        $curl = curl_init();
        if (!empty($user && !empty($password))) {
            $_SESSION[ 'user' ] = $user;
            $_SESSION[ 'password' ] = $password;
        }

        curl_setopt($curl, CURLOPT_URL, self::URL_AUTENTICATION);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$user:$password");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

    }

    public static function consulterCountry($country)
    {

        if ($_SESSION['user'] && $_SESSION['password'] && $country) {
            $user = $_SESSION['user'];
            $password = $_SESSION['password'];
            $curl = curl_init();
            $urlCountry = 'https://apps.correios.com.br/localidades/v1/paises/'.$country.'/cidades?page=1&size=500';
    
            curl_setopt($curl, CURLOPT_URL, $urlCountry);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, "$user:$password");
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    
            $response = curl_exec($curl);
            curl_close($curl);
    
            return $response;
        }
        
    }
}
<?php 

use App\Models\OtentikasiModel;
// use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use \Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

function getJWT($otentikasiHeader)
{
    if (is_null($otentikasiHeader)) {
        throw new Exception('Otentikasi JWT gagal');
    }
    return explode(" ", $otentikasiHeader)[1];
}

function validateJWT($encodedToken)
{
    $key = getenv('JWT_SECRET_KEY');
    $decodedToken = JWT::decode($encodedToken, $key, ['HS256']);
    $otentikasiModel = new OtentikasiModel();
    $otentikasiModel->getEmail($decodedToken->email);
}

function createJWT($email)
{
    $waktuRequest = time();
    $waktuToken = getenv("JWT_TIME_TO_LIVE");
    $waktuExpired = $waktuRequest + $waktuToken;
    $payload = [
        'email' => $email,
        'iat' => $waktuRequest,
        'exp' => $waktuExpired
    ];
    $jwt = JWT::encode($payload,getenv("JWT_SECRET_KEY"));
    return $jwt;
}
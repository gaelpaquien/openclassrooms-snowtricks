<?php
namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    // Generate JWT token
    public function generate(array $header, array $payload, string $secret, int $validity = 86400): string
    {
        if ($validity > 0) {
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }

        // base64 encoding
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        // Cleans up the encoded values (removes the +, / and = characters)
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        // Generate signature
        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret, true);
        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        // Generate JWT
        $jwt = "$base64Header.$base64Payload.$base64Signature";

        return $jwt;
    }

    // Check if JWT token is valid
    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    // Get JWT token payload
    public function getPayload(string $token): array
    {
        $array = explode('.', $token);
        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    // Get JWT token header
    public function getHeader(string $token): array
    {
        $array = explode('.', $token);
        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }

    // Check if JWT token is expired
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }

    // Check the signature of JWT token
    public function checkSignature(string $token, string $secret): bool
    {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        // Generate a check token to compare with the one sent
        $checkToken = $this->generate($header, $payload, $secret, 0);

        return $token === $checkToken;
    }
}

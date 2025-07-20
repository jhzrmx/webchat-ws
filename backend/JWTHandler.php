<?php

class JWTHandler {
    private $secret;
    private $header = ['alg' => 'HS256', 'typ' => 'JWT'];

    public function __construct($secret = 'defaultsecret0q5hr4xr9pyf6bo2g30vi8rcf2ste52nl42bq6r2tf7s6j') {
        $this->secret = $secret;
    }

    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public function getSecret() {
        return $this->$secret;
    }

    public function createToken($payload) {
        $headerEncoded = $this->base64UrlEncode(json_encode($this->header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    public function validateToken($jwt) {
        $parts = explode('.', $jwt);

        if (count($parts) !== 3) {
            return ['is_valid' => false, 'reason' => 'Invalid token structure'];
        }

        $headerEncoded = $parts[0];
        $payloadEncoded = $parts[1];
        $signatureEncoded = $parts[2];

        $decodedHeader = json_decode($this->base64UrlDecode($headerEncoded), true);
        $decodedPayload = json_decode($this->base64UrlDecode($payloadEncoded), true);

        if (!is_array($decodedPayload)) {
            return ['is_valid' => false, 'reason' => 'Invalid token JSON content'];
        }

        $validSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);

        if (!hash_equals($validSignature, $this->base64UrlDecode($signatureEncoded))) {
            return ['is_valid' => false, 'reason' => 'Invalid signature'];
        }

        $now = time();

        if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < $now) {
            return ['is_valid' => false, 'reason' => 'Token expired'];
        }

        if (isset($decodedPayload['nbf']) && $decodedPayload['nbf'] > $now) {
            return ['is_valid' => false, 'reason' => 'Token not valid'];
        }

        return ['is_valid' => true, 'reason' => 'Token validated', 'payload' => $decodedPayload];
    }
}


/*

Usage:

<?php

require_once 'JWTHandler.php';

$jwt = new JWTHandler();

$payload = [
    'sub' => 'user_id_123',
    'name' => 'Juan Dela Cruz',
    'exp' => time() + 3600, // 1 hour
    'nbf' => time(),        // not valid before now
];

$token = $jwt->createToken($payload);
setcookie('login_token', $token, $payload['exp'], '/');

echo "JWT: ".$_COOKIE['login_token']."\n";

$validation = $jwt->validateToken($token);
print_r($validation);

?>

*/
?>
<?php

require __DIR__ . '/../src/vendor/autoload.php';

use Firebase\JWT\JWT;

use PrismGestion\Models\Users as users;

phpCAS::setDebug();

phpCAS::setVerbose(false);

phpCAS::client(CAS_VERSION_2_0, "auth.univ-lorraine.fr", 443, "", true);

phpCAS::setNoCasServerValidation();

phpCAS::forceAuthentication();

$loader = new \Twig\Loader\FilesystemLoader('../src/templates');

$twig = new Twig\Environment($loader, [
    'cache' => false
]);

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => parse_ini_file('../src/bootstrap/conf.ini')
    ],

]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$user = users::where("NetID", '=', phpCAS::getUser())->first();

try {
    $secret = bin2hex(random_bytes(32));
    file_put_contents('../src/conf/jwt-secret', $secret);
} catch (Exception $e) {
}


if (!empty($user)) {
    $token = JWT::encode(
        ['iss' => 'https://iutnc-resamat.univ-lorraine.fr',
            'aud' => 'https://iutnc-resamat.univ-lorraine.fr',
            'iat' => time(),
            'exp' => time() + 3600,
            'uNetID' => $user->NetID,
            'lvl' => $user->NetID_Access_level
        ],
        $secret, 'HS512');
} else {
    $token = JWT::encode(
        ['iss' => 'https://iutnc-resamat.univ-lorraine.fr',
            'aud' => 'https://iutnc-resamat.univ-lorraine.fr',
            'iat' => time(),
            'exp' => time() + 3600,
            'uNetID' => phpCAS::getUser(),
            'lvl' => 1
        ],
        $secret, 'HS512');
}
try {
    echo $twig->render('index.twig', ["JWT" => $token]);
} catch (\Twig\Error\LoaderError $e) {
} catch (\Twig\Error\RuntimeError $e) {
} catch (\Twig\Error\SyntaxError $e) {
}
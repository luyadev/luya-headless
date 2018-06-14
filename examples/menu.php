<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use luya\headless\Client;
use luya\headless\cms\Menu;

$key = '2bce1834ff51af713998ca9804b28be51f48e7027a37e0777465ba6f263517b2asDSGvKovUuHnUUb2pN3rc-5v8RmX7xE';
$host = 'http://localhost/luya-test-env/public_html/';

include '../vendor/autoload.php';

$client = new Client($key, $host);

foreach (Menu::find($client)->container(1)->language(1)->root()->all() as $item) {
    echo $item['item']['title'] . PHP_EOL . '<br />';
}
<?php
define('OVERRIDE_PASSWORD', 'rivadbz');

// Take our input variables in.
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
$url = filter_var($_POST['url'], FILTER_SANITIZE_URL);

spl_autoload_register(function ($class) {
    // the package namespace
    $ns = 'Firebase';

    $base_dir = __DIR__ . '/firebase-php';

    $prefix_len = strlen($ns);
    if (substr($class, 0, $prefix_len) !== $ns) {
        return;
    }

    // strip the prefix off the class
    $class = substr($class, $prefix_len);

    // a partial filename
    $file = $base_dir .str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (is_readable($file)) {
        require $file;
    }
});

// Set the default value to return.
$arr_result = array('success' => 0, 'message' => 'Sorry, something went wrong.');

// Check to see if a URL was passed.
if ( strlen($url) == 0 ) {
    $arr_result = array('success' => -1, 'message' => 'A valid URL was not specified.');

    echo json_encode($arr_result);
    exit;
}

// Check to see if the password provided is correct.
if ( strlen($password) == 0 || $password != OVERRIDE_PASSWORD ) {
    $arr_result = array('success' => 0, 'message' => 'Sorry, an incorrect password was provided.');

    echo json_encode($arr_result);
    exit;
}

// Connect to Firebase and set the arbitrary URL.
const DEFAULT_URL = 'https://radiant-heat-6757.firebaseio.com/';
//const DEFAULT_TOKEN = 'MqL0c8tKCtheLSYcygYNtGhU8Z2hULOFs9OKPdEp';
const DEFAULT_PATH = '';

$firebase = new \Firebase\FirebaseLib(DEFAULT_URL);
$firebase->set(DEFAULT_PATH . 'videoOverrideQueue', $url);

// Return the success message.
$arr_result = array('success' => 1, 'message' => 'Successfully set the provided URL.');
echo json_encode($arr_result);
?>
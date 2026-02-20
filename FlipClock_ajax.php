<?php
$php_obj = new stdClass;
$php_obj->error = 0;
$php_obj->error_msg = '';

try {
    // get modified time of the actual file pointed to by the symbolic link FlipClock.html
    $modified_timestamp = filemtime(realpath('FlipClock.html'));
    $php_obj->modified_timestamp = $modified_timestamp;
} catch(Exception $e) {
	$php_obj->error = $e->getCode();
	$php_obj->error_msg = $e->getMessage();

	$this->log_message("Error code: " . $e->getCode() . ' ' . $e->getMessage(),'E');
}


$json = json_encode($php_obj);
echo $json;
exit();
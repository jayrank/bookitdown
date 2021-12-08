<?php
	phpinfo();
// var_dump(_isCurl());
function _isCurl(){
    return function_exists('curl_version');
}

if(extension_loaded('dom')){
    echo '<br>extension loaded';
} else {
	echo '<br>extension not loaded';
}

echo '<pre>';print_r(get_loaded_extensions());
?>
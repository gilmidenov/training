<?php

$path = 'C:/OSPanel/home/full-example.local/public/source';
$files = [];

$folders = [$path];//[]

do {
    $folder = array_shift($folders);
    $catalog = scandir($folder);

    $current_path = preg_replace('#(?<=/)[\w\d_]*\.[\w\d_]*?(?=\Z)#', '', $folder);

    foreach ($catalog as $file) {
        if (!in_array($file, ['.', '..'])) {
            if (str_contains($file, '.')) {
                require_once $current_path . '/' . $file;
//                echo '<pre>';
//                var_dump( $current_path . '/' . $file);
//                echo '</pre>';

            } else {
                $folders[] = $current_path . '/' . $file;
            }
        }
    }
} while($folders != []);

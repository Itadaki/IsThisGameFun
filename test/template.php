<?php ob_start(); ?>
<h1>HEADER</h1>

<?php

$a = [];
$a[]=0;
$a[]=0;
$a[]=0;
$a[]=0;

foreach ($a as $value) {
    echo "$value span";
}

?>

<h1>FOOTER</h1>

<?php return ob_get_clean(); ?>
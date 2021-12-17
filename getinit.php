<?php
$thisSystem = file_get_contents('system.info');
$getSource = file_get_contents('get.cfg');
if ($getSource == 'https://github.com' || $getSource == 'https://www.github.com') {
    $srcPubRepo = 'flossely';
} else {
    $srcPubRepo = 'webfloss';
}

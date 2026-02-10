<?php
// Including all required classes
require_once('qrcode/qrlib.php');

QRcode::png($_GET['text']);


?>
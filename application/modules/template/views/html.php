<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 11:57 ص
 */

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title)?$title:''; ?></title>

    <style>
        body,html,.main-content{
            padding: 0;
            margin: 0;
            width: 784px;
        }
    </style>

</head>
<body>


<div>

    <!-- main content begin -->
    <div class="main-content">
        <?php
        if(isset($content))$this->load->view($content);
        ?>
    </div>

</div>


<script src="<?= base_url()?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?= base_url()?>assets/js/jquery-ui.min.js"></script>

<?= put_headers('js') ?>

</body>
</html>

<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/01/18
 * Time: 11:29 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';

$count=0;
//var_dump($details);
?>


<div class="tb_container">
    <table class="table" id="achive_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th >حالة الانجاز</th>
            <th >نسبة الانجاز%</th>
            <th >المبررات</th>
            <th >حالة الخطة</th>
            <th >مرحل الى شهر</th>
            <th >الخطة الان</th>

        </tr>
        </thead>

        <tbody>
        <?php
        foreach($achive_res as $row) {
        ++$count+1


        ?>
            <tr>
                <td>

<?php
  echo $row['STATUS_NAME'];

?>

                </td>
                <td>
                    <?php
                    echo $row['PERSANT'].'%';

                    ?>

                </td>


                    <td>
                        <?php
                        echo $row['NOTES'];

                        ?>

                    </td>
                <td>
                    <?php
                    echo $row['IS_END_NAME'];

                    ?>


                </td>

                <td>
                    <?php
                    echo months($row['MONTH_ACHIVE']);

                    ?>


                </td><td>

                    <?php
                    echo $row['ADOPT_NAME'];

                    ?>

                </td>

            </tr>
        <?php

        }


        ?>
        </tbody>

        <tfoot>

    </table></div>

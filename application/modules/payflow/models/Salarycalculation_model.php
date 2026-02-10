<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SalaryCalculation_model extends MY_Model
{
    protected $conn;

    public function __construct()
    {
        parent::__construct();

        $dbConn = new DBConn();  // إنشاء كائن DBConn

        // استخدام الانعكاس (Reflection) للوصول للمتغير الخاص conn
        $reflection = new ReflectionClass($dbConn);
        $property = $reflection->getProperty('conn');
        $property->setAccessible(true);  // نخلي الوصول له ممكن
        $this->conn = $property->getValue($dbConn);  // ناخذ قيمة الاتصال
    }

    function executeProcedure($package, $procedure, $params = [])
    {
        try {
            $sql = "BEGIN {$package}.{$procedure}(";
            $placeholders = [];

            foreach ($params as $param) {
                $placeholders[] = $param['name'];
            }

            $sql .= implode(', ', $placeholders) . "); END;";

            $stmt = oci_parse($this->conn, $sql);

            foreach ($params as &$param) {
                if ($param['type'] == 'cursor') {
                    $param['value'] = oci_new_cursor($this->conn);
                }
                oci_bind_by_name($stmt, $param['name'], $param['value'], $param['length'], $param['type']);
            }

            if (!oci_execute($stmt)) {
                $error = oci_error($stmt);
                log_message('error', 'Oracle Error: ' . $error['message']);
                return false;
            }

            $result = [];
            foreach ($params as &$param) {
                if ($param['type'] == 'cursor') {
                    oci_execute($param['value']);
                    $result[$param['name']] = [];
                    while ($row = oci_fetch_assoc($param['value'])) {
                        $result[$param['name']][] = $row;
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Exception in executeProcedure: ' . $e->getMessage());
            return false;
        }
    }

    public function executeFunction($package, $function, $params = [])
    {
        try {
            $sql = "BEGIN :result := {$package}.{$function}(";
            $placeholders = [];

            foreach ($params as $param) {
                $placeholders[] = $param['name'];
            }

            $sql .= implode(', ', $placeholders) . "); END;";

            $stmt = oci_parse($this->conn, $sql);

            $result = null;
            oci_bind_by_name($stmt, ':result', $result, 100, SQLT_CHR);

            foreach ($params as &$param) {
                oci_bind_by_name($stmt, $param['name'], $param['value'], $param['length'], $param['type']);
            }

            if (!oci_execute($stmt)) {
                $error = oci_error($stmt);
                log_message('error', 'Oracle Error: ' . $error['message']);
                return false;
            }

            return $result;
        } catch (Exception $e) {
            log_message('error', 'Exception in executeFunction: ' . $e->getMessage());
            return false;
        }
    }
}

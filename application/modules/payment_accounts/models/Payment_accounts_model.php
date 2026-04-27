<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_accounts_model extends MY_Model
{
    var $PKG_NAME   = "GFC_PAK.PAYMENT_ACCOUNTS_PKG";
    var $TABLE_NAME = "PAYMENT_ACCOUNTS_TB";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    // =========================================================
    // PROVIDERS
    // =========================================================
    function providers_list($type = null)
    {
        $params = array(
            array('name' => ':P_TYPE',        'value' => $type,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',    'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PROVIDERS_LIST', $params);
    }

    function provider_get($id)
    {
        $params = array(
            array('name' => ':P_PROVIDER_ID', 'value' => $id,      'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',    'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PROVIDER_GET', $params);
    }

    function provider_insert($data)
    {
        $params = array(
            array('name' => ':P_TYPE',           'value' => $data['type']           ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_NAME',           'value' => $data['name']           ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_CODE',           'value' => $data['code']           ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_COMPANY_ACC_NO', 'value' => $data['company_acc_no'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_COMPANY_ACC_ID', 'value' => $data['company_acc_id'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_COMPANY_IBAN',   'value' => $data['company_iban']   ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_EXPORT_FORMAT',  'value' => $data['export_format']  ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_SORT_ORDER',     'value' => $data['sort_order']     ?? 999,  'type' => '', 'length' => -1),
            array('name' => ':P_MSG_OUT',        'value' => 'MSG',                           'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PROVIDER_INSERT', $params);
        return $result['MSG'];
    }

    function provider_update($data)
    {
        $params = array(
            array('name' => ':P_PROVIDER_ID',    'value' => $data['provider_id']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_NAME',           'value' => $data['name']           ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_CODE',           'value' => $data['code']           ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_COMPANY_ACC_NO', 'value' => $data['company_acc_no'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_COMPANY_ACC_ID', 'value' => $data['company_acc_id'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_COMPANY_IBAN',   'value' => $data['company_iban']   ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_EXPORT_FORMAT',  'value' => $data['export_format']  ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_SORT_ORDER',     'value' => $data['sort_order']     ?? 999,  'type' => '', 'length' => -1),
            array('name' => ':P_IS_ACTIVE',      'value' => $data['is_active']      ?? 1,    'type' => '', 'length' => -1),
            array('name' => ':P_MSG_OUT',        'value' => 'MSG',                           'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PROVIDER_UPDATE', $params);
        return $result['MSG'];
    }

    function provider_delete($provider_id)
    {
        $params = array(
            array('name' => ':P_PROVIDER_ID', 'value' => $provider_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',        'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PROVIDER_DELETE', $params);
        return $result['MSG'];
    }

    function provider_toggle_active($provider_id, $is_active)
    {
        $params = array(
            array('name' => ':P_PROVIDER_ID', 'value' => $provider_id,         'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_IS_ACTIVE',   'value' => $is_active ? 1 : 0,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',                'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PROVIDER_TOGGLE_ACTIVE', $params);
        return $result['MSG'];
    }

    function provider_accounts($provider_id, $only_active = 1)
    {
        $params = array(
            array('name' => ':P_PROVIDER_ID', 'value' => $provider_id, 'type' => SQLT_INT,    'length' => -1),
            array('name' => ':P_ONLY_ACTIVE', 'value' => $only_active, 'type' => SQLT_INT,    'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor',     'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',        'type' => SQLT_CHR,    'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PROVIDER_ACCOUNTS_LIST', $params);
    }

    function providers_list_paginated($filters = [], $offset = 0, $limit = 50)
    {
        $params = array(
            array('name' => ':P_TYPE',        'value' => $filters['type']      ?? null, 'type' => '',          'length' => -1),
            array('name' => ':P_IS_ACTIVE',   'value' => $filters['is_active'] ?? null, 'type' => '',          'length' => -1),
            array('name' => ':P_SEARCH',      'value' => $filters['search']    ?? null, 'type' => SQLT_CHR,    'length' => 200),
            array('name' => ':P_OFFSET',      'value' => $offset,                       'type' => SQLT_INT,    'length' => -1),
            array('name' => ':P_LIMIT',       'value' => $limit,                        'type' => SQLT_INT,    'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor',                      'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',                         'type' => SQLT_CHR,    'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PROVIDERS_LIST_PAGINATED', $params);
    }

    function providers_count($filters = [])
    {
        $params = array(
            array('name' => ':P_TYPE',      'value' => $filters['type']      ?? null, 'type' => '',          'length' => -1),
            array('name' => ':P_IS_ACTIVE', 'value' => $filters['is_active'] ?? null, 'type' => '',          'length' => -1),
            array('name' => ':P_SEARCH',    'value' => $filters['search']    ?? null, 'type' => SQLT_CHR,    'length' => 200),
            array('name' => ':P_CNT_OUT',   'value' => 'CNT',                         'type' => SQLT_INT,    'length' => -1),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG',                         'type' => SQLT_CHR,    'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PROVIDERS_COUNT', $params);
        return intval($result['CNT'] ?? 0);
    }

    function providers_totals($filters = [])
    {
        $params = array(
            array('name' => ':P_TYPE',         'value' => $filters['type']      ?? null, 'type' => '',       'length' => -1),
            array('name' => ':P_IS_ACTIVE',    'value' => $filters['is_active'] ?? null, 'type' => '',       'length' => -1),
            array('name' => ':P_SEARCH',       'value' => $filters['search']    ?? null, 'type' => SQLT_CHR, 'length' => 200),
            array('name' => ':P_TOTAL_OUT',    'value' => 'TOT',  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_BANKS_OUT',    'value' => 'BNK',  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_WALLETS_OUT',  'value' => 'WLT',  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_INACTIVE_OUT', 'value' => 'INAC', 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',      'value' => 'MSG',  'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PROVIDERS_TOTALS', $params);
        return [
            'total'    => intval($result['TOT']  ?? 0),
            'banks'    => intval($result['BNK']  ?? 0),
            'wallets'  => intval($result['WLT']  ?? 0),
            'inactive' => intval($result['INAC'] ?? 0),
        ];
    }

    function providers_bulk_action($ids_csv, $action)
    {
        $params = array(
            array('name' => ':P_IDS_CSV',  'value' => $ids_csv, 'type' => SQLT_CHR, 'length' => 4000),
            array('name' => ':P_ACTION',   'value' => $action,  'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':P_OK_OUT',   'value' => 'OK',     'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_FAIL_OUT', 'value' => 'FAIL',   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',  'value' => 'MSG',    'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PROVIDERS_BULK_ACTION', $params);
        return [
            'msg'  => $result['MSG']  ?? '',
            'ok'   => intval($result['OK']   ?? 0),
            'fail' => intval($result['FAIL'] ?? 0),
        ];
    }

    // =========================================================
    // BANK BRANCHES
    // =========================================================
    function branches_list($provider_id = null)
    {
        $params = array(
            array('name' => ':P_PROVIDER_ID', 'value' => $provider_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor',     'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',        'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'BRANCHES_LIST', $params);
    }

    function branch_insert($data)
    {
        $params = array(
            array('name' => ':P_PROVIDER_ID', 'value' => $data['provider_id'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_NAME',        'value' => $data['name']        ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_PRINT_NO',    'value' => $data['print_no']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_ADDRESS',     'value' => $data['address']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_PHONE1',      'value' => $data['phone1']      ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_PHONE2',      'value' => $data['phone2']      ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_FAX',         'value' => $data['fax']         ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',                        'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BRANCH_INSERT', $params);
        return $result['MSG'];
    }

    function branch_update($data)
    {
        $params = array(
            array('name' => ':P_BRANCH_ID',   'value' => $data['branch_id']   ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_PROVIDER_ID', 'value' => $data['provider_id'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_NAME',        'value' => $data['name']        ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_PRINT_NO',    'value' => $data['print_no']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_ADDRESS',     'value' => $data['address']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_PHONE1',      'value' => $data['phone1']      ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_PHONE2',      'value' => $data['phone2']      ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_FAX',         'value' => $data['fax']         ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_IS_ACTIVE',   'value' => $data['is_active']   ?? 1,    'type' => '', 'length' => -1),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',                        'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BRANCH_UPDATE', $params);
        return $result['MSG'];
    }

    function branch_link_provider($branch_id, $provider_id)
    {
        $params = array(
            array('name' => ':P_BRANCH_ID',   'value' => $branch_id,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PROVIDER_ID', 'value' => $provider_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',        'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BRANCH_LINK_PROVIDER', $params);
        return $result['MSG'];
    }

    // =========================================================
    // BENEFICIARIES
    // =========================================================
    function benef_list($emp_no)
    {
        $params = array(
            array('name' => ':P_EMP_NO',      'value' => $emp_no,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',    'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'BENEF_LIST', $params);
    }

    function benef_insert($data)
    {
        $params = array(
            array('name' => ':P_EMP_NO',    'value' => $data['emp_no']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_REL_TYPE',  'value' => $data['rel_type']  ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_ID_NO',     'value' => $data['id_no']     ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_NAME',      'value' => $data['name']      ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_PHONE',     'value' => $data['phone']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_PCT_SHARE', 'value' => $data['pct_share'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_FROM_DATE', 'value' => $data['from_date'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_TO_DATE',   'value' => $data['to_date']   ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_NOTES',     'value' => $data['notes']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG',                      'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BENEF_INSERT', $params);
        return $result['MSG'];
    }

    function benef_update($data)
    {
        $params = array(
            array('name' => ':P_BENEF_ID',  'value' => $data['benef_id']  ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_REL_TYPE',  'value' => $data['rel_type']  ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_ID_NO',     'value' => $data['id_no']     ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_NAME',      'value' => $data['name']      ?? '',   'type' => '', 'length' => -1),
            array('name' => ':P_PHONE',     'value' => $data['phone']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_PCT_SHARE', 'value' => $data['pct_share'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_FROM_DATE', 'value' => $data['from_date'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_TO_DATE',   'value' => $data['to_date']   ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_STATUS',    'value' => $data['status']    ?? 1,    'type' => '', 'length' => -1),
            array('name' => ':P_NOTES',     'value' => $data['notes']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG',                      'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BENEF_UPDATE', $params);
        return $result['MSG'];
    }

    function benef_delete($benef_id)
    {
        $params = array(
            array('name' => ':P_BENEF_ID', 'value' => $benef_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',  'value' => 'MSG',     'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BENEF_DELETE', $params);
        return $result['MSG'];
    }

    // =========================================================
    // ACCOUNTS
    // =========================================================
    function accounts_list($emp_no, $only_active = 0)
    {
        $params = array(
            array('name' => ':P_EMP_NO',      'value' => $emp_no,      'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_ONLY_ACTIVE', 'value' => $only_active, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor',     'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',        'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'ACCOUNTS_LIST', $params);
    }

    function account_get($acc_id)
    {
        $params = array(
            array('name' => ':P_ACC_ID',      'value' => $acc_id,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',    'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'ACCOUNT_GET', $params);
    }

    function account_insert($data)
    {
        $params = array(
            array('name' => ':P_EMP_NO',         'value' => $data['emp_no']         ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_BENEFICIARY_ID', 'value' => $data['beneficiary_id'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_PROVIDER_ID',    'value' => $data['provider_id']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_BRANCH_ID',      'value' => $data['branch_id']      ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_ACCOUNT_NO',     'value' => $data['account_no']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_IBAN',           'value' => $data['iban']           ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_WALLET_NUMBER',  'value' => $data['wallet_number']  ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_OWNER_ID_NO',    'value' => $data['owner_id_no']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_OWNER_NAME',     'value' => $data['owner_name']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_OWNER_PHONE',    'value' => $data['owner_phone']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_IS_DEFAULT',     'value' => $data['is_default']     ?? 0,    'type' => '', 'length' => -1),
            array('name' => ':P_SPLIT_TYPE',     'value' => $data['split_type']     ?? 3,    'type' => '', 'length' => -1),
            array('name' => ':P_SPLIT_VALUE',    'value' => $data['split_value']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_SPLIT_ORDER',    'value' => $data['split_order']    ?? 1,    'type' => '', 'length' => -1),
            array('name' => ':P_NOTES',          'value' => $data['notes']          ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_MSG_OUT',        'value' => 'MSG',                           'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACCOUNT_INSERT', $params);
        return $result['MSG'];
    }

    function account_update($data)
    {
        $params = array(
            array('name' => ':P_ACC_ID',         'value' => $data['acc_id']         ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_BRANCH_ID',      'value' => $data['branch_id']      ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_ACCOUNT_NO',     'value' => $data['account_no']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_IBAN',           'value' => $data['iban']           ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_WALLET_NUMBER',  'value' => $data['wallet_number']  ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_OWNER_ID_NO',    'value' => $data['owner_id_no']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_OWNER_NAME',     'value' => $data['owner_name']     ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_OWNER_PHONE',    'value' => $data['owner_phone']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_IS_DEFAULT',     'value' => $data['is_default']     ?? 0,    'type' => '', 'length' => -1),
            array('name' => ':P_SPLIT_TYPE',     'value' => $data['split_type']     ?? 3,    'type' => '', 'length' => -1),
            array('name' => ':P_SPLIT_VALUE',    'value' => $data['split_value']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_SPLIT_ORDER',    'value' => $data['split_order']    ?? 1,    'type' => '', 'length' => -1),
            array('name' => ':P_NOTES',          'value' => $data['notes']          ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_MSG_OUT',        'value' => 'MSG',                           'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACCOUNT_UPDATE', $params);
        return $result['MSG'];
    }

    function account_deactivate($acc_id, $reason, $notes = '')
    {
        $params = array(
            array('name' => ':P_ACC_ID',  'value' => $acc_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REASON',  'value' => $reason, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_NOTES',   'value' => $notes,  'type' => '',       'length' => -1),
            array('name' => ':P_MSG_OUT', 'value' => 'MSG',   'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACCOUNT_DEACTIVATE', $params);
        return $result['MSG'];
    }

    function account_reactivate($acc_id, $notes = '')
    {
        $params = array(
            array('name' => ':P_ACC_ID',  'value' => $acc_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_NOTES',   'value' => $notes,  'type' => '',       'length' => -1),
            array('name' => ':P_MSG_OUT', 'value' => 'MSG',   'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACCOUNT_REACTIVATE', $params);
        return $result['MSG'];
    }

    function account_delete($acc_id)
    {
        $params = array(
            array('name' => ':P_ACC_ID',  'value' => $acc_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT', 'value' => 'MSG',   'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACCOUNT_DELETE', $params);
        return $result['MSG'];
    }

    function account_set_default($acc_id)
    {
        $params = array(
            array('name' => ':P_ACC_ID',  'value' => $acc_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT', 'value' => 'MSG',   'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACCOUNT_SET_DEFAULT', $params);
        return $result['MSG'];
    }

    // =========================================================
    // SPLIT DISTRIBUTION
    // =========================================================
    function split_list($detail_id)
    {
        $params = array(
            array('name' => ':P_DETAIL_ID',   'value' => $detail_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor',   'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',      'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'SPLIT_LIST', $params);
    }

    function split_auto_fill($detail_id)
    {
        $params = array(
            array('name' => ':P_DETAIL_ID', 'value' => $detail_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG',      'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'AUTO_FILL_SPLIT', $params);
        return $result['MSG'];
    }

    function split_update_manual($detail_id, $splits_json)
    {
        $params = array(
            array('name' => ':P_DETAIL_ID',   'value' => $detail_id,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_SPLITS_JSON', 'value' => $splits_json, 'type' => OCI_B_CLOB, 'length' => -1),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',        'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'UPDATE_SPLIT_MANUAL', $params);
        return $result['MSG'];
    }

    function split_reset_auto($detail_id)
    {
        $params = array(
            array('name' => ':P_DETAIL_ID', 'value' => $detail_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG',      'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'RESET_SPLIT_TO_AUTO', $params);
        return $result['MSG'];
    }

    // =========================================================
    // EMPLOYEES (عبر Procedures — لا SQL مباشر)
    // =========================================================

    // قائمة الموظفين + pagination + فلاتر
    function employees_list($filters = [], $offset = 0, $limit = 50)
    {
        // ملاحظة: لا تستخدم SQLT_INT للـ OFFSET/LIMIT لأن DBConn::bind_params
        // يخزّن بـ array key = value، فلو القيمة 0 تتشارك مع is_active/has_acc
        // ويحدث type confusion. نستخدم نفس النوع الافتراضي ('').
        $params = array(
            array('name' => ':P_EMP_NO',      'value' => $filters['emp_no']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_BRANCH_NO',   'value' => $filters['branch_no'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_IS_ACTIVE',   'value' => $filters['is_active'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_HAS_ACC',     'value' => $filters['has_acc']   ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_OFFSET',      'value' => $offset,                       'type' => '', 'length' => -1),
            array('name' => ':P_LIMIT',       'value' => $limit,                        'type' => '', 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor',                      'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',                         'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'EMPLOYEES_LIST_PAGINATED', $params);
    }

    // عدد الموظفين بالفلاتر
    function employees_count($filters = [])
    {
        $params = array(
            array('name' => ':P_EMP_NO',    'value' => $filters['emp_no']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_BRANCH_NO', 'value' => $filters['branch_no'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_IS_ACTIVE', 'value' => $filters['is_active'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_HAS_ACC',   'value' => $filters['has_acc']   ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_CNT_OUT',   'value' => 'CNT',                         'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG',                         'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMPLOYEES_COUNT', $params);
        return intval($result['CNT'] ?? 0);
    }

    // إجمالي الإحصائيات بالفلاتر (لبطاقات الواجهة)
    function employees_totals($filters = [])
    {
        $params = array(
            array('name' => ':P_EMP_NO',     'value' => $filters['emp_no']    ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_BRANCH_NO',  'value' => $filters['branch_no'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_IS_ACTIVE',  'value' => $filters['is_active'] ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_HAS_ACC',    'value' => $filters['has_acc']   ?? null, 'type' => '', 'length' => -1),
            array('name' => ':P_TOTAL_OUT',  'value' => 'TOT',  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_BANK_OUT',   'value' => 'BNK',  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_WALLET_OUT', 'value' => 'WLT',  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_BENEF_OUT',  'value' => 'BNF',  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',    'value' => 'MSG',  'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMPLOYEES_TOTALS', $params);
        return [
            'total'  => intval($result['TOT'] ?? 0),
            'bank'   => intval($result['BNK'] ?? 0),
            'wallet' => intval($result['WLT'] ?? 0),
            'benef'  => intval($result['BNF'] ?? 0),
        ];
    }

    // بيانات موظف واحد
    function get_employee($emp_no)
    {
        $params = array(
            array('name' => ':P_EMP_NO',      'value' => $emp_no,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG',    'type' => SQLT_CHR, 'length' => -1),
        );
        $rows = $this->New_rmodel->general_get($this->PKG_NAME, 'EMPLOYEE_GET', $params);
        return is_array($rows) && count($rows) > 0 ? $rows[0] : null;
    }
}

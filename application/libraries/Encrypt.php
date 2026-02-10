<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Custom Encrypt library (OpenSSL-based)
 * بديل لمكتبة Encrypt القديمة المعتمدة على mcrypt
 * نفس واجهة CodeIgniter 2 تقريبًا: encode() / decode()
 */
class CI_Encrypt
{
    protected $CI;
    protected $_cipher = 'AES-256-CBC';
    protected $_encryption_key;

    public function __construct($params = array())
    {
        $this->CI =& get_instance();
        $this->CI->load->config('config', true);

        $key = $this->CI->config->item('encryption_key');
        if (empty($key)) {
            show_error('You must set an encryption key in your config file.');
        }

        // نحول المفتاح لـ 32 بايت (256-bit)
        $this->_encryption_key = hash('sha256', $key, true);
    }

    /**
     * تشفير نص
     *
     * @param string $string
     * @param string $key (اختياري)
     * @return string
     */
    public function encode($string, $key = '')
    {
        $key = $this->_get_key($key);

        $iv_length = openssl_cipher_iv_length($this->_cipher);
        $iv = openssl_random_pseudo_bytes($iv_length);

        $cipher_raw = openssl_encrypt($string, $this->_cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($cipher_raw === false) {
            return false;
        }

        // HMAC للتأكد من سلامة البيانات
        $hmac = hash_hmac('sha256', $cipher_raw, $key, true);

        // الشكل النهائي: IV + HMAC + CIPHER
        $out = base64_encode($iv . $hmac . $cipher_raw);

        return $out;
    }

    /**
     * فك التشفير
     *
     * @param string $string
     * @param string $key (اختياري)
     * @return string|bool
     */
    public function decode($string, $key = '')
    {
        $key = $this->_get_key($key);

        $data = base64_decode($string, true);
        if ($data === false) {
            return false;
        }

        $iv_length = openssl_cipher_iv_length($this->_cipher);

        if (strlen($data) < ($iv_length + 32)) {
            return false;
        }

        $iv   = substr($data, 0, $iv_length);
        $hmac = substr($data, $iv_length, 32);
        $cipher_raw = substr($data, $iv_length + 32);

        $calc_hmac = hash_hmac('sha256', $cipher_raw, $key, true);

        // مقارنة آمنة
        if (!function_exists('hash_equals')) {
            // Fallback بسيط لو PHP قديم
            if ($hmac !== $calc_hmac) {
                return false;
            }
        } else {
            if (!hash_equals($hmac, $calc_hmac)) {
                return false;
            }
        }

        $plain = openssl_decrypt($cipher_raw, $this->_cipher, $key, OPENSSL_RAW_DATA, $iv);

        return $plain;
    }

    /**
     * نفس الفكرة في CI: إذا تم تمرير key جديد نستخدمه
     */
    protected function _get_key($key = '')
    {
        if ($key === '') {
            return $this->_encryption_key;
        }

        return hash('sha256', $key, true);
    }

    // دوال شكلية للحفاظ على التوافق،
    // إذا مشروعك يناديها لن تنكسر
    public function set_cipher($cipher)
    {
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $this->_cipher = $cipher;
        }
        return $this;
    }

    public function set_mode($mode)
    {
        // فقط للحفاظ على التوافق مع الكود القديم
        return $this;
    }
}

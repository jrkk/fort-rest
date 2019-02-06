<?php
namespace App\Driver\Encryption;

class OpenSsl {
    private $iv = '';
	private $key = '';
	private $cipher = 'AES-128-CBC'; // default cipher
	private $ready = false;
	private $options = 0;
	function __construct() {
		$this->ready = ( function_exists('openssl_encrypt')
				&& function_exists('openssl_decrypt')
				&& function_exists('openssl_cipher_iv_length')
				) ? true : false;
	}
	function __set($name, $value) {
		if( $name == 'iv' ) {
			/*
			 * Forcely , set the encryption key.
			 * Before set the key you should keylength as per cipher
			 */
			$this->iv = $value;
		} else if( $name == 'key' ) {
			/*
			 * If set key IV is auto generated
			 * That IV is not carray for next request.
			 */
			$this->key = $value;
			$ivlen = openssl_cipher_iv_length($this->cipher);
			if($this->iv == '')
				$this->iv = openssl_random_pseudo_bytes($ivlen);
		} else if ( $name == 'cipher' && in_array($value, openssl_get_cipher_methods())) {
			/*
			 * Cipher is nothing but encryption alogrithm avilable openssl.
			 * Fiest checks and assigned algorithm when it is available.
			 */
			$this->cipher = $value;
		} else if ( $name == 'option' && in_array($value, [ OPENSSL_RAW_DATA,  OPENSSL_ZERO_PADDING ]) ) {
			/*
			 * Follow the php.net documentations for options.
			 */
			$this->options = $value;
		} else if(!isset($this->{$name})) {
			/*
			 * If developer is assigned undefined varibaled.
			 * throws a generalized exception.
			 */
			throw new \Exception('Invalid data try to assign with variable:'.$name);
		}
	}
	public function encrypt($data) {
		/*
		 * call and return openssl encryption
		 */
		return openssl_encrypt ($data,$this->cipher, $this->key , $this->options , $this->iv );
	}
	public function decrypt($data) {
		/*
		 * call and return  openssl decryption.
		 */
		return openssl_decrypt ($data,$this->cipher, $this->key , $this->options , $this->iv );
	}
	public function padPKCS7($data, $blocksize) {
		$pad = $blocksize - (strlen($data) % $blocksize);
		$data .= str_repeat(chr($pad), $pad);
		return $data;
	}
	public function unpadPKCS7($data) {
		$pad = ord($data[(strlen($data)) - 1]);
		$data = substr($data, 0, strlen($data) - $pad);
		return $data;
	}
	public function padBits() { }
	public function unpadBits() { }
	public function padZeros($data, $blocksize) {
		return str_pad($data, $blocksize, "\X00", STR_PAD_RIGHT);
	}
	public function unpadZeros($data) {
		return rtrim($data, "\X00");
	}
	public function padBytes() { }
	public function unpadBytes() { }
}
<?php
namespace App\Driver\Encryption;

class Rsa {
    /**
	 * @var resource $publicKey
	 * @var resource $privateKey
	 * @access strict.
	 */
	private $publicKey , $privateKey ;
	
	/**
	 * @var string $passphrase
	 * @desc auto generate no need to assign statically.
	 */
    protected $passphrase = '';
    
    /**
     * @var string $domain
     * @desc need to set the domain configuration for certificate loading.
     */
    private $dn = [ "countryName" => '',
                    "stateOrProvinceName" => '',
                    "localityName" => '',
                    "organizationName" => '',
                    "organizationalUnitName" => '',
                    "commonName" => '',
                    "emailAddress" => '' ];

    /**
     * @var string $config
     * @desc Configuration that can be passthrouh the object creation.
     */
    private $config = [
        //"config" => "/usr/lib/ssl/openssl.cnf ",
        "digest_alg" => "SHA256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
        "encrypt_key_cipher" => OPENSSL_CIPHER_AES_256_CBC
    ];
	
    function __construct() 
    {
	}
	public function encrypt($data) : string {
		if(empty($this->publicKey)) throw new RsaSecureException();
		$cypher = '';
		if (openssl_public_encrypt($data, $encrypted, $this->publicKey))
			$cypher = base64_encode($encrypted);
		return $cypher;
	}
	public function decrypt($data) : string {
		if(empty($this->privateKey)) throw new RsaSecureException();
		$text = '';
		$data = base64_decode($data);
		@openssl_private_decrypt($data, $text, $this->privateKey);
		return $text;
	}
	private function init() : self {
        
		$credentials =  new stdClass();
		
		// Create the private using require configuratin.
		$this->privateKey = openssl_pkey_new($configuration);
		
		// get private key as string format
		$credentials->private = '';
		openssl_pkey_export($this->privateKey, $credentials->private, $this->passphrase, $configuration);
		//$credentials->private = $privateKey;
		
		// Generate a certificate with using private key
		$csr = openssl_csr_new($dn, $this->privateKey, $configuration);
		
		// get subject of the certificate
		//$subject = openssl_csr_get_subject($csr, true);
		
		// extract the public key of private with help of certificate.
		$this->publicKey = openssl_csr_get_public_key($csr);
		$credentials->public = openssl_pkey_get_details($this->publicKey)['key'];
		//$credentials->public = $publicKey;
		
        /* Push credentials into file system */
		
	}
	public function setSecureKey($key) {
        $this->passphrase = $key;
        $this->config["encrypt_key"] = $this->passphrase;
    }
}
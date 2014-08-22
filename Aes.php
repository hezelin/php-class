<?php
class aes {
 
    // CRYPTO_CIPHER_BLOCK_SIZE 32
     
    private $_secret_key = 'default_secret_key';
     
    public function setKey($key) {
        $this->_secret_key = $key;
    }
     
    public function encode($data) {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);


        /*echo '$td = ',$td,'<br/>';
        echo '$iv = ',$iv,'<br/>';*/
        mcrypt_generic_init($td,$this->_secret_key,$iv);
        $encrypted = mcrypt_generic($td,$data);
        mcrypt_generic_deinit($td);
        
        // echo '$encrypted = ',$encrypted,'<br/>';
        return $iv . $encrypted;
    }
     
    public function decode($data) {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
        $iv = mb_substr($data,0,32,'latin1');
        /*echo '<br/>';
        echo '$td = ',$td,'<br/>';
        echo '$iv = ',$iv,'<br/>';*/

        mcrypt_generic_init($td,$this->_secret_key,$iv);
        $data = mb_substr($data,32,mb_strlen($data,'latin1'),'latin1');
        $data = mdecrypt_generic($td,$data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
         
        return trim($data);
    }
}
 
header("content-type:text/html;charset=utf-8");

$aes = new aes();
$aes->setKey( md5('key') );

// 加密
$string = $aes->encode('string');

echo '密钥 = ',md5('key'),'<br/>';
echo '加密字符串 = string <br/>';
echo '加密后的结果(二进制) = ',$string,'<br/>';
echo '加密后的结果(十六进制) = ',bin2hex( $string),'<br/>';

echo '密文解密的结果 = ';

echo $aes->decode($string);

echo '<br/>';

// echo $aes->decode( hex2bin('9704b537d9433bd235d41d42175d4539f930985dba7b0b884a0926e31ab1b052f9550214d69de6d3d485526524c9922e6c2ab8a78746f1602cc8bb38ce974d65') );
?>
<?php
namespace app\includes\QYwechat;

use app\includes\QYwechat\ErrorCode;
use app\includes\QYwechat\Prpcrypt;
/**
 * 1.企业回复加密消息给企业微信；
 * 2.企业收到企业微信发送的消息，验证消息的安全性，并对消息进行解密。
 */
class MsgCrypt
{
	private $m_sToken;
	private $m_sEncodingAesKey;
	private $m_sCorpid;

	/**
	 * 构造函数
	 * @param $token string 企业微信上，开发者设置的token
	 * @param $encodingAesKey string 企业微信上，开发者设置的EncodingAESKey
	 * @param $Corpid string 企业微信的Corpid
	 */
	function __construct($token, $encodingAesKey, $Corpid)
	{
		$this->m_sToken = $token;
		$this->m_sEncodingAesKey = $encodingAesKey;
		$this->m_sCorpid = $Corpid;
	}
	
    /*
	*验证URL
    * @param sMsgSignature: 签名串，对应URL参数的msg_signature
    * @param sTimeStamp: 时间戳，对应URL参数的timestamp
    * @param sNonce: 随机串，对应URL参数的nonce
    * @param sEchoStr: 随机串，对应URL参数的echostr
    * @param sReplyEchoStr: 解密之后的echostr，当return返回0时有效
    * @return：成功0，失败返回对应的错误码
	*/
	public function VerifyURL($sMsgSignature, $sTimeStamp, $sNonce, $sEchoStr, &$sReplyEchoStr)
	{
		


		if (strlen($this->m_sEncodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}

		$pc = new Prpcrypt($this->m_sEncodingAesKey);
		//verify msg_signature

		$array = $this->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $sEchoStr);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		$signature = $array[1];
		if ($signature != $sMsgSignature) {
			return ErrorCode::$ValidateSignatureError;
		}

		$result = $pc->decrypt($sEchoStr, $this->m_sCorpid);
		if ($result[0] != 0) {
			return $result[0];
		}
		$sReplyEchoStr = $result[1];

		return ErrorCode::$OK;
	}
	/**
	 * 将企业微信回复用户的消息加密打包.
	 * <ol>
	 *    <li>对要发送的消息进行AES-CBC加密</li>
	 *    <li>生成安全签名</li>
	 *    <li>将消息密文和安全签名打包成xml格式</li>
	 * </ol>
	 *
	 * @param $replyMsg string 企业微信待回复用户的消息，xml格式的字符串
	 * @param $timeStamp string 时间戳，可以自己生成，也可以用URL参数的timestamp
	 * @param $nonce string 随机串，可以自己生成，也可以用URL参数的nonce
	 * @param &$encryptMsg string 加密后的可以直接回复用户的密文，包括msg_signature, timestamp, nonce, encrypt的xml格式的字符串,
	 *                      当return返回0时有效
	 *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function EncryptMsg($sReplyMsg, $sTimeStamp, $sNonce, &$sEncryptMsg)
	{
		$pc = new Prpcrypt($this->m_sEncodingAesKey);

		//加密
		$array = $pc->encrypt($sReplyMsg, $this->m_sCorpid);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}

		if ($sTimeStamp == null) {
			$sTimeStamp = time();
		}
		$encrypt = $array[1];

		//生成安全签名

		$array = $this->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $encrypt);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}
		$signature = $array[1];

		//生成发送的xml

		$sEncryptMsg = $this->generate($encrypt, $signature, $sTimeStamp, $sNonce);
		return ErrorCode::$OK;
	}


	/**
	 * 检验消息的真实性，并且获取解密后的明文.
	 * <ol>
	 *    <li>利用收到的密文生成安全签名，进行签名验证</li>
	 *    <li>若验证通过，则提取xml中的加密消息</li>
	 *    <li>对消息进行解密</li>
	 * </ol>
	 *
	 * @param $msgSignature string 签名串，对应URL参数的msg_signature
	 * @param $timestamp string 时间戳 对应URL参数的timestamp
	 * @param $nonce string 随机串，对应URL参数的nonce
	 * @param $postData string 密文，对应POST请求的数据
	 * @param &$msg string 解密后的原文，当return返回0时有效
	 *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function DecryptMsg($sMsgSignature, $sTimeStamp = null, $sNonce, $sPostData, &$sMsg)
	{
		if (strlen($this->m_sEncodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}

		$pc = new Prpcrypt($this->m_sEncodingAesKey);

		//提取密文

		$array = $this->extract($sPostData);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		if ($sTimeStamp == null) {
			$sTimeStamp = time();
		}

		$encrypt = $array[1];
		$touser_name = $array[2];

		//验证安全签名
		$array = $this->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $encrypt);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		$signature = $array[1];
		if ($signature != $sMsgSignature) {
			return ErrorCode::$ValidateSignatureError;
		}

		$result = $pc->decrypt($encrypt, $this->m_sCorpid);
		if ($result[0] != 0) {
			return $result[0];
		}
		$sMsg = $result[1];

		return ErrorCode::$OK;
	}


	public function getSHA1($token, $timestamp, $nonce, $encrypt_msg)
	{
		//排序
		try {
			$array = [$encrypt_msg, $token, $timestamp, $nonce];
			sort($array, SORT_STRING);
			$str = implode('',$array);
			return array(ErrorCode::$OK, sha1($str));
		} catch (Exception $e) {
			print $e . "\n";
			return array(ErrorCode::$ComputeSignatureError, null);
		}
	}



	/**
	 * 提取出xml数据包中的加密消息
	 * @param string $xmltext 待提取的xml字符串
	 * @return string 提取出的加密消息字符串
	 */
	public function extract($xmltext)
	{
		try {
			$xml = new DOMDocument();
			$xml->loadXML($xmltext);
			$array_e = $xml->getElementsByTagName('Encrypt');
			$array_a = $xml->getElementsByTagName('ToUserName');
			$encrypt = $array_e->item(0)->nodeValue;
			$tousername = $array_a->item(0)->nodeValue;
			return array(0, $encrypt, $tousername);
		} catch (Exception $e) {
			print $e . "\n";
			return array(ErrorCode::$ParseXmlError, null, null);
		}
	}

	/**
	 * 生成xml消息
	 * @param string $encrypt 加密后的消息密文
	 * @param string $signature 安全签名
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 */
	public function generate($encrypt, $signature, $timestamp, $nonce)
	{
		$format = "<xml>
					   <Encrypt><![CDATA[%s]]></Encrypt>
					   <MsgSignature><![CDATA[%s]]></MsgSignature>
					   <TimeStamp>%s</TimeStamp>
					   <Nonce><![CDATA[%s]]></Nonce>
				   </xml>";
		return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
	}

	/**
	 * 提取出xml数据包中的加密消息
	 * @param string $xmltext 待提取的xml字符串
	 * @return string 提取加密后回调模式接口验证需要的参数
	 */
	public function extractCallbackParamter($xmltext)
	{
		try {				
			$xml = new DOMDocument();
			$xml->loadXML($xmltext);
			$Encrypt = $xml->getElementsByTagName('Encrypt')->item(0)->nodeValue;
			$MsgSignature = $xml->getElementsByTagName('MsgSignature')->item(0)->nodeValue;
			$TimeStamp = $xml->getElementsByTagName('TimeStamp')->item(0)->nodeValue;
			$Nonce = $xml->getElementsByTagName('Nonce')->item(0)->nodeValue;
			
			return array($Encrypt, $MsgSignature, $TimeStamp,$Nonce);
		} catch (Exception $e) {
			print $e . "\n";
			return array(ErrorCode::$ParseXmlError, null, null);
		}
	}

	/**
	 * 生成xml消息
	 * @param string $encrypt 加密后的消息密文
	 * @param string $agentId 应用ID
	 * @param string $tousername 企业ID	 
	 */
	public function generateCallbackXml($encrypt, $agentId, $tousername)
	{
		$format = "<xml>
					   <ToUserName><![CDATA[%s]]></ToUserName>
					   <AgentID><![CDATA[%s]]></AgentID>
					   <Encrypt>%s</Encrypt>					   
				   </xml>";
		return sprintf($format,$tousername,$agentId,$encrypt);
	}
}

?>
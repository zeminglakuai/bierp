<?php
namespace app\includes\QYwechat;

use Yii;
use app\includes\QYwechat\MsgCrypt;
use app\includes\QYwechat\Url;
use app\common\models\QywechatConfig;

class QYwechat{
	const ACCESS_TOKEN_URL = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=%s&corpsecret=%s';

	//需要建立的变量
	public $corpid,$encodingAesKey,$token,$agentId,$secrect,$agent_data_id;
	public $errors;
	//请求得到的变量
	public $access_token;

	function __construct($token, $encodingAesKey, $corpid, $agent_data_id){
		$this->token = $token;
		$this->encodingAesKey = $encodingAesKey;
		$this->corpid = $corpid;
		$this->agent_data_id = $agent_data_id;
		$this->secrect = QywechatConfig::get_secrect($agent_data_id);
		$this->get_token_access();
	}

	function get_token_access(){
		$access_token = QywechatConfig::find()->where(['name'=>'access_token','parent_id'=>$this->agent_data_id])->one();
		
		if ($access_token) {

			if ($access_token['expire'] - time() < 200) {
				//到期重新获取access_token
				$param = ['corpid'=>$this->corpid,'corpsecret'=>$this->secrect];
				$access_token_url = printf(self::ACCESS_TOKEN_URL,$this->corpid,$this->secrect);
				$access_token_result = Url::http_get($url);
 
				$access_token_result = json_decode($access_token_result);
 
				if (isset($access_token_result->errcode ) && $access_token_result->errcode == 0) {
					$access_token->value = $access_token_result->access_token;
					$access_token->expire = time()+7200;
					$access_token->save();
					$this->access_token = $access_token->value;
					return true;
				}else{
					$this->errors[] = ['errcode'=>$this->errcode,'errmsg'=>$this->errmsg];
					return false;
				}
			}else{
				return $this->access_token = $access_token->value;
			}
		}
	}


	function reply(){
		$verfy_arr = Yii::$app->request->get();
	    // 需要返回的明文
	    $EchoStr = "";
	    $wxcpt = new MsgCrypt($this->token, $this->encodingAesKey, $this->corpid);
	    $errCode = $wxcpt->VerifyURL($verfy_arr['msg_signature'], $verfy_arr['timestamp'], $verfy_arr['nonce'], $verfy_arr['echostr'], $EchoStr);

	    if ($errCode == 0) {
	        // 验证URL成功，将sEchoStr返回
	        echo $EchoStr;
	        exit(0);
	    } else {
	        print("ERR: " . $errCode . "\n\n");
	    }
	}

}

?>
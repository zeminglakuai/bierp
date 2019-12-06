<?php
namespace app\includes\QYwechat\module;

use Yii;
use app\includes\QYwechat\MsgCrypt;
use app\includes\QYwechat\Url;
use app\includes\QYwechat\QYwechat;
use app\common\models\QywechatConfig;
use app\includes\QYwechat\Agent;

class Txl {
  
  private $access_token;

  public function __construct() {
    $base_config = QywechatConfig::get_config(1);
    $agent_config = QywechatConfig::get_config(Agent::$agent['txl']['data_id']);
    $QYwechat = new QYwechat($agent_config['token'], $agent_config['encodingAesKey'], $base_config['corpid'],Agent::$agent['txl']['data_id']);
    $this->access_token = $QYwechat->access_token;
  }

  private function appendToken($url){
      $token = $this->access_token;

      if(strrpos($url,"?",0) > -1){
        return $url."&access_token=".$token;
      }else{
        return $url."?access_token=".$token;
      }
  }
  
  /**
   * 根据部门ID来查询下属的所有子部门
   * @param  [Number] $id 部门ID
   */
  public function getDepartmentsById($id){
      if($id > 0){
        return Url::http_get($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/department/list?id=$id"))["content"];
      }else{
        return false;
      }
  }

  /**
   * 创建新的部门  
   * @param  [Array like Object] $data 部门信息
   */
  public function createDepartment($data){

      if($data["name"] && $data["parentid"]){
        return Url::http_post($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/department/create"),$data)["content"];               
      }else{
        return false;     
      }
  }

  /**
   * 更新部门信息  
   * @param  [Array like Object] $data 更新的部门目标信息
   */
  public function updateDepartment($data){
      if($data["name"] && $data["parentid"]){
        return Url::http_post($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/department/update"),$data)["content"];        
      }else{
        return false;      
      }
  }

  /**
   * 根据ID删除指定的部门
   * @param  [Number] $id 被删除部门的ID   
   */
  public function deleteDepartmentById($id){

      if($id > 0){        
        return http_get($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/department/delete?id=$id"))["content"];
      }else{
        return false;
      } 
  }

  /**
   * 创建一个新用户
   * @param  [Array like Object] $data 用户信息   
   */
  public function createUser($data){
      if($data["name"] && $data["userid"] && $data["userid"] && $data["mobile"] && $data["department"]){

        $token = $this->access_token;
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token=$token";
    
        return Url::http_post($url,$data)["content"];        
      }else{
        return false;
      }
  }

  //更新用户信息
  public function updateUser($data){
      if($data["name"] && $data["userid"] && $data["userid"] && $data["mobile"] && $data["department"]){
        return Url::http_post($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/user/update"),$data)["content"];        
      }else{
        return false;    
      }
  }

  //根据用户ID删除用户信息
  public function deleteUserById($id = ""){
      if($id){        
        return Url::http_get($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/user/delete?userid=$id"))["content"];
      }else{
        return false;
      } 
  }

  /**
   * [batchDeleteUser description]
   * @param  [Array like Object] $data 批量删除的用户useridlist
   */
  public function batchDeleteUser($data){
      if($data["useridlist"]){          
          return Url::http_post($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/user/batchdelete"),$data)["content"];  
      }else{
        return false;     
      }
  }

  /**
   * 根据用户查询用户信息
   * @param  [Number] $id 查询的目标用户ID
   */
  public function queryUserById($id = ""){
      if($id){
        return Url::http_get($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/user/get?userid=$id"))["content"];
      }else{
        return false;
      }
  }

  /**
   * 根据部门ID查询用户信息
   * @param  [Number]  $depId    查询的部门ID
   * @param  [integer] $fetchChild 是否遍历子部门
   * @param  [boolean] $simple   是否只查询用户的基本信息
   */
  public function queryUsersByDepartmentId($depId,$fetchChild = 1,$simple = 1){
      if($depId > 0){
        $interface = $simple == 1 ? "simplelist" : "list"; 
        return Url::http_get($this->appendToken("https://qyapi.weixin.qq.com/cgi-bin/user/$interface?department_id=$depId&fetch_child=1"))["content"];        
      }else{
        return false; 
      }
  }
}


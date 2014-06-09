<?php
/**
 * 联合注册返回验证
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: receive.php 16492 2009-07-27 10:16:09Z liuhui $
*/

//商户密钥
$key="65ZS4C5WYKKLLGJN";

//接口版本，不可空
//固定值：150120
$version=$_GET['version'];

//签名类型，不可空
//固定值：1，1代表MD5加密
$signType=$_GET['signType'];

//商户在快钱的会员编号，不可空
$merchantMbrCode=$_GET['merchantMbrCode'];

//申请编号，不可空
//只允许是字母、数字、“_”等，以字母或数字开头
$requestId=$_GET['requestId'];

//用户在商户系统的ID，不可空
//只允许是字母、数字、“_”等，以字母或数字开头
$userId=$_GET['userId'];

//用户的EMAIL
$userEmail=$_GET['userEmail'];

//用户的姓名
//中文或英文
$userName=$_GET['userName'];

//单位名称
//中文或英文
$orgName=$_GET['orgName'];

//扩展参数一
//中文或英文
$ext1=$_GET['ext1'];

//扩展参数二
//中文或英文
$ext2=$_GET['ext2'];

//注册验证结果
//固定值：0、1、2
//0：注册申请成功；1：审核通过；2：激活成功
$applyResult=$_GET['applyResult'];

//错误代码
//失败时返回的错误代码，可以为空
$errorCode=$_GET['errorCode'];

//快钱返回的签名字符串
//以上关键字的值与密钥组合，经MD5加密生成的32位字符串
$signMsg=$_GET['signMsg'];

//功能函数。将变量值不为空的参数组成字符串
Function appendParam($returnStr,$paramId,$paramValue){
    if($returnStr!=""){
        if($paramValue!=""){
            $returnStr.="&".$paramId."=".$paramValue;
        }
    }else{
        If($paramValue!=""){
            $returnStr=$paramId."=".$paramValue;
        }
    }
    return $returnStr;
}
//功能函数。将变量值不为空的参数组成字符串。结束

//自己生成加密签名串
///请务必按照如下顺序和规则组成加密串！
$$signMsgVal="";
$signMsgVal=appendParam($signMsgVal,"version",$version);
$signMsgVal=appendParam($signMsgVal,"signType",$signType);
$signMsgVal=appendParam($signMsgVal,"merchantMbrCode",$merchantMbrCode);
$signMsgVal=appendParam($signMsgVal,"requestId",$requestId);
$signMsgVal=appendParam($signMsgVal,"userId",$userId);
$signMsgVal=appendParam($signMsgVal,"userEmail",$userEmail);
$signMsgVal=appendParam($signMsgVal,"userName",urlencode($userName));
$signMsgVal=appendParam($signMsgVal,"orgName",urlencode($orgName));
$signMsgVal=appendParam($signMsgVal,"ext1",urlencode($ext1));
$signMsgVal=appendParam($signMsgVal,"ext2",urlencode($ext2));
$signMsgVal=appendParam($signMsgVal,"applyResult",$applyResult);
$signMsgVal=appendParam($signMsgVal,"errorCode",$errorCode);
$signMsgVal=appendParam($signMsgVal,"key",$key);

$mysignMsg=strtoupper(md5($signMsgVal));



if($mysignMsg==$signMsg){

            /**
             *  商户进行自己的数据库逻辑处理，比如把接收的信息保存到自己的数据库中
             *  或者是更新自己数据库中用户表的状态
             */

    $status="1";

    $signMsgVal="";
    $signMsgVal=appendParam($signMsgVal,"version",$version);
    $signMsgVal=appendParam($signMsgVal,"signType",$signType);
    $signMsgVal=appendParam($signMsgVal,"merchantMbrCode",$merchantMbrCode);
    $signMsgVal=appendParam($signMsgVal,"requestId",$requestId);
    $signMsgVal=appendParam($signMsgVal,"userId",$userId);
    $signMsgVal=appendParam($signMsgVal,"status",$status);
    $reParam=$signMsgVal;
    $signMsgVal=appendParam($signMsgVal,"key",key);

    $signMsg=strtoupper(md5($signMsgVal));
    $reParam .="&signMsg=".$signMsg;
    echo $reParam; 
}else{
    echo "验证错误";
}
?>
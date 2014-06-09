/* *
 * ECSHOP 表单验证类
 * ============================================================================
 * 版权所有 (C) 2005-2011 康盛创想（北京）科技有限公司，并保留所有权利。
 * 网站地址 : http : // www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这是一个免费开源的软件；这意味着您可以在不用于商业目的的前提下对程序代码
 * 进行修改和再发布。
 * ============================================================================
 * $Author : paulgao $
 * $Date : 2007-01-31 16:23:56 +0800 (星期三, 31 一月 2007) $
 * $Id : validator.js 4824 2007-01-31 08:23:56Z paulgao $

 *//* *
 * 表单验证类
 *
 * @author : weber liu
 * @version : v1.1
 */

var Validator = function(name)
{
  this.formName = name;
  this.errMsg = new Array();

  /* *
  * 检查用户是否输入了内容
  *
  * @param :  controlId   表单元素的ID
  * @param :  msg         错误提示信息
  */
  this.required = function(controlId, msg)
  {
    var obj = document.forms[this.formName].elements[controlId];
    if (typeof(obj) == "undefined" || Utils.trim(obj.value) == "")
    {
      this.addErrorMsg(msg);
    }
  }
  ;

  /* *
  * 检查用户输入的是否为合法的邮件地址
  *
  * @param :  controlId   表单元素的ID
  * @param :  msg         错误提示信息
  * @param :  required    是否必须
  */
  this.isEmail = function(controlId, msg, required)
  {
    var obj = document.forms[this.formName].elements[controlId];
    obj.value = Utils.trim(obj.value);

    if ( ! required && obj.value == '')
    {
      return;
    }

    if ( ! Utils.isEmail(obj.value))
    {
      this.addErrorMsg(msg);
    }
  }

  /* *
  * 检查两个表单元素的值是否相等
  *
  * @param : fstControl   表单元素的ID
  * @param : sndControl   表单元素的ID
  * @param : msg         错误提示信息
  */
  this.eqaul = function(fstControl, sndControl, msg)
  {
    var fstObj = document.forms[this.formName].elements[fstControl];
    var sndObj = document.forms[this.formName].elements[sndControl];

    if (fstObj != null && sndObj != null)
    {
      if (fstObj.value == '' || fstObj.value != sndObj.value)
      {
        this.addErrorMsg(msg);
      }
    }
  }

  /* *
  * 检查前一个表单元素是否大于后一个表单元素
  *
  * @param : fstControl   表单元素的ID
  * @param : sndControl   表单元素的ID
  * @param : msg                错误提示信息
  */
  this.gt = function(fstControl, sndControl, msg)
  {
    var fstObj = document.forms[this.formName].elements[fstControl];
    var sndObj = document.forms[this.formName].elements[sndControl];

    if (fstObj != null && sndObj != null) {
      if (Utils.isNumber(fstObj.value) && Utils.isNumber(sndObj.value)) {
        var v1 = parseFloat(fstObj.value) + 0;
        var v2 = parseFloat(sndObj.value) + 0;
      } else {
        var v1 = fstObj.value;
        var v2 = sndObj.value;
      }

      if (v1 <= v2) this.addErrorMsg(msg);
    }
  }

  /* *
  * 检查输入的内容是否是一个数字
  *
  * @param :  controlId   表单元素的ID
  * @param :  msg         错误提示信息
  * @param :  required    是否必须
  */
  this.isNumber = function(controlId, msg, required)
  {
    var obj = document.forms[this.formName].elements[controlId];
    obj.value = Utils.trim(obj.value);

    if (obj.value == '' && ! required)
    {
      return;
    }
    else
    {
      if ( ! Utils.isNumber(obj.value))
      {
        this.addErrorMsg(msg);
      }
    }
  }

  /* *
  * 检查输入的内容是否是一个整数
  *
  * @param :  controlId   表单元素的ID
  * @param :  msg         错误提示信息
  * @param :  required    是否必须
  */
  this.isInt = function(controlId, msg, required)
  {

    if (document.forms[this.formName].elements[controlId])
    {
      var obj = document.forms[this.formName].elements[controlId];
    }
    else
    {
      return;    
    }

    obj.value = Utils.trim(obj.value);

    if (obj.value == '' && ! required)
    {
      return;
    }
    else
    {
      if ( ! Utils.isInt(obj.value)) this.addErrorMsg(msg);
    }
  }

  /* *
  * 检查输入的内容是否是为空
  *
  * @param :  controlId   表单元素的ID
  * @param :  msg         错误提示信息
  * @param :  required    是否必须
  */
  this.isNullOption = function(controlId, msg)
  {
    var obj = document.forms[this.formName].elements[controlId];

    obj.value = Utils.trim(obj.value);

    if (obj.value > '0' )
    {
      return;
    }
    else
    {
      this.addErrorMsg(msg);
    }
  }

  /* *
  * 检查输入的内容是否是"2006-11-12 12:00:00"格式
  *
  * @param :  controlId   表单元素的ID
  * @param :  msg         错误提示信息
  * @param :  required    是否必须
  */
  this.isTime = function(controlId, msg, required)
  {
    var obj = document.forms[this.formName].elements[controlId];
    obj.value = Utils.trim(obj.value);

    if (obj.value == '' && ! required)
    {
      return;
    }
    else
    {
      if ( ! Utils.isTime(obj.value)) this.addErrorMsg(msg);
    }
  }
  
  /* *
  * 检查前一个表单元素是否小于后一个表单元素(日期判断)
  *
  * @param : controlIdStart   表单元素的ID
  * @param : controlIdEnd     表单元素的ID
  * @param : msg              错误提示信息
  */
  this.islt = function(controlIdStart, controlIdEnd, msg)
  {
    var start = document.forms[this.formName].elements[controlIdStart];
    var end = document.forms[this.formName].elements[controlIdEnd];
    start.value = Utils.trim(start.value);
    end.value = Utils.trim(end.value);

    if(start.value <= end.value)
    {
      return;
    }
    else
    {
      this.addErrorMsg(msg);
    }
  }

  /* *
  * 检查指定的checkbox是否选定
  *
  * @param :  controlId   表单元素的name
  * @param :  msg         错误提示信息
  */
  this.requiredCheckbox = function(chk, msg)
  {
    var obj = document.forms[this.formName].elements[controlId];
    var checked = false;

    for (var i = 0; i < objects.length; i ++ )
    {
      if (objects[i].type.toLowerCase() != "checkbox") continue;
      if (objects[i].checked)
      {
        checked = true;
        break;
      }
    }

    if ( ! checked) this.addErrorMsg(msg);
  }

  this.passed = function()
  {
    if (this.errMsg.length > 0)
    {
      var msg = "";
      for (i = 0; i < this.errMsg.length; i ++ )
      {
        msg += "- " + this.errMsg[i] + "\n";
      }

      alert(msg);
      return false;
    }
    else
    {
      return true;
    }
  }

  /* *
  * 增加一个错误信息
  *
  * @param :  str
  */
  this.addErrorMsg = function(str)
  {
    this.errMsg.push(str);
  }
}

/* *
 * 帮助信息的显隐函数
 */
function showNotice(objId)
{
  var obj = document.getElementById(objId);

  if (obj)
  {
    if (obj.style.display != "block")
    {
      obj.style.display = "block";
    }
    else
    {
      obj.style.display = "none";
    }
  }
}

/* *
 * add one option of a select to another select.
 *
 * @author  Chunsheng Wang < wwccss@263.net >
 */
function addItem(src, dst)
{
  for (var x = 0; x < src.length; x ++ )
  {
    var opt = src.options[x];
    if (opt.selected && opt.value != '')
    {
      var newOpt = opt.cloneNode(true);
      newOpt.className = '';
      newOpt.text = newOpt.innerHTML.replace(/^\s+|\s+$|&nbsp;/g, '');
      dst.appendChild(newOpt);
    }
  }

  src.selectedIndex = -1;
}

/* *
 * move one selected option from a select.
 *
 * @author  Chunsheng Wang < wwccss@263.net >
 */
function delItem(ItemList)
{
  for (var x = ItemList.length - 1; x >= 0; x -- )
  {
    var opt = ItemList.options[x];
    if (opt.selected)
    {
      ItemList.options[x] = null;
    }
  }
}

/* *
 * join items of an select with ",".
 *
 * @author  Chunsheng Wang < wwccss@263.net >
 */
function joinItem(ItemList)
{
  var OptionList = new Array();
  for (var i = 0; i < ItemList.length; i ++ )
  {
    OptionList[OptionList.length] = ItemList.options[i].text + "|" + ItemList.options[i].value;
  }
  return OptionList.join(",");
}

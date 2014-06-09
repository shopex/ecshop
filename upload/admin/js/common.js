/* $Id : common.js 4824 2007-01-31 08:23:56Z paulgao $ */

/* 检查新订单的时间间隔 */
var NEW_ORDER_INTERVAL = 180000;

/* *
 * 开始检查新订单；
 */
function startCheckOrder()
{
  checkOrder()
  window.setInterval("checkOrder()", NEW_ORDER_INTERVAL);
}

/*
 * 检查订单
 */
function checkOrder()
{
  var lastCheckOrder = new Date(document.getCookie('ECS_LastCheckOrder'));
  var today = new Date();

  if (lastCheckOrder == null || today-lastCheckOrder >= NEW_ORDER_INTERVAL)
  {
    document.setCookie('ECS_LastCheckOrder', today.toGMTString());

    try
    {
      Ajax.call('index.php?is_ajax=1&act=check_order','', checkOrderResponse, 'GET', 'JSON');
    }
    catch (e) { }
  }
}

/* *
 * 处理检查订单的反馈信息
 */
function checkOrderResponse(result)
{
  //出错屏蔽
  if (result.error != 0 || (result.new_orders == 0 && result.new_paid == 0))
  {
    return;
  }
  try
  {
    document.getElementById('spanNewOrder').innerHTML = result.new_orders;
    document.getElementById('spanNewPaid').innerHTML = result.new_paid;
    Message.show();
  }
  catch (e) { }
}

/**
 * 确认后跳转到指定的URL
 */
function confirm_redirect(msg, url)
{
  if (confirm(msg))
  {
    location.href=url;
  }
}

/* *
 * 设置页面宽度
 */
function set_size(w)
{
  var y_width = document.body.clientWidth
  var s_width = screen.width
  var agent   = navigator.userAgent.toLowerCase();

  if (y_width < w)
  {
    if (agent.indexOf("msie") != - 1)
    {
      document.body.style.width = w + "px";
    }
    else
    {
      document.getElementById("bd").style.width = (w - 10) + 'px';
    }
  }
}

/* *
 * 显示隐藏图片
 * @param   id  div的id
 * @param   show | hide
 */
function showImg(id, act)
{
  if (act == 'show')
  {
    document.getElementById(id).style.visibility = 'visible';
  }
  else
  {
    document.getElementById(id).style.visibility = 'hidden';
  }
}

/*
 * 气泡式提示信息
 */
var Message = Object();

Message.bottom  = 0;
Message.count   = 0;
Message.elem    = "popMsg";
Message.mvTimer = null;

Message.show = function()
{
  try
  {
    Message.controlSound('msgBeep');
    document.getElementById(Message.elem).style.visibility = "visible"
    document.getElementById(Message.elem).style.display = "block"

    Message.bottom  = 0 - parseInt(document.getElementById(Message.elem).offsetHeight);
    Message.mvTimer = window.setInterval("Message.move()", 10);

    document.getElementById(Message.elem).style.bottom = Message.bottom + "px";
  }
  catch (e)
  {
    alert(e);
  }
}

Message.move = function()
{
  try
  {
    if (Message.bottom == 0)
    {
      window.clearInterval(Message.mvTimer)
      Message.mvTimer = window.setInterval("Message.close()", 10000)
    }

    Message.bottom ++ ;
    document.getElementById(Message.elem).style.bottom = Message.bottom + "px";
  }
  catch (e)
  {
    alert(e);
  }
}

Message.close = function()
{
  document.getElementById(Message.elem).style.visibility = 'hidden';
  document.getElementById(Message.elem).style.display = 'none';
  if (Message.mvTimer) window.clearInterval(Message.mvTimer)
}

Message.controlSound = function(_sndObj)
{
  sndObj = document.getElementById(_sndObj);

  try
  {
    sndObj.Play();
  }
  catch (e) { }
}

var listZone = new Object();

/* *
 * 显示正在载入
 */
listZone.showLoader = function()
{
  listZone.toggleLoader(true);
}

listZone.hideLoader = function()
{
  listZone.toggleLoader(false);
}

listZone.toggleLoader = function(disp)
{
  document.getElementsByTagName('body').item(0).style.cursor = (disp) ? "wait" : 'auto';

  try
  {
    var doc = top.frames['header-frame'].document;
    var loader = doc.getElementById("load-div");

    if (typeof loader == 'object') loader.style.display = disp ? "block" : "none";
  }
  catch (ex) { }
}

function $import(path,type,title){
  var s,i;
  if(type == "js"){
    var ss = document.getElementsByTagName("script");
    for(i =0;i < ss.length; i++)
    {
      if(ss[i].src && ss[i].src.indexOf(path) != -1)return ss[i];
    }
    s      = document.createElement("script");
    s.type = "text/javascript";
    s.src  =path;
  }
  else if(type == "css")
  {
    var ls = document.getElementsByTagName("link");
    for(i = 0; i < ls.length; i++)
    {
      if(ls[i].href && ls[i].href.indexOf(path)!=-1)return ls[i];
    }
    s          = document.createElement("link");
    s.rel      = "alternate stylesheet";
    s.type     = "text/css";
    s.href     = path;
    s.title    = title;
    s.disabled = false;
  }
  else return;
  var head = document.getElementsByTagName("head")[0];
  head.appendChild(s);
  return s;
}

/**
 * 返回随机数字符串
 *
 * @param : prefix  前缀字符
 *
 * @return : string
 */
function rand_str(prefix)
{
  var dd = new Date();
  var tt = dd.getTime();
  tt = prefix + tt;

  var rand = Math.random();
  rand = Math.floor(rand * 100);

  return (tt + rand);
}
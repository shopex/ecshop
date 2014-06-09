<!-- $Id: setting_first.htm 16339 2009-06-24 08:01:25Z liuhui $ -->
{include file="pageheader.htm"}
{insert_scripts files="../js/utils.js,../js/region.js"}
<div class="main-div">
<div class="step1">{$lang.shop_basic_first}</em></div>
<form action="index.php?act=second" name="theForm" method="post" onsubmit="return validator()" >
  <table cellspacing="1" cellpadding="3" width="100%">
    <tr>
      <td class="micro-label">{$lang.shop_name}:</td>
      <td><input type="text" name="shop_name" value="{$shop_name}" /></td>
    </tr>
    <tr>
      <td class="micro-label">{$lang.shop_title}:</td>
      <td><input type="text" name="shop_title" value="{$shop_title}" /></td>
    </tr>
    <tr>
      <td class="micro-label">{$lang.shop_country}:</td>
      <td>
         <select name="shop_country" id="selCountries" onchange="region.changed(this, 1, 'selProvinces')">
           <option value=''>{$lang.select_please}</option>
           {foreach from=$countries item=region}
             <option value="{$region.region_id}" {if $region.region_id eq 1}selected{/if}>{$region.region_name}</option>
           {/foreach}
         </select>                
       </td>
    </tr>
    <tr>
      <td class="micro-label">{$lang.shop_province}:</td>
      <td>
         <select name="shop_province" id="selProvinces" onchange="region.changed(this, 2, 'selCities')">
           <option value=''>{$lang.select_please}</option>
             {foreach from=$provinces item=region}
               <option value="{$region.region_id}" {if $region.region_id eq 2}selected{/if}> {$region.region_name}</option>
             {/foreach}
         </select>
      </td>
    </tr>
    <tr>
      <td class="micro-label">{$lang.shop_city}:</td>
      <td>
        <select name="shop_city" id="selCities">
          <option value=''>{$lang.select_please}</option>
            {foreach from=$cities item=region}
              <option value="{$region.region_id}" {if $region.region_id eq 35}selected{/if}>{$region.region_name}</option>
            {/foreach}
        </select>
      </td>
    </tr>
    <tr>
      <td class="micro-label">{$lang.shop_address}:</td>
      <td><input type="text" name="shop_address" /></td>
    </tr>
    <tr>
      <td class="micro-label">{$lang.shop_ship}:</td>
      <td>
        <select name="shipping" id="shipping_type" onchange="show_shipping_area()">
          <option value=''>{$lang.select_please}</option>
            {foreach from=$modules item=module}
              <option value="{$module.code}" >{$module.name}</option>
            {/foreach}
        </select>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <div id="shipping_area" style="display:none">
          <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
              <td width="80">{$lang.ship_name}:</td>
              <td><input type="text" name="area_name" style="margin-bottom:5px;vertical-align:middle"></td>
            </tr>
            <tr>
              <td>{$lang.ship_country}:</td>
              <td>
	          <select name="shipping_country" id="sel_Countries" onchange="region.changed(this, 1, 'sel_Provinces')">
	           {foreach from=$countries item=region}
	             <option value="{$region.region_id}" >{$region.region_name}</option>
	           {/foreach}
	         </select> 
              </td>
            </tr>
            <tr>
              <td>{$lang.ship_province}:</td>
              <td>
	          <select name="shipping_province" id="sel_Provinces" onchange="region.changed(this, 2, 'sel_Cities')">
	           <option value=''>{$lang.select_please}</option>
	             {foreach from=$provinces item=region}
	               <option value="{$region.region_id}" >{$region.region_name}</option>
	             {/foreach}
	         </select>
              </td>
            </tr>
             <tr>
              <td>{$lang.ship_city}:</td>
              <td>
	          <select name="shipping_city" id="sel_Cities" onchange="region.changed(this, 3, 'sel_Districts')">
	          <option value=''>{$lang.select_please}</option>
	          </select> 
              </td>
            </tr>
             <tr>
              <td>{$lang.select_please}</td>
              <td>
	          <select name="shipping_district" id="sel_Districts">
	            <option value=''>{$lang.select_please}</option>
	          </select>
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
    <tr>
      <td class="micro-label">{$lang.shop_pay}:</td>
      <td>
			  <table border="0" cellpadding="0" cellspacing="0" style="margin-top:-10px"><tr><td width="50"><select name="payment" id="payment_type" onchange="loadConfig()" >
          <option value=''>{$lang.select_please}</option>
			    
            {foreach from=$modules_payment item=module}
              
			    <option value="{$module.code}" >{$module.name}</option>
			    
            {/foreach}
        
			    </select></td>
			  <td>	<div align="left"><span id="paynon">{$lang.alipay_login}</span>
            </div></td></tr></table>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <div id="paymentConfig" style="display:none">
        
        </div>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        <input type="submit" value={$lang.next_step} class="button" />
        <input type="button" value={$lang.skip} onclick="javascript:window.location.href='index.php?act=main'" class="button" />
      </td>
    </tr>
  </table>
</form>
</div>
{insert_scripts files="validator.js"}
<script type="Text/Javascript" language="JavaScript">
<!--
{literal}
region.isAdmin = true;

onload = function()
{
   document.getElementById('paynon').style.display = 'none';
}

function validator()
{
  var validator = new Validator('theForm');
  validator.required('shop_name', shop_name_not_null);
  
  return validator.passed();
}

function show_shipping_area()
{
  Ajax.call('shipping.php?act=shipping_priv', '', shippingResponse, 'GET', 'JSON');
}

function shippingResponse(result)
{
  var shipping_name = document.getElementById('shipping_type');
  if (result.error == '1' && result.message != '')
  {
    alert(result.message);
    shipping_name.options[0].selected = true;
    return;
  }
  
  var area = document.getElementById('shipping_area');
  if(shipping_name.value == '')
  {
    area.style.display = 'none';
  }
  else
  {
    area.style.display = "block";
  }
}

function loadConfig()
{
  var payment = document.forms['theForm'].elements['payment'];
  var paymentConfig = document.getElementById('paymentConfig');
  if(payment.value == '')
  {
    paymentConfig.style.display = 'none';
    return;
  }
  else
  {
    paymentConfig.style.display = 'block';
  }
	if(payment.value == 'alipay')
  {
	  document.getElementById('paynon').style.display = 'block';
	}
	else
	{
	  document.getElementById('paynon').style.display = 'none';
	}
  var params = 'code=' + payment.value;

  Ajax.call('payment.php?is_ajax=1&act=get_config', params, showConfig, 'GET', 'JSON');
}

function showConfig(result)
{
  var payment = document.forms['theForm'].elements['payment'];
  if (result.error == '1' && result.message != '')
  {
    alert(result.message);
    payment.options[0].selected = true;
    return;
  }
  var paymentConfig = document.getElementById('paymentConfig');
  var config = result.content;

  paymentConfig.innerHTML = config;
}

{/literal}
//-->
</script>
{include file="pagefooter.htm"}

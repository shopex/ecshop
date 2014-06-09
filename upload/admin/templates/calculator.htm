<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- $Id: calculator.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> {$lang.calculator} </title>
  {insert_scripts files="../js/utils.js"}
  {literal}
  <style type="text/css">
    body, div, input {
      font: 12px arial;
    }

    .calculatorButton {
        text-align: center;
        width: 73px;
    }

    .calculatorButton2 {
        text-align: center;
        width: 154px;
    }
    
    *+html .calculatorButton2  {
        width: 157px;
    }

    #calculator .buttonArea {
        padding: 3px;
        border-color: #455690 #a6b4cf #a6b4cf #455690 ;
        border-style: solid;
        border-width: 1px;
    }

    #calculatorOutput {
        padding: 2px; border:2px inset; margin: 2px;
    }

    #topbar{
        position:absolute;
        border-right: #455690 1px solid;
        border-top: #a6b4cf 1px solid;
        border-left: #a6b4cf 1px solid;
        border-bottom: #455690 1px solid;
        background-color: #c9d3f3;
        width: 300px;
        visibility: hidden;
        z-index: 99999;
        filter: progid:DXImageTransform.Microsoft.BasicImage(opacity=.65);
        opacity: 0.65;
    }
  </style>
  <script type="text/javascript">
  <!--
  var Calculator = Object();

  Calculator.result = 0;
  Calculator.current = '';
  Calculator.values = 0;
  Calculator.handle = '';

  Calculator.elem = function(){ return document.getElementById('calculator'); };
  Calculator.output = function() { return document.getElementById('calculatorOutput'); }

  Calculator.input = function(n)
  {
    if (n == "."&& Calculator.current.toString().indexOf('.')>=0)
    {
      return;
    }
    var output = Calculator.output();

    if (Calculator.current == "0" && n != ".") Calculator.current = '';

    Calculator.current += "" + n;

    output.innerHTML = Calculator.current;
  }

  Calculator.backspace = function()
  {
    var output = Calculator.output();
    output.innerHTML = output.innerHTML.length > 1 ? output.innerHTML.substr(0, output.innerHTML.length-1) : 0;

    Calculator.current = output.innerHTML;
  }

  Calculator.clear = function()
  {
    Calculator.result = 0;
    Calculator.current = '';
    Calculator.values = 0;
    Calculator.handle = '';

    var output = Calculator.output();
    output.innerHTML = "0";
  }

  Calculator.calculate = function(p)
  { 
    if (Calculator.handle != '' && Calculator.values != '' && Calculator.current != '')
    {
      try
      {
        var value = eval(Calculator.values + Calculator.handle + Calculator.current)
        Calculator.values = value == 'Infinity' ? 0 : value;
        Calculator.output().innerHTML  = Calculator.values
      }
      catch (e)
      {
        alert(e);
      }
    }
    else
    {
      Calculator.values = Calculator.current=='' ? Calculator.values : Calculator.current;
    }

    if (p == '=')
    {
      Calculator.output().innerHTML = Calculator.values == '' ? '0' : Calculator.values;
      Calculator.current = Calculator.values;
      Calculator.handle = '';
    }
    else
    {
      Calculator.handle = p;
    }

    Calculator.current = '';
  }

  onload = function() {
    window.focus();
  }
  //-->
  </script>
  {/literal}
 </head>

 <body style="background:buttonFace">
    <div class="buttonArea">
      <div id="calculatorOutput" style="width:95%; text-align:right;border:2px inset;background:#FFF;">0</div>
      <table width="100%">
      <tr>
        <td colspan="2"><input type="button" class="calculatorButton2" value="{$lang.clear_calculator}" onclick="Calculator.clear()" /></td>
        <td colspan="2"><input type="button" class="calculatorButton2" value="{$lang.backspace}" onclick="Calculator.backspace()" /></td>
      </tr>
      <tr>
        <td><input class="calculatorButton" type="button" value="7" onclick="Calculator.input(7)" /></td>
        <td><input type="button" value="8" class="calculatorButton" onclick="Calculator.input(8)" /></td>
        <td><input type="button" value="9" class="calculatorButton" onclick="Calculator.input(9)" /></td>
        <td><input type="button" value="/" class="calculatorButton" onclick="Calculator.calculate('/')" /></td>
      </tr>
      <tr>
        <td><input type="button" value="4" class="calculatorButton" onclick="Calculator.input(4)" /></td>
        <td><input type="button" value="5" class="calculatorButton" onclick="Calculator.input(5)" /></td>
        <td><input type="button" value="6" class="calculatorButton" onclick="Calculator.input(6)" /></td>
        <td><input type="button" value="*" class="calculatorButton" onclick="Calculator.calculate('*')" /></td>
      </tr>
      <tr>
        <td><input type="button" value="1" class="calculatorButton" onclick="Calculator.input(1)" /></td>
        <td><input type="button" value="2" class="calculatorButton" onclick="Calculator.input(2)" /></td>
        <td><input type="button" value="3" class="calculatorButton" onclick="Calculator.input(3)" /></td>
        <td><input type="button" value="-" class="calculatorButton" onclick="Calculator.calculate('-')" /></td>
      </tr>
      <tr>
        <td><input type="button" value="0" class="calculatorButton" onclick="Calculator.input(0)" /></td>
        <td><input type="button" value="." class="calculatorButton" onclick="Calculator.input('.')" /></td>
        <td><input type="button" value="=" class="calculatorButton" onclick="Calculator.calculate('=')" /></td>
        <td><input type="button" value="+" class="calculatorButton" onclick="Calculator.calculate('+')" /></td>
      </tr>
      <tr>
        <td height="38">&nbsp;</td>
        <td colspan="2"><div align="center"><a href="#" onclick="top.close()">{$lang.close_window}</a></div></td>
        <td>&nbsp;</td>
      </tr>
      </table>
    </div>
 </body>

 <script type="text/javascript">
 <!--
  {literal}

  document.onkeyup = function(e)
  {
    var evt = Utils.fixEvent(e);

    if ((evt.keyCode >= 48 && evt.keyCode <= 57 && !evt.shiftKey) ||
        (evt.keyCode >= 96 && evt.keyCode <= 105 && !evt.shiftKey))
    {
      if (evt.keyCode > 57)
      {
        Calculator.input(evt.keyCode - 96);
      }
      else
      {
        Calculator.input(evt.keyCode - 48);
      }
    }
    else if ((evt.keyCode == 107 && !evt.shiftKey) || (evt.keyCode == 61 && evt.shiftKey) || (evt.keyCode == 187 && evt.shiftKey))
    {
      Calculator.calculate('+');
    }
    else if ((evt.keyCode == 109 && !evt.shiftKey) || (evt.keyCode == 189 && !evt.shiftKey))
    {
      Calculator.calculate('-');
    }
    else if ((evt.keyCode == 106 && !evt.shiftKey) || (evt.keyCode == 56 && evt.shiftKey))
    {
      Calculator.calculate('*');
    }
    else if ((evt.keyCode == 111 && !evt.shiftKey) || (evt.keyCode == 191 && !evt.shiftKey))
    {
      Calculator.calculate('/');
    }
    else if (evt.keyCode == 13 || (evt.keyCode == 61 && !evt.shiftKey) || (evt.keyCode == 187 && !evt.shiftKey))
    {
      Calculator.calculate('=');
    }
    else if ((evt.keyCode == 110 && !evt.shiftKey) || (evt.keyCode == 190 && !evt.shiftKey))
    {
        Calculator.input('.');
    }
    else if (evt.keyCode == 27)
    {
      Calculator.clear();
    }
    else if (evt.keyCode == 8)
    {
      Calculator.backspace();
    }

    return false;

    //alert(evt.keyCode);
  }
  {/literal}
 //-->
 </script>
</html>

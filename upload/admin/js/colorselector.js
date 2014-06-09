var ColorSelecter = new Object();

ColorSelecter.Show = function(sender)
{
  if(ColorSelecter.box)
  {
    if (ColorSelecter.box.style.display = "none")
      ColorSelecter.box.style.display = "";
  }
  else
  {
    ColorSelecter.box = document.createElement("Div");
    ColorSelecter.box.id = "ColorSelectertBox";
    var table = "<table width='93' border='1' cellpadding='0' cellspacing='0' bordercolor='#BDBBBC' style='border:2px #C5D9FE solid'><tr><td bgcolor='#FFFFFF'>&nbsp;</td><td bgcolor='#C0C0C0'>&nbsp;</td><td bgcolor='#969696'>&nbsp;</td><td bgcolor='#808080'>&nbsp;</td><td bgcolor='#333333'>&nbsp;</td></tr><tr><td bgcolor='#CC99FE'>&nbsp;</td><td bgcolor='#993365'>&nbsp;</td><td bgcolor='#81007F'>&nbsp;</td><td bgcolor='#6766CC'>&nbsp;</td><td bgcolor='#343399'>&nbsp;</td></tr><tr><td bgcolor='#BBBBBB'>&nbsp;</td><td bgcolor='#00CCFF'>&nbsp;</td><td bgcolor='#3366FF'>&nbsp;</td><td bgcolor='#0000FE'>&nbsp;</td><td bgcolor='#010080'>&nbsp;</td></tr><tr><td bgcolor='#CDFFFF'>&nbsp;</td><td bgcolor='#01FFFF'>&nbsp;</td><td bgcolor='#33CBCC'>&nbsp;</td><td bgcolor='#008081'>&nbsp;</td><td bgcolor='#003265'>&nbsp;</td></tr><tr><td bgcolor='#CDFFCC'>&nbsp;</td><td bgcolor='#00FF01'>&nbsp;</td><td bgcolor='#339967'>&nbsp;</td><td bgcolor='#008002'>&nbsp;</td><td bgcolor='#013300'>&nbsp;</td></tr><tr><td bgcolor='#FFFE99'>&nbsp;</td><td bgcolor='#FFFE03'>&nbsp;</td><td bgcolor='#99CD00'>&nbsp;</td><td bgcolor='#807F01'>&nbsp;</td><td bgcolor='#333301'>&nbsp;</td></tr><tr><td bgcolor='#FFCB99'>&nbsp;</td><td bgcolor='#FFCD00'>&nbsp;</td><td bgcolor='#FF9900'>&nbsp;</td><td bgcolor='#FD6600'>&nbsp;</td><td bgcolor='#993400'>&nbsp;</td></tr><tr><td bgcolor='#FF99CB'>&nbsp;</td><td bgcolor='#FF00FE'>&nbsp;</td><td bgcolor='#FE0000'>&nbsp;</td><td bgcolor='#800000'>&nbsp;</td><td bgcolor='#000000'>&nbsp;</td></tr><tr><td align='center' colspan='5'><font style='cursor:default'>" + cancel_color + "</font></td></tr></table>";
    ColorSelecter.box.innerHTML = table;
    document.body.appendChild(ColorSelecter.box);
    var myTable = ColorSelecter.box.childNodes[0];
    for (var i = 0; i<myTable.rows.length; i++)
    {
      for (var j = 0; j < myTable.rows[i].cells.length; j++)
      {
        myTable.rows[i].cells[j].style.border = "#BDBBBC 1px solid";
        myTable.rows[i].cells[j].onmousemove = function()
        {
          this.style.border = "#fff 1px solid";
        }
        myTable.rows[i].cells[j].onmouseout = function()
        {
          this.style.border = "#BDBBBC 1px solid";
        }
        myTable.rows[i].cells[j].onmousedown = function()
        {
          document.getElementById("font_color").style.backgroundColor = this.bgColor;
          document.getElementById("goods_name_color").value = this.bgColor;
          document.getElementsByName("goods_name").item(0).style.color = this.bgColor;
          ColorSelecter.box.style.display = "none";
        }
      }
    }
  }
  var pos = getPosition(sender);
  ColorSelecter.box.style.top  = pos.top + 18 + "px";
  ColorSelecter.box.style.left = pos.left + "px";

  document.onmousedown = function()
  {
    ColorSelecter.box.style.display = "none";
  }

}

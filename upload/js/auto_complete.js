/* $Id : auto_complete.js 4865 2007-01-31 14:04:10Z paulgao $ */

function autoComplete(obj, hidden, url, callback)
{
  this.borderStyle = '1px solid #000';
  this.highlight = '#000080';
  this.highlightText = '#FFF';
  this.background = '#FFF';
  this.params = '';

  var textbox = obj;
  var hidden = hidden;
  var oldValue = '';
  var flag = false;
  var url = url;

  this.call = function()
  {
    if (flag)
    {
      flag = false;
      return;
    }

    if (url == '')
    {
      return;
    }

    if (oldValue != '' && oldValue == textbox.value)
    {
      return;
    }
    else
    {
      oldValue = textbox.value;
    }

    if (textbox.value != '')
    {
      Transport.run(url, "arg=" + textbox.value, this.show, 'get', 'json');
    }
    else
    {
      var layer = document.getElementById('AC_layer');

      if (layer)
      {
        layer.style.display = 'none';
      }
    }
  }

  var _ac = this;

  this.show = function(result)
  {
    var obj = result;
    var layer = null;

    if (document.getElementById('AC_layer'))
    {
      layer = document.getElementById('AC_layer');
      layer.innerHTML = '';
      layer.style.display = 'block';
    }
    else
    {
      layer = document.createElement('DIV');
      layer.id = 'AC_layer';
      document.body.appendChild(layer);
    }

    pos = getPosition();

    layer.style.left = pos.x + 'px';
    layer.style.top = pos.y + 'px';
    layer.style.width = textbox.clientWidth + 'px';
    layer.style.position = 'absolute';
    layer.style.border = _ac.borderStyle;
    layer.style.background = _ac.background;

    createMenuItem(obj, layer);
    textbox.onkeyup = function(e)
    {
      var evt = fixEvent(e);

      if (evt.keyCode == '38')
      {
        highlightItem('prev');
      }

      if (evt.keyCode == '40')
      {
        highlightItem('next');
      }
    }

    textbox.onblur = function()
    {
      var layer = document.getElementById('AC_layer');

      if (layer)
      {
        layer.style.display = 'none';
      }
    }
  }

  var createMenuItem = function(obj, layer)
  {
    var lst = document.createElement('UL');

    lst.style.listStyle = 'none';
    lst.style.margin = '0';
    lst.style.padding = '1px';
    lst.style.display = 'block';

    for (i = 0; i < obj.length; i ++ )
    {
      if (typeof(obj[i].key) === "undefined" || typeof(obj[i].val) === "undefined")
      {
        throw new Error('Error data.');
        break;
      }
      else
      {
        var listItem = document.createElement('LI');

        listItem.id = obj[i].key;
        listItem.innerHTML = obj[i].val;
        listItem.title = obj[i].val;
        listItem.style.cursor = "default";
        listItem.style.pointer = "default";
        listItem.style.margin = '0px';
        listItem.style.padding = '0px';
        listItem.style.display = 'block';
        listItem.style.width = '100%';
        listItem.style.height = '16px';
        listItem.style.overflow = 'hidden';

        listItem.onmouseover = function(e)
        {
          for (i = 0; i < this.parentNode.childNodes.length;
          i ++ )
          {
            this.parentNode.childNodes[i].style.backgroundColor = '';
            this.parentNode.childNodes[i].style.color = '';
          }
          this.style.backgroundColor = _ac.highlight;
          this.style.color = _ac.highlightText;

          assign(this);
        }
        ;
        listItem.onmouseout = function(e)
        {
          this.style.backgroundColor = '';
          this.style.color = '';
        }
        ;

        lst.appendChild(listItem);
      }
    }

    layer.appendChild(lst);
  }

  var getPosition = function()
  {
    var obj = textbox;
    var pos =
    {
      "x" : 0, "y" : 0
    }

    pos.x = document.body.offsetLeft;
    pos.y = document.body.offsetTop + textbox.clientHeight + 3;

    do
    {
      pos.x += obj.offsetLeft;
      pos.y += obj.offsetTop;

      obj = obj.offsetParent;
    }
    while (obj.tagName.toUpperCase() != 'BODY')

    return pos;
  }

  var fixEvent = function(e)
  {
    return (typeof e == "undefined") ? window.event : e;
  }

  var highlightItem = function(direction)
  {
    var layer = document.getElementById('AC_layer');
    var list = null;
    var item = null;

    if (layer)
    {
      list = layer.childNodes[0];
    }

    if (list)
    {
      for (i = 0; i < list.childNodes.length; i ++ )
      {
        if (list.childNodes[i].style.backgroundColor == _ac.highlight)
        {
          if (direction == 'prev')
          {
            item = (i > 0) ? list.childNodes[i - 1] : list.lastChild;
          }
          else
          {
            item = (i < list.childNodes.length) ? list.childNodes[i + 1] : list.childNodes[0];
          }
        }

        list.childNodes[i].style.backgroundColor = '';
        list.childNodes[i].style.color = '';
      }
    }

    if ( ! item)
    {
      item = (direction == 'prev') ? list.lastChild : list.childNodes[0];
    }

    item.style.backgroundColor = _ac.highlight;
    item.style.color = _ac.highlightText

    assign(item);
  }

  var assign = function(item)
  {
    flag = true;
    textbox.value = item.innerHTML;

    if (typeof hidden == 'object')
    {
      hidden.value = item.id;
    }

    if (typeof callback == 'function')
    {
      callback.call(_ac, item.id, textbox.value);
    }
  }

  var debug = function()
  {
    // document.getElementById('foo').innerHTML = textbox.value;
  }
}

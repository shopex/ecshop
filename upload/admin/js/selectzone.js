/* $Id : selectzone.js 4824 2007-01-31 08:23:56Z paulgao $ */

/* *
 * SelectZone 类
 */
function SelectZone()
{
  this.filters   = new Object();

  this.id        = arguments[0] ? arguments[0] : 1;     // 过滤条件
  this.sourceSel = arguments[1] ? arguments[1] : null;  // 1 商品关联 2 组合、赠品（带价格）
  this.targetSel = arguments[2] ? arguments[2] : null;  // 源   select 对象
  this.priceObj  = arguments[3] ? arguments[3] : null;  // 目标 select 对象

  this.filename  = location.href.substring((location.href.lastIndexOf("/")) + 1, location.href.lastIndexOf("?")) + "?is_ajax=1";
  var _self = this;

  /**
   * 载入源select对象的options
   * @param   string      funcName    ajax函数名称
   * @param   function    response    处理函数
   */
  this.loadOptions = function(act, filters)
  {
    Ajax.call(this.filename+"&act=" + act, filters, this.loadOptionsResponse, "GET", "JSON");
  }

  /**
   * 将返回的数据解析为options的形式
   * @param   result      返回的数据
   */
  this.loadOptionsResponse = function(result, txt)
  {
    if (!result.error)
    {
      _self.createOptions(_self.sourceSel, result.content);
    }

    if (result.message.length > 0)
    {
      alert(result.message);
    }
    return;
  }

  /**
   * 检查对象
   * @return boolean
   */
  this.check = function()
  {
    /* source select */
    if ( ! this.sourceSel)
    {
      alert('source select undefined');
      return false;
    }
    else
    {
      if (this.sourceSel.nodeName != 'SELECT')
      {
        alert('source select is not SELECT');
        return false;
      }
    }

    /* target select */
    if ( ! this.targetSel)
    {
      alert('target select undefined');
      return false;
    }
    else
    {
      if (this.targetSel.nodeName != 'SELECT')
      {
        alert('target select is not SELECT');
        return false;
      }
    }

    /* price object */
    if (this.id == 2 && ! this.priceObj)
    {
      alert('price obj undefined');
      return false;
    }

    return true;
  }

  /**
   * 添加选中项
   * @param   boolean  all
   * @param   string   act
   * @param   mix      arguments   其他参数，下标从[2]开始
   */
  this.addItem = function(all, act)
  {
    if (!this.check())
    {
      return;
    }

    var selOpt  = new Array();

    for (var i = 0; i < this.sourceSel.length; i ++ )
    {
      if (!this.sourceSel.options[i].selected && all == false) continue;

      if (this.targetSel.length > 0)
      {
        var exsits = false;
        for (var j = 0; j < this.targetSel.length; j ++ )
        {
          if (this.targetSel.options[j].value == this.sourceSel.options[i].value)
          {
            exsits = true;

            break;
          }
        }

        if (!exsits)
        {
          selOpt[selOpt.length] = this.sourceSel.options[i].value;
        }
      }
      else
      {
        selOpt[selOpt.length] = this.sourceSel.options[i].value;
      }
    }

    if (selOpt.length > 0)
    {
      var args = new Array();

      for (var i=2; i<arguments.length; i++)
      {
        args[args.length] = arguments[i];
      }

      Ajax.call(this.filename + "&act="+act+"&add_ids=" +selOpt.toJSONString(), args, this.addRemoveItemResponse, "GET", "JSON");
    }
  }

  /**
   * 删除选中项
   * @param   boolean    all
   * @param   string     act
   */
  this.dropItem = function(all, act)
  {
    if (!this.check())
    {
      return;
    }

    var arr = new Array();

    for (var i = this.targetSel.length - 1; i >= 0 ; i -- )
    {
      if (this.targetSel.options[i].selected || all)
      {
        arr[arr.length] = this.targetSel.options[i].value;
      }
    }

    if (arr.length > 0)
    {
      var args = new Array();

      for (var i=2; i<arguments.length; i++)
      {
        args[args.length] = arguments[i];
      }

      Ajax.call(this.filename + "&act="+act+"&drop_ids=" + arr.toJSONString(), args, this.addRemoveItemResponse, 'GET', 'JSON');
    }
  }

  /**
   * 处理添加项返回的函数
   */
  this.addRemoveItemResponse = function(result,txt)
  {
    if (!result.error)
    {
      _self.createOptions(_self.targetSel, result.content);
    }

    if (result.message.length > 0)
    {
      alert(result.message);
    }
  }

  /**
   * 为select元素创建options
   */
  this.createOptions = function(obj, arr)
  {
    obj.length = 0;

    for (var i=0; i < arr.length; i++)
    {
      var opt   = document.createElement("OPTION");
      opt.value = arr[i].value;
      opt.text  = arr[i].text;
      opt.id    = arr[i].data;

      obj.options.add(opt);
    }
  }
}

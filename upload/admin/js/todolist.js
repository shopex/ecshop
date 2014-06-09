var Todolist = Class.create();
Todolist.prototype = {
  initialize:function(adminid)
  {
    this.box         = $ce("DIV");
    this.container   = $ce("DIV");
    this.head        = $ce("DIV");
    this.buttons     = $ce("DIV");
    this.middle      = $ce("DIV");
    this.bottom      = $ce("DIV");
    this.bottomLeft  = $ce("DIV");
    this.virtualBox  = $ce("DIV");
    this.bottomRight = $ce("DIV");
    this.minBtn      = $ce("IMG");
    this.closeBtn    = $ce("IMG");
    this.saveBtn     = $ce("INPUT");
    this.clearBtn    = $ce("INPUT");
    this.textBox     = $ce("TEXTAREA");

    this.tempFun              = new Object();
    this.tempFun.draging      = this.draging.bind(this);
    this.tempFun.startDrag    = this.startDrag.bind(this);
    this.tempFun.endDrag      = this.endDrag.bind(this);
    this.tempFun.startSetSize = this.startSetSize.bind(this)
    this.tempFun.setSize      = this.setSize.bind(this);
    this.tempFun.endSetSize   = this.endSetSize.bind(this);
    this.tempFun.save         = this.save.bind(this);

    this.myTimer;
    this.lastContentHash;
    var self         = this;
    this.innerWidth  = Math.min(document.body.scrollWidth, self.innerWidth||document.body.clientWidth);
    if (window.self.innerHeight)
    {
      this.innerHeight = Math.max(window.self.innerHeight,document.body.scrollHeight);
    }
    else
    {
      if (document.documentElement && document.documentElement.clientHeight)
      {
        this.innerHeight = Math.max(document.documentElement.clientHeight,document.documentElement.scrollHeight);
      }
      else if (document.body)
      {
        this.innerHeight = Math.max(document.body.clientHeight,document.body.scrollHeight);
      }
    }
  
    this.md5         = hex_md5;
    this.adminid     = adminid;

    this.closeBtn.src         = "images/btn_close.gif";
    this.closeBtn.onclick     = this.close.bind(this);

    this.minBtn.src      = "images/btn_minimize.gif";
    this.minBtn.onclick  = function()
    {
      if (self.Maxed)
      {
        self.toMin();
      }
      else
      {
        self.toMax()
      }
    }

    this.buttons.className  = "buttons";
    this.buttons.appendChild(this.minBtn);
    this.buttons.appendChild(this.closeBtn);

    this.head.className    = "head";
    this.head.style.cursor = "move";
    this.head.innerHTML    =  todolist_caption ;
    this.head.appendChild(this.buttons);

    this.textBox.style.height = "100px";
    this.textBox.style.width  = "293px";

    this.clearBtn.type      = "button"
    this.clearBtn.value     = todolist_clear;
    this.clearBtn.className = "button";
    this.clearBtn.onclick   = this.clear.bind(this);

    this.saveBtn.type      = "button";
    this.saveBtn.value     = todolist_save;
    this.saveBtn.className = "button";
    this.saveBtn.onclick   = this.save.bind(this);

    this.bottom.className = "bottom";

    this.bottomLeft.className     = "bottomLeft";
    this.bottomLeft.innerHTML    += "<label><input type=\"checkbox\" value=\"1\" name=\"remember\" />" + todolist_autosave + "</label>"
    this.bottomLeft.appendChild(this.saveBtn);
    this.bottomLeft.appendChild(this.clearBtn);

    this.bottom.appendChild(this.bottomLeft);
    this.bottom.appendChild(this.bottomRight);

    this.middle.className = "middle";
    this.middle.appendChild(this.textBox);

    this.container.className    = "container"
    this.container.appendChild(this.head);
    this.container.appendChild(this.middle);
    this.container.appendChild(this.bottom);

    this.box.className     = "todolist-box";
    this.box.style.height  = "auto";
    this.box.style.left    = "1px";
    this.box.style.top     = "1px";
    this.box.style.display = "none";
    this.box.appendChild(this.container);

    this.autoSaveCheckbox         = this.bottomLeft.getElementsByTagName('input')[0];
    this.autoSaveCheckbox.checked = document.getCookie("todolist_notautosave_" + this.adminid) == null ? true : false;
    if (this.autoSaveCheckbox.checked)
    {
      this.autoSave();
    }

    this.bottomRight.className    = "bottomRight";
    this.bottomRight.style.cursor = "se-resize";

    this.virtualBox.style.display = "none";
    this.virtualBox.className     = "virtualBox";

    document.body.appendChild(this.box);
    document.body.appendChild(this.virtualBox);

    this.LastX      = 0;
    this.LastY      = 0;
    this.LastLeft   = 0;
    this.LastTop    = 0;
    this.moveing    = false;
    this.visibility = false;
    this.Maxed      = true;
    
    Event.observe(this.head, "selectstart", this.falseFunction);
    Event.observe(this.head, "mousedown", this.tempFun.startDrag);
    Event.observe(this.bottomRight, "mousedown", this.tempFun.startSetSize);
    Event.observe(this.autoSaveCheckbox, "click", this.autoSave.bind(this));

    window.onbeforeunload = function()
    {
       if (self.lastContentHash == self.md5(self.textBox.value)) return;
       if (confirm(todolist_confirm_save))
       {
         Ajax.call('index.php?is_ajax=1&act=save_todolist', 'content=' + encodeURIComponent(self.textBox.value), null, 'POST', 'TEXT', false);
       }
    }
    
    this.head.ondblclick = function()
    {
      if (!self.Maxed)
        self.toMax();
      else
        self.toMin();
      return false;
    }

    var posObj = document.getCookie("todolist_position_" + this.adminid);
    if (posObj != null)
    {
      posObj = eval(posObj);
      this.box.style.left = posObj.X;
      this.box.style.top  = posObj.Y;
    }
    this.loadData.call(this);
  },

  loadData : function()
  {
    Ajax.call('index.php?is_ajax=1&act=get_todolist', '' , this.loadDataResponse.bind(this) , 'POST', 'TEXT');
  },

  loadDataResponse : function(result)
  {
    this.textBox.value = result;
    this.lastContentHash = this.md5(this.textBox.value);
  },

  startDrag : function(event)
  {
    Event.observe(document.body, "selectstart", this.falseFunction);
    Event.observe(document, "mousemove", this.tempFun.draging);
    Event.observe(document, "mouseup", this.tempFun.endDrag);
    var element   = Event.element(event||window.event);
    this.LastX    = Event.pointerX(event||window.event);
    this.LastY    = Event.pointerY(event||window.event);
    this.LastLeft = this.box.style.left;
    this.LastTop  = this.box.style.top;
  },

  draging : function(event)
  {
    var X  = Event.pointerX(event||window.event);
    var Y  = Event.pointerY(event||window.event);
    var tX = parseInt(this.LastLeft) + X - this.LastX;
    var tY = parseInt(this.LastTop) + Y - this.LastY;
    
    if (tX <= 0)
    {
      tX = 0;
    }
    if (tY <= 0)
    {
      tY = 0;
    }

    if ((tX + parseInt(this.box.offsetWidth)) >= parseInt(this.innerWidth))
    {
      tX = parseInt(this.innerWidth) - parseInt(this.box.offsetWidth);
    }
    
    if ((tY + parseInt(this.box.offsetHeight)) >= parseInt(this.innerHeight))
    {
      tY = parseInt(this.innerHeight) - parseInt(this.box.offsetHeight);
    }
    
    this.box.style.left = tX + "px";
    this.box.style.top  = tY + "px";
  },

  endDrag : function()
  {
    var date = new Date();
    date.setTime(date.getTime() + 99999999);
    document.setCookie("todolist_position_" + this.adminid, "({X:'" + this.box.style.left + "',Y:'" + this.box.style.top + "'})", date.toGMTString());
    Event.stopObserving(document.body, "selectstart", this.falseFunction);
    Event.stopObserving(document, "mousemove", this.tempFun.draging);
    Event.stopObserving(document, "mouseup", this.tempFun.endDrag);
  },

  toMax : function()
  {
    this.middle.style.display = "";
    this.bottom.style.display = "";
    this.Maxed = true;
    this.minBtn.src = "images/btn_minimize.gif";
  },

  toMin : function()
  {
    this.middle.style.display = "none";
    this.bottom.style.display = "none";
    this.box.style.height = "";
    this.Maxed = false;
    this.minBtn.src = "images/btn_maximize.gif";
  },

  show : function()
  {
    this.loadData();
    this.box.style.display = "";
    if (Browser.isFirefox)
    {
      this.middle.style.paddingRight = "5px";
    }

    if (parseInt(this.box.style.top) + parseInt(this.box.offsetHeight) > this.innerHeight)
    {
      var top = this.innerHeight - this.box.offsetHeight;
      this.box.style.top = top + "px";
    }

    if (parseInt(this.box.style.top) <= 0)
    {
      this.box.style.top = "1px";
    }
    this.visibility = true;
  },

  hide : function()
  {
    this.box.style.display = "none";
    this.visibility = false;
  },

  falseFunction : function()
  {
    return false;
  },

  startSetSize : function(event)
  {
    var pos = Event.position(this.box);
    this.virtualBox.style.display = "";
    this.virtualBox.style.top     = pos.top + "px";
    this.virtualBox.style.left    = pos.left + "px";
    this.virtualBox.style.height  = this.box.offsetHeight + "px";
    this.virtualBox.style.width   = this.box.offsetWidth  + "px";
    document.body.style.cursor    = "se-resize";
    Event.observe(document, "mousemove", this.tempFun.setSize);
    Event.observe(document, "mouseup", this.tempFun.endSetSize);
    Event.observe(document.body, "selectstart", this.falseFunction);
  },

  setSize : function(event)
  {
    var pos    = Event.position(this.box);
    var tX     = Event.pointerX(event||window.event);
    var tY     = Event.pointerY(event||window.event);
    var height = tY - (parseInt(pos.top) - this.box.offsetHeight) - parseInt(this.box.offsetHeight);
    var width  = tX - (parseInt(pos.left) - this.box.offsetWidth) - parseInt(this.box.offsetWidth);
    if (height >= 95)
      this.virtualBox.style.height  = height + "px";
    if (width >= 301)
      this.virtualBox.style.width   = width + "px";
  },

  endSetSize : function(event)
  {
    Event.stopObserving(document,"mousemove",this.tempFun.setSize);
    Event.stopObserving(document,"mouseup",this.tempFun.endSetSize);
    Event.stopObserving(document.body,"selectstart",this.falseFunction);
    document.body.style.cursor='';

    if (parseInt(this.virtualBox.style.height) >= 95)
    {
      this.box.style.height = this.virtualBox.style.height ;
    }
    if (parseInt(this.virtualBox.style.width) >= 301)
    {
      this.box.style.width  = this.virtualBox.style.width;
    }

    this.textBox.style.width      = parseInt(this.box.style.width) - 7 + "px";
    this.textBox.style.height     = parseInt(this.box.style.height) - parseInt(this.head.offsetHeight) - parseInt(this.bottom.offsetHeight) + "px";
    if (this.container.offsetHeight > this.box.offsetHeight)
    {
      var span = this.container.offsetHeight - this.box.offsetHeight;
      this.textBox.style.height = parseInt(this.textBox.style.height) - span - 4 + "px";
    }
    this.virtualBox.style.display = "none";
  },

  save : function()
  {
    if (this.lastContentHash == this.md5(this.textBox.value)) return false;
    this.saveBtn.disabled  = true;
    this.clearBtn.disabled = true;
    this.textBox.disabled  = true;
    var content            = encodeURIComponent(this.textBox.value);
    var self    = this;
    Ajax.call('index.php?is_ajax=1&act=save_todolist', 'content=' + content, function(result)
                                                                             {
                                                                               self.saveBtn.disabled  = false;
                                                                               self.textBox.disabled  = false;
                                                                               self.clearBtn.disabled = false;
                                                                               self.lastContentHash   = self.md5(self.textBox.value);
                                                                             }, 'POST', 'TEXT');
  },

  autoSave : function()
  {
    if (this.autoSaveCheckbox.checked)
    {
      document.removeCookie("todolist_notautosave_" + this.adminid);
      this.myTimer = window.setInterval(this.save.bind(this), 60000);
      Event.observe(this.textBox, "blur", this.tempFun.save);
    }
    else
    {
      Event.stopObserving(this.textBox, "blur", this.tempFun.save);
      var date = new Date();
      date.setTime(date.getTime() + 99999999);
      document.setCookie("todolist_notautosave_" + this.adminid, "1", date.toGMTString());
      window.clearInterval(this.myTimer);
      this.myTimer = false;
    }
  },

  close : function()
  {
    if (this.lastContentHash != this.md5(this.textBox.value))
    {
      if (!this.autoSaveCheckbox.checked)
      {
        if (confirm(todolist_confirm_save))
        {
          this.save();
        }
      }
    }
    this.hide()
  },
  
  clear : function()
  {
    if (confirm(todolist_confirm_clear))
    {
      this.textBox.value = "";
    }
  }
}
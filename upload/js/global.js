/* $Id: global.js 15469 2008-12-19 06:34:44Z testyang $ */
Object.extend = function(destination, source)
{
  for (property in source) {
    destination[property] = source[property];
  }
  return destination;
}

Object.prototype.extend = function(object)
{
  return Object.extend.apply(this, [this, object]);
}

//封装getEelementById函数
function $()
{
  var elements = new Array();
  for (var i = 0; i < arguments.length; i++) {
    var element = arguments[i];
    if (typeof element == 'string')
      element = document.getElementById(element);

    if (arguments.length == 1)
      return element;

    elements.push(element);
  }

  return elements;
}

//创建元素
function $ce(tagName)
{
    return document.createElement(tagName);
}

//定义类类型
var Class = {
  create : function()
  {
    return function()
    {
      this.initialize.apply(this, arguments);
    }
  }
}

//对象绑定
Function.prototype.bind = function(object) {
  var __method = this;
  return function()
  {
    __method.apply(object, arguments);
  }
}

if (!window.Event) {
  var Event = new Object();
}

Object.extend(Event, {
  element: function(event) {
    return event.target || event.srcElement;
  },

  pointerX: function(event) {
    return event.pageX || (event.clientX +
      (document.documentElement.scrollLeft || document.body.scrollLeft));
  },

  pointerY: function(event) {
    return event.pageY || (event.clientY +
      (document.documentElement.scrollTop || document.body.scrollTop));
  },

  stop: function(event) {
    if (event.preventDefault) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      event.returnValue = false;
    }
  },

  position: function(element)
  {
    var t = element.offsetTop;
    var l = element.offsetLeft;
    while(element = element.offsetParent)
    {
        t += element.offsetTop;
        l += element.offsetLeft;
    }
    var pos={top:t,left:l};
    return pos;
  } ,

  observers: false,

  _observeAndCache: function(element, name, observer, useCapture) {
    if (!this.observers) this.observers = [];
    if (element.addEventListener) {
      this.observers.push([element, name, observer, useCapture]);
      element.addEventListener(name, observer, useCapture);
    } else if (element.attachEvent) {
      this.observers.push([element, name, observer, useCapture]);
      element.attachEvent('on' + name, observer);
    }
  },

  observe: function(element, name, observer, useCapture) {
    var element = $(element);
    useCapture = useCapture || false;

    if (name == 'keypress' &&
        ((navigator.appVersion.indexOf('AppleWebKit') > 0)
        || element.attachEvent))
      name = 'keydown';

    this._observeAndCache(element, name, observer, useCapture);
  },

  stopObserving: function(element, name, observer, useCapture) {
    var element = $(element);
    useCapture = useCapture || false;

    if (name == 'keypress' &&
        ((navigator.appVersion.indexOf('AppleWebKit') > 0)
        || element.detachEvent))
      name = 'keydown';

    if (element.removeEventListener) {
      element.removeEventListener(name, observer, useCapture);
    } else if (element.detachEvent) {
      element.detachEvent('on' + name, observer);
    }
  }
});

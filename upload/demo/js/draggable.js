function getAbsPosition(target) {
    var pos = {x:0, y:0};

    while (target != null) {
        pos.x += target.offsetLeft;
        pos.y += target.offsetTop;
        target = target.offsetParent;
    }

    return pos;
}

var EventManager = {
    "attachEvent" : function (oTarget, sType, fHandle, bUseCapture) {
        bUseCapture = (bUseCapture === true);   
        
        if (oTarget.addEventListener) {
            oTarget.addEventListener(sType, fHandle, bUseCapture);
        } else if (oTarget.attachEvent) {
            oTarget.attachEvent("on" + sType, fHandle);
        } else {
            throw new Error("EventManager.attachEvent() fail.");
        }
    },

    "detachEvent" : function (oTarget, sType, fHandle, bUseCapture) {
        bUseCapture = (bUseCapture === true);   
        
        if (oTarget.removeEventListener) {
            oTarget.removeEventListener(sType, fHandle, bUseCapture);
        } else if (oTarget.detachEvent) {
            oTarget.detachEvent("on" + sType, fHandle);
        } else {
            throw new Error("EventManager.detachEvent() fail.");
        }
    },

    "stopPropagation" : function (e) {
        if (e.stopPropagation) { 
            e.stopPropagation();
        } else {
            e.cancelBubble = true;
        }
    },

    "preventDefault" : function (e) {
        if(e.preventDefault) {
            e.preventDefault();
        } else {
            e.returnValue = false;            
        }
    }
};

function Draggable () {}

Draggable.prototype.range = null;

/**
 * @param dn stands for "dragged node" 
 * @param fn stands for "flagged node"
 */
Draggable.prototype.bindDragNode = function (dn, fn) {   
    dn = typeof(dn) === "string" ? document.getElementById(dn) : dn;
    fn = typeof(fn) === "string" ? document.getElementById(fn) 
                                 : (typeof(fn) === "undefined" ? dn : fn);
    var self = this;
    
    EventManager.attachEvent(fn, "mouseover", function () {
        fn.style.cursor = "move";
    });
    
    EventManager.attachEvent(fn, "mousedown", function (e) {        
        e = e || window.event; 
        var absPos = getAbsPosition(dn)
            deltaX = e.clientX - absPos.x,
            deltaY = e.clientY - absPos.y;

        fn.style.cursor = "auto";
        dn.style.zIndex = self.constructor.zIndex++;    
        EventManager.attachEvent(document, "mousemove", moveHandler);
        EventManager.attachEvent(document, "mouseup", upHandler);        
        EventManager.preventDefault(e);
        
        function moveHandler(e2) {
            e2 = e2 || window.event;
            var left = e2.clientX - deltaX,
                top = e2.clientY - deltaY;

            fn.style.cursor = "auto";
            
            if (self.range) {
                var x1 = self.range.x1,
                    y1 = self.range.y1,
                    x2 = self.range.x2,
                    y2 = self.range.y2,
                    w = dn.offsetWidth,
                    h = dn.offsetHeight;
                
                left = left < x1 ? x1 : (left > x2 - w ? x2 - w : left);
                top = top < y1 ? y1 : (top > y2 - h ? y2 - h : top);
            }
            
            dn.style.left = left + "px";
            dn.style.top = top + "px";            
            EventManager.preventDefault(e2);
        }
        
        function upHandler() {
            fn.style.cursor = "move";

            EventManager.detachEvent(document, "mousemove", moveHandler);
            EventManager.detachEvent(document, "mouseup", upHandler);
        }
    });

    EventManager.attachEvent(window, "unload", function () {
        dn = null;
        fn = null;
    });
};

Draggable.zIndex = 0;

Draggable.prototype.setRange = function (xx1, yy1, xx2, yy2) {
    this.range = {x1 : xx1, y1 : yy1, x2 : xx2, y2 : yy2};
};

Draggable.prototype.clearRange = function () {
    this.range = null;
};
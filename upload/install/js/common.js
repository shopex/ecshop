function $(id, winHDL) {
    if (typeof(winHDL) === "undefined") {
        winHDL = window;
    }
    return winHDL.document.getElementById(id);
}

function getAddressLang() {
    var addressLang = location.search.match(/lang=(\w+)/);
    addressLang = addressLang ? addressLang[1] : "zh_cn";

    return addressLang;
}

function getCurStep() {
    var curStep = location.search.match(/step=(\w+)/);
    curStep = curStep ? curStep[1] : "welcome";

    return curStep;
}

function setInputCheckedStatus() {
    var targetInput=  $("js-" +getAddressLang());

    if (!targetInput) {
        return;
    }
    targetInput.setAttribute("checked", "checked");

    var langOptions = document.getElementsByName("js-lang");
    for (var i = 0; i < langOptions.length; i++) {
        langOptions[i].onclick = function () {
            var selectedLang =  this.getAttribute("id").slice(3);
            location.href = "./index.php?lang=" + selectedLang + "&step=" + getCurStep();
        }
    }
}
function setInnerHTML(id, html) {
    var id = document.getElementById(id);
    var userAgent = navigator.userAgent.toLowerCase();
    if (userAgent.indexOf('msie') >= 0 && userAgent.indexOf('opera') < 0)
    {
        html = '<div style="display:none">for IE</div>' + html;
        html = html.replace(/<script([^>]*)>/gi,'<script$1 defer="true">');
        id.innerHTML = html;
        id.removeChild(id.firstChild);
    }
    else 
    {
        var id_next = id.nextSibling;
        var id_parent = id.parentNode;
        id_parent.removeChild(id);
        id.innerHTML = html;
        if (id_next)
        {
            id_parent.insertBefore(id, id_next);
        }
        else
        {
            id_parent.appendChild(id);
        }
    }
}

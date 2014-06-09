/* $Id: index.js 15469 2008-12-19 06:34:44Z testyang $ */
var best_str = new Object();
var new_str = new Object();
var hot_str = new Object();

function init_rec_data()
{
    best_str[0] = document.getElementById("show_best_area") == null ? '' : document.getElementById("show_best_area").innerHTML;
    new_str[0] = document.getElementById("show_new_area") == null ? '' : document.getElementById("show_new_area").innerHTML;
    hot_str[0] = document.getElementById("show_hot_area") == null ? '' : document.getElementById("show_hot_area").innerHTML;
}

function get_cat_recommend(rec_type, cat_id)
{
    if (rec_type == 1)
    {
        if (typeof(best_str[cat_id]) == "string")
        {
            document.getElementById("show_best_area").innerHTML = best_str[cat_id];
            return;
        }
    }
    else if (rec_type == 2)
    {
        if (typeof(new_str[cat_id]) == "string")
        {
            document.getElementById("show_new_area").innerHTML = new_str[cat_id];
            return;
        }
    }
    else
    {
        if (typeof(hot_str[cat_id]) == "string")
        {
            document.getElementById("show_hot_area").innerHTML = hot_str[cat_id];
            return;
        }
    }
    Ajax.call('index.php?act=cat_rec', 'rec_type=' + rec_type + '&cid=' + cat_id, cat_rec_response, "POST", "TEXT");
}

function cat_rec_response(result)
{
    var res = result.parseJSON();
    if (res.type == 1)
    {
        var ele = document.getElementById("show_best_area");
        best_str[res.cat_id] = res.content;
    }
    else if (res.type == 2)
    {
        var ele = document.getElementById("show_new_area");
        new_str[res.cat_id] = res.content;
    }
    else
    {
        var ele = document.getElementById("show_hot_area");
        hot_str[res.cat_id] = res.content;
    }
    ele.innerHTML = res.content;
}

if (document.all)
{
    window.attachEvent("onload", init_rec_data);
}
else
{
    window.addEventListener("load", init_rec_data, false);
}

function change_tab_style(item, elem, obj)
{
    elem = elem.toUpperCase();
    var itemObj = document.getElementById(item);
    var elems = itemObj.getElementsByTagName(elem);
    var _o = obj.parentNode;
    while(_o.nodeName != elem)
    {
        _o = _o.parentNode;
    }
    for (var i=0,l=elems.length; i<l; i++)
    {
        elems[i].className = 'h2bg';
    }
    _o.className = '';
}
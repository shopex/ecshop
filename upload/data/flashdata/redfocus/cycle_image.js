/*
Flash Name: Red Focus
Description: 红色聚焦Flash图片轮播
*/
document.write('<div id="flash_cycle_image"></div>');
$importjs = (function()
{
    var uid = 0;
    var curr = 0;
    var remove = function(id)
    {
        var head = document.getElementsByTagName('head')[0];
        head.removeChild( document.getElementById('jsInclude_'+id) );
    };

    return function(file,callback)
    {
        var callback;
        var id = ++uid;
        var head = document.getElementsByTagName('head')[0];
        var js = document.createElement('script');
        js.setAttribute('type','text/javascript');
        js.setAttribute('src',file);
        js.setAttribute('id','jsInclude_'+id);
        if( document.all )
        {
            js.onreadystatechange = function()
            {
                if(/(complete|loaded)/.test(this.readyState))
                {
                    try
                    {
                        callback(id);remove(id);
                    }
                    catch(e)
                    {
                        setTimeout(function(){remove(id);include_js(file,callback)},2000);
                    }
                }
            };
        }
        else
        {
            js.onload = function(){callback(id); remove(id); };
        }
        head.appendChild(js);
        return uid;
    };
}
)();

function show_flash()
{
    var text_height = 0;
    var focus_width = swf_width;
    var focus_height = swf_height - text_height;
    var total_height = focus_height + text_height;

    document.getElementById('flash_cycle_image').innerHTML = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ total_height +'">'+'<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="data/flashdata/redfocus/redfocus.swf"><param name="quality" value="high"><param name="bgcolor" value="#F0F0F0">'+'<param name="menu" value="false"><param name=wmode value="opaque">'+'<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'">'+'<embed src="data/flashdata/redfocus/redfocus.swf" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'" quality="high" width="'+ focus_width +'" height="'+ total_height +'" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent"/>'+'</object>';
}

$importjs('data/flashdata/redfocus/data.js', show_flash);
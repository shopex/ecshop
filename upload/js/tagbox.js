    var pos;
	var MSIE=navigator.userAgent.indexOf("MSIE");
	var Fire=navigator.userAgent.indexOf("Fire");
	var OPER=navigator.userAgent.indexOf("Opera");
	var bdy = (document.documentElement && document.documentElement.clientWidth)?document.documentElement:document.body;
	onscroll = function()
	{
		if (Fire > 0)
		{
			pos = window.pageYOffset;
		}
		else
		{
			pos = bdy.scrollTop;
		}
			var ele_top = 100;
			document.getElementById('tag_box').style.top = (pos + ele_top) + 'px';
	}
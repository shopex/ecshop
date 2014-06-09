var SelectBox = Class.create();
SelectBox.prototype = {
    initialize:function (src, dst)
    {
        this.dst_options = new Array();
        this.src_obj = document.getElementById(src);
        this.dst_obj = document.getElementById(dst);
        this.copy_method = (typeof (arguments[2]) != 'undefined')?true:false;
    },
    addItem:function ()
    {
        var all = (typeof (arguments[0]) != 'undefined')?arguments[0]:false;
        var src_options = this.fetch_options(this.src_obj, all);
        if (src_options.length > 0)
        {
            this.copy_options(this.dst_obj, src_options);
        }
    },
    delItem:function ()
    {
        var all = (typeof (arguments[0]) != 'undefined')?arguments[0]:false;
        var dst_options = this.fetch_options(this.dst_obj, all);
        if (dst_options.length > 0)
        {
            this.del_options(this.dst_obj, dst_options);
        }
    },
    moveItem:function (direction)
    {
        var dst_options = this.fetch_options(this.dst_obj);
        var insert_position = new Object;
        if (direction == 'up')
        {
            if (dst_options[0] === this.dst_obj.options[0])
            {
                return;
            }
            insert_position = dst_options[0].previousSibling;
        }
        else
        {
            if (dst_options[dst_options.length-1] === this.dst_obj.options[this.dst_obj.options.length-1])
            {
                return;
            }
            insert_position = dst_options[dst_options.length-1].nextSibling.nextSibling;
        }
        this.move_options(this.dst_obj, insert_position, dst_options);
    },
    fetch_options:function (o)
    {
        var sel_options = [];
        var c = 0;
        var all = (typeof (arguments[1]) != 'undefined')?arguments[1]:false;
        for (var i=0,l=o.options.length; i<l; i++)
        {
            if (all === false)
            {
                if (o.options[i].selected)
                {
                    sel_options[c++] = o.options[i];
                }
            }
            else
            {
                sel_options[c++] = o.options[i];
            }
        }
        return sel_options;
    },
    del_options:function (o, arr)
    {
        for (var i=0,l=arr.length; i<l; i++)
        {
            if (this.copy_method === false)
            {
                o.removeChild(arr[i]);
            }
            else
            {
                this.src_obj.appendChild(arr[i]);
            }
            
            this.dst_options = this.delete_array(arr[i].value, this.dst_options);
        }
    },
    copy_options:function (o, arr)
    {
        for (var i=0,l=arr.length; i<l; i++)
        {
            if (!this.in_array(arr[i].value, this.dst_options))
            {
                if (this.copy_method === false)
                {
                    new_opt = this.clone_option(arr[i]);
                }
                else
                {
                    new_opt = arr[i];
                }
                o.appendChild(new_opt);
                this.dst_options[this.dst_options.length] = arr[i].value;
            }
        }
    },
    move_options:function (o, p, arr)
    {
        for (var i=0,l=arr.length; i<l; i++)
        {
            o.insertBefore(arr[i], p);
        }
    },
    clone_option:function (o)
    {
        var new_opt = document.createElement('OPTION');
        new_opt.value = o.value;
        new_opt.innerHTML = o.innerHTML;
        return new_opt;
    },
    in_array:function (elem, array)
    {
        var re = new RegExp('\\\|' + elem + '\\\|');
        return re.test('|' + array.join('|') + '|');
    },
    delete_array:function (elem, array)
    {
        var re = new RegExp( elem + '\\\|');
        var _str = (array.join('|') + '|').replace(re, '');
        return _str.split('|');
    }
}
<?php

/**
 * ECSHOP 妯℃澘绫
 * ============================================================================
 * * 鐗堟潈鎵€鏈 2005-2012 涓婃捣鍟嗘淳缃戠粶绉戞妧鏈夐檺鍏?徃锛屽苟淇濈暀鎵€鏈夋潈鍒┿€
 * 缃戠珯鍦板潃: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 杩欎笉鏄?竴涓?嚜鐢辫蒋浠讹紒鎮ㄥ彧鑳藉湪涓嶇敤浜庡晢涓氱洰鐨勭殑鍓嶆彁涓嬪?绋嬪簭浠ｇ爜杩涜?淇?敼鍜
 * 浣跨敤锛涗笉鍏佽?瀵圭▼搴忎唬鐮佷互浠讳綍褰㈠紡浠讳綍鐩?殑鐨勫啀鍙戝竷銆
 * ============================================================================
 * $Author: liubo $
 * $Date: 2009-12-14 17:22:19 +0800 (涓€, 2009-12-14) $
 * $Id: cls_template.php 16882 2009-12-14 09:22:19Z liubo $
 */

class template
{
    /**
    * 鐢ㄦ潵瀛樺偍鍙橀噺鐨勭┖闂
    *
    * @access  private
    * @var     array      $vars
    */
    var $vars = array();

   /**
    * 妯℃澘瀛樻斁鐨勭洰褰曡矾寰
    *
    * @access  private
    * @var     string      $path
    */
    var $path = '';

    /**
     * 鏋勯€犲嚱鏁
     *
     * @access  public
     * @param   string       $path
     * @return  void
     */
    function __construct($path)
    {
        $this->template($path);
    }

    /**
     * 鏋勯€犲嚱鏁
     *
     * @access  public
     * @param   string       $path
     * @return  void
     */
    function template($path)
    {
        $this->path = $path;
    }

    /**
     * 妯℃嫙smarty鐨刟ssign鍑芥暟
     *
     * @access  public
     * @param   string       $name    鍙橀噺鐨勫悕瀛
     * @param   mix           $value   鍙橀噺鐨勫€
     * @return  void
     */
    function assign($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * 妯℃嫙smarty鐨刦etch鍑芥暟
     *
     * @access  public
     * @param   string       $file   鏂囦欢鐩稿?璺?緞
     * @return  string      妯℃澘鐨勫唴瀹?鏂囨湰鏍煎紡)
     */
    function fetch($file)
    {
        extract($this->vars);
        ob_start();
        include($this->path . $file);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * 妯℃嫙smarty鐨刣isplay鍑芥暟
     *
     * @access  public
     * @param   string       $file   鏂囦欢鐩稿?璺?緞
     * @return  void
     */
    function display($file)
    {
        echo $this->fetch($file);
    }
}

?>
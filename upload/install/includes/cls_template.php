<?php

/**
 * ECSHOP 模板类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_template.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

class template
{
    /**
    * 用来存储变量的空间
    *
    * @access  private
    * @var     array      $vars
    */
    var $vars = array();

   /**
    * 模板存放的目录路径
    *
    * @access  private
    * @var     string      $path
    */
    var $path = '';

    /**
     * 构造函数
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
     * 构造函数
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
     * 模拟smarty的assign函数
     *
     * @access  public
     * @param   string       $name    变量的名字
     * @param   mix           $value   变量的值
     * @return  void
     */
    function assign($name, $value)
    {
        $this->vars[$name] = $value;
    }

    /**
     * 模拟smarty的fetch函数
     *
     * @access  public
     * @param   string       $file   文件相对路径
     * @return  string      模板的内容(文本格式)
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
     * 模拟smarty的display函数
     *
     * @access  public
     * @param   string       $file   文件相对路径
     * @return  void
     */
    function display($file)
    {
        echo $this->fetch($file);
    }
}

?>
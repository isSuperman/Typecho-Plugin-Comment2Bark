<?php
/**
 * Bark推送评论通知
 * 
 * @package Comment2Bark
 * @author Mr.Cola
 * @version 1.1.0
 * @link https://blog.colaink.com
 */
class Comment2Bark_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
    
        Typecho_Plugin::factory('Widget_Feedback')->comment = array('Comment2Bark_Plugin', 'sc_send');
        Typecho_Plugin::factory('Widget_Feedback')->trackback = array('Comment2Bark_Plugin', 'sc_send');
        Typecho_Plugin::factory('Widget_XmlRpc')->pingback = array('Comment2Bark_Plugin', 'sc_send');
        
        return _t('请配置此插件的 KEY, 以使您的Bark推送生效');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $key = new Typecho_Widget_Helper_Form_Element_Text('barkkey', NULL, NULL, _t('BARKKEY'), _t('BARKKEY 需要在 <a href="http://sc.ftqq.com/">Server酱</a> 注册<br />
        同时，注册后需要在 <a href="http://sc.ftqq.com/">Server酱</a> 绑定你的微信号才能收到推送'));
        $form->addInput($key->addRule('required', _t('您必须填写一个正确的 BarkKEY')));
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * Bark推送
     * 
     * @access public
     * @param array $comment 评论结构
     * @param Typecho_Widget $post 被评论的文章
     * @return void
     */
    public static function sc_send($comment, $post)
    {
        $options = Typecho_Widget::widget('Widget_Options');

        $barkkey = $options->plugin('Comment2Bark')->barkkey;

		$url = 'https://api.day.app';
		
		$title = '有人在你的博客发表了评论';

		$result = file_get_contents($url.'/'.$barkkey.'/'.$title.'/'.$comment['author'].'在「'.$post->title.'」说到:'.$comment['text']);
        
        return  $comment;
    }
}

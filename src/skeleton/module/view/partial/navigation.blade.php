<?php 
function generateMenuTree($menu)
{
    $layout = '';
    foreach ($menu as $key => $item)
    {
        $listItem   = '<li><a href="%s" class="%s" %s><i class="fa fa-fw %s"></i> <span>%s</span></a>%s</li>';
        $href       = $item['route']!='' ? route($item['route']) : 'javascript:void();';
        $class      = is_array($item['class']) ? implode(" ", $item) : $item['class'];
        $dataMode   = '';
        $icon       = $item['icon'];
        $label      = $item['label'];
        $subMenus   = '';
        
        if(array_key_exists('sub_items', $item) && count($item['sub_items'] > 0))
        {
           $class .= " parent collapsed";
           $dataMode = 'data-toggle="collapse" data-target="#'.$key.'Menu"';
           $subMenus = '<ul id="'.$key.'Menu" class="collapse">'.generateMenuTree($item['sub_items']).'</ul>';
        }

        $layout .= sprintf($listItem, $href, $class, $dataMode, $icon, $label, $subMenus);
    }

    return $layout;
}
?>
<?php if(Auth::check()):?>
    <div id="column-left" class="navbar-ex1-collapse">
        <ul id="left-menu" class="side-nav scrollbar-macosx">
            <div class="clearfix">
                <a href="javascript:void();" id="collapse-menu"><i class="fa fa-dedent fa-fw fa-lg"></i></a>
            </div>
            <?php echo(generateMenuTree($menu));?>
        </ul>
    </div>
<?php endif;?>
<?php


namespace ronashdkl\kodCms\modules\admin\components;


use ronashdkl\kodCms\modules\admin\events\AdminEvent;
use yii\base\Component;

class AdminView extends Component
{
    const RENDER_ADMIN_MENU = 'render_admin_menu';
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

    }



    public function renderMenu()
    {
        echo "hi";
    }
}
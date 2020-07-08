<?php

namespace ronashdkl\kodCms\widgets\sections\about;


use ronashdkl\kodCms\models\about\AboutModel;

use ronashdkl\kodCms\widgets\sections\SectionWidget;


class AboutListSectionWidget extends SectionWidget
{
    public static function navId(){
        return 'AboutListSectionWidget';
    }

    public function run()
    {
        parent::run();
       // $this->getView()->registerAssetBundle(ResizeSensorAsset::class);

        $model = new AboutModel();
        $position = $model->image_position??'bottom';
        if($model->image && $position=='left'){
            $this->getView()->registerAssetBundle(StickyAssets::class);
            $this->getView()->registerJs("
var sidebar = new StickySidebar('#sidebar', {
        containerSelector: '#main-content',
        innerWrapperSelector: '.sidebar__inner',
        topSpacing: 250,
        bottomSpacing:250,
        resizeSensor: true
    });
");
            $this->getView()->registerCss(".sidebar{
    will-change: min-height;
}

.sidebar__inner{
    transform: translate(0, 0); /* For browsers don't support translate3d. */
    transform: translate3d(0, 0, 0);
    will-change: position, transform;
}");
        }

        return $this->render(($position=='bottom')?'bottomImage':'index',['model'=>$model]);
    }

}
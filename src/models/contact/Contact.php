<?php

namespace ronashdkl\kodCms\models\contact;

use ronashdkl\kodCms\components\FieldConfig;
use ronashdkl\kodCms\models\ActiveRecordListModel;
use ronashdkl\kodCms\modules\admin\models\behaviours\Logbehaviour;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property string $address
 * @property string $created_at
 * @property string $updated_at
 */
class Contact extends ActiveRecordListModel
{
    public $reCaptcha;
    public $subject;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact';
    }
public function behaviors()
{
    return [
        [
            'class' => TimestampBehavior::className(),
            'value' => date('Y-m-d h:i')
        ],
        [
            'class' => Logbehaviour::class,
            'attribute' => 'full_name',
        ],
    ];
}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name','phone','message','email','address'],'required'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(),
                'uncheckedMessage' => Yii::t('site','Please confirm that you are not a bot.')],
            [['message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['full_name', 'phone'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('site', 'ID'),
            'full_name' => Yii::t('site', 'Full Name'),
            'email' => Yii::t('site', 'Email'),
            'phone' => Yii::t('site', 'Contact'),
            'message' => Yii::t('site', 'Message'),
            'address' => Yii::t('site', 'Adress'),
            'created_at' => Yii::t('site', 'Created At'),
            'updated_at' => Yii::t('site', 'Updated At'),
        ];
    }
    public  function displayAttributes()
    {
        return  ['full_name','email','phone'];
    }

  public function formTypes()
  {
     ['email'=>['type'=>FieldConfig::INPUT]];
  }
    public  function actionColumn()
    {
        return [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'urlCreator' => function ($action, $model, $key, $index) {
                return Url::to(['view', 'id' => $model->id]);
            },
            'template' => '{view}',
            'viewOptions' => ['title' => 'View', 'data-toggle' => 'tooltip', 'data-pjax' => 1, 'role' => 'modal-remote'],
            'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
                'data-confirm' => false, 'data-method' => false,// for overide yii data api
                'data-request-method' => 'delete',
                'data-toggle' => 'tooltip',
                'data-confirm-title' => 'Are you sure?',
                'data-confirm-message' => 'Are you sure want to delete this item'],
        ];
    }
    public function getToolBar(){
        return [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']) .
                '{toggleData}' .
                '{export}'
            ],
        ];
    }

    public function beforeSave($insert)
    {
        $this->log = $this->full_name." Send Message from contact form.";
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     * @return ContactQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ContactQuery(get_called_class());
    }
}
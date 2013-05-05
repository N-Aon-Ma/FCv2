<?php

/**
 * This is the model class for table "model".
 *
 * The followings are the available columns in table 'model':
 * @property integer $id
 * @property integer $author_id
 * @property string $head
 * @property string $body
 * @property string $date
 * @property string $head_image_url
 *
 * The followings are the available model relations:
 * @property User $author
 */
class News extends CActiveRecord
{
    /**
     * @var количество новостей на странице
     */
    private $_newsOnPage = 5;
    public $image;
    public $newComment;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return News the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'news';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('head, body', 'required', 'on'=>'add'),
            array('head', 'length', 'max' => 64),
            array('head_image_url', 'length', 'max' => 32),
            array('newComment', 'required', 'on'=>'comment'),
            array('newComment', 'length', 'max' => 256),
            array('image', 'file', 'types' => 'jpeg, jpg, gif, png', 'allowEmpty' => true, 'safe' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, author_id, head, body, date, head_image_url', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'comment' => array(self::HAS_MANY, 'CommentNews', 'news_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'author_id' => 'Автор',
            'head' => 'Название',
            'body' => 'Содержание',
            'date' => 'Дата публикации',
            'head_image_url' => 'Заглавное изображение',
            'newComment' => 'Комментарий',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('author_id', $this->author_id);
        $criteria->compare('head', $this->head, true);
        $criteria->compare('body', $this->body, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('head_image_url', $this->head_image_url, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return array последние $_newsOnPage новостей
     */
    public function getLatestNews()
    {
        $criteria = new CDbCriteria;
        $criteria->limit = $this->_newsOnPage;
        $criteria->order = 'date DESC';
        return $posts = $this->findAll($criteria);
    }

    public function addNews()
    {
        $imageExist = false;
        $this->author_id = Yii::app()->user->id;
        if ($_FILES['News']['name']['image'] != "") {
            $imageExist = true;
            $file = CUploadedFile::getInstance($this, 'image');
            $this->head_image_url = Helpers::generateRandomKey(6) . '.' . $file->getExtensionName();
        }
        $transaction = $this->dbConnection->beginTransaction();
        if ($this->save()) {
            if ($imageExist) {
                $big = Yii::getPathOfAlias('webroot') . '/images/news/';
                if (Helpers::resizeImage($file, $big, $this->head_image_url, 700)) {
                    $transaction->commit();
                    return true;
                } else {
                    $transaction->rollback();
                    $this->addError('image', 'Ошибка при загрузке изображения.');
                    return false;
                }
            } else {
                $transaction->commit();
                return true;
            }
        } else {
            $transaction->rollback();
            return false;
        }
    }

    public function addComment($value)
    {
        $comment = new CommentNews();
        $comment->user_id = Yii::app()->user->id;
        $comment->news_id = $this->id;
        $comment->value = $value;
        if ($comment->save()) {
            return true;
        }
        return false;
    }
}
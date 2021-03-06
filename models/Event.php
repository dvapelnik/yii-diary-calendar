<?php

/**
 * This is the model class for table "kit_cal_event".
 * The followings are the available columns in table 'kit_cal_event':
 * @property integer $id
 * @property integer $owner
 * @property integer $timestamp
 * @property string $text
 * @property string $type
 */
class Event extends CActiveRecord
{
    protected $_cryptor;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Event the static model class
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
        return Yii::app()->controller->module->dbPrefix . 'event';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('owner, timestamp, text', 'required'),
            array('owner, timestamp', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 4),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, owner, timestamp, text, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'        => 'ID',
            'owner'     => 'Owner',
            'timestamp' => 'Timestamp',
            'text'      => 'Text',
            'type'      => 'Type',
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
        $criteria->compare('owner', $this->owner);
        $criteria->compare('timestamp', $this->timestamp);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('type', $this->type, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function canChange($id = null, $owner = null)
    {
        if($owner === null)
        {
            if(!Yii::app()->user->isGuest)
            {
                $owner = Yii::app()->user->id;
            } else
            {
                return false;
            }
        }

        if($id === null)
        {
            $id = Yii::app()->request->getParam('id', $id);
            if(!$id)
            {
                return false;
            }
        }

        if(!preg_match('/^\d+$/', $id))
        {
            return false;
        }

        return self::model()->findByPk($id)->owner == $owner;
    }

    protected function getCryptor()
    {
        if($this->_cryptor === null)
        {
            $this->_cryptor = new Cryptor(
                Yii::app()->user->id,
                Yii::app()->user->email
            );
        }

        return $this->_cryptor;
    }

    protected function afterFind()
    {
        parent::afterFind();
        $this->text = $this->cryptor->decrypt($this->text);
    }

    protected function beforeSave()
    {
        if(parent::beforeSave()){
            $this->text = $this->cryptor->encrypt($this->text);
        }
        
        return true;
    }

}
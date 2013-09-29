<?php

/**
 * This is the model class for table "appo".
 * The followings are the available columns in table 'appo':
 * @property integer $id
 * @property integer $owner
 * @property integer $timestamp
 * @property string $text
 * @property string $email
 * @property string $send
 */
class Appo extends CalendarModelLayer
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Appo the static model class
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
        return $this->getModelTableDbPrefix() . 'appo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('owner, timestamp, text, email, send', 'required'),
            array('owner, timestamp', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 255),
            array('send', 'length', 'max' => 3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, owner, timestamp, text, email, send', 'safe', 'on' => 'search'),
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
            'email'     => 'Email',
            'send'      => 'Send',
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
        $criteria->compare('email', $this->email, true);
        $criteria->compare('send', $this->send, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
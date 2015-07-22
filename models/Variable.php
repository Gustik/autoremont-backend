<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "variable".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $param
 * @property string $value
 * @property integer $is_active
 */
class Variable extends Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'variable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'param', 'value'], 'required'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'param', 'value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'name' => 'Название',
            'param' => 'Название параметра',
            'value' => 'Значение',
            'is_active' => 'Активен',
        ];
    }

    public static function getParam($param)
    {
    	$model = static::findOne(['param' => $param]);
    	if ($model) {
    		return $model->value;
    	}
    	return null;
    }

    public static function setParam($param, $value)
    {
    	$model = static::findOne(['param' => $param]);
    	if ($model) {
	    	$model->value = $value;
	    	return $model->save();
    	}
    	return null;
    }
}
<?php

namespace app\models;

use Yii;

class Model extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }

    public function getSafeAttributes()
    {
        $attributes = [];
        $safeAttributes = $this->safeAttributes();
        foreach ($this->attributes as $attribute => $value) {
            if (in_array($attribute, $safeAttributes)) {
                $attributes[$attribute] = $value;
            }
        }
        foreach ($this->relatedRecords as $attribute => $model) {
            if ($model) {
                if (in_array($attribute, $safeAttributes)) {
                    if (is_array($model)) {
                        foreach ($model as $key => $value) {
                            $attributes[$attribute][$key] = $value->safeAttributes;
                        }
                    } else {
                        $attributes[$attribute] = $model->safeAttributes;
                    }
                }
            }
        }
        return $attributes;
    }

    public function getFriendly($name, $type)
    {
        $attribute = $this->$name;
        switch ($type) {
            case 'boolean':
                $icon = ( $attribute ? 'ok' : 'remove' );
                $color = ( $attribute ? 'green' : 'darkred' );
                $result = "<i style='color: $color;' class='glyphicon glyphicon-$icon'></i>";
                break;
            
            default:
                $result = false;
                break;
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            if ($insert || $this->is_active || array_key_exists('is_active', $this->dirtyAttributes)) {
                return true;
            }
            $this->addError('is_active', 'Объект помечен как удаленный.');
            return false;
        }
        return false;
    }
}
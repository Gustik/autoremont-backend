<?php

namespace app\models\traits;

trait SafeAttributes
{
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
}

<?php

namespace app\models\traits;

trait Delete
{
    public $is_active = 1;

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

    public function delete()
    {
        $this->is_active = 0;

        return $this->save();
    }

    public function restore()
    {
        $this->is_active = 1;

        return $this->save();
    }
}

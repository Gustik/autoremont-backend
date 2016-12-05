<?php

namespace app\models\traits;

trait Friendly
{
    public function getFriendly($name, $type)
    {
        $attribute = $this->$name;
        switch ($type) {
            case 'boolean':
                $icon = ($attribute ? 'ok' : 'remove');
                $color = ($attribute ? 'green' : 'darkred');
                $result = "<i style='color: $color;' class='glyphicon glyphicon-$icon'></i>";
                break;

            default:
                $result = false;
                break;
        }

        return $result;
    }
}

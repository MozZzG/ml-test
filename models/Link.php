<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;

/**
 * @property int $id
 * @property string $target
 * @property string $hash
 * @property int $use_count
 * 
 * @package app\models
 */
class Link extends ActiveRecord {
    const HASH_LENGTH = 6;
    
    public function rules()
    {
        return [
            [['target'], 'required'],
            [['target','hash'], 'trim'],
            [['target','hash'], 'string', 'max' => 255],
            [['use_count'], 'integer', 'min' => 0],                
        ];
    }
    
    public function incrementUseCount($save = false) {
        $this->use_count += 1;
        
        if ($save) {
            $this->save();
        }
        
        return $this;
    }
    
    public static function generateHash($target) {
        return substr(md5($target.time()), 0, self::HASH_LENGTH);
    }
    
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        
        if ($this->isNewRecord) {
            $this->hash = self::generateHash($this->target);
        }
        
        if (empty($this->use_count)) {
            $this->use_count = 0;
        }
        
        return true;
    }
    
    public function save($runValidation = true, $attributeNames = null) {
        $save = parent::save($runValidation, $attributeNames);
        
        if (!$save) {
            Yii::error('Error saving Link: '.VarDumper::dumpAsString($this->getErrorSummary(true)));
        }
        
        return $save;
    }
}
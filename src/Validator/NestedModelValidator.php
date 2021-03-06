<?php
/**
 * Data Mapper
 *
 * @link      https://github.com/hiqdev/php-data-mapper
 * @package   php-data-mapper
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\DataMapper\Validator;

use yii\base\Model;
use yii\validators\Validator;

class NestedModelValidator extends Validator
{
    public $modelClass;

    public function init()
    {
        if (!isset($this->modelClass)) {
            throw new \RuntimeException('Property "modelClass" is missing');
        }

        parent::init();
    }

    public function validateAttribute($model, $attribute)
    {
        $oldValue = $model->$attribute;

        $newModel = $this->createModel($model->$attribute);
        $model->$attribute = $newModel;

        $validationResult = parent::validateAttribute($model, $attribute);
        if ($validationResult === null) {
            return null;
        }

        $model->$attribute = $oldValue;

        return $validationResult;
    }

    private function createModel($data)
    {
        $model = new $this->modelClass();
        $model->load($data, '');

        return $model;
    }

    /**
     * @param Model $model
     * @return array|null
     */
    protected function validateValue($model)
    {
        if (!$model instanceof Model) {
            throw new \RuntimeException('Passed value must be instance of Model');
        }

        if (!$model->validate()) {
            return [reset($model->getFirstErrors()), []]; // todo: return more than one error
        }

        return null;
    }
}

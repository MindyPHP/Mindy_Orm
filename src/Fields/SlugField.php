<?php

namespace Mindy\Orm\Fields;

use Cocur\Slugify\Slugify;
use Mindy\Orm\ModelInterface;
use Mindy\Orm\Traits\UniqueUrl;

/**
 * Class SlugField
 * @package Mindy\Orm
 */
class SlugField extends CharField
{
    use UniqueUrl;
    /**
     * @var string
     */
    public $source = 'name';
    /**
     * @var bool
     */
    public $autoFetch = true;

    public function beforeInsert(ModelInterface $model, $value)
    {
        $this->value = empty($this->value) ? (new Slugify())->slugify($model->{$this->source}) : $this->value;
        if ($this->unique) {
            $this->value = $this->uniqueUrl($this->value);
        }
        $model->setAttribute($this->name, $this->value);
    }

    public function canBeEmpty()
    {
        return true;
    }

    public function beforeUpdate(ModelInterface $model, $value)
    {
        // Случай когда обнулен slug, например из админки
        if (empty($model->{$this->name})) {
            $this->value = (new Slugify())->slugify($model->{$this->source});
        }
        if ($this->unique) {
            $this->value = $this->uniqueUrl($this->value, 0, $model->pk);
        }
        $model->setAttribute($this->name, $this->value);
    }
}

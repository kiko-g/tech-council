<?php

// Credits to https://stackoverflow.com/questions/31415213/how-i-can-put-composite-keys-in-models-in-laravel-5

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKey
{
    public function getIncrementing()
    {
        return false;
    }

    protected function setKeysForSaveQuery($query)
    {
        foreach ($this->getKeyName() as $key) {
            if (isset($this->$key))
                $query->where($key, '=', $this->$key);
            else
                throw new Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
        }

        return $query;
    }

    protected static function find($id, $columns = ['*'])
    {
        $me = new self;
        $query = $me->newQuery();
    
        foreach ($me->getKeyName() as $key) {
            $query->where($key, '=', $id[$key]);
        }
    
        return $query->first($columns);
    }

    public static function findOrFail($id, $columns = array('*'))
    {
        $model = static::find($id, $columns);
        if (!is_null($model)) 
            return $model;

        throw (new ModelNotFoundException)->setModel(get_called_class());
    }
}
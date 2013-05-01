<?php

class DConfig extends CApplicationComponent
{
    protected $data = array();

    public function init()
    {
        $items = Config::model()->findAll();
        foreach ($items as $item){
            if ($item->param)
                $this->data[$item->param] = $item->value === '' ?  $item->default : $item->value;
        }
        parent::init();
    }

    public function get($key)
    {
        if (isset($this->data[$key])){
            return $this->data[$key];
        } else {
            throw new CException('Неизвестный параметр '.$key);
        }
    }

    public function set($key, $value)
    {
        $model = Config::model()->findByAttributes(array('param'=>$key));
        if (!$model)
            throw new CException('Неизвестный параметр '.$key);

        $this->data[$key] = $value;
        $model->value = $value;
        $model->save();
    }
}
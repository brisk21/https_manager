<?php


namespace app\common\model;


use think\Model;

class Conf extends Model
{
    function getValue($key)
    {
        return $this->where(['key' => $key])->value('value');
    }

    function fetch_data($key)
    {
        return $this->where(['key' => $key])->find();
    }

    function create_data($data)
    {
        return $this->allowField(true)->save($data);
    }

    function update_data($key, $value)
    {
        return $this->allowField(true)->isUpdate(true)->save(['value' => $value],['key'=>$key]);
    }
}
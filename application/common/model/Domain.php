<?php


namespace app\common\model;


use think\Model;

class Domain extends Model
{
    protected $createTime = 'add_time';
    protected $updateTime = 'up_time';

    public $autoWriteTimestamp = true;

    protected $insert = [
        'status' => 1,//1-启用，0-禁用
        'last_check_time' => 0,//上次检测时间
    ];

    public function getLastCheckTimeAttr($name, &$item = null)
    {
        return empty($item['last_check_time']) ? '' : date('Y-m-d H:i:s', $item['last_check_time']);
    }

    public function getStartTimeAttr($name, &$item = null)
    {
        return empty($item['start_time']) ? '' : date('Y-m-d H:i:s', $item['start_time']);
    }

    public function getEndTimeAttr($name, &$item = null)
    {
        return empty($item['end_time']) ? '' : date('Y-m-d H:i:s', $item['end_time']);
    }


    public function create_data($data)
    {
        if (self::where(['domain' => $data['domain']])->value('id')) {
            return false;
        }
        return $this->allowField(true)->save($data);
    }

    public function list_data($condition = [], $page = 1, $pageSize = 10)
    {
        return self::where(function ($query) use ($condition) {
            if (!empty($condition['domain'])) $query->where('domain', 'like', '%' . $condition['domain'] . '%');
        })
            ->limit(($page - 1) * $pageSize, $pageSize)
            ->order('id desc')
            ->paginate($pageSize, false, ['query' => request()->param()]);

    }

    public function list_data_for_check($condition, $limit = 30)
    {
        return self::where($condition)
            ->order('id asc')
            ->field('id,domain,status,start_time,end_time,last_check_time')
            ->limit(0, $limit)
            ->select()
            ->toArray();
    }

    public function update_data($data)
    {
        return $this->allowField(true)->isUpdate(true)->save($data);
    }

    public function fetch_data($id)
    {
        $data = self::get($id);
        return $data ? $data->toArray() : [];
    }

    public function del_data($id)
    {
        return $this->where(['id'=>$id])->delete();
    }

}
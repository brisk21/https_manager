<?php


namespace app\common\model;


use think\Model;

class LogEmail extends Model
{
    protected $createTime = 'add_time';
    protected $updateTime = 'up_time';

    public $autoWriteTimestamp = true;

    protected $insert = [
        'status' => 0,//1-发送成功，0-待发送，-1=发送失败
        'try_count' => 0,//尝试次数
    ];

    public function create_all($data)
    {
        return $this->allowField(true)->saveAll($data);
    }

    public function create_data($data)
    {
        return $this->allowField(true)->save($data);
    }

    public function list_data($condition = [], $page = 1, $pageSize = 10)
    {
        return self::where(function ($query) use ($condition) {
            if (!empty($condition['email'])) $query->where('email', '=', $condition['email']);
            if (!empty($condition['status'])) $query->where('status', '=', $condition['status']);
        })
            ->limit(($page - 1) * $pageSize, $pageSize)
            ->order('id desc')
            ->paginate($pageSize, false, ['query' => request()->param()]);

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

    /**
     * 需要发送的数据
     * @param $condition
     * @param int $limit
     * @return array|array[]|\array[][]
     */
    public function list_data_for_send($condition, $limit = 10)
    {
        return self::where($condition)
            ->limit(0, $limit)
            ->order('id asc')
            ->select()
            ->toArray();
    }

}
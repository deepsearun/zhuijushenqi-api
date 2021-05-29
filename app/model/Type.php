<?php


namespace app\model;


class Type extends Base
{
    protected $pk = 'type_id';

    protected $globalScope = ['status'];

    /**
     * 定义全局的查询范围
     * @param $query
     */
    public function scopeStatus($query)
    {
        $query->where('type_status', 1);
    }

}
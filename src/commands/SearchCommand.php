<?php

namespace hiapi\commands;

class SearchCommand extends BaseCommand
{
    public $select;
    public $where;
    public $limit;

    public function rules()
    {
        return [
            ['select', 'safe'],
            ['where', 'safe'],
            ['limit', 'number', 'max' => 100],
        ];
    }

    public function getQueryOptions()
    {
        return [
            'where' => $this->where,
            'limit' => $this->limit ?: 25,
        ];
    }
}

<?php

class Counter
{
    const PRECISION = [1,5,60,300,3600,18000,86400];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 更新计数器
     * @param $name
     * @param int $count
     * @param null $now
     */
    public function update($name,$count = 1,$now = null)
    {
        $now = is_null($now)?time():$now;

        $pipe = $this->client->pipeline();

        foreach (self::PRECISION as $prec)
        {
            $pnow = intval($now/$prec) * $prec;
            $key = $prec.":".$name;
            $pipe->zAdd("known",$key,0);
            $pipe->hIncrBy("count:".$key,$pnow,$count);
        }
        $pipe->exec();
    }

    /**
     * 获取计数器数据
     * @param $name
     * @param $prec
     * @return mixed
     */
    public function getCounter($name,$prec)
    {
        $key = "count:".$prec.":".$name;
        $data = $this->client->hGetAll($key);
        rsort($data);
        return $data;
    }
}
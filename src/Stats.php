<?php

/**
 * Class Stats
 * 5.2.2
 */

class Stats
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function update($context,$type,$value,$timeout = 5)
    {
        $key = "stats:".$context.":".$type;
        $startKey = $key.":start";
        $pipe = $this->client->pipeline();
        $end = time() + $timeout;
        while (time() < $end)
        {
            try
            {
                $pipe->watch($startKey);
                $hourStart = date("H");

                $existing = $pipe->get($startKey);

                if($existing && $existing < $hourStart)
                {
                    $pipe->rename($key,$key.":last");
                    $pipe->rename($startKey,$key.":plast");
                    $pipe->set($startKey,$hourStart);
                }

                $tKey1 = uniqid("t");
                $tKey2 = uniqid("t");

                $pipe->zAdd($tKey1,$value,"min");
                $pipe->zAdd($tKey2,$value,"max");
                $pipe->zUnion($key,[$key,$tKey1],[1,1],"MIN");
                $pipe->zUnion($key,[$key,$tKey2],[1,1],"MAX");

                $pipe->delete($tKey1,$tKey2);

                $pipe->zIncrBy($key,1,"count");
                $pipe->zIncrBy($key,$value,"sum");
                $pipe->zIncrBy($key,$value * $value,"sumsq");

                return $pipe->exec();
            }
            catch (Exception $e)
            {
                continue;
            }
        }
    }

    public function get($context,$type)
    {
        $key = "stats:".$context.":".$type;
        $data = $this->client->zRange($key,0,-1,true);
        $data['average'] = $data['sum']/$data['count'];
        return $data;
    }
}
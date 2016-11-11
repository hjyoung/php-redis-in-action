<?php

/**
 * Class Log
 * 5.1.1,5.1.2
 */

class Log
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 最近日志
     * @param $name
     * @param $message
     * @param string $severity
     * @param null $pipe
     */
    public function recent($name,$message,$severity = "info",$pipe = null)
    {
        $key = "recent:".$name.":".$severity;
        $message = date("Y-m-d H:i:s")." ".$message;
        /**@var $pipe Redis*/
        $pipe = is_null($pipe)?$this->client->pipeline():$pipe;
        $pipe->lpush($key,$message);
        $pipe->ltrim($key,0,99);
        $pipe->exec();
    }


    /**
     * 常见日志
     * @param $name
     * @param $message
     * @param string $severity
     * @param int $timeout
     */
    public function common($name,$message,$severity = "info",$timeout = 5)
    {
        $key = "common:".$name.":".$severity;
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
                    $pipe->rename($startKey,$key.":pstart");
                    $pipe->set($startKey,$hourStart);
                }
                elseif (!$existing)
                {
                    $pipe->set($startKey,$hourStart);
                }

                $pipe->zIncrBy($key,1,$message);
                $this->recent($name,$message,$severity,$pipe);
                return;
            }
            catch (Exception $e)
            {
                echo $e->getMessage()."\n";
                continue;
            }
        }
    }

}
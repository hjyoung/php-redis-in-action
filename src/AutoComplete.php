<?php


class AutoComplete
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function addOrUpdateContact($user,$contact)
    {
        $recentList = "recent:".$user;
        $pipe = $this->client->pipeline();
        $pipe->lRem($recentList,$contact,1);
        $pipe->lPush($recentList,$contact);
        $pipe->lTrim($recentList,0,99);
        $pipe->exec();
    }

    public function removeContact($user,$contact)
    {
        $this->client->lRem("recent:".$user,$contact,1);
    }

    public function fetchAutoCompleteList($user,$prefix)
    {
        $lists = $this->client->lRange("recent:".$user,0,-1);
        $matches = [];
        foreach ($lists as $contact)
        {
            if(strtolower(substr($contact,0,strlen($prefix))) == strtolower($prefix))
            {
                $matches[] = $contact;
            }
        }
        return $matches;
    }

}
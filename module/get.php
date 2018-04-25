<?php
require ("require.php");
class get
{
    private $dsn = 'mysql:host=localhost;port=3306;dbname=ippool';
    private $username = 'root';
    private $password = 'qq123456';
    private $nowdate;
    private $nowmin;
    private $nowtime;
    private $id;
	private $limit;
    public function __construct($id,$limit)
    {
        $this->nowdate=date("YmdH",time());
        //$this->nowmin=date("His",time());
        $this->nowmin=time();
        // $this->nowtime=date("His",time());
        $this->nowtime=time();
        $this->id=$id;
		$this->limit=$limit;
    }
    private function getDb($method=null) {
        return new \fangl\db\Connection($this->dsn, $this->username, $this->password);
    }

    public function getip($param=[])
    {
		$limit=$this->limit?$this->limit:10;
        $param = [];
        $db = $this->getDb();
        $ret = $db->createCommand("SELECT id,ip FROM ip_{$this->nowdate} i LEFT JOIN u_{$this->nowdate} u ON i.id=u.ipid and u.uid={$this->id} WHERE u.uid IS NULL limit 0,{$limit}", [], true)->queryAll();
        if (count($ret) != 0) {
            foreach ($ret as $key => $var) {
                echo $var['ip'] . "\n";
                $param[$key]['ipid'] = $var['id'];
                $param[$key]['uid'] = $this->id;
                $param[$key]['btime'] = $this->nowtime;
            }
            $this->getDb()->createCommand()->batchInsert("u_{$this->nowdate}", $param);
            // $this->getDb()->createCommand()->batchInsert("ip_{$this->nowdate}", $param);
        }
        else
        {
             echo "no ip";
        }
    }

}

$get=new get($_GET['id'],$_GET['l']);
$get->getip();

?>
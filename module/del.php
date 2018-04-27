<?php
require ("require.php");
class del
{
    private $dsn = 'mysql:host=localhost;port=3306;dbname=ippool';
    private $username = 'root';
    private $password = 'qq123456';
    private $nowdate;
    private $nowmin;
    private $nowtime;
    public function __construct()
	{
    $this->nowdate=date("YmdH",time());
    //$this->nowmin=date("His",time());
    $this->nowmin=time();
    // $this->nowtime=date("His",time());
    $this->nowtime=time();
	}
	public function del() {
        $db = $this->getDb();
        //echo $this->nowmin;
        if(!$ret)
        {
            $sql ="TRUNCATE u_{$this->nowdate}";
            $db->createCommand($sql)->execute();
		}
	}

}
$del=new del();
$del->del();

?>
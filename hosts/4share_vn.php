<?php

class dl_4share_vn extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://4share.vn/?control=manager", $cookie, "");
		if(stristr($data, 'Lý do: Share account!')) return array(false, 'blockAcc');
		else if(stristr($data, '<tr><td>Loại tài khoản</td><td> <b>VIP</b>	</td>	</tr>	<tr>') && stristr($data, '[<strong>VIP</strong>] | Hạn dùng: ')) return array(true, "Until ".$this->lib->cut_str($data, '[<strong>VIP</strong>] | Hạn dùng: ','| <a class=\'tbold\' style=\'color:'));
		else if(stristr($data, '<td>Loại tài khoản</td><td> <b>VIP FUNNY</b>') && stristr($data, '| Không giới hạn thời gian   |')) return array(true, "Account is VIP FUNNY. <br />Traffic available: ".$this->lib->cut_str($data, '<br />Bạn còn được download <strong>','</strong> /Tổng số:'));
		else if(stristr($data, '<td>Loại tài khoản</td><td> <b>VIP FUNNY</b>') && stristr($data, '| Đã hết hạn VIP  FUNNY  <strong>')) return array(false, "accfree");
		else if(stristr($data, '<td>Loại tài khoản</td><td> <b>MEMBER </b>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://4share.vn/?control=login","","inputUserName={$user}&inputPassword={$pass}&rememberlogin=1&submit=Login");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$post = !empty($pass) ? "pwdownload_post=".$pass : "";
		$data = $this->lib->curl($url, $this->lib->cookie, $post ? "pwdownload_post={$pass}" : "");
		if (preg_match('%<a href=\'(.+)\'><img%U', $data, $link))  return trim($link[1]);
        elseif($this->isredirect($data)) return trim($this->redirect);
  		elseif (stristr($data,"File not found"))   $this->error("dead", true, false, 2);
  		elseif (stristr($data,"FID Không hợp lệ"))  $this->error("dead", true, false, 2);		
  		elseif (stristr($data,"File đã bị xóa"))   $this->error("dead", true, false, 2);
  		elseif (stristr($data,"File có password download, hãy nhập password để download!"))  $this->error("reportpass", true, false); 
  		elseif (stristr($data,"bị khóa đến")) 	 $this->error("blockAcc", true, false);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 4Share.VN Download Plugin 
* Downloader Class By [FZ]
* Plugin By giaythuytinh176
* Date: 16.7.2013 
* Check account included - 18.7
* Fix login 4share [21.7.2013]
* Support file password by giaythuytinh176 [24.7.2013]
*/
?>
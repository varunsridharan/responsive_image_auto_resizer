<?php 
require("resize.php");
class rsimg{
	public $imageURL;
	public $height;
	public $width;
	public $croptype;
	private $imageRESOURCE;
	private $quality;
	private $cacheDIR;
	private $imgCLASS;
	function __construct (){
		$this->cacheDIR = 'rsimg_cache/';			
		$this-> checkDIR ();
		$this->get_Request();
		$this->checkImageExist();
	}
	
	public function checkImageExist() {
		$name = $this->gensavename();
		if (file_exists($this->cacheDIR.$name)) {
			$this->imageRESOURCE = $this->load_image($this->cacheDIR.$name);
			 $this->set_header();
		}  else {
			$this->imageRESOURCE = $this->load_image($this->encodeURI($this->imageURL));
			$this-> generate_imageresource();
		}
	}
	
	public function load_image($url){
		$extension = $this->getext(); 
		switch($extension) {
			case '.jpg':
			case '.jpeg':
					return imagecreatefromjpeg($url);
				break;
			case '.gif':
					return imagecreatefromgif($url);
				break;
			case '.png':
					return imagecreatefrompng($url);
				break;
			default:
				return '';
				break;
		}
	}
	
	private function set_header(){
		$extension = $this->getext(); 
		switch($extension) {
			case '.jpg':
			case '.jpeg':
					header('Content-Type: image/jpeg');
					imagejpeg($this->imageRESOURCE);
					imagedestroy($this->imageRESOURCE);
				break;
			case '.gif':
					header('Content-Type: image/gif');
					imagegif($this->imageRESOURCE);
					imagedestroy($this->imageRESOURCE);
				break;
			case '.png':
					header('Content-Type: image/png');
					imagepng($this->imageRESOURCE);
					imagedestroy($this->imageRESOURCE);
				break;
			default:
				return '';
				break;
		}
	}
	
	public function generate_imageresource(){
		$this->imgCLASS = new resize($this->imageRESOURCE);
		$this->imgCLASS->resizeImage($this->width,$this->height, $this->crop);
	 	$this->saveCACHE(); 
		unset($this->imgCLASS);
	}
	
	/**
	 * Generates Custom Cache File Name
	 * @return [[Type]] [[Description]]
	 */
	private function gensavename() {
		return $this->genname().'_'.$this->width.'_'.$this->height.'_'.$this->crop.'_'.$this->quality.$this->getext();
	}
	
	/**
	 * Extracts FILE NAME FROM URL
	 * @return [[Type]] [[Description]]
	 */
	private function genname(){ return basename($this->imageURL); }
	
	/**
	 * Extracts File Ext FROM URl
	 * @return [[Type]] [[Description]]
	 */
	private function getext(){
		$extension = strrchr($this->genname(), '.');
		return strtolower($extension);
	}
	
	/**
	 * Saves Image In Cache
	 */
	private function saveCACHE(){ 
		$saveFILENAME = $this->gensavename();
		$this->imgCLASS->saveandOUTPUT($this->cacheDIR.$saveFILENAME, "100");
	}
	
	/**
	 * Gets REQUEST Objects
	 */
	private function get_Request() {
		if(isset($_REQUEST['img']) && ! empty($_REQUEST['img'])){ $this->imageURL = $_REQUEST['img']; } 
		else { $this->imageURL = ''; }
		if(isset($_REQUEST['h']) && ! empty($_REQUEST['h'])){ $this->height = $_REQUEST['h']; } 
		else { $this->height = ''; }
		if(isset($_REQUEST['w']) && ! empty($_REQUEST['w'])){ $this->width = $_REQUEST['w']; } 
		else { $this->width = ''; }
		if(isset($_REQUEST['c']) && ! empty($_REQUEST['c'])){ $this->crop = $_REQUEST['c'];}
		else {$this->crop = 'crop';}
		if(isset($_REQUEST['q']) && ! empty($_REQUEST['q'])){ $this->quality = $_REQUEST['q'];}
		else {$this->quality = 100;}
	}

	/**
	 * Encodes URL For Internal Request
	 * @param  STRING $url [[Description]]
	 * @return STRING  URL
	 */
	private function encodeURI($url) {
		$unescaped = array('%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~','%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
		$reserved = array('%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':','%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$');
		$score = array('%23'=>'#');
		return strtr(rawurlencode($url), array_merge($reserved,$unescaped,$score));
	}

	
	private function get_fresh_image ($Url) {
		if (!function_exists('curl_init')){die('CURL is not installed!');}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //get the code of request
		curl_close($ch);
		if($httpCode == 400) 
		   return 'No donuts for you.';
		if($httpCode == 200) //is ok?
		   return $output;
	}

	
	/**
	 * Checks For Cache DIR
	 */
	private function checkDIR () {
		if (!file_exists($this->cacheDIR)) {
			mkdir($this->cacheDIR, 0777, true);
		} 
	}

}
 new rsimg;
?>

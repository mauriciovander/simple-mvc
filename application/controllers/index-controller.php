<?
		
use Gregwar\Captcha\CaptchaBuilder;

class Index_Controller extends Base_Controller {
	public function index(){

		$builder = new CaptchaBuilder();
		$builder->build();

		$_SESSION['phrase'] = $builder->getPhrase();
		echo '<img src="'.$builder->inline().'" />';

		$this->bypass();
		echo '<p><pre>'.$this.'</pre></p>';
	}
}
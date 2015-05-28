<?php
		
use Gregwar\Captcha\CaptchaBuilder;

class Index_Controller extends Base_Controller {
	public function index(){

		$builder = new CaptchaBuilder();
		$builder->build();

		$_SESSION['phrase'] = $builder->getPhrase();
		echo '<img src="'.$builder->inline().'" />';

		echo '<p>'.$_SESSION['phrase'].'</p>';

		$this->bypass();
		echo '<p><pre>'.$this.'</pre></p>';
	}

	public function sendmail() {
		$captcha = $this->captcha;
		if(empty($captcha)){
			throw new Controller_Exception("Missing captcha!", 1);
		}

		if(!isset($_SESSION['phrase']) || $captcha !== $_SESSION['phrase']){
			throw new Controller_Exception("Wrong Captcha!", 1);
		}

		// // Create the Transport
		// $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587)
		//   ->setUsername(EMAIL_USER)
		//   ->setPassword(EMAIL_PASSWORD);

		// // Create the Mailer using your created Transport
		// $mailer = Swift_Mailer::newInstance($transport);

		// // Create a message
		// $message = Swift_Message::newInstance('Wonderful Subject')
		//   ->setFrom(array('user@gmail.com' => 'Name'))
		//   ->setTo(array('user@gmail.com', 'user@gmail.com' => 'Name'))
		//   ->setBody('Here is the message itself');

		// // Send the message
		// $result = $mailer->send($message);	

		echo 'OK';
		unset($_SESSION['phrase']);
	}

}

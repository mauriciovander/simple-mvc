
<?


// https://blockchain.info/api/api_receive/


class Transaction_Controller extends Base_Controller {
	public function index(){
    
	    // http://txuy.net/transaction?input_transaction_hash=82e4253a1995de994829e33f8c58ed9e81780f966078ef010b50fcb55ddca70a&shared=false&address=1DAKHEEzx2YGZF4tUdkJzt7TNHCteUpV9&destination_address=1DAKHEEzx2YGZF4tUdkJzt7TNHCteUpV9&input_address=1BBj9WEE7bYX8GWQRWwzRwFA7BM8MZhmpR&test=true&signature=qwertyui&anonymous=false&id=12345678&confirmations=0&value=4711363480&transaction_hash=82e4253a1995de994829e33f8c58ed9e81780f966078ef010b50fcb55ddca70a
	    
	    //input_transaction_hash=82e4253a1995de994829e33f8c58ed9e81780f966078ef010b50fcb55ddca70a
	    //shared=false
	    //address=1DAKHEEzx2YGZF4tUdkJzt7TNHCteUpV9
	    //destination_address=1DAKHEEzx2YGZF4tUdkJzt7TNHCteUpV9
	    //input_address=1BBj9WEE7bYX8GWQRWwzRwFA7BM8MZhmpR
	    //test=true
	    //signature=qwertyui
	    //anonymous=false
	    //id=12345678
	    //confirmations=0
	    //value=4711363480
	    //transaction_hash=82e4253a1995de994829e33f8c58ed9e81780f966078ef010b50fcb55ddca70a
	    
	    $transaction = new Transaction_Model();
	    $transaction->input_transaction_hash = $this->input_transaction_hash;
	    $transaction->shared = $this->shared;
	    $transaction->address = $this->address;
	    $transaction->destination_address = $this->destination_address;
	    $transaction->input_address = $this->input_address;
	    $transaction->test = $this->test;
	    $transaction->signature = $this->signature;
	    $transaction->anonymous = $this->anonymous;
	    $transaction->id = $this->id;
	    $transaction->confirmations = $this->confirmations;
	    $transaction->value = $this->value;
	    $transaction->transaction_hash = $this->transaction_hash;
	    $transaction->save();
	    
	    $this->log->addInfo($transaction);
    
	}
	
	public function create(){
		$transaction = new Transaction_Model();
		$transaction->id = 'TEST_ID'; 
		$url = 'https://blockchain.info/api/receive';

		// method=create
		// cors=true
		// format=plain
		// address=1DAKHEEzx2YGZF4tUdkJzt7TNHCteUpV9
		// shared=false
		
		$signature = sha1($transaction->id . SECRET);
		
		$callback_url = urlencode('http://'.$_SERVER['HTTP_HOST'].'/transaction?id='.$transaction->id.'&signature='.$signature);
		
		
		$api_code = 'API_CODE';
		
		// $blockchain = new \Blockchain\Blockchain($api_code);
		// $response = $blockchain->Receive->generate(ADDRESS, $callback_url);
		
		$root_url = 'https://blockchain.info/api/receive';
		$parameters = 'method=create&address=' . ADDRESS .'&callback='. $callback_url;
		$response = file_get_contents($root_url . '?' . $parameters);
		$object = json_decode($response);
	
		// save response parameters
		$transaction->input_address = $object->input_address;
		$transaction->destination = $response->destination;
		$transaction->fee_percent = $response->fee_percent;

		$transaction->save();
		
		$qrCode = new Endroid\QrCode\QrCode\QrCode();
		$qrCode
		    ->setText($object->input_address)
		    ->setSize(300)
		    ->setPadding(10)
		    ->setErrorCorrection('high')
		    ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
		    ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
		    ->setLabel('Send coins to '.$object->input_address)
		    ->setLabelFontSize(16)
		    ->render()
		;
		
		$this->log->addInfo($transaction);
	}
}


<?

// https://blockchain.info/api/api_receive/

class Transaction_Controller extends Base_Controller {
	public function index(){
    
    	    if(sha1($this->id . SECRET) !== $this->signature){
    	    	throw new Controller_Exception('Invalid Signature', 1);
    	    }
    
	    $transaction = new Transaction_Model;
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
		$transaction = new Transaction_Model;
		$transaction->id = sha1(uniqid()); 
		$url = 'https://blockchain.info/api/receive';

		$signature = sha1($transaction->id . SECRET);
		
		$callback_url = urlencode('http://'.$_SERVER['HTTP_HOST'].'/transaction?id='.$transaction->id.'&signature='.$signature);
		
		$root_url = 'https://blockchain.info/api/receive';
		$parameters = 'method=create&address=' . ADDRESS .'&callback='. $callback_url;
		$response = file_get_contents($root_url . '?' . $parameters);
		$object = json_decode($response);
	
		// save response parameters
		$transaction->input_address = $object->input_address;
		$transaction->destination = $object->destination;
		$transaction->fee_percent = $object->fee_percent;

		$transaction->save();
		
		echo 'Send coins to '.$object->input_address;
		
		$this->log->addInfo($transaction);
	}
}


<?

// https://blockchain.info/api/api_receive/

class Transaction_Controller extends Base_Controller {
	public function index(){}
	
	public function update(){
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
		
		$callback_url = 'http://'.$_SERVER['HTTP_HOST'].'/transaction/update/id/'.$transaction->id.'/signature/'.$signature;
		
		// $blockchain = new \Blockchain\Blockchain(BLOCKCHAIN_API_KEY);
		// $response = $blockchain->Receive->generate(ADDRESS, $callback_url);
		
		$root_url = 'https://blockchain.info/api/receive';
		$parameters = array(
			'api_code' => BLOCKCHAIN_API_KEY,
			'method' => 'create',
			'address' => ADDRESS,
			'callback' => $callback_url
			);
			
		echo $root_url . '?' . http_build_query($parameters);
			
		$contents = file_get_contents($root_url . '?' . http_build_query($parameters));
		$response = json_decode($contents);
	
		// save response parameters
		$transaction->input_address = $response->input_address;
		$transaction->destination = $response->destination;
		$transaction->fee_percent = $response->fee_percent;

		$transaction->save();
		
		echo 'Send coins to ' . $response->input_address;
		
		$this->log->addInfo($transaction);
	}
}

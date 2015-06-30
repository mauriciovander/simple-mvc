<?

class User_Controller extends Base_Controller {
	public function index(){
	}
	
	public function add() {
	  $user = new User_Model;
	  $user->email = $this->email;
	  $user->hash = Util::hashPassword($this->password);
	  $user->firstname = $this->firstname;
	  $user->lastname = $this->lastname;
	  $user->save();
	    
	  $this->log->addInfo($user);
	}
	
	public function authenticate() {
	  $user = new User_Model;
	  $user->where('email',$this->email);
	  if(!$user->select('id_user','active','hash','firstname','lastname')) {
	     throw new Controller_Exception("Invalid User", 400);
	  }
	  
	  if($user->active != 1) {
	     throw new Controller_Exception("User is not active", 400);
	  }
	  
	  if(!Util::verifyPassword($this->password,$user->hash)) {
	     throw new Controller_Exception("Invalid password", 400);
	  }
	  	  
    echo $user;
	}
	

}

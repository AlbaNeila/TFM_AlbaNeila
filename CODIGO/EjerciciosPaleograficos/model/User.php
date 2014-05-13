<?php
include('/acceso_db.php');
class User{
	private $idUser;
	private $fields;
	private $name;
	private $surnames;
	private $dni;
	private $password;
	private $email;
	private $type;
	
	public function __construct($idUser,$name,$surnames,$dni,$password,$email,$type){
		$this->idUser = $idUser;
		$this->fields = array('name'=>$name,'surnames'=>$surnames,'dni'=>$dni,'password'=>$password,'email'=>$email,'type'=>$type);
	}
	
	public function __get($field){
		if($field == 'idUsuario'){
			return $this->idUser;
		}
		else{
			return $this->fields[$field];
		}
	}
	
	public function __set($field,$value){
		if(array_key_exists($field, $this->fields)){
			$this->fields[$field] = $value;
		}
	}
	
	public static function getById($idUser){
		$user = new User();
		$result = mysqli_query($GLOBALS['link'],"SELECT * FROM usuario WHERE usuario.idUsuario= '".$idUser."'");
		if($result){
			if($row=mysqli_fetch_assoc($result)){
				$user->idUser = $idUser;
				$user->fields['name']= $row['name'];
				$user->fields['surnames']= $row['surnames'];
				$user->fields['dni']= $row['dni'];
				$user->fields['password']= $row['password'];
				$user->fields['email']= $row['email'];
				$user->fields['type']= $row['type'];
			} 
		}
		mysqli_free_result($result);
		return $user;
	}
}
?>
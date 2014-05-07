<?php
class User{
	private $idUsuario;
	private $name;
	private $surnames;
	private $user;
	private $password;
	private $email;
	private $type;
	
	public function __construct($idUsuario,$name,$surnames,$user,$password,$email,$type){
		$this->idUsuario = $idUsuario;
		$this->name = $name;
		$this->surnames = $surnames;
		$this->user = $user;
		$this->password = $password;
		$this->email = $email;
		$this->type = $type;
	}
}
?>
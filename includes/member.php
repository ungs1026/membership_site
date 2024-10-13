<?php
// member class file

class Member
{
	private $conn; // Property

	// 생성자
	public function __construct($db)
	{
		$this->conn = $db;
	}

	// 아이디 중복체크용 멤버 함수, Method
	public function id_exists($id)
	{
		$query = "select * from member where id=:id";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return $stmt->rowCount() ? true : false;
	}

	// 이메일 형식 체크
	public function email_format_check($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	// 이메일 중복 체크
	public function email_exists($email)
	{
		$query = "select * from member where email=:email";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		return $stmt->rowCount() ? true : false;
	}

	// 회원정보 입력
	public function input($marr)
	{
		$query = 'insert into member(id, name, email, password, zipcode, addr1, addr2, photo, create_at, ip) values
						(:id, :name, :email, :password, :zipcode, :addr1, :addr2, :photo, now(), :ip)';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id', $marr['id']);
		$stmt->bindParam(':name', $marr['name']);
		$stmt->bindParam(':email', $marr['email']);
		$stmt->bindParam(':password', $marr['password']);
		$stmt->bindParam(':zipcode', $marr['zipcode']);
		$stmt->bindParam(':addr1', $marr['addr1']);
		$stmt->bindParam(':addr2', $marr['addr2']);
		$stmt->bindParam(':photo', $marr['photo']);
		$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		$stmt->execute();
	}
}

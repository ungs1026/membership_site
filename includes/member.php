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
	public function email_format_check($email)
	{
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
		// 단방향 암호화
		$new_hash_password = password_hash($marr['password'], PASSWORD_DEFAULT);

		$query = 'insert into member(id, name, email, password, zipcode, addr1, addr2, photo, create_at, ip) values
						(:id, :name, :email, :password, :zipcode, :addr1, :addr2, :photo, now(), :ip)';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id', $marr['id']);
		$stmt->bindParam(':name', $marr['name']);
		$stmt->bindParam(':email', $marr['email']);
		$stmt->bindParam(':password', $new_hash_password);
		$stmt->bindParam(':zipcode', $marr['zipcode']);
		$stmt->bindParam(':addr1', $marr['addr1']);
		$stmt->bindParam(':addr2', $marr['addr2']);
		$stmt->bindParam(':photo', $marr['photo']);
		$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		$stmt->execute();
	}

	// 로그인
	public function login($id, $pw)
	{

		// password_verify($pw, $new_password);

		$query = "SELECT password From member where id=:id";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id', $id);

		$stmt->execute();

		if ($stmt->rowCount()) {
			$row = $stmt->fetch();

			if (password_verify($pw, $row['password'])) {
				$query = "update member set login_dt=now() where id=:id";
				$stmt = $this->conn->prepare($query);
				$stmt->bindParam(':id', $id);
				$stmt->execute();

				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	// 로그아웃
	public function logout()
	{
		session_start();
		session_destroy();

		die('<script>	self.location.href="../index.php";</script>');
	}

	public function getInfo($id)
	{
		$query = "select * from member where id=:id";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id', $id);
		$stmt->setFetchMode(PDO::FETCH_ASSOC); // FETCH_NUM (index로만 나옴)
		$stmt->execute();
		return $stmt->fetch();
	}

	public function edit($marr)
	{
		$query = "update member set name=:name, email=:email, zipcode=:zipcode, addr1=:addr1, addr2=:addr2, photo=:photo";
		$params = [
			':name' => $marr['name'],
			':email' => $marr['email'],
			':zipcode' => $marr['zipcode'],
			':addr1' => $marr['addr1'],
			':addr2' => $marr['addr2'],
			':photo' => $marr['photo'],
			':id' => $marr['id']
		];
		if ($marr['password'] != '') {
			// 단방향 암호화
			$new_hash_password = password_hash($marr['password'], PASSWORD_DEFAULT);
			$params[':password'] = $new_hash_password;

			$query .= ", password=:password";
		}

		$query .= ' where id=:id';

		$stmt = $this->conn->prepare($query);
		$stmt->execute($params);
	}

	// 회원 목록
	public function list($page, $limit, $paramArr)
	{
		$start = ($page - 1) * $limit;

		$where = '';
		if ($paramArr['sn'] != '' && $paramArr['sf'] != '') {
			switch ($paramArr['sn']) {
				case 1:
					$sn_str = 'name';
					break;
				case 2:
					$sn_str = 'id';
					break;
				case 3:
					$sn_str = 'email';
					break;
			}

			$where = "where " . $sn_str . "=:sf";
		}

		// 번호 아이디 이름 이메일 등록일시
		$query = "select idx, id, name, email, DATE_FORMAT(create_at, '%Y-%m-%d %H:%i') create_at 
							from member " . $where . "
							order by idx desc limit " . $start . "," . $limit;

		$stmt = $this->conn->prepare($query);

		if ($where != '') {
			$stmt->bindParam(':sf', $paramArr['sf']);
		}

		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	public function total($paramArr)
	{
		$where = '';
		if ($paramArr['sn'] != '' && $paramArr['sf'] != '') {
			switch ($paramArr['sn']) {
				case 1:
					$sn_str = 'name';
					break;
				case 2:
					$sn_str = 'id';
					break;
				case 3:
					$sn_str = 'email';
					break;
			}

			$where = "where " . $sn_str . "=:sf";
		}

		$query = "select COUNT(*) cnt from member ". $where;
		$stmt = $this->conn->prepare($query);

		if ($where != '') {
			$stmt->bindParam(':sf', $paramArr['sf']);
		}

		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();
		$row = $stmt->fetch();

		return $row['cnt'];
	}

	// excel
	public function getAllData()
	{
		// 번호 아이디 이름 이메일 등록일시
		$query = "select * from member order by idx asc";

		$stmt = $this->conn->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	// del member
	public function member_del($idx) {
		$query = "delete from member where idx=:idx";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':idx', $idx);
		$stmt->execute();
	}
}

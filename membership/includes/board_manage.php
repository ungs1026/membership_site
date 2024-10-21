<?php

// 게시판 관리 클래스

class BoardManage
{
	private $conn;

	// 생성자
	public function __construct($db)
	{
		$this->conn = $db;
	}

	// 게시판 목록
	public function list()
	{
		// 번호 아이디 이름 이메일 등록일시
		$query = "select idx, name, bcode, btype, cnt, DATE_FORMAT(create_at, '%Y-%m-%d %H:%i') create_at 
							from board_manage
							order by idx asc";

		$stmt = $this->conn->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	// 게시판 생성
	public function create($arr)
	{
		$query = 'insert into board_manage(name, bcode, btype, create_at) values 
			(:name, :bcode, :btype, now())';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':name', $arr['name']);
		$stmt->bindParam(':bcode', $arr['bcode']);
		$stmt->bindParam(':btype', $arr['btype']);
		$stmt->execute();
	}

	// 게시판 정보 수정
	public function update($arr) {
		$query = 'update board_manage set name=:name, btype=:btype where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(':name', $arr['name']);
		$stmt->bindValue(':btype', $arr['btype']);
		$stmt->bindValue(':idx', $arr['idx']);
		$stmt->execute();
	}

	// 게시판 idx로 게시판 정보 가져오기
	public function getBcode($idx) {
		$query = "select bcode from board_manage where idx=:idx";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':idx', $idx);
		$stmt->setFetchMode(PDO::FETCH_COLUMN, 0);
		$stmt->execute();
		return $stmt->fetch();
	}

	// 게시판 삭제
	public function delete($idx) {
		// bcode
		$bcode = $this->getBcode($idx);

		$query = 'delete from board_manage where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':idx', $idx);
		$stmt->execute();

		// 
		$query = 'delete from board where bcode=:bcode';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':bcode', $bcode);
		$stmt->execute();
	}

	// 게시판 코드 생성
	public function bcode_create()
	{
		$letter = range('a', 'z');
		$bcode = '';
		for ($i = 0; $i < 6; $i++) {
			$r = rand(0, 25);
			$bcode .= $letter[$r];
		}
		return $bcode;
	}

	// 게시판 정보 불러오기
	public function getInfo($idx) {
		$query = 'select * from board_manage where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':idx', $idx);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();
		return $stmt->fetch();
	}

	// 게시판 코드로 게시판 명 가져오기
	public function getBoardName($bcode) {
		$query = 'select name from board_manage where bcode=:bcode';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':bcode', $bcode);
		$stmt->setFetchMode(PDO::FETCH_COLUMN, 0);
		$stmt->execute();
		return $stmt->fetch();
	}
}
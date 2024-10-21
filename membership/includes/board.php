<?php

// 게시판 클래스
class Board {
	private $conn;

	public function __construct($db) {
		$this->conn = $db;
	}

	// 글 등록
	public function input($arr) {
		// bcode , id, name, subject, content, hit, ip, create_at
		$query = 'insert into board( bcode, id, name, subject, content, files, ip, create_at) values
						(:bcode, :id, :name, :subject, :content, :files, :ip, now())';
		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(':bcode', $arr['bcode']);
		$stmt->bindValue(':id', $arr['id']);
		$stmt->bindValue(':name', $arr['name']);
		$stmt->bindValue(':subject', $arr['subject']);
		$stmt->bindValue(':content', $arr['content']);
		$stmt->bindValue(':files', $arr['files']);
		$stmt->bindValue(':ip', $arr['ip']);
		$stmt->execute();
	}

}
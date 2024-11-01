<?php

// 게시판 클래스
class Board
{
	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	// 글 등록
	public function input($arr)
	{
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

	// 글 수정
	public function edit($arr)
	{
		$query = 'update board set subject=:subject, content=:content where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$params = [':subject' => $arr['subject'], ':content' => $arr['content'], ':idx' => $arr['idx']];
		$stmt->execute($params);
	}

	// 글 목록
	public function list($bcode, $page, $limit, $paramArr)
	{
		$start = ($page - 1) * $limit;

		$where = 'where bcode=:bcode ';
		$params = [':bcode' => $bcode];
		if (isset($paramArr['sn']) && $paramArr['sn'] != '' && isset($paramArr['sf']) && $paramArr['sf'] != '') {
			switch ($paramArr['sn']) {
				case 1:	 // 제목 + 내용
					$where .= "and (subject like concat('%', :sf, '%') or (content like concat('%', :sf2, '%')))";
					$params = [':bcode' => $bcode, ':sf' => $paramArr['sf'], ':sf2' => $paramArr['sf']];
					break;
				case 2: // 제목
					$where .= "and (subject like concat('%', :sf, '%'))";
					$params = [':bcode' => $bcode, ':sf' => $paramArr['sf']];
					break;
				case 3: // 내용
					$where .= "and (content like concat('%', :sf, '%'))";
					$params = [':bcode' => $bcode, ':sf' => $paramArr['sf']];
					break;
				case 3: // 글쓴이
					$where .= "and (name=:sf)";
					$params = [':bcode' => $bcode, ':sf' => $paramArr['sf']];
					break;
			}
		}

		// 번호 아이디 이름 이메일 등록일시
		$query = "select idx, id, subject, name, hit, comment_cnt, DATE_FORMAT(create_at, '%Y-%m-%d %H:%i') create_at 
							from board " . $where . "
							order by idx desc limit " . $start . "," . $limit;

		$stmt = $this->conn->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute($params);

		return $stmt->fetchAll();
	}

	// total
	public function total($bcode, $paramArr)
	{
		$where = 'where bcode=:bcode ';
		$params = [':bcode' => $bcode];
		if (isset($paramArr['sn']) && $paramArr['sn'] != '' && isset($paramArr['sf']) && $paramArr['sf'] != '') {
			switch ($paramArr['sn']) {
				case 1:	 // 제목 + 내용
					$where .= "and (subject like concat('%', :sf, '%') or (content like concat('%', :sf2, '%')))";
					$params = [':bcode' => $bcode, ':sf' => $paramArr['sf'], ':sf2' => $paramArr['sf']];
					break;
				case 2: // 제목
					$where .= "and (subject like concat('%', :sf, '%'))";
					$params = [':bcode' => $bcode, ':sf' => $paramArr['sf']];
					break;
				case 3: // 내용
					$where .= "and (content like concat('%', :sf, '%'))";
					$params = [':bcode' => $bcode, ':sf' => $paramArr['sf']];
					break;
				case 3: // 글쓴이
					$where .= "and (name=:sf)";
					$params = [':bcode' => $bcode, ':sf' => $paramArr['sf']];
					break;
			}
		}

		$query = "select COUNT(*) cnt from board " . $where;
		$stmt = $this->conn->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute($params);
		$row = $stmt->fetch();

		return $row['cnt'];
	}

	// 글보기
	public function view($idx)
	{
		$sql = "SELECT * FROM board WHERE idx=:idx";
		$stmt = $this->conn->prepare($sql);
		$params = [":idx" => $idx];
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute($params);
		return $stmt->fetch();
	}

	// 글 조회수 증가
	public function hitInc($idx)
	{
		$query = 'update board set hit=hit+1 where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$params = [':idx' => $idx];
		$stmt->execute($params);
	}

	// 파일 목록 업데이트
	public function updateFileList($idx, $files, $downs)
	{
		$query = 'update board set files=:files, downhit=:downhit where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$params = [':idx' => $idx, ':files' => $files, ':downhit' => $downs];
		$stmt->execute($params);
	}

	// 첨부 파일 Download
	public function getAttachFile($idx, $th)
	{
		$query = 'select files from board where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$params = [':idx' => $idx];
		$stmt->execute($params);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$row = $stmt->fetch();

		$filelist = explode('?', $row['files']);

		return $filelist[$th] . '|' . count($filelist);
	}

	// 다운로드 횟수
	public function getDownhit($idx)
	{
		$query = 'select downhit from board where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$params = [':idx' => $idx];
		$stmt->execute($params);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$row = $stmt->fetch();

		return $row['downhit'];
	}

	// 다운로드 횟수 증가
	public function increaseDownhit($idx, $downhit)
	{
		$query = 'update board set downhit=:downhit where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$params = [":downhit" => $downhit, "idx" => $idx];
		$stmt->execute($params);
	}

	// last reader 값 변경
	public function updateLastReader($idx, $str)
	{
		$query = 'update board set last_reader=:last_reader where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$params =  [":last_reader" => $str, "idx" => $idx];
		$stmt->execute($params);
	}

	// 파일 첨부
	public function file_attach($files, $file_cnt)
	{

		$files = is_array($files['name']) ? $files : array_map(fn($item) => [$item], $files);
		if (sizeof($files['name']) != 1 && sizeof($files['name']) > $file_cnt) {
			$arr = ["result" => "file_upload_count_exeed"];
			die(json_encode($arr));
		}

		$tmp_arr = [];
		foreach ($files['name'] as $key => $val) {
			//$files['name'][$key];
			$full_str = '';

			$tmparr = explode('.', $files['name'][$key]);
			$ext = end($tmparr);

			$not_arrowed_file_ext = ['txt', 'exe', 'xls'];

			if (in_array($ext, $not_arrowed_file_ext)) {
				$arr = ['result' => 'not_allowed_file'];
				die(json_encode($arr));
			}


			$flag = rand(1000, 9999);
			$filename = 'a' . date('YmdHis') . $flag . '.' . $ext;
			$file_ori = $files['name'][$key];
			//  a2023112322234434.jpg|새파일.jpg

			// copy() move_uploaded_file()
			copy($files['tmp_name'][$key], BOARD_DIR . '/' . $filename);

			$full_str = $filename . '|' . $file_ori;
			$tmp_arr[]  = $full_str;
		}

		return implode('?', $tmp_arr);
	}

	public function extract_image($content)
	{
		// 이미지 정규화
		preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);
		$img_array = [];
		foreach ($matches[1] as $key => $row) {
			$img_array[] = $row;
		}
		return $img_array;
	}

	public function delete($idx) {
		$query = 'delete from board where idx=:idx';
		$stmt = $this->conn->prepare($query);
		$params = [':idx' => $idx];
		$stmt->execute($params);
	}
} // class Board

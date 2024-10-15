<?php

/**
 * total : 게시물 총 갯수
 * limit : 한 화면 출력 갯수
 *  page_limit : 출력 페이지 수
 * page : 현 페이지
 */
function My_Pagination($total, $limit, $page_limit, $page, $param)
{
	$total_page = ceil($total / $limit);

	$start_page = ((floor(($page - 1) / $page_limit)) * $page_limit) + 1;
	$end_page = $start_page + $page_limit - 1;

	if ($end_page > $total_page) {
		$end_page = $total_page;
	}

	$prev_page = $start_page - 1;

	if ($prev_page < 1) {
		$prev_page = 1;
	}

	$rs_str = '	<nav>
		<ul class="pagination">';

	$rs_str .= '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=1' . $param . '">FIRST</a></li>';

	if ($prev_page > 1) {
		$rs_str .= '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . $prev_page . $param . '">PREV</a></li> ';
	}

	for ($i = $start_page; $i <= $end_page; $i++) {
		if ($page == $i) {
			$rs_str .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
		} else {
			$rs_str .= '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . $param . '">' . $i . '</a></li>';
		}
	}

	$next_page = $end_page + 1;

	if ($next_page <= $total_page) {
		$rs_str .= '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . $next_page . $param . '">NEXT</a></li> ';
	}

	$rs_str .= '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . $total_page . $param . '">LAST</a></li>';

	$rs_str .= '</ul></nav>';

	return $rs_str;
}
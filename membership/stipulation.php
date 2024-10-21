<?php
	$js_array = ['js/member.js'];

	$menu_code = 'member';

	$g_title = '약관확인';

	include 'includes/part/inc_header.php';
?>
<main class="p-5 border rounded-5">
	<h1 class="text-center">회원 약관 및 개인정보 취급방침 동의</h1>
	<h4>회원 약관</h4>
	<textarea name="" id="" cols="30" rows="10" class="form-control">
				Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim eaque assumenda, odio, ex dolorum maxime saepe ea aliquid officia quo delectus ducimus, temporibus rerum similique maiores soluta ipsa! Sapiente, repellendus earum molestiae odio, ab debitis autem temporibus harum rem laudantium, quod sunt fugit voluptatum voluptas natus praesentium placeat hic aliquam adipisci ullam dolorum libero dolor! Maxime eveniet ipsa ex sit illum at autem dolores, corrupti delectus. Quis sunt unde accusantium exercitationem atque necessitatibus, quidem assumenda explicabo non quibusdam, cum soluta expedita. Doloremque voluptatem, ratione dolore nihil tempora ea expedita ex aspernatur natus commodi cupiditate quo nobis illo corrupti totam, deleniti ut beatae! Velit placeat maxime odio cupiditate laudantium repudiandae ex porro ea enim, non consequatur nam? Aperiam est excepturi, dolore similique vero dignissimos cum quis dolores quasi perspiciatis, unde aut magni veniam. Accusantium veritatis nostrum quidem quasi earum obcaecati! Ea minus totam, esse aliquid doloribus enim a distinctio laudantium eum doloremque et officia rerum explicabo cumque voluptates odio iusto vel voluptatibus nam amet quasi alias commodi id? Tenetur culpa provident veniam laborum corporis impedit, nesciunt enim veritatis commodi quis quaerat officia aspernatur similique dignissimos ullam, minus praesentium earum, doloribus cupiditate iusto vel consequuntur nobis. Exercitationem sint porro beatae, dicta sunt tempore saepe! Sed reprehenderit minus illo. Delectus ipsum culpa odit quae facilis hic? Rerum consectetur maiores repudiandae temporibus, odio aspernatur laborum ab sequi architecto mollitia voluptas, recusandae veniam tenetur nam debitis perspiciatis? Libero ullam doloribus facilis. Doloribus, facilis modi deleniti corrupti harum nemo quaerat veniam dignissimos pariatur praesentium non iste fuga. Cupiditate nobis maxime quisquam nihil animi aut autem ut obcaecati a ipsa nisi nulla esse vero id commodi in ratione sunt odit, quae, dolorum iusto laudantium dolor labore praesentium! Placeat aspernatur eveniet et harum vel reiciendis ipsam, assumenda, officia vitae laudantium unde, architecto veniam tempora inventore officiis suscipit necessitatibus.
			</textarea>

	<div class="form-check mt-2">
		<input class="form-check-input" type="checkbox" value="1" id="chk_member1">
		<label class="form-check-label" for="chk_member1">
			위 약관에 동의하시겠습니까?
		</label>
	</div>

	<h4 class="mt-3">개인정보 취급방침</h4>
	<textarea name="" id="" cols="30" rows="10" class="form-control">
				Lorem, ipsum dolor sit amet consectetur adipisicing elit. Harum, perspiciatis in ut atque tempora excepturi ducimus magnam dignissimos laudantium enim ex earum? Deserunt impedit porro quis quidem culpa quas quasi, hic aspernatur. Molestiae vero unde nisi officiis aspernatur, voluptas, minus, explicabo rem accusamus beatae provident perspiciatis reiciendis velit ea excepturi quam praesentium. Saepe labore a excepturi quod neque itaque ratione! Ipsum quam sunt nostrum delectus provident. Iste repellat a aliquid eius error minus magni consectetur voluptatum. Reprehenderit quam ratione facere nam alias voluptas at labore eaque deleniti veniam. Quam cupiditate quae, maxime perspiciatis inventore mollitia pariatur veritatis doloremque minima? Quibusdam, unde dolore impedit quam minus enim placeat illo molestias nobis, facere tenetur possimus, incidunt eius animi! Id dolorem sed quas delectus corrupti dolor, unde error consequatur neque excepturi fugiat recusandae modi vero nostrum eius perspiciatis a hic, maxime possimus illum ab. Excepturi autem eaque, unde reprehenderit provident incidunt eum? Facere quos numquam autem laboriosam eos esse sunt qui provident alias eaque, veniam cum consectetur, reiciendis eveniet ex aliquid laborum sit minima dicta optio dolorum sapiente! Qui eius accusantium distinctio non unde voluptate sunt ipsam nemo dolores doloribus. Culpa eum doloribus modi quam aspernatur, eveniet rem nulla! Cupiditate cumque nam ipsum!
			</textarea>

	<div class="form-check mt-2">
		<input class="form-check-input" type="checkbox" value="2" id="chk_member2">
		<label class="form-check-label" for="chk_member2">
			위 개인정보 취급방침에 동의하시겠습니까?
		</label>
	</div>

	<div class="mt-4 d-flex justify-content-center gap-2">
		<button class="btn btn-primary w-50" id="btn_member">회원가입</button>
		<button class="btn btn-secondary w-50">가입취소</button>
	</div>

	<form method="post" name="stipulation_form" action="member_input.php" style="display: none;">
		<input type="hidden" name="chk" value="0">
	</form>

</main>
<?php include 'includes/part/inc_footer.php'; ?>
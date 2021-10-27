
// 名前編集モーダル
$(function() {
	var scrollPos;//topからのスクロール位置
	// 名前編集モーダル
	// $('#name-edit-tag').click(function() {
	// 	scrollPos = $(window).scrollTop();//topからのスクロール位置を格納
	// 	$('#name-edit-modal').fadeIn();//モーダルをフェードイン
	// 	$('body').addClass('fixed');//背景固定
	// });

	// スキル編集モーダル
	$('#skill-edit-tag').click(function() {
		scrollPos = $(window).scrollTop();
		$('#skill-edit-modal').fadeIn();
		$('body').addClass('fixed');
	});

	$('#url-edit-tag').click(function() {
		scrollPos = $(window).scrollTop();
		$('#url-edit-modal').fadeIn();
		$('body').addClass('fixed');
	});

	$('#intro-edit-tag').click(function() {
		scrollPos = $(window).scrollTop();
		$('#intro-edit-modal').fadeIn();
		$('body').addClass('fixed');
	});

	$('#birthday-edit-tag').click(function() {
		scrollPos = $(window).scrollTop();
		$('#birthday-edit-modal').fadeIn();
		$('body').addClass('fixed');
	});

	$('#engineer-career-edit-tag').click(function() {
		scrollPos = $(window).scrollTop();
		$('#engineer-career-edit-modal').fadeIn();
		$('body').addClass('fixed');
	});


	$('.overlay, .modal-close').click(function() {
		$('.content-modal').fadeOut();//モーダルをフェードアウト
		$('body').removeClass('fixed');//背景固定を解除
		$(window).scrollTop(scrollPos);//元の位置までスクロール
	});
});

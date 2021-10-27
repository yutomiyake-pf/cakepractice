<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php echo $this->Html->css('pc/Logins/index.css'); ?>
		<?php echo $this->Html->css('pc/footer.css'); ?>
		<?php echo $this->Html->css('pc/session.css'); ?>
		<?php echo $this->Html->script('jquery-3.6.0.min.js'); ?>
		<?php echo $this->Html->script('session.js'); ?>
		<title>ログイン</title>
	</head>

	<body>
		<div class="session-wrap">
			<?php echo $this->Session->flash(); ?>
		</div>

		<div id="main-wrap">
			<div id="title-wrap">
				<h1 class="title">Team<span style="color: red;">Up</span></h1>
				<h2 class="sub-title">We ourselves will create the future that doesn't exist here now.</h2>
				<!-- <h2 class="sub-title"></h2> -->
			</div>

			<div id="login-form-wrap">
				<?php echo $this->Form->create('',['Controller' => 'Logins','url' => 'login','id' => false]); ?>
				<?php echo $this->Form->input('email',[
						'label' => false,
						'error' => false,
						'div' => false,
						'id' => false,
						'class' => 'input text',
						'placeholder' => 'メールアドレス',
						'type' => 'email'
				]);
				?>
				<?php echo $this->Form->input('password',[
						'label' => false,
						'error' => false,
						'div' => false,
						'id' => false,
						'class' => 'input pass',
						'placeholder' => 'パスワード',
						'type' => 'password'
				]);
				?>
				<?php echo $this->Form->submit('ログイン',[
					'div' => false,
					'id' => false,
					'class' => 'login-btn',
				]);
				?>
				<?php echo $this->Form->end(); ?>

			</div>

			<div id="sub-content-wrap">
				<h2 class="sub-title">TeamUpはエンジニアのプロフィールサイトです</h2>
				<h2 class="sub-title">シンプル会員登録は<u><span id="regi-modal-open">こちら</span></u></h2>
			</div>

<!------------------ 会員登録モーダル --------------------------------------------------->
<!------------------ ------------------------------------------------------------------>
<!------------------ ------------------------------------------------------------------>
			<div id="regi-form-mask" class="hidden"></div>
			<div id="regi-form-modal" class="hidden">
				<u><h2 style="font-size: 25px;">会員登録</h2></u>
				<?php echo $this->Form->create('Login',['Controller' => 'Logins','url' => 'index','class' => 'regi-form','onsubmit'=>'return confirm("登録を完了します。よろしいですか？")']); ?>
				<?php echo $this->Form->input('last_name',[
						'label' => false,
						'error' => false,
						'div' => false,
						'class' => 'input name-input',
						'placeholder' => '苗字',
				]);
				?>
				<?php echo $this->Form->input('first_name',[
						'label' => false,
						'error' => false,
						'div' => false,
						'class' => 'input name-input',
						'placeholder' => '名前',
				]);
				?>
				<br>*苗字、名前はマイページなどには公開されません<br>

				<?php echo $this->Form->input('user_name',[
						'label' => false,
						'error' => false,
						'div' => false,
						'class' => 'input regi-input',
						'placeholder' => 'ユーザー名',
				]);
				?>

				<?php echo $this->Form->input('email',[
						'label' => false,
						'error' => false,
						'div' => false,
						'class' => 'input regi-input',
						'placeholder' => 'メールアドレス',
						'type' => 'email'
				]);
				?>
				<br>

				*初期パスワードはご登録されたemail宛に自動発行されます<br><br>
				<a href="" target="_blank" rel="noopener noreferrer">利用規約</a>に同意する<?php echo $this->Form->checkbox('agreeService',[
					'hiddenField' => false,
					'error' => false,
					'div' => false,
					'class' => 'regi-check',
					'value' => 1
				]); ?>
				<br><br>
				<?php echo $this->Form->submit('登録する',[
					'div' => false,
					'class' => 'login-btn register-btn',
				]);
				?>
				<?php echo $this->Form->end(); ?>
				<br><br>
				<span id="close">閉じる</span>
			</div>
<!------------------ --------------------------------------------------------------------->
<!------------------ --------------------------------------------------------------------->
<!--------------- 会員登録モーダルここまで --------------------------------------------------->
		<?php echo $this->element('pc/footer'); ?>
		</div>

		<script>
			registerModal();
			//会員登録モーダル
			function registerModal() {
				const open = document.getElementById('regi-modal-open');
				const close = document.getElementById('close');
				const modal = document.getElementById('regi-form-modal');
				const mask = document.getElementById('regi-form-mask');

				open.addEventListener('click',()=>{
					modal.classList.remove('hidden');
					mask.classList.remove('hidden');
				});

				close.addEventListener('click',()=>{
					modal.classList.add('hidden');
					mask.classList.add('hidden');
				});

				mask.addEventListener('click',()=>{
					close.click();
				});
			}
		</script>
	</body>
</html>

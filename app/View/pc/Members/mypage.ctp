<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <?php echo $this->Html->css('pc/simple_reset.css'); ?>
    <?php echo $this->Html->css('pc/Members/mypage.css'); ?>
    <?php echo $this->Html->css('pc/header.css'); ?>
    <?php echo $this->Html->css('pc/footer.css'); ?>
    <?php echo $this->Html->css('pc/session.css'); ?>
    <?php echo $this->Html->script('jquery-3.6.0.min.js'); ?>
    <?php echo $this->Html->script('session.js'); ?>
    <title>マイページ</title>
  </head>
  <body>
      <?php echo $this->element('/pc/default'); ?>

    <!-- edit modal -->
    <!------------------------------------------------------------------------->
    <!-------------------------------------------------------------------------->
    <!--------------------------------------------------------------------------->
    <!---------------------------------------------------------------------------->

    <!--名前編集モーダル ---------------------------------------------------------->
    <!-- <div id="name-edit-modal" class="content-modal">
    	<div class="overlay"></div>
    	<div id="name-edit-content" class="modal">
    		<p>名前編集モーダル</p>
    		<a href="" class="modal__close">閉じる</a>
    	</div>
    </div> -->
    <!-------------------------------------------------------------------------->

    <!-- スキル編集モーダル ------------------------------------------------------------------------------------------------>
    <div id="skill-edit-modal" class="content-modal">
    	<div class="overlay"></div>
    	<div id="skill-edit-content" class="modal">
            <div class="modal-top-messege">
                <u><p>登録するスキルを選んでください</p></u>
            </div>
    		<div id="skill-main-wrap" class="modal-main-wrap">
                <?php echo $this->Form->create('UserSkill',['controller' => 'Members','url' => 'insertUserSkill']); ?>
                <table border="1">
                    <?php foreach($skills as $key=>$value): ?>
                        <tr>
                            <td id="skill-label"><?php echo h($value); ?></td>
                            <td>
                                <?php echo $this->Form->input('Skill.' .h($key),[
                                    'type' => 'checkbox',
                                    'div' => false,
                                    'label' => false,
                                    'id' => false,
                                    'class' => 'skill-input',
                                    'hiddenField' => false,
                                    'value' => h($key),
                                ]);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <?php echo $this->Form->submit('スキルを登録',[
                'class' => "submit-btn"
            ]); ?>
            <?php echo $this->Form->end(); ?>

            <?php if ($userSkills): ?>
            <div class="dele-btn-wrap">
                <?php echo $this->Form->postLink('登録されているスキルを全て削除する',
                    ['controller' => 'Members','action' => 'deleteAllUserSkill'],
                    ['class' => 'dele-btn'],
                    '登録されているスキルが全て削除されます。よろしいですか？'
                );
                ?>
            </div>
            <?php endif; ?>

            <div class="modal-top-messege">
                <p class="modal-close">とじる</p>
            </div>
    	</div>
    </div>
    <!------------------------------------------------------------------------------------------------------>
    <!------------------------------------------------------------------------------------------------------>

    <!--url編集 -------------------------------------------------------------------------------------------->
    <div id="url-edit-modal" class="content-modal">
    	<div class="overlay"></div>
    	<div id="url-edit-content" class="modal">
            <div class="modal-top-messege">
                <u><p>登録するURLを入力してください</p></u>
            </div>
            <div class="modal-main-wrap">
                <div class="modal-form-wrap">
                    <?php echo $this->Form->create('SnsUrl',['controller' => 'Members','url' => 'insertSnsUrl']); ?>
                    <i class="fab fa-github fa-2x modal-icons"></i><span class="service-name">github</span>
                    <?php echo $this->Form->input('github',[
                        'label' => false,
                        'error' => false,
                        'dev' => false,
                        'class' => 'sns-input',
                        'placeholder' => 'github',
                        'type' => 'url',
                        'value' => $loginParams['logSnsUrls'] && $loginParams['logSnsUrls']['github'] ? $loginParams['logSnsUrls']['github'] : '',
                    ]);
                    ?>

                    <i class="fab fa-twitter fa-2x modal-icons"></i><span class="service-name">twitter</span>
                    <?php echo $this->Form->input('twitter',[
                        'label' => false,
                        'error' => false,
                        'dev' => false,
                        'class' => 'sns-input',
                        'placeholder' => 'twitter',
                        'type' => 'url',
                        'value' => $loginParams['logSnsUrls'] && $loginParams['logSnsUrls']['twitter'] ? $loginParams['logSnsUrls']['twitter'] : '',
                    ]);
                    ?>

                    <i class="fab fa-facebook-square fa-2x modal-icons"></i><span class="service-name">facebook</span>
                    <?php echo $this->Form->input('facebook',[
                        'label' => false,
                        'error' => false,
                        'dev' => false,
                        'class' => 'sns-input',
                        'placeholder' => 'facebook',
                        'type' => 'url',
                        'value' => $loginParams['logSnsUrls'] && $loginParams['logSnsUrls']['facebook'] ? $loginParams['logSnsUrls']['facebook'] : '',
                    ]);
                    ?>

                    <i class="fab fa-instagram fa-2x modal-icons"></i><span class="service-name">instagram</span>
                    <?php echo $this->Form->input('instagram',[
                        'label' => false,
                        'error' => false,
                        'dev' => false,
                        'class' => 'sns-input',
                        'placeholder' => 'instagram',
                        'type' => 'url',
                        'value' => $loginParams['logSnsUrls'] && $loginParams['logSnsUrls']['instagram'] ? $loginParams['logSnsUrls']['instagram'] : '',
                    ]);
                    ?>
                </div>
                <?php echo $this->Form->submit('URLを登録',[
                    'class' => "submit-btn"
                ]); ?>
                <?php echo $this->Form->end(); ?>

                <?php if ($loginParams['logSnsUrls']): ?>
                <div class="dele-btn-wrap">
                    <?php echo $this->Form->postLink('登録されているURLを全て削除する',
                        ['controller' => 'Members','action' => 'deleteAllSnsUrl'],
                        ['class' => 'dele-btn'],
                        '登録されているURLが全て削除されます。よろしいですか？'
                    );
                    ?>
                </div>
                <?php endif; ?>

                <div class="modal-top-messege">
                    <p class="modal-close">とじる</p>
                </div>
            </div>
        </div>
    </div>

    <!-------------------------------------------------------------------------------------------------------------------------->
    <!--------------------------------------------------------------------------------------------------------------------------->

    <!-- 紹介文編集 --------------------------------------------------------------------------------------------------------------->
    <div id="intro-edit-modal" class="content-modal">
    	<div class="overlay"></div>
    	<div id="intro-edit-content" class="modal">
            <div class="modal-top-messege">
                <u><p>登録する自己紹介文を入力してください</p></u>
            </div>
            <div class="modal-main-wrap">
                <div class="modal-form-wrap">
                    <?php echo $this->Form->create('LoginIntroduction',['controller' => 'Members','url' => 'insertIntroduction']); ?>
                    <?php echo $this->Form->textarea('introduction',[
                        'row' => 10,
                        'cols' => 10,
                        'error' => false,
                        'id' => false,
                        'label' => false,
                        'value' => $loginParams['loginIntro'] === false ? '' : h($loginParams['loginIntro']),
                        'class' => 'intro-textarea'
                    ]);
                    ?>
                </div>
                <?php echo $this->Form->submit('紹介文を登録',[
                    'class' => "submit-btn"
                ]); ?>
                <?php echo $this->Form->end(); ?>

                <?php if ($loginParams['loginIntro'] !== false && $loginParams['loginIntro'] !== ''): ?>
                <div class="dele-btn-wrap">
                    <?php echo $this->Form->postLink('登録されている紹介文を削除する',
                        ['controller' => 'Members','action' => 'deleteIntro'],
                        ['class' => 'dele-btn'],
                        '登録されている紹介文が削除されます。よろしいですか？'
                    );
                    ?>
                </div>
                <?php endif; ?>

                <div class="modal-top-messege">
                    <p class="modal-close">とじる</p>
                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------------------------------------------------------------->
    <!-------------------------------------------------------------------------------------------------------------------------------->

    <!-- 生年月日編集------------------------------------------------------------------------------------------------------------------->
    <!-------------------------------------------------------------------------------------------------------------------------------->
    <div id="birthday-edit-modal" class="content-modal">
    	<div class="overlay"></div>
    	<div id="birthday-edit-content" class="modal">
            <div class="modal-top-messege">
                <u><p>登録する生年月日を選択してください</p></u>
            </div>
            <div class="modal-main-wrap">
                <div class="modal-form-wrap">
                    <?php echo $this->Form->create('Birthday',['controller' => 'Members','url' => 'insertBirthday']); ?>
                    <input type="date" name="birthday" class="birth-input" value="<?php echo $loginParams['loginBirth'] ? h($loginParams['loginBirth']) : date("Y-m-d"); ?>" min="<?php echo date("Y-m-d",strtotime("-100 year")); ?>" max="<?php echo date("Y-m-d"); ?>">
                </div>
                <?php echo $this->Form->submit('生年月日を登録',[
                    'class' => "submit-btn"
                ]); ?>
                <?php echo $this->Form->end(); ?>

                <?php if ($loginParams['loginBirth']): ?>
                <div class="dele-btn-wrap">
                    <?php echo $this->Form->postLink('登録されている生年月日を削除する',
                        ['controller' => 'Members','action' => 'deleteBirthday'],
                        ['class' => 'dele-btn'],
                        '登録されている生年月日が削除されます。よろしいですか？'
                    );
                    ?>
                </div>
                <?php endif; ?>

                <div class="modal-top-messege">
                    <p class="modal-close">とじる</p>
                </div>
            </div>
        </div>
    </div>

    <!---------------------------------------------------------------------------------------------------------------------------------->
    <!---------------------------------------------------------------------------------------------------------------------------------->

    <!-- エンジニア歴編集 ---------------------------------------------------------------------------------------------------------------->
    <!----------------------------------------------------------------------------------------------------------------------------------->
    <div id="engineer-career-edit-modal" class="content-modal">
    	<div class="overlay"></div>
    	<div id="engineer-career-edit-content" class="modal">
            <div class="modal-top-messege">
                <u><p>登録するエンジニア歴を選択してください</p></u>
            </div>
            <div class="modal-main-wrap">
                <div class="modal-form-wrap">
                    <?php echo $this->Form->create('EngineerCareer',['controller' => 'Members','url' => 'insertEngineerCareer']); ?>
                    <?php echo $this->Form->input('career',[
                        'type' => 'select',
                        'options' => $careerLabel,
                        'div' => false,
                        'label' => false,
                        'empty' => false,
                        'class' => 'career-select',
                        'selected' => array_keys($careerLabel,$loginParams['logEngineerCareer']),
                        'default' => 1,
                    ]);
                    ?>
                </div>
                <?php echo $this->Form->submit('エンジニア歴を登録',[
                    'class' => "submit-btn"
                ]); ?>
                <?php echo $this->Form->end(); ?>

                <?php if ($loginParams['logEngineerCareer']): ?>
                <div class="dele-btn-wrap">
                    <?php echo $this->Form->postLink('登録されているエンジニア歴を削除する',
                        ['controller' => 'Members','action' => 'deleteEngineerCareer'],
                        ['class' => 'dele-btn'],
                        '登録されているエンジニア歴が削除されます。よろしいですか？'
                    );
                    ?>
                </div>
                <?php endif; ?>

                <div class="modal-top-messege">
                    <p class="modal-close">とじる</p>
                </div>
            </div>
        </div>
    </div>
    <!------------------------------------------------------------------------------------------------------------------------------------>
    <!------------------------------------------------------------------------------------------------------------------------------------>
    <?php echo $this->Html->script('pc/mypageModal.js'); ?>

    <div id="main-wrap">
      <!-- トップ部分のユーザー情報 -->
      <div id="top-info-wrap">
          <figure class="image">
              <?php if ($loginParams['loginImage']): ?>
                  <img src="images/user-default.png" alt="image" class="user-image">
              <?php else: ?>
                  <!-- <img src="<?php //echo WEBROOT_DIR . '/img/default/user_default.png';?>" alt="image" class="user-image"> -->
                  <?php echo $this->Html->image('/img/default/user_default.png',[
                      'alt' => 'image',
                      'class' => 'user-image'
                  ]);
                  ?>
              <?php endif; ?>
          </figure>
        <div id="user-info">
          <p class="name"><?php echo h($loginParams['nickName']); ?></p><br>
          <!-- url -->
          <?php if ($loginParams['logSnsUrls']): ?>
              <?php if ($loginParams['logSnsUrls']['github']): ?>
                  <a href="<?php echo h($loginParams['logSnsUrls']['github']); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-github fa-4x icons"></i></a>
              <?php endif; ?>
              <?php if ($loginParams['logSnsUrls']['twitter']): ?>
                  <a href="<?php echo h($loginParams['logSnsUrls']['twitter']); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter fa-4x icons"></i></a>
              <?php endif; ?>
              <?php if ($loginParams['logSnsUrls']['facebook']): ?>
                  <a href="<?php echo h($loginParams['logSnsUrls']['facebook']); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-square fa-4x icons"></i></a>
              <?php endif; ?>
              <?php if ($loginParams['logSnsUrls']['instagram']): ?>
                  <a href="<?php echo h($loginParams['logSnsUrls']['instagram']); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram fa-4x icons"></i></a>
              <?php endif; ?>
          <?php endif; ?>
          <!--  -->

          <!-- 保有スキル -->
          <?php if ($userSkills): ?>
              <p class="skill">
              <?php foreach ($userSkills as $skill): ?>
                  <?php echo h($skill); ?>
              <?php endforeach; ?>
              </p>
          <?php endif; ?>
          <!--  -->
          <!-- <span id="name-edit-tag">ユーザー名を編集</span> -->
          <span id="skill-edit-tag">スキルを編集</span>
          <span id="url-edit-tag">URLを編集</span>
        </div>
      </div>
      <!-- ここまでトップ部分のユーザー情報 -->

      <hr style="margin-top: 50px;">

      <!-- ユーザー情報詳細 -->
      <div id="detail-info-wrap">
        <div class="info-wrap">
          <div class="info-name">
            <p>紹介文</p><span><i id="intro-edit-tag" class="fas fa-pencil-alt fa-2x icons edit-icon"></i></span>
          </div>
          <div class="info-content">
            <p class="content"><?php echo $loginParams['loginIntro'] === false || $loginParams['loginIntro'] == '' ? '登録なし' : h($loginParams['loginIntro']); ?></p>
          </div>
        </div>

        <div class="info-wrap">
          <div class="info-name">
            <p>生年月日：<?php echo $loginParams['loginBirth'] ? h($loginParams['loginBirth']) : '登録なし'; ?></p><span><i id="birthday-edit-tag" class="fas fa-pencil-alt fa-2x icons edit-icon"></i></span>
          </div>
        </div>

        <div class="info-wrap">
          <div class="info-name">
            <p>エンジニア歴：<?php echo $loginParams['logEngineerCareer'] ? h($loginParams['logEngineerCareer']) : '登録なし'; ?></p><span><i id="engineer-career-edit-tag" class="fas fa-pencil-alt fa-2x icons edit-icon"></i></span>
          </div>
        </div>

        <!-- <div class="info-wrap">
          <div class="info-name">
            <p>ポートフォリオURL</p><span><i class="fas fa-pencil-alt fa-2x icons edit-icon"></i></span><span><i class="fas fa-plus fa-2x icons edit-icon"></i></span>
          </div>
          <div class="info-content">
            <a class="content">https://aaaaaaaaaaaaa.com</a>
          </div>
        </div> -->
      </div>
    </div>

	<?php echo $this->element('pc/footer'); ?>
  </body>
</html>

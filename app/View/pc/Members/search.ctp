<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <?php echo $this->Html->css('pc/simple_reset.css'); ?>
    <?php echo $this->Html->css('pc/Members/search.css'); ?>
    <?php echo $this->Html->css('pc/header.css'); ?>
    <?php echo $this->Html->css('pc/footer.css'); ?>
    <?php echo $this->Html->css('pc/session.css'); ?>
    <?php echo $this->Html->script('jquery-3.6.0.min.js'); ?>
    <?php echo $this->Html->script('session.js'); ?>
    <title>top</title>
</head>
<body>
    <?php echo $this->element('pc/default'); ?>

  <!-- メイン -->
  <div id="main-wrapper">
    <!-- 左側のメイン -->
    <div id="main-content-wrap">
      <!-- 検索フォーム -->
      <div id="search-wrapper">
        <div id="search-forms">
          <form class="name-search" action="" method="get">
            <input type="text" name="" value="" placeholder="ユーザー名で検索" class="search-form">
            <input type="submit" name="" value="検索" class="search-submit">
          </form>

          <form class="url-search" action="" method="get">
            <input type="url" name="" value="" placeholder="ポートフォリオURLで検索" class="search-form">
            <input type="submit" name="" value="検索" class="search-submit">
          </form>
        </div>
      </div>
      <!-- ここまで検索フォーム -->
      <!-- スキル検索リスト -->
      <div id="skill-search-wrap">
        <h3>スキルからユーザーを検索</h3>
        <div id="skills-wrap">
            <?php if ($skills): ?>
                <?php foreach ($skills as $key => $value): ?>
                    <a class="skill-list" href="#"><?php echo h($value); ?></a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: white;">スキルリストの取得に失敗しました</p>
            <?php endif; ?>
        </div>
      </div>
      <!-- ここまでスキル検索リスト -->
    </div>
    <!-- ここまで左側 -->
    <div id="side-menu-wrapper">
      <h3 class="side-menu-info">あなたと同じスキルを持っているユーザー</h3>
      <div id="same-skill-users-wrap">

        <hr style="margin-bottom: 5px;">
        <a href="#" class="same-skill-user">
          <div class="same-skill-user-info-wrap">
            <figure class="image">
              <img src="images/user-default.png" alt="image" class="user-image">
            </figure>
            <div class="user-info">
              <p class="name">miyake</p>
              <p>php,lalabel,cakephp</p>
              <p>comentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcoment</p>
              <p>アイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオ</p>
            </div>
          </div>
        </a>

        <hr style="margin-bottom: 5px;">
        <a href="#" class="same-skill-user">
          <div class="same-skill-user-info-wrap">
            <figure class="image">
              <img src="images/user-default.png" alt="image" class="user-image">
            </figure>
            <div class="user-info">
              <p class="name">miyake</p>
              <p>php,lalabel,cakephp</p>
              <p>comentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcoment</p>
              <p>アイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオ</p>
            </div>
          </div>
        </a>

        <a href="#" class="same-skill-user">
          <div class="same-skill-user-info-wrap">
            <figure class="image">
              <img src="images/user-default.png" alt="image" class="user-image">
            </figure>
            <div class="user-info">
              <p class="name">miyake</p>
              <p>php,lalabel,cakephp</p>
              <p>comentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcoment</p>
              <p>アイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオ</p>
            </div>
          </div>
        </a>

        <a href="#" class="same-skill-user">
          <div class="same-skill-user-info-wrap">
            <figure class="image">
              <img src="images/user-default.png" alt="image" class="user-image">
            </figure>
            <div class="user-info">
              <p class="name">miyake</p>
              <p>php,lalabel,cakephp</p>
              <p>comentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcomentcoment</p>
              <p>アイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオアイウエオ</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <?php echo $this->element('pc/footer'); ?>

  <?php echo $this->Html->script('pc/search_default.js'); ?>
</body>
</html>

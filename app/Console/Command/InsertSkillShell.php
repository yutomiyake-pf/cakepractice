<?php

/**
 * skillをインサートするバッチ
 */
class InsertSkillShell extends Shell {
  public $uses = ['Skill'];

  public function main() {

    $insertSkills = [
      "HTML","CSS","JavaScript","Ruby","Python","Perl","Scheme","C","Haskell",
      "D","Rust","C++","Go","Java","Makefile","Dart","Fortran","Clojure",
      "Groovy","PHP","Erlang","Elixir","Pascal","Scala","C#","Swift","Pnuts",
      "PowerShell","Lush","Kotlin","Objective-C","MySQL","AWS",
      "Sinatra","Ruby on Rails","Padrino","Vue.js","React","AngularJS","Express.js",
      "Node.js","jQuery","Spring Framework","Play Framework","JSF","Foundation",
      "Bootstrap","UIkit","CakePHP","FuelPHP","Laravel","Flask","Django"
    ];

    $data = [];
    foreach ($insertSkills as $skill) {
      $data[] = [
        "skill_name" => $skill,
        "created_at" => date("Y-m-d H:i:s")
      ];
    }

    if ($this->Skill->saveMany($data)) {
      echo "保存に成功しました。";
    } else {
      echo "保存に失敗しました。";
    }
  }
}

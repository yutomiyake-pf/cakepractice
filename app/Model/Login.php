<?php

App::uses('AppModel','Model');

class Login extends AppModel {

    public $useTable = "logins";
    public $primaryKey = 'login_id';
    public $actsAs = ['Containable'];

    public $hasMany = ['UserSkill','LoginIntroduction','Birthday','EngineerCareer','LoginImage','SnsUrl'];

    public $validate = [
      'last_name' => [
          [
              'rule' => 'notBlank',
              'required' => true,
              'message' => '苗字を入力してください'
          ],
          [
              'rule' => ['maxLength', 10],
              'message' => '苗字は10文字以内で入力してください'
          ]
      ],
      'first_name' => [
          [
              'rule' => 'notBlank',
              'required' => true,
              'message' => '名前を入力してください'
          ],
          [
              'rule' => ['maxLength', 10],
              'message' => '名前は10文字以内で入力してください'
          ]
      ],
      'real_name' => [
          [
              'rule' => 'notBlank',
              'required' => true,
              'message' => '本名を入力してください'
          ],
          [
              'rule' => ['maxLength', 20],
              'message' => '本名は20文字以内で入力してください'
          ]
      ],
      'user_name' => [
          [
              'rule' => 'notBlank',
              'required' => true,
              'message' => 'ユーザー名を入力してください'
          ],
          [
              'rule' => ['maxLength', 20],
              'message' => 'ユーザー名は20文字以内で入力してください'
          ]
      ],
      'email' => [
          [
              'rule' => 'notBlank',
              'required' => true,
              'message' => 'メールアドレスを入力してください'
          ],
          [
              'rule' => 'email',
              'message' => '正しいメールアドレスを入力してください'
          ],
          [
              'rule' => 'isUnique',
              'message' => 'このメールアドレスは使われています'
          ],
          [
              'rule' => ['minLength',4],
              'message' => '4文字以上で入力してください'
          ],
          [
              'rule' => ['maxLength',40],
              'message' => '40文字以内で入力してください'
          ]
      ],
      'password' => [
          [
              'rule' => 'notBlank',
              'required' => true,
              'message' => 'パスワードを入力してください'
          ],
          [
              'rule' => ['minLength',4],
              'message' => '4文字以上で入力してください'
          ],
          [
              'rule' => ['maxLength',35],
              'message' => '35文字以内で入力してください'
          ],
          [
              'rule' => '/^[a-z0-9]{4,}$/i',
              'message' => '半角英数字しか使えません'
          ],
      ]
    ];


    /**
    * 会員登録専用
    * @param  [param] $data
    * @return [boolean]
    */
    public function insertLogin($data) {
      if (!$data) return false;
      return $this->save($data,true);
    }

    /**
     * emailとpasswordからユーザー情報を取得
     * @param  [str] $email
     * @param  [str,hash] $password
     * @return [boolean]
     */
    public function getLogDataByMailAndPass($email,$password) {
        if (!$email || !$password) return false;

        $params = [
            'conditions' => [
                'email' => $email,
                'password' => $password,
                'delete_flag' => 0
            ],
            'fields' => ['login_id','real_name','user_name','email','signup_device']
        ];

        return $this->find('first',$params);
    }

    /**
     * /searchで使う同じスキルを持っているユーザーを取得
     * @param  [array] $loginIds
     * @param  [int] $limit
     * @return [array]
     */
    public function getSkillAndIntroByLoginIdsAndLimit($loginIds,$limit = null) {
        if (!$loginIds) return false;

        $params = [
            'contain' => [
                'UserSkill' => [
                    'Skill' => ['fields' => ['Skill.skill_name']],
                    'fields' => ['UserSkill.skill_id']
                ],
                'LoginIntroduction' => [
                    'fields' => ['LoginIntroduction.introduction']
                ]
            ],
            'conditions' => [
                'Login.login_id' => $loginIds
            ],
            'fields' => ['Login.user_name',],
            'limit' => $limit,
            'order' => 'Login.created_at desc'
        ];

        return $this->find('all',$params);
    }
}

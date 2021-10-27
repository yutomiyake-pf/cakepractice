<?php

App::uses('AppModel','Model');

class LoginIntroduction extends AppModel {

    public $useTable = "login_introductions";
    public $primaryKey = 'login_introduction_id';
    public $actsAs = ['Containable'];

    public $belongsTo = [
        'Login' => [
            'foreignKey' => 'login_id',
        ],
    ];

    public $validate = [
        'login_id' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'ユーザーidがありません'
            ],
            [
                'rule'    => 'naturalNumber',
                'required' => true,
                'message' => '数字で入力してください',
            ]
        ],
        'introduction' => [
            [
                'rule' => ['maxLength', 400],
                'message' => '自己紹介文は400文字以内で入力してください',
                'allowEmpty' => true
            ]
        ]
    ];

    /**
     * loginIdからレコードの存在確認
     * @param  [int] $loginId
     * @return [boolean]
     */
    public function chkExistByLoginId($loginId) {
        if (!$loginId) return false;

        $params = ['login_id' => $loginId];

        return $this->hasAny($params);
    }

    /**
     * loginIdから物理削除
     * @param  [int] $loginId
     * @return [boolean]
     */
    public function deletebyLoginId($loginId) {
        if (!$loginId) return false;

        $params = [
            'LoginIntroduction.login_id' => $loginId
        ];

        return $this->deleteAll($params);
    }

    /**
     * insert
     * @param  [array] $param
     * @return [array]
     */
    public function insert($param) {
        if (!$param) return false;

        return $this->save($param,true);
    }

    //mypage専用
    public function getByLoginIdForMyPage($loginId) {
        if (!$loginId) return false;

        $params = [
            'conditions' => [
                'LoginIntroduction.login_id' => $loginId,
            ],
            'fields' => ['introduction']
        ];

        return $this->find('first',$params);
    }

}

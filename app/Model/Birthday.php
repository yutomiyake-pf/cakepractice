<?php

App::uses('AppModel','Model');

class Birthday extends AppModel {

    public $useTable = "birthdays";
    public $primaryKey = 'birthday_id';
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
        'birthday' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => '生年月日の記入がありません'
            ],
            [
                'rule' => ['date','ymd'],
                'required' => true,
                'messege' => '生年月日の形式が正しくありません'
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
    public function deleteByLoginId($loginId) {
        if (!$loginId) return false;
        $params = [
            'Birthday.login_id' => $loginId
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

    /**
     * mypage用
     * @param  [int] $loginId
     * @return [array]
     */
    public function getByLoginIdForMyPage($loginId) {
        if (!$loginId) return false;

        $params = [
            'conditions' => [
                'Birthday.login_id' => $loginId,
            ],
            'fields' => ['birthday']
        ];

        return $this->find('first',$params);
    }
}

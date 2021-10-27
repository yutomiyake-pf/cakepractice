<?php

App::uses('AppModel','Model');

class SnsUrl extends AppModel {

    public $useTable = "sns_urls";
    public $primaryKey = 'sns_url_id';
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
        'github' => [
            [
                'rule' => ['url',true],
                'messege' => 'githubのURLが不正です',
                'allowEmpty' => true,
            ],
            [
                'rule' => ['maxLength', 200],
                'message' => 'githubのURLは200文字以内で入力してください'
            ]
        ],
        'twitter' => [
            [
                'rule' => ['url',true],
                'messege' => 'twitterのURLが不正です',
                'allowEmpty' => true
            ],
            [
                'rule' => ['maxLength', 200],
                'message' => 'twitterのURLは200文字以内で入力してください'
            ]
        ],
        'facebook' => [
            [
                'rule' => ['url',true],
                'messege' => 'facebookのURLが不正です',
                'allowEmpty' => true
            ],
            [
                'rule' => ['maxLength', 200],
                'message' => 'facebookのURLは200文字以内で入力してください'
            ]
        ],
        'instagram' => [
            [
                'rule' => ['url',true],
                'messege' => 'instagramのURLが不正です',
                'allowEmpty' => true
            ],
            [
                'rule' => ['maxLength', 200],
                'message' => 'instagramのURLは200文字以内で入力してください'
            ]
        ],
    ];

    //mypage専用
    public function getByLoginIdForMyPage($loginId) {
        if (!$loginId) return false;
        $params = [
            'conditions' => [
                'SnsUrl.login_id' => $loginId
            ],
            'fields' => ['twitter','instagram','facebook','github']
        ];
        return $this->find('first',$params);
    }

    /**
     * login_idからレコード存在確認
     * @param  [int] $loginId
     * @return [boolean]
     */
    public function chkExistByLoginId($loginId) {
        if (!$loginId) return false;

        $params = ['login_id' => $loginId];

        return $this->hasAny($params);
    }

    /**
     * login_idから物理削除
     * @param  [int] $loginId
     * @return [array]          [description]
     */
    public function deleteByLoginId($loginId) {
        if (!$loginId) return false;

        $params = [
            'SnsUrl.login_id' => $loginId
        ];

        return $this->deleteAll($params);
    }

    //insert
    public function insert($param) {
        if (!$param) return false;

        return $this->save($param,true);
    }
}

<?php

App::uses('AppModel','Model');

class EngineerCareer extends AppModel {

    public $useTable = "engineer_careers";
    public $primaryKey = 'engineer_career_id';
    public $actsAs = ['Containable'];

    public $belongsTo = [
        'Login' => [
            'foreignKey' => 'login_id',
        ],
    ];

    const CAREERLABEL = [
        1 => '1年未満',
        2 => '1年以上2年未満',
        3 => '2年以上3年未満',
        4 => '3年以上5年未満',
        5 => '5年以上7年未満',
        6 => '7年以上10年未満',
        7 => '10年以上'
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
                'message' => '数字では入力してください',
            ]
        ],
        'career' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'messege' => 'エンジニア歴を入力してください'
            ],
            [
                'rule' => 'chkLabel',
                'message' => '正しい値を入力してください'
            ]
        ]
    ];

    /**
     * labelの審査
     * @param  [int] $check
     * @return [boolean]
     */
    public function chkLabel($check) {
        if (is_array($check)) {
            if ($check = $check['career']) $return = false;
        }
        if (!$check) return false;
        $return = false;
        foreach (self::CAREERLABEL as $key => $value) {
            if ($key == $check) $return = true;
        }

        return $return;
    }

    //エンジニア歴の種類を取得
    public function getCareerLabel() {

        return self::CAREERLABEL;
    }

    /**
     * loginIdからレコード存在確認
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
            'EngineerCareer.login_id' => $loginId
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
                'EngineerCareer.login_id' => $loginId,
            ],
            'fields' => ['career']
        ];

        return $this->find('first',$params);
    }
}

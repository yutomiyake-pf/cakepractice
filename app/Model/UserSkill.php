<?php

App::uses('AppModel','Model');

class UserSkill extends AppModel {

    public $useTable = "user_skills";
    public $primaryKey = 'user_skill_id';
    public $actsAs = ['Containable'];

    public $belongsTo = [
        'Login' => [
            'foreignKey' => 'login_id',
        ],
        'Skill' => [
            'foreignKey' => 'skill_id'
        ]
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
        'skill_id' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'スキルidがありません'
            ],
            [
                'rule'    => 'naturalNumber',
                'required' => true,
                'message' => '数字で入力してください',
            ]
        ]
    ];



    /**
     * loginIdからユーザーの登録スキルを取得
     * @param  [int] $loginId
     * @return [array]
     */
    public function getByLoginIdForMyPage($loginId) {
        if (!$loginId) return false;
        $params = [
            'contain' => ['Skill'],
            'conditions' => [
                'UserSkill.login_id' => $loginId,
                'Skill.delete_flag' => 0
            ],
            'fields' => ['Skill.skill_name']
        ];
        return $this->find('all',$params);
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
     * @return [boolean]
     */
    public function deleteByLoginId($loginId) {
        if (!$loginId) return false;
        $params = [
            'UserSkill.login_id' => $loginId
        ];

        return $this->deleteAll($params);
    }

    /**
     * スキルインサート
     * @param  [array] $params
     * @return [array]
     */
    public function insert($params) {
        if (!$params || !is_array($params)) return false;

        return $this->saveAll($params);
    }

    /**
     * 登録スキルのskill_idを取得
     * @param  [int] $loginId
     * @return [array]
     */
    public function getIdsByLogId($loginId) {
        if (!$loginId) return false;

        $params = [
            'contain' => ['Skill'],
            'conditions' => [
                'UserSkill.login_id' => $loginId,
                'Skill.delete_flag' => 0
            ],
            'fields' => ['Skill.skill_id']
        ];
        return $this->find('all',$params);
    }


    /**
     * 指定したスキルIDを持っているユーザーのidを取得
     * @param  [array] $skillIds
     * @return [array]
     */
    public function getSameSkillUserIdBySkillIds($skillIds,$loginId = null) {
        if (!$skillIds) return false;

        $params = [
            'contain' => [
                'Skill','Login'
            ],
            'conditions' => [
                'UserSkill.skill_id' => $skillIds,
                'Skill.delete_flag' => 0,
                'Login.delete_flag' => 0,
                'Login.login_id !=' => $loginId,
            ],
            'fields' => ['Login.login_id']
        ];

        return $this->find('all',$params);
    }
}

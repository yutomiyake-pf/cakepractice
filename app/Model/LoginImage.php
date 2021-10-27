<?php

App::uses('AppModel','Model');

class LoginImage extends AppModel {

    public $useTable = "login_images";
    public $primaryKey = 'login_image_id';
    public $actsAs = ['Containable'];
    public $belongsTo = [
        'Login' => [
            'foreignKey' => 'login_id',
        ],
    ];









    /**
     * ユーザーアイコン取得
     * @param  [int] $loginId
     * @return [array]
     */
    public function getByLoginIdForMyPage($loginId) {
        if (!$loginId) return false;

        $params = [
            'conditions' => [
                'LoginImage.login_id' => $loginId,
            ],
            'fields' => ['image_name']
        ];
        return $this->find('first',$params);
    }
}

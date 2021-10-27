<?php

App::uses('AppModel','Model');

class Skill extends AppModel {

    public $useTable = "skills";
    public $primaryKey = 'skill_id';




    public function getAllList() {
        $params = [
            'conditions' => [
                'Skill.delete_flag' => 0
            ],
            'fields' => ['skill_id','skill_name']
        ];

        return $this->find('list',$params);
    }

    /**
     * skill_idの存在確認
     * @param  [array] $skillIds
     * @return [boolean]
     */
    public function chkExistBySkillIds($skillIds) {
        if (!$skillIds || !is_array($skillIds)) return false;

        foreach ($skillIds as $id) {
            if (!$this->exists($id)) return false;
        }
        return true;
    }
}

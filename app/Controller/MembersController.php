<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail','Network/Email');

class MembersController extends AppController {
    public $uses = ['Login','LoginImage','SnsUrl','UserSkill','Skill','LoginIntroduction','Birthday','EngineerCareer'];

    /**
     * マイページ画面　ユーザー情報の取得はprivate function 経由で取得
     */
    public function mypage () {
        $this->layout = false;
        if (!$userId = $this->Auth->user('id')) {
            $this->Session->setFlash('ユーザー認証に失敗しました');
            return $this->redirect(['action' => 'search']);
        }

        if (!$skills = $this->getAllSkillList()) {
            $this->Session->setFlash('情報の取得に失敗しました。');
            return $this->redirect(['action' => 'search']);
        }
        if (!$careerLabel = $this->EngineerCareer->getCareerLabel()) {
            $this->Session->setFlash('情報の取得に失敗しました。');
            return $this->redirect(['action' => 'search']);
        }
        $loginParams = [
            'nickName' => $this->Auth->user('user_name'),
            'loginImage' => $this->getLogImageByLogId($userId),
            'loginIntro' => $this->getLogIntroByLoginId($userId),
            'loginBirth' => $this->getBirthdayByLoginId($userId),
            'logEngineerCareer' => $this->getEngineerCareerByLogId($userId),
            'logSnsUrls' => $this->getSnsUrlsByLogId($userId),
        ];

        //登録スキル取得
        $userSkills = $this->getUserSkillsByLogId($userId);

        $this->set(compact('skills','careerLabel','loginParams','userSkills'));
        if ($this->chkDeviceType() == "pc") {
          $this->render('/pc/Members/mypage');
        }
    }

    /**
     * スキル登録メソッド
     */
    public function insertUserSkill() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です。');
            return $this->redirect('/mypage');
        }
        if (!$this->request->data['Skill']) {
            $this->Session->setFlash('スキルが選択されていません');
            return $this->redirect('/mypage');
        }

        $requestSkillIds = [];
        foreach ($this->request->data['Skill'] as $skillId) {
            $validateParam = ['skill_id' => $skillId];
            $this->UserSkill->set($validateParam);
            if ($this->UserSkill->validates(['fieldList' => ['skill_id']])) {
                $requestSkillIds[] = $skillId;
            } else {
                $this->Session->setFlash('不正なデータが送られてきたため登録できませんでした');
                return $this->redirect('/mypage');
            }
        }

        //スキルの存在確認
        if (!$this->Skill->chkExistBySkillIds($requestSkillIds)) {
            $this->Session->setFlash('不正なデータが送られてきたため登録できませんでした');
            return $this->redirect('/mypage');
        }

        if (count($requestSkillIds) > 5) {
            $this->Session->setFlash('スキルは最大5つまでのみ登録できます');
            return $this->redirect('/mypage');
        }

        //すでに登録されている場合、全て物理削除
        if ($this->UserSkill->chkExistByLoginId($this->Auth->user('id'))) {
            try {
                if (!$this->UserSkill->deleteByLoginId($this->Auth->user('id'))) {
                    throw new InternalErrorException('スキル情報の更新処理に失敗しました。お手数ですがもう一度実行してください');
                }
            } catch (Exception $e) {
                $this->Session->setFlash('スキル情報の更新処理に失敗しました。お手数ですがもう一度実行してください');
                return $this->redirect('/mypage');
            }
        }

        $insertParams = [];
        foreach ($requestSkillIds as $id) {
            $insertParams[] = [
                'login_id' => $this->Auth->user('id'),
                'skill_id' => $id,
                'created_at' => date("Y-m-d H:i:s")
            ];
        }

        try {
            if (!$this->UserSkill->insert($insertParams)) {
                throw new InternalErrorException('スキル情報の登録処理に失敗しました。お手数ですがもう一度実行してください');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('スキル情報の登録処理に失敗しました。お手数ですがもう一度実行してください');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('スキル情報を更新しました。');
        return $this->redirect('/mypage');
    }


    /**
     * url登録
     *各項目空文字容認
     */
    public function insertSnsUrl() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です。');
            return $this->redirect('/mypage');
        }
        if (!$this->request->data['SnsUrl']) {
            $this->Session->setFlash('データがありません');
            return $this->redirect('/mypage');
        }
        $params = [
            'github' => $this->request->data('SnsUrl.github'),
            'twitter' => $this->request->data('SnsUrl.twitter'),
            'facebook' => $this->request->data('SnsUrl.facebook'),
            'instagram' => $this->request->data('SnsUrl.instagram'),
        ];
        foreach ($params as $param) {
            if (is_null($param)) {
                $this->Session->setFlash('不正なデータが送られてきました');
                return $this->redirect('/mypage');
            }

            if ($param) {
                if (!filter_var($param,FILTER_VALIDATE_URL)) {
                    $this->Session->setFlash('URL形式が正しくないデータがあります');
                    return $this->redirect('/mypage');
                } else if (mb_strlen($param) > 200){
                    $this->Session->setFlash('URLは200文字以内で入力してください');
                    return $this->redirect('/mypage');
                }
            }
        }
        $params['login_id'] = $this->Auth->user('id');
        $params['created_at'] = date("Y-m-d H:i:s");

        // 登録されたものがある場合削除
        if ($this->SnsUrl->chkExistByLoginId($this->Auth->user('id'))) {
            try {
                if (!$this->SnsUrl->deleteByLoginId($this->Auth->user('id'))) {
                    throw new InternalErrorException('URLの更新処理に失敗しました');
                }
            } catch (Exception $e) {
                $this->Session->setFlash('URLの更新処理に失敗しました');
                return $this->redirect('/mypage');
            }
        }

        //insert
        try {
            if (!$this->SnsUrl->insert($params)) {
                throw new InternalErrorException('URLの登録処理に失敗しました');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('URLの登録処理に失敗しました');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('URLが登録されました');
        return $this->redirect('/mypage');
    }

    /**
     * 自己紹介文登録
     *空文字容認
     */
    public function insertIntroduction() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です。');
            return $this->redirect('/mypage');
        }
        if (!$this->request->data['LoginIntroduction']) {
            $this->Session->setFlash('データが見つかりませんでした。');
            return $this->redirect('/mypage');
        }

        $params = [
            'introduction' => $this->request->data('LoginIntroduction.introduction'),
        ];

        if (is_null($params['introduction'])) {
            $this->Session->setFlash('不正なデータが送られてきました。');
            return $this->redirect('/mypage');
        }

        // 空でない場合文字数チェック
        if (!empty($params['introduction'])) {
            if (mb_strlen($params['introduction']) > 400) {
                $this->Session->setFlash('自己紹介文は400文字以内で入力してください');
                return $this->redirect('/mypage');
            }
        }

        //登録ずみのものがある場合削除
        if ($this->LoginIntroduction->chkExistByLoginId($this->Auth->user('id'))) {
            try {
                if (!$this->LoginIntroduction->deleteByLoginId($this->Auth->user('id'))) {
                    throw new InternalErrorException('自己紹介文の更新処理に失敗しました。');
                }
            } catch (Exception $e) {
                $this->Session->setFlash('自己紹介文の更新処理に失敗しました。');
                return $this->redirect('/mypage');
            }
        }
        $params['login_id'] = $this->Auth->user('id');
        $params['created_at'] = date("Y-m-d H:i:s");

        try {
            if (!$this->LoginIntroduction->insert($params)) {
                throw new InternalErrorException('自己紹介文の登録処理に失敗しました。');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('自己紹介文の登録処理に失敗しました。');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('自己紹介文が登録されました');
        return $this->redirect('/mypage');
    }


    /**
     * 生年月日登録
     * 空文字容認しない
     * 削除リンクを用意
     */
    public function insertBirthday() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です。');
            return $this->redirect('/mypage');
        }
        if (!$this->request->data['birthday']) {
            $this->Session->setFlash('生年月日を入力してください');
            return $this->redirect('/mypage');
        }

        $params = [
            'birthday' => $this->request->data['birthday']
        ];

        //存在確認し削除
        if ($this->Birthday->chkExistByLoginId($this->Auth->user('id'))) {
            try {
                if (!$this->Birthday->deleteByLoginId($this->Auth->user('id'))) {
                    throw new InternalErrorException('生年月日の更新処理に失敗しました');
                }
            } catch(Exception $e) {
                $this->Session->setFlash('生年月日の更新処理に失敗しました');
                return $this->redirect('/mypage');
            }
        }

        $params['login_id'] = $this->Auth->user('id');
        $params['created_at'] = date("Y-m-d H:i:s");

        try {
            if (!$this->Birthday->insert($params)) {
                throw new InternalErrorException('生年月日の登録処理に失敗しました');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('生年月日の登録処理に失敗しました');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('生年月日が登録されました');
        return $this->redirect('/mypage');

    }

    /**
     * エンジニア歴登録
     * 削除リンクを用意
     */
    public function insertEngineerCareer() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です');
            return $this->redirect('/mypage');
        }
        if (!$this->request->data('EngineerCareer.career')) {
            $this->Session->setFlash('エンジニア歴を選択してください');
            return $this->redirect('/mypage');
        }

        $params = [
            'career' => $this->request->data('EngineerCareer.career')
        ];
        if (!$this->EngineerCareer->chkLabel($params['career'])) {
            $this->Session->setFlash('正しいエンジニア歴を入力してください');
            return $this->redirect('/mypage');
        }

        //登録がある場合削除
        if ($this->EngineerCareer->chkExistByLoginId($this->Auth->user('id'))) {
            try {
                if(!$this->EngineerCareer->deleteByLoginId($this->Auth->user('id'))) {
                    throw new InternalErrorException('エンジニア歴の更新処理に失敗しました');
                }
            } catch (Exception $e) {
                $this->Session->setFlash('エンジニア歴の更新処理に失敗しました');
                return $this->redirect('/mypage');
            }
        }

        $params['login_id'] = $this->Auth->user('id');
        $params['created_at'] = date("Y-m-d H:i:s");

        try {
            if (!$this->EngineerCareer->insert($params)) {
                throw new InternalErrorException('エンジニア歴の登録処理に失敗しました');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('エンジニア歴の登録処理に失敗しました');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('エンジニア歴が登録されました');
        return $this->redirect('/mypage');
    }


    /**
     * ユーザーアイコン取得
     * @param  [int] $loginId
     * @return [text]
     */
    private function getLogImageByLogId($loginId) {
        if (!$loginId) return false;
        $return = $this->LoginImage->getByLoginIdForMyPage($loginId);
        if (!$return) return false;
        return $return['LoginImage']['image_name'];
    }

    /**
     * 紹介文取得
     * @param  [int] $loginId
     * @return [text]
     */
    private function getLogIntroByLoginId($loginId) {
        if (!$loginId) return false;
        $return = $this->LoginIntroduction->getByLoginIdForMyPage($loginId);

        if (empty($return)) return false;

        return $return['LoginIntroduction']['introduction'];
    }

    /**
     * 誕生日取得
     * @param  [int] $loginId
     * @return [text]
     */
    private function getBirthdayByLoginId($loginId) {
        if (!$loginId) return false;
        $return = $this->Birthday->getByLoginIdForMyPage($loginId);

        if (!$return) return false;

        return $return['Birthday']['birthday'];
    }

    /**
     * エンジニア歴取得
     * @param  [int] $loginId
     * @return [text]
     */
    private function getEngineerCareerByLogId($loginId) {
        if (!$loginId) return false;
        $return = $this->EngineerCareer->getByLoginIdForMyPage($loginId);

        if (!$return) return false;

        $labels = $this->EngineerCareer->getCareerLabel();
        return $labels[$return['EngineerCareer']['career']];
    }

    /**
     * snsUrl取得
     * @param  [int] $loginId
     * @return [array]
     */
    private function getSnsUrlsByLogId($loginId) {
        if (!$loginId) return false;
        $return = $this->SnsUrl->getByLoginIdForMyPage($loginId);

        if (!$return) return false;
        return $return['SnsUrl'];
    }

    /**
     * ユーザーの登録スキルを取得
     * @param  [int] $loginId
     * @return [array]
     */
    private function getUserSkillsByLogId($loginId) {
        if (!$loginId) return false;
        $return = $this->UserSkill->getByLoginIdForMyPage($loginId);

        if (!$return) return false;

        $returnSkills = [];
        foreach ($return as $r) {
            $returnSkills[] = $r['Skill']['skill_name'] . '  ';
        }

        return $returnSkills;
    }

    //skill一覧取得
    private function getAllSkillList() {
        return $this->Skill->getAllList();
    }

    /**
     * 保有スキル全削除
     */
    public function deleteAllUserSkill() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です');
            return $this->redirect('/mypage');
        }
        if (!$loginId = $this->Auth->user('id')) {
            $this->Session->setFlash('ユーザー情報の取得に失敗しました');
            return $this->redirect('/mypage');
        }

        try {
            if (!$this->UserSkill->deleteByLoginId($loginId)) {
                throw new InternalErrorException('スキル情報の削除処理に失敗しました。');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('スキル情報の削除処理に失敗しました。お手数ですがもう一度実行してください');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('登録スキルを削除しました。');
        return $this->redirect('/mypage');
    }

    /**
     * 登録SnsUrlを全部削除
     */
    public function deleteAllSnsUrl() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です');
            return $this->redirect('/mypage');
        }
        if (!$loginId = $this->Auth->user('id')) {
            $this->Session->setFlash('ユーザー情報の取得に失敗しました');
            return $this->redirect('/mypage');
        }

        try {
            if (!$this->SnsUrl->deleteByLoginId($loginId)) {
                throw new InternalErrorException('URL情報の削除処理に失敗しました。');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('URL情報の削除処理に失敗しました。お手数ですがもう一度実行してください');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('登録URLを削除しました。');
        return $this->redirect('/mypage');
    }

    /**
     * 登録紹介文を全部削除
     */
    public function deleteIntro() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です');
            return $this->redirect('/mypage');
        }
        if (!$loginId = $this->Auth->user('id')) {
            $this->Session->setFlash('ユーザー情報の取得に失敗しました');
            return $this->redirect('/mypage');
        }

        try {
            if (!$this->LoginIntroduction->deleteByLoginId($loginId)) {
                throw new InternalErrorException('紹介文の削除処理に失敗しました。');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('紹介文の削除処理に失敗しました。お手数ですがもう一度実行してください');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('紹介文を削除しました。');
        return $this->redirect('/mypage');
    }

    /**
     * 生年月日の削除
     */
    public function deleteBirthday() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です');
            return $this->redirect('/mypage');
        }
        if (!$loginId = $this->Auth->user('id')) {
            $this->Session->setFlash('ユーザー情報の取得に失敗しました');
            return $this->redirect('/mypage');
        }

        try {
            if (!$this->Birthday->deleteByLoginId($loginId)) {
                throw new InternalErrorException('生年月日の削除処理に失敗しました。');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('生年月日の削除処理に失敗しました。お手数ですがもう一度実行してください');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('生年月日を削除しました。');
        return $this->redirect('/mypage');
    }

    /**
     * エンジニア歴削除
     */
    public function deleteEngineerCareer() {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です');
            return $this->redirect('/mypage');
        }
        if (!$loginId = $this->Auth->user('id')) {
            $this->Session->setFlash('ユーザー情報の取得に失敗しました');
            return $this->redirect('/mypage');
        }

        try {
            if (!$this->EngineerCareer->deleteByLoginId($loginId)) {
                throw new InternalErrorException('エンジニア歴の削除処理に失敗しました。');
            }
        } catch (Exception $e) {
            $this->Session->setFlash('エンジニア歴の削除処理に失敗しました。お手数ですがもう一度実行してください');
            return $this->redirect('/mypage');
        }

        $this->Session->setFlash('エンジニア歴を削除しました。');
        return $this->redirect('/mypage');
    }


    /**
     * ユーザー検索画面
     */
    public function search () {
        $this->layout = false;
        $loginId = $this->Auth->user('id');

        $skills = $this->getAllSkillList() ? $this->getAllSkillList() : false;

        //スキルが登録されている場合、ユーザーを検索
        if ($userSkillIds = $this->UserSkill->getIdsByLogId($loginId)) {
            $searchSkillIds = [];
            foreach ($userSkillIds as $id) {
                $searchSkillIds[] = $id['Skill']['skill_id'];
            }

            //同じスキルを持っているユーザーのidをまとめる
            if ($sameUserIds = $this->UserSkill->getSameSkillUserIdBySkillIds($searchSkillIds,$loginId)) {//自分は除外
                $searchUserIds = [];
                foreach ($sameUserIds as $userId) {
                    if (in_array($userId['Login']['login_id'],$searchUserIds)) continue;
                    $searchUserIds[] = $userId['Login']['login_id'];
                }

                //ユーザー情報取得limitは10
                if (!$sameUserData = $this->Login->getSkillAndIntroByLoginIdsAndLimit($searchUserIds,10)) $sameUserData = false;//万が一失敗したらfalse
                // debug($sameUserData);exit;
            } else {
                $sameUserData = false;//同じスキルを持ったユーザーが以内場合false
            }

        } else {
            $sameUserData = false;//登録スキルがない場合false
        }

        $this->set(compact('skills'));
        if ($this->chkDeviceType() == "pc") {
          $this->render('/pc/Members/search');
        }
    }
}

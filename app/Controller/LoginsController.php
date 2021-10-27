<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail','Network/Email');

class LoginsController extends AppController {

    public $uses = ['Login',];

    public function beforeFilter(){

        $this->Auth->allow('index','login');
    }

    /**
    * 会員登録画面
    */
    public function index() {
        $this->layout = false;
        if ($this->chkDeviceType() == "pc") {
          $this->render('/pc/Logins/index');
        }

        // 会員登録処理
        if ($this->request->is('post')) {
            $this->Login->set($this->request->data);

            if ($this->Login->validates(['fieldList' => ['last_name','first_name','user_name','email']])) {
                if (!$this->request->data('Login.agreeService')) {
                    $this->Session->setFlash('ご利用規約に同意されていません。');
                    return false;
                }

                $realName = $this->request->data('Login.last_name') . "　" . $this->request->data('Login.first_name');

                if(!$firstPass = $this->makeFirstPass()) {
                    $this->Session->setFlash('初期パスワードの設定に失敗しました。お手数ですがもう一度実行してください');
                    return false;
                }
                $hashPass = $this->makeHashPass($firstPass);

                $params = [
                    'real_name'  => $realName,
                    'user_name'  => $this->request->data('Login.user_name'),
                    'email'      => $this->request->data('Login.email'),
                    'password'   => $hashPass,
                    'signup_device' => $this->chkDeviceType(),
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                // insert処理
                try {
                    if (!$this->Login->insertLogin($params)) {
                        throw new InternalErrorException('ユーザー登録が正常に行われませんでした。');
                    }
                } catch (Exception $e) {
                    $this->Session->setFlash('ユーザー登録が正常に行われませんでした。お手数ですが運営までお問い合わせください');
                    return $this->redirect(['action' => 'index']);
                }

                //mail送信処理(後で必ず直す)
                $email = new CakeEmail('default');
                $email->to($params['email']);
                $email->subject('会員登録後、初期パスワード発行のお知らせ');
                $email->send("初期パスワード：".$firstPass);

                return $this->autoLogin($params['email'],$hashPass);//会員登録後ログイン処理
            } else {
                $error = reset($this->Login->validationErrors);
                $this->Session->setFlash($error[0]);
                return false;
            }
        }
    }

    /**
     * 初期パスワード生成(8桁)
     * @return [str]
     */
    private function makeFirstPass() {
      $pwd = str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz0123456789');
      $str = $str = substr(str_shuffle($pwd), 0, 8);
      if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).*$/',$str)) {
          return $str;
      } else {
          return $this->makeFirstPass();
      }
    }

    /**
     * パスワードのハッシュ化
     * md5に脆弱性あり！後で変更すること
     * @param  [str] $password
     * @return [str]
     */
    private function makeHashPass($password) {
        if (!$password) return false;
        return md5($password);
    }

    /**
     * 会員登録後以外で使用しないで！
     * 会員登録後自動login処理
     * @param  [str] $email
     * @param  [hash,str] $hashPassword
     */
    private function autoLogin($email, $hashPassword) {
        if (!$email || !$hashPassword) {
            $this->Session->setFlash('会員登録後自動ログインに失敗しました。お手数ですが運営までお問い合わせください');
            return $this->redirect(['action' => 'index']);
        }
        // 会員登録後自動ログイン
        if ($email && $hashPassword) {
            if (!$loginData = $this->Login->getLogDataByMailAndPass($email,$hashPassword)) {
                $this->Session->setFlash('会員登録後自動ログインに失敗しました。お手数ですが運営までお問い合わせください');
                return $this->redirect(['action' => 'index']);
            }

            //セッションに保存
            $this->Auth->login([
                'id' => $loginData['Login']['login_id'],
                'real_name' => $loginData['Login']['real_name'],
                'user_name' => $loginData['Login']['user_name'],
                'email' => $loginData['Login']['email'],
                'signup_device' => $loginData['Login']['signup_device'],
            ]);

            $this->Session->setFlash('会員登録が完了しました。');
            return $this->redirect('/mypage');
        }
    }


    /**
     * login
     */
    public function login () {
        if (!$this->request->is('post')) {
            $this->Session->setFlash('不正な通信です');
            return $this->redirect(['action' => 'index']);
        }
        $email = $this->request->data('Login.email');
        $password = $this->request->data('Login.password');

        if (!$email || !$password) {
            $this->Session->setFlash('正しいメールアドレスとパスワードを入力してください。');
            return $this->redirect(['action' => 'index']);
        }
        $hashPass = md5($password);
        if (!$loginData = $this->Login->getLogDataByMailAndPass($email,$hashPass)) {
            $this->Session->setFlash('ユーザー情報が見つかりませんでした。');
            return $this->redirect(['action' => 'index']);
        }

        //セッションに保存
        $this->Auth->login([
            'id' => $loginData['Login']['login_id'],
            'real_name' => $loginData['Login']['real_name'],
            'user_name' => $loginData['Login']['user_name'],
            'email' => $loginData['Login']['email'],
            'signup_device' => $loginData['Login']['signup_device'],
        ]);

        $this->Session->setFlash('ログインしました。');
        return $this->redirect('/mypage');
    }


}

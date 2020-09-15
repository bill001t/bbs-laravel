<?php

namespace App\Http\Controllers\u\controller;

use App\Core\ErrorBag;
use App\Core\Tool;
use App\Http\Controllers\Controller;
use App\Other\PwUserHelper;
use App\Services\Api\UserApi;
use App\Services\invite\bm\PwInviteFriendService;
use App\Services\user\bm\PwLoginService;
use App\Services\user\bm\PwRegisterService;
use App\Services\user\bm\PwTryPwdBp;
use App\Services\user\bm\PwUserService;
use App\Services\user\bo\PwUserBo;
use App\Services\user\bs\PwUser;
use App\Services\user\bs\PwUserLoginIpRecode;
use App\Services\user\dm\PwUserInfoDm;
use Core;
use Illuminate\Http\Request;
use Route;

/**
 * 登录
 *
 * @author xiaoxia.xu <xiaoxia.xuxx@aliyun-inc.com>
 * @copyright ©2003-2103 phpwind.com
 * @license http://www.phpwind.com
 * @version $Id: LoginController.php 24383 2013-01-29 10:09:39Z jieyin $
 * @package products.u.controller
 */
class LoginController extends Controller
{
    public function beforeAction($request)
    {
        $currentRoute = Route::currentRouteName();
        $action = trim(mb_strrichr($currentRoute, '.'), '.');

        /*if ($this->loginUser->isExists() && !in_array($action, array('showverify', 'logout', 'show'))) {
            $inviteCode = $request->get('invite');

            if (isset($inviteCode)) {
                $user = app(PwInviteFriendService::class)->invite($inviteCode, $this->loginUser->uid);
                if ($user instanceof ErrorBag) {
                    return $this->showError($user->getError());
                }
            }

            if ($action == 'fast') {
                return $this->showMessage('USER:login.success');
            } elseif ($action == 'welcome') {
                return redirect('u/login/show');
            } elseif ($request->ajax()) {
                return $this->showError('USER:login.exists');
            } else {
                return redirect($this->_filterUrl($request));
            }
        }*/

        return true;
    }


    /*
     * (non-PHPdoc) 页面登录页 @see WindController::run()
     */
    public function run(Request $request)
    {
        if ($this->beforeAction($request) !== true) {
            return $this->beforeAction($request);
        }

        return view('u.login')
            ->with('verify', $this->_showVerify($request))
            ->with('title', '用户登录')
            ->with('url', $this->_filterUrl($request, false))
            ->with('loginWay', PwUserHelper::getLoginMessage())
            ->with('invite', $request->get('invite'));

        /*Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $lang = Wind::getComponent('i18n');
        $seoBo->setCustomSeo($lang->getMessage('SEO:u.login.run.title'), '', '');
        Core::setV('seo', $seoBo);*/
    }

    /**
     * 快捷登录
     */
    public function fastAction(Request $request)
    {

        return view('u.login_fast')
            ->with('verify', $this->_showVerify($request))
            ->with('url', $this->_filterUrl($request))
            ->with('loginWay', PwUserHelper::getLoginMessage());
    }

    /**
     * 页面登录
     */
    public function dorunAction(Request $request)
    {
        $userForm = $this->_getLoginForm($request);

        /* [验证验证码是否正确] */
        if ($this->_showVerify($request)) {
            $veryfy = $this->_getVerifyService();
            if ($veryfy->checkVerify($userForm['code']) !== true) {
                return $this->showError('USER:verifycode.error');
            }
        }

        $question = $userForm['question'];
        if ($question == -4) {
            $question = $request->get('myquestion');
        }

        /* [验证用户名和密码是否正确] */
        $login = new PwLoginService();
        $this->runHook('c_login_dorun', $login);

        $isSuccess = $login->login($userForm['username'], $userForm['password'], $request->ip(), $question, $userForm['answer']);
        if ($isSuccess instanceof ErrorBag) {
            return $this->showError($isSuccess->getError());
        }

        $config = Core::C('site');
        if ($config['windid'] != 'local') {
            $localUser = $this->_getUserDs()->getUserByUid($isSuccess['uid'], PwUser::FETCH_MAIN);
            if ($localUser['username'] && $userForm['username'] != $localUser['username']) return $this->showError('USER:user.syn.error');
        }

        $registerService = new PwRegisterService();
        $info = $registerService->sysUser($isSuccess['uid']);

        if (!$info) return $this->showError('USER:user.syn.error');

        $identity = PwLoginService::createLoginIdentify($info);
        $identity = base64_encode($identity . '|' . $request->get('backurl') . '|' . $userForm['rememberme']);

        /* [是否需要设置安全问题] */
        /* @var $userService PwUserService */
        $userService = app(PwUserService::class);
        //解决浏览器记录用户帐号和密码问题
        if ($isSuccess['safecv'] && !$question) {
            $this->addMessage(true, 'qaE');
            return $this->showError('USER:verify.question.empty');
        }

        //该帐号必须设置安全问题
        if (empty($isSuccess['safecv']) && $userService->mustSettingSafeQuestion($isSuccess['uid'])) {
            $this->addMessage(array('url' => url('u/login/setquestion', array('v' => 1, '_statu' => $identity))), 'check');
        }

        return $this->showMessage('', url('u/login/welcome?_statu=' . $identity));
    }

    /**
     * 页头登录
     */
    public function dologinAction(Request $request)
    {

        //快捷登录功能关闭
        return;

        //
        $userForm = $this->_getLoginForm();

        $login = new PwLoginService();
        $result = app(PwUser::class)->getUserByName($userForm['username']);

        //如果开启了验证码 
        Wind::import(PwRegisterService::class);
        $registerService = new PwRegisterService();
        $info = $registerService->sysUser($result['uid']);
        $identity = PwLoginService::createLoginIdentify($info);
        $backUrl = $request->get('backurl');
        if (!$backUrl) $backUrl = $request->getServer('HTTP_REFERER');
        $identity = base64_encode($identity . '|' . $backUrl . '|' . $userForm['rememberme']);

        $url = '';
        if ($result['safecv']) {
            $url = url('u/login/showquestion', array('_statu' => $identity));
        } elseif (app('user.srv.PwUserService')->mustSettingSafeQuestion($info['uid'])) {
            $url = url('u/login/setquestion', array('_statu' => $identity));
        } elseif ($this->_showVerify()) {
            $url = url('u/login/showquestion', array('_statu' => $identity));
        }
        if ($url != '') {
            $url = url('u/login/run', array('_statu' => $identity));
            $this->addMessage(array('url' => ''), 'check');
            return $this->showMessage('USER:login.success', 'u/login/run/?_statu=' . $identity);
            return;
        }

        //----
        $userForm = $this->_getLoginForm();

        $login = new PwLoginService();
        $result = $login->login($userForm['username'], $userForm['password'], $request->getClientIp());
        if ($result instanceof ErrorBag) {
            return $this->showError($result->getError());
        } else {
            $config = Core::C('site');
            if ($config['windid'] != 'local') {
                $localUser = $this->_getUserDs()->getUserByUid($result['uid'], PwUser::FETCH_MAIN);
                if ($localUser['username'] && $userForm['username'] != $localUser['username']) return $this->showError('USER:user.syn.error');
            }

            $registerService = new PwRegisterService();
            $info = $registerService->sysUser($result['uid']);
            $identity = PwLoginService::createLoginIdentify($info);
            $backUrl = $request->get('backurl');
            if (!$backUrl) $backUrl = $request->getServer('HTTP_REFERER');
            $identity = base64_encode($identity . '|' . $backUrl . '|' . $userForm['rememberme']);

            if ($result['safecv']) {
                $url = url('u/login/showquestion', array('_statu' => $identity));
            } elseif (app('user.srv.PwUserService')->mustSettingSafeQuestion($info['uid'])) {
                $url = url('u/login/setquestion', array('_statu' => $identity));
            } elseif ($this->_showVerify()) {
                $url = url('u/login/showquestion', array('_statu' => $identity));
            }
            $this->addMessage(array('url' => $url), 'check');
            return $this->showMessage('USER:login.success', 'u/login/welcome?_statu=' . $identity);
        }
    }

    /**
     * 显示安全问题
     */
    public function showquestionAction(Request $request)
    {
        $statu = $this->checkUserInfo($request);
        $verify = $this->_showVerify($request);
        $v = $request->get('v');
        /* @var $userSrv PwUserService */
        $userSrv = app(PwUserService::class);
        $hasQuestion = $userSrv->isSetSafecv($this->loginUser->uid);
        if (!$hasQuestion && (1 == $v || !$verify)) {
            return redirect('u/login/welcome')
                ->with($statu, '_statu');
        }

        $view = view('login_question')
            ->with('hasQuestion', $hasQuestion)
            ->with('safeCheckList', $this->_getQuestions())
            ->with('_statu', $statu)
            ->with('v', $v)
            ->with('s', $request->get('s', 'get'));

        if (1 != $v) {
            $view->with('verify', $verify);
        }

        return $view;

    }

    /**
     * 检查安全问题是否正确---也头登录的弹窗，带有验证码
     */
    public function doshowquestionAction(Request $request)
    {
        $statu = $this->checkUserInfo($request);
        $code = $request->get('code', 'post');
        if ($this->_showVerify($request) && (1 != $request->get('v'))) {
            $veryfy = $this->_getVerifyService();
            if (false === $veryfy->checkVerify($code)) return $this->showError('USER:verifycode.error');
        }
        /* @var $userSrv PwUserService */
        $userSrv = app(PwUserService::class);
        $hasQuestion = $userSrv->isSetSafecv($this->loginUser->uid);
        if ($hasQuestion) {
            list($question, $answer) = $request->get(array('question', 'answer'), 'post');
            if ($question == -4) {
                $question = $request->get('myquestion', 'post');
            }
            $pwdBp = new PwTryPwdBp();
            $result = $pwdBp->checkQuestion($this->loginUser->uid, $question, $answer, $request->getClientIp());
            if ($result instanceof ErrorBag) {
                return $this->showError($result->getError());
            }
        }
        return $this->showMessage('USER:login.success', 'u/login/welcome?_statu=' . $statu);
    }

    /**
     * 验证密码
     */
    public function checkpwdAction(Request $request)
    {
        list($password, $username) = $request->only(['password', 'username']);
        $pwdBp = new PwTryPwdBp();
        $info = $pwdBp->author($username, $password, $request->getClientIp());
        if ($info instanceof ErrorBag) {
            return $this->showError($info->getError());
        }
        return $this->showMessage();
    }

    /**
     * 验证安全问题
     */
    public function checkquestionAction(Request $request)
    {
        $statu = $this->checkUserInfo();
        list($question, $answer) = $request->get(array('question', 'answer'), 'post');
        $pwdBp = new PwTryPwdBp();
        $result = $pwdBp->checkQuestion($this->loginUser->uid, $question, $answer, $request->getClientIp());
        if ($result instanceof ErrorBag) {
            return $this->showError($result->getError());
        }
        return $this->showMessage();
    }

    /**
     * 设置安全问题弹窗
     */
    public function setquestionAction(Request $request)
    {
        $statu = $this->checkUserInfo();
        $mustSetting = app(PwUserService::class)->mustSettingSafeQuestion($this->loginUser->uid);
        $verify = $this->_showVerify();
        $v = $request->get('v', 'get');
        if (!$mustSetting && (1 == $v || !$verify)) {
            return redirect('u/login/welcome')
                ->with('_statu', $statu);
        }


        $view = view('login_setquestion')
            ->with($v, 'v')
            ->with($this->_getQuestions(), 'safeCheckList')
            ->with($statu, '_statu');

        if (1 != $v) {
            $view->with($verify, 'verify');
        }

        return $view;
    }

    /**
     * 执行设置安全问题
     */
    public function dosettingAction(Request $request)
    {
        $statu = $this->checkUserInfo();
        $code = $request->get('code', 'post');
        if ($this->_showVerify() && (1 != $request->get('v', 'post'))) {
            $veryfy = $this->_getVerifyService();
            if (false === $veryfy->checkVerify($code)) {
                return $this->showError('USER:verifycode.error');
            }
        }
        list($question, $answer) = $request->get(array('question', 'answer'), 'post');
        if (!$question || !$answer) return $this->showError('USER:login.question.setting');
        if (intval($question) === -4) {
            $question = $request->get('myquestion', 'post');
            if (!$question) return $this->showError('USER:login.question.setting');
        }

        /* @var $userDs PwUser */
        $userDs = app(PwUser::class);
        $userDm = new PwUserInfoDm($this->loginUser->uid);
        $userDm->setQuestion($question, $answer);
        if (($result = $userDs->editUser($userDm, PwUser::FETCH_MAIN)) instanceof ErrorBag) {
            return $this->showError($result->getError());
        }
        return $this->showMessage('USER:login.question.setting.success', 'u/login/welcome?_statu=' . $statu);
    }

    /**
     * 登录成功
     */
    public function welcomeAction(Request $request)
    {
        $identify = $this->checkUserInfo($request);

        if($identify instanceof ErrorBag){
            return $identify;
        }

        if (isset($this->loginUser->info['status']) && Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNACTIVE)) {
            $identify = PwRegisterService::createRegistIdentify($this->loginUser->uid, $this->loginUser->info['password']);
            return redirect('u/register/sendActiveEmail')
                ->with('_statu', $identify)
                ->with('from', 'login');
        }

        list(, $refUrl, $rememberme) = explode('|', base64_decode($identify));
        $login = new PwLoginService();
        $login->setLoginCookie($this->loginUser, $request->getClientIp(), $rememberme);

        if (Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNCHECK)) {
            return redirect('u/login/show')
                ->with('backurl', $refUrl);
        }

        if (!$refUrl) $refUrl = '/';

        /*if ($synLogin = $this->_getWindid()->synLogin($this->loginUser->uid)) {
            return view('u.login_welcome')
                ->with('username', $this->loginUser->username)
                ->with('refUrl', $refUrl)
                ->with('synLogin', $synLogin);
        } else {*/
            return redirect($refUrl);
        /*}*/
    }

    /**
     * 提示信息
     */
    public function showAction(Request $request)
    {
        if (Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNCHECK)) {
            return $this->showError('USER:login.active.check');
        }
        return redirect($this->_filterUrl());
    }

    /**
     * 检查用户输入的用户名
     */
    public function checknameAction(Request $request)
    {
        $login = new PwLoginService();
        $info = $login->checkInput($request->get('username'));
        if (!$info) return $this->showError('USER:user.error.-14');
        if (!empty($info['safecv'])) {
            $registerService = new PwRegisterService();
            $status = PwLoginService::createLoginIdentify($registerService->sysUser($info['uid']));
            $identify = base64_encode($status . '|');
            $this->addMessage($this->_getQuestions(), 'safeCheck');
            $this->addMessage($identify, '_statu');
            return $this->showMessage();
        }
        return $this->showMessage();
    }

    /**
     * 退出
     *
     * @return void
     */
    public function logoutAction(Request $request)
    {

        /* @var $userService PwUserService */
        $uid = $this->loginUser->uid;
        $username = $this->loginUser->username;
        $userService = app(PwUserService::class);
        if (!$userService->logout()) return $this->showMessage('USER:loginout.fail');
        $url = $request->get('backurl');
        if (!$url) $url = $request->server('HTTP_REFERER');
        if (!$url) $url = 'u/login/run';

        /*if ($synLogout = $this->_getWindid()->synLogout($uid)) {
            ->with($username, 'username');
            ->with($url, 'refUrl');
            ->with($synLogout, 'synLogout');
        } else {*/
        return redirect($url)
            ->with('用户登出', 'title');
        /*}*/
    }

    /**
     * 检查用户信息合法性
     *
     * @return string
     */
    private function checkUserInfo(Request $request)
    {
        $identify = $request->get('_statu');
//        !$identify && $identify = $request->get('_statu');

        if (!$identify) return $this->showError('USER:illegal.request');
        list($identify, $url, $rememberme) = explode('|', base64_decode($identify) . '|');
        list($uid, $password) = PwLoginService::parseLoginIdentify(rawurldecode($identify));

// 		$info = $this->_getUserDs()->getUserByUid($uid, PwUser::FETCH_MAIN);
        $this->loginUser = new PwUserBo($uid);
        if (!$this->loginUser->isExists() || Tool::getPwdCode($this->loginUser->info['password']) != $password) {
            return $this->showError('USER:illegal.request');
        }
        return base64_encode($identify . '|' . $url . '|' . $rememberme);
    }

    /**
     * 获得安全问题列表
     *
     * @return array
     */
    private function _getQuestions()
    {
        $questions = PwUserHelper::getSafeQuestion();
        $questions[-4] = '自定义安全问题';
        return $questions;
    }

    /**
     * 判断是否需要展示验证码
     *
     * @return boolean
     */
    private function _showVerify(Request $request)
    {
        $config = Core::C('verify', 'showverify');
        !$config && $config = array();
        if (in_array('userlogin', $config) == true) {
            return true;
        }

        //ip限制,防止撞库; 错误三次,自动显示验证码
        $ipDs = app(PwUserLoginIpRecode::class);
        $info = $ipDs->getRecode($request->getClientIp());
        return is_array($info) && $info['error_count'] > 3 ? true : false;
    }

    private function _getWindid()
    {
        return app(UserApi::class);
    }

    /**
     * 获得用户DS
     *
     * @return PwUser
     */
    private function _getUserDs()
    {
        return app(PwUser::class);
    }

    /**
     * Enter description here ...
     *
     * @return PwCheckVerifyService
     */
    private function _getVerifyService()
    {
//        return app(PwCheckVerifyService::class);
    }

    /**
     * 过滤来源URL
     *
     * TODO
     *
     * @return string
     */
    private function _filterUrl(Request $request, $returnDefault = true)
    {
        $url = $request->get('backurl');
        if (!$url) $url = $request->server('HTTP_REFERER');
        if ($url) {
            // 排除来自注册页面/自身welcome/show的跳转
            if (self::$router->currentRouteName() == 'u/login' || self::$router->currentRouteName() == 'register') {
                $url = '';
            }
            /*$args = WindUrlHelper::urlToArgs($url);
            if ($args['m'] == 'u' && in_array($args['c'], array('register', 'login'))) {
                $url = '';
            }*/
        }
        if (!$url && $returnDefault) $url = '/';
        return $url;
    }

    /**
     * @return array
     */
    private function _getLoginForm(Request $request)
    {
        $data = array();
        list($data['username'], $data['password'], $data['question'], $data['answer'], $data['code'], $data['rememberme']) = array_map(function ($value) {
            return is_null($value) ? '' : $value;
        }, array_values($request->only(
            array('username', 'password', 'question', 'answer', 'code', 'rememberme'), 'post')));

        if (empty($data['username']) || empty($data['password'])) return $this->showError('USER:login.user.require', 'u/login/run');
        return $data;
    }
}

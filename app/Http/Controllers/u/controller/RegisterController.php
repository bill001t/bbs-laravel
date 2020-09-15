<?php

namespace App\Http\Controllers\u\controller;

use App\Core\CommonValidator;
use App\Core\ErrorBag;
use App\Core\MessageTool;
use App\Core\Tool;
use App\Http\Controllers\Controller;
use App\Http\Controllers\u\service\PwUserRegisterGuideService;
use App\Other\PwUserHelper;
use App\Services\Api\AreaApi;
use App\Services\Api\UserApi;
use App\Services\invite\bm\PwInviteCodeService;
use App\Services\mobile\bm\PwMobileService;
use App\Services\user\bm\PwLoginService;
use App\Services\user\bm\PwRegisterService;
use App\Services\user\bo\PwUserBo;
use App\Services\user\bs\PwUser;
use App\Services\user\bs\PwUserLoginIpRecode;
use App\Services\user\bs\PwUserMobile;
use App\Services\user\dm\PwUserInfoDm;
use App\Services\user\validator\PwUserValidator;
use Core;
use Illuminate\Http\Request;
use Route;

class RegisterController extends Controller
{

    public function beforeAction($viewname = '')
    {
        $currentRoute = Route::currentRouteName();
        $action = trim(mb_strrichr($currentRoute, '.'), '.');

        if (!empty($viewname)) {
            $args = ['title' => '用户注册'];
            view()->composer($viewname, function ($view) use ($args) {
                $view->with($args);
            });
        }

        $config = Core::C('register');
        if (0 == $config['type'] && ('close' != $action)) {
            return redirect(url('u/register/close'));
        }

        return true;

    }

    /* (non-PHPdoc)
     *  用户注册
     * 如果开启同一个IP地址在一定时间内不能再次注册
     * @see WindController::run()
     */
    public function run(Request $request)
    {
        $viewname = 'u.register';
        if ($this->beforeAction($viewname) !== true) {
            return $this->beforeAction($viewname);
        }

        $this->init($request, $viewname);

        $request->flashOnly('username', 'email');

        return view($viewname)
            ->with('invite', $request->get('invite'))
            ->with('backurl', 'bbs/index/run');

        /*Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $lang = Wind::getComponent('i18n');
        $seoBo->setCustomSeo($lang->getMessage('SEO:u.register.run.title'), '', '');
        Core::setV('seo', $seoBo);*/
    }

    /**
     * 邀请码链接
     */
    public function inviteAction(Request $request)
    {
        $viewname = 'u.register';

        $config = Core::C('register');
        if ($config['type'] != 2) return $this->showError('USER:invite.close');

        $this->init($request, $viewname);

        $inviteCode = $request->get('code', 'get');

        return view($viewname)
            ->with('invitecode', $inviteCode);
    }

    /**
     * 检查邀请码是否可以用
     */
    public function checkInvitecodeAction(Request $request)
    {
        $code = $request->get('invitecode');
        /* @var $inviteService PwInviteCodeService */
        $inviteService = app(PwInviteCodeService::class);
        if (($info = $inviteService->allowUseInviteCode($code)) instanceof ErrorBag) {
            return $this->showError($info->getError());
        }
        $info = $this->_getUserDs()->getUserByUid($info['created_userid']);
        return $this->showMessage(array('USER:invite.code.check.success', array('username' => $info['username'])));
    }

    /**
     * 执行用户注册
     */
    public function dorunAction(Request $request)
    {
        $viewname = '';
        if ($this->beforeAction($viewname) !== true) {
            return $this->beforeAction($viewname);
        }

        $registerService = new PwRegisterService();
        $registerService->setUserDm($this->_getUserDm($request));

        $this->runHook('c_register', $registerService);

        if (($info = $registerService->register()) instanceof ErrorBag) {
            return $this->showError($info->getError());
        } else {
            $identity = PwRegisterService::createRegistIdentify($info['uid'], $info['password']);
            if (1 == Core::C('register', 'active.mail')) {
                return redirect('u/register/sendActiveEmail')
                    ->with('_statu', $identity)
                    ->with('注册', 'title');
            } else {
                return redirect('u/register/welcome')
                    ->with('_statu', $identity)
                    ->with('注册', 'title');
            }
        }
    }

    /**
     * 发送激活邮箱
     */
    public function sendActiveEmailAction(Request $request)
    {
        $statu = $this->checkRegisterUser($request);
        if (!Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNACTIVE)) {
            return view('register_about')
                ->with('type', 'activeEmail');
        }
        $registerService = new PwRegisterService();
        $info = $this->loginUser->info;
        if (false == $registerService->checkIfActiveEmailSend($info['uid'], $info['email'])) {
            $registerService->sendEmailActive($info['username'], $info['email'], $statu, $info['uid']);
        }

        $mailList = array('gmail.com' => 'google.com');
        list(, $mail) = explode('@', $info['email'], 2);
        $gotoEmail = 'http://mail.' . (isset($mailList[$mail]) ? $mailList[$mail] : $mail);


        return view('register_emailactive')
            ->with('email', $info['email'])
            ->with('username', $info['username'])
            ->with('gotoEmail', $gotoEmail)
            ->with('_statu', $statu)
            ->with('from', $request->get('from'));
    }

    /**
     * 再次发送激活邮件
     */
    public function sendActiveEmailAgainAction(Request $request)
    {
        $_statu = $this->checkRegisterUser();
        if (!Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNACTIVE)) {
            return $this->showMessage('USER:active.email.dumplicate');
        }
        $registerService = new PwRegisterService();
        $registerService->sendEmailActive($this->loginUser->info['username'], $this->loginUser->info['email']);
        return $this->showMessage('USER:active.sendemail.success');
    }

    /**
     * 更改邮箱
     */
    public function editEmailAction(Request $request)
    {
        $_statu = $this->checkRegisterUser();
        if (!Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNACTIVE)) {
            return $this->showMessage('USER:active.email.dumplicate', 'u/login/run');
        }
        $email = $request->get('email', 'post');
        $result = PwUserValidator::isEmailValid($email, $this->loginUser->info['username']);
        if ($result instanceof ErrorBag) {
            return $this->showError($result->getError());
        } else {
            $userInfo = new PwUserInfoDm($this->loginUser->uid);
            $userInfo->setEmail($email);
            $this->_getUserDs()->editUser($userInfo, PwUser::FETCH_MAIN);
            $registerService = new PwRegisterService();
            $registerService->sendEmailActive($this->loginUser->info['username'], $email, $_statu, $this->loginUser->uid);
            return $this->showMessage('USER:active.editemail.success', 'u/register/sendActiveEmail?_statu=' . $_statu);
        }
    }

    /**
     * 激活邮箱链接
     */
    public function activeEmailAction(Request $request)
    {
        $_statu = $this->checkRegisterUser();
        if (!Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNACTIVE)) {
            return view('register_about')
                ->with('type', 'activeEmail');
        }
        $code = $request->get('code');
        $PwUserRegisterBp = new PwRegisterService();
        $result = $PwUserRegisterBp->activeEmail($this->loginUser->uid, $this->loginUser->info['email'], $code);
        if ($result instanceof ErrorBag) return $this->showError($result->getError());

        //激活成功登录
        $login = new PwLoginService();
        $login->setLoginCookie($this->loginUser, $request->getClientIp());
        /* @var $guideService PwUserRegisterGuideService */
        $guideService = app(PwUserRegisterGuideService::class);

        return view('register_about')
            ->with('goGuide', $guideService->hasGuide())
            ->with('type', 'activeEmailSuccess');
    }

    /**
     * 完成注册，显示欢迎信息
     */
    public function welcomeAction(Request $request)
    {
        if (!$request->get('_statu')) return redirect('u/register/run');
        $statu = $this->checkRegisterUser($request);
        if (Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNACTIVE)) {
            return redirect('u/register/sendActiveEmail')
                ->with($statu, '_statu');
        }
        $login = new PwLoginService();
        $login->setLoginCookie($this->loginUser, $request->getClientIp());

        return redirect('u/register/guide');
    }

    /**
     * 用户引导页面
     *
     */
    public function guideAction(Request $request)
    {
        if (!$this->loginUser->isExists()) return redirect('/');
        $key = $request->get('key');
        $guideService = app(PwUserRegisterGuideService::class);/*需要迁移过来*/
        $next = $guideService->getNextGuide($key);
        if (!$next) {
            if (Core::C('register', 'active.check')) {

                if (!Tool::getstatus($this->loginUser->info['status'], PwUser::STATUS_UNCHECK)) {
                    return redirect('/')
                        ->with('check', 1);
                }
            }
            $synLogin = $this->_getWindid()->synLogin($this->loginUser->uid);

            return view('register_about')
                ->with('username', $this->loginUser->info['username'])
                ->with('type', 'success')
                ->with('synLogin', $synLogin);
        } else {
            return redirect($next['guide']);
        }
    }

    /**
     * 检查邮箱唯一性
     */
    public function checkemailAction(Request $request)
    {
        list($email, $username) = array_values($request->only('email', 'username'));
        $result = PwUserValidator::isEmailValid($email, $username);
        if ($result instanceof ErrorBag) return $this->showError($result->getError());
        return $this->showMessage();
    }

    /**
     * 检查用户名的唯一性
     */
    public function checkusernameAction(Request $request)
    {
        $username = $request->get('username');
        $result = PwUserValidator::isUsernameValid($username);
        if ($result instanceof ErrorBag) return $this->showError($result->getError());
        return $this->showMessage();
    }

    /**
     * 检查密码复杂度是否符合
     */
    public function checkpwdAction(Request $request)
    {
        list($pwd, $username) = array_values($request->only('pwd', 'username'));
        $result = PwUserValidator::isPwdValid($pwd, $username);
        if ($result instanceof ErrorBag) return $this->showError($result->getError());

        $message = ['rank' => PwUserHelper::checkPwdStrong($pwd)];

        return $this->showMessage($message);
    }

    /**
     * 检查密码强度
     */
    public function checkpwdStrongAction(Request $request)
    {
        $pwd = $request->get('pwd');

        $message = ['rank' => PwUserHelper::checkPwdStrong($pwd)];

        return $this->showMessage($message);
    }

    /**
     * 发送手机验证码
     */
    public function sendmobileAction(Request $request)
    {
        $mobile = $request->get('mobile', 'post');
        if (($result = $this->_checkMobileRight($mobile)) instanceof ErrorBag) {
            return $this->showError($result->getError());
        }
        if (($result = app(PwMobileService::class)->sendMobileMessage($mobile)) instanceof ErrorBag) {
            return $this->showError($result->getError());
        }
        return $this->showMessage('success');
    }

    /**
     * 验证手机号码
     */
    public function checkmobileAction(Request $request)
    {
        $mobile = $request->get('mobile', 'post');
        if (($result = $this->_checkMobileRight($mobile)) instanceof ErrorBag) {
            return $this->showError($result->getError());
        }
        $result = app(PwMobileService::class)->checkTodayNum($mobile);
        if ($result instanceof ErrorBag) {
            return $this->showError($result->getError());
        }
        return $this->showMessage();
    }

    private function _checkMobileRight($mobile)
    {
        $config = Core::C('register');
        if (!$config['active.phone']) {
            return new ErrorBag('USER:mobile.reg.open.error');
        }
        if (!PwUserValidator::isMobileValid($mobile)) {
            return new ErrorBag('USER:error.mobile');
        }
        $mobileInfo = app(PwUserMobile::class)->getByMobile($mobile);
        if ($mobileInfo) return $this->showError('USER:mobile.mobile.exist');
        return true;
    }

    /**
     * 验证用户标识
     *
     * @return string
     */
    private function checkRegisterUser(Request $request)
    {
        $identify = $request->get('_statu', 'get');
        !$identify && $identify = $request->get('_statu', 'post');
        if (!$identify) return $this->showError('USER:illegal.request');
        list($uid, $password) = PwRegisterService::parserRegistIdentify($identify);
        $info = $this->_getUserDs()->getUserByUid($uid, PwUser::FETCH_MAIN);
        if (Tool::getPwdCode($info['password']) != $password) {
            return $this->showError('USER:illegal.request');
        }
        $this->loginUser = new PwUserBo($uid);
        return $identify;
    }

    /**
     * 初始化
     */
    private function init(Request $request, $viewname)
    {
        $registerService = new PwRegisterService();
        $result = $registerService->checkIp($request->ip());
        if ($result instanceof ErrorBag) {
            return $this->showMessage($result->getError());
        }
        $resource = app(MessageTool::class);
        list($_pwdMsg, $_pwdArgs) = PwUserValidator::buildPwdShowMsg();
        list($_nameMsg, $_nameArgs) = PwUserValidator::buildNameShowMsg();

        $args = [
            'pwdReg' => $resource->getMessage($_pwdMsg, $_pwdArgs),
            'nameReg' => $resource->getMessage($_nameMsg, $_nameArgs),
            'verify' => $this->_showVerify($request),
            'config' => $this->_getRegistConfig(),
            'needFields' => PwUserHelper::getRegFieldsMap(),
            'areaFields' => array('location', 'hometown'),
        ];

        view()->composer($viewname, function ($view) use ($args) {
            $view->with($args);
        });
    }


    /**
     * 判断是否需要展示验证码
     * @return boolean
     */
    private function _showVerify(Request $request)
    {
        $config = Core::C('verify', 'showverify');
        !$config && $config = array();

        if (in_array('register', $config) == true) {
            return true;
        } else {
            //ip限制,防止撞库; 错误三次,自动显示验证码
            $ipDs = app(PwUserLoginIpRecode::class);
            $info = $ipDs->getRecode($request->getClientIp());
            return is_array($info) && $info['error_count'] > 3 ? true : false;
        }
    }

    /**
     * 关闭
     */
    public function closeAction()
    {
        $config = Core::C('register');
        if (isset($config['type'])) {
            return redirect('u/register/run');
        }

        return view('u.register_close')
            ->with('close', $config['close.msg']);
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

    private function _getWindid()
    {
        return app(UserApi::class);
    }

    /**
     * 获取注册的信息
     *
     * @return PwUserInfoDm
     */
    private function _getUserDm(Request $request)
    {
        list($username, $password, $repassword, $email, $aliww, $qq, $msn, $mobile, $mobileCode, $hometown, $location, $question, $answer, $regreason, $code) = array_map(function ($value) {
            return is_null($value) ? '' : $value;
        }, array_values($request->only(array('username', 'password', 'repassword', 'email', 'aliww', 'qq', 'msn', 'mobile', 'mobileCode', 'hometown', 'location', 'question', 'answer', 'regreason', 'code'))));

        //	验证输入
        $config = $this->_getRegistConfig();
        if (!$username) return $this->showError('USER:user.error.-1', 'u/register/run');
        if (!$password) return $this->showError('USER:pwd.require', 'u/register/run');
        if (!$email) return $this->showError('USER:user.error.-6', 'u/register/run');
        if (!CommonValidator::isEmail($email)) return $this->showError('USER:user.error.-7', 'u/register/run');

        foreach ($config['active.field'] as $field) {
            if (!$request->get($field, 'post')) return $this->showError('USER:register.error.require.needField.' . $field, 'u/register/run');
        }
        if ($config['active.check'] && !$regreason) {
            return $this->showError('USER:register.error.require.regreason', 'u/register/run');
        }
        if ($config['active.phone']) {
            if (!PwUserValidator::isMobileValid($mobile)) return $this->showError('USER:error.mobile', 'u/register/run');
            if (($mobileCheck = app(PwMobileService::class)->checkVerify($mobile, $mobileCode)) instanceof ErrorBag) {
                return $this->showError($mobileCheck->getError());
            }
        }
        if ($repassword != $password) return $this->showError('USER:user.error.-20', 'u/register/run');
        if (in_array('register', (array)Core::C('verify', 'showverify'))) {
            $veryfy = app(PwCheckVerifyService::class);
            if (false === $veryfy->checkVerify($code)) return $this->showError('USER:verifycode.error', 'u/register/run');
        }

        $userDm = new PwUserInfoDm();
        $userDm->setUsername($username);
        $userDm->setPassword($password);
        $userDm->setEmail($email);
        $userDm->setRegdate(Tool::getTime());
        $userDm->setLastvisit(Tool::getTime());
        /*$userDm->setRegip($request->ip());*///TODO::将来要换回

        $userDm->setAliww($aliww);
        $userDm->setQq($qq);
        $userDm->setMsn($msn);
        $userDm->setMobile($mobile);
        $userDm->setMobileCode($mobileCode);
        /*$userDm->setQuestion($question, $answer);*/
        $userDm->setRegreason($regreason);

        $areaids = array($hometown, $location);
        if ($areaids) {
            $srv = app(AreaApi::class);
            $areas = $srv->fetchAreaInfo($areaids);
            $userDm->setHometown($hometown, isset($areas[$hometown]) ? $areas[$hometown] : '');
            $userDm->setLocation($location, isset($areas[$location]) ? $areas[$location] : '');
        }
        return $userDm;
    }

    /**
     * 注册的相关配置
     *
     * @return array
     */
    private function _getRegistConfig()
    {
        $config = Core::C('register');
        !$config['active.field'] && $config['active.field'] = array();
        return $config;
    }

}

<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jarrus90\User;

use Yii;
use yii\base\Module as BaseModule;

/**
 * This is the main module class for the Yii2-user.
 *
 * @property array $modelMap
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Module extends BaseModule {

    /** Email is changed right after user enter's new email address. */
    const STRATEGY_INSECURE = 0;

    /** Email is changed after user clicks confirmation link sent to his new email address. */
    const STRATEGY_DEFAULT = 1;

    /** Email is changed after user clicks both confirmation links sent to his old and new email addresses. */
    const STRATEGY_SECURE = 2;

    /** @var bool Whether to show flash messages. */
    public $enableFlashMessages = true;

    /** @var bool Whether to enable registration. */
    public $enableRegistration = true;

    /** @var bool Whether to remove password field from registration form. */
    public $enableGeneratingPassword = false;

    /** @var bool Whether user has to confirm his account. */
    public $enableConfirmation = true;

    /** @var bool Whether to allow logging in without confirmation. */
    public $enableUnconfirmedLogin = false;

    /** @var bool Whether to enable password recovery. */
    public $enablePasswordRecovery = true;

    /** @var int Email changing strategy. */
    public $emailChangeStrategy = self::STRATEGY_SECURE;

    /** @var int The time you want the user will be remembered without asking for credentials. */
    public $rememberFor = 1209600; // two weeks

    /** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin = 86400; // 24 hours

    /** @var int The time before a recovery token becomes invalid. */
    public $recoverWithin = 21600; // 6 hours

    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10;

    /** @var string The Administrator permission name. */
    public $adminPermission;

    /** @var array Mailer configuration */
    public $mailer = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'user';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '<id:\d+>' => 'profile/show',
        '<action:(login|logout)>' => 'security/<action>',
        '<action:(register|resend)>' => 'registration/<action>',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
        'forgot' => 'recovery/request',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'settings/<action:\w+>' => 'settings/<action>'
    ];
    public $filesUploadUrl = '@web/uploads/blog';
    public $filesUploadDir = '@webroot/uploads/blog';
    public $useCommonStorage = false;

    public function init() {
        parent::init();
        if (!$this->get('storage', false)) {
            if ($this->useCommonStorage && ($storage = Yii::$app->get('storage', false))) {
                $this->set('storage', $storage);
            } else {
                $this->set('storage', [
                    'class' => 'creocoder\flysystem\LocalFilesystem',
                    'path' => $this->filesUploadDir
                ]);
            }
        }
    }

    public function getAdminMenu() {
        return [
            'label' => Yii::t('user', 'Users'),
            'position' => 2,
            'icon' => '<i class="fa fa-users"></i>',
            'items' => [
                [
                    'label' => Yii::t('user', 'Users'),
                    'url' => '/user/admin/index'
                ],
                [
                    'label' => Yii::t('rbac', 'Roles'),
                    'url' => '/user/role/index'
                ],
                [
                    'label' => Yii::t('rbac', 'Permissions'),
                    'url' => '/user/permission/index'
                ],
            ]
        ];
    }

}

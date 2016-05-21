<?php

namespace jarrus90\User\Models\Clients;

use Yii;
use yii\authclient\clients\YandexOAuth as BaseYandex;

class Yandex extends BaseYandex implements ClientInterface {

    /** @inheritdoc */
    public function getEmail() {
        $emails = isset($this->getUserAttributes()['emails']) ? $this->getUserAttributes()['emails'] : null;

        if ($emails !== null && isset($emails[0])) {
            return $emails[0];
        } else {
            return null;
        }
    }

    /** @inheritdoc */
    public function getUsername() {
        return isset($this->getUserAttributes()['login']) ? $this->getUserAttributes()['login'] : null;
    }

    /** @inheritdoc */
    protected function defaultTitle() {
        return Yii::t('user', 'Yandex');
    }

}

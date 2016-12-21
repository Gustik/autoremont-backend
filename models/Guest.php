<?php
/**
 * Использовал наработки https://github.com/ethercreative/yii2-ip-ratelimiter
 */
namespace app\models;


use Yii;
use yii\filters\RateLimitInterface;

// TODO: Пока не включим кеширование
/**
 * Class Guest
 * @package app\models
 *
 * Модель гостя. Идентификация по ip.
 * Нужен для лимитирования запросов, например, для платежей.
 */
class Guest implements RateLimitInterface
{
    public $ip;
    public $rateLimit = 10;
    public $timePeriod = 60;

    /**
     * Guest constructor.
     * @param $ip
     * @param int $rateLimit
     * @param int $timePeriod
     */
    public function __construct($ip, $rateLimit, $timePeriod)
    {
        $this->ip = $ip;
        $this->rateLimit = $rateLimit;
        $this->timePeriod = $timePeriod;
    }


    /**
     * Returns the maximum number of allowed requests and the window size.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @return array an array of two elements. The first element is the maximum number of allowed requests,
     * and the second element is the size of the window in seconds.
     */
    public function getRateLimit($request, $action)
    {
        return [$this->rateLimit, $this->timePeriod];
    }

    /**
     * Loads the number of allowed requests and the corresponding timestamp from a persistent storage.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @return array an array of two elements. The first element is the number of allowed requests,
     * and the second element is the corresponding UNIX timestamp.
     */
    public function loadAllowance($request, $action)
    {
        $cache = Yii::$app->cache;
        return [
            $cache->get('user.ratelimit.ip.allowance.' . $this->ip),
            $cache->get('user.ratelimit.ip.allowance_updated_at.' . $this->ip),
        ];
    }

    /**
     * Saves the number of allowed requests and the corresponding timestamp to a persistent storage.
     * @param \yii\web\Request $request the current request
     * @param \yii\base\Action $action the action to be executed
     * @param integer $allowance the number of allowed requests remaining.
     * @param integer $timestamp the current timestamp.
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $cache = Yii::$app->cache;
        $cache->set('user.ratelimit.ip.allowance.' . $this->ip, $allowance);
        $cache->set('user.ratelimit.ip.allowance_updated_at.' . $this->ip, $timestamp);
    }
}
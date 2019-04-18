<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Vote\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rating
 * @property int $user_id
 * @property string $client_ip
 * @property int $value
 * @property int $source_id
 * @property string $source_type
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Rating extends Model
{
    const VOTE_LIKE = 1;
    const VOTE_DISLIKE = 0;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'vote_rating';

    /**
     * @var bool 禁用时间戳
     */
    public $timestamps = false;

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'client_ip', 'value', 'source_id', 'source_type'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $model->updateRating();
        });
    }

    /**
     * Get the source entity that the Transaction belongs to.
     */
    public function source()
    {
        return $this->morphTo();
    }

    /**
     * 更新评分
     */
    public function updateRating()
    {
        $likes = static::query()->where('source_id', $this->source_id)->where('source_type', $this->source_type)->where('value', self::VOTE_LIKE)->count();
        $dislikes = static::query()->where('source_id', $this->source_id)->where('source_type', $this->source_type)->where('value', self::VOTE_DISLIKE)->count();
        if ($likes + $dislikes !== 0) {
            // Рейтинг = Нижняя граница доверительного интервала Вильсона (Wilson) для параметра Бернулли
            // http://habrahabr.ru/company/darudar/blog/143188/
            $rating = (($likes + 1.9208) / ($likes + $dislikes) - 1.96 * sqrt(($likes * $dislikes)
                        / ($likes + $dislikes) + 0.9604) / ($likes + $dislikes)) / (1 + 3.8416 / ($likes + $dislikes));
        } else {
            $rating = 0;
        }
        $rating = round($rating * 10, 2);
        if (($aggregateModel = AggregateRating::query()->where('source_id', $this->source_id)->where('source_type', $this->source_type)->first()) == null        ) {
            $aggregateModel = new AggregateRating;
            $aggregateModel->source_id = $this->source_id;
            $aggregateModel->source_type = $this->source_type;
        }
        $aggregateModel->likes = $likes;
        $aggregateModel->dislikes = $dislikes;
        $aggregateModel->rating = $rating;
        $aggregateModel->save();
    }
}
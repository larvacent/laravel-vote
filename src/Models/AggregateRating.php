<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Vote\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AggregateRating
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class AggregateRating extends Model
{
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
        'source_id', 'source_type', 'likes', 'dislikes', 'rating'
    ];

    /**
     * Get the source entity that the Transaction belongs to.
     */
    public function source()
    {
        return $this->morphTo();
    }
}
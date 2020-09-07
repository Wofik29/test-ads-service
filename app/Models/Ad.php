<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Ad
 * @package App\Models
 * @var int $id
 * @var string $text
 * @var int $price
 * @var int $amount count of view
 * @var int $limit max view
 * @var string $banner
 */
class Ad extends Model
{
    protected $dateFormat = 'U';
    protected $fillable = ['text', 'price', 'amount', 'limit', 'banner', 'amount'];

    protected $hidden = ['updated_at', 'created_at'];
}
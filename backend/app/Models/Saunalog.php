<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Saunalog extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'saunalogs';

    // 代入可能な属性
    protected $fillable = ['name', 'area', 'rank', 'comment'];

    // 日付属性（created_atはデフォルトで含まれている）
    protected $dates = ['created_at'];

    // Userモデルとのリレーション
    public function user() 
    {
        return $this->belongsTo(User::class, 'userId');
    }

}

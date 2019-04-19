<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // 自定义表名

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // boot在模型完成初始化后进行加载
    public static function boot() {
        parent::boot();

        // 监听creating事件,在用户模型创建之前生成激活令牌
        static::creating(function($user) {
            $user->activation_token = str_random(30);
        });
    }

    // 一个用户拥有多条微博
    public function feeds() {
        return $this->hasMany(Feed::class);
    }

    // 获取首页微博动态
    public function feedsInHome() {
        return $this->feeds()->orderBy('created_at', 'desc');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebsiteUser extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'website_users';


    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'password',
        'secret_key',
        'last_activity_time',
        'updated_at',
        'created_at',
    ];

    public static function updateLastActivityTime(WebsiteUser $websiteUser)
    {
        $newTime = ("" . (time()) . "");
        WebsiteUser::where('id', '=', $websiteUser->id)->update([
            'last_activity_time' => $newTime
        ]);
        $websiteUser->last_activity_time = $newTime;
    }
}

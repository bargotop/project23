<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['full_name', 'group_id', 'author_id'];
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function delete()
    {
        $group = $this->group;
        parent::delete();

        // Проверяем, остались ли другие студенты в группе
        // Не удалять
        // if ($group && $group->students()->count() === 0) {
            // $group->delete();
        // }

        return true;
    }
}

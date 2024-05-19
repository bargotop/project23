<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'faculty_id', 'author_id'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function delete()
    {
        // удаляется cascade
        // foreach ($this->groups as $group) {
        //     $group->delete();
        // }

        // Проверяем, остались ли другие отделения в факультете
        $faculty = $this->faculty;
        parent::delete();
        if ($faculty && $faculty->departments()->count() === 0) {
            $faculty->delete();
        }

        return true;
    }
}

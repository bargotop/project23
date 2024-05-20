<?php

namespace App\Models;

use App\Interfaces\Deletable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'department_id', 'author_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'group_subject')->withTimestamps();
    }

    public function delete()
    {
        $department = $this->department;
        return parent::delete();

        // Проверяем, остались ли другие группы в отделении
        // Не удаляем направление, даже если не осталось групп
        // if ($department && $department->groups()->count() === 0) {
        //     $department->delete();
        // }
    }
}

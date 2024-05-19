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

    public function delete()
    {
        // удаляется cascade
        // foreach ($this->students as $student) {
        //     $student->delete();
        // }

        // Проверяем, остались ли другие группы в отделении
        $department = $this->department;
        parent::delete();
        // if ($department && $department->groups()->count() === 0) {
        //     $department->delete();
        // }

        return true;
    }
}

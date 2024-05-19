<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'author_id'];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function delete()
    {
        // удаляется cascade
        // foreach ($this->departments as $department) {
        //     $department->delete();
        // }

        // Затем удаляем сам факультет
        return parent::delete();
    }
}

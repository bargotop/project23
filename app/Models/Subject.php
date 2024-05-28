<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'author_id'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_subject', 'subject_id', 'group_id')->withTimestamps();
    }
}

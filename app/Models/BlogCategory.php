<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * ID Корня
     */

    const ROOT =1;

    protected $fillable
        =[
            'title',
            'slug',
            'parent_id',
            'description',
        ];

    /**
     * Получить родительскую категорию
     *
     * @return BlogCategory
     */

    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    /**
     * Аксессуар(Accessor)
     *
     * @return string
     */

    public function getParentTitleAttribute()
    {
        $title = $this->parentCategory->title ?? ($this->isRoot() ? 'Корень': '???' );

        return $title;
    }

    public function isRoot()
    {
        return $this->id === BlogCategory::ROOT;
    }

    /**
     * Аксессуар
     *
     * @param string $valueFromDB
     *
     * @return bool|mixed|null|string|string[]
     */

    public function getTitleAttribute(string $valueFromObject)
    {
        return mb_strtoupper($valueFromObject);
    }

    /**
     * Мутатор
     *
     * @param string $incomingValue
     */

    public function setTitleAttribute($incomingValue)
    {
        $this->attributes['title'] = mb_strtolower($incomingValue);
    }
}

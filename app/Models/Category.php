<?php

namespace App\Models;

use Str;
use URL;

class Category extends MyBaseModel
{
    /**
     * The validation rules.
     *
     * @return array $rules
     */
    public function rules()
    {
        return [
            'name'               => 'required',
        ];
    }

    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    protected $messages = [
        'title.required'                       => 'You must at least give a title for your event.',
    ];

    /**
     * The organizer associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organiser()
    {
        return $this->belongsTo(\App\Models\Organiser::class);
    }
}

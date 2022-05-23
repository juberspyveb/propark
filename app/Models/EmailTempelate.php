<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTempelate extends Model
{
    public $table = 'email_templates';
    protected $fillable = [
    'email_key',
    'email_title',
    'email_subject',
    'email_html',
    'tags'
  ];
}

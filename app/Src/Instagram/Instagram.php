<?php namespace App\Src\Instagram;

use Illuminate\Database\Eloquent\Model;

class Instagram extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'instagrams';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];


}

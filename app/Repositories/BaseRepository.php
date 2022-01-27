<?php
namespace App\Repositories;
use App\Traits\CommonTrait;
use App\Traits\StoreImageTrait;
use App\Traits\EmailPoolTrait;

class BaseRepository
{
	use CommonTrait, StoreImageTrait, EmailPoolTrait;
}

<?php namespace App\Events;

use App\Events\Event;
use Pochika\Entry\Entry;

use Illuminate\Queue\SerializesModels;

class AfterConvert extends Event {

	use SerializesModels;
	
	public $entry;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(Entry &$entry)
	{
		$this->entry = &$entry;
	}
	
}

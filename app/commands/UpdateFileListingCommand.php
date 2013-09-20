<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateFileListingCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'thedrop:updateFileListing';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates the file listing in the database.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->line('Updating database...');
		
		RepositoryFile::updateFileListing();
		Cache::forget('files');

		$this->line(' Done.');
	}

}

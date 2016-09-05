<?php

namespace App\Console\Commands;

use App\MangaLink;
use App\Services\Crawler\ICrawlerManga;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetEachMangaLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:manga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get manga in link for each';

    protected $crawlerManga;
    
    protected $mangaLink;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ICrawlerManga $crawlerManga, MangaLink $mangaLink)
    {
        parent::__construct();
        $this->crawlerManga = $crawlerManga;
        $this->mangaLink = $mangaLink;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $mangaLinks = $this->crawlerManga->getAllMangaUrl();
            
            DB::beginTransaction();
            
            $this->mangaLink->insertOnNotExits($mangaLinks);
            
            DB::commit();
            echo 'Crawl manga link successfully';
        } catch (\Exception $ex) {
            DB::rollBack();
            echo $ex;
        }
    }
}

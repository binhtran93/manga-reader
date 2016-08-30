<?php

namespace App\Console\Commands;

use App\MangaLink;
use App\Services\Crawler\ICrawlerManga;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CrawlChapter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:chapter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $crawlerManga;
    
    protected $mangaLink;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ICrawlerManga $crawlerManga, MangaLink $mangaLink)
    {
        $this->crawlerManga = $crawlerManga;
        $this->mangaLink = $mangaLink;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $mangaLink = $this->mangaLink->getAllMangaLink()->get();
            
            foreach ( $mangaLink as $manga ) {
                $url = $manga->domain . $manga->link;
                $mangaInfo = $this->crawlerManga->getMangaInfo($url);
            }
        } catch (Exception $ex) {

        }
        
    }
}

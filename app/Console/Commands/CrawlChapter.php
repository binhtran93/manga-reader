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
            
            if ( count($mangaLink) == 0 ) {
                echo 'No links are found';
                return;
            }
            
            foreach ( $mangaLink as $manga ) {

                $mangaInfo = $this->crawlerManga->getManga($manga->link);die();
            }
        } catch (Exception $ex) {
            echo $ex;
        }
        
    }
}

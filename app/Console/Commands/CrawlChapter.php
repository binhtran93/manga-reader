<?php

namespace App\Console\Commands;

use App\MangaLink;
use App\Services\Crawler\ICrawlerManga;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\StoreMangaService;

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
    
    protected $storeManga;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ICrawlerManga $crawlerManga, MangaLink $mangaLink, StoreMangaService $storeManga )
    {
        $this->crawlerManga = $crawlerManga;
        $this->mangaLink = $mangaLink;
        $this->storeManga = $storeManga;
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
            $mangaLink = $this->mangaLink->getNewMangaLink()->get();
            
            if ( count($mangaLink) == 0 ) {
                echo 'No links are found';
                return;
            }
            
            foreach ( $mangaLink as $index => $manga ) {
                if ($index >0) break;
                $mangaInfo = $this->crawlerManga->getManga($manga->link);
                $this->storeManga->storeMangaInformaiton($mangaInfo);
            }
            
            
//            file_put_contents(public_path() . DIRECTORY_SEPARATOR . 'test.txt', print_r($mangas, true));
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}

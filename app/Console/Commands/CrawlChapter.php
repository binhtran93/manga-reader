<?php

namespace App\Console\Commands;

use App\MangaLink;
use App\Services\Crawler\ICrawlerManga;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Manga;

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

    protected $manga;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ICrawlerManga $crawlerManga, MangaLink $mangaLink, Manga $manga)
    {
        $this->crawlerManga = $crawlerManga;
        $this->mangaLink = $mangaLink;
        $this->manga = $manga;
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
                $mangaInfo = $this->crawlerManga->getManga($manga->link);
                dd($mangaInfo);
                $this->manga->saveManga($mangaInfo);
            }
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}

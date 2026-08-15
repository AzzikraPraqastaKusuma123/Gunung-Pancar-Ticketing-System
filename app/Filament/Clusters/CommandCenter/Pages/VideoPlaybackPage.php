<?php

namespace App\Filament\Clusters\CommandCenter\Pages;


use Filament\Pages\Page;

class VideoPlaybackPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationLabel = 'Sistem Rekaman (DVR)';

    protected static ?string $title = 'Video Playback / DVR';

    protected string $view = 'filament.clusters.command-center.pages.video-playback-page';

    protected static string | \UnitEnum | null $navigationGroup = 'Command Center';

    protected static ?int $navigationSort = 4;
}

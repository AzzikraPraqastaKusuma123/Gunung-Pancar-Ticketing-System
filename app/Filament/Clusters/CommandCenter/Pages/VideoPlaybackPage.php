<?php

namespace App\Filament\Clusters\CommandCenter\Pages;

use App\Filament\Clusters\CommandCenter\CommandCenterCluster;
use Filament\Pages\Page;

class VideoPlaybackPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationLabel = 'Sistem Rekaman (DVR)';

    protected static ?string $title = 'Video Playback / DVR';

    protected string $view = 'filament.clusters.command-center.pages.video-playback-page';

    protected static ?string $cluster = \App\Filament\Clusters\CommandCenter\CommandCenterCluster::class;

    protected static ?int $navigationSort = 4;
}

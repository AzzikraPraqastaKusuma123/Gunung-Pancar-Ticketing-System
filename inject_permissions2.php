<?php
$dir = __DIR__ . '/app/Filament/Widgets/';
$files = scandir($dir);

$salesWidgets = [
    'WeeklySummaryWidget.php', 'SalesMetricsWidget.php', 'SalesAnalyticsWidget.php', 
    'RevenueMetricsWidget.php', 'RecentBookingsWidget.php', 'FollowUpRemindersWidget.php', 
    'BookingStats.php', 'BookingBarChartWidget.php'
];

$ticketWidgets = [
    'TicketAnalyticsWidget.php', 'ScannerWidget.php'
];

foreach ($files as $file) {
    if (!str_ends_with($file, '.php')) continue;
    $path = $dir . $file;
    $content = file_get_contents($path);
    
    if (strpos($content, 'function canView') !== false) {
        continue;
    }

    $role = null;
    if (in_array($file, $salesWidgets)) {
        $role = 'ticketing';
    } elseif (in_array($file, $ticketWidgets)) {
        $role = 'sales';
    }

    if ($role) {
        $inject = "\n    public static function canView(): bool\n    {\n        return !auth()->user()->hasRole('$role');\n    }\n";
        // Find the first { after class definition
        $pos = strpos($content, '{', strpos($content, 'class '));
        if ($pos !== false) {
            $content = substr_replace($content, '{' . $inject, $pos, 1);
            file_put_contents($path, $content);
            echo "Updated $file\n";
        }
    }
}

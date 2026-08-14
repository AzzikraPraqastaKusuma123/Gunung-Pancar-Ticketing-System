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
        $content = preg_replace('/(class\s+\w+\s+extends\s+\w+\s*\{)/', "$1$inject", $content, 1);
        file_put_contents($path, $content);
        echo "Updated $file\n";
    }
}

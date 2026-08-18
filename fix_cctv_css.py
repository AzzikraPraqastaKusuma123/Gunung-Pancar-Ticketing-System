import sys

file_path = r'c:\Users\azzik\Documents\Frreelance PT ITSJ 2026\Ticketing System\resources\views\filament\clusters\command-center\pages\live-cctv-monitoring.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add wrapper class
content = content.replace('<div class="cctv-page-header">', '<div class="camping-theme-wrapper">\n\n    <div class="cctv-page-header">')
content = content.replace('</x-filament-panels::page>', '    </div>\n</x-filament-panels::page>')

# 2. Add variables to style block
css_vars = '''    <style>
        .camping-theme-wrapper {
            --bg-dark: #f9fafb;
            --bg-card: #ffffff;
            --bg-subtle: #f3f4f6;
            --primary: #10b981;
            --secondary: #059669;
            --accent: #34d399;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --success: #22c55e;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --bg-translucent: rgba(255, 255, 255, 0.7);
            color: var(--text-main);
        }
        .dark .camping-theme-wrapper {
            --bg-dark: #000000;
            --bg-card: #0a0a0a;
            --bg-subtle: #18181b;
            --primary: #10b981;
            --secondary: #059669;
            --accent: #34d399;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --border-color: #1f2937;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --success: #22c55e;
            --shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            --bg-translucent: rgba(10, 31, 18, 0.7);
        }
'''
content = content.replace('    <style>', css_vars)

# 3. Replace hardcoded colors
replacements = {
    'color: #fff;': 'color: var(--text-main);',
    'color: #9ca3af;': 'color: var(--text-muted);',
    'color: #22c55e;': 'color: var(--success);',
    'color: #4ade80;': 'color: var(--success);',
    'background: rgba(10, 31, 18, 0.7);': 'background: var(--bg-translucent);',
    'background: rgba(10, 31, 18, 0.6);': 'background: var(--bg-translucent);',
    'border: 1px solid rgba(34, 197, 94, 0.2);': 'border: 1px solid var(--border-color);',
    'border: 1px solid rgba(34, 197, 94, 0.15);': 'border: 1px solid var(--border-color);',
    'border: 2px dashed rgba(255, 255, 255, 0.1);': 'border: 2px dashed var(--border-color);',
    'background: rgba(17, 24, 39, 0.8);': 'background: var(--bg-card);',
    'background: rgba(31, 41, 55, 1);': 'background: var(--bg-subtle);',
    'background: rgba(5, 15, 8, 0.8);': 'background: var(--bg-subtle);',
    'box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.4);': 'box-shadow: var(--shadow);',
}

# Apply replacements specifically to CSS
css_start = content.find('<style>')
css_part = content[css_start:]
html_part = content[:css_start]

for k, v in replacements.items():
    css_part = css_part.replace(k, v)

# Fix specific issues
css_part = css_part.replace('text-shadow: 0 0 15px rgba(34,197,94,0.3);', '/* text-shadow handled in dark mode */')
css_part = css_part.replace('.cctv-page-header h2 {', '.cctv-page-header h2 {\\n            text-shadow: none;')
css_part = css_part.replace('.cctv-page-header h2 {\\n            text-shadow: none;', '.dark .cctv-page-header h2 { text-shadow: 0 0 15px rgba(34,197,94,0.3); }\\n        .cctv-page-header h2 {')

# The overlay text and meta icon should stay white/light even in light mode because the video background is dark
css_part = css_part.replace('.cctv-video-overlay h3 {\\n            color: var(--text-main);', '.cctv-video-overlay h3 {\\n            color: #fff;')
css_part = css_part.replace('.cctv-video-meta p {\\n            color: var(--text-muted);', '.cctv-video-meta p {\\n            color: rgba(255, 255, 255, 0.8);')
css_part = css_part.replace('.cctv-video-meta span {\\n            color: var(--text-muted);', '.cctv-video-meta span {\\n            color: rgba(255, 255, 255, 0.6);')
css_part = css_part.replace('.cctv-badge-cam {\\n            position: absolute;\\n            top: 12px;\\n            left: 12px;\\n            background: rgba(0, 0, 0, 0.6);\\n            backdrop-filter: blur(4px);\\n            padding: 4px 8px;\\n            border-radius: 6px;\\n            font-size: 10px;\\n            font-weight: 700;\\n            color: var(--text-main);', '.cctv-badge-cam {\\n            position: absolute;\\n            top: 12px;\\n            left: 12px;\\n            background: rgba(0, 0, 0, 0.6);\\n            backdrop-filter: blur(4px);\\n            padding: 4px 8px;\\n            border-radius: 6px;\\n            font-size: 10px;\\n            font-weight: 700;\\n            color: #fff;')

content = html_part + css_part

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("File updated successfully.")

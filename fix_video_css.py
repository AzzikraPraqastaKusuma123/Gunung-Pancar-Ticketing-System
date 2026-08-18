import sys

file_path = r'c:\Users\azzik\Documents\Frreelance PT ITSJ 2026\Ticketing System\resources\views\filament\clusters\command-center\pages\video-playback-page.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add wrapper class
if '<div class="camping-theme-wrapper">' not in content:
    content = content.replace('<div class="dvr-header">', '<div class="camping-theme-wrapper">\n\n    <div class="dvr-header">')
    content = content.replace('</x-filament-panels::page>', '    </div>\n</x-filament-panels::page>')

# 2. Add variables to style block
css_vars = '''    <style>
        .camping-theme-wrapper {
            --bg-dark: transparent;
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
            --bg-dark: transparent;
            --bg-card: rgba(255, 255, 255, 0.03);
            --bg-subtle: rgba(255, 255, 255, 0.08);
            --primary: #10b981;
            --secondary: #059669;
            --accent: #34d399;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --border-color: rgba(255, 255, 255, 0.1);
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --success: #22c55e;
            --shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            --bg-translucent: rgba(0, 0, 0, 0.3);
        }
'''
if '.camping-theme-wrapper {' not in content:
    content = content.replace('    <style>', css_vars)

# 3. Replace hardcoded colors
replacements = {
    'color:#f9fafb;': 'color:var(--text-main);',
    'color:#f8fafc;': 'color:var(--text-main);',
    'color:#f3f4f6;': 'color:var(--text-main);',
    'color:#fff;': 'color:var(--text-main);',
    'color:#94a3b8;': 'color:var(--text-muted);',
    'color:#9ca3af;': 'color:var(--text-muted);',
    'color:#6b7280;': 'color:var(--text-muted);',
    'color:#d1d5db;': 'color:var(--text-muted);',
    'color:#10b981;': 'color:var(--success);',
    'color:#34d399;': 'color:var(--success);',
    'color:#ef4444;': 'color:var(--danger);',
    'color:#fca5a5;': 'color:var(--danger);',
    'background:rgba(10,15,25,.75);': 'background:var(--bg-card);',
    'background:rgba(31,41,55,.6);': 'background:var(--bg-subtle);',
    'background:rgba(31,41,55,.8);': 'background:var(--bg-subtle);',
    'background:rgba(17,24,39,.6);': 'background:var(--bg-subtle);',
    'background:rgba(31,41,55,.9);': 'background:var(--bg-subtle);',
    'border:1px solid rgba(255,255,255,.08);': 'border:1px solid var(--border-color);',
    'border:1px solid rgba(255,255,255,.1);': 'border:1px solid var(--border-color);',
    'border:1px solid rgba(255,255,255,.06);': 'border:1px solid var(--border-color);',
    'border-color:rgba(255,255,255,.15);': 'border-color:var(--border-color);',
}

# Apply replacements specifically to CSS
css_start = content.find('<style>')
css_part = content[css_start:]
html_part = content[:css_start]

for k, v in replacements.items():
    css_part = css_part.replace(k, v)

# Fix video OSD text that must stay white
css_part = css_part.replace('.dvr-play-btn { position:relative; width:80px; height:80px; border-radius:50%; background:rgba(16,185,129,.9); border:3px solid rgba(255,255,255,.3); color:var(--text-main);', '.dvr-play-btn { position:relative; width:80px; height:80px; border-radius:50%; background:rgba(16,185,129,.9); border:3px solid rgba(255,255,255,.3); color:#fff;')

content = html_part + css_part

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("File updated successfully.")

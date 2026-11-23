@extends('layouts.admin')

@section('page_title', 'System Maintenance & Cache Controls')

@section('content')
<div class="space-y-6">

    <!-- Header info banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white border border-slate-800 shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-indigo-500/30 text-indigo-300 border border-indigo-400/20">
                    System Administration
                </span>
                <h1 class="text-xl sm:text-2xl font-black mt-2 tracking-tight">Cache Optimizer & Log File Manager</h1>
                <p class="text-xs text-slate-300 mt-1 max-w-xl">
                    Purge views, configurations, routes, and data caches with smooth async updates. View real-time application logs and rename log files in-place without page reloads.
                </p>
            </div>
            <button type="button" id="btn-purge-all" onclick="clearSpecificCache('all', this)" class="shrink-0 px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-xs shadow-lg shadow-emerald-500/25 active:scale-95 transition flex items-center space-x-2">
                <i class="fa-solid fa-broom text-sm"></i>
                <span class="btn-text">Purge All System Caches</span>
            </button>
        </div>
    </div>

    <!-- Live Notification Banner (Refined Success & Error Design) -->
    <div id="liveStatusAlert" class="hidden rounded-2xl border p-4 shadow-lg backdrop-blur-md transition-all duration-300 animate-in fade-in slide-in-from-top-3 flex items-start justify-between">
        <div class="flex items-start space-x-3.5">
            <div id="liveStatusIconWrap" class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-xs">
                <i id="liveStatusIcon" class="text-sm"></i>
            </div>
            <div class="space-y-0.5 pt-0.5">
                <h4 id="liveStatusTitle" class="text-xs font-black tracking-wide uppercase"></h4>
                <p id="liveStatusMessage" class="text-xs font-medium leading-relaxed"></p>
            </div>
        </div>
        <button type="button" onclick="$('#liveStatusAlert').addClass('hidden')" class="w-7 h-7 rounded-lg hover:bg-black/5 flex items-center justify-center transition text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    </div>

    <!-- SECTION 1: Cache Cleaners Grid -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-bolt text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Application Cache Controls</h3>
                    <p class="text-[11px] text-slate-400">Clear specific cache layers asynchronously without reloading the page</p>
                </div>
            </div>
            <span id="cacheLastClearedBadge" class="hidden px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="fa-solid fa-circle-check mr-1 text-emerald-600"></i>
                <span id="cacheLastClearedText"></span>
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-1">
            <!-- View Cache -->
            <div id="card-cache-view" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-indigo-300 transition shadow-xs flex flex-col justify-between">
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i class="fa-regular fa-file-code text-sm"></i>
                        </div>
                        <span class="status-indicator text-[10px] font-bold text-slate-400">Ready</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-900">Compiled Views</h4>
                    <p class="text-[11px] text-slate-500">Deletes compiled Blade templates in <code>storage/framework/views</code>.</p>
                </div>
                <button type="button" onclick="clearSpecificCache('view', this)" class="mt-4 w-full py-2 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-[11px] shadow-sm transition flex items-center justify-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    <span class="btn-text">Clear Views</span>
                </button>
            </div>

            <!-- Route Cache -->
            <div id="card-cache-route" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-indigo-300 transition shadow-xs flex flex-col justify-between">
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                            <i class="fa-solid fa-route text-sm"></i>
                        </div>
                        <span class="status-indicator text-[10px] font-bold text-slate-400">Ready</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-900">Route Cache</h4>
                    <p class="text-[11px] text-slate-500">Purges cached routing tables so route edits take immediate effect.</p>
                </div>
                <button type="button" onclick="clearSpecificCache('route', this)" class="mt-4 w-full py-2 px-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-[11px] shadow-sm transition flex items-center justify-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    <span class="btn-text">Clear Routes</span>
                </button>
            </div>

            <!-- Config Cache -->
            <div id="card-cache-config" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-indigo-300 transition shadow-xs flex flex-col justify-between">
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-gears text-sm"></i>
                        </div>
                        <span class="status-indicator text-[10px] font-bold text-slate-400">Ready</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-900">Configuration Cache</h4>
                    <p class="text-[11px] text-slate-500">Flushes cached <code>.env</code> and config files so environment updates apply.</p>
                </div>
                <button type="button" onclick="clearSpecificCache('config', this)" class="mt-4 w-full py-2 px-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] shadow-sm transition flex items-center justify-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    <span class="btn-text">Clear Config</span>
                </button>
            </div>

            <!-- Application Data Cache -->
            <div id="card-cache-cache" class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-indigo-300 transition shadow-xs flex flex-col justify-between">
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-database text-sm"></i>
                        </div>
                        <span class="status-indicator text-[10px] font-bold text-slate-400">Ready</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-900">Data / Application Cache</h4>
                    <p class="text-[11px] text-slate-500">Flushes database/array session and memory cached queries.</p>
                </div>
                <button type="button" onclick="clearSpecificCache('cache', this)" class="mt-4 w-full py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition flex items-center justify-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    <span class="btn-text">Clear Cache</span>
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION 2: Automated Database Backups & Export -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-3">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                    <i class="fa-solid fa-database text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Automated Database Backups & SQL Export</h3>
                    <p class="text-[11px] text-slate-400">Scheduled daily backups via Laravel Scheduler and instant on-demand export test endpoints</p>
                </div>
            </div>

            <!-- Header Quick Badges -->
            <div class="flex items-center space-x-2 flex-wrap">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Daily at 02:00 AM (Automated)</span>
                </span>
                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                    DB: <span class="font-mono font-black text-indigo-600">{{ config('database.connections.' . config('database.default') . '.database') }}</span>
                </span>
            </div>
        </div>

        <!-- Testing Endpoint Banner -->
        <div class="p-4 rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-md">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-indigo-500/30 text-indigo-300 border border-indigo-400/20">
                        Testing URL
                    </span>
                    <span class="text-xs font-bold text-slate-300">Direct Browser Export / Test URL:</span>
                </div>
                <div class="flex items-center space-x-2">
                    <code id="testExportUrlCode" class="font-mono text-xs text-cyan-300 bg-black/40 px-2.5 py-1 rounded-lg border border-white/10 select-all">
                        {{ url('/admin/database/export') }}
                    </code>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ url('/admin/database/export') }}'); alert('Test URL copied to clipboard!');" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white transition" title="Copy URL">
                        <i class="fa-regular fa-copy text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center space-x-2 shrink-0">
                <a href="{{ route('admin.system.db_export', ['format' => 'json']) }}" target="_blank" class="px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/15 flex items-center space-x-1.5">
                    <i class="fa-solid fa-code text-[11px] text-cyan-300"></i>
                    <span>Test JSON API</span>
                </a>
                <a href="{{ route('admin.system.db_export') }}" class="px-4 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white font-extrabold text-xs shadow-md shadow-cyan-500/30 transition flex items-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-download text-xs"></i>
                    <span>Export & Download (.sql)</span>
                </a>
            </div>
        </div>

        <!-- Backups List -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-600">Generated Backups on Server ({{ count($backups) }})</h4>
                <span class="text-[11px] text-slate-400">Retention: Kept for 14 days (automatically pruned)</span>
            </div>

            @if(empty($backups))
            <div class="p-8 text-center text-slate-400 rounded-2xl bg-slate-50 border border-slate-100">
                <i class="fa-solid fa-database text-3xl text-slate-300 mb-2 block"></i>
                <p class="text-xs font-bold text-slate-600">No backup files found</p>
                <p class="text-[11px]">Click "Export & Download (.sql)" or wait for the 02:00 AM daily scheduler.</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b text-slate-400 uppercase text-[10px]">
                            <th class="py-2.5 px-3">File Name</th>
                            <th class="py-2.5 px-3">File Size</th>
                            <th class="py-2.5 px-3">Generated Date</th>
                            <th class="py-2.5 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($backups as $b)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-3 font-mono font-bold text-indigo-600 flex items-center space-x-2">
                                <i class="fa-regular fa-file-lines text-slate-400"></i>
                                <span>{{ $b['filename'] }}</span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-slate-700">{{ $b['size'] }}</td>
                            <td class="py-3 px-3 text-slate-500">{{ $b['created_at'] }}</td>
                            <td class="py-3 px-3 text-right space-x-1">
                                <a href="{{ route('admin.system.db_download', $b['filename']) }}" class="inline-flex items-center space-x-1 px-3 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-[11px] transition">
                                    <i class="fa-solid fa-download text-[10px]"></i>
                                    <span>Download</span>
                                </a>
                                <form action="{{ route('admin.system.db_delete', $b['filename']) }}" method="POST" class="inline" onsubmit="return confirm('Delete this database backup file permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete Backup">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <!-- SECTION 3: Log File Viewer and Renamer -->
    <div id="logExplorerContainer" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-3">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i class="fa-regular fa-file-lines text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">System Logs Explorer</h3>
                    <p class="text-[11px] text-slate-400">View real-time error traces, warning logs, and manage storage log files</p>
                </div>
            </div>

            <!-- Actions: Refresh & Rename Modal Trigger -->
            <div class="flex items-center space-x-2">
                <button type="button" id="btnOpenRenameModal" onclick="openRenameModal(currentActiveLogFile)" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-pen-to-square text-indigo-600 text-xs"></i>
                    <span>Rename File</span>
                </button>
                <button type="button" id="btnRefreshLog" onclick="fetchLogContent(currentActiveLogFile, true)" class="px-3.5 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                    <span id="refreshLogBtnText">Refresh Log</span>
                </button>
            </div>
        </div>

        <!-- Log Files Selection Tabs -->
        <div id="logTabsContainer" class="flex items-center space-x-2 overflow-x-auto pb-1 text-xs">
            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mr-1 shrink-0">Available Logs:</span>
            @forelse($logFiles as $log)
                <button type="button" 
                        onclick="fetchLogContent('{{ $log['name'] }}')"
                        data-logfile="{{ $log['name'] }}"
                        class="log-tab-btn px-3 py-1.5 rounded-xl font-bold transition shrink-0 flex items-center space-x-2 {{ $selectedFile === $log['name'] ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                    <i class="fa-solid fa-file-lines text-[10px]"></i>
                    <span class="tab-filename">{{ $log['name'] }}</span>
                    <span class="tab-filesize text-[10px] opacity-75 font-mono">({{ $log['size'] }})</span>
                </button>
            @empty
                <span class="text-xs text-slate-400 italic">No log files found in storage/logs</span>
            @endforelse
        </div>

        <!-- Active Log File Meta -->
        <div id="logMetaBar" class="flex items-center justify-between text-xs text-slate-500 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                <span><strong class="text-slate-700">File:</strong> <span id="metaFileName" class="font-mono text-slate-900 font-bold">{{ $selectedFile }}</span></span>
                <span><strong class="text-slate-700">Size:</strong> <span id="metaFileSize">{{ round($logFileSize / 1024, 2) }} KB</span></span>
                <span><strong class="text-slate-700">Updated:</strong> <span id="metaFileUpdated">Now</span></span>
                <span><strong class="text-slate-700">Lines:</strong> <span id="metaFileLines">~{{ $totalLines }} lines (Tail)</span></span>
            </div>
            <button type="button" onclick="copyLogContent()" class="text-indigo-600 hover:text-indigo-800 font-bold flex items-center space-x-1 text-xs shrink-0">
                <i class="fa-regular fa-copy"></i>
                <span>Copy</span>
            </button>
        </div>

        <!-- Terminal / Code Log Viewer -->
        <div class="relative rounded-2xl overflow-hidden border border-slate-800 shadow-inner bg-slate-950">
            <div class="px-4 py-2 bg-slate-900 border-b border-slate-800 flex items-center justify-between text-[11px] text-slate-400">
                <div class="flex items-center space-x-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                    <span id="terminalHeaderFileName" class="ml-2 font-mono text-slate-300">{{ $selectedFile }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="logLoadingIndicator" class="hidden text-amber-400 text-[10px] font-mono flex items-center space-x-1">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <span>Fetching...</span>
                    </span>
                    <span class="text-[10px] text-slate-500 font-mono">Scroll down for newest entries</span>
                </div>
            </div>
            <pre id="logPreContent" class="p-4 text-xs font-mono text-slate-200 overflow-x-auto max-h-[500px] leading-relaxed whitespace-pre-wrap select-all">{{ $logContent }}</pre>
        </div>
    </div>

</div>

<!-- Rename Modal -->
<div id="renameLogModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4 animate-in fade-in zoom-in-95">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                </div>
                <h3 class="text-sm font-extrabold text-slate-900">Rename Log File</h3>
            </div>
            <button type="button" onclick="closeRenameModal()" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <form id="renameLogForm" onsubmit="submitRenameLog(event)" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Current File Name</label>
                <input type="text" name="current_name" id="renameCurrentName" readonly class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 text-xs font-mono font-bold cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">New File Name <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <input type="text" name="new_name" id="renameNewName" required placeholder="e.g. laravel-backup-2026-11-19" class="w-full pl-3.5 pr-14 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-mono text-slate-400 font-bold pointer-events-none">.log</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Letters, numbers, hyphens and underscores allowed.</p>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeRenameModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" id="btnSubmitRename" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold shadow-md shadow-indigo-600/20 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span class="btn-text">Rename File</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentActiveLogFile = "{{ $selectedFile ?? 'laravel.log' }}";

    function showLiveAlert(type, message, title = null) {
        const alertBox = $('#liveStatusAlert');
        const iconWrap = $('#liveStatusIconWrap');
        const icon = $('#liveStatusIcon');
        const titleEl = $('#liveStatusTitle');
        const text = $('#liveStatusMessage');

        alertBox.removeClass('hidden bg-gradient-to-r from-emerald-50 via-teal-50/50 to-emerald-50 border-emerald-200/90 text-emerald-950 bg-gradient-to-r from-rose-50 via-red-50/50 to-rose-50 border-rose-200/90 text-rose-950 shadow-emerald-500/10 shadow-rose-500/10');
        iconWrap.removeClass('bg-emerald-500 text-white shadow-emerald-500/30 bg-rose-500 text-white shadow-rose-500/30');

        if (type === 'success') {
            alertBox.addClass('bg-gradient-to-r from-emerald-50 via-teal-50/50 to-emerald-50 border-emerald-200/90 text-emerald-950 shadow-emerald-500/10');
            iconWrap.addClass('bg-emerald-500 text-white shadow-emerald-500/30');
            icon.attr('class', 'fa-solid fa-check text-sm');
            titleEl.text(title || 'Operation Completed Successfully').attr('class', 'text-xs font-black tracking-wide text-emerald-800 uppercase');
            text.attr('class', 'text-xs font-medium text-emerald-700 leading-relaxed').text(message);
        } else {
            alertBox.addClass('bg-gradient-to-r from-rose-50 via-red-50/50 to-rose-50 border-rose-200/90 text-rose-950 shadow-rose-500/10');
            iconWrap.addClass('bg-rose-500 text-white shadow-rose-500/30');
            icon.attr('class', 'fa-solid fa-triangle-exclamation text-sm');
            titleEl.text(title || 'Action Failed').attr('class', 'text-xs font-black tracking-wide text-rose-800 uppercase');
            text.attr('class', 'text-xs font-medium text-rose-700 leading-relaxed').text(message);
        }

        // Auto hide live banner after 6 seconds
        clearTimeout(window.liveAlertTimeout);
        window.liveAlertTimeout = setTimeout(() => {
            alertBox.addClass('hidden');
        }, 6000);
    }

    function showToastNotification(type, title, message) {
        if (typeof Swal === 'undefined') return;

        const isSuccess = type === 'success';
        const isInfo = type === 'info';

        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: {
                popup: '!rounded-2xl !p-3.5 !shadow-2xl !border !border-slate-100 !bg-white/95 !backdrop-blur-md',
                timerProgressBar: isSuccess ? '!bg-emerald-500' : (isInfo ? '!bg-sky-500' : '!bg-rose-500')
            },
            html: `
                <div class="flex items-center space-x-3 text-left w-full">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 ${isSuccess ? 'bg-emerald-100 text-emerald-600' : (isInfo ? 'bg-sky-100 text-sky-600' : 'bg-rose-100 text-rose-600')}">
                        <i class="fa-solid ${isSuccess ? 'fa-circle-check text-base' : (isInfo ? 'fa-circle-info text-base' : 'fa-circle-exclamation text-base')}"></i>
                    </div>
                    <div class="flex-1 min-w-0 pr-1">
                        <p class="text-xs font-extrabold text-slate-900 leading-tight">${title}</p>
                        <p class="text-[11px] font-medium text-slate-500 mt-0.5 leading-snug">${message}</p>
                    </div>
                </div>
            `
        });
    }

    // 1. CLEAR CACHE WITHOUT PAGE RELOAD
    function clearSpecificCache(type, triggerBtn) {
        const btn = $(triggerBtn);
        const originalHtml = btn.html();
        const card = $(`#card-cache-${type}`);
        const indicator = card.find('.status-indicator');

        // Show inline loading state on trigger button
        btn.attr('disabled', 'disabled').addClass('opacity-75 cursor-not-allowed');
        btn.find('.btn-text').text('Clearing...');
        btn.find('i').attr('class', 'fa-solid fa-spinner fa-spin text-[10px]');
        indicator.text('Clearing...').removeClass('text-slate-400 text-emerald-600 text-rose-500').addClass('text-amber-500');

        $.ajax({
            url: "{{ route('admin.system.clear_cache') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                type: type
            },
            success: function(res) {
                btn.removeAttr('disabled').removeClass('opacity-75 cursor-not-allowed').html(originalHtml);
                indicator.text('Cleared').removeClass('text-amber-500 text-slate-400').addClass('text-emerald-600 font-bold');

                // Update badge timestamp
                const now = new Date();
                const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                $('#cacheLastClearedText').text(`Last cleared at ${timeStr}`);
                $('#cacheLastClearedBadge').removeClass('hidden');

                showLiveAlert('success', res.message, 'Cache Optimization Complete');
                showToastNotification('success', 'Cache Cleared Successfully', res.message);

                // Auto reset indicator after 4 seconds
                setTimeout(() => {
                    indicator.text('Ready').removeClass('text-emerald-600 font-bold').addClass('text-slate-400');
                }, 4000);
            },
            error: function(xhr) {
                btn.removeAttr('disabled').removeClass('opacity-75 cursor-not-allowed').html(originalHtml);
                indicator.text('Failed').removeClass('text-amber-500 text-slate-400').addClass('text-rose-500 font-bold');
                
                const errMsg = xhr.responseJSON?.message || 'An error occurred while clearing cache.';
                showLiveAlert('error', errMsg, 'Cache Clearing Failed');
                showToastNotification('error', 'Execution Error', errMsg);
            }
        });
    }

    // 2. FETCH LOG CONTENT ASYNC (Component Only)
    function fetchLogContent(filename, isManualRefresh = false) {
        currentActiveLogFile = filename;
        $('#logLoadingIndicator').removeClass('hidden');
        if (isManualRefresh) {
            $('#refreshLogBtnText').text('Refreshing...');
            $('#btnRefreshLog i').addClass('fa-spin');
        }

        // Highlight active tab
        $('.log-tab-btn').removeClass('bg-indigo-600 text-white shadow-sm').addClass('bg-slate-100 text-slate-600 hover:bg-slate-200');
        $(`.log-tab-btn[data-logfile="${filename}"]`).addClass('bg-indigo-600 text-white shadow-sm').removeClass('bg-slate-100 text-slate-600 hover:bg-slate-200');

        $.ajax({
            url: "{{ route('admin.system.read_log') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                file: filename
            },
            success: function(res) {
                $('#logLoadingIndicator').addClass('hidden');
                $('#refreshLogBtnText').text('Refresh Log');
                $('#btnRefreshLog i').removeClass('fa-spin');

                // Update only log viewer components
                $('#metaFileName').text(res.filename);
                $('#terminalHeaderFileName').text(res.filename);
                $('#metaFileSize').text(res.size);
                $('#metaFileUpdated').text(res.updated_at);
                
                const lines = (res.content.match(/\n/g) || []).length;
                $('#metaFileLines').text(`~${lines} lines (Tail)`);

                const pre = document.getElementById('logPreContent');
                pre.innerText = res.content || '(File is empty)';
                pre.scrollTop = pre.scrollHeight;

                if (isManualRefresh) {
                    showToastNotification('info', 'Log Stream Refreshed', `Successfully updated tail lines for ${res.filename}`);
                }
            },
            error: function(xhr) {
                $('#logLoadingIndicator').addClass('hidden');
                $('#refreshLogBtnText').text('Refresh Log');
                $('#btnRefreshLog i').removeClass('fa-spin');
                showLiveAlert('error', xhr.responseJSON?.error || 'Unable to fetch log content.');
            }
        });
    }

    // 3. RENAME LOG FILE ASYNC (Component Only)
    function openRenameModal(fileName) {
        $('#renameCurrentName').val(fileName);
        const nameWithoutExt = fileName.replace(/\.log$/, '');
        $('#renameNewName').val(nameWithoutExt + '-backup');
        $('#renameLogModal').removeClass('hidden');
    }

    function closeRenameModal() {
        $('#renameLogModal').addClass('hidden');
    }

    function submitRenameLog(e) {
        e.preventDefault();
        const submitBtn = $('#btnSubmitRename');
        const originalHtml = submitBtn.html();
        submitBtn.attr('disabled', 'disabled').html('<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Renaming...</span>');

        const currentName = $('#renameCurrentName').val();
        const newName = $('#renameNewName').val();

        $.ajax({
            url: "{{ route('admin.system.rename_log') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                current_name: currentName,
                new_name: newName
            },
            success: function(res) {
                submitBtn.removeAttr('disabled').html(originalHtml);
                closeRenameModal();
                showLiveAlert('success', res.message, 'Log Renamed');
                showToastNotification('success', 'File Renamed Successfully', res.message);

                // Update tabs list component dynamically without reloading page
                if (res.log_files) {
                    renderLogTabs(res.log_files, res.new_name);
                }

                // Automatically load the newly renamed file
                fetchLogContent(res.new_name);
            },
            error: function(xhr) {
                submitBtn.removeAttr('disabled').html(originalHtml);
                const errMsg = xhr.responseJSON?.message || 'Failed to rename log file.';
                Swal.fire({
                    icon: 'error',
                    title: 'Rename Failed',
                    text: errMsg,
                    confirmButtonColor: '#4f46e5'
                });
            }
        });
    }

    function renderLogTabs(files, activeName) {
        const container = $('#logTabsContainer');
        container.empty();
        container.append('<span class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mr-1 shrink-0">Available Logs:</span>');

        files.forEach(file => {
            const isActive = file.name === activeName;
            const activeClasses = isActive ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-600';
            const btn = `
                <button type="button" 
                        onclick="fetchLogContent('${file.name}')"
                        data-logfile="${file.name}"
                        class="log-tab-btn px-3 py-1.5 rounded-xl font-bold transition shrink-0 flex items-center space-x-2 ${activeClasses}">
                    <i class="fa-solid fa-file-lines text-[10px]"></i>
                    <span class="tab-filename">${file.name}</span>
                    <span class="tab-filesize text-[10px] opacity-75 font-mono">(${file.size})</span>
                </button>
            `;
            container.append(btn);
        });
    }

    function copyLogContent() {
        const text = document.getElementById('logPreContent').innerText;
        navigator.clipboard.writeText(text).then(() => {
            showToastNotification('info', 'Copied to Clipboard', 'Log lines copied to your system clipboard.');
        });
    }

    $(document).ready(function() {
        const pre = document.getElementById('logPreContent');
        if (pre) {
            pre.scrollTop = pre.scrollHeight;
        }
    });
</script>
@endsection
